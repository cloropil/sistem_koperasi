<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Http\Request;

class AnggotaController extends Controller
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
     * Display a listing of anggotas.
     */
    public function index()
    {
        $anggotas = Anggota::all();
        return view('anggota.index', ['anggotas' => $anggotas]);
    }

    /**
     * Show the form for creating a new anggota.
     */
    public function create()
    {
        return view('anggota.create');
    }

    /**
     * Store a newly created anggota in database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|unique:anggotas,nip|max:255',
            'status' => 'required|string',
            'jabatan' => 'required|string',
            'nomor_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        Anggota::create($validated);

        return redirect()->route('anggota.index')->with('success', 'Anggota berhasil ditambahkan');
    }

    /**
     * Display the specified anggota.
     */
    public function show($id)
    {
        $anggota = Anggota::findOrFail($id);
        return view('anggota.show', ['anggota' => $anggota]);
    }

    /**
     * Show the form for editing the specified anggota.
     */
    public function edit($id)
    {
        $anggota = Anggota::findOrFail($id);
        return view('anggota.edit', ['anggota' => $anggota]);
    }

    /**
     * Update the specified anggota in database.
     */
    public function update(Request $request, $id)
    {
        $anggota = Anggota::findOrFail($id);
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|string|unique:anggotas,nip,' . $anggota->id . '|max:255',
            'status' => 'required|string',
            'jabatan' => 'required|string',
            'nomor_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
        ]);

        $anggota->update($validated);

        return redirect()->route('anggota.show', $anggota->id)->with('success', 'Anggota berhasil diupdate');
    }

    /**
     * Remove the specified anggota from database.
     */
    public function destroy($id)
    {
        $anggota = Anggota::findOrFail($id);
        $anggota->delete();

        return redirect()->route('anggota.index')->with('success', 'Anggota berhasil dihapus');
    }
}
