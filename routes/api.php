<?php

use App\Http\Controllers\Api\PositionController;
use App\Http\Controllers\Api\TokenController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('token', [TokenController::class, 'make']);
Route::get('positions',   [PositionController::class, 'index'])
    ->middleware(['api.json']);

Route::group(['prefix' => 'users'], function(){
    Route::get('',       [UserController::class, 'index']);
    Route::post('', [UserController::class, 'store'])->middleware(['jwt.auth']);
    Route::get('{user}',[UserController::class, 'show']);
});
