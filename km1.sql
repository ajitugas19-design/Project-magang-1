-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 14, 2026 at 06:59 PM
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
-- Database: `km1`
--

-- --------------------------------------------------------

--
-- Table structure for table `bu`
--

CREATE TABLE `bu` (
  `id` int(11) NOT NULL,
  `bu` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bu`
--

INSERT INTO `bu` (`id`, `bu`) VALUES
(1, 'HO'),
(2, 'HD-PELLET'),
(3, 'HW'),
(4, 'IKS'),
(5, 'KARUNG JUMBO'),
(6, 'PSF'),
(7, 'PVD'),
(8, 'STRAPPING');

-- --------------------------------------------------------

--
-- Table structure for table `data_km`
--

CREATE TABLE `data_km` (
  `id` int(11) NOT NULL,
  `kode` varchar(50) NOT NULL,
  `kendaraan_id` int(11) DEFAULT NULL,
  `nopol` varchar(20) DEFAULT NULL,
  `kendaraan` varchar(100) DEFAULT NULL,
  `sopir` varchar(100) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `tgl_out` date DEFAULT NULL,
  `tgl_in` date DEFAULT NULL,
  `jam_out` time DEFAULT NULL,
  `jam_in` time DEFAULT NULL,
  `bu` varchar(100) DEFAULT NULL,
  `bu2` varchar(100) DEFAULT NULL,
  `material` varchar(100) DEFAULT NULL,
  `material2` varchar(100) DEFAULT NULL,
  `ket` varchar(255) DEFAULT NULL,
  `ket2` varchar(255) DEFAULT NULL,
  `km_keluar` int(11) DEFAULT NULL,
  `km_datang` int(11) DEFAULT NULL,
  `km_total` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kendaraan`
--

CREATE TABLE `kendaraan` (
  `id` int(11) NOT NULL,
  `nopol` varchar(20) NOT NULL,
  `kendaraan` varchar(100) NOT NULL,
  `sopir` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `material`
--

CREATE TABLE `material` (
  `id` int(11) NOT NULL,
  `material` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `material`
--

INSERT INTO `material` (`id`, `material`) VALUES
(1, 'DACRON'),
(2, 'BODONGAN'),
(3, 'GILINGAN'),
(4, 'HDP'),
(5, 'KARUNG JUBO'),
(6, 'LEMBUTAN'),
(7, 'PADDING'),
(8, 'PELET'),
(9, 'PRESS'),
(10, 'PVD'),
(11, 'SAMPAH PLASTIK'),
(12, 'SAMPAH BESI'),
(13, 'STRAPPING');

-- --------------------------------------------------------

--
-- Table structure for table `pengguna`
--

CREATE TABLE `pengguna` (
  `id` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `sandi` varchar(255) NOT NULL,
  `status` varchar(20) DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengguna`
--

INSERT INTO `pengguna` (`id`, `nama`, `sandi`, `status`) VALUES
(1, 'admin', 'admin', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bu`
--
ALTER TABLE `bu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_km`
--
ALTER TABLE `data_km`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kendaraan_id` (`kendaraan_id`);

--
-- Indexes for table `kendaraan`
--
ALTER TABLE `kendaraan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nopol` (`nopol`);

--
-- Indexes for table `material`
--
ALTER TABLE `material`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bu`
--
ALTER TABLE `bu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `data_km`
--
ALTER TABLE `data_km`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `kendaraan`
--
ALTER TABLE `kendaraan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `material`
--
ALTER TABLE `material`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `data_km`
--
ALTER TABLE `data_km`
  ADD CONSTRAINT `data_km_ibfk_1` FOREIGN KEY (`kendaraan_id`) REFERENCES `kendaraan` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
