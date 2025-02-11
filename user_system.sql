-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 11, 2025 at 07:30 AM
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
-- Database: `user_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `aktivitas_dosen`
--

CREATE TABLE `aktivitas_dosen` (
  `id` int(11) NOT NULL,
  `dosen_id` int(11) DEFAULT NULL,
  `jenis_aktivitas` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `durasi` varchar(255) DEFAULT NULL,
  `status_aktivitas` varchar(255) DEFAULT NULL,
  `bukti` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penelitian`
--

CREATE TABLE `penelitian` (
  `id` int(11) NOT NULL,
  `dosen_id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `tanggal_mulai` date DEFAULT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `pendanaan` varchar(255) DEFAULT NULL,
  `publikasi` text DEFAULT NULL,
  `kata_kunci` varchar(255) DEFAULT NULL,
  `lampiran` varchar(255) DEFAULT NULL,
  `hasil_penelitian` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengabdian_dosen`
--

CREATE TABLE `pengabdian_dosen` (
  `id` int(11) NOT NULL,
  `judul_kegiatan` varchar(255) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `lokasi` varchar(255) NOT NULL,
  `deskripsi_kegiatan` text NOT NULL,
  `manfaat` text DEFAULT NULL,
  `tim_pelaksana` text NOT NULL,
  `dokumentasi` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `dosen_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penunjang_pengabdian`
--

CREATE TABLE `penunjang_pengabdian` (
  `id` int(11) NOT NULL,
  `dosen_id` int(11) NOT NULL,
  `jenis_penunjang` text NOT NULL,
  `deskripsi` text NOT NULL,
  `tanggal_pengajuan` date DEFAULT NULL,
  `jumlah_penunjang` int(11) NOT NULL,
  `bukti_penunjang` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','dosen') NOT NULL,
  `nama_dosen` varchar(255) DEFAULT NULL,
  `nip` varchar(20) DEFAULT NULL,
  `fakultas` varchar(255) DEFAULT NULL,
  `program_studi` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `nama_dosen`, `nip`, `fakultas`, `program_studi`) VALUES
(2, '2', '$2y$10$K70Jz6HCca9Y29rr8Jh65eFMt7xyYbOfQN8..kpHi1ArA9uX4.9ea', 'admin', NULL, NULL, NULL, NULL),
(4, 'bayu', '$2y$10$FbTQVsTPZdSvMRh.dQGMWORiQyIUHXlQXvonr3wwhVOEnbkeDwTPm', 'dosen', 'Bayu winata', '220170103', 'ilmu komputer', 'sistem informasi');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `aktivitas_dosen`
--
ALTER TABLE `aktivitas_dosen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dosen_id` (`dosen_id`);

--
-- Indexes for table `penelitian`
--
ALTER TABLE `penelitian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dosen_id` (`dosen_id`);

--
-- Indexes for table `pengabdian_dosen`
--
ALTER TABLE `pengabdian_dosen`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `penunjang_pengabdian`
--
ALTER TABLE `penunjang_pengabdian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dosen_id` (`dosen_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aktivitas_dosen`
--
ALTER TABLE `aktivitas_dosen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `penelitian`
--
ALTER TABLE `penelitian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `pengabdian_dosen`
--
ALTER TABLE `pengabdian_dosen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `penunjang_pengabdian`
--
ALTER TABLE `penunjang_pengabdian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `aktivitas_dosen`
--
ALTER TABLE `aktivitas_dosen`
  ADD CONSTRAINT `aktivitas_dosen_ibfk_1` FOREIGN KEY (`dosen_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `penelitian`
--
ALTER TABLE `penelitian`
  ADD CONSTRAINT `penelitian_ibfk_1` FOREIGN KEY (`dosen_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `penunjang_pengabdian`
--
ALTER TABLE `penunjang_pengabdian`
  ADD CONSTRAINT `penunjang_pengabdian_ibfk_1` FOREIGN KEY (`dosen_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
