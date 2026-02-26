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
                    <h5 class="mb-0">Jadwal Pembayaran (Maksimal 12 Bulan)</h5>
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
                </div>
                <div class="card-body table-responsive">
                    @php
                        $pembayarans = $piutang->pembayarans()->limit(12)->get();
                    @endphp
                    @if($pembayarans->count() > 0)
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
                                            <input type="number" step="0.01" name="jumlah_dibayar" class="form-control form-control-sm" value="{{ old('jumlah_dibayar', $pembayaran->jumlah_dibayar) }}" required>
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
                    @else
                        <p class="text-muted">Tidak ada jadwal pembayaran</p>
                    @endif
                </div>
            </div>

            <a class="btn btn-warning mt-3" href="{{ route('piutang.edit', $piutang->id) }}">Edit</a>
            <a class="btn btn-secondary" href="{{ route('piutang.index') }}">Kembali</a>
        </div>
    </div>
</div>
@endsection
