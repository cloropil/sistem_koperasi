<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanPinjaman extends Model
{
    /**
     * Explicit table name. Laravel would otherwise try to pluralize to "pengajuan_pinjamen".
     *
     * @var string
     */
    protected $table = 'pengajuan_pinjamans';

    use HasFactory;

    protected $fillable = [
        'anggota_id',
        'simpanan_id',
        'jumlah_pengajuan',
        'status',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function simpanan()
    {
        return $this->belongsTo(SimpananAnggota::class, 'simpanan_id');
    }
}
