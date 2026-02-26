<?php

namespace App\Http\Controllers;

use App\Models\PengajuanPinjaman;
use App\Models\Anggota;
use App\Models\SimpananAnggota;
use App\Models\Piutang;
use Illuminate\Http\Request;

class PengajuanPinjamanController extends Controller
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
        $piutangs = Piutang::all();
        
        // Get list of anggota with outstanding piutang
        $anggotaWithDebt = Piutang::where('status_lunas', false)->pluck('anggota_id')->toArray();
        
        return view('pengajuan_pinjaman.create', [
            'anggotas' => $anggotas,
            'simpanans' => $simpanans,
            'piutangs' => $piutangs,
            'anggotaWithDebt' => $anggotaWithDebt
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

        // Check jika anggota masih punya utang yang belum lunas
        $existingDebt = Piutang::where('anggota_id', $validated['anggota_id'])
            ->where('status_lunas', false)
            ->first();
        
        if ($existingDebt) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['anggota_id' => "Anggota masih memiliki tagihan sebesar Rp " . number_format($existingDebt->sisa_piutang, 0, ',', '.') . ". Harap lunasi terlebih dahulu."]);
        }

        // Validasi maksimal pinjaman berdasarkan jabatan
        $piutang = Piutang::where('anggota_id', $validated['anggota_id'])->first();
        if ($piutang) {
            // Hitung total simpanan untuk Honorer
            $totalSimpanan = 0;
            if ($validated['simpanan_id']) {
                $simpanan = SimpananAnggota::find($validated['simpanan_id']);
                $totalSimpanan = ($simpanan->simpanan_pokok ?? 0) + ($simpanan->simpanan_wajib ?? 0);
            }

            $maxPinjaman = Piutang::getMaxPinjamanByJabatan($piutang->jabatan, $totalSimpanan);
            
            if ($validated['jumlah_pengajuan'] > $maxPinjaman) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['jumlah_pengajuan' => "Maksimal pinjaman untuk {$piutang->jabatan} adalah Rp " . number_format($maxPinjaman, 0, ',', '.')]);
            }
        }

        PengajuanPinjaman::create($validated);

        return redirect()->route('pengajuan_pinjaman.index')->with('success', 'Pengajuan pinjaman berhasil ditambahkan');
    }

    /**
     * Display the specified pengajuan pinjaman.
     */
    public function show($id)
    {
        $pengajuan_pinjaman = PengajuanPinjaman::findOrFail($id);
        return view('pengajuan_pinjaman.show', ['pengajuan' => $pengajuan_pinjaman]);
    }

    /**
     * Show the form for editing the specified pengajuan pinjaman.
     */
    public function edit($id)
    {
        $pengajuan_pinjaman = PengajuanPinjaman::findOrFail($id);
        $anggotas = Anggota::all();
        $simpanans = SimpananAnggota::all();
        $piutangs = Piutang::all();
        
        // Get list of anggota with outstanding piutang
        $anggotaWithDebt = Piutang::where('status_lunas', false)->pluck('anggota_id')->toArray();
        
        return view('pengajuan_pinjaman.edit', [
            'pengajuan' => $pengajuan_pinjaman,
            'anggotas' => $anggotas,
            'simpanans' => $simpanans,
            'piutangs' => $piutangs,
            'anggotaWithDebt' => $anggotaWithDebt
        ]);
    }

    /**
     * Update the specified pengajuan pinjaman.
     */
    public function update(Request $request, $id)
    {
        $pengajuan_pinjaman = PengajuanPinjaman::findOrFail($id);
        $validated = $request->validate([
            'anggota_id' => 'required|exists:anggotas,id',
            'simpanan_id' => 'nullable|exists:simpanan_anggotas,id',
            'jumlah_pengajuan' => 'required|numeric|min:0',
            'status' => 'required|string|in:pending,approved,rejected',
        ]);

        // Validasi maksimal pinjaman berdasarkan jabatan
        $piutang = Piutang::where('anggota_id', $validated['anggota_id'])->first();
        if ($piutang) {
            // Hitung total simpanan untuk Honorer
            $totalSimpanan = 0;
            if ($validated['simpanan_id']) {
                $simpanan = SimpananAnggota::find($validated['simpanan_id']);
                $totalSimpanan = ($simpanan->simpanan_pokok ?? 0) + ($simpanan->simpanan_wajib ?? 0);
            }

            $maxPinjaman = Piutang::getMaxPinjamanByJabatan($piutang->jabatan, $totalSimpanan);
            
            if ($validated['jumlah_pengajuan'] > $maxPinjaman) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['jumlah_pengajuan' => "Maksimal pinjaman untuk {$piutang->jabatan} adalah Rp " . number_format($maxPinjaman, 0, ',', '.')]);
            }
        }

        $pengajuan_pinjaman->update($validated);

        // If status changed to 'approved', create piutang
        if ($validated['status'] === 'approved' && $pengajuan_pinjaman->status !== 'approved') {
            $piutang = Piutang::where('anggota_id', $validated['anggota_id'])->first();
            
            if ($piutang) {
                // Create new piutang record
                Piutang::create([
                    'anggota_id' => $validated['anggota_id'],
                    'jabatan' => $piutang->jabatan,
                    'jumlah_pinjam' => $validated['jumlah_pengajuan'],
                    'sisa_piutang' => $validated['jumlah_pengajuan'],
                    'pembayaran_perbulan' => 0, // Admin bisa set nanti
                    'status_lunas' => false,
                ]);
            }
        }

        return redirect()->route('pengajuan_pinjaman.show', $pengajuan_pinjaman)->with('success', 'Pengajuan pinjaman berhasil diupdate');
    }

    /**
     * Remove the specified pengajuan pinjaman.
     */
    public function destroy($id)
    {
        $pengajuan_pinjaman = PengajuanPinjaman::findOrFail($id);
        $pengajuan_pinjaman->delete();

        return redirect()->route('pengajuan_pinjaman.index')->with('success', 'Pengajuan pinjaman berhasil dihapus');
    }
}
