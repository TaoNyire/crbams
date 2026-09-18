```blade
<x-app-layout>

    <div class="container-fluid py-4">

        {{-- Page Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Asset Allocation Register</h2>
                <p class="text-muted mb-0">
                    Monitor current asset allocation, availability and operational status.
                </p>
            </div>
        </div>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close">
                </button>
            </div>
        @endif

        {{-- Error Message --}}
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                {{ session('error') }}

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close">
                </button>
            </div>
        @endif

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <div class="fw-bold mb-2">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Please correct the following errors:
                </div>

                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Register Information --}}
        <div class="alert alert-info border-0 shadow-sm mb-4">
            <div class="d-flex align-items-start">
                <i class="bi bi-info-circle fs-5 me-2"></i>

                <div>
                    <strong>Asset Operations:</strong>
                    This register shows assets within your permitted management
                    area, including assigned, available and under-repair assets.
                </div>
            </div>
        </div>

        {{-- Statistics --}}
        <div class="row g-4 mb-4">

            {{-- Total --}}
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-start">

                            <div>
                                <div class="text-muted small mb-1">
                                    Total Assets
                                </div>

                                <h3 class="fw-bold mb-1">
                                    {{ $totalAssets }}
                                </h3>

                                <div class="small text-muted">
                                    Assets in this register
                                </div>
                            </div>

                            <div class="fs-3 text-primary">
                                <i class="bi bi-box-seam"></i>
                            </div>

                        </div>

                    </div>
                </div>
            </div>

            {{-- Assigned --}}
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-start">

                            <div>
                                <div class="text-muted small mb-1">
                                    Assigned
                                </div>

                                <h3 class="fw-bold mb-1">
                                    {{ $assignedAssets }}
                                </h3>

                                <div class="small text-muted">
                                    Currently allocated
                                </div>
                            </div>

                            <div class="fs-3 text-success">
                                <i class="bi bi-person-check"></i>
                            </div>

                        </div>

                    </div>
                </div>
            </div>

            {{-- Available --}}
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-start">

                            <div>
                                <div class="text-muted small mb-1">
                                    Available / Idle
                                </div>

                                <h3 class="fw-bold mb-1">
                                    {{ $availableAssets }}
                                </h3>

                                <div class="small text-muted">
                                    Ready for allocation
                                </div>
                            </div>

                            <div class="fs-3 text-info">
                                <i class="bi bi-box-arrow-in-down"></i>
                            </div>

                        </div>

                    </div>
                </div>
            </div>

            {{-- Under Repair --}}
            <div class="col-xl-3 col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-start">

                            <div>
                                <div class="text-muted small mb-1">
                                    Under Repair
                                </div>

                                <h3 class="fw-bold mb-1">
                                    {{ $underRepairAssets }}
                                </h3>

                                <div class="small text-muted">
                                    Currently unavailable
                                </div>
                            </div>

                            <div class="fs-3 text-warning">
                                <i class="bi bi-tools"></i>
                            </div>

                        </div>

                    </div>
                </div>
            </div>

        </div>

        {{-- Retired --}}
        <div class="row g-4 mb-4">

            <div class="col-xl-3 col-md-6">

                <div class="card border-0 shadow-sm h-100">

                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-start">

                            <div>
                                <div class="text-muted small mb-1">
                                    Retired
                                </div>

                                <h3 class="fw-bold mb-1">
                                    {{ $retiredAssets }}
                                </h3>

                                <div class="small text-muted">
                                    Removed from active use
                                </div>
                            </div>

                            <div class="fs-3 text-secondary">
                                <i class="bi bi-archive"></i>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- Filters --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white py-3">

                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-funnel me-2"></i>
                    Search & Filter Assets
                </h5>

            </div>

            <div class="card-body">

                <form method="GET"
                      action="{{ route('assignments.index') }}">

                    <div class="row g-3">

                        {{-- Search --}}
                        <div class="col-lg-4">

                            <label for="search"
                                   class="form-label fw-semibold">
                                Search Assets
                            </label>

                            <input
                                type="text"
                                id="search"
                                name="search"
                                value="{{ request('search') }}"
                                class="form-control"
                                placeholder="Asset code, name, serial, employee..."
                            >

                        </div>

                        {{-- Management Area --}}
                        @if (auth()->user()->role === 'system_admin')

                            <div class="col-lg-3">

                                <label for="management_area"
                                       class="form-label fw-semibold">
                                    Management Area
                                </label>

                                <select
                                    id="management_area"
                                    name="management_area"
                                    class="form-select"
                                >

                                    <option value="">
                                        All Management Areas
                                    </option>

                                    @foreach ($managementAreas as $key => $label)

                                        <option
                                            value="{{ $key }}"
                                            {{ request('management_area') === $key ? 'selected' : '' }}
                                        >
                                            {{ $label }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>

                        @else

                            <div class="col-lg-3">

                                <label class="form-label fw-semibold">
                                    Management Area
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    value="{{ ucfirst(auth()->user()->management_area) }}"
                                    readonly
                                >

                            </div>

                        @endif

                        {{-- Department --}}
                        <div class="col-lg-3">

                            <label for="department_id"
                                   class="form-label fw-semibold">
                                Department
                            </label>

                            <select
                                id="department_id"
                                name="department_id"
                                class="form-select"
                            >

                                <option value="">
                                    All Departments
                                </option>

                                @foreach ($departments as $department)

                                    <option
                                        value="{{ $department->id }}"
                                        {{ (string) request('department_id') === (string) $department->id ? 'selected' : '' }}
                                    >
                                        {{ $department->name }}
                                    </option>

                                @endforeach

                            </select>

                        </div>

                        {{-- Status --}}
                        <div class="col-lg-2">

                            <label for="status"
                                   class="form-label fw-semibold">
                                Asset Status
                            </label>

                            <select
                                id="status"
                                name="status"
                                class="form-select"
                            >

                                <option value="">
                                    All Statuses
                                </option>

                                <option value="assigned"
                                    {{ request('status') === 'assigned' ? 'selected' : '' }}>
                                    Assigned
                                </option>

                                <option value="available"
                                    {{ request('status') === 'available' ? 'selected' : '' }}>
                                    Available / Idle
                                </option>

                                <option value="under_repair"
                                    {{ request('status') === 'under_repair' ? 'selected' : '' }}>
                                    Under Repair
                                </option>

                                <option value="lost"
                                    {{ request('status') === 'lost' ? 'selected' : '' }}>
                                    Lost
                                </option>

                                <option value="disposed"
                                    {{ request('status') === 'disposed' ? 'selected' : '' }}>
                                    Disposed
                                </option>

                                <option value="retired"
                                    {{ request('status') === 'retired' ? 'selected' : '' }}>
                                    Retired
                                </option>

                            </select>

                        </div>

                        {{-- Buttons --}}
                        <div class="col-12">

                            <div class="d-flex gap-2">

                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                >
                                    <i class="bi bi-search me-1"></i>
                                    Apply Filters
                                </button>

                                <a
                                    href="{{ route('assignments.index') }}"
                                    class="btn btn-outline-secondary"
                                >
                                    <i class="bi bi-x-circle me-1"></i>
                                    Clear
                                </a>

                            </div>

                        </div>

                    </div>

                </form>

            </div>

        </div>

        {{-- Allocation Register --}}
        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white py-3">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h5 class="mb-1 fw-bold">
                            Current Asset Allocation
                        </h5>

                        <small class="text-muted">
                            Current allocation and operational status of assets.
                        </small>

                    </div>

                </div>

            </div>

            <div class="card-body p-0">

                @if ($assets->count())

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th>Asset</th>
                                    <th>Category</th>
                                    <th>Management Area</th>
                                    <th>Department</th>
                                    <th>Current Holder</th>
                                    <th>Assignment</th>
                                    <th>Status</th>
                                    <th class="text-end">Action</th>

                                </tr>

                            </thead>

                            <tbody>

                                @foreach ($assets as $asset)

                                    @php
                                        $activeAssignment = $asset->activeAssignment;
                                    @endphp

                                    <tr>

                                        {{-- Asset --}}
                                        <td>

                                            <a
                                                href="{{ route('assets.show', $asset) }}"
                                                class="text-decoration-none fw-bold"
                                            >
                                                {{ $asset->asset_code }}
                                            </a>

                                            <div class="small text-muted">
                                                {{ $asset->asset_name }}
                                            </div>

                                            @if ($asset->serial_number)

                                                <div class="small text-muted">
                                                    S/N: {{ $asset->serial_number }}
                                                </div>

                                            @endif

                                        </td>

                                        {{-- Category --}}
                                        <td>
                                            {{ $asset->category?->name ?? '—' }}
                                        </td>

                                        {{-- Management Area --}}
                                        <td>

                                            <span class="badge bg-light text-dark">

                                                {{ ucfirst(
                                                    $asset->category?->responsible_officer ?? '—'
                                                ) }}

                                            </span>

                                        </td>

                                        {{-- Department --}}
                                        <td>

                                            @if ($asset->department)

                                                {{ $asset->department->name }}

                                                @if ($asset->department->code)

                                                    <div class="small text-muted">
                                                        {{ $asset->department->code }}
                                                    </div>

                                                @endif

                                            @else

                                                <span class="text-muted fst-italic">
                                                    Unassigned
                                                </span>

                                            @endif

                                        </td>

                                        {{-- Current Holder --}}
                                        <td>

                                            @if ($activeAssignment?->employee)

                                                <strong>
                                                    {{ $activeAssignment->employee->first_name }}
                                                    {{ $activeAssignment->employee->last_name }}
                                                </strong>

                                                <div class="small text-muted">
                                                    {{ $activeAssignment->employee->employee_number }}
                                                </div>

                                            @else

                                                <span class="text-muted fst-italic">
                                                    No current holder
                                                </span>

                                            @endif

                                        </td>

                                        {{-- Assignment Information --}}
                                        <td>

                                            @if ($activeAssignment)

                                                <div class="fw-semibold">
                                                    {{ $activeAssignment->assignedBy?->name ?? '—' }}
                                                </div>

                                                <div class="small text-muted">
                                                    Assigned:
                                                    {{ $activeAssignment->assigned_at?->format('d M Y · H:i') ?? '—' }}
                                                </div>

                                            @else

                                                <span class="text-muted fst-italic">
                                                    No active assignment
                                                </span>

                                            @endif

                                        </td>

                                        {{-- Status --}}
                                        <td>

                                            @switch($asset->status)

                                                @case('assigned')

                                                    <span class="badge bg-success">
                                                        Assigned
                                                    </span>

                                                    @break

                                                @case('available')

                                                    <span class="badge bg-info text-dark">
                                                        Available / Idle
                                                    </span>

                                                    @break

                                                @case('under_repair')

                                                    <span class="badge bg-warning text-dark">
                                                        Under Repair
                                                    </span>

                                                    @break

                                                @case('lost')

                                                    <span class="badge bg-danger">
                                                        Lost
                                                    </span>

                                                    @break

                                                @case('disposed')

                                                    <span class="badge bg-secondary">
                                                        Disposed
                                                    </span>

                                                    @break

                                                @case('retired')

                                                    <span class="badge bg-dark">
                                                        Retired
                                                    </span>

                                                    @break

                                                @default

                                                    <span class="badge bg-secondary">
                                                        {{ $asset->status_label }}
                                                    </span>

                                            @endswitch

                                        </td>

                                        {{-- Action --}}
                                        <td class="text-end">

                                            @if ($asset->status === 'assigned')

                                                @if (auth()->user()->role !== 'system_admin')

                                                    <a
                                                        href="{{ route('assets.return.form', $asset) }}"
                                                        class="btn btn-warning btn-sm"
                                                        title="Return Asset"
                                                    >
                                                        <i class="bi bi-box-arrow-in-left me-1"></i>
                                                        Return
                                                    </a>

                                                @else

                                                    <span class="text-muted small">
                                                        View Only
                                                    </span>

                                                @endif

                                            @else

                                                <span class="text-muted">
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

                    <div class="text-center py-5">

                        <div class="fs-1 text-muted mb-3">
                            <i class="bi bi-inboxes"></i>
                        </div>

                        <h5 class="fw-bold">
                            No Assets Found
                        </h5>

                        <p class="text-muted mb-0">
                            No assets match the current search and filter criteria.
                        </p>

                    </div>

                @endif

            </div>

            {{-- Pagination --}}
            @if ($assets->hasPages())

                <div class="card-footer bg-white">

                    {{ $assets->links() }}

                </div>

            @endif

        </div>

    </div>

</x-app-layout>
```
