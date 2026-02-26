@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <h1>{{ $anggota->nama }}</h1>
            
            <div class="card mb-3">
                <div class="card-body">
                    <p><strong>NIP:</strong> {{ $anggota->nip }}</p>
                    <p><strong>Status:</strong> {{ $anggota->status }}</p>
                    <p><strong>Jabatan:</strong> {{ $anggota->jabatan }}</p>
                    <p><strong>Nomor HP:</strong> {{ $anggota->nomor_hp }}</p>
                    <p><strong>Alamat:</strong> {{ $anggota->alamat }}</p>
                </div>
            </div>

            <a class="btn btn-warning" href="{{ route('anggota.edit', $anggota->id) }}">Edit</a>
            <a class="btn btn-secondary" href="{{ route('anggota.index') }}">Kembali</a>
        </div>
    </div>
</div>
@endsection
