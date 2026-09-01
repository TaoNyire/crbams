<x-app-layout>
    <x-slot name="title">{{ $asset->asset_name }}</x-slot>

```
{{-- =========================================================
    PAGE HEADER
========================================================== --}}

<div class="crb-page-title d-flex justify-content-between align-items-center">

    <div>
        <h1>{{ $asset->asset_name }}</h1>
        <p>{{ $asset->asset_code }}</p>
    </div>

    {{-- Operational officers can edit.
         System Administrator is READ-ONLY. --}}
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

                @if ($asset->employee)
                    {{ $asset->employee->first_name }}
                    {{ $asset->employee->last_name }}
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
```

</x-app-layout>
