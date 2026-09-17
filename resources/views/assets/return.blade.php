<x-app-layout>

    <div class="container-fluid py-4">

        {{-- Page Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Return Asset</h2>
                <p class="text-muted mb-0">
                    Record the return of an assigned asset and update its condition.
                </p>
            </div>

            <a href="{{ route('assets.show', $asset) }}"
               class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                Back to Asset
            </a>
        </div>


        {{-- Asset Summary --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">

                <div class="row g-4">

                    <div class="col-md-3">
                        <small class="text-muted d-block">Asset Code</small>
                        <strong>{{ $asset->asset_code }}</strong>
                    </div>

                    <div class="col-md-3">
                        <small class="text-muted d-block">Asset Name</small>
                        <strong>{{ $asset->asset_name }}</strong>
                    </div>

                    <div class="col-md-3">
                        <small class="text-muted d-block">Serial Number</small>
                        <strong>
                            {{ $asset->serial_number ?: 'Not specified' }}
                        </strong>
                    </div>

                    <div class="col-md-3">
                        <small class="text-muted d-block">Current Status</small>

                        <span class="badge bg-primary">
                            {{ $asset->status_label }}
                        </span>
                    </div>

                </div>

            </div>
        </div>


        {{-- Current Allocation --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0 pt-4 px-4">

                <h5 class="fw-bold mb-1">
                    <i class="bi bi-person-check me-2"></i>
                    Current Allocation
                </h5>

                <p class="text-muted small mb-0">
                    Asset currently assigned to this employee.
                </p>

            </div>

            <div class="card-body px-4 pb-4">

                @if($assignment && $assignment->employee)

                    <div class="row g-4">

                        <div class="col-md-4">
                            <small class="text-muted d-block">Employee</small>
                            <strong>
                                {{ $assignment->employee->first_name }}
                                {{ $assignment->employee->last_name }}
                            </strong>
                        </div>

                        <div class="col-md-4">
                            <small class="text-muted d-block">Employee Number</small>
                            <strong>
                                {{ $assignment->employee->employee_number }}
                            </strong>
                        </div>

                        <div class="col-md-4">
                            <small class="text-muted d-block">Department</small>
                            <strong>
                                {{ $assignment->department?->name ?? 'Not specified' }}
                            </strong>
                        </div>

                        <div class="col-md-4">
                            <small class="text-muted d-block">Assigned Date</small>
                            <strong>
                                {{ $assignment->assigned_at?->format('d M Y H:i') }}
                            </strong>
                        </div>

                        <div class="col-md-4">
                            <small class="text-muted d-block">Location</small>
                            <strong>
                                {{ $asset->location ?: 'Not specified' }}
                            </strong>
                        </div>

                        <div class="col-md-4">
                            <small class="text-muted d-block">Assigned By</small>
                            <strong>
                                {{ $assignment->assignedBy?->name ?? 'Not specified' }}
                            </strong>
                        </div>

                    </div>

                @else

                    <div class="alert alert-warning mb-0">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        No active assignment was found for this asset.
                    </div>

                @endif

            </div>
        </div>


        {{-- Return Form --}}
        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white border-0 pt-4 px-4">

                <h5 class="fw-bold mb-1">
                    <i class="bi bi-box-arrow-in-left me-2"></i>
                    Return Details
                </h5>

                <p class="text-muted small mb-0">
                    Record the condition of the asset when it is returned.
                </p>

            </div>

            <div class="card-body px-4 pb-4">

                {{-- Validation Errors --}}
                @if($errors->any())

                    <div class="alert alert-danger">

                        <strong>Please correct the following:</strong>

                        <ul class="mb-0 mt-2">

                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach

                        </ul>

                    </div>

                @endif


                <form method="POST"
                      action="{{ route('assets.return', $asset) }}">

                    @csrf


                    <div class="row g-4">

                        {{-- Return Date --}}
                        <div class="col-md-6">

                            <label for="returned_at"
                                   class="form-label fw-semibold">
                                Return Date
                            </label>

                            <input
                                type="date"
                                name="returned_at"
                                id="returned_at"
                                class="form-control"
                                value="{{ old('returned_at') ?: date('Y-m-d') }}"
                                required
                            >

                            <div class="form-text">
                                Date the asset was physically returned.
                            </div>

                        </div>


                        {{-- Condition --}}
                        <div class="col-md-6">

                            <label for="returned_condition"
                                   class="form-label fw-semibold">
                                Condition on Return
                            </label>

                            <select
                                name="returned_condition"
                                id="returned_condition"
                                class="form-select"
                                required
                            >

                                <option value="" disabled
                                    {{ old('returned_condition') ? '' : 'selected' }}>
                                    Select condition
                                </option>

                                <option value="good"
                                    {{ old('returned_condition') == 'good' ? 'selected' : '' }}>
                                    Good
                                </option>

                                <option value="damaged"
                                    {{ old('returned_condition') == 'damaged' ? 'selected' : '' }}>
                                    Damaged
                                </option>

                                <option value="needs_repair"
                                    {{ old('returned_condition') == 'needs_repair' ? 'selected' : '' }}>
                                    Needs Repair
                                </option>

                            </select>

                            <div class="form-text">
                                If the asset needs repair, its status will automatically become Under Repair.
                            </div>

                        </div>


                        {{-- Return Notes --}}
                        <div class="col-12">

                            <label for="return_notes"
                                   class="form-label fw-semibold">
                                Return Notes
                            </label>

                            <textarea
                                name="return_notes"
                                id="return_notes"
                                rows="4"
                                class="form-control"
                                placeholder="Describe the condition of the asset, any damage, missing accessories, or other relevant information..."
                            >{{ old('return_notes') }}</textarea>

                            <div class="form-text">
                                Optional. These notes will remain part of the assignment history.
                            </div>

                        </div>

                    </div>


                    {{-- Warning --}}
                    <div class="alert alert-warning mt-4">

                        <i class="bi bi-exclamation-triangle me-2"></i>

                        <strong>Important:</strong>

                        Returning this asset will end its current assignment.
                        The assignment will remain permanently recorded in the asset history.

                    </div>


                    {{-- Actions --}}
                    <div class="d-flex justify-content-end gap-2 mt-4">

                        <a href="{{ route('assets.show', $asset) }}"
                           class="btn btn-outline-secondary">
                            Cancel
                        </a>

                        <button type="submit"
                                class="btn btn-primary"
                                onclick="return confirm('Are you sure you want to return this asset?');">

                            <i class="bi bi-box-arrow-in-left me-1"></i>

                            Confirm Return

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</x-app-layout>