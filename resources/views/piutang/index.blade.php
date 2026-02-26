@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Data Piutang Anggota</h1>
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    {{ $message }}
                </div>
            @endif
            <a class="btn btn-primary mb-3" href="{{ route('piutang.create') }}">Tambah Piutang</a>
            
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Anggota</th>
                        <th>Jabatan</th>
                        <th>Jumlah Pinjam</th>
                        <th>Sisa Piutang</th>
                        <th>Jangka Waktu (Bulan)</th>
                        <th>Pembayaran/Bulan</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($piutangs as $key => $piutang)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $piutang->anggota->nama }}</td>
                        <td>{{ $piutang->jabatan }}</td>
                        <td>Rp. {{ number_format($piutang->jumlah_pinjam, 0, ',', '.') }}</td>
                        <td>Rp. {{ number_format($piutang->sisa_piutang, 0, ',', '.') }}</td>
                        <td>{{ $piutang->jangka_pinjaman ? $piutang->jangka_pinjaman . ' bulan' : '-' }}</td>
                        <td>Rp. {{ number_format($piutang->pembayaran_perbulan, 0, ',', '.') }}</td>
                        <td>
                            @if($piutang->status_lunas)
                                <span class="badge bg-success">Lunas</span>
                            @else
                                <span class="badge bg-danger">Belum Lunas</span>
                            @endif
                        </td>
                        <td>
                            <a class="btn btn-info btn-sm" href="{{ route('piutang.show', $piutang->id) }}">Show</a>
                            <a class="btn btn-warning btn-sm" href="{{ route('piutang.edit', $piutang->id) }}">Edit</a>
                            <form method="POST" action="{{ route('piutang.destroy', $piutang->id) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin hapus?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
