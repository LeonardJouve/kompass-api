<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\ItemNotFoundException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class Craft extends Model
{
    use HasFactory;

    public function ingredients(): HasMany
    {
        return $this->hasMany(Ingredient::class);
    }

    /**
     * @param integer $craftId
     * @param  Array<integer, integer> $selectedItemsId
     * @throws UnprocessableEntityHttpException | ItemNotFoundException
     * @return AvailableItem
     */
    public static function getCraftResult($craftId, $selectedItemsId)
    {
        $craft = Craft::where('id', '=', $craftId)->get()->firstOrFail();
        $craftIngredients = Ingredient::where('craft_id', '=', $craftId)->get()->sortBy('id');
        $craftIngredientsCount = $craftIngredients->count();
        $selectedItemsCount = count($selectedItemsId);

        if ($craftIngredientsCount !== $selectedItemsCount) {
            throw new UnprocessableEntityHttpException();
        }

        $tierSum = 0;
        foreach ($selectedItemsId as $index=>$selectedItemId) {
            $availableItem = AvailableItem::where('id', '=', $selectedItemId)->get()->firstOrFail();
            if (strcmp($craftIngredients->get($index)->type, $availableItem->type) !== 0) {
                throw new UnprocessableEntityHttpException();
            }
            $tierSum += $availableItem->tier;
        }

        $tier = round($tierSum / $selectedItemsCount);
        $type = $craft->type;

        return AvailableItem::where('tier', '=', $tier)->where('type', '=', $type)->get()->firstOrFail();
    }

    public function format()
    {
        $recipe = Ingredient::where('craft_id', '=', $this->id)->get()->sortBy('id');
        return [
            'craft_id' => $this->id,
            'type' => $this->type,
            'category' => $this->category,
            'recipe' => $recipe,
        ];
    }
}
