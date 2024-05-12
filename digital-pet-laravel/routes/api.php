<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\DigitalMonsterController;

Route::post('/auth/register', [UserController::class, 'createUser']);
Route::post('/auth/login', [UserController::class, 'loginUser']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/digital-monsters', [DigitalMonsterController::class, 'index']);
    Route::post('/digital-monsters', [DigitalMonsterController::class, 'store']);
    Route::get('/auth/validate-token', [UserController::class, 'validateToken']);
    Route::post('/user/nickname', [UserController::class, 'updateNickname']);
});
