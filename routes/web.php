<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\SimpananController;
use App\Http\Controllers\PiutangController;
use App\Http\Controllers\PembayaranPiutangController;
use App\Http\Controllers\PengajuanPinjamanController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// authenticated routes - accessible to all logged in users
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// admin-only routes (authorization handled in controllers)
Route::middleware(['auth'])->group(function () {
    // Anggota Routes
    Route::get('/anggota', [AnggotaController::class, 'index'])->name('anggota.index');
    Route::get('/anggota/create', [AnggotaController::class, 'create'])->name('anggota.create');
    Route::post('/anggota', [AnggotaController::class, 'store'])->name('anggota.store');
    Route::get('/anggota/{id}', [AnggotaController::class, 'show'])->name('anggota.show');
    Route::get('/anggota/{id}/edit', [AnggotaController::class, 'edit'])->name('anggota.edit');
    Route::put('/anggota/{id}', [AnggotaController::class, 'update'])->name('anggota.update');
    Route::delete('/anggota/{id}', [AnggotaController::class, 'destroy'])->name('anggota.destroy');
    
    // Simpanan Routes
    Route::get('/simpanan', [SimpananController::class, 'index'])->name('simpanan.index');
    Route::get('/simpanan/create', [SimpananController::class, 'create'])->name('simpanan.create');
    Route::post('/simpanan', [SimpananController::class, 'store'])->name('simpanan.store');
    Route::get('/simpanan/{id}', [SimpananController::class, 'show'])->name('simpanan.show');
    Route::get('/simpanan/{id}/edit', [SimpananController::class, 'edit'])->name('simpanan.edit');
    Route::put('/simpanan/{id}', [SimpananController::class, 'update'])->name('simpanan.update');
    Route::delete('/simpanan/{id}', [SimpananController::class, 'destroy'])->name('simpanan.destroy');
    
    // Piutang Routes
    Route::get('/piutang', [PiutangController::class, 'index'])->name('piutang.index');
    Route::get('/piutang/create', [PiutangController::class, 'create'])->name('piutang.create');
    Route::post('/piutang', [PiutangController::class, 'store'])->name('piutang.store');
    Route::get('/piutang/{id}', [PiutangController::class, 'show'])->name('piutang.show');
    Route::get('/piutang/{id}/edit', [PiutangController::class, 'edit'])->name('piutang.edit');
    Route::put('/piutang/{id}', [PiutangController::class, 'update'])->name('piutang.update');
    Route::delete('/piutang/{id}', [PiutangController::class, 'destroy'])->name('piutang.destroy');

    // Pembayaran Piutang (nested under piutang)
    Route::put('/piutang/{piutang}/pembayaran/{pembayaran}', [PembayaranPiutangController::class, 'update'])->name('pembayaran_piutang.update');
    
    // Pengajuan Pinjaman Routes
    Route::get('/pengajuan_pinjaman', [PengajuanPinjamanController::class, 'index'])->name('pengajuan_pinjaman.index');
    Route::get('/pengajuan_pinjaman/create', [PengajuanPinjamanController::class, 'create'])->name('pengajuan_pinjaman.create');
    Route::post('/pengajuan_pinjaman', [PengajuanPinjamanController::class, 'store'])->name('pengajuan_pinjaman.store');
    Route::get('/pengajuan_pinjaman/{id}', [PengajuanPinjamanController::class, 'show'])->name('pengajuan_pinjaman.show');
    Route::get('/pengajuan_pinjaman/{id}/edit', [PengajuanPinjamanController::class, 'edit'])->name('pengajuan_pinjaman.edit');
    Route::put('/pengajuan_pinjaman/{id}', [PengajuanPinjamanController::class, 'update'])->name('pengajuan_pinjaman.update');
    Route::delete('/pengajuan_pinjaman/{id}', [PengajuanPinjamanController::class, 'destroy'])->name('pengajuan_pinjaman.destroy');
});

// user routes - authenticated users (both roles) or only role:user if you want restriction
Route::middleware(['auth'])->group(function () {
    // these routes will be available to regular users and admins
    Route::get('/profile', function(){
        return 'User profile';
    })->name('profile');
});
