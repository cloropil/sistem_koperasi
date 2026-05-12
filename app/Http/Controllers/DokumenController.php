<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DokumenController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $dokumens = Dokumen::latest()->get();
        return view('dokumen.index', compact('dokumens'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_dokumen' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,png,jpg,jpeg,txt|max:10240',
            'keterangan' => 'nullable|string|max:1000',
        ]);

        $path = $request->file('file')->store('dokumen', 'public');

        Dokumen::create([
            'nama_dokumen' => $validated['nama_dokumen'],
            'file_path' => $path,
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        return redirect()->route('dokumen.index')->with('success', 'Dokumen berhasil diunggah.');
    }

    public function show($id)
    {
        $dokumen = Dokumen::findOrFail($id);

        return view('dokumen.show', compact('dokumen'));
    }

    public function print($id)
    {
        $dokumen = Dokumen::findOrFail($id);
        return view('dokumen.print', compact('dokumen'));
    }

    public function destroy($id)
    {
        $dokumen = Dokumen::findOrFail($id);
        Storage::disk('public')->delete($dokumen->file_path);
        $dokumen->delete();

        return redirect()->route('dokumen.index')->with('success', 'Dokumen berhasil dihapus.');
    }
}
