<?php

namespace App\Http\Controllers;

use App\Models\SimpananAnggota;
use App\Models\Anggota;
use Illuminate\Http\Request;

class SimpananController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (! auth()->user() || ! auth()->user()->isAdmin()) {
                abort(403, 'Unauthorized');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of simpanans.
     */
    public function index()
    {
        $simpanans = SimpananAnggota::with('anggota')->get();
        
        // Group simpanans by jabatan
        $groupedSimpanans = [
            'Militer & PNS' => [],
            'PPPK' => [],
            'Honorer' => [],
        ];

        foreach ($simpanans as $simpanan) {
            $jabatan = $simpanan->anggota->jabatan;
            
            if (in_array($jabatan, ['Militer', 'PNS'])) {
                $groupedSimpanans['Militer & PNS'][] = $simpanan;
            } elseif ($jabatan === 'PPPK') {
                $groupedSimpanans['PPPK'][] = $simpanan;
            } elseif ($jabatan === 'Honorer') {
                $groupedSimpanans['Honorer'][] = $simpanan;
            }
        }

        return view('simpanan.index', ['groupedSimpanans' => $groupedSimpanans, 'simpanans' => $simpanans]);
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
    public function show($id)
    {
        $simpanan = SimpananAnggota::findOrFail($id);
        return view('simpanan.show', ['simpanan' => $simpanan]);
    }

    /**
     * Show the form for editing the specified simpanan.
     */
    public function edit($id)
    {
        $simpanan = SimpananAnggota::findOrFail($id);
        $anggotas = Anggota::all();
        return view('simpanan.edit', ['simpanan' => $simpanan, 'anggotas' => $anggotas]);
    }

    /**
     * Update the specified simpanan.
     */
    public function update(Request $request, $id)
    {
        $simpanan = SimpananAnggota::findOrFail($id);
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
    public function destroy($id)
    {
        $simpanan = SimpananAnggota::findOrFail($id);
        $simpanan->delete();

        return redirect()->route('simpanan.index')->with('success', 'Simpanan berhasil dihapus');
    }
}
