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
    #使用者
    Route::controller(UserController::class)->prefix('user')->group(function () {
        Route::post('register', 'register')->withoutMiddleware('jwt.verify');
        Route::post('/login', 'login')->withoutMiddleware('jwt.verify');
        Route::post('/logout', 'logout');
        Route::patch('edit/profile', 'editProfile');
        Route::get('/google/login', 'googleLogin')->withoutMiddleware('jwt.verify');
    });
    #好友
    Route::controller(UserFriendController::class)->prefix('friend')->group(function () {
        Route::get('/{user_id}/list', 'list');
        Route::post('/invite', 'store');
        Route::patch('/{user_friend_id}/update', 'update');
    });
    #隨機配對
    Route::controller(RandomChatController::class)->prefix('random')->group(function () {
        Route::post('start', 'startRandom');
        Route::post('check', 'checkRandomChat');
        Route::post('leave', 'leaveRandomRoom');
        Route::post('cancel', 'cancelRandom');
    });
    #訊息
    Route::controller(MessageController::class)->prefix('message')->group(function () {
        Route::get('room/{room_id}', 'getRoomMessage');
        Route::post('send', 'store');
    });
    #儀表板
    Route::controller(DashboardController::class)->prefix('dashboard')->group(function () {
        Route::get('list', 'list');
    });
});
