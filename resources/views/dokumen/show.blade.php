@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Pratinjau Dokumen</span>
                    <a href="{{ route('dokumen.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
                </div>
                <div class="card-body">
                    <p><strong>Nama Dokumen:</strong> {{ $dokumen->nama_dokumen }}</p>
                    <p><strong>Keterangan:</strong> {{ $dokumen->keterangan ?? '-' }}</p>
                    <p><strong>Jenis File:</strong> {{ strtoupper(pathinfo($dokumen->file_path, PATHINFO_EXTENSION)) }}</p>

                    @php
                        $extension = strtolower(pathinfo($dokumen->file_path, PATHINFO_EXTENSION));
                        $url = asset('storage/' . $dokumen->file_path);
                    @endphp

                    @if(in_array($extension, ['pdf']))
                        <iframe src="{{ $url }}" width="100%" height="800" frameborder="0"></iframe>
                        <p class="mt-3">
                            Jika dokumen tidak muncul, klik <a href="{{ $url }}" target="_blank">di sini</a> untuk membuka langsung.
                        </p>
                    @elseif(in_array($extension, ['png', 'jpg', 'jpeg', 'gif']))
                        <img src="{{ $url }}" class="img-fluid" alt="{{ $dokumen->nama_dokumen }}">
                    @else
                        <div class="alert alert-info">
                            Pratinjau tidak tersedia untuk jenis file ini.
                            <br>
                            <a href="{{ $url }}" target="_blank" class="btn btn-primary btn-sm mt-2">Buka / Unduh Dokumen</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
