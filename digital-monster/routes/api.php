<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ApiAuthenticatedSessionController;
use App\Http\Controllers\ApiUserController;

Route::middleware('check-api-key')->group(function () {
    Route::post('/register', [ApiAuthenticatedSessionController::class, 'register']);
    Route::post('/login', [ApiAuthenticatedSessionController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/check-token', [ApiAuthenticatedSessionController::class, 'checkToken']);
        Route::post('/logout', [ApiAuthenticatedSessionController::class, 'logout']);

        Route::get('/eggs', [ApiUserController::class, 'getEggs']);
        Route::get('/user-digital-monsters', [ApiUserController::class, 'getUserDigitalMonsters']);
        Route::get('/user-digital-monster/switch', [ApiUserController::class, 'switchUserDigitalMonster']);
        Route::post('/user-digital-monster/create', [ApiUserController::class, 'createUserDigitalMonster']);
        Route::post('/user-digital-monster/evolve', [ApiUserController::class, 'evolve']);
        Route::post('/user-digital-monster/save', [ApiUserController::class, 'saveUserDigitalMonster']);
        
        Route::post('/items', [ApiUserController::class, 'getItems']);
        Route::post('/purchase-item', [ApiUserController::class, 'purchaseItem']);
        Route::post('/inventory-item/update', [ApiUserController::class, 'updateInventoryItem']);
    });
});
