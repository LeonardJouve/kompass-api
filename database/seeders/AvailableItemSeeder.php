<?php

namespace Database\Seeders;

use App\Models\AvailableItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AvailableItemSeeder extends Seeder
{
    public function run()
    {
        DB::table('available_items')->insert([
            ['name' => 'wood', 'category' => 'ressource'],
            ['name' => 'stick', 'category' => 'ressource'],
            ['name' => 'copper_powder', 'category' => 'ressource'],
            ['name' => 'zinc_powder', 'category' => 'ressource'],
            ['name' => 'copper_ingot', 'category' => 'ressource'],
            ['name' => 'zinc_ingot', 'category' => 'ressource'],
            ['name' => 'brass_ingot', 'category' => 'ressource'],
            ['name' => 'copper_plate', 'category' => 'ressource'],
            ['name' => 'zinc_plate', 'category' => 'ressource'],
            ['name' => 'brass_plate', 'category' => 'ressource'],
            ['name' => 'copper_boots', 'category' => 'equipement'],
            ['name' => 'mushroom', 'category' => 'food'],
            ['name' => 'berry', 'category' => 'food'],
            ['name' => 'mushroom_soup', 'category' => 'food'],
            ['name' => 'berry_soup', 'category' => 'food'],
            ['name' => 'lettuce', 'category' => 'food'],
            ['name' => 'salad', 'category' => 'food'],
        ]);
    }
}
