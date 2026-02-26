<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\SimpananController;
use App\Http\Controllers\PiutangController;
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

// admin-only routes
Route::middleware(['auth','role:admin'])->group(function () {
    Route::resource('anggota', AnggotaController::class);
    Route::resource('simpanan', SimpananController::class);
    Route::resource('piutang', PiutangController::class);
    Route::resource('pengajuan_pinjaman', PengajuanPinjamanController::class);
});

// user routes - authenticated users (both roles) or only role:user if you want restriction
Route::middleware(['auth','role:user,admin'])->group(function () {
    // these routes will be available to regular users and admins
    Route::get('/profile', function(){
        return 'User profile';
    })->name('profile');
});
