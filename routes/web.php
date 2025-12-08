<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController; 
use App\Http\Controllers\KunjunganController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

// ==================== LOGIN ROUTES ====================
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.process');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ==================== PUBLIC ROUTES ====================
// Halaman utama sudah ditangani di route '/'

// Development helper: quick login by email (only when APP_DEBUG=true)
if (config('app.debug')) {
    Route::get('/dev/login/{email}', function ($email) {
        $user = User::where('email', $email)->first();
        if (! $user) {
            return response("User not found: $email", 404);
        }
        Auth::login($user);
        return redirect()->route('pages.dashboard');
    });
}

// ==================== PROTECTED ROUTES - NAKES (PERAWAT & DOKTER) ====================
// Use parameterized `role` middleware to allow perawat, dokter, and admin
Route::middleware(['auth', 'role:perawat,dokter,admin,rekam_medis'])->group(function () {
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

    // Edit patient
    Route::get('/master/pasien/{id}/edit', [PatientController::class, 'edit'])
        ->name('patient.edit');

    // Update patient
    Route::put('/master/pasien/{id}', [PatientController::class, 'update'])
        ->name('patient.update');

    // Delete patient
    Route::delete('/master/pasien/{id}', [PatientController::class, 'destroy'])
        ->name('patient.destroy');
    
    // Asuhan CRUD routes dengan implicit model binding
    // Route untuk aksi rujuk (rawat inap) — hanya dipanggil dari halaman detail asuhan
    Route::post('/ashuhans/{asuhan}/rujuk', [App\Http\Controllers\AsuhanController::class, 'rujuk'])
        ->name('ashuhans.rujuk');

    Route::resource('ashuhans', App\Http\Controllers\AsuhanController::class)
        ->parameters(['ashuhans' => 'asuhan']);
    Route::get('/ashuhans/export/csv', [App\Http\Controllers\AsuhanController::class, 'export'])->name('ashuhans.export');
    // Update discharge date for asuhan
    Route::patch('/ashuhans/{asuhan}/discharge', [App\Http\Controllers\AsuhanController::class, 'updateDischarge'])->name('ashuhans.update-discharge');
    Route::patch('/ashuhans/{asuhan}/discharge/clear', [App\Http\Controllers\AsuhanController::class, 'clearDischarge'])->name('ashuhans.clear-discharge');
    // Poliklinik quick-asuhan: submit asuhan from poliklinik pages (ugd, umum, ranap)
    Route::post('/poliklinik/{poli}/assessments', [App\Http\Controllers\AsuhanController::class, 'storeFromPoliklinik'])->name('poliklinik.assessments.store');
});

// ==================== PROTECTED ROUTES - ADMIN ====================
// Use parameterized role middleware for clarity
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // User Management
    Route::resource('users', App\Http\Controllers\UserController::class);
});

// ==================== PROTECTED ROUTES - REKAM MEDIS ====================
// Allow rekam_medis and admin
Route::middleware(['auth', 'role:rekam_medis,admin'])->group(function () {
    
    // Data Medis (Asuhan Medis)
    Route::get('/ashuhans', [App\Http\Controllers\AsuhanController::class, 'index'])
        ->name('ashuhans.index');
    Route::get('/ashuhans/{asuhan}', [App\Http\Controllers\AsuhanController::class, 'show'])
        ->name('ashuhans.show');

    // Reports
    Route::get('/reports/kunjungan/{poli}/csv', [App\Http\Controllers\ReportController::class, 'kunjunganCsv'])
        ->name('reports.kunjungan.csv');
    Route::get('/reports/farmasi/csv', [App\Http\Controllers\ReportController::class, 'farmasiCsv'])
        ->name('reports.farmasi.csv');
});

