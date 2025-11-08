<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HostController;
use App\Http\Controllers\ManagerController;
use Illuminate\Support\Facades\Route;

// Halaman utama (sebelum login)
Route::get('/', function () {
    return view('welcome');
});

// Dashboard (setelah login)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route untuk user yang sudah login
Route::middleware('auth')->group(function () {
    // Profile routes (bawaan Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Routes khusus HOST (hanya bisa diakses oleh user dengan role 'HOST')
    Route::middleware('role:HOST')->group(function () {
        Route::get('/host/laporan', [HostController::class, 'create'])->name('host.laporan.create');
        Route::post('/host/laporan', [HostController::class, 'store'])->name('host.laporan.store');
    });

    // Routes khusus MANAJER (hanya bisa diakses oleh user dengan role 'MANAJER')
    Route::middleware('role:MANAJER')->group(function () {
        Route::get('/manager/dashboard', [ManagerController::class, 'dashboard'])->name('manager.dashboard');
    });
});

// Auth routes (login, register, dll - bawaan Breeze)
require __DIR__.'/auth.php';