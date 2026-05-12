@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <h1>Detail Piutang</h1>
            
            <div class="card">
                <div class="card-body">
                    <p><strong>Nama Anggota:</strong> {{ $piutang->anggota->nama }}</p>
                    <p><strong>NIP:</strong> {{ $piutang->anggota->nip }}</p>
                    <p><strong>Jabatan:</strong> {{ $piutang->jabatan }}</p>
                    <p><strong>Jumlah Pinjam:</strong> Rp. {{ number_format($piutang->jumlah_pinjam, 0, ',', '.') }}</p>
                    <p><strong>Sisa Piutang:</strong> Rp. {{ number_format($piutang->sisa_piutang, 0, ',', '.') }}</p>
                    <p><strong>Jangka Waktu Pinjaman:</strong> {{ $piutang->jangka_pinjaman }} bulan</p>
                    <p><strong>Pembayaran Per Bulan:</strong> Rp. {{ number_format($piutang->pembayaran_perbulan, 0, ',', '.') }}</p>
                    <p><strong>Status:</strong> 
                        @if($piutang->status_lunas)
                            <span class="badge bg-success">Lunas</span>
                        @else
                            <span class="badge bg-danger">Belum Lunas</span>
                        @endif
                    </p>
                    <p><strong>Tanggal Dibuat:</strong> {{ $piutang->created_at->format('d-m-Y H:i') }}</p>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Tambah Jadwal Pembayaran</h5>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('pembayaran_piutang.store', ['piutang' => $piutang->id]) }}" class="row g-3" id="paymentForm">
                        @csrf
                        <div class="col-md-2">
                            <label class="form-label">Bulan Ke</label>
                            <input type="number" name="bulan_ke" min="1" class="form-control" value="{{ old('bulan_ke') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Jatuh Tempo</label>
                            <input type="date" name="tanggal_jatuh_tempo" class="form-control" value="{{ old('tanggal_jatuh_tempo') }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Jumlah Pembayaran</label>
                            <input type="number" name="jumlah_pembayaran" id="jumlah_pembayaran" class="form-control" value="{{ old('jumlah_pembayaran') }}" readonly required>
                            <small class="text-muted">Otomatis dihitung: (Pinjaman ÷ Jangka Waktu) + (1% × Pinjaman)</small>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Jumlah Dibayar</label>
                            <input type="number" name="jumlah_dibayar" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Tanggal Bayar</label>
                            <input type="date" name="tanggal_pembayaran" class="form-control" value="{{ old('tanggal_pembayaran') }}">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-success">Tambah Jadwal</button>
                            <p class="text-muted small mt-2">Jika jumlah dibayar diisi, sisa piutang akan otomatis berkurang.</p>
                        </div>
                    </form>
                </div>
                @php
                    $pembayarans = $piutang->pembayarans()->orderBy('bulan_ke')->paginate(24); // 24 bulan = 2 tahun per halaman
                @endphp
                @if($pembayarans->count() > 0)
                <div class="card-body table-responsive">
                    <h6 class="mb-3">Jadwal Pembayaran yang Telah Ditambahkan ({{ $pembayarans->total() }} total)</h6>
                    <div class="mb-3">
                        <small class="text-muted">Menampilkan {{ $pembayarans->count() }} dari {{ $pembayarans->total() }} jadwal pembayaran</small>
                    </div>
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>Bulan Ke</th>
                                <th>Jatuh Tempo</th>
                                <th>Jumlah Pembayaran</th>
                                <th>Jumlah Dibayar</th>
                                <th>Status</th>
                                <th>Tanggal Pembayaran</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pembayarans as $pembayaran)
                            <tr>
                                <form method="POST" action="{{ route('pembayaran_piutang.update', ['piutang' => $piutang->id, 'pembayaran' => $pembayaran->id]) }}">
                                    @csrf
                                    @method('PUT')
                                    <td>{{ $pembayaran->bulan_ke }}</td>
                                    <td>{{ $pembayaran->tanggal_jatuh_tempo->format('d-m-Y') }}</td>
                                    <td>Rp. {{ number_format($pembayaran->jumlah_pembayaran, 0, ',', '.') }}</td>
                                    <td>
                                        <input type="number" name="jumlah_dibayar" class="form-control form-control-sm" value="{{ old('jumlah_dibayar', $pembayaran->jumlah_dibayar) }}" required>
                                    </td>
                                    <td>
                                        @if($pembayaran->status == 'lunas')
                                            <span class="badge bg-success">Lunas</span>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        <input type="date" name="tanggal_pembayaran" class="form-control form-control-sm" value="{{ old('tanggal_pembayaran', $pembayaran->tanggal_pembayaran ? $pembayaran->tanggal_pembayaran->toDateString() : '') }}" required>
                                    </td>
                                    <td>
                                        <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                                    </td>
                                </form>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                    @if($pembayarans->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        {{ $pembayarans->links() }}
                    </div>
                    @endif
                </div>
                @endif
            </div>

            <a class="btn btn-warning mt-3" href="{{ route('piutang.edit', $piutang->id) }}">Edit</a>
            <a class="btn btn-secondary" href="{{ route('piutang.index') }}">Kembali</a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-calculate payment amount when form loads
    calculatePaymentAmount();
    
    function calculatePaymentAmount() {
        const jumlahPinjam = {{ $piutang->jumlah_pinjam }};
        const jangkaPinjaman = {{ $piutang->jangka_pinjaman }};
        
        // Formula: (jumlah pinjaman ÷ jangka waktu pinjaman) + (1% × jumlah pinjaman)
        const basePayment = jumlahPinjam / jangkaPinjaman;
        const interest = 0.01 * jumlahPinjam; // 1% of loan amount
        const totalPayment = basePayment + interest;
        
        // Round to nearest integer (pure number only)
        document.getElementById('jumlah_pembayaran').value = Math.round(totalPayment);
    }
});
</script>
@endsection
