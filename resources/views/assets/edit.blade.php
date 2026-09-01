<x-app-layout>
    <x-slot name="title">Edit {{ $asset->asset_name }}</x-slot>
    <div class="crb-page-title d-flex justify-content-between align-items-center"><div><h1>Edit Asset</h1><p>Update the asset record and assignment details.</p></div><a href="{{ route('assets.show', $asset) }}" class="btn btn-light">Cancel</a></div>
    <form action="{{ route('assets.update', $asset) }}" method="POST" class="crb-form-card">
        @csrf
        @method('PUT')

        <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Asset Code</label>
            <input
                type="text"
                name="asset_code"
                value="{{ old('asset_code', $asset->asset_code) }}"
                required
            >
        </div>

        <div class="col-md-6">
            <label class="form-label">Asset Name</label>
            <input
                type="text"
                name="asset_name"
                value="{{ old('asset_name', $asset->asset_name) }}"
                required
            >
        </div>

        <div class="col-md-6">
            <label class="form-label">Serial Number</label>
            <input
                type="text"
                name="serial_number"
                value="{{ old('serial_number', $asset->serial_number) }}"
            >
        </div>

        <div class="col-md-6">
            <label class="form-label">Asset Category</label>
            <select name="asset_category_id" required>
                <option value="">-- Select Category --</option>

                @foreach ($categories as $category)
                    <option
                        value="{{ $category->id }}"
                        {{ old('asset_category_id', $asset->asset_category_id) == $category->id ? 'selected' : '' }}
                    >
                        {{ $category->name }} ({{ str($category->responsible_officer)->title() }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Asset Type</label>
            <select name="asset_type_id" required>
                <option value="">-- Select Type --</option>

                @foreach ($categories as $category)
                    <optgroup label="{{ $category->name }}">
                        @foreach ($category->assetTypes as $type)
                            <option
                                value="{{ $type->id }}"
                                {{ old('asset_type_id', $asset->asset_type_id) == $type->id ? 'selected' : '' }}
                            >
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Department</label>
            <select name="department_id">
                <option value="">-- Unassigned --</option>

                @foreach ($departments as $department)
                    <option
                        value="{{ $department->id }}"
                        {{ old('department_id', $asset->department_id) == $department->id ? 'selected' : '' }}
                    >
                        {{ $department->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Employee</label>
            <select name="employee_id">
                <option value="">-- Unassigned --</option>

                @foreach ($employees as $employee)
                    <option
                        value="{{ $employee->id }}"
                        {{ old('employee_id', $asset->employee_id) == $employee->id ? 'selected' : '' }}
                    >
                        {{ $employee->first_name }} {{ $employee->last_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Location</label>
            <input
                type="text"
                name="location"
                value="{{ old('location', $asset->location) }}"
            >
        </div>

        <div class="col-md-6">
            <label class="form-label">Purchase Date</label>
            <input
                type="date"
                name="purchase_date"
                value="{{ old('purchase_date', $asset->purchase_date?->format('Y-m-d')) }}"
            >
        </div>

        <div class="col-md-6">
            <label class="form-label">Purchase Price</label>
            <input
                type="number"
                step="0.01"
                name="purchase_price"
                value="{{ old('purchase_price', $asset->purchase_price) }}"
            >
        </div>

        <div class="col-md-6">
            <label class="form-label">Supplier</label>
            <input
                type="text"
                name="supplier"
                value="{{ old('supplier', $asset->supplier) }}"
            >
        </div>

        <div class="col-md-6">
            <label class="form-label">Condition</label>
            <select name="condition" required>
                @foreach (['new', 'good', 'fair', 'poor', 'damaged'] as $condition)
                    <option
                        value="{{ $condition }}"
                        {{ old('condition', $asset->condition) === $condition ? 'selected' : '' }}
                    >
                        {{ ucfirst($condition) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Status</label>
            <select name="status" required>
                @foreach ([
                    'available',
                    'assigned',
                    'under_repair',
                    'disposed',
                    'lost',
                    'retired'
                ] as $status)
                    <option
                        value="{{ $status }}"
                        {{ old('status', $asset->status) === $status ? 'selected' : '' }}
                    >
                        {{ ucwords(str_replace('_', ' ', $status)) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Barcode</label>
            <input
                type="text"
                name="barcode"
                value="{{ old('barcode', $asset->barcode) }}"
            >
        </div>

        <div class="col-12">
            <label class="form-label">Notes</label>
            <textarea name="notes">{{ old('notes', $asset->notes) }}</textarea>
        </div>

        </div>
        <div class="mt-4"><button type="submit" class="btn btn-crb">Update Asset</button></div>
    </form>
</x-app-layout>
