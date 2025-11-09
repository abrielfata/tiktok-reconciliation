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
        Route::get('/host/laporan/{id}/edit', [HostController::class, 'edit'])->name('host.laporan.edit');
        Route::put('/host/laporan/{id}', [HostController::class, 'update'])->name('host.laporan.update');
        Route::delete('/host/laporan/{id}', [HostController::class, 'destroy'])->name('host.laporan.destroy');
        Route::get('/host/laporan/{id}/history', [HostController::class, 'history'])->name('host.laporan.history');
        Route::get('/host/activities', [HostController::class, 'activities'])->name('host.activities');
    });

    // Routes khusus MANAJER (hanya bisa diakses oleh user dengan role 'MANAJER')
    Route::middleware('role:MANAJER')->group(function () {
        Route::get('/manager/dashboard', [ManagerController::class, 'dashboard'])->name('manager.dashboard');
    });

    Route::get('/tiktok/auth', function() {
    $appKey = config('services.tiktok.app_key');
    $redirectUri = url('/tiktok/callback');
    $state = Str::random(32); // Random string untuk security
    
    $authUrl = "https://services.tiktokshop.com/open/authorize?" . http_build_query([
        'app_key' => $appKey,
        'state' => $state,
        'redirect_uri' => $redirectUri,
    ]);
    
    session(['tiktok_state' => $state]);
    
    return redirect($authUrl);
    })->name('tiktok.auth');

    Route::get('/tiktok/callback', function() {
    $code = request('code');
    $state = request('state');
    
    if ($state !== session('tiktok_state')) {
        return 'Invalid state';
    }
    
    // TODO: Exchange code for access token
    // Simpan access token ke database
    
    dd('Authorization code: ' . $code);
    })->name('tiktok.callback');
});

// Auth routes (login, register, dll - bawaan Breeze)
require __DIR__.'/auth.php';