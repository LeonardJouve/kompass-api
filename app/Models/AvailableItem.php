<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AvailableItem extends Model
{
    use HasFactory;

    public function items(): HasMany
    {
        return $this->hasMany(Item::class, 'item_id');
    }

    public function drops(): HasMany
    {
        return $this->hasMany(Drop::class, 'item_id');
    }
}
