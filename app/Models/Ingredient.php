<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ingredient extends Model
{
    use HasFactory;

    protected $hidden = [
        'id',
        'available_craft_id',
    ];

    public function available_craft(): BelongsTo
    {
        return $this->belongsTo(AvailableCraft::class);
    }
}
