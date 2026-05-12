@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between mb-3">
                <div>
                    <h2>Data Simpanan Anggota</h2>
                    <p class="mb-0">Pengelompokan berdasarkan jabatan</p>
                </div>
                <button class="btn btn-primary" onclick="window.print()">Print</button>
            </div>

            @foreach ($groupedSimpanans as $jabatanGroup => $simpanansGroup)
                @if (count($simpanansGroup) > 0)
                    <div class="mb-4">
                        <h5>{{ $jabatanGroup }}</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Anggota</th>
                                    <th>Jabatan</th>
                                    <th>Simpanan Pokok</th>
                                    <th>Simpanan Wajib</th>
                                    <th>Total Simpanan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($simpanansGroup as $key => $simpanan)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $simpanan->anggota->nama }}</td>
                                        <td>{{ $simpanan->anggota->jabatan }}</td>
                                        <td>Rp. {{ number_format($simpanan->simpanan_pokok, 0, ',', '.') }}</td>
                                        <td>Rp. {{ number_format($simpanan->simpanan_wajib, 0, ',', '.') }}</td>
                                        <td><strong>Rp. {{ number_format($simpanan->getTotalSimpanan(), 0, ',', '.') }}</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-light">
                                    <td colspan="3"><strong>Subtotal {{ $jabatanGroup }}</strong></td>
                                    <td><strong>Rp. {{ number_format(collect($simpanansGroup)->sum('simpanan_pokok'), 0, ',', '.') }}</strong></td>
                                    <td><strong>Rp. {{ number_format(collect($simpanansGroup)->sum('simpanan_wajib'), 0, ',', '.') }}</strong></td>
                                    <td><strong>Rp. {{ number_format(collect($simpanansGroup)->sum(function ($s) { return $s->getTotalSimpanan(); }), 0, ',', '.') }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endif
            @endforeach

            <div class="alert alert-info">
                <strong>Total Keseluruhan Simpanan:</strong>
                Rp. {{ number_format($simpanans->sum(function ($s) { return $s->getTotalSimpanan(); }), 0, ',', '.') }}
            </div>
        </div>
    </div>
</div>
@endsection