// ==================== PROTECTED ROUTES - FARMASI ====================
// Allow farmasi and admin
Route::middleware(['auth', 'role:farmasi,admin'])->group(function () {
    // Daftar resep & aksi koleksi
    Route::get('/farmasi/prescriptions', [App\Http\Controllers\FarmasiController::class, 'index'])
        ->name('farmasi.prescriptions');
    Route::post('/farmasi/prescriptions/{asuhan}/collect', [App\Http\Controllers\FarmasiController::class, 'collect'])
        ->name('farmasi.prescriptions.collect');
    // Mark antrian finished (finalize registration/queue)
    Route::post('/farmasi/prescriptions/{asuhan}/finish', [App\Http\Controllers\FarmasiController::class, 'finish'])
        ->name('farmasi.prescriptions.finish');
    // Finish all pending prescriptions (bulk)
    Route::post('/farmasi/prescriptions/finish-all', [App\Http\Controllers\FarmasiController::class, 'finishAll'])
        ->name('farmasi.prescriptions.finish_all');
    // Dispense prescription: input dispensed medicines and total price
    Route::post('/farmasi/prescriptions/{asuhan}/dispense', [App\Http\Controllers\FarmasiController::class, 'dispense'])
        ->name('farmasi.prescriptions.dispense');

    // Medicine management (kelola obat)
    Route::resource('medicines', App\Http\Controllers\MedicineController::class)->except(['show']);
});

// ==================== SHARED PROTECTED ROUTES ====================
Route::middleware('auth')->group(function () {
    // Dashboard accessible by nakes, rekam_medis, and farmasi
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('pages.dashboard');
    
    // Laporan (accessible by all authenticated users)
    Route::get('/laporan/kunjungan', [App\Http\Controllers\LaporanController::class, 'kunjungan'])
        ->name('laporan.kunjungan');
    Route::get('/laporan/kunjungan/export', [App\Http\Controllers\LaporanController::class, 'exportKunjungan'])
        ->name('laporan.kunjungan.export');
    Route::get('/laporan/resep', [App\Http\Controllers\LaporanController::class, 'resep'])
        ->name('laporan.resep');
    Route::get('/laporan/resep/export', [App\Http\Controllers\LaporanController::class, 'exportResep'])
        ->name('laporan.resep.export');
});
 
// ==================== UNPROTECTED PAGE ROUTES ====================


Route::get('/ugd', function () {
    $date = request()->query('date');
    $day = request()->query('day');

    $query = \App\Models\Kunjungan::with('patient')
        ->whereRaw('LOWER(poli_tujuan) = ?', ['ugd']);

    if ($date) {
        $query->whereDate('tanggal_kunjungan', $date);
    }
    if ($day) {
        $query->whereRaw("DAYNAME(tanggal_kunjungan) = ?", [$day]);
    }

    $kunjungans = $query->orderBy('tanggal_kunjungan', 'desc')->get();

    return view('pages.ugd', compact('kunjungans', 'date', 'day'));
});

Route::get('/umum', function () {
    $date = request()->query('date');
    $day = request()->query('day');

    $query = \App\Models\Kunjungan::with('patient')
        ->whereRaw('LOWER(poli_tujuan) = ?', ['umum']);

    if ($date) {
        $query->whereDate('tanggal_kunjungan', $date);
    }
    if ($day) {
        $query->whereRaw("DAYNAME(tanggal_kunjungan) = ?", [$day]);
    }

    $kunjungans = $query->orderBy('tanggal_kunjungan', 'desc')->get();

    return view('pages.umum', compact('kunjungans', 'date', 'day'));
});

Route::get('/ranap', function () {
    $date = request()->query('date');
    $day = request()->query('day');

    $query = \App\Models\Kunjungan::with('patient')
        ->whereRaw('LOWER(poli_tujuan) = ?', ['rawat_inap']);

    if ($date) {
        $query->whereDate('tanggal_kunjungan', $date);
    }
    if ($day) {
        $query->whereRaw("DAYNAME(tanggal_kunjungan) = ?", [$day]);
    }

    // Exclude patients who have an asuhan (rawat_inap) with a discharge_date (already discharged)
    $query->whereNotExists(function ($q) {
        $q->select(\DB::raw(1))
          ->from('ashuhans')
          ->whereRaw('ashuhans.patient_id = kunjungans.patient_id')
          ->whereRaw("LOWER(ashuhans.poli_tujuan) = 'rawat_inap'")
          ->whereNotNull('ashuhans.discharge_date');
    });

    $kunjungans = $query->orderBy('tanggal_kunjungan', 'desc')->get();

    // Load medicines for the ranap form
    $medicines = \App\Models\Medicine::orderBy('name')->get();

    return view('pages.ranap', compact('kunjungans', 'date', 'day', 'medicines'));
});