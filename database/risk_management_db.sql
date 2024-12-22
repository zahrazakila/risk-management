-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 22, 2024 at 01:41 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `risk_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `faculties`
--

CREATE TABLE `faculties` (
  `id` int NOT NULL,
  `faculty_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_lengkap` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculties`
--

INSERT INTO `faculties` (`id`, `faculty_name`, `nama_lengkap`) VALUES
(1, 'FADIB', 'Fakultas Adab dan Ilmu Budaya'),
(2, 'FDK', 'Fakultas Dakwah dan Komunikasi'),
(3, 'FEBI', 'Fakultas Ekonomi dan Bisnis Islam'),
(4, 'FISHUM', 'Fakultas Ilmu Sosial dan Humaniora'),
(5, 'FITK', 'Fakultas Ilmu Tarbiyah dan Keguruan'),
(6, 'FSH', 'Fakultas Syariah dan Hukum'),
(7, 'FST', 'Fakultas Sains dan Teknologi'),
(8, 'FUPI', 'Fakultas Ushuluddin dan Pemikiran Islam'),
(9, 'UNI', 'Universitas');

-- --------------------------------------------------------

--
-- Table structure for table `mitigations`
--

CREATE TABLE `mitigations` (
  `id` int NOT NULL,
  `risk_code` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `inherent_likehood` tinyint DEFAULT NULL,
  `inherent_impact` tinyint DEFAULT NULL,
  `inherent_risk_level` int DEFAULT NULL,
  `existing_control` enum('Yes','No') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `control_quality` enum('Sufficient','Not Sufficient') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `execution_status` enum('On Progress','Pending','Completed') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `residual_likehood` tinyint DEFAULT NULL,
  `residual_impact` tinyint DEFAULT NULL,
  `residual_risk_level` int DEFAULT NULL,
  `risk_treatment` enum('Accept','Share','Reduce','avoid') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `mitigation_plan` text COLLATE utf8mb4_general_ci,
  `after_mitigation_likehood` tinyint DEFAULT NULL,
  `after_mitigation_impact` tinyint DEFAULT NULL,
  `after_mitigation_risk_level` int DEFAULT NULL,
  `faculty_id` int DEFAULT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mitigations`
--

INSERT INTO `mitigations` (`id`, `risk_code`, `inherent_likehood`, `inherent_impact`, `inherent_risk_level`, `existing_control`, `control_quality`, `execution_status`, `residual_likehood`, `residual_impact`, `residual_risk_level`, `risk_treatment`, `mitigation_plan`, `after_mitigation_likehood`, `after_mitigation_impact`, `after_mitigation_risk_level`, `faculty_id`, `is_completed`) VALUES
(37, 'R1', 2, 4, 8, 'Yes', 'Sufficient', 'Pending', 1, 1, 1, 'Share', 'New', 1, 1, 1, 2, 1),
(38, 'R2', 2, 2, 4, 'Yes', 'Sufficient', 'On Progress', 1, 1, 1, 'Accept', 'tes', 1, 1, 1, 1, 1),
(39, 'R3', 3, 1, 3, 'Yes', 'Sufficient', 'Completed', 1, 1, 1, 'Reduce', 'tes', 2, 1, 2, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `monitoring`
--

CREATE TABLE `monitoring` (
  `id` int NOT NULL,
  `risk_code` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `risk_event` text COLLATE utf8mb4_general_ci NOT NULL,
  `mitigation_plan` text COLLATE utf8mb4_general_ci NOT NULL,
  `month` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('rencana','pelaksanaan') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `evidence` text COLLATE utf8mb4_general_ci,
  `pic` text COLLATE utf8mb4_general_ci,
  `month_status` text COLLATE utf8mb4_general_ci,
  `likelihood` int NOT NULL DEFAULT '1',
  `impact` int NOT NULL DEFAULT '1',
  `faculty_id` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `monitoring`
--

INSERT INTO `monitoring` (`id`, `risk_code`, `risk_event`, `mitigation_plan`, `month`, `status`, `evidence`, `pic`, `month_status`, `likelihood`, `impact`, `faculty_id`) VALUES
(47, 'R1', 'Targenyat tidak terpenuhi', '', NULL, NULL, 'YES', 'WR Bidang Keuangan', '{\"Jan\":{\"rencana\":\"rencana\",\"pelaksanaan\":\"none\"},\"Feb\":{\"rencana\":\"rencana\",\"pelaksanaan\":\"none\"},\"Mar\":{\"rencana\":\"rencana\",\"pelaksanaan\":\"none\"},\"Apr\":{\"rencana\":\"rencana\",\"pelaksanaan\":\"none\"},\"May\":{\"rencana\":\"none\",\"pelaksanaan\":\"pelaksanaan\"},\"Jun\":{\"rencana\":\"none\",\"pelaksanaan\":\"pelaksanaan\"},\"Jul\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Aug\":{\"rencana\":\"none\",\"pelaksanaan\":\"pelaksanaan\"},\"Sep\":{\"rencana\":\"none\",\"pelaksanaan\":\"pelaksanaan\"},\"Oct\":{\"rencana\":\"none\",\"pelaksanaan\":\"pelaksanaan\"},\"Nov\":{\"rencana\":\"none\",\"pelaksanaan\":\"pelaksanaan\"},\"Dec\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"}}', 1, 1, 2),
(48, 'R2', 'tes', '', NULL, NULL, 'tes', 'tes', '{\"Jan\":{\"rencana\":\"rencana\",\"pelaksanaan\":\"none\"},\"Feb\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Mar\":{\"rencana\":\"none\",\"pelaksanaan\":\"pelaksanaan\"},\"Apr\":{\"rencana\":\"none\",\"pelaksanaan\":\"pelaksanaan\"},\"May\":{\"rencana\":\"rencana\",\"pelaksanaan\":\"none\"},\"Jun\":{\"rencana\":\"rencana\",\"pelaksanaan\":\"none\"},\"Jul\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Aug\":{\"rencana\":\"none\",\"pelaksanaan\":\"pelaksanaan\"},\"Sep\":{\"rencana\":\"none\",\"pelaksanaan\":\"pelaksanaan\"},\"Oct\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Nov\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Dec\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"}}', 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `risks`
--

CREATE TABLE `risks` (
  `id` int NOT NULL,
  `objective` text COLLATE utf8mb4_general_ci NOT NULL,
  `process_business` enum('Akademik','Finansial','Kepegawaian') COLLATE utf8mb4_general_ci NOT NULL,
  `risk_category` enum('Strategic','Financial','Operational') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `risk_code` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `risk_event` text COLLATE utf8mb4_general_ci NOT NULL,
  `risk_cause` text COLLATE utf8mb4_general_ci NOT NULL,
  `risk_source` enum('Internal','External') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `qualitative` text COLLATE utf8mb4_general_ci,
  `quantitative` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `risk_owner` text COLLATE utf8mb4_general_ci,
  `department` text COLLATE utf8mb4_general_ci,
  `faculty_id` int DEFAULT NULL,
  `created_by` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `risks`
--

INSERT INTO `risks` (`id`, `objective`, `process_business`, `risk_category`, `risk_code`, `risk_event`, `risk_cause`, `risk_source`, `qualitative`, `quantitative`, `risk_owner`, `department`, `faculty_id`, `created_by`) VALUES
(32, 'Jumlah keluaran Penelitian yang memiliki rekognisi nasional /internasional  mencacapai 220 karya', 'Akademik', 'Strategic', 'R1', 'Targenyat tidak terpenuhi', 'Kompetensi akademik writing rendah', 'Internal', 'Menurunnya reputasi universitas', '500000', 'WR Bidang Keuangan', 'AAK', 2, 12),
(33, 'Jumlah keluaran Penelitian yang memiliki rekognisi nasional /internasional  mencacapai 220 karya', 'Finansial', 'Strategic', 'R2', 'tes', 'tes', 'External', 'tes', '500000', 'WR Bidang Keuangan', 'tes', 1, 11),
(34, 'Jumlah keluaran Penelitian yang memiliki rekognisi nasional /internasional  mencacapai 220 karya', 'Finansial', 'Financial', 'R3', 'tes', 'Kompetensi akademik writing rendah', 'External', 'Menurunnya universitas', '1000000', 'tes', 'auk', 2, 12);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('admin','sub-admin','user') COLLATE utf8mb4_general_ci NOT NULL,
  `faculty_id` int DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `total_logins` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `faculty_id`, `last_login`, `total_logins`) VALUES
(1, 'admin', '$2y$10$XHi1LR.OGU9Ll2ODKi4gveBu.eh9B3Glqe5tnSnNWKEYPEzOk28OO', 'admin', 9, '2024-12-22 08:14:53', 36),
(11, 'admin.fadib@uin-suka.ac.id', '$2y$10$P3JLW8i7E0T0RKedn4iAz.Txpumn/0VB8BP6JMoTECMD2azL6cxMe', 'sub-admin', 1, '2024-12-20 13:13:00', 2),
(12, 'admin.fdk@uin-suka.ac.id', '$2y$10$1aR7X5JpNrLsTN48Mp5CmeB35vI7YXbXX4J8GAWZ0kA.RCWLTQ70O', 'sub-admin', 2, '2024-12-20 14:38:39', 9),
(13, 'admin.febi@uin-suka.ac.id', '$2y$10$SmrHqgkosjW6SHmWj5fC5uUO68KebQT0SUSr8C7ZCyhDb2dNazuL2', 'sub-admin', 3, NULL, 0),
(14, 'admin.fishum@uin-suka.ac.id', '$2y$10$wcJte38kunZjEGaGfeasmecoyt4GQCy9ip835Fwka5tKXRmj0rEKu', 'sub-admin', 4, NULL, 0),
(15, 'admin.fitk@uin-suka.ac.id', '$2y$10$QQZcs05intEBvEInt8z6HuPdUR1I5nS3rX1vqLBnBo5SP/v0L/bSy', 'sub-admin', 5, NULL, 0),
(16, 'admin.fsh@uin-suka.ac.id', '$2y$10$1EWECaNhdBNtEz4o9ceR..xq7ulyhOFexPO9qSFZBv82Xjrk9lhJO', 'sub-admin', 6, NULL, 0),
(17, 'admin.fst@uin-suka.ac.id', '$2y$10$NANJKE23Td4PMTOGEyoGlOy8GPAQwbJKN32XOz0.Fsad9Dh7iS/te', 'sub-admin', 7, '2024-12-19 16:27:27', 1),
(18, 'admin.fupi@uin-suka.ac.id', '$2y$10$qnBKC9F7vBLZ6pKZS1KO7eRyoWza85w7vRlXZdvsug1X6UZnuAWrS', 'sub-admin', 8, NULL, 0),
(19, 'user.fadib@uin-suka.ac.id', '$2y$10$cL4NwTPaRuGDJCrbS0Eev.KTOpb5lTM4zl0JjInXwlT8KSiBZmF6q', 'user', 1, '2024-12-20 13:16:28', 1),
(20, 'user.fdk@uin-suka.ac.id', '$2y$10$NGeH.7M4ykmgT1wQvIn3Pu1EgsHa4QrNs3nltmFqX94TYemyROpeG', 'user', 2, '2024-12-20 14:02:24', 5),
(21, 'user.febi@uin-suka.ac.id', '$2y$10$8KMJMVOYJrQQ0nL66B367ur.OktbM2WoelDec6BdsULT0P4EUqbMy', 'user', 3, NULL, 0),
(22, 'user.fishum@uin-suka.ac.id', '$2y$10$tbHSs3J1eH8wgB2PQnOTDu1cg.Z7h6xKullXlVD0c9xVlevBcNzKe', 'user', 4, NULL, 0),
(24, 'user.fsh@uin-suka.ac.id', '$2y$10$OinmOiWx9K0mhzE/vwm1LOWWb600PVoKHLlfu.Ky5Rg9BbfBRi9la', 'user', 6, NULL, 0),
(25, 'user.fst@uin-suka.ac.id', '$2y$10$1hij..t9YL71qafpD7Em0OpCtzMgfjkAZcl2o0P.w3Zv9ttQ0Hz3u', 'user', 7, '2024-12-19 16:32:34', 1),
(26, 'user.fupi@uin-suka.ac.id', '$2y$10$b0u/WQaqtJVzPLeNbb2GmObf0eBUb4ecgQBHzg0RQjtXxAkkBA2xm', 'user', 8, NULL, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `faculties`
--
ALTER TABLE `faculties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mitigations`
--
ALTER TABLE `mitigations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `risk_code` (`risk_code`);

--
-- Indexes for table `monitoring`
--
ALTER TABLE `monitoring`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_risk_code_monitoring` (`risk_code`);

--
-- Indexes for table `risks`
--
ALTER TABLE `risks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `risk_code` (`risk_code`),
  ADD KEY `fk_faculty_id` (`faculty_id`),
  ADD KEY `fk_created_by` (`created_by`);

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
-- AUTO_INCREMENT for table `faculties`
--
ALTER TABLE `faculties`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `mitigations`
--
ALTER TABLE `mitigations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `monitoring`
--
ALTER TABLE `monitoring`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `risks`
--
ALTER TABLE `risks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `mitigations`
--
ALTER TABLE `mitigations`
  ADD CONSTRAINT `fk_risk_code_mitigations` FOREIGN KEY (`risk_code`) REFERENCES `risks` (`risk_code`) ON DELETE CASCADE,
  ADD CONSTRAINT `mitigations_ibfk_1` FOREIGN KEY (`risk_code`) REFERENCES `risks` (`risk_code`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `monitoring`
--
ALTER TABLE `monitoring`
  ADD CONSTRAINT `fk_risk_code_monitoring` FOREIGN KEY (`risk_code`) REFERENCES `risks` (`risk_code`) ON DELETE CASCADE;

--
-- Constraints for table `risks`
--
ALTER TABLE `risks`
  ADD CONSTRAINT `fk_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_faculty_id` FOREIGN KEY (`faculty_id`) REFERENCES `faculties` (`id`),
  ADD CONSTRAINT `risks_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
