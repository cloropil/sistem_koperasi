@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <h1>Tambah Anggota</h1>
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('anggota.store') }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label for="nama">Nama</label>
                    <input type="text" class="form-control" id="nama" name="nama" required>
                </div>
                
                <div class="form-group mb-3">
                    <label for="nip">NIP</label>
                    <input type="text" class="form-control" id="nip" name="nip" required>
                </div>

                <div class="form-group mb-3">
                    <label for="status">Status</label>
                    <input type="text" class="form-control" id="status" name="status" placeholder="aktif/non-aktif" required>
                </div>

                <div class="form-group mb-3">
                    <label for="jabatan">Jabatan</label>
                    <input type="text" class="form-control" id="jabatan" name="jabatan" required>
                </div>

                <div class="form-group mb-3">
                    <label for="nomor_hp">Nomor HP</label>
                    <input type="tel" class="form-control" id="nomor_hp" name="nomor_hp">
                </div>

                <div class="form-group mb-3">
                    <label for="alamat">Alamat</label>
                    <textarea class="form-control" id="alamat" name="alamat" rows="3"></textarea>
                </div>

                <button type="submit" class="btn btn-success">Simpan</button>
                <a class="btn btn-secondary" href="{{ route('anggota.index') }}">Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection
