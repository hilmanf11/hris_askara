-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 01 Agu 2023 pada 09.21
-- Versi server: 10.4.27-MariaDB
-- Versi PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hris_tmp`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `accounts`
--

CREATE TABLE `accounts` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `number` varchar(30) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `account_coa`
--

CREATE TABLE `account_coa` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `departement_id` varchar(30) DEFAULT NULL,
  `position_id` varchar(30) DEFAULT NULL,
  `contract_id` varchar(30) DEFAULT NULL,
  `account_id` varchar(30) DEFAULT NULL,
  `job_type` varchar(10) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `agreements`
--

CREATE TABLE `agreements` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT 0,
  `approved_to` varchar(30) DEFAULT NULL,
  `approved_by` varchar(30) DEFAULT NULL,
  `approved_date` timestamp NULL DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `number` varchar(30) DEFAULT NULL,
  `position_id` varchar(30) DEFAULT NULL,
  `contract_id` varchar(30) DEFAULT NULL,
  `group_id` varchar(30) DEFAULT NULL,
  `date_sign` date DEFAULT NULL,
  `date_expired` date DEFAULT NULL,
  `attachment` text DEFAULT NULL,
  `note` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `allowances`
--

CREATE TABLE `allowances` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `number` varchar(30) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `amount` int(11) NOT NULL DEFAULT 0,
  `calculate_days` tinyint(1) DEFAULT 0,
  `type` varchar(20) NOT NULL DEFAULT 'FIX',
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `allowances`
--

INSERT INTO `allowances` (`id`, `created_by`, `created_date`, `updated_by`, `updated_date`, `deleted`, `number`, `name`, `description`, `amount`, `calculate_days`, `type`, `status`) VALUES
('20221210000001', 'admin', '2022-12-10 00:59:41', 'admin', '2022-12-14 11:45:53', 0, 'OPT001', 'Tunjangan Operator 1', '', 875000, 0, 'FIX', 0),
('20221210000012', 'admin', '2022-12-10 01:00:47', 'admin', '2022-12-14 11:47:10', 0, 'OPT012', 'Tunjangan Operator 12', '', 975000, 0, 'FIX', 0),
('20221210000013', 'admin', '2022-12-10 01:00:50', 'admin', '2022-12-14 11:48:50', 0, 'OPT013', 'Tunjangan Operator 13', '', 762500, 0, 'FIX', 0),
('20221210000014', 'admin', '2022-12-10 01:00:56', NULL, NULL, 0, 'OPT014', 'Tunjangan Operator 14', '', 0, 0, 'FIX', 0),
('20221210000018', 'admin', '2022-12-10 01:01:48', 'admin', '2023-02-07 15:12:49', 0, 'CSE001', 'Tunjangan Cleaning Service', '', 525000, 0, 'FIX', 0),
('20230117000002', 'admin', '2023-01-17 14:27:02', 'admin', '2023-02-10 15:20:40', 0, 'STF002', 'Tunjangan Jabatan', '', 0, 0, 'TEMPORARY', 0),
('20230117000003', 'admin', '2023-01-17 14:27:09', 'admin', '2023-02-10 15:20:45', 0, 'STF003', 'Tunjangan Kinerja', '', 0, 0, 'TEMPORARY', 0),
('20230210000001', 'admin', '2023-02-10 15:10:54', NULL, NULL, 0, 'STF004', 'Tunjangan Skill', '', 0, 0, 'TEMPORARY', 0),
('20230221000001', 'admin', '2023-02-21 13:37:29', NULL, NULL, 0, 'STF001', 'Tunjangan Tetap', '', 0, 0, 'FIX', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `allowance_cash_carries`
--

CREATE TABLE `allowance_cash_carries` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `number` varchar(10) NOT NULL,
  `name` varchar(30) DEFAULT NULL,
  `weekday` int(11) NOT NULL DEFAULT 0,
  `saturday` int(11) NOT NULL DEFAULT 0,
  `sunday` int(11) NOT NULL DEFAULT 0,
  `holiday` int(11) NOT NULL DEFAULT 0,
  `meal` int(11) NOT NULL DEFAULT 0,
  `remarks` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `allowance_students`
--

CREATE TABLE `allowance_students` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT 0,
  `group_id` varchar(30) DEFAULT NULL,
  `number` varchar(30) NOT NULL,
  `name` varchar(50) NOT NULL,
  `amount` int(11) DEFAULT 0,
  `months` tinyint(1) DEFAULT 0,
  `status` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `approvals`
--

CREATE TABLE `approvals` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL,
  `division_id` varchar(30) DEFAULT NULL,
  `departement_id` varchar(30) DEFAULT NULL,
  `table_name` varchar(50) DEFAULT NULL,
  `user_approval_1` varchar(30) DEFAULT NULL,
  `user_approval_2` varchar(30) DEFAULT NULL,
  `user_approval_3` varchar(30) DEFAULT NULL,
  `user_approval_4` varchar(30) DEFAULT NULL,
  `user_approval_5` varchar(30) DEFAULT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `attandances`
--

CREATE TABLE `attandances` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL,
  `number` varchar(20) NOT NULL,
  `date_in` date DEFAULT NULL,
  `date_out` date DEFAULT NULL,
  `time_in` time DEFAULT NULL,
  `time_out` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `attandances_temp`
--

CREATE TABLE `attandances_temp` (
  `id` bigint(20) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL,
  `number` varchar(30) NOT NULL,
  `attandance_date` date NOT NULL,
  `attandance_time` time NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `attandance_generates`
--

CREATE TABLE `attandance_generates` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL,
  `employee_id` varchar(30) NOT NULL,
  `shift` varchar(200) DEFAULT NULL,
  `date_in` date DEFAULT NULL,
  `date_out` date DEFAULT NULL,
  `time_in` varchar(10) DEFAULT NULL,
  `time_out` varchar(10) DEFAULT NULL,
  `overtime_request` varchar(30) DEFAULT NULL,
  `overtime_start` varchar(10) DEFAULT NULL,
  `overtime_end` varchar(10) DEFAULT NULL,
  `overtime_duration` varchar(50) DEFAULT NULL,
  `type` varchar(30) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `bpjs`
--

CREATE TABLE `bpjs` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `number` varchar(30) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `company` decimal(10,2) DEFAULT 0.00,
  `employee` decimal(10,2) DEFAULT 0.00,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `bpjs`
--

INSERT INTO `bpjs` (`id`, `created_by`, `created_date`, `updated_by`, `updated_date`, `deleted`, `number`, `name`, `company`, `employee`, `status`) VALUES
('20221202000001', 'admin', '2022-12-02 17:15:13', 'admin', '2022-12-02 17:15:33', 0, 'JHT', 'Jaminan Hari Tua', '3.70', '2.00', 0),
('20221210000002', 'admin', '2022-12-10 00:47:54', NULL, NULL, 0, 'JP', 'Jaminan Pensiun', '2.00', '1.00', 0),
('20221226000001', 'admin', '2022-12-26 04:13:33', NULL, NULL, 0, 'JKM', 'Jaminan Kematian', '0.30', '0.00', 0),
('20221226000002', 'admin', '2022-12-26 04:14:19', NULL, NULL, 0, 'JKK', 'Jaminan Kecelakaan Kerja', '0.89', '0.00', 0),
('20221226000003', 'admin', '2022-12-26 04:17:39', NULL, NULL, 0, 'BPJS', 'BPJS Kesehatan', '4.00', '1.00', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `calendars`
--

CREATE TABLE `calendars` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `trans_date` date DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cash_carries`
--

CREATE TABLE `cash_carries` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT 0,
  `approved_to` varchar(30) DEFAULT NULL,
  `approved_by` varchar(30) DEFAULT NULL,
  `approved_date` timestamp NULL DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL,
  `employee_id` varchar(30) NOT NULL,
  `trans_date` date NOT NULL,
  `request_code` varchar(50) NOT NULL,
  `request_name` varchar(50) DEFAULT NULL,
  `start` time DEFAULT NULL,
  `end` time DEFAULT NULL,
  `break` int(11) NOT NULL DEFAULT 0,
  `type` enum('REGULAR','CORRECTION') NOT NULL,
  `duration` varchar(30) DEFAULT NULL,
  `duration_hour` decimal(10,2) NOT NULL DEFAULT 0.00,
  `duration_convert` decimal(10,2) NOT NULL DEFAULT 0.00,
  `meal` tinyint(1) NOT NULL DEFAULT 0,
  `amount` int(11) NOT NULL DEFAULT 0,
  `plan` int(11) NOT NULL DEFAULT 0,
  `actual` int(11) NOT NULL DEFAULT 0,
  `remarks` text DEFAULT NULL,
  `idm_no` varchar(30) DEFAULT NULL,
  `attachment_idm` text DEFAULT NULL,
  `attachment` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `change_days`
--

CREATE TABLE `change_days` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT 0,
  `approved_to` varchar(30) DEFAULT NULL,
  `approved_by` varchar(30) DEFAULT NULL,
  `approved_date` timestamp NULL DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL,
  `employee_id` varchar(30) NOT NULL,
  `request_code` varchar(30) NOT NULL,
  `start` date DEFAULT NULL,
  `end` date DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `chats`
--

