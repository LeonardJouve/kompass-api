<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OpenTripMapController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Http::get('http://api.opentripmap.com/0.1/en/places/radius', [
            'radius' => 150,
            'lat' => $request->input('latitude'),
            'lon' => $request->input('longitude'),
            'limit' => 20,
            'kinds' => 'interesting_places,sport,tourist_facilities,adult,amusements,accomodations',
            'format' => 'json',
            'apikey' => env('OPEN_TRIP_MAP_API_KEY'),
        ])->json();
    }
}
