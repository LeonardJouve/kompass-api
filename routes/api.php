<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ErrorController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\OpenTripMapController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('auth/register', [AuthController::class, 'register'])->withoutMiddleware(['auth:sanctum']);
Route::post('auth/login', [AuthController::class, 'login'])->withoutMiddleware(['auth:sanctum']);

Route::resource('errors', ErrorController::class)->only(['index', 'store']);

Route::resource('config', ConfigController::class)->only(['index']);

Route::resource('opentripmap', OpenTripMapController::class)->only(['index']);
Route::get('opentripmap/search', [OpenTripMapController::class, 'search']);

Route::resource('items', ItemController::class)->only(['index', 'destroy']);
