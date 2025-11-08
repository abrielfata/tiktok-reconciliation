<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_penjualan_api', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique(); // ID order dari TikTok (unik)
            $table->date('tanggal_penjualan');
            $table->decimal('total_harga', 15, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_penjualan_api');
    }
};