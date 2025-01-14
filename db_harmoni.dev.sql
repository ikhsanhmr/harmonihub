-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versi server:                 8.0.30 - MySQL Community Server - GPL
-- OS Server:                    Win64
-- HeidiSQL Versi:               12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=1 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Membuang struktur basisdata untuk db_harmoni
DROP DATABASE IF EXISTS `db_harmoni`;
CREATE DATABASE IF NOT EXISTS `db_harmoni` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `db_harmoni`;

-- membuang struktur untuk table db_harmoni.roles
DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `role_name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `createdAt` datetime DEFAULT NULL,
  `updatedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Membuang data untuk tabel db_harmoni.roles: ~4 rows (lebih kurang)
DELETE FROM `roles`;
INSERT INTO `roles` (`id`, `role_name`, `createdAt`, `updatedAt`) VALUES
	(1, 'admin', NULL, NULL),
	(2, 'user', NULL, NULL),
	(3, 'serikat', NULL, NULL),
	(4, 'unit', NULL, NULL);

-- membuang struktur untuk table db_harmoni.users
DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `role_id` int DEFAULT NULL,
  `tim` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `profile_picture` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `roleId` (`role_id`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Membuang data untuk tabel db_harmoni.users: ~2 rows (lebih kurang)
-- admin pw: K@rtini23
-- user pw: 123456

DELETE FROM `users`;
INSERT INTO `users` (`id`, `role_id`, `name`, `username`, `password`, `email`, `profile_picture`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Admin', 'admin', '$2y$10$rz6KONMHQxpl6cBg4Zp09e..3wGV/QKOkX5S5PvzXFdn1hikXBrxe', 'admin@gmail.com', 'uploads/template_avatar.jpg', '2024-11-25 23:12:24', '2024-11-25 23:12:24'),
	(2, 2, 'user', 'user', '$2y$10$vxuwcBhkfpRSFYQEa3DA9e/DxcUYwdrZ9O27hFOqA8G37OVzlOxzq', 'user@gmail.com', NULL, '2024-12-01 17:36:45', '2024-12-01 17:36:45');

-- membuang struktur untuk table db_harmoni.units
DROP TABLE IF EXISTS `units`;
CREATE TABLE IF NOT EXISTS `units` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `manager_unit` varchar(100) NOT NULL,
  `createdAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `updateAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Membuang data untuk tabel db_harmoni.units: ~3 rows (lebih kurang)
DELETE FROM `units`;
INSERT INTO `units` (`id`, `name`, `manager_unit`, `createdAt`, `updateAt`) VALUES
	(1, 'Gabungan', 'manager 1', '2024-11-25 19:25:36', '2024-12-07 21:15:50'),
	(2, 'Kesatuan', 'manager 2', '2024-12-06 20:14:18', '2024-12-07 21:15:54'),
	(3, 'Unit Tes', 'manager 3', '2024-12-06 20:14:25', '2024-12-07 21:15:58');

-- membuang struktur untuk table db_harmoni.serikat
DROP TABLE IF EXISTS `serikat`;
CREATE TABLE IF NOT EXISTS `serikat` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `logoPath` varchar(255) DEFAULT NULL,
  `createdAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `updateAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Membuang data untuk tabel db_harmoni.serikat: ~3 rows (lebih kurang)
DELETE FROM `serikat`;
INSERT INTO `serikat` (`id`, `name`, `logoPath`, `createdAt`, `updateAt`) VALUES
	(1, 'SP PLN', 'uploads/template_under-construct.png', '2024-11-25 19:26:32', '2024-11-25 19:26:32'),
	(2, 'SPPI', NULL, '2024-11-25 22:44:35', '2024-11-25 22:44:35'),
	(3, 'SERPEG', NULL, '2024-11-25 22:44:35', '2024-11-25 22:44:35');

-- membuang struktur untuk table db_harmoni.anggota_serikats
DROP TABLE IF EXISTS `anggota_serikats`;
CREATE TABLE IF NOT EXISTS `anggota_serikats` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(120) DEFAULT NULL,
  `unitId` int DEFAULT NULL,
  `nip` int DEFAULT NULL,
  `membership` varchar(255) DEFAULT NULL,
  `noKta` int DEFAULT NULL,
  `serikatId` int DEFAULT NULL,
  `createdAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `updateAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `unitId` (`unitId`),
  KEY `serikatId` (`serikatId`),
  CONSTRAINT `serikat_ibfk_1` FOREIGN KEY (`unitId`) REFERENCES `units` (`id`),
  CONSTRAINT `serikat_ibfk_2` FOREIGN KEY (`serikatId`) REFERENCES `serikat` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Membuang data untuk tabel db_harmoni.anggota_serikats: ~0 rows (lebih kurang)
DELETE FROM `anggota_serikats`;
INSERT INTO `anggota_serikats` (`id`, `name`, `unitId`, `nip`, `membership`, `noKta`, `serikatId`, `createdAt`, `updateAt`) VALUES
	(1, 'anggota serikat 1', 1, 9809, 'member serikat 1', 1389, 1, '2025-01-04 11:00:15', '2025-01-04 11:00:15');

-- membuang struktur untuk table db_harmoni.tema_lks_bipartit
DROP TABLE IF EXISTS `tema_lks_bipartit`;
CREATE TABLE IF NOT EXISTS `tema_lks_bipartit` (
  `id` int NOT NULL AUTO_INCREMENT,
  `namaTema` varchar(255) DEFAULT NULL,
  `createdAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `updateAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Membuang data untuk tabel db_harmoni.tema_lks_bipartit: ~2 rows (lebih kurang)
DELETE FROM `tema_lks_bipartit`;
INSERT INTO `tema_lks_bipartit` (`id`, `namaTema`, `createdAt`, `updateAt`) VALUES
	(1, 'tema 1', '2024-11-29 22:59:21', '2024-11-29 22:59:21'),
	(2, 'tema 2', '2024-11-29 22:59:21', '2024-11-29 22:59:21');

-- membuang struktur untuk table db_harmoni.ba_pembentukan
DROP TABLE IF EXISTS `ba_pembentukan`;
CREATE TABLE IF NOT EXISTS `ba_pembentukan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `unit_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `tanggal` datetime DEFAULT CURRENT_TIMESTAMP,
  `dokumen` varchar(255) DEFAULT NULL,
  `status` varchar(50) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `no_ba` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `unit_id` (`unit_id`),
  CONSTRAINT `ba_pembentukan_ibfk_1` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Membuang data untuk tabel db_harmoni.ba_pembentukan: ~2 rows (lebih kurang)
DELETE FROM `ba_pembentukan`;
INSERT INTO `ba_pembentukan` (`id`, `unit_id`, `name`, `tanggal`, `dokumen`, `status`, `created_at`, `updated_at`, `no_ba`) VALUES
	(1, 1, 'ba bentuk 1', '2025-01-10 00:00:00', 'uploads/template_document.pdf', 'approved', '2024-12-08 10:21:13', '2025-01-02 22:55:18', 'b0001'),
	(2, 2, 'ba bentuk 2', '2025-01-20 00:00:00', NULL, 'approved', '2025-01-03 10:09:39', '2025-01-03 10:09:39', 'b0002');

-- membuang struktur untuk table db_harmoni.ba_perubahan
DROP TABLE IF EXISTS `ba_perubahan`;
CREATE TABLE IF NOT EXISTS `ba_perubahan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `unit_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `tanggal` datetime DEFAULT CURRENT_TIMESTAMP,
  `dokumen` varchar(255) DEFAULT NULL,
  `status` varchar(50) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `no_ba` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `unit_id` (`unit_id`),
  CONSTRAINT `ba_perubahan_ibfk_1` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Membuang data untuk tabel db_harmoni.ba_perubahan: ~3 rows (lebih kurang)
DELETE FROM `ba_perubahan`;
INSERT INTO `ba_perubahan` (`id`, `unit_id`, `name`, `tanggal`, `dokumen`, `status`, `created_at`, `updated_at`, `no_ba`) VALUES
	(1, 1, 'perubahan 1', '2025-01-14 00:00:00', 'uploads/template_document.pdf', 'approved', '2025-01-02 22:45:02', '2025-01-02 22:45:02', 'b3243'),
	(2, 2, 'perubahan 2', '2025-01-17 00:00:00', NULL, 'approved', '2025-01-04 08:50:35', '2025-01-04 08:50:35', 'b3243'),
	(3, 3, 'perubahan 3', '2025-01-22 00:00:00', NULL, 'approved', '2025-01-04 08:50:49', '2025-01-04 08:50:49', 'a435');

-- membuang struktur untuk table db_harmoni.bulan
DROP TABLE IF EXISTS `bulan`;
CREATE TABLE IF NOT EXISTS `bulan` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Membuang data untuk tabel db_harmoni.bulan: ~12 rows (lebih kurang)
DELETE FROM `bulan`;
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

-- membuang struktur untuk table db_harmoni.dokumen
DROP TABLE IF EXISTS `dokumen`;
CREATE TABLE IF NOT EXISTS `dokumen` (
  `id_dokumen` int NOT NULL AUTO_INCREMENT,
  `nama_dokumen` varchar(255) NOT NULL,
  `file_dokumen` varchar(255) NOT NULL,
  `keterangan` text NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id_dokumen`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Membuang data untuk tabel db_harmoni.dokumen: ~2 rows (lebih kurang)
DELETE FROM `dokumen`;
INSERT INTO `dokumen` (`id_dokumen`, `nama_dokumen`, `file_dokumen`, `keterangan`, `created_at`, `updated_at`) VALUES
	(1, 'Dokumen pertama', 'uploads/template_document.pdf', 'Edit dokumen kedua', '2024-12-26 15:36:21', '2024-12-26 16:43:32');

-- membuang struktur untuk table db_harmoni.dsp
DROP TABLE IF EXISTS `dsp`;
CREATE TABLE IF NOT EXISTS `dsp` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_serikat` int NOT NULL,
  `dokumen` varchar(200) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_serikat` (`id_serikat`),
  CONSTRAINT `dsp_ibfk_1` FOREIGN KEY (`id_serikat`) REFERENCES `serikat` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Membuang data untuk tabel db_harmoni.dsp: ~0 rows (lebih kurang)
DELETE FROM `dsp`;
INSERT INTO `dsp` (`id`, `id_serikat`, `dokumen`, `created_at`, `updated_at`) VALUES
	(1, 1, 'uploads/template_document.pdf', '2025-01-04 11:00:35', '2025-01-04 11:00:35'),
	(2, 1, NULL, '2025-01-04 11:00:35', '2025-01-04 11:00:35');

-- membuang struktur untuk table db_harmoni.info_sirus
DROP TABLE IF EXISTS `info_sirus`;
CREATE TABLE IF NOT EXISTS `info_sirus` (
  `id` int NOT NULL AUTO_INCREMENT,
  `filePath` varchar(255) DEFAULT NULL,
  `type` enum('video','flyer') NOT NULL,
  `sender` int NOT NULL,
  `createdAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `updateAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `info_sirus_infk_users` (`sender`),
  CONSTRAINT `info_sirus_infk_users` FOREIGN KEY (`sender`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Membuang data untuk tabel db_harmoni.info_sirus: ~0 rows (lebih kurang)
DELETE FROM `info_sirus`;
INSERT INTO `info_sirus` (`id`, `filePath`, `type`, `sender`, `createdAt`, `updateAt`) VALUES
	(1, 'uploads/template_info-siru.png', 'flyer', 1, '2024-12-26 19:34:01', '2024-12-26 19:34:01'),
	(2, 'uploads/template_info-siru.mp4', 'video', 1, '2025-01-04 10:18:33', '2025-01-04 10:18:33');

-- membuang struktur untuk table db_harmoni.jadwal_lks_bipartit
DROP TABLE IF EXISTS `jadwal_lks_bipartit`;
CREATE TABLE IF NOT EXISTS `jadwal_lks_bipartit` (
  `id` int NOT NULL AUTO_INCREMENT,
  `temaId` int DEFAULT NULL,
  `namaAgenda` varchar(255) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `createdAt` datetime DEFAULT CURRENT_TIMESTAMP,
  `updateAt` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `temaId` (`temaId`),
  CONSTRAINT `jadwal_lks_bipartit_ibfk_1` FOREIGN KEY (`temaId`) REFERENCES `tema_lks_bipartit` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Membuang data untuk tabel db_harmoni.jadwal_lks_bipartit: ~0 rows (lebih kurang)
DELETE FROM `jadwal_lks_bipartit`;

-- membuang struktur untuk table db_harmoni.laporan_lks_bipartit
DROP TABLE IF EXISTS `laporan_lks_bipartit`;
CREATE TABLE IF NOT EXISTS `laporan_lks_bipartit` (
  `id` int NOT NULL AUTO_INCREMENT,
  `unit_id` int DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `topik_bahasan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `latar_belakang` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `rekomendasi` varchar(255) DEFAULT NULL,
  `tanggal_tindak_lanjut` date DEFAULT NULL,
  `uraian_tindak_lanjut` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `unitId` (`unit_id`),
  CONSTRAINT `laporan_lks_bipartit_ibfk_1` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Membuang data untuk tabel db_harmoni.laporan_lks_bipartit: ~0 rows (lebih kurang)
DELETE FROM `laporan_lks_bipartit`;
INSERT INTO `laporan_lks_bipartit` (`id`, `unit_id`, `tanggal`, `topik_bahasan`, `latar_belakang`, `rekomendasi`, `tanggal_tindak_lanjut`, `uraian_tindak_lanjut`, `created_at`, `updated_at`) VALUES
	(1, 1, '2024-12-01', 'Pelayanan Kesehatan ', ' Adanya pembatasan pemberian obat kepada pegawai atau keluarga pegawai yang ditanggung', 'Mengundang Yan HC Palembang dan APLN secara offline terkait prosedur pelayanan kesehatan di lingkungan PT PLN (Persero) UID S2JB atau melakukan pertemuan dengan  VP Yan HC di Kantor Yan HC Palembang', '2024-12-01', 'Pertemuan direncanakan tanggal 4 Desember 2024', '2024-12-01 15:28:30', '2024-12-01 15:29:29');

-- membuang struktur untuk table db_harmoni.monitor_lks_bipartit
DROP TABLE IF EXISTS `monitor_lks_bipartit`;
CREATE TABLE IF NOT EXISTS `monitor_lks_bipartit` (
  `id` int NOT NULL AUTO_INCREMENT,
  `unit_id` int DEFAULT NULL,
  `ba_id` int DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `unit_id` (`unit_id`),
  KEY `ba_id` (`ba_id`),
  CONSTRAINT `monitor_lks_bipartit_ibfk_1` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`),
  CONSTRAINT `monitor_lks_bipartit_ibfk_2` FOREIGN KEY (`ba_id`) REFERENCES `ba_pembentukan` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Membuang data untuk tabel db_harmoni.monitor_lks_bipartit: ~0 rows (lebih kurang)
DELETE FROM `monitor_lks_bipartit`;
INSERT INTO `monitor_lks_bipartit` (`id`, `unit_id`, `ba_id`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, '2024-12-01 17:36:45', '2024-12-01 17:36:45');

-- membuang struktur untuk table db_harmoni.monitor_serikat
DROP TABLE IF EXISTS `monitor_serikat`;
CREATE TABLE IF NOT EXISTS `monitor_serikat` (
  `monitor_id` int NOT NULL,
  `serikat_id` int NOT NULL,
  `nilai` int DEFAULT NULL,
  PRIMARY KEY (`monitor_id`,`serikat_id`),
  KEY `monitor_serikat_ibfk_2` (`serikat_id`),
  CONSTRAINT `monitor_serikat_ibfk_1` FOREIGN KEY (`monitor_id`) REFERENCES `monitor_lks_bipartit` (`id`),
  CONSTRAINT `monitor_serikat_ibfk_2` FOREIGN KEY (`serikat_id`) REFERENCES `serikat` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Membuang data untuk tabel db_harmoni.monitor_serikat: ~0 rows (lebih kurang)
DELETE FROM `monitor_serikat`;
INSERT INTO `monitor_serikat` (`monitor_id`, `serikat_id`, `nilai`) VALUES
	(1, 1, 3),
	(1, 2, 0),
	(1, 3, 0);

-- membuang struktur untuk table db_harmoni.penilaian_pdp
DROP TABLE IF EXISTS `penilaian_pdp`;
CREATE TABLE IF NOT EXISTS `penilaian_pdp` (
  `id` int NOT NULL AUTO_INCREMENT,
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
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `unitId` (`unit_id`),
  KEY `userId` (`user_id`),
  KEY `anggota_serikat_id` (`anggota_serikat_id`),
  CONSTRAINT `penilaian_pdp_ibfk_1` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`),
  CONSTRAINT `penilaian_pdp_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Membuang data untuk tabel db_harmoni.penilaian_pdp: ~0 rows (lebih kurang)
DELETE FROM `penilaian_pdp`;
INSERT INTO `penilaian_pdp` (`id`, `unit_id`, `user_id`, `anggota_serikat_id`, `peran`, `kpi`, `uraian`, `hasil_verifikasi`, `semester`, `nilai`, `tanggal`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 1, 'tes', 'tidak', 'ya', 'tidak', '1', 15, '2024-11-09', '2024-11-30 15:15:10', '2024-11-30 15:15:10'),
	(2, 1, 2, 2, 'tes 2.1', 'tidak', 'tidak', 'tidak', '1', 10, '2024-11-13', '2024-11-30 16:31:42', '2024-12-26 19:34:18'),
	(3, 1, 2, 2, 'tes 2.2', 'ya', 'tidak', 'tidak', '1', 14, '2024-11-28', '2024-11-30 16:35:27', '2024-11-30 16:51:17');

-- membuang struktur untuk table db_harmoni.tim_lks_bipartit
DROP TABLE IF EXISTS `tim_lks_bipartit`;
CREATE TABLE IF NOT EXISTS `tim_lks_bipartit` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nip_pegawai` int DEFAULT NULL,
  `nama_pegawai` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `peran` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `unitId` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `unit_lks_bipartit_1` (`unitId`),
  CONSTRAINT `unit_lks_bipartit_1` FOREIGN KEY (`unitId`) REFERENCES `units` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Membuang data untuk tabel db_harmoni.tim_lks_bipartit: ~0 rows (lebih kurang)
DELETE FROM `tim_lks_bipartit`;
INSERT INTO `tim_lks_bipartit` (`id`, `nip_pegawai`, `nama_pegawai`, `peran`, `unitId`) VALUES
	(1, 10101010, 'Andre Apriyana', 'Presiden', 1),
	(2, 23020229, 'Budiyana', 'Ketua', 2),
	(3, 6345347, 'Dimas', 'Ketua DPC', 2);

-- membuang struktur untuk table db_harmoni.date_monitor_lks_bipartit
DROP TABLE IF EXISTS `date_monitor_lks_bipartit`;
CREATE TABLE IF NOT EXISTS `date_monitor_lks_bipartit` (
  `id` int NOT NULL AUTO_INCREMENT,
  `monitor_id` int DEFAULT NULL,
  `tema_id` int DEFAULT NULL,
  `bulan_id` int DEFAULT NULL,
  `rekomendasi` varchar(255) DEFAULT NULL,
  `tindak_lanjut` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `evaluasi` varchar(255) DEFAULT NULL,
  `follow_up` varchar(255) DEFAULT NULL,
  `realisasi` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `monitor_id` (`monitor_id`),
  KEY `tema_id` (`tema_id`),
  KEY `bulan_id` (`bulan_id`),
  CONSTRAINT `date_monitor_lks_bipartit_ibfk_1` FOREIGN KEY (`monitor_id`) REFERENCES `monitor_lks_bipartit` (`id`),
  CONSTRAINT `date_monitor_lks_bipartit_ibfk_2` FOREIGN KEY (`bulan_id`) REFERENCES `bulan` (`id`),
  CONSTRAINT `date_monitor_lks_bipartit_ibfk_3` FOREIGN KEY (`tema_id`) REFERENCES `tema_lks_bipartit` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Membuang data untuk tabel db_harmoni.date_monitor_lks_bipartit: ~2 rows (lebih kurang)
DELETE FROM `date_monitor_lks_bipartit`;
INSERT INTO `date_monitor_lks_bipartit` (`id`, `monitor_id`, `tema_id`, `bulan_id`, `rekomendasi`, `tindak_lanjut`, `evaluasi`, `follow_up`, `realisasi`, `created_at`, `updated_at`) VALUES
	(1, 1, 1, 1, 'Mengundang Yan HC Reg Sumkal untuk memberikan penjelasan terkait aturan layanan kesehatan terbaru di PLN', 'Akan ditindaklanjuti sesuai rekomendasi', 'dalam proses', 'tidak', '100%', '2024-11-25 23:12:24', '2024-11-25 23:12:24'),
	(2, 1, 2, 2, 'tes tes tes', 'tes tes', 'dalam tes', 'tidak', '0%', '2024-11-25 23:12:24', '2024-11-25 23:12:24');

  -- membuang struktur untuk table db_harmoni.dokumen_hi
DROP TABLE IF EXISTS `dokumen_hi`;
CREATE TABLE IF NOT EXISTS `dokumen_hi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_dokumen` varchar(255) DEFAULT NULL, 
  `link_gdrive` varchar(255) DEFAULT NULL, 
  `kategori` int DEFAULT NULL, 
  `tanggal` DATE,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Membuang data untuk tabel db_harmoni.dokumen_hi
DELETE FROM `dokumen_hi`;
INSERT INTO `dokumen_hi` (`id`, `nama_dokumen`, `link_gdrive`, `kategori`, `tanggal`, `created_at`, `updated_at`) VALUES 
  (1, 'dokumen hi 1', "https://drive.google.com/file/d/test", 1, '2024-11-25', '2024-11-25 23:12:24', '2024-11-25 23:12:24'),
  (2, 'dokumen hi 2', null, 2, '2024-11-25', '2024-11-25 23:12:24', '2024-11-25 23:12:24');  

  -- membuang struktur untuk table db_harmoni.dokumen_ad
DROP TABLE IF EXISTS `dokumen_ad`;
CREATE TABLE IF NOT EXISTS `dokumen_ad` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama_dokumen` varchar(255) DEFAULT NULL, 
  `link_gdrive` varchar(255) DEFAULT NULL, 
  `kategori` int DEFAULT NULL, 
  `tanggal` DATE,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


-- Membuang data untuk tabel db_harmoni.dokumen_ad
DELETE FROM `dokumen_ad`;
INSERT INTO `dokumen_ad` (`id`, `nama_dokumen`, `link_gdrive`, `kategori`, `tanggal`, `created_at`, `updated_at`) VALUES 
  (1, 'dokumen ad 1', "https://drive.google.com/file/d/test", 1, '2024-11-25', '2024-11-25 23:12:24', '2024-11-25 23:12:24'),
  (2, 'dokumen ad 2', null, 2, '2024-11-25', '2024-11-25 23:12:24', '2024-11-25 23:12:24');  

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
