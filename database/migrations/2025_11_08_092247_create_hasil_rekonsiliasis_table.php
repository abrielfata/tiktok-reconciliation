<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_rekonsiliasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal');
            $table->decimal('total_api', 15, 2);
            $table->decimal('total_host', 15, 2);
            $table->decimal('selisih', 15, 2); // Bisa negatif atau positif
            $table->enum('status', ['COCOK', 'SELISIH', 'HOST_BELUM_LAPOR']);
            $table->timestamps();
            
            // Constraint: 1 user, 1 tanggal = 1 hasil rekonsiliasi
            $table->unique(['user_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_rekonsiliasi');
    }
};