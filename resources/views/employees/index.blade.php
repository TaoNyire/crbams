<x-app-layout>
    <x-slot name="title">
        Employees
    </x-slot>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div class="crb-page-title mb-0">

        <h1>Employees</h1>

        <p>
            Manage CRB employees and their assigned assets.
        </p>

    </div>

    <a
        href="{{ route('employees.create') }}"
        class="btn btn-crb"
    >
        <i class="bi bi-person-plus me-1"></i>
        Add Employee
    </a>

</div>


{{-- =========================================================
    SUCCESS MESSAGE
========================================================= --}}

@if (session('success'))

    <div
        class="alert alert-success alert-dismissible fade show mb-4"
        role="alert"
    >

        <i class="bi bi-check-circle me-2"></i>

        {{ session('success') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
        ></button>

    </div>

@endif


{{-- =========================================================
    ERROR MESSAGE
========================================================= --}}

@if (session('error'))

    <div
        class="alert alert-danger alert-dismissible fade show mb-4"
        role="alert"
    >

        <i class="bi bi-exclamation-circle me-2"></i>

        {{ session('error') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
        ></button>

    </div>

@endif


{{-- =========================================================
    EMPLOYEE REGISTER
========================================================= --}}

<div class="crb-card">

    {{-- HEADER --}}

    <div class="crb-card-header">

        <div>

            <h5>

                <i class="bi bi-people me-2"></i>

                Employee Register

            </h5>

            <small>

                {{ $employees->total() }}

                {{ $employees->total() == 1 ? 'employee' : 'employees' }}

                registered

            </small>

        </div>

    </div>


    {{-- =====================================================
        TABLE
    ====================================================== --}}

    @if ($employees->count())

        <div class="table-responsive">

            <table class="table crb-table align-middle mb-0">

                <thead>

                    <tr>

                        <th>
                            Employee No.
                        </th>

                        <th>
                            Employee
                        </th>

                        <th>
                            Department
                        </th>

                        <th>
                            Position
                        </th>

                        <th>
                            Contact
                        </th>

                        <th>
                            Assets
                        </th>

                        <th class="text-end">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @foreach ($employees as $employee)

                        <tr>

                            {{-- =================================================
                                EMPLOYEE NUMBER
                            ================================================== --}}

                            <td>

                                <a
                                    href="{{ route('employees.show', $employee) }}"
                                    class="crb-asset-code"
                                >

                                    {{ $employee->employee_number }}

                                </a>

                            </td>


                            {{-- =================================================
                                EMPLOYEE
                            ================================================== --}}

                            <td>

                                <div class="crb-employee">

                                    <div class="crb-avatar">

                                        {{ strtoupper(
                                            substr($employee->first_name, 0, 1) .
                                            substr($employee->last_name, 0, 1)
                                        ) }}

                                    </div>

                                    <div>

                                        <div class="fw-semibold">

                                            {{ $employee->first_name }}
                                            {{ $employee->last_name }}

                                        </div>

                                        @if ($employee->email)

                                            <div class="crb-muted">

                                                {{ $employee->email }}

                                            </div>

                                        @endif

                                    </div>

                                </div>

                            </td>


                            {{-- =================================================
                                DEPARTMENT
                            ================================================== --}}

                            <td>

                                @if ($employee->department)

                                    <span>

                                        {{ $employee->department->name }}

                                    </span>

                                    @if ($employee->department->code)

                                        <div class="crb-muted">

                                            {{ $employee->department->code }}

                                        </div>

                                    @endif

                                @else

                                    <span class="crb-muted">

                                        Unassigned

                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                POSITION
                            ================================================== --}}

                            <td>

                                @if ($employee->position)

                                    {{ $employee->position }}

                                @else

                                    <span class="crb-muted">

                                        Not specified

                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                CONTACT
                            ================================================== --}}

                            <td>

                                @if ($employee->phone)

                                    <span>

                                        <i class="bi bi-telephone me-1 text-muted"></i>

                                        {{ $employee->phone }}

                                    </span>

                                @elseif ($employee->email)

                                    <span>

                                        <i class="bi bi-envelope me-1 text-muted"></i>

                                        {{ $employee->email }}

                                    </span>

                                @else

                                    <span class="crb-muted">

                                        Not provided

                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                ASSETS
                            ================================================== --}}

                            <td>

                                @if ($employee->assets_count > 0)

                                    <span class="crb-status assigned">

                                        <span class="crb-status-dot"></span>

                                        {{ $employee->assets_count }}

                                        {{ $employee->assets_count == 1 ? 'Asset' : 'Assets' }}

                                    </span>

                                @else

                                    <span class="crb-muted">

                                        No assets

                                    </span>

                                @endif

                            </td>


                            {{-- =================================================
                                ACTIONS
                            ================================================== --}}

                            <td class="text-end">

                                <div class="crb-actions">


                                    {{-- VIEW --}}

                                    <a
                                        href="{{ route('employees.show', $employee) }}"
                                        class="crb-action-btn"
                                        title="View Employee"
                                    >

                                        <i class="bi bi-eye"></i>

                                    </a>


                                    {{-- EDIT --}}

                                    <a
                                        href="{{ route('employees.edit', $employee) }}"
                                        class="crb-action-btn"
                                        title="Edit Employee"
                                    >

                                        <i class="bi bi-pencil"></i>

                                    </a>


                                    {{-- DELETE --}}

                                    <form
                                        action="{{ route('employees.destroy', $employee) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Are you sure you want to remove this employee?');"
                                    >

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="crb-action-btn danger"
                                            title="Remove Employee"
                                        >

                                            <i class="bi bi-trash"></i>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>


        {{-- =====================================================
            PAGINATION
        ====================================================== --}}

        @if ($employees->hasPages())

            <div class="crb-pagination">

                {{ $employees->links() }}

            </div>

        @endif


    @else

        {{-- =====================================================
            EMPTY STATE
        ====================================================== --}}

        <div class="crb-empty-state">

            <div class="crb-empty-icon">

                <i class="bi bi-people"></i>

            </div>

            <h4>
                No employees registered
            </h4>

            <p>
                Start by registering your first CRB employee.
            </p>

            <a
                href="{{ route('employees.create') }}"
                class="btn btn-crb"
            >

                <i class="bi bi-person-plus me-1"></i>

                Add First Employee

            </a>

        </div>

    @endif

</div>

</x-app-layout>