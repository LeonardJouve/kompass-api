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
            ['name' => '', 'category' => ''],
        ]);
    }
}
