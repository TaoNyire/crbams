<?php

namespace Database\Seeders;

use App\Models\AssetCategory;
use Illuminate\Database\Seeder;

class AssetCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Computers',
                'responsible_officer' => 'hardware',
                'description' => 'Desktop computers, laptops, and related computer equipment.',
                'is_active' => true,
            ],
            [
                'name' => 'Servers',
                'responsible_officer' => 'hardware',
                'description' => 'Physical servers and server hardware.',
                'is_active' => true,
            ],
            [
                'name' => 'Network Equipment',
                'responsible_officer' => 'hardware',
                'description' => 'Routers, switches, firewalls, access points, and related equipment.',
                'is_active' => true,
            ],
            [
                'name' => 'Printers',
                'responsible_officer' => 'hardware',
                'description' => 'Printers, scanners, photocopiers, and related equipment.',
                'is_active' => true,
            ],
            [
                'name' => 'CCTV',
                'responsible_officer' => 'hardware',
                'description' => 'CCTV cameras, NVRs, DVRs, monitors, and related security equipment.',
                'is_active' => true,
            ],
            [
                'name' => 'Other IT Assets',
                'responsible_officer' => 'hardware',
                'description' => 'Other information technology equipment.',
                'is_active' => true,
            ],
            [
                'name' => 'Furniture',
                'responsible_officer' => 'administration',
                'description' => 'Desks, chairs, cabinets, tables, and other furniture.',
                'is_active' => true,
            ],
            [
                'name' => 'Vehicles',
                'responsible_officer' => 'administration',
                'description' => 'Vehicles and other transport assets.',
                'is_active' => true,
            ],
            [
                'name' => 'Office Equipment',
                'responsible_officer' => 'administration',
                'description' => 'General office equipment managed by Administration.',
                'is_active' => true,
            ],
            [
                'name' => 'Other Assets',
                'responsible_officer' => 'administration',
                'description' => 'Other non-IT assets.',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            AssetCategory::create($category);
        }
    }
}
