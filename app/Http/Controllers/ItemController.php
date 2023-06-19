<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\ItemNotFoundException;

class ItemController extends Controller
{
    public function index()
    {
        return response()->json(
            Auth::user()->items
        , 200);
    }

    public function store(Request $request)
    {
        $validatedItem = $request->validate([
            'category' => 'required|string|in:food,equipement,weapon,tool',
            'name' => 'required|string',
            'amount' => 'required|integer',
        ]);
            
        $item = new Item();
        $item->user_id = Auth::user()->id;
        $item->category = $validatedItem['category'];
        $item->name = $validatedItem['name'];
        $item->amount = $validatedItem['amount'];
        $item->save();

        return response()->json([
            'item' => $item,
        ], 201);
    }

    public function destroy(Request $request, $itemId)
    {
        try {
            Auth::user()->items->where('id', $itemId)->firstOrFail()->delete();

            return response()->json([
                'status' => 'ok',
            ], 200);
        } catch (ItemNotFoundException $error) {
            return response()->json([
                'message' => 'api.rest.error.not_found',
            ], 404);
        }
    }
}
