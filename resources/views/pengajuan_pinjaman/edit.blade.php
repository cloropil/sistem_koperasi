@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <h1>Edit Pengajuan Pinjaman</h1>
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('pengajuan_pinjaman.update', $pengajuan->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group mb-3">
                    <label for="anggota_id">Anggota</label>
                    <select class="form-control" id="anggota_id" name="anggota_id" required>
                        @foreach ($anggotas as $anggota)
                            <option value="{{ $anggota->id }}" {{ $pengajuan->anggota_id == $anggota->id ? 'selected' : '' }}>{{ $anggota->nama }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group mb-3">
                    <label for="simpanan_id">Simpanan Anggota (Optional)</label>
                    <select class="form-control" id="simpanan_id" name="simpanan_id">
                        <option value="">Pilih Simpanan...</option>
                        @foreach ($simpanans as $simpanan)
                            <option value="{{ $simpanan->id }}" {{ $pengajuan->simpanan_id == $simpanan->id ? 'selected' : '' }}>{{ $simpanan->anggota->nama }} - Rp. {{ number_format($simpanan->simpanan_pokok + $simpanan->simpanan_wajib, 0, ',', '.') }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="jumlah_pengajuan">Jumlah Pengajuan</label>
                    <input type="number" class="form-control" id="jumlah_pengajuan" name="jumlah_pengajuan" value="{{ $pengajuan->jumlah_pengajuan }}" step="0.01" required>
                </div>

                <div class="form-group mb-3">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="pending" {{ $pengajuan->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ $pengajuan->status == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ $pengajuan->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-warning">Update</button>
                <a class="btn btn-secondary" href="{{ route('pengajuan_pinjaman.index') }}">Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection
