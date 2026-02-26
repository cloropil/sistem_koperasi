<?php

namespace App\Http\Controllers;

use App\Models\PengajuanPinjaman;
use App\Models\Anggota;
use App\Models\SimpananAnggota;
use Illuminate\Http\Request;

class PengajuanPinjamanController extends Controller
{
    /**
     * Display a listing of pengajuan pinjaman.
     */
    public function index()
    {
        $pengajuans = PengajuanPinjaman::with('anggota', 'simpanan')->get();
        return view('pengajuan_pinjaman.index', ['pengajuans' => $pengajuans]);
    }

    /**
     * Show the form for creating a new pengajuan pinjaman.
     */
    public function create()
    {
        $anggotas = Anggota::all();
        $simpanans = SimpananAnggota::all();
        return view('pengajuan_pinjaman.create', [
            'anggotas' => $anggotas,
            'simpanans' => $simpanans
        ]);
    }

    /**
     * Store a newly created pengajuan pinjaman.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'anggota_id' => 'required|exists:anggotas,id',
            'simpanan_id' => 'nullable|exists:simpanan_anggotas,id',
            'jumlah_pengajuan' => 'required|numeric|min:0',
            'status' => 'required|string|in:pending,approved,rejected',
        ]);

        PengajuanPinjaman::create($validated);

        return redirect()->route('pengajuan_pinjaman.index')->with('success', 'Pengajuan pinjaman berhasil ditambahkan');
    }

    /**
     * Display the specified pengajuan pinjaman.
     */
    public function show(PengajuanPinjaman $pengajuan_pinjaman)
    {
        return view('pengajuan_pinjaman.show', ['pengajuan' => $pengajuan_pinjaman]);
    }

    /**
     * Show the form for editing the specified pengajuan pinjaman.
     */
    public function edit(PengajuanPinjaman $pengajuan_pinjaman)
    {
        $anggotas = Anggota::all();
        $simpanans = SimpananAnggota::all();
        return view('pengajuan_pinjaman.edit', [
            'pengajuan' => $pengajuan_pinjaman,
            'anggotas' => $anggotas,
            'simpanans' => $simpanans
        ]);
    }

    /**
     * Update the specified pengajuan pinjaman.
     */
    public function update(Request $request, PengajuanPinjaman $pengajuan_pinjaman)
    {
        $validated = $request->validate([
            'anggota_id' => 'required|exists:anggotas,id',
            'simpanan_id' => 'nullable|exists:simpanan_anggotas,id',
            'jumlah_pengajuan' => 'required|numeric|min:0',
            'status' => 'required|string|in:pending,approved,rejected',
        ]);

        $pengajuan_pinjaman->update($validated);

        return redirect()->route('pengajuan_pinjaman.show', $pengajuan_pinjaman)->with('success', 'Pengajuan pinjaman berhasil diupdate');
    }

    /**
     * Remove the specified pengajuan pinjaman.
     */
    public function destroy(PengajuanPinjaman $pengajuan_pinjaman)
    {
        $pengajuan_pinjaman->delete();

        return redirect()->route('pengajuan_pinjaman.index')->with('success', 'Pengajuan pinjaman berhasil dihapus');
    }
}
