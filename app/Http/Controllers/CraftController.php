<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AvailableItem;
use App\Models\Craft;
use App\Models\Ingredient;
use App\Models\Item;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\ItemNotFoundException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class CraftController extends Controller
{
    public function index()
    {
        $playerLevel = Player::current()->level;
        $crafts = Craft::where('min_level', '<=', $playerLevel)->get()->map(function ($craft) {
            return $craft->format();
        });

        return response()->json($crafts, 200);
    }

    public function store(Request $request)
    {
        try {
            $validatedParams = $request->validate([
                'craft_id' => 'required|integer',
                'selected_items_id' => 'required|array|min:1',
                'selected_items_id.*' => 'integer',
                'amount' => 'required|integer|min:1',
            ]);

            $player = Player::current();

            $result = Craft::getCraftResult($validatedParams['craft_id'], $validatedParams['selected_items_id']);

            $selectedItems = collect($validatedParams['selected_items_id'])->map(function ($selectedItemId) use ($player) {
                return $player->items->where('item_id', '=', $selectedItemId)->firstOrFail();
            });

            if ($selectedItems->count() !== count($validatedParams['selected_items_id'])) {
                throw new UnprocessableEntityHttpException();
            }

            $itemsAmount = $selectedItems->reduce(function ($carry, $selectedItem) use ($validatedParams) {
                $itemAmount = $carry[$selectedItem->item_id] ?? 0;
                $itemAmount += $validatedParams['amount'];
                $carry[$selectedItem->item_id] = $itemAmount;
                if ($selectedItem->amount < $itemAmount) {
                    throw new UnprocessableEntityHttpException();
                }
                return $carry;
            }, []);

            foreach ($itemsAmount as $id => $amount) {
                $selectedItem = $selectedItems->where('item_id', '=', $id)->firstOrFail();
                $selectedItem->remove($amount);
            }

            $item = new Item();
            $item->item_id = $result->id;
            $item->player_id = $player->id;
            $item->amount = $validatedParams['amount'];

            return response()->json($item->merge()->format(), 200);
        } catch (ItemNotFoundException $error) {
            return response()->json([
                'message' => 'api.rest.error.not_found',
            ], 404);
        } catch (UnprocessableEntityHttpException $error) {
            return response()->json([
                'message' => 'api.rest.error.unprocessable_entity',
            ], 422);
        }
    }

    public function preview(Request $request)
    {
        try {
            $validatedParams = $request->validate([
                'craft_id' => 'required|integer',
                'selected_items_id' => 'required|array|min:1',
                'selected_items_id.*' => 'integer',
            ]);

            $result = Craft::getCraftResult($validatedParams['craft_id'], $validatedParams['selected_items_id']);

            return response()->json($result, 200);
        } catch (ItemNotFoundException $error) {
            return response()->json([
                'message' => 'api.rest.error.not_found',
            ], 404);
        } catch (UnprocessableEntityHttpException $error) {
            return response()->json([
                'message' => 'api.rest.error.unprocessable_entity',
            ], 422);
        }
    }
}
