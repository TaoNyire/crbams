<x-app-layout>
    <x-slot name="title">Create User</x-slot>
    <div class="crb-page-title"><h1>Create User Account</h1><p>Assign the user to Hardware or Administration. Their dashboard access is configured automatically.</p></div>
    <form method="POST" action="{{ route('users.store') }}" class="crb-form-card">@csrf
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label">Full Name</label><input name="name" class="form-control" value="{{ old('name') }}" required></div>
            <div class="col-md-6"><label class="form-label">Email Address</label><input name="email" type="email" class="form-control" value="{{ old('email') }}" required></div>
            <div class="col-md-6"><label class="form-label">Role</label><select name="role" class="form-select" required><option value="">Select role</option><option value="hardware_officer" @selected(old('role') === 'hardware_officer')>Hardware Officer</option><option value="administration_officer" @selected(old('role') === 'administration_officer')>Administration Officer</option></select></div>
            <div class="col-md-6"><label class="form-label">Management Area</label><input class="form-control" value="Set automatically from the selected role" readonly></div>
            <div class="col-md-6"><label class="form-label">Temporary Password</label><input name="password" type="password" class="form-control" required autocomplete="new-password"></div>
            <div class="col-md-6"><label class="form-label">Confirm Password</label><input name="password_confirmation" type="password" class="form-control" required autocomplete="new-password"></div>
        </div>
        <div class="mt-4 d-flex gap-2"><button type="submit" class="btn btn-crb">Create User</button><a href="{{ route('users.index') }}" class="btn btn-light">Cancel</a></div>
    </form>
</x-app-layout>
