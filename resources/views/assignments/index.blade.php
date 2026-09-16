<x-app-layout>

    <x-slot name="title">
        assignments
    </x-slot>

<style>
    .assignments-page {
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
        box-shadow: 0 4px 18px rgba(0,0,0,.06);
        height: 100%;
    }

    .stat-label {
        color: #6c757d;
        font-size: .85rem;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    .stat-number {
        font-size: 1.8rem;
        font-weight: 700;
        color: #246d69;
    }

    .filter-card,
    .table-card {
        border: 0;
        border-radius: 14px;
        box-shadow: 0 4px 18px rgba(0,0,0,.06);
    }

    .table-card {
        overflow: hidden;
    }

    .table thead th {
        background: #246d69;
        color: white;
        font-size: .82rem;
        white-space: nowrap;
        vertical-align: middle;
    }

    .table tbody td {
        vertical-align: middle;
        font-size: .9rem;
    }

    .asset-code {
        font-weight: 700;
        color: #246d69;
    }

    .employee-name {
        font-weight: 600;
    }

    .badge-active {
        background: #c9ff83;
        color: #1d4d49;
    }

    .badge-returned {
        background: #e9ecef;
        color: #495057;
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

    .empty-state {
        padding: 60px 20px;
        text-align: center;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 3rem;
        color: #246d69;
        margin-bottom: 15px;
    }
</style>

<div class="container-fluid assignments-page">

    {{-- PAGE HEADER --}}
    <div class="page-header">

        <div>
            <h2 class="page-title">
                <i class="bi bi-arrow-left-right me-2"></i>
                Assignments Register
            </h2>

            <p class="page-subtitle">
                Track asset assignment history, current holders and returned assets.
            </p>
        </div>

        <a href="{{ route('assets.index') }}" class="btn btn-crb">
            <i class="bi bi-box-seam me-1"></i>
            View Assets
        </a>

    </div>


    {{-- FLASH MESSAGES --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-circle me-2"></i>
            {{ session('error') }}

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif


    {{-- STATISTICS --}}
    <div class="row g-4 mb-4">

        <div class="col-md-4">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="stat-label">Total Assignments</div>
                    <div class="stat-number">
                        {{ number_format($totalAssignments) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="stat-label">Active Assignments</div>
                    <div class="stat-number">
                        {{ number_format($activeAssignments) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card">
                <div class="card-body">
                    <div class="stat-label">Returned Assets</div>
                    <div class="stat-number">
                        {{ number_format($returnedAssignments) }}
                    </div>
                </div>
            </div>
        </div>

    </div>


    {{-- FILTERS --}}
    <div class="card filter-card mb-4">

        <div class="card-body">

            <form method="GET"
                  action="{{ route('assignments.index') }}">

                <div class="row g-3 align-items-end">

                    {{-- SEARCH --}}
                    <div class="col-lg-5">

                        <label class="form-label fw-semibold">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            value="{{ request('search') }}"
                            placeholder="Asset code, asset name, serial number, employee..."
                        >

                    </div>


                    {{-- DEPARTMENT --}}
                    <div class="col-lg-3">

                        <label class="form-label fw-semibold">
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
                                    @selected(request('department_id') == $department->id)
                                >
                                    {{ $department->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- STATUS --}}
                    <div class="col-lg-2">

                        <label class="form-label fw-semibold">
                            Status
                        </label>

                        <select name="status"
                                class="form-select">

                            <option value="">
                                All
                            </option>

                            <option
                                value="active"
                                @selected(request('status') === 'active')
                            >
                                Active
                            </option>

                            <option
                                value="returned"
                                @selected(request('status') === 'returned')
                            >
                                Returned
                            </option>

                        </select>

                    </div>


                    {{-- BUTTONS --}}
                    <div class="col-lg-2 d-flex gap-2">

                        <button type="submit"
                                class="btn btn-crb flex-fill">
                            <i class="bi bi-search"></i>
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


    {{-- ASSIGNMENTS TABLE --}}
    <div class="card table-card">

        <div class="card-body p-0">

            @if($assignments->count())

                <div class="table-responsive">

                    <table class="table table-hover mb-0">

                        <thead>

                            <tr>
                                <th>Asset</th>
                                <th>Employee</th>
                                <th>Department</th>
                                <th>Assigned By</th>
                                <th>Assigned Date</th>
                                <th>Returned Date</th>
                                <th>Status</th>
                                <th class="text-end">Action</th>
                            </tr>

                        </thead>

                        <tbody>

                            @foreach($assignments as $assignment)

                                <tr>

                                    {{-- ASSET --}}
                                    <td>

                                        @if($assignment->asset)

                                            <a
                                                href="{{ route('assets.show', $assignment->asset) }}"
                                                class="text-decoration-none"
                                            >

                                                <div class="asset-code">
                                                    {{ $assignment->asset->asset_code }}
                                                </div>

                                                <div class="text-muted small">
                                                    {{ $assignment->asset->asset_name }}
                                                </div>

                                            </a>

                                        @else

                                            <span class="text-muted">
                                                Asset unavailable
                                            </span>

                                        @endif

                                    </td>


                                    {{-- EMPLOYEE --}}
                                    <td>

                                        @if($assignment->employee)

                                            <div class="employee-name">
                                                {{ $assignment->employee->first_name }}
                                                {{ $assignment->employee->last_name }}
                                            </div>

                                            <div class="text-muted small">
                                                {{ $assignment->employee->employee_number }}
                                            </div>

                                        @else

                                            <span class="text-muted">
                                                Employee unavailable
                                            </span>

                                        @endif

                                    </td>


                                    {{-- DEPARTMENT --}}
                                    <td>

                                        @if($assignment->department)

                                            {{ $assignment->department->name }}

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- ASSIGNED BY --}}
                                    <td>

                                        {{ $assignment->assignedBy?->name ?? 'Unknown' }}

                                    </td>


                                    {{-- ASSIGNED DATE --}}
                                    <td>

                                        {{ $assignment->assigned_at?->format('d M Y') }}

                                        <div class="text-muted small">
                                            {{ $assignment->assigned_at?->format('H:i') }}
                                        </div>

                                    </td>


                                    {{-- RETURNED DATE --}}
                                    <td>

                                        @if($assignment->returned_at)

                                            {{ $assignment->returned_at->format('d M Y') }}

                                            <div class="text-muted small">
                                                {{ $assignment->returned_at->format('H:i') }}
                                            </div>

                                        @else

                                            <span class="text-muted">
                                                —
                                            </span>

                                        @endif

                                    </td>


                                    {{-- STATUS --}}
                                    <td>

                                        @if(is_null($assignment->returned_at))

                                            <span class="badge badge-active">
                                                Active
                                            </span>

                                        @else

                                            <span class="badge badge-returned">
                                                Returned
                                            </span>

                                        @endif

                                    </td>


                                    {{-- ACTION --}}
                                    <td class="text-end">

                                        <div class="d-flex justify-content-end gap-1">

                                            @if($assignment->asset)

                                                <a
                                                    href="{{ route('assets.show', $assignment->asset) }}"
                                                    class="btn btn-sm btn-outline-secondary"
                                                    title="View Asset"
                                                >
                                                    <i class="bi bi-eye"></i>
                                                </a>

                                            @endif

                                            @if(
                                                is_null($assignment->returned_at)
                                                && $assignment->asset
                                                && in_array(auth()->user()->role, [
                                                    'hardware_officer',
                                                    'administration_officer'
                                                ])
                                            )

                                                <form
                                                    method="POST"
                                                    action="{{ route('assets.return', $assignment->asset) }}"
                                                    onsubmit="return confirm('Return this asset and close the current assignment?');"
                                                >

                                                    @csrf

                                                    <button
                                                        type="submit"
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Return Asset"
                                                    >
                                                        <i class="bi bi-arrow-return-left"></i>
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

                <div class="empty-state">

                    <i class="bi bi-clipboard-x"></i>

                    <h5>No assignment records found</h5>

                    <p class="mb-0">
                        There are no assignment records matching your current filters.
                    </p>

                </div>

            @endif

        </div>


        {{-- PAGINATION --}}
        @if($assignments->hasPages())

            <div class="card-footer bg-white border-0">

                {{ $assignments->links() }}

            </div>

        @endif

    </div>

</div>

</x-layouts.app>