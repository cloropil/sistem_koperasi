<?php

namespace App\Http\Controllers;

use App\Models\SimpananAnggota;
use App\Models\Anggota;
use Illuminate\Http\Request;

class SimpananController extends Controller
{
    /**
     * Display a listing of simpanan.
     */
    public function index()
    {
        $simpanans = SimpananAnggota::with('anggota')->get();
        return view('simpanan.index', ['simpanans' => $simpanans]);
    }

    /**
     * Show the form for creating a new simpanan.
     */
    public function create()
    {
        $anggotas = Anggota::all();
        return view('simpanan.create', ['anggotas' => $anggotas]);
    }

    /**
     * Store a newly created simpanan.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'anggota_id' => 'required|exists:anggotas,id',
            'simpanan_pokok' => 'required|numeric|min:0',
            'simpanan_wajib' => 'required|numeric|min:0',
        ]);

        SimpananAnggota::create($validated);

        return redirect()->route('simpanan.index')->with('success', 'Simpanan berhasil ditambahkan');
    }

    /**
     * Display the specified simpanan.
     */
    public function show(SimpananAnggota $simpanan)
    {
        return view('simpanan.show', ['simpanan' => $simpanan]);
    }

    /**
     * Show the form for editing the specified simpanan.
     */
    public function edit(SimpananAnggota $simpanan)
    {
        $anggotas = Anggota::all();
        return view('simpanan.edit', ['simpanan' => $simpanan, 'anggotas' => $anggotas]);
    }

    /**
     * Update the specified simpanan.
     */
    public function update(Request $request, SimpananAnggota $simpanan)
    {
        $validated = $request->validate([
            'anggota_id' => 'required|exists:anggotas,id',
            'simpanan_pokok' => 'required|numeric|min:0',
            'simpanan_wajib' => 'required|numeric|min:0',
        ]);

        $simpanan->update($validated);

        return redirect()->route('simpanan.show', $simpanan)->with('success', 'Simpanan berhasil diupdate');
    }

    /**
     * Remove the specified simpanan.
     */
    public function destroy(SimpananAnggota $simpanan)
    {
        $simpanan->delete();

        return redirect()->route('simpanan.index')->with('success', 'Simpanan berhasil dihapus');
    }
}
