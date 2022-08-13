<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController, UserController, HomeController};

Route::controller(HomeController::class)->group(function(){
    Route::get('/', 'index')->name('index');
    Route::get('/logout', 'logout')->name('logout');
});
Route::get('/auth/{provider}', [AuthController::class, 'auth'])->name('login');
