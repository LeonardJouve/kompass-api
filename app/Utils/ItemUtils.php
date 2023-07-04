<?php

namespace App\Utils;

use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class ItemUtils
{
    public static function formatItem($item)
    {
        $newItem = clone $item;
        $availableItem = DB::table('available_items')->where('id', '=', $newItem->item_id)->first();
        $newItem->name = $availableItem->name;
        $newItem->category = $availableItem->category;

        return $newItem;
    }

    public static function formatItems($items)
    {
        return $items->map(function ($item) {
            return self::formatItem($item);
        });
    }

    public static function mergeItem($existingItems, $item)
    {
        $existingItem = $existingItems->first(function (&$existingItem) use ($item) {
            return $existingItem->item_id === $item['item_id'];
        });
        if ($existingItem) {
            $existingItem->amount += $item->amount;
            $existingItem->save();
            return;
        }
        $item->save();
    }

    public static function deleteItem($items, $itemId, $amount)
    {
        $item = $items->where('item_id', '=', $itemId)->firstOrFail();
        if ($item->amount < $amount) {
            throw new UnprocessableEntityHttpException();
        }
        if ($item->amount == $amount) {
            $item->delete();
            return;
        }
        $item->amount -= $amount;
        $item->save();
    }
}
