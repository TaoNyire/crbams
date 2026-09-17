<x-app-layout>

    <x-slot name="title">
        {{ $asset->asset_name }}
    </x-slot>


    {{-- =========================================================
        PAGE HEADER
    ========================================================== --}}

    <div class="crb-page-title d-flex justify-content-between align-items-center">

        <div>
            <h1>{{ $asset->asset_name }}</h1>
            <p>{{ $asset->asset_code }}</p>
        </div>

        <div class="d-flex gap-2 flex-wrap">

            {{-- PRINT QR ASSET TAG --}}
            <a
                href="{{ route('assets.tag', $asset) }}"
                target="_blank"
                class="btn btn-outline-dark"
            >
                <i class="bi bi-qr-code me-1"></i>
                Print Asset Tag
            </a>


            {{-- OPERATIONAL OFFICERS --}}
            @if (
                auth()->user()->role === 'hardware_officer' ||
                auth()->user()->role === 'administration_officer'
            )

                <a
                    href="{{ route('assets.edit', $asset) }}"
                    class="btn btn-crb"
                >
                    <i class="bi bi-pencil me-1"></i>
                    Edit Asset
                </a>

            @endif

        </div>

    </div>


    {{-- =========================================================
        CURRENT ALLOCATION DATA
    ========================================================== --}}

    @php

        /*
        |--------------------------------------------------------------------------
        | Current Active Assignment
        |--------------------------------------------------------------------------
        |
        | An active assignment is an assignment where returned_at is NULL.
        |
        | This is the source of truth for the current holder.
        |
        */

        $activeAssignment = $asset->assignments()
            ->with([
                'employee',
                'department',
                'assignedBy',
            ])
            ->whereNull('returned_at')
            ->latest('assigned_at')
            ->first();

    @endphp


    {{-- =========================================================
        CURRENT ALLOCATION
    ========================================================== --}}

    <div class="crb-card mb-4">

        <div class="crb-card-header d-flex justify-content-between align-items-center">

            <div>
                <h5 class="mb-0">
                    <i class="bi bi-person-check me-2"></i>
                    Current Allocation
                </h5>

                <small class="text-muted">
                    Current holder and active assignment information
                </small>
            </div>


            {{-- STATUS BADGE --}}

            @if ($asset->status === 'assigned')

                <span class="badge bg-success">
                    Assigned
                </span>

            @elseif ($asset->status === 'available')

                <span class="badge bg-secondary">
                    Available / Idle
                </span>

            @elseif ($asset->status === 'under_repair')

                <span class="badge bg-warning text-dark">
                    Under Repair
                </span>

            @elseif ($asset->status === 'retired')

                <span class="badge bg-dark">
                    Retired
                </span>

            @elseif ($asset->status === 'lost')

                <span class="badge bg-danger">
                    Lost
                </span>

            @elseif ($asset->status === 'disposed')

                <span class="badge bg-dark">
                    Disposed
                </span>

            @else

                <span class="badge bg-secondary">
                    {{ str($asset->status)->replace('_', ' ')->title() }}
                </span>

            @endif

        </div>


        <div class="crb-card-body">

            @if ($activeAssignment)

                <div class="row g-4">

                    {{-- CURRENT HOLDER --}}

                    <div class="col-md-6">

                        <div class="border rounded p-3 h-100">

                            <div class="text-muted small mb-1">
                                Current Holder
                            </div>

                            <div class="fw-semibold fs-5">

                                {{ $activeAssignment->employee?->first_name }}
                                {{ $activeAssignment->employee?->last_name }}

                            </div>

                        </div>

                    </div>


                    {{-- EMPLOYEE NUMBER --}}

                    <div class="col-md-6">

                        <div class="border rounded p-3 h-100">

                            <div class="text-muted small mb-1">
                                Employee Number
                            </div>

                            <div class="fw-semibold">

                                {{ $activeAssignment->employee?->employee_number ?? 'Not specified' }}

                            </div>

                        </div>

                    </div>


                    {{-- DEPARTMENT --}}

                    <div class="col-md-6">

                        <div class="border rounded p-3 h-100">

                            <div class="text-muted small mb-1">
                                Department
                            </div>

                            <div class="fw-semibold">

                                {{ $activeAssignment->department?->name ?? 'Not specified' }}

                            </div>

                        </div>

                    </div>


                    {{-- ASSIGNED BY --}}

                    <div class="col-md-6">

                        <div class="border rounded p-3 h-100">

                            <div class="text-muted small mb-1">
                                Assigned By
                            </div>

                            <div class="fw-semibold">

                                {{ $activeAssignment->assignedBy?->name ?? 'Not specified' }}

                            </div>

                        </div>

                    </div>


                    {{-- ASSIGNED DATE --}}

                    <div class="col-md-6">

                        <div class="border rounded p-3 h-100">

                            <div class="text-muted small mb-1">
                                Assigned Date
                            </div>

                            <div class="fw-semibold">

                                {{ $activeAssignment->assigned_at?->format('d M Y H:i') ?? 'Not specified' }}

                            </div>

                        </div>

                    </div>


                    {{-- LOCATION --}}

                    <div class="col-md-6">

                        <div class="border rounded p-3 h-100">

                            <div class="text-muted small mb-1">
                                Location
                            </div>

                            <div class="fw-semibold">

                                {{ $asset->location ?? 'Not specified' }}

                            </div>

                        </div>

                    </div>


                    {{-- ASSIGNMENT NOTES --}}

                    <div class="col-12">

                        <div class="border rounded p-3">

                            <div class="text-muted small mb-1">
                                Assignment Notes
                            </div>

                            <div>

                                {{ $activeAssignment->notes ?? 'No assignment notes.' }}

                            </div>

                        </div>

                    </div>

                </div>


                {{-- RETURN BUTTON --}}

                @if (
                    auth()->user()->role === 'hardware_officer' ||
                    auth()->user()->role === 'administration_officer'
                )

                    <div class="mt-4 pt-3 border-top">

                        <form
                            method="POST"
                            action="{{ route('assets.return', $asset) }}"
                            onsubmit="return confirm('Are you sure you want to return this asset?');"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="btn btn-outline-danger"
                            >
                                <i class="bi bi-arrow-return-left me-1"></i>
                                Return Asset
                            </button>

                        </form>

                    </div>

                @endif


            @else

                {{-- =================================================
                    NO ACTIVE ASSIGNMENT
                ================================================== --}}

                <div class="text-center py-4">

                    <div class="mb-3">

                        @if ($asset->status === 'under_repair')

                            <i
                                class="bi bi-tools fs-1 text-warning"
                            ></i>

                        @elseif ($asset->status === 'retired')

                            <i
                                class="bi bi-archive fs-1 text-secondary"
                            ></i>

                        @elseif ($asset->status === 'lost')

                            <i
                                class="bi bi-exclamation-triangle fs-1 text-danger"
                            ></i>

                        @elseif ($asset->status === 'disposed')

                            <i
                                class="bi bi-trash fs-1 text-secondary"
                            ></i>

                        @else

                            <i
                                class="bi bi-person-x fs-1 text-muted"
                            ></i>

                        @endif

                    </div>


                    @if ($asset->status === 'under_repair')

                        <h5>
                            No Current Holder
                        </h5>

                        <p class="text-muted mb-0">
                            This asset is currently under repair and has no
                            active assignment.
                        </p>

                    @elseif ($asset->status === 'available')

                        <h5>
                            Available / Idle
                        </h5>

                        <p class="text-muted mb-3">
                            This asset currently has no active assignment.
                        </p>


                        {{-- ASSIGN ASSET --}}

                        @if (
                            auth()->user()->role === 'hardware_officer' ||
                            auth()->user()->role === 'administration_officer'
                        )

                            <a
                                href="{{ route('assets.assign', $asset) }}"
                                class="btn btn-crb"
                            >
                                <i class="bi bi-person-plus me-1"></i>
                                Assign Asset
                            </a>

                        @endif


                    @elseif ($asset->status === 'retired')

                        <h5>
                            No Current Holder
                        </h5>

                        <p class="text-muted mb-0">
                            This asset has been retired.
                        </p>

                    @elseif ($asset->status === 'lost')

                        <h5>
                            No Current Holder
                        </h5>

                        <p class="text-muted mb-0">
                            This asset is currently marked as lost.
                        </p>

                    @elseif ($asset->status === 'disposed')

                        <h5>
                            No Current Holder
                        </h5>

                        <p class="text-muted mb-0">
                            This asset has been disposed.
                        </p>

                    @else

                        <h5>
                            No Active Assignment
                        </h5>

                        <p class="text-muted mb-0">
                            There is currently no active assignment for this asset.
                        </p>

                    @endif

                </div>

            @endif

        </div>

    </div>


    {{-- =========================================================
        ASSIGNMENT HISTORY
    ========================================================== --}}

    <div class="crb-card mb-4">

        <div class="crb-card-header">

            <h5 class="mb-0">
                <i class="bi bi-clock-history me-2"></i>
                Assignment History
            </h5>

            <small class="text-muted">
                Complete allocation history for this asset
            </small>

        </div>


        <div class="crb-card-body p-0">

            @php

                $assignmentHistory = $asset->assignments()
                    ->with([
                        'employee',
                        'department',
                        'assignedBy',
                    ])
                    ->latest('assigned_at')
                    ->get();

            @endphp


            @if ($assignmentHistory->count())

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead>

                            <tr>

                                <th class="ps-4">
                                    Employee
                                </th>

                                <th>
                                    Department
                                </th>

                                <th>
                                    Assigned By
                                </th>

                                <th>
                                    Assigned At
                                </th>

                                <th>
                                    Returned At
                                </th>

                                <th>
                                    Status
                                </th>

                                <th class="pe-4">
                                    Notes
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach ($assignmentHistory as $assignment)

                                <tr>

                                    {{-- EMPLOYEE --}}

                                    <td class="ps-4">

                                        <div class="fw-semibold">

                                            {{ $assignment->employee?->first_name }}
                                            {{ $assignment->employee?->last_name }}

                                        </div>

                                        @if ($assignment->employee?->employee_number)

                                            <small class="text-muted">

                                                {{ $assignment->employee->employee_number }}

                                            </small>

                                        @endif

                                    </td>


                                    {{-- DEPARTMENT --}}

                                    <td>

                                        {{ $assignment->department?->name ?? 'Not specified' }}

                                    </td>


                                    {{-- ASSIGNED BY --}}

                                    <td>

                                        {{ $assignment->assignedBy?->name ?? 'Unknown' }}

                                    </td>


                                    {{-- ASSIGNED AT --}}

                                    <td>

                                        <div class="fw-semibold">

                                            {{ $assignment->assigned_at?->format('d M Y') }}

                                        </div>

                                        <small class="text-muted">

                                            {{ $assignment->assigned_at?->format('H:i') }}

                                        </small>

                                    </td>


                                    {{-- RETURNED AT --}}

                                    <td>

                                        @if ($assignment->returned_at)

                                            <div class="fw-semibold">

                                                {{ $assignment->returned_at->format('d M Y') }}

                                            </div>

                                            <small class="text-muted">

                                                {{ $assignment->returned_at->format('H:i') }}

                                            </small>

                                        @else

                                            —

                                        @endif

                                    </td>


                                    {{-- STATUS --}}

                                    <td>

                                        @if (is_null($assignment->returned_at))

                                            <span class="badge bg-success">
                                                Current
                                            </span>

                                        @else

                                            <span class="badge bg-secondary">
                                                Returned
                                            </span>

                                        @endif

                                    </td>


                                    {{-- NOTES --}}

                                    <td class="pe-4">

                                        {{ $assignment->notes ?? '—' }}

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="text-center py-5">

                    <i class="bi bi-clock-history fs-1 text-muted"></i>

                    <h5 class="mt-3">
                        No Assignment History
                    </h5>

                    <p class="text-muted mb-0">
                        This asset has not yet been assigned to an employee.
                    </p>

                </div>

            @endif

        </div>

    </div>


    {{-- =========================================================
        QR CODE QUICK INFORMATION
    ========================================================== --}}

    <div class="crb-card mb-4">

        <div class="crb-card-body">

            <div class="row align-items-center">

                <div class="col-md-8">

                    <h5 class="mb-2">

                        <i class="bi bi-qr-code me-2"></i>
                        Asset QR Code

                    </h5>

                    <p class="text-muted mb-2">

                        This asset has a unique QR code that links directly
                        to its CRB Asset Management System record.

                    </p>

                    <p class="mb-0">

                        <strong>Asset Code:</strong>
                        {{ $asset->asset_code }}

                    </p>

                </div>


                <div class="col-md-4 text-md-end mt-3 mt-md-0">

                    <a
                        href="{{ route('assets.tag', $asset) }}"
                        target="_blank"
                        class="btn btn-crb"
                    >

                        <i class="bi bi-printer me-1"></i>
                        Print QR Tag

                    </a>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
        ASSET INFORMATION
    ========================================================== --}}

    <div class="crb-card">

        <div class="crb-card-body">

            <dl class="row mb-0">

                {{-- Asset Code --}}

                <dt class="col-md-3">
                    Asset Code
                </dt>

                <dd class="col-md-9">
                    {{ $asset->asset_code }}
                </dd>


                {{-- Asset Name --}}

                <dt class="col-md-3">
                    Asset Name
                </dt>

                <dd class="col-md-9">
                    {{ $asset->asset_name }}
                </dd>


                {{-- Category --}}

                <dt class="col-md-3">
                    Category
                </dt>

                <dd class="col-md-9">
                    {{ $asset->category?->name ?? 'Not specified' }}
                </dd>


                {{-- Management Area --}}

                <dt class="col-md-3">
                    Management Area
                </dt>

                <dd class="col-md-9">

                    @php

                        $managementArea =
                            $asset->category?->responsible_officer;

                    @endphp


                    @if ($managementArea === 'hardware')

                        Hardware Officer

                    @elseif ($managementArea === 'administration')

                        Administration Officer

                    @elseif ($managementArea === 'system_admin')

                        System Administrator

                    @else

                        Not assigned

                    @endif

                </dd>


                {{-- Asset Type --}}

                <dt class="col-md-3">
                    Asset Type
                </dt>

                <dd class="col-md-9">
                    {{ $asset->type?->name ?? 'Not specified' }}
                </dd>


                {{-- Department --}}

                <dt class="col-md-3">
                    Department
                </dt>

                <dd class="col-md-9">
                    {{ $asset->department?->name ?? 'Unassigned' }}
                </dd>


                {{-- Assigned Employee --}}

                <dt class="col-md-3">
                    Assigned To
                </dt>

                <dd class="col-md-9">

                    @if ($activeAssignment)

                        {{ $activeAssignment->employee?->first_name }}
                        {{ $activeAssignment->employee?->last_name }}

                    @else

                        Unassigned

                    @endif

                </dd>


                {{-- Location --}}

                <dt class="col-md-3">
                    Location
                </dt>

                <dd class="col-md-9">
                    {{ $asset->location ?? 'Not specified' }}
                </dd>


                {{-- Serial Number --}}

                <dt class="col-md-3">
                    Serial Number
                </dt>

                <dd class="col-md-9">
                    {{ $asset->serial_number ?? 'Not specified' }}
                </dd>


                {{-- Condition --}}

                <dt class="col-md-3">
                    Condition
                </dt>

                <dd class="col-md-9">

                    {{ str($asset->condition)->replace('_', ' ')->title() }}

                </dd>


                {{-- Status --}}

                <dt class="col-md-3">
                    Status
                </dt>

                <dd class="col-md-9">

                    {{ str($asset->status)->replace('_', ' ')->title() }}

                </dd>


                {{-- Supplier --}}

                <dt class="col-md-3">
                    Supplier
                </dt>

                <dd class="col-md-9">

                    {{ $asset->supplier ?? 'Not specified' }}

                </dd>


                {{-- Purchase Date --}}

                <dt class="col-md-3">
                    Purchase Date
                </dt>

                <dd class="col-md-9">

                    {{ $asset->purchase_date
                        ? \Carbon\Carbon::parse($asset->purchase_date)->format('d M Y')
                        : 'Not specified'
                    }}

                </dd>


                {{-- Purchase Price --}}

                <dt class="col-md-3">
                    Purchase Price
                </dt>

                <dd class="col-md-9">

                    {{ $asset->purchase_price !== null
                        ? number_format($asset->purchase_price, 2)
                        : 'Not specified'
                    }}

                </dd>


                {{-- Barcode --}}

                <dt class="col-md-3">
                    Barcode
                </dt>

                <dd class="col-md-9">

                    {{ $asset->barcode ?? 'Not generated' }}

                </dd>


                {{-- Notes --}}

                <dt class="col-md-3">
                    Notes
                </dt>

                <dd class="col-md-9">

                    {{ $asset->notes ?? 'None' }}

                </dd>

            </dl>

        </div>

    </div>

</x-app-layout>