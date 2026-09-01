<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of employees.
     */
    public function index(Request $request)
    {
        $query = Employee::with('department')
            ->withCount('assets');

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('employee_number', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {
            $query->where(
                'is_active',
                $request->status === 'active'
            );
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

        $employees = $query
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $departments = Department::orderBy('name')->get();

        return view(
            'employees.index',
            compact('employees', 'departments')
        );
    }

    /**
     * Show the form for creating a new employee.
     */
    public function create()
    {
        $departments = Department::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view(
            'employees.create',
            compact('departments')
        );
    }

    /**
     * Store a newly created employee.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_number' => [
                'required',
                'string',
                'max:255',
                'unique:employees,employee_number',
            ],

            'first_name' => [
                'required',
                'string',
                'max:255',
            ],

            'last_name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
                'unique:employees,email',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:50',
            ],

            'department_id' => [
                'required',
                'exists:departments,id',
            ],

            'section' => [
                'nullable',
                'string',
                'max:255',
            ],

            'designation' => [
                'nullable',
                'string',
                'max:255',
            ],

            'position' => [
                'nullable',
                'string',
                'max:255',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        $validated['is_active'] = $request->boolean(
            'is_active',
            true
        );

        Employee::create($validated);

        return redirect()
            ->route('employees.index')
            ->with(
                'success',
                'Employee registered successfully.'
            );
    }

    /**
     * Display the specified employee.
     */
    public function show(Employee $employee)
    {
        $employee->load([
            'department',
            'assets.category',
            'assets.type',
        ]);

        return view(
            'employees.show',
            compact('employee')
        );
    }

    /**
     * Show the form for editing the specified employee.
     */
    public function edit(Employee $employee)
    {
        $departments = Department::where('is_active', true)
            ->orderBy('name')
            ->get();

        /*
        |----------------------------------------------------------------------
        | Include the employee's current department
        |----------------------------------------------------------------------
        |
        | This is important when a department has subsequently
        | been deactivated.
        |
        */

        if (
            $employee->department &&
            !$departments->contains('id', $employee->department_id)
        ) {
            $departments->push($employee->department);
            $departments = $departments->sortBy('name');
        }

        return view(
            'employees.edit',
            compact('employee', 'departments')
        );
    }

    /**
     * Update the specified employee.
     */
    public function update(
        Request $request,
        Employee $employee
    ) {
        $validated = $request->validate([
            'employee_number' => [
                'required',
                'string',
                'max:255',
                'unique:employees,employee_number,' . $employee->id,
            ],

            'first_name' => [
                'required',
                'string',
                'max:255',
            ],

            'last_name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
                'unique:employees,email,' . $employee->id,
            ],

            'phone' => [
                'nullable',
                'string',
                'max:50',
            ],

            'department_id' => [
                'required',
                'exists:departments,id',
            ],

            'section' => [
                'nullable',
                'string',
                'max:255',
            ],

            'designation' => [
                'nullable',
                'string',
                'max:255',
            ],

            'position' => [
                'nullable',
                'string',
                'max:255',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        $validated['is_active'] = $request->boolean(
            'is_active'
        );

        $employee->update($validated);

        return redirect()
            ->route('employees.show', $employee)
            ->with(
                'success',
                'Employee information updated successfully.'
            );
    }

    /**
     * Deactivate the specified employee.
     *
     * Employees are retained for historical and audit purposes.
     */
    public function destroy(Employee $employee)
    {
        /*
        |--------------------------------------------------------------------------
        | Employees with assigned assets
        |--------------------------------------------------------------------------
        |
        | An employee with assets should not simply be deactivated
        | until those assets have been reassigned or otherwise handled.
        |
        */

        if ($employee->assets()->exists()) {
            return redirect()
                ->route('employees.index')
                ->with(
                    'error',
                    'This employee cannot be deactivated while assets are still associated with them. Please reassign or otherwise process the assets first.'
                );
        }

        $employee->update([
            'is_active' => false,
        ]);

        return redirect()
            ->route('employees.index')
            ->with(
                'success',
                'Employee has been deactivated. The employee record has been retained for historical purposes.'
            );
    }
}