<x-app-layout>

```
<x-slot name="title">
    Edit Employee
</x-slot>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div class="crb-page-title mb-0">

        <h1>Edit Employee</h1>

        <p>
            Update employee information and organisational placement.
        </p>

    </div>

    <div class="d-flex gap-2">

        <a
            href="{{ route('employees.show', $employee) }}"
            class="btn btn-light border"
        >
            <i class="bi bi-eye me-1"></i>
            View Employee
        </a>

        <a
            href="{{ route('employees.index') }}"
            class="btn btn-crb"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back to Employees
        </a>

    </div>

</div>


@if ($errors->any())

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

@endif


<form
    action="{{ route('employees.update', $employee) }}"
    method="POST"
>

    @csrf

    @method('PUT')


    <div class="row g-4">


        {{-- EMPLOYEE INFORMATION --}}

        <div class="col-lg-8">

            <div class="crb-card h-100">

                <div class="crb-card-header">

                    <div>

                        <h5>

                            <i class="bi bi-person me-2"></i>

                            Employee Information

                        </h5>

                        <small>
                            Update employee details
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
                                value="{{ old('employee_number', $employee->employee_number) }}"
                                placeholder="e.g. CRB-EMP-001"
                                required
                            >

                            @error('employee_number')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                            <div class="form-text">
                                Employee number must be unique.
                            </div>

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
                                value="{{ old('position', $employee->position) }}"
                                placeholder="e.g. Hardware Officer"
                            >

                            @error('position')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

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
                                value="{{ old('first_name', $employee->first_name) }}"
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
                                value="{{ old('last_name', $employee->last_name) }}"
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
                                value="{{ old('email', $employee->email) }}"
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
                                value="{{ old('phone', $employee->phone) }}"
                                placeholder="e.g. 0999 123 456"
                            >

                            @error('phone')

                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>

                            @enderror

                        </div>


                    </div>

                </div>

            </div>

        </div>


        {{-- ORGANISATION --}}

        <div class="col-lg-4">

            <div class="crb-card h-100">

                <div class="crb-card-header">

                    <div>

                        <h5>

                            <i class="bi bi-building me-2"></i>

                            Organisation

                        </h5>

                        <small>
                            Employee placement
                        </small>

                    </div>

                </div>


                <div class="crb-card-body">


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
                                    {{ old('department_id', $employee->department_id) == $department->id ? 'selected' : '' }}
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
                                No active departments are available.
                            </div>

                        @else

                            <div class="form-text">
                                Select the employee's current department.
                            </div>

                        @endif

                    </div>


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
                                    Employee assignment
                                </div>

                                <div
                                    class="crb-muted"
                                    style="line-height: 1.5;"
                                >
                                    Changing the department will update
                                    the employee's organisational placement.
                                    Existing asset assignments remain linked
                                    to the employee.
                                </div>

                            </div>

                        </div>

                    </div>


                </div>

            </div>

        </div>


    </div>


    {{-- ACTIONS --}}

    <div class="d-flex justify-content-end gap-2 mt-4">

        <a
            href="{{ route('employees.show', $employee) }}"
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

            <i class="bi bi-check2-circle me-1"></i>

            Update Employee

        </button>

    </div>


</form>
```

</x-app-layout>
