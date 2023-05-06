<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ErrorController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\OpenTripMapController;

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

Route::resource('errors', ErrorController::class)->only(['index', 'store']);

Route::resource('config', ConfigController::class)->only(['index']);

Route::resource('opentripmap', OpenTripMapController::class)->only(['index']);
