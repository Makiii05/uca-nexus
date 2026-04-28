-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 06, 2026 at 05:06 AM
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
-- Database: `enrollment_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `academic_terms`
--

CREATE TABLE `academic_terms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `code` text NOT NULL,
  `description` text NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'semester',
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `academic_year` text NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `academic_terms`
--

INSERT INTO `academic_terms` (`id`, `created_at`, `updated_at`, `code`, `description`, `type`, `department_id`, `academic_year`, `start_date`, `end_date`, `status`) VALUES
(1, '2025-12-31 13:13:09', '2025-12-31 13:30:53', 'FULL 25-26', 'Full Year 2025-2026', 'Full Year', 1, '2025-2026', '2025-06-01', '2026-03-27', 'active'),
(2, '2025-12-31 13:13:09', '2025-12-31 13:30:53', 'FULL 25-26', 'Full Year 2025-2026', 'Full Year', 2, '2025-2026', '2025-06-01', '2026-03-27', 'active'),
(3, '2025-12-31 13:13:09', '2025-12-31 13:30:53', 'FULL 25-26', 'Full Year 2025-2026', 'Full Year', 3, '2025-2026', '2025-06-01', '2026-03-27', 'active'),
(4, '2025-12-31 13:13:09', '2025-12-31 13:30:53', '1st 25-26', '1st Semester 2025-2026', 'Semester', 4, '2025-2026', '2025-06-01', '2025-10-31', 'active'),
(5, '2025-12-31 13:30:07', '2025-12-31 13:34:05', '2nd 25-26', '2nd Semester 2025-2026', 'Semester', 4, '2025-2026', '2025-11-12', '2026-03-27', 'active'),
(6, '2025-12-31 13:35:04', '2025-12-31 13:35:04', 'SUMMER 25-26', 'Summer 2025-2026', 'Semester', 4, '2025-2026', '2026-04-06', '2026-05-29', 'active'),
(7, '2025-12-31 13:40:26', '2025-12-31 13:40:26', '1st 25-26', '1st Semester 2025-2026', 'Semester', 5, '2025-2026', '2025-08-18', '2025-12-19', 'active'),
(8, '2025-12-31 13:41:08', '2025-12-31 13:41:08', '2nd 25-26', '2nd Semester 2025-2026', 'Semester', 5, '2025-2026', '2026-01-12', '2026-05-22', 'active'),
(9, '2025-12-31 13:42:04', '2025-12-31 13:42:04', 'SUMMER 25-26', 'Summer 2025-2026', 'Semester', 5, '2025-2026', '2026-05-25', '2026-07-31', 'active');

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
-- Table structure for table `curricula`
--

CREATE TABLE `curricula` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `curriculum` text NOT NULL,
  `status` varchar(255) NOT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `curricula`
--

INSERT INTO `curricula` (`id`, `created_at`, `updated_at`, `curriculum`, `status`, `department_id`) VALUES
(1, '2025-12-31 21:14:40', '2025-12-31 21:14:40', '2012-13', 'active', 1),
(2, '2025-12-31 21:15:02', '2025-12-31 21:15:02', '2012-13', 'active', 2),
(3, '2025-12-31 21:15:18', '2025-12-31 21:15:18', '2012-13', 'active', 3),
(4, '2025-12-31 21:15:25', '2025-12-31 21:15:25', '2012-13', 'active', 4),
(5, '2025-12-31 21:15:33', '2025-12-31 21:15:33', '2023-24', 'active', 5);

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `code` text NOT NULL,
  `description` text NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `created_at`, `updated_at`, `code`, `description`, `status`) VALUES
(1, '2025-12-31 14:14:09', '2025-12-31 14:14:09', 'PRE', 'Pre-Elementary', 'active'),
(2, '2025-12-31 14:15:31', '2025-12-31 14:15:31', 'ELEM', 'Elementary', 'active'),
(3, '2025-12-31 14:15:52', '2025-12-31 14:15:52', 'JHS', 'Junior High School', 'active'),
(4, '2025-12-31 14:16:05', '2025-12-31 14:16:05', 'SHS', 'Senior High School', 'active'),
(5, '2025-12-31 14:16:26', '2025-12-31 14:50:56', 'CCTE', 'College of Computing and Technology Engineering', 'active');

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
-- Table structure for table `fees`
--

