<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// admin-only routes
Route::middleware(['auth','role:admin'])->group(function () {
    // place admin routes here, e.g. manage anggota, simpanan, piutang, pengajuan
    Route::get('/admin/dashboard', function(){
        return 'Admin dashboard';
    })->name('admin.dashboard');
});

// user routes - authenticated users (both roles) or only role:user if you want restriction
Route::middleware(['auth','role:user,admin'])->group(function () {
    // these routes will be available to regular users and admins
    Route::get('/profile', function(){
        return 'User profile';
    })->name('profile');
});
