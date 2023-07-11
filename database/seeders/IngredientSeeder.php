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
            'salad' => ['vegetable', ['type' => 'vegetable', 'min_tier' => 3]],
            'soup' => ['vegetable', 'vegetable', 'log'],
        ]);

        $availableCrafts = DB::table('available_crafts')->pluck('id', 'type');

        $ingredients = $recipes->reduce(function ($acc, $ingredients, $craft) use ($availableCrafts) {
            foreach ($ingredients as $ingredient) {
                if (is_string($ingredient)) {
                    $type = $ingredient;
                    $minTier = 1;
                } else {
                    $type = $ingredient['type'];
                    $minTier = $ingredient['min_tier'];
                }
                $newIngredient = ['available_craft_id' => $availableCrafts[$craft], 'type' => $type, 'min_tier' => $minTier];
                array_push($acc, $newIngredient);
            }
            return $acc;
        }, []);

        DB::table('ingredients')->insert($ingredients);
    }
}
