<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DigitalMonsterController;
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



    Route::get('/monsters/create', [DigitalMonsterController::class, 'create'])->name('monsters.create');
    Route::post('/monsters/store', [DigitalMonsterController::class, 'store'])->name('monsters.store');
    Route::get('/monsters/edit/{id}', [DigitalMonsterController::class, 'edit'])->name('monsters.edit');
    Route::put('/monsters/{id}}', [DigitalMonsterController::class, 'update'])->name('monsters.update');
});

Route::middleware(IsAdmin::class)->group(function () {
    Route::get('/monsters/create', [DigitalMonsterController::class, 'create'])->name('monsters.create');
    Route::get('/monsters', [DigitalMonsterController::class, 'index'])->name('monsters.index');
    Route::post('/monsters/store', [DigitalMonsterController::class, 'store'])->name('monsters.store');
    Route::get('/monsters/edit/{id}', [DigitalMonsterController::class, 'edit'])->name('monsters.edit');
    Route::put('/monsters/{id}', [DigitalMonsterController::class, 'update'])->name('monsters.update');
    Route::delete('/monsters/{id}', [DigitalMonsterController::class, 'destroy'])->name('monsters.destroy');
});


require __DIR__ . '/auth.php';
