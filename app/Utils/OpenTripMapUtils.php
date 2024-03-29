<?php

namespace App\Utils;

use App\Models\Drop;
use App\Models\Item;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class OpenTripMapUtils
{
    public static function findValidKind(string $kinds)
    {
        foreach (explode(',', env('OPEN_TRIP_MAP_KINDS')) as $validKind) {
            if (Str::contains($kinds, $validKind)) {
                return $validKind;
            }
        }
        return null;
    }

    private static function searchItem($playerId, $drops, $garbage)
    {
        $selectedDrop = null;
        $target = rand(1, 100);
        $value = 0;

        foreach ($drops as $drop) {
            $value += $drop->rate;
            if ($value >= $target) {
                $selectedDrop = $drop;
                break;
            }
        }

        if ($selectedDrop === null) {
            $value = $target % count($garbage);
            $selectedDrop = $garbage->get($value);
        }

        $amount = rand(1, $selectedDrop->amount);

        $item = new Item();
        $item->player_id = $playerId;
        $item->item_id = $selectedDrop->item_id;
        $item->amount = $amount;

        return $item
            ->merge()
            ->format();
    }

    public static function searchItems($playerId, string $kind)
    {
        $drops = Drop::where('kind', '=', $kind)->get();
        $garbage = Drop::where('kind', '=', 'garbage')->get();
        $amount = rand(1, env('OPEN_TRIP_MAP_SEARCH_MAX_ITEM_AMOUNT'));

        $items = new Collection();

        for ($i = 0; $i < $amount; $i++) {
            $newItem = self::searchItem($playerId, $drops, $garbage);

            $itemAlreadyExists = false;

            $items->transform(function ($item) use ($newItem, &$itemAlreadyExists) {
                if (strcmp($item['item_id'], $newItem['item_id']) === 0) {
                    $item['amount'] += $newItem['amount'];
                    $itemAlreadyExists = true;
                }
                return $item;
            });

            if ($itemAlreadyExists) {
                continue;
            }

            $items->push($newItem);
        }

        return $items;
    }
}
