@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <h1>Detail Simpanan</h1>
            
            <div class="card">
                <div class="card-body">
                    <p><strong>Nama Anggota:</strong> {{ $simpanan->anggota->nama }}</p>
                    <p><strong>NIP:</strong> {{ $simpanan->anggota->nip }}</p>
                    <p><strong>Simpanan Pokok:</strong> Rp. {{ number_format($simpanan->simpanan_pokok, 0, ',', '.') }}</p>
                    <p><strong>Simpanan Wajib:</strong> Rp. {{ number_format($simpanan->simpanan_wajib, 0, ',', '.') }}</p>
                    <p><strong>Total Simpanan:</strong> Rp. {{ number_format($simpanan->simpanan_pokok + $simpanan->simpanan_wajib, 0, ',', '.') }}</p>
                    <p><strong>Tanggal Dibuat:</strong> {{ $simpanan->created_at->format('d-m-Y H:i') }}</p>
                </div>
            </div>

            <a class="btn btn-warning mt-3" href="{{ route('simpanan.edit', $simpanan->id) }}">Edit</a>
            <a class="btn btn-secondary" href="{{ route('simpanan.index') }}">Kembali</a>
        </div>
    </div>
</div>
@endsection
