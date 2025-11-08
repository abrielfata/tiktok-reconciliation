<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Command bawaan Laravel (inspirational quote)
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// ========================================
// SCHEDULE: Rekonsiliasi TikTok Otomatis
// ========================================

// Jalankan setiap hari jam 02:00 pagi
Schedule::command('reconcile:tiktok')
    ->dailyAt('02:00')
    ->timezone('Asia/Jakarta')
    ->withoutOverlapping() // Cegah command berjalan 2x bersamaan
    ->onSuccess(function () {
        // Log ketika berhasil
        \Log::info('✅ Rekonsiliasi TikTok berhasil dijalankan: ' . now());
    })
    ->onFailure(function () {
        // Log ketika gagal
        \Log::error('❌ Rekonsiliasi TikTok gagal: ' . now());
        
        // TODO: Kirim notifikasi ke Manajer (email/telegram)
    });

// OPTIONAL: Jalankan juga setiap jam (untuk testing)
// Uncomment baris di bawah jika ingin testing scheduler
// Schedule::command('reconcile:tiktok')->hourly();