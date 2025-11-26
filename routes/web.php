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

// FORM PASIEN BARU
Route::get('/pendaftaran/baru', [PatientController::class, 'create'])
    ->name('pendaftaran.baru');

// SIMPAN PASIEN BARU
Route::post('/pendaftaran/baru', [PatientController::class, 'store'])
    ->name('patient.store');

// FORM PENDAFTARAN PASIEN LAMA (HALAMAN KOSONG)
Route::get('/pendaftaran/lama', [KunjunganController::class, 'form'])
    ->name('pendaftaran.lama');

// FORM PENDAFTARAN PASIEN LAMA (SETELAH PASIEN DITEMUKAN)
Route::get('/pendaftaran/lama/pasien/{id}', [KunjunganController::class, 'create'])
    ->name('kunjungan.create');

// EDIT KUNJUNGAN
Route::get('/pendaftaran/{id}/edit', [KunjunganController::class, 'edit'])
    ->name('kunjungan.edit');

// UPDATE KUNJUNGAN
Route::put('/pendaftaran/{id}', [KunjunganController::class, 'update'])
    ->name('kunjungan.update');

// DELETE KUNJUNGAN
Route::delete('/pendaftaran/{id}', [KunjunganController::class, 'destroy'])
    ->name('kunjungan.destroy');

// SIMPAN KUNJUNGAN PASIEN LAMA
Route::post('/kunjungan/store', [KunjunganController::class, 'store'])
    ->name('kunjungan.store');
    
// LIST PENDAFTARAN
Route::get('/list/pendaftaran', [KunjunganController::class, 'index'])
    ->name('list.pendaftaran');

// CARI PASIEN (AJAX)
Route::get('/pasien/cari', [PatientController::class, 'search'])
    ->name('pasien.search');

// Master pasien
Route::get('/master/pasien', [PatientController::class, 'index'])
    ->name('master.pasien');

Route::get('/master/pasien/{id}/edit', [PatientController::class, 'edit'])
    ->name('patient.edit');

// UPDATE DATA PASIEN
Route::put('/master/pasien/{id}', [PatientController::class, 'update'])
    ->name('patient.update');

// DELETE DATA PASIEN
Route::delete('/master/pasien/{id}', [PatientController::class, 'destroy'])
    ->name('patient.destroy');

Route::get('/ugd', function () {
    return view('pages.ugd');
});

Route::get('/umum', function () {
    return view('pages.ugd');
});

Route::get('/ranap', function () {
    return view('pages.ranap');
});

