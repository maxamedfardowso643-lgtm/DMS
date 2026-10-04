-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 04, 2026 at 05:32 PM
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
-- Database: `dcatms`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `subject_type` varchar(255) DEFAULT NULL,
  `subject_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` text DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`properties`)),
  `ip_address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `subject_type`, `subject_id`, `description`, `properties`, `ip_address`, `created_at`, `updated_at`) VALUES
(1, 1, 'created', 'App\\Models\\Treatment', 1, 'Treatment recorded for Ayan Yusuf', '[]', '127.0.0.1', '2026-09-13 03:42:54', '2026-09-13 03:42:54'),
(3, 1, 'created', 'App\\Models\\User', 8, 'User mahamed ahemd created', '[]', '127.0.0.1', '2026-09-13 04:08:07', '2026-09-13 04:08:07'),
(4, 1, 'created', 'App\\Models\\Dentist', 5, 'Dentist geele mahmed adan added', '[]', '127.0.0.1', '2026-09-13 07:33:03', '2026-09-13 07:33:03'),
(5, 10, 'created', 'App\\Models\\Appointment', 67, 'Appointment APT-2026-00066 booked', '[]', '127.0.0.1', '2026-09-13 07:38:18', '2026-09-13 07:38:18'),
(6, 7, 'created', 'App\\Models\\Appointment', 68, 'Appointment APT-2026-00067 booked', '[]', '127.0.0.1', '2026-09-13 07:46:49', '2026-09-13 07:46:49'),
(7, 2, 'created', 'App\\Models\\Payment', 28, 'Payment PAY-2026-00036 recorded for invoice INV-2026-00030', '[]', '127.0.0.1', '2026-09-13 08:01:15', '2026-09-13 08:01:15'),
(8, 1, 'created', 'App\\Models\\Payment', 29, 'Payment PAY-2026-00037 recorded for invoice INV-2026-00011', '[]', '127.0.0.1', '2026-09-13 10:50:17', '2026-09-13 10:50:17'),
(9, 1, 'created', 'App\\Models\\User', 11, 'User haawo maxamed created', '[]', '127.0.0.1', '2026-09-13 10:52:29', '2026-09-13 10:52:29'),
(10, 1, 'created', 'App\\Models\\Treatment', 2, 'Treatment recorded for Fardowsa Mohamed', '[]', '127.0.0.1', '2026-09-13 10:57:19', '2026-09-13 10:57:19'),
(11, 1, 'created', 'App\\Models\\Invoice', 36, 'Invoice INV-2026-00036 created', '[]', '127.0.0.1', '2026-09-13 10:59:40', '2026-09-13 10:59:40'),
(12, 1, 'created', 'App\\Models\\Payment', 30, 'Payment PAY-2026-00038 recorded for invoice INV-2026-00024', '[]', '127.0.0.1', '2026-09-13 11:04:06', '2026-09-13 11:04:06'),
(13, 1, 'created', 'App\\Models\\Invoice', 37, 'Invoice INV-2026-00037 created', '[]', '127.0.0.1', '2026-09-13 11:11:35', '2026-09-13 11:11:35'),
(14, 1, 'created', 'App\\Models\\Payment', 31, 'Payment PAY-2026-00039 recorded for invoice INV-2026-00003', '[]', '127.0.0.1', '2026-09-13 11:12:25', '2026-09-13 11:12:25'),
(15, 1, 'updated', 'App\\Models\\Invoice', 37, 'Invoice INV-2026-00037 updated', '[]', '127.0.0.1', '2026-09-13 11:13:52', '2026-09-13 11:13:52'),
(16, 1, 'deleted', 'App\\Models\\Invoice', 5, 'Invoice INV-2026-00005 deleted', '[]', '127.0.0.1', '2026-09-13 11:18:27', '2026-09-13 11:18:27'),
(17, 1, 'deleted', 'App\\Models\\Invoice', 36, 'Invoice INV-2026-00036 deleted', '[]', '127.0.0.1', '2026-09-13 11:18:39', '2026-09-13 11:18:39'),
(18, 1, 'created', 'App\\Models\\Invoice', 38, 'Invoice INV-2026-00038 created', '[]', '127.0.0.1', '2026-09-13 11:19:27', '2026-09-13 11:19:27'),
(19, 1, 'deleted', 'App\\Models\\Invoice', 38, 'Invoice INV-2026-00038 deleted', '[]', '127.0.0.1', '2026-09-13 11:20:28', '2026-09-13 11:20:28'),
(20, 1, 'created', 'App\\Models\\Invoice', 39, 'Invoice INV-2026-00039 created', '[]', '127.0.0.1', '2026-09-13 11:22:34', '2026-09-13 11:22:34'),
(21, 1, 'created', 'App\\Models\\User', 12, 'User fardowso maxamed created', '[]', '127.0.0.1', '2026-09-19 13:35:32', '2026-09-19 13:35:32'),
(22, 1, 'created', 'App\\Models\\User', 13, 'User hhhhhh created', '[]', '127.0.0.1', '2026-09-19 13:36:33', '2026-09-19 13:36:33'),
(23, 13, 'updated', NULL, NULL, 'Clinic settings updated', '[]', '127.0.0.1', '2026-09-19 15:18:17', '2026-09-19 15:18:17'),
(24, 13, 'created', 'App\\Models\\Payment', 32, 'Payment PAY-2026-00040 recorded for invoice INV-2026-00039', '[]', '127.0.0.1', '2026-09-19 15:38:57', '2026-09-19 15:38:57'),
(25, 1, 'created', 'App\\Models\\Appointment', 69, 'Appointment APT-2026-00067 booked', '[]', '127.0.0.1', '2026-09-23 06:21:40', '2026-09-23 06:21:40'),
(26, 1, 'created', 'App\\Models\\Treatment', 3, 'Treatment recorded for Farah Muse', '[]', '127.0.0.1', '2026-09-23 06:23:16', '2026-09-23 06:23:16'),
(27, 1, 'updated', 'App\\Models\\Invoice', 37, 'Invoice INV-2026-00037 updated', '[]', '127.0.0.1', '2026-09-23 06:28:54', '2026-09-23 06:28:54'),
(28, 1, 'updated', 'App\\Models\\InventoryItem', 1, 'Inventory item Dental Gloves (Box) updated', '[]', '127.0.0.1', '2026-09-27 13:18:25', '2026-09-27 13:18:25'),
(29, 1, 'updated', 'App\\Models\\InventoryItem', 1, 'Inventory item Dental Gloves (Box) updated', '[]', '127.0.0.1', '2026-09-27 13:18:26', '2026-09-27 13:18:26'),
(30, 1, 'created', 'App\\Models\\Patient', 43, 'Patient caasho mahamed cali registered', '[]', '127.0.0.1', '2026-09-27 13:25:37', '2026-09-27 13:25:37'),
(31, 1, 'created', 'App\\Models\\Appointment', 70, 'Appointment APT-2026-00068 booked', '[]', '127.0.0.1', '2026-09-27 13:26:48', '2026-09-27 13:26:48'),
(32, 1, 'deleted', 'App\\Models\\Invoice', 37, 'Invoice INV-2026-00037 deleted', '[]', '127.0.0.1', '2026-09-28 15:25:54', '2026-09-28 15:25:54'),
(33, 1, 'updated', 'App\\Models\\User', 6, 'User Dr. Hodan Yusuf Warsame updated', '[]', '127.0.0.1', '2026-09-28 15:29:11', '2026-09-28 15:29:11'),
(34, 1, 'created', 'App\\Models\\Appointment', 71, 'Appointment APT-2026-00069 created from website lead', '[]', '127.0.0.1', '2026-09-28 15:38:19', '2026-09-28 15:38:19'),
(35, 1, 'status_changed', 'App\\Models\\Appointment', 71, 'Appointment APT-2026-00069 status: booked -> completed', '[]', '127.0.0.1', '2026-09-28 15:40:33', '2026-09-28 15:40:33'),
(36, 1, 'updated', 'App\\Models\\User', 6, 'User Dr. Hodan Yusuf Warsame updated', '[]', '127.0.0.1', '2026-09-28 15:41:22', '2026-09-28 15:41:22'),
(37, 1, 'updated', 'App\\Models\\User', 5, 'User Dr. Abdirahman Mohamed Ali updated', '[]', '127.0.0.1', '2026-09-28 15:42:05', '2026-09-28 15:42:05'),
(38, 1, 'updated', 'App\\Models\\User', 5, 'User Dr. Abdirahman Mohamed Ali updated', '[]', '127.0.0.1', '2026-09-28 15:42:07', '2026-09-28 15:42:07'),
(39, 1, 'created', 'App\\Models\\User', 14, 'User Drs Ismahan Moha created', '[]', '127.0.0.1', '2026-09-28 15:45:14', '2026-09-28 15:45:14'),
(40, 1, 'created', 'App\\Models\\Appointment', 72, 'Appointment APT-2026-00070 created from website lead', '[]', '127.0.0.1', '2026-09-28 15:49:33', '2026-09-28 15:49:33'),
(41, 1, 'updated', 'App\\Models\\User', 10, 'User geele mahmed adan updated', '[]', '127.0.0.1', '2026-09-28 15:50:29', '2026-09-28 15:50:29'),
(42, 1, 'updated', 'App\\Models\\User', 10, 'User geele mahmed adan updated', '[]', '127.0.0.1', '2026-09-28 15:50:31', '2026-09-28 15:50:31'),
(43, 1, 'updated', 'App\\Models\\User', 14, 'User Drs Ismahan Moha updated', '[]', '127.0.0.1', '2026-09-28 15:50:51', '2026-09-28 15:50:51'),
(44, 1, 'updated', 'App\\Models\\User', 10, 'User geele mahmed adan updated', '[]', '127.0.0.1', '2026-09-28 15:51:16', '2026-09-28 15:51:16'),
(45, 1, 'updated', 'App\\Models\\User', 13, 'User hhhhhh updated', '[]', '127.0.0.1', '2026-09-28 15:51:39', '2026-09-28 15:51:39'),
(46, 1, 'deactivated', 'App\\Models\\User', 14, 'User Drs Ismahan Moha deactivated', '[]', '127.0.0.1', '2026-09-28 15:55:19', '2026-09-28 15:55:19'),
(47, 1, 'activated', 'App\\Models\\User', 14, 'User Drs Ismahan Moha activated', '[]', '127.0.0.1', '2026-09-28 15:55:31', '2026-09-28 15:55:31'),
(48, 1, 'deactivated', 'App\\Models\\User', 2, 'User Amina Yusuf deactivated', '[]', '127.0.0.1', '2026-09-28 15:55:33', '2026-09-28 15:55:33'),
(49, 1, 'activated', 'App\\Models\\User', 2, 'User Amina Yusuf activated', '[]', '127.0.0.1', '2026-09-28 15:55:33', '2026-09-28 15:55:33'),
(50, 1, 'deactivated', 'App\\Models\\User', 13, 'User hhhhhh deactivated', '[]', '127.0.0.1', '2026-09-28 15:55:37', '2026-09-28 15:55:37'),
(51, 1, 'activated', 'App\\Models\\User', 13, 'User hhhhhh activated', '[]', '127.0.0.1', '2026-09-28 15:55:41', '2026-09-28 15:55:41'),
(52, 1, 'updated', 'App\\Models\\User', 10, 'User geele mahmed adan updated', '[]', '127.0.0.1', '2026-09-28 15:57:49', '2026-09-28 15:57:49'),
(53, 1, 'created', 'App\\Models\\Patient', 46, 'Patient Sumayo Ali Ciise registered', '[]', '127.0.0.1', '2026-09-30 14:05:26', '2026-09-30 14:05:26'),
(54, 1, 'created', 'App\\Models\\Dentist', 6, 'Dentist FUAAD SAID added', '[]', '127.0.0.1', '2026-09-30 14:16:34', '2026-09-30 14:16:34'),
(55, 1, 'created', 'App\\Models\\Appointment', 73, 'Appointment APT-2026-00071 booked', '[]', '127.0.0.1', '2026-09-30 14:30:07', '2026-09-30 14:30:07'),
(56, 1, 'status_changed', 'App\\Models\\Appointment', 73, 'Appointment APT-2026-00071 status: booked -> completed', '[]', '127.0.0.1', '2026-09-30 14:31:09', '2026-09-30 14:31:09'),
(57, 1, 'created', 'App\\Models\\Treatment', 4, 'Treatment recorded for Sumayo Ali Ciise', '[]', '127.0.0.1', '2026-09-30 14:31:44', '2026-09-30 14:31:44'),
(60, 1, 'deleted', 'App\\Models\\Treatment', 4, 'Treatment record deleted', '[]', '127.0.0.1', '2026-09-30 14:50:20', '2026-09-30 14:50:20'),
(61, 1, 'created', 'App\\Models\\Treatment', 6, 'Treatment recorded for Sumayo Ali Ciise', '[]', '127.0.0.1', '2026-09-30 14:52:11', '2026-09-30 14:52:11'),
(62, 1, 'created', 'App\\Models\\Invoice', 41, 'Invoice INV-2026-00040 generated from treatment', '[]', '127.0.0.1', '2026-09-30 14:52:11', '2026-09-30 14:52:11'),
(63, 1, 'created', 'App\\Models\\Payment', 33, 'Payment PAY-2026-00041 recorded for invoice INV-2026-00040', '[]', '127.0.0.1', '2026-09-30 15:01:11', '2026-09-30 15:01:11'),
(64, 1, 'deactivated', 'App\\Models\\User', 4, 'User Demo Patient deactivated', '[]', '127.0.0.1', '2026-10-02 13:12:36', '2026-10-02 13:12:36'),
(65, 1, 'activated', 'App\\Models\\User', 4, 'User Demo Patient activated', '[]', '127.0.0.1', '2026-10-02 13:13:08', '2026-10-02 13:13:08'),
(66, 1, 'deactivated', 'App\\Models\\User', 10, 'User Dr. Geele Mahmed Adan deactivated', '[]', '127.0.0.1', '2026-10-02 13:13:16', '2026-10-02 13:13:16'),
(67, 1, 'activated', 'App\\Models\\User', 10, 'User Dr. Geele Mahmed Adan activated', '[]', '127.0.0.1', '2026-10-02 13:13:23', '2026-10-02 13:13:23');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `appointment_no` varchar(255) NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `dentist_id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `appointment_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `source` enum('scheduled','walk_in') NOT NULL DEFAULT 'scheduled',
  `status` enum('booked','confirmed','checked_in','in_progress','completed','cancelled','no_show') NOT NULL DEFAULT 'booked',
  `notes` text DEFAULT NULL,
  `cancellation_reason` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `appointment_no`, `patient_id`, `dentist_id`, `service_id`, `appointment_date`, `start_time`, `end_time`, `source`, `status`, `notes`, `cancellation_reason`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'APT-2026-00001', 28, 3, 2, '2026-09-08', '11:30:00', '12:00:00', 'walk_in', 'completed', NULL, NULL, NULL, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(2, 'APT-2026-00002', 8, 1, 9, '2026-08-15', '16:15:00', '18:15:00', 'walk_in', 'completed', NULL, NULL, NULL, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(3, 'APT-2026-00003', 1, 2, 3, '2026-08-19', '14:45:00', '15:30:00', 'walk_in', 'completed', 'Modi ipsum molestias omnis est aliquam eius quis.', NULL, NULL, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(4, 'APT-2026-00004', 6, 1, 4, '2026-09-14', '11:00:00', '11:30:00', 'walk_in', 'confirmed', NULL, NULL, NULL, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(5, 'APT-2026-00005', 37, 1, 3, '2026-09-17', '15:00:00', '15:45:00', 'walk_in', 'booked', NULL, NULL, NULL, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(6, 'APT-2026-00006', 20, 3, 1, '2026-08-10', '09:15:00', '09:35:00', 'walk_in', 'completed', NULL, NULL, NULL, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(7, 'APT-2026-00007', 2, 2, 8, '2026-08-18', '12:15:00', '12:45:00', 'scheduled', 'completed', NULL, NULL, NULL, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(8, 'APT-2026-00008', 15, 1, 9, '2026-09-06', '15:30:00', '17:30:00', 'walk_in', 'completed', 'Et enim a voluptas et.', NULL, NULL, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(9, 'APT-2026-00009', 18, 3, 5, '2026-09-11', '14:30:00', '16:00:00', 'scheduled', 'completed', NULL, NULL, NULL, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(10, 'APT-2026-00010', 34, 3, 9, '2026-09-17', '11:15:00', '13:15:00', 'scheduled', 'booked', 'Enim molestias rerum laudantium reiciendis.', NULL, NULL, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(11, 'APT-2026-00011', 7, 1, 9, '2026-08-31', '14:15:00', '16:15:00', 'scheduled', 'completed', NULL, NULL, NULL, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(12, 'APT-2026-00012', 12, 2, 9, '2026-09-11', '13:00:00', '15:00:00', 'walk_in', 'cancelled', 'Quis fugiat quisquam sapiente non in.', NULL, NULL, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(13, 'APT-2026-00013', 3, 3, 7, '2026-08-09', '12:30:00', '13:15:00', 'scheduled', 'completed', NULL, NULL, NULL, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(14, 'APT-2026-00014', 32, 2, 9, '2026-08-06', '15:30:00', '17:30:00', 'walk_in', 'completed', NULL, NULL, NULL, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(15, 'APT-2026-00015', 28, 2, 1, '2026-08-20', '15:45:00', '16:05:00', 'walk_in', 'completed', NULL, NULL, NULL, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(16, 'APT-2026-00016', 20, 3, 5, '2026-08-02', '10:30:00', '12:00:00', 'scheduled', 'completed', 'Voluptatum repellat aperiam ad.', NULL, NULL, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(17, 'APT-2026-00017', 16, 2, 8, '2026-08-17', '12:00:00', '12:30:00', 'scheduled', 'completed', NULL, NULL, NULL, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(18, 'APT-2026-00018', 30, 2, 10, '2026-08-19', '16:15:00', '16:30:00', 'scheduled', 'completed', NULL, NULL, NULL, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(19, 'APT-2026-00019', 2, 1, 9, '2026-09-01', '15:45:00', '17:45:00', 'scheduled', 'no_show', 'Blanditiis unde pariatur omnis eos fugit.', NULL, NULL, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(20, 'APT-2026-00020', 8, 2, 6, '2026-09-17', '12:15:00', '13:15:00', 'scheduled', 'booked', 'Corporis nihil et omnis explicabo.', NULL, NULL, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(21, 'APT-2026-00021', 36, 1, 2, '2026-09-15', '10:45:00', '11:15:00', 'walk_in', 'confirmed', 'Deleniti et eaque ex culpa exercitationem.', NULL, NULL, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(22, 'APT-2026-00022', 35, 1, 6, '2026-09-20', '10:45:00', '11:45:00', 'walk_in', 'confirmed', NULL, NULL, NULL, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(23, 'APT-2026-00023', 16, 3, 10, '2026-08-30', '10:45:00', '11:00:00', 'walk_in', 'cancelled', 'Esse dolor sed aut totam ut beatae.', NULL, NULL, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(24, 'APT-2026-00024', 9, 3, 2, '2026-08-11', '13:15:00', '13:45:00', 'scheduled', 'completed', NULL, NULL, NULL, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(25, 'APT-2026-00025', 24, 2, 8, '2026-09-22', '16:30:00', '17:00:00', 'walk_in', 'confirmed', NULL, NULL, NULL, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(26, 'APT-2026-00026', 31, 3, 7, '2026-08-23', '16:00:00', '16:45:00', 'scheduled', 'completed', NULL, NULL, NULL, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(27, 'APT-2026-00027', 32, 1, 9, '2026-09-06', '14:30:00', '16:30:00', 'scheduled', 'completed', 'Magni voluptatem quia qui dolorem aut totam.', NULL, NULL, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(28, 'APT-2026-00028', 1, 2, 5, '2026-08-20', '09:00:00', '10:30:00', 'scheduled', 'completed', NULL, NULL, NULL, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(29, 'APT-2026-00029', 13, 3, 8, '2026-09-05', '09:45:00', '10:15:00', 'scheduled', 'completed', NULL, NULL, NULL, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(30, 'APT-2026-00030', 14, 2, 8, '2026-09-05', '13:15:00', '13:45:00', 'scheduled', 'completed', NULL, NULL, NULL, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(31, 'APT-2026-00031', 32, 2, 5, '2026-08-11', '16:30:00', '18:00:00', 'scheduled', 'cancelled', NULL, NULL, NULL, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(32, 'APT-2026-00032', 26, 3, 2, '2026-08-24', '16:15:00', '16:45:00', 'scheduled', 'completed', NULL, NULL, NULL, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(33, 'APT-2026-00033', 40, 1, 1, '2026-08-11', '15:00:00', '15:20:00', 'walk_in', 'completed', 'Vero ex totam aspernatur exercitationem non.', NULL, NULL, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(34, 'APT-2026-00034', 20, 3, 6, '2026-09-18', '15:15:00', '16:15:00', 'scheduled', 'confirmed', NULL, NULL, NULL, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(35, 'APT-2026-00035', 21, 2, 1, '2026-09-22', '14:45:00', '15:05:00', 'walk_in', 'confirmed', NULL, NULL, NULL, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(36, 'APT-2026-00036', 34, 2, 4, '2026-08-18', '15:15:00', '15:45:00', 'walk_in', 'completed', NULL, NULL, NULL, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(37, 'APT-2026-00037', 9, 1, 10, '2026-08-07', '09:45:00', '10:00:00', 'scheduled', 'cancelled', NULL, NULL, NULL, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(38, 'APT-2026-00038', 37, 2, 6, '2026-09-01', '15:45:00', '16:45:00', 'scheduled', 'completed', NULL, NULL, NULL, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(39, 'APT-2026-00039', 5, 1, 5, '2026-09-07', '13:45:00', '15:15:00', 'scheduled', 'completed', NULL, NULL, NULL, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(40, 'APT-2026-00040', 17, 2, 1, '2026-08-07', '14:15:00', '14:35:00', 'scheduled', 'completed', NULL, NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(41, 'APT-2026-00041', 27, 2, 7, '2026-08-24', '16:45:00', '17:30:00', 'scheduled', 'completed', NULL, NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(42, 'APT-2026-00042', 15, 2, 7, '2026-09-13', '10:30:00', '11:15:00', 'scheduled', 'confirmed', 'Deleniti eum doloremque repellendus neque.', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(43, 'APT-2026-00043', 10, 1, 7, '2026-08-02', '12:45:00', '13:30:00', 'scheduled', 'completed', NULL, NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(44, 'APT-2026-00044', 17, 3, 5, '2026-09-20', '15:30:00', '17:00:00', 'walk_in', 'confirmed', NULL, NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(45, 'APT-2026-00045', 11, 2, 6, '2026-08-19', '12:15:00', '13:15:00', 'walk_in', 'completed', 'Et occaecati dolor sunt qui.', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(46, 'APT-2026-00046', 28, 2, 1, '2026-08-05', '15:00:00', '15:20:00', 'scheduled', 'completed', 'Rerum omnis soluta possimus.', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(47, 'APT-2026-00047', 38, 3, 3, '2026-08-31', '12:30:00', '13:15:00', 'scheduled', 'completed', 'Est molestias et dolorem culpa voluptatem ut.', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(48, 'APT-2026-00048', 14, 3, 2, '2026-08-31', '13:30:00', '14:00:00', 'scheduled', 'no_show', 'Hic sed quidem accusamus ut adipisci atque.', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(49, 'APT-2026-00049', 17, 1, 3, '2026-08-21', '14:30:00', '15:15:00', 'walk_in', 'completed', 'Itaque minima perspiciatis similique cum est assumenda reiciendis.', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(50, 'APT-2026-00050', 14, 2, 4, '2026-09-22', '13:00:00', '13:30:00', 'scheduled', 'booked', NULL, NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(51, 'APT-2026-00051', 38, 2, 4, '2026-08-05', '14:45:00', '15:15:00', 'scheduled', 'completed', 'Neque magni ut voluptas repellat.', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(52, 'APT-2026-00052', 8, 1, 4, '2026-08-25', '09:00:00', '09:30:00', 'scheduled', 'completed', 'Consequatur et voluptas quo aliquid ab aut.', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(53, 'APT-2026-00053', 12, 2, 2, '2026-09-03', '14:00:00', '14:30:00', 'scheduled', 'completed', 'Ea in rerum modi explicabo quam molestiae placeat.', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(54, 'APT-2026-00054', 5, 3, 5, '2026-08-20', '14:30:00', '16:00:00', 'scheduled', 'no_show', 'Est repudiandae est sed dolores.', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(55, 'APT-2026-00055', 9, 2, 6, '2026-08-22', '16:45:00', '17:45:00', 'scheduled', 'completed', NULL, NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(56, 'APT-2026-00056', 9, 1, 5, '2026-08-13', '16:15:00', '17:45:00', 'walk_in', 'no_show', 'Eius reprehenderit sed qui unde.', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(57, 'APT-2026-00057', 5, 3, 8, '2026-08-18', '11:30:00', '12:00:00', 'walk_in', 'completed', NULL, NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(58, 'APT-2026-00058', 8, 2, 5, '2026-08-14', '12:15:00', '13:45:00', 'scheduled', 'no_show', NULL, NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(59, 'APT-2026-00059', 17, 3, 1, '2026-08-01', '16:15:00', '16:35:00', 'walk_in', 'cancelled', NULL, NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(60, 'APT-2026-00060', 11, 3, 1, '2026-09-15', '10:30:00', '10:50:00', 'walk_in', 'booked', 'Ipsam iure rerum officiis expedita tenetur reprehenderit odit consequatur.', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(61, 'APT-2026-00061', 26, 1, 5, '2026-09-12', '12:30:00', '14:00:00', 'walk_in', 'completed', NULL, NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(62, 'APT-2026-00062', 18, 1, 10, '2026-08-18', '09:00:00', '09:15:00', 'walk_in', 'completed', NULL, NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(63, 'APT-2026-00063', 36, 3, 5, '2026-08-02', '15:00:00', '16:30:00', 'scheduled', 'completed', 'Iste voluptatum et expedita dolore vitae corrupti.', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(64, 'APT-2026-00064', 33, 2, 3, '2026-09-16', '09:00:00', '09:45:00', 'scheduled', 'booked', NULL, NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(65, 'APT-2026-00065', 30, 3, 2, '2026-08-17', '13:30:00', '14:00:00', 'scheduled', 'completed', NULL, NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(67, 'APT-2026-00066', 14, 3, 2, '2026-09-13', '09:00:00', '09:30:00', 'walk_in', 'booked', 'ilko dhaqid', NULL, 10, '2026-09-13 07:38:18', '2026-09-13 07:38:18', NULL),
(69, 'APT-2026-00067', 8, 2, 1, '2026-09-23', '14:00:00', '14:20:00', 'walk_in', 'booked', 'CLEANIN', NULL, 1, '2026-09-23 06:21:40', '2026-09-23 06:21:40', NULL),
(70, 'APT-2026-00068', 43, 3, 2, '2026-09-29', '09:00:00', '09:30:00', 'walk_in', 'booked', NULL, NULL, 1, '2026-09-27 13:26:48', '2026-09-27 13:26:48', NULL),
(71, 'APT-2026-00069', 44, 2, 3, '2026-10-06', '14:00:00', '14:45:00', 'scheduled', 'completed', 'ilko xiris', NULL, 1, '2026-09-28 15:38:18', '2026-09-28 15:40:33', NULL),
(72, 'APT-2026-00070', 45, 3, 8, '2026-09-29', '10:30:00', '11:00:00', 'scheduled', 'booked', 'ok', NULL, 1, '2026-09-28 15:49:33', '2026-09-28 15:49:33', NULL),
(73, 'APT-2026-00071', 46, 6, 2, '2026-09-30', '18:00:00', '18:30:00', 'walk_in', 'completed', 'ilko dhaqid', NULL, 1, '2026-09-30 14:30:07', '2026-09-30 14:31:09', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `appointment_requests`
--

CREATE TABLE `appointment_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `service_id` bigint(20) UNSIGNED DEFAULT NULL,
  `preferred_date` date DEFAULT NULL,
  `preferred_time` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('pending','contacted','converted','dismissed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `appointment_requests`
--

INSERT INTO `appointment_requests` (`id`, `name`, `phone`, `email`, `service_id`, `preferred_date`, `preferred_time`, `notes`, `status`, `created_at`, `updated_at`) VALUES
(1, 'fardowso maxamed', '0907896658', 'maxamedfardowso643@gmail.com', 1, '2026-09-13', '05:43', 'hagajinta ilkaha', 'pending', '2026-09-13 10:43:32', '2026-09-13 10:43:32'),
(2, 'fartuuun maxamed', '906787878', 'fartuun@gmail.com', 8, '2026-09-23', '12:17', NULL, 'pending', '2026-09-23 06:18:52', '2026-09-23 06:18:52'),
(3, 'ahmed abdi jamac', '906787878', 'ahmed21@gmail.com', 3, '2026-10-06', NULL, 'ilko xiris', 'contacted', '2026-09-28 15:36:40', '2026-09-30 15:08:37'),
(4, 'Hussein Abdiwali', '907086993', 'hussein@gmail.com', 3, '2026-09-30', NULL, 'ok', 'pending', '2026-09-28 15:47:56', '2026-09-30 15:07:54'),
(5, 'farxiyo mahmed', '906787878', 'maxamedfardowso643@gmail.com', 5, '2026-10-06', '13:46', 'ilko beeris', 'pending', '2026-10-02 17:45:19', '2026-10-02 17:45:19');

-- --------------------------------------------------------

--
-- Table structure for table `appointment_status_logs`
--

CREATE TABLE `appointment_status_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `appointment_id` bigint(20) UNSIGNED NOT NULL,
  `from_status` varchar(255) DEFAULT NULL,
  `to_status` varchar(255) NOT NULL,
  `changed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `appointment_status_logs`
--

INSERT INTO `appointment_status_logs` (`id`, `appointment_id`, `from_status`, `to_status`, `changed_by`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(2, 1, 'booked', 'completed', NULL, 'Status updated', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(3, 2, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(4, 2, 'booked', 'completed', NULL, 'Status updated', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(5, 3, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(6, 3, 'booked', 'completed', NULL, 'Status updated', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(7, 4, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(8, 4, 'booked', 'confirmed', NULL, 'Status updated', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(9, 5, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(10, 6, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(11, 6, 'booked', 'completed', NULL, 'Status updated', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(12, 7, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(13, 7, 'booked', 'completed', NULL, 'Status updated', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(14, 8, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(15, 8, 'booked', 'completed', NULL, 'Status updated', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(16, 9, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(17, 9, 'booked', 'completed', NULL, 'Status updated', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(18, 10, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(19, 11, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(20, 11, 'booked', 'completed', NULL, 'Status updated', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(21, 12, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(22, 12, 'booked', 'cancelled', NULL, 'Status updated', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(23, 13, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(24, 13, 'booked', 'completed', NULL, 'Status updated', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(25, 14, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(26, 14, 'booked', 'completed', NULL, 'Status updated', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(27, 15, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(28, 15, 'booked', 'completed', NULL, 'Status updated', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(29, 16, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(30, 16, 'booked', 'completed', NULL, 'Status updated', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(31, 17, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(32, 17, 'booked', 'completed', NULL, 'Status updated', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(33, 18, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(34, 18, 'booked', 'completed', NULL, 'Status updated', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(35, 19, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(36, 19, 'booked', 'no_show', NULL, 'Status updated', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(37, 20, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(38, 21, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(39, 21, 'booked', 'confirmed', NULL, 'Status updated', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(40, 22, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(41, 22, 'booked', 'confirmed', NULL, 'Status updated', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(42, 23, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(43, 23, 'booked', 'cancelled', NULL, 'Status updated', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(44, 24, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(45, 24, 'booked', 'completed', NULL, 'Status updated', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(46, 25, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(47, 25, 'booked', 'confirmed', NULL, 'Status updated', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(48, 26, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(49, 26, 'booked', 'completed', NULL, 'Status updated', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(50, 27, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(51, 27, 'booked', 'completed', NULL, 'Status updated', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(52, 28, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(53, 28, 'booked', 'completed', NULL, 'Status updated', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(54, 29, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(55, 29, 'booked', 'completed', NULL, 'Status updated', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(56, 30, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(57, 30, 'booked', 'completed', NULL, 'Status updated', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(58, 31, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(59, 31, 'booked', 'cancelled', NULL, 'Status updated', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(60, 32, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(61, 32, 'booked', 'completed', NULL, 'Status updated', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(62, 33, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(63, 33, 'booked', 'completed', NULL, 'Status updated', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(64, 34, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(65, 34, 'booked', 'confirmed', NULL, 'Status updated', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(66, 35, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(67, 35, 'booked', 'confirmed', NULL, 'Status updated', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(68, 36, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(69, 36, 'booked', 'completed', NULL, 'Status updated', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(70, 37, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(71, 37, 'booked', 'cancelled', NULL, 'Status updated', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(72, 38, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(73, 38, 'booked', 'completed', NULL, 'Status updated', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(74, 39, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(75, 39, 'booked', 'completed', NULL, 'Status updated', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(76, 40, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(77, 40, 'booked', 'completed', NULL, 'Status updated', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(78, 41, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(79, 41, 'booked', 'completed', NULL, 'Status updated', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(80, 42, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(81, 42, 'booked', 'confirmed', NULL, 'Status updated', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(82, 43, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(83, 43, 'booked', 'completed', NULL, 'Status updated', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(84, 44, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(85, 44, 'booked', 'confirmed', NULL, 'Status updated', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(86, 45, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(87, 45, 'booked', 'completed', NULL, 'Status updated', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(88, 46, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(89, 46, 'booked', 'completed', NULL, 'Status updated', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(90, 47, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(91, 47, 'booked', 'completed', NULL, 'Status updated', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(92, 48, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(93, 48, 'booked', 'no_show', NULL, 'Status updated', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(94, 49, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(95, 49, 'booked', 'completed', NULL, 'Status updated', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(96, 50, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(97, 51, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(98, 51, 'booked', 'completed', NULL, 'Status updated', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(99, 52, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(100, 52, 'booked', 'completed', NULL, 'Status updated', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(101, 53, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(102, 53, 'booked', 'completed', NULL, 'Status updated', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(103, 54, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(104, 54, 'booked', 'no_show', NULL, 'Status updated', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(105, 55, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(106, 55, 'booked', 'completed', NULL, 'Status updated', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(107, 56, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(108, 56, 'booked', 'no_show', NULL, 'Status updated', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(109, 57, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(110, 57, 'booked', 'completed', NULL, 'Status updated', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(111, 58, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(112, 58, 'booked', 'no_show', NULL, 'Status updated', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(113, 59, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(114, 59, 'booked', 'cancelled', NULL, 'Status updated', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(115, 60, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(116, 61, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(117, 61, 'booked', 'completed', NULL, 'Status updated', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(118, 62, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(119, 62, 'booked', 'completed', NULL, 'Status updated', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(120, 63, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(121, 63, 'booked', 'completed', NULL, 'Status updated', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(122, 64, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(123, 65, NULL, 'booked', NULL, 'Appointment created', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(124, 65, 'booked', 'completed', NULL, 'Status updated', '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(126, 67, NULL, 'booked', 10, 'Appointment created', '2026-09-13 07:38:18', '2026-09-13 07:38:18'),
(128, 69, NULL, 'booked', 1, 'Appointment created', '2026-09-23 06:21:40', '2026-09-23 06:21:40'),
(129, 70, NULL, 'booked', 1, 'Appointment created', '2026-09-27 13:26:48', '2026-09-27 13:26:48'),
(130, 71, NULL, 'booked', 1, 'Converted from website lead', '2026-09-28 15:38:18', '2026-09-28 15:38:18'),
(131, 71, 'booked', 'completed', 1, 'Appointment completed', '2026-09-28 15:40:33', '2026-09-28 15:40:33'),
(132, 72, NULL, 'booked', 1, 'Converted from website lead', '2026-09-28 15:49:33', '2026-09-28 15:49:33'),
(133, 73, NULL, 'booked', 1, 'Appointment created', '2026-09-30 14:30:07', '2026-09-30 14:30:07'),
(134, 73, 'booked', 'completed', 1, 'Appointment completed', '2026-09-30 14:31:09', '2026-09-30 14:31:09');

-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--

CREATE TABLE `attachments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `treatment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_type` varchar(255) DEFAULT NULL,
  `file_size` bigint(20) UNSIGNED DEFAULT NULL,
  `uploaded_by` bigint(20) UNSIGNED DEFAULT NULL,
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

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('setting.appointment_prefix', 's:3:\"APT\";', 2105201897),
('setting.clinic_address', 's:18:\"Mogadishu, Somalia\";', 2105201897),
('setting.clinic_email', 's:16:\"info@dcatms.test\";', 2105201897),
('setting.clinic_logo', 'N;', 2106334050),
('setting.clinic_name', 's:6:\"DCATMS\";', 2105201897),
('setting.clinic_phone', 's:15:\"+252 61 0000000\";', 2105201897),
('setting.currency', 's:3:\"USD\";', 2105201897),
('setting.currency_symbol', 's:1:\"$\";', 2105201897),
('setting.invoice_prefix', 's:3:\"INV\";', 2105201897),
('setting.tax_rate', 's:1:\"0\";', 2105201897),
('setting.working_hours', 's:25:\"09:00 - 17:00 (Sun - Thu)\";', 2105201897);

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
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `phone`, `subject`, `message`, `is_read`, `created_at`, `updated_at`) VALUES
(1, 'fardowso maxamed', 'maxamedfardowso643@gmail.com', '905229131', 'somali', 'ilko hagaajin', 0, '2026-09-13 10:44:32', '2026-09-13 10:44:32'),
(2, 'fartuun ahmed', 'fartuun43@gmail.com', '906787878', 'somali', 'shaqo wangsan', 0, '2026-09-23 06:17:00', '2026-09-23 06:17:00'),
(3, 'fartuun maxamed', 'faiso@gmail.com', '906787878', 'somali', 'wanagsan', 0, '2026-09-23 06:19:29', '2026-09-23 06:19:29'),
(4, 'farxiyo mahmed', 'maxamedfardowso643@gmail.com', '906787878', 'somali', 'dad wanagsan', 0, '2026-10-02 17:46:18', '2026-10-02 17:46:18');

-- --------------------------------------------------------

--
-- Table structure for table `dental_chart_entries`
--

CREATE TABLE `dental_chart_entries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `treatment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tooth_number` varchar(255) NOT NULL,
  `condition` enum('healthy','decayed','filled','missing','crowned','root_canal','implant','extracted','impacted') NOT NULL DEFAULT 'healthy',
  `notes` text DEFAULT NULL,
  `recorded_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dental_chart_entries`
--

INSERT INTO `dental_chart_entries` (`id`, `patient_id`, `treatment_id`, `tooth_number`, `condition`, `notes`, `recorded_date`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '15', 'filled', NULL, '2026-09-13', '2026-09-13 03:42:54', '2026-09-13 03:42:54'),
(2, 37, 2, '18', 'decayed', NULL, '2026-09-13', '2026-09-13 10:57:19', '2026-09-13 10:57:19'),
(3, 8, 3, '18', 'decayed', NULL, '2026-09-23', '2026-09-23 06:23:16', '2026-09-23 06:23:16'),
(4, 46, 4, '24', 'decayed', NULL, '2026-09-30', '2026-09-30 14:31:44', '2026-09-30 14:31:44'),
(5, 46, 6, '21', 'decayed', NULL, '2026-09-30', '2026-09-30 14:52:11', '2026-09-30 14:52:11');

-- --------------------------------------------------------

--
-- Table structure for table `dentists`
--

CREATE TABLE `dentists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `dentist_code` varchar(255) NOT NULL,
  `specialization` varchar(255) DEFAULT NULL,
  `license_number` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dentists`
--

INSERT INTO `dentists` (`id`, `user_id`, `dentist_code`, `specialization`, `license_number`, `bio`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 5, 'DEN-0001', 'General Dentistry', 'LIC-16089', 'Dr. Abdirahman Mohamed Ali specializes in General Dentistry, based at the clinic in Garowe, Puntland.', 1, '2026-09-12 15:23:20', '2026-09-12 15:23:20', NULL),
(2, 6, 'DEN-0002', 'Orthodontics', 'LIC-25332', 'Dr. Hodan Yusuf Warsame specializes in Orthodontics, based at the clinic in Garowe, Puntland.', 1, '2026-09-12 15:23:20', '2026-09-12 15:23:20', NULL),
(3, 7, 'DEN-0003', 'Oral Surgery', 'LIC-20245', 'Dr. Khalid Ahmed Farah specializes in Oral Surgery, based at the clinic in Garowe, Puntland.', 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(4, 9, 'DEN-0004', 'General Dentistry', NULL, NULL, 1, '2026-09-13 06:38:39', '2026-09-13 06:38:39', NULL),
(5, 10, 'DEN-0005', 'Dental Hygiene', '23', 'Dr. Geele Mahmed Adan specializes in Dental Hygiene (teeth cleaning and scaling), based at the clinic in Garowe, Puntland.', 1, '2026-09-13 07:33:03', '2026-10-02 04:31:50', NULL),
(6, 15, 'DEN-0006', 'General Dentistry', '30', 'Dr. Fuaad Said specializes in General Dentistry, based at the clinic in Garowe, Puntland.', 1, '2026-09-30 14:16:34', '2026-10-02 04:31:50', NULL);

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
-- Table structure for table `inventory_items`
--

CREATE TABLE `inventory_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `item_code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `unit` varchar(255) DEFAULT NULL,
  `quantity_on_hand` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `reorder_level` int(10) UNSIGNED NOT NULL DEFAULT 10,
  `unit_cost` decimal(12,2) NOT NULL DEFAULT 0.00,
  `supplier` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory_items`
--

INSERT INTO `inventory_items` (`id`, `item_code`, `name`, `unit`, `quantity_on_hand`, `reorder_level`, `unit_cost`, `supplier`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'INV-0001', 'Dental Gloves (Box)', 'box', 50, 10, 5.00, 'MedSupply Co.', 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(2, 'INV-0002', 'Face Masks (Box)', 'box', 40, 10, 4.00, 'MedSupply Co.', 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(3, 'INV-0003', 'Composite Filling Material', 'pcs', 15, 5, 12.00, 'MedSupply Co.', 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(4, 'INV-0004', 'Local Anesthetic Cartridges', 'box', 8, 10, 20.00, 'MedSupply Co.', 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(5, 'INV-0005', 'Dental Bibs', 'pcs', 200, 50, 0.20, 'MedSupply Co.', 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(6, 'INV-0006', 'Sterilization Pouches', 'pcs', 5, 20, 0.50, 'MedSupply Co.', 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(7, 'INV-0007', 'X-Ray Film', 'box', 12, 5, 25.00, 'MedSupply Co.', 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(8, 'INV-0008', 'Suture Kits', 'pcs', 18, 10, 8.00, 'MedSupply Co.', 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_no` varchar(255) NOT NULL,
  `appointment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `issue_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` enum('draft','unpaid','partially_paid','paid','overdue','cancelled') NOT NULL DEFAULT 'draft',
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `invoice_no`, `appointment_id`, `patient_id`, `issue_date`, `due_date`, `subtotal`, `discount_amount`, `tax_amount`, `total_amount`, `paid_amount`, `status`, `notes`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'INV-2026-00001', 1, 28, '2026-09-08', '2026-09-22', 30.00, 3.00, 0.00, 27.00, 27.00, 'paid', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(2, 'INV-2026-00002', 2, 8, '2026-08-15', '2026-08-29', 400.00, 0.00, 0.00, 400.00, 400.00, 'paid', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(3, 'INV-2026-00003', 3, 1, '2026-08-19', '2026-09-02', 40.00, 0.00, 0.00, 40.00, 40.00, 'paid', NULL, NULL, '2026-09-12 15:23:22', '2026-09-13 11:12:25', NULL),
(4, 'INV-2026-00004', 6, 20, '2026-08-10', '2026-08-24', 15.00, 0.00, 0.00, 15.00, 15.00, 'paid', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(5, 'INV-2026-00005', 7, 2, '2026-08-18', '2026-09-01', 25.00, 2.50, 0.00, 22.50, 0.00, 'unpaid', NULL, NULL, '2026-09-12 15:23:22', '2026-09-13 11:18:27', '2026-09-13 11:18:27'),
(6, 'INV-2026-00006', 8, 15, '2026-09-06', '2026-09-20', 400.00, 0.00, 0.00, 400.00, 400.00, 'paid', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(7, 'INV-2026-00007', 9, 18, '2026-09-11', '2026-09-25', 120.00, 0.00, 0.00, 120.00, 120.00, 'paid', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(8, 'INV-2026-00008', 11, 7, '2026-08-31', '2026-09-14', 400.00, 40.00, 0.00, 360.00, 180.00, 'partially_paid', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(9, 'INV-2026-00009', 13, 3, '2026-08-09', '2026-08-23', 80.00, 8.00, 0.00, 72.00, 72.00, 'paid', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(10, 'INV-2026-00010', 14, 32, '2026-08-06', '2026-08-20', 400.00, 0.00, 0.00, 400.00, 400.00, 'paid', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(11, 'INV-2026-00011', 15, 28, '2026-08-20', '2026-09-03', 15.00, 5.00, 0.00, 10.00, 15.00, 'paid', NULL, NULL, '2026-09-12 15:23:22', '2026-09-13 10:50:17', NULL),
(12, 'INV-2026-00012', 16, 20, '2026-08-02', '2026-08-16', 120.00, 0.00, 0.00, 120.00, 60.00, 'partially_paid', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(13, 'INV-2026-00013', 17, 16, '2026-08-17', '2026-08-31', 25.00, 0.00, 0.00, 25.00, 25.00, 'paid', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(14, 'INV-2026-00014', 18, 30, '2026-08-19', '2026-09-02', 20.00, 0.00, 0.00, 20.00, 20.00, 'paid', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(15, 'INV-2026-00015', 24, 9, '2026-08-11', '2026-08-25', 30.00, 0.00, 0.00, 30.00, 15.00, 'partially_paid', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(16, 'INV-2026-00016', 26, 31, '2026-08-23', '2026-09-06', 80.00, 0.00, 0.00, 80.00, 80.00, 'paid', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(17, 'INV-2026-00017', 27, 32, '2026-09-06', '2026-09-20', 400.00, 40.00, 0.00, 360.00, 360.00, 'paid', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(18, 'INV-2026-00018', 28, 1, '2026-08-20', '2026-09-03', 120.00, 12.00, 0.00, 108.00, 108.00, 'paid', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(19, 'INV-2026-00019', 29, 13, '2026-09-05', '2026-09-19', 25.00, 0.00, 0.00, 25.00, 12.50, 'partially_paid', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(20, 'INV-2026-00020', 30, 14, '2026-09-05', '2026-09-19', 25.00, 0.00, 0.00, 25.00, 25.00, 'paid', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(21, 'INV-2026-00021', 32, 26, '2026-08-24', '2026-09-07', 30.00, 0.00, 0.00, 30.00, 0.00, 'unpaid', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(22, 'INV-2026-00022', 33, 40, '2026-08-11', '2026-08-25', 15.00, 0.00, 0.00, 15.00, 0.00, 'unpaid', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(23, 'INV-2026-00023', 36, 34, '2026-08-18', '2026-09-01', 35.00, 0.00, 0.00, 35.00, 35.00, 'paid', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(24, 'INV-2026-00024', 38, 37, '2026-09-01', '2026-09-15', 150.00, 10.00, 0.00, 140.00, 150.00, 'paid', NULL, NULL, '2026-09-12 15:23:22', '2026-09-13 11:04:06', NULL),
(25, 'INV-2026-00025', 39, 5, '2026-09-07', '2026-09-21', 120.00, 12.00, 0.00, 108.00, 108.00, 'paid', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(26, 'INV-2026-00026', 40, 17, '2026-08-07', '2026-08-21', 15.00, 0.00, 0.00, 15.00, 0.00, 'unpaid', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(27, 'INV-2026-00027', 41, 27, '2026-08-24', '2026-09-07', 80.00, 0.00, 0.00, 80.00, 80.00, 'paid', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(28, 'INV-2026-00028', 43, 10, '2026-08-02', '2026-08-16', 80.00, 0.00, 0.00, 80.00, 0.00, 'unpaid', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(29, 'INV-2026-00029', 45, 11, '2026-08-19', '2026-09-02', 150.00, 15.00, 0.00, 135.00, 0.00, 'unpaid', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(30, 'INV-2026-00030', 46, 28, '2026-08-05', '2026-08-19', 15.00, 6.00, 0.00, 9.00, 15.00, 'paid', NULL, NULL, '2026-09-12 15:23:22', '2026-09-13 08:01:15', NULL),
(31, 'INV-2026-00031', 47, 38, '2026-08-31', '2026-09-14', 40.00, 0.00, 0.00, 40.00, 40.00, 'paid', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(32, 'INV-2026-00032', 49, 17, '2026-08-21', '2026-09-04', 40.00, 0.00, 0.00, 40.00, 40.00, 'paid', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(33, 'INV-2026-00033', 51, 38, '2026-08-05', '2026-08-19', 35.00, 0.00, 0.00, 35.00, 35.00, 'paid', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(34, 'INV-2026-00034', 52, 8, '2026-08-25', '2026-09-08', 35.00, 3.50, 0.00, 31.50, 0.00, 'unpaid', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(35, 'INV-2026-00035', 53, 12, '2026-09-03', '2026-09-17', 30.00, 3.00, 0.00, 27.00, 27.00, 'paid', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(36, 'INV-2026-00036', NULL, 37, '2026-09-13', '2026-09-30', 30.00, 2.00, 0.00, 28.00, 0.00, 'unpaid', NULL, 1, '2026-09-13 10:59:40', '2026-09-13 11:18:39', '2026-09-13 11:18:39'),
(37, 'INV-2026-00037', NULL, 1, '2026-09-23', '2026-09-23', 15.00, 3.00, 0.00, 12.00, 0.00, 'unpaid', 'wey bixisey lacagtii', 1, '2026-09-13 11:11:35', '2026-09-28 15:25:54', '2026-09-28 15:25:54'),
(38, 'INV-2026-00038', NULL, 8, '2026-09-13', '2026-09-16', 400.00, 5.00, 0.00, 395.00, 0.00, 'unpaid', NULL, 1, '2026-09-13 11:19:27', '2026-09-13 11:20:28', '2026-09-13 11:20:28'),
(39, 'INV-2026-00039', NULL, 28, '2026-09-13', '2026-09-23', 15.00, 0.00, 0.00, 15.00, 15.00, 'paid', 'ilko fiirin', 1, '2026-09-13 11:22:34', '2026-09-19 15:38:57', NULL),
(41, 'INV-2026-00040', 73, 46, '2026-09-30', '2026-09-30', 30.00, 1.50, 0.00, 28.50, 28.50, 'paid', NULL, 1, '2026-09-30 14:52:11', '2026-09-30 15:01:11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `tooth_number` varchar(255) DEFAULT NULL,
  `quantity` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `unit_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `tax_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `line_total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice_items`
--

INSERT INTO `invoice_items` (`id`, `invoice_id`, `service_id`, `description`, `tooth_number`, `quantity`, `unit_price`, `discount_amount`, `tax_amount`, `line_total`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'Teeth Cleaning (Scaling)', NULL, 1, 30.00, 3.00, 0.00, 27.00, '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(2, 2, 9, 'Dental Implant', NULL, 1, 400.00, 0.00, 0.00, 400.00, '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(3, 3, 3, 'Tooth Filling', NULL, 1, 40.00, 0.00, 0.00, 40.00, '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(4, 4, 1, 'Dental Check-up', NULL, 1, 15.00, 0.00, 0.00, 15.00, '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(5, 5, 8, 'Braces Consultation', NULL, 1, 25.00, 2.50, 0.00, 22.50, '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(6, 6, 9, 'Dental Implant', NULL, 1, 400.00, 0.00, 0.00, 400.00, '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(7, 7, 5, 'Root Canal Treatment', NULL, 1, 120.00, 0.00, 0.00, 120.00, '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(8, 8, 9, 'Dental Implant', NULL, 1, 400.00, 40.00, 0.00, 360.00, '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(9, 9, 7, 'Teeth Whitening', NULL, 1, 80.00, 8.00, 0.00, 72.00, '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(10, 10, 9, 'Dental Implant', NULL, 1, 400.00, 0.00, 0.00, 400.00, '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(11, 11, 1, 'Dental Check-up', NULL, 1, 15.00, 0.00, 0.00, 15.00, '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(12, 12, 5, 'Root Canal Treatment', NULL, 1, 120.00, 0.00, 0.00, 120.00, '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(13, 13, 8, 'Braces Consultation', NULL, 1, 25.00, 0.00, 0.00, 25.00, '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(14, 14, 10, 'X-Ray Imaging', NULL, 1, 20.00, 0.00, 0.00, 20.00, '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(15, 15, 2, 'Teeth Cleaning (Scaling)', NULL, 1, 30.00, 0.00, 0.00, 30.00, '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(16, 16, 7, 'Teeth Whitening', NULL, 1, 80.00, 0.00, 0.00, 80.00, '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(17, 17, 9, 'Dental Implant', NULL, 1, 400.00, 40.00, 0.00, 360.00, '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(18, 18, 5, 'Root Canal Treatment', NULL, 1, 120.00, 12.00, 0.00, 108.00, '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(19, 19, 8, 'Braces Consultation', NULL, 1, 25.00, 0.00, 0.00, 25.00, '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(20, 20, 8, 'Braces Consultation', NULL, 1, 25.00, 0.00, 0.00, 25.00, '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(21, 21, 2, 'Teeth Cleaning (Scaling)', NULL, 1, 30.00, 0.00, 0.00, 30.00, '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(22, 22, 1, 'Dental Check-up', NULL, 1, 15.00, 0.00, 0.00, 15.00, '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(23, 23, 4, 'Tooth Extraction', NULL, 1, 35.00, 0.00, 0.00, 35.00, '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(24, 24, 6, 'Dental Crown', NULL, 1, 150.00, 0.00, 0.00, 150.00, '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(25, 25, 5, 'Root Canal Treatment', NULL, 1, 120.00, 12.00, 0.00, 108.00, '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(26, 26, 1, 'Dental Check-up', NULL, 1, 15.00, 0.00, 0.00, 15.00, '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(27, 27, 7, 'Teeth Whitening', NULL, 1, 80.00, 0.00, 0.00, 80.00, '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(28, 28, 7, 'Teeth Whitening', NULL, 1, 80.00, 0.00, 0.00, 80.00, '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(29, 29, 6, 'Dental Crown', NULL, 1, 150.00, 15.00, 0.00, 135.00, '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(30, 30, 1, 'Dental Check-up', NULL, 1, 15.00, 0.00, 0.00, 15.00, '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(31, 31, 3, 'Tooth Filling', NULL, 1, 40.00, 0.00, 0.00, 40.00, '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(32, 32, 3, 'Tooth Filling', NULL, 1, 40.00, 0.00, 0.00, 40.00, '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(33, 33, 4, 'Tooth Extraction', NULL, 1, 35.00, 0.00, 0.00, 35.00, '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(34, 34, 4, 'Tooth Extraction', NULL, 1, 35.00, 3.50, 0.00, 31.50, '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(35, 35, 2, 'Teeth Cleaning (Scaling)', NULL, 1, 30.00, 3.00, 0.00, 27.00, '2026-09-12 15:23:22', '2026-09-12 15:23:22'),
(36, 36, 1, 'Dental Check-up', NULL, 1, 15.00, 1.00, 0.00, 14.00, '2026-09-13 10:59:40', '2026-09-13 10:59:40'),
(37, 36, 1, 'Dental Check-up', NULL, 1, 15.00, 1.00, 0.00, 14.00, '2026-09-13 10:59:40', '2026-09-13 10:59:40'),
(40, 38, 9, 'Dental Implant', NULL, 1, 400.00, 5.00, 0.00, 395.00, '2026-09-13 11:19:27', '2026-09-13 11:19:27'),
(41, 39, 1, 'Dental Check-up', NULL, 1, 15.00, 0.00, 0.00, 15.00, '2026-09-13 11:22:34', '2026-09-13 11:22:34'),
(42, 37, 1, 'Dental Check-up', NULL, 1, 15.00, 3.00, 0.00, 12.00, '2026-09-23 06:28:54', '2026-09-23 06:28:54'),
(45, 41, 2, 'Teeth Cleaning (Scaling)', NULL, 1, 30.00, 0.00, 0.00, 30.00, '2026-09-30 14:52:11', '2026-09-30 14:52:11');

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
(4, '2026_09_08_174835_create_roles_table', 1),
(5, '2026_09_08_174837_create_role_user_table', 1),
(6, '2026_09_08_174839_add_fields_to_users_table', 1),
(7, '2026_09_08_174841_create_settings_table', 1),
(8, '2026_09_08_174843_create_activity_logs_table', 1),
(9, '2026_09_08_174845_create_patients_table', 1),
(10, '2026_09_08_174847_create_dentists_table', 1),
(11, '2026_09_08_174848_create_schedules_table', 1),
(12, '2026_09_08_174850_create_services_table', 1),
(13, '2026_09_08_174851_create_appointments_table', 1),
(14, '2026_09_08_174852_create_appointment_status_logs_table', 1),
(15, '2026_09_08_174853_create_treatments_table', 1),
(16, '2026_09_08_174855_create_treatment_details_table', 1),
(17, '2026_09_08_174856_create_prescriptions_table', 1),
(18, '2026_09_08_174857_create_dental_chart_entries_table', 1),
(19, '2026_09_08_174858_create_attachments_table', 1),
(20, '2026_09_08_174859_create_payment_methods_table', 1),
(21, '2026_09_08_174903_create_invoices_table', 1),
(22, '2026_09_08_174904_create_invoice_items_table', 1),
(23, '2026_09_08_174905_create_payments_table', 1),
(24, '2026_09_08_174907_create_inventory_items_table', 1),
(25, '2026_09_08_174908_create_stock_movements_table', 1),
(26, '2026_09_08_205238_create_contact_messages_table', 1),
(27, '2026_09_08_205239_create_appointment_requests_table', 1),
(28, '2026_09_13_092826_create_testimonials_table', 2);

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
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `patient_code` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female') DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `emergency_contact_name` varchar(255) DEFAULT NULL,
  `emergency_contact_phone` varchar(255) DEFAULT NULL,
  `medical_history` text DEFAULT NULL,
  `allergies` text DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `user_id`, `patient_code`, `first_name`, `last_name`, `date_of_birth`, `gender`, `phone`, `email`, `address`, `emergency_contact_name`, `emergency_contact_phone`, `medical_history`, `allergies`, `photo`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, NULL, 'PT-2026-00001', 'Ayan', 'Yusuf', '2006-12-06', 'female', '+25269268709', 'ayan-yusuf1@example.com', 'Garowe - Horseed, Puntland', 'Abdiqadir Ismail', '+25268021666', NULL, NULL, NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(2, NULL, 'PT-2026-00002', 'Halima', 'Warsame', '1994-10-11', 'female', '+25261040754', 'halima-warsame2@example.com', 'Garowe - Horseed, Puntland', 'Mustafe Salah', '+25266859653', NULL, NULL, NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(3, NULL, 'PT-2026-00003', 'Faiza', 'Adan', '1999-11-01', 'female', '+25269734712', 'faiza-adan3@example.com', 'Garowe - Bulo Watiin, Puntland', 'Ibrahim Ismail', '+25268677547', NULL, NULL, NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(4, NULL, 'PT-2026-00004', 'Ikran', 'Muse', '1958-03-17', 'female', '+25269223398', 'ikran-muse4@example.com', 'Garowe - Wadada Isgaarsiinta, Puntland', 'Abdirahman Hussein', '+25265678957', NULL, 'Latex', NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(5, NULL, 'PT-2026-00005', 'Zainab', 'Aden', '1957-01-13', 'female', '+25268493854', 'zainab-aden5@example.com', 'Garowe - Iskoshuban Rd, Puntland', 'Abdifatah Elmi', '+25269974802', NULL, NULL, NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(6, NULL, 'PT-2026-00006', 'Khalid', 'Adan', '2013-07-04', 'male', '+25265563939', 'khalid-adan6@example.com', 'Garowe - Tawakal, Puntland', 'Faiza Hassan', '+25290058896', NULL, NULL, NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(7, NULL, 'PT-2026-00007', 'Deeqa', 'Aden', '2021-07-08', 'female', '+25265332333', 'deeqa-aden7@example.com', 'Garowe - Tawakal, Puntland', 'Liban Nur', '+25266517165', NULL, NULL, NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(8, NULL, 'PT-2026-00008', 'Farah', 'Muse', '1960-03-06', 'male', '+25269555160', 'farah-muse8@example.com', 'Garowe - Tawakal, Puntland', 'Amina Warsame', '+25266757610', NULL, NULL, NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(9, NULL, 'PT-2026-00009', 'Zainab', 'Ismail', '2009-06-11', 'female', '+25265226270', 'zainab-ismail9@example.com', 'Garowe - Wadada Isgaarsiinta, Puntland', 'Ali Mohamed', '+25265758558', 'Consectetur at est exercitationem similique sit et voluptatem.', NULL, NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(10, NULL, 'PT-2026-00010', 'Rahma', 'Omar', '1980-06-28', 'female', '+25290502294', 'rahma-omar10@example.com', 'Garowe - Iskoshuban Rd, Puntland', 'Khalid Nur', '+25266595158', NULL, NULL, NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(11, NULL, 'PT-2026-00011', 'Abdiqadir', 'Jama', '2019-09-07', 'male', '+25261389966', 'abdiqadir-jama11@example.com', 'Garowe - Bulo Watiin, Puntland', 'Faiza Nur', '+25265336493', NULL, 'Penicillin', NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(12, NULL, 'PT-2026-00012', 'Deeqa', 'Abdi', '1992-05-15', 'female', '+25265094606', 'deeqa-abdi12@example.com', 'Garowe - Tawakal, Puntland', 'Ibrahim Osman', '+25269936506', NULL, NULL, NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(13, NULL, 'PT-2026-00013', 'Deeqa', 'Osman', '1994-09-10', 'female', '+25269149581', 'deeqa-osman13@example.com', 'Garowe - Horseed, Puntland', 'Liban Salah', '+25290682800', NULL, NULL, NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(14, NULL, 'PT-2026-00014', 'Ali', 'Omar', '1989-01-07', 'male', '+25269004854', 'ali-omar14@example.com', 'Garowe - Wadada Isgaarsiinta, Puntland', 'Fadumo Muse', '+25269168628', NULL, NULL, NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(15, NULL, 'PT-2026-00015', 'Faiza', 'Jama', '2014-07-01', 'female', '+25265222462', 'faiza-jama15@example.com', 'Garowe - Tawakal, Puntland', 'Abdirahman Salah', '+25290349894', NULL, NULL, NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(16, NULL, 'PT-2026-00016', 'Said', 'Ismail', '1975-03-27', 'male', '+25261274470', 'said-ismail16@example.com', 'Garowe - Horseed, Puntland', 'Zainab Hussein', '+25265734135', 'Hic quibusdam ipsum mollitia labore ut ea vero aliquid.', NULL, NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(17, NULL, 'PT-2026-00017', 'Ali', 'Adan', '1956-12-24', 'male', '+25265112831', 'ali-adan17@example.com', 'Garowe - Bulo Watiin, Puntland', 'Yasmin Osman', '+25268203398', 'Natus alias quas necessitatibus quis odit natus unde eius.', 'Latex', NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(18, NULL, 'PT-2026-00018', 'Khalid', 'Yusuf', '1987-08-06', 'male', '+25268195675', 'khalid-yusuf18@example.com', 'Garowe - Iskoshuban Rd, Puntland', 'Sahra Jama', '+25265606712', NULL, NULL, NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(19, NULL, 'PT-2026-00019', 'Nasteha', 'Elmi', '1964-05-27', 'female', '+25261259423', 'nasteha-elmi19@example.com', 'Garowe - Horseed, Puntland', 'Abdullahi Yusuf', '+25266926646', NULL, 'Latex', NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(20, NULL, 'PT-2026-00020', 'Farah', 'Ali', '1998-06-25', 'male', '+25265486312', 'farah-ali20@example.com', 'Garowe - Wadada Isgaarsiinta, Puntland', 'Naima Ahmed', '+25290379876', NULL, NULL, NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(21, NULL, 'PT-2026-00021', 'Abdifatah', 'Ibrahim', '1989-03-21', 'male', '+25290422307', 'abdifatah-ibrahim21@example.com', 'Garowe - Horseed, Puntland', 'Zainab Farah', '+25268082233', NULL, NULL, NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(22, NULL, 'PT-2026-00022', 'Hodan', 'Adan', '1996-09-10', 'female', '+25269818322', 'hodan-adan22@example.com', 'Garowe - Bulo Watiin, Puntland', 'Yusuf Ahmed', '+25268551333', 'Eaque quibusdam at sed.', NULL, NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(23, NULL, 'PT-2026-00023', 'Ali', 'Osman', '2016-06-17', 'male', '+25265523361', 'ali-osman23@example.com', 'Garowe - Bulo Watiin, Puntland', 'Ubah Farah', '+25261192917', NULL, 'Penicillin', NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(24, NULL, 'PT-2026-00024', 'Khadija', 'Farah', '1975-07-13', 'female', '+25266756154', 'khadija-farah24@example.com', 'Garowe - Horseed, Puntland', 'Hussein Ibrahim', '+25266299592', NULL, NULL, NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(25, NULL, 'PT-2026-00025', 'Yusuf', 'Yusuf', '1996-01-06', 'male', '+25266697827', 'yusuf-yusuf25@example.com', 'Garowe - Iskoshuban Rd, Puntland', 'Faiza Salah', '+25265225209', NULL, NULL, NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(26, NULL, 'PT-2026-00026', 'Ali', 'Ali', '2007-01-04', 'male', '+25266547901', 'ali-ali26@example.com', 'Garowe - Horseed, Puntland', 'Hawa Ibrahim', '+25268679246', NULL, NULL, NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(27, NULL, 'PT-2026-00027', 'Ayan', 'Hassan', '2009-04-03', 'female', '+25266331931', 'ayan-hassan27@example.com', 'Garowe - Wadada Isgaarsiinta, Puntland', 'Said Omar', '+25290130929', 'Est velit velit cumque porro enim.', NULL, NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(28, NULL, 'PT-2026-00028', 'Ahmed', 'Mohamed', '2021-09-01', 'male', '+25268239747', 'ahmed-mohamed28@example.com', 'Garowe, Puntland', 'Rahma Muse', '+25269801659', 'Dolor perferendis aspernatur odio aut non illo.', 'Penicillin', NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(29, NULL, 'PT-2026-00029', 'Mohamed', 'Aden', '1984-11-08', 'male', '+25290593921', 'mohamed-aden29@example.com', 'Garowe - Bulo Watiin, Puntland', 'Hodan Adan', '+25268383683', NULL, 'Penicillin', NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(30, NULL, 'PT-2026-00030', 'Hassan', 'Warsame', '1956-10-05', 'male', '+25266094131', 'hassan-warsame30@example.com', 'Garowe - Tawakal, Puntland', 'Deeqa Hussein', '+25268654785', NULL, NULL, NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(31, NULL, 'PT-2026-00031', 'Mustafe', 'Ismail', '2021-05-19', 'male', '+25268323320', 'mustafe-ismail31@example.com', 'Garowe - Tawakal, Puntland', 'Ikran Mohamed', '+25261268087', 'Dolorem sequi aut quasi dolorem dolores sit ut.', NULL, NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(32, NULL, 'PT-2026-00032', 'Liban', 'Ahmed', '1982-02-28', 'male', '+25265076370', 'liban-ahmed32@example.com', 'Garowe - Wadada Isgaarsiinta, Puntland', 'Fardowsa Elmi', '+25268051049', NULL, NULL, NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(33, NULL, 'PT-2026-00033', 'Ubah', 'Warsame', '1968-11-17', 'female', '+25268959719', 'ubah-warsame33@example.com', 'Garowe - Horseed, Puntland', 'Liban Warsame', '+25269611926', NULL, NULL, NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(34, NULL, 'PT-2026-00034', 'Jamal', 'Ali', '1963-03-26', 'male', '+25268733140', 'jamal-ali34@example.com', 'Garowe - Iskoshuban Rd, Puntland', 'Amina Abdi', '+25261204616', NULL, NULL, NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(35, NULL, 'PT-2026-00035', 'Khalid', 'Ibrahim', '1958-09-11', 'male', '+25269567360', 'khalid-ibrahim35@example.com', 'Garowe - Tawakal, Puntland', 'Fardowsa Salah', '+25265128980', 'Laboriosam est soluta modi molestiae enim.', NULL, NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(36, NULL, 'PT-2026-00036', 'Hussein', 'Ahmed', '1967-11-29', 'male', '+25261320329', 'hussein-ahmed36@example.com', 'Garowe, Puntland', 'Yasmin Elmi', '+25266790910', NULL, 'Latex', NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(37, NULL, 'PT-2026-00037', 'Fardowsa', 'Mohamed', '2020-02-12', 'female', '+25290888545', 'fardowsa-mohamed37@example.com', 'Garowe, Puntland', 'Said Jama', '+25269741328', NULL, NULL, NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(38, NULL, 'PT-2026-00038', 'Hassan', 'Yusuf', '1989-05-13', 'male', '+25266377777', 'hassan-yusuf38@example.com', 'Garowe - Tawakal, Puntland', 'Zainab Elmi', '+25266122934', 'Et voluptatem enim voluptas voluptates.', 'Latex', NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(39, NULL, 'PT-2026-00039', 'Jamal', 'Elmi', '2013-09-17', 'male', '+25290195879', 'jamal-elmi39@example.com', 'Garowe - Wadada Isgaarsiinta, Puntland', 'Hawa Ibrahim', '+25261226431', NULL, NULL, NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(40, NULL, 'PT-2026-00040', 'Mohamed', 'Jama', '1968-03-01', 'male', '+25269107565', 'mohamed-jama40@example.com', 'Garowe, Puntland', 'Faiza Ibrahim', '+25269709953', 'Sunt expedita quidem ab rem sequi sit aut.', NULL, NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21', NULL),
(43, NULL, 'PT-2026-00041', 'caasho mahamed', 'cali', '2015-12-06', 'female', '0906592163', 'caasho@gmail.com', 'x.mubarak', 'xaliimo abdulqadir', '0906592163', 'ilkaha buuxin', NULL, NULL, 1, '2026-09-27 13:25:37', '2026-09-27 13:25:37', NULL),
(44, NULL, 'PT-2026-00042', 'ahmed', 'abdi jamac', NULL, NULL, '906787878', 'ahmed21@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-09-28 15:38:18', '2026-09-28 15:38:18', NULL),
(45, NULL, 'PT-2026-00043', 'Hussein', 'Abdiwali', NULL, NULL, '907086993', 'hussein@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-09-28 15:49:33', '2026-09-28 15:49:33', NULL),
(46, NULL, 'PT-2026-00044', 'Sumayo Ali', 'Ciise', '2003-06-18', 'female', '905676767', 'sumayo@gmail.com', 'x.hodon', 'fardowso maxamed', '9067765681', NULL, NULL, NULL, 1, '2026-09-30 14:05:25', '2026-09-30 14:05:25', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payment_no` varchar(255) NOT NULL,
  `invoice_id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `payment_date` date NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `discount_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `payment_method_id` bigint(20) UNSIGNED DEFAULT NULL,
  `method` varchar(255) NOT NULL DEFAULT 'cash',
  `sender_phone` varchar(255) DEFAULT NULL,
  `reference_no` varchar(255) DEFAULT NULL,
  `type` enum('payment','refund','credit_note') NOT NULL DEFAULT 'payment',
  `notes` text DEFAULT NULL,
  `received_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `payment_no`, `invoice_id`, `patient_id`, `payment_date`, `amount`, `discount_amount`, `payment_method_id`, `method`, `sender_phone`, `reference_no`, `type`, `notes`, `received_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'PAY-2026-00001', 1, 28, '2026-09-08', 27.00, 0.00, NULL, 'cash', NULL, 'REF-9902WR', 'payment', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(2, 'PAY-2026-00002', 2, 8, '2026-08-15', 400.00, 0.00, NULL, 'cash', NULL, 'REF-4092SQ', 'payment', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(3, 'PAY-2026-00003', 3, 1, '2026-08-19', 20.00, 0.00, NULL, 'mobile_money', NULL, 'REF-7659KQ', 'payment', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(4, 'PAY-2026-00004', 4, 20, '2026-08-10', 15.00, 0.00, NULL, 'cash', NULL, 'REF-9105KH', 'payment', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(5, 'PAY-2026-00006', 6, 15, '2026-09-06', 400.00, 0.00, NULL, 'cash', NULL, 'REF-7702YE', 'payment', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(6, 'PAY-2026-00007', 7, 18, '2026-09-11', 120.00, 0.00, NULL, 'mobile_money', NULL, 'REF-0421WA', 'payment', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(7, 'PAY-2026-00008', 8, 7, '2026-08-31', 180.00, 0.00, NULL, 'bank_transfer', NULL, 'REF-3355ZW', 'payment', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(8, 'PAY-2026-00009', 9, 3, '2026-08-09', 72.00, 0.00, NULL, 'bank_transfer', NULL, 'REF-2169BP', 'payment', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(9, 'PAY-2026-00010', 10, 32, '2026-08-06', 400.00, 0.00, NULL, 'cash', NULL, 'REF-4954SY', 'payment', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(10, 'PAY-2026-00011', 11, 28, '2026-08-20', 7.50, 0.00, NULL, 'card', NULL, 'REF-7669FU', 'payment', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(11, 'PAY-2026-00012', 12, 20, '2026-08-02', 60.00, 0.00, NULL, 'bank_transfer', NULL, 'REF-6043SB', 'payment', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(12, 'PAY-2026-00013', 13, 16, '2026-08-17', 25.00, 0.00, NULL, 'cash', NULL, 'REF-2596BD', 'payment', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(13, 'PAY-2026-00014', 14, 30, '2026-08-19', 20.00, 0.00, NULL, 'bank_transfer', NULL, 'REF-9730UQ', 'payment', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(14, 'PAY-2026-00015', 15, 9, '2026-08-11', 15.00, 0.00, NULL, 'mobile_money', NULL, 'REF-0231DV', 'payment', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(15, 'PAY-2026-00016', 16, 31, '2026-08-23', 80.00, 0.00, NULL, 'mobile_money', NULL, 'REF-0507QK', 'payment', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(16, 'PAY-2026-00017', 17, 32, '2026-09-06', 360.00, 0.00, NULL, 'card', NULL, 'REF-7848QV', 'payment', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(17, 'PAY-2026-00018', 18, 1, '2026-08-20', 108.00, 0.00, NULL, 'cash', NULL, 'REF-5326UL', 'payment', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(18, 'PAY-2026-00019', 19, 13, '2026-09-05', 12.50, 0.00, NULL, 'mobile_money', NULL, 'REF-9981HY', 'payment', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(19, 'PAY-2026-00020', 20, 14, '2026-09-05', 25.00, 0.00, NULL, 'cash', NULL, 'REF-6571EG', 'payment', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(20, 'PAY-2026-00023', 23, 34, '2026-08-18', 35.00, 0.00, NULL, 'card', NULL, 'REF-6528PG', 'payment', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(21, 'PAY-2026-00025', 25, 5, '2026-09-07', 108.00, 0.00, NULL, 'bank_transfer', NULL, 'REF-6046ES', 'payment', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(22, 'PAY-2026-00027', 27, 27, '2026-08-24', 80.00, 0.00, NULL, 'cash', NULL, 'REF-1764NB', 'payment', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(23, 'PAY-2026-00030', 30, 28, '2026-08-05', 7.50, 0.00, NULL, 'bank_transfer', NULL, 'REF-2396CT', 'payment', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(24, 'PAY-2026-00031', 31, 38, '2026-08-31', 40.00, 0.00, NULL, 'cash', NULL, 'REF-8115NF', 'payment', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(25, 'PAY-2026-00032', 32, 17, '2026-08-21', 40.00, 0.00, NULL, 'card', NULL, 'REF-9283PO', 'payment', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(26, 'PAY-2026-00033', 33, 38, '2026-08-05', 35.00, 0.00, NULL, 'cash', NULL, 'REF-3966YP', 'payment', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(27, 'PAY-2026-00035', 35, 12, '2026-09-03', 27.00, 0.00, NULL, 'cash', NULL, 'REF-1062SH', 'payment', NULL, NULL, '2026-09-12 15:23:22', '2026-09-12 15:23:22', NULL),
(28, 'PAY-2026-00036', 30, 28, '2026-09-13', 7.50, 6.00, 1, 'Cash', NULL, NULL, 'payment', NULL, 2, '2026-09-13 08:01:15', '2026-09-13 08:01:15', NULL),
(29, 'PAY-2026-00037', 11, 28, '2026-09-13', 7.50, 5.00, 2, 'e-Dahab', '252617787878', NULL, 'payment', NULL, 1, '2026-09-13 10:50:16', '2026-09-13 10:50:16', NULL),
(30, 'PAY-2026-00038', 24, 37, '2026-09-13', 150.00, 10.00, 1, 'Cash', NULL, '0907896657', 'payment', 'ilko dhaqid', 1, '2026-09-13 11:04:06', '2026-09-13 11:04:06', NULL),
(31, 'PAY-2026-00039', 3, 1, '2026-09-13', 20.00, 0.00, 4, 'Bank Transfer', '252617787878', NULL, 'payment', NULL, 1, '2026-09-13 11:12:25', '2026-09-13 11:12:25', NULL),
(32, 'PAY-2026-00040', 39, 28, '2026-09-19', 15.00, 0.00, 3, 'Sahal (EVC Plus)', '252617787878', NULL, 'payment', NULL, 13, '2026-09-19 15:38:57', '2026-09-19 15:38:57', NULL),
(33, 'PAY-2026-00041', 41, 46, '2026-09-30', 28.50, 1.50, 3, 'SAHAL GOLIS', '252617787878', NULL, 'payment', NULL, 1, '2026-09-30 15:01:11', '2026-09-30 15:01:11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `account_number` varchar(255) DEFAULT NULL,
  `account_name` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payment_methods`
--

INSERT INTO `payment_methods` (`id`, `name`, `code`, `account_number`, `account_name`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Cash', 'cash', NULL, NULL, 1, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(2, 'e-Dahab', 'edahab', '252-65-0000000', 'DCATMS Dental Clinic', 1, 2, '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(3, 'SAHAL GOLIS', 'sahal', '252-61-0000000', 'DCATMS Dental Clinic', 1, 3, '2026-09-12 15:23:21', '2026-09-30 15:00:29'),
(4, 'Bank Transfer', 'bank_transfer', 'SO00 0000 0000 0000', 'DCATMS Dental Clinic', 1, 4, '2026-09-12 15:23:21', '2026-09-12 15:23:21');

-- --------------------------------------------------------

--
-- Table structure for table `prescriptions`
--

CREATE TABLE `prescriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `treatment_id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `dentist_id` bigint(20) UNSIGNED NOT NULL,
  `medicine_name` varchar(255) NOT NULL,
  `dosage` varchar(255) DEFAULT NULL,
  `frequency` varchar(255) DEFAULT NULL,
  `duration` varchar(255) DEFAULT NULL,
  `instructions` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin', 'Full system access', '2026-09-12 15:23:18', '2026-09-12 15:23:18'),
(2, 'Dentist', 'dentist', 'Clinical staff', '2026-09-12 15:23:18', '2026-09-12 15:23:18'),
(3, 'Receptionist', 'receptionist', 'Front desk / appointments', '2026-09-12 15:23:18', '2026-09-12 15:23:18'),
(4, 'Accountant', 'accountant', 'Billing and finance', '2026-09-12 15:23:18', '2026-09-12 15:23:18'),
(5, 'Patient', 'patient', 'Patient portal access', '2026-09-12 15:23:18', '2026-09-12 15:23:18');

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

CREATE TABLE `role_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_user`
--

INSERT INTO `role_user` (`id`, `user_id`, `role_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL),
(2, 2, 3, NULL, NULL),
(3, 3, 4, NULL, NULL),
(4, 4, 5, NULL, NULL),
(5, 5, 2, NULL, NULL),
(6, 6, 2, NULL, NULL),
(7, 7, 2, NULL, NULL),
(8, 8, 1, NULL, NULL),
(9, 9, 2, NULL, NULL),
(10, 10, 2, NULL, NULL),
(11, 11, 3, NULL, NULL),
(12, 12, 1, NULL, NULL),
(13, 13, 1, NULL, NULL),
(14, 14, 2, NULL, NULL),
(15, 15, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dentist_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('weekly','leave') NOT NULL DEFAULT 'weekly',
  `day_of_week` tinyint(4) DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `leave_date` date DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `dentist_id`, `type`, `day_of_week`, `start_time`, `end_time`, `leave_date`, `reason`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'weekly', 0, '08:00:00', '14:00:00', NULL, NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(2, 1, 'weekly', 1, '08:00:00', '14:00:00', NULL, NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(3, 1, 'weekly', 2, '08:00:00', '14:00:00', NULL, NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(4, 1, 'weekly', 3, '08:00:00', '14:00:00', NULL, NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(5, 1, 'weekly', 4, '08:00:00', '14:00:00', NULL, NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(6, 2, 'weekly', 0, '14:00:00', '20:00:00', NULL, NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(7, 2, 'weekly', 1, '14:00:00', '20:00:00', NULL, NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(8, 2, 'weekly', 2, '14:00:00', '20:00:00', NULL, NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(9, 2, 'weekly', 3, '14:00:00', '20:00:00', NULL, NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(10, 2, 'weekly', 4, '14:00:00', '20:00:00', NULL, NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(11, 3, 'weekly', 0, '09:00:00', '17:00:00', NULL, NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(12, 3, 'weekly', 1, '09:00:00', '17:00:00', NULL, NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(13, 3, 'weekly', 2, '09:00:00', '17:00:00', NULL, NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(14, 3, 'weekly', 3, '09:00:00', '17:00:00', NULL, NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(15, 3, 'weekly', 4, '09:00:00', '17:00:00', NULL, NULL, 1, '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(16, 6, 'weekly', 6, '08:00:00', '20:00:00', NULL, NULL, 1, '2026-09-30 14:24:24', '2026-09-30 14:24:24'),
(17, 6, 'weekly', 0, '08:00:00', '20:00:00', NULL, NULL, 1, '2026-09-30 14:24:24', '2026-09-30 14:24:24'),
(18, 6, 'weekly', 1, '08:00:00', '20:00:00', NULL, NULL, 1, '2026-09-30 14:24:24', '2026-09-30 14:24:24'),
(19, 6, 'weekly', 2, '08:00:00', '20:00:00', NULL, NULL, 1, '2026-09-30 14:24:24', '2026-09-30 14:24:24'),
(20, 6, 'weekly', 3, '08:00:00', '20:00:00', NULL, NULL, 1, '2026-09-30 14:24:24', '2026-09-30 14:24:24');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `duration_minutes` int(10) UNSIGNED NOT NULL DEFAULT 30,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `category`, `description`, `price`, `duration_minutes`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Dental Check-up', 'preventive', NULL, 15.00, 20, 1, '2026-09-12 15:23:20', '2026-09-12 15:23:20', NULL),
(2, 'Teeth Cleaning (Scaling)', 'preventive', NULL, 30.00, 30, 1, '2026-09-12 15:23:20', '2026-09-12 15:23:20', NULL),
(3, 'Tooth Filling', 'restorative', NULL, 40.00, 45, 1, '2026-09-12 15:23:20', '2026-09-12 15:23:20', NULL),
(4, 'Tooth Extraction', 'surgical', NULL, 35.00, 30, 1, '2026-09-12 15:23:20', '2026-09-12 15:23:20', NULL),
(5, 'Root Canal Treatment', 'restorative', NULL, 120.00, 90, 1, '2026-09-12 15:23:20', '2026-09-12 15:23:20', NULL),
(6, 'Dental Crown', 'restorative', NULL, 150.00, 60, 1, '2026-09-12 15:23:20', '2026-09-12 15:23:20', NULL),
(7, 'Teeth Whitening', 'cosmetic', NULL, 80.00, 45, 1, '2026-09-12 15:23:20', '2026-09-12 15:23:20', NULL),
(8, 'Braces Consultation', 'orthodontic', NULL, 25.00, 30, 1, '2026-09-12 15:23:20', '2026-09-12 15:23:20', NULL),
(9, 'Dental Implant', 'surgical', NULL, 400.00, 120, 1, '2026-09-12 15:23:20', '2026-09-12 15:23:20', NULL),
(10, 'X-Ray Imaging', 'diagnostic', NULL, 20.00, 15, 1, '2026-09-12 15:23:20', '2026-09-12 15:23:20', NULL);

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
('jpWZeaLSeFzRLSMR89hdKJgQeEFMuBIuYTEmR7dm', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiM0ZybHFuTXFNdjBmUGlFSTBEUUJpVzRIVGJIMWZVY21JR2doZkNudyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9wYXltZW50cy8zMy9yZWNlaXB0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1790974050);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'clinic_name', 'DCATMS', '2026-09-12 15:23:21', '2026-09-19 15:18:16'),
(2, 'clinic_phone', '+252 61 0000000', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(3, 'clinic_email', 'info@dcatms.test', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(4, 'clinic_address', 'Mogadishu, Somalia', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(5, 'working_hours', '09:00 - 17:00 (Sun - Thu)', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(6, 'currency', 'USD', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(7, 'currency_symbol', '$', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(8, 'tax_rate', '0', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(9, 'invoice_prefix', 'INV', '2026-09-12 15:23:21', '2026-09-12 15:23:21'),
(10, 'appointment_prefix', 'APT', '2026-09-12 15:23:21', '2026-09-12 15:23:21');

-- --------------------------------------------------------

--
-- Table structure for table `stock_movements`
--

CREATE TABLE `stock_movements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `inventory_item_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('in','out') NOT NULL DEFAULT 'in',
  `quantity` int(10) UNSIGNED NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `performed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `rating` tinyint(3) UNSIGNED NOT NULL DEFAULT 5,
  `message` text NOT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `treatments`
--

CREATE TABLE `treatments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `appointment_id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `dentist_id` bigint(20) UNSIGNED NOT NULL,
  `visit_date` date NOT NULL,
  `diagnosis` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `treatments`
--

INSERT INTO `treatments` (`id`, `appointment_id`, `patient_id`, `dentist_id`, `visit_date`, `diagnosis`, `notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 28, 1, 2, '2026-08-20', 'ilkig xanuun', 'ilig raba in xididka laga dilo', '2026-09-13 03:42:54', '2026-09-13 03:42:54', NULL),
(2, 38, 37, 2, '2026-09-01', 'ilko badalid', 'ilko hagajin', '2026-09-13 10:57:19', '2026-09-13 10:57:19', NULL),
(3, 2, 8, 1, '2026-08-15', NULL, NULL, '2026-09-23 06:23:16', '2026-09-23 06:23:16', NULL),
(4, 73, 46, 6, '2026-09-30', 'Ilko dhaqid', NULL, '2026-09-30 14:31:44', '2026-09-30 14:50:20', '2026-09-30 14:50:20'),
(6, 73, 46, 6, '2026-09-30', 'ilko dhaqid', NULL, '2026-09-30 14:52:11', '2026-09-30 14:52:11', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `treatment_details`
--

CREATE TABLE `treatment_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `treatment_id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `tooth_number` varchar(255) DEFAULT NULL,
  `procedure_notes` text DEFAULT NULL,
  `quantity` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `unit_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `treatment_details`
--

INSERT INTO `treatment_details` (`id`, `treatment_id`, `service_id`, `tooth_number`, `procedure_notes`, `quantity`, `unit_price`, `created_at`, `updated_at`) VALUES
(1, 1, 5, NULL, NULL, 1, 120.00, '2026-09-13 03:42:54', '2026-09-13 03:42:54'),
(2, 2, 6, NULL, NULL, 1, 150.00, '2026-09-13 10:57:19', '2026-09-13 10:57:19'),
(3, 3, 9, NULL, NULL, 1, 400.00, '2026-09-23 06:23:16', '2026-09-23 06:23:16'),
(4, 4, 2, NULL, NULL, 1, 30.00, '2026-09-30 14:31:44', '2026-09-30 14:31:44'),
(7, 6, 2, NULL, NULL, 1, 30.00, '2026-09-30 14:52:11', '2026-09-30 14:52:11');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `photo`, `is_active`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'System Administrator', 'admin@dcatms.test', NULL, NULL, 1, NULL, '$2y$12$ZjCy8Wx4h0tKtLU.ekFYO.7UBVr/iic4/W.bZfatVvK8sV6Ua41QC', NULL, '2026-09-12 15:23:19', '2026-09-12 15:23:19', NULL),
(2, 'Amina Yusuf', 'receptionist@dcatms.test', NULL, NULL, 1, NULL, '$2y$12$zHvtCu3CJkZ.2AkpKaK8euBcXLn3ixY/.w4JQgBnONJdekTb5yUqq', NULL, '2026-09-12 15:23:19', '2026-09-28 15:55:33', NULL),
(3, 'Khalid Warsame', 'accountant@dcatms.test', NULL, NULL, 1, NULL, '$2y$12$f30NYFf.rO2GRGH2sLLjTe7CqZVa0TUC5oSDPbnQVmxqqDTTFtPNG', NULL, '2026-09-12 15:23:19', '2026-09-12 15:23:19', NULL),
(4, 'Demo Patient', 'patient@dcatms.test', NULL, NULL, 1, NULL, '$2y$12$WGWkt9zWI69/Qpo1GFJrUuFSPiYkocvfI/P4hy05v52Mc00LPK0Sq', NULL, '2026-09-12 15:23:20', '2026-10-02 13:13:08', NULL),
(5, 'Dr. Abdirahman Mohamed Ali', 'dentist1@dcatms.test', '+2529012345', 'avatars/dentist1.jpg', 1, NULL, '$2y$12$6N4KQBhykt0jdZ8i8Rad0uB77vlYEum7Z9qOqCrSvtlgqPVctVWnW', NULL, '2026-09-12 15:23:20', '2026-09-28 15:42:07', NULL),
(6, 'Dr. Hodan Yusuf Warsame', 'dentist2@dcatms.test', '+2526112233', 'avatars/dentist2.jpg', 1, NULL, '$2y$12$wS1HGFkrzRrMnJoBnzyz3uUEEuaeAvC9yzvaYzagrvmNb1fwZ5T0u', NULL, '2026-09-12 15:23:20', '2026-09-13 06:41:50', NULL),
(7, 'Dr. Khalid Ahmed Farah', 'dentist3@dcatms.test', '+2526898765', 'avatars/dentist3.jpg', 1, NULL, '$2y$12$u5Ugr1F6ieKgXSOw0YpfBeaFE4j72nqLCSTuKw6eTBkVkWaBlyeZa', NULL, '2026-09-12 15:23:21', '2026-09-13 06:41:50', NULL),
(8, 'mahamed ahemd', 'ahmed@dcatms.test', NULL, NULL, 1, NULL, '$2y$12$0kbR/7z0VHvG1QePMTt/e.rECv.tR3owmZoXXRqWUQRpqlx9NFyzS', NULL, '2026-09-13 04:08:06', '2026-09-13 04:08:06', NULL),
(9, 'Ismahan Mahmed', 'ismahan@gmail.com', NULL, 'avatars/ismahan-mahmed.jpg', 1, NULL, '$2y$12$QRR0iIFtNoTVr6Ext397x.ymYWYiyQ.watdUsTGdPxEBnppDyZAiW', NULL, '2026-09-13 06:38:39', '2026-09-13 06:41:50', NULL),
(10, 'Dr. Geele Mahmed Adan', 'geele@dcatms.test', '+252905229131', NULL, 1, NULL, '$2y$12$rHMlwCxctPLx4HKcGSoTfu49lI5KDmWEisPPGz.hm.giyFt2zdFBG', NULL, '2026-09-13 07:33:03', '2026-10-02 13:13:23', NULL),
(11, 'haawo maxamed', 'haawa@dcatms.test', NULL, NULL, 1, NULL, '$2y$12$/AbiAu7gzlaWGMYesBYuae72LGRmKD/3KvBoSebuOpzmlAI7A63vC', NULL, '2026-09-13 10:52:29', '2026-09-13 10:52:29', NULL),
(12, 'fardowso maxamed', 'ishak@dcatms.test', '906787878', NULL, 1, NULL, '$2y$12$XDoN5.wNkzbIXLvz4tx9huLTXRGiyPRAK1sSH0dRM9hTVBVPBJ7XW', NULL, '2026-09-19 13:35:32', '2026-09-19 13:35:32', NULL),
(13, 'hhhhhh', 'fardowso@gmail.com', NULL, NULL, 1, NULL, '$2y$12$MayLf4glzDyxVGzdvuyf4OKl0eOgpr0r0wdNlYgLP2IqYAK8c5IKm', NULL, '2026-09-19 13:36:33', '2026-09-28 15:55:41', NULL),
(14, 'Drs Ismahan Moha', 'ismahaan23@dcatms.test', '906787878', NULL, 1, NULL, '$2y$12$YnrrGPP/4TRJ2t/4JhqUK.R9B4H6zz9qxoomy5D3e/oKeEPjhOTKu', NULL, '2026-09-28 15:45:14', '2026-09-28 15:55:31', NULL),
(15, 'Dr. Fuaad Said', 'fahadsaidmohamed@gmail.com', '+252907316979', NULL, 1, NULL, '$2y$12$yZDnOBDPkxRWxMKcqUcVYOY27g.NrZyYulaSTX4vU42fQrB/dSNOm', NULL, '2026-09-30 14:16:34', '2026-10-02 04:31:50', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_logs_user_id_foreign` (`user_id`),
  ADD KEY `activity_logs_subject_type_subject_id_index` (`subject_type`,`subject_id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `appointments_appointment_no_unique` (`appointment_no`),
  ADD KEY `appointments_service_id_foreign` (`service_id`),
  ADD KEY `appointments_created_by_foreign` (`created_by`),
  ADD KEY `appointments_dentist_id_appointment_date_start_time_index` (`dentist_id`,`appointment_date`,`start_time`),
  ADD KEY `appointments_patient_id_index` (`patient_id`),
  ADD KEY `appointments_status_index` (`status`);

--
-- Indexes for table `appointment_requests`
--
ALTER TABLE `appointment_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointment_requests_service_id_foreign` (`service_id`);

--
-- Indexes for table `appointment_status_logs`
--
ALTER TABLE `appointment_status_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointment_status_logs_changed_by_foreign` (`changed_by`),
  ADD KEY `appointment_status_logs_appointment_id_index` (`appointment_id`);

--
-- Indexes for table `attachments`
--
ALTER TABLE `attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attachments_patient_id_foreign` (`patient_id`),
  ADD KEY `attachments_treatment_id_foreign` (`treatment_id`),
  ADD KEY `attachments_uploaded_by_foreign` (`uploaded_by`);

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
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dental_chart_entries`
--
ALTER TABLE `dental_chart_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dental_chart_entries_treatment_id_foreign` (`treatment_id`),
  ADD KEY `dental_chart_entries_patient_id_tooth_number_index` (`patient_id`,`tooth_number`);

--
-- Indexes for table `dentists`
--
ALTER TABLE `dentists`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `dentists_user_id_unique` (`user_id`),
  ADD UNIQUE KEY `dentists_dentist_code_unique` (`dentist_code`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `inventory_items`
--
ALTER TABLE `inventory_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `inventory_items_item_code_unique` (`item_code`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoices_invoice_no_unique` (`invoice_no`),
  ADD KEY `invoices_appointment_id_foreign` (`appointment_id`),
  ADD KEY `invoices_created_by_foreign` (`created_by`),
  ADD KEY `invoices_patient_id_index` (`patient_id`),
  ADD KEY `invoices_status_index` (`status`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_items_invoice_id_foreign` (`invoice_id`),
  ADD KEY `invoice_items_service_id_foreign` (`service_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `patients_patient_code_unique` (`patient_code`),
  ADD UNIQUE KEY `patients_user_id_unique` (`user_id`),
  ADD KEY `patients_first_name_last_name_index` (`first_name`,`last_name`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payments_payment_no_unique` (`payment_no`),
  ADD KEY `payments_payment_method_id_foreign` (`payment_method_id`),
  ADD KEY `payments_received_by_foreign` (`received_by`),
  ADD KEY `payments_invoice_id_index` (`invoice_id`),
  ADD KEY `payments_patient_id_index` (`patient_id`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_methods_code_unique` (`code`);

--
-- Indexes for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prescriptions_treatment_id_foreign` (`treatment_id`),
  ADD KEY `prescriptions_patient_id_foreign` (`patient_id`),
  ADD KEY `prescriptions_dentist_id_foreign` (`dentist_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_slug_unique` (`slug`);

--
-- Indexes for table `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_user_user_id_role_id_unique` (`user_id`,`role_id`),
  ADD KEY `role_user_role_id_foreign` (`role_id`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schedules_dentist_id_day_of_week_index` (`dentist_id`,`day_of_week`),
  ADD KEY `schedules_dentist_id_leave_date_index` (`dentist_id`,`leave_date`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indexes for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_movements_performed_by_foreign` (`performed_by`),
  ADD KEY `stock_movements_inventory_item_id_index` (`inventory_item_id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `treatments`
--
ALTER TABLE `treatments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `treatments_appointment_id_foreign` (`appointment_id`),
  ADD KEY `treatments_dentist_id_foreign` (`dentist_id`),
  ADD KEY `treatments_patient_id_index` (`patient_id`);

--
-- Indexes for table `treatment_details`
--
ALTER TABLE `treatment_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `treatment_details_treatment_id_foreign` (`treatment_id`),
  ADD KEY `treatment_details_service_id_foreign` (`service_id`);

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
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `appointment_requests`
--
ALTER TABLE `appointment_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `appointment_status_logs`
--
ALTER TABLE `appointment_status_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;

--
-- AUTO_INCREMENT for table `attachments`
--
ALTER TABLE `attachments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `dental_chart_entries`
--
ALTER TABLE `dental_chart_entries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `dentists`
--
ALTER TABLE `dentists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_items`
--
ALTER TABLE `inventory_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `prescriptions`
--
ALTER TABLE `prescriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `role_user`
--
ALTER TABLE `role_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `treatments`
--
ALTER TABLE `treatments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `treatment_details`
--
ALTER TABLE `treatment_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `appointments_dentist_id_foreign` FOREIGN KEY (`dentist_id`) REFERENCES `dentists` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `appointment_requests`
--
ALTER TABLE `appointment_requests`
  ADD CONSTRAINT `appointment_requests_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `appointment_status_logs`
--
ALTER TABLE `appointment_status_logs`
  ADD CONSTRAINT `appointment_status_logs_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointment_status_logs_changed_by_foreign` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `attachments`
--
ALTER TABLE `attachments`
  ADD CONSTRAINT `attachments_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `attachments_treatment_id_foreign` FOREIGN KEY (`treatment_id`) REFERENCES `treatments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `attachments_uploaded_by_foreign` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `dental_chart_entries`
--
ALTER TABLE `dental_chart_entries`
  ADD CONSTRAINT `dental_chart_entries_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `dental_chart_entries_treatment_id_foreign` FOREIGN KEY (`treatment_id`) REFERENCES `treatments` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `dentists`
--
ALTER TABLE `dentists`
  ADD CONSTRAINT `dentists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `invoices_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `invoices_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD CONSTRAINT `invoice_items_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invoice_items_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `patients_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payments_received_by_foreign` FOREIGN KEY (`received_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `prescriptions`
--
ALTER TABLE `prescriptions`
  ADD CONSTRAINT `prescriptions_dentist_id_foreign` FOREIGN KEY (`dentist_id`) REFERENCES `dentists` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prescriptions_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prescriptions_treatment_id_foreign` FOREIGN KEY (`treatment_id`) REFERENCES `treatments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_user`
--
ALTER TABLE `role_user`
  ADD CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `schedules_dentist_id_foreign` FOREIGN KEY (`dentist_id`) REFERENCES `dentists` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD CONSTRAINT `stock_movements_inventory_item_id_foreign` FOREIGN KEY (`inventory_item_id`) REFERENCES `inventory_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_movements_performed_by_foreign` FOREIGN KEY (`performed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `treatments`
--
ALTER TABLE `treatments`
  ADD CONSTRAINT `treatments_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `treatments_dentist_id_foreign` FOREIGN KEY (`dentist_id`) REFERENCES `dentists` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `treatments_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `treatment_details`
--
ALTER TABLE `treatment_details`
  ADD CONSTRAINT `treatment_details_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `treatment_details_treatment_id_foreign` FOREIGN KEY (`treatment_id`) REFERENCES `treatments` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
