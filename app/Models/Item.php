<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class Item extends Model
{
    use HasFactory;

    protected $hidden = [
        'id',
        'player_id',
    ];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function available_item(): BelongsTo
    {
        return $this->belongsTo(AvailableItem::class, 'item_id');
    }

    public function format()
    {
        $availableItem = AvailableItem::where('id', '=', $this->item_id)->get()->firstOrFail();
        return [
            'item_id' => $this->item_id,
            'name' => $availableItem->name,
            'category' => $availableItem->category,
            'type' => $availableItem->type,
            'tier' => $availableItem->tier,
            'amount' => $this->amount,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    /**
     * @return Item
     */
    public function merge()
    {
        $item = self::where('player_id', '=', $this->player_id)
            ->get()
            ->first(function (&$item) {
                return $item->item_id === $this->item_id;
        });
        if ($item) {
            $item->amount += $this->amount;
            $item->save();
            return $item;
        }
        $this->save();
        return $this;
    }

    /**
     * @param integer $amount
     * @throws UnprocessableEntityHttpException
     */
    public function remove($amount)
    {
        if ($this->amount < $amount) {
            throw new UnprocessableEntityHttpException();
        }
        if ($this->amount == $amount) {
            $this->delete();
            return;
        }
        $this->amount -= $amount;
        $this->save();
    }
}
