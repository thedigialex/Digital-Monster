<?php

use App\Http\Controllers\DigitalMonsterController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EggGroupController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

//Public
Route::get('/', function () {
    return view('pages.welcome');
});

Route::fallback(function () {
    return response()->view('pages.404', [], 404);
});

Route::fallback(function () {
    if (Auth::check()) {
        return response()->view('pages.404', [], 404);
    } else {
        return response()->view('pages.welcome', [], 404);
    }
});

//Log In Required
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware(['auth'])
        ->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Admin Only
    Route::middleware(['admin'])->group(function () {
        Route::post('/session/store', [SessionController::class, 'store'])->name('session.store');
        Route::get('/session/get/{model}', [SessionController::class, 'get'])->name('session.get');
        Route::post('/session/clear', [SessionController::class, 'clear'])->name('session.clear');


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
        Route::get('/item/edit', [ItemController::class, 'edit'])->name('item.edit');
        Route::post('/item/update', [ItemController::class, 'update'])->name('item.update');
        Route::delete('/item/delete', [ItemController::class, 'destroy'])->name('item.destroy');


        Route::get('/userItem/edit', [UserController::class, 'editUserItem'])->name('user.item.edit');
        Route::post('/userItem/update', [UserController::class, 'updateUserItem'])->name('user.item.update');
        Route::delete('/userItem/delete', [UserController::class, 'destroyUserItem'])->name('user.item.destroy');

        Route::get('/equipment', [EquipmentController::class, 'index'])->name('equipment.index');
        Route::get('/equipment/edit', [EquipmentController::class, 'edit'])->name('equipment.edit');
        Route::post('/equipment/update', [EquipmentController::class, 'update'])->name('equipment.update');
        Route::delete('/equipment/delete', [EquipmentController::class, 'destroy'])->name('equipment.destroy');
        Route::get('/userEquipment/edit', [UserController::class, 'editUserEquipment'])->name('user.equipment.edit');
        Route::post('/userEquipment/update', [UserController::class, 'updateUserEquipment'])->name('user.equipment.update');
        Route::delete('/userEquipment/delete', [UserController::class, 'destroyUserEquipment'])->name('user.equipment.destroy');
    });
});

require __DIR__ . '/auth.php';
