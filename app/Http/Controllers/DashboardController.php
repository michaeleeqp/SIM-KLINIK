<?php
namespace App\Http\Controllers;

use App\Models\Kunjungan;
use App\Models\Patient;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Hitung Kunjungan Hari Ini
        // Menggunakan tanggal_kunjungan
        $kunjunganHariIni = Kunjungan::whereDate('tanggal_kunjungan', Carbon::today())->count();

        // 2. Hitung Pasien Baru Hari Ini
        // Menggunakan created_at dari model Pasien
        $pasienBaruHariIni = Patient::whereDate('created_at', Carbon::today())->count();

        //hitung total pasien
        $totalPasien = Patient::count();
        
        // 3. Kembalikan SATU view dengan SEMUA data yang dibutuhkan
        return view('pages.dashboard', compact('kunjunganHariIni', 'pasienBaruHariIni', 'totalPasien'));

        // CATATAN: Pastikan Anda hanya memiliki SATU return view() di akhir method.
    }
}
