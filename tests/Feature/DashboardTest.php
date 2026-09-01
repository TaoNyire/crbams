<?php

use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\AssetType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('users are redirected to their role-specific dashboard', function (string $role, ?string $managementArea, string $routeName): void {
    $user = User::factory()->create([
        'role' => $role,
        'management_area' => $managementArea,
    ]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertRedirect(route($routeName));
})->with([
    ['hardware_officer', 'hardware', 'hardware.dashboard'],
    ['administration_officer', 'administration', 'administration.dashboard'],
    ['system_admin', null, 'system-admin.dashboard'],
]);

test('hardware dashboard only displays hardware assets', function (): void {
    $hardwareUser = User::factory()->create([
        'role' => 'hardware_officer',
        'management_area' => 'hardware',
    ]);

    createAssetForOfficer('hardware', 'HW-001');
    createAssetForOfficer('administration', 'ADM-001');

    $this->actingAs($hardwareUser)
        ->get(route('hardware.dashboard'))
        ->assertOk()
        ->assertSee('HW-001')
        ->assertDontSee('ADM-001');
});

test('system administrators can see both management areas and user access totals', function (): void {
    $systemAdministrator = User::factory()->create([
        'role' => 'system_admin',
        'management_area' => null,
    ]);

    User::factory()->create(['role' => 'hardware_officer', 'management_area' => 'hardware']);
    User::factory()->create(['role' => 'administration_officer', 'management_area' => 'administration']);
    createAssetForOfficer('hardware', 'HW-001');
    createAssetForOfficer('administration', 'ADM-001');

    $this->actingAs($systemAdministrator)
        ->get(route('system-admin.dashboard'))
        ->assertOk()
        ->assertSee('HW-001')
        ->assertSee('ADM-001')
        ->assertSee('User Access Summary');
});

function createAssetForOfficer(string $responsibleOfficer, string $assetCode): Asset
{
    $category = AssetCategory::create([
        'name' => $responsibleOfficer.' '.$assetCode,
        'responsible_officer' => $responsibleOfficer,
        'is_active' => true,
    ]);

    $assetType = AssetType::create([
        'asset_category_id' => $category->id,
        'name' => 'Standard',
        'is_active' => true,
    ]);

    return Asset::create([
        'asset_code' => $assetCode,
        'asset_name' => $assetCode.' Asset',
        'asset_category_id' => $category->id,
        'asset_type_id' => $assetType->id,
        'status' => 'available',
    ]);
}
