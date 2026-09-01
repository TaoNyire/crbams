<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // System Administrator
        User::factory()->create([
            'name' => 'System Administrator',
            'email' => 'admin@crb.test',
            'role' => 'system_admin',
            'management_area' => null,
        ]);

        // Hardware Officer
        User::factory()->create([
            'name' => 'Hardware Officer',
            'email' => 'hardware@crb.test',
            'role' => 'hardware_officer',
            'management_area' => 'hardware',
        ]);

        // Administration Officer
        User::factory()->create([
            'name' => 'Administration Officer',
            'email' => 'administration@crb.test',
            'role' => 'administration_officer',
            'management_area' => 'administration',
        ]);

        $this->call([
            AssetCategorySeeder::class,
            AssetTypeSeeder::class,
        ]);
    }
}