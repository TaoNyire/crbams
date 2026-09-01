<x-app-layout>
    <x-slot name="title">Edit {{ $user->name }}</x-slot>
    <div class="crb-page-title"><h1>Edit User Account</h1><p>Update the user role or reset their password when required.</p></div>
    <form method="POST" action="{{ route('users.update', $user) }}" class="crb-form-card">@csrf @method('PUT')
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Full Name</label><input name="name" class="form-control" value="{{ old('name', $user->name) }}" required></div>
            <div class="col-md-6"><label class="form-label">Email Address</label><input name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required></div>
            <div class="col-md-6"><label class="form-label">Role</label><select name="role" class="form-select" required><option value="hardware_officer" @selected(old('role', $user->role) === 'hardware_officer')>Hardware Officer</option><option value="administration_officer" @selected(old('role', $user->role) === 'administration_officer')>Administration Officer</option></select></div>
            <div class="col-md-6"><label class="form-label">Management Area</label><input class="form-control" value="Set automatically from the selected role" readonly></div>
            <div class="col-md-6"><label class="form-label">New Password <span class="text-muted">(optional)</span></label><input name="password" type="password" class="form-control" autocomplete="new-password"></div>
            <div class="col-md-6"><label class="form-label">Confirm New Password</label><input name="password_confirmation" type="password" class="form-control" autocomplete="new-password"></div>
        </div>
        <div class="mt-4 d-flex gap-2"><button type="submit" class="btn btn-crb">Save Changes</button><a href="{{ route('users.index') }}" class="btn btn-light">Cancel</a></div>
    </form>
</x-app-layout>
