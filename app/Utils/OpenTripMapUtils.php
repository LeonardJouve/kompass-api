<?php

namespace App\Utils;

use App\Models\Item;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class OpenTripMapUtils {
    public static function findValidKind(string $kinds)
    {
        foreach (explode(',', env('OPEN_TRIP_MAP_KINDS')) as $validKind) {
            if (Str::contains($kinds, $validKind)) {
                return $validKind;
            }
        }
        return null;
    }

    private static $lootTable = [
        'foods' => [
            ['rate' => 20, 'amount' => 5, 'name' => 'mushroom'],
        ],
        'shops' => [],
        'transport' => [],
        'banks' => [],
        'natural' => [],
        'accomodations' => [],
        'industrial_facilities' => [],
        'religion' => [],
        'sport' => [],
        'amusements' => [],
        'adult' => [],
        'garbage' => [
            ['amount' => 7, 'name' => 'wood'],
        ],
    ];

    private static function searchItem($kindLoot)
    {
        $item = null;
        $target = rand(1, 100);
        $value = 0;

        foreach ($kindLoot as $loot) {
            $value += $loot['rate'];
            if ($value >= $target) {
                $item = $loot;
                break;
            }
        }

        if ($item === null) {
            $garbage = self::$lootTable['garbage'];
            $value = $target % count($garbage);
            $itemKey = array_keys($garbage)[$value];
            $item = $garbage[$itemKey];
        }

        $amount = rand(1, $item['amount']);

        return [
            'name' => $item['name'],
            'amount' => $amount,
        ];
    }

    public static function searchItems(string $kind)
    {
        $kindLoot = self::$lootTable[$kind];
        $itemAmount = rand(1, env('OPEN_TRIP_MAP_SEARCH_MAX_ITEM_AMOUNT'));
        
        if (!$kindLoot) {
            return [];
        }

        $foundItems = new Collection();
        
        for ($i = 0; $i < $itemAmount; $i++) {
            $item = self::searchItem($kindLoot);
            
            $existingItem = $foundItems->first(function ($existingItem) use ($item) {
                return strcmp($existingItem['name'], $item['name']) === 0;
            });

            if ($existingItem !== null) {
                $existingItem['amount'] += $item['amount'];
                continue;
            }

            $foundItems->push($item);
        }

        return $foundItems;
    }
}