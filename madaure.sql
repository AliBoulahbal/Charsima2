-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 28 déc. 2025 à 13:06
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `madaure`
--

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `deliveries`
--

CREATE TABLE `deliveries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `school_id` bigint(20) UNSIGNED DEFAULT NULL,
  `distributor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `delivery_date` date NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` int(11) NOT NULL,
  `total_price` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `kiosk_id` bigint(20) UNSIGNED DEFAULT NULL,
  `delivery_type` varchar(255) NOT NULL DEFAULT 'school',
  `discount_percentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `final_price` int(11) NOT NULL DEFAULT 0,
  `paid_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `remaining_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `payment_status` varchar(255) NOT NULL DEFAULT 'unpaid',
  `status` varchar(255) NOT NULL DEFAULT 'confirmed',
  `transaction_id` varchar(255) DEFAULT NULL,
  `payment_method` varchar(255) NOT NULL DEFAULT 'cash',
  `teacher_name` varchar(255) DEFAULT NULL,
  `teacher_phone` varchar(255) DEFAULT NULL,
  `teacher_subject` varchar(255) DEFAULT NULL,
  `teacher_email` varchar(255) DEFAULT NULL,
  `customer_cin` varchar(255) DEFAULT NULL,
  `delivery_address` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `wilaya` varchar(255) DEFAULT NULL,
  `online_payment_status` varchar(255) DEFAULT NULL,
  `payment_code` varchar(255) DEFAULT NULL,
  `payment_code_expires_at` timestamp NULL DEFAULT NULL,
  `payment_confirmation_date` timestamp NULL DEFAULT NULL,
  `payment_confirmed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `payment_receipt_number` varchar(255) DEFAULT NULL,
  `bank_deposit_slip` varchar(255) DEFAULT NULL,
  `payment_verification_notes` text DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `location_validated` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `deliveries`
--

INSERT INTO `deliveries` (`id`, `school_id`, `distributor_id`, `delivery_date`, `quantity`, `unit_price`, `total_price`, `created_at`, `updated_at`, `kiosk_id`, `delivery_type`, `discount_percentage`, `final_price`, `paid_amount`, `remaining_amount`, `payment_status`, `status`, `transaction_id`, `payment_method`, `teacher_name`, `teacher_phone`, `teacher_subject`, `teacher_email`, `customer_cin`, `delivery_address`, `notes`, `wilaya`, `online_payment_status`, `payment_code`, `payment_code_expires_at`, `payment_confirmation_date`, `payment_confirmed_by`, `payment_receipt_number`, `bank_deposit_slip`, `payment_verification_notes`, `latitude`, `longitude`, `location_validated`) VALUES
(1, 13, 1, '2025-12-21', 500, 1000, 500000, '2025-12-21 11:09:57', '2025-12-21 11:09:57', NULL, 'school', 20.00, 400000, 0.00, 0.00, 'unpaid', 'confirmed', 'SCH-20251221-G9U3HO', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Djelfa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(2, 12, NULL, '2025-12-21', 250, 1000, 250000, '2025-12-21 11:10:33', '2025-12-21 11:10:33', NULL, 'school', 0.00, 250000, 0.00, 0.00, 'unpaid', 'pending', 'SCH-20251221-KX8KBF', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Djelfa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 34.75374080, 5.90807040, 0),
(3, 70, NULL, '2025-12-21', 250, 1000, 250000, '2025-12-21 11:16:55', '2025-12-21 11:16:55', NULL, 'school', 0.00, 250000, 0.00, 0.00, 'unpaid', 'pending', 'SCH-20251221-H8IDXW', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Djelfa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 34.75374080, 5.90807040, 0),
(4, 12, NULL, '2025-12-21', 500, 1000, 500000, '2025-12-21 11:34:00', '2025-12-21 11:34:00', NULL, 'school', 0.00, 500000, 0.00, 0.00, 'unpaid', 'pending', 'SCH-20251221-A5SC8R', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Djelfa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 34.75374080, 5.90807040, 0),
(5, 12, NULL, '2025-12-21', 50, 1000, 50000, '2025-12-21 11:58:14', '2025-12-22 09:46:25', NULL, 'school', 0.00, 50000, 50000.00, 0.00, 'paid', 'pending', 'SCH-20251221-HBABNF', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Djelfa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 35.55100430, 6.17214620, 0),
(6, 11, NULL, '2025-12-21', 20, 1000, 20000, '2025-12-21 11:58:55', '2025-12-21 11:58:55', NULL, 'school', 0.00, 20000, 0.00, 0.00, 'unpaid', 'pending', 'SCH-20251221-PAU95X', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Djelfa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 35.55100430, 6.17214620, 0),
(7, 70, NULL, '2025-12-21', 10, 1000, 10000, '2025-12-21 12:21:19', '2025-12-21 12:21:19', NULL, 'school', 0.00, 10000, 0.00, 0.00, 'unpaid', 'pending', 'SCH-20251221-03S1YJ', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Djelfa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 34.75374080, 5.90807040, 0),
(8, 14, NULL, '2025-12-21', 10, 1000, 10000, '2025-12-21 12:24:36', '2025-12-22 10:31:35', NULL, 'school', 0.00, 10000, 10000.00, 0.00, 'paid', 'pending', 'SCH-20251221-SZD8QC', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Djelfa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 34.75374080, 5.90807040, 0),
(9, 999, NULL, '2025-12-22', 500, 1000, 500000, '2025-12-22 08:40:12', '2025-12-22 09:45:00', NULL, 'school', 0.00, 500000, 500000.00, 0.00, 'paid', 'completed', 'SCH-20251222-YFCZHC', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Biskra', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(10, 999, NULL, '2025-12-22', 250, 1000, 250000, '2025-12-22 08:40:55', '2025-12-22 10:16:14', NULL, 'school', 0.00, 250000, 250000.00, 0.00, 'paid', 'completed', 'SCH-20251222-60ZIGO', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Biskra', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(11, 392, NULL, '2025-12-22', 50, 1000, 50000, '2025-12-22 08:54:23', '2025-12-22 10:29:04', NULL, 'school', 0.00, 50000, 50000.00, 0.00, 'paid', 'completed', 'SCH-20251222-GVVIXQ', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'M\'Sila', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(12, 499, NULL, '2025-12-22', 520, 1000, 520000, '2025-12-22 08:55:04', '2025-12-22 09:47:23', NULL, 'school', 0.00, 520000, 520000.00, 0.00, 'paid', 'completed', 'SCH-20251222-5A7MBU', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Constantine', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(13, 10, NULL, '2025-12-22', 50, 1000, 50000, '2025-12-22 09:03:56', '2025-12-22 09:49:21', NULL, 'school', 0.00, 50000, 50000.00, 0.00, 'paid', 'completed', 'SCH-20251222-WH6YUK', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Djelfa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(14, 710, NULL, '2025-12-22', 25, 1000, 25000, '2025-12-22 09:24:06', '2025-12-22 10:51:06', NULL, 'school', 0.00, 25000, 25000.00, 0.00, 'paid', 'completed', 'SCH-20251222-IOWP8R', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Skikda', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(15, 874, NULL, '2025-12-22', 50, 1000, 50000, '2025-12-22 10:29:23', '2025-12-22 10:52:56', NULL, 'school', 0.00, 50000, 50000.00, 0.00, 'paid', 'completed', 'SCH-20251222-QCJUZL', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Annaba', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(16, 941, NULL, '2025-12-22', 250, 1000, 250000, '2025-12-22 10:34:30', '2025-12-23 08:15:02', NULL, 'school', 0.00, 250000, 250000.00, 0.00, 'paid', 'completed', 'SCH-20251222-2MGQDZ', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Biskra', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(17, 1038, NULL, '2025-12-22', 150, 1000, 150000, '2025-12-22 10:43:46', '2025-12-22 10:44:02', NULL, 'school', 0.00, 150000, 150000.00, 0.00, 'paid', 'completed', 'SCH-20251222-EVYQ7N', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Biskra', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(18, 874, NULL, '2025-12-22', 50, 1000, 50000, '2025-12-22 10:50:52', '2025-12-22 10:50:52', NULL, 'school', 0.00, 50000, 0.00, 0.00, 'unpaid', 'completed', 'SCH-20251222-VXXSEB', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Annaba', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(19, 874, NULL, '2025-12-22', 250, 1000, 250000, '2025-12-22 10:52:40', '2025-12-22 10:52:40', NULL, 'school', 0.00, 250000, 0.00, 0.00, 'unpaid', 'completed', 'SCH-20251222-ENOBUK', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Annaba', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(20, 874, NULL, '2025-12-22', 50, 1000, 50000, '2025-12-22 11:04:23', '2025-12-22 11:04:23', NULL, 'school', 0.00, 50000, 0.00, 0.00, 'unpaid', 'completed', 'SCH-20251222-ZEC2ZW', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Annaba', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(21, 1038, 1, '2025-12-23', 500, 1000, 500000, '2025-12-23 09:04:48', '2025-12-23 13:13:11', NULL, 'school', 0.00, 500000, 0.00, 0.00, 'unpaid', 'completed', 'SCH-20251223-COQ0L2', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Biskra', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 37.42199830, -122.08400000, 0),
(22, 605, 1, '2025-12-23', 500, 1000, 500000, '2025-12-23 09:05:35', '2025-12-23 09:05:35', NULL, 'school', 0.00, 500000, 0.00, 0.00, 'unpaid', 'pending', 'SCH-20251223-IK0ANY', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Constantine', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 37.42199830, -122.08400000, 0),
(23, 607, 1, '2025-12-23', 10, 1000, 10000, '2025-12-23 09:13:02', '2025-12-23 09:13:02', NULL, 'school', 0.00, 10000, 0.00, 0.00, 'unpaid', 'pending', 'SCH-20251223-NPXNSX', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Constantine', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 37.42199830, -122.08400000, 0),
(24, 713, 1, '2025-12-23', 10, 1000, 10000, '2025-12-23 09:19:29', '2025-12-23 11:02:43', NULL, 'school', 0.00, 10000, 0.00, 0.00, 'unpaid', 'completed', 'SCH-20251223-HUSPTQ', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Skikda', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 37.42199830, -122.08400000, 0),
(25, 863, 1, '2025-12-23', 150, 1000, 150000, '2025-12-23 09:22:50', '2025-12-23 09:22:50', NULL, 'school', 0.00, 150000, 0.00, 0.00, 'unpaid', 'pending', 'SCH-20251223-NL2VCQ', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Annaba', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 35.48774400, 6.93043200, 0),
(26, 713, 1, '2025-12-23', 10, 1000, 10000, '2025-12-23 09:29:14', '2025-12-23 09:29:14', NULL, 'school', 0.00, 10000, 0.00, 0.00, 'unpaid', 'pending', 'SCH-20251223-BDWRYI', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Skikda', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 35.48774400, 6.93043200, 0),
(27, 10, 1, '2025-12-23', 10, 1000, 10000, '2025-12-23 09:33:16', '2025-12-23 13:08:51', NULL, 'school', 0.00, 10000, 0.00, 0.00, 'unpaid', 'completed', 'SCH-20251223-MKKPPC', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Djelfa', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 35.55099980, 6.17214050, 0),
(28, 392, 1, '2025-12-23', 5, 1000, 5000, '2025-12-23 09:39:39', '2025-12-23 09:39:39', NULL, 'school', 0.00, 5000, 0.00, 0.00, 'unpaid', 'pending', 'SCH-20251223-FFNXCN', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'M\'Sila', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 35.55099980, 6.17214050, 0),
(29, 874, 1, '2025-12-24', 50, 1000, 50000, '2025-12-24 07:42:41', '2025-12-27 09:40:28', NULL, 'school', 0.00, 50000, 0.00, 0.00, 'unpaid', 'completed', 'SCH-20251224-4OYMKT', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Annaba', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 34.43589120, 5.05282560, 0),
(30, 875, 1, '2025-12-24', 150, 1000, 150000, '2025-12-24 07:43:13', '2025-12-24 07:46:24', NULL, 'school', 0.00, 150000, 0.00, 0.00, 'unpaid', 'completed', 'SCH-20251224-HV1CGD', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Annaba', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 34.43589120, 5.05282560, 0),
(31, 941, 1, '2025-12-24', 25, 1000, 25000, '2025-12-24 07:45:36', '2025-12-24 08:24:36', NULL, 'school', 0.00, 25000, 0.00, 0.00, 'unpaid', 'completed', 'SCH-20251224-2T33L3', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Biskra', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 34.43589120, 5.05282560, 0),
(32, 874, 1, '2025-12-24', 5, 1000, 5000, '2025-12-24 07:47:30', '2025-12-24 08:42:40', NULL, 'school', 0.00, 5000, 0.00, 0.00, 'unpaid', 'completed', 'SCH-20251224-INUDLQ', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Annaba', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 34.43589120, 5.05282560, 0),
(33, 1038, 1, '2025-12-24', 25, 1000, 25000, '2025-12-24 08:24:57', '2025-12-24 08:24:57', NULL, 'school', 0.00, 25000, 0.00, 0.00, 'unpaid', 'pending', 'SCH-20251224-GG9FMZ', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Biskra', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 35.55100340, 6.17214880, 0),
(34, 874, 1, '2025-12-24', 10, 1000, 10000, '2025-12-24 08:42:52', '2025-12-24 08:42:52', NULL, 'school', 0.00, 10000, 0.00, 0.00, 'unpaid', 'pending', 'SCH-20251224-5CXOYQ', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Annaba', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 34.43589120, 5.05282560, 0),
(35, 874, 1, '2025-12-24', 510, 1000, 510000, '2025-12-24 08:44:23', '2025-12-24 08:44:23', NULL, 'school', 0.00, 510000, 0.00, 0.00, 'unpaid', 'pending', 'SCH-20251224-YEI9ZR', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Annaba', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 34.43589120, 5.05282560, 0),
(36, 874, 1, '2025-12-24', 20, 1000, 20000, '2025-12-24 09:15:06', '2025-12-24 09:15:06', NULL, 'school', 0.00, 20000, 0.00, 0.00, 'unpaid', 'pending', 'SCH-20251224-1LVJ1O', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Annaba', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 35.55101280, 6.17216250, 0),
(37, 874, 1, '2025-12-25', 50, 1000, 50000, '2025-12-25 10:47:51', '2025-12-25 10:47:51', NULL, 'school', 0.00, 50000, 0.00, 0.00, 'unpaid', 'pending', 'SCH-20251225-4QGMFP', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Annaba', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 35.54956450, 6.18453130, 0),
(38, 1020, 2, '2025-12-25', 1000, 1000, 1000000, '2025-12-25 13:16:56', '2025-12-25 13:16:56', NULL, 'school', 10.00, 900000, 0.00, 0.00, 'unpaid', 'confirmed', 'SCH-20251225-HHZLDH', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Biskra', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0),
(39, 477, 1, '2025-12-27', 25, 1000, 25000, '2025-12-27 07:32:09', '2025-12-28 10:55:37', NULL, 'school', 0.00, 25000, 0.00, 0.00, 'unpaid', 'completed', 'SCH-20251227-HLLQQ8', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'El M\'Ghair', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 35.54017280, 6.18332160, 0),
(40, 874, 1, '2025-12-27', 25, 1000, 25000, '2025-12-27 07:36:53', '2025-12-27 08:31:14', NULL, 'school', 0.00, 25000, 0.00, 0.00, 'unpaid', 'completed', 'SCH-20251227-N3TT8I', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Annaba', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 35.54017280, 6.18332160, 0),
(41, 1038, 1, '2025-12-27', 25, 1000, 25000, '2025-12-27 07:47:40', '2025-12-27 07:47:40', NULL, 'school', 0.00, 25000, 0.00, 0.00, 'unpaid', 'pending', 'SCH-20251227-LQMJEV', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Biskra', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 35.54017280, 6.18332160, 0),
(42, 1042, 1, '2025-12-27', 1000, 1000, 1000000, '2025-12-27 07:50:47', '2025-12-27 10:17:36', NULL, 'school', 0.00, 1000000, 0.00, 0.00, 'unpaid', 'completed', 'SCH-20251227-XQDQVE', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Constantine', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 35.54017280, 6.18332160, 0),
(43, 1042, 1, '2025-12-27', 50, 1000, 50000, '2025-12-27 07:53:42', '2025-12-27 07:53:42', NULL, 'school', 0.00, 50000, 0.00, 0.00, 'unpaid', 'pending', 'SCH-20251227-KHKLIX', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Constantine', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 35.54017280, 6.18332160, 0),
(44, 710, 1, '2025-12-27', 1000, 1000, 1000000, '2025-12-27 08:05:27', '2025-12-27 10:37:24', NULL, 'school', 0.00, 1000000, 0.00, 0.00, 'unpaid', 'completed', 'SCH-20251227-LHAEEU', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Skikda', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 35.55100190, 6.17216040, 0),
(45, 1042, 1, '2025-12-27', 500, 1000, 500000, '2025-12-27 08:33:33', '2025-12-27 08:33:33', NULL, 'school', 0.00, 500000, 0.00, 0.00, 'unpaid', 'pending', 'SCH-20251227-K24LKO', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Constantine', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 35.55100600, 6.17214810, 0),
(46, 1042, 1, '2025-12-27', 10, 1000, 10000, '2025-12-27 09:39:39', '2025-12-27 10:18:05', NULL, 'school', 0.00, 10000, 0.00, 0.00, 'unpaid', 'completed', 'SCH-20251227-RFKCVN', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Constantine', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 35.54017280, 6.18332160, 0),
(47, 1043, 1, '2025-12-27', 1000, 1000, 1000000, '2025-12-27 10:17:19', '2025-12-27 10:17:19', NULL, 'school', 0.00, 1000000, 0.00, 0.00, 'unpaid', 'pending', 'SCH-20251227-RXHSKZ', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Constantine', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 35.42876160, 7.16636160, 0),
(48, 1047, 1, '2025-12-28', 50, 1000, 50000, '2025-12-28 10:56:03', '2025-12-28 10:56:03', NULL, 'school', 0.00, 50000, 0.00, 0.00, 'unpaid', 'pending', 'SCH-20251228-HZN09K', 'cash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'M\'Sila', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 34.87170560, 5.73767680, 0);

-- --------------------------------------------------------

--
-- Structure de la table `distributors`
--

CREATE TABLE `distributors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `wilaya` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `distributors`
--

INSERT INTO `distributors` (`id`, `name`, `phone`, `wilaya`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'ALI BOULAHBAL', '0550825346', 'Djelfa', 2, '2025-12-21 11:04:08', '2025-12-21 11:07:56'),
(2, 'bahi', NULL, 'Biskra', 3, '2025-12-23 07:17:30', '2025-12-23 07:17:30'),
(3, 'ahmed ben ahmed', NULL, 'Béjaïa', 4, '2025-12-25 10:36:37', '2025-12-25 10:36:37');

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
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
-- Structure de la table `jobs`
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
-- Structure de la table `job_batches`
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
-- Structure de la table `kiosks`
--

CREATE TABLE `kiosks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `owner_name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text NOT NULL,
  `wilaya` varchar(255) NOT NULL,
  `district` varchar(255) NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `kiosks`
--

INSERT INTO `kiosks` (`id`, `name`, `owner_name`, `phone`, `email`, `address`, `wilaya`, `district`, `latitude`, `longitude`, `is_active`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'Kiosque Alger Centre', 'Ahmed Benali', '0551234567', 'kiosque.alger@example.com', 'Rue Didouche Mourad, Alger', 'Alger', 'Alger Centre', NULL, NULL, 1, NULL, '2025-12-25 10:51:28', '2025-12-25 10:51:28'),
(2, 'Kiosque Oran', 'Fatima Zohra', '0779876543', 'kiosque.oran@example.com', 'Place du 1er Novembre, Oran', 'Oran', 'Oran Centre', NULL, NULL, 1, NULL, '2025-12-25 10:51:28', '2025-12-25 10:51:28'),
(3, 'Kiosque Constantine', 'Mohamed Boudiaf', '0665432198', 'kiosque.constantine@example.com', 'Avenue Ali Mendjeli, Constantine', 'Constantine', 'Constantine Centre', NULL, NULL, 1, NULL, '2025-12-25 10:51:28', '2025-12-25 10:51:28');

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_01_01_000001_create_distributors_table', 1),
(5, '2025_01_01_000002_create_schools_table', 1),
(6, '2025_01_01_000003_create_deliveries_table', 1),
(7, '2025_01_01_000004_create_payments_table', 1),
(8, '2025_12_09_083803_create_personal_access_tokens_table', 1),
(9, '2025_12_09_084444_create_permission_tables', 1),
(10, '2025_12_11_084121_add_gps_columns_to_schools_table', 1),
(11, '2025_12_11_112159_create_kiosks_table', 1),
(12, '2025_12_11_112243_add_kiosk_fields_to_deliveries_table', 1),
(13, '2025_12_11_112321_add_school_fields_to_payments_table', 1),
(14, '2025_12_12_175621_update_deliveries_foreign_keys_to_nullable', 1),
(15, '2025_12_12_180905_add_delivery_id_to_payments_table', 1),
(16, '2025_12_13_094341_make_delivery_foreign_keys_nullable', 1),
(17, '2025_12_13_095313_make_payment_foreign_keys_nullable', 1),
(18, '2025_12_13_095648_make_distributor_id_in_payments_nullable', 1),
(19, '2025_12_13_123558_add_payment_type_to_payments_table', 1),
(20, '2025_12_14_093859_add_commune_to_schools_table', 1),
(21, '2025_12_14_131013_add_phone_to_users_table', 1),
(22, '2025_12_14_142822_add_location_fields_to_deliveries_table', 1),
(23, '2025_12_15_093530_add_phone_to_users_table', 1),
(24, '2025_12_15_095922_add_address_to_schools_table', 1),
(25, '2025_12_21_105302_add_amount_to_payments_table', 1),
(26, '2025_12_21_114506_add_payment_columns_to_deliveries_table', 1),
(27, '2025_12_21_114742_add_payment_tracking_to_deliveries', 1);

-- --------------------------------------------------------

--
-- Structure de la table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(3, 'App\\Models\\User', 2),
(3, 'App\\Models\\User', 3),
(3, 'App\\Models\\User', 4),
(5, 'App\\Models\\User', 1);

-- --------------------------------------------------------

