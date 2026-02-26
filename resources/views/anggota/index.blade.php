@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Data Anggota</h1>
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    {{ $message }}
                </div>
            @endif
            <a class="btn btn-primary mb-3" href="{{ route('anggota.create') }}">Tambah Anggota</a>
            
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>NIP</th>
                        <th>Status</th>
                        <th>Jabatan</th>
                        <th>Nomor HP</th>
                        <th>Alamat</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($anggotas as $key => $anggota)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $anggota->nama }}</td>
                        <td>{{ $anggota->nip }}</td>
                        <td>{{ $anggota->status }}</td>
                        <td>{{ $anggota->jabatan }}</td>
                        <td>{{ $anggota->nomor_hp }}</td>
                        <td>{{ $anggota->alamat }}</td>
                        <td>
                            <a class="btn btn-info btn-sm" href="{{ route('anggota.show', $anggota->id) }}">Show</a>
                            <a class="btn btn-warning btn-sm" href="{{ route('anggota.edit', $anggota->id) }}">Edit</a>
                            <form method="POST" action="{{ route('anggota.destroy', $anggota->id) }}" style="display:inline;">
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
