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

            <a class="btn btn-warning mt-3" href="{{ route('piutang.edit', $piutang->id) }}">Edit</a>
            <a class="btn btn-secondary" href="{{ route('piutang.index') }}">Kembali</a>
        </div>
    </div>
</div>
@endsection
