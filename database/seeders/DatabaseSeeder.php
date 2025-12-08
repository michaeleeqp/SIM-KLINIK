<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Patient;
use App\Models\Kunjungan;
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
        // Create demo users for each role (idempotent)
        $admin = User::firstOrCreate(
            ['email' => 'admin@klinik.com'],
            ['name' => 'Admin Klinik', 'password' => bcrypt('admin123'), 'role' => 'admin']
        );

        $perawat = User::firstOrCreate(
            ['email' => 'perawat@klinik.com'],
            ['name' => 'Perawat Adi', 'password' => bcrypt('perawat123'), 'role' => 'perawat']
        );

        $dokter = User::firstOrCreate(
            ['email' => 'dokter@klinik.com'],
            ['name' => 'Dokter Budi', 'password' => bcrypt('dokter123'), 'role' => 'dokter']
        );

        $rekam = User::firstOrCreate(
            ['email' => 'rekammedis@klinik.com'],
            ['name' => 'Petugas Rekam Medis', 'password' => bcrypt('rekammedis123'), 'role' => 'rekam_medis']
        );

        $farmasi = User::firstOrCreate(
            ['email' => 'farmasi@klinik.com'],
            ['name' => 'Petugas Farmasi', 'password' => bcrypt('farmasi123'), 'role' => 'farmasi']
        );

        // Create sample patients (idempotent by no_rm)
        $p1 = Patient::firstOrCreate(
            ['no_rm' => '000001'],
            [
                'nama_pasien' => 'Budi Santoso',
                'no_ktp' => '1234567890123456',
                'agama' => 'Islam',
                'pendidikan' => 'SMA',
                'status_perkawinan' => 'Belum Kawin',
                'status_keluarga' => 'Anak',
                'tanggal_lahir' => '1990-01-01',
                'jenis_kelamin' => 'Laki-laki',
                'golongan_darah' => 'O',
                'alamat' => 'Jl. Contoh No.1',
                'no_wa' => '081234567890',
                'pekerjaan' => 'Karyawan',
                'provinsi_id' => 1,
                'kabupaten_id' => 1,
                'kecamatan_id' => 1,
                'desa_id' => 1,
            ]
        );

        $p2 = Patient::firstOrCreate(
            ['no_rm' => '000002'],
            [
                'nama_pasien' => 'Siti Aminah',
                'no_ktp' => '2234567890123456',
                'agama' => 'Islam',
                'pendidikan' => 'S1',
                'status_perkawinan' => 'Kawin',
                'status_keluarga' => 'Istri',
                'tanggal_lahir' => '1985-05-10',
                'jenis_kelamin' => 'Perempuan',
                'golongan_darah' => 'A',
                'alamat' => 'Jl. Contoh No.2',
                'no_wa' => '081234000001',
                'pekerjaan' => 'Guru',
                'provinsi_id' => 1,
                'kabupaten_id' => 1,
                'kecamatan_id' => 1,
                'desa_id' => 1,
            ]
        );

        // Create sample kunjungans
        Kunjungan::firstOrCreate([
            'patient_id' => $p1->id,
            'poli_tujuan' => 'ugd',
            'tanggal_kunjungan' => now()->toDateString(),
        ], [
            'rujukan_dari' => 'Pribadi',
            'keterangan_rujukan' => 'Keluhan demam dan pusing',
            'jenis_bayar' => 'Umum',
            'kunjungan' => 'Sakit',
            'jadwal_dokter' => 'Pagi',
        ]);

        Kunjungan::firstOrCreate([
            'patient_id' => $p2->id,
            'poli_tujuan' => 'umum',
            'tanggal_kunjungan' => now()->toDateString(),
        ], [
            'rujukan_dari' => 'Pribadi',
            'keterangan_rujukan' => 'Cek kesehatan rutin',
            'jenis_bayar' => 'Umum',
            'kunjungan' => 'Sehat',
            'jadwal_dokter' => 'Pagi',
        ]);
    }
}
