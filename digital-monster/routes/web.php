<?php

use App\Http\Controllers\MonsterController;
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
    if (Auth::check()) {
        return redirect('/dashboard');
    } else {
        return view('pages.welcome');
    }
});

// Fallback Route - Handles all undefined routes
Route::fallback(function () {
    if (Auth::check()) {
        return response()->view('pages.404', [], 404);
    } else {
        return redirect('/');
    }
});

//Log In Required
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
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
        Route::get('/eggGroup/edit', [EggGroupController::class, 'edit'])->name('egg_group.edit');
        Route::post('/eggGroup/update', [EggGroupController::class, 'update'])->name('egg_group.update');
        Route::delete('/eggGroup/delete', [EggGroupController::class, 'destroy'])->name('egg_group.destroy');

        Route::get('/monsters', [MonsterController::class, 'index'])->name('monsters.index');
        Route::get('/monster/edit', [MonsterController::class, 'edit'])->name('monster.edit');
        Route::post('/monster/update', [MonsterController::class, 'update'])->name('monster.update');
        Route::delete('/monster/delete', [MonsterController::class, 'destroy'])->name('monster.destroy');
        Route::get('/userMonster/edit', [UserController::class, 'editUserMonster'])->name('user.monster.edit');
        Route::post('/userMonster/update', [UserController::class, 'updateUserMonster'])->name('user.monster.update');
        Route::delete('/userMonster/delete', [UserController::class, 'destroyUserMonster'])->name('user.monster.destroy');

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
