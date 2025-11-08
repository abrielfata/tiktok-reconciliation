<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataPenjualanApi extends Model
{
    use HasFactory;

    protected $table = 'data_penjualan_api';

    protected $fillable = [
        'order_id',
        'tanggal_penjualan',
        'total_harga',
    ];

    protected $casts = [
        'tanggal_penjualan' => 'date',
        'total_harga' => 'decimal:2',
    ];
}