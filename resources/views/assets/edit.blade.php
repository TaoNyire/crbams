<x-app-layout>
    <x-slot name="title">Edit {{ $asset->asset_name }}</x-slot>

    <div class="crb-page-title d-flex justify-content-between align-items-center">
        <div>
            <h1>Edit Asset</h1>
            <p>Update the registration details for {{ $asset->asset_code }}.</p>
        </div>

        <a href="{{ route('assets.show', $asset) }}" class="btn btn-light border">
            <i class="bi bi-arrow-left me-1"></i>
            Back to Asset
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <div class="fw-semibold mb-2">Please correct the highlighted fields.</div>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('assets.update', $asset) }}" method="POST" class="crb-form-card">
        @csrf
        @method('PUT')

        <section class="crb-form-section">
            <div class="crb-form-section-title"><i class="bi bi-box-seam me-2"></i>Asset Information</div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="asset_code" class="form-label">Asset Code</label>
                    <input id="asset_code" type="text" name="asset_code" class="form-control" value="{{ old('asset_code', $asset->asset_code) }}" required>
                </div>

                <div class="col-md-6">
                    <label for="asset_name" class="form-label">Asset Name <span class="text-danger">*</span></label>
                    <input id="asset_name" type="text" name="asset_name" class="form-control @error('asset_name') is-invalid @enderror" value="{{ old('asset_name', $asset->asset_name) }}" required>
                    @error('asset_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label for="serial_number" class="form-label">Serial Number</label>
                    <input id="serial_number" type="text" name="serial_number" class="form-control @error('serial_number') is-invalid @enderror" value="{{ old('serial_number', $asset->serial_number) }}">
                    @error('serial_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label for="barcode" class="form-label">Barcode</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-upc-scan"></i></span>
                        <input id="barcode" type="text" name="barcode" class="form-control @error('barcode') is-invalid @enderror" value="{{ old('barcode', $asset->barcode) }}">
                    </div>
                    @error('barcode')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label for="asset_category_id" class="form-label">Asset Category <span class="text-danger">*</span></label>
                    <select id="asset_category_id" name="asset_category_id" class="form-select @error('asset_category_id') is-invalid @enderror" required>
                        <option value="">-- Select Category --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('asset_category_id', $asset->asset_category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('asset_category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label for="asset_type_id" class="form-label">Asset Type <span class="text-danger">*</span></label>
                    <select id="asset_type_id" name="asset_type_id" class="form-select @error('asset_type_id') is-invalid @enderror" required>
                        <option value="">-- Select Type --</option>
                        @foreach ($categories as $category)
                            <optgroup label="{{ $category->name }}">
                                @foreach ($category->assetTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('asset_type_id', $asset->asset_type_id) == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    @error('asset_type_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </section>

        <section class="crb-form-section">
            <div class="crb-form-section-title"><i class="bi bi-person-workspace me-2"></i>Assignment & Location</div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="department_id" class="form-label">Department</label>
                    <select id="department_id" name="department_id" class="form-select">
                        <option value="">-- Unassigned --</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}" {{ old('department_id', $asset->department_id) == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="employee_id" class="form-label">Employee</label>
                    <select id="employee_id" name="employee_id" class="form-select">
                        <option value="">-- Unassigned --</option>
                        @foreach ($employees as $employee)
                            <option value="{{ $employee->id }}" {{ old('employee_id', $asset->employee_id) == $employee->id ? 'selected' : '' }}>{{ $employee->first_name }} {{ $employee->last_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12">
                    <label for="location" class="form-label">Location</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                        <input id="location" type="text" name="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location', $asset->location) }}" placeholder="e.g. IT Department, Server Room">
                    </div>
                    @error('location')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
            </div>
        </section>

        <section class="crb-form-section">
            <div class="crb-form-section-title"><i class="bi bi-receipt me-2"></i>Purchase Information</div>

            <div class="row g-3">
                <div class="col-md-4">
                    <label for="purchase_date" class="form-label">Purchase Date</label>
                    <input id="purchase_date" type="date" name="purchase_date" class="form-control" value="{{ old('purchase_date', $asset->purchase_date?->format('Y-m-d')) }}">
                </div>

                <div class="col-md-4">
                    <label for="purchase_price" class="form-label">Purchase Price</label>
                    <div class="input-group">
                        <span class="input-group-text">MWK</span>
                        <input id="purchase_price" type="number" step="0.01" min="0" name="purchase_price" class="form-control" value="{{ old('purchase_price', $asset->purchase_price) }}">
                    </div>
                </div>

                <div class="col-md-4">
                    <label for="supplier" class="form-label">Supplier</label>
                    <input id="supplier" type="text" name="supplier" class="form-control" value="{{ old('supplier', $asset->supplier) }}">
                </div>
            </div>
        </section>

        <section class="crb-form-section">
            <div class="crb-form-section-title"><i class="bi bi-clipboard-check me-2"></i>Condition & Status</div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="condition" class="form-label">Condition <span class="text-danger">*</span></label>
                    <select id="condition" name="condition" class="form-select" required>
                        @foreach (['new', 'good', 'fair', 'poor', 'damaged'] as $condition)
                            <option value="{{ $condition }}" {{ old('condition', $asset->condition) === $condition ? 'selected' : '' }}>{{ ucfirst($condition) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="status" class="form-label">Asset Status <span class="text-danger">*</span></label>
                    <select id="status" name="status" class="form-select" required>
                        @foreach (['available', 'assigned', 'under_repair', 'disposed', 'lost', 'retired'] as $status)
                            <option value="{{ $status }}" {{ old('status', $asset->status) === $status ? 'selected' : '' }}>{{ ucwords(str_replace('_', ' ', $status)) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </section>

        <section class="crb-form-section">
            <div class="crb-form-section-title"><i class="bi bi-journal-text me-2"></i>Additional Information</div>
            <label for="notes" class="form-label">Notes</label>
            <textarea id="notes" name="notes" rows="4" class="form-control" placeholder="Enter any additional information about this asset...">{{ old('notes', $asset->notes) }}</textarea>
        </section>

        <div class="d-flex justify-content-end gap-2 pt-2">
            <a href="{{ route('assets.show', $asset) }}" class="btn btn-light border">Cancel</a>
            <button type="submit" class="btn btn-crb"><i class="bi bi-check-lg me-1"></i>Save Changes</button>
        </div>
    </form>
</x-app-layout>
