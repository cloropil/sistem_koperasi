@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Data Simpanan Anggota</h1>
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    {{ $message }}
                </div>
            @endif
            <a class="btn btn-primary mb-3" href="{{ route('simpanan.create') }}">Tambah Simpanan</a>
            
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Anggota</th>
                        <th>Simpanan Pokok</th>
                        <th>Simpanan Wajib</th>
                        <th>Total Simpanan</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($simpanans as $key => $simpanan)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $simpanan->anggota->nama }}</td>
                        <td>Rp. {{ number_format($simpanan->simpanan_pokok, 0, ',', '.') }}</td>
                        <td>Rp. {{ number_format($simpanan->simpanan_wajib, 0, ',', '.') }}</td>
                        <td>Rp. {{ number_format($simpanan->simpanan_pokok + $simpanan->simpanan_wajib, 0, ',', '.') }}</td>
                        <td>
                            <a class="btn btn-info btn-sm" href="{{ route('simpanan.show', $simpanan->id) }}">Show</a>
                            <a class="btn btn-warning btn-sm" href="{{ route('simpanan.edit', $simpanan->id) }}">Edit</a>
                            <form method="POST" action="{{ route('simpanan.destroy', $simpanan->id) }}" style="display:inline;">
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
