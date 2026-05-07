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

                <div class="alert alert-danger d-none" id="submitAlert" role="alert"></div>

                <!-- Info Jabatan dan Maksimal Pinjaman -->
                <div class="alert alert-info d-none" id="infoBox">
                    <div><strong>Jabatan:</strong> <span id="jabatanInfo">-</span></div>
                    <div><strong>Total Simpanan:</strong> <span id="simpananInfo">-</span></div>
                    <div><strong>Maksimal Pinjaman:</strong> <span id="maxPinjamanInfo">-</span></div>
                </div>
                
                <div class="form-group mb-3">
                    <label for="simpanan_id">Simpanan Anggota</label>
                    <select class="form-control" id="simpanan_id" name="simpanan_id" disabled>
                        <option value="">Pilih Anggota terlebih dahulu</option>
                    </select>
                    <small class="form-text text-muted">* Simpanan akan terisi otomatis berdasarkan anggota yang dipilih</small>
                </div>

                <div class="form-group mb-3">
                    <label for="jumlah_pengajuan">Jumlah Pengajuan</label>
                    <input type="number" class="form-control" id="jumlah_pengajuan" name="jumlah_pengajuan" value="0" step="0.01" required>
                    <small class="text-danger d-none" id="warningMax">Nominal melebihi maksimal pinjaman!</small>
                </div>

                <div class="form-group mb-3">
                    <label for="jangka_pinjaman">Jangka Waktu Pinjaman (Bulan)</label>
                    <select class="form-control" id="jangka_pinjaman" name="jangka_pinjaman" required>
                        <option value="">Pilih Jangka Waktu</option>
                        <option value="3">3 Bulan</option>
                        <option value="6">6 Bulan</option>
                        <option value="12">12 Bulan (1 Tahun)</option>
                        <option value="24">24 Bulan (2 Tahun)</option>
                        <option value="36">36 Bulan (3 Tahun)</option>
                        <option value="48">48 Bulan (4 Tahun)</option>
                        <option value="60">60 Bulan (5 Tahun)</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="pending">Pending</option>
                        <option value="process">Process</option>
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

<!-- Data Piutang dan Simpanan dalam format JSON -->
<script>
    const piutangsData = {!! json_encode($piutangs->map(function($p) { 
        return ['anggota_id' => $p->anggota_id, 'jabatan' => $p->jabatan]; 
    })) !!};

    const simpananByAnggota = {!! json_encode($simpananByAnggota) !!};
    const anggotaData = {!! json_encode($anggotas->map(function($a) { return ['id' => $a->id, 'jabatan' => $a->jabatan]; })) !!};

    document.getElementById('anggota_id').addEventListener('change', function() {
        const anggotaId = parseInt(this.value);
        const selectedOption = this.options[this.selectedIndex];
        const hasDebt = selectedOption.dataset.hasDebt === 'true';
        const debtAlert = document.getElementById('debtAlert');
        const simpananSelect = document.getElementById('simpanan_id');
        
        // Show/hide debt warning
        if (hasDebt) {
            debtAlert.classList.remove('d-none');
        } else {
            debtAlert.classList.add('d-none');
        }
        
        // Auto-select simpanan and populate info
        if (anggotaId && simpananByAnggota[anggotaId]) {
            const simpananData = simpananByAnggota[anggotaId];
            // Populate the select with a single option for this anggota's simpanan
            const opt = document.createElement('option');
            opt.value = simpananData.id;
            opt.textContent = 'Simpanan #' + simpananData.id + ' — Rp ' + new Intl.NumberFormat('id-ID').format(simpananData.total) + ' (Pokok: Rp ' + new Intl.NumberFormat('id-ID').format(simpananData.pokok) + ', Wajib: Rp ' + new Intl.NumberFormat('id-ID').format(simpananData.wajib) + ')';
            simpananSelect.innerHTML = '';
            simpananSelect.appendChild(opt);
            simpananSelect.value = simpananData.id;
            simpananSelect.disabled = false;
        } else {
            simpananSelect.innerHTML = '<option value="">Pilih Anggota terlebih dahulu</option>';
            simpananSelect.value = '';
            simpananSelect.disabled = true;
        }
        
        // Determine jabatan from piutang if present, otherwise from anggota data
        const piutang = piutangsData.find(p => p.anggota_id === anggotaId);
        const anggota = anggotaData.find(a => a.id === anggotaId);
        let jabatan = piutang ? piutang.jabatan : (anggota ? anggota.jabatan : null);
        const simpananData = simpananByAnggota[anggotaId] || { total: 0, pokok: 0, wajib: 0 };

        if (jabatan) {
            document.getElementById('infoBox').classList.remove('d-none');
            document.getElementById('jabatanInfo').textContent = jabatan;
            document.getElementById('simpananInfo').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(simpananData.total);

            let maxPinjaman = 0;
            if (jabatan === 'Militer' || jabatan === 'PNS') {
                maxPinjaman = 50000000;
            } else if (jabatan === 'PPPK') {
                maxPinjaman = 30000000;
            } else if (jabatan === 'Honorer') {
                maxPinjaman = simpananData.total;
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

    // Form submit validation: block if anggota has unpaid debt or nominal > maxPinjaman
    const form = document.querySelector('form');
    const submitAlert = document.getElementById('submitAlert');
    form.addEventListener('submit', function(e) {
        const anggotaSelect = document.getElementById('anggota_id');
        const selectedOption = anggotaSelect.options[anggotaSelect.selectedIndex];
        const hasDebt = selectedOption && selectedOption.dataset.hasDebt === 'true';
        const jumlahInput = document.getElementById('jumlah_pengajuan');
        const maxPinjaman = parseFloat(jumlahInput.dataset.maxPinjaman || 0);
        const nominal = parseFloat(jumlahInput.value || 0);

        // Reset alert
        submitAlert.classList.add('d-none');
        submitAlert.textContent = '';

        if (hasDebt) {
            e.preventDefault();
            submitAlert.textContent = 'Pengajuan diblokir: anggota masih memiliki piutang yang belum lunas.';
            submitAlert.classList.remove('d-none');
            submitAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return false;
        }

        if (maxPinjaman > 0 && nominal > maxPinjaman) {
            e.preventDefault();
            submitAlert.textContent = 'Jumlah pengajuan melebihi batas maksimal untuk jabatan/ketentuan anggota.';
            submitAlert.classList.remove('d-none');
            submitAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return false;
        }

        return true;
    });
</script>
@endsection
