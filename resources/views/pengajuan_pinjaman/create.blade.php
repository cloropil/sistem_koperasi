@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <h1>Tambah Pengajuan Pinjaman</h1>
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('pengajuan_pinjaman.store') }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label for="anggota_id">Anggota</label>
                    <select class="form-control" id="anggota_id" name="anggota_id" required>
                        <option value="">Pilih Anggota</option>
                        @foreach ($anggotas as $anggota)
                            <option value="{{ $anggota->id }}" data-has-debt="{{ in_array($anggota->id, $anggotaWithDebt) ? 'true' : 'false' }}">{{ $anggota->nama }}</option>
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
                            <option value="{{ $simpanan->id }}" data-anggota-id="{{ $simpanan->anggota_id }}" data-total="{{ $simpanan->simpanan_pokok + $simpanan->simpanan_wajib }}">{{ $simpanan->anggota->nama }} - Rp. {{ number_format($simpanan->simpanan_pokok + $simpanan->simpanan_wajib, 0, ',', '.') }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="jumlah_pengajuan">Jumlah Pengajuan</label>
                    <input type="number" class="form-control" id="jumlah_pengajuan" name="jumlah_pengajuan" value="0" step="0.01" required>
                    <small class="text-danger d-none" id="warningMax">Nominal melebihi maksimal pinjaman!</small>
                </div>

                <div class="form-group mb-3">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-success">Simpan</button>
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

    document.getElementById('anggota_id').addEventListener('change', function() {
        const anggotaId = parseInt(this.value);
        const selectedOption = this.options[this.selectedIndex];
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
    });

    document.getElementById('simpanan_id').addEventListener('change', function() {
        // Trigger anggota change to update maximal pinjaman untuk Honorer
        document.getElementById('anggota_id').dispatchEvent(new Event('change'));
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
</script>
@endsection
