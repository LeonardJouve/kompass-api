<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Player;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CraftController extends Controller
{
    public function index()
    {
        $playerLevel = Player::current()->level;
        $crafts = DB::table('available_crafts')->where('min_level', '<=', $playerLevel)->get();

        return response()->json($crafts, 200);
    }

    public function image(Request $request, $craftId)
    {
        $craft = DB::table('available_crafts')->where('id', '=', $craftId)->first();

        if (!$craft) {
            return response()->json([
                'message' => 'api.rest.error.unprocessable_entity'
            ], 422);
        }

        $imageName = 'blueprint_' . $craft->name . '.png';

        if (!Storage::disk('items')->exists($imageName)) {
            return response()->json([
                'message' => 'api.rest.error.not_found',
            ], 404);
        }

        $fileContent = Storage::disk('items')->get($imageName);

        return response($fileContent, 200)->header('Content-Type', 'image/png');
    }
}
