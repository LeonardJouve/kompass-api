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
        'craft_id',
    ];

    public function craft(): BelongsTo
    {
        return $this->belongsTo(Craft::class);
    }
}
