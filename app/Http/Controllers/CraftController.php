<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Craft;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
                'message' => 'api.rest.error.unprocessable_entity'
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
}
