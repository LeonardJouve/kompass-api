<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Utils\ItemUtils;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ItemNotFoundException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class ItemController extends Controller
{
    public function index()
    {
        $items = Auth::user()->items;

        return response()->json(
            ItemUtils::formatItems($items)
        , 200);
    }

    public function destroy(Request $request, $itemId)
    {
        try {
            $validatedParams = $request->validate([
                'amount' => 'required|numeric|min:1',
            ]);

            $items = Auth::user()->items;

            ItemUtils::deleteItem($items, $itemId, $validatedParams['amount']);

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
}
