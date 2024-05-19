<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{FriendController, UserController, AuthJwtController};

Route::controller(UserController::class)->prefix('user')->group(function(){
    Route::middleware('auth-direct')->group(function(){
        Route::get('/', 'index');
        Route::get('/{name}', 'show');
        Route::patch('/notified', 'view');
    });
    
    Route::post('/', 'store');
});

Route::controller(FriendController::class)->prefix('friends')->middleware('auth-direct')->group(function(){
    Route::get('/', 'index');
    Route::get('pendent', 'showPendencyFriends');
    Route::get('{friend}', 'show');
    Route::post('{user}', 'store');
    Route::patch('{friend}/friendship', 'friendship')->can('status-friend');
});

#!!!!
Route::post('/auth', [AuthJwtController::class, 'login'])->name('loginWithPayload');
Route::get('/auth/me', [AuthJwtController::class, 'me'])->middleware('auth-direct');