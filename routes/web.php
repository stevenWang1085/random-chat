<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/login', function () {
    return view('login');
});

Route::get('/register', function () {
    return view('register');
});


Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('random');
    });
    Route::get('/random', function () {
        return view('random');
    });
    Route::get('/friend', function () {
        return view('friend');
    });

    Route::get('/friend_check', function () {
        return view('friend_check');
    });

    Route::get('/friend_chat', function () {
        return view('friend_chat');
    });
});




