@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Dokumen Koperasi</h1>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card mb-4">
                <div class="card-header">Unggah Dokumen Baru</div>
                <div class="card-body">
                    <form action="{{ route('dokumen.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label" for="nama_dokumen">Nama Dokumen</label>
                                <input type="text" id="nama_dokumen" name="nama_dokumen" class="form-control" value="{{ old('nama_dokumen') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="file">File Dokumen</label>
                                <input type="file" id="file" name="file" class="form-control" required>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label" for="keterangan">Keterangan (opsional)</label>
                                <textarea id="keterangan" name="keterangan" class="form-control" rows="3">{{ old('keterangan') }}</textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Unggah Dokumen</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">Daftar Dokumen</div>
                <div class="card-body table-responsive">
                    @if($dokumens->count() > 0)
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nama Dokumen</th>
                                    <th>Keterangan</th>
                                    <th>Diunggah Pada</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dokumens as $dokumen)
                                    <tr>
                                        <td>{{ $dokumen->nama_dokumen }}</td>
                                        <td>{{ $dokumen->keterangan ?? '-' }}</td>
                                        <td>{{ $dokumen->created_at->format('d-m-Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('dokumen.show', $dokumen->id) }}" class="btn btn-sm btn-outline-secondary" target="_blank">Lihat</a>
                                            <form action="{{ route('dokumen.destroy', $dokumen->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Hapus dokumen ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted">Belum ada dokumen yang diunggah.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
