<?php

namespace Database\Seeders;

use App\Models\InventoryItem;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name' => 'Dental Gloves (Box)', 'unit' => 'box', 'quantity_on_hand' => 50, 'reorder_level' => 10, 'unit_cost' => 5],
            ['name' => 'Face Masks (Box)', 'unit' => 'box', 'quantity_on_hand' => 40, 'reorder_level' => 10, 'unit_cost' => 4],
            ['name' => 'Composite Filling Material', 'unit' => 'pcs', 'quantity_on_hand' => 15, 'reorder_level' => 5, 'unit_cost' => 12],
            ['name' => 'Local Anesthetic Cartridges', 'unit' => 'box', 'quantity_on_hand' => 8, 'reorder_level' => 10, 'unit_cost' => 20],
            ['name' => 'Dental Bibs', 'unit' => 'pcs', 'quantity_on_hand' => 200, 'reorder_level' => 50, 'unit_cost' => 0.2],
            ['name' => 'Sterilization Pouches', 'unit' => 'pcs', 'quantity_on_hand' => 5, 'reorder_level' => 20, 'unit_cost' => 0.5],
            ['name' => 'X-Ray Film', 'unit' => 'box', 'quantity_on_hand' => 12, 'reorder_level' => 5, 'unit_cost' => 25],
            ['name' => 'Suture Kits', 'unit' => 'pcs', 'quantity_on_hand' => 18, 'reorder_level' => 10, 'unit_cost' => 8],
        ];

        foreach ($items as $i => $item) {
            InventoryItem::updateOrCreate(
                ['item_code' => 'INV-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT)],
                $item + ['supplier' => 'MedSupply Co.', 'is_active' => true]
            );
        }
    }
}
