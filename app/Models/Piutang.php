<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Piutang extends Model
{
    use HasFactory;

    protected $fillable = [
        'anggota_id',
        'jabatan',
        'jumlah_pinjam',
        'sisa_piutang',
        'pembayaran_perbulan',
        'status_lunas',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }
}
