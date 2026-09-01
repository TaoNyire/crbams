<x-app-layout>
    <x-slot name="title">
        Edit Department
    </x-slot>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div class="crb-page-title mb-0">

        <h1>Edit Department</h1>

        <p>
            Update the information and status of this department.
        </p>

    </div>

    <a
        href="{{ route('departments.show', $department) }}"
        class="btn btn-light border"
    >
        <i class="bi bi-arrow-left me-1"></i>
        Back to Department
    </a>

</div>


{{-- VALIDATION ERRORS --}}

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


<div class="row">

    <div class="col-lg-8">

        <div class="crb-card">

            <div class="crb-card-header">

                <div>

                    <h5>
                        <i class="bi bi-pencil-square me-2"></i>
                        Department Information
                    </h5>

                    <small>
                        Update department details
                    </small>

                </div>

            </div>


            <div class="p-4">

                <form
                    action="{{ route('departments.update', $department) }}"
                    method="POST"
                >

                    @csrf

                    @method('PUT')


                    {{-- DEPARTMENT NAME --}}

                    <div class="mb-3">

                        <label
                            for="name"
                            class="form-label fw-semibold"
                        >
                            Department Name
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            class="form-control @error('name') is-invalid @enderror"
                            id="name"
                            name="name"
                            value="{{ old('name', $department->name) }}"
                            placeholder="e.g. Information Technology"
                            required
                        >

                        @error('name')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- DEPARTMENT CODE --}}

                    <div class="mb-3">

                        <label
                            for="code"
                            class="form-label fw-semibold"
                        >
                            Department Code
                        </label>

                        <input
                            type="text"
                            class="form-control @error('code') is-invalid @enderror"
                            id="code"
                            name="code"
                            value="{{ old('code', $department->code) }}"
                            placeholder="e.g. IT, FIN, HR, ADM"
                        >

                        <div class="form-text">
                            A short unique code used to identify the department.
                        </div>

                        @error('code')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- DESCRIPTION --}}

                    <div class="mb-3">

                        <label
                            for="description"
                            class="form-label fw-semibold"
                        >
                            Description
                        </label>

                        <textarea
                            class="form-control @error('description') is-invalid @enderror"
                            id="description"
                            name="description"
                            rows="5"
                            placeholder="Enter a brief description of the department..."
                        >{{ old('description', $department->description) }}</textarea>

                        @error('description')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- STATUS --}}

                    <div class="mb-4">

                        <label
                            for="is_active"
                            class="form-label fw-semibold"
                        >
                            Status
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            class="form-select @error('is_active') is-invalid @enderror"
                            id="is_active"
                            name="is_active"
                            required
                        >

                            <option
                                value="1"
                                {{ old('is_active', $department->is_active) == '1' ? 'selected' : '' }}
                            >
                                Active
                            </option>

                            <option
                                value="0"
                                {{ old('is_active', $department->is_active) == '0' ? 'selected' : '' }}
                            >
                                Inactive
                            </option>

                        </select>

                        @error('is_active')

                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>

                        @enderror

                    </div>


                    {{-- ACTIONS --}}

                    <div class="d-flex justify-content-end gap-2">

                        <a
                            href="{{ route('departments.show', $department) }}"
                            class="btn btn-light border"
                        >
                            Cancel
                        </a>

                        <button
                            type="submit"
                            class="btn btn-crb"
                        >
                            <i class="bi bi-check-lg me-1"></i>
                            Update Department
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>


    {{-- DEPARTMENT SUMMARY --}}

    <div class="col-lg-4 mt-4 mt-lg-0">

        <div class="crb-card">

            <div class="crb-card-header">

                <div>

                    <h5>
                        <i class="bi bi-info-circle me-2"></i>
                        Department Summary
                    </h5>

                </div>

            </div>


            <div class="p-4">

                <div class="mb-3">

                    <div class="crb-muted small">
                        Current Department
                    </div>

                    <div class="fw-semibold">
                        {{ $department->name }}
                    </div>

                </div>


                <div class="mb-3">

                    <div class="crb-muted small">
                        Employees
                    </div>

                    <div class="fw-semibold">
                        {{ $department->employees()->count() }}
                    </div>

                </div>


                <div class="mb-3">

                    <div class="crb-muted small">
                        Assets
                    </div>

                    <div class="fw-semibold">
                        {{ $department->assets()->count() }}
                    </div>

                </div>


                <div>

                    <div class="crb-muted small mb-1">
                        Current Status
                    </div>

                    @if ($department->is_active)

                        <span class="crb-status available">

                            <span class="crb-status-dot"></span>

                            Active

                        </span>

                    @else

                        <span class="crb-status retired">

                            <span class="crb-status-dot"></span>

                            Inactive

                        </span>

                    @endif

                </div>

            </div>

        </div>


        <div class="alert alert-light border mt-4 small">

            <i class="bi bi-info-circle me-1"></i>

            Changes made here will update the department information
            throughout the CRB Asset Management System.

        </div>

    </div>

</div>

</x-app-layout>