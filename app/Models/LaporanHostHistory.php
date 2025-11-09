<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanHostHistory extends Model
{
    use HasFactory;

    protected $table = 'laporan_host_history';

    protected $fillable = [
        'laporan_host_id',
        'user_id',
        'action',
        'tanggal_laporan_old',
        'tanggal_laporan_new',
        'total_penjualan_host_old',
        'total_penjualan_host_new',
        'notes',
    ];

    protected $casts = [
        'tanggal_laporan_old' => 'date',
        'tanggal_laporan_new' => 'date',
        'total_penjualan_host_old' => 'decimal:2',
        'total_penjualan_host_new' => 'decimal:2',
    ];

    // Relasi
    public function laporanHost()
    {
        return $this->belongsTo(LaporanHost::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
