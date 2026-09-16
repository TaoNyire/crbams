<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetAssignment;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AssetAssignmentController extends Controller
{
    /**
     * Display the assignment register.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();

        abort_unless(
            in_array($user->role, [
                'hardware_officer',
                'administration_officer',
                'system_admin',
            ]),
            403,
            'You are not authorized to view assignment records.'
        );

        $query = AssetAssignment::query()
            ->with([
                'asset.category',
                'asset.type',
                'employee',
                'department',
                'assignedBy',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Management Area Restriction
        |--------------------------------------------------------------------------
        */

        if ($user->role !== 'system_admin') {
            $query->whereHas('asset.category', function ($categoryQuery) use ($user) {
                $categoryQuery->where(
                    'responsible_officer',
                    $user->management_area
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            $query->where(function ($q) use ($search) {

                $q->whereHas('asset', function ($assetQuery) use ($search) {
                    $assetQuery
                        ->where('asset_code', 'like', "%{$search}%")
                        ->orWhere('asset_name', 'like', "%{$search}%")
                        ->orWhere('serial_number', 'like', "%{$search}%");
                });

                $q->orWhereHas('employee', function ($employeeQuery) use ($search) {
                    $employeeQuery
                        ->where('employee_number', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                });

                $q->orWhereHas('department', function ($departmentQuery) use ($search) {
                    $departmentQuery
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                });
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Assignment Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->status === 'active') {
            $query->whereNull('returned_at');
        }

        if ($request->status === 'returned') {
            $query->whereNotNull('returned_at');
        }

        /*
        |--------------------------------------------------------------------------
        | Department Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('department_id')) {
            $query->where(
                'department_id',
                $request->department_id
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $assignments = $query
            ->latest('assigned_at')
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Departments
        |--------------------------------------------------------------------------
        */

        $departmentsQuery = Department::query()
            ->where('is_active', true)
            ->orderBy('name');

        if ($user->role !== 'system_admin') {
            $departmentsQuery->whereHas('assets.category', function ($categoryQuery) use ($user) {
                $categoryQuery->where(
                    'responsible_officer',
                    $user->management_area
                );
            });
        }

        $departments = $departmentsQuery->get();

        /*
        |--------------------------------------------------------------------------
        | Register Statistics
        |--------------------------------------------------------------------------
        */

        $statisticsQuery = AssetAssignment::query();

        if ($user->role !== 'system_admin') {
            $statisticsQuery->whereHas('asset.category', function ($categoryQuery) use ($user) {
                $categoryQuery->where(
                    'responsible_officer',
                    $user->management_area
                );
            });
        }

        $totalAssignments = (clone $statisticsQuery)->count();

        $activeAssignments = (clone $statisticsQuery)
            ->whereNull('returned_at')
            ->count();

        $returnedAssignments = (clone $statisticsQuery)
            ->whereNotNull('returned_at')
            ->count();

        return view('assignments.index', compact(
            'assignments',
            'departments',
            'totalAssignments',
            'activeAssignments',
            'returnedAssignments'
        ));
    }

    /**
     * Show the assignment form.
     */
    public function create(Asset $asset): View
    {
        $this->ensureCanAssignAssets();
        $this->ensureAssetIsAccessible($asset);

        abort_if(
            $asset->status === 'retired',
            422,
            'A retired asset cannot be assigned.'
        );

        abort_if(
            $asset->status === 'assigned',
            422,
            'This asset is already assigned.'
        );

        $departments = Department::where('is_active', true)
            ->orderBy('name')
            ->get();

        $employees = Employee::where('is_active', true)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        return view('assets.assign', compact(
            'asset',
            'departments',
            'employees'
        ));
    }

    /**
     * Assign an asset to an employee.
     */
    public function store(Request $request, Asset $asset)
    {
        $this->ensureCanAssignAssets();
        $this->ensureAssetIsAccessible($asset);

        abort_if(
            $asset->status === 'retired',
            422,
            'A retired asset cannot be assigned.'
        );

        if ($asset->status === 'assigned') {
            return redirect()
                ->route('assets.show', $asset)
                ->with(
                    'error',
                    'This asset is already assigned.'
                );
        }

        $validated = $request->validate([
            'employee_id' => [
                'required',
                'exists:employees,id',
            ],

            'department_id' => [
                'required',
                'exists:departments,id',
            ],

            'location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        $employee = Employee::findOrFail(
            $validated['employee_id']
        );

        abort_unless(
            $employee->is_active,
            422,
            'The selected employee is not active.'
        );

        $department = Department::findOrFail(
            $validated['department_id']
        );

        abort_unless(
            $department->is_active,
            422,
            'The selected department is not active.'
        );

        abort_unless(
            (int) $employee->department_id ===
                (int) $department->id,
            422,
            'The selected department does not match the employee\'s department.'
        );

        DB::transaction(function () use (
            $asset,
            $employee,
            $department,
            $validated
        ) {
            AssetAssignment::create([
                'asset_id' => $asset->id,
                'employee_id' => $employee->id,
                'department_id' => $department->id,
                'assigned_by' => auth()->id(),
                'assigned_at' => now(),
                'returned_at' => null,
                'notes' => $validated['notes'] ?? null,
            ]);

            $asset->update([
                'employee_id' => $employee->id,
                'department_id' => $department->id,
                'location' => $validated['location']
                    ?? $asset->location,
                'status' => 'assigned',
                'notes' => $validated['notes']
                    ?? $asset->notes,
            ]);
        });

        return redirect()
            ->route('assets.show', $asset)
            ->with(
                'success',
                'Asset successfully assigned to ' .
                $employee->first_name . ' ' .
                $employee->last_name . '.'
            );
    }

    /**
     * Return an assigned asset.
     */
    public function returnAsset(Asset $asset)
    {
        $this->ensureCanAssignAssets();
        $this->ensureAssetIsAccessible($asset);

        abort_unless(
            $asset->status === 'assigned',
            422,
            'This asset is not currently assigned.'
        );

        DB::transaction(function () use ($asset) {

            $currentAssignment = AssetAssignment::query()
                ->where('asset_id', $asset->id)
                ->whereNull('returned_at')
                ->latest('assigned_at')
                ->first();

            if ($currentAssignment) {
                $currentAssignment->update([
                    'returned_at' => now(),
                ]);
            }

            $asset->update([
                'employee_id' => null,
                'department_id' => null,
                'status' => 'available',
            ]);
        });

        return redirect()
            ->route('assets.show', $asset)
            ->with(
                'success',
                'Asset successfully returned and is now available.'
            );
    }

    /**
     * Ensure current user can assign assets.
     */
    protected function ensureCanAssignAssets(): void
    {
        $user = auth()->user();

        abort_unless(
            in_array($user->role, [
                'hardware_officer',
                'administration_officer',
            ]),
            403,
            'You are not authorized to assign assets.'
        );
    }

    /**
     * Ensure the asset belongs to the officer's management area.
     */
    protected function ensureAssetIsAccessible(Asset $asset): void
    {
        $user = auth()->user();

        if ($user->role === 'system_admin') {
            return;
        }

        $accessible = Asset::query()
            ->whereKey($asset->id)
            ->whereHas('category', function ($query) use ($user) {
                $query->where(
                    'responsible_officer',
                    $user->management_area
                );
            })
            ->exists();

        abort_unless(
            $accessible,
            403,
            'You are not authorized to access this asset.'
        );
    }
} 