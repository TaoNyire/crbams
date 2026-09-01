<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of departments.
     */
    public function index()
    {
        $departments = Department::withCount([
            'employees',
            'assets',
        ])
            ->latest()
            ->paginate(15);

        return view('departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new department.
     */
    public function create()
    {
        return view('departments.create');
    }

    /**
     * Store a newly created department.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
            'code' => 'nullable|string|max:50|unique:departments,code',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);
        Department::create($validated);

        return redirect()
            ->route('departments.index')
            ->with('success', 'Department created successfully.');
    }

    /**
     * Display the specified department.
     */
    public function show(Department $department)
    {
        $department->load([
            'employees',
            'assets.category',
            'assets.type',
        ]);

        return view('departments.show', compact('department'));
    }

    /**
     * Show the form for editing the specified department.
     */
    public function edit(Department $department)
    {
        return view('departments.edit', compact('department'));
    }

    /**
     * Update the specified department.
     */
    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,'.$department->id,
            'code' => 'nullable|string|max:50|unique:departments,code,'.$department->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $department->update($validated);

        return redirect()
            ->route('departments.index')
            ->with('success', 'Department updated successfully.');
    }

    /**
     * Remove the specified department.
     */
    public function destroy(Department $department)
    {
        if ($department->employees()->exists()) {
            return redirect()
                ->route('departments.index')
                ->with(
                    'error',
                    'This department cannot be deleted because employees are assigned to it.'
                );
        }

        if ($department->assets()->exists()) {
            return redirect()
                ->route('departments.index')
                ->with(
                    'error',
                    'This department cannot be deleted because assets are assigned to it.'
                );
        }

        $department->delete();

        return redirect()
            ->route('departments.index')
            ->with('success', 'Department deleted successfully.');
    }
}
