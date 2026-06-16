-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 16, 2026 at 06:05 AM
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
-- Database: `lansia_dss`
--

-- --------------------------------------------------------

--
-- Table structure for table `bantuan`
--

CREATE TABLE `bantuan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hasil`
--

CREATE TABLE `hasil` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kriteria`
--

CREATE TABLE `kriteria` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(10) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `atribut` enum('benefit','cost') NOT NULL DEFAULT 'benefit',
  `bobot` decimal(10,6) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kriteria`
--

INSERT INTO `kriteria` (`id`, `kode`, `nama`, `atribut`, `bobot`, `created_at`, `updated_at`) VALUES
(1, 'C1', 'Usia', 'benefit', 0.215857, NULL, '2026-06-07 00:30:59'),
(2, 'C2', 'Kondisi Ekonomi', 'cost', 0.518127, NULL, '2026-06-07 00:30:59'),
(3, 'C3', 'Kondisi Kesehatan', 'cost', 0.131312, NULL, '2026-06-07 00:30:59'),
(4, 'C4', 'Status Tinggal', 'cost', 0.082052, NULL, '2026-06-07 00:30:59'),
(5, 'C5', 'Jumlah Tanggungan', 'benefit', 0.052652, NULL, '2026-06-07 00:30:59');

-- --------------------------------------------------------

--
-- Table structure for table `lansia`
--

CREATE TABLE `lansia` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `umur` int(11) DEFAULT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `kecamatan` varchar(100) DEFAULT NULL,
  `status_tinggal` enum('tinggal_sendiri','bersama_pasangan','bersama_keluarga','tinggal_di_panti') DEFAULT NULL,
  `kondisi_kesehatan` enum('sehat','penyakit_ringan','penyakit_kronis','disabilitas') DEFAULT NULL,
  `kondisi_rumah` enum('rumah_layak','rumah_cukup_layak','rumah_tidak_layak') DEFAULT NULL,
  `kategori_penghasilan` enum('tidak_memiliki_penghasilan','penghasilan_rendah','penghasilan_menengah') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lansia`
--

INSERT INTO `lansia` (`id`, `created_at`, `updated_at`, `nik`, `nama`, `umur`, `jenis_kelamin`, `alamat`, `kecamatan`, `status_tinggal`, `kondisi_kesehatan`, `kondisi_rumah`, `kategori_penghasilan`) VALUES
(1, '2026-06-06 21:21:33', '2026-06-06 21:44:33', '7171010101010001', 'Grace Mambu', 86, 'P', 'Jl. Weris, Taas', 'Paal 4', 'bersama_keluarga', 'penyakit_ringan', 'rumah_cukup_layak', 'tidak_memiliki_penghasilan'),
(2, '2026-06-07 05:46:16', '2026-06-07 05:46:16', '7171010101010002', 'Johan Runtuwene', 68, 'L', 'Jl. Walanda Maramis No. 21, Bumi Beringin', 'Wenang', 'bersama_pasangan', 'sehat', 'rumah_layak', 'penghasilan_menengah'),
(3, '2026-06-07 05:46:16', '2026-06-07 05:46:16', '7171010101010003', 'Maria Paat', 74, 'P', 'Jl. Bethesda No. 8, Titiwungen Selatan', 'Sario', 'tinggal_sendiri', 'penyakit_ringan', 'rumah_cukup_layak', 'penghasilan_rendah'),
(4, '2026-06-07 05:46:16', '2026-06-07 05:46:16', '7171010101010004', 'Hendrik Lumentut', 81, 'L', 'Jl. Ahmad Yani No. 34, Tikala Kumaraka', 'Tikala', 'bersama_keluarga', 'penyakit_kronis', 'rumah_cukup_layak', 'tidak_memiliki_penghasilan'),
(5, '2026-06-07 05:46:16', '2026-06-07 05:46:16', '7171010101010005', 'Yuliana Sumampouw', 79, 'P', 'Jl. Sam Ratulangi No. 55, Ranotana', 'Wanea', 'tinggal_sendiri', 'disabilitas', 'rumah_tidak_layak', 'tidak_memiliki_penghasilan'),
(6, '2026-06-07 05:46:16', '2026-06-07 05:46:16', '7171010101010006', 'Frans Waworuntu', 66, 'L', 'Jl. Piere Tendean No. 77, Sario Tumpaan', 'Sario', 'bersama_pasangan', 'sehat', 'rumah_layak', 'penghasilan_menengah'),
(7, '2026-06-07 05:46:16', '2026-06-07 05:46:16', '7171010101010007', 'Martha Tumbel', 85, 'P', 'Jl. Martadinata No. 12, Sindulang Satu', 'Tuminting', 'tinggal_di_panti', 'penyakit_kronis', 'rumah_cukup_layak', 'tidak_memiliki_penghasilan'),
(8, '2026-06-07 05:46:16', '2026-06-07 05:46:16', '7171010101010008', 'Rudolf Sondakh', 72, 'L', 'Jl. A.A. Maramis No. 45, Paniki Bawah', 'Mapanget', 'bersama_keluarga', 'penyakit_ringan', 'rumah_layak', 'penghasilan_rendah'),
(9, '2026-06-07 05:46:16', '2026-06-07 05:46:16', '7171010101010009', 'Clara Moningka', 77, 'P', 'Jl. Hasanuddin No. 18, Kombos Barat', 'Singkil', 'tinggal_sendiri', 'disabilitas', 'rumah_tidak_layak', 'tidak_memiliki_penghasilan'),
(10, '2026-06-07 05:46:16', '2026-06-07 05:46:16', '7171010101010010', 'Andries Wenas', 69, 'L', 'Jl. RE Martadinata No. 91, Dendengan Dalam', 'Paal Dua', 'bersama_pasangan', 'sehat', 'rumah_layak', 'penghasilan_menengah'),
(11, '2026-06-07 05:46:16', '2026-06-07 05:46:16', '7171010101010011', 'Ester Kawatu', 83, 'P', 'Jl. Yos Sudarso No. 28, Bailang', 'Bunaken', 'tinggal_sendiri', 'penyakit_kronis', 'rumah_tidak_layak', 'tidak_memiliki_penghasilan'),
(12, '2026-06-09 16:01:32', '2026-06-09 16:01:32', '7171000000200', 'Max Verstappen', 79, 'L', 'Peum Wenang Permai, Blok-L, No.3', 'Paal 2', 'tinggal_sendiri', 'penyakit_kronis', 'rumah_layak', 'penghasilan_menengah');

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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_06_03_095957_create_kriterias_table', 1),
(5, '2026_06_03_095957_create_lansias_table', 1),
(6, '2026_06_03_095958_create_bantuans_table', 1),
(7, '2026_06_03_095958_create_hasils_table', 1),
(8, '2026_06_03_095958_create_penilaians_table', 1),
(9, '2026_06_03_100013_create_perbandingan_kriterias_table', 1),
(10, '2026_06_05_111058_create_personal_access_tokens_table', 2),
(11, '2026_06_05_111206_add_role_to_users_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengajuan_bantuan`
--