CREATE TABLE `fees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `description` text NOT NULL,
  `amount` decimal(8,2) NOT NULL,
  `type` text DEFAULT NULL,
  `month_to_pay` double DEFAULT NULL,
  `group` text NOT NULL,
  `academic_year` text NOT NULL,
  `program_id` bigint(20) UNSIGNED NOT NULL
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
-- Table structure for table `levels`
--

CREATE TABLE `levels` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `code` text NOT NULL,
  `description` text NOT NULL,
  `program_id` bigint(20) UNSIGNED NOT NULL,
  `order` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `levels`
--

INSERT INTO `levels` (`id`, `created_at`, `updated_at`, `code`, `description`, `program_id`, `order`) VALUES
(1, '2026-01-01 15:21:50', '2026-01-01 15:21:50', 'NUR 1', 'Nursery 1', 1, 1),
(2, '2026-01-01 15:22:08', '2026-01-01 15:22:08', 'KIN 1', 'Kindergarten 1', 2, 1),
(3, '2026-01-01 15:22:21', '2026-01-01 15:22:21', 'KIN 2', 'Kindergarten 2', 2, 2),
(4, '2026-01-01 15:22:41', '2026-01-01 15:22:41', 'G1', 'Grade 1', 3, 1),
(5, '2026-01-01 15:23:25', '2026-01-01 15:23:25', 'G2', 'Grade 2', 3, 2),
(6, '2026-01-01 15:23:43', '2026-01-01 15:23:43', 'G3', 'Grade 3', 3, 3),
(7, '2026-01-01 15:24:01', '2026-01-01 15:24:01', 'G4', 'Grade 4', 3, 4),
(8, '2026-01-01 15:24:12', '2026-01-01 15:24:12', 'G5', 'Grade 5', 3, 5),
(9, '2026-01-01 15:24:28', '2026-01-01 15:24:28', 'G6', 'Grade 6', 3, 6),
(10, '2026-01-01 15:24:49', '2026-01-01 15:24:49', 'G7', 'Grade 7', 4, 1),
(11, '2026-01-01 15:25:04', '2026-01-01 15:25:04', 'G8', 'Grade 8', 4, 2),
(12, '2026-01-01 15:25:17', '2026-01-01 15:25:17', 'G9', 'Grade 9', 4, 3),
(13, '2026-01-01 15:25:42', '2026-01-01 15:25:42', 'G10', 'Grade 10', 4, 4),
(14, '2026-01-01 15:27:26', '2026-01-01 15:29:37', 'STEM 11', 'Grade 11', 5, 1),
(15, '2026-01-01 15:27:38', '2026-01-01 15:29:50', 'STEM 12', 'Grade 12', 5, 2),
(16, '2026-01-01 15:29:20', '2026-01-01 15:29:20', 'BSCS 1', '1st Year', 6, 1),
(17, '2026-01-01 15:30:20', '2026-01-01 15:30:20', 'BSCS 2', '2nd Year', 6, 2),
(18, '2026-01-01 15:30:37', '2026-01-01 15:30:37', 'BSCS 3', '3rd Year', 6, 3),
(19, '2026-01-01 15:30:54', '2026-01-01 15:30:54', 'BSCS 4', '4th Year', 6, 4);

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
(4, '2025_12_06_162707_departments_table', 1),
(5, '2025_12_06_162717_programs_table', 1),
(6, '2025_12_06_162834_curricula_table', 1),
(7, '2025_12_06_162844_subjects_table', 1),
(8, '2025_12_06_162910_semesters_table', 1),
(9, '2025_12_06_162911_levels_table', 1),
(10, '2025_12_06_162912_prospectuses_table', 1),
(11, '2025_12_18_135029_fees_table', 1);

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
-- Table structure for table `programs`
--

CREATE TABLE `programs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `code` text NOT NULL,
  `description` text NOT NULL,
  `status` varchar(255) NOT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `programs`
--

INSERT INTO `programs` (`id`, `created_at`, `updated_at`, `code`, `description`, `status`, `department_id`) VALUES
(1, '2026-01-01 14:58:55', '2026-01-01 14:58:55', 'NUR', 'Nursery', 'active', 1),
(2, '2026-01-01 14:59:08', '2026-01-01 14:59:08', 'KIN', 'Kindergarten', 'active', 1),
(3, '2026-01-01 14:59:38', '2026-01-01 14:59:38', 'GRADE', 'Grade School', 'active', 2),
(4, '2026-01-01 14:59:56', '2026-01-01 14:59:56', 'JHS-GEN', 'Junior High', 'active', 3),
(5, '2026-01-01 15:00:12', '2026-01-01 15:00:12', 'STEM', 'Science, Technology, Engineering and Mathematics', 'active', 4),
(6, '2026-01-01 15:00:29', '2026-01-01 15:00:29', 'BSCS', 'Bachelor of Science in Computer Science', 'active', 5);

