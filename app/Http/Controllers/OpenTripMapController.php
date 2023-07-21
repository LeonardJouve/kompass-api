<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Utils\OpenTripMapUtils;
use App\Models\PoiTimer;
use Illuminate\Support\Collection;

class OpenTripMapController extends Controller
{
    public function index(Request $request)
    {
        $validatedParams = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $response = Http::get('http://api.opentripmap.com/0.1/en/places/radius', [
            'radius' => 150,
            'lat' => $validatedParams['latitude'],
            'lon' => $validatedParams['longitude'],
            'limit' => 20,
            'kinds' => env('OPEN_TRIP_MAP_KINDS'),
            'format' => 'json',
            'apikey' => env('OPEN_TRIP_MAP_API_KEY'),
        ]);

        if (!$response->successful()) {
            return response()->json([
                'message' => 'api.rest.error.service_unavailable',
            ], 503);
        }

        $poiTimers = Player::current()->poiTimers;
        $pois = new Collection($response->json());

        $pois->transform(function ($poi) use ($poiTimers) {
            $poiTimer = $poiTimers->first(function ($poiTimer) use ($poi) {
                return strcmp($poiTimer->xid, $poi['xid']) === 0;
            });
            if ($poiTimer) {
                $poi['available_at'] = $poiTimer->available_at;
            }
            return $poi;
        });

        return response()->json($pois, 200);
    }

    public function search(Request $request) {
        $validatedParams = $request->validate([
            'xid' => 'required|string',
        ]);

        $response = Http::get('http://api.opentripmap.com/0.1/en/places/xid/' . $validatedParams['xid'], [
            'apikey' => env('OPEN_TRIP_MAP_API_KEY'),
        ]);

        if (!$response->successful()) {
            return response()->json([
                'message' => 'api.rest.error.not_found',
            ], 404);
        }

        $poi = $response->json();

        $kind = OpenTripMapUtils::findValidKind($poi['kinds']);

        if ($kind == null) {
            return response()->json([
                'message' => 'api.rest.error.unprocessable_entity',
            ], 422);
        }

        $player = Player::current();
        $playerId = $player->id;
        $poiTimers = $player->poiTimers;

        foreach ($poiTimers as $poiTimer) {
            if (now() > $poiTimer->available_at) {
                $poiTimer->delete();
                continue;
            } else if (strcmp($poiTimer->xid, $validatedParams['xid']) == 0) {
                return response()->json([
                    'message' => 'api.rest.error.unprocessable_entity',
                ], 422);
            }
        }

        $poiTimer = new PoiTimer();
        $poiTimer->player_id = $playerId;
        $poiTimer->xid = $validatedParams['xid'];
        $poiTimer->available_at = now()->addMinutes(env('OPEN_TRIP_MAP_POI_COOLDOWN'));
        $poiTimer->save();

        $items = OpenTripMapUtils::searchItems($playerId, $kind);

        return response()->json($items, 200);
    }
}
