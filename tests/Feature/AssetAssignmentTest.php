<?php

use App\Models\Asset;
use App\Models\AssetAssignment;
use App\Models\AssetCategory;
use App\Models\AssetType;
use App\Models\Department;
use App\Models\Employee;
use App\Models\User;

test('an available asset can be assigned and its movement is recorded', function (): void {
    $assignment = assignmentContext();

    $this->actingAs($assignment['officer'])
        ->post(route('assets.assign.store', $assignment['asset']), [
            'employee_id' => $assignment['employee']->id,
            'department_id' => $assignment['department']->id,
            'location' => 'Head Office',
            'notes' => 'Issued with charger.',
        ])
        ->assertRedirect(route('assets.show', $assignment['asset']));

    $this->assertDatabaseHas('asset_assignments', [
        'asset_id' => $assignment['asset']->id,
        'employee_id' => $assignment['employee']->id,
        'department_id' => $assignment['department']->id,
        'assigned_by' => $assignment['officer']->id,
        'notes' => 'Issued with charger.',
    ]);

    expect($assignment['asset']->refresh())
        ->status->toBe('assigned')
        ->employee_id->toBe($assignment['employee']->id)
        ->department_id->toBe($assignment['department']->id)
        ->location->toBe('Head Office')
        ->notes->toBe('Original asset note.');
});

test('only available assets can be assigned', function (): void {
    $assignment = assignmentContext(['status' => 'under_repair']);

    $this->actingAs($assignment['officer'])
        ->post(route('assets.assign.store', $assignment['asset']), [
            'employee_id' => $assignment['employee']->id,
            'department_id' => $assignment['department']->id,
        ])
        ->assertStatus(422);

    $this->assertDatabaseMissing('asset_assignments', [
        'asset_id' => $assignment['asset']->id,
    ]);
});

test('operational officers can work with assignments while system administrators are view only', function (): void {
    $hardwareAssignment = assignmentContext();
    $administrationAssignment = assignmentContext();
    $administrationAssignment['asset']->category->update([
        'responsible_officer' => 'administration',
    ]);

    $administrationOfficer = User::factory()->create([
        'role' => 'administration_officer',
        'management_area' => 'administration',
    ]);

    $systemAdministrator = User::factory()->create([
        'role' => 'system_admin',
        'management_area' => null,
    ]);

    $this->actingAs($hardwareAssignment['officer'])
        ->get(route('assignments.index'))
        ->assertOk()
        ->assertSee(route('assets.assign', $hardwareAssignment['asset']))
        ->assertSee('Assign');

    $this->actingAs($administrationOfficer)
        ->get(route('assignments.index'))
        ->assertOk()
        ->assertSee(route('assets.assign', $administrationAssignment['asset']))
        ->assertSee('Assign');

    $this->actingAs($systemAdministrator)
        ->get(route('assignments.index'))
        ->assertOk()
        ->assertSee('View Only')
        ->assertDontSee(route('assets.assign', $hardwareAssignment['asset']));

    $this->actingAs($systemAdministrator)
        ->get(route('assets.assign', $hardwareAssignment['asset']))
        ->assertForbidden();
});

test('returning an asset records the return movement and audit user', function (): void {
    $assignment = assignmentContext(['status' => 'assigned']);
    $assignedAt = now()->subHour();

    AssetAssignment::create([
        'asset_id' => $assignment['asset']->id,
        'employee_id' => $assignment['employee']->id,
        'department_id' => $assignment['department']->id,
        'assigned_by' => $assignment['officer']->id,
        'assigned_at' => $assignedAt,
    ]);

    $returnedAt = now()->subMinutes(10);

    $this->actingAs($assignment['officer'])
        ->post(route('assets.return', $assignment['asset']), [
            'returned_at' => $returnedAt->format('Y-m-d H:i:s'),
            'returned_condition' => 'needs_repair',
            'return_notes' => 'Screen flickers intermittently.',
        ])
        ->assertRedirect(route('assets.show', $assignment['asset']));

    $this->assertDatabaseHas('asset_assignments', [
        'asset_id' => $assignment['asset']->id,
        'returned_by' => $assignment['officer']->id,
        'returned_condition' => 'needs_repair',
        'return_notes' => 'Screen flickers intermittently.',
    ]);

    expect($assignment['asset']->refresh())
        ->status->toBe('under_repair')
        ->employee_id->toBeNull()
        ->department_id->toBeNull();
});

test('an asset cannot be returned before it was assigned', function (): void {
    $assignment = assignmentContext(['status' => 'assigned']);

    AssetAssignment::create([
        'asset_id' => $assignment['asset']->id,
        'employee_id' => $assignment['employee']->id,
        'department_id' => $assignment['department']->id,
        'assigned_by' => $assignment['officer']->id,
        'assigned_at' => now()->subHour(),
    ]);

    $this->actingAs($assignment['officer'])
        ->post(route('assets.return', $assignment['asset']), [
            'returned_at' => now()->subHours(2)->format('Y-m-d H:i:s'),
            'returned_condition' => 'good',
        ])
        ->assertStatus(422);

    expect($assignment['asset']->refresh()->status)->toBe('assigned');
});

test('assets with assignment history cannot be deleted', function (): void {
    $assignment = assignmentContext(['status' => 'assigned']);

    AssetAssignment::create([
        'asset_id' => $assignment['asset']->id,
        'employee_id' => $assignment['employee']->id,
        'department_id' => $assignment['department']->id,
        'assigned_by' => $assignment['officer']->id,
        'assigned_at' => now(),
    ]);

    $this->actingAs($assignment['officer'])
        ->delete(route('assets.destroy', $assignment['asset']))
        ->assertRedirect(route('assets.show', $assignment['asset']));

    $this->assertDatabaseHas('assets', [
        'id' => $assignment['asset']->id,
    ]);
});

/**
 * @param  array{status?: string}  $assetAttributes
 * @return array{asset: Asset, department: Department, employee: Employee, officer: User}
 */
function assignmentContext(array $assetAttributes = []): array
{
    $officer = User::factory()->create([
        'role' => 'hardware_officer',
        'management_area' => 'hardware',
    ]);

    $department = Department::create([
        'name' => fake()->unique()->company(),
        'is_active' => true,
    ]);

    $employee = Employee::create([
        'employee_number' => fake()->unique()->numerify('EMP-####'),
        'first_name' => fake()->firstName(),
        'last_name' => fake()->lastName(),
        'department_id' => $department->id,
        'is_active' => true,
    ]);

    $category = AssetCategory::create([
        'name' => fake()->unique()->words(2, true),
        'responsible_officer' => 'hardware',
        'is_active' => true,
    ]);

    $type = AssetType::create([
        'asset_category_id' => $category->id,
        'name' => fake()->unique()->word(),
        'is_active' => true,
    ]);

    $asset = Asset::create([
        'asset_code' => fake()->unique()->bothify('HW-####'),
        'asset_name' => fake()->words(2, true),
        'asset_category_id' => $category->id,
        'asset_type_id' => $type->id,
        'status' => $assetAttributes['status'] ?? 'available',
        'notes' => 'Original asset note.',
        'department_id' => ($assetAttributes['status'] ?? null) === 'assigned'
            ? $department->id
            : null,
        'employee_id' => ($assetAttributes['status'] ?? null) === 'assigned'
            ? $employee->id
            : null,
    ]);

    return compact('asset', 'department', 'employee', 'officer');
}
