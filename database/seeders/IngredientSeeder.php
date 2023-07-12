<?php

namespace Database\Seeders;

use Illuminate\Support\Collection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IngredientSeeder extends Seeder
{
    public function run()
    {
        $recipes = new Collection([
            'stick' => ['log', 'log'],
            'ingot' => ['powder', 'powder', 'log'],
            'plate' => ['ingot', 'ingot', 'log'],
            'salad' => ['vegetable', 'vegetable'],
            'soup' => ['vegetable', 'vegetable', 'log'],
        ]);

        $crafts = DB::table('crafts')->pluck('id', 'type');

        $ingredients = $recipes->reduce(function ($acc, $ingredients, $craft) use ($crafts) {
            foreach ($ingredients as $ingredient) {
                $newIngredient = ['craft_id' => $crafts[$craft], 'type' => $ingredient];
                array_push($acc, $newIngredient);
            }
            return $acc;
        }, []);

        DB::table('ingredients')->insert($ingredients);
    }
}
