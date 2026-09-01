<x-app-layout>
    <x-slot name="title">Register Asset</x-slot>

<div class="crb-page-title d-flex justify-content-between align-items-center">

    <div>
        <h1>Register New Asset</h1>
        <p>
            Add a new asset to the CRB asset register.
        </p>
    </div>

    <a href="{{ route('assets.index') }}" class="btn btn-crb">
        <i class="bi bi-arrow-left me-1"></i>
        Back to Assets
    </a>

</div>


{{-- =========================================================
     VALIDATION ERRORS
========================================================= --}}

@if ($errors->any())

    <div class="alert alert-danger mb-4">

        <div class="fw-semibold mb-2">
            <i class="bi bi-exclamation-triangle me-1"></i>
            Please correct the following errors:
        </div>

        <ul class="mb-0 ps-3">

            @foreach ($errors->all() as $error)

                <li>{{ $error }}</li>

            @endforeach

        </ul>

    </div>

@endif


<form
    action="{{ route('assets.store') }}"
    method="POST"
>

    @csrf


    {{-- =========================================================
         ASSET INFORMATION
    ========================================================== --}}

    <div class="crb-form-card mb-4">

        <div class="crb-form-section">

            <div class="crb-form-section-title">

                <i class="bi bi-box-seam me-2"></i>

                Asset Information

            </div>


            <div class="row g-3">


                {{-- =================================================
                     ASSET CODE
                ================================================== --}}

                <div class="col-md-6">

                    <label class="form-label">
                        Asset Code
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        name="asset_code"
                        class="form-control"
                        value="{{ old('asset_code') }}"
                        placeholder="e.g. CRB-IT-0001"
                        required
                    >

                    <small class="text-muted">
                        Unique identification code for the asset.
                    </small>

                </div>


                {{-- =================================================
                     ASSET NAME
                ================================================== --}}

                <div class="col-md-6">

                    <label class="form-label">
                        Asset Name
                        <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        name="asset_name"
                        class="form-control"
                        value="{{ old('asset_name') }}"
                        placeholder="e.g. HP EliteBook Laptop"
                        required
                    >

                </div>


                {{-- =================================================
                     SERIAL NUMBER
                ================================================== --}}

                <div class="col-md-6">

                    <label class="form-label">
                        Serial Number
                    </label>

                    <input
                        type="text"
                        name="serial_number"
                        class="form-control"
                        value="{{ old('serial_number') }}"
                        placeholder="Enter serial number"
                    >

                </div>


                {{-- =================================================
                     BARCODE
                ================================================== --}}

                <div class="col-md-6">

                    <label class="form-label">
                        Barcode
                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="bi bi-upc-scan"></i>
                        </span>

                        <input
                            type="text"
                            name="barcode"
                            class="form-control"
                            value="{{ old('barcode') }}"
                            placeholder="Enter or scan barcode"
                        >

                    </div>

                </div>


                {{-- =================================================
                     CATEGORY
                ================================================== --}}

                <div class="col-md-6">

                    <label class="form-label">
                        Asset Category
                        <span class="text-danger">*</span>
                    </label>

                    <select
                        name="asset_category_id"
                        id="asset_category_id"
                        class="form-select"
                        required
                    >

                        <option value="">
                            -- Select Category --
                        </option>

                        @foreach ($categories as $category)

                            <option
                                value="{{ $category->id }}"
                                data-responsible-officer="{{ $category->responsible_officer }}"
                                {{ old('asset_category_id') == $category->id ? 'selected' : '' }}
                            >

                                {{ $category->name }}

                            </option>

                        @endforeach

                    </select>

                    <small class="text-muted">
                        Select the category that matches the asset.
                    </small>

                </div>


                {{-- =================================================
                     MANAGEMENT AREA / RESPONSIBLE OFFICER
                ================================================== --}}

                <div class="col-md-6">

                    <label class="form-label">
                        Management Area
                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="bi bi-person-badge"></i>
                        </span>

                        <input
                            type="text"
                            id="management_area_display"
                            class="form-control"
                            value=""
                            placeholder="Select an asset category"
                            readonly
                        >

                    </div>

                    <small class="text-muted">
                        The officer responsible for managing this asset.
                    </small>

                </div>


                {{-- =================================================
                     ASSET TYPE
                ================================================== --}}

                <div class="col-md-6">

                    <label class="form-label">
                        Asset Type
                        <span class="text-danger">*</span>
                    </label>

                    <select
                        name="asset_type_id"
                        id="asset_type_id"
                        class="form-select"
                        required
                    >

                        <option value="">
                            -- Select Asset Type --
                        </option>

                        @foreach ($categories as $category)

                            @if ($category->assetTypes->count())

                                <optgroup
                                    label="{{ $category->name }}"
                                    data-category-id="{{ $category->id }}"
                                >

                                    @foreach ($category->assetTypes as $type)

                                        <option
                                            value="{{ $type->id }}"
                                            data-category-id="{{ $category->id }}"
                                            {{ old('asset_type_id') == $type->id ? 'selected' : '' }}
                                        >

                                            {{ $type->name }}

                                        </option>

                                    @endforeach

                                </optgroup>

                            @endif

                        @endforeach

                    </select>

                    <small class="text-muted">
                        Select the specific type of asset.
                    </small>

                </div>


            </div>

        </div>


        {{-- =========================================================
             ASSIGNMENT & LOCATION
        ========================================================== --}}

        <div class="crb-form-section">

            <div class="crb-form-section-title">

                <i class="bi bi-person-workspace me-2"></i>

                Assignment & Location

            </div>


            <div class="row g-3">


                {{-- =================================================
                     DEPARTMENT
                ================================================== --}}

                <div class="col-md-6">

                    <label class="form-label">
                        Department
                    </label>

                    <select
                        name="department_id"
                        class="form-select"
                    >

                        <option value="">
                            -- Unassigned --
                        </option>

                        @foreach ($departments as $department)

                            <option
                                value="{{ $department->id }}"
                                {{ old('department_id') == $department->id ? 'selected' : '' }}
                            >

                                {{ $department->name }}

                            </option>

                        @endforeach

                    </select>

                    <small class="text-muted">
                        Department where the asset is currently located or assigned.
                    </small>

                </div>


                {{-- =================================================
                     EMPLOYEE
                ================================================== --}}

                <div class="col-md-6">

                    <label class="form-label">
                        Employee
                    </label>

                    <select
                        name="employee_id"
                        class="form-select"
                    >

                        <option value="">
                            -- Unassigned --
                        </option>

                        @foreach ($employees as $employee)

                            <option
                                value="{{ $employee->id }}"
                                {{ old('employee_id') == $employee->id ? 'selected' : '' }}
                            >

                                {{ $employee->first_name }}
                                {{ $employee->last_name }}

                                @if ($employee->department)
                                    — {{ $employee->department->name }}
                                @endif

                            </option>

                        @endforeach

                    </select>

                    <small class="text-muted">
                        Employee currently responsible for or using the asset.
                    </small>

                </div>


                {{-- =================================================
                     LOCATION
                ================================================== --}}

                <div class="col-md-12">

                    <label class="form-label">
                        Location
                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="bi bi-geo-alt"></i>
                        </span>

                        <input
                            type="text"
                            name="location"
                            class="form-control"
                            value="{{ old('location') }}"
                            placeholder="e.g. IT Department, Server Room"
                        >

                    </div>

                </div>


            </div>

        </div>


        {{-- =========================================================
             PURCHASE INFORMATION
        ========================================================== --}}

        <div class="crb-form-section">

            <div class="crb-form-section-title">

                <i class="bi bi-receipt me-2"></i>

                Purchase Information

            </div>


            <div class="row g-3">


                {{-- =================================================
                     PURCHASE DATE
                ================================================== --}}

                <div class="col-md-4">

                    <label class="form-label">
                        Purchase Date
                    </label>

                    <input
                        type="date"
                        name="purchase_date"
                        class="form-control"
                        value="{{ old('purchase_date') }}"
                    >

                </div>


                {{-- =================================================
                     PURCHASE PRICE
                ================================================== --}}

                <div class="col-md-4">

                    <label class="form-label">
                        Purchase Price
                    </label>

                    <div class="input-group">

                        <span class="input-group-text">
                            MWK
                        </span>

                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="purchase_price"
                            class="form-control"
                            value="{{ old('purchase_price') }}"
                            placeholder="0.00"
                        >

                    </div>

                </div>


                {{-- =================================================
                     SUPPLIER
                ================================================== --}}

                <div class="col-md-4">

                    <label class="form-label">
                        Supplier
                    </label>

                    <input
                        type="text"
                        name="supplier"
                        class="form-control"
                        value="{{ old('supplier') }}"
                        placeholder="Supplier name"
                    >

                </div>


            </div>

        </div>


        {{-- =========================================================
             CONDITION & STATUS
        ========================================================== --}}

        <div class="crb-form-section">

            <div class="crb-form-section-title">

                <i class="bi bi-clipboard-check me-2"></i>

                Condition & Status

            </div>


            <div class="row g-3">


                {{-- =================================================
                     CONDITION
                ================================================== --}}

                <div class="col-md-6">

                    <label class="form-label">
                        Condition
                        <span class="text-danger">*</span>
                    </label>

                    <select
                        name="condition"
                        class="form-select"
                        required
                    >

                        <option
                            value="new"
                            {{ old('condition', 'new') == 'new' ? 'selected' : '' }}
                        >
                            New
                        </option>

                        <option
                            value="good"
                            {{ old('condition') == 'good' ? 'selected' : '' }}
                        >
                            Good
                        </option>

                        <option
                            value="fair"
                            {{ old('condition') == 'fair' ? 'selected' : '' }}
                        >
                            Fair
                        </option>

                        <option
                            value="poor"
                            {{ old('condition') == 'poor' ? 'selected' : '' }}
                        >
                            Poor
                        </option>

                        <option
                            value="damaged"
                            {{ old('condition') == 'damaged' ? 'selected' : '' }}
                        >
                            Damaged
                        </option>

                    </select>

                </div>


                {{-- =================================================
                     STATUS
                ================================================== --}}

                <div class="col-md-6">

                    <label class="form-label">
                        Asset Status
                        <span class="text-danger">*</span>
                    </label>

                    <select
                        name="status"
                        class="form-select"
                        required
                    >

                        <option
                            value="available"
                            {{ old('status', 'available') == 'available' ? 'selected' : '' }}
                        >
                            Available
                        </option>

                        <option
                            value="assigned"
                            {{ old('status') == 'assigned' ? 'selected' : '' }}
                        >
                            Assigned
                        </option>

                        <option
                            value="under_repair"
                            {{ old('status') == 'under_repair' ? 'selected' : '' }}
                        >
                            Under Repair
                        </option>

                        <option
                            value="disposed"
                            {{ old('status') == 'disposed' ? 'selected' : '' }}
                        >
                            Disposed
                        </option>

                        <option
                            value="lost"
                            {{ old('status') == 'lost' ? 'selected' : '' }}
                        >
                            Lost
                        </option>

                        <option
                            value="retired"
                            {{ old('status') == 'retired' ? 'selected' : '' }}
                        >
                            Retired
                        </option>

                    </select>

                </div>


            </div>

        </div>


        {{-- =========================================================
             ADDITIONAL INFORMATION
        ========================================================== --}}

        <div class="crb-form-section">

            <div class="crb-form-section-title">

                <i class="bi bi-journal-text me-2"></i>

                Additional Information

            </div>


            <div>

                <label class="form-label">
                    Notes
                </label>

                <textarea
                    name="notes"
                    rows="4"
                    class="form-control"
                    placeholder="Enter any additional information about this asset..."
                >{{ old('notes') }}</textarea>

            </div>

        </div>


        {{-- =========================================================
             FORM ACTIONS
        ========================================================== --}}

        <div class="d-flex justify-content-end gap-2 pt-2">

            <a
                href="{{ route('assets.index') }}"
                class="btn btn-light border"
            >

                <i class="bi bi-x-lg me-1"></i>

                Cancel

            </a>


            <button
                type="submit"
                class="btn btn-crb"
            >

                <i class="bi bi-check-lg me-1"></i>

                Register Asset

            </button>

        </div>

    </div>

