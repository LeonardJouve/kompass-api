<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AvailableItem;
use App\Models\Craft;
use App\Models\Ingredient;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

    public function image(Request $request, $craftId)
    {
        $craft = Craft::where('id', '=', $craftId)->first();

        if (!$craft) {
            return response()->json([
                'message' => 'api.rest.error.unprocessable_entity',
            ], 422);
        }

        $imageName = 'blueprint_' . $craft->type . '.png';

        if (!Storage::disk('items')->exists($imageName)) {
            return response()->json([
                'message' => 'api.rest.error.not_found',
            ], 404);
        }

        $fileContent = Storage::disk('items')->get($imageName);

        return response($fileContent, 200)->header('Content-Type', 'image/png');
    }

    public function preview(Request $request)
    {
        try {
            $validatedParams = $request->validate([
                'craft_id' => 'required|integer',
                'selected_items_id' => 'required|array|min:1',
                'selected_items_id.*' => 'integer',
            ]);

            $craftIngredients = Ingredient::where('craft_id', '=', $validatedParams['craft_id'])->get()->sortBy('id');
            $craftIngredientsCount = $craftIngredients->count();
            $selectedItemsCount = count($validatedParams['selected_items_id']);

            if ($craftIngredientsCount !== $selectedItemsCount) {
                throw new UnprocessableEntityHttpException();
            }

            $tierSum = 0;
            foreach ($validatedParams['selected_items_id'] as $index=>$selectedItemId) {
                $availableItem = AvailableItem::where('id', '=', $selectedItemId)->firstOrFail();
                if (strcmp($craftIngredients->get($index)->type, $availableItem->type) !== 0) {
                    throw new UnprocessableEntityHttpException();
                }
                $tierSum += $availableItem->tier;
            }

            $tier = round($tierSum / $selectedItemsCount);
            $type = Craft::where('id', '=', $validatedParams['craft_id'])->firstOrFail()->type;

            $item = AvailableItem::where('tier', '=', $tier)->where('type', '=', $type)->firstOrFail();

            return response()->json($item, 200);
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
