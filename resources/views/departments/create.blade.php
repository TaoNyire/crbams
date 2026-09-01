<x-app-layout>
    <x-slot name="title">
        Create Department
    </x-slot>
<div class="container-fluid py-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold">Create Department</h4>
            <p class="text-muted mb-0">
                Register a new department in the organization.
            </p>
        </div>

        <a href="{{ route('departments.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Back to Departments
        </a>
    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please correct the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-semibold">
                        Department Information
                    </h5>
                </div>

                <div class="card-body">

                    <form action="{{ route('departments.store') }}" method="POST">
                        @csrf

                        {{-- Department Name --}}
                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">
                                Department Name
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                class="form-control @error('name') is-invalid @enderror"
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
                                placeholder="e.g. Information Technology"
                                required
                            >

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Department Code --}}
                        <div class="mb-3">
                            <label for="code" class="form-label fw-semibold">
                                Department Code
                            </label>

                            <input
                                type="text"
                                class="form-control @error('code') is-invalid @enderror"
                                id="code"
                                name="code"
                                value="{{ old('code') }}"
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

                        {{-- Description --}}
                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold">
                                Description
                            </label>

                            <textarea
                                class="form-control @error('description') is-invalid @enderror"
                                id="description"
                                name="description"
                                rows="5"
                                placeholder="Enter a brief description of the department..."
                            >{{ old('description') }}</textarea>

                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div class="mb-4">
                            <label for="is_active" class="form-label fw-semibold">
                                Status
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                class="form-select @error('is_active') is-invalid @enderror"
                                id="is_active"
                                name="is_active"
                                required
                            >
                                <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>
                                    Active
                                </option>

                                <option value="0" {{ old('is_active') === '0' ? 'selected' : '' }}>
                                    Inactive
                                </option>
                            </select>

                            @error('is_active')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Actions --}}
                        <div class="d-flex justify-content-end gap-2">
                            <a
                                href="{{ route('departments.index') }}"
                                class="btn btn-light border"
                            >
                                Cancel
                            </a>

                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-building-add me-1"></i>
                                Create Department
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

        {{-- Information Panel --}}
        <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-info-circle me-2"></i>
                        Department Information
                    </h6>

                    <p class="text-muted small mb-3">
                        Departments are used to organize employees and assets
                        within the CRB Asset Management System.
                    </p>

                    <div class="small text-muted">
                        <div class="d-flex mb-2">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            Department names must be unique.
                        </div>

                        <div class="d-flex mb-2">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            Department codes should be short and identifiable.
                        </div>

                        <div class="d-flex">
                            <i class="bi bi-check-circle text-success me-2"></i>
                            Inactive departments can be retained for historical records.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</x-app-layout>