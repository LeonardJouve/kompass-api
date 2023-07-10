<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Player;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CraftController extends Controller
{
    public function index()
    {
        $userLevel = Player::current()->level;
        $crafts = DB::table('available_crafts')->where('min_level', '<=', $userLevel)->get();

        return response()->json($crafts, 200);
    }
}
