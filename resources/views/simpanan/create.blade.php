@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <h1>Tambah Simpanan</h1>
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('simpanan.store') }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label for="anggota_id">Anggota</label>
                    <select class="form-control" id="anggota_id" name="anggota_id" required>
                        <option value="">Pilih Anggota</option>
                        @foreach ($anggotas as $anggota)
                            <option value="{{ $anggota->id }}">{{ $anggota->nama }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group mb-3">
                    <label for="simpanan_pokok">Simpanan Pokok</label>
                    <input type="number" class="form-control" id="simpanan_pokok" name="simpanan_pokok" required>
                </div>

                <div class="form-group mb-3">
                    <label for="simpanan_wajib">Simpanan Wajib (1 Tahun)</label>
                    <input type="number" class="form-control" id="simpanan_wajib" name="simpanan_wajib" required>
                </div>

                <button type="submit" class="btn btn-success">Simpan</button>
                <a class="btn btn-secondary" href="{{ route('simpanan.index') }}">Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection
