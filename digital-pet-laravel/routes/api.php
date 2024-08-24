<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserDigitalMonsterController;
use App\Http\Controllers\InventoryController;

Route::post('/auth/register', [UserController::class, 'createUser']);
Route::post('/auth/login', [UserController::class, 'loginUser']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/validate-token', [UserController::class, 'validateToken']);
    Route::post('/user/nickname', [UserController::class, 'updateNickname']);

    Route::get('/digitalMonster', [UserDigitalMonsterController::class, 'getDigitalMonsterByEggAndMonsterId']);




    Route::get('/user/userDigitalMonsters', [UserDigitalMonsterController::class, 'getUserDigitalMonsters']);
    Route::get('/user/inventory', [InventoryController::class, 'getUserInventory']);
});
