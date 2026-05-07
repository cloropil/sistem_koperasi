<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SimpananAnggota extends Model
{
    use HasFactory;

    protected $fillable = [
        'anggota_id',
        'simpanan_pokok',
        'simpanan_wajib',
        'total_simpanan',
    ];

    protected $casts = [
        'simpanan_pokok' => 'float',
        'simpanan_wajib' => 'float',
        'total_simpanan' => 'float',
    ];

    protected $attributes = [
        'total_simpanan' => 0,
    ];

    protected $appends = [];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }

    /**
     * Get total simpanan (pokok + wajib + total_simpanan)
     */
    public function getTotalSimpanan()
    {
        $pokok = $this->getAttribute('simpanan_pokok') ?? 0;
        $wajib = $this->getAttribute('simpanan_wajib') ?? 0;
        $total = $this->getAttribute('total_simpanan') ?? 0;
        
        return (float)$pokok + (float)$wajib + (float)$total;
    }
}
