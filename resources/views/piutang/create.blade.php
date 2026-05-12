@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <h1>Tambah Piutang</h1>
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('piutang.store') }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label for="anggota_id">Anggota</label>
                    <select class="form-control" id="anggota_id" name="anggota_id" required>
                        <option value="">Pilih Anggota</option>
                        @foreach ($anggotas as $anggota)
                            <option value="{{ $anggota->id }}" data-jabatan="{{ $anggota->jabatan }}" {{ old('anggota_id') == $anggota->id ? 'selected' : '' }}>
                                {{ $anggota->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group mb-3">
                    <label for="jabatan_display">Jabatan</label>
                    <input type="text" class="form-control" id="jabatan_display" value="{{ old('jabatan') ?? '' }}" disabled>
                    <input type="hidden" id="jabatan" name="jabatan" value="{{ old('jabatan') ?? '' }}">
                </div>

                <div class="form-group mb-3">
                    <label for="jumlah_pinjam">Jumlah Pinjam</label>
                    <input type="number" class="form-control" id="jumlah_pinjam" name="jumlah_pinjam" required>
                </div>

                <div class="form-group mb-3">
                    <label for="sisa_piutang">Sisa Piutang</label>
                    <input type="number" class="form-control" id="sisa_piutang" name="sisa_piutang" required>
                </div>

                <div class="form-group mb-3">
                    <label for="jangka_pinjaman">Jangka Waktu (Bulan)</label>
                    <select class="form-control" id="jangka_pinjaman" name="jangka_pinjaman" required>
                        <option value="">Pilih Jangka Waktu</option>
                        <option value="3" {{ old('jangka_pinjaman') == '3' ? 'selected' : '' }}>3 Bulan</option>
                        <option value="6" {{ old('jangka_pinjaman') == '6' ? 'selected' : '' }}>6 Bulan</option>
                        <option value="12" {{ old('jangka_pinjaman') == '12' ? 'selected' : '' }}>12 Bulan</option>
                        <option value="24" {{ old('jangka_pinjaman') == '24' ? 'selected' : '' }}>24 Bulan</option>
                        <option value="36" {{ old('jangka_pinjaman') == '36' ? 'selected' : '' }}>36 Bulan</option>
                        <option value="48" {{ old('jangka_pinjaman') == '48' ? 'selected' : '' }}>48 Bulan</option>
                        <option value="60" {{ old('jangka_pinjaman') == '60' ? 'selected' : '' }}>60 Bulan</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="pembayaran_perbulan">Pembayaran Per Bulan</label>
                    <input type="number" class="form-control" id="pembayaran_perbulan" name="pembayaran_perbulan" required>
                </div>

                <div class="form-group mb-3">
                    <label for="status_lunas">Status</label>
                    <div>
                        <input type="radio" id="status_lunas_0" name="status_lunas" value="0" checked> <label for="status_lunas_0">Belum Lunas</label>
                        <input type="radio" id="status_lunas_1" name="status_lunas" value="1"> <label for="status_lunas_1">Lunas</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-success">Simpan</button>
                <a class="btn btn-secondary" href="{{ route('piutang.index') }}">Kembali</a>
            </form>
        </div>
    </div>
</div>

<script>
    function updateJabatan() {
        const anggotaSelect = document.getElementById('anggota_id');
        const selectedOption = anggotaSelect.options[anggotaSelect.selectedIndex];
        const jabatan = selectedOption ? selectedOption.dataset.jabatan || '' : '';
        document.getElementById('jabatan_display').value = jabatan;
        document.getElementById('jabatan').value = jabatan;
    }

    document.getElementById('anggota_id').addEventListener('change', updateJabatan);
    document.addEventListener('DOMContentLoaded', updateJabatan);
</script>
@endsection
