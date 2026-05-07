<?php

namespace Database\Seeders;

use App\Models\GroceryItem;
use Illuminate\Database\Seeder;

class GroceryItemSeeder extends Seeder
{
    /**
     * Add sample grocery items for group cart creation.
     */
    public function run(): void
    {
        $items = [
            [
                'name' => 'Potato',
                'market_price_per_kg_paisa' => 6000,
                'wholesale_price_per_kg_paisa' => 4200,
                'minimum_bulk_weight_grams' => 50000,
                'minimum_contribution_grams' => 2000,
            ],
            [
                'name' => 'Onion',
                'market_price_per_kg_paisa' => 9000,
                'wholesale_price_per_kg_paisa' => 6500,
                'minimum_bulk_weight_grams' => 40000,
                'minimum_contribution_grams' => 1000,
            ],
            [
                'name' => 'Rice',
                'market_price_per_kg_paisa' => 7500,
                'wholesale_price_per_kg_paisa' => 6200,
                'minimum_bulk_weight_grams' => 100000,
                'minimum_contribution_grams' => 5000,
            ],
            [
                'name' => 'Tomato',
                'market_price_per_kg_paisa' => 10000,
                'wholesale_price_per_kg_paisa' => 7000,
                'minimum_bulk_weight_grams' => 30000,
                'minimum_contribution_grams' => 1000,
            ],
            [
                'name' => 'Green Chili',
                'market_price_per_kg_paisa' => 18000,
                'wholesale_price_per_kg_paisa' => 13000,
                'minimum_bulk_weight_grams' => 20000,
                'minimum_contribution_grams' => 500,
            ],
        ];

        foreach ($items as $item) {
            GroceryItem::updateOrCreate(
                ['name' => $item['name']],
                $item + ['is_active' => true]
            );
        }
    }
}