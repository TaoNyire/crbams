<x-app-layout>
    <x-slot name="title">Asset Categories</x-slot>

    <div class="crb-page-title d-flex justify-content-between align-items-center">
        <div><h1>Asset Categories</h1><p>Manage the categories assigned to your management area.</p></div>
        <a href="{{ route('asset-categories.create') }}" class="btn btn-crb"><i class="bi bi-plus-lg me-1"></i>Add Category</a>
    </div>

    <div class="crb-card">
        <div class="table-responsive">
            <table class="table crb-table">
                <thead><tr><th>Category</th><th>Responsible Area</th><th>Asset Types</th><th>Assets</th><th>Status</th><th></th></tr></thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td><strong>{{ $category->name }}</strong><div class="crb-muted">{{ $category->description ?: 'No description' }}</div></td>
                            <td>{{ str($category->responsible_officer)->replace('_', ' ')->title() }}</td>
                            <td>{{ $category->asset_types_count }}</td><td>{{ $category->assets_count }}</td>
                            <td><span class="crb-status {{ $category->is_active ? 'available' : 'retired' }}"><span class="crb-status-dot"></span>{{ $category->is_active ? 'Active' : 'Inactive' }}</span></td>
                            <td class="text-end"><a href="{{ route('asset-categories.show', $category) }}" class="crb-action-btn" title="View category"><i class="bi bi-eye"></i></a><a href="{{ route('asset-categories.edit', $category) }}" class="crb-action-btn ms-1" title="Edit category"><i class="bi bi-pencil"></i></a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center py-5 text-muted">No categories have been created for this management area.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($categories->hasPages())<div class="crb-pagination">{{ $categories->links() }}</div>@endif
    </div>
</x-app-layout>
