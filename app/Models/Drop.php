<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Drop extends Model
{
    use HasFactory;

    public function available_item(): BelongsTo
    {
        return $this->belongsTo(AvailableItem::class, 'item_id');
    }
}