-- --------------------------------------------------------

--
-- Table structure for table `prospectuses`
--

CREATE TABLE `prospectuses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `curriculum_id` bigint(20) UNSIGNED DEFAULT NULL,
  `academic_term_id` bigint(20) UNSIGNED DEFAULT NULL,
  `level_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subject_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `prospectuses`
--

INSERT INTO `prospectuses` (`id`, `created_at`, `updated_at`, `curriculum_id`, `academic_term_id`, `level_id`, `subject_id`, `status`) VALUES
(2, '2026-01-04 01:12:39', '2026-01-04 01:12:39', 5, 7, 16, 27, 'active'),
(3, '2026-01-04 01:12:57', '2026-01-04 01:12:57', 5, 8, 16, 28, 'active'),
(4, '2026-01-04 01:13:26', '2026-01-04 01:13:26', 5, 7, 17, 29, 'active'),
(5, '2026-01-04 01:13:54', '2026-01-04 01:13:54', 5, 8, 17, 30, 'active'),
(6, '2026-01-04 01:15:44', '2026-01-04 01:15:44', 5, 7, 18, 31, 'active'),
(7, '2026-01-04 01:17:00', '2026-01-04 01:17:00', 5, 8, 18, 32, 'active'),
(8, '2026-01-04 01:17:39', '2026-01-04 01:17:39', 5, 7, 19, 33, 'active'),
(9, '2026-01-04 01:18:05', '2026-01-04 01:18:05', 5, 8, 19, 34, 'active'),
(13, '2026-01-04 02:33:25', '2026-01-04 02:33:25', 2, 2, 4, 3, 'active'),
(14, '2026-01-04 02:39:49', '2026-01-04 02:39:49', 5, 7, 16, 28, 'active'),
(15, '2026-01-04 02:40:42', '2026-01-04 02:40:42', 5, 7, 16, 29, 'active'),
(16, '2026-01-04 02:40:51', '2026-01-04 02:40:51', 5, 7, 16, 30, 'active');

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
('a1DVUpj6DLdIF64pm4CZLcVMVB4rFI7I9ZgfNkbI', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiME9kRmcwc2lVekZJYlpvSGFmV2lCa01YNGd0V00wS3FRRUFCcFhZSCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo1OiJpbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1767581044),
('ayZe8mDorwdF4o6K1zuCOu4YSsxrqpJnOZkT05aB', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVW9uMmhTdEdhV1c2ZzI2UTRDSnpLWVVFb29SUW1JVnQzTEM5UnhybSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hY2NvdW50aW5nL2ZlZSI7czo1OiJyb3V0ZSI7czoxNDoiYWNjb3VudGluZy5mZWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=', 1767671129),
('bNgT5hMRBxGVCYvKMR6Oq574SkjyR4SAcWAe50GQ', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiV1VEb3d5TUYyVEYydTU4ZHdLZlhHVGJYYXBHWkZOdktTNjBkV1RxdCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yZWdpc3RyYXIvcHJvZ3JhbXMiO3M6NToicm91dGUiO3M6MTc6InJlZ2lzdHJhci5wcm9ncmFtIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1767588777),
('sOgu7CD7heYEOu2asgkKRHpZA5kUJVma93GE8r5G', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoid3FrNW8xMmFmUDVod3VyOHNxVGxTWGJrMkR0NVl0MlY3aVYzcld6OCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hY2NvdW50aW5nL2ZlZSI7czo1OiJyb3V0ZSI7czoxNDoiYWNjb3VudGluZy5mZWUiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=', 1767591478);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `code` text NOT NULL,
  `description` text NOT NULL,
  `unit` int(11) NOT NULL,
  `lech` int(11) NOT NULL,
  `lecu` int(11) NOT NULL,
  `labh` int(11) NOT NULL,
  `labu` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `created_at`, `updated_at`, `code`, `description`, `unit`, `lech`, `lecu`, `labh`, `labu`, `type`, `status`) VALUES
(1, '2025-12-31 19:19:19', '2025-12-31 21:44:33', 'PBL', 'Play-Based Learning', 1, 1, 1, 0, 0, 'lec', 'active'),
(2, '2025-12-31 19:26:18', '2025-12-31 21:44:28', 'WRITING', 'Writing Readiness', 1, 1, 1, 0, 0, 'lec', 'active'),
(3, '2025-12-31 20:09:43', '2025-12-31 21:44:21', 'MT', 'Mother Tongue', 1, 1, 1, 0, 0, 'lec', 'active'),
(4, '2025-12-31 20:17:44', '2025-12-31 21:44:14', 'READING', 'Basic Reading', 1, 1, 1, 0, 0, 'lec', 'active'),
(5, '2025-12-31 21:43:01', '2025-12-31 21:44:07', 'MATH 2', 'Mathematics 2', 1, 1, 1, 0, 0, 'lec', 'active'),
(6, '2025-12-31 21:43:55', '2025-12-31 21:43:55', 'SCI 2', 'Science 2', 1, 1, 1, 1, 1, 'lec lab', 'active'),
(7, '2025-12-31 21:46:21', '2025-12-31 21:48:55', 'FIL 3', 'Filipino 3', 1, 1, 1, 0, 0, 'lec', 'active'),
(8, '2025-12-31 21:49:13', '2025-12-31 21:49:13', 'ENG 3', 'English 3', 1, 1, 1, 0, 0, 'lec', 'active'),
(9, '2025-12-31 21:50:17', '2025-12-31 21:50:17', 'ESP 4', 'Edukasyon sa Pagpapakatao 4', 1, 1, 1, 0, 0, 'lec', 'active'),
(10, '2025-12-31 21:51:31', '2025-12-31 21:51:31', 'SCI 4', 'Science 4', 1, 1, 1, 1, 1, 'lec lab', 'active'),
(11, '2025-12-31 21:52:55', '2025-12-31 21:52:55', 'EPP 5', 'Edukasyong Pantahanan at Pangkabuhayan 5', 2, 1, 1, 1, 1, 'lec lab', 'active'),
(12, '2025-12-31 21:53:25', '2025-12-31 21:53:25', 'MATH 5', 'Mathematics 5', 1, 1, 1, 0, 0, 'lec', 'active'),
(13, '2025-12-31 21:54:03', '2025-12-31 21:54:03', 'FIL 6', 'Filipino 6', 1, 1, 1, 0, 0, 'lec', 'active'),
(14, '2025-12-31 21:54:30', '2025-12-31 21:54:30', 'ENG 6', 'English 6', 1, 1, 1, 0, 0, 'lec', 'active'),
(15, '2025-12-31 21:57:50', '2025-12-31 21:57:50', 'FIL 7', 'Filipino 7', 1, 1, 1, 0, 0, 'lec', 'active'),
(16, '2025-12-31 21:58:20', '2025-12-31 21:58:20', 'ENG 7', 'English 7', 1, 1, 1, 0, 0, 'lec', 'active'),
(17, '2025-12-31 21:58:57', '2025-12-31 21:58:57', 'MATH 8', 'Mathematics 8', 1, 1, 1, 1, 0, 'lec', 'active'),
(18, '2025-12-31 21:59:18', '2025-12-31 21:59:18', 'SCI 8', 'Science 8', 1, 1, 1, 0, 0, 'lec', 'active'),
(19, '2025-12-31 21:59:49', '2025-12-31 21:59:49', 'AP 9', 'Araling Panlipunan 9', 1, 1, 1, 0, 0, 'lec', 'active'),
(20, '2025-12-31 22:00:29', '2025-12-31 22:00:29', 'ESP 9', 'Edukasyon sa Pagpapakatao 9', 1, 1, 1, 0, 0, 'lec', 'active'),
(21, '2025-12-31 22:01:26', '2025-12-31 22:01:26', 'TLE 10', 'Technology and Livelihood Education 10', 2, 1, 1, 1, 1, 'lec lab', 'active'),
(22, '2025-12-31 22:03:46', '2025-12-31 22:03:46', 'MAPEH 10', 'Music, Arts, Physical Education, and Health 10', 4, 1, 2, 1, 2, 'lec lab', 'active'),
(23, '2026-01-04 00:28:41', '2026-01-04 00:28:41', 'PRECALC', 'Pre-Calculus', 2, 1, 1, 1, 1, 'lec lab', 'active'),
(24, '2026-01-04 00:29:08', '2026-01-04 00:29:08', 'GENCHEM', 'General Chemistry', 2, 1, 1, 1, 1, 'lec lab', 'active'),
(25, '2026-01-04 00:29:38', '2026-01-04 00:29:38', 'BASCALC', 'Basic Calculus', 2, 1, 1, 1, 1, 'lec lab', 'active'),
(26, '2026-01-04 00:30:05', '2026-01-04 00:30:05', 'GENPHYS', 'General Physics', 2, 1, 1, 1, 1, 'lec lab', 'active'),
(27, '2026-01-04 00:30:56', '2026-01-04 00:30:56', 'INTROCOM', 'Introduction to Computing', 3, 1, 1, 2, 2, 'lec lab', 'active'),
(28, '2026-01-04 00:31:48', '2026-01-04 00:31:48', 'FUNPROG', 'Fundamentals of Programming', 3, 1, 1, 2, 2, 'lec lab', 'active'),
(29, '2026-01-04 00:32:13', '2026-01-04 00:32:13', 'DATASTRUC', 'Data Structures', 3, 1, 1, 2, 2, 'lec lab', 'active'),
(30, '2026-01-04 00:32:34', '2026-01-04 00:32:34', 'DISCRETE', 'Discrete Mathematics', 3, 1, 1, 2, 2, 'lec lab', 'active'),
(31, '2026-01-04 00:32:53', '2026-01-04 00:32:53', 'OS', 'Operating Systems', 3, 1, 1, 2, 2, 'lec lab', 'active'),
(32, '2026-01-04 00:33:17', '2026-01-04 00:33:17', 'DBM', 'Database Management Systems', 3, 1, 1, 2, 2, 'lec lab', 'active'),
(33, '2026-01-04 00:33:41', '2026-01-04 00:33:41', 'AI', 'Artificial Intelligence', 3, 1, 1, 2, 2, 'lec lab', 'active'),
(34, '2026-01-04 00:34:11', '2026-01-04 00:34:11', 'SE', 'Software Engineering', 3, 1, 1, 2, 2, 'lec lab', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `type` varchar(200) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `type`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Registrar', 'registrar@gmail.com', 'registrar', '2026-01-03 23:05:57', '$2y$12$OdTBYzNwroQ5SLvrkcczGehRnnEkvaBBYZQ/F4N1ObegxZHHiIJ6y', NULL, '2026-01-03 23:05:57', '2026-01-03 23:05:57'),
(2, 'Accounting', 'accounting@gmail.com', 'accounting', '2026-01-03 23:05:57', '$2y$12$2aHMDnzet12URjnnSB/48uWmIRmpgPOxsiDgiAuMAJYmRLTvVnTym', NULL, '2026-01-03 23:05:57', '2026-01-03 23:05:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic_terms`
--
ALTER TABLE `academic_terms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `academic_terms_department_id_foreign` (`department_id`);

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
-- Indexes for table `curricula`
--
ALTER TABLE `curricula`
  ADD PRIMARY KEY (`id`),
  ADD KEY `curricula_department_id_foreign` (`department_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `fees`
--
ALTER TABLE `fees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fees_program_id_foreign` (`program_id`);

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
-- Indexes for table `levels`
--
ALTER TABLE `levels`
  ADD PRIMARY KEY (`id`),
  ADD KEY `levels_program_id_foreign` (`program_id`);

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
-- Indexes for table `programs`
--
ALTER TABLE `programs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `programs_department_id_foreign` (`department_id`);

--
-- Indexes for table `prospectuses`
--
ALTER TABLE `prospectuses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `prospectuses_curriculum_id_foreign` (`curriculum_id`),
  ADD KEY `prospectuses_academic_term_id_foreign` (`academic_term_id`),
  ADD KEY `prospectuses_level_id_foreign` (`level_id`),
  ADD KEY `prospectuses_subject_id_foreign` (`subject_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `academic_terms`
--
ALTER TABLE `academic_terms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `curricula`
--
ALTER TABLE `curricula`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fees`
--
ALTER TABLE `fees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `levels`
--
ALTER TABLE `levels`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `programs`
--
ALTER TABLE `programs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `prospectuses`
--
ALTER TABLE `prospectuses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `academic_terms`
--
ALTER TABLE `academic_terms`
  ADD CONSTRAINT `academic_terms_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `curricula`
--
ALTER TABLE `curricula`
  ADD CONSTRAINT `curricula_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fees`
--
ALTER TABLE `fees`
  ADD CONSTRAINT `fees_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `levels`
--
ALTER TABLE `levels`
  ADD CONSTRAINT `levels_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `programs`
--
ALTER TABLE `programs`
  ADD CONSTRAINT `programs_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `prospectuses`
--
ALTER TABLE `prospectuses`
  ADD CONSTRAINT `prospectuses_academic_term_id_foreign` FOREIGN KEY (`academic_term_id`) REFERENCES `academic_terms` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `prospectuses_curriculum_id_foreign` FOREIGN KEY (`curriculum_id`) REFERENCES `curricula` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `prospectuses_level_id_foreign` FOREIGN KEY (`level_id`) REFERENCES `levels` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `prospectuses_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
