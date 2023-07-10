<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AvailableCraftSeeder extends Seeder
{
    public function run()
    {
        $crafts = [
            ['name' => 'stick', 'category' => 'ressource', 'min_level' => 1],
            ['name' => 'ingot', 'category' => 'ressource', 'min_level' => 2],
            ['name' => 'plate', 'category' => 'ressource', 'min_level' => 5],
            ['name' => 'soup', 'category' => 'food', 'min_level' => 3],
            ['name' => 'salad', 'category' => 'food', 'min_level' => 4],
        ];

        foreach ($crafts as $craft) {
            $name = 'blueprint_' . $craft['name'] . '.png';
            if (!Storage::disk('items')->exists($name)) {
                throw new \Exception('Image ' . $name . ' not found');
            }
        }

        DB::table('available_crafts')->insert($crafts);
    }
}
