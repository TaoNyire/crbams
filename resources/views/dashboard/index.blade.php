<x-app-layout>

    <x-slot name="title">
        Dashboard
    </x-slot>

<div class="crb-page-title d-flex justify-content-between align-items-center">

    <div>
        <h1>Dashboard</h1>
        <p>Overview of the CRB asset management system.</p>
    </div>

    <a href="{{ route('assets.create') }}" class="btn btn-crb">
        <i class="bi bi-plus-lg me-1"></i>
        Register Asset
    </a>

</div>


{{-- STATISTICS --}}
<div class="row g-3 mb-4">

    <div class="col-xl-3 col-md-6">

        <div class="crb-stat-card">

            <div class="crb-stat-top">

                <div class="crb-stat-icon">
                    <i class="bi bi-box-seam"></i>
                </div>

                <span class="crb-stat-change">
                    <i class="bi bi-arrow-up-right"></i>
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


    <div class="col-xl-3 col-md-6">

        <div class="crb-stat-card">

            <div class="crb-stat-top">

                <div class="crb-stat-icon">
                    <i class="bi bi-person-check"></i>
                </div>

                <span class="crb-stat-change">
                    <i class="bi bi-arrow-up-right"></i>
                    Active
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


    <div class="col-xl-3 col-md-6">

        <div class="crb-stat-card">

            <div class="crb-stat-top">

                <div class="crb-stat-icon">
                    <i class="bi bi-check-circle"></i>
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


    <div class="col-xl-3 col-md-6">

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
                Assets Under Repair
            </div>

        </div>

    </div>

</div>


{{-- MAIN DASHBOARD --}}
<div class="row g-3 mb-4">


    {{-- ASSET OVERVIEW --}}

    <div class="col-lg-8">

        <div class="crb-card h-100">

            <div class="crb-card-header">

                <div>

                    <h5>
                        Asset Overview
                    </h5>

                    <small class="text-muted">
                        Current asset distribution
                    </small>

                </div>

                <select
                    class="form-select form-select-sm"
                    style="width: 120px;"
                >

                    <option>All Areas</option>
                    <option>IT</option>
                    <option>Administration</option>

                </select>

            </div>


            <div class="crb-card-body">

                <div
                    style="
                        height: 250px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    "
                >

                    <div class="text-center">

                        <div
                            class="mb-3"
                            style="
                                width: 110px;
                                height: 110px;
                                border-radius: 50%;
                                border: 18px solid #246d69;
                                border-right-color: #9de2dd;
                                border-bottom-color: #d7f2ef;
                                margin: auto;
                            "
                        ></div>

                        <div class="text-muted small">
                            Total registered assets
                        </div>

                        <strong style="font-size: 24px;">
                            {{ $totalAssets }}
                        </strong>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- STATUS --}}
    <div class="col-lg-4">

        <div class="crb-card h-100">

            <div class="crb-card-header">

                <div>

                    <h5>
                        Asset Status
                    </h5>

                    <small class="text-muted">
                        Current status
                    </small>

                </div>

                <i class="bi bi-three-dots"></i>

            </div>


            <div class="crb-card-body">

                <div class="mb-4">

                    <div class="d-flex justify-content-between mb-2">

                        <span class="small">
                            Available
                        </span>

                        <strong class="small">
                            {{ $availableAssets }}
                        </strong>

                    </div>

                    <div class="progress" style="height: 7px;">

                        <div
                            class="progress-bar"
                            style="
                                width:
                                {{ $totalAssets > 0 ? ($availableAssets / $totalAssets) * 100 : 0 }}%;
                                background:#246d69;
                            "
                        ></div>

                    </div>

                </div>


                <div class="mb-4">

                    <div class="d-flex justify-content-between mb-2">

                        <span class="small">
                            Assigned
                        </span>

                        <strong class="small">
                            {{ $assignedAssets }}
                        </strong>

                    </div>

                    <div class="progress" style="height: 7px;">

                        <div
                            class="progress-bar"
                            style="
                                width:
                                {{ $totalAssets > 0 ? ($assignedAssets / $totalAssets) * 100 : 0 }}%;
                                background:#5f9fd1;
                            "
                        ></div>

                    </div>

                </div>


                <div>

                    <div class="d-flex justify-content-between mb-2">

                        <span class="small">
                            Under Repair
                        </span>

                        <strong class="small">
                            {{ $repairAssets }}
                        </strong>

                    </div>

                    <div class="progress" style="height: 7px;">

                        <div
                            class="progress-bar"
                            style="
                                width:
                                {{ $totalAssets > 0 ? ($repairAssets / $totalAssets) * 100 : 0 }}%;
                                background:#e5b84f;
                            "
                        ></div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- RECENT ASSETS --}}

<div class="crb-card">

    <div class="crb-card-header">

        <div>

            <h5>
                Recent Assets
            </h5>

            <small class="text-muted">
                Recently registered assets
            </small>

        </div>


        <a
            href="{{ route('assets.index') }}"
            class="btn btn-sm btn-light"
        >
            View All
            <i class="bi bi-arrow-right ms-1"></i>
        </a>

    </div>


    <div class="table-responsive">

        <table class="table crb-table">

            <thead>

                <tr>

                    <th>Asset Code</th>

                    <th>Asset</th>

                    <th>Category</th>

                    <th>Department</th>

                    <th>Employee</th>

                    <th>Status</th>

                </tr>

            </thead>


            <tbody>

                @forelse ($recentAssets as $asset)

                    <tr>

                        <td>

                            <strong>
                                {{ $asset->asset_code }}
                            </strong>

                        </td>


                        <td>

                            {{ $asset->asset_name }}

                            @if ($asset->serial_number)

                                <div class="text-muted small">
                                    SN: {{ $asset->serial_number }}
                                </div>

                            @endif

                        </td>


                        <td>

                            {{ $asset->category?->name ?? 'N/A' }}

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

                            @php

                                $statusClass = match ($asset->status) {

                                    'available' => 'available',

                                    'assigned' => 'assigned',

                                    'under_repair' => 'repair',

                                    'retired',
                                    'disposed',
                                    'lost' => 'retired',

                                    default => 'retired',

                                };

                            @endphp


                            <span class="crb-status {{ $statusClass }}">

                                <i class="bi bi-circle-fill"
                                   style="font-size: 5px;"></i>

                                {{ ucwords(str_replace('_', ' ', $asset->status)) }}

                            </span>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="6"
                            class="text-center py-5 text-muted"
                        >

                            <i
                                class="bi bi-box-seam"
                                style="font-size: 30px;"
                            ></i>

                            <div class="mt-2">
                                No assets registered yet.
                            </div>

                            <a
                                href="{{ route('assets.create') }}"
                                class="btn btn-crb btn-sm mt-3"
                            >
                                Register First Asset
                            </a>

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

</x-app-layout>