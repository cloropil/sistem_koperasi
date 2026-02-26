@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <h1>Edit Piutang</h1>
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('piutang.update', $piutang->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group mb-3">
                    <label for="anggota_id">Anggota</label>
                    <select class="form-control" id="anggota_id" name="anggota_id" required>
                        @foreach ($anggotas as $anggota)
                            <option value="{{ $anggota->id }}" {{ $piutang->anggota_id == $anggota->id ? 'selected' : '' }}>{{ $anggota->nama }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group mb-3">
                    <label for="jabatan">Jabatan</label>
                    <select class="form-control" id="jabatan" name="jabatan" required>
                        <option value="Militer" {{ $piutang->jabatan == 'Militer' ? 'selected' : '' }}>Militer</option>
                        <option value="PNS" {{ $piutang->jabatan == 'PNS' ? 'selected' : '' }}>PNS (Pegawai Negeri Sipil)</option>
                        <option value="PPPK" {{ $piutang->jabatan == 'PPPK' ? 'selected' : '' }}>PPPK (Pemerintah dengan Perjanjian Kerja)</option>
                        <option value="Honorer" {{ $piutang->jabatan == 'Honorer' ? 'selected' : '' }}>Honorer</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="jumlah_pinjam">Jumlah Pinjam</label>
                    <input type="number" class="form-control" id="jumlah_pinjam" name="jumlah_pinjam" value="{{ $piutang->jumlah_pinjam }}" step="0.01" required>
                </div>

                <div class="form-group mb-3">
                    <label for="sisa_piutang">Sisa Piutang</label>
                    <input type="number" class="form-control" id="sisa_piutang" name="sisa_piutang" value="{{ $piutang->sisa_piutang }}" step="0.01" required>
                </div>

                <div class="form-group mb-3">
                    <label for="pembayaran_perbulan">Pembayaran Per Bulan</label>
                    <input type="number" class="form-control" id="pembayaran_perbulan" name="pembayaran_perbulan" value="{{ $piutang->pembayaran_perbulan }}" step="0.01" required>
                </div>

                <div class="form-group mb-3">
                    <label for="status_lunas">Status</label>
                    <div>
                        <input type="radio" id="status_lunas_0" name="status_lunas" value="0" {{ !$piutang->status_lunas ? 'checked' : '' }}> <label for="status_lunas_0">Belum Lunas</label>
                        <input type="radio" id="status_lunas_1" name="status_lunas" value="1" {{ $piutang->status_lunas ? 'checked' : '' }}> <label for="status_lunas_1">Lunas</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-warning">Update</button>
                <a class="btn btn-secondary" href="{{ route('piutang.index') }}">Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection
