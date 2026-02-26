@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Admin Dashboard</h1>
            <p>Selamat datang di Sistem Koperasi</p>

            <div class="row mt-4">
                <div class="col-md-3 mb-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h5 class="card-title">Anggota</h5>
                            <p class="card-text display-4">
                                @if(isset($jumlah_anggota))
                                    {{ $jumlah_anggota }}
                                @else
                                    -
                                @endif
                            </p>
                            <a href="{{ route('anggota.index') }}" class="btn btn-light">Lihat Detail</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5 class="card-title">Simpanan</h5>
                            <p class="card-text display-4">
                                @if(isset($jumlah_simpanan))
                                    {{ $jumlah_simpanan }}
                                @else
                                    -
                                @endif
                            </p>
                            <a href="{{ route('simpanan.index') }}" class="btn btn-light">Lihat Detail</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h5 class="card-title">Piutang</h5>
                            <p class="card-text display-4">
                                @if(isset($jumlah_piutang))
                                    {{ $jumlah_piutang }}
                                @else
                                    -
                                @endif
                            </p>
                            <a href="{{ route('piutang.index') }}" class="btn btn-light">Lihat Detail</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 mb-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <h5 class="card-title">Pengajuan</h5>
                            <p class="card-text display-4">
                                @if(isset($jumlah_pengajuan))
                                    {{ $jumlah_pengajuan }}
                                @else
                                    -
                                @endif
                            </p>
                            <a href="{{ route('pengajuan_pinjaman.index') }}" class="btn btn-light">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
