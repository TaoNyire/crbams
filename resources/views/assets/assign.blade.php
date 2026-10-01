
<x-app-layout>
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Assign Asset</h2>
            <p class="text-muted mb-0">
                Assign this asset to an active employee.
            </p>
        </div>

        <a href="{{ route('assets.show', $asset) }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i>
            Back to Asset
        </a>
    </div>

    {{-- Asset Information --}}
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="bi bi-box-seam me-2"></i>
                Asset Information
            </h5>
        </div>

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-3">
                    <small class="text-muted d-block">
                        Asset Code
                    </small>
                    <strong>
                        {{ $asset->asset_code }}
                    </strong>
                </div>

                <div class="col-md-3">
                    <small class="text-muted d-block">
                        Asset Name
                    </small>
                    <strong>
                        {{ $asset->asset_name }}
                    </strong>
                </div>

                <div class="col-md-3">
                    <small class="text-muted d-block">
                        Serial Number
                    </small>
                    <strong>
                        {{ $asset->serial_number ?: '—' }}
                    </strong>
                </div>

                <div class="col-md-3">
                    <small class="text-muted d-block">
                        Current Status
                    </small>

                    <span class="badge bg-{{ $asset->status === 'available' ? 'success' : 'warning' }}">
                        {{ $asset->status_label }}
                    </span>
                </div>

            </div>

        </div>
    </div>

    {{-- Assignment Form --}}
    <div class="card shadow-sm">

        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="bi bi-person-check me-2"></i>
                Assignment Details
            </h5>
        </div>

        <div class="card-body">

            <form
                method="POST"
                action="{{ route('assets.assign.store', $asset) }}"
            >

                @csrf

                <div class="row g-3">

                    {{-- Employee --}}
                    <div class="col-md-6">

                        <label
                            for="employee_id"
                            class="form-label fw-semibold"
                        >
                            Employee <span class="text-danger">*</span>
                        </label>

                        <select
                            name="employee_id"
                            id="employee_id"
                            class="form-select @error('employee_id') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                Select Employee
                            </option>

                            @foreach($employees as $employee)

                                <option
                                    value="{{ $employee->id }}"
                                    data-department="{{ $employee->department_id }}"
                                    {{ old('employee_id') == $employee->id ? 'selected' : '' }}
                                >
                                    {{ $employee->employee_number }}
                                    —
                                    {{ $employee->first_name }}
                                    {{ $employee->last_name }}
                                    @if($employee->designation)
                                        ({{ $employee->designation }})
                                    @endif
                                </option>

                            @endforeach

                        </select>

                        @error('employee_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Department --}}
                    <div class="col-md-6">

                        <label
                            for="department_id"
                            class="form-label fw-semibold"
                        >
                            Department <span class="text-danger">*</span>
                        </label>

                        <select
                            name="department_id"
                            id="department_id"
                            class="form-select @error('department_id') is-invalid @enderror"
                            required
                        >

                            <option value="">
                                Select Department
                            </option>

                            @foreach($departments as $department)

                                <option
                                    value="{{ $department->id }}"
                                    {{ old('department_id') == $department->id ? 'selected' : '' }}
                                >
                                    {{ $department->name }}
                                    @if($department->code)
                                        ({{ $department->code }})
                                    @endif
                                </option>

                            @endforeach

                        </select>

                        @error('department_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Location --}}
                    <div class="col-md-6">

                        <label
                            for="location"
                            class="form-label fw-semibold"
                        >
                            Asset Location
                        </label>

                        <input
                            type="text"
                            name="location"
                            id="location"
                            class="form-control @error('location') is-invalid @enderror"
                            value="{{ old('location', $asset->location) }}"
                            placeholder="e.g. Administration Office"
                        >

                        @error('location')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    {{-- Notes --}}
                    <div class="col-12">

                        <label
                            for="notes"
                            class="form-label fw-semibold"
                        >
                            Assignment Notes
                        </label>

                        <textarea
                            name="notes"
                            id="notes"
                            rows="4"
                            class="form-control @error('notes') is-invalid @enderror"
                            placeholder="Enter any relevant assignment notes..."
                        >{{ old('notes', $asset->notes) }}</textarea>

                        @error('notes')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">

                    <a
                        href="{{ route('assets.show', $asset) }}"
                        class="btn btn-light"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        <i class="bi bi-person-check me-1"></i>
                        Confirm Assignment
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

</x-app-layout>