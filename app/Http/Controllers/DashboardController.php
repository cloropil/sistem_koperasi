<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\SimpananAnggota;
use App\Models\Piutang;
use App\Models\PengajuanPinjaman;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        $jumlah_anggota = Anggota::count();
        $jumlah_simpanan = SimpananAnggota::count();
        $jumlah_piutang = Piutang::count();
        $jumlah_pengajuan = PengajuanPinjaman::count();

        return view('dashboard', [
            'jumlah_anggota' => $jumlah_anggota,
            'jumlah_simpanan' => $jumlah_simpanan,
            'jumlah_piutang' => $jumlah_piutang,
            'jumlah_pengajuan' => $jumlah_pengajuan,
        ]);
    }
}
