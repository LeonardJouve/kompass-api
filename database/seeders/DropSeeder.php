<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class DropSeeder extends Seeder
{
    
    private function transformDrops(string $kind, Collection &$collection)
    {
        $items = DB::table('available_items')->pluck('id', 'name');
        $collection->transform(function ($drop) use ($items, $kind) {
            $drop['kind'] = $kind;
            $drop['item_id'] = $items[$drop['name']];
            unset($drop['name']);
            return $drop;
        });
    }

    public function run()
    {

        $foods = new Collection([
            [
                'rate' => 20,
                'amount' => 5,
                'name' => '',
            ],
            [
                'rate' => 20,
                'amount' => 5,
                'name' => '',
            ],
        ]);
        self::transformDrops('foods', $foods);

        $shops = new Collection([]);
        self::transformDrops('shops', $shops);

        $transport = new Collection([]);
        self::transformDrops('transport', $transport);

        $banks = new Collection([]);
        self::transformDrops('banks', $banks);

        $natural = new Collection([]);
        self::transformDrops('natural', $natural);

        $accomodations = new Collection([]);
        self::transformDrops('accomodations', $accomodations);

        $industrial_facilities = new Collection([]);
        self::transformDrops('industrial_facilities', $industrial_facilities);

        $religion = new Collection([]);
        self::transformDrops('religion', $religion);

        $sport = new Collection([]);
        self::transformDrops('sport', $sport);

        $amusements = new Collection([]);
        self::transformDrops('amusements', $amusements);

        $adult = new Collection([]);
        self::transformDrops('adult', $adult);

        $garbage = new Collection([
            [
                'amount' => 6,
                'name' => '',
            ],
        ]);
        self::transformDrops('garbage', $garbage);

        $drops = (new Collection())
            ->merge($foods)
            ->merge($shops)
            ->merge($transport)
            ->merge($banks)
            ->merge($natural)
            ->merge($accomodations)
            ->merge($industrial_facilities)
            ->merge($sport)
            ->merge($amusements)
            ->merge($adult)
            ->merge($garbage);
        
        DB::table('drops')->insert($drops->all());
    }
}