CREATE TABLE `chats` (
  `id` varchar(20) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL,
  `from_users_id` varchar(30) NOT NULL,
  `to_users_id` varchar(30) NOT NULL,
  `messages` text NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `coachings`
--

CREATE TABLE `coachings` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `employee_id` varchar(30) DEFAULT NULL,
  `trans_date` date DEFAULT NULL,
  `type` varchar(30) DEFAULT NULL,
  `header` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `config`
--

CREATE TABLE `config` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT 0,
  `number` varchar(30) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `address` text DEFAULT NULL,
  `latitude` text DEFAULT NULL,
  `longitude` text DEFAULT NULL,
  `logo` text DEFAULT NULL,
  `favicon` text DEFAULT NULL,
  `image` text DEFAULT NULL,
  `theme` varchar(50) DEFAULT NULL,
  `token` text DEFAULT NULL,
  `status` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `config`
--

INSERT INTO `config` (`id`, `created_by`, `created_date`, `updated_by`, `updated_date`, `deleted`, `number`, `name`, `description`, `address`, `latitude`, `longitude`, `logo`, `favicon`, `image`, `theme`, `token`, `status`) VALUES
('4c9a9e62-3ff6-11ed-a526-7085c2', 'admin', '2022-10-01 06:31:13', NULL, NULL, 0, 'BANSHU', 'PT KINENTA INDONESIA', 'HUMAN RESOURCE INFORMATION SYSTEM', '-', '-6.922522439207045', '107.73600556214177', 'http://192.168.7.250/hris-kinenta/assets/image/config/logo/1681469516.png', 'http://192.168.7.250/hris-kinenta/assets/image/config/favicon/1681469516.png', 'http://192.168.7.250/hris-kinenta/assets/image/config/login/1681469516.png', 'default', 'QR_pz2NGTl4q7W1JgbdHQVo3DOe80wIaunjytrsUKfkEXYPh', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `contracts`
--

CREATE TABLE `contracts` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `number` varchar(30) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `contracts`
--

INSERT INTO `contracts` (`id`, `created_by`, `created_date`, `updated_by`, `updated_date`, `deleted`, `number`, `name`, `description`, `status`) VALUES
('20221119000002', 'admin', '2022-11-19 09:27:06', NULL, NULL, 0, 'CTK-0003', 'CONTRACT 2', '', 0),
('20221119000003', 'admin', '2022-11-19 09:27:18', NULL, NULL, 0, 'CTK-0006', 'HARIAN LEPAS', '', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `corrections`
--

CREATE TABLE `corrections` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL,
  `employee_id` varchar(20) NOT NULL,
  `trans_date` date DEFAULT NULL,
  `correction_type` varchar(20) NOT NULL,
  `correction_name` varchar(30) NOT NULL DEFAULT 'CORRECTION',
  `amount` int(11) NOT NULL,
  `remarks` text NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cutoff`
--

CREATE TABLE `cutoff` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `start` date DEFAULT NULL,
  `finish` date DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `cutoff`
--

INSERT INTO `cutoff` (`id`, `created_by`, `created_date`, `updated_by`, `updated_date`, `deleted`, `start`, `finish`, `status`) VALUES
('20221229000001', 'admin', '2022-12-29 16:00:11', NULL, NULL, 0, '2022-11-16', '2022-12-15', 0),
('20230116000001', 'admin', '2023-01-16 10:47:07', NULL, NULL, 0, '2022-12-16', '2023-01-15', 0),
('20230210000001', 'admin', '2023-02-10 15:56:24', NULL, NULL, 0, '2023-01-16', '2023-02-15', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `deductions`
--

CREATE TABLE `deductions` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `number` varchar(30) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `amount` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `departements`
--

CREATE TABLE `departements` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `division_id` varchar(30) DEFAULT NULL,
  `number` varchar(30) DEFAULT NULL,
  `number_id` varchar(10) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `departements`
--

INSERT INTO `departements` (`id`, `created_by`, `created_date`, `updated_by`, `updated_date`, `deleted`, `division_id`, `number`, `number_id`, `name`, `description`, `status`) VALUES
('20221119000008', 'admin', '2022-11-19 09:36:08', NULL, NULL, 0, '20221119000002', 'DEPT-0009', NULL, 'ENGINEERING', '', 0),
('20221119000009', 'admin', '2022-11-19 09:36:34', 'admin', '2023-02-13 21:41:30', 0, '20221119000002', 'DEPT-0002', NULL, ' FINANCE ACC & COST CONTROL', '220', 0),
('20221119000010', 'admin', '2022-11-19 09:36:57', 'admin', '2023-02-13 21:41:38', 0, '20221119000002', 'DEPT-0003', NULL, 'HRD', '220', 0),
('20221119000011', 'admin', '2022-11-19 09:37:34', 'admin', '2023-02-13 21:41:50', 0, '20221119000002', 'DEPT-0004', NULL, 'IT', '220', 0),
('20221119000012', 'admin', '2022-11-19 09:37:50', NULL, NULL, 0, '20221119000002', 'DEPT-0019', NULL, 'MAINTENANCE', '', 0),
('20221119000013', 'admin', '2022-11-19 09:38:15', 'admin', '2023-02-13 21:41:23', 0, '20221119000002', 'DEPT-0001', NULL, 'OPERATION EXCELLENCE', '220', 0),
('20221119000014', 'admin', '2022-11-19 09:38:38', NULL, NULL, 0, '20221119000002', 'DEPT-0008', NULL, 'PRODUCTION DOMESTIC', '', 0),
('20221119000015', 'admin', '2022-11-19 09:39:03', NULL, NULL, 0, '20221119000002', 'DEPT-0007', NULL, ' PRODUCTION EXPORT', '', 0),
('20221119000017', 'admin', '2022-11-19 09:39:46', NULL, NULL, 0, '20221119000002', 'DEPT-0010', NULL, ' QUALITY MANAGEMENT', '', 0),
('20221119000018', 'admin', '2022-11-19 09:40:02', 'admin', '2023-02-13 21:42:21', 0, '20221119000002', 'DEPT-0006', NULL, 'SCM', '210', 0),
('20221119000019', 'admin', '2022-11-19 09:40:32', NULL, NULL, 0, '20221119000002', 'DEPT-0011', NULL, ' TEACHING FACTORY', '', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `departement_subs`
--

CREATE TABLE `departement_subs` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `division_id` varchar(30) DEFAULT NULL,
  `departement_id` varchar(30) DEFAULT NULL,
  `number` varchar(30) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `departement_subs`
--

INSERT INTO `departement_subs` (`id`, `created_by`, `created_date`, `updated_by`, `updated_date`, `deleted`, `division_id`, `departement_id`, `number`, `name`, `description`, `type`, `status`) VALUES
('20221119000016', 'admin', '2022-11-19 09:58:58', 'admin', '2022-11-19 12:46:24', 0, '20221119000002', '20221119000008', 'DEPTS-0051', ' PROCESS ENGINEERING', '', 'INDIRECT', 0),
('20221119000017', 'admin', '2022-11-19 09:59:24', 'admin', '2022-11-19 12:46:19', 0, '20221119000002', '20221119000008', 'DEPTS-0047', ' PRODUCT DEVELOPMENT', '', 'INDIRECT', 0),
('20221119000018', 'admin', '2022-11-19 10:00:07', 'admin', '2022-11-19 12:46:11', 0, '20221119000002', '20221119000009', 'DEPTS-0002', 'FINANCE ACC & COST CONTROL', '', 'INDIRECT', 0),
('20221119000019', 'admin', '2022-11-19 10:00:43', 'admin', '2022-11-19 12:45:33', 0, '20221119000002', '20221119000010', 'DEPTS-0050', ' HR DEVELOPMENT', '', 'INDIRECT', 0),
('20221119000020', 'admin', '2022-11-19 10:01:13', 'admin', '2022-11-19 12:45:29', 0, '20221119000002', '20221119000010', 'DEPTS-0048', ' HR OPERATIONAL', '', 'INDIRECT', 0),
('20221119000021', 'admin', '2022-11-19 10:01:51', 'admin', '2022-11-19 12:45:24', 0, '20221119000002', '20221119000010', 'DEPTS-0003', ' HR SERVICES', '', 'INDIRECT', 0),
('20221119000022', 'admin', '2022-11-19 10:02:13', 'admin', '2022-11-19 12:46:28', 0, '20221119000002', '20221119000011', 'DEPTS-0004', 'IT', '', 'INDIRECT', 0),
('20221119000023', 'admin', '2022-11-19 10:02:43', 'admin', '2022-11-19 12:46:34', 0, '20221119000002', '20221119000012', 'DEPTS-0075', ' TECHNICAL', '', 'INDIRECT', 0),
('20221119000024', 'admin', '2022-11-19 10:03:14', 'admin', '2022-11-19 12:46:39', 0, '20221119000002', '20221119000012', 'DEPTS-0076', ' UTILITY', '', 'INDIRECT', 0),
('20221119000025', 'admin', '2022-11-19 10:03:48', 'admin', '2022-11-19 12:46:43', 0, '20221119000002', '20221119000013', 'DEPTS-0001', ' OPERATION EXCELLENCE', '', 'INDIRECT', 0),
('20221119000026', 'admin', '2022-11-19 10:04:47', NULL, NULL, 0, '20221119000002', '20221119000014', 'DEPTS-0044', 'HAV-ASSEMBLING', '', 'DIRECT', 0),
('20221119000027', 'admin', '2022-11-19 10:05:12', NULL, NULL, 0, '20221119000002', '20221119000014', 'DEPTS-0043', ' HAV-HOUSING', '', 'DIRECT', 0),
('20221119000029', 'admin', '2022-11-19 10:05:57', NULL, NULL, 0, '20221119000002', '20221119000014', 'DEPTS-0046', ' PACKING DELIVERY', '', 'DIRECT', 0),
('20221119000030', 'admin', '2022-11-19 10:06:24', NULL, NULL, 0, '20221119000002', '20221119000014', 'DEPTS-0029', 'PP-ACCESSORIES', '', 'DIRECT', 0),
('20221119000031', 'admin', '2022-11-19 10:07:14', NULL, NULL, 0, '20221119000002', '20221119000014', 'DEPTS-0025', ' PP-CUTTING', '', 'DIRECT', 0),
('20221119000032', 'admin', '2022-11-19 10:07:42', NULL, NULL, 0, '20221119000002', '20221119000014', 'DEPTS-0035', 'PP-GULUNG CIRCUIT', '', 'DIRECT', 0),
('20221119000033', 'admin', '2022-11-19 10:08:56', NULL, NULL, 0, '20221119000002', '20221119000014', 'DEPTS-0031', ' PP-HF SEALER', '', 'DIRECT', 0),
('20221119000034', 'admin', '2022-11-19 10:09:17', NULL, NULL, 0, '20221119000002', '20221119000014', 'DEPTS-0039', ' PP-HOT STAMPING', '', 'DIRECT', 0),
('20221119000035', 'admin', '2022-11-19 10:09:49', NULL, NULL, 0, '20221119000002', '20221119000014', 'DEPTS-0034', ' PP-JOINT TAPPING', '', 'DIRECT', 0),
('20221119000036', 'admin', '2022-11-19 10:10:08', NULL, NULL, 0, '20221119000002', '20221119000014', 'DEPTS-0033', ' PP-JOINTING', '', 'DIRECT', 0),
('20221119000037', 'admin', '2022-11-19 10:10:30', NULL, NULL, 0, '20221119000002', '20221119000014', 'DEPTS-0037', 'PP-JST', '', 'DIRECT', 0),
('20221119000038', 'admin', '2022-11-19 10:10:53', NULL, NULL, 0, '20221119000002', '20221119000014', 'DEPTS-0027', 'PP-MANUAL CRIMPING', '', 'DIRECT', 0),
('20221119000039', 'admin', '2022-11-19 10:11:17', NULL, NULL, 0, '20221119000002', '20221119000014', 'DEPTS-0041', ' PP-MIDDLE INSPECTION', '', 'DIRECT', 0),
('20221119000040', 'admin', '2022-11-19 10:11:42', NULL, NULL, 0, '20221119000002', '20221119000014', 'DEPTS-0032', 'PP-MIDDLE STRIPPING', '', 'DIRECT', 0),
('20221119000041', 'admin', '2022-11-19 10:12:18', NULL, NULL, 0, '20221119000002', '20221119000014', 'DEPTS-0028', ' PP-SEMI AUTO CUTTING', '', 'DIRECT', 0),
('20221119000042', 'admin', '2022-11-19 10:12:39', NULL, NULL, 0, '20221119000002', '20221119000014', 'DEPTS-0040', ' PP-SOLDER', '', 'DIRECT', 0),
('20221119000043', 'admin', '2022-11-19 10:13:34', NULL, NULL, 0, '20221119000002', '20221119000014', 'DEPTS-0030', 'PP-SPOT WELDING', '', 'DIRECT', 0),
('20221119000044', 'admin', '2022-11-19 10:13:57', NULL, NULL, 0, '20221119000002', '20221119000014', 'DEPTS-0026', 'PP-STRIPPING', '', 'DIRECT', 0),
('20221119000045', 'admin', '2022-11-19 10:14:19', NULL, NULL, 0, '20221119000002', '20221119000014', 'DEPTS-0038', ' PP-TWIST', '', 'DIRECT', 0),
('20221119000046', 'admin', '2022-11-19 10:14:38', NULL, NULL, 0, '20221119000002', '20221119000014', 'DEPTS-0036', '  PP-WELDING JOINT', '', 'DIRECT', 0),
('20221119000047', 'admin', '2022-11-19 10:15:03', NULL, NULL, 0, '20221119000002', '20221119000014', 'DEPTS-0042', 'PP-WIRE COLLECTION', '', 'DIRECT', 0),
('20221119000048', 'admin', '2022-11-19 10:15:20', NULL, NULL, 0, '20221119000002', '20221119000014', 'DEPTS-0054', 'QC', '', 'DIRECT', 0),
('20221119000049', 'admin', '2022-11-19 10:15:59', NULL, NULL, 0, '20221119000002', '20221119000015', 'DEPTS-0073', 'ADMINISTRATION', '', 'ADMINISTRATION', 0),
('20221119000050', 'admin', '2022-11-19 10:17:19', NULL, NULL, 0, '20221119000002', '20221119000015', 'DEPTS-0023', 'HAV-ASSEMBLING', '', 'DIRECT', 0),
('20221119000051', 'admin', '2022-11-19 10:18:05', NULL, NULL, 0, '20221119000002', '20221119000015', 'DEPTS-0022', ' HAV-HOUSING', '', 'DIRECT', 0),
('20221119000052', 'admin', '2022-11-19 10:18:44', NULL, NULL, 0, '20221119000002', '20221119000015', 'DEPTS-0024', 'HAV-VISUAL', '', 'DIRECT', 0),
('20221119000053', 'admin', '2022-11-19 10:19:10', NULL, NULL, 0, '20221119000002', '20221119000015', 'DEPTS-0077', ' PACKING DELIVERY', '', 'INDIRECT', 0),
('20221119000054', 'admin', '2022-11-19 10:19:35', NULL, NULL, 0, '20221119000002', '20221119000015', 'DEPTS-0011', 'PP-ACCESSORIES', '', 'DIRECT', 0),
('20221119000055', 'admin', '2022-11-19 10:20:07', NULL, NULL, 0, '20221119000002', '20221119000015', 'DEPTS-0009', ' PP-ACCESSORIES INSERTION', '', 'DIRECT', 0),
('20221119000056', 'admin', '2022-11-19 10:20:28', NULL, NULL, 0, '20221119000002', '20221119000015', 'DEPTS-0018', ' PP-CASTING', '', 'DIRECT', 0),
('20221119000057', 'admin', '2022-11-19 10:20:50', NULL, NULL, 0, '20221119000002', '20221119000015', 'DEPTS-0008', 'PP-CUTTING', '', 'DIRECT', 0),
('20221119000058', 'admin', '2022-11-19 10:21:11', NULL, NULL, 0, '20221119000002', '20221119000015', 'DEPTS-0078', ' PP-GULUNG CIRCUIT', '', 'DIRECT', 0),
('20221119000059', 'admin', '2022-11-19 10:21:25', NULL, NULL, 0, '20221119000002', '20221119000015', 'DEPTS-0017', 'PP-HEAT GUN', '', 'DIRECT', 0),
('20221119000060', 'admin', '2022-11-19 10:21:43', NULL, NULL, 0, '20221119000002', '20221119000015', 'DEPTS-0014', 'PP-JOINT TAPPING', '', 'DIRECT', 0),
('20221119000061', 'admin', '2022-11-19 10:22:10', NULL, NULL, 0, '20221119000002', '20221119000015', 'DEPTS-0019', 'PP-JOINTING', '', 'DIRECT', 0),
('20221119000062', 'admin', '2022-11-19 10:22:37', NULL, NULL, 0, '20221119000002', '20221119000015', 'DEPTS-0010', ' PP-MANUAL CRIMPING', '', 'DIRECT', 0),
('20221119000063', 'admin', '2022-11-19 10:22:54', NULL, NULL, 0, '20221119000002', '20221119000015', 'DEPTS-0021', 'PP-MIDDLE INSPECTION', '', 'DIRECT', 0),
('20221119000064', 'admin', '2022-11-19 10:23:15', NULL, NULL, 0, '20221119000002', '20221119000015', 'DEPTS-0013', ' PP-MIDDLE STRIPPING', '', 'DIRECT', 0),
('20221119000065', 'admin', '2022-11-19 10:23:36', NULL, NULL, 0, '20221119000002', '20221119000015', 'DEPTS-0015', 'PP-OPEN SHIELD', '', 'DIRECT', 0),
('20221119000066', 'admin', '2022-11-19 10:24:04', NULL, NULL, 0, '20221119000002', '20221119000015', 'DEPTS-0016', ' PP-SOLDER', '', 'DIRECT', 0),
('20221119000067', 'admin', '2022-11-19 10:24:28', NULL, NULL, 0, '20221119000002', '20221119000015', 'DEPTS-0012', ' PP-TWIST', '', 'DIRECT', 0),
('20221119000068', 'admin', '2022-11-19 10:24:47', NULL, NULL, 0, '20221119000002', '20221119000015', 'DEPTS-0020', ' PP-WIRE COLLECTION', '', 'DIRECT', 0),
('20221119000071', 'admin', '2022-11-19 10:26:05', NULL, NULL, 0, '20221119000002', '20221119000018', 'DEPTS-0007', 'MCL', '', 'INDIRECT', 0),
('20221119000072', 'admin', '2022-11-19 10:26:30', NULL, NULL, 0, '20221119000002', '20221119000018', 'DEPTS-0006', 'PPIC', '', 'INDIRECT', 0),
('20221119000073', 'admin', '2022-11-19 10:26:55', NULL, NULL, 0, '20221119000002', '20221119000018', 'DEPTS-0049', ' WAREHOUSE', '', 'INDIRECT', 0),
('20221119000074', 'admin', '2022-11-19 10:27:53', NULL, NULL, 0, '20221119000002', '20221119000019', 'DEPTS-0055', 'TF MUHAMMADIYAH 1', '', 'DIRECT', 0),
('20221119000075', 'admin', '2022-11-19 10:28:44', NULL, NULL, 0, '20221119000002', '20221119000019', 'DEPTS-0056', 'TF TAZAKKA', '', 'DIRECT', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `divisions`
--

CREATE TABLE `divisions` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `number` varchar(30) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `divisions`
--

INSERT INTO `divisions` (`id`, `created_by`, `created_date`, `updated_by`, `updated_date`, `deleted`, `number`, `name`, `description`, `status`) VALUES
('20221119000002', 'admin', '2022-11-19 09:29:13', NULL, NULL, 0, 'DIV-0001', 'PT. KINENTA INDONESIA', '', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `documents`
--

CREATE TABLE `documents` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `type` varchar(30) DEFAULT NULL,
  `number` varchar(30) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `attachment` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `employees`
--

CREATE TABLE `employees` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL,
  `candidate_id` varchar(30) DEFAULT NULL,
  `division_id` varchar(30) DEFAULT NULL,
  `departement_id` varchar(30) DEFAULT NULL,
  `departement_sub_id` varchar(30) DEFAULT NULL,
  `contract_id` varchar(30) DEFAULT NULL,
  `position_id` varchar(30) DEFAULT NULL,
  `group_id` varchar(30) DEFAULT NULL,
  `source_id` varchar(30) DEFAULT NULL,
  `marital_id` varchar(30) DEFAULT NULL,
  `religion_id` varchar(30) DEFAULT NULL,
  `number` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `nickname` varchar(20) NOT NULL,
  `date_sign` date NOT NULL,
  `date_expired` date DEFAULT NULL,
  `address` text DEFAULT NULL,
  `place_birth` varchar(50) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `gender` enum('MALE','FEMALE') NOT NULL,
  `blood` varchar(2) DEFAULT NULL,
  `national_id` varchar(20) DEFAULT NULL,
  `tax_id` varchar(20) DEFAULT NULL,
  `jamsostek` varchar(20) NOT NULL,
  `jamsostek_date` date DEFAULT NULL,
  `jkn` varchar(30) DEFAULT NULL,
  `jkn_date` date DEFAULT NULL,
  `jkn_family` tinyint(1) NOT NULL DEFAULT 1,
  `telphone` varchar(20) DEFAULT NULL,
  `mobile_phone` varchar(20) DEFAULT NULL,
  `emergency_no` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `driving_no` varchar(30) DEFAULT NULL,
  `driving_date` date DEFAULT NULL,
  `stnk_no` varchar(30) DEFAULT NULL,
  `stnk_date` date DEFAULT NULL,
  `maps` text DEFAULT NULL,
  `image_id` text DEFAULT NULL,
  `image_profile` text DEFAULT NULL,
  `bank_name` varchar(30) DEFAULT NULL,
  `bank_no` varchar(30) DEFAULT NULL,
  `bank_branch` varchar(30) DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  `status_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `employee_candidates`
--

CREATE TABLE `employee_candidates` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL,
  `request_id` varchar(20) NOT NULL,
  `division_id` varchar(30) DEFAULT NULL,
  `departement_id` varchar(30) DEFAULT NULL,
  `departement_sub_id` varchar(30) DEFAULT NULL,
  `marital_id` varchar(30) DEFAULT NULL,
  `religion_id` varchar(30) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` text DEFAULT NULL,
  `mobile_phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `place_birth` varchar(50) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `blood` varchar(2) DEFAULT NULL,
  `national_id` varchar(20) DEFAULT NULL,
  `tax_id` varchar(20) DEFAULT NULL,
  `resume` text DEFAULT NULL,
  `certificate` text DEFAULT NULL,
  `health` text DEFAULT NULL,
  `image_id` text DEFAULT NULL,
  `image_profile` text DEFAULT NULL,
  `psycological` text DEFAULT NULL,
  `psycological_date` date DEFAULT NULL,
  `psycological_total` int(11) NOT NULL DEFAULT 0,
  `psycological_judgement` varchar(10) DEFAULT NULL,
  `interview` text DEFAULT NULL,
  `interview_date` date DEFAULT NULL,
  `interview_total` int(11) NOT NULL DEFAULT 0,
  `interview_judgement` varchar(10) DEFAULT NULL,
  `medical` text DEFAULT NULL,
  `medical_date` date DEFAULT NULL,
  `medical_total` int(11) NOT NULL DEFAULT 0,
  `medical_judgement` varchar(10) DEFAULT NULL,
  `skill` text DEFAULT NULL,
  `skill_date` date DEFAULT NULL,
  `skill_total` int(11) NOT NULL DEFAULT 0,
  `skill_judgement` varchar(10) DEFAULT NULL,
  `mail` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `employee_carrers`
--

CREATE TABLE `employee_carrers` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(30) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_by` varchar(30) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT 0,
  `number` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `start` date DEFAULT NULL,
  `profesion` varchar(30) DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `employee_educations`
--

CREATE TABLE `employee_educations` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(30) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_by` varchar(30) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT 0,
  `number` varchar(20) NOT NULL,
  `level` varchar(20) NOT NULL,
  `degree` varchar(10) DEFAULT NULL,
  `school` varchar(30) DEFAULT NULL,
  `start` date DEFAULT NULL,
  `end` date DEFAULT NULL,
  `qpa` varchar(10) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `employee_experiences`
--

CREATE TABLE `employee_experiences` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(30) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_by` varchar(30) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT 0,
  `number` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `type` varchar(30) DEFAULT NULL,
  `start` date DEFAULT NULL,
  `end` date DEFAULT NULL,
  `position` varchar(30) DEFAULT NULL,
  `salary` varchar(10) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `employee_familys`
--

CREATE TABLE `employee_familys` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(30) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_by` varchar(30) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT 0,
  `number` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `place` varchar(50) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `relation` varchar(20) DEFAULT NULL,
  `profesion` varchar(50) DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `employee_requests`
--

CREATE TABLE `employee_requests` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `division_id` varchar(30) DEFAULT NULL,
  `departement_id` varchar(30) DEFAULT NULL,
  `departement_sub_id` varchar(30) DEFAULT NULL,
  `request_by` varchar(30) DEFAULT NULL,
  `request_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `qty` tinyint(11) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `employee_trainings`
--

CREATE TABLE `employee_trainings` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(30) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_by` varchar(30) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT 0,
  `number` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `start` date DEFAULT NULL,
  `profesion` varchar(30) DEFAULT NULL,
  `contact` varchar(20) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `groups`
--

CREATE TABLE `groups` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `number` varchar(30) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `ppt` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `groups`
--

INSERT INTO `groups` (`id`, `created_by`, `created_date`, `updated_by`, `updated_date`, `deleted`, `number`, `name`, `description`, `ppt`, `status`) VALUES
('20221119000001', 'admin', '2022-11-19 10:38:07', NULL, NULL, 0, 'GRP-0002', ' CONTRACT', '', 0, 0),
('20221119000002', 'admin', '2022-11-19 10:38:22', 'admin', '2023-03-02 11:55:00', 0, 'GRP-0003', 'HARIAN LEPAS', '', 1, 0),
('20221119000003', 'admin', '2022-11-19 10:38:40', 'admin', '2023-03-02 11:55:51', 0, 'GRP-0004', 'MAGANG', '', 1, 0),
('20221119000005', 'admin', '2022-11-19 10:39:08', 'admin', '2023-03-02 11:55:57', 0, 'GRP-0005', 'PKL', '', 1, 0),
('20221119000006', 'admin', '2022-11-19 10:39:22', NULL, NULL, 0, 'GRP-0006', 'TRAINING', '', 0, 0),
('20221212000002', 'admin', '2022-12-12 14:48:25', NULL, NULL, 0, 'GRP-0008', 'MANAGEMENT', 'ASSISTANT MANAGER UP', 0, 0),
('20230117000001', 'admin', '2023-01-17 11:49:39', NULL, NULL, 0, 'GRP-0011', 'PERMANENT OPERATOR', 'OPERATOR', 0, 0),
('20230121000001', 'admin', '2023-01-21 12:35:45', NULL, NULL, 0, 'GRP-008', 'CONTRACT STAFF & LEADER', 'CONTRACT STAFF & LEADER', 0, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_types`
--

CREATE TABLE `job_types` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `employee_id` varchar(30) DEFAULT NULL,
  `job_type` varchar(10) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kinenta_jpk`
--

CREATE TABLE `kinenta_jpk` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `number` varchar(30) DEFAULT NULL,
  `amount` int(11) DEFAULT 0,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kinenta_jpk`
--

INSERT INTO `kinenta_jpk` (`id`, `created_by`, `created_date`, `updated_by`, `updated_date`, `deleted`, `number`, `amount`, `description`, `status`) VALUES
('20230226000001', 'admin', '2023-02-26 20:41:54', NULL, NULL, 0, 'MAGANG', 32000, '', 0),
('20230226000002', 'admin', '2023-02-26 20:41:57', NULL, NULL, 0, 'TK/0', 32000, '', 0),
('20230226000003', 'admin', '2023-02-26 20:42:02', 'admin', '2023-02-26 20:42:14', 0, 'K/0', 54000, '', 0),
('20230322000001', 'admin', '2023-03-22 09:42:50', NULL, NULL, 0, 'PKL', 32000, 'PKL', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `loans`
--

CREATE TABLE `loans` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL,
  `employee_id` varchar(20) NOT NULL,
  `trans_date` date DEFAULT NULL,
  `loan_type` enum('BANK','COOPERATIVE','OTHER') NOT NULL,
  `amount` int(11) NOT NULL,
  `remarks` text NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `logins`
--

CREATE TABLE `logins` (
  `id` bigint(30) NOT NULL,
  `created_by` varchar(30) DEFAULT NULL,
  `created_date` datetime NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  `ip_address` varchar(30) NOT NULL,
  `mac_address` varchar(30) NOT NULL,
  `username` varchar(30) NOT NULL,
  `description` text NOT NULL,
  `status` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `logs`
--

CREATE TABLE `logs` (
  `id` bigint(30) NOT NULL,
  `created_by` varchar(30) DEFAULT NULL,
  `created_date` datetime NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  `ip_address` varchar(30) NOT NULL,
  `action` varchar(30) NOT NULL,
  `menu` varchar(30) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `maritals`
--

CREATE TABLE `maritals` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `number` varchar(30) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `maritals`
--

INSERT INTO `maritals` (`id`, `created_by`, `created_date`, `updated_by`, `updated_date`, `deleted`, `number`, `name`, `description`, `status`) VALUES
('20221119000001', 'admin', '2022-11-19 10:44:16', 'admin', '2022-11-19 12:35:10', 0, 'K/1', 'Kawin 1 Tanggungan', '', 0),
('20221119000002', 'admin', '2022-11-19 10:44:37', 'admin', '2022-11-19 12:35:16', 0, 'K/2', 'Kawin 2 Tanggungan', '', 0),
('20221119000003', 'admin', '2022-11-19 10:44:47', 'admin', '2022-11-19 12:35:22', 0, 'K/3', 'Kawin 3 Tanggungan', '', 0),
('20221119000004', 'admin', '2022-11-19 10:45:14', 'admin', '2022-11-19 12:35:31', 0, 'K/0', 'Kawin Tidak Ada Tanggungan', '', 0),
('20221119000005', 'admin', '2022-11-19 10:45:36', 'admin', '2022-11-19 12:35:43', 0, 'TK/1', 'Lajang 1 Tanggungan', '', 0),
('20221119000006', 'admin', '2022-11-19 10:45:48', 'admin', '2022-11-19 12:35:52', 0, 'TK/2', 'Lajang 2 Tanggungan', '', 0),
('20221119000007', 'admin', '2022-11-19 10:46:08', 'admin', '2022-11-19 12:36:01', 0, 'TK/3', 'Lajang 3 Tanggungan', '', 0),
('20221119000008', 'admin', '2022-11-19 10:46:35', 'admin', '2022-11-19 12:36:11', 0, 'TK/0', 'Lajang Tidak Ada Tanggungan', '', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `menus`
--

CREATE TABLE `menus` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL,
  `menus_id` varchar(30) NOT NULL,
  `number` varchar(20) DEFAULT NULL,
  `name` varchar(30) NOT NULL,
  `description` text DEFAULT NULL,
  `link` text NOT NULL,
  `sort` int(11) NOT NULL,
  `icon` varchar(30) NOT NULL,
  `flag` varchar(10) DEFAULT NULL,
  `color` varchar(10) DEFAULT NULL,
  `state` varchar(10) DEFAULT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `menus`
--

INSERT INTO `menus` (`id`, `created_by`, `created_date`, `updated_by`, `updated_date`, `deleted`, `menus_id`, `number`, `name`, `description`, `link`, `sort`, `icon`, `flag`, `color`, `state`, `status`) VALUES
('20221120000001', 'admin', '2022-11-20 09:57:14', NULL, NULL, 0, '3e6189e47bad4f7b9fd9d494de67a5', NULL, 'Requirements', NULL, 'requitment/requirements', 1, '', NULL, NULL, '', 0),
('20221120000002', 'admin', '2022-11-20 09:58:24', 'admin', '2022-11-22 15:17:40', 0, '3e6189e47bad4f7b9fd9d494de67a5', NULL, 'Requests', NULL, 'requitment/requests', 3, '', NULL, NULL, '', 0),
('20221120000003', 'admin', '2022-11-20 09:58:55', NULL, NULL, 0, '3e6189e47bad4f7b9fd9d494de67a5', NULL, 'Candidates', NULL, 'requitment/candidates', 3, '', NULL, NULL, '', 0),
('20221120000004', 'admin', '2022-11-20 10:00:08', 'admin', '2022-11-20 10:03:13', 0, '3e6189e47bad4f7b9fd9d494de67a5', NULL, 'Documents', NULL, 'requitment/documents', 2, '', NULL, NULL, '', 0),
('20221124000001', 'admin', '2022-11-24 22:49:36', NULL, NULL, 0, '3c65f38ca10c4f03a908f6ef813434', NULL, 'Shifts', NULL, 'attandance/shifts', 1, '', NULL, NULL, '', 0),
('20221124000002', 'admin', '2022-11-24 22:49:46', NULL, NULL, 0, '3c65f38ca10c4f03a908f6ef813434', NULL, 'Shift Details', NULL, 'attandance/shift_details', 2, '', NULL, NULL, '', 0),
('20221124000003', 'admin', '2022-11-24 23:27:45', 'admin', '2022-11-24 23:31:12', 0, '3c65f38ca10c4f03a908f6ef813434', NULL, 'Permit Types', NULL, 'attandance/permit_types', 3, '', NULL, NULL, '', 0),
('20221124000004', 'admin', '2022-11-24 23:28:13', NULL, NULL, 0, '3c65f38ca10c4f03a908f6ef813434', NULL, 'Reasons', NULL, 'attandance/reasons', 4, '', NULL, NULL, '', 0),
('20221124000005', 'admin', '2022-11-24 23:29:26', 'admin', '2022-11-24 23:53:56', 0, '3c65f38ca10c4f03a908f6ef813434', NULL, 'OT Budget Dept', NULL, 'attandance/overtime_budget_dept', 6, '', NULL, NULL, '', 0),
('20221124000006', 'admin', '2022-11-24 23:29:41', 'admin', '2022-11-24 23:54:01', 0, '3c65f38ca10c4f03a908f6ef813434', NULL, 'OT Budget Emp', NULL, 'attandance/overtime_budget_emp', 7, '', NULL, NULL, '', 0),
('20221124000007', 'admin', '2022-11-24 23:54:30', 'admin', '2022-11-25 15:07:04', 0, '3c65f38ca10c4f03a908f6ef813434', NULL, 'Working Calendar', NULL, 'attandance/calendars', 5, '', NULL, NULL, '', 0),
('20221125000001', 'admin', '2022-11-25 15:31:38', 'admin', '2023-07-03 10:10:12', 0, '7ea3a784a2e84b69a890da966a12f0', NULL, 'Shift Employees', NULL, 'attandance/shift_employees', 1, 'icon-schedule', NULL, NULL, '', 0),
('20221125000002', 'admin', '2022-11-25 15:32:02', 'admin', '2022-12-31 18:35:15', 0, '7ea3a784a2e84b69a890da966a12f0', NULL, 'Attandances', NULL, 'attandance/attandances', 2, 'icon-schedule', NULL, NULL, '', 0),
('20221125000003', 'admin', '2022-11-25 15:32:14', 'admin', '2022-12-31 18:35:42', 0, '7ea3a784a2e84b69a890da966a12f0', NULL, 'Permits', NULL, 'attandance/permits', 6, 'icon-schedule', NULL, NULL, '', 0),
('20221125000004', 'admin', '2022-11-25 15:33:00', 'admin', '2022-12-31 18:35:33', 0, '7ea3a784a2e84b69a890da966a12f0', NULL, 'Cash Carry', NULL, 'attandance/cash_carries', 4, 'icon-clock', NULL, NULL, '', 0),
('20221126000001', 'admin', '2022-11-26 05:23:51', 'admin', '2022-12-31 18:35:23', 0, '7ea3a784a2e84b69a890da966a12f0', NULL, 'Overtimes', NULL, 'attandance/overtimes', 3, 'icon-clock', NULL, NULL, '', 0),
('20221129000001', 'admin', '2022-11-29 17:14:16', 'admin', '2023-07-03 10:10:37', 0, 'a2cba087f116454ab7070d4bd72f15', NULL, 'Attandance Reports', NULL, 'report/attandances', 1, '', NULL, NULL, '', 0),
('20221129000002', 'admin', '2022-11-29 17:15:45', 'admin', '2022-12-07 14:05:24', 0, 'a2cba087f116454ab7070d4bd72f15', NULL, 'Attandance Summary', NULL, 'report/attandance_summary', 2, '', NULL, NULL, '', 0),
('20221129000003', 'admin', '2022-11-29 17:16:48', NULL, NULL, 0, 'a2cba087f116454ab7070d4bd72f15', NULL, 'Leaves', NULL, 'report/leaves', 3, '', NULL, NULL, '', 0),
('20221129000005', 'admin', '2022-11-29 17:18:14', 'admin', '2023-07-03 10:16:45', 0, '7bd22debdbb44893ac9a1a0eca1e3f', NULL, 'Cash Carries', NULL, 'report/cash_carries', 8, '', NULL, NULL, '', 0),
('20221202000001', 'admin', '2022-12-02 16:57:10', NULL, NULL, 0, '8998e43f7a834ea7800518a26986b7', NULL, 'BPJS', NULL, 'payroll/bpjs', 1, '', NULL, NULL, '', 0),
('20221202000002', 'admin', '2022-12-02 16:57:34', NULL, NULL, 0, '8998e43f7a834ea7800518a26986b7', NULL, 'PTKP', NULL, 'payroll/ptkp', 2, '', NULL, NULL, '', 0),
('20221202000003', 'admin', '2022-12-02 16:58:26', 'admin', '2022-12-02 17:01:00', 0, '8998e43f7a834ea7800518a26986b7', NULL, 'Component', NULL, 'payroll/components', 3, '', NULL, NULL, '', 0),
('20221202000004', 'admin', '2022-12-02 16:59:11', 'admin', '2022-12-02 17:42:12', 0, '8998e43f7a834ea7800518a26986b7', NULL, 'Allowances', NULL, 'payroll/allowances', 4, '', NULL, NULL, '', 0),
('20221202000005', 'admin', '2022-12-02 16:59:29', 'admin', '2023-01-11 23:43:46', 0, '8998e43f7a834ea7800518a26986b7', NULL, 'Deductions', NULL, 'payroll/deductions', 7, '', NULL, NULL, '', 0),
('20221202000006', 'admin', '2022-12-02 18:10:44', 'admin', '2022-12-31 18:37:35', 0, '9f956951158443299d153f439242ee', NULL, 'Setup Allowances', NULL, 'payroll/setup_allowances', 1, 'icon-payment', NULL, NULL, '', 0),
('20221202000007', 'admin', '2022-12-02 18:11:11', 'admin', '2022-12-31 18:37:40', 0, '9f956951158443299d153f439242ee', NULL, 'Setup Deductions', NULL, 'payroll/setup_deductions', 2, 'icon-payment', NULL, NULL, '', 0),
('20221202000008', 'admin', '2022-12-02 18:11:22', 'admin', '2023-05-17 13:32:36', 0, '9f956951158443299d153f439242ee', NULL, 'Setup Payroll', NULL, 'payroll/setup_salaries', 3, 'icon-payment', NULL, NULL, '', 0),
('20221202000009', 'admin', '2022-12-02 18:12:13', 'admin', '2022-12-31 18:36:40', 0, '9f956951158443299d153f439242ee', NULL, 'Generate Payroll', NULL, 'payroll/payrolls', 6, 'icon-money', NULL, NULL, '', 0),
('20221202000010', 'admin', '2022-12-02 18:12:48', 'admin', '2022-12-31 18:38:43', 0, '9f956951158443299d153f439242ee', NULL, 'Corrections', NULL, 'payroll/corrections', 4, 'icon-payment', NULL, NULL, '', 0),
('20221206000001', 'admin', '2022-12-06 03:35:00', 'admin', '2022-12-31 18:35:51', 0, '7ea3a784a2e84b69a890da966a12f0', NULL, 'Change Days', NULL, 'attandance/change_days', 7, 'icon-schedule', NULL, NULL, '', 0),
('20221206000002', 'admin', '2022-12-06 07:59:53', 'admin', '2022-12-31 18:38:51', 0, '9f956951158443299d153f439242ee', NULL, 'Loans', NULL, 'payroll/loans', 5, 'icon-payment', NULL, NULL, '', 0),
('20221207000001', 'admin', '2022-12-07 13:49:15', NULL, NULL, 0, '7bd22debdbb44893ac9a1a0eca1e3f', NULL, 'Payroll Summary', NULL, 'report/summary_payrolls', 1, '', NULL, NULL, '', 0),
('20221207000002', 'admin', '2022-12-07 13:49:43', 'admin', '2023-07-03 10:15:39', 0, '7bd22debdbb44893ac9a1a0eca1e3f', NULL, 'Salary Employees', NULL, 'report/employee_salaries', 5, '', NULL, NULL, '', 0),
('20221207000003', 'admin', '2022-12-07 13:50:01', 'admin', '2023-07-03 10:16:17', 0, '7bd22debdbb44893ac9a1a0eca1e3f', NULL, 'BPJS Resume', NULL, 'report/bpjs', 7, '', NULL, NULL, '', 0),
('20221207000004', 'admin', '2022-12-07 13:50:35', 'admin', '2023-07-03 10:15:48', 0, '7bd22debdbb44893ac9a1a0eca1e3f', NULL, 'Salary Slips', NULL, 'report/salary_slips', 6, '', NULL, NULL, '', 0),
('20221211000001', 'admin', '2022-12-11 22:17:35', 'admin', '2022-12-31 18:30:23', 0, 'cf98f97766f6405590b26daa586e00', NULL, 'Privilege Groups', NULL, 'admin/privilege_groups', 7, 'icon-key', NULL, NULL, '', 0),
('20221215000001', 'admin', '2022-12-15 00:08:33', 'admin', '2023-07-03 10:08:13', 0, 'b3a3f809a4b64f76b5a163ee11ff9f', NULL, 'Sources', NULL, 'employee/sources', 7, 'icon-people', NULL, NULL, '', 0),
('20221215000002', 'admin', '2022-12-15 00:09:22', 'admin', '2023-07-03 10:11:29', 0, '8998e43f7a834ea7800518a26986b7', NULL, 'Allowance Students', NULL, 'payroll/allowance_students', 5, '', NULL, NULL, '', 0),
('20221215000003', 'admin', '2022-12-15 00:10:17', 'admin', '2023-07-03 10:14:06', 0, '7bd22debdbb44893ac9a1a0eca1e3f', NULL, 'Payroll Students', NULL, 'report/student_payrolls', 2, '', NULL, NULL, '', 0),
('20221215000004', 'admin', '2022-12-15 20:42:12', NULL, NULL, 0, 'a2cba087f116454ab7070d4bd72f15', NULL, 'Attandance Days', NULL, 'report/attandance_days', 2, '', NULL, NULL, '', 0),
('20221216000001', 'admin', '2022-12-16 23:27:45', 'admin', '2022-12-31 18:31:51', 0, '29a3b810cf99401fbdbfb88c85fc8c', NULL, 'Banks', NULL, 'employee/banks', 4, 'icon-bank', NULL, NULL, '', 0),
('20221217000001', 'admin', '2022-12-17 23:22:28', 'admin', '2022-12-31 18:30:28', 0, 'cf98f97766f6405590b26daa586e00', NULL, 'Privilege Positions', NULL, 'admin/privilege_positions', 8, 'icon-key', NULL, NULL, '', 0),
('20221229000001', 'admin', '2022-12-29 15:48:15', 'admin', '2023-01-11 23:43:53', 0, '8998e43f7a834ea7800518a26986b7', NULL, 'Cut Off', NULL, 'payroll/cutoff', 8, '', NULL, NULL, '', 0),
('20221231000001', 'admin', '2022-12-31 15:00:16', 'admin', '2023-01-11 06:55:07', 0, '9379ea6a0c0048589eb20c5ad2586f', NULL, 'View Employee Lists', NULL, 'report/employees', 1, '', NULL, NULL, '', 0),
('20230105000001', 'admin', '2023-01-05 06:23:08', 'admin', '2023-07-03 10:12:12', 0, '8998e43f7a834ea7800518a26986b7', NULL, 'Allowance Cash Carries', NULL, 'payroll/allowance_cash_carries', 6, '', NULL, NULL, '', 0),
('20230122000001', 'admin', '2023-01-22 23:58:03', 'admin', '2023-07-03 10:14:14', 0, '7bd22debdbb44893ac9a1a0eca1e3f', NULL, 'Payroll Official', NULL, 'report/official_payrolls', 3, '', NULL, NULL, '', 0),
('20230123000001', 'admin', '2023-01-23 21:06:36', 'admin', '2023-07-03 10:16:57', 0, '7bd22debdbb44893ac9a1a0eca1e3f', NULL, 'Sourcing Fee', NULL, 'report/sourcing_fee', 9, '', NULL, NULL, '', 0),
('20230207000001', 'admin', '2023-02-07 19:02:59', 'admin', '2023-02-20 15:31:40', 0, '', NULL, 'Industrial Relation', NULL, '', 7, '', NULL, NULL, 'closed', 0),
('20230207000002', 'admin', '2023-02-07 19:04:18', 'admin', '2023-04-30 19:34:17', 0, '20230430000002', NULL, 'Resignations', NULL, 'relation/resignations', 2, '', NULL, NULL, '', 0),
('20230207000003', 'admin', '2023-02-07 19:09:56', 'admin', '2023-04-30 19:33:46', 0, '20230430000002', NULL, 'Warning Letter ', NULL, 'relation/warning_letters', 1, '', NULL, NULL, '', 0),
('20230207000004', 'admin', '2023-02-07 19:24:54', 'admin', '2023-04-30 19:34:38', 0, '20230430000002', NULL, 'Coaching & Counseling ', NULL, 'relation/coachings', 3, '', NULL, NULL, '', 0),
('20230226000001', 'admin', '2023-02-26 19:42:17', NULL, NULL, 0, '8998e43f7a834ea7800518a26986b7', NULL, 'JPK RSHB', NULL, 'payroll/kinenta_jpk', 9, '', NULL, NULL, '', 0),
('20230226000002', 'admin', '2023-02-26 19:47:19', NULL, NULL, 0, '7bd22debdbb44893ac9a1a0eca1e3f', NULL, 'JPK RSBH', NULL, 'report/kinenta_jpk', 9, '', NULL, NULL, '', 0),
('20230301000001', 'admin', '2023-03-01 00:51:02', NULL, NULL, 0, '7bd22debdbb44893ac9a1a0eca1e3f', NULL, 'PPH 21 PPT', NULL, 'report/pph_21', 10, '', NULL, NULL, '', 0),
('20230325000001', 'admin', '2023-03-25 21:32:47', NULL, NULL, 0, '9f956951158443299d153f439242ee', NULL, 'Generate Payroll PKL', NULL, 'payroll/payroll_pkl', 7, 'icon-money', NULL, NULL, '', 0),
('20230331000001', 'admin', '2023-03-31 21:30:19', NULL, NULL, 0, '9f956951158443299d153f439242ee', NULL, 'Generate THR', NULL, 'payroll/thr', 7, 'icon-money', NULL, NULL, '', 0),
('20230430000001', 'admin', '2023-04-30 19:33:12', NULL, NULL, 0, '20230207000001', NULL, 'Master Data', NULL, '', 1, '', NULL, NULL, 'closed', 0),
('20230430000002', 'admin', '2023-04-30 19:33:18', NULL, NULL, 0, '20230207000001', NULL, 'Transaction', NULL, '', 1, '', NULL, NULL, 'closed', 0),
('20230430000003', 'admin', '2023-04-30 19:33:23', 'admin', '2023-05-01 10:07:43', 0, '20230207000001', NULL, 'Reports', NULL, '', 1, '', NULL, NULL, '', 0),
('20230430000004', 'admin', '2023-04-30 19:35:08', 'admin', '2023-05-01 07:23:34', 0, '20230430000001', NULL, 'Reasons of Resign', NULL, 'relation/reasons', 1, '', NULL, NULL, '', 0),
('20230430000005', 'admin', '2023-04-30 19:35:18', NULL, NULL, 0, '20230430000001', NULL, 'Violations', NULL, 'relation/violations', 1, '', NULL, NULL, '', 0),
('20230520000001', 'admin', '2023-05-20 01:21:14', NULL, NULL, 0, '9f956951158443299d153f439242ee', NULL, 'Setup Cash Carries', NULL, 'payroll/setup_cash_carries', 3, 'icon-payment', NULL, NULL, '', 0),
('20230703000001', 'admin', '2023-07-03 10:12:51', 'admin', '2023-07-05 15:15:06', 0, '8998e43f7a834ea7800518a26986b7', NULL, 'COA', NULL, 'payroll/accounts', 10, '', NULL, NULL, '', 0),
('20230703000002', 'admin', '2023-07-03 10:13:11', 'admin', '2023-07-05 11:11:48', 0, '8998e43f7a834ea7800518a26986b7', NULL, 'Setup COA', NULL, 'payroll/account_coa', 11, '', NULL, NULL, '', 0),
('20230703000003', 'admin', '2023-07-03 10:15:03', NULL, NULL, 0, '7bd22debdbb44893ac9a1a0eca1e3f', NULL, 'Payroll Records', NULL, 'report/payroll_records', 4, '', NULL, NULL, '', 0),
('29a3b810cf99401fbdbfb88c85fc8c', 'admin', '2022-11-18 15:16:09', NULL, NULL, 0, 'cf3754f95019423a91dd08f121d59b', NULL, 'Transaction', NULL, '', 2, '', NULL, NULL, 'closed', 0),
('2dffbcb5de604cfeabc36ca26ac2ef', 'admin', '2022-11-18 15:21:01', 'admin', '2023-07-03 10:07:18', 0, 'b3a3f809a4b64f76b5a163ee11ff9f', NULL, 'Employee Types', NULL, 'employee/contracts', 4, 'icon-people', NULL, NULL, '', 0),
('3b6f413a07674016a7bd971ea3d8f1', 'admin', '2022-11-18 15:21:10', 'admin', '2023-07-03 10:07:31', 0, 'b3a3f809a4b64f76b5a163ee11ff9f', NULL, 'Divisions', NULL, 'employee/divisions', 1, 'icon-company', NULL, NULL, '', 0),
('3c65f38ca10c4f03a908f6ef813434', 'admin', '2022-11-18 15:15:33', NULL, NULL, 0, 'fe33d31953e5419d8c8d083163d1d3', NULL, 'Master Data', NULL, '', 1, '', NULL, NULL, 'closed', 0),
('3e6189e47bad4f7b9fd9d494de67a5', 'admin', '2022-11-18 15:13:29', 'admin', '2022-11-18 15:14:53', 0, '', NULL, 'Reqruitments', NULL, '', 3, '', NULL, NULL, 'closed', 0),
('3f328f5162194699b1b56b6bbdc388', 'admin', '2022-11-18 15:26:20', 'admin', '2023-07-03 10:08:33', 0, 'b3a3f809a4b64f76b5a163ee11ff9f', NULL, 'Religions', NULL, 'employee/religions', 8, '', NULL, NULL, '', 0),
('44964312f0264429978158ada88843', 'admin', '2022-09-29 16:12:08', 'admin', '2022-12-31 18:29:43', 0, 'cf98f97766f6405590b26daa586e00', NULL, 'Users', NULL, 'admin/users', 2, 'icon-user', NULL, NULL, '', 0),
('6ccd20c54d1d415189120ec5cc6c81', 'admin', '2022-09-29 16:41:40', 'admin', '2022-12-31 18:30:37', 0, 'cf98f97766f6405590b26daa586e00', NULL, 'Config', NULL, 'admin/config', 9, 'icon-setting', NULL, NULL, '', 0),
('71526252fe5447089abfb8f78ea088', 'admin', '2022-11-18 15:21:27', 'admin', '2023-07-03 10:07:43', 0, 'b3a3f809a4b64f76b5a163ee11ff9f', NULL, 'Sub Departments', NULL, 'employee/departement_subs', 3, 'icon-company', NULL, NULL, '', 0),
('7bd22debdbb44893ac9a1a0eca1e3f', 'admin', '2022-11-18 15:16:36', NULL, NULL, 0, 'bfa6813659bb4f4b9b6e813d747321', NULL, 'Reports', NULL, '', 3, '', NULL, NULL, 'closed', 0),
('7ea3a784a2e84b69a890da966a12f0', 'admin', '2022-11-18 15:16:07', NULL, NULL, 0, 'fe33d31953e5419d8c8d083163d1d3', NULL, 'Transaction', NULL, '', 2, '', NULL, NULL, 'closed', 0),
('8753a1bac53746dab9cce4754c716b', 'admin', '2022-11-18 17:18:42', 'admin', '2022-12-31 18:31:40', 0, '29a3b810cf99401fbdbfb88c85fc8c', NULL, 'Mutations', NULL, 'employee/mutations', 3, 'icon-worker', NULL, NULL, '', 0),
('8998e43f7a834ea7800518a26986b7', 'admin', '2022-11-18 15:15:38', NULL, NULL, 0, 'bfa6813659bb4f4b9b6e813d747321', NULL, 'Master Data', NULL, '', 1, '', NULL, NULL, 'closed', 0),
('9379ea6a0c0048589eb20c5ad2586f', 'admin', '2022-11-18 15:16:33', 'admin', '2022-12-31 14:59:57', 0, 'cf3754f95019423a91dd08f121d59b', NULL, 'Reports', NULL, '', 3, '', NULL, NULL, 'closed', 0),
('9889164c42ed410ea75819c0379aba', 'admin', '2022-11-18 15:26:31', 'admin', '2023-07-03 10:08:04', 0, 'b3a3f809a4b64f76b5a163ee11ff9f', NULL, 'Groups', NULL, 'employee/groups', 6, 'icon-people', NULL, NULL, '', 0),
('9f956951158443299d153f439242ee', 'admin', '2022-11-18 15:16:15', NULL, NULL, 0, 'bfa6813659bb4f4b9b6e813d747321', NULL, 'Transaction', NULL, '', 2, '', NULL, NULL, 'closed', 0),
('a2cba087f116454ab7070d4bd72f15', 'admin', '2022-11-18 15:16:31', NULL, NULL, 0, 'fe33d31953e5419d8c8d083163d1d3', NULL, 'Reports', NULL, '', 3, '', NULL, NULL, 'closed', 0),
('a32396bb412847c3b24dbd6e6bc8aa', 'admin', '2022-11-18 15:26:41', 'admin', '2023-07-03 10:08:43', 0, 'b3a3f809a4b64f76b5a163ee11ff9f', NULL, 'Maritals', NULL, 'employee/maritals', 9, '', NULL, NULL, '', 0),
('a391d47a7bce4f02aff4e45a2648b7', 'admin', '2022-11-18 17:18:34', 'admin', '2022-12-31 18:31:31', 0, '29a3b810cf99401fbdbfb88c85fc8c', NULL, 'Agreements', NULL, 'employee/agreements', 2, 'icon-worker', NULL, NULL, '', 0),
('a46529bba2204f8fa09c6351ab6a04', 'admin', '2022-11-18 17:17:14', 'admin', '2022-12-31 18:31:23', 0, '29a3b810cf99401fbdbfb88c85fc8c', NULL, 'Employee Lists', NULL, 'employee/employees', 1, 'icon-employee', NULL, NULL, '', 0),
('b3a3f809a4b64f76b5a163ee11ff9f', 'admin', '2022-11-18 15:15:26', NULL, NULL, 0, 'cf3754f95019423a91dd08f121d59b', NULL, 'Master Data', NULL, '', 1, '', NULL, NULL, 'closed', 0),
('b679033b3256414b8f916c69f17674', 'admin', '2022-09-29 16:22:01', 'admin', '2022-12-31 18:29:35', 0, 'cf98f97766f6405590b26daa586e00', NULL, 'Approval', NULL, 'admin/approvals', 1, 'icon-ok', NULL, NULL, '', 0),
('bfa6813659bb4f4b9b6e813d747321', 'admin', '2022-11-18 15:13:41', 'admin', '2022-11-18 15:14:59', 0, '', NULL, 'Payrolls', NULL, '', 5, '', NULL, NULL, 'closed', 0),
('c8f8362a5f6c432ab27d37213f15d4', 'admin', '2022-09-29 16:35:49', 'admin', '2022-12-31 18:30:06', 0, 'cf98f97766f6405590b26daa586e00', NULL, 'Setting Users', NULL, 'admin/setting_users', 6, 'icon-setting', NULL, NULL, '', 0),
('cbb12dde2fdb4e34bad9a31972dbad', 'admin', '2022-11-18 15:25:49', 'admin', '2022-12-31 18:33:06', 0, 'b3a3f809a4b64f76b5a163ee11ff9f', NULL, 'Positions', NULL, 'employee/positions', 5, 'icon-people', NULL, NULL, '', 0),
('cf3754f95019423a91dd08f121d59b', 'admin', '2022-11-18 15:13:04', 'admin', '2022-11-18 15:14:50', 0, '', NULL, 'Employees', NULL, '', 2, '', NULL, NULL, 'closed', 0),
('cf98f97766f6405590b26daa586e00', 'admin', '2022-09-29 16:05:52', NULL, NULL, 0, '', NULL, 'Administrator', NULL, '', 1, '', NULL, NULL, 'closed', 0),
('d13439e3f2324450a69b4e0e50159a', 'admin', '2022-09-29 16:15:42', 'admin', '2022-09-29 16:36:50', 0, 'cf98f97766f6405590b26daa586e00', NULL, 'Menu', NULL, 'admin/menus', 3, '', NULL, NULL, '', 0),
('de3f6855009e49deb7fd2fdd0f3b3d', 'admin', '2022-09-29 16:32:23', 'admin', '2022-12-31 18:29:54', 0, 'cf98f97766f6405590b26daa586e00', NULL, 'Logs', NULL, 'admin/logs', 4, 'icon-help', NULL, NULL, '', 0),
('e3c31e10b6c64e119b068ae4b73be6', 'admin', '2022-09-29 16:35:33', 'admin', '2022-12-31 18:30:01', 0, 'cf98f97766f6405590b26daa586e00', NULL, 'Setting Menu', NULL, 'admin/setting_menus', 5, 'icon-setting', NULL, NULL, '', 0),
('e63d10bdf94e493ca86430645e8a54', 'admin', '2022-11-18 15:22:34', 'admin', '2023-07-03 10:07:39', 0, 'b3a3f809a4b64f76b5a163ee11ff9f', NULL, 'Departements', NULL, 'employee/departements', 2, 'icon-company', NULL, NULL, '', 0),
('fe33d31953e5419d8c8d083163d1d3', 'admin', '2022-11-18 15:13:35', 'admin', '2022-11-18 15:14:56', 0, '', NULL, 'Attandances', NULL, '', 4, '', NULL, NULL, 'closed', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `mutations`
--

CREATE TABLE `mutations` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT 0,
  `approved_to` varchar(30) DEFAULT NULL,
  `approved_by` varchar(30) DEFAULT NULL,
  `approved_date` timestamp NULL DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `division_id` varchar(30) DEFAULT NULL,
  `departement_id` varchar(30) DEFAULT NULL,
  `departement_sub_id` varchar(30) DEFAULT NULL,
  `employee_id` varchar(30) DEFAULT NULL,
  `trans_date` date DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifications`
--

CREATE TABLE `notifications` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(30) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_by` varchar(30) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `approvals_id` varchar(30) DEFAULT NULL,
  `users_id_from` varchar(30) DEFAULT NULL,
  `users_id_to` varchar(30) DEFAULT NULL,
  `table_id` varchar(30) DEFAULT NULL,
  `table_name` varchar(50) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `notifications_m`
--

CREATE TABLE `notifications_m` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(30) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_by` varchar(30) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `number` varchar(30) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `overtimes`
--

CREATE TABLE `overtimes` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT 0,
  `approved_to` varchar(30) DEFAULT NULL,
  `approved_by` varchar(30) DEFAULT NULL,
  `approved_date` timestamp NULL DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL,
  `employee_id` varchar(30) NOT NULL,
  `trans_date` date NOT NULL,
  `request_code` varchar(50) NOT NULL,
  `start` time DEFAULT NULL,
  `end` time DEFAULT NULL,
  `type` enum('REGULAR','CORRECTION') NOT NULL,
  `duration` varchar(30) DEFAULT NULL,
  `duration_hour` int(11) NOT NULL DEFAULT 0,
  `duration_convert` int(11) NOT NULL DEFAULT 0,
  `amount` int(11) NOT NULL DEFAULT 0,
  `remarks` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `overtime_budgets`
--

CREATE TABLE `overtime_budgets` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `departement_id` varchar(30) DEFAULT NULL,
  `employee_id` varchar(30) DEFAULT NULL,
  `amount` int(11) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `type` enum('DEPT','EMP') DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `payrolls`
--

CREATE TABLE `payrolls` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT 0,
  `approved_to` varchar(30) DEFAULT NULL,
  `approved_by` varchar(30) DEFAULT NULL,
  `approved_date` timestamp NULL DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL,
  `employee_id` varchar(30) NOT NULL,
  `number` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `period_start` varchar(50) NOT NULL,
  `period_end` varchar(50) NOT NULL,
  `division_name` varchar(50) NOT NULL,
  `departement_name` varchar(50) NOT NULL,
  `departement_sub_name` varchar(50) NOT NULL,
  `contract_name` varchar(50) NOT NULL,
  `position_name` varchar(50) NOT NULL,
  `group_name` varchar(50) NOT NULL,
  `job_type` varchar(50) NOT NULL,
  `level` varchar(50) NOT NULL,
  `marital` varchar(50) NOT NULL,
  `tax_id` varchar(50) NOT NULL,
  `shift_name` varchar(50) NOT NULL,
  `attandance` text NOT NULL,
  `attandance_wd` int(11) NOT NULL,
  `working_day` int(11) NOT NULL,
  `salary` int(11) NOT NULL,
  `allowence` text NOT NULL,
  `bpjs_company` text NOT NULL,
  `bpjs_company_total` int(11) NOT NULL,
  `correction_plus` int(11) NOT NULL,
  `overtime_weekday` int(11) NOT NULL,
  `overtime_convert_weekday` decimal(10,2) NOT NULL,
  `overtime_amount_weekday` int(11) NOT NULL,
  `overtime_holiday` int(11) NOT NULL,
  `overtime_convert_holiday` decimal(10,2) NOT NULL,
  `overtime_amount_holiday` int(11) NOT NULL,
  `overtime_correction` int(11) NOT NULL,
  `overtime_convert_correction` decimal(10,2) NOT NULL,
  `overtime_amount_correction` int(11) NOT NULL,
  `total_overtime` int(11) NOT NULL,
  `total_overtime_convert` decimal(10,2) NOT NULL,
  `total_overtime_amount` int(11) NOT NULL,
  `total_all_allowance` int(11) NOT NULL,
  `deduction_number` text NOT NULL,
  `deduction_amount` text NOT NULL,
  `deduction_absence` int(11) NOT NULL,
  `deduction_absence_amount` int(11) NOT NULL,
  `correction_minus` int(11) NOT NULL,
  `total_all_deduction` int(11) NOT NULL,
  `income` int(11) NOT NULL,
  `deduction` text NOT NULL,
  `loan_cooperative` int(11) NOT NULL,
  `loan_bank` int(11) NOT NULL,
  `loan_other` int(11) NOT NULL,
  `bpjs_employee` text NOT NULL,
  `bpjs_employee_total` int(11) NOT NULL,
  `pph` int(11) NOT NULL,
  `net_income` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `payroll_config`
--

CREATE TABLE `payroll_config` (
  `id` int(11) NOT NULL,
  `payroll_pph_position` int(11) NOT NULL DEFAULT 0,
  `payroll_pph_position_limit` int(11) NOT NULL DEFAULT 0,
  `payroll_pph_payable` int(11) NOT NULL DEFAULT 0,
  `payroll_npwp_null` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `payroll_config`
--

INSERT INTO `payroll_config` (`id`, `payroll_pph_position`, `payroll_pph_position_limit`, `payroll_pph_payable`, `payroll_npwp_null`) VALUES
(1, 5, 500000, 5, 120);

-- --------------------------------------------------------

--
-- Struktur dari tabel `payroll_pkl`
--

CREATE TABLE `payroll_pkl` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `employee_id` varchar(30) DEFAULT NULL,
  `employee_number` varchar(30) DEFAULT NULL,
  `employee_name` varchar(100) DEFAULT NULL,
  `source_id` varchar(30) DEFAULT NULL,
  `source_name` varchar(100) DEFAULT NULL,
  `departement_name` varchar(100) DEFAULT NULL,
  `departement_sub_name` varchar(100) DEFAULT NULL,
  `date_sign` date DEFAULT NULL,
  `period_start` date DEFAULT NULL,
  `period_end` date DEFAULT NULL,
  `services` varchar(50) DEFAULT NULL,
  `attandance` int(11) DEFAULT 0,
  `month_1` int(11) DEFAULT 0,
  `month_2` int(11) DEFAULT 0,
  `month_3` int(11) DEFAULT 0,
  `allowence` int(11) DEFAULT 0,
  `intern_fee` int(11) DEFAULT 0,
  `boarding` int(11) DEFAULT 0,
  `correction_plus` int(11) DEFAULT 0,
  `correction_minus` int(11) DEFAULT 0,
  `total_income` int(11) DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `permits`
--

CREATE TABLE `permits` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT 0,
  `approved_to` varchar(30) DEFAULT NULL,
  `approved_by` varchar(30) DEFAULT NULL,
  `approved_date` timestamp NULL DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT 0,
  `employee_id` varchar(30) DEFAULT NULL,
  `permit_type_id` varchar(30) DEFAULT NULL,
  `reason_id` varchar(30) DEFAULT NULL,
  `trans_date` date DEFAULT NULL,
  `permit_date` date DEFAULT NULL,
  `duration` int(11) DEFAULT 1,
  `leave` int(11) NOT NULL DEFAULT 0,
  `note` text DEFAULT NULL,
  `attachment` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `permit_types`
--

CREATE TABLE `permit_types` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL,
  `number` varchar(20) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `payroll` varchar(20) DEFAULT NULL,
  `cutoff` enum('YES','NO') NOT NULL DEFAULT 'NO',
  `absence` varchar(10) NOT NULL DEFAULT 'NO',
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `permit_types`
--

INSERT INTO `permit_types` (`id`, `created_by`, `created_date`, `updated_by`, `updated_date`, `deleted`, `number`, `name`, `payroll`, `cutoff`, `absence`, `status`) VALUES
('20221124000001', 'admin', '2022-11-24 23:38:20', 'admin', '2023-03-03 09:37:17', 0, 'CT', 'CUTI', 'NON DEDUCTION', 'YES', 'NO', 0),
('20221124000002', 'admin', '2022-11-24 23:38:31', NULL, NULL, 0, 'DL', 'DINAS LUAR', 'NON DEDUCTION', 'NO', 'NO', 0),
('20221124000003', 'admin', '2022-11-24 23:38:40', NULL, NULL, 0, 'DM', 'DUTY MANAGER', 'NON DEDUCTION', 'NO', 'NO', 0),
('20221124000004', 'admin', '2022-11-24 23:38:52', 'sarah', '2023-03-17 19:59:05', 0, 'CH', 'ID CARD HILANG', 'NON DEDUCTION', 'NO', 'YES', 0),
('20221124000005', 'admin', '2022-11-24 23:38:58', 'sarah', '2023-03-17 19:59:15', 0, 'CR', 'ID CARD RUSAK', 'NON DEDUCTION', 'NO', 'YES', 0),
('20221124000006', 'admin', '2022-11-24 23:39:12', 'admin', '2022-12-11 08:14:20', 0, 'PR', 'IZIN', 'NON DEDUCTION', 'NO', 'NO', 0),
('20221124000007', 'admin', '2022-11-24 23:39:20', NULL, NULL, 0, 'IP', 'IZIN POTONG GAJI', 'DEDUCTION', 'NO', 'NO', 0),
('20221124000008', 'admin', '2022-11-24 23:39:30', 'admin', '2022-12-11 08:14:33', 0, 'SK', 'SAKIT', 'NON DEDUCTION', 'NO', 'NO', 0),
('20221124000009', 'admin', '2022-11-24 23:39:41', NULL, NULL, 0, 'WFH', 'WORK FROM HOME', 'NON DEDUCTION', 'NO', 'NO', 0),
('20221207000001', 'admin', '2022-12-07 17:17:52', 'admin', '2023-03-17 19:53:11', 0, 'TA', 'TIDAK ABSEN', 'NON DEDUCTION', 'NO', 'YES', 0),
('20230120000001', 'admin', '2023-01-20 10:56:45', NULL, NULL, 0, 'SC', 'OFF SECURITY', 'NON DEDUCTION', 'NO', 'NO', 0),
('20230130000001', 'sarah', '2023-01-30 14:10:55', 'admin', '2023-05-19 10:18:20', 0, 'GTH', 'GANTI HARI', 'NON DEDUCTION', 'NO', 'YES', 0),
('20230321000001', 'sarah', '2023-03-21 16:19:17', NULL, NULL, 0, 'CTK', 'CUTI KHUSUS', 'NON DEDUCTION', 'NO', 'NO', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `positions`
--

CREATE TABLE `positions` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `number` varchar(30) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `level` varchar(30) DEFAULT NULL,
  `access` varchar(30) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `positions`
--

INSERT INTO `positions` (`id`, `created_by`, `created_date`, `updated_by`, `updated_date`, `deleted`, `number`, `name`, `description`, `level`, `access`, `status`) VALUES
('20221119000001', 'admin', '2022-11-19 10:32:02', 'admin', '2022-11-19 14:59:26', 0, 'PST-0004', 'ASSISTANT MANAGER', '', ' 04', NULL, 0),
('20221119000002', 'admin', '2022-11-19 10:32:22', NULL, NULL, 0, 'PST-0002', 'COO', '', ' 02', NULL, 0),
('20221119000003', 'admin', '2022-11-19 10:32:46', 'admin', '2022-11-19 14:59:22', 0, 'PST-0001', 'DIRECTOR', '', ' 01', NULL, 0),
('20221119000004', 'admin', '2022-11-19 10:33:14', NULL, NULL, 0, 'PST-0006', 'GROUP LEADER', '', ' 06', NULL, 0),
('20221119000005', 'admin', '2022-11-19 10:33:32', 'admin', '2022-11-19 14:59:30', 0, 'PST-0005', 'LEADER', '', ' 05', NULL, 0),
('20221119000006', 'admin', '2022-11-19 10:34:10', 'admin', '2022-11-19 14:59:35', 0, 'PST-0003', 'MANAGER', '', ' 03', NULL, 0),
('20221119000007', 'admin', '2022-11-19 10:34:44', NULL, NULL, 0, 'PST-0008', 'OPERATOR', '', ' 09', NULL, 0),
('20221119000008', 'admin', '2022-11-19 10:34:59', 'admin', '2022-12-05 11:44:14', 0, 'PST-0007', 'STAFF', '', ' 07', '22070006200202', 0),
('20230404000001', 'sarah', '2023-04-04 11:05:08', NULL, NULL, 0, 'PST-0009', 'PLANT MANAGER', '', '03', NULL, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `privilege_groups`
--

CREATE TABLE `privilege_groups` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL,
  `username` varchar(30) DEFAULT NULL,
  `group_id` varchar(30) DEFAULT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ptkp`
--

CREATE TABLE `ptkp` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `marital_id` varchar(30) DEFAULT NULL,
  `amount` int(1) DEFAULT 0,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `ptkp`
--

INSERT INTO `ptkp` (`id`, `created_by`, `created_date`, `updated_by`, `updated_date`, `deleted`, `marital_id`, `amount`, `description`, `status`) VALUES
('20221202000001', 'admin', '2022-12-02 17:28:54', NULL, NULL, 0, '20221119000004', 58500000, '-', 0),
('20221202000002', 'admin', '2022-12-02 17:30:06', NULL, NULL, 0, '20221119000001', 63000000, '', 0),
('20221210000001', 'admin', '2022-12-10 00:48:44', NULL, NULL, 0, '20221119000002', 67500000, '', 0),
('20221210000002', 'admin', '2022-12-10 00:48:56', NULL, NULL, 0, '20221119000003', 72000000, '', 0),
('20221210000003', 'admin', '2022-12-10 00:49:13', NULL, NULL, 0, '20221119000008', 54000000, '', 0),
('20221210000004', 'admin', '2022-12-10 00:50:04', NULL, NULL, 0, '20221119000006', 63500000, '', 0),
('20221210000005', 'admin', '2022-12-10 00:52:47', NULL, NULL, 0, '20221119000007', 67500000, '', 0),
('20221210000006', 'admin', '2022-12-10 00:52:55', NULL, NULL, 0, '20221119000005', 58500000, '', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `reasons`
--

CREATE TABLE `reasons` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL,
  `permit_type_id` varchar(30) NOT NULL,
  `number` varchar(20) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `reasons`
--

INSERT INTO `reasons` (`id`, `created_by`, `created_date`, `updated_by`, `updated_date`, `deleted`, `permit_type_id`, `number`, `name`, `status`) VALUES
('20221124000001', 'admin', '2022-11-24 23:46:52', 'admin', '2022-12-11 08:18:50', 0, '20221124000001', 'CT01', 'CUTI', 0),
('20221124000002', 'admin', '2022-11-24 23:46:57', 'admin', '2022-12-11 08:18:43', 0, '20221124000001', 'CT02', 'CUTI BERSAMA', 0),
('20221124000003', 'admin', '2022-11-24 23:48:18', 'admin', '2022-12-11 08:19:02', 0, '20221124000001', 'CT03', 'CUTI HAID', 0),
('20221124000004', 'admin', '2022-11-24 23:48:21', 'admin', '2022-12-11 09:32:08', 0, '20221124000001', 'CT04', 'CUTI MELAHIRKAN', 0),
('20221124000005', 'admin', '2022-11-24 23:48:29', 'admin', '2022-12-11 08:19:23', 0, '20221124000001', 'CT05', 'CUTI KHUSUS', 0),
('20221124000006', 'admin', '2022-11-24 23:48:37', 'admin', '2022-12-11 08:30:45', 0, '20221124000001', 'CT06', 'CUTI MENIKAH', 0),
('20221124000007', 'admin', '2022-11-24 23:48:48', 'admin', '2022-12-11 08:32:37', 0, '20221124000002', 'DL01', 'DINAS LUAR', 0),
('20221124000009', 'admin', '2022-11-24 23:49:10', 'admin', '2022-11-26 13:31:35', 0, '20221124000003', 'DM01', 'DUTY MANAGER', 0),
('20221124000012', 'admin', '2022-11-24 23:49:49', 'admin', '2022-12-11 08:39:43', 0, '20221124000006', 'PR01', 'IZIN TERLAMBAT MASUK', 0),
('20221124000013', 'admin', '2022-11-24 23:49:58', 'admin', '2022-12-11 08:39:54', 0, '20221124000006', 'PR02', 'IZIN TIDAK POTONG GAJI', 0),
('20221124000014', 'admin', '2022-11-24 23:50:05', 'admin', '2022-12-11 08:39:34', 0, '20221124000006', 'PR03', 'IZIN PULANG CEPAT', 0),
('20221124000015', 'admin', '2022-11-24 23:50:20', 'admin', '2022-11-26 13:32:09', 0, '20221124000007', 'IP01', 'IZIN POTONG GAJI', 0),
('20221124000017', 'admin', '2022-11-24 23:50:39', 'admin', '2022-12-11 09:36:15', 0, '20221124000008', 'SK01', 'SAKIT DENGAN SURAT DOKTER', 0),
('20221124000018', 'admin', '2022-11-24 23:50:45', 'admin', '2022-11-26 13:33:31', 0, '20221124000009', 'WFH01', 'WORK FROM HOME', 0),
('20221207000001', 'admin', '2022-12-07 17:19:23', 'admin', '2022-12-11 08:30:56', 0, '20221124000001', 'CT07', 'CUTI UMROH', 0),
('20221207000002', 'admin', '2022-12-07 17:19:39', 'admin', '2022-12-11 08:31:05', 0, '20221124000001', 'CT08', 'DUKA CITA', 0),
('20221207000003', 'admin', '2022-12-07 17:19:45', 'admin', '2022-12-11 08:31:26', 0, '20221124000001', 'CT09', 'KEGUGURAN', 0),
('20221207000004', 'admin', '2022-12-07 17:22:48', 'admin', '2022-12-11 08:33:27', 0, '20221124000006', 'PR04', 'GANTI HARI KERJA', 0),
('20221207000005', 'admin', '2022-12-07 17:22:54', 'admin', '2022-12-11 08:35:24', 0, '20221124000006', 'PR05', 'GANTI JAM KERJA', 0),
('20221207000006', 'admin', '2022-12-07 17:24:00', 'admin', '2022-12-11 08:40:04', 0, '20221124000006', 'PR06', 'TRAINING', 0),
('20221207000007', 'admin', '2022-12-07 17:24:24', NULL, NULL, 0, '20221124000007', 'IP02', 'SAKIT TANPA SURAT DOKTER', 0),
('20221207000008', 'admin', '2022-12-07 17:24:50', NULL, NULL, 0, '20221207000001', 'TA01', 'BELUM ADA KARTU', 0),
('20221207000009', 'admin', '2022-12-07 17:24:57', NULL, NULL, 0, '20221207000001', 'TA02', 'ID CARD HILANG', 0),
('20221207000010', 'admin', '2022-12-07 17:25:04', NULL, NULL, 0, '20221207000001', 'TA03', 'ID CARD RUSAK', 0),
('20221207000011', 'admin', '2022-12-07 17:25:10', NULL, NULL, 0, '20221207000001', 'TA04', 'LUPA ABSEN', 0),
('20221207000012', 'admin', '2022-12-07 17:25:18', NULL, NULL, 0, '20221207000001', 'TA05', 'MESIN RUSAK', 0),
('20221207000013', 'admin', '2022-12-07 17:25:29', NULL, NULL, 0, '20221207000001', 'TA06', 'TANPA KETERANGAN', 0),
('20230120000001', 'admin', '2023-01-20 10:57:26', 'admin', '2023-01-20 10:58:12', 0, '20230120000001', 'SC01', 'OFF  SECURITY ', 0),
('20230130000001', 'sarah', '2023-01-30 14:11:26', NULL, NULL, 0, '20230130000001', 'GTH01', 'GANTI HARI OFF', 0),
('20230215000001', 'sarah', '2023-02-15 16:01:17', NULL, NULL, 0, '20221124000005', 'CR01', 'ID CARD RUSAK', 0),
('20230415000001', 'sarah', '2023-04-15 01:13:30', NULL, NULL, 0, '20230321000001', 'CTK', 'DUKA CITA', 0),
('20230615000001', 'fitri', '2023-06-15 11:29:42', NULL, NULL, 0, '20230321000001', 'CT10', 'CUTI MENIKAH', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `reason_resignations`
--

CREATE TABLE `reason_resignations` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `number` varchar(30) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `reason_resignations`
--

INSERT INTO `reason_resignations` (`id`, `created_by`, `created_date`, `updated_by`, `updated_date`, `deleted`, `number`, `name`, `description`, `status`) VALUES
('20230508000001', 'fitri', '2023-05-08 11:19:11', NULL, NULL, 0, 'RSN001', 'Sakit', '', 0),
('20230508000002', 'fitri', '2023-05-08 11:19:25', NULL, NULL, 0, 'RSN002', 'Hamil', '', 0),
('20230508000003', 'fitri', '2023-05-08 11:19:55', NULL, NULL, 0, 'RSN003', 'Melanjutkan/Fokus Kuliah', '', 0),
('20230508000004', 'fitri', '2023-05-08 11:20:23', NULL, NULL, 0, 'RSN004', 'Tanpa Keterangan', '', 0),
('20230508000005', 'fitri', '2023-05-08 11:20:52', NULL, NULL, 0, 'RSN005', 'Mendapat Pekerjaan Baru', '', 0),
('20230508000006', 'fitri', '2023-05-08 11:21:06', NULL, NULL, 0, 'RSN006', 'Habis PKL', '', 0),
('20230508000007', 'fitri', '2023-05-08 11:21:29', NULL, NULL, 0, 'RSN007', 'Habis Magang/Kontrak ', '', 0),
('20230508000008', 'fitri', '2023-05-08 13:05:37', NULL, NULL, 0, 'RSN008', 'Menikah', '', 0),
('20230508000009', 'fitri', '2023-05-08 13:05:53', NULL, NULL, 0, 'RSN009', 'Melanjutkan usaha orangtua', '', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `religions`
--

CREATE TABLE `religions` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `number` varchar(30) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `religions`
--

INSERT INTO `religions` (`id`, `created_by`, `created_date`, `updated_by`, `updated_date`, `deleted`, `number`, `name`, `description`, `status`) VALUES
('20221119000001', 'admin', '2022-11-19 10:35:42', 'admin', '2022-11-19 12:38:00', 0, '5', 'BUDHA', '', 0),
('20221119000002', 'admin', '2022-11-19 10:35:59', 'admin', '2022-11-19 12:38:05', 0, '4', 'HINDU', '', 0),
('20221119000003', 'admin', '2022-11-19 10:36:11', 'admin', '2022-11-19 12:38:10', 0, '1', 'ISLAM', '', 0),
('20221119000004', 'admin', '2022-11-19 10:36:38', 'admin', '2022-11-19 12:37:48', 0, '2', ' KRISTEN KATHOLIK', '', 0),
('20221119000005', 'admin', '2022-11-19 10:37:01', 'admin', '2022-11-19 12:37:55', 0, '3', ' KRISTEN PROTESTAN', '', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `requirements`
--

CREATE TABLE `requirements` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `number` varchar(30) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(20) DEFAULT NULL,
  `grade` int(11) DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `requirements`
--

INSERT INTO `requirements` (`id`, `created_by`, `created_date`, `updated_by`, `updated_date`, `deleted`, `number`, `name`, `description`, `category`, `grade`, `status`) VALUES
('20221120000001', 'admin', '2022-11-20 10:14:15', 'admin', '2022-11-20 10:14:44', 0, 'R001', 'KEPRIBADIAN', '', 'PSYCHOLOGICAL', 80, 0),
('20221120000002', 'admin', '2022-11-20 10:14:56', NULL, NULL, 0, 'R002', 'KARAKTER', '', 'PSYCHOLOGICAL', 80, 0),
('20221120000003', 'admin', '2022-11-20 10:15:14', NULL, NULL, 0, 'R003', 'MENULIS & LISAN', '', 'PSYCHOLOGICAL', 80, 0),
('20221120000004', 'admin', '2022-11-20 10:15:58', NULL, NULL, 0, 'R004', 'PENGALAMAN KERJA', '', 'INTERVIEW', 80, 0),
('20221120000005', 'admin', '2022-11-20 10:16:08', NULL, NULL, 0, 'R005', 'KETERTARIKAN', '', 'INTERVIEW', 80, 0),
('20221120000006', 'admin', '2022-11-20 10:17:00', NULL, NULL, 0, 'R006', 'JENJANG PENDIDIKAN', '', 'INTERVIEW', 80, 0),
('20221120000007', 'admin', '2022-11-20 10:17:46', NULL, NULL, 0, 'R007', 'INDIVIDUALISME', '', 'PSYCHOLOGICAL', 80, 0),
('20221120000008', 'admin', '2022-11-20 10:18:13', NULL, NULL, 0, 'R008', 'ORGANISASI', '', 'INTERVIEW', 80, 0),
('20221120000009', 'admin', '2022-11-20 10:18:58', NULL, NULL, 0, 'R009', 'MEROKOK', '', 'MEDICAL', 80, 0),
('20221120000010', 'admin', '2022-11-20 10:19:09', NULL, NULL, 0, 'R010', 'RIWAYAT PENYAKIT', '', 'MEDICAL', 80, 0),
('20221120000011', 'admin', '2022-11-20 10:19:31', NULL, NULL, 0, 'R011', 'PENGLIHATAN', '', 'MEDICAL', 80, 0),
('20221120000012', 'admin', '2022-11-20 10:19:39', NULL, NULL, 0, 'R012', 'PENDENGARAN', '', 'MEDICAL', 80, 0),
('20221120000013', 'admin', '2022-11-20 10:19:51', 'admin', '2022-11-20 10:21:03', 0, 'R013', 'DISABILITAS', '', 'MEDICAL', 80, 0),
('20221120000014', 'admin', '2022-11-20 10:20:08', NULL, NULL, 0, 'R014', 'KOMPUTER', '', 'SKILL', 80, 0),
('20221120000015', 'admin', '2022-11-20 10:20:25', NULL, NULL, 0, 'R015', 'BAHASA', '', 'SKILL', 80, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `resignations`
--

CREATE TABLE `resignations` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT 0,
  `approved_to` varchar(30) DEFAULT NULL,
  `approved_by` varchar(30) DEFAULT NULL,
  `approved_date` timestamp NULL DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `employee_id` varchar(30) DEFAULT NULL,
  `reason_resignation_id` varchar(30) DEFAULT NULL,
  `trans_date` date DEFAULT NULL,
  `resign_type` varchar(30) DEFAULT NULL,
  `request_date` date DEFAULT NULL,
  `resign_date` date DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `status_resign` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `salary_components`
--

CREATE TABLE `salary_components` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `number` varchar(30) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `salary` int(11) DEFAULT NULL,
  `salary_type` varchar(20) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `salary_components`
--

INSERT INTO `salary_components` (`id`, `created_by`, `created_date`, `updated_by`, `updated_date`, `deleted`, `number`, `name`, `salary`, `salary_type`, `status`) VALUES
('20221210000001', 'admin', '2022-12-10 00:54:07', 'fitri', '2023-02-17 09:55:48', 0, 'GP-CSE001', 'Gaji Cleaning Service', 1675000, 'GROSS', 0),
('20221210000002', 'admin', '2022-12-10 00:54:31', 'winasavana', '2023-02-17 09:24:54', 0, 'GP-LPK001', 'Gaji LPK', 2600000, 'GROSS', 0),
('20221210000003', 'admin', '2022-12-10 00:55:02', 'admin', '2023-06-19 15:42:09', 0, 'GP-OPT002', 'Gaji Pokok Karyawan 1', 2825000, 'GROSS', 0),
('20221210000004', 'admin', '2022-12-10 00:55:14', 'fitri', '2023-02-17 10:01:03', 0, 'GP-OPT003', 'Gaji Pokok Karyawan 2', 2350000, 'GROSS', 0),
('20221210000005', 'admin', '2022-12-10 00:55:25', 'fitri', '2023-02-17 14:37:00', 0, 'GP-OPT004', 'Gaji Pokok Karyawan 3', 2900000, 'GROSS', 0),
('20221210000006', 'admin', '2022-12-10 00:55:37', 'fitri', '2023-02-17 14:37:24', 0, 'GP-OPT005', 'Gaji Pokok Karyawan 4', 3050000, 'GROSS', 0),
('20221210000007', 'admin', '2022-12-10 00:55:57', 'fitri', '2023-02-17 14:37:46', 0, 'GP-OPT006', 'Gaji Pokok Karyawan 5', 3175000, 'GROSS', 0),
('20221210000008', 'admin', '2022-12-10 00:56:19', 'winasavana', '2023-04-14 11:21:06', 0, 'GP-OPT007', 'Gaji Pokok Karyawan 6', 2525000, 'GROSS', 0),
('20221210000009', 'admin', '2022-12-10 00:56:49', 'winasavana', '2023-07-17 15:46:41', 0, 'GP-OPT008', 'Gaji Pokok Karyawan 7', 3187500, 'GROSS', 0),
('20221210000010', 'admin', '2022-12-10 00:57:10', 'admin', '2022-12-12 11:53:27', 0, 'GP-OPT009', 'Gaji Pokok Karyawan 8', 2287000, 'GROSS', 0),
('20221210000011', 'admin', '2022-12-10 00:57:33', 'admin', '2023-02-17 13:47:07', 0, 'GP-OPT001', 'Gaji Pokok Karyawan Training', 2275000, 'GROSS', 0),
('20221210000012', 'admin', '2022-12-10 00:57:50', 'admin', '2022-12-12 11:54:23', 0, 'GP-CSE002', 'Gaji Training CS', 1500000, 'NET', 0),
('20221210000013', 'admin', '2022-12-10 00:58:10', NULL, NULL, 0, 'GP-MGG001', 'Uang Saku Mahasiswa Magang', 1300000, 'NET', 0),
('20221210000015', 'admin', '2022-12-10 00:58:53', 'winasavana', '2023-06-16 13:58:11', 0, 'GP-MGG003', 'Uang Saku Mahasiswa Magang 1 STTBSTTMSTMIK', 1350000, 'NET', 0),
('20221210000016', 'admin', '2022-12-10 00:59:07', 'winasavana', '2023-06-16 13:58:49', 0, 'GP-MGG004', 'Uang Saku Mahasiswa Magang 2 STIES AKOM', 1350000, 'NET', 0),
('20230217000001', 'fitri', '2023-02-17 11:44:04', NULL, NULL, 0, 'GP-OPT010', 'Gaji Pokok Karyawan 10', 3390000, 'GROSS', 0),
('20230217000002', 'fitri', '2023-02-17 11:44:50', 'fitri', '2023-02-17 11:48:41', 0, 'GP-OPT011', 'Gaji Pokok Karyawan 11', 3150000, 'GROSS', 0),
('20230217000003', 'fitri', '2023-02-17 11:45:23', 'fitri', '2023-02-17 11:48:52', 0, 'GP-OPT012', 'Gaji Pokok Karyawan 12', 3145000, 'GROSS', 0),
('20230217000004', 'fitri', '2023-02-17 11:46:59', NULL, NULL, 0, 'GP-OPT013', 'Gaji Pokok Karyawan 13', 3050000, 'GROSS', 0),
('20230217000005', 'fitri', '2023-02-17 11:47:37', NULL, NULL, 0, 'GP-OPT014', 'Gaji Pokok Karyawan 14', 3125000, 'GROSS', 0),
('20230217000006', 'fitri', '2023-02-17 11:48:12', NULL, NULL, 0, 'GP-OPT015', 'Gaji Pokok Karyawan 15', 3185000, 'GROSS', 0),
('20230217000007', 'faisal', '2023-02-17 13:30:07', NULL, NULL, 0, 'GP-STF001', 'GAJI KARYAWAN STAFF 1', 3348750, 'GROSS', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `setting_menus`
--

CREATE TABLE `setting_menus` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(30) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_by` varchar(30) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `menus_id` varchar(30) DEFAULT NULL,
  `m_view` varchar(5) DEFAULT NULL,
  `m_add` varchar(5) DEFAULT NULL,
  `m_edit` varchar(5) DEFAULT NULL,
  `m_delete` varchar(5) DEFAULT NULL,
  `m_upload` varchar(5) DEFAULT NULL,
  `m_download` varchar(5) DEFAULT NULL,
  `m_print` varchar(5) DEFAULT NULL,
  `m_excel` varchar(5) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `setting_menus`
--

INSERT INTO `setting_menus` (`id`, `created_by`, `created_date`, `updated_by`, `updated_date`, `deleted`, `menus_id`, `m_view`, `m_add`, `m_edit`, `m_delete`, `m_upload`, `m_download`, `m_print`, `m_excel`, `status`) VALUES
('01ecbb44ee0b473695f445d9f68032', 'admin', '2022-11-18 15:27:20', NULL, NULL, 0, '3b6f413a07674016a7bd971ea3d8f1', 'on', 'on', 'on', 'on', NULL, NULL, 'on', 'on', 0),
('04a7682cc50247a8a75f609d17e14a', 'admin', '2022-09-29 17:03:33', NULL, NULL, 0, 'b679033b3256414b8f916c69f17674', 'on', 'on', 'on', 'on', NULL, NULL, 'on', 'on', 0),
('0fddaa1405bf4a6081704dba2da56b', 'admin', '2022-09-29 17:01:01', NULL, NULL, 0, 'cf98f97766f6405590b26daa586e00', 'on', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
('10c311839f3c45d9baf9cb88e25235', 'admin', '2022-11-18 15:17:38', NULL, NULL, 0, '29a3b810cf99401fbdbfb88c85fc8c', 'on', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
('18944e423a144c35b0c76050a4d74d', 'admin', '2022-09-29 17:04:17', NULL, NULL, 0, 'c8f8362a5f6c432ab27d37213f15d4', 'on', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
('20221120000002', 'admin', '2022-11-20 10:01:33', NULL, NULL, 0, '20221120000004', 'on', 'on', 'on', 'on', NULL, NULL, 'on', 'on', 0),
('20221120000003', 'admin', '2022-11-20 10:01:38', NULL, NULL, 0, '20221120000002', 'on', 'on', 'on', 'on', NULL, NULL, 'on', 'on', 0),
('20221120000004', 'admin', '2022-11-20 10:01:44', NULL, NULL, 0, '20221120000001', 'on', 'on', 'on', 'on', NULL, NULL, 'on', 'on', 0),
('20221122000001', 'admin', '2022-11-22 16:16:18', NULL, NULL, 0, '20221120000003', 'on', 'on', 'on', 'on', NULL, NULL, 'on', 'on', 0),
('20221124000001', 'admin', '2022-11-24 22:50:13', NULL, NULL, 0, '20221124000001', 'on', 'on', 'on', 'on', NULL, NULL, 'on', 'on', 0),
('20221124000002', 'admin', '2022-11-24 22:50:16', NULL, NULL, 0, '20221124000002', 'on', 'on', 'on', 'on', NULL, NULL, 'on', 'on', 0),
('20221124000003', 'admin', '2022-11-24 23:30:14', NULL, NULL, 0, '20221124000003', 'on', 'on', 'on', 'on', NULL, NULL, 'on', 'on', 0),
('20221124000004', 'admin', '2022-11-24 23:30:17', NULL, NULL, 0, '20221124000004', 'on', 'on', 'on', 'on', NULL, NULL, 'on', 'on', 0),
('20221124000006', 'admin', '2022-11-24 23:30:37', NULL, NULL, 0, '20221124000006', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 0),
('20221124000007', 'admin', '2022-11-24 23:30:43', NULL, NULL, 0, '20221124000005', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 0),
('20221124000008', 'admin', '2022-11-24 23:55:19', NULL, NULL, 0, '20221124000007', 'on', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
('20221125000001', 'admin', '2022-11-25 15:40:17', NULL, NULL, 0, '20221125000001', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 0),
('20221125000002', 'admin', '2022-11-25 15:40:21', NULL, NULL, 0, '20221125000003', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 0),
('20221125000003', 'admin', '2022-11-25 15:40:25', NULL, NULL, 0, '20221125000004', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 0),
('20221125000004', 'admin', '2022-11-25 15:40:39', NULL, NULL, 0, '20221125000002', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 0),
('20221126000002', 'admin', '2022-11-26 06:17:11', NULL, NULL, 0, '20221126000001', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 0),
('20221129000002', 'admin', '2022-11-29 17:18:42', NULL, NULL, 0, '20221129000005', 'on', NULL, NULL, NULL, NULL, NULL, 'on', 'on', 0),
('20221129000003', 'admin', '2022-11-29 17:18:44', NULL, NULL, 0, '20221129000003', 'on', NULL, NULL, NULL, NULL, NULL, 'on', 'on', 0),
('20221129000005', 'admin', '2022-11-29 17:18:52', NULL, NULL, 0, '20221129000002', 'on', NULL, NULL, NULL, NULL, NULL, 'on', 'on', 0),
('20221202000001', 'admin', '2022-12-02 16:59:54', NULL, NULL, 0, '20221202000002', 'on', 'on', 'on', 'on', NULL, NULL, 'on', 'on', 0),
('20221202000002', 'admin', '2022-12-02 16:59:57', NULL, NULL, 0, '20221202000005', 'on', 'on', 'on', 'on', NULL, NULL, 'on', 'on', 0),
('20221202000003', 'admin', '2022-12-02 16:59:59', NULL, NULL, 0, '20221202000003', 'on', 'on', 'on', 'on', NULL, NULL, 'on', 'on', 0),
('20221202000004', 'admin', '2022-12-02 17:00:01', NULL, NULL, 0, '20221202000001', 'on', 'on', 'on', 'on', NULL, NULL, 'on', 'on', 0),
('20221202000005', 'admin', '2022-12-02 17:00:03', NULL, NULL, 0, '20221202000004', 'on', 'on', 'on', 'on', NULL, NULL, 'on', 'on', 0),
('20221202000006', 'admin', '2022-12-02 18:13:14', NULL, NULL, 0, '20221202000010', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 0),
('20221202000007', 'admin', '2022-12-02 18:13:42', NULL, NULL, 0, '20221202000009', 'on', 'on', NULL, NULL, NULL, NULL, 'on', 'on', 0),
('20221202000008', 'admin', '2022-12-02 18:13:50', NULL, NULL, 0, '20221202000006', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 0),
('20221202000009', 'admin', '2022-12-02 18:13:52', NULL, NULL, 0, '20221202000007', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 0),
('20221202000010', 'admin', '2022-12-02 18:13:54', NULL, NULL, 0, '20221202000008', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 0),
('20221206000001', 'admin', '2022-12-06 03:35:12', NULL, NULL, 0, '20221206000001', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 0),
('20221206000002', 'admin', '2022-12-06 08:00:03', NULL, NULL, 0, '20221206000002', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 0),
('20221207000001', 'admin', '2022-12-07 13:51:01', NULL, NULL, 0, '20221207000003', 'on', NULL, NULL, NULL, NULL, NULL, 'on', 'on', 0),
('20221207000002', 'admin', '2022-12-07 13:51:04', NULL, NULL, 0, '20221207000002', 'on', NULL, NULL, NULL, NULL, NULL, 'on', 'on', 0),
('20221207000003', 'admin', '2022-12-07 13:51:06', NULL, NULL, 0, '20221207000001', 'on', NULL, NULL, NULL, NULL, NULL, 'on', 'on', 0),
('20221207000004', 'admin', '2022-12-07 13:51:08', NULL, NULL, 0, '20221207000004', 'on', NULL, NULL, NULL, NULL, NULL, 'on', 'on', 0),
('20221215000001', 'admin', '2022-12-15 00:10:35', NULL, NULL, 0, '20221215000001', 'on', 'on', 'on', 'on', NULL, NULL, 'on', 'on', 0),
('20221215000002', 'admin', '2022-12-15 00:10:39', NULL, NULL, 0, '20221215000002', 'on', 'on', 'on', 'on', NULL, NULL, 'on', 'on', 0),
('20221215000003', 'admin', '2022-12-15 00:10:53', NULL, NULL, 0, '20221215000003', 'on', NULL, NULL, NULL, NULL, NULL, 'on', 'on', 0),
('20221215000005', 'admin', '2022-12-15 20:44:25', NULL, NULL, 0, '20221215000004', 'on', NULL, NULL, NULL, NULL, NULL, 'on', 'on', 0),
('20221216000001', 'admin', '2022-12-16 23:27:57', NULL, NULL, 0, '20221216000001', 'on', NULL, 'on', NULL, 'on', 'on', 'on', 'on', 0),
('20221217000001', 'admin', '2022-12-17 23:22:52', NULL, NULL, 0, '20221217000001', 'on', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
('20221217000002', 'admin', '2022-12-17 23:24:40', NULL, NULL, 0, '20221211000001', 'on', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
('20221229000001', 'admin', '2022-12-29 15:48:28', NULL, NULL, 0, '20221229000001', 'on', 'on', 'on', 'on', NULL, NULL, 'on', 'on', 0),
('20221231000001', 'admin', '2022-12-31 15:00:40', NULL, NULL, 0, '20221231000001', 'on', NULL, NULL, NULL, NULL, NULL, 'on', 'on', 0),
('20230111000001', 'admin', '2023-01-11 23:43:17', NULL, NULL, 0, '20230105000001', 'on', 'on', 'on', 'on', NULL, NULL, 'on', 'on', 0),
('20230122000001', 'admin', '2023-01-22 23:58:15', NULL, NULL, 0, '20230122000001', 'on', NULL, NULL, NULL, NULL, NULL, 'on', 'on', 0),
('20230123000001', 'admin', '2023-01-23 21:06:47', NULL, NULL, 0, '20230123000001', 'on', NULL, NULL, NULL, NULL, NULL, 'on', 'on', 0),
('20230207000001', 'admin', '2023-02-07 19:03:14', NULL, NULL, 0, '20230207000001', 'on', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
('20230207000003', 'admin', '2023-02-07 19:11:22', NULL, NULL, 0, '20230207000003', 'on', 'on', 'on', 'on', NULL, NULL, 'on', 'on', 0),
('20230207000004', 'admin', '2023-02-07 19:26:10', NULL, NULL, 0, '20230207000004', 'on', 'on', 'on', 'on', NULL, NULL, 'on', 'on', 0),
('20230226000001', 'admin', '2023-02-26 19:42:43', NULL, NULL, 0, '20230226000001', 'on', 'on', 'on', 'on', NULL, NULL, 'on', 'on', 0),
('20230226000002', 'admin', '2023-02-26 19:47:50', NULL, NULL, 0, '20230226000002', 'on', NULL, NULL, NULL, NULL, NULL, 'on', 'on', 0),
('20230301000001', 'admin', '2023-03-01 00:51:11', NULL, NULL, 0, '20230301000001', 'on', NULL, NULL, NULL, NULL, NULL, 'on', 'on', 0),
('20230307000001', 'admin', '2023-03-07 21:28:05', NULL, NULL, 0, '8753a1bac53746dab9cce4754c716b', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 0),
('20230325000001', 'admin', '2023-03-25 21:33:30', NULL, NULL, 0, '20230325000001', 'on', 'on', NULL, NULL, NULL, NULL, 'on', 'on', 0),
('20230331000001', 'admin', '2023-03-31 21:30:28', NULL, NULL, 0, '20230331000001', 'on', 'on', NULL, NULL, NULL, NULL, 'on', 'on', 0),
('20230415000001', 'admin', '2023-04-15 12:21:11', NULL, NULL, 0, '20221129000001', 'on', NULL, NULL, NULL, NULL, NULL, 'on', 'on', 0),
('20230430000001', 'admin', '2023-04-30 19:35:30', NULL, NULL, 0, '20230430000001', 'on', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
('20230430000002', 'admin', '2023-04-30 19:35:37', NULL, NULL, 0, '20230430000002', 'on', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
('20230430000003', 'admin', '2023-04-30 19:35:44', NULL, NULL, 0, '20230430000003', 'on', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
('20230430000004', 'admin', '2023-04-30 19:35:52', NULL, NULL, 0, '20230430000004', 'on', 'on', 'on', 'on', NULL, NULL, 'on', 'on', 0),
('20230430000005', 'admin', '2023-04-30 19:35:58', NULL, NULL, 0, '20230430000005', 'on', 'on', 'on', 'on', NULL, NULL, 'on', 'on', 0),
('20230516000001', 'admin', '2023-05-16 20:31:12', NULL, NULL, 0, '20230207000002', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 0),
('20230520000001', 'admin', '2023-05-20 01:21:34', NULL, NULL, 0, '20230520000001', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 0),
('20230703000001', 'admin', '2023-07-03 10:18:11', NULL, NULL, 0, '20230703000003', 'on', NULL, NULL, NULL, NULL, NULL, 'on', 'on', 0),
('20230705000001', 'admin', '2023-07-05 16:10:20', NULL, NULL, 0, '20230703000001', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 0),
('20230705000002', 'admin', '2023-07-05 16:10:24', NULL, NULL, 0, '20230703000002', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 0),
('255266d281ed4c8d9ba2dd743d9ce4', 'admin', '2022-11-18 17:20:13', NULL, NULL, 0, 'a391d47a7bce4f02aff4e45a2648b7', 'on', 'on', 'on', 'on', NULL, NULL, 'on', 'on', 0),
('2d3ca428e5694771aa05abd80e9f73', 'admin', '2022-11-18 15:17:05', NULL, NULL, 0, 'fe33d31953e5419d8c8d083163d1d3', 'on', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
('3477afe170e74c88a550720fab556b', 'admin', '2022-11-18 15:17:35', NULL, NULL, 0, '9379ea6a0c0048589eb20c5ad2586f', 'on', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
('40b757c310b242ba8610befca821f9', 'admin', '2022-11-18 15:27:29', NULL, NULL, 0, 'cbb12dde2fdb4e34bad9a31972dbad', 'on', 'on', 'on', 'on', NULL, NULL, 'on', 'on', 0),
('43a374f3ea3b483fb8eaf9c01ccad3', 'admin', '2022-11-18 15:17:30', NULL, NULL, 0, '7bd22debdbb44893ac9a1a0eca1e3f', 'on', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
('48db17dfc2cc4c898b834436abf4f5', 'admin', '2022-11-18 15:27:32', NULL, NULL, 0, '3f328f5162194699b1b56b6bbdc388', 'on', 'on', 'on', 'on', NULL, NULL, 'on', 'on', 0),
('560bd394e4504c6180c20881a21d07', 'admin', '2022-11-18 15:17:28', NULL, NULL, 0, '8998e43f7a834ea7800518a26986b7', 'on', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
('65bdf777a5564d5c94068feb0edcb9', 'admin', '2022-09-29 17:03:57', NULL, NULL, 0, 'de3f6855009e49deb7fd2fdd0f3b3d', 'on', NULL, NULL, 'on', NULL, NULL, 'on', 'on', 0),
('6749ff895a5e49b2bc335f4f375c30', 'admin', '2022-11-18 15:17:13', NULL, NULL, 0, '3e6189e47bad4f7b9fd9d494de67a5', 'on', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
('6912c54aaf7e40c8a7de99584b012c', 'admin', '2022-11-18 17:20:05', NULL, NULL, 0, 'a46529bba2204f8fa09c6351ab6a04', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 0),
('6e9c7bb7067a4b85a859bd02619b4f', 'admin', '2022-11-18 15:27:24', NULL, NULL, 0, '9889164c42ed410ea75819c0379aba', 'on', 'on', 'on', 'on', NULL, NULL, 'on', 'on', 0),
('718ad094392446a19b1655078cee1c', 'admin', '2022-11-18 15:17:33', NULL, NULL, 0, 'b3a3f809a4b64f76b5a163ee11ff9f', 'on', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
('781a58491f8c4c00a0f533e531c8b8', 'admin', '2022-11-18 15:17:32', NULL, NULL, 0, '9f956951158443299d153f439242ee', 'on', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
('79485e88cf884bc789d9d3028edffc', 'admin', '2022-11-18 15:17:07', NULL, NULL, 0, 'cf3754f95019423a91dd08f121d59b', 'on', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
('7f240b89a5a74d778f478c4a3ed3c9', 'admin', '2022-11-18 15:17:49', NULL, NULL, 0, '7ea3a784a2e84b69a890da966a12f0', 'on', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
('836ff9fa6650482fbf81e4f49bb255', 'admin', '2022-09-29 17:03:46', NULL, NULL, 0, '6ccd20c54d1d415189120ec5cc6c81', 'on', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
('8b7c0b552ba344918adfe23549f4b9', 'admin', '2022-11-18 15:27:22', NULL, NULL, 0, '2dffbcb5de604cfeabc36ca26ac2ef', 'on', 'on', 'on', 'on', NULL, NULL, 'on', 'on', 0),
('962311b4039448699f25c8ef64f470', 'admin', '2022-09-29 17:04:22', NULL, NULL, 0, '44964312f0264429978158ada88843', 'on', 'on', 'on', 'on', NULL, NULL, 'on', 'on', 0),
('972a76a5a04e416e92a9b4e225c19c', 'admin', '2022-09-29 17:04:02', NULL, NULL, 0, 'd13439e3f2324450a69b4e0e50159a', 'on', 'on', 'on', 'on', NULL, NULL, 'on', 'on', 0),
('a3395fe142a2445ca926fcaa4d61fa', 'admin', '2022-11-18 15:17:11', NULL, NULL, 0, 'bfa6813659bb4f4b9b6e813d747321', 'on', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
('ab13111bb2c74bedae6bcc1f39d362', 'admin', '2022-11-18 15:27:27', NULL, NULL, 0, 'a32396bb412847c3b24dbd6e6bc8aa', 'on', 'on', 'on', 'on', NULL, NULL, 'on', 'on', 0),
('c150682d482f4c25b613172ba9b880', 'admin', '2022-09-29 17:04:13', NULL, NULL, 0, 'e3c31e10b6c64e119b068ae4b73be6', 'on', 'on', 'on', 'on', NULL, NULL, 'on', 'on', 0),
('c2938361d86e4ec58a76f93c426b4d', 'admin', '2022-11-18 15:17:47', NULL, NULL, 0, 'a2cba087f116454ab7070d4bd72f15', 'on', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
('cdfb0a664a654df8be5a6bca2ef6ee', 'admin', '2022-11-18 15:27:14', NULL, NULL, 0, '71526252fe5447089abfb8f78ea088', 'on', 'on', 'on', 'on', NULL, NULL, 'on', 'on', 0),
('d76b699aaacc4c7f97f3b1ad401982', 'admin', '2022-11-18 15:17:45', NULL, NULL, 0, '3c65f38ca10c4f03a908f6ef813434', 'on', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
('d902445966e24a42b5fc5575a693fb', 'admin', '2022-11-18 15:27:17', NULL, NULL, 0, 'e63d10bdf94e493ca86430645e8a54', 'on', 'on', 'on', 'on', NULL, NULL, 'on', 'on', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `setting_users`
--

CREATE TABLE `setting_users` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(30) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_by` varchar(30) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `users_id` varchar(30) DEFAULT NULL,
  `menus_id` varchar(30) DEFAULT NULL,
  `v_view` tinyint(1) NOT NULL DEFAULT 0,
  `v_add` tinyint(1) NOT NULL DEFAULT 0,
  `v_edit` tinyint(1) NOT NULL DEFAULT 0,
  `v_delete` tinyint(1) NOT NULL DEFAULT 0,
  `v_upload` tinyint(1) NOT NULL DEFAULT 0,
  `v_download` tinyint(1) NOT NULL DEFAULT 0,
  `v_print` tinyint(1) NOT NULL DEFAULT 0,
  `v_excel` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `setting_users`
--

INSERT INTO `setting_users` (`id`, `created_by`, `created_date`, `updated_by`, `updated_date`, `deleted`, `users_id`, `menus_id`, `v_view`, `v_add`, `v_edit`, `v_delete`, `v_upload`, `v_download`, `v_print`, `v_excel`, `status`) VALUES
('00b71e7fbb8840a8b08de7bda3579b', 'admin', '2022-11-18 15:17:55', 'admin', '2022-11-18 15:18:11', 0, 'admin', '3c65f38ca10c4f03a908f6ef813434', 1, 0, 0, 0, 0, 0, 0, 0, 0),
('0b52189607084cb9829e711fdb38db', 'admin', '2022-11-18 15:17:55', 'admin', '2022-11-18 15:18:14', 0, 'admin', '9f956951158443299d153f439242ee', 1, 0, 0, 0, 0, 0, 0, 0, 0),
('0d07f3b5e5174911ab789c3d628f4b', 'admin', '2022-11-18 15:17:55', 'admin', '2022-11-18 15:17:57', 0, 'admin', 'cf3754f95019423a91dd08f121d59b', 1, 0, 0, 0, 0, 0, 0, 0, 0),
('1eb43683537049588b3bd459d7276b', 'admin', '2022-11-18 15:17:55', 'admin', '2022-11-18 15:18:09', 0, 'admin', '9379ea6a0c0048589eb20c5ad2586f', 1, 0, 0, 0, 0, 0, 0, 0, 0),
('20221120000040', 'admin', '2022-11-20 10:01:47', 'admin', '2022-11-20 10:02:01', 0, 'admin', '20221120000001', 1, 1, 1, 1, 0, 0, 1, 1, 0),
('20221120000041', 'admin', '2022-11-20 10:01:47', 'admin', '2022-11-20 10:02:01', 0, 'admin', '20221120000002', 1, 1, 1, 1, 0, 0, 1, 1, 0),
('20221120000042', 'admin', '2022-11-20 10:01:47', 'admin', '2022-11-22 16:16:27', 0, 'admin', '20221120000003', 1, 1, 1, 1, 0, 0, 1, 1, 0),
('20221120000043', 'admin', '2022-11-20 10:01:47', 'admin', '2022-11-20 10:02:02', 0, 'admin', '20221120000004', 1, 1, 1, 1, 0, 0, 1, 1, 0),
('20221124000001', 'admin', '2022-11-24 22:50:21', 'admin', '2022-11-24 22:50:30', 0, 'admin', '20221124000001', 1, 1, 1, 1, 0, 0, 1, 1, 0),
('20221124000002', 'admin', '2022-11-24 22:50:21', 'admin', '2022-11-24 22:50:31', 0, 'admin', '20221124000002', 1, 1, 1, 1, 0, 0, 1, 1, 0),
('20221124000003', 'admin', '2022-11-24 23:30:46', 'admin', '2022-11-24 23:31:02', 0, 'admin', '20221124000003', 1, 1, 1, 1, 0, 0, 1, 1, 0),
('20221124000004', 'admin', '2022-11-24 23:30:46', 'admin', '2022-11-24 23:31:02', 0, 'admin', '20221124000004', 1, 1, 1, 1, 0, 0, 1, 1, 0),
('20221124000005', 'admin', '2022-11-24 23:30:46', 'admin', '2022-11-24 23:31:03', 0, 'admin', '20221124000005', 1, 1, 1, 1, 1, 1, 1, 1, 0),
('20221124000006', 'admin', '2022-11-24 23:30:46', 'admin', '2022-11-24 23:31:03', 0, 'admin', '20221124000006', 1, 1, 1, 1, 1, 1, 1, 1, 0),
('20221124000007', 'admin', '2022-11-24 23:55:27', 'admin', '2022-11-24 23:55:32', 0, 'admin', '20221124000007', 1, 0, 0, 0, 0, 0, 0, 0, 0),
('20221125000001', 'admin', '2022-11-25 15:40:46', 'admin', '2022-11-25 15:41:10', 0, 'admin', '20221125000001', 1, 1, 1, 1, 1, 1, 1, 1, 0),
('20221125000002', 'admin', '2022-11-25 15:40:46', 'admin', '2022-11-25 15:41:10', 0, 'admin', '20221125000002', 1, 1, 1, 1, 1, 1, 1, 1, 0),
('20221125000003', 'admin', '2022-11-25 15:40:46', 'admin', '2022-11-25 15:41:11', 0, 'admin', '20221125000003', 1, 1, 1, 1, 1, 1, 1, 1, 0),
('20221125000004', 'admin', '2022-11-25 15:40:46', 'admin', '2022-11-25 15:41:11', 0, 'admin', '20221125000004', 1, 1, 1, 1, 1, 1, 1, 1, 0),
('20221126000001', 'admin', '2022-11-26 06:17:26', 'admin', '2022-11-26 06:17:43', 0, 'admin', '20221126000001', 1, 1, 1, 1, 1, 1, 1, 1, 0),
('20221129000001', 'admin', '2022-11-29 17:19:01', 'admin', '2023-04-15 12:21:30', 0, 'admin', '20221129000001', 1, 0, 0, 0, 0, 0, 1, 1, 0),
('20221129000002', 'admin', '2022-11-29 17:19:01', 'admin', '2022-11-29 17:19:10', 0, 'admin', '20221129000002', 1, 0, 0, 0, 0, 0, 1, 1, 0),
('20221129000003', 'admin', '2022-11-29 17:19:01', 'admin', '2022-11-29 17:19:10', 0, 'admin', '20221129000003', 1, 0, 0, 0, 0, 0, 1, 1, 0),
('20221129000005', 'admin', '2022-11-29 17:19:01', 'admin', '2022-11-29 17:19:11', 0, 'admin', '20221129000005', 1, 0, 0, 0, 0, 0, 1, 1, 0),
('20221202000001', 'admin', '2022-12-02 17:00:13', 'admin', '2022-12-02 17:00:31', 0, 'admin', '20221202000001', 1, 1, 1, 1, 0, 0, 1, 1, 0),
('20221202000002', 'admin', '2022-12-02 17:00:13', 'admin', '2022-12-02 17:00:31', 0, 'admin', '20221202000002', 1, 1, 1, 1, 0, 0, 1, 1, 0),
('20221202000003', 'admin', '2022-12-02 17:00:13', 'admin', '2022-12-02 17:00:32', 0, 'admin', '20221202000003', 1, 1, 1, 1, 0, 0, 1, 1, 0),
('20221202000004', 'admin', '2022-12-02 17:00:13', 'admin', '2022-12-02 17:00:32', 0, 'admin', '20221202000004', 1, 1, 1, 1, 0, 0, 1, 1, 0),
('20221202000005', 'admin', '2022-12-02 17:00:13', 'admin', '2022-12-02 17:00:33', 0, 'admin', '20221202000005', 1, 1, 1, 1, 0, 0, 1, 1, 0),
('20221202000006', 'admin', '2022-12-02 18:13:58', 'admin', '2022-12-02 18:14:21', 0, 'admin', '20221202000006', 1, 1, 1, 1, 1, 1, 1, 1, 0),
('20221202000007', 'admin', '2022-12-02 18:13:58', 'admin', '2022-12-02 18:14:21', 0, 'admin', '20221202000007', 1, 1, 1, 1, 1, 1, 1, 1, 0),
('20221202000008', 'admin', '2022-12-02 18:13:58', 'admin', '2022-12-02 18:14:22', 0, 'admin', '20221202000008', 1, 1, 1, 1, 1, 1, 1, 1, 0),
('20221202000009', 'admin', '2022-12-02 18:13:58', 'admin', '2022-12-02 18:14:22', 0, 'admin', '20221202000009', 1, 1, 0, 0, 0, 0, 1, 1, 0),
('20221202000010', 'admin', '2022-12-02 18:13:58', 'admin', '2022-12-02 18:14:23', 0, 'admin', '20221202000010', 1, 1, 1, 1, 1, 1, 1, 1, 0),
('20221206000001', 'admin', '2022-12-06 03:35:16', 'admin', '2022-12-06 03:35:25', 0, 'admin', '20221206000001', 1, 1, 1, 1, 1, 1, 1, 1, 0),
('20221206000017', 'admin', '2022-12-06 08:00:06', 'admin', '2022-12-06 08:00:13', 0, 'admin', '20221206000002', 1, 1, 1, 1, 1, 1, 1, 1, 0),
('20221207000001', 'admin', '2022-12-07 13:51:12', 'admin', '2022-12-07 13:51:20', 0, 'admin', '20221207000001', 1, 0, 0, 0, 0, 0, 1, 1, 0),
('20221207000002', 'admin', '2022-12-07 13:51:12', 'admin', '2022-12-07 13:51:21', 0, 'admin', '20221207000002', 1, 0, 0, 0, 0, 0, 1, 1, 0),
('20221207000003', 'admin', '2022-12-07 13:51:12', 'admin', '2022-12-07 13:51:21', 0, 'admin', '20221207000003', 1, 0, 0, 0, 0, 0, 1, 1, 0),
('20221207000004', 'admin', '2022-12-07 13:51:12', 'admin', '2022-12-07 13:51:21', 0, 'admin', '20221207000004', 1, 0, 0, 0, 0, 0, 1, 1, 0),
('20221211000001', 'admin', '2022-12-11 22:17:52', 'admin', '2022-12-17 23:24:56', 0, 'admin', '20221211000001', 1, 0, 0, 0, 0, 0, 0, 0, 0),
('20221215000001', 'admin', '2022-12-15 00:10:57', 'admin', '2022-12-15 00:11:05', 0, 'admin', '20221215000001', 1, 1, 1, 1, 0, 0, 1, 1, 0),
('20221215000002', 'admin', '2022-12-15 00:10:57', 'admin', '2022-12-15 00:11:35', 0, 'admin', '20221215000002', 1, 1, 1, 1, 0, 0, 1, 1, 0),
('20221215000003', 'admin', '2022-12-15 00:10:57', 'admin', '2022-12-15 00:11:11', 0, 'admin', '20221215000003', 1, 0, 0, 0, 0, 0, 1, 1, 0),
('20221215000013', 'admin', '2022-12-15 20:44:43', 'admin', '2022-12-15 20:45:03', 0, 'admin', '20221215000004', 1, 0, 0, 0, 0, 0, 1, 1, 0),
('20221216000020', 'admin', '2022-12-16 23:28:01', 'admin', '2022-12-16 23:28:10', 0, 'admin', '20221216000001', 1, 0, 1, 0, 1, 1, 1, 1, 0),
('20221217000003', 'admin', '2022-12-17 23:24:48', 'admin', '2022-12-17 23:24:57', 0, 'admin', '20221217000001', 1, 0, 0, 0, 0, 0, 0, 0, 0),
('20221229000001', 'admin', '2022-12-29 15:48:33', 'admin', '2022-12-29 15:48:46', 0, 'admin', '20221229000001', 1, 1, 1, 1, 0, 0, 1, 1, 0),
('20221231000001', 'admin', '2022-12-31 15:00:44', 'admin', '2022-12-31 15:00:53', 0, 'admin', '20221231000001', 1, 0, 0, 0, 0, 0, 1, 1, 0),
('20230105000001', 'admin', '2023-01-05 06:23:47', 'admin', '2023-01-11 23:43:28', 0, 'admin', '20230105000001', 1, 1, 1, 1, 0, 0, 1, 1, 0),
('20230122000001', 'admin', '2023-01-22 23:58:21', 'admin', '2023-01-22 23:58:26', 0, 'admin', '20230122000001', 1, 0, 0, 0, 0, 0, 1, 1, 0),
('20230123000002', 'admin', '2023-01-23 21:06:52', 'admin', '2023-01-23 21:06:57', 0, 'admin', '20230123000001', 1, 0, 0, 0, 0, 0, 1, 1, 0),
('20230207000001', 'admin', '2023-02-07 19:03:23', 'admin', '2023-02-07 19:03:26', 0, 'admin', '20230207000001', 1, 0, 0, 0, 0, 0, 0, 0, 0),
('20230207000002', 'admin', '2023-02-07 19:05:43', 'admin', '2023-05-16 20:31:52', 0, 'admin', '20230207000002', 1, 1, 1, 1, 1, 1, 1, 1, 0),
('20230207000003', 'admin', '2023-02-07 19:11:01', 'admin', '2023-02-07 19:11:43', 0, 'admin', '20230207000003', 1, 1, 1, 1, 0, 0, 1, 1, 0),
('20230207000010', 'admin', '2023-02-07 19:25:44', 'admin', '2023-02-07 19:26:33', 0, 'admin', '20230207000004', 1, 1, 1, 1, 0, 0, 1, 1, 0),
('20230226000001', 'admin', '2023-02-26 19:43:24', 'admin', '2023-02-26 19:43:32', 0, 'admin', '20230226000001', 1, 1, 1, 1, 0, 0, 1, 1, 0),
('20230226000002', 'admin', '2023-02-26 19:48:00', 'admin', '2023-02-26 19:48:06', 0, 'admin', '20230226000002', 1, 0, 0, 0, 0, 0, 1, 1, 0),
('20230301000001', 'admin', '2023-03-01 00:51:15', 'admin', '2023-03-01 00:51:21', 0, 'admin', '20230301000001', 1, 0, 0, 0, 0, 0, 1, 1, 0),
('20230325000001', 'admin', '2023-03-25 21:33:35', 'admin', '2023-03-25 21:33:41', 0, 'admin', '20230325000001', 1, 1, 0, 0, 0, 0, 1, 1, 0),
('20230331000001', 'admin', '2023-03-31 21:30:33', 'admin', '2023-03-31 21:30:40', 0, 'admin', '20230331000001', 1, 1, 0, 0, 0, 0, 1, 1, 0),
('20230430000001', 'admin', '2023-04-30 19:36:03', 'admin', '2023-04-30 19:36:06', 0, 'admin', '20230430000001', 1, 0, 0, 0, 0, 0, 0, 0, 0),
('20230430000002', 'admin', '2023-04-30 19:36:03', 'admin', '2023-04-30 19:36:06', 0, 'admin', '20230430000002', 1, 0, 0, 0, 0, 0, 0, 0, 0),
('20230430000003', 'admin', '2023-04-30 19:36:03', 'admin', '2023-04-30 19:36:07', 0, 'admin', '20230430000003', 1, 0, 0, 0, 0, 0, 0, 0, 0),
('20230430000004', 'admin', '2023-04-30 19:36:03', 'admin', '2023-04-30 19:36:20', 0, 'admin', '20230430000004', 1, 1, 1, 1, 0, 0, 1, 1, 0),
('20230430000005', 'admin', '2023-04-30 19:36:03', 'admin', '2023-04-30 19:36:20', 0, 'admin', '20230430000005', 1, 1, 1, 1, 0, 0, 1, 1, 0),
('20230520000001', 'admin', '2023-05-20 01:21:38', 'admin', '2023-05-20 01:21:48', 0, 'admin', '20230520000001', 1, 1, 1, 1, 1, 1, 1, 1, 0),
('20230703000001', 'admin', '2023-07-03 10:18:44', 'admin', '2023-07-05 16:10:38', 0, 'admin', '20230703000001', 1, 1, 1, 1, 1, 1, 1, 1, 0),
('20230703000002', 'admin', '2023-07-03 10:18:44', 'admin', '2023-07-05 16:10:39', 0, 'admin', '20230703000002', 1, 1, 1, 1, 1, 1, 1, 1, 0),
('20230703000003', 'admin', '2023-07-03 10:18:44', 'admin', '2023-07-03 10:19:17', 0, 'admin', '20230703000003', 1, 0, 0, 0, 0, 0, 1, 1, 0),
('2936a6a943ea4e9bbf2d991ba6dbb5', 'admin', '2022-11-18 15:17:55', 'admin', '2022-11-18 15:18:14', 0, 'admin', '7bd22debdbb44893ac9a1a0eca1e3f', 1, 0, 0, 0, 0, 0, 0, 0, 0),
('2a3756ba248a4c4a9be7320cb21c03', 'admin', '2022-11-18 15:17:55', 'admin', '2022-11-18 15:18:08', 0, 'admin', '29a3b810cf99401fbdbfb88c85fc8c', 1, 0, 0, 0, 0, 0, 0, 0, 0),
('2c192616c6374feebbbc2778dd4443', 'admin', '2022-09-29 17:18:39', 'admin', '2022-09-29 17:19:46', 0, 'admin', '44964312f0264429978158ada88843', 1, 1, 1, 1, 0, 0, 1, 1, 0),
('4185a36a0bfe4f98bbb86ef59eb46d', 'admin', '2022-11-18 15:27:42', 'admin', '2022-11-18 15:28:06', 0, 'admin', '2dffbcb5de604cfeabc36ca26ac2ef', 1, 1, 1, 1, 0, 0, 1, 1, 0),
('442f524911434a90bf925c3db160a8', 'admin', '2022-11-18 15:27:42', 'admin', '2022-11-18 15:28:07', 0, 'admin', 'e63d10bdf94e493ca86430645e8a54', 1, 1, 1, 1, 0, 0, 1, 1, 0),
('56c39e081a4d4d4c8db20e988f14cc', 'admin', '2022-09-29 17:18:39', 'admin', '2022-09-29 17:19:52', 0, 'admin', 'de3f6855009e49deb7fd2fdd0f3b3d', 1, 0, 0, 1, 0, 0, 1, 1, 0),
('5b804be4fbca4c36ae75acde671b35', 'admin', '2022-11-18 15:17:55', 'admin', '2022-11-18 15:18:13', 0, 'admin', '8998e43f7a834ea7800518a26986b7', 1, 0, 0, 0, 0, 0, 0, 0, 0),
('62aa172fcf7c443aba135013fbcc54', 'admin', '2022-09-29 17:18:39', 'admin', '2022-09-29 17:19:26', 0, 'admin', 'cf98f97766f6405590b26daa586e00', 1, 0, 0, 0, 0, 0, 0, 0, 0),
('6383d23517f74191a72be4dfd29137', 'admin', '2022-11-18 15:27:42', 'admin', '2022-11-18 15:28:07', 0, 'admin', '3b6f413a07674016a7bd971ea3d8f1', 1, 1, 1, 1, 0, 0, 1, 1, 0),
('667cb45b45d44930b9018552b2ff14', 'admin', '2022-11-18 15:27:42', 'admin', '2022-11-18 15:28:10', 0, 'admin', '9889164c42ed410ea75819c0379aba', 1, 1, 1, 1, 0, 0, 1, 1, 0),
('67bf89a8259942d799ead773394497', 'admin', '2022-09-29 17:18:39', 'admin', '2022-09-29 17:19:31', 0, 'admin', 'c8f8362a5f6c432ab27d37213f15d4', 1, 0, 0, 0, 0, 0, 0, 0, 0),
('6a1e4657c9af49aa9b3e921fd7668e', 'admin', '2022-11-18 17:20:45', 'admin', '2022-11-18 17:21:00', 0, 'admin', 'a46529bba2204f8fa09c6351ab6a04', 1, 1, 1, 1, 1, 1, 1, 1, 0),
('6d0322ecdf844e94af36d8d6239723', 'admin', '2022-11-18 17:20:45', 'admin', '2022-11-18 17:21:00', 0, 'admin', 'a391d47a7bce4f02aff4e45a2648b7', 1, 1, 1, 1, 0, 0, 1, 1, 0),
('73282893b9a14abb9737c2cd2afc14', 'admin', '2022-11-18 15:27:42', 'admin', '2022-11-18 15:28:08', 0, 'admin', '3f328f5162194699b1b56b6bbdc388', 1, 1, 1, 1, 0, 0, 1, 1, 0),
('746d6989a790471ca2e4de24a9f871', 'admin', '2022-09-29 17:18:39', 'admin', '2022-09-29 17:19:48', 0, 'admin', 'b679033b3256414b8f916c69f17674', 1, 1, 1, 1, 0, 0, 1, 1, 0),
('756cd5244a234c1b87ba2812470ce1', 'admin', '2022-11-18 15:27:42', 'admin', '2022-11-18 15:28:10', 0, 'admin', 'a32396bb412847c3b24dbd6e6bc8aa', 1, 1, 1, 1, 0, 0, 1, 1, 0),
('77cb5389db0541b3b5975b8a77a56b', 'admin', '2022-11-18 15:17:55', 'admin', '2022-11-18 15:17:57', 0, 'admin', 'fe33d31953e5419d8c8d083163d1d3', 1, 0, 0, 0, 0, 0, 0, 0, 0),
('7a54939be9f443019bc91e27c8c463', 'admin', '2022-11-18 15:17:55', 'admin', '2022-11-18 15:18:11', 0, 'admin', '7ea3a784a2e84b69a890da966a12f0', 1, 0, 0, 0, 0, 0, 0, 0, 0),
('7fc262dde9d24c5d95bc1f87976f34', 'admin', '2022-11-18 15:17:55', 'admin', '2022-11-18 15:17:58', 0, 'admin', 'bfa6813659bb4f4b9b6e813d747321', 1, 0, 0, 0, 0, 0, 0, 0, 0),
('8eddc79f263c404fbd7e4176ff1c20', 'admin', '2022-11-18 15:17:55', 'admin', '2022-11-18 15:18:13', 0, 'admin', 'a2cba087f116454ab7070d4bd72f15', 1, 0, 0, 0, 0, 0, 0, 0, 0),
('9b9c36848ff4460eb505761f37efaf', 'admin', '2022-09-29 17:18:39', 'admin', '2022-09-29 17:19:51', 0, 'admin', 'd13439e3f2324450a69b4e0e50159a', 1, 1, 1, 1, 0, 0, 1, 1, 0),
('9c9bf3609d284f5c85dbf9b2bc08c6', 'admin', '2022-11-18 15:17:55', 'admin', '2022-11-18 15:17:57', 0, 'admin', '3e6189e47bad4f7b9fd9d494de67a5', 1, 0, 0, 0, 0, 0, 0, 0, 0),
('9fb26e18ddfc41b39fe0d68d8a3ba6', 'admin', '2022-11-18 15:17:55', 'admin', '2022-11-18 15:18:07', 0, 'admin', 'b3a3f809a4b64f76b5a163ee11ff9f', 1, 0, 0, 0, 0, 0, 0, 0, 0),
('af473d04fefb479e967d32fd497e2e', 'admin', '2022-09-29 17:18:39', 'admin', '2022-09-29 17:19:53', 0, 'admin', 'e3c31e10b6c64e119b068ae4b73be6', 1, 1, 1, 1, 0, 0, 1, 1, 0),
('be21f39a8f094cf98441efaa482742', 'admin', '2022-11-18 15:27:42', 'admin', '2022-11-18 15:28:08', 0, 'admin', 'cbb12dde2fdb4e34bad9a31972dbad', 1, 1, 1, 1, 0, 0, 1, 1, 0),
('c8723a0626fb460ea78634f1737933', 'admin', '2022-11-18 15:27:42', 'admin', '2022-11-18 15:28:08', 0, 'admin', '71526252fe5447089abfb8f78ea088', 1, 1, 1, 1, 0, 0, 1, 1, 0),
('c93d58aadd4b46ce94ac9e8af4f42c', 'admin', '2022-09-29 17:18:39', 'admin', '2022-09-29 17:19:30', 0, 'admin', '6ccd20c54d1d415189120ec5cc6c81', 1, 0, 0, 0, 0, 0, 0, 0, 0),
('f81c9e765475403ca4cb4650b0489d', 'admin', '2022-11-18 17:20:45', 'admin', '2023-03-07 21:28:23', 0, 'admin', '8753a1bac53746dab9cce4754c716b', 1, 1, 1, 1, 1, 1, 1, 1, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `setup_allowances`
--

CREATE TABLE `setup_allowances` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `employee_id` varchar(30) DEFAULT NULL,
  `allowance_id` varchar(30) DEFAULT NULL,
  `amount` int(11) DEFAULT 0,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `setup_cash_carries`
--

CREATE TABLE `setup_cash_carries` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `employee_id` varchar(30) DEFAULT NULL,
  `allowance_id` varchar(30) DEFAULT NULL,
  `amount` int(11) DEFAULT 0,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `setup_deductions`
--

CREATE TABLE `setup_deductions` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `employee_id` varchar(30) DEFAULT NULL,
  `deduction_id` varchar(30) DEFAULT NULL,
  `amount` int(11) DEFAULT 0,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `setup_salaries`
--

CREATE TABLE `setup_salaries` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT 0,
  `approved_to` varchar(30) DEFAULT NULL,
  `approved_by` varchar(30) DEFAULT NULL,
  `approved_date` timestamp NULL DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `employee_id` varchar(30) DEFAULT NULL,
  `salary_component_id` varchar(30) DEFAULT NULL,
  `amount` int(11) DEFAULT 0,
  `bpjs` int(11) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `shifts`
--

CREATE TABLE `shifts` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `number` varchar(30) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `shifts`
--

INSERT INTO `shifts` (`id`, `created_by`, `created_date`, `updated_by`, `updated_date`, `deleted`, `number`, `name`, `type`, `description`, `status`) VALUES
('20221207000001', 'admin', '2022-12-07 17:06:42', 'admin', '2023-07-12 17:36:25', 0, 'SH-0002', '2 SHIFT A', '2 SHIFT', 'REGULAR KI SENIN - JUMAT', 0),
('20221207000002', 'admin', '2022-12-07 17:06:54', 'admin', '2023-07-12 17:36:42', 0, 'SH-0003', '3 SHIFT A', '3 SHIFT', 'REGULAR KI SENIN - SABTU', 0),
('20221207000004', 'admin', '2022-12-07 17:07:31', 'admin', '2023-07-12 17:36:47', 0, 'SH-0001', 'NON SHIFT A', 'NON SHIFT', 'NON SHIFT REGULAR', 0),
('20221207000005', 'admin', '2022-12-07 17:07:52', 'admin', '2023-07-12 17:36:31', 0, 'SH-0005', '2 SHIFT B', '2 SHIFT', '2 SHIFT (pagi 07.30)', 0),
('20230106000001', 'sarah', '2023-01-06 09:24:58', 'admin', '2023-07-12 17:36:52', 0, 'SH-0007', 'NON SHIFT B', 'NON SHIFT', 'NON SHIFT SORE', 0),
('20230130000001', 'sarah', '2023-01-30 11:33:27', 'admin', '2023-07-12 17:37:00', 0, 'SH-0008', 'NON SHIFT FG WHS', 'NON SHIFT', 'Khusus Finish Good', 0),
('20230320000001', 'sarah', '2023-03-20 16:41:46', 'admin', '2023-07-12 17:36:38', 0, 'SH-009', '3 SHIFT 5 HKW', '3 SHIFT', ' TAKOYAKI', 0),
('20230605000001', 'fitri', '2023-06-05 11:46:57', 'admin', '2023-07-12 17:37:06', 0, 'SH-010', 'SHIFT DRIVER', 'NON SHIFT', '6 HKW (07.30)', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `shift_details`
--

CREATE TABLE `shift_details` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `shift_id` varchar(30) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `tolerance` int(11) DEFAULT 0,
  `briefing` time DEFAULT NULL,
  `start` time DEFAULT NULL,
  `end` time DEFAULT NULL,
  `working` enum('WEEKDAY','WEEKEND') DEFAULT 'WEEKDAY',
  `days` int(11) DEFAULT 0,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `shift_details`
--

INSERT INTO `shift_details` (`id`, `created_by`, `created_date`, `updated_by`, `updated_date`, `deleted`, `shift_id`, `name`, `tolerance`, `briefing`, `start`, `end`, `working`, `days`, `description`, `status`) VALUES
('20221207000001', 'admin', '2022-12-07 17:11:03', 'sarah', '2023-04-15 13:03:22', 0, '20221207000001', 'SHIFT REGULAR MALAM', 3, '20:45:00', '21:00:00', '06:15:00', 'WEEKDAY', 5, '', 0),
('20221207000002', 'admin', '2022-12-07 17:11:24', 'sarah', '2023-04-16 09:55:33', 0, '20221207000001', 'SHIFT REGULAR PAGI', 3, '06:45:00', '07:00:00', '16:15:00', 'WEEKDAY', 5, '', 0),
('20221207000005', 'admin', '2022-12-07 17:13:15', 'sarah', '2023-04-16 09:54:08', 0, '20221207000002', '3 SHIFT REGULAR MALAM', 3, '22:45:00', '23:00:00', '07:00:00', 'WEEKDAY', 6, '', 0),
('20221207000006', 'admin', '2022-12-07 17:13:38', 'sarah', '2023-04-16 09:54:17', 0, '20221207000002', '3 SHIFT REGULAR PAGI', 3, '06:45:00', '07:00:00', '15:00:00', 'WEEKDAY', 6, '', 0),
('20221207000007', 'admin', '2022-12-07 17:14:03', 'sarah', '2023-04-16 09:54:26', 0, '20221207000002', '3 SHIFT REGULAR SABTU PAGI', 3, '06:45:00', '07:00:00', '12:00:00', 'WEEKEND', 6, '', 0),
('20221207000008', 'admin', '2022-12-07 17:14:51', 'fitri', '2023-06-26 09:34:38', 0, '20221207000002', '3 SHIFT REGULAR SORE', 3, '15:45:00', '16:00:00', '24:00:00', 'WEEKDAY', 6, '', 0),
('20221207000012', 'admin', '2022-12-07 17:16:42', 'sarah', '2023-04-16 09:54:59', 0, '20221207000004', 'NON SHIFT REGULAR', 3, '07:15:00', '07:30:00', '16:45:00', 'WEEKDAY', 5, '', 0),
('20230130000001', 'sarah', '2023-01-30 11:35:51', 'sarah', '2023-04-16 09:54:42', 0, '20230130000001', 'NON SHIFT FG WHS', 3, '10:00:00', '10:00:00', '19:00:00', 'WEEKDAY', 5, '', 0),
('20230130000002', 'sarah', '2023-01-30 11:41:25', 'sarah', '2023-04-16 09:55:21', 0, '20221207000005', 'REGULAR SHIFT PAGI ENG', 3, '07:15:00', '07:30:00', '16:45:00', 'WEEKDAY', 5, '', 0),
('20230130000003', 'sarah', '2023-01-30 11:42:39', 'sarah', '2023-04-16 09:55:10', 0, '20221207000005', 'REGULAR SHIFT MALAM ENG', 3, '20:45:00', '21:00:00', '06:15:00', 'WEEKDAY', 5, '', 0),
('20230203000001', 'sarah', '2023-02-03 08:14:29', 'fitri', '2023-06-26 10:11:03', 0, '20230106000001', 'NON SHIFT KI REGULAR SORE', 3, '15:45:00', '16:00:00', '01:00:00', 'WEEKDAY', 5, '', 0),
('20230605000001', 'fitri', '2023-06-05 11:49:46', NULL, NULL, 0, '20230605000001', 'SHIFT Driver', 2, '07:30:00', '07:30:00', '19:30:00', 'WEEKDAY', 6, '', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `shift_employees`
--

CREATE TABLE `shift_employees` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) DEFAULT 0,
  `employee_id` varchar(30) DEFAULT NULL,
  `shift_id` varchar(30) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sources`
--

CREATE TABLE `sources` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `group_id` varchar(30) DEFAULT NULL,
  `number` varchar(30) DEFAULT NULL,
  `number_id` varchar(10) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `amount` int(11) NOT NULL DEFAULT 0,
  `boarding_fee` int(11) NOT NULL DEFAULT 0,
  `sourcing_fee` int(11) NOT NULL DEFAULT 0,
  `thr_fee` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `sources`
--

INSERT INTO `sources` (`id`, `created_by`, `created_date`, `updated_by`, `updated_date`, `deleted`, `group_id`, `number`, `number_id`, `name`, `description`, `amount`, `boarding_fee`, `sourcing_fee`, `thr_fee`, `status`) VALUES
('20221216000001', 'winasavana', '2022-12-16 11:25:11', 'sarah', '2023-05-08 11:49:44', 0, '20221119000005', 'PKL001', 'KINP', 'SMK AL MUFTI', 'Jl. Raya K.H. Zaenal Mufti, Wanakerta, Kec. Purwadadi, Kabupaten Subang, Jawa Barat 41261', 0, 0, 0, 0, 0),
('20221216000002', 'winasavana', '2022-12-16 11:25:40', 'sarah', '2023-06-19 14:57:32', 0, '20221119000005', 'PKL002', 'KINP', 'SMK AL HUDA', 'Jayaratu, Kec. Sariwangi, Kabupaten Tasikmalaya, Jawa Barat 46465', 0, 0, 0, 0, 0),
('20221216000003', 'winasavana', '2022-12-16 11:26:13', 'sarah', '2023-06-19 14:57:39', 0, '20221119000005', 'PKL003', 'KINP', 'SMK KARYA NASIONAL KUNINGAN', 'Jl. Cirendang - Cigugur, Cirendang, Kec. Kuningan, Kabupaten Kuningan, Jawa Barat 45518', 0, 0, 0, 0, 0),
('20221216000004', 'winasavana', '2022-12-16 11:27:00', NULL, NULL, 0, '20221119000005', 'PKL004', NULL, 'SMK MAARIF NU 1 AJIBARANG', 'Jl. Ajibarang - Purwokerto No.KM.01, Kedungmeong, Bumiayu, Kec. Ajibarang, Kabupaten Banyumas, Jawa Tengah 53163', 0, 0, 0, 0, 0),
('20221216000005', 'winasavana', '2022-12-16 11:27:28', 'winasavana', '2023-01-18 14:03:13', 0, '20221119000005', 'PKL005', NULL, 'SMK SEHATI KARAWANG', 'l. Kp. Wates No.33, Pancawati, Kec. Klari, Karawang, Jawa Barat 41371', 0, 0, 0, 0, 0),
('20221216000006', 'winasavana', '2022-12-16 11:28:00', 'winasavana', '2023-01-18 14:03:21', 0, '20221119000005', 'PKL006', NULL, 'SMK SMTI PONTIANAK', 'Jl. Sulawesi Dalam No.31, Akcaya, Kec. Pontianak Sel., Kota Pontianak, Kalimantan Barat 78113', 0, 0, 0, 0, 0),
('20221216000007', 'winasavana', '2022-12-16 11:28:53', NULL, NULL, 0, '20221119000005', 'PKL007', NULL, 'SMK SMTI YOGYAKARTA', 'Jl. Kusumanegara No.3, Semaki, Kec. Umbulharjo, Kota Yogyakarta, Daerah Istimewa Yogyakarta 55166', 0, 0, 0, 0, 0),
('20221216000008', 'winasavana', '2022-12-16 11:29:26', NULL, NULL, 0, '20221119000005', 'PKL008', NULL, 'SMK TARUNA KARYA MANDIRI', 'Purwajaya, Kec. Tempuran, Karawang, Jawa Barat 41385', 0, 0, 0, 0, 0),
('20221216000009', 'winasavana', '2022-12-16 11:29:49', 'sarah', '2023-06-26 10:07:22', 0, '20221119000005', 'PKL009', 'KINP', 'SMK TEXAR KLARI KARAWANG', 'Jl. Raya Kosambi, Duren, Kec. Klari, Karawang, Jawa Barat 41371', 0, 0, 0, 0, 0),
('20221216000010', 'winasavana', '2022-12-16 11:30:18', NULL, NULL, 0, '20221119000005', 'PKL010', NULL, 'SMKN 1 CIKAMPEK', 'Jl. Sukamanah Timur , Cikampek Barat, Cikampek Barat, Kec. Cikampek, Kab. Karawang, Jawa Barat, dengan kode pos 41373', 0, 0, 0, 0, 0),
('20221216000011', 'winasavana', '2022-12-16 11:30:41', NULL, NULL, 0, '20221119000005', 'PKL011', NULL, 'SMKN 1 CILAKU', 'Sukasari, Kec. Cilaku, Kabupaten Cianjur, Jawa Barat 43285', 0, 0, 0, 0, 0),
('20221216000012', 'winasavana', '2022-12-16 11:35:37', 'winasavana', '2023-01-18 14:03:39', 0, '20221119000005', 'PKL012', NULL, 'SMKN 1 LEGONKULON', 'Mayangan, Legonkulon, Subang Regency, West Java 41254', 0, 0, 0, 0, 0),
('20221216000013', 'winasavana', '2022-12-16 11:36:32', 'winasavana', '2022-12-19 14:40:28', 0, '20221119000005', 'PKL013', NULL, 'SMKN MANIIS PURWAKARTA', 'JL. Sumur Nangka, Cijati, Kec. Maniis, Kab. Purwakarta Prov. Jawa Barat', 7500, 0, 0, 0, 0),
('20221216000014', 'winasavana', '2022-12-16 11:37:11', 'admin', '2023-04-02 05:09:30', 0, '20221119000003', 'KMP001', '180', 'STT BANDUNG', 'Jl. Soekarno Hatta No.378, Kb. Lega, Kec. Bojongloa Kidul, Kota Bandung, Jawa Barat 40235', 0, 0, 450000, 600000, 0),
('20221216000015', 'winasavana', '2022-12-16 11:37:51', 'admin', '2023-04-02 05:09:42', 0, '20221119000003', 'KMP002', '270', 'STT MUTTAQIEN', 'Jl. Letjend Basuki Rahmat No 37 Sindang Kasih Kab. Purwakarta Prov. Jawa Barat', 0, 0, 450000, 600000, 0),
('20221216000016', 'winasavana', '2022-12-16 11:38:22', 'admin', '2023-04-02 05:08:58', 0, '20221119000003', 'KMP003', '290', 'STIES INDONESIA PURWAKARTA', ' Jl. Veteran No.150, Ciseureuh, Kec. Purwakarta, Kabupaten Purwakarta, Jawa Barat 41118', 0, 0, 467500, 600000, 0),
('20221216000017', 'winasavana', '2022-12-16 11:38:51', 'winasavana', '2023-04-17 10:26:35', 0, '20221119000003', 'KMP004', '310', 'AKOM TAZAKKA', 'Jl. Ir. Haji Juanda Jl. Sukaseuri No.27, Cikampek Tim., Kec. Kota Baru, kab.karawang, Jawa Barat 41373', 0, 0, 467500, 0, 0),
('20221216000018', 'winasavana', '2022-12-16 11:39:49', 'admin', '2023-04-02 05:09:18', 0, '20221119000003', 'KMP005', '320', 'STMIK DCI', 'Jl. Sutisna Senjaya No.158-A, Cikalang, Kec. Tawang, Kab. Tasikmalaya, Jawa Barat 46112', 0, 0, 450000, 600000, 0),
('20221216000019', 'winasavana', '2022-12-16 11:40:53', 'winasavana', '2023-02-14 08:45:22', 0, '20221119000003', 'LPK001', '110', 'LPK CIKUMPAY', 'Cikumpay, Campaka Purwakarta', 0, 0, 0, 0, 0),
('20221217000001', 'winasavana', '2022-12-17 14:35:25', 'winasavana', '2023-01-18 14:04:53', 0, '20221119000005', 'PKL014', NULL, 'SMKN 1 PURWAKARTA', 'Jl. Industri No.Km.4, Babakancikao, Kec. Babakancikao, Kabupaten Purwakarta, Jawa Barat 41151', 0, 0, 0, 0, 0),
('20230109000001', 'sarah', '2023-01-09 02:57:43', 'sarah', '2023-06-19 14:57:50', 0, '20221119000005', 'PKL015', 'KINP', 'SMKS SUKAPURA', 'Tasikmalaya', 0, 0, 0, 0, 0),
('20230224000001', 'winasavana', '2023-02-24 13:46:40', 'sarah', '2023-06-06 13:45:10', 0, '20221119000005', 'PKL016', 'KINP', 'SMK MUHAMADIYAH CIKAMPEK', 'Jl. Raya Parakan No.70, Cikampek Utara, Kec. Kota Baru, Karawang, Jawa Barat 41373', 0, 0, 0, 0, 0),
('20230302000001', 'sarah', '2023-03-02 20:47:49', NULL, NULL, 0, '20221119000005', 'PKL017', 'KINP', 'SMK TEXMACO KARAWANG', '', 0, 0, 0, 0, 0),
('20230403000001', 'sarah', '2023-04-03 09:46:59', NULL, NULL, 0, '20221119000005', 'PKL018', 'KINP', 'SMK LENTERA BANGSA 2', 'Lemahabang, Karawang', 0, 0, 0, 0, 0),
('20230606000001', 'sarah', '2023-06-06 13:44:55', NULL, NULL, 0, '20221119000005', 'PKL019', 'KINP', 'SMK IPTEK CILAMAYA', 'CILAMAYA', 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `thr`
--

CREATE TABLE `thr` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `employee_id` varchar(30) DEFAULT NULL,
  `employee_number` varchar(30) DEFAULT NULL,
  `employee_name` varchar(100) DEFAULT NULL,
  `departement_name` varchar(100) DEFAULT NULL,
  `departement_sub_name` varchar(100) DEFAULT NULL,
  `date_sign` date DEFAULT NULL,
  `period` varchar(4) DEFAULT NULL,
  `services` varchar(50) DEFAULT NULL,
  `position_name` varchar(50) DEFAULT NULL,
  `marital` varchar(20) DEFAULT NULL,
  `tax_id` varchar(30) DEFAULT NULL,
  `salary` int(11) DEFAULT 0,
  `allowence` int(11) DEFAULT 0,
  `total` int(11) DEFAULT 0,
  `thr` int(11) NOT NULL DEFAULT 0,
  `pph` int(11) DEFAULT 0,
  `total_thr` int(11) DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT 0,
  `approved_to` varchar(30) DEFAULT NULL,
  `approved_by` varchar(30) DEFAULT NULL,
  `approved_date` timestamp NULL DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `departement_id` varchar(30) DEFAULT NULL,
  `number` varchar(30) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `username` varchar(30) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `position` varchar(50) DEFAULT NULL,
  `avatar` text DEFAULT NULL,
  `actived` tinyint(1) NOT NULL DEFAULT 0,
  `access` tinyint(1) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `created_by`, `created_date`, `updated_by`, `updated_date`, `approved`, `approved_to`, `approved_by`, `approved_date`, `deleted`, `departement_id`, `number`, `name`, `description`, `username`, `password`, `email`, `phone`, `position`, `avatar`, `actived`, `access`, `status`) VALUES
('20221110000001', 'admin', '2021-12-26 11:24:58', 'admin', '2022-12-31 15:03:19', 0, NULL, NULL, NULL, 0, NULL, '1', 'Administrator', '', 'admin', 'Login@190320', 'admin@aeconsys.com', '', 'Administrator', NULL, 0, 0, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users_m`
--

CREATE TABLE `users_m` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `departement_id` varchar(30) DEFAULT NULL,
  `number` varchar(30) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `avatar` text DEFAULT NULL,
  `token` varchar(50) DEFAULT NULL,
  `device_id` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `violations`
--

CREATE TABLE `violations` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `number` varchar(30) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `violations`
--

INSERT INTO `violations` (`id`, `created_by`, `created_date`, `updated_by`, `updated_date`, `deleted`, `number`, `name`, `description`, `status`) VALUES
('20230508000001', 'fitri', '2023-05-08 11:25:29', NULL, NULL, 0, 'SP001', 'Melakukan Kesalahan', '', 0),
('20230508000002', 'fitri', '2023-05-08 11:25:42', NULL, NULL, 0, 'SP002', 'Tingkah Laku', '', 0),
('20230508000003', 'fitri', '2023-05-08 11:25:56', NULL, NULL, 0, 'SP003', 'Berkelahi', '', 0),
('20230508000004', 'fitri', '2023-05-08 11:26:13', NULL, NULL, 0, 'SP004', 'Mencuri', '', 0),
('20230508000005', 'fitri', '2023-05-08 11:26:35', NULL, NULL, 0, 'SP005', 'Ketidak Hadiran', '', 0),
('20230508000006', 'fitri', '2023-05-08 11:26:52', NULL, NULL, 0, 'SP006', 'Menolak Perintah', '', 0),
('20230508000007', 'fitri', '2023-05-08 11:27:12', NULL, NULL, 0, 'SP007', 'Minum Minuman Keras', '', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `warning_letters`
--

CREATE TABLE `warning_letters` (
  `id` varchar(30) NOT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT 0,
  `approved_to` varchar(30) DEFAULT NULL,
  `approved_by` varchar(30) DEFAULT NULL,
  `approved_date` timestamp NULL DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `employee_id` varchar(30) DEFAULT NULL,
  `violation_id` varchar(30) DEFAULT NULL,
  `trans_date` date DEFAULT NULL,
  `warning_letter` varchar(30) DEFAULT NULL,
  `issue_date` date DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `number` (`number`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indeks untuk tabel `account_coa`
--
ALTER TABLE `account_coa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indeks untuk tabel `agreements`
--
ALTER TABLE `agreements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `number` (`number`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indeks untuk tabel `allowances`
--
ALTER TABLE `allowances`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `number` (`number`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indeks untuk tabel `allowance_cash_carries`
--
ALTER TABLE `allowance_cash_carries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indeks untuk tabel `allowance_students`
--
ALTER TABLE `allowance_students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `allowance_students_ibfk_3` (`group_id`);

--
-- Indeks untuk tabel `approvals`
--
ALTER TABLE `approvals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indeks untuk tabel `attandances`
--
ALTER TABLE `attandances`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `attandances_temp`
--
ALTER TABLE `attandances_temp`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indeks untuk tabel `attandance_generates`
--
ALTER TABLE `attandance_generates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indeks untuk tabel `bpjs`
--
ALTER TABLE `bpjs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `number` (`number`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indeks untuk tabel `calendars`
--
ALTER TABLE `calendars`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indeks untuk tabel `cash_carries`
--
ALTER TABLE `cash_carries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indeks untuk tabel `change_days`
--
ALTER TABLE `change_days`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indeks untuk tabel `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `from_users_id` (`from_users_id`),
  ADD KEY `to_users_id` (`to_users_id`);

--
-- Indeks untuk tabel `coachings`
--
ALTER TABLE `coachings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indeks untuk tabel `config`
--
ALTER TABLE `config`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indeks untuk tabel `contracts`
--
ALTER TABLE `contracts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `number` (`number`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indeks untuk tabel `corrections`
--
ALTER TABLE `corrections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indeks untuk tabel `cutoff`
--
ALTER TABLE `cutoff`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indeks untuk tabel `deductions`
--
ALTER TABLE `deductions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `number` (`number`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indeks untuk tabel `departements`
--
ALTER TABLE `departements`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `number` (`number`),
  ADD KEY `division_id` (`created_by`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `departements_ibfk_3` (`division_id`);

--
-- Indeks untuk tabel `departement_subs`
--
ALTER TABLE `departement_subs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `number` (`number`),
  ADD KEY `division_id` (`created_by`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `departement_subs_ibfk_3` (`division_id`),
  ADD KEY `departement_subs_ibfk_4` (`departement_id`);

--
-- Indeks untuk tabel `divisions`
--
ALTER TABLE `divisions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `number` (`number`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indeks untuk tabel `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `number` (`number`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indeks untuk tabel `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `number` (`number`) USING BTREE,
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `division_id` (`division_id`),
  ADD KEY `departement_id` (`departement_id`),
  ADD KEY `departement_sub_id` (`departement_sub_id`);

--
-- Indeks untuk tabel `employee_candidates`
--
ALTER TABLE `employee_candidates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `request_id` (`request_id`);

--
-- Indeks untuk tabel `employee_carrers`
--
ALTER TABLE `employee_carrers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `number` (`number`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indeks untuk tabel `employee_educations`
--
ALTER TABLE `employee_educations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `number` (`number`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indeks untuk tabel `employee_experiences`
--
ALTER TABLE `employee_experiences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `number` (`number`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indeks untuk tabel `employee_familys`
--
ALTER TABLE `employee_familys`
  ADD PRIMARY KEY (`id`),
  ADD KEY `number` (`number`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indeks untuk tabel `employee_requests`
--
ALTER TABLE `employee_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indeks untuk tabel `employee_trainings`
--
ALTER TABLE `employee_trainings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `number` (`number`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indeks untuk tabel `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `number` (`number`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indeks untuk tabel `job_types`
--
ALTER TABLE `job_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indeks untuk tabel `kinenta_jpk`
--
ALTER TABLE `kinenta_jpk`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `number` (`number`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indeks untuk tabel `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indeks untuk tabel `logins`
--
ALTER TABLE `logins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indeks untuk tabel `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indeks untuk tabel `maritals`
--
ALTER TABLE `maritals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `number` (`number`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indeks untuk tabel `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indeks untuk tabel `mutations`
--
ALTER TABLE `mutations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indeks untuk tabel `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `approvals_id` (`approvals_id`);

--
-- Indeks untuk tabel `notifications_m`
--
ALTER TABLE `notifications_m`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `number` (`number`);

--
-- Indeks untuk tabel `overtimes`
--
ALTER TABLE `overtimes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indeks untuk tabel `overtime_budgets`
--
ALTER TABLE `overtime_budgets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `departement_id` (`departement_id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indeks untuk tabel `payrolls`
--
ALTER TABLE `payrolls`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indeks untuk tabel `payroll_config`
--
ALTER TABLE `payroll_config`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `payroll_pkl`
--
ALTER TABLE `payroll_pkl`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indeks untuk tabel `permits`
--
ALTER TABLE `permits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `permit_type_id` (`permit_type_id`),
  ADD KEY `reason_id` (`reason_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indeks untuk tabel `permit_types`
--
ALTER TABLE `permit_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `number` (`number`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indeks untuk tabel `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `number` (`number`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indeks untuk tabel `privilege_groups`
--
ALTER TABLE `privilege_groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indeks untuk tabel `ptkp`
--
ALTER TABLE `ptkp`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `marital_id` (`marital_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indeks untuk tabel `reasons`
--
ALTER TABLE `reasons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `permit_type_id` (`permit_type_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indeks untuk tabel `reason_resignations`
--
ALTER TABLE `reason_resignations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `number` (`number`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indeks untuk tabel `religions`
--
ALTER TABLE `religions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `number` (`number`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indeks untuk tabel `requirements`
--
ALTER TABLE `requirements`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `number` (`number`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indeks untuk tabel `resignations`
--
ALTER TABLE `resignations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `reason_resignation_id` (`reason_resignation_id`);

--
-- Indeks untuk tabel `salary_components`
--
ALTER TABLE `salary_components`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `number` (`number`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indeks untuk tabel `setting_menus`
--
ALTER TABLE `setting_menus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `menus_id` (`menus_id`);

--
-- Indeks untuk tabel `setting_users`
--
ALTER TABLE `setting_users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `users_id` (`users_id`),
  ADD KEY `menus_id` (`menus_id`);

--
-- Indeks untuk tabel `setup_allowances`
--
ALTER TABLE `setup_allowances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `allowance_id` (`allowance_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indeks untuk tabel `setup_cash_carries`
--
ALTER TABLE `setup_cash_carries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `allowance_id` (`allowance_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indeks untuk tabel `setup_deductions`
--
ALTER TABLE `setup_deductions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `deduction_id` (`deduction_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indeks untuk tabel `setup_salaries`
--
ALTER TABLE `setup_salaries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indeks untuk tabel `shifts`
--
ALTER TABLE `shifts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `number` (`number`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indeks untuk tabel `shift_details`
--
ALTER TABLE `shift_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shift_id` (`shift_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indeks untuk tabel `shift_employees`
--
ALTER TABLE `shift_employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_id` (`employee_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `shift_id` (`shift_id`);

--
-- Indeks untuk tabel `sources`
--
ALTER TABLE `sources`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `number` (`number`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `sources_ibfk_3` (`group_id`);

--
-- Indeks untuk tabel `thr`
--
ALTER TABLE `thr`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indeks untuk tabel `users_m`
--
ALTER TABLE `users_m`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `number` (`number`);

--
-- Indeks untuk tabel `violations`
--
ALTER TABLE `violations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `number` (`number`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`);

--
-- Indeks untuk tabel `warning_letters`
--
ALTER TABLE `warning_letters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `violation_id` (`violation_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `attandances_temp`
--
ALTER TABLE `attandances_temp`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `logins`
--
ALTER TABLE `logins`
  MODIFY `id` bigint(30) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `logs`
--
ALTER TABLE `logs`
  MODIFY `id` bigint(30) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `payroll_config`
--
ALTER TABLE `payroll_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `accounts_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `accounts_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `account_coa`
--
ALTER TABLE `account_coa`
  ADD CONSTRAINT `account_coa_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `account_coa_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `account_coa_ibfk_3` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `agreements`
--
ALTER TABLE `agreements`
  ADD CONSTRAINT `agreements_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `agreements_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `agreements_ibfk_3` FOREIGN KEY (`number`) REFERENCES `employees` (`number`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `allowances`
--
ALTER TABLE `allowances`
  ADD CONSTRAINT `allowances_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `allowances_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `allowance_cash_carries`
--
ALTER TABLE `allowance_cash_carries`
  ADD CONSTRAINT `allowance_cash_carries_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `allowance_cash_carries_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `allowance_students`
--
ALTER TABLE `allowance_students`
  ADD CONSTRAINT `allowance_students_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `allowance_students_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `allowance_students_ibfk_3` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `approvals`
--
ALTER TABLE `approvals`
  ADD CONSTRAINT `approvals_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `approvals_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `attandances_temp`
--
ALTER TABLE `attandances_temp`
  ADD CONSTRAINT `attandances_temp_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `attandances_temp_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `attandance_generates`
--
ALTER TABLE `attandance_generates`
  ADD CONSTRAINT `attandance_generates_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `bpjs`
--
ALTER TABLE `bpjs`
  ADD CONSTRAINT `bpjs_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bpjs_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `calendars`
--
ALTER TABLE `calendars`
  ADD CONSTRAINT `calendars_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `calendars_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `cash_carries`
--
ALTER TABLE `cash_carries`
  ADD CONSTRAINT `cash_carries_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cash_carries_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cash_carries_ibfk_3` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `change_days`
--
ALTER TABLE `change_days`
  ADD CONSTRAINT `change_days_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `change_days_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `change_days_ibfk_3` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `chats`
--
ALTER TABLE `chats`
  ADD CONSTRAINT `chats_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `chats_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `chats_ibfk_3` FOREIGN KEY (`from_users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `chats_ibfk_4` FOREIGN KEY (`to_users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `coachings`
--
ALTER TABLE `coachings`
  ADD CONSTRAINT `coachings_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `coachings_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `coachings_ibfk_3` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `config`
--
ALTER TABLE `config`
  ADD CONSTRAINT `config_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `config_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `contracts`
--
ALTER TABLE `contracts`
  ADD CONSTRAINT `contracts_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `contracts_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `corrections`
--
ALTER TABLE `corrections`
  ADD CONSTRAINT `corrections_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `corrections_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `corrections_ibfk_3` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `cutoff`
--
ALTER TABLE `cutoff`
  ADD CONSTRAINT `cutoff_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cutoff_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `deductions`
--
ALTER TABLE `deductions`
  ADD CONSTRAINT `deductions_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `deductions_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `departements`
--
ALTER TABLE `departements`
  ADD CONSTRAINT `departements_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `departements_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `departements_ibfk_3` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `departement_subs`
--
ALTER TABLE `departement_subs`
  ADD CONSTRAINT `departement_subs_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `departement_subs_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `departement_subs_ibfk_3` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `departement_subs_ibfk_4` FOREIGN KEY (`departement_id`) REFERENCES `departements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `divisions`
--
ALTER TABLE `divisions`
  ADD CONSTRAINT `divisions_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `divisions_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documents_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `documents_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `employees_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `employees_ibfk_3` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `employees_ibfk_4` FOREIGN KEY (`departement_id`) REFERENCES `departements` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `employees_ibfk_5` FOREIGN KEY (`departement_sub_id`) REFERENCES `departement_subs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `employee_candidates`
--
ALTER TABLE `employee_candidates`
  ADD CONSTRAINT `employee_candidates_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `employee_candidates_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `employee_candidates_ibfk_3` FOREIGN KEY (`request_id`) REFERENCES `employee_requests` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `employee_carrers`
--
ALTER TABLE `employee_carrers`
  ADD CONSTRAINT `employee_carrers_ibfk_1` FOREIGN KEY (`number`) REFERENCES `employees` (`number`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `employee_carrers_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `employee_carrers_ibfk_3` FOREIGN KEY (`updated_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `employee_educations`
--
ALTER TABLE `employee_educations`
  ADD CONSTRAINT `employee_educations_ibfk_1` FOREIGN KEY (`number`) REFERENCES `employees` (`number`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `employee_educations_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `employee_educations_ibfk_3` FOREIGN KEY (`updated_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `employee_experiences`
--
ALTER TABLE `employee_experiences`
  ADD CONSTRAINT `employee_experiences_ibfk_1` FOREIGN KEY (`number`) REFERENCES `employees` (`number`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `employee_experiences_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `employee_experiences_ibfk_3` FOREIGN KEY (`updated_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `employee_familys`
--
ALTER TABLE `employee_familys`
  ADD CONSTRAINT `employee_familys_ibfk_1` FOREIGN KEY (`number`) REFERENCES `employees` (`number`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `employee_familys_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `employee_familys_ibfk_3` FOREIGN KEY (`updated_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `employee_requests`
--
ALTER TABLE `employee_requests`
  ADD CONSTRAINT `employee_requests_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `employee_requests_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `employee_trainings`
--
ALTER TABLE `employee_trainings`
  ADD CONSTRAINT `employee_trainings_ibfk_1` FOREIGN KEY (`number`) REFERENCES `employees` (`number`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `employee_trainings_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `employee_trainings_ibfk_3` FOREIGN KEY (`updated_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `groups`
--
ALTER TABLE `groups`
  ADD CONSTRAINT `groups_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `groups_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `job_types`
--
ALTER TABLE `job_types`
  ADD CONSTRAINT `job_types_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `job_types_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `job_types_ibfk_3` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `kinenta_jpk`
--
ALTER TABLE `kinenta_jpk`
  ADD CONSTRAINT `kinenta_jpk_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kinenta_jpk_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `loans`
--
ALTER TABLE `loans`
  ADD CONSTRAINT `loans_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `loans_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `loans_ibfk_3` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `logins`
--
ALTER TABLE `logins`
  ADD CONSTRAINT `logins_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
