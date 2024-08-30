<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserDigitalMonsterController;
use App\Http\Controllers\InventoryController;

Route::post('/auth/register', [UserController::class, 'createUser']);
Route::post('/auth/login', [UserController::class, 'loginUser']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/validate-token', [UserController::class, 'validateToken']);
    Route::get('/digitalMonsters', [UserDigitalMonsterController::class, 'getDigitalMonsters']);
    Route::get('/user/userDigitalMonster', [UserDigitalMonsterController::class, 'getUserDigitalMonster']);
    Route::get('/user/inventory', [InventoryController::class, 'getUserInventory']);
});
