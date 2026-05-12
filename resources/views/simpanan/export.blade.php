<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Data Simpanan Anggota</title>
</head>
<body>
    <h2>Data Simpanan Anggota</h2>
    <p>Pengelompokan berdasarkan jabatan</p>

    @foreach ($groupedSimpanans as $jabatanGroup => $simpanansGroup)
        @if (count($simpanansGroup) > 0)
            <h4>{{ $jabatanGroup }}</h4>
            <table border="1" cellpadding="5" cellspacing="0">
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
                            <td>{{ $simpanan->simpanan_pokok }}</td>
                            <td>{{ $simpanan->simpanan_wajib }}</td>
                            <td>{{ $simpanan->getTotalSimpanan() }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3"><strong>Subtotal {{ $jabatanGroup }}</strong></td>
                        <td><strong>{{ collect($simpanansGroup)->sum('simpanan_pokok') }}</strong></td>
                        <td><strong>{{ collect($simpanansGroup)->sum('simpanan_wajib') }}</strong></td>
                        <td><strong>{{ collect($simpanansGroup)->sum(function ($s) { return $s->getTotalSimpanan(); }) }}</strong></td>
                    </tr>
                </tfoot>
            </table>
            <br>
        @endif
    @endforeach

    <p><strong>Total Keseluruhan Simpanan:</strong> {{ $simpanans->sum(function ($s) { return $s->getTotalSimpanan(); }) }}</p>
</body>
</html>