CREATE TABLE `pengajuan_bantuan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `lansia_id` bigint(20) UNSIGNED NOT NULL,
  `jenis` varchar(255) NOT NULL DEFAULT 'Bantuan Sosial',
  `urgensi` enum('rendah','sedang','tinggi') NOT NULL DEFAULT 'rendah',
  `status` enum('pending','diproses','disalurkan') NOT NULL DEFAULT 'pending',
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengajuan_bantuan`
--

INSERT INTO `pengajuan_bantuan` (`id`, `lansia_id`, `jenis`, `urgensi`, `status`, `catatan`, `created_at`, `updated_at`) VALUES
(1, 6, 'Bantuan Kesehatan', 'rendah', 'diproses', NULL, '2026-06-07 16:18:29', '2026-06-10 03:49:31');

-- --------------------------------------------------------

--
-- Table structure for table `penilaian`
--

CREATE TABLE `penilaian` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `lansia_id` bigint(20) UNSIGNED NOT NULL,
  `kriteria_id` bigint(20) UNSIGNED NOT NULL,
  `nilai` decimal(10,4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `penilaian`
--

INSERT INTO `penilaian` (`id`, `created_at`, `updated_at`, `lansia_id`, `kriteria_id`, `nilai`) VALUES
(1, '2026-06-06 23:06:17', '2026-06-06 23:06:17', 1, 1, 2.0000),
(2, '2026-06-06 23:06:17', '2026-06-06 23:06:17', 1, 2, 2.0000),
(3, '2026-06-06 23:06:17', '2026-06-06 23:06:17', 1, 3, 3.0000),
(4, '2026-06-06 23:06:18', '2026-06-06 23:06:18', 1, 4, 4.0000),
(5, '2026-06-06 23:06:18', '2026-06-06 23:06:18', 1, 5, 2.0000),
(6, '2026-06-06 23:23:19', '2026-06-06 23:23:19', 5, 1, 3.0000),
(7, '2026-06-06 23:23:19', '2026-06-06 23:23:19', 5, 2, 3.0000),
(8, '2026-06-06 23:23:19', '2026-06-06 23:23:19', 5, 3, 3.0000),
(9, '2026-06-06 23:23:19', '2026-06-06 23:23:19', 5, 4, 3.0000),
(10, '2026-06-06 23:23:19', '2026-06-06 23:23:19', 5, 5, 3.0000),
(11, '2026-06-06 23:23:42', '2026-06-06 23:23:42', 11, 1, 5.0000),
(12, '2026-06-06 23:23:42', '2026-06-06 23:23:42', 11, 2, 5.0000),
(13, '2026-06-06 23:23:42', '2026-06-06 23:23:42', 11, 3, 1.0000),
(14, '2026-06-06 23:23:42', '2026-06-06 23:23:42', 11, 4, 2.0000),
(15, '2026-06-06 23:23:42', '2026-06-06 23:23:42', 11, 5, 2.0000),
(16, '2026-06-06 23:23:58', '2026-06-06 23:23:58', 6, 1, 2.0000),
(17, '2026-06-06 23:23:58', '2026-06-06 23:23:58', 6, 2, 3.0000),
(18, '2026-06-06 23:23:58', '2026-06-06 23:23:58', 6, 3, 2.0000),
(19, '2026-06-06 23:23:58', '2026-06-06 23:23:58', 6, 4, 3.0000),
(20, '2026-06-06 23:23:58', '2026-06-06 23:23:58', 6, 5, 3.0000),
(21, '2026-06-06 23:24:08', '2026-06-06 23:24:08', 10, 1, 4.0000),
(22, '2026-06-06 23:24:08', '2026-06-06 23:24:08', 10, 2, 3.0000),
(23, '2026-06-06 23:24:08', '2026-06-06 23:24:08', 10, 3, 3.0000),
(24, '2026-06-06 23:24:08', '2026-06-06 23:24:08', 10, 4, 3.0000),
(25, '2026-06-06 23:24:08', '2026-06-06 23:24:08', 10, 5, 4.0000),
(26, '2026-06-07 08:04:01', '2026-06-07 08:04:01', 2, 1, 3.0000),
(27, '2026-06-07 08:04:01', '2026-06-07 08:04:01', 2, 2, 2.0000),
(28, '2026-06-07 08:04:01', '2026-06-07 08:04:01', 2, 3, 2.0000),
(29, '2026-06-07 08:04:01', '2026-06-07 08:04:01', 2, 4, 3.0000),
(30, '2026-06-07 08:04:01', '2026-06-07 08:04:01', 2, 5, 2.0000),
(31, '2026-06-07 08:04:01', '2026-06-07 08:04:01', 3, 1, 4.0000),
(32, '2026-06-07 08:04:01', '2026-06-07 08:04:01', 3, 2, 4.0000),
(33, '2026-06-07 08:04:01', '2026-06-07 08:04:01', 3, 3, 3.0000),
(34, '2026-06-07 08:04:01', '2026-06-07 08:04:01', 3, 4, 5.0000),
(35, '2026-06-07 08:04:01', '2026-06-07 08:04:01', 3, 5, 3.0000),
(36, '2026-06-07 08:04:01', '2026-06-07 08:04:01', 4, 1, 5.0000),
(37, '2026-06-07 08:04:01', '2026-06-07 08:04:01', 4, 2, 5.0000),
(38, '2026-06-07 08:04:01', '2026-06-07 08:04:01', 4, 3, 5.0000),
(39, '2026-06-07 08:04:01', '2026-06-07 08:04:01', 4, 4, 4.0000),
(40, '2026-06-07 08:04:01', '2026-06-07 08:04:01', 4, 5, 4.0000),
(41, '2026-06-07 08:04:01', '2026-06-07 08:04:01', 7, 1, 5.0000),
(42, '2026-06-07 08:04:01', '2026-06-07 08:04:01', 7, 2, 5.0000),
(43, '2026-06-07 08:04:01', '2026-06-07 08:04:01', 7, 3, 5.0000),
(44, '2026-06-07 08:04:01', '2026-06-07 08:04:01', 7, 4, 5.0000),
(45, '2026-06-07 08:04:01', '2026-06-07 08:04:01', 7, 5, 4.0000),
(46, '2026-06-07 08:04:01', '2026-06-07 08:04:01', 8, 1, 3.0000),
(47, '2026-06-07 08:04:01', '2026-06-07 08:04:01', 8, 2, 3.0000),
(48, '2026-06-07 08:04:01', '2026-06-07 08:04:01', 8, 3, 3.0000),
(49, '2026-06-07 08:04:01', '2026-06-07 08:04:01', 8, 4, 3.0000),
(50, '2026-06-07 08:04:01', '2026-06-07 08:04:01', 8, 5, 2.0000),
(51, '2026-06-07 08:04:01', '2026-06-07 08:04:01', 9, 1, 5.0000),
(52, '2026-06-07 08:04:01', '2026-06-07 08:04:01', 9, 2, 5.0000),
(53, '2026-06-07 08:04:01', '2026-06-07 08:04:01', 9, 3, 5.0000),
(54, '2026-06-07 08:04:01', '2026-06-07 08:04:01', 9, 4, 5.0000),
(55, '2026-06-07 08:04:01', '2026-06-07 08:04:01', 9, 5, 5.0000);

-- --------------------------------------------------------

--
-- Table structure for table `perbandingan_kriteria`
--

CREATE TABLE `perbandingan_kriteria` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kriteria_1_id` bigint(20) UNSIGNED NOT NULL,
  `kriteria_2_id` bigint(20) UNSIGNED NOT NULL,
  `nilai` decimal(10,4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `perbandingan_kriteria`
--

INSERT INTO `perbandingan_kriteria` (`id`, `kriteria_1_id`, `kriteria_2_id`, `nilai`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 0.3333, NULL, '2026-06-05 22:08:14'),
(2, 1, 3, 2.0000, NULL, NULL),
(3, 1, 4, 3.0000, NULL, NULL),
(4, 1, 5, 4.0000, NULL, NULL),
(5, 2, 3, 5.0000, NULL, NULL),
(6, 2, 4, 6.0000, NULL, NULL),
(7, 2, 5, 7.0000, NULL, NULL),
(8, 3, 4, 2.0000, NULL, NULL),
(9, 3, 5, 3.0000, NULL, NULL),
(10, 4, 5, 2.0000, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(2, 'App\\Models\\User', 1, 'auth_token', '2422452e01edce93c705ef034d0e765003c54b4a473d0ad0cfbcfb033fccc77a', '[\"*\"]', NULL, NULL, '2026-06-05 09:23:54', '2026-06-05 09:23:54'),
(3, 'App\\Models\\User', 2, 'auth_token', '555fa1c2c22d2532405382aa7d5d56006b6ee30419cc24a2e9d840691e152ffd', '[\"*\"]', NULL, NULL, '2026-06-05 09:24:17', '2026-06-05 09:24:17'),
(4, 'App\\Models\\User', 2, 'auth_token', '3426f4096e006b64b21cf9b3b3a84ad7499e00e46904805a3efb7ff6d14d6baa', '[\"*\"]', NULL, NULL, '2026-06-05 09:24:27', '2026-06-05 09:24:27'),
(5, 'App\\Models\\User', 2, 'auth_token', 'a87e5f1fe0a194db62c3d0599b034a2400a7238c785db62c5e4124a151d8698f', '[\"*\"]', NULL, NULL, '2026-06-05 09:24:38', '2026-06-05 09:24:38'),
(6, 'App\\Models\\User', 1, 'auth_token', 'f9ee876af72d4f51e70f91104783fcbee41ae118269d40db2793d57441eb75a7', '[\"*\"]', NULL, NULL, '2026-06-05 09:24:49', '2026-06-05 09:24:49'),
(7, 'App\\Models\\User', 1, 'auth_token', '85ecde4f93bdbed075a3a14bb9f94e3acca1c7a43b89e3f9c9c72fe5c6cdce7e', '[\"*\"]', NULL, NULL, '2026-06-05 09:27:27', '2026-06-05 09:27:27'),
(8, 'App\\Models\\User', 2, 'auth_token', '1e860ff6d8c0350b1bd3767c9308a1a5dcd223a903a2a94a22b3009fa435bced', '[\"*\"]', NULL, NULL, '2026-06-05 09:27:40', '2026-06-05 09:27:40'),
(10, 'App\\Models\\User', 1, 'auth_token', '0524b9f61a941e812fe4b6ef39f3771608b054c9d0304fb407b429b1df64b768', '[\"*\"]', '2026-06-05 10:59:02', NULL, '2026-06-05 10:51:28', '2026-06-05 10:59:02'),
(11, 'App\\Models\\User', 1, 'auth_token', '2e4ea72992613ee72186386a1ce5c515a7808e2a2cebd9267d2a44afcfe179a1', '[\"*\"]', '2026-06-05 10:59:32', NULL, '2026-06-05 10:59:22', '2026-06-05 10:59:32'),
(12, 'App\\Models\\User', 1, 'auth_token', '4959beb989288ed42f56eae520f170d19be356a1d3b61cc2aee3d72a324efd99', '[\"*\"]', '2026-06-05 11:19:10', NULL, '2026-06-05 11:01:04', '2026-06-05 11:19:10'),
(13, 'App\\Models\\User', 1, 'auth_token', '83ee983dc349817ddc0759f39f5bd314d6263a5f1055f8b2b691f2b822bc9783', '[\"*\"]', '2026-06-05 11:04:46', NULL, '2026-06-05 11:04:24', '2026-06-05 11:04:46'),
(14, 'App\\Models\\User', 1, 'auth_token', '0c8418ebbdd39414a66017b64b9e7c148e8902af68d23453beaca9626408e3ba', '[\"*\"]', '2026-06-05 11:12:05', NULL, '2026-06-05 11:07:59', '2026-06-05 11:12:05'),
(15, 'App\\Models\\User', 1, 'auth_token', '1652e09f227391ad88ed4251ef2dd4992cf68f69c6930c899ed0ab8f20c4fe6b', '[\"*\"]', '2026-06-05 20:32:00', NULL, '2026-06-05 20:11:02', '2026-06-05 20:32:00'),
(16, 'App\\Models\\User', 1, 'auth_token', '53f3dd4261ad70681c514c0af57cd2cc194021413d62b29e6b619f90334c7c6f', '[\"*\"]', '2026-06-05 23:35:05', NULL, '2026-06-05 21:57:53', '2026-06-05 23:35:05'),
(17, 'App\\Models\\User', 1, 'auth_token', '79634d9c3ea7da13bbf92effa67a95673451058ade8a2e34805f496bb14c0be2', '[\"*\"]', NULL, NULL, '2026-06-06 08:42:33', '2026-06-06 08:42:33'),
(18, 'App\\Models\\User', 2, 'auth_token', 'c128efc90d48292a973690c27ccd1bf3c59cc0e40b630d393e41df23a01dfac6', '[\"*\"]', NULL, NULL, '2026-06-06 08:43:46', '2026-06-06 08:43:46'),
(19, 'App\\Models\\User', 1, 'auth_token', 'e99deb6ad776ea6f877c7526e556e9d840799b5075fbefee67ecd73caa23726b', '[\"*\"]', '2026-06-09 09:46:12', NULL, '2026-06-06 08:44:27', '2026-06-09 09:46:12'),
(20, 'App\\Models\\User', 1, 'auth_token', '5173fd9f4929f45adec8ac4a41760e54fc0df58a26af0f78823a7dfffd5eac7f', '[\"*\"]', NULL, NULL, '2026-06-06 19:52:40', '2026-06-06 19:52:40'),
(22, 'App\\Models\\User', 1, 'auth_token', 'bec87d11feb4c243b86a20d4ef3d2e410d25ae52603c536d00b5d7ec832a0bb6', '[\"*\"]', '2026-06-06 22:57:32', NULL, '2026-06-06 22:55:56', '2026-06-06 22:57:32'),
(23, 'App\\Models\\User', 2, 'auth_token', 'e98ff47ae1c0d540c2d4fc34edd5918fd10b6f1a6aeec48613d3081567f7dba5', '[\"*\"]', '2026-06-09 01:44:10', NULL, '2026-06-07 17:21:41', '2026-06-09 01:44:10'),
(26, 'App\\Models\\User', 2, 'auth_token', '2479b1f4695bb21b4d81648330dfcd25dbd9b4880b9c371fe435e0ee570c9bbc', '[\"*\"]', '2026-06-11 03:52:41', NULL, '2026-06-09 09:17:35', '2026-06-11 03:52:41');

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
('8QD5iMVIg2p5ngP3ltHFmDAZ9pwyEUHI6QNd0n3F', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidHRCMnlVZzh0R0ZTYmRHa3JCTUhGWWJKWUVBSnhmYU1wOWlsb01WVCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9iYW50dWFuIjtzOjU6InJvdXRlIjtOO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1781091422),
('EjiV1PvJX8RKOK0gVbPghreHXlcu82n1m1a3EPxk', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.123.2 Chrome/148.0.7778.97 Electron/42.2.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVHhMS0V5Y2swdGNvYXRoZzNHbnFiRlVlSmZyUDdMdXB6bHB2MUNISiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1781090138),
('pLFPQKa4IH0zkuCbVcEYyYKaJINdAxjZCGlKP72s', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.123.2 Chrome/148.0.7778.97 Electron/42.2.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYzZrb2wxanpYOHBaYlloRm1jcEZidTU1b2xNOGlKUjdESnFjc3dMUSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9wZW5pbGFpYW4iO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1781178755),
('QUaSA848aK1gbByKWotZGpfETiJxqd4Zrk6ZcAKJ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoib2RlZ2Z1b21yaUhaUW14dW5WdFQwU3JHclRBMllKUnQxMmI5WlplMiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NzA6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC8ud2VsbC1rbm93bi9hcHBzcGVjaWZpYy9jb20uY2hyb21lLmRldnRvb2xzLmpzb24iO3M6NToicm91dGUiO047fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1781082329),
('SjF397ndweJcCcDkAs5OBHgCAD7UeMeWTQkHAdAZ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.123.2 Chrome/148.0.7778.97 Electron/42.2.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNmZiTG00Z0Nyaklpak9LNFk5TGg0TWdhbjFucTJNNnppYjdtVVVWSSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1781080434);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` enum('admin','petugas') NOT NULL DEFAULT 'petugas'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role`) VALUES
(1, 'Administrator', 'admin@silansia.com', NULL, '$2y$12$az12XIPMuVxdJopPF35NKu/qlZv/88KaFrc0SSjonlGaKtlthd3km', NULL, '2026-06-05 03:14:07', '2026-06-05 03:14:07', 'admin'),
(2, 'Petugas', 'petugas@silansia.com', NULL, '$2y$12$YPZMZMCDmVmaR.eO5QYwo.5SRAtSxyb7AjZt2DSOJ2O8B4geQEFm6', NULL, '2026-06-05 03:26:38', '2026-06-09 05:03:19', 'petugas');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bantuan`
--
ALTER TABLE `bantuan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `hasil`
--
ALTER TABLE `hasil`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_reserved_at_available_at_index` (`queue`,`reserved_at`,`available_at`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lansia`
--
ALTER TABLE `lansia`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nik` (`nik`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pengajuan_bantuan`
--
ALTER TABLE `pengajuan_bantuan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lansia_id` (`lansia_id`);

--
-- Indexes for table `penilaian`
--
ALTER TABLE `penilaian`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_penilaian` (`lansia_id`,`kriteria_id`),
  ADD KEY `fk_penilaian_kriteria` (`kriteria_id`);

--
-- Indexes for table `perbandingan_kriteria`
--
ALTER TABLE `perbandingan_kriteria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pk_k1` (`kriteria_1_id`),
  ADD KEY `fk_pk_k2` (`kriteria_2_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bantuan`
--
ALTER TABLE `bantuan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hasil`
--
ALTER TABLE `hasil`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kriteria`
--
ALTER TABLE `kriteria`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `lansia`
--
ALTER TABLE `lansia`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `pengajuan_bantuan`
--
ALTER TABLE `pengajuan_bantuan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `penilaian`
--
ALTER TABLE `penilaian`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `perbandingan_kriteria`
--
ALTER TABLE `perbandingan_kriteria`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pengajuan_bantuan`
--
ALTER TABLE `pengajuan_bantuan`
  ADD CONSTRAINT `pengajuan_bantuan_ibfk_1` FOREIGN KEY (`lansia_id`) REFERENCES `lansia` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `penilaian`
--
ALTER TABLE `penilaian`
  ADD CONSTRAINT `fk_penilaian_kriteria` FOREIGN KEY (`kriteria_id`) REFERENCES `kriteria` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_penilaian_lansia` FOREIGN KEY (`lansia_id`) REFERENCES `lansia` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `perbandingan_kriteria`
--
ALTER TABLE `perbandingan_kriteria`
  ADD CONSTRAINT `fk_pk_k1` FOREIGN KEY (`kriteria_1_id`) REFERENCES `kriteria` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_pk_k2` FOREIGN KEY (`kriteria_2_id`) REFERENCES `kriteria` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
