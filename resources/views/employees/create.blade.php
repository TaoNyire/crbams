<x-app-layout>
    <x-slot name="title">
        Add Employee
    </x-slot>

<div class="d-flex justify-content-between align-items-center mb-4">

```
<div class="crb-page-title mb-0">

    <h1>Add Employee</h1>

    <p>
        Register a new employee in the CRB Asset Management System.
    </p>

</div>

<a
    href="{{ route('employees.index') }}"
    class="btn btn-crb"
>
    <i class="bi bi-arrow-left me-1"></i>
    Back to Employees
</a>
```

</div>

{{-- =========================================================
VALIDATION ERRORS
========================================================= --}}

@if ($errors->any())

```
<div class="alert alert-danger mb-4">

    <div class="fw-semibold mb-2">
        <i class="bi bi-exclamation-triangle me-1"></i>
        Please correct the following errors:
    </div>

    <ul class="mb-0">

        @foreach ($errors->all() as $error)

            <li>{{ $error }}</li>

        @endforeach

    </ul>

</div>
```

@endif

<form
    action="{{ route('employees.store') }}"
    method="POST"
>

```
@csrf


<div class="row g-4">

    {{-- =====================================================
         PERSONAL INFORMATION
    ====================================================== --}}

    <div class="col-lg-8">

        <div class="crb-card h-100">

            <div class="crb-card-header">

                <div>

                    <h5>
                        <i class="bi bi-person me-2"></i>
                        Employee Information
                    </h5>

                    <small>
                        Personal and employment identification details
                    </small>

                </div>

            </div>


            <div class="crb-card-body">

                <div class="row g-3">


                    {{-- EMPLOYEE NUMBER --}}

                    <div class="col-md-6">

                        <label
                            for="employee_number"
                            class="form-label"
                        >
                            Employee Number
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            class="form-control @error('employee_number') is-invalid @enderror"
                            id="employee_number"
                            name="employee_number"
                            value="{{ old('employee_number') }}"
                            placeholder="e.g. CRB-EMP-001"
                            required
                        >

                        @error('employee_number')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                        <div class="form-text">
                            Must be unique for each employee.
                        </div>

                    </div>


                    {{-- FIRST NAME --}}

                    <div class="col-md-6">

                        <label
                            for="first_name"
                            class="form-label"
                        >
                            First Name
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            class="form-control @error('first_name') is-invalid @enderror"
                            id="first_name"
                            name="first_name"
                            value="{{ old('first_name') }}"
                            placeholder="Enter first name"
                            required
                        >

                        @error('first_name')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- LAST NAME --}}

                    <div class="col-md-6">

                        <label
                            for="last_name"
                            class="form-label"
                        >
                            Last Name
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            class="form-control @error('last_name') is-invalid @enderror"
                            id="last_name"
                            name="last_name"
                            value="{{ old('last_name') }}"
                            placeholder="Enter last name"
                            required
                        >

                        @error('last_name')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- EMAIL --}}

                    <div class="col-md-6">

                        <label
                            for="email"
                            class="form-label"
                        >
                            Email Address
                        </label>

                        <input
                            type="email"
                            class="form-control @error('email') is-invalid @enderror"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="employee@crb.org.mw"
                        >

                        @error('email')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- PHONE --}}

                    <div class="col-md-6">

                        <label
                            for="phone"
                            class="form-label"
                        >
                            Phone Number
                        </label>

                        <input
                            type="text"
                            class="form-control @error('phone') is-invalid @enderror"
                            id="phone"
                            name="phone"
                            value="{{ old('phone') }}"
                            placeholder="e.g. 0999 123 456"
                        >

                        @error('phone')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- DESIGNATION --}}

                    <div class="col-md-6">

                        <label
                            for="designation"
                            class="form-label"
                        >
                            Designation
                        </label>

                        <input
                            type="text"
                            class="form-control @error('designation') is-invalid @enderror"
                            id="designation"
                            name="designation"
                            value="{{ old('designation') }}"
                            placeholder="e.g. IT Officer"
                        >

                        @error('designation')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- POSITION --}}

                    <div class="col-md-6">

                        <label
                            for="position"
                            class="form-label"
                        >
                            Position
                        </label>

                        <input
                            type="text"
                            class="form-control @error('position') is-invalid @enderror"
                            id="position"
                            name="position"
                            value="{{ old('position') }}"
                            placeholder="e.g. Hardware Officer"
                        >

                        @error('position')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                </div>

            </div>

        </div>

    </div>


    {{-- =====================================================
         ORGANISATIONAL INFORMATION
    ====================================================== --}}

    <div class="col-lg-4">

        <div class="crb-card h-100">

            <div class="crb-card-header">

                <div>

                    <h5>
                        <i class="bi bi-building me-2"></i>
                        Organisation
                    </h5>

                    <small>
                        Employee placement and status
                    </small>

                </div>

            </div>


            <div class="crb-card-body">


                {{-- DEPARTMENT --}}

                <div class="mb-3">

                    <label
                        for="department_id"
                        class="form-label"
                    >
                        Department
                        <span class="text-danger">*</span>
                    </label>

                    <select
                        class="form-select @error('department_id') is-invalid @enderror"
                        id="department_id"
                        name="department_id"
                        required
                    >

                        <option value="">
                            -- Select Department --
                        </option>

                        @forelse ($departments as $department)

                            <option
                                value="{{ $department->id }}"
                                {{ old('department_id') == $department->id ? 'selected' : '' }}
                            >
                                {{ $department->name }}

                                @if ($department->code)
                                    ({{ $department->code }})
                                @endif

                            </option>

                        @empty

                            <option value="" disabled>
                                No active departments available
                            </option>

                        @endforelse

                    </select>

                    @error('department_id')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                    @if ($departments->isEmpty())

                        <div class="form-text text-danger">
                            Please create an active department before
                            registering an employee.
                        </div>

                    @else

                        <div class="form-text">
                            Select the employee's current department.
                        </div>

                    @endif

                </div>


                {{-- SECTION --}}

                <div class="mb-3">

                    <label
                        for="section"
                        class="form-label"
                    >
                        Section
                    </label>

                    <input
                        type="text"
                        class="form-control @error('section') is-invalid @enderror"
                        id="section"
                        name="section"
                        value="{{ old('section') }}"
                        placeholder="e.g. ICT / Hardware"
                    >

                    @error('section')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>


                {{-- ACTIVE STATUS --}}

                <div class="mb-3">

                    <label class="form-label">
                        Employee Status
                    </label>

                    <div class="form-check form-switch">

                        <input
                            class="form-check-input"
                            type="checkbox"
                            role="switch"
                            id="is_active"
                            name="is_active"
                            value="1"
                            {{ old('is_active', true) ? 'checked' : '' }}
                        >

                        <label
                            class="form-check-label"
                            for="is_active"
                        >
                            Active Employee
                        </label>

                    </div>

                    <div class="form-text">
                        New employees are normally registered as active.
                    </div>

                </div>


                {{-- INFORMATION BOX --}}

                <div
                    class="p-3 rounded"
                    style="background: var(--crb-surface-alt);"
                >

                    <div class="d-flex gap-2">

                        <i
                            class="bi bi-info-circle"
                            style="color: var(--crb-teal);"
                        ></i>

                        <div>

                            <div
                                class="fw-semibold mb-1"
                                style="font-size: 12px;"
                            >
                                Employee record
                            </div>

                            <div
                                class="crb-muted"
                                style="line-height: 1.5;"
                            >
                                Employee records are retained for
                                organisational and asset management
                                history. Employees who leave the
                                organisation should be deactivated
                                rather than deleted.
                            </div>

                        </div>

                    </div>

                </div>


            </div>

        </div>

    </div>


</div>


{{-- =====================================================
     FORM ACTIONS
====================================================== --}}

<div class="d-flex justify-content-end gap-2 mt-4">

    <a
        href="{{ route('employees.index') }}"
        class="btn btn-light border"
    >
        <i class="bi bi-x-lg me-1"></i>
        Cancel
    </a>

    <button
        type="submit"
        class="btn btn-crb"
        {{ $departments->isEmpty() ? 'disabled' : '' }}
    >

        <i class="bi bi-person-plus me-1"></i>

        Register Employee

    </button>

</div>
```

</form>

</x-app-layout>
