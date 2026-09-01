<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('public registration is unavailable', function (): void {
    $this->get('/register')->assertNotFound();
});

test('system administrators can create an administration officer account', function (): void {
    $systemAdministrator = User::factory()->create([
        'role' => 'system_admin',
        'management_area' => null,
    ]);

    $this->actingAs($systemAdministrator)
        ->post(route('users.store'), [
            'name' => 'Administration User',
            'email' => 'administration@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'role' => 'administration_officer',
        ])
        ->assertRedirect(route('users.index'));

    $user = User::where('email', 'administration@example.com')->firstOrFail();

    expect($user->role)->toBe('administration_officer')
        ->and($user->management_area)->toBe('administration')
        ->and($user->email_verified_at)->not->toBeNull();
});

test('created hardware officers can log in and access the hardware dashboard', function (): void {
    $hardwareOfficer = User::factory()->create([
        'role' => 'hardware_officer',
        'management_area' => 'hardware',
    ]);

    $this->post('/login', [
        'email' => $hardwareOfficer->email,
        'password' => 'password',
    ])->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticatedAs($hardwareOfficer);

    $this->get(route('dashboard'))
        ->assertRedirect(route('hardware.dashboard'));
});

test('officers cannot access user management', function (): void {
    $administrationOfficer = User::factory()->create([
        'role' => 'administration_officer',
        'management_area' => 'administration',
    ]);

    $this->actingAs($administrationOfficer)
        ->get(route('users.index'))
        ->assertForbidden();
});
