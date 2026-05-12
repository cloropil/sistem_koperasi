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
                            <option value="{{ $anggota->id }}" data-has-debt="{{ in_array($anggota->id, $anggotaWithDebt ?? []) ? 'true' : 'false' }}" {{ $pengajuan->anggota_id == $anggota->id ? 'selected' : '' }}>{{ $anggota->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Alert untuk tagihan yang belum lunas -->
                <div class="alert alert-warning d-none" id="debtAlert" role="alert">
                    ⚠️ <strong>Perhatian!</strong> Anggota ini masih memiliki tagihan yang belum lunas. Harap lunasi terlebih dahulu sebelum melakukan pengajuan pinjaman baru.
                </div>

                <!-- Info Jabatan dan Maksimal Pinjaman -->
                <div class="alert alert-info d-none" id="infoBox">
                    <div><strong>Jabatan:</strong> <span id="jabatanInfo">-</span></div>
                    <div><strong>Maksimal Pinjaman:</strong> <span id="maxPinjamanInfo">-</span></div>
                </div>
                
                <div class="form-group mb-3">
                    <label for="simpanan_id">Simpanan Anggota (Optional)</label>
                    <select class="form-control" id="simpanan_id" name="simpanan_id">
                        <option value="">Pilih Simpanan...</option>
                        @foreach ($simpanans as $simpanan)
                            <option value="{{ $simpanan->id }}" data-anggota-id="{{ $simpanan->anggota_id }}" data-total="{{ $simpanan->simpanan_pokok + $simpanan->simpanan_wajib }}" {{ $pengajuan->simpanan_id == $simpanan->id ? 'selected' : '' }}>{{ $simpanan->anggota->nama }} - Rp. {{ number_format($simpanan->simpanan_pokok + $simpanan->simpanan_wajib, 0, ',', '.') }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="jumlah_pengajuan">Jumlah Pengajuan</label>
                    <input type="number" class="form-control" id="jumlah_pengajuan" name="jumlah_pengajuan" value="{{ $pengajuan->jumlah_pengajuan }}" required>
                    <small class="text-danger d-none" id="warningMax">Nominal melebihi maksimal pinjaman!</small>
                </div>

                <div class="form-group mb-3">
                    <label for="jangka_pinjaman">Jangka Waktu Pinjaman (Bulan)</label>
                    <select class="form-control" id="jangka_pinjaman" name="jangka_pinjaman" required>
                        <option value="">Pilih Jangka Waktu</option>
                        <option value="3" {{ $pengajuan->jangka_pinjaman == 3 ? 'selected' : '' }}>3 Bulan</option>
                        <option value="6" {{ $pengajuan->jangka_pinjaman == 6 ? 'selected' : '' }}>6 Bulan</option>
                        <option value="12" {{ $pengajuan->jangka_pinjaman == 12 ? 'selected' : '' }}>12 Bulan (1 Tahun)</option>
                        <option value="24" {{ $pengajuan->jangka_pinjaman == 24 ? 'selected' : '' }}>24 Bulan (2 Tahun)</option>
                        <option value="36" {{ $pengajuan->jangka_pinjaman == 36 ? 'selected' : '' }}>36 Bulan (3 Tahun)</option>
                        <option value="48" {{ $pengajuan->jangka_pinjaman == 48 ? 'selected' : '' }}>48 Bulan (4 Tahun)</option>
                        <option value="60" {{ $pengajuan->jangka_pinjaman == 60 ? 'selected' : '' }}>60 Bulan (5 Tahun)</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="pending" {{ $pengajuan->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="process" {{ $pengajuan->status == 'process' ? 'selected' : '' }}>Process</option>
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

<!-- Data Piutang dalam format JSON -->
<script>
    const piutangsData = {!! json_encode($piutangs->map(function($p) { 
        return ['anggota_id' => $p->anggota_id, 'jabatan' => $p->jabatan]; 
    })) !!};

    function updateMaximumPinjaman() {
        const anggotaId = parseInt(document.getElementById('anggota_id').value);
        const selectedOption = document.getElementById('anggota_id').options[document.getElementById('anggota_id').selectedIndex];
        const hasDebt = selectedOption.dataset.hasDebt === 'true';
        const debtAlert = document.getElementById('debtAlert');
        
        // Show/hide debt warning
        if (hasDebt) {
            debtAlert.classList.remove('d-none');
        } else {
            debtAlert.classList.add('d-none');
        }
        
        const piutang = piutangsData.find(p => p.anggota_id === anggotaId);
        const simpananSelect = document.getElementById('simpanan_id');
        const totalSimpanan = parseFloat(simpananSelect.options[simpananSelect.selectedIndex]?.dataset.total || 0);

        if (piutang) {
            document.getElementById('infoBox').classList.remove('d-none');
            document.getElementById('jabatanInfo').textContent = piutang.jabatan;
            
            let maxPinjaman = 0;
            if (piutang.jabatan === 'Militer' || piutang.jabatan === 'PNS') {
                maxPinjaman = 50000000;
            } else if (piutang.jabatan === 'PPPK') {
                maxPinjaman = 30000000;
            } else if (piutang.jabatan === 'Honorer') {
                maxPinjaman = totalSimpanan;
            }
            
            document.getElementById('maxPinjamanInfo').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(maxPinjaman);
            document.getElementById('jumlah_pengajuan').dataset.maxPinjaman = maxPinjaman;
            
        } else {
            document.getElementById('infoBox').classList.add('d-none');
            document.getElementById('jumlah_pengajuan').dataset.maxPinjaman = 0;
        }
    }

    document.getElementById('anggota_id').addEventListener('change', updateMaximumPinjaman);

    document.getElementById('simpanan_id').addEventListener('change', function() {
        updateMaximumPinjaman();
    });

    document.getElementById('jumlah_pengajuan').addEventListener('input', function() {
        const maxPinjaman = parseFloat(this.dataset.maxPinjaman || 0);
        const nominal = parseFloat(this.value || 0);
        const warningMax = document.getElementById('warningMax');
        
        if (maxPinjaman > 0 && nominal > maxPinjaman) {
            warningMax.classList.remove('d-none');
        } else {
            warningMax.classList.add('d-none');
        }
    });

    // Load data on page load
    document.addEventListener('DOMContentLoaded', updateMaximumPinjaman);
</script>
@endsection
