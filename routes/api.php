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

Route::middleware(['auth'])->prefix('v1')->group(function () {
    Route::prefix('user')->group(function () {
        Route::post('/register', [\App\Http\Controllers\UserController::class, 'register'])->withoutMiddleware('auth');
        Route::post('/login', [\App\Http\Controllers\UserController::class, 'login'])->withoutMiddleware('auth');
        Route::post('/logout', [\App\Http\Controllers\UserController::class, 'logout']);
    });

    Route::prefix('message')->group(function () {
        Route::post('send', [\App\Http\Controllers\MessageController::class, 'store']);
    });
});
