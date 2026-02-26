<?php

namespace App\Http\Controllers;

use App\Models\Piutang;
use App\Models\Anggota;
use Illuminate\Http\Request;

class PiutangController extends Controller
{
    /**
     * Display a listing of piutang.
     */
    public function index()
    {
        $piutangs = Piutang::with('anggota')->get();
        return view('piutang.index', ['piutangs' => $piutangs]);
    }

    /**
     * Show the form for creating a new piutang.
     */
    public function create()
    {
        $anggotas = Anggota::all();
        return view('piutang.create', ['anggotas' => $anggotas]);
    }

    /**
     * Store a newly created piutang.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'anggota_id' => 'required|exists:anggotas,id',
            'jabatan' => 'required|string|in:Militer,PNS,PPPK,Honorer',
            'jumlah_pinjam' => 'required|numeric|min:0',
            'sisa_piutang' => 'required|numeric|min:0',
            'pembayaran_perbulan' => 'required|numeric|min:0',
            'status_lunas' => 'required|boolean',
        ]);

        Piutang::create($validated);

        return redirect()->route('piutang.index')->with('success', 'Piutang berhasil ditambahkan');
    }

    /**
     * Display the specified piutang.
     */
    public function show(Piutang $piutang)
    {
        return view('piutang.show', ['piutang' => $piutang]);
    }

    /**
     * Show the form for editing the specified piutang.
     */
    public function edit(Piutang $piutang)
    {
        $anggotas = Anggota::all();
        return view('piutang.edit', ['piutang' => $piutang, 'anggotas' => $anggotas]);
    }

    /**
     * Update the specified piutang.
     */
    public function update(Request $request, Piutang $piutang)
    {
        $validated = $request->validate([
            'anggota_id' => 'required|exists:anggotas,id',
            'jabatan' => 'required|string|in:Militer,PNS,PPPK,Honorer',
            'jumlah_pinjam' => 'required|numeric|min:0',
            'sisa_piutang' => 'required|numeric|min:0',
            'pembayaran_perbulan' => 'required|numeric|min:0',
            'status_lunas' => 'required|boolean',
        ]);

        $piutang->update($validated);

        return redirect()->route('piutang.show', $piutang)->with('success', 'Piutang berhasil diupdate');
    }

    /**
     * Remove the specified piutang.
     */
    public function destroy(Piutang $piutang)
    {
        $piutang->delete();

        return redirect()->route('piutang.index')->with('success', 'Piutang berhasil dihapus');
    }
}
