<?php

use App\Http\Controllers\MessageController;
use App\Http\Controllers\RandomChatController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserFriendController;
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
        Route::post('/register', [UserController::class, 'register'])->withoutMiddleware('auth');
        Route::post('/login', [UserController::class, 'login'])->withoutMiddleware('auth');
        Route::post('/logout', [UserController::class, 'logout']);
    });

    Route::prefix('friend')->group(function () {
        Route::get('/{user_id}/list', [UserFriendController::class, 'list']);
        Route::post('/invite', [UserFriendController::class, 'store']);
        Route::patch('/{user_friend_id}/update', [UserFriendController::class, 'update']);
    });

    Route::prefix('random')->group(function () {

    });

    Route::post('start_random', [RandomChatController::class, 'startRandom']);
    Route::post('check_random', [RandomChatController::class, 'checkRandomChat']);
    Route::post('leave_random', [RandomChatController::class, 'leaveRandomRoom']);

    Route::prefix('message')->group(function () {
        Route::get('room/{room_id}', [MessageController::class, 'getRoomMessage']);
        Route::post('send', [MessageController::class, 'store']);

    });
});
