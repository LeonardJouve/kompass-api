<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'websocket_host' => env('PUSHER_HOST', '127.0.0.1'),
            'websocket_port' => env('PUSHER_PORT', 6001),
            'websocket_key' => env('PUSHER_APP_KEY', 'pusher-app-key'),
        ], 200);
    }
}
