@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Data Pengajuan Pinjaman</h1>
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    {{ $message }}
                </div>
            @endif
            <a class="btn btn-primary mb-3" href="{{ route('pengajuan_pinjaman.create') }}">Tambah Pengajuan</a>
            
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Anggota</th>
                        <th>Jumlah Pengajuan</th>
                        <th>Simpanan Anggota</th>
                        <th>Status</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pengajuans as $key => $pengajuan)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $pengajuan->anggota->nama }}</td>
                        <td>Rp. {{ number_format($pengajuan->jumlah_pengajuan, 0, ',', '.') }}</td>
                        <td>
                            @if($pengajuan->simpanan)
                                Rp. {{ number_format($pengajuan->simpanan->simpanan_pokok + $pengajuan->simpanan->simpanan_wajib, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($pengajuan->status == 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @elseif($pengajuan->status == 'approved')
                                <span class="badge bg-success">Approved</span>
                            @else
                                <span class="badge bg-danger">Rejected</span>
                            @endif
                        </td>
                        <td>{{ $pengajuan->created_at->format('d-m-Y') }}</td>
                        <td>
                            <a class="btn btn-info btn-sm" href="{{ route('pengajuan_pinjaman.show', $pengajuan->id) }}">Show</a>
                            <a class="btn btn-warning btn-sm" href="{{ route('pengajuan_pinjaman.edit', $pengajuan->id) }}">Edit</a>
                            <form method="POST" action="{{ route('pengajuan_pinjaman.destroy', $pengajuan->id) }}" style="display:inline;">
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
