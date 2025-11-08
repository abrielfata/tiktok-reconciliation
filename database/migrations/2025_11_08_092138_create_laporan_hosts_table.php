<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_host', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal_laporan');
            $table->decimal('total_penjualan_host', 15, 2); // 15 digit total, 2 desimal
            $table->timestamps();
            
            // Constraint: 1 user hanya bisa submit 1 laporan per hari
            $table->unique(['user_id', 'tanggal_laporan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_host');
    }
};