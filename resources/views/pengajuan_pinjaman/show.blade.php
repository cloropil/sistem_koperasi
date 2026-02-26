@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <h1>Detail Pengajuan Pinjaman</h1>
            
            <div class="card">
                <div class="card-body">
                    <p><strong>Nama Anggota:</strong> {{ $pengajuan->anggota->nama }}</p>
                    <p><strong>NIP:</strong> {{ $pengajuan->anggota->nip }}</p>
                    <p><strong>Jumlah Pengajuan:</strong> Rp. {{ number_format($pengajuan->jumlah_pengajuan, 0, ',', '.') }}</p>
                    <p><strong>Simpanan Terkait:</strong> 
                        @if($pengajuan->simpanan)
                            Rp. {{ number_format($pengajuan->simpanan->simpanan_pokok + $pengajuan->simpanan->simpanan_wajib, 0, ',', '.') }}
                        @else
                            <em>Tidak ada</em>
                        @endif
                    </p>
                    <p><strong>Status:</strong> 
                        @if($pengajuan->status == 'pending')
                            <span class="badge bg-warning">Pending</span>
                        @elseif($pengajuan->status == 'approved')
                            <span class="badge bg-success">Approved</span>
                        @else
                            <span class="badge bg-danger">Rejected</span>
                        @endif
                    </p>
                    <p><strong>Tanggal Pengajuan:</strong> {{ $pengajuan->created_at->format('d-m-Y H:i') }}</p>
                </div>
            </div>

            <a class="btn btn-warning mt-3" href="{{ route('pengajuan_pinjaman.edit', $pengajuan->id) }}">Edit</a>
            <a class="btn btn-secondary" href="{{ route('pengajuan_pinjaman.index') }}">Kembali</a>
        </div>
    </div>
</div>
@endsection
