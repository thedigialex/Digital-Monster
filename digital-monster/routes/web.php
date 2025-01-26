<?php

use App\Http\Controllers\DigitalMonsterController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\TrainingEquipmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EggGroupController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

//Public
Route::get('/', function () {
    return view('welcome');
});

//Log In Required
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    //Admin Only
    Route::middleware(['admin'])->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/user', [UserController::class, 'profile'])->name('user.profile');

        Route::get('/eggGroups', [EggGroupController::class, 'index'])->name('egg_groups.index');
        Route::get('/eggGroup/edit', [EggGroupController::class, 'edit'])->name('egg_groups.edit');
        Route::post('/eggGroups/update', [EggGroupController::class, 'update'])->name('egg_groups.update');
        Route::delete('/eggGroups/{eggGroup}', [EggGroupController::class, 'destroy'])->name('egg_groups.destroy');

        Route::get('/digitalMonsters', [DigitalMonsterController::class, 'index'])->name('digital_monsters.index');
        Route::get('/digitalMonsters/edit', [DigitalMonsterController::class, 'edit'])->name('digital_monsters.edit');
        Route::post('/digitalMonsters/update', [DigitalMonsterController::class, 'update'])->name('digital_monsters.update');
        Route::delete('/digitalMonsters/{digitalMonster}', [DigitalMonsterController::class, 'destroy'])->name('digital_monsters.destroy');
        Route::get('/userDigitalMonsters/edit', [UserController::class, 'editUserDigitalMonster'])->name('user.digital_monsters.edit');
        Route::post('/userDigitalMonsters/update', [UserController::class, 'updateUserDigitalMonster'])->name('user.digital_monsters.update');
        Route::delete('/userDigitalMonsters/{userDigitalMonster}', [UserController::class, 'destroyUserDigitalMonster'])->name('user.digital_monsters.destroy');

        Route::get('/items', [ItemController::class, 'index'])->name('items.index');
        Route::get('/items/edit', [ItemController::class, 'edit'])->name('items.edit');
        Route::post('/items/update', [ItemController::class, 'update'])->name('items.update');
        Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy');
        Route::get('/userInventory/edit', [UserController::class, 'editUserInventory'])->name('user.inventory.edit');
        Route::post('/userInventory/update', [UserController::class, 'updateUserInventory'])->name('user.inventory.update');
        Route::delete('/userInventory/{inventory}', [UserController::class, 'destroyUserInventory'])->name('user.inventory.destroy');

        Route::get('/trainingEquipment', [TrainingEquipmentController::class, 'index'])->name('trainingEquipments.index');
        Route::get('/trainingEquipment/edit', [TrainingEquipmentController::class, 'edit'])->name('trainingEquipments.edit');
        Route::post('/trainingEquipment/update', [TrainingEquipmentController::class, 'update'])->name('trainingEquipments.update');
        Route::delete('/trainingEquipment/{trainingEquipment}', [TrainingEquipmentController::class, 'destroy'])->name('trainingEquipments.destroy');
        Route::get('/userTrainingEquipment/edit', [UserController::class, 'editUserTrainingEquipment'])->name('user.training_equipment.edit');
        Route::post('/userTrainingEquipment/update', [UserController::class, 'updateUserTrainingEquipment'])->name('user.training_equipment.update');
        Route::delete('/userTrainingEquipment/{trainingEquipment}', [UserController::class, 'destroyUserTrainingEquipment'])->name('user.training_equipment.destroy'); 
    });
});

require __DIR__ . '/auth.php';