--
-- Structure de la table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `distributor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` int(11) NOT NULL,
  `payment_date` date NOT NULL,
  `method` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `kiosk_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payment_type` varchar(50) DEFAULT NULL,
  `school_id` bigint(20) UNSIGNED DEFAULT NULL,
  `delivery_id` bigint(20) UNSIGNED DEFAULT NULL,
  `school_name` varchar(255) DEFAULT NULL,
  `wilaya` varchar(255) DEFAULT NULL,
  `reference_number` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `confirmed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `payments`
--

INSERT INTO `payments` (`id`, `distributor_id`, `amount`, `payment_date`, `method`, `created_at`, `updated_at`, `kiosk_id`, `payment_type`, `school_id`, `delivery_id`, `school_name`, `wilaya`, `reference_number`, `notes`, `confirmed_by`, `note`) VALUES
(1, 1, 25000, '2025-12-21', 'cash', '2025-12-21 13:57:36', '2025-12-21 13:57:36', NULL, 'distributor', 10, NULL, '20اوت1956', 'Djelfa', NULL, NULL, NULL, NULL),
(2, NULL, 500000, '2025-12-22', 'cash', '2025-12-22 09:45:00', '2025-12-22 09:45:00', NULL, NULL, NULL, 9, NULL, NULL, NULL, NULL, NULL, 'Paiement via application mobile'),
(3, NULL, 3500, '2025-12-22', 'cash', '2025-12-22 09:46:00', '2025-12-22 09:46:00', NULL, NULL, NULL, 14, NULL, NULL, NULL, NULL, NULL, 'Paiement via application mobile'),
(4, NULL, 50000, '2025-12-22', 'cash', '2025-12-22 09:46:25', '2025-12-22 09:46:25', NULL, NULL, NULL, 5, NULL, NULL, NULL, NULL, NULL, 'Paiement via application mobile'),
(5, NULL, 520000, '2025-12-22', 'cash', '2025-12-22 09:47:23', '2025-12-22 09:47:23', NULL, NULL, NULL, 12, NULL, NULL, NULL, NULL, NULL, 'Paiement via application mobile'),
(6, NULL, 50000, '2025-12-22', 'cash', '2025-12-22 09:49:21', '2025-12-22 09:49:21', NULL, NULL, NULL, 13, NULL, NULL, NULL, NULL, NULL, 'Paiement via application mobile'),
(7, NULL, 250000, '2025-12-22', 'cash', '2025-12-22 10:16:14', '2025-12-22 10:16:14', NULL, NULL, NULL, 10, NULL, NULL, NULL, NULL, NULL, 'Paiement via application mobile'),
(8, NULL, 50000, '2025-12-22', 'cash', '2025-12-22 10:29:04', '2025-12-22 10:29:04', NULL, NULL, NULL, 11, NULL, NULL, NULL, NULL, NULL, 'Paiement via application mobile'),
(9, NULL, 10000, '2025-12-22', 'cash', '2025-12-22 10:31:35', '2025-12-22 10:31:35', NULL, NULL, NULL, 8, NULL, NULL, NULL, NULL, NULL, 'Paiement via application mobile'),
(10, NULL, 150000, '2025-12-22', 'cash', '2025-12-22 10:44:02', '2025-12-22 10:44:02', NULL, NULL, NULL, 17, NULL, NULL, NULL, NULL, NULL, 'Paiement via application mobile'),
(11, NULL, 21500, '2025-12-22', 'cash', '2025-12-22 10:51:06', '2025-12-22 10:51:06', NULL, NULL, NULL, 14, NULL, NULL, NULL, NULL, NULL, 'Paiement via application mobile'),
(12, NULL, 50000, '2025-12-22', 'cash', '2025-12-22 10:52:56', '2025-12-22 10:52:56', NULL, NULL, NULL, 15, NULL, NULL, NULL, NULL, NULL, 'Paiement via application mobile'),
(13, NULL, 250000, '2025-12-23', 'cash', '2025-12-23 08:15:02', '2025-12-23 08:15:02', NULL, NULL, NULL, 16, NULL, NULL, NULL, NULL, NULL, 'Paiement via application mobile'),
(14, 1, 1000, '2025-12-23', 'cash', '2025-12-23 10:02:45', '2025-12-23 10:02:45', NULL, NULL, 607, 23, NULL, 'Constantine', NULL, NULL, NULL, NULL),
(15, 1, 50000, '2025-12-23', 'cash', '2025-12-23 11:00:13', '2025-12-23 11:00:13', NULL, NULL, 605, 22, NULL, 'Constantine', NULL, NULL, NULL, NULL),
(16, 1, 150000, '2025-12-23', 'cash', '2025-12-23 11:02:11', '2025-12-23 11:02:11', NULL, NULL, 13, 1, NULL, 'Djelfa', NULL, NULL, NULL, NULL),
(17, 1, 10000, '2025-12-23', 'cash', '2025-12-23 11:02:43', '2025-12-23 11:02:43', NULL, NULL, 713, 24, NULL, 'Skikda', NULL, NULL, NULL, NULL),
(18, 1, 2500, '2025-12-23', 'cash', '2025-12-23 11:41:41', '2025-12-23 11:41:41', NULL, NULL, 713, 26, NULL, 'Skikda', NULL, NULL, NULL, NULL),
(19, 1, 1000, '2025-12-23', 'cash', '2025-12-23 11:42:23', '2025-12-23 11:42:23', NULL, NULL, 863, 25, NULL, 'Annaba', NULL, NULL, NULL, NULL),
(20, 1, 1500, '2025-12-23', 'cash', '2025-12-23 12:55:36', '2025-12-23 12:55:36', NULL, NULL, 607, 23, NULL, 'Constantine', NULL, NULL, NULL, NULL),
(21, 1, 500, '2025-12-23', 'cash', '2025-12-23 13:05:26', '2025-12-23 13:05:26', NULL, NULL, 392, 28, NULL, 'M\'Sila', NULL, NULL, NULL, NULL),
(22, 1, 10000, '2025-12-23', 'cash', '2025-12-23 13:08:51', '2025-12-23 13:08:51', NULL, NULL, 10, 27, NULL, 'Djelfa', NULL, NULL, NULL, NULL),
(23, 1, 500000, '2025-12-23', 'cash', '2025-12-23 13:13:11', '2025-12-23 13:13:11', NULL, NULL, 1038, 21, NULL, 'Biskra', NULL, NULL, NULL, NULL),
(24, 1, 4000, '2025-12-23', 'cash', '2025-12-23 13:42:15', '2025-12-23 13:42:15', NULL, NULL, 13, 1, NULL, 'Djelfa', NULL, NULL, NULL, NULL),
(25, 1, 500, '2025-12-23', 'cash', '2025-12-23 13:50:43', '2025-12-23 13:50:43', NULL, NULL, 713, 26, NULL, 'Skikda', NULL, NULL, NULL, NULL),
(26, 1, 5000, '2025-12-23', 'cash', '2025-12-23 14:07:38', '2025-12-23 14:07:38', NULL, NULL, 605, 22, NULL, 'Constantine', NULL, NULL, NULL, NULL),
(27, 1, 150000, '2025-12-24', 'cash', '2025-12-24 07:46:24', '2025-12-24 07:46:24', NULL, NULL, 875, 30, NULL, 'Annaba', NULL, NULL, NULL, NULL),
(28, 1, 25000, '2025-12-24', 'cash', '2025-12-24 08:24:36', '2025-12-24 08:24:36', NULL, NULL, 941, 31, NULL, 'Biskra', NULL, NULL, NULL, NULL),
(29, 1, 5000, '2025-12-24', 'cash', '2025-12-24 08:42:40', '2025-12-24 08:42:40', NULL, NULL, 874, 32, NULL, 'Annaba', NULL, NULL, NULL, NULL),
(30, 1, 1000, '2025-12-24', 'cash', '2025-12-24 08:43:49', '2025-12-24 08:43:49', NULL, NULL, 713, 26, NULL, 'Skikda', NULL, NULL, NULL, NULL),
(31, 1, 50000, '2025-12-24', 'cash', '2025-12-24 09:15:32', '2025-12-24 09:15:32', NULL, NULL, 605, 22, NULL, 'Constantine', NULL, NULL, NULL, NULL),
(32, 1, 25000, '2025-12-27', 'cash', '2025-12-27 08:31:14', '2025-12-27 08:31:14', NULL, NULL, 874, 40, NULL, 'Annaba', NULL, NULL, NULL, NULL),
(33, 1, 5000, '2025-12-27', 'cash', '2025-12-27 08:32:50', '2025-12-27 08:32:50', NULL, NULL, 1042, 43, NULL, 'Constantine', NULL, NULL, NULL, NULL),
(34, 1, 10000, '2025-12-27', 'cash', '2025-12-27 08:33:50', '2025-12-27 08:33:50', NULL, NULL, 1042, 42, NULL, 'Constantine', NULL, NULL, NULL, NULL),
(35, 1, 50000, '2025-12-27', 'cash', '2025-12-27 09:40:28', '2025-12-27 09:40:28', NULL, NULL, 874, 29, NULL, 'Annaba', NULL, NULL, NULL, NULL),
(36, 1, 990000, '2025-12-27', 'cash', '2025-12-27 10:17:36', '2025-12-27 10:17:36', NULL, NULL, 1042, 42, NULL, 'Constantine', NULL, NULL, NULL, NULL),
(37, 1, 10000, '2025-12-27', 'cash', '2025-12-27 10:18:05', '2025-12-27 10:18:05', NULL, NULL, 1042, 46, NULL, 'Constantine', NULL, NULL, NULL, NULL),
(38, 1, 1000000, '2025-12-27', 'cash', '2025-12-27 10:37:24', '2025-12-27 10:37:24', NULL, NULL, 710, 44, NULL, 'Skikda', NULL, NULL, NULL, NULL),
(39, 1, 2500, '2025-12-27', 'cash', '2025-12-27 10:38:45', '2025-12-27 10:38:45', NULL, NULL, 1038, 33, NULL, 'Biskra', NULL, NULL, NULL, NULL),
(40, 1, 25000, '2025-12-28', 'cash', '2025-12-28 10:55:37', '2025-12-28 10:55:37', NULL, NULL, 477, 39, NULL, 'El M\'Ghair', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'view dashboard', 'web', '2025-12-21 11:01:01', '2025-12-21 11:01:01'),
(2, 'view statistics', 'web', '2025-12-21 11:01:01', '2025-12-21 11:01:01'),
(3, 'view users', 'web', '2025-12-21 11:01:01', '2025-12-21 11:01:01'),
(4, 'create users', 'web', '2025-12-21 11:01:01', '2025-12-21 11:01:01'),
(5, 'edit users', 'web', '2025-12-21 11:01:01', '2025-12-21 11:01:01'),
(6, 'delete users', 'web', '2025-12-21 11:01:01', '2025-12-21 11:01:01'),
(7, 'assign roles', 'web', '2025-12-21 11:01:01', '2025-12-21 11:01:01'),
(8, 'view schools', 'web', '2025-12-21 11:01:01', '2025-12-21 11:01:01'),
(9, 'create schools', 'web', '2025-12-21 11:01:01', '2025-12-21 11:01:01'),
(10, 'edit schools', 'web', '2025-12-21 11:01:01', '2025-12-21 11:01:01'),
(11, 'delete schools', 'web', '2025-12-21 11:01:01', '2025-12-21 11:01:01'),
(12, 'view deliveries', 'web', '2025-12-21 11:01:01', '2025-12-21 11:01:01'),
(13, 'create deliveries', 'web', '2025-12-21 11:01:01', '2025-12-21 11:01:01'),
(14, 'edit deliveries', 'web', '2025-12-21 11:01:01', '2025-12-21 11:01:01'),
(15, 'delete deliveries', 'web', '2025-12-21 11:01:01', '2025-12-21 11:01:01'),
(16, 'view own deliveries', 'web', '2025-12-21 11:01:01', '2025-12-21 11:01:01'),
(17, 'view payments', 'web', '2025-12-21 11:01:01', '2025-12-21 11:01:01'),
(18, 'create payments', 'web', '2025-12-21 11:01:01', '2025-12-21 11:01:01'),
(19, 'edit payments', 'web', '2025-12-21 11:01:01', '2025-12-21 11:01:01'),
(20, 'delete payments', 'web', '2025-12-21 11:01:01', '2025-12-21 11:01:01'),
(21, 'view own payments', 'web', '2025-12-21 11:01:01', '2025-12-21 11:01:01'),
(22, 'view reports', 'web', '2025-12-21 11:01:01', '2025-12-21 11:01:01'),
(23, 'export reports', 'web', '2025-12-21 11:01:01', '2025-12-21 11:01:01'),
(24, 'manage settings', 'web', '2025-12-21 11:01:01', '2025-12-21 11:01:01');

-- --------------------------------------------------------

--
-- Structure de la table `personal_access_tokens`
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
-- Déchargement des données de la table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 2, 'flutter-app', '70314522ac478f3ae412d411bc691ab11eee718f3450f571913f7771cb7c1b02', '[\"*\"]', '2025-12-21 13:21:02', NULL, '2025-12-21 11:05:37', '2025-12-21 13:21:02'),
(2, 'App\\Models\\User', 2, 'flutter-app', 'a7be50b86741ca82d058379b8bfae7b1cd4b56a37c453dc16df5dd02164a70bb', '[\"*\"]', '2025-12-21 13:23:24', NULL, '2025-12-21 13:22:17', '2025-12-21 13:23:24'),
(3, 'App\\Models\\User', 2, 'flutter-app', '7ee6cc1bd42d30d310c788acd4f422332c99b03b0c633204aadc73766b4f57af', '[\"*\"]', '2025-12-21 13:48:52', NULL, '2025-12-21 13:47:45', '2025-12-21 13:48:52'),
(4, 'App\\Models\\User', 2, 'flutter-app', 'dbd479ce5f7eb3c8ddac53858530172f0aa8778356b8283dd0c901ffc180fad0', '[\"*\"]', '2025-12-21 13:58:58', NULL, '2025-12-21 13:55:15', '2025-12-21 13:58:58'),
(5, 'App\\Models\\User', 2, 'flutter-app', 'f1bf3d737aa9d951d2cf8f10026d102fcceee32e37ce92dad1695b109ec8a927', '[\"*\"]', '2025-12-22 09:18:38', NULL, '2025-12-22 08:14:18', '2025-12-22 09:18:38'),
(6, 'App\\Models\\User', 2, 'flutter-app', '199942f8d014b76778f6d343fc18ed9406ee336de594180efdbecbc577eec340', '[\"*\"]', '2025-12-22 10:16:15', NULL, '2025-12-22 09:23:46', '2025-12-22 10:16:15'),
(7, 'App\\Models\\User', 2, 'flutter-app', '41d4e2c1518173bb175ccfccf68ba6a9d615e0d0fc39c93d49112ac449bd9526', '[\"*\"]', '2025-12-22 10:42:18', NULL, '2025-12-22 10:28:52', '2025-12-22 10:42:18'),
(8, 'App\\Models\\User', 2, 'flutter-app', '0aab863d128630d4fe0a6d1e61d5377207f7f4093b52e333245e10b13b081283', '[\"*\"]', '2025-12-22 11:12:43', NULL, '2025-12-22 10:43:21', '2025-12-22 11:12:43'),
(9, 'App\\Models\\User', 2, 'flutter-app', 'a53e643203119cc6c10bc26c96429b80854baeb7736ee661263fd0157111c715', '[\"*\"]', '2025-12-22 12:04:49', NULL, '2025-12-22 12:00:09', '2025-12-22 12:04:49'),
(12, 'App\\Models\\User', 2, 'flutter-app', '54a4cbd0ad05e5473b59fb5e62728ed49dc433cad82af642403e1a3791f54c89', '[\"*\"]', '2025-12-22 12:12:20', NULL, '2025-12-22 12:12:18', '2025-12-22 12:12:20'),
(14, 'App\\Models\\User', 2, 'flutter-app', 'da0221d4cb0dafb73fcaec2ebf69f4ea02cbb8d54f0c2b5a1cf34fee428025fc', '[\"*\"]', '2025-12-23 07:19:18', NULL, '2025-12-23 07:19:16', '2025-12-23 07:19:18'),
(15, 'App\\Models\\User', 2, 'flutter-app', '5fd3830f41e6f9c6036d4d8039c5ab604101b9d44fadb2ca3b10cc3431119f1b', '[\"*\"]', '2025-12-23 07:49:47', NULL, '2025-12-23 07:49:46', '2025-12-23 07:49:47'),
(18, 'App\\Models\\User', 2, 'flutter-app', '93cdcad9e1098f4ea0e8eab58ce8d73b80f6bde2d58b7d4e59d2ed3a19c59812', '[\"*\"]', '2025-12-23 08:22:07', NULL, '2025-12-23 08:21:41', '2025-12-23 08:22:07'),
(19, 'App\\Models\\User', 2, 'flutter-app', '5f92120ce4702555df95560b9f05e5c2ad674d8aac50a30297829d31479253a8', '[\"*\"]', '2025-12-23 08:25:27', NULL, '2025-12-23 08:24:46', '2025-12-23 08:25:27'),
(20, 'App\\Models\\User', 2, 'flutter-app', '4f171e4014c51d56ebc00f201b674b0d7186ca3c597e6439883c2dd3246d465f', '[\"*\"]', '2025-12-23 08:55:13', NULL, '2025-12-23 08:37:15', '2025-12-23 08:55:13'),
(21, 'App\\Models\\User', 2, 'flutter-app', 'bb1f9dad3d375eb6173cdfd32e0d63bf78182d55e777604e4206b336c872d1e7', '[\"*\"]', '2025-12-23 09:19:38', NULL, '2025-12-23 09:00:12', '2025-12-23 09:19:38'),
(22, 'App\\Models\\User', 2, 'flutter-app', '86d39399adf53a7a0b65df400b78b9373dee9bc1ee407d9cb070717a9f928fb5', '[\"*\"]', '2025-12-23 09:39:51', NULL, '2025-12-23 09:22:27', '2025-12-23 09:39:51'),
(23, 'App\\Models\\User', 2, 'flutter-app', '198365c122eac5a57c622088983d0c4440eaf00d62381d6dca69db9db25dd3b4', '[\"*\"]', '2025-12-23 10:09:00', NULL, '2025-12-23 09:48:04', '2025-12-23 10:09:00'),
(24, 'App\\Models\\User', 2, 'flutter-app', '90ef73c7b0d7e4322f88970dc53af5a617ce2c9ca8b764f8c3bff30860b6bb69', '[\"*\"]', '2025-12-23 10:16:55', NULL, '2025-12-23 10:11:27', '2025-12-23 10:16:55'),
(25, 'App\\Models\\User', 2, 'flutter-app', '19a285f91c5c8a612e235d7df7868166b801c33d125443533bb7028b098c05d7', '[\"*\"]', '2025-12-23 11:55:20', NULL, '2025-12-23 10:46:54', '2025-12-23 11:55:20'),
(26, 'App\\Models\\User', 2, 'flutter-app', '29c2f3f8a61c5d99a28ea3924ff033a0b55a3250d915ed751a5f0d092e520c97', '[\"*\"]', '2025-12-23 13:16:41', NULL, '2025-12-23 12:55:20', '2025-12-23 13:16:41'),
(28, 'App\\Models\\User', 2, 'flutter-app', '496f1449321b93c89235a2ec04c782c5ee05b06b71def1eeef05434bb23d5874', '[\"*\"]', '2025-12-23 14:07:39', NULL, '2025-12-23 13:57:46', '2025-12-23 14:07:39'),
(30, 'App\\Models\\User', 2, 'flutter-app', '7bdf50b5e4bf2dbe30fc1a062c60fd8daba40975a0ad38a72fb149eb4b54b665', '[\"*\"]', '2025-12-24 08:47:24', NULL, '2025-12-24 08:42:32', '2025-12-24 08:47:24'),
(31, 'App\\Models\\User', 1, 'flutter-app', '6dcd1c572bfef53c9fea4535e547a992c2ad89c3f0b4162344e5e0acc7b495e5', '[\"*\"]', '2025-12-24 09:13:35', NULL, '2025-12-24 09:13:34', '2025-12-24 09:13:35'),
(32, 'App\\Models\\User', 2, 'flutter-app', '4e8c22ef7174a76a400ee02d7844e807f3c10b0016c5b30c327883e4760828ac', '[\"*\"]', '2025-12-24 09:15:33', NULL, '2025-12-24 09:14:48', '2025-12-24 09:15:33'),
(33, 'App\\Models\\User', 1, 'flutter-app', '2d872f7d7e8619ea28b76796783c85f88938885df94b3a71e6a69800bf8463a4', '[\"*\"]', '2025-12-25 07:46:21', NULL, '2025-12-25 07:46:20', '2025-12-25 07:46:21'),
(34, 'App\\Models\\User', 1, 'flutter-app', '961100eae31bf2157cc1e2e07830e739d0abd82b30105f5296da851e135735fa', '[\"*\"]', '2025-12-25 08:01:47', NULL, '2025-12-25 07:51:22', '2025-12-25 08:01:47'),
(35, 'App\\Models\\User', 1, 'flutter-app', 'a2a71a9ca11d22accbabec802668c3cc3629ced6b0ca17a4c2ad6f8dd361ab23', '[\"*\"]', '2025-12-25 08:46:51', NULL, '2025-12-25 08:06:04', '2025-12-25 08:46:51'),
(36, 'App\\Models\\User', 1, 'flutter-app', '03a268b6dced4c4c951f70560c7459c0f15ee3cb6ef920af3f188e3ccf8ecc41', '[\"*\"]', '2025-12-25 08:49:56', NULL, '2025-12-25 08:47:55', '2025-12-25 08:49:56'),
(37, 'App\\Models\\User', 1, 'flutter-app', '4c86eaa5cdcafae263c60601420eed922445f2f38ea4f283f69f48ef2d827e1c', '[\"*\"]', '2025-12-25 09:24:11', NULL, '2025-12-25 08:50:51', '2025-12-25 09:24:11'),
(38, 'App\\Models\\User', 1, 'flutter-app', '93fe3387ddf9499a54e0b4e0472e357d8232256231c480d59239a5cfcea101db', '[\"*\"]', '2025-12-25 09:29:33', NULL, '2025-12-25 09:25:37', '2025-12-25 09:29:33'),
(39, 'App\\Models\\User', 1, 'flutter-app', 'cfa3c9c18b73b39f42b86d183c2995e0805ef49146882902dec63a76882375a8', '[\"*\"]', '2025-12-25 09:40:21', NULL, '2025-12-25 09:40:19', '2025-12-25 09:40:21'),
(40, 'App\\Models\\User', 1, 'flutter-app', 'cb61645cc31a1466ba142dc57f9bb1475784cad3028085d5283a35b7549cd640', '[\"*\"]', '2025-12-25 10:08:33', NULL, '2025-12-25 09:50:41', '2025-12-25 10:08:33'),
(41, 'App\\Models\\User', 1, 'flutter-app', 'f300f7bd24c9b18bf68536ee55d27fcb7a030fbd4ec6536839560bdd509dad80', '[\"*\"]', '2025-12-25 10:13:11', NULL, '2025-12-25 10:11:24', '2025-12-25 10:13:11'),
(42, 'App\\Models\\User', 1, 'flutter-app', 'd2baaa686534b668235b5091c0b2f9ee64b3db06f59c5d79ae8e94886190753f', '[\"*\"]', '2025-12-25 10:36:54', NULL, '2025-12-25 10:14:13', '2025-12-25 10:36:54'),
(43, 'App\\Models\\User', 2, 'flutter-app', 'a3b889251b2ed767a13144a9b5180fa856776062214f288fad22b0c898dc1a4a', '[\"*\"]', '2025-12-25 10:48:23', NULL, '2025-12-25 10:37:51', '2025-12-25 10:48:23'),
(44, 'App\\Models\\User', 2, 'flutter-app', 'ea7580bda368f5762941cc670f21123a2dca4e2f354c0accc1fb9ab766d4c2bf', '[\"*\"]', '2025-12-25 11:11:28', NULL, '2025-12-25 10:58:41', '2025-12-25 11:11:28'),
(45, 'App\\Models\\User', 2, 'flutter-app', '8127af68f82c93475fda88a73dab37f0e8e5ae06df58468101b0670d3c184858', '[\"*\"]', '2025-12-25 12:15:07', NULL, '2025-12-25 12:12:22', '2025-12-25 12:15:07'),
(46, 'App\\Models\\User', 1, 'flutter-app', '5b65cc6727b35c4f13dc9075d0a8de4905282800ad19a3c2e9bc2d7b51abac7c', '[\"*\"]', '2025-12-25 12:18:46', NULL, '2025-12-25 12:18:45', '2025-12-25 12:18:46'),
(47, 'App\\Models\\User', 2, 'flutter-app', 'bec630a5c727ef0ff5a3e421dbd33cf8617f7d3d3bca65d9f0750353db320d32', '[\"*\"]', '2025-12-25 13:02:42', NULL, '2025-12-25 12:21:10', '2025-12-25 13:02:42'),
(48, 'App\\Models\\User', 2, 'flutter-app', '3cf937a6aa14cdebd44930e46dfb869d70fffeb0a210f6e5453202659cb11c4c', '[\"*\"]', '2025-12-25 13:12:41', NULL, '2025-12-25 13:12:23', '2025-12-25 13:12:41'),
(49, 'App\\Models\\User', 1, 'flutter-app', 'd626bbc8ccbd6ff83fd4481269cf8bc1495964818ab2c69a0d25619e2ac46e30', '[\"*\"]', '2025-12-25 13:17:12', NULL, '2025-12-25 13:14:11', '2025-12-25 13:17:12'),
(51, 'App\\Models\\User', 2, 'flutter-app', 'c21a98e809297a2356b15453a3edf0b97180f32cf67912af037734aec4ecb806', '[\"*\"]', '2025-12-27 08:06:09', NULL, '2025-12-27 07:26:44', '2025-12-27 08:06:09'),
(52, 'App\\Models\\User', 1, 'flutter-app', '24e34fd52ea116a3a299fe5b8c0adb279df715fc291f7dff9bc4882dec50dd14', '[\"*\"]', '2025-12-27 08:29:56', NULL, '2025-12-27 08:29:54', '2025-12-27 08:29:56'),
(53, 'App\\Models\\User', 2, 'flutter-app', '32da3f3d66941c3d5ebedbfad3826603a55cbd2388232a24c3fced313ab3b8b8', '[\"*\"]', '2025-12-27 08:33:50', NULL, '2025-12-27 08:30:57', '2025-12-27 08:33:50'),
(54, 'App\\Models\\User', 1, 'flutter-app', 'db1c8069ce4a638c6572fb9ad06c628efc3227e6a04f194c1c23c4da50c13c40', '[\"*\"]', '2025-12-27 08:47:26', NULL, '2025-12-27 08:35:39', '2025-12-27 08:47:26'),
(55, 'App\\Models\\User', 1, 'flutter-app', 'cdb65ee15d6db4bf49fc4261d5b9d5652ea535253f3d945e69db906dfb35bccd', '[\"*\"]', '2025-12-27 09:22:42', NULL, '2025-12-27 08:50:15', '2025-12-27 09:22:42'),
(56, 'App\\Models\\User', 1, 'flutter-app', 'cff6622b0da4c73ce9f4b154c8c4c1485960f772231b3cf301be12f5929ce38e', '[\"*\"]', '2025-12-27 09:36:17', NULL, '2025-12-27 09:26:29', '2025-12-27 09:36:17'),
(57, 'App\\Models\\User', 2, 'flutter-app', 'ada314377a928a7a30d59ed33f8761326dbfb3a5575eb0a720efdc55218c7e60', '[\"*\"]', '2025-12-27 09:40:28', NULL, '2025-12-27 09:39:20', '2025-12-27 09:40:28'),
(58, 'App\\Models\\User', 1, 'flutter-app', '5b4305dbf40f0424215e4fd37aeb8fb92e160e8106ccf7381c5e61a4300d68df', '[\"*\"]', '2025-12-27 10:15:40', NULL, '2025-12-27 09:41:47', '2025-12-27 10:15:40'),
(59, 'App\\Models\\User', 2, 'flutter-app', '96648ba76f6bda15b33eab5a8d13b714adc6f37facf39a291cf9b07e780725f8', '[\"*\"]', '2025-12-27 10:38:45', NULL, '2025-12-27 10:16:52', '2025-12-27 10:38:45'),
(60, 'App\\Models\\User', 2, 'flutter-app', '9ed6c223493c4a9b1910a663e18190c4d6c4d041a05192ff493c0b1e4560ce27', '[\"*\"]', '2025-12-28 10:56:04', NULL, '2025-12-28 10:54:49', '2025-12-28 10:56:04');

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'super_admin', 'web', '2025-12-21 11:01:01', '2025-12-21 11:01:01'),
(2, 'manager', 'web', '2025-12-21 11:01:01', '2025-12-21 11:01:01'),
(3, 'distributor', 'web', '2025-12-21 11:01:01', '2025-12-21 11:01:01'),
(4, 'employee', 'web', '2025-12-21 11:01:01', '2025-12-21 11:01:01'),
(5, 'admin', 'web', '2025-12-25 10:07:34', '2025-12-25 10:07:34'),
(6, 'user', 'web', '2025-12-25 10:12:11', '2025-12-25 10:12:11');

-- --------------------------------------------------------

--
-- Structure de la table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(2, 1),
(2, 2),
(2, 3),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(8, 2),
(8, 4),
(9, 1),
(9, 2),
(10, 1),
(10, 2),
(11, 1),
(12, 1),
(12, 2),
(12, 4),
(13, 1),
(13, 2),
(13, 3),
(14, 1),
(14, 2),
(15, 1),
(16, 1),
(16, 3),
(17, 1),
(17, 2),
(17, 4),
(18, 1),
(18, 2),
(19, 1),
(19, 2),
(20, 1),
(21, 1),
(21, 3),
(22, 1),
(22, 2),
(23, 1),
(23, 2),
(24, 1);

-- --------------------------------------------------------

--
-- Structure de la table `schools`
--

CREATE TABLE `schools` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `district` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `manager_name` varchar(255) NOT NULL,
  `student_count` int(11) NOT NULL DEFAULT 0,
  `wilaya` varchar(255) NOT NULL,
  `commune` varchar(100) NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `radius` decimal(5,3) DEFAULT NULL COMMENT 'Rayon de validation en km (défaut: 0.05 = 50m)',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `schools`
--

INSERT INTO `schools` (`id`, `name`, `address`, `district`, `phone`, `manager_name`, `student_count`, `wilaya`, `commune`, `latitude`, `longitude`, `radius`, `created_at`, `updated_at`) VALUES
(1, 'البشير الابراهيمي', '', '', '779370822', 'امسعودان مراد', 0, 'Djelfa', 'البيرين', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(2, 'بوزيان بن يحي', '', '', '663419387', 'شحيم الببشير', 0, 'Djelfa', 'البيرين', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(3, 'بوعقلين الحاج', 'وسارة', 'وسارة', '0541152325/0778683308', '', 0, 'Djelfa', 'البيرين', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(4, 'حميداني محمد', 'بنهار', 'بنهار', '776319012', 'حمزة عبد الكريم', 0, 'Djelfa', 'البيرين', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(5, 'سائحي مختار', '', '', '771703300', 'شنوف رشيدة', 0, 'Djelfa', 'البيرين', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(6, 'قاسمي بن علية', '', '', '558621055', '', 0, 'Djelfa', 'البيرين', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(7, 'محمد بن سليم', '', '', '793626784', 'عبد الوهاب عبد المجيد', 0, 'Djelfa', 'البيرين', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(8, 'محمد عبد الوهاب', '', '', '795902754', '', 0, 'Djelfa', 'البيرين', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(9, 'المختار بخوش', '', '', '774186501', '', 0, 'Djelfa', 'البيرين', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(10, '20اوت1956', '', '', '662063837', 'ب, بن مسعود', 0, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(11, 'الابتدائية المركزية', '', '', '668079519', '', 0, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(12, 'الامام بن ربيح', '', '', '660429279', '', 530, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(13, 'السعدي بلقاسم', '', '', '665386565', 'عمر الود', 0, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(14, 'الشيخ احمد بن علي', '', '', '667831859', '', 0, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(15, 'الشيهب بلخير', '', '', '656003131', '', 0, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(16, 'القطب الحضري بربيع', 'م 27', 'م 27', '0655223020/0668992624', '', 0, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(17, 'العقون مصطفى', '', '', '671302665', '', 0, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(18, 'القزي عبد الحميد', 'الوئام', 'الوئام', '696970412', '', 0, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(19, 'الكر الطاهر', '', '', '660949588', '', 0, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(20, 'الهاني محمد بن الهادي', '', '', '676056546', 'زروث رقية', 0, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(21, 'بالي العيد', '', '', '661760122', 'عبد اللطيف نور الهدى', 0, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(22, 'بطاش نعاس', '', '', '666925817', 'عبد الحميد شعش', 0, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(23, 'بلعربي عبد الباقي', '', '', '657457628', '', 0, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(24, 'بلعطرة قويدر', '', '', '668148115', 'الود مصطفى', 0, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(25, 'بلعطرة مختار', '', '', '27934378', '', 0, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(26, 'بن حليمة محمد', '', '', '', '', 0, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(27, 'بن عالية علي', '', '', '660878797', '', 0, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(28, 'بن علية علي شعوة', '', '', '660878797', '', 0, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(29, 'بن عمران تامر', '', '', '', '', 0, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(30, 'بورزق بوبكر', 'برنادة', 'برنادة', '659527456', 'شارف بن سعد', 0, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(31, 'بورقدة احمد', '', '', '675962343', '', 0, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(32, 'بوزيدي زكراوي', '', '', '696962265', '', 0, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(33, 'تفريج محمد', '', '', '660937364', 'بكاي أ', 318, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(34, 'جربيع سعد بن الميلود', '', '', '671401798', '', 300, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(35, 'جعيد احمد', '', '', '696022213', '', 0, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(36, 'جعيد عمر', '', '', '699441321', 'صادقي عمر', 0, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(37, 'حرفوش عبد القادر', 'ج 02', 'ج 02', '654929924', '', 0, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(38, 'حرفوش عيسى', 'القطب', 'القطب', '656301246', 'عدلي عبد العزيز', 700, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(39, 'جواف علي', '', '', '676207135', '', 0, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(40, 'حفاف يحيى', '', '', '664007871', '', 350, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(41, 'حلفاوي مصطفى', 'عين اسراء', 'عين اسراء', '658655148', '', 350, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(42, 'حنيشي محمد الجنوبية', '', '', '674056962', 'بوساسي وردة', 320, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(43, 'حنيشي محمد الشمالية', '', '', '672885888', 'سليمة', 509, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(44, 'خدومة سعد', '', '', '699717178', '', 0, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(45, 'دروازي الشامخ 01', '100دار', '100دار', '697392052', 'قويلي عبد القادر', 50, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(46, 'دريسي عيسى', '', '', '666507356', '', 0, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(47, 'دلاعة عطية', '', '', '662797990', 'شامخ عبد الله', 0, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(48, 'ريكي مصطفى', '', '', '27937258', '', 0, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(49, 'زريعة عبد القادر', '', '', '027926857/0676457271', 'طاهري عبد القادر', 0, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(50, 'زيتوني بلخير(عبيكشي السعيد)', '', '', '777583159', '', 0, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(51, 'شلالي يوسف', '', '', '663989277', '', 0, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(52, 'صويلح شويحة', '1200تليذ', '1200تليذ', '676155547', 'جناد لعموري', 0, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(53, 'صيلع بلخير', '', '', '668674102', 'بن غربي دلال', 0, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(54, 'عبيدي ربيح', 'فوج 4', 'فوج 4', '698521089', '', 0, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(55, 'غربي بن عبد الله', '', '', '656170746', '', 400, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(56, 'فيلالي البشير', '', '', '699250342', '', 0, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(57, 'قاسم سليمان', '', '', '655454081', '', 0, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(58, 'قشام الميلود', '', '', '662728015', '', 0, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(59, 'قوريدة احمد', '', '', '671481925', '', 460, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(60, 'كاس محمد', '', '', '699515076', '', 0, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(61, 'لباز مصطفى', '', '', '663452225', 'فيطس سليمة', 300, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(62, 'لقرادة بلقاسم', '', '', '657784078', '', 0, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(63, 'محفوظي عمر', '', '', '660777759', '', 0, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(64, 'مقواس عمر', '', '', '771373127', 'امحمد بن صالح بودانة', 0, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(65, 'شيبوط بلعباس', '', '', '697357585', '', 0, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(66, 'نعاس عبد الحميد', '', '', '659618131', 'قطوش مصطفى', 0, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(67, 'ونوقي احمد', '', '', '658946050', 'جعفر محمد', 0, 'Djelfa', 'الجلفة وسط', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(68, 'الامير عبد القادر', '', '', '776501720', '', 0, 'Djelfa', 'حاسي بحبح', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(69, 'العطري بن عرعار', '', '', '783175153', 'بن تشيش مصطفى', 0, 'Djelfa', 'حاسي بحبح', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(70, 'القدس', '', '', '775151786', '', 300, 'Djelfa', 'حاسي بحبح', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(71, 'بختي عطية', '', '', '657702749', '', 0, 'Djelfa', 'حاسي بحبح', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(72, 'بشار بن جدو', '', '', '672691893', '', 470, 'Djelfa', 'حاسي بحبح', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(73, 'بلحرش عبد الله', '', '', '664478780', '', 0, 'Djelfa', 'حاسي بحبح', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(74, 'بن حنة بلعارية', '', '', '771648980', '', 300, 'Djelfa', 'حاسي بحبح', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(75, 'بن خيره محمد', '', '', '676966026', '', 0, 'Djelfa', 'حاسي بحبح', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(76, 'بن دنيدينة محمد', '', '', '698717205', 'زروقي الحاج', 0, 'Djelfa', 'حاسي بحبح', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(77, 'بن يطو عبد القادر', '', '', '27954094', '', 0, 'Djelfa', 'حاسي بحبح', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(78, 'بهناس عطية', '', '', '770107245', '', 0, 'Djelfa', 'حاسي بحبح', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(79, 'بويح محمد', '', '', '664191916', '', 0, 'Djelfa', 'حاسي بحبح', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(80, 'حمداني المداني', 'القندور', 'القندور', '666336986', '', 0, 'Djelfa', 'حاسي بحبح', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(81, 'خلاص اسماعيل', '', '', '664787582', 'تفاح رابح', 0, 'Djelfa', 'حاسي بحبح', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(82, 'سيدي نايل', '', '', '27961855', '', 0, 'Djelfa', 'حاسي بحبح', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(83, 'شتوح الطيب', '', '', '783281243', '', 0, 'Djelfa', 'حاسي بحبح', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(84, 'عبد الرحيم بلعباس', '', '', '27964132/0675712406', 'دوارة سالم', 0, 'Djelfa', 'حاسي بحبح', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(85, 'عمر ادريس', '', '', '667729080', '', 0, 'Djelfa', 'حاسي بحبح', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(86, 'قارف احمد', '', '', '780770811', '', 471, 'Djelfa', 'حاسي بحبح', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(87, 'قاسمي الحاج', '', '', '668175980', '', 0, 'Djelfa', 'حاسي بحبح', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(88, 'قوق سليمان', '', '', '770348848', '', 0, 'Djelfa', 'حاسي بحبح', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(89, 'كربوعة سالم', '', '', '665810807', '', 0, 'Djelfa', 'حاسي بحبح', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(90, 'مبخوتة احمد', '', '', '673546793', 'سايب خديجة', 0, 'Djelfa', 'حاسي بحبح', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(91, 'محمد بوضياف', '', '', '27950337', '', 320, 'Djelfa', 'حاسي بحبح', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(92, 'مغربي الحاج', '', '', '662600976', '', 0, 'Djelfa', 'حاسي بحبح', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(93, 'نقبيل عبد القادر', '', '', '662709905', 'غراب عبد القادر', 320, 'Djelfa', 'حاسي بحبح', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(94, 'نوع د', 'العطري', 'العطري', '697909906', '', 525, 'Djelfa', 'حاسي بحبح', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(95, 'نوي احمد', '', '', '667757975', 'شراك لخضر', 0, 'Djelfa', 'حاسي بحبح', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(96, 'هزرشي بن صيفية', '', '', '776073666', '', 0, 'Djelfa', 'حاسي بحبح', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(97, 'الاخضر بوضياف', '', '', '778186818', '', 0, 'Djelfa', 'عين وسارة', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(98, 'العربي بن مهدي', '', '', '797066363', 'لعموري عبد العزيز', 420, 'Djelfa', 'عين وسارة', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(99, 'العقيد لطفي', '', '', '672523076', '', 0, 'Djelfa', 'عين وسارة', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(100, 'اوقيس مرباع', '', '', '27807309', 'كريفيف صالح', 0, 'Djelfa', 'عين وسارة', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(101, 'اول نوفمبر 54', '', '', '699935021', '', 145, 'Djelfa', 'عين وسارة', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(102, 'بن مهل فارس', '', '', '696265771', '', 0, 'Djelfa', 'عين وسارة', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(103, 'جعفور محمد بوعمامة', '', '', '780257966', '', 300, 'Djelfa', 'عين وسارة', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(104, 'جغمة رمضان', '', '', '772390823', '', 0, 'Djelfa', 'عين وسارة', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(105, 'حيرش عبد القادر', '', '', '793478818', '', 0, 'Djelfa', 'عين وسارة', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(106, 'خديجة ام المؤمنين', '', '', '664160563', '', 400, 'Djelfa', 'عين وسارة', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(107, 'دهيليس احمد', '', '', '791510592', 'عبد الحميد شداد', 0, 'Djelfa', 'عين وسارة', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(108, 'ربحي العيهار', '', '', '777692156', 'نية دردوري', 420, 'Djelfa', 'عين وسارة', NULL, NULL, NULL, '2025-12-21 11:07:31', '2025-12-21 11:07:31'),
(109, 'رحماني محمد يحي', '', '', '772035311', '', 0, 'Djelfa', 'عين وسارة', NULL, NULL, NULL, '2025-12-21 11:07:32', '2025-12-21 11:07:32'),
(110, 'زايدي داود القدس', '', '', '777962329', '', 0, 'Djelfa', 'عين وسارة', NULL, NULL, NULL, '2025-12-21 11:07:32', '2025-12-21 11:07:32'),
(111, 'شراف سعد', '', '', '773306282', '', 0, 'Djelfa', 'عين وسارة', NULL, NULL, NULL, '2025-12-21 11:07:32', '2025-12-21 11:07:32'),
(112, 'طرشون مصطفى', '', '', '778858490', '', 0, 'Djelfa', 'عين وسارة', NULL, NULL, NULL, '2025-12-21 11:07:32', '2025-12-21 11:07:32'),
(113, 'طيباوي المسعود', '', '', '771659591', '', 0, 'Djelfa', 'عين وسارة', NULL, NULL, NULL, '2025-12-21 11:07:32', '2025-12-21 11:07:32'),
(114, 'عائشة ام المؤمنين', '', '', '774657682', 'قوطارة دحمان', 0, 'Djelfa', 'عين وسارة', NULL, NULL, NULL, '2025-12-21 11:07:32', '2025-12-21 11:07:32'),
(115, 'عبد الحميد قمورة', '', '', '771865045', '', 0, 'Djelfa', 'عين وسارة', NULL, NULL, NULL, '2025-12-21 11:07:32', '2025-12-21 11:07:32'),
(116, 'عقبة بن نافع', '', '', '772364683', 'قاسمي', 460, 'Djelfa', 'عين وسارة', NULL, NULL, NULL, '2025-12-21 11:07:32', '2025-12-21 11:07:32'),
(117, 'عمران الطيب', '', '', '791569877', '', 0, 'Djelfa', 'عين وسارة', NULL, NULL, NULL, '2025-12-21 11:07:32', '2025-12-21 11:07:32'),
(118, 'غول محمد', '', '', '659611075', '', 0, 'Djelfa', 'عين وسارة', NULL, NULL, NULL, '2025-12-21 11:07:32', '2025-12-21 11:07:32'),
(119, 'غويني عبد القادر', '', '', '27945154', '', 0, 'Djelfa', 'عين وسارة', NULL, NULL, NULL, '2025-12-21 11:07:32', '2025-12-21 11:07:32'),
(120, 'فرحات الطيب', '', '', '770202590', '', 617, 'Djelfa', 'عين وسارة', NULL, NULL, NULL, '2025-12-21 11:07:32', '2025-12-21 11:07:32'),
(121, 'فرحات مرهون', '', '', '774743154', '', 0, 'Djelfa', 'عين وسارة', NULL, NULL, NULL, '2025-12-21 11:07:32', '2025-12-21 11:07:32'),
(122, 'قاسمي الحسني احمد', '', '', '671085755', 'الصيد حيزية', 376, 'Djelfa', 'عين وسارة', NULL, NULL, NULL, '2025-12-21 11:07:32', '2025-12-21 11:07:32'),
(123, 'قداش السعيد', '', '', '792400592', '', 0, 'Djelfa', 'عين وسارة', NULL, NULL, NULL, '2025-12-21 11:07:32', '2025-12-21 11:07:32'),
(124, 'لحرش مصطفى', 'حاسي بحبح', 'حاسي بحبح', '666110690', '', 0, 'Djelfa', 'عين وسارة', NULL, NULL, NULL, '2025-12-21 11:07:32', '2025-12-21 11:07:32'),
(125, 'لحول الميلود', '', '', '669330188', '', 0, 'Djelfa', 'عين وسارة', NULL, NULL, NULL, '2025-12-21 11:07:32', '2025-12-21 11:07:32'),
(126, 'لمين عبد الرحيم', '', '', '797971233', 'حزي لخضر', 0, 'Djelfa', 'عين وسارة', NULL, NULL, NULL, '2025-12-21 11:07:32', '2025-12-21 11:07:32'),
(127, 'مالك بن النبي', '', '', '27802421', '', 488, 'Djelfa', 'عين وسارة', NULL, NULL, NULL, '2025-12-21 11:07:32', '2025-12-21 11:07:32'),
(128, 'محمدالصديق بن يحي', '', '', '666539056', '', 230, 'Djelfa', 'عين وسارة', NULL, NULL, NULL, '2025-12-21 11:07:32', '2025-12-21 11:07:32'),
(129, 'محمد العيد ال خليفة', '', '', '771586635', 'بن كردو كريمة', 0, 'Djelfa', 'عين وسارة', NULL, NULL, NULL, '2025-12-21 11:07:32', '2025-12-21 11:07:32'),
(130, 'مرباح محمد بن الدكاني', '', '', '773007261', '', 0, 'Djelfa', 'عين وسارة', NULL, NULL, NULL, '2025-12-21 11:07:32', '2025-12-21 11:07:32'),
(131, 'مريسي محمد', '', '', '27807083', '', 0, 'Djelfa', 'عين وسارة', NULL, NULL, NULL, '2025-12-21 11:07:32', '2025-12-21 11:07:32'),
(132, 'مصطفاي بلقاسم', '', '', '673010202', '', 0, 'Djelfa', 'عين وسارة', NULL, NULL, NULL, '2025-12-21 11:07:32', '2025-12-21 11:07:32'),
(133, 'معمري محمد', '', '', '774937804', '', 0, 'Djelfa', 'عين وسارة', NULL, NULL, NULL, '2025-12-21 11:07:32', '2025-12-21 11:07:32'),
(134, 'نايب محمد', '', '', '699545016', '', 0, 'Djelfa', 'عين وسارة', NULL, NULL, NULL, '2025-12-21 11:07:32', '2025-12-21 11:07:32'),
(135, 'نقزيو ساعد', '', '', '667783761', '', 0, 'Djelfa', 'عين وسارة', NULL, NULL, NULL, '2025-12-21 11:07:32', '2025-12-21 11:07:32'),
(136, 'نوع د 400 مسكن 600مسكن', '', '', '770630112', '', 0, 'Djelfa', 'عين وسارة', NULL, NULL, NULL, '2025-12-21 11:07:32', '2025-12-21 11:07:32'),
(137, 'يحي بن علية', '', '', '790028517', '', 207, 'Djelfa', 'عين وسارة', NULL, NULL, NULL, '2025-12-21 11:07:32', '2025-12-21 11:07:32'),
(138, 'بار عمر بن عبد الرحمان', '', '', '672698825', 'خنيفر علي', 0, 'Ouled Djellal', 'البسباس', NULL, NULL, NULL, '2025-12-21 11:23:03', '2025-12-21 11:23:03'),
(139, 'هاني محمد', '', '', '671135867', '', 0, 'Ouled Djellal', 'البسباس', NULL, NULL, NULL, '2025-12-21 11:23:03', '2025-12-21 11:23:03'),
(140, 'بوبكر مبروك', '', '', '666106644', 'الزهرة يوب', 0, 'Ouled Djellal', 'الدوسن', NULL, NULL, NULL, '2025-12-21 11:23:03', '2025-12-21 11:23:03'),
(141, 'بوساحة المداني', '', '', '797972234', '', 0, 'Ouled Djellal', 'الدوسن', NULL, NULL, NULL, '2025-12-21 11:23:03', '2025-12-21 11:23:03'),
(142, 'تفة محمد', '', '', '0662146259/0550325220', '', 0, 'Ouled Djellal', 'الدوسن', NULL, NULL, NULL, '2025-12-21 11:23:03', '2025-12-21 11:23:03'),
(143, 'سكال حسين', '', '', '', '', 0, 'Ouled Djellal', 'الدوسن', NULL, NULL, NULL, '2025-12-21 11:23:03', '2025-12-21 11:23:03'),
(144, 'شايب ذراع علي', '', '', '33677281', '', 0, 'Ouled Djellal', 'الدوسن', NULL, NULL, NULL, '2025-12-21 11:23:03', '2025-12-21 11:23:03'),
(145, 'لكحل محمد', '', '', '793999589', '', 0, 'Ouled Djellal', 'الدوسن', NULL, NULL, NULL, '2025-12-21 11:23:03', '2025-12-21 11:23:03'),
(146, 'مصمودي محمد', '', '', '552050860', '', 0, 'Ouled Djellal', 'الدوسن', NULL, NULL, NULL, '2025-12-21 11:23:03', '2025-12-21 11:23:03'),
(147, 'العقيد لطفي', '', '', '663513720', '', 0, 'Ouled Djellal', 'اولاد جلال وسط', NULL, NULL, NULL, '2025-12-21 11:23:03', '2025-12-21 11:23:03'),
(148, 'الغول ابراهيم', '', '', '663894989', '', 0, 'Ouled Djellal', 'اولاد جلال وسط', NULL, NULL, NULL, '2025-12-21 11:23:03', '2025-12-21 11:23:03'),
(149, 'اولاد موسى العربي', '', '', '664129257', '', 0, 'Ouled Djellal', 'اولاد جلال وسط', NULL, NULL, NULL, '2025-12-21 11:23:03', '2025-12-21 11:23:03'),
(150, 'زنودة مصطفى', '', '', '33560201', '', 0, 'Ouled Djellal', 'اولاد جلال وسط', NULL, NULL, NULL, '2025-12-21 11:23:03', '2025-12-21 11:23:03'),
(151, 'سماتي محمد بلعابد', '', '', '33662858', '', 0, 'Ouled Djellal', 'اولاد جلال وسط', NULL, NULL, NULL, '2025-12-21 11:23:03', '2025-12-21 11:23:03'),
(152, 'سي مزراق بلقاسم', '', '', '662157556', '', 0, 'Ouled Djellal', 'اولاد جلال وسط', NULL, NULL, NULL, '2025-12-21 11:23:03', '2025-12-21 11:23:03'),
(153, 'شخشوخ عبد الرحمان', '', '', '669886307', '', 0, 'Ouled Djellal', 'اولاد جلال وسط', NULL, NULL, NULL, '2025-12-21 11:23:03', '2025-12-21 11:23:03'),
(154, 'شلاغة الحشاني', '', '', '676284030', '', 0, 'Ouled Djellal', 'اولاد جلال وسط', NULL, NULL, NULL, '2025-12-21 11:23:03', '2025-12-21 11:23:03'),
(155, 'شنوفي الشريف', '', '', '673643837', '', 0, 'Ouled Djellal', 'اولاد جلال وسط', NULL, NULL, NULL, '2025-12-21 11:23:03', '2025-12-21 11:23:03'),
(156, 'عبدالحميد بن باديس', '', '', '698140411', '', 0, 'Ouled Djellal', 'اولاد جلال وسط', NULL, NULL, NULL, '2025-12-21 11:23:03', '2025-12-21 11:23:03'),
(157, 'فردوس عبد اللطيف', '', '', '671365318', '', 0, 'Ouled Djellal', 'اولاد جلال وسط', NULL, NULL, NULL, '2025-12-21 11:23:03', '2025-12-21 11:23:03'),
(158, 'لكحل مختار', '', '', '663788900', '', 0, 'Ouled Djellal', 'اولاد جلال وسط', NULL, NULL, NULL, '2025-12-21 11:23:03', '2025-12-21 11:23:03'),
(159, 'مفدي زكرياء', '', '', '668512413', '', 0, 'Ouled Djellal', 'اولاد جلال وسط', NULL, NULL, NULL, '2025-12-21 11:23:03', '2025-12-21 11:23:03'),
(160, 'مواق مسعود', '', '', '33662233', '', 0, 'Ouled Djellal', 'اولاد جلال وسط', NULL, NULL, NULL, '2025-12-21 11:23:03', '2025-12-21 11:23:03'),
(161, 'بن محياوي محمد', '', '', '666911498', '', 0, 'Ouled Djellal', 'سيدي خالد', NULL, NULL, NULL, '2025-12-21 11:23:03', '2025-12-21 11:23:03'),
(162, 'بودرهم محمد', '', '', '662754884', '', 0, 'Ouled Djellal', 'سيدي خالد', NULL, NULL, NULL, '2025-12-21 11:23:03', '2025-12-21 11:23:03'),
(163, 'بوطي لخضر', '', '', '', '', 0, 'Ouled Djellal', 'سيدي خالد', NULL, NULL, NULL, '2025-12-21 11:23:03', '2025-12-21 11:23:03'),
(164, 'جهرة الشيخ', '', '', '668607124', '', 0, 'Ouled Djellal', 'سيدي خالد', NULL, NULL, NULL, '2025-12-21 11:23:03', '2025-12-21 11:23:03'),
(165, 'حويلي بلعباسي', '', '', '33669543', '', 0, 'Ouled Djellal', 'سيدي خالد', NULL, NULL, NULL, '2025-12-21 11:23:03', '2025-12-21 11:23:03'),
(166, 'دريسي عبد الغفار', '', '', '660460978', '', 0, 'Ouled Djellal', 'سيدي خالد', NULL, NULL, NULL, '2025-12-21 11:23:03', '2025-12-21 11:23:03'),
(167, 'زهانة لزهاري', '', '', '665961133', '', 0, 'Ouled Djellal', 'سيدي خالد', NULL, NULL, NULL, '2025-12-21 11:23:03', '2025-12-21 11:23:03'),
(168, 'عبد الحميد بن باديس', '', '', '33672151', '', 0, 'Ouled Djellal', 'سيدي خالد', NULL, NULL, NULL, '2025-12-21 11:23:03', '2025-12-21 11:23:03'),
(169, 'مدلل محمد', '', '', '664639423', '', 0, 'Ouled Djellal', 'سيدي خالد', NULL, NULL, NULL, '2025-12-21 11:23:03', '2025-12-21 11:23:03'),
(170, 'بن خليفة عبد المجيد', '', '', '698654851', '', 0, 'Mila', 'الرواشد', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(171, 'بوعنق رابح', 'شرش', 'شرش', '655324665', '', 0, 'Mila', 'الرواشد', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(172, 'شحدان عبد المجيد', 'سيدي زروق', 'سيدي زروق', '790773574', 'بن حمادة التوفيق', 0, 'Mila', 'الرواشد', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(173, 'صالح بن عميرة', '', '', '657842928', '', 0, 'Mila', 'الرواشد', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(174, 'علي برباش', 'تيبرقنت', 'تيبرقنت', '658483051', '', 0, 'Mila', 'الرواشد', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(175, 'مدني مولود', '', '', '775234001', '', 0, 'Mila', 'الرواشد', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(176, 'احمد بوشطاط', '', '', '668618401', '', 0, 'Mila', 'القرارم قوقة', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(177, 'الاخوان فلاحي', '', '', '772068909', '', 0, 'Mila', 'القرارم قوقة', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(178, 'الاخوان نجار', '', '', '777408642', '', 0, 'Mila', 'القرارم قوقة', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(179, 'جامع مختار', '', '', '792488793', '', 0, 'Mila', 'القرارم قوقة', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(180, 'حميدة مزهود', '', '', '44571670', '', 0, 'Mila', 'القرارم قوقة', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(181, 'دفيش عمار', '', '', '795075415', '', 0, 'Mila', 'القرارم قوقة', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(182, 'عبد الكريم قراشي', '', '', '676991028', '', 0, 'Mila', 'القرارم قوقة', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(183, 'عمر بوالعرتروس', '', '', '667996461', '', 0, 'Mila', 'القرارم قوقة', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(184, 'محمد سفاري', '', '', '665267322', '', 0, 'Mila', 'القرارم قوقة', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(185, 'نوار بورصاص', '', '', '675871799', '', 0, 'Mila', 'القرارم قوقة', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(186, 'غلام عبد المجيد', '', '', '777485518', '', 0, 'Mila', 'بوحاتم', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(187, 'الاخوان بلخير', '', '', '774539358', '', 0, 'Mila', 'تاجنانت', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(188, 'الاخوين قروج', '', '', '796868037', '', 0, 'Mila', 'تاجنانت', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(189, 'الوئام', '704مسكن', '704مسكن', '0791674690/0791624374', 'فرطاس سميرة', 608, 'Mila', 'تاجنانت', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(190, 'جمعون عيسى', '', '', '775011493', '', 0, 'Mila', 'تاجنانت', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(191, 'حليتيم رابح', '', '', '799068469', '', 0, 'Mila', 'تاجنانت', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(192, 'رقيعي عياش', '', '', '779662050', '', 1032, 'Mila', 'تاجنانت', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(193, 'عرامة الهاشمي', '', '', '0671402202/0773750579', '', 0, 'Mila', 'تاجنانت', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(194, 'عوري شاقر', '', '', '774463726', '', 0, 'Mila', 'تاجنانت', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(195, 'قاسم اوريدة', '', '', '771543565', '', 335, 'Mila', 'تاجنانت', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(196, 'قاسمية عمار', '', '', '799835422', '', 0, 'Mila', 'تاجنانت', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(197, 'قوجيل دراجي', '', '', '699807739', '', 0, 'Mila', 'تاجنانت', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(198, 'مانع رمضان', '', '', '799541756', '', 0, 'Mila', 'تاجنانت', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(199, 'مظاهرات11ديسمبر', '', '', '31402410', '', 0, 'Mila', 'تاجنانت', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(200, 'مولود فرعون', '', '', '772915346', '', 0, 'Mila', 'تاجنانت', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(201, 'يحي احمد', '', '', '542136139', 'باها نورة', 783, 'Mila', 'تاجنانت', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(202, 'الاخوة بلحاج', '', '', '559819426', '', 0, 'Mila', 'تلاغمة', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(203, 'الطاهر خنفوف', '', '', '540445058', '', 414, 'Mila', 'تلاغمة', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(204, 'حدروف الطيب', '', '', '780224762', '', 0, 'Mila', 'تلاغمة', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(205, 'حويشة عمار', '', '', '798437373', '', 0, 'Mila', 'تلاغمة', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(206, 'خالد بن الوليد', '', '', '799070322', '', 0, 'Mila', 'تلاغمة', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(207, 'بولمزاود', '', '', '793354393', '', 360, 'Mila', 'زغاية', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(208, '20اوت 1955', '', '', '774932291', '', 440, 'Mila', 'شلغوم العيد', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(209, 'احمد هلال', '', '', '776135229', '', 0, 'Mila', 'شلغوم العيد', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(210, 'اردير عبد الرحمان', '', '', '670255825', '', 0, 'Mila', 'شلغوم العيد', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(211, 'اعجيبة محمد', '', '', '673651749', '', 0, 'Mila', 'شلغوم العيد', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(212, 'الجمهورية', '', '', '776842103', '', 250, 'Mila', 'شلغوم العيد', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(213, 'الحرية', '', '', '793683743', 'مسعود شنوف', 0, 'Mila', 'شلغوم العيد', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(214, 'العمري ماضي', '', '', '659685239', 'بن خليفة سليم', 0, 'Mila', 'شلغوم العيد', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(215, 'بن مرزوق لخضر', '', '', '658229022', '', 0, 'Mila', 'شلغوم العيد', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(216, 'بن يحيى عمار', '', '', '658787280', '', 0, 'Mila', 'شلغوم العيد', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(217, 'بورواق عبد الحميد', '', '', '668573899', '', 0, 'Mila', 'شلغوم العيد', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(218, 'بوكرسي رابح', '', '', '541016840', '', 700, 'Mila', 'شلغوم العيد', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(219, 'حامور بولخراص', '', '', '698735686', '', 0, 'Mila', 'شلغوم العيد', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(220, 'حايفي صالح', '', '', '798737473', '', 0, 'Mila', 'شلغوم العيد', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(221, 'حمود بن خاوة', '', '', '662364789', '', 0, 'Mila', 'شلغوم العيد', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(222, 'خليفي عبد الرحمان', '', '', '771896280', '', 0, 'Mila', 'شلغوم العيد', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(223, 'رقيق عبد الرحمان', '', '', '676480829', '', 0, 'Mila', 'شلغوم العيد', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(224, 'شعلال ابراهيم', '', '', '777431917', '', 0, 'Mila', 'شلغوم العيد', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(225, 'عبان رمضان', '', '', '668103196', '', 0, 'Mila', 'شلغوم العيد', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(226, 'عبد الله باشا', '', '', '798782980', '', 0, 'Mila', 'شلغوم العيد', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(227, 'عليمي الصافية', '', '', '557979408', '', 0, 'Mila', 'شلغوم العيد', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(228, 'عمر حجار', '', '', '794400090', 'لزهر', 0, 'Mila', 'شلغوم العيد', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(229, 'قرفي محمد', '', '', '791840201', 'حمة ش', 0, 'Mila', 'شلغوم العيد', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(230, 'محمد ادريس', '', '', '793244299', '', 0, 'Mila', 'شلغوم العيد', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(231, 'مخناش موسى', '', '', '668329909', '', 0, 'Mila', 'شلغوم العيد', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(232, 'مزدورة الربيع بن علي', '', '', '668058565', 'لعور وهيبة', 619, 'Mila', 'شلغوم العيد', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(233, 'مصطفى بن بولعيد', '', '', '790846347', '', 550, 'Mila', 'شلغوم العيد', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(234, 'مصنف يوسف', 'جامع الأخضر', 'جامع الأخضر', '774937116', '', 0, 'Mila', 'شلغوم العيد', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(235, 'اعزيز احمد', '', '', '672406794', '', 332, 'Mila', 'عين الملوك', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(236, 'دباش الزواوي', '', '', '666549869', '', 0, 'Mila', 'عين الملوك', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(237, 'احمد عبد الرزاق', '', '', '773928286', 'التوفيق مرابط فيلالي', 300, 'Mila', 'فرجيوة', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(238, 'بوفنيزة العربي', '', '', '559652810', 'ص, ساحلي', 0, 'Mila', 'فرجيوة', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(239, 'بوجاجوة صالح', '', '', '31452503', '', 0, 'Mila', 'فرجيوة', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(240, 'عطية العمري', '', '', '663592029', 'مسعود بن سي علي', 0, 'Mila', 'فرجيوة', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(241, 'مقدم اسماعيل', '', '', '560067572', '', 0, 'Mila', 'فرجيوة', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(242, 'نوارة بلعيدي', '', '', '663141719', '', 0, 'Mila', 'فرجيوة', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(243, 'بوزريدة حمو', '', '', '774093843', 'عبد المالك', 355, 'Mila', 'مشيرة', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(244, 'زواوي الربيع', '', '', '674017941', '', 0, 'Mila', 'مشيرة', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(245, 'احمد حملة', '', '', '666791630', '', 0, 'Mila', 'ميلة وسط', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(246, 'اعليوش اسماعيل', '', '', '0655188896/0774853860', '', 0, 'Mila', 'ميلة وسط', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(247, 'العربي بن رجم', '', '', '676773076', '', 0, 'Mila', 'ميلة وسط', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(248, 'بلعطار ابراهيم', '', '', '655911441', '', 0, 'Mila', 'ميلة وسط', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(249, 'بلعطار محمد الصالح', 'الثنية', 'الثنية', '662864875', 'لكحل بشير', 0, 'Mila', 'ميلة وسط', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(250, 'بن الشيهب عبد الرحمان', '', '', '031414773/0661160444', '', 0, 'Mila', 'ميلة وسط', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(251, 'بن داكير صالح', '', '', '666738806', 'بن طليعة فتيحة', 154, 'Mila', 'ميلة وسط', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(252, 'بن مخلوف شعبان', '', '', '661124754', '', 0, 'Mila', 'ميلة وسط', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(253, 'بوخروبة محمد', '', '', '658899060', '', 0, 'Mila', 'ميلة وسط', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(254, 'بوزراع محمد', '', '', '540547961', '', 555, 'Mila', 'ميلة وسط', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(255, 'بوسبيسي عمار', '', '', '772408010', '', 0, 'Mila', 'ميلة وسط', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(256, 'بوطبجة عيسى', 'صناوة العليا', 'صناوة العليا', '676987881', '', 0, 'Mila', 'ميلة وسط', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(257, 'بوفامة محمود', '', '', '655651331', '', 0, 'Mila', 'ميلة وسط', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(258, 'بيرش مختار', '', '', '655239189', '', 0, 'Mila', 'ميلة وسط', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(259, 'حياة الشباب', '', '', '31473829', '', 0, 'Mila', 'ميلة وسط', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(260, 'رابح لحمر', '', '', '698437694', '', 0, 'Mila', 'ميلة وسط', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(261, 'زراقي عبد الله', '', '', '698061971', '', 0, 'Mila', 'ميلة وسط', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(262, 'زياني السعيد', '', '', '31480239', '', 0, 'Mila', 'ميلة وسط', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(263, 'سعي صالح', '', '', '31463576', '', 0, 'Mila', 'ميلة وسط', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(264, 'شاشي محمد الشريف', '', '', '781709299', '', 287, 'Mila', 'ميلة وسط', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(265, 'شريط فضيل', '', '', '', '', 0, 'Mila', 'ميلة وسط', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(266, 'صدراتي السعيد', 'صناوة العليا', 'صناوة العليا', '659131654', 'فراجي ز', 0, 'Mila', 'ميلة وسط', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(267, 'عبد الحميد بن باديس', '', '', '559261168', '', 0, 'Mila', 'ميلة وسط', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(268, 'علي بن ناصف', '', '', '658523024', '', 0, 'Mila', 'ميلة وسط', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(269, 'علي سعيداني', '', '', '770738400', '', 659, 'Mila', 'ميلة وسط', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(270, 'قشود محمد', '', '', '699553115', '', 0, 'Mila', 'ميلة وسط', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(271, 'لزغد خوخة', '', '', '664103710', '', 0, 'Mila', 'ميلة وسط', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(272, 'لعلالي لخضر', '', '', '655183997', '', 0, 'Mila', 'ميلة وسط', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(273, 'مشري المكي', '', '', '674147436', '', 0, 'Mila', 'ميلة وسط', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(274, 'ابن خلدون', '', '', '791562681', 'هند جبيلي', 664, 'Mila', 'وادي السقان', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(275, 'قرطوم احمد', '', '', '795727650', '', 431, 'Mila', 'وادي السقان', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(276, 'بحري امبارك بن الصادق', '', '', '779977868', '', 0, 'Mila', 'وادي العثمانية', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(277, 'بن عامر محفوظ', '', '', '779385708', '', 556, 'Mila', 'وادي العثمانية', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(278, 'بوحملين شعبان', '', '', '799408098', '', 0, 'Mila', 'وادي العثمانية', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(279, 'بوسحرة المولد', '', '', '553257805', '', 488, 'Mila', 'وادي العثمانية', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(280, 'حاج يوسف عبد الرحمان', '', '', '776614164', '', 0, 'Mila', 'وادي العثمانية', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(281, 'دعاس السعيد', '', '', '782076621', 'مليكة', 409, 'Mila', 'وادي العثمانية', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(282, 'شلوش عمار', 'جبل اعقاب', 'جبل اعقاب', '670184317', 'صونية', 0, 'Mila', 'وادي العثمانية', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(283, 'شوقي محمود', '', '', '779302142', 'صالحي السبتي', 338, 'Mila', 'وادي العثمانية', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(284, 'عقاب احمد', 'جبل اعقاب', 'جبل اعقاب', '698738402', '', 0, 'Mila', 'وادي العثمانية', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(285, 'فوغالي عيسى', 'جبل اعقاب', 'جبل اعقاب', '659001615', '', 0, 'Mila', 'وادي العثمانية', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(286, 'لقرع بوزيد', '', '', '555613143', '', 406, 'Mila', 'وادي العثمانية', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(287, 'الاخوان ضربان', '', '', '657130142', '', 297, 'Mila', 'وادي النجاء', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(288, 'بوعزة العمري', '', '', '698686466', '', 400, 'Mila', 'وادي النجاء', NULL, NULL, NULL, '2025-12-21 11:26:12', '2025-12-21 11:26:12'),
(289, 'الطيب بن عمر', '', '', '555641041', '', 0, 'M\'Sila', 'المعاضيد', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(290, 'اول نوفمبر54', '', '', '660506091', '', 482, 'M\'Sila', 'المعاضيد', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(291, 'بن خالد احمد', '', '', '667621822', '', 190, 'M\'Sila', 'المعاضيد', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(292, 'بن شعبان الحاج', '', '', '773330493', 'سرايش صالح', 270, 'M\'Sila', 'المعاضيد', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(293, 'بيدي شعبان', '', '', '662006683', '', 0, 'M\'Sila', 'المعاضيد', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(294, 'ساحة الشهداء', '', '', '657059410', '', 190, 'M\'Sila', 'المعاضيد', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(295, 'شرشال جلود ابراهيم', '', '', '696335357', '', 0, 'M\'Sila', 'المعاضيد', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(296, 'حساني بولنوار', '', '', '35454296', '', 180, 'M\'Sila', 'الهامل', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(297, 'عبد اللطيف الهاشمي', '', '', '669503294', '', 0, 'M\'Sila', 'الهامل', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(298, 'لخداري عمر', '', '', '666739752', '', 0, 'M\'Sila', 'الهامل', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(299, 'لغويني حسين', '', '', '669600760', '', 134, 'M\'Sila', 'الهامل', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(300, 'ابن باديس', '', '', '657095855', '', 0, 'M\'Sila', 'اولاد دراج', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(301, 'الغزالي', '', '', '0663447094/0541417678', '', 0, 'M\'Sila', 'اولاد دراج', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(302, 'بكري عبد القادر', 'الجرف', 'الجرف', '674533063', '', 0, 'M\'Sila', 'اولاد دراج', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(303, 'جغابة عبد الحميد', '', '', '0779749052/0659583793', '', 0, 'M\'Sila', 'اولاد دراج', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(304, 'داود محمد', '', '', '', '', 0, 'M\'Sila', 'اولاد دراج', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(305, 'دريدي', '', '', '778742221', '', 0, 'M\'Sila', 'اولاد دراج', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(306, 'سعادة سعادة', '', '', '798151300', '', 0, 'M\'Sila', 'اولاد دراج', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(307, 'صغيري سعد', '', '', '660538184', '', 594, 'M\'Sila', 'اولاد دراج', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(308, 'جميات موسى', '', '', '660140596', '', 0, 'M\'Sila', 'اولاد عدي قبالة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(309, 'زين سعد', '', '', '658811970', '', 0, 'M\'Sila', 'اولاد عدي قبالة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(310, 'ساسي الحواس', '', '', '697089938', '', 0, 'M\'Sila', 'اولاد عدي قبالة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(311, 'عرعار الصديق', '', '', '779378306', '', 0, 'M\'Sila', 'اولاد عدي قبالة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(312, 'علي صوشة بوزيد', '', '', '672016848', '', 0, 'M\'Sila', 'اولاد عدي قبالة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(313, 'لعويجي بشير', '', '', '776253376', '', 0, 'M\'Sila', 'اولاد عدي قبالة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(314, 'اول نوفمبر المركزية', '', '', '774604065', '', 0, 'M\'Sila', 'برهوم', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(315, 'بوزيدي ساعد', '', '', '673215811', '', 0, 'M\'Sila', 'برهوم', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(316, 'رحماني بلقاسم', '', '', '676681296', '', 0, 'M\'Sila', 'برهوم', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(317, 'رزيق ابراهيم', '', '', '669156878', '', 0, 'M\'Sila', 'برهوم', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(318, 'سعادي علاوة', '', '', '780579047', '', 0, 'M\'Sila', 'برهوم', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(319, 'عطابي احمد', '', '', '779316397', '', 0, 'M\'Sila', 'برهوم', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(320, 'عطابي عطاء الله', '', '', '699875917', '', 0, 'M\'Sila', 'برهوم', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(321, 'غرابي', '', '', '', '', 0, 'M\'Sila', 'برهوم', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(322, 'كريم مبروك', '', '', '669876178', '', 0, 'M\'Sila', 'برهوم', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(323, 'هدلي محمد', '', '', '655554507', '', 0, 'M\'Sila', 'برهوم', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(324, 'جعلابي الباهي', '', '', '66815668', '', 0, 'M\'Sila', 'بلعايبة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(325, 'خزاري السعيد', 'نمط ب الجديدة', 'نمط ب الجديدة', '667113309', '', 0, 'M\'Sila', 'بلعايبة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(326, 'خزاري عثمان', '', '', '658070024', '', 0, 'M\'Sila', 'بلعايبة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(327, 'راجي احمد', '', '', '662841681', '', 0, 'M\'Sila', 'بلعايبة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(328, 'لعجال ابراهيم', '', '', '668935880', '', 0, 'M\'Sila', 'بلعايبة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(329, 'لعجال الرحابي', '', '', '664105653', '', 0, 'M\'Sila', 'بلعايبة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(330, 'ابراهيم سرقين', '', '', '698954625', '', 0, 'M\'Sila', 'بوسعادة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(331, 'ابن شلالي احمد', '', '', '657906195', '', 0, 'M\'Sila', 'بوسعادة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(332, 'الاخوة الباهي', '', '', '656192113', '', 0, 'M\'Sila', 'بوسعادة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(333, 'الاخوة الهاني محمد وعبد الله', '', '', '774820804', '', 482, 'M\'Sila', 'بوسعادة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(334, 'الاخوة بودشيشة', '', '', '', '', 0, 'M\'Sila', 'بوسعادة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(335, 'الاخوة رحموني', '', '', '553762535', '', 0, 'M\'Sila', 'بوسعادة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(336, 'الاخوة طيبي', '', '', '669793662', '', 0, 'M\'Sila', 'بوسعادة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(337, 'الزين مازوز', '400مسكن', '400مسكن', '', '', 0, 'M\'Sila', 'بوسعادة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(338, 'الفاتح نوفمبر 1954', '', '', '665063682', 'بن سعيدي اسعيد', 800, 'M\'Sila', 'بوسعادة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(339, 'المجمع المدرسي الجديد', '', '', '550206051', '', 0, 'M\'Sila', 'بوسعادة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(340, 'المجمع المدرسي الجديد نمط د', 'لمخلطي احمد', 'لمخلطي احمد', '698535815', 'قويدر لوعيل', 0, 'M\'Sila', 'بوسعادة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(341, 'المعلمون الأربعة', '', '', '674460097', '', 0, 'M\'Sila', 'بوسعادة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(342, 'بازة محمد', '', '', '675177999', '', 0, 'M\'Sila', 'بوسعادة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(343, 'بوسعادة مركز', '', '', '666392545', '', 0, 'M\'Sila', 'بوسعادة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(344, 'توامة السعيد', '', '', '664041862', '', 0, 'M\'Sila', 'بوسعادة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(345, 'حليتيم عبد الله', '', '', '660465770', 'بن ثامر فرحات', 400, 'M\'Sila', 'بوسعادة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(346, 'حميدة عبد القادر', '', '', '669139773', '', 0, 'M\'Sila', 'بوسعادة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(347, 'حميدي عيسى', '', '', '658890721', 'حيمد عبد الحفيظ', 706, 'M\'Sila', 'بوسعادة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(348, 'حي المستشفى', '', '', '668213101', '', 0, 'M\'Sila', 'بوسعادة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(349, 'ربيع احمد', '', '', '672885768', '', 0, 'M\'Sila', 'بوسعادة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(350, 'رضا حوحو', '', '', '698584627', '', 0, 'M\'Sila', 'بوسعادة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(351, 'رمضان حسوني', '', '', '666434213', '', 0, 'M\'Sila', 'بوسعادة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(352, 'سعيداني سعد', '', '', '669646653', '', 0, 'M\'Sila', 'بوسعادة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(353, 'سيدي ثامر', '', '', '660133050', '', 140, 'M\'Sila', 'بوسعادة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(354, 'سيدي سليمان الجديدة', '', '', '666720567', '', 0, 'M\'Sila', 'بوسعادة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(355, 'سيدي سليمان القديمة', '', '', '658459268', 'خزاري الحواس', 0, 'M\'Sila', 'بوسعادة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28');
INSERT INTO `schools` (`id`, `name`, `address`, `district`, `phone`, `manager_name`, `student_count`, `wilaya`, `commune`, `latitude`, `longitude`, `radius`, `created_at`, `updated_at`) VALUES
(356, 'صلاح الدين الأيوبي', '', '', '673096779', '', 0, 'M\'Sila', 'بوسعادة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(357, 'طاع الله محمد', '', '', '669827318', 'عثماني جمال', 0, 'M\'Sila', 'بوسعادة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(358, 'طالب عبد الرحمان', '', '', '698003720', 'سليمان رزيقي', 370, 'M\'Sila', 'بوسعادة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(359, 'طريق الجزائر2', '', '', '655596622', 'الحامدي رمضان', 0, 'M\'Sila', 'بوسعادة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(360, 'طريق بسكرة', '', '', '697552672', 'إسماعيل ط', 500, 'M\'Sila', 'بوسعادة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(361, 'طويري عبد القادر', '', '', '655502951', 'بن سعيدي عيشة', 0, 'M\'Sila', 'بوسعادة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(362, 'محمد عبدو', '', '', '666422115', '', 0, 'M\'Sila', 'بوسعادة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(363, 'شنيحات محمد', '', '', '663865144', 'جياب صباح', 547, 'M\'Sila', 'حمام الضلعة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(364, 'سمان عبد القادر', '', '', '660813834', '', 0, 'M\'Sila', 'حمام الضلعة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(365, 'عبد السلام السايح', '', '', '655644453', 'مراح كمال', 132, 'M\'Sila', 'حمام الضلعة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(366, 'عربية دحمان', '', '', '663677784', 'بحاش جمال', 0, 'M\'Sila', 'حمام الضلعة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(367, 'منصوري النوي', '', '', '663008228', '', 0, 'M\'Sila', 'حمام الضلعة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(368, 'بوصيلة بن هني', '', '', '0770828509/0665151785', '', 0, 'M\'Sila', 'سيدي عامر', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(369, 'حديبي فرحات', '', '', '667677582', '', 0, 'M\'Sila', 'سيدي عامر', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(370, 'حديبي عبد القادر', '', '', '667185669', '', 456, 'M\'Sila', 'سيدي عامر', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(371, 'زياد احمد', '', '', '770530785', '', 0, 'M\'Sila', 'سيدي عامر', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(372, 'لكحل محمد', '', '', '35465175', '', 0, 'M\'Sila', 'سيدي عامر', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(373, 'ياسف السعيد', '', '', '0770212379/0663787569', '', 0, 'M\'Sila', 'سيدي عامر', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(374, 'احمد زبانة', '', '', '659134224', '', 287, 'M\'Sila', 'سيدي عيسى', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(375, 'بن عروس الحسين', '', '', '661937653', 'فطيمة قحقاح', 0, 'M\'Sila', 'سيدي عيسى', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(376, 'حسان بن ثابت', '', '', '675061501', 'منير جلابي', 0, 'M\'Sila', 'سيدي عيسى', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(377, 'زيغود يوسف', '', '', '662720411', 'فاضل كمال', 0, 'M\'Sila', 'سيدي عيسى', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(378, 'ضيف بلعموري', '', '', '662039972', '', 0, 'M\'Sila', 'سيدي عيسى', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(379, 'عبدلي محمد بن شتيون', '', '', '658240577', '', 0, 'M\'Sila', 'سيدي عيسى', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(380, 'علالي عبد القادر', '', '', '661453207', '', 0, 'M\'Sila', 'سيدي عيسى', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(381, 'محجوبي محمد الربيع', '', '', '659198003', 'وارم مسعود', 0, 'M\'Sila', 'سيدي عيسى', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(382, 'مصطفى بن بولعيد', '', '', '662549134', 'قارة سعيد', 0, 'M\'Sila', 'سيدي عيسى', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(383, 'الابتدائية الجديدة', '', '', '669092353', 'سعودي فارس', 202, 'M\'Sila', 'عين الحجل', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(384, 'هواري بومدين', '', '', '664378824', 'بلهادي جمال', 0, 'M\'Sila', 'عين الحجل', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(385, 'بركاتي عبد الله', '', '', '668819896', '', 0, 'M\'Sila', 'عين الخضراء', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(386, 'دمان علي', 'ذبابحة', 'ذبابحة', '665461624', '', 0, 'M\'Sila', 'عين الخضراء', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(387, 'رقيق لخضر', '', '', '662944501', '', 0, 'M\'Sila', 'عين الخضراء', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(388, 'شريف درويش', '', '', '791652562', '', 0, 'M\'Sila', 'عين الخضراء', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(389, 'شيهاني قويدر', '', '', '698837000', '', 0, 'M\'Sila', 'عين الخضراء', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(390, 'مبروك طعشوش', '', '', '660016083', '', 0, 'M\'Sila', 'عين الخضراء', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(391, '22571', '', '', '671427119', '', 0, 'M\'Sila', 'مسيلة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(392, 'ابن الصديق السعيد', '', '', '550466373', 'سلطاني الخثير', 125, 'M\'Sila', 'مسيلة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(393, 'الرجاء', '', '', '674975495', 'قطوش حنان', 0, 'M\'Sila', 'مسيلة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(394, 'الشهداء', '', '', '656334483', '', 600, 'M\'Sila', 'مسيلة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(395, 'بركة عمار', '', '', '655291909', '', 0, 'M\'Sila', 'مسيلة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(396, 'بلخير بوجمعة', '', '', '696613893', '', 500, 'M\'Sila', 'مسيلة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(397, 'بلقاسمي مسعو د', '', '', '667498731', 'ريحاوي أحمد', 720, 'M\'Sila', 'مسيلة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(398, 'بن عيسى مولود', '', '', '655958925', '', 0, 'M\'Sila', 'مسيلة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(399, 'بن عيشة محمد الطيب', '', '', '669680203', '', 0, 'M\'Sila', 'مسيلة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(400, 'بن يونس عيسى', '', '', '671638195', 'طيايبة مصطفى', 0, 'M\'Sila', 'مسيلة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(401, 'بودراي ضلعاوي', '', '', '660425677', '', 0, 'M\'Sila', 'مسيلة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(402, 'بورزق عبد الرحمان', '', '', '676132200', '', 0, 'M\'Sila', 'مسيلة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(403, 'بورويس علي', '', '', '668711231', 'بطيح محمد', 235, 'M\'Sila', 'مسيلة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(404, 'بوضياف علي', '', '', '696035113', 'مزعاش هاجر', 400, 'M\'Sila', 'مسيلة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(405, 'تواتي محمد بن العمري', '', '', '671296128', 'مرنيز أحمد', 0, 'M\'Sila', 'مسيلة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(406, 'جدي الديلمي', '', '', '666155342', '', 0, 'M\'Sila', 'مسيلة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(407, 'حجاب احمد', '', '', '662873920', '', 0, 'M\'Sila', 'مسيلة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(408, 'حجاب لهول', '', '', '658919668', 'جعيجع الضيف', 0, 'M\'Sila', 'مسيلة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(409, 'حريزي فرحات', '', '', '656550604', 'بورنان عبد الحفيظ', 0, 'M\'Sila', 'مسيلة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(410, 'حيمر عبد الرحمان', '', '', '698461684', '', 0, 'M\'Sila', 'مسيلة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(411, 'خلفة بركاهم', '', '', '657786669', '', 908, 'M\'Sila', 'مسيلة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(412, 'رجم عبد القادر', '', '', '662831818', 'زاوي مصطفى', 0, 'M\'Sila', 'مسيلة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(413, 'سالمي سليم', '', '', '662874414', 'عبد الرفيق روبي', 0, 'M\'Sila', 'مسيلة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(414, 'سالمي مهدي', '', '', '770401218', '', 0, 'M\'Sila', 'مسيلة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(415, 'سهيلي ديلمي', '', '', '671948381', '', 0, 'M\'Sila', 'مسيلة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(416, 'شبيرة بن شيرة', '', '', '699081568', '', 0, 'M\'Sila', 'مسيلة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(417, 'شتيح المداني', '', '', '658812285', 'شلباب عبيدة', 0, 'M\'Sila', 'مسيلة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(418, 'شيكوش سعد', '', '', '35345538', '', 233, 'M\'Sila', 'مسيلة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(419, 'عبد الحميد ابن باديس', '', '', '669242910', '', 0, 'M\'Sila', 'مسيلة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(420, 'عريوة قانة', '', '', '656267080', '', 0, 'M\'Sila', 'مسيلة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(421, 'علي عفصي عبد الرحمان', '', '', '671625247', '', 0, 'M\'Sila', 'مسيلة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(422, 'عمروش ابراهيم', 'مزرير', 'مزرير', '698075818', '', 0, 'M\'Sila', 'مسيلة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(423, 'عمرون مختار', '', '', '662933409', '', 0, 'M\'Sila', 'مسيلة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(424, 'عميش مبارك', '', '', '669887531', 'علي بختي', 759, 'M\'Sila', 'مسيلة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(425, 'عياظ فطوم', '', '', '697612096', '', 0, 'M\'Sila', 'مسيلة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(426, 'عيشوش محمد', 'المويطة', 'المويطة', '671905796', 'بن يطو توفيق', 0, 'M\'Sila', 'مسيلة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(427, 'غضبان بن صوشة', '', '', '666155286', '', 0, 'M\'Sila', 'مسيلة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(428, 'غلاب السعيد', '', '', '667034664', 'عبد الحق تيطراوي', 0, 'M\'Sila', 'مسيلة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(429, 'لخنش عبد الله', '', '', '666457144', 'فرحات إبراهيم', 0, 'M\'Sila', 'مسيلة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(430, 'لمرد خذير', '', '', '664888894', 'والي رمضان', 0, 'M\'Sila', 'مسيلة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(431, 'محمد الشريف خير الدين', '', '', '662294179', 'عمروش احمد', 0, 'M\'Sila', 'مسيلة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(432, 'مسلم عبد الرحمان لخضر', '', '', '668619379', '', 0, 'M\'Sila', 'مسيلة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(433, 'مشني السعيد', '', '', '662555657', '', 420, 'M\'Sila', 'مسيلة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(434, 'مويسات الفضيل', '', '', '663549739', '', 0, 'M\'Sila', 'مسيلة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(435, 'هنتالي علي', '', '', '661333739', 'نقاز عثمان', 0, 'M\'Sila', 'مسيلة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(436, 'الشاذلي محمد الأخضر', '', '', '', '', 0, 'M\'Sila', 'مقرة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(437, 'بن ناصر علي', '', '', '663984118', '', 0, 'M\'Sila', 'مقرة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(438, 'بوحفص دحمان', '', '', '772280991', '', 260, 'M\'Sila', 'مقرة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(439, 'بوعافية الطيب', '', '', '771896324', '', 0, 'M\'Sila', 'مقرة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(440, 'حاجي قسوم', '', '', '774202036', '', 0, 'M\'Sila', 'مقرة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(441, 'خيري رابح', '', '', '660929649', '', 0, 'M\'Sila', 'مقرة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(442, 'رحماني محمود', '', '', '657107531', '', 0, 'M\'Sila', 'مقرة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(443, 'شوق محمد', '', '', '555046402', 'شموري وليد', 0, 'M\'Sila', 'مقرة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(444, 'عاشور العربي', '', '', '668140616', 'عشور الخير', 604, 'M\'Sila', 'مقرة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(445, 'عامر احمد', '', '', '666165415', '', 0, 'M\'Sila', 'مقرة', NULL, NULL, NULL, '2025-12-21 11:26:28', '2025-12-21 11:26:28'),
(446, 'براشد عبد الرزاق', '', '', '780341377', '', 0, 'El M\'Ghair', 'انسيغة', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(447, 'دغوش صالح', '', '', '698621303', '', 0, 'El M\'Ghair', 'انسيغة', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(448, 'خالدي عيسى', '', '', '780424039', 'سعد الله عيدية', 0, 'El M\'Ghair', 'انسيغة', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(449, 'مداس عمر', '', '', '792188757', 'براهيمي عثمان', 182, 'El M\'Ghair', 'انسيغة', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(450, 'الاخوة سلطاني', '', '', '780578343', '', 0, 'El M\'Ghair', 'تنديلة', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(451, 'رحماني محمد', '', '', '663496073', '', 0, 'El M\'Ghair', 'تنديلة', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(452, 'سلطاني التهامي', '', '', '663259095', '', 0, 'El M\'Ghair', 'تنديلة', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(453, 'سلطاني عبد القادر', 'الأغفيان', 'الأغفيان', '', '', 0, 'El M\'Ghair', 'تنديلة', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(454, 'بريالة بلقاسم', 'وغلانة', 'وغلانة', '780236071', 'بالطاهر مبروك بن احمد', 0, 'El M\'Ghair', 'جامعة', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(455, 'بلعقون صالح', '', '', '659926740', '', 0, 'El M\'Ghair', 'جامعة', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(456, 'بن عوالي عبد الرزاق', '', '', '662969883', '', 0, 'El M\'Ghair', 'جامعة', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(457, 'بن مبروك محمد', '', '', '780402027', '', 0, 'El M\'Ghair', 'جامعة', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(458, 'بن نونة الدراجي', '', '', '780254493', 'عزوك مختار', 0, 'El M\'Ghair', 'جامعة', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(459, 'بوحفص الحاج', '', '', '0667369833/0661950553', '', 0, 'El M\'Ghair', 'جامعة', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(460, 'بوحنية احمد', 'وغلانة', 'وغلانة', '699365089', 'منير', 0, 'El M\'Ghair', 'جامعة', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(461, 'بوعنان بلقاسم', '', '', '780294132', '', 347, 'El M\'Ghair', 'جامعة', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(462, 'رحماني ام الخير', '', '', '666571641', '', 0, 'El M\'Ghair', 'جامعة', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(463, 'سوالمي الجموعي', 'مازر الزاوية', 'مازر الزاوية', '782271111', 'هشام دودو', 0, 'El M\'Ghair', 'جامعة', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(464, 'عماري محمد', '', '', '', '', 0, 'El M\'Ghair', 'جامعة', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(465, 'قادري عبد السلام', 'العراق', 'العراق', '780454541', 'أيش محمد لسعد', 0, 'El M\'Ghair', 'جامعة', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(466, 'قطار مسعود', '', '', '662257037', '', 0, 'El M\'Ghair', 'جامعة', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(467, 'مجمع حي الحبل', '', '', '780236512', '', 0, 'El M\'Ghair', 'جامعة', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(468, 'مجمع علوشة', '', '', '780253217', 'زكاري تماسيني', 0, 'El M\'Ghair', 'جامعة', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(469, 'مسعودي علي', '', '', '782137045', 'لخضر ناصر', 518, 'El M\'Ghair', 'جامعة', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(470, 'برابح اسماعيل', '', '', '665848381', '', 0, 'El M\'Ghair', 'سطيل', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(471, 'رميثة محمد', '', '', '780609125', 'محمد جروبي', 384, 'El M\'Ghair', 'سطيل', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(472, 'غشة صالح', '', '', '662424640', '', 0, 'El M\'Ghair', 'سطيل', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(473, 'العربي بن مهيدي1', '', '', '664167393', 'معمر بالطاهر', 0, 'El M\'Ghair', 'سيدي خليل', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(474, 'العربي بن مهيدي2', '', '', '666504989', 'بن قلية احمد', 0, 'El M\'Ghair', 'سيدي خليل', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(475, 'بالرابح علي', '', '', '782898755', '', 0, 'El M\'Ghair', 'سيدي خليل', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(476, 'سويسي محمد', 'عين الشيخ', 'عين الشيخ', '696519769', 'زوبيري الزهرة', 0, 'El M\'Ghair', 'سيدي خليل', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(477, '05جويلية1962', '', '', '', '', 0, 'El M\'Ghair', 'سيدي عمران', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(478, 'جلالي عيسى', '', '', '780206849', 'الأخضر بوليف', 0, 'El M\'Ghair', 'سيدي عمران', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(479, 'حمادي حسين', '', '', '660135881', '', 0, 'El M\'Ghair', 'سيدي عمران', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(480, 'حملاوي ابراهيم', '', '', '780453366', '', 0, 'El M\'Ghair', 'سيدي عمران', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(481, 'ريزوق بشير', '', '', '780261851', '', 0, 'El M\'Ghair', 'سيدي عمران', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(482, 'عمراني علي', '', '', '782272405', '', 0, 'El M\'Ghair', 'سيدي عمران', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(483, 'فضل السعيد', '', '', '780338006', '', 0, 'El M\'Ghair', 'سيدي عمران', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(484, 'العربي التبسي', '', '', '780223480', '', 691, 'El M\'Ghair', 'لمغير وسط', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(485, 'العقيد سي الحواس', '', '', '541530081', '', 0, 'El M\'Ghair', 'لمغير وسط', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(486, 'باسو لخضر', '', '', '663362830', '', 0, 'El M\'Ghair', 'لمغير وسط', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(487, 'بربيع محمد', '', '', '780217810', '', 0, 'El M\'Ghair', 'لمغير وسط', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(488, 'بركة موسى', '', '', '782295724', '', 0, 'El M\'Ghair', 'لمغير وسط', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(489, 'برمكي عيسى', '', '', '780349450', 'امحمد دباخ', 517, 'El M\'Ghair', 'لمغير وسط', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(490, 'جابو عبد الرحمان', '', '', '780343995', '', 0, 'El M\'Ghair', 'لمغير وسط', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(491, 'جروني رابح', '', '', '770538223', 'موسى الصغير', 0, 'El M\'Ghair', 'لمغير وسط', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(492, 'جريبيع عمر', '', '', '780233610', 'سعد دهنون', 0, 'El M\'Ghair', 'لمغير وسط', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(493, 'دباخ علي', '', '', '780553131', '', 0, 'El M\'Ghair', 'لمغير وسط', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(494, 'شهرة موسى', '', '', '780475105', '', 431, 'El M\'Ghair', 'لمغير وسط', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(495, 'عائشة ام المؤمنين', '', '', '780412191', 'كمال بالمهدي', 400, 'El M\'Ghair', 'لمغير وسط', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(496, 'علي خليل', '', '', '699716469', '', 0, 'El M\'Ghair', 'لمغير وسط', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(497, 'قيدوس احمد', '', '', '780339158', '', 0, 'El M\'Ghair', 'لمغير وسط', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(498, 'لهرم محمد', '', '', '780411814', '', 0, 'El M\'Ghair', 'لمغير وسط', NULL, NULL, NULL, '2025-12-21 11:26:44', '2025-12-21 11:26:44'),
(499, 'ابن رشد', '', '', '658402248', '', 0, 'Constantine', 'الخروب', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(500, 'احمد بوشبعة', '', '', '550689308', '', 0, 'Constantine', 'الخروب', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(501, 'البنات', '', '', '540563938', '', 0, 'Constantine', 'الخروب', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(502, 'الشيخ البيوض', '', '', '554552358', '', 0, 'Constantine', 'الخروب', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(503, 'العربي بن مهيدي', '', '', '793195994', '', 0, 'Constantine', 'الخروب', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(504, 'بطلي ساعد', '', '', '799354256', '', 0, 'Constantine', 'الخروب', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(505, 'بلعشار عمار', 'ماسينيسا الجديدة', 'ماسينيسا الجديدة', '0664018293/0784797950', '', 0, 'Constantine', 'الخروب', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(506, 'بلعطار محفوظ', '', '', '673664910', '', 407, 'Constantine', 'الخروب', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(507, 'بوخلخال شعبان', '', '', '655194849', '', 0, 'Constantine', 'الخروب', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(508, 'بوسنطوح صالح', '', '', '699056440', '', 0, 'Constantine', 'الخروب', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(509, 'حسين عبد الرزاق', '', '', '668525825', '', 0, 'Constantine', 'الخروب', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(510, 'ذيب الطاهر', '', '', '659004212', '', 0, 'Constantine', 'الخروب', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(511, 'ربيعي احمد', '', '', '540539390', '', 0, 'Constantine', 'الخروب', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(512, 'رميتة عبد العزيز', '', '', '790561578', '', 0, 'Constantine', 'الخروب', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(513, 'زبيري لخضر', '', '', '699666491', '', 476, 'Constantine', 'الخروب', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(514, 'زروق حسين', '', '', '555649957', '', 0, 'Constantine', 'الخروب', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(515, 'صالح خنشوش', '', '', '659912930', '', 0, 'Constantine', 'الخروب', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(516, 'طالب سليمان محمد', '', '', '779149189', '', 0, 'Constantine', 'الخروب', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(517, 'عبد الحميد ابن باديس', '', '', '550213357', '', 0, 'Constantine', 'الخروب', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(518, 'عزوز بوعروج', '', '', '676783332', '', 0, 'Constantine', 'الخروب', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(519, 'علاوة ابراهيم', '', '', '698830127', '', 0, 'Constantine', 'الخروب', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(520, 'فرقاني الطاهر', 'ماسينيسا', 'ماسينيسا', '667072103', '', 0, 'Constantine', 'الخروب', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(521, 'فيليكس سيقا محمد الصالح', '', '', '798263331', '', 0, 'Constantine', 'الخروب', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(522, 'كويرة محمد', '', '', '660648338', '', 289, 'Constantine', 'الخروب', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(523, 'محمد بوغرارة', '', '', '668927747', '', 0, 'Constantine', 'الخروب', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(524, 'مجدوب محمد', '', '', '555108009', '', 0, 'Constantine', 'الخروب', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(525, 'مخناش عبد الوحيد', '', '', '551438640', '', 0, 'Constantine', 'الخروب', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(526, 'مسعود بن مالك', '', '', '558277983', '', 0, 'Constantine', 'الخروب', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(527, 'مولود فرعون', 'ع1', 'ع1', '550594808', '', 0, 'Constantine', 'الخروب', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(528, 'يحياوي رمضان', '', '', '795667841', '', 0, 'Constantine', 'الخروب', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(529, 'يزليوي علي', '', '', '655162688', '', 0, 'Constantine', 'الخروب', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(530, 'بوحبيلة محمد', '', '', '794370145', '', 410, 'Constantine', 'حامة بوزيان', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(531, 'جلاب محمد الطاهر', '', '', '791648503', '', 0, 'Constantine', 'حامة بوزيان', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(532, 'شتيوي زينب', '', '', '796010623', '', 0, 'Constantine', 'حامة بوزيان', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(533, 'عدوي رابح', '', '', '779285204', '', 0, 'Constantine', 'حامة بوزيان', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(534, 'علاوة بن عربية', '', '', '797612582', '', 0, 'Constantine', 'حامة بوزيان', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(535, 'علاق عبد المجيد', '', '', '0795128041/0780308506', '', 0, 'Constantine', 'حامة بوزيان', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(536, 'علاق فضيل', '', '', '665193378', '', 0, 'Constantine', 'حامة بوزيان', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(537, 'علي عايش', '', '', '699504780', '', 0, 'Constantine', 'حامة بوزيان', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(538, 'عميور محمد', '', '', '657964319', '', 0, 'Constantine', 'حامة بوزيان', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(539, 'كرواش بكير', '', '', '673263218', '', 214, 'Constantine', 'حامة بوزيان', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(540, 'لقدار الشريف', '', '', '657603675', '', 0, 'Constantine', 'حامة بوزيان', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(541, 'بهولي عزيز', '', '', '698652646', '', 0, 'Constantine', 'ديدوش مراد', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(542, 'بوستح السعيد', '', '', '773350177', '', 0, 'Constantine', 'ديدوش مراد', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(543, 'بوشحم زيدان', '', '', '779598837', '', 0, 'Constantine', 'ديدوش مراد', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(544, 'بوفنش عمار', '', '', '0557777863/0667052580', '', 0, 'Constantine', 'ديدوش مراد', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(545, 'معمر بوعمامة', '', '', '795184623', '', 0, 'Constantine', 'ديدوش مراد', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(546, 'مولود بلعابد', '', '', '672636206', '', 0, 'Constantine', 'ديدوش مراد', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(547, 'ويلي حسان', '', '', '553005657', '', 0, 'Constantine', 'ديدوش مراد', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(548, 'الامير عبد القادر', '', '', '666013344', '', 0, 'Constantine', 'زيغود يوسف', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(549, 'الثعالبي علي', '', '', '31711198', '', 0, 'Constantine', 'زيغود يوسف', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(550, 'العربي التبسي', '', '', '667740425', '', 0, 'Constantine', 'زيغود يوسف', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(551, 'بوشامة حسين', '', '', '697838315', '', 450, 'Constantine', 'زيغود يوسف', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(552, 'بوشريحة احسن', '', '', '661624064', '', 0, 'Constantine', 'زيغود يوسف', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(553, 'بوضرسة السعيد بن الشريف', '', '', '699585419', '', 0, 'Constantine', 'زيغود يوسف', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(554, 'بوضرسة عيسى', '', '', '', '', 0, 'Constantine', 'زيغود يوسف', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(555, 'حمودي محمد', '', '', '31713256', '', 400, 'Constantine', 'زيغود يوسف', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(556, 'ريكواح الطاهر', '', '', '031919184/0696110212', '', 472, 'Constantine', 'زيغود يوسف', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(557, 'زغاد علي', '', '', '698857310', 'رجم عبد الغني', 687, 'Constantine', 'زيغود يوسف', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(558, 'شوقي يوسف', '', '', '658294094', '', 0, 'Constantine', 'زيغود يوسف', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(559, 'احمد بوشمال', '', '', '778287235', '', 0, 'Constantine', 'سيدي مبروك', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(560, 'الاخوة لشطر', '', '', '540051810', '', 0, 'Constantine', 'سيدي مبروك', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(561, 'الأمير عبد القادر', '', '', '540975725', '', 0, 'Constantine', 'سيدي مبروك', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(562, 'الجاحظ', '', '', '775707988', '', 0, 'Constantine', 'سيدي مبروك', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(563, 'حليمة السعدية', '', '', '550089039', '', 0, 'Constantine', 'سيدي مبروك', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(564, 'قرين خديجة', '', '', '657962859', '', 0, 'Constantine', 'سيدي مبروك', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(565, 'احمد شنطي', 'و ج 20', 'و ج 20', '699202544', '', 0, 'Constantine', 'علي منجلي', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(566, 'الهادف العكي34', '', '', '665920742', '', 0, 'Constantine', 'علي منجلي', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(567, 'الهاشمي اوتيلي', '', '', '542128020', '', 0, 'Constantine', 'علي منجلي', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(568, 'امامي السعيد', '', '', '791355986', '', 0, 'Constantine', 'علي منجلي', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(569, 'بحري الزواوي', '14', '14', '665716336', 'برحايل رضا', 0, 'Constantine', 'علي منجلي', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(570, 'بن عبد المالك رمضان', '', '', '674378621', '', 435, 'Constantine', 'علي منجلي', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(571, 'بن مخلوف عمار', '', '', '561680635', '', 0, 'Constantine', 'علي منجلي', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(572, 'بغيجة عمار', '', '', '657428080', 'ساحلي حمزة', 0, 'Constantine', 'علي منجلي', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(573, 'بوزيتونة الطيب', '', '', '661680658', '', 0, 'Constantine', 'علي منجلي', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(574, 'بوشريط لحرش', '', '', '31774444', '', 0, 'Constantine', 'علي منجلي', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(575, 'بوغازي محمد', '', '', '542295869', '', 355, 'Constantine', 'علي منجلي', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(576, 'بولحلايس لخضر', '', '', '670211625', '', 0, 'Constantine', 'علي منجلي', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(577, 'توفوتي لخضر', '', '', '', '', 0, 'Constantine', 'علي منجلي', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(578, 'جون لويس سيقا', '', '', '550781879', '', 0, 'Constantine', 'علي منجلي', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(579, 'دغة عثمان', '', '', '796008574', '', 0, 'Constantine', 'علي منجلي', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(580, 'دهشار عمار', '', '', '661707710', '', 0, 'Constantine', 'علي منجلي', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(581, 'روابح مبارك', '', '', '779105307', '', 0, 'Constantine', 'علي منجلي', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(582, 'زيادي بطو', '', '', '659049486', '', 0, 'Constantine', 'علي منجلي', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(583, 'سايبي بوشريط', '', '', '665115814', '', 0, 'Constantine', 'علي منجلي', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(584, 'سفاري حسين', '', '', '779905994', '', 0, 'Constantine', 'علي منجلي', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(585, 'سفاري مسعود', '', '', '557547672', '', 0, 'Constantine', 'علي منجلي', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(586, 'شعوة احمد', 'و ج 13', 'و ج 13', '792994049', '', 0, 'Constantine', 'علي منجلي', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(587, 'صدراتي عمر', '', '', '662277137', '', 0, 'Constantine', 'علي منجلي', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(588, 'طلاعة عمر', '', '', '792957216', '', 0, 'Constantine', 'علي منجلي', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(589, 'طيب نوايلي', '', '', '770615136', '', 0, 'Constantine', 'علي منجلي', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(590, 'عبد الحميد بلمجات', '', '', '30288686', '', 0, 'Constantine', 'علي منجلي', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(591, 'عيساني عمار', '24', '24', '657050777', '', 0, 'Constantine', 'علي منجلي', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(592, 'قيقاية الطيب', '', '', '673772523', '', 0, 'Constantine', 'علي منجلي', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(593, 'كحلوش حسين', '', '', '657148476', '', 0, 'Constantine', 'علي منجلي', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(594, 'كرباب السعيد', '', '', '772105149', '', 0, 'Constantine', 'علي منجلي', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(595, 'لحنش مختار', '', '', '699496067', '', 0, 'Constantine', 'علي منجلي', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(596, 'لعور رابح', '', '', '666320422', '', 0, 'Constantine', 'علي منجلي', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(597, 'مالك حداد/لخضر بن سي خليفة', '0', '0', '657481920', '', 0, 'Constantine', 'علي منجلي', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(598, 'مختار بوشحد', '', '', '775249115', '', 0, 'Constantine', 'علي منجلي', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(599, 'مختار بوشحد مشرفة', '', '', '', '', 0, 'Constantine', 'علي منجلي', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(600, 'مخلوف بوخزر', '', '', '656404488', '', 0, 'Constantine', 'علي منجلي', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(601, 'مزياني الشريف', '', '', '655340605', '', 0, 'Constantine', 'علي منجلي', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(602, 'معمر كريمي', '', '', '699736309', '', 0, 'Constantine', 'علي منجلي', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(603, 'نايلي صالح', '', '', '0778185820/0696399323', '', 0, 'Constantine', 'علي منجلي', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(604, 'يوسف ايدير', '', '', '657225434', '', 0, 'Constantine', 'علي منجلي', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(605, '5جويلية', '', '', '798680919', '', 0, 'Constantine', 'عين السمارة', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(606, 'بوطبر صالح', '', '', '551616597', '', 0, 'Constantine', 'عين السمارة', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(607, 'ابن الجبير', '', '', '795632553', '', 0, 'Constantine', 'قسنطينة', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(608, 'ابن زيدون', '', '', '30334483', 'السعيد راس الواد', 0, 'Constantine', 'قسنطينة', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(609, 'ابو القاسم الشابي', 'القماص', 'القماص', '659698936', '', 0, 'Constantine', 'قسنطينة', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(610, 'ابو عبيدة الجراح', 'الدقسي', 'الدقسي', '542670397', '', 349, 'Constantine', 'قسنطينة', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(611, 'اسد بن الفرات', '', '', '670235745', '', 530, 'Constantine', 'قسنطينة', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(612, 'العيدي خليفة', 'تخصيص محمود بوالصوف', 'تخصيص محمود بوالصوف', '774540188', '', 0, 'Constantine', 'قسنطينة', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(613, 'بابوري عتيقة', '', '', '772931462', '', 0, 'Constantine', 'قسنطينة', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(614, 'بن حافظ غنوجة', 'الاخوة فرار', 'الاخوة فرار', '550502859', '', 0, 'Constantine', 'قسنطينة', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(615, 'بوحبل صالح', '', '', '780704203', '', 0, 'Constantine', 'قسنطينة', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(616, 'بورغود بشير', '', '', '795253558', '', 0, 'Constantine', 'قسنطينة', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(617, 'بوقطاية محمد', 'القماص', 'القماص', '667104164', '', 729, 'Constantine', 'قسنطينة', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(618, 'حماني عمر', '', '', '795398966', '', 0, 'Constantine', 'قسنطينة', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(619, 'كريس بلقاسم', 'زواغي سليمان', 'زواغي سليمان', '549312532', 'نظيرة بودلاعة', 482, 'Constantine', 'قسنطينة', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(620, 'رابح عيسوس', '', '', '779241541', 'ليلى بوالصوف', 0, 'Constantine', 'قسنطينة', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(621, 'ريغة صالح', '', '', '561232931', '', 0, 'Constantine', 'قسنطينة', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(622, 'زرداني بلقاسم', '', '', '798141408', '', 0, 'Constantine', 'قسنطينة', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(623, 'زيغد اسماعيل', '', '', '550771767', '', 0, 'Constantine', 'قسنطينة', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(624, 'سي محمد بوقرة', '', '', '697779293', '', 98, 'Constantine', 'قسنطينة', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(625, 'طه حسين', '', '', '674458799', '', 0, 'Constantine', 'قسنطينة', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(626, 'عبد الحق عبد الحميد', '', '', '672389702', 'حبشي عبد الحفيظ', 0, 'Constantine', 'قسنطينة', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(627, 'عبد الرحمان الداخل', '', '', '794479626', '', 0, 'Constantine', 'قسنطينة', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(628, 'عبد القادر مجاوي', '', '', '773818150', '', 0, 'Constantine', 'قسنطينة', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(629, 'عبد الله ثنيو', '', '', '552925121', '', 0, 'Constantine', 'قسنطينة', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(630, 'عنابي مبارك', '', '', '797264417', '', 0, 'Constantine', 'قسنطينة', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(631, 'محمد خميستي', '', '', '655422049', '', 0, 'Constantine', 'قسنطينة', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(632, 'موسى شعبان', '', '', '659955809', '', 120, 'Constantine', 'قسنطينة', NULL, NULL, NULL, '2025-12-21 11:27:02', '2025-12-21 11:27:02'),
(633, 'احمد سلطان', '', '', '660616400', '', 0, 'Skikda', 'الحدائق', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(634, 'الاخوة شناف', '', '', '', '', 0, 'Skikda', 'الحدائق', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(635, 'الأمير عبد القادر', '', '', '793389953', '', 0, 'Skikda', 'الحدائق', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(636, 'بولقطوط صالح', '', '', '790863730', '', 0, 'Skikda', 'الحدائق', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(637, 'ثعابني احمد', '', '', '659491403', '', 0, 'Skikda', 'الحدائق', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(638, 'دخيل الطاهر', '', '', '557099742/0671076789', '', 0, 'Skikda', 'الحدائق', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(639, 'علي بوحديبة', '', '', '666214072', '', 0, 'Skikda', 'الحدائق', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(640, 'لبديوي رابح', '', '', '697901672', '', 0, 'Skikda', 'الحدائق', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(641, 'احمد بهلول', '', '', '664242392', '', 0, 'Skikda', 'الحروش', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(642, 'احمد قانوني', '', '', '792439194', '', 0, 'Skikda', 'الحروش', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(643, 'الاخوة بوعنينبة', '', '', '797517404', '', 0, 'Skikda', 'الحروش', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(644, 'الاخوة كحال', '', '', '792475674', '', 0, 'Skikda', 'الحروش', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(645, 'الاخوين قدماني', '', '', '556491117', '', 0, 'Skikda', 'الحروش', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(646, 'الشهيدين علي وظريف', '', '', '671592869', '', 0, 'Skikda', 'الحروش', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(647, 'امحمد بوحوش', 'الحروش', 'الحروش', '774585898', '', 0, 'Skikda', 'الحروش', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(648, 'بن غرس الله بلقاسم', '', '', '792381729', '', 0, 'Skikda', 'الحروش', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(649, 'بورقعة العربي', '', '', '699779513', '', 0, 'Skikda', 'الحروش', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(650, 'بوبلي الطاهر', '', '', '662797449', '', 0, 'Skikda', 'الحروش', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(651, 'عمار لطرش', '', '', '773812788', '', 0, 'Skikda', 'الحروش', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(652, 'عمارة بوتمورة', '', '', '771350530', '', 0, 'Skikda', 'الحروش', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(653, 'مصطفى بوربطة', '', '', '775253811', '', 0, 'Skikda', 'الحروش', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(654, 'موسى بوالشعور', '', '', '0697731901/0563235504', '', 0, 'Skikda', 'الحروش', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(655, 'شوية شوشان', '', '', '666751369', '', 0, 'Skikda', 'السبت', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(656, 'غربي حسين', '', '', '663662733', '', 306, 'Skikda', 'السبت', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(657, 'قلعة صالح', '', '', '676342037', '', 312, 'Skikda', 'السبت', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(658, 'مدودة مسعود', '', '', '699566938', 'قواسمية زهية', 461, 'Skikda', 'السبت', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(659, 'احسن بلحوس', '', '', '671638816', 'بن جامع عبد الوهاب', 246, 'Skikda', 'القل', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(660, 'احمد الطويل', '', '', '676986990', 'بروش نادية', 0, 'Skikda', 'القل', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(661, 'احمد بوصوفة', '', '', '699542949', 'محمد زبيلة', 621, 'Skikda', 'القل', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(662, 'بوخبيبة مسعود', '', '', '665809834', '', 0, 'Skikda', 'القل', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(663, 'علي بن يوسف', '', '', '554221372', '', 184, 'Skikda', 'القل', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(664, 'غميرد حسين', '', '', '699856340', '', 0, 'Skikda', 'القل', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(665, 'عياش رابح', '', '', '0659790811/038905306', '', 0, 'Skikda', 'القل', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(666, 'فروم مختار', '', '', '699923679', 'بورنان نوال', 418, 'Skikda', 'القل', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(667, 'كيحل محمد', '', '', '667647567', '', 0, 'Skikda', 'القل', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(668, 'مسيخ علي', '', '', '699550762', '', 0, 'Skikda', 'القل', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(669, 'مسيخ عمار', '', '', '657955168', 'سمية جمعون', 471, 'Skikda', 'القل', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(670, 'الاخوة مشحود', '', '', '770931781', '', 0, 'Skikda', 'امجاز الدشيش', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(671, 'الاخوين بلموكر', '', '', '658756043', 'رمضان بومغيتي', 0, 'Skikda', 'امجاز الدشيش', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(672, 'بطين شريفة', '', '', '', '', 0, 'Skikda', 'امجاز الدشيش', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(673, 'بوراوي محمد', '', '', '772700251', '', 0, 'Skikda', 'امجاز الدشيش', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(674, 'حمود سليمان', '', '', '671394648', '', 0, 'Skikda', 'امجاز الدشيش', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(675, 'عقبة بن نافع', '', '', '658507304', 'بوبكر وداد', 0, 'Skikda', 'امجاز الدشيش', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(676, 'لعشي عمار', '', '', '667755901', '', 0, 'Skikda', 'امجاز الدشيش', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(677, 'مصيبح رشيد', '', '', '662652258', '', 0, 'Skikda', 'امجاز الدشيش', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(678, 'بداي شعبان', '', '', '666572219', '', 0, 'Skikda', 'بني بشير', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(679, 'بولقلوف مبروك', '', '', '776306258', '', 0, 'Skikda', 'بني بشير', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(680, 'زواغي محمد', '', '', '772008429', '', 0, 'Skikda', 'بني بشير', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(681, 'مخيبي اعمر', '', '', '664854935', 'طيبي عز الدين', 0, 'Skikda', 'بني بشير', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(682, 'قدوش اسماعيل', '', '', '698776174', '', 0, 'Skikda', 'بوشطاطة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(683, 'قرفي احمد', '', '', '775924446', '', 0, 'Skikda', 'بوشطاطة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(684, 'قروي احمادو', '', '', '673613859', '', 0, 'Skikda', 'بوشطاطة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(685, 'مجماج رابح', '', '', '664382984', '', 0, 'Skikda', 'بوشطاطة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(686, 'الركاكب الجديدة', '', '', '655328152', 'العيد بوسنان', 299, 'Skikda', 'بين الوديان', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(687, 'سحنون الساسي', '', '', '698716921', 'يونس ياسمينة', 550, 'Skikda', 'بين الوديان', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(688, 'الاخوة بولعراس', '', '', '659797706', '', 0, 'Skikda', 'تمالوس', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(689, 'الاخوة مجذوب', '', '', '655608113', '', 0, 'Skikda', 'تمالوس', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(690, 'العلوش محمد', '', '', '782637794', '', 0, 'Skikda', 'تمالوس', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(691, 'زعير لونيس01', '', '', '774030808', '', 0, 'Skikda', 'تمالوس', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(692, 'زرميت مالك', '', '', '669799845', '', 0, 'Skikda', 'تمالوس', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(693, 'زيرق صالح', '', '', '38937599/0655860221', '', 0, 'Skikda', 'تمالوس', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(694, 'ساطوح بوربيع', '', '', '666709986', '', 0, 'Skikda', 'تمالوس', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(695, 'طريف محمد', '', '', '696256295', '', 0, 'Skikda', 'تمالوس', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(696, 'لجرو العبيدي', '', '', '667871497', '', 0, 'Skikda', 'تمالوس', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(697, 'احمد زريول', '', '', '662857029', 'مسعد فاطمة الزهراء', 0, 'Skikda', 'حمادي كرومة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(698, 'احمد زعبوب', '', '', '554609536', '', 0, 'Skikda', 'حمادي كرومة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(699, 'رحمة وعبد الرحيم', '', '', '773905373', 'مولود بوطاطة', 479, 'Skikda', 'حمادي كرومة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(700, 'مرجاوي محمد', '', '', '798873412', 'عمار معاليم', 0, 'Skikda', 'حمادي كرومة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(701, 'الشهاب المحولة', '', '', '775051275', '', 290, 'Skikda', 'رمضان جمال', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(702, 'بن موسى صالح', '', '', '667788530', '', 0, 'Skikda', 'رمضان جمال', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(703, 'جربي عزيز', '', '', '558399810', '', 0, 'Skikda', 'رمضان جمال', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(704, 'خلوط عبد الله', '', '', '777853541', '', 314, 'Skikda', 'رمضان جمال', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(705, 'فضيلة سعدان', '', '', '561862150', '', 0, 'Skikda', 'رمضان جمال', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(706, 'لوصيف بوشطاطة', '', '', '662981362', '', 0, 'Skikda', 'رمضان جمال', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(707, 'معطى الله الفاضل', '', '', '797304497', '', 0, 'Skikda', 'رمضان جمال', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14');
INSERT INTO `schools` (`id`, `name`, `address`, `district`, `phone`, `manager_name`, `student_count`, `wilaya`, `commune`, `latitude`, `longitude`, `radius`, `created_at`, `updated_at`) VALUES
(708, 'مولود بوراس', '', '', '781741814', '', 0, 'Skikda', 'رمضان جمال', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(709, 'لخضر بوزيد', '', '', '792475674', '', 0, 'Skikda', 'زردازة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(710, '8ماي1954', '', '', '661725703', '', 0, 'Skikda', 'سكيكدة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(711, 'احسن الكافي', '', '', '697321719', 'بكوش نصيرة', 0, 'Skikda', 'سكيكدة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(712, 'احسن بن ذيب', '', '', '670316535', '', 0, 'Skikda', 'سكيكدة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(713, 'أحسن بوعافية', '', '', '663132725', '', 0, 'Skikda', 'سكيكدة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(714, 'احمد بن فلاسي', '', '', '670369794', '', 0, 'Skikda', 'سكيكدة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(715, 'احمد بوعوينة', '', '', '791846098', '', 0, 'Skikda', 'سكيكدة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(716, 'اسماعيل مرابط', '', '', '38743420', 'رزاق بويديوة', 0, 'Skikda', 'سكيكدة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(717, 'الاخوة يحي', '', '', '795720706', '', 0, 'Skikda', 'سكيكدة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(718, 'الإرشاد', '', '', '667492960', 'بريك السعيد', 381, 'Skikda', 'سكيكدة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(719, 'الامير عبد القادر', '', '', '674345122', 'زرازحي ميلود', 0, 'Skikda', 'سكيكدة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(720, 'الشهداء اودينة', '', '', '778221105', 'عبد النور حسان', 603, 'Skikda', 'سكيكدة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(721, 'الشهداء لزغد', '', '', '699351815', 'شريف', 348, 'Skikda', 'سكيكدة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(722, 'العيدي بومالطة', '', '', '669622186', '', 0, 'Skikda', 'سكيكدة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(723, 'الطاهر شلوفي', '', '', '670068163', '', 416, 'Skikda', 'سكيكدة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(724, 'الفارابي', '', '', '665014885', '', 0, 'Skikda', 'سكيكدة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(725, 'اول نوفمبر', '', '', '655217105', 'ح ,طمين', 0, 'Skikda', 'سكيكدة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(726, 'بعبوش رشيد', '', '', '663616255', 'الطيب بوخميس', 168, 'Skikda', 'سكيكدة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(727, 'بلحاجي عبد المجيد', '', '', '38751888', '', 0, 'Skikda', 'سكيكدة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(728, 'بوجمعة قرمش', '', '', '774390195', 'نور الدين دريدح', 0, 'Skikda', 'سكيكدة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(729, 'بوطوقة ابراهيم', '', '', '655337177', '', 316, 'Skikda', 'سكيكدة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(730, 'حسين لعور', '', '', '697321988', '', 0, 'Skikda', 'سكيكدة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(731, 'خنشول السعيد', '', '', '775963057', '', 0, 'Skikda', 'سكيكدة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(732, 'رابح العيفة', '', '', '671500298', '', 0, 'Skikda', 'سكيكدة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(733, 'زعير لونيس', '', '', '698086596', 'ك بوعيطة', 0, 'Skikda', 'سكيكدة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(734, 'زندوح عيسى', '', '', '666232989', '', 0, 'Skikda', 'سكيكدة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(735, 'سعد قرمش علي', '', '', '671695250', 'ص بوقنة', 0, 'Skikda', 'سكيكدة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(736, 'سليمان تيش تيش ابراهيم', '', '', '38741082', '', 0, 'Skikda', 'سكيكدة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(737, 'صالح بوثلجة', '', '', '670471217', '', 0, 'Skikda', 'سكيكدة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(738, 'عبد الحميد بن باديس', '', '', '657673313', 'مشحود إسماعيل', 0, 'Skikda', 'سكيكدة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(739, 'عزاز ميداني', '', '', '557031831', '', 0, 'Skikda', 'سكيكدة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(740, 'غاوي محمد الصغير', 'ميسون1', 'ميسون1', '770567266', '', 0, 'Skikda', 'سكيكدة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(741, 'فاطمة بن خوخة', '', '', '668458009', '', 0, 'Skikda', 'سكيكدة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(742, 'قويسم عبد الحق', '', '', '782025411', '', 400, 'Skikda', 'سكيكدة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(743, 'لخشين عبد الله', '', '', '671713641', '', 0, 'Skikda', 'سكيكدة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(744, 'لقرل عمار', '', '', '662985197', 'ريمان آسيا', 197, 'Skikda', 'سكيكدة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(745, 'محمد بوقفة', '', '', '699728898', '', 182, 'Skikda', 'سكيكدة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(746, 'محمد شكيل', '', '', '697472321', '', 0, 'Skikda', 'سكيكدة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(747, 'مختار حمبارك', '', '', '661494875', '', 0, 'Skikda', 'سكيكدة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(748, 'مشحود أحسن', '', '', '779519025', '', 0, 'Skikda', 'سكيكدة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(749, 'مفدي زكرياء', '', '', '672824981/782776689', '', 0, 'Skikda', 'سكيكدة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(750, 'جيلاني محمد', '', '', '659654279', '', 0, 'Skikda', 'سويسي', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(751, 'فلوس بشير', '', '', '666673866', '', 0, 'Skikda', 'سويسي', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(752, 'علي بوصاع', '', '', '799056765', '', 0, 'Skikda', 'سيدي مزغيش', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(753, 'قروط اسماعيل', '', '', '796588169', '', 0, 'Skikda', 'سيدي مزغيش', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(754, 'لحمر عمار', '', '', '795864892', '', 0, 'Skikda', 'سيدي مزغيش', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(755, 'محمد عبد العزيز', '', '', '667568115', '', 514, 'Skikda', 'سيدي مزغيش', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(756, 'الضيف بلكحلة', '', '', '671050668', '', 546, 'Skikda', 'صالح بوالشعور', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(757, 'برنيقة حليمة', '', '', '770312010', '', 0, 'Skikda', 'صالح بوالشعور', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(758, 'بوعمامة محمد', '', '', '699274614', '', 0, 'Skikda', 'صالح بوالشعور', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(759, 'رابح جفال', '', '', '799342208', '', 0, 'Skikda', 'صالح بوالشعور', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(760, 'سحقي ابراهيم', '', '', '790716731', '', 0, 'Skikda', 'صالح بوالشعور', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(761, 'سعد حجال', '', '', '778752176', '', 0, 'Skikda', 'صالح بوالشعور', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(762, 'لعطيوي عمار', '', '', '774336869', '', 0, 'Skikda', 'صالح بوالشعور', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(763, 'الترقي', '', '', '666668944', 'زعلاني سومية', 0, 'Skikda', 'عزابة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(764, 'العشي محمود', '', '', '697327908', '', 0, 'Skikda', 'عزابة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(765, 'العوفي احسن', '', '', '665483935', '', 0, 'Skikda', 'عزابة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(766, 'المجمع المدرسي 800مسكن', 'عدل', 'عدل', '773168472', '', 0, 'Skikda', 'عزابة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(767, 'بن احمد تركي', '', '', '675347897', 'عماري كريمة', 0, 'Skikda', 'عزابة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(768, 'بولحية صالح', '', '', '656345651', 'م حليمي', 0, 'Skikda', 'عزابة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(769, 'خراط عمار', '', '', '699826248', '', 0, 'Skikda', 'عزابة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(770, 'شبل حسين', '', '', '664126978', 'ع, زايدي', 0, 'Skikda', 'عزابة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(771, 'صيفي محمد الصالح', '', '', '', '', 0, 'Skikda', 'عزابة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(772, 'عبدلي احسن', '', '', '699655836', '', 0, 'Skikda', 'عزابة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(773, 'علقمي الطاهر1', 'منزل الابطال', 'منزل الابطال', '696348086', 'حاسي عبد العزيز', 310, 'Skikda', 'عزابة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(774, 'لعمامرة فاطمة البنات', '', '', '38956137', '', 0, 'Skikda', 'عزابة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(775, 'خروفة محمد', '', '', '696627245', '', 0, 'Skikda', 'عين بوزيان', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(776, 'مساعد حميد', '', '', '666063980', '', 0, 'Skikda', 'عين بوزيان', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(777, 'عزوزي حميد', '', '', '675380278', '', 0, 'Skikda', 'عين شرشار', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(778, 'غربي موسى', '', '', '698446387', 'حلاسي سماح', 0, 'Skikda', 'عين شرشار', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(779, 'مضيف مبارك', '', '', '698498064', '', 0, 'Skikda', 'عين شرشار', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(780, 'احمد هرموش', '', '', '771588489', 'فضيل بولغمار', 0, 'Skikda', 'فلفلة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(781, 'الاخوة عثماني', '', '', '775108213', '', 0, 'Skikda', 'فلفلة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(782, 'المجمع المدرسي نوع د', '2800 بوزعرورة', '2800 بوزعرورة', '661709115', '', 0, 'Skikda', 'فلفلة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(783, 'اوتيلي مسعود', '', '', '775623182', '', 0, 'Skikda', 'فلفلة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(784, 'بشيري يوسف', '', '', '', '', 0, 'Skikda', 'فلفلة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(785, 'بير قادر عمار', '', '', '673007813/0657313940', '', 0, 'Skikda', 'فلفلة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(786, 'حسين بولوداني', '', '', '655325995', '', 0, 'Skikda', 'فلفلة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(787, 'حي 2800 مسكن عدل', 'بوزعرورة', 'بوزعرورة', '796482721', '', 0, 'Skikda', 'فلفلة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(788, 'طاشي بلقاسم', '', '', '663340229', '', 0, 'Skikda', 'فلفلة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(789, 'عثماني الساسي', '', '', '655237877', '', 0, 'Skikda', 'فلفلة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(790, 'عزيز بوزغاية', '', '', '775218525', '', 0, 'Skikda', 'فلفلة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(791, 'علي مسامر', '', '', '664472489', '', 0, 'Skikda', 'فلفلة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(792, 'عميرة صدوق', '', '', '38797110', '', 0, 'Skikda', 'فلفلة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(793, 'عياش صالح بن الطاهر', '', '', '698505697', '', 0, 'Skikda', 'فلفلة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(794, 'لخشين حسين', 'بوزعرورة', 'بوزعرورة', '559307224', '', 0, 'Skikda', 'فلفلة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(795, 'لخشين صالح', '', '', '', 'بوقرياطة عمار', 0, 'Skikda', 'فلفلة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(796, 'محمد بوزليفة', '', '', '797823088', '', 0, 'Skikda', 'فلفلة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(797, 'محمود سايب', 'بوزعرورة', 'بوزعرورة', '771636061', '', 0, 'Skikda', 'فلفلة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(798, 'موسى بن قطار', '', '', '659064580', '', 0, 'Skikda', 'فلفلة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(799, 'العايب بوجمعة', '', '', '698206966', '', 0, 'Skikda', 'كركرة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(800, 'بوزردونة محمد', '', '', '699370232', 'ريحان عبد الرزاق', 400, 'Skikda', 'كركرة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(801, 'بوقدح الزهراء', '', '', '668651997', '', 0, 'Skikda', 'كركرة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(802, 'بولامة احمد', '', '', '661859049', '', 186, 'Skikda', 'كركرة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(803, 'بولقمة مسعود', '', '', '699677686', '', 0, 'Skikda', 'كركرة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(804, 'جمال لعموشي', '', '', '663076129', 'عكوشي سعيد', 107, 'Skikda', 'كركرة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(805, 'ساحلي حسين', '', '', '671660663', '', 0, 'Skikda', 'كركرة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(806, 'طويل احمد بن حسان', '', '', '669495745', 'مسعود لرقم', 0, 'Skikda', 'كركرة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(807, 'فوفو ابراهيم', 'بني زيد', 'بني زيد', '778411261', '', 0, 'Skikda', 'كركرة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(808, 'قجيو سعد', '', '', '671438095', '', 150, 'Skikda', 'كركرة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(809, 'لقشري عبد الحميد', '', '', '660856349', '', 0, 'Skikda', 'كركرة', NULL, NULL, NULL, '2025-12-21 11:27:14', '2025-12-21 11:27:14'),
(810, 'صالح بوتريبة', '', '', '669292089', '', 0, 'Annaba', 'احمد بوقصاص', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(811, 'معنصري امحمد', '', '', '675296721', '', 0, 'Annaba', 'احمد بوقصاص', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(812, 'الصومام', '', '', '675121198', '', 376, 'Annaba', 'البوني', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(813, 'الطيب يوسيف', '', '', '666895084', 'خالد نادية', 0, 'Annaba', 'البوني', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(814, 'الهادي بن خليل', 'سيدي سالم', 'سيدي سالم', '674176459', '', 0, 'Annaba', 'البوني', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(815, 'الهادي خلفاوي', 'الصرول', 'الصرول', '658263459', '', 325, 'Annaba', 'البوني', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(816, 'بلقاسم بوركبة', '', '', '770630135', '', 0, 'Annaba', 'البوني', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(817, 'بلقاسم بوغرارة', '', '', '666035210', '', 0, 'Annaba', 'البوني', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(818, 'بوخميرة الجديدة', '', '', '699060954', '', 0, 'Annaba', 'البوني', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(819, 'بورفوس محمد السعيد', '', '', '673961061', '', 0, 'Annaba', 'البوني', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(820, 'بوشارب إسماعيل', '', '', '667862931', '', 0, 'Annaba', 'البوني', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(821, 'بوعصيدة زغدود', '', '', '670017425', '', 460, 'Annaba', 'البوني', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(822, 'بوعصيدة علي', '', '', '0670281865/038566470', '', 425, 'Annaba', 'البوني', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(823, 'بوقدة بن خالد', '', '', '666056756', '', 187, 'Annaba', 'البوني', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(824, 'جمعة حسين الجديدة', '', '', '561031991', '', 354, 'Annaba', 'البوني', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(825, 'جمعة حسين القديمة', '', '', '540701624', '', 160, 'Annaba', 'البوني', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(826, 'حسان بن عثمان', '', '', '675568115', '', 125, 'Annaba', 'البوني', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(827, 'حسين خوجة الشابية', '', '', '676155249', '', 0, 'Annaba', 'البوني', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(828, 'حميدة بوزيد', '', '', '696441186', 'حمور مريم', 0, 'Annaba', 'البوني', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(829, 'خالد ابن الوليد', '', '', '554646930', '', 0, 'Annaba', 'البوني', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(830, 'ساسي بن موسى', '', '', '660744569', '', 0, 'Annaba', 'البوني', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(831, 'سيدي سالم مركز', '', '', '655828995', '', 367, 'Annaba', 'البوني', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(832, 'شيحاوي يوسف', '', '', '659221139', '', 568, 'Annaba', 'البوني', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(833, 'طارق بن زياد', '', '', '770742202', '', 0, 'Annaba', 'البوني', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(834, 'علي بن محمد بن سالم', '', '', '676180008', '', 0, 'Annaba', 'البوني', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(835, 'عمار خالد خوجة', '', '', '655178219', '', 0, 'Annaba', 'البوني', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(836, 'عيسات ايدير', '', '', '799465858', '', 0, 'Annaba', 'البوني', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(837, 'فوغالي منصر', '', '', '699684955', '', 78, 'Annaba', 'البوني', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(838, 'محمد كرباري', 'الصرول', 'الصرول', '', '', 0, 'Annaba', 'البوني', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(839, 'ملوكي حسين', 'بوزعرورة', 'بوزعرورة', '660045435', '', 0, 'Annaba', 'البوني', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(840, 'نخيلي محمد القطب الجامعي', '', '', '673691897', 'مضايفية مبروك', 590, 'Annaba', 'البوني', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(841, 'بن سالم ميلود', '', '', '672908226', '', 0, 'Annaba', 'التريعات', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(842, 'لعطاوي الصالح', '', '', '662313085', '', 0, 'Annaba', 'التريعات', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(843, 'لبيض محمود', '', '', '656948097', '', 0, 'Annaba', 'التريعات', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(844, '20اوت55', '', '', '560326279', '', 0, 'Annaba', 'الحجار', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(845, 'البصائر', '', '', '662954110', 'رايجية حسين', 0, 'Annaba', 'الحجار', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(846, 'المقاومة الجديدة', '', '', '659154360', 'فاطمة فريحي', 0, 'Annaba', 'الحجار', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(847, 'النهضة', 'قسطل نفطي', 'قسطل نفطي', '655632822', '', 330, 'Annaba', 'الحجار', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(848, 'حداد عمار المعرفة', '', '', '698185521', '', 618, 'Annaba', 'الحجار', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(849, 'ساكر عمار', '', '', '658662757', '', 0, 'Annaba', 'الحجار', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(850, 'شكيب ارسلان', '', '', '675105054/0669918588', '', 0, 'Annaba', 'الحجار', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(851, 'عينوز عبد العزيز', '', '', '667770979', '', 0, 'Annaba', 'الحجار', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(852, 'مشري حسين', '', '', '770329586', 'موسى برباق', 0, 'Annaba', 'الحجار', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(853, 'الاخوة عثماني', '', '', '669796344', '', 0, 'Annaba', 'برحال', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(854, 'الغجاتي لخضر', '', '', '770237226', '', 0, 'Annaba', 'برحال', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(855, 'بن عطوي احمد', 'الكاليتوسة', 'الكاليتوسة', '663941873', '', 0, 'Annaba', 'برحال', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(856, 'بوعريشة يوسف', '', '', '675217807', '', 347, 'Annaba', 'برحال', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(857, 'بوعشة احمد', '', '', '672082714', '', 456, 'Annaba', 'برحال', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(858, 'بومشطة عمار', '', '', '656425230', '', 0, 'Annaba', 'برحال', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(859, 'جابري عمار', '', '', '557537188', '', 0, 'Annaba', 'برحال', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(860, 'خصيري الشريف بن عمار', '', '', '673515597', '', 0, 'Annaba', 'برحال', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(861, 'زوار صالح', '', '', '667881273', '', 0, 'Annaba', 'برحال', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(862, 'عميرات حسين', '', '', '667931413', '', 0, 'Annaba', 'برحال', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(863, 'ابن حواس ابراهيم', '', '', '772063594', '', 0, 'Annaba', 'سيدي عمار', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(864, 'اول نوفمبر', '', '', '663046656', '', 0, 'Annaba', 'سيدي عمار', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(865, 'بالرزاق العربي الجديدة', '', '', '670294799', '', 0, 'Annaba', 'سيدي عمار', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(866, 'باهي عمار 1', '', '', '674579842/666299001', '', 0, 'Annaba', 'سيدي عمار', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(867, 'باهي عمار 2', '', '', '657684282', '', 0, 'Annaba', 'سيدي عمار', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(868, 'حجوج محمد', '', '', '666976745', '', 397, 'Annaba', 'سيدي عمار', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(869, 'حسيس لخضر', '', '', '655743518', '', 0, 'Annaba', 'سيدي عمار', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(870, 'شطايبي عبد الوهاب', '', '', '655474175', 'معصمي حسيبة', 0, 'Annaba', 'سيدي عمار', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(871, 'صالح بلعيد', '', '', '699067392', '', 0, 'Annaba', 'سيدي عمار', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(872, 'مقدم موسى', '', '', '667979513', '', 0, 'Annaba', 'سيدي عمار', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(873, 'ناجي محمد العربي 500مسكن', '', '', '699247810', '', 0, 'Annaba', 'سيدي عمار', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(874, '05جويلية1962', '', '', '655555658', '', 0, 'Annaba', 'عنابة وسط', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(875, '22261', '', '', '773352737', '', 0, 'Annaba', 'عنابة وسط', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(876, 'ابو بكر الصديق', '', '', '660760581', '', 434, 'Annaba', 'عنابة وسط', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(877, 'احمد زبانة', '', '', '699674522', '', 0, 'Annaba', 'عنابة وسط', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(878, 'الخنساء', '', '', '664171419', '', 0, 'Annaba', 'عنابة وسط', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(879, 'الشهاب (ص3)', '', '', '697171112/0550840408', '', 515, 'Annaba', 'عنابة وسط', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(880, 'العقيد عميروش', '', '', '662130553', 'كمال كرناني', 0, 'Annaba', 'عنابة وسط', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(881, 'القادسية', '', '', '552915576', '', 0, 'Annaba', 'عنابة وسط', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(882, 'النصر', '', '', '38405560', '', 0, 'Annaba', 'عنابة وسط', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(883, 'بزري صالح', '', '', '671110430', '', 0, 'Annaba', 'عنابة وسط', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(884, 'بلحمراوي عبد العزيز', '', '', '666680500', '', 0, 'Annaba', 'عنابة وسط', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(885, 'بلعيد بلقاسم', '', '', '549842390', '', 474, 'Annaba', 'عنابة وسط', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(886, 'بن حمزة صالح', '', '', '699230156', 'صالح حمانة', 0, 'Annaba', 'عنابة وسط', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(887, 'بن وهيبة محمد', '', '', '794590680', '', 0, 'Annaba', 'عنابة وسط', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(888, 'بوجادي عمار', '', '', '668338504', 'محمد انيزير', 0, 'Annaba', 'عنابة وسط', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(889, 'بوزراد حسين المختلطة', '', '', '662820745', '', 0, 'Annaba', 'عنابة وسط', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(890, 'بوزراد حسين بنات', '', '', '670127143', '', 0, 'Annaba', 'عنابة وسط', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(891, 'بوزراد حسين ذكور', '', '', '671555788', '', 0, 'Annaba', 'عنابة وسط', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(892, 'بوعشة الخروف', '', '', '657620625', '', 205, 'Annaba', 'عنابة وسط', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(893, 'حربي عمار', '', '', '549021442', '', 0, 'Annaba', 'عنابة وسط', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(894, 'حسن بن الهيثم', '', '', '699713758', '', 0, 'Annaba', 'عنابة وسط', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(895, 'حمزة بن عبد المطلب', '', '', '770192652', '', 0, 'Annaba', 'عنابة وسط', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(896, 'خديجة ام المؤمنين', '', '', '791689993', '', 148, 'Annaba', 'عنابة وسط', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(897, 'دوايسية عمارة', '', '', '662044645', '', 0, 'Annaba', 'عنابة وسط', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(898, 'ديدوش مراد بنات', '', '', '38432961', '', 0, 'Annaba', 'عنابة وسط', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(899, 'ديدوش مراد ذكور', '', '', '670478343', '', 0, 'Annaba', 'عنابة وسط', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(900, 'رفاس زهوان', '', '', '663425237', '', 148, 'Annaba', 'عنابة وسط', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(901, 'ريزي عمر', '', '', '561289474', 'عايب م زوجة زمشة', 0, 'Annaba', 'عنابة وسط', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(902, 'زيغود يوسف', '', '', '779199877', '', 0, 'Annaba', 'عنابة وسط', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(903, 'سيدي ابراهيم', '', '', '666683591', '', 0, 'Annaba', 'عنابة وسط', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(904, 'عائشة ام المؤمنين', '', '', '699427072', '', 0, 'Annaba', 'عنابة وسط', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(905, 'عباس موسى', '', '', '660349640', '', 294, 'Annaba', 'عنابة وسط', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(906, 'عبان رمضان', '', '', '672570827', '', 0, 'Annaba', 'عنابة وسط', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(907, 'عبد الحميد بن باديس اناث', '', '', '', '', 0, 'Annaba', 'عنابة وسط', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(908, 'عبد الحميد بن باديس ذكور', '', '', '771726224', '', 0, 'Annaba', 'عنابة وسط', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(909, 'عماري موسى', '', '', '661610228', '', 0, 'Annaba', 'عنابة وسط', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(910, 'عمر بن الخطاب', '', '', '699883235', 'كليبات سكينة', 0, 'Annaba', 'عنابة وسط', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(911, 'فريخ عبد الحميد', '', '', '660874408', '', 0, 'Annaba', 'عنابة وسط', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(912, 'فضيل الورتلاني', '', '', '0673635841/0671108720', 'زبوج نور الدين', 0, 'Annaba', 'عنابة وسط', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(913, 'قتيبة بن مسلم', '', '', '561204431', '', 86, 'Annaba', 'عنابة وسط', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(914, 'قاسمي عبد العزيز', '', '', '664462451', '', 0, 'Annaba', 'عنابة وسط', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(915, 'كرماني مسعود', '', '', '658402690', '', 0, 'Annaba', 'عنابة وسط', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(916, 'كليبات الطاهر', 'ع2', 'ع2', '796464515', '', 0, 'Annaba', 'عنابة وسط', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(917, 'لعلالي احمد', '', '', '540589359', '', 0, 'Annaba', 'عنابة وسط', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(918, 'مريم سعدان', '', '', '665523670', '', 250, 'Annaba', 'عنابة وسط', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(919, 'باي عبد الوناس', 'ذراع الريش', 'ذراع الريش', '672500632', '', 0, 'Annaba', 'واد العنب', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(920, 'بن حيرية سعد الله', '', '', '671129950', '', 0, 'Annaba', 'واد العنب', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(921, 'بوحجيلة عمار', 'ذراع الريش', 'ذراع الريش', '657058416', '', 0, 'Annaba', 'واد العنب', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(922, 'بوهالي رابح', '', '', '658120099', '', 0, 'Annaba', 'واد العنب', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(923, 'بونوبة محمد الصالح', '', '', '671047913', '', 0, 'Annaba', 'واد العنب', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(924, 'تريعة مسعود', '', '', '666709401', '', 0, 'Annaba', 'واد العنب', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(925, 'جميلي ابراهيم', '', '', '661621186', '', 0, 'Annaba', 'واد العنب', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(926, 'شطاب عبد الله', 'خرازة', 'خرازة', '797200687', '', 437, 'Annaba', 'واد العنب', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(927, 'عماني بلقاسم', 'ذراع الريش', 'ذراع الريش', '657235457', 'مرابطة رفيقة', 195, 'Annaba', 'واد العنب', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(928, 'عميرات الطاهر', '', '', '660664092', '', 0, 'Annaba', 'واد العنب', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(929, 'فلفلي ابراهيم', '', '', '672662550', '', 0, 'Annaba', 'واد العنب', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(930, 'قوري موسى', 'ذراع الريش', 'ذراع الريش', '667846218', '', 0, 'Annaba', 'واد العنب', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(931, 'ويشاوي حسين', '', '', '674428278', 'سوفي نذيرة', 0, 'Annaba', 'واد العنب', NULL, NULL, NULL, '2025-12-21 11:27:27', '2025-12-21 11:27:27'),
(932, 'بوخاري', '', '', '668180430', '', 0, 'Biskra', 'اوماش', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(933, 'عليمي عبد الله', '', '', '663518887', '', 0, 'Biskra', 'اوماش', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(934, 'اولاد الصيد', '', '', '661765839', '', 0, 'Biskra', 'البرانيس', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(935, 'محمد مرزوق', '', '', '', '', 0, 'Biskra', 'البرانيس', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(936, 'الاخوة شمة', '', '', '552500010', '', 0, 'Biskra', 'برج بن عزوز', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(937, 'بجاوي الطاهر', '', '', '770658064', '', 0, 'Biskra', 'برج بن عزوز', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(938, 'بن خرف الله مسعود', '', '', '558253110', '', 0, 'Biskra', 'برج بن عزوز', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(939, 'عبادو لخضر', '', '', '779251021', '', 0, 'Biskra', 'برج بن عزوز', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(940, '17اكتوبر', '', '', '663398972', '', 0, 'Biskra', 'بسكرة وسط', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(941, 'ابن باديس', '', '', '699092241', '', 0, 'Biskra', 'بسكرة وسط', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(942, 'احمد فارح', '', '', '671955481', '', 0, 'Biskra', 'بسكرة وسط', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(943, 'بركات العرافي', '', '', '663543095', '', 0, 'Biskra', 'بسكرة وسط', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(944, 'بن النوي حركات', 'العالية', 'العالية', '778047682', '', 0, 'Biskra', 'بسكرة وسط', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(945, 'بن مالك بن لحسن', '', '', '770297458/0770365256', '', 0, 'Biskra', 'بسكرة وسط', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(946, 'بن ومان المداني', '', '', '671461443', '', 0, 'Biskra', 'بسكرة وسط', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(947, 'بوستة محمد', '', '', '772334477', '', 0, 'Biskra', 'بسكرة وسط', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(948, 'تمامي لخضر', '', '', '793584393', '', 0, 'Biskra', 'بسكرة وسط', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(949, 'جهرة الحفناوي', '', '', '659777694', '', 0, 'Biskra', 'بسكرة وسط', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(950, 'حبة عبد المجيد', '', '', '663999800', '', 0, 'Biskra', 'بسكرة وسط', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(951, 'حسين قصباية', '', '', '', '', 0, 'Biskra', 'بسكرة وسط', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(952, 'حمودي بولرباح', '', '', '655746338', '', 0, 'Biskra', 'بسكرة وسط', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(953, 'خباش عبد الحميد', '', '', '0779087042/0773782215', '', 0, 'Biskra', 'بسكرة وسط', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(954, 'خراشي احمد', '', '', '666934233', '', 0, 'Biskra', 'بسكرة وسط', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(955, 'خليفة احمد', '', '', '657206287', '', 0, 'Biskra', 'بسكرة وسط', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(956, 'دبابش سيف الدين', '', '', '662334131', '', 0, 'Biskra', 'بسكرة وسط', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(957, 'دبابش عبد الله', '', '', '662717035', '', 0, 'Biskra', 'بسكرة وسط', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(958, 'دبابش لزهاري', '', '', '699633016', '', 0, 'Biskra', 'بسكرة وسط', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(959, 'دراجي عمار', '', '', '671711352', '', 0, 'Biskra', 'بسكرة وسط', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(960, 'رقاز محمد', '', '', '660677935', '', 0, 'Biskra', 'بسكرة وسط', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(961, 'زيادي زيادي', '', '', '667426016', '', 0, 'Biskra', 'بسكرة وسط', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(962, 'سعادة ابراهيم', '', '', '671535180', '', 0, 'Biskra', 'بسكرة وسط', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(963, 'سي العابدي مهنية', '', '', '662434579', '', 0, 'Biskra', 'بسكرة وسط', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(964, 'سيدهم ميلود', '', '', '797520814', '', 0, 'Biskra', 'بسكرة وسط', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(965, 'صاولي الشريف', '', '', '795402205', '', 0, 'Biskra', 'بسكرة وسط', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(966, 'طبش محمد', '', '', '0669306771/0658754366', '', 0, 'Biskra', 'بسكرة وسط', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(967, 'علواني عبد الحميد', '', '', '673790157', '', 0, 'Biskra', 'بسكرة وسط', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(968, 'عيسى واعر', '', '', '', '', 0, 'Biskra', 'بسكرة وسط', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(969, 'قرين بشير', '', '', '774417330', '', 0, 'Biskra', 'بسكرة وسط', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(970, 'لخضر بن كريبع', '', '', '699027131', '', 0, 'Biskra', 'بسكرة وسط', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(971, 'لهلالي زميط', '', '', '659862191', '', 0, 'Biskra', 'بسكرة وسط', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(972, 'مبارك العنابي', '', '', '662708209', '', 0, 'Biskra', 'بسكرة وسط', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(973, 'مرزوق لخضر', '', '', '773219280', '', 0, 'Biskra', 'بسكرة وسط', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(974, 'مزياني العيد', '', '', '655248357', '', 0, 'Biskra', 'بسكرة وسط', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(975, 'مزياني عمر', '', '', '777708243', '', 0, 'Biskra', 'بسكرة وسط', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(976, 'ميرة السعيد', '', '', '675348253', '', 0, 'Biskra', 'بسكرة وسط', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(977, 'هراكي لخضر', '', '', '665993322', '', 0, 'Biskra', 'بسكرة وسط', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(978, 'يكن الهادي', '', '', '663743544', '', 0, 'Biskra', 'بسكرة وسط', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(979, 'مغزي حب الله', '', '', '669160069', '', 0, 'Biskra', 'بوشقرون', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(980, 'برباري الصادق', '', '', '696799562', '', 0, 'Biskra', 'جمورة', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(981, 'برباري العربي', '', '', '', '', 0, 'Biskra', 'جمورة', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(982, 'زرقان علي', '', '', '662709595', '', 0, 'Biskra', 'جمورة', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(983, 'سايحي بلقاسم', '', '', '', '', 0, 'Biskra', 'جمورة', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(984, 'فراس سعيد', '', '', '', '', 0, 'Biskra', 'جمورة', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(985, 'احمد رضا حوحو', '', '', '662385400', '', 0, 'Biskra', 'سيدي عقبة', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(986, 'بن خلف الله موفق', '', '', '666675473', '', 0, 'Biskra', 'سيدي عقبة', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(987, 'تبينة علي', '', '', '660166406', '', 0, 'Biskra', 'سيدي عقبة', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(988, 'خطاب عبد الحفيظ', '', '', '671139489', '', 0, 'Biskra', 'سيدي عقبة', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(989, 'رقيق بشير', '', '', '', '', 0, 'Biskra', 'سيدي عقبة', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(990, 'سعدية دراجي', '', '', '675436357', '', 0, 'Biskra', 'سيدي عقبة', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(991, 'سلطاني عمر', '', '', '698790361', '', 0, 'Biskra', 'سيدي عقبة', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(992, 'مسعودي اسماعيل', '', '', '663521293', '', 0, 'Biskra', 'سيدي عقبة', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(993, 'مسعودي سبع', 'سريانة', 'سريانة', '675737405', '', 0, 'Biskra', 'سيدي عقبة', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(994, 'مسعودي مصطفى', '', '', '660850057', '', 0, 'Biskra', 'سيدي عقبة', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(995, 'طالبي مختار', '', '', '675159142', '', 0, 'Biskra', 'شتمة', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(996, 'عباس نطار', '', '', '778082351', '', 0, 'Biskra', 'شتمة', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(997, 'نحوي محمد', '', '', '674972609', '', 0, 'Biskra', 'شتمة', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(998, 'احمد محبوب', '', '', '698474034', '', 0, 'Biskra', 'طولقة', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(999, 'السايب معمر', '', '', '', '', 0, 'Biskra', 'طولقة', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(1000, 'النهضة', '', '', '559059368', '', 0, 'Biskra', 'طولقة', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(1001, 'بن بلعباس', '', '', '668421330', '', 0, 'Biskra', 'طولقة', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(1002, 'بوشامي محمد', '', '', '699966630', '', 0, 'Biskra', 'طولقة', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(1003, 'حسين علي', '', '', '656696171', '', 0, 'Biskra', 'طولقة', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(1004, 'حشاني الدراجي', '', '', '772519991', '', 0, 'Biskra', 'طولقة', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(1005, 'حملاوي عامر', '', '', '', '', 0, 'Biskra', 'طولقة', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(1006, 'حمود مسعود', '', '', '779004280', '', 0, 'Biskra', 'طولقة', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(1007, 'حميدي عيسى', '', '', '', '', 0, 'Biskra', 'طولقة', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(1008, 'ساعد مخلوف', '', '', '675761607', '', 0, 'Biskra', 'طولقة', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(1009, 'سلمي محمد الصغير', '', '', '668421184', '', 0, 'Biskra', 'طولقة', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(1010, 'شريف مواقي', '', '', '782003327', '', 0, 'Biskra', 'طولقة', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(1011, 'شكري محمد', '', '', '', '', 0, 'Biskra', 'طولقة', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(1012, 'شوراب احمد', '', '', '793645058', '', 0, 'Biskra', 'طولقة', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(1013, 'عطية مداني', '', '', '793399318', '', 0, 'Biskra', 'طولقة', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(1014, 'قانة صميدة', '', '', '774162401', '', 0, 'Biskra', 'طولقة', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(1015, 'قيصران احمد', '', '', '775941468', '', 0, 'Biskra', 'طولقة', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(1016, 'محمد العيد ال خليفة', '', '', '555269441', '', 0, 'Biskra', 'طولقة', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(1017, 'ضحوي محمد', '', '', '779155150', '', 0, 'Biskra', 'فوغالة', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(1018, 'غنانية مسعود', '', '', '676497636', '', 0, 'Biskra', 'فوغالة', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(1019, 'نقنوق عثمان', '', '', '781815279', '', 0, 'Biskra', 'فوغالة', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(1020, 'احمد طالب', '', '', '781398989', '', 0, 'Biskra', 'لغروس', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(1021, 'الوافي لزهاري', '', '', '772474069', '', 0, 'Biskra', 'لغروس', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(1022, 'ضيفلي صالح', '', '', '774866047', '', 0, 'Biskra', 'لغروس', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(1023, 'صالح الهامل', '', '', '', '', 0, 'Biskra', 'لغروس', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(1024, 'بحمة علي', '', '', '665152161', '', 0, 'Biskra', 'لوطاية', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(1025, 'بن صغير بوزيان', '', '', '792889998', '', 0, 'Biskra', 'ليشانة', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(1026, 'شيخ بوزيان', '', '', '559226139', '', 0, 'Biskra', 'ليشانة', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(1027, 'لمعافي عبد الباقي', '', '', '791575494', '', 0, 'Biskra', 'ليشانة', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(1028, 'رحاب العرافي', '', '', '549721546', '', 0, 'Biskra', 'ليوة', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(1029, 'صيد محمد', '', '', '561598444', '', 0, 'Biskra', 'ليوة', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(1030, 'طواهرية سليمان', '', '', '542189264', '', 0, 'Biskra', 'ليوة', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(1031, 'قروج الجموعي', '', '', '671173618', '', 0, 'Biskra', 'ليوة', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(1032, 'حسين عبد الباقي', '', '', '671731045', '', 0, 'Biskra', 'مشونش', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(1033, 'زير بشير', '', '', '663550218', '', 0, 'Biskra', 'مشونش', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(1034, 'سي الحواس', '', '', '660905154', '', 0, 'Biskra', 'مشونش', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(1035, 'صالح عمراوي', '', '', '671534449', '', 0, 'Biskra', 'مشونش', NULL, NULL, NULL, '2025-12-21 11:27:36', '2025-12-21 11:27:36'),
(1036, 'g', 'g', 'g', '0777889623', 'gg', 500, 'Constantine', 'g', NULL, NULL, NULL, '2025-12-22 08:32:11', '2025-12-22 08:32:11'),
(1037, 'test', 'test', 'test', '0777889323', 'test', 500, 'Sétif', 'test', NULL, NULL, NULL, '2025-12-22 10:15:57', '2025-12-22 10:15:57'),
(1038, 'test1', '0001', '0001', '0777889623', 'test2', 1000, 'Biskra', 'test1', NULL, NULL, NULL, '2025-12-22 10:33:44', '2025-12-22 10:33:44'),
(1039, 'djelfa test', '00000', '01', '0777889623', '000000', 500, 'Djelfa', 'الجلفة وسط', 35.55100340, 6.17214880, 0.100, '2025-12-24 08:24:02', '2025-12-24 08:24:02'),
(1040, 'ss', 'ss', 'ss', '0777889623', 'ss', 350, 'Constantine', 'حامة بوزيان', 34.43589120, 5.05282560, 0.100, '2025-12-24 08:43:23', '2025-12-24 08:43:23'),
(1041, 'gh', 'dhg', 'dfhg', '0777889623', 'dhg', 350, 'Constantine', 'dfh', 35.54956450, 6.18453130, 0.100, '2025-12-25 10:59:54', '2025-12-25 10:59:54'),
(1042, '0001', '0', '0', '0777885555', '0', 1000, 'Constantine', 'الخروب', 35.54017280, 6.18332160, 0.100, '2025-12-27 07:47:14', '2025-12-27 07:47:14'),
(1043, '22', '22', '22', '0777889623', '22', 1500, 'Constantine', 'حامة بوزيان', 35.54017280, 6.18332160, 0.100, '2025-12-27 07:55:02', '2025-12-27 07:55:02'),
(1044, 's', 's', 's', '0777889656', 's', 250, 'Biskra', 'جمورة', 35.55100190, 6.17216040, 0.100, '2025-12-27 08:00:29', '2025-12-27 08:00:29'),
(1045, 'dd', 'd', 'd', '0777889656', 'd', 350, 'Constantine', 'سيدي مبروك', 35.54017280, 6.18332160, 0.100, '2025-12-27 08:04:38', '2025-12-27 08:04:38'),
(1046, '98989898', 'd', 'd', '0777885645', 'd', 250, 'Skikda', 'حمادي كرومة', 35.54017280, 6.18332160, 0.100, '2025-12-27 09:40:15', '2025-12-27 09:40:15'),
(1047, '22222', 'd', 'd', '0777888858', 'd', 600, 'M\'Sila', 'بلعايبة', 34.87170560, 5.73767680, 0.100, '2025-12-28 10:55:25', '2025-12-28 10:55:25');

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
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
-- Déchargement des données de la table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('O9I5Nrn95XBIGpWkarzQLgAuwM412lTMGEV0RVHS', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoid1dNWEZadktVdXM4SGFva3V1ZkRHVmk3MmhEZk1yRXI2SndGRTJjZiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjM4OiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYWRtaW4vZGVsaXZlcmllcyI7czo1OiJyb3V0ZSI7czoyMjoiYWRtaW4uZGVsaXZlcmllcy5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==', 1766922810);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','manager','distributor') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `role`, `created_at`, `updated_at`, `address`) VALUES
(1, 'Madaure', 'admin@example.com', NULL, '$2y$12$cZSRAcV85A6CxZe50SS7aujnUklNc1XhaK9v7LmD.9vLwBizq8mYa', 'admin', '2025-12-21 11:01:01', '2025-12-25 10:07:52', NULL),
(2, 'ALI BOULAHBAL', 'ali@gmail.com', '0550825346', '$2y$12$.dcpdsocfmEZV9aWJlaNbeyojhbKFxZO0SUjBukb86P12tmN/9PNW', 'distributor', '2025-12-21 11:04:08', '2025-12-21 11:04:08', NULL),
(3, 'bahi', 'bahi@gmail.com', NULL, '$2y$12$QKDHCW51Gv80Mrr8xVCF6O5Ty.W0dP0/6XnVLzfTbmFgG80ovPSSO', 'distributor', '2025-12-23 07:17:30', '2025-12-23 07:17:30', NULL),
(4, 'ahmed ben ahmed', 'ahmed@gmail.com', NULL, '$2y$12$dqN16xdNxnnycy8crzYzdeqZMk/NfX2mn1G1XELPoEp/wcF41PRUm', 'distributor', '2025-12-25 10:36:37', '2025-12-25 10:36:37', NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Index pour la table `deliveries`
--
ALTER TABLE `deliveries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `deliveries_transaction_id_unique` (`transaction_id`),
  ADD UNIQUE KEY `deliveries_payment_code_unique` (`payment_code`),
  ADD KEY `deliveries_payment_confirmed_by_foreign` (`payment_confirmed_by`),
  ADD KEY `deliveries_delivery_type_status_index` (`delivery_type`,`status`),
  ADD KEY `deliveries_payment_code_payment_code_expires_at_index` (`payment_code`,`payment_code_expires_at`),
  ADD KEY `deliveries_kiosk_id_delivery_date_index` (`kiosk_id`,`delivery_date`),
  ADD KEY `deliveries_delivery_type_index` (`delivery_type`),
  ADD KEY `deliveries_status_index` (`status`),
  ADD KEY `deliveries_wilaya_index` (`wilaya`),
  ADD KEY `deliveries_online_payment_status_index` (`online_payment_status`),
  ADD KEY `deliveries_school_id_foreign` (`school_id`),
  ADD KEY `deliveries_distributor_id_foreign` (`distributor_id`);

