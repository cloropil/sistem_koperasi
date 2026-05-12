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
            <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                <a class="btn btn-primary" href="{{ route('simpanan.create') }}">Tambah Simpanan</a>
                <a class="btn btn-success" href="{{ route('simpanan.export') }}">Export Excel</a>
                <a class="btn btn-warning text-white" href="{{ route('simpanan.print') }}" target="_blank">Print</a>
            </div>
            <!-- Accordion untuk pengelompokan jabatan -->
            <div class="accordion" id="simpananAccordion">
                @foreach ($groupedSimpanans as $jabatanGroup => $simpanansGroup)
                    @if (count($simpanansGroup) > 0)
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $loop->index }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="collapse{{ $loop->index }}">
                                <strong>{{ $jabatanGroup }}</strong>
                                <span class="badge bg-primary ms-2">{{ count($simpanansGroup) }} data</span>
                            </button>
                        </h2>
                        <div id="collapse{{ $loop->index }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}" data-bs-parent="#simpananAccordion">
                            <div class="accordion-body p-0">
                                <table class="table table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Anggota</th>
                                            <th>Jabatan</th>
                                            <th>Simpanan Pokok</th>
                                            <th>Simpanan Wajib</th>
                                            <th>Total Simpanan</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($simpanansGroup as $key => $simpanan)
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>{{ $simpanan->anggota->nama }}</td>
                                            <td>{{ $simpanan->anggota->jabatan }}</td>
                                            <td>Rp. {{ number_format($simpanan->simpanan_pokok, 0, ',', '.') }}</td>
                                            <td>Rp. {{ number_format($simpanan->simpanan_wajib, 0, ',', '.') }}</td>
                                            <td><strong>Rp. {{ number_format($simpanan->getTotalSimpanan(), 0, ',', '.') }}</strong></td>
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
                                    <tfoot>
                                        <tr class="table-light">
                                            <td colspan="3"><strong>Subtotal {{ $jabatanGroup }}</strong></td>
                                            <td><strong>Rp. {{ number_format(collect($simpanansGroup)->sum('simpanan_pokok'), 0, ',', '.') }}</strong></td>
                                            <td><strong>Rp. {{ number_format(collect($simpanansGroup)->sum('simpanan_wajib'), 0, ',', '.') }}</strong></td>
                                            <td colspan="2"><strong>Rp. {{ number_format(collect($simpanansGroup)->sum(function($s) { return $s->getTotalSimpanan(); }), 0, ',', '.') }}</strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>

            <!-- Total keseluruhan -->
            <div class="alert alert-info mt-3">
                <strong>Total Keseluruhan Simpanan:</strong> 
                Rp. {{ number_format($simpanans->sum(function($s) { return $s->getTotalSimpanan(); }), 0, ',', '.') }}
            </div>
        </div>
    </div>
</div>
@endsection
