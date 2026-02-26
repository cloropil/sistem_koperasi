<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'nip',
        'status',
        'jabatan',
        'nomor_hp',
        'alamat',
    ];

    public function simpanans()
    {
        return $this->hasMany(SimpananAnggota::class);
    }

    public function piutangs()
    {
        return $this->hasMany(Piutang::class);
    }

    public function pengajuanPinjamans()
    {
        return $this->hasMany(PengajuanPinjaman::class);
    }
}
