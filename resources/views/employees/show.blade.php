<x-app-layout>

```
<x-slot name="title">
    Employee Details
</x-slot>


{{-- PAGE HEADER --}}

<div class="d-flex justify-content-between align-items-center mb-4">

    <div class="crb-page-title mb-0">

        <h1>Employee Details</h1>

        <p>
            View employee information and assigned assets.
        </p>

    </div>

    <div class="d-flex gap-2">

        <a
            href="{{ route('employees.edit', $employee) }}"
            class="btn btn-crb"
        >
            <i class="bi bi-pencil me-1"></i>
            Edit Employee
        </a>

        <a
            href="{{ route('employees.index') }}"
            class="btn btn-light border"
        >
            <i class="bi bi-arrow-left me-1"></i>
            Back
        </a>

    </div>

</div>


@if (session('success'))

    <div class="alert alert-success mb-4">

        <i class="bi bi-check-circle me-2"></i>

        {{ session('success') }}

    </div>

@endif


<div class="row g-4">


    {{-- EMPLOYEE PROFILE --}}

    <div class="col-lg-4">

        <div class="crb-card h-100">

            <div class="crb-card-header">

                <div>

                    <h5>

                        <i class="bi bi-person me-2"></i>

                        Employee Profile

                    </h5>

                    <small>
                        Employee information
                    </small>

                </div>

            </div>


            <div class="crb-card-body text-center">


                <div
                    class="crb-avatar mx-auto mb-3"
                    style="width: 70px; height: 70px; font-size: 22px;"
                >

                    {{ strtoupper(
                        substr($employee->first_name, 0, 1) .
                        substr($employee->last_name, 0, 1)
                    ) }}

                </div>


                <h4 class="mb-1">

                    {{ $employee->first_name }}
                    {{ $employee->last_name }}

                </h4>


                <div class="crb-muted mb-4">

                    {{ $employee->employee_number }}

                </div>


                <div class="text-start">


                    {{-- DEPARTMENT --}}

                    <div class="mb-3">

                        <div class="crb-muted mb-1">
                            Department
                        </div>

                        @if ($employee->department)

                            <div class="fw-semibold">

                                {{ $employee->department->name }}

                            </div>

                            @if ($employee->department->code)

                                <div class="crb-muted">

                                    {{ $employee->department->code }}

                                </div>

                            @endif

                        @else

                            <span class="crb-muted">
                                Unassigned
                            </span>

                        @endif

                    </div>


                    {{-- POSITION --}}

                    <div class="mb-3">

                        <div class="crb-muted mb-1">
                            Position
                        </div>

                        @if ($employee->position)

                            <div class="fw-semibold">

                                {{ $employee->position }}

                            </div>

                        @else

                            <span class="crb-muted">
                                Not specified
                            </span>

                        @endif

                    </div>


                    {{-- EMAIL --}}

                    <div class="mb-3">

                        <div class="crb-muted mb-1">
                            Email Address
                        </div>

                        @if ($employee->email)

                            <div>

                                <i class="bi bi-envelope me-1"></i>

                                {{ $employee->email }}

                            </div>

                        @else

                            <span class="crb-muted">
                                Not provided
                            </span>

                        @endif

                    </div>


                    {{-- PHONE --}}

                    <div class="mb-3">

                        <div class="crb-muted mb-1">
                            Phone Number
                        </div>

                        @if ($employee->phone)

                            <div>

                                <i class="bi bi-telephone me-1"></i>

                                {{ $employee->phone }}

                            </div>

                        @else

                            <span class="crb-muted">
                                Not provided
                            </span>

                        @endif

                    </div>


                    {{-- ASSET COUNT --}}

                    <div>

                        <div class="crb-muted mb-1">
                            Assigned Assets
                        </div>

                        @if ($employee->assets->count())

                            <span class="crb-status assigned">

                                <span class="crb-status-dot"></span>

                                {{ $employee->assets->count() }}

                                {{ $employee->assets->count() == 1 ? 'Asset' : 'Assets' }}

                            </span>

                        @else

                            <span class="crb-muted">
                                No assets assigned
                            </span>

                        @endif

                    </div>


                </div>

            </div>

        </div>

    </div>


    {{-- ASSIGNED ASSETS --}}

    <div class="col-lg-8">

        <div class="crb-card">

            <div class="crb-card-header">

                <div>

                    <h5>

                        <i class="bi bi-box-seam me-2"></i>

                        Assigned Assets

                    </h5>

                    <small>
                        Assets currently assigned to this employee
                    </small>

                </div>

            </div>


            @if ($employee->assets->count())

                <div class="table-responsive">

                    <table class="table crb-table align-middle mb-0">

                        <thead>

                            <tr>

                                <th>Asset Code</th>

                                <th>Asset</th>

                                <th>Category</th>

                                <th>Type</th>

                                <th>Status</th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach ($employee->assets as $asset)

                                <tr>

                                    <td>

                                        <a
                                            href="{{ route('assets.show', $asset) }}"
                                            class="crb-asset-code"
                                        >

                                            {{ $asset->asset_code }}

                                        </a>

                                    </td>


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


                                    <td>

                                        {{ $asset->category?->name ?? '—' }}

                                    </td>


                                    <td>

                                        {{ $asset->type?->name ?? '—' }}

                                    </td>


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
                                                str_replace('_', ' ', $asset->status)
                                            ) }}

                                        </span>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            @else

                <div class="crb-empty-state">

                    <div class="crb-empty-icon">

                        <i class="bi bi-box-seam"></i>

                    </div>

                    <h4>
                        No Assets Assigned
                    </h4>

                    <p>
                        This employee currently has no assets assigned.
                    </p>

                </div>

            @endif

        </div>

    </div>


</div>
```

</x-app-layout>
