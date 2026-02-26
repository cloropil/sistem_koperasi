@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <h1>Edit Simpanan</h1>
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('simpanan.update', $simpanan->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group mb-3">
                    <label for="anggota_id">Anggota</label>
                    <select class="form-control" id="anggota_id" name="anggota_id" required>
                        @foreach ($anggotas as $anggota)
                            <option value="{{ $anggota->id }}" {{ $simpanan->anggota_id == $anggota->id ? 'selected' : '' }}>{{ $anggota->nama }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group mb-3">
                    <label for="simpanan_pokok">Simpanan Pokok</label>
                    <input type="number" class="form-control" id="simpanan_pokok" name="simpanan_pokok" value="{{ $simpanan->simpanan_pokok }}" step="0.01" required>
                </div>

                <div class="form-group mb-3">
                    <label for="simpanan_wajib">Simpanan Wajib</label>
                    <input type="number" class="form-control" id="simpanan_wajib" name="simpanan_wajib" value="{{ $simpanan->simpanan_wajib }}" step="0.01" required>
                </div>

                <button type="submit" class="btn btn-warning">Update</button>
                <a class="btn btn-secondary" href="{{ route('simpanan.index') }}">Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection
