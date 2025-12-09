<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController; 
use App\Http\Controllers\KunjunganController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AsuhanController; // Pastikan controller ini ada
use App\Http\Controllers\FarmasiController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Models\User;
use App\Models\Kunjungan; // Tambahkan Model Kunjungan
use App\Models\Medicine;  // Tambahkan Model Medicine
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Tambahkan DB Facade

// ==================== LOGIN ROUTES ====================
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.process');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Development helper
if (config('app.debug')) {
    Route::get('/dev/login/{email}', function ($email) {
        $user = User::where('email', $email)->first();
        if (!$user) return response("User not found: $email", 404);
        Auth::login($user);
        return redirect()->route('pages.dashboard');
    });
}

// ==================== 1. SHARED: DASHBOARD ====================
Route::middleware('auth')->get('/dashboard', [DashboardController::class, 'index'])->name('pages.dashboard');

// ==================== 2. GROUP: DATA MEDIS / ASUHAN (Nakes + RM) ====================
// Berisi: Logika halaman poli (UGD, Umum, Ranap) dan CRUD Asuhan
Route::middleware(['auth', 'role:perawat,dokter,rekam_medis,admin'])->group(function () {
    
    // --- HALAMAN POLIKLINIK (Logic dipindah ke sini agar Secure) ---
    
    // 1. UGD
    Route::get('/ugd', function () {
        $date = request()->query('date');
        $day = request()->query('day');

        $query = Kunjungan::with('patient')
            ->whereRaw('LOWER(poli_tujuan) = ?', ['ugd']);

        if ($date) {
            $query->whereDate('tanggal_kunjungan', $date);
        }
        if ($day) {
            $query->whereRaw("DAYNAME(tanggal_kunjungan) = ?", [$day]);
        }

        $kunjungans = $query->orderBy('tanggal_kunjungan', 'desc')->get();
        return view('pages.ugd', compact('kunjungans', 'date', 'day'));
    })->name('poli.ugd');

    // 2. KLINIK UMUM
    Route::get('/umum', function () {
        $date = request()->query('date');
        $day = request()->query('day');

        $query = Kunjungan::with('patient')
            ->whereRaw('LOWER(poli_tujuan) = ?', ['umum']);

        if ($date) {
            $query->whereDate('tanggal_kunjungan', $date);
        }
        if ($day) {
            $query->whereRaw("DAYNAME(tanggal_kunjungan) = ?", [$day]);
        }

        $kunjungans = $query->orderBy('tanggal_kunjungan', 'desc')->get();
        return view('pages.umum', compact('kunjungans', 'date', 'day'));
    })->name('poli.umum');

    // 3. RAWAT INAP
    Route::get('/ranap', function () {
        $date = request()->query('date');
        $day = request()->query('day');

        $query = Kunjungan::with('patient')
            ->whereRaw('LOWER(poli_tujuan) = ?', ['rawat_inap']);

        if ($date) {
            $query->whereDate('tanggal_kunjungan', $date);
        }
        if ($day) {
            $query->whereRaw("DAYNAME(tanggal_kunjungan) = ?", [$day]);
        }

        // Exclude patients who have an asuhan (rawat_inap) with a discharge_date
        $query->whereNotExists(function ($q) {
            $q->select(DB::raw(1))
              ->from('ashuhans')
              ->whereRaw('ashuhans.patient_id = kunjungans.patient_id')
              ->whereRaw("LOWER(ashuhans.poli_tujuan) = 'rawat_inap'")
              ->whereNotNull('ashuhans.discharge_date');
        });

        $kunjungans = $query->orderBy('tanggal_kunjungan', 'desc')->get();
        
        // Load medicines for the ranap form
        $medicines = Medicine::orderBy('name')->get();

        return view('pages.ranap', compact('kunjungans', 'date', 'day', 'medicines'));
    })->name('poli.ranap');


    // --- CRUD ASUHAN UTAMA ---
    Route::resource('ashuhans', AsuhanController::class)
        ->parameters(['ashuhans' => 'asuhan']);

    // Export Data Medis
    Route::get('/ashuhans/export/csv', [AsuhanController::class, 'export'])->name('ashuhans.export');
});

// ==================== 3. GROUP: AKSI MEDIS KHUSUS (Nakes Only) ====================
// Aksi berat (Rujuk, Discharge, Input Medis) hanya untuk Dokter/Perawat
Route::middleware(['auth', 'role:perawat,dokter,admin'])->group(function () {
    Route::post('/ashuhans/{asuhan}/rujuk', [AsuhanController::class, 'rujuk'])->name('ashuhans.rujuk');
    Route::patch('/ashuhans/{asuhan}/discharge', [AsuhanController::class, 'updateDischarge'])->name('ashuhans.update-discharge');
    Route::patch('/ashuhans/{asuhan}/discharge/clear', [AsuhanController::class, 'clearDischarge'])->name('ashuhans.clear-discharge');
    
    // Input Quick Assessment dari halaman poli
    Route::post('/poliklinik/{poli}/assessments', [AsuhanController::class, 'storeFromPoliklinik'])
        ->name('poliklinik.assessments.store');
});

// ==================== 4. GROUP: PENDAFTARAN & MASTER PASIEN (RM & Admin) ====================
Route::middleware(['auth', 'role:rekam_medis,admin'])->group(function () {
    // Pendaftaran
    Route::get('/pendaftaran/baru', [PatientController::class, 'create'])->name('pendaftaran.baru');
    Route::post('/pendaftaran/baru', [PatientController::class, 'store'])->name('patient.store');
    Route::get('/pendaftaran/lama', [KunjunganController::class, 'form'])->name('pendaftaran.lama');
    Route::get('/pendaftaran/lama/pasien/{id}', [KunjunganController::class, 'create'])->name('kunjungan.create');
    Route::post('/kunjungan/store', [KunjunganController::class, 'store'])->name('kunjungan.store');
    
    // List & Manage Kunjungan
    Route::get('/list/pendaftaran', [KunjunganController::class, 'index'])->name('list.pendaftaran');
    Route::get('/pendaftaran/{id}/edit', [KunjunganController::class, 'edit'])->name('kunjungan.edit');
    Route::put('/pendaftaran/{id}', [KunjunganController::class, 'update'])->name('kunjungan.update');
    Route::delete('/pendaftaran/{id}', [KunjunganController::class, 'destroy'])->name('kunjungan.destroy');

    // Master Pasien
    Route::get('/master/pasien', [PatientController::class, 'index'])->name('master.pasien');
    Route::get('/pasien/cari', [PatientController::class, 'search'])->name('pasien.search');
    Route::get('/master/pasien/{id}/edit', [PatientController::class, 'edit'])->name('patient.edit');
    Route::put('/master/pasien/{id}', [PatientController::class, 'update'])->name('patient.update');
    Route::delete('/master/pasien/{id}', [PatientController::class, 'destroy'])->name('patient.destroy');
});

// ==================== 5. GROUP: FARMASI (Farmasi & Admin) ====================
Route::middleware(['auth', 'role:farmasi,admin'])->group(function () {
    Route::get('/farmasi/prescriptions', [FarmasiController::class, 'index'])->name('farmasi.prescriptions');
    Route::post('/farmasi/prescriptions/{asuhan}/collect', [FarmasiController::class, 'collect'])->name('farmasi.prescriptions.collect');
    Route::post('/farmasi/prescriptions/{asuhan}/finish', [FarmasiController::class, 'finish'])->name('farmasi.prescriptions.finish');
    Route::post('/farmasi/prescriptions/finish-all', [FarmasiController::class, 'finishAll'])->name('farmasi.prescriptions.finish_all');
    Route::post('/farmasi/prescriptions/{asuhan}/dispense', [FarmasiController::class, 'dispense'])->name('farmasi.prescriptions.dispense');

    Route::resource('medicines', MedicineController::class)->except(['show']);
});

// ==================== 6. GROUP: LAPORAN (RM & Farmasi & Admin) ====================
Route::middleware(['auth', 'role:rekam_medis,farmasi,admin'])->group(function () {
    Route::get('/laporan/kunjungan', [LaporanController::class, 'kunjungan'])->name('laporan.kunjungan');
    Route::get('/laporan/resep', [LaporanController::class, 'resep'])->name('laporan.resep');
    Route::get('/laporan/kunjungan/export', [LaporanController::class, 'exportKunjungan'])->name('laporan.kunjungan.export');
    Route::get('/laporan/resep/export', [LaporanController::class, 'exportResep'])->name('laporan.resep.export');
    
    // Legacy Routes
    Route::get('/reports/kunjungan/{poli}/csv', [ReportController::class, 'kunjunganCsv'])->name('reports.kunjungan.csv');
    Route::get('/reports/farmasi/csv', [ReportController::class, 'farmasiCsv'])->name('reports.farmasi.csv');
});

// ==================== 7. ADMIN ONLY ====================
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('users', UserController::class);
});