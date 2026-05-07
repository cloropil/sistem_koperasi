@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <h1>Edit Anggota</h1>
            
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('anggota.update', $anggota->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group mb-3">
                    <label for="nama">Nama</label>
                    <input type="text" class="form-control" id="nama" name="nama" value="{{ $anggota->nama }}" required>
                </div>
                
                <div class="form-group mb-3">
                    <label for="nip">NIP</label>
                    <input type="text" class="form-control" id="nip" name="nip" value="{{ $anggota->nip }}" required>
                </div>

                <div class="form-group mb-3">
                    <label>Status</label>
                    <div class="form-check">
                        <input class="form-check-input status-checkbox" type="checkbox" name="status[]" value="aktif" id="status_aktif" {{ $anggota->status == 'aktif' ? 'checked' : '' }}>
                        <label class="form-check-label" for="status_aktif">
                            Aktif
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input status-checkbox" type="checkbox" name="status[]" value="non-aktif" id="status_non_aktif" {{ $anggota->status == 'non-aktif' ? 'checked' : '' }}>
                        <label class="form-check-label" for="status_non_aktif">
                            Non-Aktif
                        </label>
                    </div>
                    <input type="hidden" name="status" id="status_hidden" value="{{ $anggota->status }}" required>
                </div>

                <div class="form-group mb-3">
                    <label>Jabatan</label>
                    <div class="form-check">
                        <input class="form-check-input jabatan-checkbox" type="checkbox" name="jabatan[]" value="Militer" id="jabatan_militer" {{ $anggota->jabatan == 'Militer' ? 'checked' : '' }}>
                        <label class="form-check-label" for="jabatan_militer">
                            Militer
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input jabatan-checkbox" type="checkbox" name="jabatan[]" value="PNS" id="jabatan_pns" {{ $anggota->jabatan == 'PNS' ? 'checked' : '' }}>
                        <label class="form-check-label" for="jabatan_pns">
                            PNS (Pegawai Negeri Sipil)
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input jabatan-checkbox" type="checkbox" name="jabatan[]" value="PPPK" id="jabatan_pppk" {{ $anggota->jabatan == 'PPPK' ? 'checked' : '' }}>
                        <label class="form-check-label" for="jabatan_pppk">
                            PPPK (Pemerintah dengan Perjanjian Kerja)
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input jabatan-checkbox" type="checkbox" name="jabatan[]" value="Honorer" id="jabatan_honorer" {{ $anggota->jabatan == 'Honorer' ? 'checked' : '' }}>
                        <label class="form-check-label" for="jabatan_honorer">
                            Honorer
                        </label>
                    </div>
                    <input type="hidden" name="jabatan" id="jabatan_hidden" value="{{ $anggota->jabatan }}" required>
                </div>

                <div class="form-group mb-3">
                    <label for="nomor_hp">Nomor HP</label>
                    <input type="tel" class="form-control" id="nomor_hp" name="nomor_hp" value="{{ $anggota->nomor_hp }}">
                </div>

                <div class="form-group mb-3">
                    <label for="alamat">Alamat</label>
                    <textarea class="form-control" id="alamat" name="alamat" rows="3">{{ $anggota->alamat }}</textarea>
                </div>

                <button type="submit" class="btn btn-warning">Update</button>
                <a class="btn btn-secondary" href="{{ route('anggota.index') }}">Kembali</a>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle jabatan checkboxes
    const jabatanCheckboxes = document.querySelectorAll('.jabatan-checkbox');
    const jabatanHiddenInput = document.getElementById('jabatan_hidden');
    
    jabatanCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            // Uncheck all other checkboxes
            jabatanCheckboxes.forEach(cb => {
                if (cb !== this) {
                    cb.checked = false;
                }
            });
            
            // Update hidden input
            if (this.checked) {
                jabatanHiddenInput.value = this.value;
            } else {
                jabatanHiddenInput.value = '';
            }
        });
    });

    // Handle status checkboxes
    const statusCheckboxes = document.querySelectorAll('.status-checkbox');
    const statusHiddenInput = document.getElementById('status_hidden');
    
    statusCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            // Uncheck all other checkboxes
            statusCheckboxes.forEach(cb => {
                if (cb !== this) {
                    cb.checked = false;
                }
            });
            
            // Update hidden input
            if (this.checked) {
                statusHiddenInput.value = this.value;
            } else {
                statusHiddenInput.value = '';
            }
        });
    });
});
</script>
@endsection
