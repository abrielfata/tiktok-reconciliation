<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanHost extends Model
{
    use HasFactory;

    protected $table = 'laporan_host'; // Nama tabel (Laravel default pakai plural)

    protected $fillable = [
        'user_id',
        'tanggal_laporan',
        'total_penjualan_host',
    ];

    protected $casts = [
        'tanggal_laporan' => 'date',
        'total_penjualan_host' => 'decimal:2',
    ];

    // Relasi: LaporanHost milik 1 User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}