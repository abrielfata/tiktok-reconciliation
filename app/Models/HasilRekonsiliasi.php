<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilRekonsiliasi extends Model
{
    use HasFactory;

    protected $table = 'hasil_rekonsiliasi';

    protected $fillable = [
        'user_id',
        'tanggal',
        'total_api',
        'total_host',
        'selisih',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'total_api' => 'decimal:2',
        'total_host' => 'decimal:2',
        'selisih' => 'decimal:2',
    ];

    // Relasi: HasilRekonsiliasi milik 1 User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}