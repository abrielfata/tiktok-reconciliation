<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('laporan_host_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_host_id')->nullable()->constrained('laporan_host')->onDelete('set null');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('action', ['created', 'updated', 'deleted']);
            $table->date('tanggal_laporan_old')->nullable();
            $table->date('tanggal_laporan_new')->nullable();
            $table->decimal('total_penjualan_host_old', 15, 2)->nullable();
            $table->decimal('total_penjualan_host_new', 15, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('laporan_host_id');
            $table->index('user_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_host_history');
    }
};
