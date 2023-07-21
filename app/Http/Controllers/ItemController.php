<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AvailableItem;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ItemNotFoundException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class ItemController extends Controller
{
    public function index()
    {
        $items = Player::current()->items->map(function ($item) {
            return $item->format();
        });

        return response()->json($items, 200);
    }

    public function destroy(Request $request, $itemId)
    {
        try {
            $validatedParams = $request->validate([
                'amount' => 'required|numeric|min:1',
            ]);

            Player::current()
                ->items
                ->where('item_id', '=', $itemId)
                ->firstOrFail()
                ->remove($validatedParams['amount']);

            return response()->json([
                'status' => 'ok',
            ], 200);
        } catch (ItemNotFoundException $error) {
            return response()->json([
                'message' => 'api.rest.error.not_found',
            ], 404);
        } catch (UnprocessableEntityHttpException $error) {
            return response()->json([
                'message' => 'api.rest.error.unprocessable_entity'
            ], 422);
        }
    }

    public function image(Request $request, $itemId)
    {
        $item = AvailableItem::where('id', '=', $itemId)->first();

        if (!$item) {
            return response()->json([
                'message' => 'api.rest.error.unprocessable_entity'
            ], 422);
        }

        $imageName = $item->name . '.png';

        if (!Storage::disk('items')->exists($imageName)) {
            return response()->json([
                'message' => 'api.rest.error.not_found',
            ], 404);
        }

        $fileContent = Storage::disk('items')->get($imageName);

        return response($fileContent, 200)->header('Content-Type', 'image/png');
    }

    public function imagePreview(Request $request, $type)
    {
        $craft = AvailableItem::where('type', '=', $type)->first();

        if (!$craft) {
            return response()->json([
                'message' => 'api.rest.error.unprocessable_entity',
            ], 422);
        }

        $imageName = 'blueprint_' . $type . '.png';

        if (!Storage::disk('items')->exists($imageName)) {
            return response()->json([
                'message' => 'api.rest.error.not_found',
            ], 404);
        }

        $fileContent = Storage::disk('items')->get($imageName);

        return response($fileContent, 200)->header('Content-Type', 'image/png');
    }
}
