<?php

namespace App\Http\Controllers;

use App\Models\Piutang;
use App\Models\Anggota;
use Illuminate\Http\Request;

class PiutangController extends Controller
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
    public function show($id)
    {
        $piutang = Piutang::findOrFail($id);
        return view('piutang.show', ['piutang' => $piutang]);
    }

    /**
     * Show the form for editing the specified piutang.
     */
    public function edit($id)
    {
        $piutang = Piutang::findOrFail($id);
        $anggotas = Anggota::all();
        return view('piutang.edit', ['piutang' => $piutang, 'anggotas' => $anggotas]);
    }

    /**
     * Update the specified piutang.
     */
    public function update(Request $request, $id)
    {
        $piutang = Piutang::findOrFail($id);
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
    public function destroy($id)
    {
        $piutang = Piutang::findOrFail($id);
        $piutang->delete();

        return redirect()->route('piutang.index')->with('success', 'Piutang berhasil dihapus');
    }
}
