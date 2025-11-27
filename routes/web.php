<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController; 
use App\Http\Controllers\KunjunganController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// FORM PASIEN BARU
Route::get('/pendaftaran/baru', [PatientController::class, 'create'])->name('pendaftaran.baru');
Route::post('/pendaftaran/baru', [PatientController::class, 'store'])->name('patient.store');

// FORM PASIEN LAMA
Route::get('/pendaftaran/lama', [KunjunganController::class, 'form'])->name('pendaftaran.lama');
Route::get('/pendaftaran/lama/pasien/{id}', [KunjunganController::class, 'create'])->name('kunjungan.create');

// CRUD KUNJUNGAN
Route::post('/kunjungan/store', [KunjunganController::class, 'store'])->name('kunjungan.store');
Route::get('/pendaftaran/{id}/edit', [KunjunganController::class, 'edit'])->name('kunjungan.edit');
Route::put('/pendaftaran/{id}', [KunjunganController::class, 'update'])->name('kunjungan.update');
Route::delete('/pendaftaran/{id}', [KunjunganController::class, 'destroy'])->name('kunjungan.destroy');
Route::get('/list/pendaftaran', [KunjunganController::class, 'index'])->name('list.pendaftaran');

// CARI PASIEN AJAX
Route::get('/pasien/cari', [PatientController::class, 'search'])->name('pasien.search');

// MASTER PASIEN
Route::get('/master/pasien', [PatientController::class, 'index'])->name('master.pasien');
Route::get('/master/pasien/{id}/edit', [PatientController::class, 'edit'])->name('patient.edit');
Route::put('/master/pasien/{id}', [PatientController::class, 'update'])->name('patient.update');
Route::delete('/master/pasien/{id}', [PatientController::class, 'destroy'])->name('patient.destroy');

// UGD
Route::get('/ugd', [KunjunganController::class, 'ugd'])->name('ugd.index');
Route::get('/ugd/get/{id}', [KunjunganController::class, 'getUGDData']);
Route::post('/ugd/store', [KunjunganController::class, 'storeUGD'])
    ->name('ugd.store');


// Halaman lain
Route::get('/umum', fn() => view('pages.umum'));
Route::get('/ranap', fn() => view('pages.ranap'));
