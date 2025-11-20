-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 20, 2025 at 03:11 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `simklinik`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kunjungans`
--

CREATE TABLE `kunjungans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `rujukan_dari` varchar(100) NOT NULL,
  `keterangan_rujukan` varchar(255) NOT NULL,
  `tanggal_kunjungan` date NOT NULL,
  `poli_tujuan` varchar(100) NOT NULL,
  `jadwal_dokter` varchar(100) NOT NULL,
  `kunjungan` varchar(50) NOT NULL,
  `jenis_bayar` varchar(50) NOT NULL,
  `no_asuransi` varchar(50) DEFAULT NULL,
  `pj_nama` varchar(255) DEFAULT NULL,
  `pj_no_ktp` varchar(16) DEFAULT NULL,
  `pj_alamat` text DEFAULT NULL,
  `pj_no_wa` varchar(13) DEFAULT NULL,
  `catatan_kunjungan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kunjungans`
--

INSERT INTO `kunjungans` (`id`, `patient_id`, `rujukan_dari`, `keterangan_rujukan`, `tanggal_kunjungan`, `poli_tujuan`, `jadwal_dokter`, `kunjungan`, `jenis_bayar`, `no_asuransi`, `pj_nama`, `pj_no_ktp`, `pj_alamat`, `pj_no_wa`, `catatan_kunjungan`, `created_at`, `updated_at`) VALUES
(1, 2, 'Sendiri/Keluarga', 'Sakit', '2025-11-19', 'UGD', 'Klinik Umum - dr. Mikel  - 07.00-13.00', 'Sakit', 'Umum', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-19 05:54:21', '2025-11-19 05:54:21'),
(2, 3, 'Sendiri/Keluarga', 'wsdf', '2025-11-20', 'UGD', 'Klinik Umum - dr. Mikel  - 07.00-13.00', 'Sakit', 'Umum', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-19 10:15:30', '2025-11-19 10:15:30'),
(3, 2, 'Sendiri/Keluarga', 'MCU', '2025-11-20', 'Klinik Umum', 'Klinik Umum - dr. Mikel  - 07.00-13.00', 'Sehat', 'Umum', NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-19 10:51:24', '2025-11-19 10:51:24');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2025_11_09_162413_create_cache_table', 1),
(2, '2025_11_09_162642_create_jobs_table', 1),
(3, '2025_11_09_162759_create_sessions_table', 1),
(4, '2025_11_09_164434_create_patients_table', 1),
(5, '2025_11_09_174912_create_kunjungans_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_pasien` varchar(100) NOT NULL,
  `no_rm` varchar(6) NOT NULL,
  `no_ktp` varchar(16) NOT NULL,
  `agama` varchar(255) NOT NULL,
  `pendidikan` varchar(255) NOT NULL,
  `status_perkawinan` varchar(255) NOT NULL,
  `status_keluarga` varchar(255) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `golongan_darah` varchar(255) NOT NULL,
  `alamat` text NOT NULL,
  `no_wa` varchar(13) NOT NULL,
  `pekerjaan` varchar(255) NOT NULL,
  `provinsi_id` bigint(20) UNSIGNED NOT NULL,
  `kabupaten_id` bigint(20) UNSIGNED NOT NULL,
  `kecamatan_id` bigint(20) UNSIGNED NOT NULL,
  `desa_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `nama_pasien`, `no_rm`, `no_ktp`, `agama`, `pendidikan`, `status_perkawinan`, `status_keluarga`, `tanggal_lahir`, `jenis_kelamin`, `golongan_darah`, `alamat`, `no_wa`, `pekerjaan`, `provinsi_id`, `kabupaten_id`, `kecamatan_id`, `desa_id`, `created_at`, `updated_at`) VALUES
(2, 'mikel', '000001', '1234567812345678', 'Kristen', 'Diploma', 'Belum Kawin', 'Anak', '2003-07-06', 'Laki-laki', 'O', 'Cirebon', '081911835195', 'Pelajar / Mahasiswa', 11, 1101, 1101010, 1101010001, '2025-11-19 05:54:21', '2025-11-19 05:54:21'),
(3, 'dqw', '000002', '1111111122222222', 'Islam', 'Belum / Tidak Tamat SD', 'Belum Kawin', 'Tuan', '2002-02-02', 'Laki-laki', 'A', 'awd', '0819415634564', 'Pegawai BUMN', 11, 1101, 1101010, 1101010016, '2025-11-19 10:15:30', '2025-11-19 10:15:30');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('x4abwT06MKqrTV0IjPVM0C11spHBY08n01tDnY1i', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZG5VRGttUlkxa2xUeGpOZEE4aXIxR3NNZkRuMkdDRUVNYjNtallCdiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYXN0ZXIvcGFzaWVuIjtzOjU6InJvdXRlIjtzOjEzOiJtYXN0ZXIucGFzaWVuIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1763601825);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `kunjungans`
--
ALTER TABLE `kunjungans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `patients_no_rm_unique` (`no_rm`),
  ADD UNIQUE KEY `patients_no_ktp_unique` (`no_ktp`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kunjungans`
--
ALTER TABLE `kunjungans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
