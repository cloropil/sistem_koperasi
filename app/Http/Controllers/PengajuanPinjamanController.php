<?php

namespace App\Http\Controllers;

use App\Models\PengajuanPinjaman;
use App\Models\Anggota;
use App\Models\SimpananAnggota;
use App\Models\Piutang;
use App\Models\PembayaranPiutang;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

        // Build simpananByAnggota map for frontend auto-fill (id, pokok, wajib, total)
        $simpananByAnggota = [];
        foreach ($anggotas as $anggota) {
            $simpanan = SimpananAnggota::where('anggota_id', $anggota->id)->first();
            if ($simpanan) {
                $simpananByAnggota[$anggota->id] = [
                    'id' => $simpanan->id,
                    'total' => $simpanan->simpanan_pokok + $simpanan->simpanan_wajib,
                    'pokok' => $simpanan->simpanan_pokok,
                    'wajib' => $simpanan->simpanan_wajib,
                ];
            }
        }

        return view('pengajuan_pinjaman.create', [
            'anggotas' => $anggotas,
            'simpanans' => $simpanans,
            'piutangs' => $piutangs,
            'anggotaWithDebt' => $anggotaWithDebt,
            'simpananByAnggota' => $simpananByAnggota,
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
            'jangka_pinjaman' => 'required|integer|in:3,6,12,24,36,48,60',
            'status' => 'required|string|in:pending,approved,rejected',
        ]);

        // Check jika anggota masih punya utang yang belum lunas
        $existingDebt = Piutang::where('anggota_id', $validated['anggota_id'])
            ->where('status_lunas', false)
            ->first();

        // Hitung total simpanan (untuk Honorer rule)
        $totalSimpanan = 0;
        if ($validated['simpanan_id']) {
            $simpanan = SimpananAnggota::find($validated['simpanan_id']);
            $totalSimpanan = ($simpanan->simpanan_pokok ?? 0) + ($simpanan->simpanan_wajib ?? 0);
        } else {
            $simpanan = SimpananAnggota::where('anggota_id', $validated['anggota_id'])->first();
            if ($simpanan) {
                $totalSimpanan = ($simpanan->simpanan_pokok ?? 0) + ($simpanan->simpanan_wajib ?? 0);
            }
        }

        // Ambil jabatan dari anggota (jika belum ada piutang sebelumnya)
        $anggota = Anggota::find($validated['anggota_id']);
        $jabatan = $anggota ? $anggota->jabatan : null;

        // Determine max pinjaman
        $maxPinjaman = Piutang::getMaxPinjamanByJabatan($jabatan, $totalSimpanan);

        // Jika anggota punya utang yang belum lunas -> buat pengajuan dengan status 'rejected'
        if ($existingDebt) {
            $validated['status'] = 'rejected';
            $pengajuan = PengajuanPinjaman::create($validated);
            return redirect()->route('pengajuan_pinjaman.index')->with('warning', 'Pengajuan otomatis ditolak karena anggota masih memiliki piutang belum lunas.');
        }

        // Jika jumlah pengajuan melebihi maksimum -> buat pengajuan dengan status 'rejected'
        if ($maxPinjaman > 0 && $validated['jumlah_pengajuan'] > $maxPinjaman) {
            $validated['status'] = 'rejected';
            $pengajuan = PengajuanPinjaman::create($validated);
            return redirect()->route('pengajuan_pinjaman.index')->with('warning', 'Pengajuan otomatis ditolak karena melebihi batas maksimal pinjaman untuk jabatan/ketentuan anggota.');
        }

        // Jika lolos semua, simpan seperti biasa
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

        // Build simpananByAnggota map for frontend auto-fill (id, pokok, wajib, total)
        $simpananByAnggota = [];
        foreach ($anggotas as $anggota) {
            $simpanan = SimpananAnggota::where('anggota_id', $anggota->id)->first();
            if ($simpanan) {
                $simpananByAnggota[$anggota->id] = [
                    'id' => $simpanan->id,
                    'total' => $simpanan->simpanan_pokok + $simpanan->simpanan_wajib,
                    'pokok' => $simpanan->simpanan_pokok,
                    'wajib' => $simpanan->simpanan_wajib,
                ];
            }
        }

        return view('pengajuan_pinjaman.edit', [
            'pengajuan' => $pengajuan_pinjaman,
            'anggotas' => $anggotas,
            'simpanans' => $simpanans,
            'piutangs' => $piutangs,
            'anggotaWithDebt' => $anggotaWithDebt,
            'simpananByAnggota' => $simpananByAnggota,
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
            'jangka_pinjaman' => 'required|integer|in:3,6,12,24,36,48,60',
            'status' => 'required|string|in:pending,approved,rejected',
        ]);

        // Validasi maksimal pinjaman berdasarkan jabatan
        // Ambil jabatan dari anggota (bukan hanya dari piutang yang sudah ada)
        $anggota = Anggota::find($validated['anggota_id']);
        $jabatan = $anggota ? $anggota->jabatan : null;

        // Hitung total simpanan untuk Honorer
        $totalSimpanan = 0;
        if ($validated['simpanan_id']) {
            $simpanan = SimpananAnggota::find($validated['simpanan_id']);
            $totalSimpanan = ($simpanan->simpanan_pokok ?? 0) + ($simpanan->simpanan_wajib ?? 0);
        } else {
            $simpanan = SimpananAnggota::where('anggota_id', $validated['anggota_id'])->first();
            if ($simpanan) {
                $totalSimpanan = ($simpanan->simpanan_pokok ?? 0) + ($simpanan->simpanan_wajib ?? 0);
            }
        }

        $maxPinjaman = Piutang::getMaxPinjamanByJabatan($jabatan, $totalSimpanan);

        // Jika max dilanggar saat update dan status diminta 'approved', ubah jadi 'rejected'
        if ($validated['status'] === 'approved' && $maxPinjaman > 0 && $validated['jumlah_pengajuan'] > $maxPinjaman) {
            $validated['status'] = 'rejected';
        }

        // Simpan status lama sebelum update
        $oldStatus = $pengajuan_pinjaman->status;
        $pengajuan_pinjaman->update($validated);

        // If status changed to 'approved', create piutang
        if ($validated['status'] === 'approved' && $oldStatus !== 'approved') {
            $piutang = Piutang::where('anggota_id', $validated['anggota_id'])->first();
            $jab = $piutang ? $piutang->jabatan : ($anggota ? $anggota->jabatan : null);
            
            if ($jab) {
                $pembayaranPerbulan = 0;
                if ($validated['jangka_pinjaman'] && $validated['jangka_pinjaman'] > 0) {
                    $pembayaranPerbulan = $validated['jumlah_pengajuan'] / $validated['jangka_pinjaman'];
                }

                $piutangBaru = Piutang::create([
                    'anggota_id' => $validated['anggota_id'],
                    'jabatan' => $jab,
                    'jumlah_pinjam' => $validated['jumlah_pengajuan'],
                    'sisa_piutang' => $validated['jumlah_pengajuan'],
                    'pembayaran_perbulan' => $pembayaranPerbulan,
                    'jangka_pinjaman' => $validated['jangka_pinjaman'],
                    'status_lunas' => false,
                ]);

                // Generate jadwal pembayaran per bulan
                // pertama jatuh tempo satu bulan setelah persetujuan
                $tanggalMulai = Carbon::now()->addMonth();
                for ($bulan = 1; $bulan <= $validated['jangka_pinjaman']; $bulan++) {
                    PembayaranPiutang::create([
                        'piutang_id' => $piutangBaru->id,
                        'bulan_ke' => $bulan,
                        'tanggal_jatuh_tempo' => $tanggalMulai->copy()->addMonths($bulan - 1)->endOfMonth(),
                        'jumlah_pembayaran' => $pembayaranPerbulan,
                        'jumlah_dibayar' => 0,
                        'status' => 'pending',
                        'tanggal_pembayaran' => null,
                    ]);
                }
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
