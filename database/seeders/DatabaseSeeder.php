<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Anggota;
use App\Models\SimpananAnggota;
use App\Models\Piutang;
use App\Models\PengajuanPinjaman;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // create admin and regular user
        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'role' => 'user',
            'password' => bcrypt('password'),
        ]);

        // create some anggota records with related data
        $anggota = Anggota::create([
            'nama' => 'Budi Santoso',
            'nip' => '123456789',
            'status' => 'aktif',
            'jabatan' => 'PNS',
            'nomor_hp' => '081234567890',
            'alamat' => 'Jl. Merdeka No.1',
        ]);

        // simpanan for anggota
        $simpanan = SimpananAnggota::create([
            'anggota_id' => $anggota->id,
            'simpanan_pokok' => 1000000,
            'simpanan_wajib' => 1200000, // 1 tahun
        ]);

        // piutang example
        Piutang::create([
            'anggota_id' => $anggota->id,
            'jabatan' => $anggota->jabatan,
            'jumlah_pinjam' => 5000000,
            'sisa_piutang' => 3000000,
            'pembayaran_perbulan' => 500000,
            'status_lunas' => false,
        ]);

        // pengajuan pinjaman referencing anggota and simpanan
        PengajuanPinjaman::create([
            'anggota_id' => $anggota->id,
            'simpanan_id' => $simpanan->id,
            'jumlah_pengajuan' => 2000000,
            'status' => 'pending',
        ]);
    }
}
