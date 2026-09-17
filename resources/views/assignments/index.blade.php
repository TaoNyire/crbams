<x-app-layout>

    <x-slot name="title">
        Asset Allocation Register
    </x-slot>

    <style>
        .allocation-page {
            padding-bottom: 40px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 25px;
        }

        .page-title {
            margin: 0;
            font-weight: 700;
            color: #246d69;
        }

        .page-subtitle {
            margin: 5px 0 0;
            color: #6c757d;
        }

        .stat-card {
            border: 0;
            border-radius: 14px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, .06);
            height: 100%;
        }

        .stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eef7f6;
            color: #246d69;
            font-size: 1.15rem;
        }

        .stat-label {
            color: #6c757d;
            font-size: .78rem;
            text-transform: uppercase;
            letter-spacing: .04em;
            margin-top: 12px;
        }

        .stat-number {
            font-size: 1.8rem;
            font-weight: 700;
            color: #246d69;
            margin-top: 3px;
        }

        .filter-card,
        .table-card {
            border: 0;
            border-radius: 14px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, .06);
        }

        .filter-card .card-body {
            padding: 22px;
        }

        .table-card {
            overflow: hidden;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .allocation-table {
            min-width: 1250px;
        }

        .allocation-table thead th {
            background: #246d69;
            color: white;
            font-size: .79rem;
            font-weight: 600;
            white-space: nowrap;
            vertical-align: middle;
            padding: 14px 12px;
            border: 0;
        }

        .allocation-table tbody td {
            vertical-align: middle;
            font-size: .88rem;
            padding: 14px 12px;
            border-color: #edf0f0;
        }

        .allocation-table tbody tr:hover {
            background: #f8fbfb;
        }

        .asset-code {
            font-weight: 700;
            color: #246d69;
            font-size: .9rem;
        }

        .asset-name {
            max-width: 220px;
            white-space: normal;
            line-height: 1.35;
        }

        .category-name {
            font-weight: 600;
        }

        .management-area {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 8px;
            border-radius: 6px;
            background: #f1f8f7;
            color: #246d69;
            font-size: .75rem;
            font-weight: 600;
        }

        .department-name {
            font-weight: 600;
        }

        .department-code {
            color: #6c757d;
            font-size: .75rem;
            margin-top: 2px;
        }

        .employee-name {
            font-weight: 600;
        }

        .employee-number {
            color: #6c757d;
            font-size: .75rem;
            margin-top: 2px;
        }

        .unassigned-text {
            color: #6c757d;
            font-style: italic;
        }

        .assigned-by {
            font-weight: 500;
        }

        .date-value {
            white-space: nowrap;
            font-weight: 500;
        }

        .date-time {
            color: #6c757d;
            font-size: .75rem;
            margin-top: 2px;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 10px;
            border-radius: 50px;
            font-size: .75rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .status-assigned {
            background: #c9ff83;
            color: #1d4d49;
        }

        .status-available {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .status-repair {
            background: #fff3cd;
            color: #856404;
        }

        .status-lost {
            background: #f8d7da;
            color: #842029;
        }

        .status-disposed {
            background: #e9ecef;
            color: #495057;
        }

        .status-retired {
            background: #dee2e6;
            color: #343a40;
        }

        .action-buttons {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
        }

        .action-buttons .btn {
            min-width: 38px;
        }

        .return-button {
            border-color: #dc3545;
            color: #dc3545;
        }

        .return-button:hover {
            background: #dc3545;
            color: white;
        }

        .btn-crb {
            background: #246d69;
            border-color: #246d69;
            color: white;
        }

        .btn-crb:hover {
            background: #1c5956;
            border-color: #1c5956;
            color: white;
        }

        .oversight-note {
            background: #f1f8f7;
            border-left: 4px solid #246d69;
            color: #495057;
            border-radius: 8px;
            padding: 12px 15px;
            margin-bottom: 20px;
        }

        .oversight-note i {
            color: #246d69;
        }

        .filter-label {
            margin-bottom: 7px;
            font-size: .85rem;
        }

        .empty-state {
            padding: 70px 20px;
            text-align: center;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 3rem;
            color: #246d69;
            margin-bottom: 15px;
        }

        .register-info {
            padding: 15px 18px;
            background: #fafcfc;
            border-bottom: 1px solid #edf0f0;
        }

        .register-info-title {
            font-weight: 700;
            color: #246d69;
        }

        .register-info-text {
            color: #6c757d;
            font-size: .82rem;
            margin-top: 2px;
        }

        @media (max-width: 768px) {

            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .page-header .btn {
                width: 100%;
            }

            .stat-card {
                margin-bottom: 5px;
            }
        }

        @media print {

            .crb-sidebar,
            .page-header .btn,
            .filter-card,
            .action-buttons,
            .oversight-note,
            .pagination {
                display: none !important;
            }

            .allocation-page {
                padding: 0;
            }

            .table-card,
            .stat-card {
                box-shadow: none !important;
            }

            .allocation-table {
                min-width: 0;
            }
        }
    </style>


    <div class="container-fluid allocation-page">

        {{-- =====================================================
             PAGE HEADER
        ====================================================== --}}

        <div class="page-header">

            <div>
                <h2 class="page-title">
                    <i class="bi bi-arrow-left-right me-2"></i>
                    Asset Allocation Register
                </h2>

                <p class="page-subtitle">
                    Monitor current asset allocation, availability and operational status.
                </p>
            </div>

            <a href="{{ route('assets.index') }}"
               class="btn btn-crb">

                <i class="bi bi-box-seam me-1"></i>
                View Asset Register

            </a>

        </div>


        {{-- =====================================================
             ROLE INFORMATION
        ====================================================== --}}

        @if(auth()->user()->role === 'system_admin')

            <div class="oversight-note">

                <i class="bi bi-shield-check me-2"></i>

                <strong>Oversight Mode:</strong>

                You are viewing the consolidated asset allocation register
                for monitoring and audit purposes. System Administrators
                cannot assign, return or modify assets.

            </div>

        @else

            <div class="oversight-note">

                <i class="bi bi-info-circle me-2"></i>

                <strong>Asset Operations:</strong>

                This register shows assets within your management area,
                including assigned, available and under-repair assets.

            </div>

        @endif


        {{-- =====================================================
             FLASH MESSAGES
        ====================================================== --}}

        @if(session('success'))

            <div class="alert alert-success alert-dismissible fade show"
                 role="alert">

                <i class="bi bi-check-circle me-2"></i>

                {{ session('success') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        @if(session('error'))

            <div class="alert alert-danger alert-dismissible fade show"
                 role="alert">

                <i class="bi bi-exclamation-circle me-2"></i>

                {{ session('error') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                </button>

            </div>

        @endif


        {{-- =====================================================
             STATISTICS
        ====================================================== --}}

        <div class="row g-4 mb-4">

            {{-- TOTAL ASSETS --}}

            <div class="col-xl col-md-6">

                <div class="card stat-card">

                    <div class="card-body">

                        <div class="stat-icon">
                            <i class="bi bi-box-seam"></i>
                        </div>

                        <div class="stat-label">
                            Total Assets
                        </div>

                        <div class="stat-number">
                            {{ number_format($totalAssets) }}
                        </div>

                        <div class="text-muted small">
                            Assets in this register
                        </div>

                    </div>

                </div>

            </div>


            {{-- ASSIGNED --}}

            <div class="col-xl col-md-6">

                <div class="card stat-card">

                    <div class="card-body">

                        <div class="stat-icon">
                            <i class="bi bi-person-check"></i>
                        </div>

                        <div class="stat-label">
                            Assigned
                        </div>

                        <div class="stat-number">
                            {{ number_format($assignedAssets) }}
                        </div>

                        <div class="text-muted small">
                            Currently allocated
                        </div>

                    </div>

                </div>

            </div>


            {{-- AVAILABLE --}}

            <div class="col-xl col-md-6">

                <div class="card stat-card">

                    <div class="card-body">

                        <div class="stat-icon">
                            <i class="bi bi-box-arrow-in-down"></i>
                        </div>

                        <div class="stat-label">
                            Available / Idle
                        </div>

                        <div class="stat-number">
                            {{ number_format($availableAssets) }}
                        </div>

                        <div class="text-muted small">
                            Ready for allocation
                        </div>

                    </div>

                </div>

            </div>


            {{-- UNDER REPAIR --}}

            <div class="col-xl col-md-6">

                <div class="card stat-card">

                    <div class="card-body">

                        <div class="stat-icon">
                            <i class="bi bi-tools"></i>
                        </div>

                        <div class="stat-label">
                            Under Repair
                        </div>

                        <div class="stat-number">
                            {{ number_format($underRepairAssets) }}
                        </div>

                        <div class="text-muted small">
                            Currently unavailable
                        </div>

                    </div>

                </div>

            </div>


            {{-- RETIRED --}}

            <div class="col-xl col-md-6">

                <div class="card stat-card">

                    <div class="card-body">

                        <div class="stat-icon">
                            <i class="bi bi-archive"></i>
                        </div>

                        <div class="stat-label">
                            Retired
                        </div>

                        <div class="stat-number">
                            {{ number_format($retiredAssets) }}
                        </div>

                        <div class="text-muted small">
                            Removed from active use
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =====================================================
             FILTERS
        ====================================================== --}}

        <div class="card filter-card mb-4">

            <div class="card-body">

                <form method="GET"
                      action="{{ route('assignments.index') }}">

                    <div class="row g-3 align-items-end">


                        {{-- SEARCH --}}

                        <div class="col-xl-4 col-lg-5">

                            <label class="form-label fw-semibold filter-label">
                                Search Assets
                            </label>

                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                value="{{ request('search') }}"
                                placeholder="Asset code, name, serial number, employee..."
                            >

                        </div>


                        {{-- MANAGEMENT AREA --}}

                        <div class="col-xl-2 col-lg-3">

                            <label class="form-label fw-semibold filter-label">
                                Management Area
                            </label>

                            @if(auth()->user()->role === 'system_admin')

                                <select name="management_area"
                                        class="form-select">

                                    <option value="">
                                        All Areas
                                    </option>

                                    @foreach($managementAreas as $value => $label)

                                        <option
                                            value="{{ $value }}"
                                            @selected(request('management_area') === $value)
                                        >
                                            {{ $label }}
                                        </option>

                                    @endforeach

                                </select>

                            @else

                                <select class="form-select" disabled>

                                    <option>
                                        {{ $managementAreas[auth()->user()->management_area] ?? ucfirst(auth()->user()->management_area) }}
                                    </option>

                                </select>

                                <input
                                    type="hidden"
                                    name="management_area"
                                    value="{{ auth()->user()->management_area }}"
                                >

                            @endif

                        </div>


                        {{-- DEPARTMENT --}}

                        <div class="col-xl-2 col-lg-3">

                            <label class="form-label fw-semibold filter-label">
                                Department
                            </label>

                            <select name="department_id"
                                    class="form-select">

                                <option value="">
                                    All Departments
                                </option>

                                @foreach($departments as $department)

                                    <option
                                        value="{{ $department->id }}"
                                        @selected((string) request('department_id') === (string) $department->id)
                                    >
                                        {{ $department->name }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- STATUS --}}

                        <div class="col-xl-2 col-lg-3">

                            <label class="form-label fw-semibold filter-label">
                                Asset Status
                            </label>

                            <select name="status"
                                    class="form-select">

                                <option value="">
                                    All Statuses
                                </option>

                                <option
                                    value="assigned"
                                    @selected(request('status') === 'assigned')
                                >
                                    Assigned
                                </option>

                                <option
                                    value="available"
                                    @selected(request('status') === 'available')
                                >
                                    Available / Idle
                                </option>

                                <option
                                    value="under_repair"
                                    @selected(request('status') === 'under_repair')
                                >
                                    Under Repair
                                </option>

                                <option
                                    value="lost"
                                    @selected(request('status') === 'lost')
                                >
                                    Lost
                                </option>

                                <option
                                    value="disposed"
                                    @selected(request('status') === 'disposed')
                                >
                                    Disposed
                                </option>

                                <option
                                    value="retired"
                                    @selected(request('status') === 'retired')
                                >
                                    Retired
                                </option>

                            </select>

                        </div>


                        {{-- BUTTONS --}}

                        <div class="col-xl-2 col-lg-3 d-flex gap-2">

                            <button type="submit"
                                    class="btn btn-crb flex-fill">

                                <i class="bi bi-funnel me-1"></i>
                                Filter

                            </button>

                            <a href="{{ route('assignments.index') }}"
                               class="btn btn-outline-secondary"
                               title="Clear filters">

                                <i class="bi bi-x-lg"></i>

                            </a>

                        </div>

                    </div>

                </form>

            </div>

        </div>


        {{-- =====================================================
             REGISTER TABLE
        ====================================================== --}}

        <div class="card table-card">

            {{-- REGISTER HEADER --}}

            <div class="register-info">

                <div class="register-info-title">

                    <i class="bi bi-list-check me-1"></i>

                    Current Asset Allocation

                </div>

                <div class="register-info-text">

                    Every asset within your permitted management area is
                    displayed. Assignment status reflects the asset's
                    current operational state.

                </div>

            </div>


            <div class="card-body p-0">

                @if($assets->count())

                    <div class="table-responsive">

                        <table class="table table-hover mb-0 allocation-table">

                            <thead>

                                <tr>

                                    <th>
                                        Asset
                                    </th>

                                    <th>
                                        Category
                                    </th>

                                    <th>
                                        Management Area
                                    </th>

                                    <th>
                                        Department
                                    </th>

                                    <th>
                                        Current Holder
                                    </th>

                                    <th>
                                        Assignment
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th class="text-end">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach($assets as $asset)

                                    @php

                                        /*
                                         * Asset status is the source of truth.
                                         *
                                         * An active assignment is only treated
                                         * as the current allocation when the
                                         * asset itself is marked "assigned".
                                         */

                                        $currentAssignment = null;

                                        if ($asset->status === 'assigned') {
                                            $currentAssignment = $asset->assignments->first();
                                        }

                                        $statusLabel = match($asset->status) {
                                            'assigned' => 'Assigned',
                                            'available' => 'Available / Idle',
                                            'under_repair' => 'Under Repair',
                                            'lost' => 'Lost',
                                            'disposed' => 'Disposed',
                                            'retired' => 'Retired',
                                            default => ucfirst(str_replace('_', ' ', $asset->status)),
                                        };

                                        $statusClass = match($asset->status) {
                                            'assigned' => 'status-assigned',
                                            'available' => 'status-available',
                                            'under_repair' => 'status-repair',
                                            'lost' => 'status-lost',
                                            'disposed' => 'status-disposed',
                                            'retired' => 'status-retired',
                                            default => 'status-disposed',
                                        };

                                        $statusIcon = match($asset->status) {
                                            'assigned' => 'bi-person-check',
                                            'available' => 'bi-check-circle',
                                            'under_repair' => 'bi-tools',
                                            'lost' => 'bi-question-circle',
                                            'disposed' => 'bi-trash',
                                            'retired' => 'bi-archive',
                                            default => 'bi-info-circle',
                                        };

                                    @endphp


                                    <tr>

                                        {{-- ==========================================
                                             ASSET
                                        =========================================== --}}

                                        <td>

                                            <a
                                                href="{{ route('assets.show', $asset) }}"
                                                class="text-decoration-none"
                                            >

                                                <div class="asset-code">
                                                    {{ $asset->asset_code }}
                                                </div>

                                                <div class="text-muted small asset-name">
                                                    {{ $asset->asset_name }}
                                                </div>

                                                @if($asset->serial_number)

                                                    <div class="text-muted"
                                                         style="font-size:.72rem; margin-top:3px;">

                                                        S/N:
                                                        {{ $asset->serial_number }}

                                                    </div>

                                                @endif

                                            </a>

                                        </td>


                                        {{-- ==========================================
                                             CATEGORY
                                        =========================================== --}}

                                        <td>

                                            @if($asset->category)

                                                <div class="category-name">
                                                    {{ $asset->category->name }}
                                                </div>

                                            @else

                                                <span class="text-muted">
                                                    —
                                                </span>

                                            @endif

                                        </td>


                                        {{-- ==========================================
                                             MANAGEMENT AREA
                                        =========================================== --}}

                                        <td>

                                            @php
                                                $area = $asset->category?->responsible_officer;
                                            @endphp

                                            @if($area)

                                                <span class="management-area">

                                                    <i class="bi bi-shield-check"></i>

                                                    {{ $managementAreas[$area] ?? ucfirst($area) }}

                                                </span>

                                            @else

                                                <span class="text-muted">
                                                    —
                                                </span>

                                            @endif

                                        </td>


                                        {{-- ==========================================
                                             DEPARTMENT
                                        =========================================== --}}

                                        <td>

                                            @if($asset->department)

                                                <div class="department-name">
                                                    {{ $asset->department->name }}
                                                </div>

                                                @if($asset->department->code)

                                                    <div class="department-code">
                                                        {{ $asset->department->code }}
                                                    </div>

                                                @endif

                                            @else

                                                <span class="unassigned-text">
                                                    Unassigned
                                                </span>

                                            @endif

                                        </td>


                                        {{-- ==========================================
                                             CURRENT HOLDER
                                        =========================================== --}}

                                        <td>

                                            @if($asset->status === 'assigned' && $asset->employee)

                                                <div class="employee-name">

                                                    {{ $asset->employee->first_name }}
                                                    {{ $asset->employee->last_name }}

                                                </div>

                                                @if($asset->employee->employee_number)

                                                    <div class="employee-number">

                                                        {{ $asset->employee->employee_number }}

                                                    </div>

                                                @endif

                                            @else

                                                <span class="unassigned-text">

                                                    <i class="bi bi-person-dash me-1"></i>

                                                    No current holder

                                                </span>

                                            @endif

                                        </td>


                                        {{-- ==========================================
                                             ASSIGNMENT INFORMATION
                                        =========================================== --}}

                                        <td>

                                            @if($currentAssignment)

                                                <div class="assigned-by">

                                                    @if($currentAssignment->assignedBy)

                                                        {{ $currentAssignment->assignedBy->name }}

                                                    @else

                                                        <span class="text-muted">
                                                            Unknown
                                                        </span>

                                                    @endif

                                                </div>

                                                @if($currentAssignment->assigned_at)

                                                    <div class="date-time">

                                                        {{ $currentAssignment->assigned_at->format('d M Y') }}

                                                        ·

                                                        {{ $currentAssignment->assigned_at->format('H:i') }}

                                                    </div>

                                                @endif

                                            @else

                                                <span class="unassigned-text">
                                                    No active assignment
                                                </span>

                                            @endif

                                        </td>


                                        {{-- ==========================================
                                             STATUS
                                        =========================================== --}}

                                        <td>

                                            <span class="status-badge {{ $statusClass }}">

                                                <i class="bi {{ $statusIcon }}"></i>

                                                {{ $statusLabel }}

                                            </span>

                                        </td>


                                        {{-- ==========================================
                                             ACTIONS
                                        =========================================== --}}

                                        <td class="text-end">

                                            <div class="action-buttons">

                                                {{-- VIEW ASSET --}}

                                                <a
                                                    href="{{ route('assets.show', $asset) }}"
                                                    class="btn btn-sm btn-outline-secondary"
                                                    title="View Asset"
                                                >

                                                    <i class="bi bi-eye"></i>

                                                </a>


                                                {{-- RETURN ASSET --}}
                                                {{-- ONLY OPERATIONAL OFFICERS --}}
                                                {{-- ONLY CURRENTLY ASSIGNED ASSETS --}}

                                                @if(
                                                    $asset->status === 'assigned'
                                                    &&
                                                    in_array(auth()->user()->role, [
                                                        'hardware_officer',
                                                        'administration_officer'
                                                    ])
                                                )

                                                    <form
                                                        method="POST"
                                                        action="{{ route('assets.return', $asset) }}"
                                                        onsubmit="return confirm('Return this asset and close the current assignment?');"
                                                        class="d-inline"
                                                    >

                                                        @csrf

                                                        <button
                                                            type="submit"
                                                            class="btn btn-sm return-button"
                                                            title="Return Asset"
                                                        >

                                                            <i class="bi bi-arrow-return-left me-1"></i>

                                                            Return

                                                        </button>

                                                    </form>

                                                @endif

                                            </div>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @else

                    {{-- =================================================
                         EMPTY STATE
                    ================================================== --}}

                    <div class="empty-state">

                        <i class="bi bi-search"></i>

                        <h5>
                            No assets found
                        </h5>

                        <p class="mb-0">
                            No assets match the current search and filter
                            criteria.
                        </p>

                    </div>

                @endif

            </div>


            {{-- =====================================================
                 PAGINATION
            ====================================================== --}}

            @if($assets->hasPages())

                <div class="card-footer bg-white border-0">

                    {{ $assets->links() }}

                </div>

            @endif

        </div>

    </div>

</x-app-layout>