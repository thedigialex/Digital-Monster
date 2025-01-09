<?php

use App\Http\Controllers\DigitalMonsterController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\TrainingEquipmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EggGroupController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware(['admin'])->group(function () {
        Route::get('/egg_groups', [EggGroupController::class, 'index'])->name('egg_groups.index');
        Route::get('/egg_group/edit', [EggGroupController::class, 'edit'])->name('egg_groups.edit');
        Route::post('/egg_groups/update', [EggGroupController::class, 'update'])->name('egg_groups.update');
        Route::delete('/egg_groups/{eggGroup}', [EggGroupController::class, 'destroy'])->name('egg_groups.destroy');

        Route::get('/digital_monsters', [DigitalMonsterController::class, 'index'])->name('digital_monsters.index');
        Route::get('/digital_monsters/edit', [DigitalMonsterController::class, 'edit'])->name('digital_monsters.edit');
        Route::post('/digital_monsters/update', [DigitalMonsterController::class, 'update'])->name('digital_monsters.update');
        Route::delete('/digital_monsters/{digitalMonster}', [DigitalMonsterController::class, 'destroy'])->name('digital_monsters.destroy');

        Route::get('/items', [ItemController::class, 'index'])->name('items.index');
        Route::get('/items/edit', [ItemController::class, 'edit'])->name('items.edit');
        Route::post('/items/update', [ItemController::class, 'update'])->name('items.update');
        Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy');

        Route::get('/trainingEquipments', [TrainingEquipmentController::class, 'index'])->name('trainingEquipments.index');
        Route::get('/trainingEquipments/edit', [TrainingEquipmentController::class, 'edit'])->name('trainingEquipments.edit');
        Route::post('/trainingEquipments/update', [TrainingEquipmentController::class, 'update'])->name('trainingEquipments.update');
        Route::delete('/trainingEquipments/{trainingEquipment}', [TrainingEquipmentController::class, 'destroy'])->name('trainingEquipments.destroy');

        Route::get('/users', [UserController::class, 'index'])->name('users.index');

        Route::get('/user_digital_monsters', [UserController::class, 'showUserDigitalMonsters'])->name('user.digital_monsters');
        Route::get('/user_digital_monsters/edit', [UserController::class, 'editUserDigitalMonster'])->name('user.digital_monsters.edit');
        Route::post('/user_digital_monsters/update', [UserController::class, 'updateUserDigitalMonster'])->name('user.digital_monsters.update');
        Route::delete('/user_digital_monsters/{userDigitalMonster}', [UserController::class, 'destroyUserDigitalMonster'])->name('user.digital_monsters.destroy');

        Route::get('/user_inventory', [UserController::class, 'showUserInventory'])->name('user.inventory');
        Route::get('/user_inventory/edit', [UserController::class, 'editUserInventory'])->name('user.inventory.edit');
        Route::post('/user_inventory/update', [UserController::class, 'updateUserInventory'])->name('user.inventory.update');
        Route::delete('/user_inventory/{inventory}', [UserController::class, 'destroyUserInventory'])->name('user.inventory.destroy');

        Route::get('/user_training_equipment', [UserController::class, 'showUserTrainingEquipment'])->name('user.training_equipment');
        Route::get('/user_training_equipment/edit', [UserController::class, 'editUserTrainingEquipment'])->name('user.training_equipment.edit');
        Route::post('/user_training_equipment/update', [UserController::class, 'updateUserTrainingEquipment'])->name('user.training_equipment.update');
        Route::delete('/user_training_equipment/{trainingEquipment}', [UserController::class, 'destroyUserTrainingEquipment'])->name('user.training_equipment.destroy');
    });
});

require __DIR__ . '/auth.php';
