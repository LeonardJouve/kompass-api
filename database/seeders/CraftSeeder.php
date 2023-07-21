<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CraftSeeder extends Seeder
{
    public function run()
    {
        $crafts = [
            ['type' => 'stick', 'category' => 'ressource', 'min_level' => 1],
            ['type' => 'ingot', 'category' => 'ressource', 'min_level' => 2],
            ['type' => 'plate', 'category' => 'ressource', 'min_level' => 5],
            ['type' => 'salad', 'category' => 'food', 'min_level' => 3],
            ['type' => 'soup', 'category' => 'food', 'min_level' => 4],
        ];

        DB::table('crafts')->insert($crafts);
    }
}
