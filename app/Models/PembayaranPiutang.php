<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranPiutang extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_piutangs';

    protected $fillable = [
        'piutang_id',
        'bulan_ke',
        'tanggal_jatuh_tempo',
        'jumlah_pembayaran',
        'jumlah_dibayar',
        'status',
        'tanggal_pembayaran',
    ];

    protected $casts = [
        'tanggal_jatuh_tempo' => 'date',
        'tanggal_pembayaran' => 'date',
    ];

    public function piutang()
    {
        return $this->belongsTo(Piutang::class);
    }
}

