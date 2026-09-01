<?php

use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\AssetType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('administration officers only see assets and categories from their management area', function (): void {
    $administrationOfficer = User::factory()->create([
        'role' => 'administration_officer',
        'management_area' => 'administration',
    ]);

    $administrationAsset = createManagedAsset('administration', 'ADM-001');
    $hardwareAsset = createManagedAsset('hardware', 'HW-001');

    $this->actingAs($administrationOfficer)
        ->get(route('assets.index'))
        ->assertOk()
        ->assertSee('ADM-001')
        ->assertDontSee('HW-001');

    $this->actingAs($administrationOfficer)
        ->get(route('assets.show', $hardwareAsset))
        ->assertForbidden();

    $this->actingAs($administrationOfficer)
        ->get(route('asset-categories.index'))
        ->assertOk()
        ->assertSee($administrationAsset->category->name)
        ->assertDontSee($hardwareAsset->category->name);
});

test('administration officers cannot create categories for another management area', function (): void {
    $administrationOfficer = User::factory()->create([
        'role' => 'administration_officer',
        'management_area' => 'administration',
    ]);

    $this->actingAs($administrationOfficer)
        ->post(route('asset-categories.store'), [
            'name' => 'Administration Furniture',
            'responsible_officer' => 'hardware',
            'is_active' => true,
        ])
        ->assertRedirect(route('asset-categories.index'));

    expect(AssetCategory::firstOrFail()->responsible_officer)->toBe('administration');
});

function createManagedAsset(string $responsibleOfficer, string $assetCode): Asset
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
