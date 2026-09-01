<x-app-layout>
    <x-slot name="title">{{ $title }}</x-slot>

    <div class="crb-page-title d-flex justify-content-between align-items-center">
        <div>
            <h1>{{ $title }}</h1>
            <p>{{ $description }}</p>
        </div>

        <a href="{{ route('assets.create') }}" class="btn btn-crb">
            <i class="bi bi-plus-lg me-1"></i>
            {{ $registerLabel }}
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6"><div class="crb-stat-card"><div class="crb-stat-top"><div class="crb-stat-icon"><i class="bi {{ $areaIcon }}"></i></div></div><div class="crb-stat-value">{{ $totalAssets }}</div><div class="crb-stat-label">{{ $areaLabel }} Assets</div></div></div>
        <div class="col-xl-3 col-md-6"><div class="crb-stat-card"><div class="crb-stat-top"><div class="crb-stat-icon"><i class="bi bi-person-check"></i></div></div><div class="crb-stat-value">{{ $assignedAssets }}</div><div class="crb-stat-label">Assigned Assets</div></div></div>
        <div class="col-xl-3 col-md-6"><div class="crb-stat-card"><div class="crb-stat-top"><div class="crb-stat-icon"><i class="bi bi-check-circle"></i></div></div><div class="crb-stat-value">{{ $availableAssets }}</div><div class="crb-stat-label">Available Assets</div></div></div>
        <div class="col-xl-3 col-md-6"><div class="crb-stat-card"><div class="crb-stat-top"><div class="crb-stat-icon"><i class="bi bi-tools"></i></div></div><div class="crb-stat-value">{{ $repairAssets }}</div><div class="crb-stat-label">Under Repair</div></div></div>
    </div>

    <div class="crb-card">
        <div class="crb-card-header">
            <div><h5>Recent {{ $areaLabel }} Assets</h5><small>Latest assets registered in your management area.</small></div>
            <a href="{{ route('assets.index') }}" class="btn btn-sm btn-light">View assets <i class="bi bi-arrow-right ms-1"></i></a>
        </div>
        <div class="table-responsive">
            <table class="table crb-table">
                <thead><tr><th>Asset</th><th>Category</th><th>Location</th><th>Assigned To</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse ($recentAssets as $asset)
                        <tr>
                            <td><a href="{{ route('assets.show', $asset) }}" class="crb-asset-code">{{ $asset->asset_code }}</a><div class="crb-muted">{{ $asset->asset_name }}</div></td>
                            <td>{{ $asset->category?->name ?? 'Not specified' }}</td>
                            <td>{{ $asset->location ?? 'Not specified' }}</td>
                            <td>{{ $asset->employee ? $asset->employee->first_name.' '.$asset->employee->last_name : 'Unassigned' }}</td>
                            <td><span class="crb-status {{ $asset->status === 'under_repair' ? 'repair' : $asset->status }}"><span class="crb-status-dot"></span>{{ str($asset->status)->replace('_', ' ')->title() }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-5 text-muted">No {{ strtolower($areaLabel) }} assets have been registered yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
