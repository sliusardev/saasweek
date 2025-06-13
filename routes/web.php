<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

//Route::get('dashboard', function () {
//    return Inertia::render('Dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');

Route::prefix('dashboard')->middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'users'])->name('users.list');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::get('/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/{user}/delete', [UserController::class, 'delete'])->name('users.delete');
    });
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
