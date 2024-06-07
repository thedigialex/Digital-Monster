<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DigitalMonsterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserDigitalMonsterController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\InventoryController;
use App\Http\Middleware\IsAdmin;
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
});

Route::middleware(IsAdmin::class)->group(function () {
    Route::get('/monsters/create', [DigitalMonsterController::class, 'create'])->name('monsters.create');
    Route::get('/monsters', [DigitalMonsterController::class, 'index'])->name('monsters.index');
    Route::post('/monsters/store', [DigitalMonsterController::class, 'store'])->name('monsters.store');
    Route::get('/monsters/edit/{id}', [DigitalMonsterController::class, 'edit'])->name('monsters.edit');
    Route::put('/monsters/{id}', [DigitalMonsterController::class, 'update'])->name('monsters.update');
    Route::delete('/monsters/{id}', [DigitalMonsterController::class, 'destroy'])->name('monsters.destroy');

    Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
    Route::get('/items', [ItemController::class, 'index'])->name('items.index');
    Route::post('/items/store', [ItemController::class, 'store'])->name('items.store');
    Route::get('/items/edit/{id}', [ItemController::class, 'edit'])->name('items.edit');
    Route::put('/items/{id}', [ItemController::class, 'update'])->name('items.update');
    Route::delete('/items/{id}', [ItemController::class, 'destroy'])->name('items.destroy');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/user/{id}', [UserController::class, 'show'])->name('user.show');
    Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update');

    Route::match(['get', 'post'], '/user/{id}/usermonster/{monsterId?}', [UserDigitalMonsterController::class, 'handleMonster'])->name('user.handleMonster');
    Route::delete('/user/{id}/usermonster/{monsterId}', [UserDigitalMonsterController::class, 'deleteMonster'])->name('user.deleteMonster');


    Route::match(['get', 'post'], '/user/{id}/inventory/{itemId?}', [InventoryController::class, 'handleItem'])->name('user.handleItem');
    Route::delete('/user/{id}/inventory/{itemId}', [InventoryController::class, 'deleteItem'])->name('user.deleteItem');
});


require __DIR__ . '/auth.php';
