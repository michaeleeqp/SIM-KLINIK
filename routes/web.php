<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController; 
use App\Http\Controllers\KunjunganController;
use App\Http\Controllers\DashboardController;

// Halaman utama
Route::get('/', function () {
    return view('welcome');
});

// Dashboard
Route::get('/dashboard', function () {
    return view('pages.dashboard');
});

// Dashboard Controller
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// FORM PENDAFTARAN PASIEN LAMA (HALAMAN KOSONG)
Route::get('/pendaftaran/lama', [KunjunganController::class, 'form'])
    ->name('pendaftaran.lama');

// FORM PENDAFTARAN PASIEN LAMA (SETELAH PASIEN DITEMUKAN)
Route::get('/pendaftaran/lama/pasien/{id}', [KunjunganController::class, 'create'])
    ->name('kunjungan.create');

// FORM PASIEN BARU
Route::get('/pendaftaran/baru', [PatientController::class, 'create'])
    ->name('pendaftaran.baru');

// SIMPAN PASIEN BARU
Route::post('/pendaftaran/baru', [PatientController::class, 'store'])
    ->name('patient.store');

// LIST PENDAFTARAN
Route::get('/list/pendaftaran', [KunjunganController::class, 'index'])
    ->name('list.pendaftaran');

// CARI PASIEN (AJAX)
Route::get('/pasien/cari', [PatientController::class, 'search'])
    ->name('pasien.search');

// SIMPAN KUNJUNGAN PASIEN LAMA
Route::post('/kunjungan/store', [KunjunganController::class, 'store'])
    ->name('kunjungan.store');

// Master pasien
Route::get('/master/pasien', [PatientController::class, 'index'])
    ->name('master.pasien');