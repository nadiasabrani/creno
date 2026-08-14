<?php

use App\Http\Controllers\Admin\CreneauController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ProfileController;
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

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/creneaux', [CreneauController::class, 'index'])->name('creneaux.index');
        Route::get('/creneaux/create', [CreneauController::class, 'create'])->name('creneaux.create');
        Route::post('/creneaux', [CreneauController::class, 'store'])->name('creneaux.store');
        Route::get('/creneaux/{creneau}/edit', [CreneauController::class, 'edit'])->name('creneaux.edit');
        Route::put('/creneaux/{creneau}', [CreneauController::class, 'update'])->name('creneaux.update');
        Route::delete('/creneaux/{creneau}', [CreneauController::class, 'destroy'])->name('creneaux.destroy');
    });

require __DIR__.'/auth.php';
