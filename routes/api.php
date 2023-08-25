<?php

use App\Http\Controllers\DashboardController;
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
Route::middleware(['jwt.verify'])->prefix('v1')->group(function () {
    Route::prefix('user')->group(function () {
        Route::post('/register', [UserController::class, 'register'])->withoutMiddleware('jwt.verify');
        Route::post('/login', [UserController::class, 'login'])->withoutMiddleware('jwt.verify');
        Route::post('/logout', [UserController::class, 'logout']);
        Route::patch('edit/profile', [UserController::class, 'editProfile']);
        Route::get('/google/login', [UserController::class, 'googleLogin'])->withoutMiddleware('jwt.verify');
    });

    Route::prefix('friend')->group(function () {
        Route::get('/{user_id}/list', [UserFriendController::class, 'list']);
        Route::post('/invite', [UserFriendController::class, 'store']);
        Route::patch('/{user_friend_id}/update', [UserFriendController::class, 'update']);
    });

    Route::prefix('random')->group(function () {
        Route::post('start', [RandomChatController::class, 'startRandom']);
        Route::post('check', [RandomChatController::class, 'checkRandomChat']);
        Route::post('leave', [RandomChatController::class, 'leaveRandomRoom']);
        Route::post('cancel', [RandomChatController::class, 'cancelRandom']);
    });

    Route::prefix('message')->group(function () {
        Route::get('room/{room_id}', [MessageController::class, 'getRoomMessage']);
        Route::post('send', [MessageController::class, 'store']);

    });

    Route::prefix('dashboard')->group(function () {
        Route::get('list', [DashboardController::class, 'list']);
    });
});
