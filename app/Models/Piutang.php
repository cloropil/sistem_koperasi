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
        'jangka_pinjaman',
        'status_lunas',
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    public function pembayarans()
    {
        return $this->hasMany(PembayaranPiutang::class)->orderBy('bulan_ke');
    }

    /**
     * Get maksimal pinjaman berdasarkan jabatan
     * Militer & PNS: 50 juta
     * PPPK: 30 juta
     * Honorer: berdasarkan simpanan
     */
    public static function getMaxPinjamanByJabatan($jabatan, $simpanan = 0)
    {
        switch ($jabatan) {
            case 'Militer':
            case 'PNS':
                return 50000000;
            case 'PPPK':
                return 30000000;
            case 'Honorer':
                return $simpanan; // Sesuai dengan simpanan yang tersimpan
            default:
                return 0;
        }
    }
}
