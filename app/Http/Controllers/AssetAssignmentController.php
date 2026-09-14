<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssetAssignmentController extends Controller
{
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
     * Assign the asset.
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

        /*
         * If the employee belongs to a department,
         * make sure the assignment uses the employee's department.
         */
        abort_unless(
            (int) $employee->department_id ===
                (int) $department->id,
            422,
            'The selected department does not match the employee\'s department.'
        );

        /*
         * Prevent accidental reassignment.
         */
        if ($asset->status === 'assigned') {
            return redirect()
                ->route('assets.show', $asset)
                ->with(
                    'error',
                    'This asset is already assigned. Return it before assigning it again.'
                );
        }

        $asset->update([
            'employee_id' => $employee->id,
            'department_id' => $department->id,
            'location' => $validated['location']
                ?? $asset->location,
            'status' => 'assigned',
            'notes' => $validated['notes']
                ?? $asset->notes,
        ]);

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

        $asset->update([
            'employee_id' => null,
            'department_id' => null,
            'status' => 'available',
        ]);

        return redirect()
            ->route('assets.show', $asset)
            ->with(
                'success',
                'Asset successfully returned and is now available.'
            );
    }

    /**
     * Officers who are responsible for assets can assign them.
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
     * Make sure the officer can access this asset category.
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