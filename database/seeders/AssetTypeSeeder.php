<?php

namespace Database\Seeders;

use App\Models\AssetCategory;
use App\Models\AssetType;
use Illuminate\Database\Seeder;

class AssetTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'Computers' => [
                'Desktop Computer',
                'Laptop',
                'Tablet',
                'Workstation',
            ],

            'Servers' => [
                'Tower Server',
                'Rack Server',
                'NAS',
            ],

            'Network Equipment' => [
                'Router',
                'Network Switch',
                'Firewall',
                'Wireless Access Point',
                'Network Cabinet',
                'Network Equipment',
            ],

            'Printers' => [
                'Laser Printer',
                'Inkjet Printer',
                'Multifunction Printer',
                'Scanner',
                'Photocopier',
            ],

            'CCTV' => [
                'CCTV Camera',
                'NVR',
                'DVR',
                'CCTV Monitor',
                'CCTV Storage',
            ],

            'Other IT Assets' => [
                'Projector',
                'UPS',
                'External Storage',
                'Other IT Equipment',
            ],

            'Furniture' => [
                'Office Desk',
                'Office Chair',
                'Cabinet',
                'Filing Cabinet',
                'Conference Table',
                'Other Furniture',
            ],

            'Vehicles' => [
                'Motor Vehicle',
                'Motorcycle',
                'Other Vehicle',
            ],

            'Office Equipment' => [
                'Air Conditioner',
                'Generator',
                'Telephone',
                'Office Equipment',
            ],

            'Other Assets' => [
                'Other Asset',
            ],
        ];

        foreach ($types as $categoryName => $typeNames) {
            $category = AssetCategory::where('name', $categoryName)->first();

            if (! $category) {
                continue;
            }

            foreach ($typeNames as $typeName) {
                AssetType::create([
                    'asset_category_id' => $category->id,
                    'name' => $typeName,
                    'is_active' => true,
                ]);
            }
        }
    }
}
