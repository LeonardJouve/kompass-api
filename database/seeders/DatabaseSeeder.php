<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(AvailableItemSeeder::class);
        $this->call(DropSeeder::class);
        $this->call(AvailableCraftSeeder::class);
    }
}
