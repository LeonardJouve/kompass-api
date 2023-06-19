<?php

namespace App\Utils;

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

    public static function searchItems(string $kind)
    {
        // TODO - get and return new items by kind
        return [];
    }
}