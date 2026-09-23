<x-layout>
    <div class="container-fluid py-4">

        {{-- Page Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Return Asset</h2>
                <p class="text-muted mb-0">
                    Record the return of an assigned asset.
                </p>
            </div>

            <a href="{{ route('assets.show', $asset) }}"
               class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                Back to Asset
            </a>
        </div>

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <div class="fw-bold mb-2">
                    Please correct the following errors:
                </div>

                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Success Message --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="row g-4">

            {{-- Asset Information --}}
            <div class="col-lg-5">

                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-box-seam me-2"></i>
                            Asset Information
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="mb-3">
                            <small class="text-muted d-block">
                                Asset Code
                            </small>

                            <strong>
                                {{ $asset->asset_code }}
                            </strong>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">
                                Asset Name
                            </small>

                            <strong>
                                {{ $asset->asset_name }}
                            </strong>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">
                                Serial Number
                            </small>

                            <strong>
                                {{ $asset->serial_number ?: 'Not specified' }}
                            </strong>
                        </div>

                        <hr>

                        <h6 class="fw-bold mb-3">
                            Current Allocation
                        </h6>

                        <div class="mb-3">
                            <small class="text-muted d-block">
                                Current Holder
                            </small>

                            <strong>
                                {{ $assignment->employee->first_name }}
                                {{ $assignment->employee->last_name }}
                            </strong>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">
                                Employee Number
                            </small>

                            <strong>
                                {{ $assignment->employee->employee_number }}
                            </strong>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">
                                Department
                            </small>

                            <strong>
                                {{ $assignment->department->name }}
                            </strong>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted d-block">
                                Assigned Date
                            </small>

                            <strong>
                                {{ $assignment->assigned_at?->format('d M Y H:i') }}
                            </strong>
                        </div>

                        <div>
                            <small class="text-muted d-block">
                                Current Condition
                            </small>

                            <strong>
                                {{ $asset->condition_label }}
                            </strong>
                        </div>

                    </div>
                </div>

            </div>

            {{-- Return Form --}}
            <div class="col-lg-7">

                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold">
                            <i class="bi bi-box-arrow-in-left me-2"></i>
                            Return Details
                        </h5>
                    </div>

                    <div class="card-body">

                        <form method="POST"
                              action="{{ route('assets.return', $asset) }}">

                            @csrf

                            {{-- Returned Date --}}
                            <div class="mb-4">

                                <label for="returned_at"
                                       class="form-label fw-semibold">

                                    Returned Date
                                    <span class="text-danger">*</span>

                                </label>

                                <input
                                type="datetime-local"
                                    id="returned_at"
                                    name="returned_at"
                                    class="form-control @error('returned_at') is-invalid @enderror"
                                value="{{ old('returned_at', now()->format('Y-m-d\\TH:i')) }}"
                                max="{{ now()->format('Y-m-d\\TH:i') }}"
                                    required
                                >

                                @error('returned_at')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- Returned Condition --}}
                            <div class="mb-4">

                                <label for="returned_condition"
                                       class="form-label fw-semibold">

                                    Returned Condition
                                    <span class="text-danger">*</span>

                                </label>

                                <select
                                    id="returned_condition"
                                    name="returned_condition"
                                    class="form-select @error('returned_condition') is-invalid @enderror"
                                    required
                                >

                                    <option value="">
                                        -- Select Condition --
                                    </option>

                                    <option value="good"
                                        {{ old('returned_condition') === 'good' ? 'selected' : '' }}>
                                        Good
                                    </option>

                                    <option value="damaged"
                                        {{ old('returned_condition') === 'damaged' ? 'selected' : '' }}>
                                        Damaged
                                    </option>

                                    <option value="needs_repair"
                                        {{ old('returned_condition') === 'needs_repair' ? 'selected' : '' }}>
                                        Needs Repair
                                    </option>

                                </select>

                                @error('returned_condition')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            {{-- Return Notes --}}
                            <div class="mb-4">

                                <label for="return_notes"
                                       class="form-label fw-semibold">

                                    Return Notes
                                </label>

                                <textarea
                                    id="return_notes"
                                    name="return_notes"
                                    rows="4"
                                    class="form-control @error('return_notes') is-invalid @enderror"
                                    placeholder="Enter any relevant information about the returned asset..."
                                >{{ old('return_notes') }}</textarea>

                                @error('return_notes')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                            </div>

                            <hr class="my-4">

                            {{-- Buttons --}}
                            <div class="d-flex justify-content-end gap-2">

                                <a href="{{ route('assets.show', $asset) }}"
                                   class="btn btn-outline-secondary">

                                    Cancel

                                </a>

                                <button type="submit"
                                        class="btn btn-warning">

                                    <i class="bi bi-box-arrow-in-left me-1"></i>

                                    Complete Return

                                </button>

                            </div>

                        </form>

                    </div>
                </div>

            </div>

        </div>

    </div>
</x-layout>
