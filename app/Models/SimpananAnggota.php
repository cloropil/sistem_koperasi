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
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class);
    }
}