</form>


{{-- =========================================================
     MANAGEMENT AREA SCRIPT
========================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const categorySelect = document.getElementById('asset_category_id');

    const managementAreaDisplay =
        document.getElementById('management_area_display');

    const assetTypeSelect =
        document.getElementById('asset_type_id');


    function updateManagementArea() {

        const selectedOption =
            categorySelect.options[categorySelect.selectedIndex];

        if (!selectedOption || !selectedOption.value) {

            managementAreaDisplay.value = '';

            managementAreaDisplay.placeholder =
                'Select an asset category';

            return;
        }


        const responsibleOfficer =
            selectedOption.dataset.responsibleOfficer;


        if (responsibleOfficer === 'hardware') {

            managementAreaDisplay.value =
                'Hardware Officer';

        }
        else if (responsibleOfficer === 'administration') {

            managementAreaDisplay.value =
                'Administration Officer';

        }
        else {

            managementAreaDisplay.value =
                'Not specified';

        }

    }


    function filterAssetTypes() {

        const selectedCategory =
            categorySelect.value;


        Array.from(assetTypeSelect.options).forEach(function (option) {

            if (!option.value) {

                option.hidden = false;

                return;
            }


            const optionCategory =
                option.dataset.categoryId;


            if (optionCategory === selectedCategory) {

                option.hidden = false;

            }
            else {

                option.hidden = true;

            }

        });


        const selectedType =
            assetTypeSelect.options[
                assetTypeSelect.selectedIndex
            ];


        if (
            selectedType &&
            selectedType.value &&
            selectedType.dataset.categoryId !== selectedCategory
        ) {

            assetTypeSelect.value = '';

        }

    }


    categorySelect.addEventListener(
        'change',
        function () {

            updateManagementArea();

            filterAssetTypes();

        }
    );


    // Run when page initially loads.
    updateManagementArea();

    filterAssetTypes();

});

</script>

</x-app-layout>
