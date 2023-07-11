<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AvailableItemSeeder extends Seeder
{
    public function run()
    {
        $items = [
            ['name' => 'oak_log', 'category' => 'ressource', 'type' => 'log', 'tier' => 1],
            ['name' => 'oak_stick', 'category' => 'ressource', 'type' => 'stick', 'tier' => 1],
            ['name' => 'copper_powder', 'category' => 'ressource', 'type' => 'powder', 'tier' => 1],
            ['name' => 'zinc_powder', 'category' => 'ressource', 'type' => 'powder', 'tier' => 2],
            ['name' => 'copper_ingot', 'category' => 'ressource', 'type' => 'ingot', 'tier' => 1],
            ['name' => 'zinc_ingot', 'category' => 'ressource', 'type' => 'ingot', 'tier' => 2],
            ['name' => 'brass_ingot', 'category' => 'ressource', 'type' => 'ingot', 'tier' => 3],
            ['name' => 'copper_plate', 'category' => 'ressource', 'type' => 'plate', 'tier' => 1],
            ['name' => 'zinc_plate', 'category' => 'ressource', 'type' => 'plate', 'tier' => 2],
            ['name' => 'brass_plate', 'category' => 'ressource', 'type' => 'plate', 'tier' => 3],
            ['name' => 'mushroom', 'category' => 'food', 'type' => 'vegetable', 'tier' => 1],
            ['name' => 'berry', 'category' => 'food', 'type' => 'vegetable', 'tier' => 2],
            ['name' => 'lettuce', 'category' => 'food', 'type' => 'vegetable', 'tier' => 3],
            ['name' => 'mushroom_soup', 'category' => 'food', 'type' => 'soup', 'tier' => 1],
            ['name' => 'berry_soup', 'category' => 'food', 'type' => 'soup', 'tier' => 2],
            ['name' => 'mushroom_salad', 'category' => 'food', 'type' => 'salad', 'tier' => 1],
            ['name' => 'berry_salad', 'category' => 'food', 'type' => 'salad', 'tier' => 2],
        ];

        foreach ($items as $item) {
            $name = $item['name'] . '.png';
            if (!Storage::disk('items')->exists($name)) {
                throw new \Exception('Image ' . $name . ' not found');
            }
        }

        DB::table('available_items')->insert($items);

    }
}