--
-- Index pour la table `distributors`
--
ALTER TABLE `distributors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `distributors_user_id_foreign` (`user_id`);

--
-- Index pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Index pour la table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Index pour la table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `kiosks`
--
ALTER TABLE `kiosks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kiosks_user_id_foreign` (`user_id`),
  ADD KEY `kiosks_wilaya_index` (`wilaya`),
  ADD KEY `kiosks_is_active_index` (`is_active`),
  ADD KEY `kiosks_wilaya_is_active_index` (`wilaya`,`is_active`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Index pour la table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Index pour la table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Index pour la table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_confirmed_by_foreign` (`confirmed_by`),
  ADD KEY `payments_school_id_payment_date_index` (`school_id`,`payment_date`),
  ADD KEY `payments_kiosk_id_payment_date_index` (`kiosk_id`,`payment_date`),
  ADD KEY `payments_wilaya_payment_date_index` (`wilaya`,`payment_date`),
  ADD KEY `payments_wilaya_index` (`wilaya`),
  ADD KEY `payments_delivery_id_foreign` (`delivery_id`),
  ADD KEY `payments_distributor_id_foreign` (`distributor_id`);

--
-- Index pour la table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Index pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Index pour la table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Index pour la table `schools`
--
ALTER TABLE `schools`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schools_latitude_longitude_index` (`latitude`,`longitude`),
  ADD KEY `schools_wilaya_index` (`wilaya`);

--
-- Index pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `deliveries`
--
ALTER TABLE `deliveries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT pour la table `distributors`
--
ALTER TABLE `distributors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `kiosks`
--
ALTER TABLE `kiosks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT pour la table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT pour la table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `schools`
--
ALTER TABLE `schools`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1048;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `deliveries`
--
ALTER TABLE `deliveries`
  ADD CONSTRAINT `deliveries_distributor_id_foreign` FOREIGN KEY (`distributor_id`) REFERENCES `distributors` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `deliveries_kiosk_id_foreign` FOREIGN KEY (`kiosk_id`) REFERENCES `kiosks` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `deliveries_payment_confirmed_by_foreign` FOREIGN KEY (`payment_confirmed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `deliveries_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `distributors`
--
ALTER TABLE `distributors`
  ADD CONSTRAINT `distributors_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `kiosks`
--
ALTER TABLE `kiosks`
  ADD CONSTRAINT `kiosks_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_confirmed_by_foreign` FOREIGN KEY (`confirmed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payments_delivery_id_foreign` FOREIGN KEY (`delivery_id`) REFERENCES `deliveries` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payments_distributor_id_foreign` FOREIGN KEY (`distributor_id`) REFERENCES `distributors` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payments_kiosk_id_foreign` FOREIGN KEY (`kiosk_id`) REFERENCES `kiosks` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payments_school_id_foreign` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
