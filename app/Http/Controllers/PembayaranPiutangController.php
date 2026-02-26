<?php

namespace App\Http\Controllers;

use App\Models\PembayaranPiutang;
use Illuminate\Http\Request;

class PembayaranPiutangController extends Controller
{
    /**
     * Update a payment record (tanggal dan jumlah dibayar).
     *
     * Applies a 10% penalty to the next month's instalment if this payment
     * was made after the due date without full amount.
     */
    public function update(Request $request, $piutangId, $id)
    {
        $pembayaran = PembayaranPiutang::findOrFail($id);

        $validated = $request->validate([
            'tanggal_pembayaran' => 'required|date',
            'jumlah_dibayar' => 'required|numeric|min:0',
        ]);

        $pembayaran->tanggal_pembayaran = $validated['tanggal_pembayaran'];
        $pembayaran->jumlah_dibayar = $validated['jumlah_dibayar'];
        if ($validated['jumlah_dibayar'] >= $pembayaran->jumlah_pembayaran) {
            $pembayaran->status = 'lunas';
        }
        $pembayaran->save();

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
