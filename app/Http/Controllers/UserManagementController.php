<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class UserManagementController extends Controller
{
    public function index(): View
    {
        $users = User::query()
            ->whereIn('role', ['hardware_officer', 'administration_officer'])
            ->orderBy('name')
            ->paginate(15);

        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        return view('users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedUserData($request);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'management_area' => $this->managementAreaFor($validated['role']),
            'email_verified_at' => now(),
        ]);

        return redirect()->route('users.index')->with('success', 'User account created successfully.');
    }

    public function edit(User $user): View
    {
        $this->ensureOperationalUser($user);

        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->ensureOperationalUser($user);

        $validated = $this->validatedUserData($request, $user);

        $attributes = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'management_area' => $this->managementAreaFor($validated['role']),
        ];

        if (filled($validated['password'] ?? null)) {
            $attributes['password'] = Hash::make($validated['password']);
        }

        $user->update($attributes);

        return redirect()->route('users.index')->with('success', 'User account updated successfully.');
    }

    /**
     * @return array{name: string, email: string, password?: string, role: string}
     */
    private function validatedUserData(Request $request, ?User $user = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user)],
            'password' => $user
                ? ['nullable', 'confirmed', Rules\Password::defaults()]
                : ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', Rule::in(['hardware_officer', 'administration_officer'])],
        ]);
    }

    private function managementAreaFor(string $role): string
    {
        return $role === 'hardware_officer' ? 'hardware' : 'administration';
    }

    private function ensureOperationalUser(User $user): void
    {
        abort_unless(
            in_array($user->role, ['hardware_officer', 'administration_officer'], true),
            403,
            'System administrator accounts cannot be managed from this screen.'
        );
    }
}
