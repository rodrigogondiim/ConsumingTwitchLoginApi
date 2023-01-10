<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FriendController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(FriendController::class)->prefix('friends')->middleware('auth')->group(function(){
    Route::get('/', 'index');
    Route::get('pendent', 'showPendencyFriends');
    Route::get('{friend}', 'show');
    Route::post('{user}', 'store');
    Route::patch('{friend}/accept', 'showAcceptFriends')->can('status-friend');
    Route::patch('{friend}/recuse', 'showRecuseFriends')->can('status-friend');
});
