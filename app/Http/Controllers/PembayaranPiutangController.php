<?php

namespace App\Http\Controllers;

use App\Models\PembayaranPiutang;
use App\Models\Piutang;
use App\Models\SimpananAnggota;
use Illuminate\Http\Request;

class PembayaranPiutangController extends Controller
{
    /**
     * Store a new manual payment schedule.
     *
     * If jumlah_dibayar is entered, that amount is deducted from remaining debt.
     */
    public function store(Request $request, $piutangId)
    {
        $piutang = Piutang::findOrFail($piutangId);

        $validated = $request->validate([
            'bulan_ke' => 'required|integer|min:1',
            'tanggal_jatuh_tempo' => 'required|date',
            'jumlah_pembayaran' => 'required|numeric|min:0',
            'jumlah_dibayar' => 'nullable|numeric|min:0',
            'tanggal_pembayaran' => 'nullable|date',
        ]);

        $jumlahDibayar = $validated['jumlah_dibayar'] ?? 0;
        if ($jumlahDibayar > 0 && empty($validated['tanggal_pembayaran'])) {
            return redirect()->route('piutang.show', $piutangId)
                ->withErrors(['tanggal_pembayaran' => 'Tanggal pembayaran harus diisi jika ada jumlah bayar.']);
        }

        $status = ($jumlahDibayar >= $validated['jumlah_pembayaran'] && $jumlahDibayar > 0) ? 'lunas' : 'pending';

        PembayaranPiutang::create([
            'piutang_id' => $piutangId,
            'bulan_ke' => $validated['bulan_ke'],
            'tanggal_jatuh_tempo' => $validated['tanggal_jatuh_tempo'],
            'jumlah_pembayaran' => $validated['jumlah_pembayaran'],
            'jumlah_dibayar' => $jumlahDibayar,
            'status' => $status,
            'tanggal_pembayaran' => $validated['tanggal_pembayaran'] ?? null,
        ]);

        if ($jumlahDibayar > 0) {
            $piutang->decrement('sisa_piutang', $jumlahDibayar);
            if ($piutang->sisa_piutang <= 0) {
                $piutang->status_lunas = true;
                $piutang->sisa_piutang = 0;
                $piutang->save();
            }

            // Add payment amount back to member's total savings
            $simpanan = SimpananAnggota::where('anggota_id', $piutangId)->first();
            if ($simpanan) {
                $simpanan->increment('total_simpanan', $jumlahDibayar);
            }
        }

        return redirect()->route('piutang.show', $piutangId)
            ->with('success', 'Jadwal pembayaran manual berhasil ditambahkan');
    }

    /**
     * Update a payment record (tanggal dan jumlah dibayar).
     *
     * Applies a 10% penalty to the next month's instalment if this payment
     * was made after the due date without full amount.
     */
    public function update(Request $request, $piutangId, $id)
    {
        $pembayaran = PembayaranPiutang::findOrFail($id);
        $piutang = Piutang::findOrFail($piutangId);

        $validated = $request->validate([
            'tanggal_pembayaran' => 'required|date',
            'jumlah_dibayar' => 'required|numeric|min:0',
        ]);

        $oldJumlahDibayar = $pembayaran->jumlah_dibayar;
        $delta = $validated['jumlah_dibayar'] - $oldJumlahDibayar;

        $pembayaran->tanggal_pembayaran = $validated['tanggal_pembayaran'];
        $pembayaran->jumlah_dibayar = $validated['jumlah_dibayar'];
        if ($validated['jumlah_dibayar'] >= $pembayaran->jumlah_pembayaran) {
            $pembayaran->status = 'lunas';
        }
        $pembayaran->save();

        if ($delta !== 0) {
            $piutang->decrement('sisa_piutang', $delta);
            if ($piutang->sisa_piutang <= 0) {
                $piutang->status_lunas = true;
                $piutang->sisa_piutang = 0;
                $piutang->save();
            }

            // Add payment amount back to member's total savings
            $simpanan = SimpananAnggota::where('anggota_id', $piutang->anggota_id)->first();
            if ($simpanan) {
                $simpanan->increment('total_simpanan', $delta);
            }
        }

        // if paid late and not full, add penalty to next installment
        if (
            $pembayaran->tanggal_pembayaran->gt($pembayaran->tanggal_jatuh_tempo) &&
            $validated['jumlah_dibayar'] < $pembayaran->jumlah_pembayaran
        ) {
            $next = PembayaranPiutang::where('piutang_id', $pembayaran->piutang_id)
                ->where('bulan_ke', $pembayaran->bulan_ke + 1)
                ->first();
            if ($next) {
                $penalty = $pembayaran->jumlah_pembayaran * 0.10; // 10 %
                $next->jumlah_pembayaran += $penalty;
                $next->save();
            }
        }

        return redirect()->route('piutang.show', $pembayaran->piutang_id)
            ->with('success', 'Pembayaran diperbarui');
    }
}
