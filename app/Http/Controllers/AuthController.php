<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $createNewUser = new CreateNewUser();
        $user = $createNewUser->create($request->all());
        $user->createPlayer();
        return response()->json(['token' => $user->createToken($request->email)->plainTextToken], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return response()->json([
                'token' => Auth::user()->createToken($request->email)->plainTextToken,
            ], 200);
        }

        return response()->json(['message' => ['api.rest.error.auth.credentials']], 401);
    }
}
