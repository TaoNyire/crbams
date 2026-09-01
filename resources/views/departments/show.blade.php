<x-app-layout>
    <x-slot name="title">
        {{ $department->name }}
    </x-slot>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div class="crb-page-title mb-0">

        <h1>{{ $department->name }}</h1>

        <p>
            Department details, employees and assigned assets.
        </p>

    </div>

    <div class="d-flex gap-2">

        <a
            href="{{ route('departments.index') }}"
            class="btn btn-light border"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back
        </a>

        <a
            href="{{ route('departments.edit', $department) }}"
            class="btn btn-crb"
        >
            <i class="bi bi-pencil me-1"></i>
            Edit Department
        </a>

    </div>

</div>


{{-- SUCCESS MESSAGE --}}

@if (session('success'))

    <div class="alert alert-success mb-4">

        <i class="bi bi-check-circle me-1"></i>

        {{ session('success') }}

    </div>

@endif


{{-- DEPARTMENT INFORMATION --}}

<div class="row g-4 mb-4">

    {{-- BASIC INFORMATION --}}

    <div class="col-lg-8">

        <div class="crb-card h-100">

            <div class="crb-card-header">

                <div>

                    <h5>
                        <i class="bi bi-building me-2"></i>
                        Department Information
                    </h5>

                    <small>
                        Organizational details
                    </small>

                </div>

            </div>


            <div class="p-4">

                <div class="row g-4">

                    {{-- NAME --}}

                    <div class="col-md-6">

                        <div class="crb-muted small mb-1">
                            Department Name
                        </div>

                        <div class="fw-semibold fs-5">
                            {{ $department->name }}
                        </div>

                    </div>


                    {{-- CODE --}}

                    <div class="col-md-6">

                        <div class="crb-muted small mb-1">
                            Department Code
                        </div>

                        <div class="fw-semibold">

                            @if ($department->code)

                                {{ $department->code }}

                            @else

                                <span class="crb-muted">Not assigned</span>

                            @endif

                        </div>

                    </div>


                    {{-- STATUS --}}

                    <div class="col-md-6">

                        <div class="crb-muted small mb-1">
                            Status
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


                    {{-- CREATED --}}

                    <div class="col-md-6">

                        <div class="crb-muted small mb-1">
                            Registered
                        </div>

                        <div class="fw-semibold">

                            {{ $department->created_at?->format('d M Y') ?? '—' }}

                        </div>

                    </div>


                    {{-- DESCRIPTION --}}

                    <div class="col-12">

                        <div class="crb-muted small mb-1">
                            Description
                        </div>

                        @if ($department->description)

                            <p class="mb-0">
                                {{ $department->description }}
                            </p>

                        @else

                            <span class="crb-muted">
                                No description provided.
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- SUMMARY --}}

    <div class="col-lg-4">

        <div class="row g-4">

            {{-- EMPLOYEES --}}

            <div class="col-6 col-lg-12">

                <div class="crb-card">

                    <div class="p-4 d-flex align-items-center">

                        <div class="crb-avatar me-3">

                            <i class="bi bi-people"></i>

                        </div>

                        <div>

                            <div class="crb-muted small">
                                Employees
                            </div>

                            <div class="fs-3 fw-bold">
                                {{ $department->employees->count() }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ASSETS --}}

            <div class="col-6 col-lg-12">

                <div class="crb-card">

                    <div class="p-4 d-flex align-items-center">

                        <div class="crb-avatar me-3">

                            <i class="bi bi-box-seam"></i>

                        </div>

                        <div>

                            <div class="crb-muted small">
                                Assets
                            </div>

                            <div class="fs-3 fw-bold">
                                {{ $department->assets->count() }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- EMPLOYEES --}}

<div class="crb-card mb-4">

    <div class="crb-card-header">

        <div>

            <h5>

                <i class="bi bi-people me-2"></i>

                Department Employees

            </h5>

            <small>
                Employees assigned to this department
            </small>

        </div>

        <span class="crb-muted">

            {{ $department->employees->count() }}

        </span>

    </div>


    @if ($department->employees->count())

        <div class="table-responsive">

            <table class="table crb-table align-middle mb-0">

                <thead>

                    <tr>

                        <th>Employee</th>

                        <th>Employee Number</th>

                        <th>Position</th>

                        <th>Status</th>

                    </tr>

                </thead>

                <tbody>

                    @foreach ($department->employees as $employee)

                        <tr>

                            <td>

                                <div class="d-flex align-items-center gap-2">

                                    <div class="crb-avatar">

                                        <i class="bi bi-person"></i>

                                    </div>

                                    <div>

                                        <div class="fw-semibold">

                                            {{ $employee->first_name ?? '' }}
                                            {{ $employee->last_name ?? '' }}

                                        </div>

                                    </div>

                                </div>

                            </td>

                            <td>

                                {{ $employee->employee_number ?? '—' }}

                            </td>

                            <td>

                                {{ $employee->position ?? '—' }}

                            </td>

                            <td>

                                @if ($employee->is_active ?? true)

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

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    @else

        <div class="crb-empty-state py-5">

            <div class="crb-empty-icon">

                <i class="bi bi-people"></i>

            </div>

            <h4>No Employees</h4>

            <p>
                No employees are currently assigned to this department.
            </p>

        </div>

    @endif

</div>


{{-- ASSETS --}}

<div class="crb-card">

    <div class="crb-card-header">

        <div>

            <h5>

                <i class="bi bi-box-seam me-2"></i>

                Department Assets

            </h5>

            <small>
                Assets assigned to this department
            </small>

        </div>

        <span class="crb-muted">

            {{ $department->assets->count() }}

        </span>

    </div>


    @if ($department->assets->count())

        <div class="table-responsive">

            <table class="table crb-table align-middle mb-0">

                <thead>

                    <tr>

                        <th>Asset</th>

                        <th>Asset Code</th>

                        <th>Category</th>

                        <th>Type</th>

                        <th>Status</th>

                    </tr>

                </thead>

                <tbody>

                    @foreach ($department->assets as $asset)

                        <tr>

                            <td>

                                <div class="fw-semibold">

                                    {{ $asset->name ?? 'Unnamed Asset' }}

                                </div>

                            </td>

                            <td>

                                @if ($asset->asset_code ?? false)

                                    <span class="crb-asset-code">

                                        {{ $asset->asset_code }}

                                    </span>

                                @else

                                    <span class="crb-muted">
                                        —
                                    </span>

                                @endif

                            </td>

                            <td>

                                {{ $asset->category->name ?? '—' }}

                            </td>

                            <td>

                                {{ $asset->type->name ?? '—' }}

                            </td>

                            <td>

                                @if (isset($asset->status))

                                    <span class="crb-muted">

                                        {{ ucfirst(str_replace('_', ' ', $asset->status)) }}

                                    </span>

                                @else

                                    <span class="crb-muted">
                                        —
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    @else

        <div class="crb-empty-state py-5">

            <div class="crb-empty-icon">

                <i class="bi bi-box-seam"></i>

            </div>

            <h4>No Assets</h4>

            <p>
                No assets are currently assigned to this department.
            </p>

        </div>

    @endif

</div>

</x-app-layout>