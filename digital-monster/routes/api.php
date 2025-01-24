<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ApiAuthenticatedSessionController;
use App\Http\Controllers\ApiUserController;
use App\Http\Controllers\ApiGetDataController;
use App\Http\Controllers\ApiPostDataController;

Route::middleware('check-api-key')->group(function () {
    Route::post('/register', [ApiAuthenticatedSessionController::class, 'register']);
    Route::post('/login', [ApiAuthenticatedSessionController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/validate-token', [ApiAuthenticatedSessionController::class, 'validateToken']);
        Route::post('/logout', [ApiAuthenticatedSessionController::class, 'logout']);

        Route::get('/eggs', [ApiGetDataController::class, 'getEggs']);
        Route::get('/user/user-digital-monsters', [ApiGetDataController::class, 'getUserDigitalMonsters']);
        Route::get('/user/training-equipment', [ApiGetDataController::class, 'getUserTrainingEquipment']);
        Route::get('/user/inventory', [ApiGetDataController::class, 'getUserInventories']);
       
        Route::get('/user-digital-monsters', [ApiUserController::class, 'getUserDigitalMonsters']);
        //Route::get('/user-digital-monster/switch', [ApiUserController::class, 'switchUserDigitalMonster']);
        Route::post('/user-digital-monster/create', [ApiPostDataController::class, 'createUserDigitalMonster']);
        Route::post('/user-digital-monster/evolve', [ApiUserController::class, 'evolve']);
        Route::post('/user-digital-monster/update', [ApiPostDataController::class, 'updateUserDigitalMonster']);
        
        Route::post('/items', [ApiPostDataController::class, 'getItems']);
        Route::post('/item/buy', [ApiPostDataController::class, 'buyItem']);
        Route::post('/inventory-item/update', [ApiPostDataController::class, 'updateInventoryItem']);
    });
});
