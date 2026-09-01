<x-app-layout>
    <x-slot name="title">User Accounts</x-slot>

    <div class="crb-page-title d-flex justify-content-between align-items-center">
        <div><h1>User Accounts</h1><p>Create and manage hardware and administration officer accounts.</p></div>
        <a href="{{ route('users.create') }}" class="btn btn-crb"><i class="bi bi-person-plus me-1"></i>Create User</a>
    </div>

    <div class="crb-card">
        <div class="table-responsive">
            <table class="table crb-table">
                <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Management Area</th><th></th></tr></thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td><strong>{{ $user->name }}</strong></td>
                            <td>{{ $user->email }}</td>
                            <td>{{ str($user->role)->replace('_', ' ')->title() }}</td>
                            <td>{{ str($user->management_area ?? 'system')->replace('_', ' ')->title() }}</td>
                            <td class="text-end"><a href="{{ route('users.edit', $user) }}" class="crb-action-btn" title="Edit user"><i class="bi bi-pencil"></i></a></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-5 text-muted">No user accounts have been created.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($users->hasPages())<div class="crb-pagination">{{ $users->links() }}</div>@endif
    </div>
</x-app-layout>
