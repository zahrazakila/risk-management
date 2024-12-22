-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 18 Des 2024 pada 09.00
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

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
-- Struktur dari tabel `faculties`
--

CREATE TABLE `faculties` (
  `id` int(11) NOT NULL,
  `faculty_name` varchar(100) NOT NULL,
  `nama_lengkap` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `faculties`
--

INSERT INTO `faculties` (`id`, `faculty_name`, `nama_lengkap`) VALUES
(1, 'FADIB', 'Fakultas Adab dan Ilmu Budaya'),
(2, 'FDK', 'Fakultas Dakwah dan Komunikasi'),
(3, 'FEBI', 'Fakultas Ekonomi dan Bisnis Islam'),
(4, 'FISHUM', 'Fakultas Ilmu Sosial dan Humaniora'),
(5, 'FITK', 'Fakultas Ilmu Tarbiyah dan Keguruan'),
(6, 'FSH', 'Fakultas Syariah dan Hukum'),
(7, 'FST', 'Fakultas Sains dan Teknologi'),
(8, 'FUPI', 'Fakultas Ushuluddin dan Pemikiran Islam');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mitigations`
--

CREATE TABLE `mitigations` (
  `id` int(11) NOT NULL,
  `risk_code` varchar(10) DEFAULT NULL,
  `inherent_likehood` tinyint(4) DEFAULT NULL,
  `inherent_impact` tinyint(4) DEFAULT NULL,
  `inherent_risk_level` int(11) DEFAULT NULL,
  `existing_control` enum('ada','tidak ada') DEFAULT NULL,
  `control_quality` enum('memadai','belum memadai') DEFAULT NULL,
  `execution_status` enum('dijalankan 100%','belum dijalankan 100%') DEFAULT NULL,
  `residual_likehood` tinyint(4) DEFAULT NULL,
  `residual_impact` tinyint(4) DEFAULT NULL,
  `residual_risk_level` int(11) DEFAULT NULL,
  `risk_treatment` enum('accept','share','reduce','avoid') DEFAULT NULL,
  `mitigation_plan` text DEFAULT NULL,
  `after_mitigation_likehood` tinyint(4) DEFAULT NULL,
  `after_mitigation_impact` tinyint(4) DEFAULT NULL,
  `after_mitigation_risk_level` int(11) DEFAULT NULL,
  `faculty_id` int(11) DEFAULT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `mitigations`
--

INSERT INTO `mitigations` (`id`, `risk_code`, `inherent_likehood`, `inherent_impact`, `inherent_risk_level`, `existing_control`, `control_quality`, `execution_status`, `residual_likehood`, `residual_impact`, `residual_risk_level`, `risk_treatment`, `mitigation_plan`, `after_mitigation_likehood`, `after_mitigation_impact`, `after_mitigation_risk_level`, `faculty_id`, `is_completed`) VALUES
(26, 'R1', 1, 1, 1, 'ada', 'memadai', NULL, 1, 1, 1, 'accept', 'hahaha', 1, 1, 1, 2, 1),
(27, 'R2', 4, 5, 20, 'tidak ada', 'belum memadai', NULL, 3, 4, 12, 'share', 'dfghjkuyg', 3, 1, 3, 1, 1),
(28, 'R3', 3, 1, 3, 'tidak ada', 'memadai', NULL, 1, 2, 2, 'reduce', 'dfghjkjh', 1, 1, 1, 1, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `monitoring`
--

CREATE TABLE `monitoring` (
  `id` int(11) NOT NULL,
  `risk_code` varchar(255) DEFAULT NULL,
  `risk_event` text NOT NULL,
  `mitigation_plan` text NOT NULL,
  `month` varchar(10) DEFAULT NULL,
  `status` enum('rencana','pelaksanaan') DEFAULT NULL,
  `evidence` text DEFAULT NULL,
  `pic` text DEFAULT NULL,
  `month_status` text DEFAULT NULL,
  `likelihood` int(1) NOT NULL DEFAULT 1,
  `impact` int(1) NOT NULL DEFAULT 1,
  `faculty_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `monitoring`
--

INSERT INTO `monitoring` (`id`, `risk_code`, `risk_event`, `mitigation_plan`, `month`, `status`, `evidence`, `pic`, `month_status`, `likelihood`, `impact`, `faculty_id`) VALUES
(37, 'R1', '', '', NULL, NULL, 'hahk', 'WR Bidang Keuangan', '{\"Jan\":{\"rencana\":\"rencana\",\"pelaksanaan\":\"none\"},\"Feb\":{\"rencana\":\"rencana\",\"pelaksanaan\":\"none\"},\"Mar\":{\"rencana\":\"rencana\",\"pelaksanaan\":\"none\"},\"Apr\":{\"rencana\":\"rencana\",\"pelaksanaan\":\"none\"},\"May\":{\"rencana\":\"rencana\",\"pelaksanaan\":\"none\"},\"Jun\":{\"rencana\":\"rencana\",\"pelaksanaan\":\"none\"},\"Jul\":{\"rencana\":\"rencana\",\"pelaksanaan\":\"none\"},\"Aug\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Sep\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Oct\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Nov\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Dec\":{\"rencana\":\"none\",\"pelaksanaan\":\"pelaksanaan\"}}', 1, 1, 2),
(38, 'R2', '', '', NULL, NULL, 'ghjkiytgfvbn', 'mjhgvbn', '{\"Jan\":{\"rencana\":\"none\",\"pelaksanaan\":\"pelaksanaan\"},\"Feb\":{\"rencana\":\"rencana\",\"pelaksanaan\":\"pelaksanaan\"},\"Mar\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Apr\":{\"rencana\":\"none\",\"pelaksanaan\":\"pelaksanaan\"},\"May\":{\"rencana\":\"rencana\",\"pelaksanaan\":\"none\"},\"Jun\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Jul\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Aug\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Sep\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Oct\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Nov\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Dec\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"}}', 1, 1, 1),
(39, 'R3', '', '', NULL, NULL, 'siujhgfg', 'sdfgh', '{\"Jan\":{\"rencana\":\"rencana\",\"pelaksanaan\":\"pelaksanaan\"},\"Feb\":{\"rencana\":\"rencana\",\"pelaksanaan\":\"pelaksanaan\"},\"Mar\":{\"rencana\":\"rencana\",\"pelaksanaan\":\"none\"},\"Apr\":{\"rencana\":\"rencana\",\"pelaksanaan\":\"none\"},\"May\":{\"rencana\":\"rencana\",\"pelaksanaan\":\"none\"},\"Jun\":{\"rencana\":\"none\",\"pelaksanaan\":\"pelaksanaan\"},\"Jul\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Aug\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Sep\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Oct\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Nov\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"},\"Dec\":{\"rencana\":\"none\",\"pelaksanaan\":\"none\"}}', 1, 1, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `risks`
--

CREATE TABLE `risks` (
  `id` int(11) NOT NULL,
  `objective` text NOT NULL,
  `process_business` enum('Akademik','Finansial','Kepegawaian') NOT NULL,
  `risk_category` enum('strategic','financial','operational') NOT NULL,
  `risk_code` varchar(10) NOT NULL,
  `risk_event` text NOT NULL,
  `risk_cause` text NOT NULL,
  `risk_source` enum('internal','external') NOT NULL,
  `qualitative` text DEFAULT NULL,
  `quantitative` varchar(50) DEFAULT NULL,
  `risk_owner` text DEFAULT NULL,
  `department` text DEFAULT NULL,
  `faculty_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `risks`
--

INSERT INTO `risks` (`id`, `objective`, `process_business`, `risk_category`, `risk_code`, `risk_event`, `risk_cause`, `risk_source`, `qualitative`, `quantitative`, `risk_owner`, `department`, `faculty_id`, `created_by`) VALUES
(20, 'Jumlah keluaran Penelitian yang memiliki rekognisi nasional /internasional  mencacapai 220 karya', 'Akademik', 'strategic', 'R1', 'tes', 'Kompetensi akademik writing rendah', 'internal', 'Menurunnya universitas', '5000000', 'Jane ', 'auk', 2, 12),
(21, 'Target PNBP 2021 sebesar Rp135.000.000.000', 'Kepegawaian', 'financial', 'R2', 'Target tidak terpenuhi', 'Mahasiswa banyak yang telat bayar dan cuti karena pandemi', 'internal', 'Menurunnya reputasi universitas', '4567890', 'tes', 'AAK', 1, 11),
(22, 'Target PNBP 2021 sebesar Rp135.000.000.000', 'Finansial', 'strategic', 'R3', 'rugi', 'tes', 'internal', 'Menurunnya universitas', '1234567', 'tes', 'auk', 1, 11);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','sub-admin','user') NOT NULL,
  `faculty_id` int(11) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `total_logins` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `faculty_id`, `last_login`, `total_logins`) VALUES
(1, 'admin', '$2y$10$XHi1LR.OGU9Ll2ODKi4gveBu.eh9B3Glqe5tnSnNWKEYPEzOk28OO', 'admin', NULL, '2024-12-18 14:56:08', 4),
(2, 'user', '$2y$10$Lio5EZHICI0Xz2UM4qTbbO0QxKEHzmeTzZRG8rbe7yCkP4J93klWC', 'user', NULL, NULL, 0),
(11, 'admin.fadib@uin-suka.ac.id', '$2y$10$MOl0z1FkgdRLaAla.H6K6OvZ8aKdcJPyk8VMpvMMgMZNHdp/7RaVa', 'sub-admin', 1, NULL, 0),
(12, 'admin.fdk@uin-suka.ac.id', '$2y$10$O.NgF2F/4mh1QTNsEJDor.oUDAA1Ts0kmfAEPtKttcQQfrQPj5122', 'sub-admin', 2, '2024-12-18 14:40:34', 1),
(13, 'admin.febi@uin-suka.ac.id', '$2y$10$2TaJIsssPCWLQ6fi6pQYKOZXEFEj.h4TI1GBR..mZLLZWZp2mMqmW', 'sub-admin', 3, NULL, 0),
(14, 'admin.fishum@uin-suka.ac.id', '$2y$10$hulXxdIDOX5rtL3hOyo7/u6PTfS4.6IR9WovUbVSxZi2wFV09htty', 'sub-admin', 4, NULL, 0),
(15, 'admin.fitk@uin-suka.ac.id', '$2y$10$TtB8YFs/mmgAvEGsPJhAE.cIAY9vcqrxhxaTU1F0zNSu82b.PQ9Em', 'sub-admin', 5, NULL, 0),
(16, 'admin.fsh@uin-suka.ac.id', '$2y$10$4m1BmE8jNTnS.rksIqYA1OXmMzs2Tap/csGReF7diyofL9.F/Lnka', 'sub-admin', 6, NULL, 0),
(17, 'admin.fst@uin-suka.ac.id', '$2y$10$C2Cx3OgdY83NGAAA7CaYceK01RX.qzSqVlxoNNfmipfjLzZWZc76u', 'sub-admin', 7, NULL, 0),
(18, 'admin.fupi@uin-suka.ac.id', '$2y$10$jBznbfsaX6qrVX0UlvehkeQRe43gkkE.mUKgohKiSYjnwVM5KyQW.', 'sub-admin', 8, NULL, 0),
(19, 'user.fadib@uin-suka.ac.id', '$2y$10$Ua8OqUZC40iIdX.KK26Ypu8q15aYIjr5vW1vSvqCJGPF5hp5WEZoi', 'user', 1, NULL, 0),
(20, 'user.fdk@uin-suka.ac.id', '$2y$10$F1JPBKEanBGQNgp99p7jZ.JgFDUdieCfqF38hhUGGGc.L2eQdpgHy', 'user', 2, '2024-12-18 14:50:07', 1),
(21, 'user.febi@uin-suka.ac.id', '$2y$10$X3C49Al3N.5fq0/de6BjruEOFRJI6CvA4IwspusK7BAhFFnPWDNNa', 'user', 3, NULL, 0),
(22, 'user.fishum@uin-suka.ac.id', '$2y$10$rEsDgUKTpGyM2YH31TM5SuPa6An/ra9YSK/Fjw3PAXf.BV7TEwonu', 'user', 4, NULL, 0),
(24, 'user.fsh@uin-suka.ac.id', '$2y$10$XS3gEq.L4tw3iQwklN0OQuvGLl0Krq1/Jsp4ubHW.NDftQG05PDKa', 'user', 6, NULL, 0),
(25, 'user.fst@uin-suka.ac.id', '$2y$10$NklsIHPWTlaaG.B6.QtAOuaVrazw.m0Ga/GPJxMhFQLJMzFqoJhZe', 'user', 7, NULL, 0),
(26, 'user.fupi@uin-suka.ac.id', '$2y$10$72D9zUiZVVtuGsOGbWm8xOkKI3eIIYAaMCimruWC0PACtEDrDA.5i', 'user', 8, NULL, 0);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `faculties`
--
ALTER TABLE `faculties`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `mitigations`
--
ALTER TABLE `mitigations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `risk_code` (`risk_code`);

--
-- Indeks untuk tabel `monitoring`
--
ALTER TABLE `monitoring`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `risks`
--
ALTER TABLE `risks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `risk_code` (`risk_code`),
  ADD KEY `fk_faculty_id` (`faculty_id`),
  ADD KEY `fk_created_by` (`created_by`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `faculties`
--
ALTER TABLE `faculties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `mitigations`
--
ALTER TABLE `mitigations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT untuk tabel `monitoring`
--
ALTER TABLE `monitoring`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT untuk tabel `risks`
--
ALTER TABLE `risks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `mitigations`
--
ALTER TABLE `mitigations`
  ADD CONSTRAINT `mitigations_ibfk_1` FOREIGN KEY (`risk_code`) REFERENCES `risks` (`risk_code`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `risks`
--
ALTER TABLE `risks`
  ADD CONSTRAINT `fk_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_faculty_id` FOREIGN KEY (`faculty_id`) REFERENCES `faculties` (`id`),
  ADD CONSTRAINT `risks_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
