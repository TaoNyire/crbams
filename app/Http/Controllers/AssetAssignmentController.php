<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetAssignment;
use App\Models\Department;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AssetAssignmentController extends Controller
{
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
            'You are not authorized to view the asset allocation register.'
        );

        $managementAreas = [
            'hardware' => 'Hardware',
            'administration' => 'Administration',
        ];

        $query = Asset::query()
            ->with([
                'category',
                'type',
                'department',
                'employee',
                'activeAssignment' => function ($assignmentQuery) {
                    $assignmentQuery
                        ->with([
                            'employee',
                            'department',
                            'assignedBy',
                        ])
                        ->latest('assigned_at');
                },
            ]);

        /*
        |--------------------------------------------------------------------------
        | Management Area Access
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'system_admin') {
            $managementArea = $request->input('management_area');

            if (
                $managementArea &&
                array_key_exists($managementArea, $managementAreas)
            ) {
                $query->whereHas(
                    'category',
                    function ($categoryQuery) use ($managementArea) {
                        $categoryQuery->where(
                            'responsible_officer',
                            $managementArea
                        );
                    }
                );
            }
        } else {
            $query->whereHas(
                'category',
                function ($categoryQuery) use ($user) {
                    $categoryQuery->where(
                        'responsible_officer',
                        $user->management_area
                    );
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            $query->where(function ($q) use ($search) {
                $q->where(
                    'asset_code',
                    'like',
                    "%{$search}%"
                )
                    ->orWhere(
                        'asset_name',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhere(
                        'serial_number',
                        'like',
                        "%{$search}%"
                    )
                    ->orWhereHas(
                        'employee',
                        function ($employeeQuery) use ($search) {
                            $employeeQuery
                                ->where(
                                    'employee_number',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'first_name',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'last_name',
                                    'like',
                                    "%{$search}%"
                                );
                        }
                    )
                    ->orWhereHas(
                        'department',
                        function ($departmentQuery) use ($search) {
                            $departmentQuery
                                ->where(
                                    'name',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'code',
                                    'like',
                                    "%{$search}%"
                                );
                        }
                    );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {
            $allowedStatuses = [
                'assigned',
                'available',
                'under_repair',
                'lost',
                'disposed',
                'retired',
            ];

            if (
                in_array(
                    $request->input('status'),
                    $allowedStatuses,
                    true
                )
            ) {
                $query->where(
                    'status',
                    $request->input('status')
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Department Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('department_id')) {
            $query->where(
                'department_id',
                $request->input('department_id')
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $statisticsQuery = clone $query;

        $totalAssets = (clone $statisticsQuery)->count();

        $assignedAssets = (clone $statisticsQuery)
            ->where('status', 'assigned')
            ->whereHas('activeAssignment')
            ->count();

        $availableAssets = (clone $statisticsQuery)
            ->where('status', 'available')
            ->whereDoesntHave('activeAssignment')
            ->count();

        $underRepairAssets = (clone $statisticsQuery)
            ->where('status', 'under_repair')
            ->whereDoesntHave('activeAssignment')
            ->count();

        $retiredAssets = (clone $statisticsQuery)
            ->where('status', 'retired')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Asset Register
        |--------------------------------------------------------------------------
        */

        $assets = $query
            ->orderBy('asset_code')
            ->paginate(15)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Department Filter Options
        |--------------------------------------------------------------------------
        */

        $departmentsQuery = Department::query()
            ->where('is_active', true)
            ->orderBy('name');

        if ($user->role === 'system_admin') {
            $managementArea = $request->input('management_area');

            if (
                $managementArea &&
                array_key_exists($managementArea, $managementAreas)
            ) {
                $departmentsQuery->whereHas(
                    'assets.category',
                    function ($categoryQuery) use ($managementArea) {
                        $categoryQuery->where(
                            'responsible_officer',
                            $managementArea
                        );
                    }
                );
            }
        } else {
            $departmentsQuery->whereHas(
                'assets.category',
                function ($categoryQuery) use ($user) {
                    $categoryQuery->where(
                        'responsible_officer',
                        $user->management_area
                    );
                }
            );
        }

        $departments = $departmentsQuery->get();

        return view(
            'assignments.index',
            compact(
                'assets',
                'departments',
                'managementAreas',
                'totalAssets',
                'assignedAssets',
                'availableAssets',
                'underRepairAssets',
                'retiredAssets'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Assignment Form
    |--------------------------------------------------------------------------
    */

    public function create(Asset $asset): View
    {
        $this->ensureCanAssignAssets();
        $this->ensureAssetIsAccessible($asset);

        abort_unless(
            $asset->status === 'available',
            422,
            'Only available assets can be assigned.'
        );

        abort_unless(
            ! $asset->activeAssignment()->exists(),
            422,
            'This asset already has an active assignment.'
        );

        $departments = Department::where('is_active', true)
            ->orderBy('name')
            ->get();

        $employees = Employee::where('is_active', true)
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        return view(
            'assets.assign',
            compact(
                'asset',
                'departments',
                'employees'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Store Assignment
    |--------------------------------------------------------------------------
    */

    public function store(Request $request, Asset $asset)
    {
        $this->ensureCanAssignAssets();
        $this->ensureAssetIsAccessible($asset);

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
            $lockedAsset = Asset::query()
                ->lockForUpdate()
                ->findOrFail($asset->id);

            abort_unless(
                $lockedAsset->status === 'available',
                422,
                'Only available assets can be assigned.'
            );

            abort_unless(
                ! $lockedAsset->activeAssignment()->exists(),
                422,
                'This asset already has an active assignment.'
            );

            AssetAssignment::create([
                'asset_id' => $lockedAsset->id,
                'employee_id' => $employee->id,
                'department_id' => $department->id,
                'assigned_by' => auth()->id(),
                'assigned_at' => now(),
                'returned_at' => null,
                'returned_condition' => null,
                'return_notes' => null,
                'notes' => $validated['notes'] ?? null,
            ]);

            $lockedAsset->update([
                'employee_id' => $employee->id,
                'department_id' => $department->id,
                'location' => $validated['location']
                    ?? $asset->location,
                'status' => 'assigned',
            ]);
        });

        return redirect()
            ->route('assets.show', $asset)
            ->with(
                'success',
                'Asset successfully assigned to '.
                $employee->first_name.' '.
                $employee->last_name.'.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Return Form
    |--------------------------------------------------------------------------
    */

    public function returnForm(Asset $asset): View
    {
        $this->ensureCanAssignAssets();
        $this->ensureAssetIsAccessible($asset);

        $assignment = $asset->activeAssignment()
            ->with([
                'employee',
                'department',
                'assignedBy',
            ])
            ->first();

        abort_unless(
            $assignment,
            422,
            'No active assignment was found for this asset.'
        );

        abort_unless(
            $asset->status === 'assigned',
            422,
            'This asset has an active assignment but its status is not Assigned.'
        );

        return view(
            'assets.return',
            compact(
                'asset',
                'assignment'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Return Asset
    |--------------------------------------------------------------------------
    */

    public function returnAsset(Request $request, Asset $asset)
    {
        $this->ensureCanAssignAssets();
        $this->ensureAssetIsAccessible($asset);

        $validated = $request->validate([
            'returned_at' => [
                'required',
                'date',
            ],

            'returned_condition' => [
                'required',
                'in:good,damaged,needs_repair',
            ],

            'return_notes' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        DB::transaction(function () use (
            $asset,
            $validated
        ) {
            $lockedAsset = Asset::query()
                ->lockForUpdate()
                ->findOrFail($asset->id);

            $currentAssignment = $lockedAsset->activeAssignment()
                ->lockForUpdate()
                ->first();

            abort_unless(
                $currentAssignment,
                422,
                'No active assignment was found for this asset.'
            );

            abort_unless(
                $lockedAsset->status === 'assigned',
                422,
                'This asset has an active assignment but its status is not Assigned.'
            );

            $returnedAt = Carbon::parse($validated['returned_at']);

            abort_if(
                $returnedAt->isFuture(),
                422,
                'The returned date cannot be in the future.'
            );

            abort_if(
                $returnedAt->lt($currentAssignment->assigned_at),
                422,
                'The returned date cannot be before the assignment date.'
            );

            /*
            |--------------------------------------------------------------------------
            | Close Assignment
            |--------------------------------------------------------------------------
            */

            $currentAssignment->update([
                'returned_at' => $returnedAt,
                'returned_by' => auth()->id(),
                'returned_condition' => $validated['returned_condition'],
                'return_notes' => $validated['return_notes'] ?? null,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Determine New Asset Status
            |--------------------------------------------------------------------------
            */

            $newStatus = $validated['returned_condition'] === 'needs_repair'
                ? 'under_repair'
                : 'available';

            /*
            |--------------------------------------------------------------------------
            | Update Asset
            |--------------------------------------------------------------------------
            */

            $lockedAsset->update([
                'employee_id' => null,
                'department_id' => null,
                'status' => $newStatus,
                'condition' => $validated['returned_condition'] === 'good'
                    ? 'good'
                    : 'damaged',
            ]);
        });

        return redirect()
            ->route('assets.show', $asset)
            ->with(
                'success',
                'Asset successfully returned and its allocation history has been updated.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Authorization
    |--------------------------------------------------------------------------
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
            'You are not authorized to perform asset assignment operations.'
        );
    }

    protected function ensureAssetIsAccessible(Asset $asset): void
    {
        $user = auth()->user();

        if ($user->role === 'system_admin') {
            return;
        }

        $accessible = Asset::query()
            ->whereKey($asset->id)
            ->whereHas(
                'category',
                function ($query) use ($user) {
                    $query->where(
                        'responsible_officer',
                        $user->management_area
                    );
                }
            )
            ->exists();

        abort_unless(
            $accessible,
            403,
            'You are not authorized to access this asset.'
        );
    }
}
