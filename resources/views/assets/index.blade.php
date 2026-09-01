<x-app-layout>
    <x-slot name="title">
        Assets
    </x-slot>

```
{{-- =========================================================
    PAGE HEADER
========================================================== --}}

<div class="crb-page-title d-flex justify-content-between align-items-center">

    <div>
        <h1>
            @if (auth()->user()->role === 'hardware_officer')
                Hardware Assets
            @elseif (auth()->user()->role === 'administration_officer')
                Administration Assets
            @else
                All Assets
            @endif
        </h1>

        <p>
            @if (auth()->user()->role === 'system_admin')
                Read-only system-wide asset monitoring.
            @elseif (auth()->user()->role === 'hardware_officer')
                Manage and monitor CRB hardware assets.
            @else
                Manage and monitor CRB administration assets.
            @endif
        </p>
    </div>


    {{-- SYSTEM ADMINISTRATOR CANNOT REGISTER ASSETS --}}

    @if (
        auth()->user()->role === 'hardware_officer' ||
        auth()->user()->role === 'administration_officer'
    )

        <a
            href="{{ route('assets.create') }}"
            class="btn btn-crb"
        >
            <i class="bi bi-plus-lg me-1"></i>
            Add Asset
        </a>

    @endif

</div>


{{-- =========================================================
    SUCCESS MESSAGE
========================================================== --}}

@if (session('success'))

    <div class="alert alert-success mb-4">
        <i class="bi bi-check-circle me-2"></i>
        {{ session('success') }}
    </div>

@endif


{{-- =========================================================
    SYSTEM ADMINISTRATOR READ-ONLY NOTICE
========================================================== --}}

@if (auth()->user()->role === 'system_admin')

    <div class="alert alert-info mb-4">

        <i class="bi bi-eye me-2"></i>

        <strong>Read-only monitoring:</strong>

        You are viewing the asset register for oversight purposes.
        Asset registration, editing, assignment and retirement are
        performed by the responsible management officers.

    </div>

@endif


{{-- =========================================================
    ASSET SUMMARY
========================================================== --}}

<div class="row g-3 mb-4">

    {{-- TOTAL --}}

    <div class="col-xl col-md-6">

        <div class="crb-stat-card">

            <div class="crb-stat-top">

                <div class="crb-stat-icon">
                    <i class="bi bi-box-seam"></i>
                </div>

                <span class="crb-stat-change">
                    Total
                </span>

            </div>

            <div class="crb-stat-value">
                {{ $totalAssets }}
            </div>

            <div class="crb-stat-label">
                Total Assets
            </div>

        </div>

    </div>


    {{-- AVAILABLE --}}

    <div class="col-xl col-md-6">

        <div class="crb-stat-card">

            <div class="crb-stat-top">

                <div class="crb-stat-icon">
                    <i class="bi bi-check2-circle"></i>
                </div>

                <span class="crb-stat-change">
                    Available
                </span>

            </div>

            <div class="crb-stat-value">
                {{ $availableAssets }}
            </div>

            <div class="crb-stat-label">
                Available Assets
            </div>

        </div>

    </div>


    {{-- ASSIGNED --}}

    <div class="col-xl col-md-6">

        <div class="crb-stat-card">

            <div class="crb-stat-top">

                <div class="crb-stat-icon">
                    <i class="bi bi-person-check"></i>
                </div>

                <span class="crb-stat-change">
                    Assigned
                </span>

            </div>

            <div class="crb-stat-value">
                {{ $assignedAssets }}
            </div>

            <div class="crb-stat-label">
                Assigned Assets
            </div>

        </div>

    </div>


    {{-- REPAIR --}}

    <div class="col-xl col-md-6">

        <div class="crb-stat-card">

            <div class="crb-stat-top">

                <div class="crb-stat-icon">
                    <i class="bi bi-tools"></i>
                </div>

                <span class="crb-stat-change">
                    Attention
                </span>

            </div>

            <div class="crb-stat-value">
                {{ $repairAssets }}
            </div>

            <div class="crb-stat-label">
                Under Repair
            </div>

        </div>

    </div>


    {{-- RETIRED --}}

    <div class="col-xl col-md-6">

        <div class="crb-stat-card">

            <div class="crb-stat-top">

                <div class="crb-stat-icon">
                    <i class="bi bi-archive"></i>
                </div>

                <span class="crb-stat-change">
                    Retired
                </span>

            </div>

            <div class="crb-stat-value">
                {{ $retiredAssets }}
            </div>

            <div class="crb-stat-label">
                Retired Assets
            </div>

        </div>

    </div>

</div>


{{-- =========================================================
    SEARCH & FILTERS
========================================================== --}}

<div class="crb-card mb-4">

    <div class="crb-card-header">

        <div>

            <h5>
                <i class="bi bi-funnel me-2"></i>
                Asset Register
            </h5>

            <small>
                Search and filter registered assets
            </small>

        </div>

        <div class="text-muted small">

            {{ $assets->total() }}

            {{ $assets->total() === 1 ? 'asset' : 'assets' }}

        </div>

    </div>


    <div class="crb-card-body">

        <form
            method="GET"
            action="{{ route('assets.index') }}"
        >

            <div class="row g-3 align-items-end">


                {{-- SEARCH --}}

                <div class="col-lg-4">

                    <label class="form-label">
                        Search
                    </label>

                    <div class="crb-search w-100">

                        <i class="bi bi-search"></i>

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Asset code, name, serial number..."
                        >

                    </div>

                </div>


                {{-- STATUS --}}

                <div class="col-lg-2 col-md-4">

                    <label class="form-label">
                        Status
                    </label>

                    <select
                        name="status"
                        class="form-select"
                    >

                        <option value="">
                            All Statuses
                        </option>

                        <option
                            value="available"
                            {{ request('status') === 'available' ? 'selected' : '' }}
                        >
                            Available
                        </option>

                        <option
                            value="assigned"
                            {{ request('status') === 'assigned' ? 'selected' : '' }}
                        >
                            Assigned
                        </option>

                        <option
                            value="under_repair"
                            {{ request('status') === 'under_repair' ? 'selected' : '' }}
                        >
                            Under Repair
                        </option>

                        <option
                            value="disposed"
                            {{ request('status') === 'disposed' ? 'selected' : '' }}
                        >
                            Disposed
                        </option>

                        <option
                            value="lost"
                            {{ request('status') === 'lost' ? 'selected' : '' }}
                        >
                            Lost
                        </option>

                        <option
                            value="retired"
                            {{ request('status') === 'retired' ? 'selected' : '' }}
                        >
                            Retired
                        </option>

                    </select>

                </div>


                {{-- CATEGORY --}}

                <div class="col-lg-2 col-md-4">

                    <label class="form-label">
                        Category
                    </label>

                    <select
                        name="category"
                        class="form-select"
                    >

                        <option value="">
                            All Categories
                        </option>

                        @foreach ($categories as $category)

                            <option
                                value="{{ $category->id }}"
                                {{ request('category') == $category->id ? 'selected' : '' }}
                            >
                                {{ $category->name }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- DEPARTMENT --}}

                <div class="col-lg-2 col-md-4">

                    <label class="form-label">
                        Department
                    </label>

                    <select
                        name="department"
                        class="form-select"
                    >

                        <option value="">
                            All Departments
                        </option>

                        @foreach ($departments as $department)

                            <option
                                value="{{ $department->id }}"
                                {{ request('department') == $department->id ? 'selected' : '' }}
                            >
                                {{ $department->name }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- BUTTONS --}}

                <div class="col-lg-2">

                    <div class="d-flex gap-2">

                        <button
                            type="submit"
                            class="btn btn-crb flex-grow-1"
                        >
                            <i class="bi bi-search me-1"></i>
                            Search
                        </button>

                        <a
                            href="{{ route('assets.index') }}"
                            class="crb-action-btn"
                            title="Clear filters"
                        >
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>

                    </div>

                </div>

            </div>

        </form>

    </div>

</div>


{{-- =========================================================
    ASSET TABLE
========================================================== --}}

<div class="crb-card">

    @if ($assets->count())

        <div class="table-responsive">

            <table class="table crb-table align-middle mb-0">

                <thead>

                    <tr>

                        <th>
                            Asset Code
                        </th>

                        <th>
                            Asset
                        </th>

                        <th>
                            Category
                        </th>

                        <th>
                            Department
                        </th>

                        <th>
                            Employee
                        </th>

                        <th>
                            Status
                        </th>

                        <th class="text-end">
                            Actions
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @foreach ($assets as $asset)

                        <tr>

                            {{-- ASSET CODE --}}

                            <td>

                                <a
                                    href="{{ route('assets.show', $asset) }}"
                                    class="crb-asset-code"
                                >
                                    {{ $asset->asset_code }}
                                </a>

                            </td>


                            {{-- ASSET --}}

                            <td>

                                <div class="fw-semibold">
                                    {{ $asset->asset_name }}
                                </div>

                                @if ($asset->serial_number)

                                    <div class="crb-muted">

                                        SN:
                                        {{ $asset->serial_number }}

                                    </div>

                                @endif

                            </td>


                            {{-- CATEGORY --}}

                            <td>
                                {{ $asset->category?->name ?? '—' }}
                            </td>


                            {{-- DEPARTMENT --}}

                            <td>
                                {{ $asset->department?->name ?? 'Unassigned' }}
                            </td>


                            {{-- EMPLOYEE --}}

                            <td>

                                @if ($asset->employee)

                                    <div class="crb-employee">

                                        <div class="crb-avatar">

                                            {{ strtoupper(
                                                substr(
                                                    $asset->employee->first_name,
                                                    0,
                                                    1
                                                )
                                            ) }}

                                        </div>

                                        <span>

                                            {{ $asset->employee->first_name }}
                                            {{ $asset->employee->last_name }}

                                        </span>

                                    </div>

                                @else

                                    <span class="crb-muted">
                                        Unassigned
                                    </span>

                                @endif

                            </td>


                            {{-- STATUS --}}

                            <td>

                                @php

                                    $statusClass = match($asset->status) {

                                        'available' => 'available',

                                        'assigned' => 'assigned',

                                        'under_repair' => 'repair',

                                        'retired' => 'retired',

                                        'disposed' => 'disposed',

                                        'lost' => 'lost',

                                        default => 'default',

                                    };

                                @endphp


                                <span class="crb-status {{ $statusClass }}">

                                    <span class="crb-status-dot"></span>

                                    {{ ucwords(
                                        str_replace(
                                            '_',
                                            ' ',
                                            $asset->status
                                        )
                                    ) }}

                                </span>

                            </td>


                            {{-- =================================================
                                ACTIONS
                            ================================================== --}}

                            <td class="text-end">

                                <div class="crb-actions">

                                    {{-- VIEW
                                         Everyone with access can view. --}}

                                    <a
                                        href="{{ route('assets.show', $asset) }}"
                                        class="crb-action-btn"
                                        title="View asset"
                                    >
                                        <i class="bi bi-eye"></i>
                                    </a>


                                    {{-- OPERATIONAL ACTIONS
                                         System Administrator MUST NOT
                                         see or use these. --}}

                                    @if (
                                        auth()->user()->role === 'hardware_officer' ||
                                        auth()->user()->role === 'administration_officer'
                                    )

                                        {{-- EDIT --}}

                                        <a
                                            href="{{ route('assets.edit', $asset) }}"
                                            class="crb-action-btn"
                                            title="Edit asset"
                                        >
                                            <i class="bi bi-pencil"></i>
                                        </a>


                                        {{-- RETIRE --}}

                                        @if ($asset->status !== 'retired')

                                            <form
                                                action="{{ route('assets.destroy', $asset) }}"
                                                method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to retire this asset?');"
                                            >

                                                @csrf

                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="crb-action-btn danger"
                                                    title="Retire asset"
                                                >
                                                    <i class="bi bi-archive"></i>
                                                </button>

                                            </form>

                                        @endif

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>


        {{-- =================================================
            PAGINATION
        ================================================== --}}

        @if ($assets->hasPages())

            <div class="crb-pagination">
                {{ $assets->links() }}
            </div>

        @endif


    @else

        {{-- =================================================
            EMPTY STATE
        ================================================== --}}

        <div class="crb-empty-state">

            <div class="crb-empty-icon">
                <i class="bi bi-box-seam"></i>
            </div>

            <h4>
                No assets found
            </h4>

            <p>
                No assets match your current search or filters.
            </p>

            <a
                href="{{ route('assets.index') }}"
                class="btn btn-crb"
            >
                Clear Filters
            </a>

        </div>

    @endif

</div>
```

</x-app-layout>
