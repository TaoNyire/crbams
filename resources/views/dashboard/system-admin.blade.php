<x-app-layout>

    <x-slot name="title">
        System Administration
    </x-slot>


    {{-- ============================================================
        PAGE HEADER
    ============================================================= --}}

    <div class="crb-page-title d-flex justify-content-between align-items-center">

        <div>
            <h1>System Administration</h1>

            <p>
                People, governance, audit oversight and system-wide reporting.
            </p>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('users.index') }}"
               class="btn btn-primary">

                <i class="bi bi-person-plus me-1"></i>

                Manage Users

            </a>

            <a href="{{ route('employees.index') }}"
               class="btn btn-light">

                <i class="bi bi-people me-1"></i>

                Staff Directory

            </a>

        </div>

    </div>


    {{-- ============================================================
        SYSTEM SUMMARY
    ============================================================= --}}

    <div class="row g-3 mb-4">

        {{-- TOTAL STAFF --}}

        <div class="col-xl-3 col-md-6">

            <a href="{{ route('employees.index') }}"
               class="text-decoration-none">

                <div class="crb-stat-card h-100">

                    <div class="crb-stat-top">

                        <div class="crb-stat-icon">
                            <i class="bi bi-people"></i>
                        </div>

                        <i class="bi bi-arrow-up-right text-muted"></i>

                    </div>

                    <div class="crb-stat-value">
                        {{ $totalStaff }}
                    </div>

                    <div class="crb-stat-label">
                        Total Staff
                    </div>

                    <small class="text-muted">
                        {{ $activeStaff }} active
                    </small>

                </div>

            </a>

        </div>


        {{-- SYSTEM USERS --}}

        <div class="col-xl-3 col-md-6">

            <a href="{{ route('users.index') }}"
               class="text-decoration-none">

                <div class="crb-stat-card h-100">

                    <div class="crb-stat-top">

                        <div class="crb-stat-icon">
                            <i class="bi bi-person-badge"></i>
                        </div>

                        <i class="bi bi-arrow-up-right text-muted"></i>

                    </div>

                    <div class="crb-stat-value">
                        {{ $totalUsers }}
                    </div>

                    <div class="crb-stat-label">
                        System Users
                    </div>

                    <small class="text-muted">
                        Manage system access
                    </small>

                </div>

            </a>

        </div>


        {{-- TOTAL ASSETS --}}

        <div class="col-xl-3 col-md-6">

            <a href="{{ route('assets.index') }}"
               class="text-decoration-none">

                <div class="crb-stat-card h-100">

                    <div class="crb-stat-top">

                        <div class="crb-stat-icon">
                            <i class="bi bi-box-seam"></i>
                        </div>

                        <i class="bi bi-arrow-up-right text-muted"></i>

                    </div>

                    <div class="crb-stat-value">
                        {{ $totalAssets }}
                    </div>

                    <div class="crb-stat-label">
                        Total Assets
                    </div>

                    <small class="text-muted">
                        System-wide read-only oversight
                    </small>

                </div>

            </a>

        </div>


        {{-- DEPARTMENTS --}}

        <div class="col-xl-3 col-md-6">

            <a href="{{ route('departments.index') }}"
               class="text-decoration-none">

                <div class="crb-stat-card h-100">

                    <div class="crb-stat-top">

                        <div class="crb-stat-icon">
                            <i class="bi bi-building"></i>
                        </div>

                        <i class="bi bi-arrow-up-right text-muted"></i>

                    </div>

                    <div class="crb-stat-value">
                        {{ $totalDepartments }}
                    </div>

                    <div class="crb-stat-label">
                        Departments
                    </div>

                    <small class="text-muted">
                        {{ $activeDepartments }} active
                    </small>

                </div>

            </a>

        </div>

    </div>


    {{-- ============================================================
        PEOPLE + USER ACCESS
    ============================================================= --}}

    <div class="row g-3 mb-4">


        {{-- PEOPLE OVERVIEW --}}

        <div class="col-lg-6">

            <div class="crb-card h-100">

                <div class="crb-card-header">

                    <div>

                        <h5>People Overview</h5>

                        <small>
                            Staff and organizational records.
                        </small>

                    </div>

                    <a href="{{ route('employees.index') }}"
                       class="btn btn-sm btn-light">

                        View Staff

                        <i class="bi bi-arrow-right ms-1"></i>

                    </a>

                </div>


                <div class="crb-card-body">

                    <div class="row text-center">

                        {{-- TOTAL --}}

                        <div class="col-4">

                            <a href="{{ route('employees.index') }}"
                               class="text-decoration-none text-dark">

                                <div class="crb-stat-value">
                                    {{ $totalStaff }}
                                </div>

                                <div class="crb-muted">
                                    Total Staff
                                </div>

                            </a>

                        </div>


                        {{-- ACTIVE --}}

                        <div class="col-4">

                            <a href="{{ route('employees.index') }}"
                               class="text-decoration-none text-dark">

                                <div class="crb-stat-value">
                                    {{ $activeStaff }}
                                </div>

                                <div class="crb-muted">
                                    Active
                                </div>

                            </a>

                        </div>


                        {{-- INACTIVE --}}

                        <div class="col-4">

                            <a href="{{ route('employees.index') }}"
                               class="text-decoration-none text-dark">

                                <div class="crb-stat-value">
                                    {{ $inactiveStaff }}
                                </div>

                                <div class="crb-muted">
                                    Inactive
                                </div>

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- USER ACCESS --}}

        <div class="col-lg-6">

            <div class="crb-card h-100">

                <div class="crb-card-header">

                    <div>

                        <h5>User Access</h5>

                        <small>
                            Accounts grouped by operational responsibility.
                        </small>

                    </div>

                    <a href="{{ route('users.index') }}"
                       class="btn btn-sm btn-light">

                        Manage

                        <i class="bi bi-arrow-right ms-1"></i>

                    </a>

                </div>


                <div class="crb-card-body">


                    {{-- HARDWARE OFFICERS --}}

                    <a href="{{ route('users.index') }}"
                       class="text-decoration-none text-dark">

                        <div class="d-flex justify-content-between align-items-center mb-3">

                            <span>

                                <i class="bi bi-cpu me-2"></i>

                                Hardware Officers

                            </span>

                            <strong>

                                {{ $usersByRole->get('hardware_officer', 0) }}

                            </strong>

                        </div>

                    </a>


                    {{-- ADMINISTRATION OFFICERS --}}

                    <a href="{{ route('users.index') }}"
                       class="text-decoration-none text-dark">

                        <div class="d-flex justify-content-between align-items-center mb-3">

                            <span>

                                <i class="bi bi-building me-2"></i>

                                Administration Officers

                            </span>

                            <strong>

                                {{ $usersByRole->get('administration_officer', 0) }}

                            </strong>

                        </div>

                    </a>


                    {{-- SYSTEM ADMINISTRATORS --}}

                    <a href="{{ route('users.index') }}"
                       class="text-decoration-none text-dark">

                        <div class="d-flex justify-content-between align-items-center">

                            <span>

                                <i class="bi bi-shield-check me-2"></i>

                                System Administrators

                            </span>

                            <strong>

                                {{ $usersByRole->get('system_admin', 0) }}

                            </strong>

                        </div>

                    </a>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        ASSET OVERSIGHT
    ============================================================= --}}

    <div class="row g-3 mb-4">

        <div class="col-12">

            <div class="crb-card">

                <div class="crb-card-header">

                    <div>

                        <h5>Asset Oversight</h5>

                        <small>
                            System-wide asset visibility. Operational asset
                            management remains with the responsible officers.
                        </small>

                    </div>

                    <a href="{{ route('assets.index') }}"
                       class="btn btn-sm btn-light">

                        View Assets

                        <i class="bi bi-arrow-right ms-1"></i>

                    </a>

                </div>


                <div class="crb-card-body">

                    <div class="row g-3">


                        {{-- HARDWARE ASSETS --}}

                        <div class="col-md-3">

                            <a href="{{ route('assets.index') }}"
                               class="text-decoration-none text-dark">

                                <div class="border rounded p-3 h-100">

                                    <div class="d-flex justify-content-between">

                                        <div class="text-muted small">
                                            Hardware Assets
                                        </div>

                                        <i class="bi bi-arrow-up-right text-muted"></i>

                                    </div>

                                    <div class="fs-3 fw-bold">
                                        {{ $hardwareAssets }}
                                    </div>

                                    <small class="text-muted">
                                        View hardware inventory
                                    </small>

                                </div>

                            </a>

                        </div>


                        {{-- ADMINISTRATION ASSETS --}}

                        <div class="col-md-3">

                            <a href="{{ route('assets.index') }}"
                               class="text-decoration-none text-dark">

                                <div class="border rounded p-3 h-100">

                                    <div class="d-flex justify-content-between">

                                        <div class="text-muted small">
                                            Administration Assets
                                        </div>

                                        <i class="bi bi-arrow-up-right text-muted"></i>

                                    </div>

                                    <div class="fs-3 fw-bold">
                                        {{ $administrationAssets }}
                                    </div>

                                    <small class="text-muted">
                                        View administration inventory
                                    </small>

                                </div>

                            </a>

                        </div>


                        {{-- ASSIGNED ASSETS --}}

                        <div class="col-md-3">

                            <a href="{{ route('assets.index') }}"
                               class="text-decoration-none text-dark">

                                <div class="border rounded p-3 h-100">

                                    <div class="d-flex justify-content-between">

                                        <div class="text-muted small">
                                            Assigned Assets
                                        </div>

                                        <i class="bi bi-arrow-up-right text-muted"></i>

                                    </div>

                                    <div class="fs-3 fw-bold">
                                        {{ $assignedAssets }}
                                    </div>

                                    <small class="text-muted">
                                        View assigned inventory
                                    </small>

                                </div>

                            </a>

                        </div>


                        {{-- UNASSIGNED ASSETS --}}

                        <div class="col-md-3">

                            <a href="{{ route('assets.index') }}"
                               class="text-decoration-none text-dark">

                                <div class="border rounded p-3 h-100">

                                    <div class="d-flex justify-content-between">

                                        <div class="text-muted small">
                                            Unassigned Assets
                                        </div>

                                        <i class="bi bi-arrow-up-right text-muted"></i>

                                    </div>

                                    <div class="fs-3 fw-bold">
                                        {{ $unassignedAssets }}
                                    </div>

                                    <small class="text-muted">
                                        Review unassigned assets
                                    </small>

                                </div>

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        GOVERNANCE / ATTENTION
    ============================================================= --}}

    <div class="row g-3 mb-4">


        {{-- ATTENTION REQUIRED --}}

        <div class="col-lg-5">

            <div class="crb-card h-100">

                <div class="crb-card-header">

                    <div>

                        <h5>Attention Required</h5>

                        <small>
                            Items requiring governance review.
                        </small>

                    </div>

                </div>


                <div class="crb-card-body">


                    {{-- INACTIVE STAFF WITH ASSETS --}}

                    <a href="{{ route('employees.index') }}"
                       class="text-decoration-none text-dark">

                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <div>

                                <i class="bi bi-exclamation-triangle text-danger me-2"></i>

                                Inactive staff with assets

                            </div>

                            <strong>
                                {{ $inactiveStaffWithAssets }}
                            </strong>

                        </div>

                    </a>


                    {{-- ASSETS WITHOUT DEPARTMENT --}}

                    <a href="{{ route('assets.index') }}"
                       class="text-decoration-none text-dark">

                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <div>

                                <i class="bi bi-building-exclamation text-warning me-2"></i>

                                Assets without department

                            </div>

                            <strong>
                                {{ $assetsWithoutDepartment }}
                            </strong>

                        </div>

                    </a>


                    {{-- ASSETS UNDER REPAIR --}}

                    <a href="{{ route('assets.index') }}"
                       class="text-decoration-none text-dark">

                        <div class="d-flex justify-content-between align-items-center">

                            <div>

                                <i class="bi bi-tools text-warning me-2"></i>

                                Assets under repair

                            </div>

                            <strong>
                                {{ $repairAssets }}
                            </strong>

                        </div>

                    </a>

                </div>

            </div>

        </div>


        {{-- RECENT STAFF --}}

        <div class="col-lg-7">

            <div class="crb-card h-100">

                <div class="crb-card-header">

                    <div>

                        <h5>Recently Enrolled Staff</h5>

                        <small>
                            Latest people added to CRBAMS.
                        </small>

                    </div>

                    <a href="{{ route('employees.index') }}"
                       class="btn btn-sm btn-light">

                        View All

                        <i class="bi bi-arrow-right ms-1"></i>

                    </a>

                </div>


                <div class="table-responsive">

                    <table class="table crb-table">

                        <thead>

                            <tr>

                                <th>
                                    Employee
                                </th>

                                <th>
                                    Department
                                </th>

                                <th>
                                    Status
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse ($recentStaff as $employee)

                                <tr>

                                    <td>

                                        <strong>

                                            {{ $employee->first_name }}
                                            {{ $employee->last_name }}

                                        </strong>

                                        <div class="crb-muted">

                                            {{ $employee->employee_number }}

                                        </div>

                                    </td>


                                    <td>

                                        {{ $employee->department?->name ?? 'Unassigned' }}

                                    </td>


                                    <td>

                                        @if ($employee->is_active)

                                            <span class="crb-status available">

                                                <span class="crb-status-dot"></span>

                                                Active

                                            </span>

                                        @else

                                            <span class="crb-status">

                                                <span class="crb-status-dot"></span>

                                                Inactive

                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="3"
                                        class="text-center py-5 text-muted">

                                        No staff have been enrolled yet.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================
        RECENT ASSET ACTIVITY
    ============================================================= --}}

    <div class="row g-3">

        <div class="col-12">

            <div class="crb-card">

                <div class="crb-card-header">

                    <div>

                        <h5>Recent Asset Activity</h5>

                        <small>
                            Read-only visibility of recently registered assets.
                        </small>

                    </div>

                    <a href="{{ route('assets.index') }}"
                       class="btn btn-sm btn-light">

                        View Assets

                        <i class="bi bi-arrow-right ms-1"></i>

                    </a>

                </div>


                <div class="table-responsive">

                    <table class="table crb-table">

                        <thead>

                            <tr>

                                <th>
                                    Asset
                                </th>

                                <th>
                                    Management Area
                                </th>

                                <th>
                                    Department
                                </th>

                                <th>
                                    Assigned To
                                </th>

                                <th>
                                    Status
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse ($recentAssets as $asset)

                                <tr>

                                    <td>

                                        <a href="{{ route('assets.show', $asset) }}"
                                           class="crb-asset-code">

                                            {{ $asset->asset_code }}

                                        </a>

                                        <div class="crb-muted">

                                            {{ $asset->asset_name }}

                                        </div>

                                    </td>


                                    <td>

                                        {{ str(
                                            $asset->category?->responsible_officer
                                            ?? 'unassigned'
                                        )->replace('_', ' ')->title() }}

                                    </td>


                                    <td>

                                        {{ $asset->department?->name ?? 'Unassigned' }}

                                    </td>


                                    <td>

                                        @if ($asset->employee)

                                            {{ $asset->employee->first_name }}
                                            {{ $asset->employee->last_name }}

                                        @else

                                            <span class="text-muted">
                                                Unassigned
                                            </span>

                                        @endif

                                    </td>


                                    <td>

                                        <span class="crb-status {{ $asset->status === 'under_repair' ? 'repair' : $asset->status }}">

                                            <span class="crb-status-dot"></span>

                                            {{ str($asset->status)
                                                ->replace('_', ' ')
                                                ->title() }}

                                        </span>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="5"
                                        class="text-center py-5 text-muted">

                                        No assets have been registered yet.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>


</x-app-layout>