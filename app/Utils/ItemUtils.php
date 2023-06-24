<?php

namespace App\Utils;

use Illuminate\Support\Facades\DB;

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
}