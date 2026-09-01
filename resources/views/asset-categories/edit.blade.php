<x-app-layout>
    <x-slot name="title">Edit {{ $assetCategory->name }}</x-slot>
    <div class="crb-page-title"><h1>Edit Asset Category</h1><p>Update category information and management responsibility.</p></div>
    <form method="POST" action="{{ route('asset-categories.update', $assetCategory) }}" class="crb-form-card">@csrf @method('PUT')
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Category Name</label><input name="name" class="form-control" value="{{ old('name', $assetCategory->name) }}" required></div>
            @if ($canChooseResponsibleOfficer)<div class="col-md-6"><label class="form-label">Responsible Area</label><select name="responsible_officer" class="form-select" required><option value="hardware" @selected(old('responsible_officer', $assetCategory->responsible_officer) === 'hardware')>Hardware</option><option value="administration" @selected(old('responsible_officer', $assetCategory->responsible_officer) === 'administration')>Administration</option></select></div>@endif
            <div class="col-12"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="4">{{ old('description', $assetCategory->description) }}</textarea></div>
            <div class="col-12"><div class="form-check"><input name="is_active" value="1" type="checkbox" class="form-check-input" id="is_active" @checked(old('is_active', $assetCategory->is_active))><label for="is_active" class="form-check-label">Category is active</label></div></div>
        </div>
        <div class="mt-4 d-flex gap-2"><button class="btn btn-crb" type="submit">Save Changes</button><a href="{{ route('asset-categories.index') }}" class="btn btn-light">Cancel</a></div>
    </form>
</x-app-layout>
