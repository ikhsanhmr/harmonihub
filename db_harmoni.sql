-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 09, 2025 at 09:20 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


-- Database: `db_harmoni`
--

-- --------------------------------------------------------

--
-- Table structure for table `anggota_serikats`
--

CREATE TABLE `anggota_serikats` (
  `id` int NOT NULL,
  `name` varchar(120) DEFAULT NULL,
  `unitId` int DEFAULT NULL,
  `nip` int DEFAULT NULL,
  `membership` varchar(255) DEFAULT NULL,
  `noKta` int DEFAULT NULL,
  `serikatId` int DEFAULT NULL,
  `createdAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `updateAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `anggota_serikats`
--

INSERT INTO `anggota_serikats` (`id`, `name`, `unitId`, `nip`, `membership`, `noKta`, `serikatId`, `createdAt`, `updateAt`) VALUES
(4, 'tes tes', 1, 9809, 'tes tes tes', 1389, 1, '2025-01-04 11:00:15', '2025-01-04 11:00:15');

-- --------------------------------------------------------

--
-- Table structure for table `ba_pembentukan`
--

CREATE TABLE `ba_pembentukan` (
  `id` int NOT NULL,
  `unit_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `tanggal` datetime DEFAULT CURRENT_TIMESTAMP,
  `dokumen` varchar(255) DEFAULT NULL,
  `status` varchar(50) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `no_ba` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ba_pembentukan`
--

INSERT INTO `ba_pembentukan` (`id`, `unit_id`, `name`, `tanggal`, `dokumen`, `status`, `created_at`, `updated_at`, `no_ba`) VALUES
(3, 1, 'ga jadi deh', '2025-01-10 00:00:00', 'template_document.pdf', 'approved', '2024-12-08 10:21:13', '2025-01-02 22:55:18', 'b3243'),
(19, 3, 'ga jadi deh ahasd', '2025-01-20 00:00:00', 'template_document.pdf', 'approved', '2025-01-03 10:09:39', '2025-01-03 10:09:39', 'b3243');

-- --------------------------------------------------------

--
-- Table structure for table `ba_perubahan`
--

CREATE TABLE `ba_perubahan` (
  `id` int NOT NULL,
  `unit_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `tanggal` datetime DEFAULT CURRENT_TIMESTAMP,
  `dokumen` varchar(255) DEFAULT NULL,
  `status` varchar(50) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `no_ba` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ba_perubahan`
--

INSERT INTO `ba_perubahan` (`id`, `unit_id`, `name`, `tanggal`, `dokumen`, `status`, `created_at`, `updated_at`, `no_ba`) VALUES
(12, 1, 'ada deh', '2025-01-14 00:00:00', 'template_document.pdf', 'approved', '2025-01-02 22:45:02', '2025-01-02 22:45:02', 'b3243'),
(27, 3, 'ga jadi deh', '2025-01-17 00:00:00', 'template_document.pdf', 'approved', '2025-01-04 08:50:35', '2025-01-04 08:50:35', 'b3243'),
(28, 3, 'tidak ada aja deh say', '2025-01-22 00:00:00', 'template_document.pdf', 'approved', '2025-01-04 08:50:49', '2025-01-04 08:50:49', 'a435');

-- --------------------------------------------------------

--
-- Table structure for table `bulan`
--

CREATE TABLE `bulan` (
  `id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `bulan`
--

INSERT INTO `bulan` (`id`, `name`) VALUES
(1, 'Januari'),
(2, 'Feburari'),
(3, 'Maret'),
(4, 'April'),
(5, 'Mei'),
(6, 'Juni'),
(7, 'Juli'),
(8, 'Agustus'),
(9, 'September'),
(10, 'Oktober'),
(11, 'November'),
(12, 'Desember');

-- --------------------------------------------------------

--
-- Table structure for table `date_monitor_lks_bipartit`
--

CREATE TABLE `date_monitor_lks_bipartit` (
  `id` int NOT NULL,
  `monitor_id` int DEFAULT NULL,
  `tema_id` int DEFAULT NULL,
  `bulan_id` int DEFAULT NULL,
  `rekomendasi` varchar(255) DEFAULT NULL,
  `tindak_lanjut` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `evaluasi` varchar(255) DEFAULT NULL,
  `follow_up` varchar(255) DEFAULT NULL,
  `realisasi` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `date_monitor_lks_bipartit`
--

INSERT INTO `date_monitor_lks_bipartit` (`id`, `monitor_id`, `tema_id`, `bulan_id`, `rekomendasi`, `tindak_lanjut`, `evaluasi`, `follow_up`, `realisasi`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 1, 'Mengundang Yan HC Reg Sumkal untuk memberikan penjelasan terkait aturan layanan kesehatan terbaru di PLN', 'Akan ditindaklanjuti sesuai rekomendasi', 'dalam proses', 'tidak', '100%', '2024-11-25 23:12:24', '2024-11-25 23:12:24'),
(2, 1, 3, 2, 'tes tes tes', 'tes tes', 'dalam tes', 'tidak', '0%', '2024-11-25 23:12:24', '2024-11-25 23:12:24');

-- --------------------------------------------------------

--
-- Table structure for table `dokumen`
--

CREATE TABLE `dokumen` (
  `id_dokumen` int NOT NULL,
  `nama_dokumen` varchar(255) NOT NULL,
  `file_dokumen` varchar(255) NOT NULL,
  `keterangan` text NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `dokumen`
--

INSERT INTO `dokumen` (`id_dokumen`, `nama_dokumen`, `file_dokumen`, `keterangan`, `created_at`, `updated_at`) VALUES
(17, 'Edit Dokumen pertama', 'template_document.pdf', 'Edit dokumen kedua', '2024-12-26 15:36:21', '2024-12-26 16:43:32'),
(18, 'Bisamilahhh ada', 'template_document.pdf', 'Tambah Dokumen', '2024-12-26 16:19:05', '2024-12-26 16:43:14');

-- --------------------------------------------------------

--
-- Table structure for table `dsp`
--

CREATE TABLE `dsp` (
  `id` int NOT NULL,
  `id_serikat` int NOT NULL,
  `dokumen` varchar(200) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `dsp`
--

INSERT INTO `dsp` (`id`, `id_serikat`, `dokumen`, `created_at`, `updated_at`) VALUES
(5, 1, 'template_document.pdf', '2025-01-04 11:00:35', '2025-01-04 11:00:35');

-- --------------------------------------------------------

--
-- Table structure for table `info_sirus`
--

CREATE TABLE `info_sirus` (
  `id` int NOT NULL,
  `filePath` varchar(255) DEFAULT NULL,
  `type` enum('video','flyer') NOT NULL,
  `sender` int NOT NULL,
  `createdAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `updateAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `info_sirus`
--

INSERT INTO `info_sirus` (`id`, `filePath`, `type`, `sender`, `createdAt`, `updateAt`) VALUES
(1, 'uploads/info-siru/template_image.jpg', 'flyer', 8, '2024-12-26 19:34:01', '2024-12-26 19:34:01'),
(2, 'uploads/info-siru/template_video.mp4', 'video', 8, '2025-01-04 10:18:33', '2025-01-04 10:18:33');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_lks_bipartit`
--

CREATE TABLE `jadwal_lks_bipartit` (
  `id` int NOT NULL,
  `temaId` int DEFAULT NULL,
  `namaAgenda` varchar(255) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `createdAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `updateAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `laporan_lks_bipartit`
--

CREATE TABLE `laporan_lks_bipartit` (
  `id` int NOT NULL,
  `unit_id` int DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `topik_bahasan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `latar_belakang` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `rekomendasi` varchar(255) DEFAULT NULL,
  `tanggal_tindak_lanjut` date DEFAULT NULL,
  `uraian_tindak_lanjut` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `laporan_lks_bipartit`
--

INSERT INTO `laporan_lks_bipartit` (`id`, `unit_id`, `tanggal`, `topik_bahasan`, `latar_belakang`, `rekomendasi`, `tanggal_tindak_lanjut`, `uraian_tindak_lanjut`, `created_at`, `updated_at`) VALUES
(2, 1, '2024-12-01', 'Pelayanan Kesehatan ', ' Adanya pembatasan pemberian obat kepada pegawai atau keluarga pegawai yang ditanggung', 'Mengundang Yan HC Palembang dan APLN secara offline terkait prosedur pelayanan kesehatan di lingkungan PT PLN (Persero) UID S2JB atau melakukan pertemuan dengan  VP Yan HC di Kantor Yan HC Palembang', '2024-12-01', 'Pertemuan direncanakan tanggal 4 Desember 2024', '2024-12-01 15:28:30', '2024-12-01 15:29:29');

-- --------------------------------------------------------

--
-- Table structure for table `monitor_lks_bipartit`
--

CREATE TABLE `monitor_lks_bipartit` (
  `id` int NOT NULL,
  `unit_id` int DEFAULT NULL,
  `ba_id` int DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `monitor_lks_bipartit`
--

INSERT INTO `monitor_lks_bipartit` (`id`, `unit_id`, `ba_id`, `created_at`, `updated_at`) VALUES
(1, 3, 3, '2024-12-01 17:36:45', '2024-12-01 17:36:45');

-- --------------------------------------------------------

--
-- Table structure for table `monitor_serikat`
--

CREATE TABLE `monitor_serikat` (
  `monitor_id` int NOT NULL,
  `serikat_id` int NOT NULL,
  `nilai` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `monitor_serikat`
--

INSERT INTO `monitor_serikat` (`monitor_id`, `serikat_id`, `nilai`) VALUES
(1, 1, 3),
(1, 2, 0),
(1, 3, 0);

-- --------------------------------------------------------

--
-- Table structure for table `penilaian_pdp`
--

CREATE TABLE `penilaian_pdp` (
  `id` int NOT NULL,
  `unit_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `anggota_serikat_id` int DEFAULT NULL,
  `peran` varchar(255) DEFAULT NULL,
  `kpi` enum('ya','tidak') DEFAULT NULL,
  `uraian` enum('ya','tidak') DEFAULT NULL,
  `hasil_verifikasi` enum('ya','tidak') DEFAULT NULL,
  `semester` enum('1','2') DEFAULT '1',
  `nilai` int DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `penilaian_pdp`
--

INSERT INTO `penilaian_pdp` (`id`, `unit_id`, `user_id`, `anggota_serikat_id`, `peran`, `kpi`, `uraian`, `hasil_verifikasi`, `semester`, `nilai`, `tanggal`, `created_at`, `updated_at`) VALUES
(2, 1, 8, 3, 'tes', 'tidak', 'ya', 'tidak', '1', 15, '2024-11-09', '2024-11-30 15:15:10', '2024-11-30 15:15:10'),
(3, 1, 8, 3, 'tes 2', 'tidak', 'tidak', 'tidak', '1', 10, '2024-11-13', '2024-11-30 16:31:42', '2024-12-26 19:34:18'),
(4, 1, 8, 3, 'tes 222', 'ya', 'tidak', 'tidak', '1', 14, '2024-11-28', '2024-11-30 16:35:27', '2024-11-30 16:51:17');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int NOT NULL,
  `role_name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `createdAt` datetime DEFAULT NULL,
  `updatedAt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`, `createdAt`, `updatedAt`) VALUES
(1, 'admin', NULL, NULL),
(2, 'user', NULL, NULL),
(3, 'serikat', NULL, NULL),
(4, 'unit', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `serikat`
--

CREATE TABLE `serikat` (
  `id` int NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `logoPath` varchar(255) DEFAULT NULL,
  `createdAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `updateAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `serikat`
--

INSERT INTO `serikat` (`id`, `name`, `logoPath`, `createdAt`, `updateAt`) VALUES
(1, 'SP PLN', NULL, '2024-11-25 19:26:32', '2024-11-25 19:26:32'),
(2, 'SPPI', NULL, '2024-11-25 22:44:35', '2024-11-25 22:44:35'),
(3, 'SERPEG', NULL, '2024-11-25 22:44:35', '2024-11-25 22:44:35');

-- --------------------------------------------------------

--
-- Table structure for table `tema_lks_bipartit`
--

CREATE TABLE `tema_lks_bipartit` (
  `id` int NOT NULL,
  `namaTema` varchar(255) DEFAULT NULL,
  `createdAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `updateAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tema_lks_bipartit`
--

INSERT INTO `tema_lks_bipartit` (`id`, `namaTema`, `createdAt`, `updateAt`) VALUES
(2, 'tes tema', '2024-11-29 22:59:21', '2024-11-29 22:59:21'),
(3, 'tes tema 2', '2024-11-29 22:59:21', '2024-11-29 22:59:21');

-- --------------------------------------------------------

--
-- Table structure for table `tim_lks_bipartit`
--

CREATE TABLE `tim_lks_bipartit` (
  `id` int NOT NULL,
  `nip_pegawai` int DEFAULT NULL,
  `nama_pegawai` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `peran` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `unitId` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tim_lks_bipartit`
--

INSERT INTO `tim_lks_bipartit` (`id`, `nip_pegawai`, `nama_pegawai`, `peran`, `unitId`) VALUES
(1, 10101010, 'Andre Apriyana', 'Presiden', 1),
(2, 23020229, 'Budiyana', 'Ketua', 3),
(5, 6345347, 'Dimas', 'Ketua DPC', 3);

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` int NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `manager_unit` varchar(100) NOT NULL,
  `createdAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `updateAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `name`, `manager_unit`, `createdAt`, `updateAt`) VALUES
(1, 'Unit Tes 2', 'manager 1', '2024-11-25 19:25:36', '2024-12-07 21:15:50'),
(3, 'Kesatuan', 'manager 2', '2024-12-06 20:14:18', '2024-12-07 21:15:54'),
(4, 'Gabungan', 'manager 3', '2024-12-06 20:14:25', '2024-12-07 21:15:58');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `role_id` int DEFAULT NULL,
  `tim` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `profile_picture` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
-- admin pw: K@rtini23
--

INSERT INTO `users` (`id`, `role_id`, `tim`, `name`, `username`, `password`, `email`, `profile_picture`, `created_at`, `updated_at`) VALUES
(2, 2, 'user', 'User', 'user', '$2b$12$..A2l4Xi9avfuZB2h5kAGOzv.xKDgqVkWlBN3gSfIeWa88ibu1sJG', 'user@gmail.com', '-', '2024-12-01 17:36:45', '2024-12-01 17:36:45'),
(8, 1, 'superAdmin', 'Admin', 'admin', '$2y$10$o9.dFz1heO4eZIsbCMtLCu/2croZJ0kGzJ6wBiV4.05E/22//uzqG', 'admin@gmail.com', 'uploads/template_avatar.jpg', '2024-11-25 23:12:24', '2024-11-25 23:12:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `anggota_serikats`
--
ALTER TABLE `anggota_serikats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `unitId` (`unitId`),
  ADD KEY `serikatId` (`serikatId`);

--
-- Indexes for table `ba_pembentukan`
--
ALTER TABLE `ba_pembentukan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `unit_id` (`unit_id`);

--
-- Indexes for table `ba_perubahan`
--
ALTER TABLE `ba_perubahan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `unit_id` (`unit_id`);

--
-- Indexes for table `bulan`
--
ALTER TABLE `bulan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `date_monitor_lks_bipartit`
--
ALTER TABLE `date_monitor_lks_bipartit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `monitor_id` (`monitor_id`),
  ADD KEY `tema_id` (`tema_id`),
  ADD KEY `bulan_id` (`bulan_id`);

--
-- Indexes for table `dokumen`
--
ALTER TABLE `dokumen`
  ADD PRIMARY KEY (`id_dokumen`);

--
-- Indexes for table `dsp`
--
ALTER TABLE `dsp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_serikat` (`id_serikat`);

--
-- Indexes for table `info_sirus`
--
ALTER TABLE `info_sirus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `info_sirus_infk_users` (`sender`);

--
-- Indexes for table `jadwal_lks_bipartit`
--
ALTER TABLE `jadwal_lks_bipartit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `temaId` (`temaId`);

--
-- Indexes for table `laporan_lks_bipartit`
--
ALTER TABLE `laporan_lks_bipartit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `unitId` (`unit_id`);

--
-- Indexes for table `monitor_lks_bipartit`
--
ALTER TABLE `monitor_lks_bipartit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `unit_id` (`unit_id`),
  ADD KEY `ba_id` (`ba_id`);

--
-- Indexes for table `monitor_serikat`
--
ALTER TABLE `monitor_serikat`
  ADD PRIMARY KEY (`monitor_id`,`serikat_id`),
  ADD KEY `monitor_serikat_ibfk_2` (`serikat_id`);

--
-- Indexes for table `penilaian_pdp`
--
ALTER TABLE `penilaian_pdp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `unitId` (`unit_id`),
  ADD KEY `userId` (`user_id`),
  ADD KEY `anggota_serikat_id` (`anggota_serikat_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `serikat`
--
ALTER TABLE `serikat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tema_lks_bipartit`
--
ALTER TABLE `tema_lks_bipartit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tim_lks_bipartit`
--
ALTER TABLE `tim_lks_bipartit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `unit_lks_bipartit_1` (`unitId`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `roleId` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `anggota_serikats`
--
ALTER TABLE `anggota_serikats`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ba_pembentukan`
--
ALTER TABLE `ba_pembentukan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `ba_perubahan`
--
ALTER TABLE `ba_perubahan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `bulan`
--
ALTER TABLE `bulan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `date_monitor_lks_bipartit`
--
ALTER TABLE `date_monitor_lks_bipartit`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `dokumen`
--
ALTER TABLE `dokumen`
  MODIFY `id_dokumen` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `dsp`
--
ALTER TABLE `dsp`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `info_sirus`
--
ALTER TABLE `info_sirus`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `jadwal_lks_bipartit`
--
ALTER TABLE `jadwal_lks_bipartit`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `laporan_lks_bipartit`
--
ALTER TABLE `laporan_lks_bipartit`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `monitor_lks_bipartit`
--
ALTER TABLE `monitor_lks_bipartit`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `penilaian_pdp`
--
ALTER TABLE `penilaian_pdp`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `serikat`
--
ALTER TABLE `serikat`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tema_lks_bipartit`
--
ALTER TABLE `tema_lks_bipartit`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tim_lks_bipartit`
--
ALTER TABLE `tim_lks_bipartit`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `anggota_serikats`
--
ALTER TABLE `anggota_serikats`
  ADD CONSTRAINT `serikat_ibfk_1` FOREIGN KEY (`unitId`) REFERENCES `units` (`id`),
  ADD CONSTRAINT `serikat_ibfk_2` FOREIGN KEY (`serikatId`) REFERENCES `serikat` (`id`);

--
-- Constraints for table `ba_pembentukan`
--
ALTER TABLE `ba_pembentukan`
  ADD CONSTRAINT `ba_pembentukan_ibfk_1` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`);

--
-- Constraints for table `ba_perubahan`
--
ALTER TABLE `ba_perubahan`
  ADD CONSTRAINT `ba_perubahan_ibfk_1` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `date_monitor_lks_bipartit`
--
ALTER TABLE `date_monitor_lks_bipartit`
  ADD CONSTRAINT `date_monitor_lks_bipartit_ibfk_1` FOREIGN KEY (`monitor_id`) REFERENCES `monitor_lks_bipartit` (`id`),
  ADD CONSTRAINT `date_monitor_lks_bipartit_ibfk_2` FOREIGN KEY (`bulan_id`) REFERENCES `bulan` (`id`),
  ADD CONSTRAINT `date_monitor_lks_bipartit_ibfk_3` FOREIGN KEY (`tema_id`) REFERENCES `tema_lks_bipartit` (`id`);

--
-- Constraints for table `dsp`
--
ALTER TABLE `dsp`
  ADD CONSTRAINT `dsp_ibfk_1` FOREIGN KEY (`id_serikat`) REFERENCES `serikat` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `info_sirus`
--
ALTER TABLE `info_sirus`
  ADD CONSTRAINT `info_sirus_infk_users` FOREIGN KEY (`sender`) REFERENCES `users` (`id`);

--
-- Constraints for table `jadwal_lks_bipartit`
--
ALTER TABLE `jadwal_lks_bipartit`
  ADD CONSTRAINT `jadwal_lks_bipartit_ibfk_1` FOREIGN KEY (`temaId`) REFERENCES `tema_lks_bipartit` (`id`);

--
-- Constraints for table `laporan_lks_bipartit`
--
ALTER TABLE `laporan_lks_bipartit`
  ADD CONSTRAINT `laporan_lks_bipartit_ibfk_1` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`);

--
-- Constraints for table `monitor_lks_bipartit`
--
ALTER TABLE `monitor_lks_bipartit`
  ADD CONSTRAINT `monitor_lks_bipartit_ibfk_1` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`),
  ADD CONSTRAINT `monitor_lks_bipartit_ibfk_2` FOREIGN KEY (`ba_id`) REFERENCES `ba_pembentukan` (`id`);

--
-- Constraints for table `monitor_serikat`
--
ALTER TABLE `monitor_serikat`
  ADD CONSTRAINT `monitor_serikat_ibfk_1` FOREIGN KEY (`monitor_id`) REFERENCES `monitor_lks_bipartit` (`id`),
  ADD CONSTRAINT `monitor_serikat_ibfk_2` FOREIGN KEY (`serikat_id`) REFERENCES `serikat` (`id`);

--
-- Constraints for table `penilaian_pdp`
--
ALTER TABLE `penilaian_pdp`
  ADD CONSTRAINT `penilaian_pdp_ibfk_1` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`),
  ADD CONSTRAINT `penilaian_pdp_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `tim_lks_bipartit`
--
ALTER TABLE `tim_lks_bipartit`
  ADD CONSTRAINT `unit_lks_bipartit_1` FOREIGN KEY (`unitId`) REFERENCES `units` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
