<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'application'], function () {
    //event controller
    Route::post('/event', [\App\Http\Controllers\Api\CallbackController::class, 'event']);
});

Route::group(['middleware' => ['auth:sanctum'], 'prefix' => 'mock'], function () {
    Route::post('/google/control', [\App\Http\Controllers\Api\GoogleMockController::class, 'control']);
    Route::post('/ios/control', [\App\Http\Controllers\Api\AppleMockController::class, 'control']);
});

Route::group(['prefix' => 'mobile'], function () {

    //device & app controller
    Route::post('/register', [\App\Http\Controllers\Api\MobileController::class, 'register']);

    // purchase and check controllers
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::post('/purchase', [\App\Http\Controllers\Api\MobileController::class, 'purchase']);
        Route::post('/check', [\App\Http\Controllers\Api\MobileController::class, 'check']);
    });

});
