-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for uca-nexus
CREATE DATABASE IF NOT EXISTS `uca-nexus` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `uca-nexus`;

-- Dumping structure for table uca-nexus.academic_terms
CREATE TABLE IF NOT EXISTS `academic_terms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `code` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'semester',
  `department_id` bigint unsigned DEFAULT NULL,
  `academic_year_id` bigint unsigned NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `academic_terms_department_id_foreign` (`department_id`),
  KEY `academic_terms_academic_year_id_foreign` (`academic_year_id`),
  CONSTRAINT `academic_terms_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE CASCADE,
  CONSTRAINT `academic_terms_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table uca-nexus.academic_terms: ~0 rows (approximately)
INSERT INTO `academic_terms` (`id`, `created_at`, `updated_at`, `code`, `description`, `type`, `department_id`, `academic_year_id`, `start_date`, `end_date`, `status`) VALUES
	(1, '2026-04-28 17:30:45', '2026-04-28 17:32:12', '1st 26-27', '1st Semester 2026-2027', 'semester', 1, 1, '2026-08-17', '2027-12-18', 'active'),
	(2, '2026-04-28 17:33:13', '2026-04-28 17:33:13', '2nd 26-27', '2nd Semester 2026-2027', 'semester', 1, 1, '2027-01-25', '2027-05-15', 'active'),
	(3, '2026-04-28 17:38:39', '2026-04-28 17:38:39', '1st 26-27', '1st Semester 2026-2027', 'semester', 2, 1, '2026-08-17', '2026-12-19', 'active'),
	(4, '2026-04-28 17:39:12', '2026-04-28 17:39:12', '2nd 25-26', '2nd Semester 2026-2027', 'semester', 2, 1, '2027-01-25', '2027-05-14', 'active'),
	(5, '2026-04-28 17:48:04', '2026-04-28 17:50:23', 'FULL 26-27', 'Full Year 2026 - 2027', 'full year', 3, 1, '2026-06-15', '2027-03-19', 'active'),
	(6, '2026-04-28 17:48:41', '2026-04-28 17:48:41', 'FULL 26-27', 'Full Year 2026 - 2027', 'full year', 4, 1, '2026-06-15', '2027-03-19', 'active'),
	(7, '2026-04-28 17:52:32', '2026-04-28 17:52:32', 'FULL 26-27', 'Full Year 2026 - 2027', 'full year', 5, 1, '2026-06-15', '2027-03-19', 'active');

-- Dumping structure for table uca-nexus.academic_years
CREATE TABLE IF NOT EXISTS `academic_years` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `code` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_year` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `end_year` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table uca-nexus.academic_years: ~0 rows (approximately)
INSERT INTO `academic_years` (`id`, `created_at`, `updated_at`, `code`, `description`, `start_year`, `end_year`, `status`) VALUES
	(1, '2026-04-28 17:21:56', '2026-04-28 17:21:56', 'SY 26-27', '2026 - 2027', '2026', '2027', 'active'),
	(2, '2026-04-28 17:22:30', '2026-04-28 17:22:30', 'SY 27-28', '2027 - 2028', '2027', '2028', 'inactive'),
	(3, '2026-04-28 17:28:42', '2026-04-28 17:29:17', 'SY 28-29', '2028 - 2029', '2028', '2029', 'inactive'),
	(4, '2026-04-28 17:29:11', '2026-04-28 17:29:11', 'SY 29-30', '2029 - 2030', '2029', '2030', 'inactive');

-- Dumping structure for table uca-nexus.admissions
CREATE TABLE IF NOT EXISTS `admissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `applicant_id` bigint unsigned NOT NULL,
  `interview_schedule_id` bigint unsigned NOT NULL,
  `interview_score` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `interview_remark` text COLLATE utf8mb4_unicode_ci,
  `interview_result` enum('passed','failed','pending') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `exam_schedule_id` bigint unsigned NOT NULL,
  `math_score` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `science_score` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `english_score` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `filipino_score` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `abstract_score` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `exam_score` text COLLATE utf8mb4_unicode_ci,
  `exam_result` enum('passed','failed','pending') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `final_score` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `decision` enum('accepted','rejected','pending') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `program_id` bigint unsigned DEFAULT NULL,
  `evaluation_schedule_id` bigint unsigned NOT NULL,
  `evaluated_by` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `evaluated_at` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `admissions_applicant_id_foreign` (`applicant_id`),
  KEY `admissions_interview_schedule_id_foreign` (`interview_schedule_id`),
  KEY `admissions_exam_schedule_id_foreign` (`exam_schedule_id`),
  KEY `admissions_program_id_foreign` (`program_id`),
  KEY `admissions_evaluation_schedule_id_foreign` (`evaluation_schedule_id`),
  CONSTRAINT `admissions_applicant_id_foreign` FOREIGN KEY (`applicant_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `admissions_evaluation_schedule_id_foreign` FOREIGN KEY (`evaluation_schedule_id`) REFERENCES `schedules` (`id`) ON DELETE CASCADE,
  CONSTRAINT `admissions_exam_schedule_id_foreign` FOREIGN KEY (`exam_schedule_id`) REFERENCES `schedules` (`id`) ON DELETE CASCADE,
  CONSTRAINT `admissions_interview_schedule_id_foreign` FOREIGN KEY (`interview_schedule_id`) REFERENCES `schedules` (`id`) ON DELETE CASCADE,
  CONSTRAINT `admissions_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table uca-nexus.admissions: ~11 rows (approximately)
INSERT INTO `admissions` (`id`, `created_at`, `updated_at`, `applicant_id`, `interview_schedule_id`, `interview_score`, `interview_remark`, `interview_result`, `exam_schedule_id`, `math_score`, `science_score`, `english_score`, `filipino_score`, `abstract_score`, `exam_score`, `exam_result`, `final_score`, `decision`, `program_id`, `evaluation_schedule_id`, `evaluated_by`, `evaluated_at`) VALUES
	(1, '2026-04-28 20:54:57', '2026-04-28 20:58:31', 1, 1, NULL, NULL, 'pending', 1, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', '0', 'accepted', 5, 1, 'Admissions', '2026-04-29 04:58:31'),
	(2, '2026-04-28 20:54:57', '2026-04-28 20:58:39', 2, 1, NULL, NULL, 'pending', 1, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', '0', 'accepted', 5, 1, 'Admissions', '2026-04-29 04:58:39'),
	(3, '2026-04-28 20:54:57', '2026-04-28 20:58:47', 3, 1, NULL, NULL, 'pending', 1, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', '0', 'accepted', 5, 1, 'Admissions', '2026-04-29 04:58:47'),
	(4, '2026-04-28 20:54:57', '2026-04-28 20:59:32', 4, 1, NULL, NULL, 'pending', 1, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', '0', 'accepted', 3, 1, 'Admissions', '2026-04-29 04:59:32'),
	(5, '2026-04-28 20:54:57', '2026-04-28 20:54:57', 5, 1, NULL, NULL, 'pending', 1, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', '0', 'accepted', 4, 1, 'Admissions', '2026-04-29 04:57:00'),
	(6, '2026-04-28 20:54:57', '2026-04-28 20:54:57', 6, 1, NULL, NULL, 'pending', 1, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', '0', 'accepted', 4, 1, 'Admissions', '2026-04-29 04:57:00'),
	(7, '2026-04-28 20:54:57', '2026-04-28 20:57:00', 7, 1, NULL, NULL, 'pending', 1, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', '0', 'accepted', 4, 1, 'Admissions', '2026-04-29 04:57:00'),
	(8, '2026-04-28 20:54:57', '2026-04-28 20:54:57', 8, 1, NULL, NULL, 'pending', 1, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', '0', 'accepted', 1, 1, 'Admissions', '2026-04-29 04:56:13'),
	(9, '2026-04-28 20:54:57', '2026-04-28 20:54:57', 9, 1, NULL, NULL, 'pending', 1, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', '0', 'accepted', 1, 1, 'Admissions', '2026-04-29 04:56:13'),
	(10, '2026-04-28 20:54:57', '2026-04-28 20:54:57', 10, 1, NULL, NULL, 'pending', 1, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', '0', 'accepted', 1, 1, 'Admissions', '2026-04-29 04:56:13'),
	(11, '2026-04-28 20:54:57', '2026-04-28 20:56:13', 11, 1, NULL, NULL, 'pending', 1, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', '0', 'accepted', 1, 1, 'Admissions', '2026-04-29 04:56:13');

-- Dumping structure for table uca-nexus.applicants
CREATE TABLE IF NOT EXISTS `applicants` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `application_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `student_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year_level` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `strand` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_program_choice` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `second_program_choice` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `third_program_choice` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `middle_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sex` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `citizenship` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `religion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `birthdate` date NOT NULL,
  `place_of_birth` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `civil_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zip_code` int NOT NULL,
  `present_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `permanent_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mother_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mother_occupation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mother_contact_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mother_monthly_income` int NOT NULL,
  `father_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `father_occupation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `father_contact_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `father_monthly_income` int NOT NULL,
  `guardian_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guardian_occupation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guardian_contact_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guardian_monthly_income` int NOT NULL,
  `elementary_school_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `elementary_school_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `elementary_inclusive_years` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `junior_school_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `junior_school_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `junior_inclusive_years` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `senior_school_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `senior_school_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `senior_inclusive_years` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `college_school_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `college_school_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `college_inclusive_years` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lrn` int NOT NULL,
  `status` enum('pending','interview','exam','evaluation','rejected','accepted','admitted') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `academic_year` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reject_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `applicants_application_no_unique` (`application_no`),
  UNIQUE KEY `applicants_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table uca-nexus.applicants: ~11 rows (approximately)
INSERT INTO `applicants` (`id`, `created_at`, `updated_at`, `application_no`, `level`, `student_type`, `year_level`, `strand`, `first_program_choice`, `second_program_choice`, `third_program_choice`, `last_name`, `first_name`, `middle_name`, `sex`, `citizenship`, `religion`, `birthdate`, `place_of_birth`, `civil_status`, `zip_code`, `present_address`, `permanent_address`, `telephone_number`, `mobile_number`, `email`, `mother_name`, `mother_occupation`, `mother_contact_number`, `mother_monthly_income`, `father_name`, `father_occupation`, `father_contact_number`, `father_monthly_income`, `guardian_name`, `guardian_occupation`, `guardian_contact_number`, `guardian_monthly_income`, `elementary_school_name`, `elementary_school_address`, `elementary_inclusive_years`, `junior_school_name`, `junior_school_address`, `junior_inclusive_years`, `senior_school_name`, `senior_school_address`, `senior_inclusive_years`, `college_school_name`, `college_school_address`, `college_inclusive_years`, `lrn`, `status`, `academic_year`, `reject_reason`) VALUES
	(1, '2026-04-28 20:25:22', '2026-04-28 20:59:09', '20260429120522026', 'Junior High School', 'new', 'Grade 7', NULL, NULL, NULL, NULL, 'Ponse', 'Juanito', 'Reyes', 'male', 'Filipino', 'Catholic', '2008-02-05', 'Lipa City', 'single', 4224, 'Tangob, Padre Garcia Batangas', 'Tangob, Padre Garcia Batangas', 'N/A', '09937006750', 'markanthonylina05@gmail.com', 'Juanita Reyes Ponse', 'Housewife', '09937006750', 100000, 'Peter Merionette Smith Ponse', 'Surgeon Doctor', '09937006750', 1000000, 'Juanita Reyes Ponse', 'Housewife', '09937006750', 100000, 'Tangob Elementray School', 'Tangob, Padre Garcia Batangas', '2012-2018', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 12345678, 'admitted', '2026 - 2027', NULL),
	(2, '2026-03-18 01:55:34', '2026-04-28 20:59:13', '2026031800000001', 'Junior High School', 'new', 'Grade 7', NULL, '', '', '', 'Navarro', 'Luis', 'Reyes', 'male', 'Filipino', 'Christian', '2005-01-01', 'Quezon City', 'single', 1100, '21 Mabini St., Quezon City', '21 Mabini St., Quezon City', 'N/A', '09181000001', 'luis.navarro1@email.com', 'Maria Reyes Navarro', 'Teacher', '09172000001', 25100, 'Jose Navarro', 'Driver', '09193000001', 18090, 'Maria Reyes Navarro', 'Teacher', '09172000001', 25100, 'QC Central Elementary School', 'Quezon City', '2012-2018', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 201200001, 'admitted', '2026 - 2027', NULL),
	(3, '2026-03-18 01:55:34', '2026-04-28 20:59:15', '2026031800000002', 'Junior High School', 'transferee', 'Grade 9', NULL, '', '', '', 'Dela Cruz', 'Andrea', 'Garcia', 'female', 'Filipino', 'Christian', '2006-02-02', 'Quezon City', 'single', 1100, '22 Mabini St., Quezon City', '22 Mabini St., Quezon City', 'N/A', '09181000002', 'andrea.delacruz2@email.com', 'Maria Garcia Dela Cruz', 'Teacher', '09172000002', 25200, 'Jose Dela Cruz', 'Driver', '09193000002', 18180, 'Maria Garcia Dela Cruz', 'Teacher', '09172000002', 25200, 'QC Central Elementary School', 'Quezon City', '2012-2018', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 201200002, 'admitted', '2026 - 2027', NULL),
	(4, '2026-03-18 01:55:34', '2026-04-28 21:00:02', '2026031800000003', 'Senior High School', 'new', 'Grade 11', '3', '', '', '', 'Torres', 'Mark', 'Torres', 'male', 'Filipino', 'Christian', '2007-03-03', 'Quezon City', 'single', 1100, '23 Mabini St., Quezon City', '23 Mabini St., Quezon City', 'N/A', '09181000003', 'mark.torres3@email.com', 'Maria Torres Torres', 'Teacher', '09172000003', 25300, 'Jose Torres', 'Driver', '09193000003', 18270, 'Maria Torres Torres', 'Teacher', '09172000003', 25300, 'QC Central Elementary School', 'Quezon City', '2012-2018', 'Quezon City National High School', 'Quezon City', '2018-2022', 'Pasig City Senior High School', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 201200003, 'admitted', '2026 - 2027', NULL),
	(5, '2026-03-18 01:55:34', '2026-04-28 21:00:02', '2026031800000004', 'Senior High School', 'new', 'Grade 11', '3', '', '', '', 'Ramos', 'Janelle', 'Mendoza', 'female', 'Filipino', 'Christian', '2005-04-04', 'Quezon City', 'single', 1100, '24 Mabini St., Quezon City', '24 Mabini St., Quezon City', 'N/A', '09181000004', 'janelle.ramos4@email.com', 'Maria Mendoza Ramos', 'Teacher', '09172000004', 25400, 'Jose Ramos', 'Driver', '09193000004', 18360, 'Maria Mendoza Ramos', 'Teacher', '09172000004', 25400, 'QC Central Elementary School', 'Quezon City', '2012-2018', 'Quezon City National High School', 'Quezon City', '2018-2022', 'Makati Senior High School', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 201200004, 'admitted', '2026 - 2027', NULL),
	(6, '2026-03-18 01:55:34', '2026-04-28 21:00:02', '2026031800000005', 'Senior High School', 'new', 'Grade 11', '3', '', '', '', 'Bautista', 'Paolo', 'Marquez', 'male', 'Filipino', 'Christian', '2006-05-05', 'Quezon City', 'single', 1100, '25 Mabini St., Quezon City', '25 Mabini St., Quezon City', 'N/A', '09181000005', 'paolo.bautista5@email.com', 'Maria Marquez Bautista', 'Teacher', '09172000005', 25500, 'Jose Bautista', 'Driver', '09193000005', 18450, 'Maria Marquez Bautista', 'Teacher', '09172000005', 25500, 'QC Central Elementary School', 'Quezon City', '2012-2018', 'Quezon City National High School', 'Quezon City', '2018-2022', 'Rizal National High School', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 201200005, 'admitted', '2026 - 2027', NULL),
	(7, '2026-03-18 01:55:34', '2026-04-28 20:59:16', '2026031800000006', 'Senior High School', 'transferee', 'Grade 12', '4', '', '', '', 'Morales', 'Katrina', 'Reyes', 'female', 'Filipino', 'Christian', '2007-06-06', 'Quezon City', 'single', 1100, '26 Mabini St., Quezon City', '26 Mabini St., Quezon City', 'N/A', '09181000006', 'katrina.morales6@email.com', 'Maria Reyes Morales', 'Teacher', '09172000006', 25600, 'Jose Morales', 'Driver', '09193000006', 18540, 'Maria Reyes Morales', 'Teacher', '09172000006', 25600, 'QC Central Elementary School', 'Quezon City', '2012-2018', 'Quezon City National High School', 'Quezon City', '2018-2022', 'Caloocan City Senior High School', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 201200006, 'admitted', '2026 - 2027', NULL),
	(8, '2026-03-18 01:55:34', '2026-04-28 20:59:16', '2026031800000007', 'College', 'new', '1st Year', NULL, '1', '2', '2', 'Salazar', 'Joshua', 'Garcia', 'male', 'Filipino', 'Christian', '2005-07-07', 'Quezon City', 'single', 1100, '27 Mabini St., Quezon City', '27 Mabini St., Quezon City', 'N/A', '09181000007', 'joshua.salazar7@email.com', 'Maria Garcia Salazar', 'Teacher', '09172000007', 25700, 'Jose Salazar', 'Driver', '09193000007', 18630, 'Maria Garcia Salazar', 'Teacher', '09172000007', 25700, 'QC Central Elementary School', 'Quezon City', '2012-2018', 'Quezon City National High School', 'Quezon City', '2018-2022', 'Taguig Integrated High School', 'Taguig City', '2022-2024', 'N/A', 'N/A', 'N/A', 201200007, 'admitted', '2026 - 2027', NULL),
	(9, '2026-03-18 01:55:34', '2026-04-28 20:59:16', '2026031800000008', 'College', 'new', '1st Year', NULL, '1', '2', '2', 'Reyes', 'Angela', 'Torres', 'female', 'Filipino', 'Christian', '2006-08-08', 'Quezon City', 'single', 1100, '28 Mabini St., Quezon City', '28 Mabini St., Quezon City', 'N/A', '09181000008', 'angela.reyes8@email.com', 'Maria Torres Reyes', 'Teacher', '09172000008', 25800, 'Jose Reyes', 'Driver', '09193000008', 18720, 'Maria Torres Reyes', 'Teacher', '09172000008', 25800, 'QC Central Elementary School', 'Quezon City', '2012-2018', 'Quezon City National High School', 'Quezon City', '2018-2022', 'Quezon City National High School', 'Quezon City', '2022-2024', 'N/A', 'N/A', 'N/A', 201200008, 'admitted', '2026 - 2027', NULL),
	(10, '2026-03-18 01:55:34', '2026-04-28 20:59:17', '2026031800000009', 'College', 'new', '1st Year', NULL, '1', '2', '2', 'Garcia', 'Ramon', 'Mendoza', 'male', 'Filipino', 'Christian', '2007-09-09', 'Quezon City', 'single', 1100, '29 Mabini St., Quezon City', '29 Mabini St., Quezon City', 'N/A', '09181000009', 'ramon.garcia9@email.com', 'Maria Mendoza Garcia', 'Teacher', '09172000009', 25900, 'Jose Garcia', 'Driver', '09193000009', 18810, 'Maria Mendoza Garcia', 'Teacher', '09172000009', 25900, 'QC Central Elementary School', 'Quezon City', '2012-2018', 'Quezon City National High School', 'Quezon City', '2018-2022', 'Manila Science High School', 'Manila', '2022-2024', 'N/A', 'N/A', 'N/A', 201200009, 'admitted', '2026 - 2027', NULL),
	(11, '2026-03-18 01:55:34', '2026-04-28 20:59:17', '2026031800000010', 'College', 'new', '1st Year', NULL, '1', '2', '2', 'Castro', 'Bianca', 'Marquez', 'female', 'Filipino', 'Christian', '2005-10-10', 'Quezon City', 'single', 1100, '30 Mabini St., Quezon City', '30 Mabini St., Quezon City', 'N/A', '09181000010', 'bianca.castro10@email.com', 'Maria Marquez Castro', 'Teacher', '09172000010', 26000, 'Jose Castro', 'Driver', '09193000010', 18900, 'Maria Marquez Castro', 'Teacher', '09172000010', 26000, 'QC Central Elementary School', 'Quezon City', '2012-2018', 'Quezon City National High School', 'Quezon City', '2018-2022', 'Pasig City Senior High School', 'Pasig City', '2022-2024', 'N/A', 'N/A', 'N/A', 201200010, 'admitted', '2026 - 2027', NULL);

-- Dumping structure for table uca-nexus.assessment_histories
CREATE TABLE IF NOT EXISTS `assessment_histories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `academic_term_id` bigint unsigned NOT NULL,
  `date_printed` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `assessment_histories_student_id_foreign` (`student_id`),
  KEY `assessment_histories_academic_term_id_foreign` (`academic_term_id`),
  CONSTRAINT `assessment_histories_academic_term_id_foreign` FOREIGN KEY (`academic_term_id`) REFERENCES `academic_terms` (`id`) ON DELETE CASCADE,
  CONSTRAINT `assessment_histories_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table uca-nexus.assessment_histories: ~0 rows (approximately)

-- Dumping structure for table uca-nexus.assessment_history_enlistments
CREATE TABLE IF NOT EXISTS `assessment_history_enlistments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `assessment_history_id` bigint unsigned NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `units` decimal(5,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `assessment_history_enlistments_assessment_history_id_foreign` (`assessment_history_id`),
  CONSTRAINT `assessment_history_enlistments_assessment_history_id_foreign` FOREIGN KEY (`assessment_history_id`) REFERENCES `assessment_histories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table uca-nexus.assessment_history_enlistments: ~0 rows (approximately)

-- Dumping structure for table uca-nexus.assessment_history_fees
CREATE TABLE IF NOT EXISTS `assessment_history_fees` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `assessment_history_id` bigint unsigned NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `assessment_history_fees_assessment_history_id_foreign` (`assessment_history_id`),
  CONSTRAINT `assessment_history_fees_assessment_history_id_foreign` FOREIGN KEY (`assessment_history_id`) REFERENCES `assessment_histories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table uca-nexus.assessment_history_fees: ~0 rows (approximately)

-- Dumping structure for table uca-nexus.assessment_history_students
CREATE TABLE IF NOT EXISTS `assessment_history_students` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `assessment_history_id` bigint unsigned NOT NULL,
  `student_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `year_level` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `program` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `assessment_history_students_assessment_history_id_foreign` (`assessment_history_id`),
  CONSTRAINT `assessment_history_students_assessment_history_id_foreign` FOREIGN KEY (`assessment_history_id`) REFERENCES `assessment_histories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table uca-nexus.assessment_history_students: ~0 rows (approximately)

-- Dumping structure for table uca-nexus.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table uca-nexus.cache: ~0 rows (approximately)

-- Dumping structure for table uca-nexus.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table uca-nexus.cache_locks: ~0 rows (approximately)

-- Dumping structure for table uca-nexus.curricula
CREATE TABLE IF NOT EXISTS `curricula` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `curriculum` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `curricula_department_id_foreign` (`department_id`),
  CONSTRAINT `curricula_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table uca-nexus.curricula: ~0 rows (approximately)
INSERT INTO `curricula` (`id`, `created_at`, `updated_at`, `curriculum`, `status`, `department_id`) VALUES
	(1, '2026-04-28 17:16:28', '2026-04-28 17:16:47', '2012 - 13', 'active', 1),
	(2, '2026-04-28 17:17:01', '2026-04-28 17:17:01', '2013 - 14', 'active', 2),
	(3, '2026-04-28 17:17:23', '2026-04-28 17:17:23', '2023 - 24', 'active', 3),
	(4, '2026-04-28 17:17:34', '2026-04-28 17:17:34', '2023 - 24', 'active', 4),
	(5, '2026-04-28 17:18:09', '2026-04-28 17:18:09', '2013 - 14', 'active', 5);

-- Dumping structure for table uca-nexus.departments
CREATE TABLE IF NOT EXISTS `departments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `code` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `education_level` enum('K12','college') COLLATE utf8mb4_unicode_ci DEFAULT 'college',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table uca-nexus.departments: ~0 rows (approximately)
INSERT INTO `departments` (`id`, `created_at`, `updated_at`, `code`, `description`, `education_level`, `status`) VALUES
	(1, '2026-04-28 16:08:48', '2026-04-28 16:08:48', 'COL', 'College', 'college', 'active'),
	(2, '2026-04-28 16:18:13', '2026-04-28 16:18:13', 'SHS', 'Senior High School', 'K12', 'active'),
	(3, '2026-04-28 16:18:35', '2026-04-28 16:18:35', 'JHS', 'Junior High School', 'K12', 'active'),
	(4, '2026-04-28 16:20:11', '2026-04-28 16:20:11', 'ELEM', 'Elementary', 'K12', 'active'),
	(5, '2026-04-28 16:20:34', '2026-04-28 16:20:34', 'PRE', 'Pre-Elementary', 'K12', 'active');

-- Dumping structure for table uca-nexus.enlistments
CREATE TABLE IF NOT EXISTS `enlistments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `academic_term_id` bigint unsigned NOT NULL,
  `subject_offering_id` bigint unsigned NOT NULL,
  `final_grade` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `enlistments_student_id_foreign` (`student_id`),
  KEY `enlistments_academic_term_id_foreign` (`academic_term_id`),
  KEY `enlistments_subject_offering_id_foreign` (`subject_offering_id`),
  CONSTRAINT `enlistments_academic_term_id_foreign` FOREIGN KEY (`academic_term_id`) REFERENCES `academic_terms` (`id`) ON DELETE CASCADE,
  CONSTRAINT `enlistments_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  CONSTRAINT `enlistments_subject_offering_id_foreign` FOREIGN KEY (`subject_offering_id`) REFERENCES `subject_offerings` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table uca-nexus.enlistments: ~0 rows (approximately)

-- Dumping structure for table uca-nexus.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table uca-nexus.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table uca-nexus.fees
CREATE TABLE IF NOT EXISTS `fees` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(8,2) NOT NULL,
  `type` text COLLATE utf8mb4_unicode_ci,
  `month_to_pay` double DEFAULT NULL,
  `group` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `program_id` bigint unsigned NOT NULL,
  `academic_term_id` bigint unsigned DEFAULT NULL,
  `student_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fees_program_id_foreign` (`program_id`),
  KEY `fees_academic_term_id_foreign` (`academic_term_id`),
  KEY `fees_student_id_foreign` (`student_id`),
  CONSTRAINT `fees_academic_term_id_foreign` FOREIGN KEY (`academic_term_id`) REFERENCES `academic_terms` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fees_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fees_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table uca-nexus.fees: ~0 rows (approximately)

-- Dumping structure for table uca-nexus.grades
CREATE TABLE IF NOT EXISTS `grades` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `student_id` bigint unsigned DEFAULT NULL,
  `teacher_id` bigint unsigned DEFAULT NULL,
  `term_id` bigint unsigned DEFAULT NULL,
  `grading_period` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ww_whole` decimal(5,2) DEFAULT NULL,
  `pt_whole` decimal(5,2) DEFAULT NULL,
  `qa_whole` decimal(5,2) DEFAULT NULL,
  `ww_total` decimal(5,2) DEFAULT NULL,
  `pt_total` decimal(5,2) DEFAULT NULL,
  `qa_total` decimal(5,2) DEFAULT NULL,
  `ww_percent` decimal(5,2) DEFAULT NULL,
  `pt_percent` decimal(5,2) DEFAULT NULL,
  `qa_percent` decimal(5,2) DEFAULT NULL,
  `initial_grade` decimal(5,2) DEFAULT NULL,
  `period_grade` decimal(5,2) DEFAULT NULL,
  `status` enum('pending','draft','submitted','approved','finalized') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `is_direct_input` tinyint(1) NOT NULL DEFAULT '1',
  `submtted_at` datetime NOT NULL,
  `approved_by` datetime NOT NULL,
  `approved_at` datetime NOT NULL,
  `fnalized_at` datetime NOT NULL,
  `remarks` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `grades_student_id_foreign` (`student_id`),
  KEY `grades_teacher_id_foreign` (`teacher_id`),
  KEY `grades_term_id_foreign` (`term_id`),
  CONSTRAINT `grades_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE SET NULL,
  CONSTRAINT `grades_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `grades_term_id_foreign` FOREIGN KEY (`term_id`) REFERENCES `academic_terms` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table uca-nexus.grades: ~0 rows (approximately)

-- Dumping structure for table uca-nexus.grade_columns
CREATE TABLE IF NOT EXISTS `grade_columns` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `teacher_id` bigint unsigned DEFAULT NULL,
  `offering_id` bigint unsigned DEFAULT NULL,
  `academic_term_id` bigint unsigned DEFAULT NULL,
  `grading_period` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `component_type` enum('ww','pt','qa') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ww',
  `column_number` int NOT NULL,
  `highest_possible_score` decimal(8,2) NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `grade_columns_teacher_id_foreign` (`teacher_id`),
  KEY `grade_columns_offering_id_foreign` (`offering_id`),
  KEY `grade_columns_academic_term_id_foreign` (`academic_term_id`),
  CONSTRAINT `grade_columns_academic_term_id_foreign` FOREIGN KEY (`academic_term_id`) REFERENCES `academic_terms` (`id`) ON DELETE SET NULL,
  CONSTRAINT `grade_columns_offering_id_foreign` FOREIGN KEY (`offering_id`) REFERENCES `subject_offerings` (`id`) ON DELETE SET NULL,
  CONSTRAINT `grade_columns_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table uca-nexus.grade_columns: ~0 rows (approximately)

-- Dumping structure for table uca-nexus.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table uca-nexus.jobs: ~0 rows (approximately)

-- Dumping structure for table uca-nexus.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table uca-nexus.job_batches: ~0 rows (approximately)

-- Dumping structure for table uca-nexus.levels
CREATE TABLE IF NOT EXISTS `levels` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `code` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `program_id` bigint unsigned NOT NULL,
  `order` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `levels_program_id_foreign` (`program_id`),
  CONSTRAINT `levels_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table uca-nexus.levels: ~0 rows (approximately)
INSERT INTO `levels` (`id`, `created_at`, `updated_at`, `code`, `description`, `program_id`, `order`) VALUES
	(1, '2026-04-28 16:53:11', '2026-04-28 16:53:11', 'BSN 1', '1st Year', 1, 1),
	(2, '2026-04-28 16:54:02', '2026-04-28 16:54:02', 'BSN 2', '2nd Year', 1, 2),
	(3, '2026-04-28 16:54:20', '2026-04-28 16:54:20', 'BSN 3', '3rd Year', 1, 3),
	(4, '2026-04-28 16:54:32', '2026-04-28 16:54:32', 'BSN 4', '4th Year', 1, 4),
	(5, '2026-04-28 16:54:59', '2026-04-28 16:54:59', 'BSRT 1', '1st Year', 2, 1),
	(6, '2026-04-28 16:55:12', '2026-04-28 16:55:12', 'BSRT 2', '2nd Year', 2, 2),
	(7, '2026-04-28 16:55:30', '2026-04-28 16:55:30', 'BSRT 3', '3rd Year', 2, 3),
	(8, '2026-04-28 16:55:43', '2026-04-28 16:55:43', 'BSRT 4', '4th Year', 2, 4),
	(9, '2026-04-28 16:55:59', '2026-04-28 16:55:59', 'STEM 11', 'Grade 11', 3, 1),
	(10, '2026-04-28 16:56:19', '2026-04-28 16:56:19', 'STEM 12', 'Grade 12', 3, 2),
	(11, '2026-04-28 16:56:33', '2026-04-28 16:56:33', 'ABM 11', 'Grade 11', 4, 1),
	(12, '2026-04-28 16:56:47', '2026-04-28 16:56:47', 'ABM 12', 'Grade 12', 4, 2),
	(13, '2026-04-28 16:57:26', '2026-04-28 16:57:26', 'G7', 'Grade 7', 5, 1),
	(14, '2026-04-28 16:57:42', '2026-04-28 16:57:42', 'G8', 'Grade 8', 5, 2),
	(15, '2026-04-28 16:57:53', '2026-04-28 16:57:53', 'G9', 'Grade 9', 5, 3),
	(16, '2026-04-28 16:58:02', '2026-04-28 16:58:02', 'G10', 'Grade 10', 5, 4),
	(17, '2026-04-28 16:58:16', '2026-04-28 16:58:16', 'G1', 'Grade 1', 6, 1),
	(18, '2026-04-28 16:58:25', '2026-04-28 16:58:25', 'G2', 'Grade 2', 6, 2),
	(19, '2026-04-28 16:58:35', '2026-04-28 16:58:35', 'G3', 'Grade 3', 6, 3),
	(20, '2026-04-28 16:58:46', '2026-04-28 16:58:46', 'G4', 'Grade 4', 6, 4),
	(21, '2026-04-28 16:59:04', '2026-04-28 16:59:04', 'G5', 'Grade 5', 6, 5),
	(22, '2026-04-28 16:59:13', '2026-04-28 16:59:13', 'G6', 'Grade 6', 6, 6),
	(23, '2026-04-28 16:59:29', '2026-04-28 16:59:29', 'KIN 1', 'Kindergarten 1', 7, 1);

-- Dumping structure for table uca-nexus.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table uca-nexus.migrations: ~1 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2025_12_06_162706_academic_year', 1),
	(5, '2025_12_06_162707_departments_table', 1),
	(6, '2025_12_06_162717_programs_table', 1),
	(7, '2025_12_06_162834_curricula_table', 1),
	(8, '2025_12_06_162844_subjects_table', 1),
	(9, '2025_12_06_162910_semesters_table', 1),
	(10, '2025_12_06_162911_levels_table', 1),
	(11, '2025_12_06_162912_prospectuses_table', 1),
	(12, '2026_01_06_052703_create_applicants_table', 1),
	(13, '2026_01_10_000000_create_schedules_table', 1),
	(14, '2026_01_13_041308_create_admissions_table', 1),
	(15, '2026_01_19_000001_create_students_table', 1),
	(16, '2026_01_19_000002_create_student_contacts_table', 1),
	(17, '2026_01_19_000003_create_student_guardians_table', 1),
	(18, '2026_01_19_000004_create_student_academic_histories_table', 1),
	(19, '2026_01_19_000005_fees_table', 1),
	(20, '2026_02_11_000001_create_subject_offerings_table', 1),
	(21, '2026_02_11_000002_create_enlistments_table', 1),
	(22, '2026_02_17_000002_create_student_fees_table', 1),
	(23, '2026_02_18_030100_create_payment_accounts_table', 1),
	(24, '2026_02_18_030200_create_payment_types_table', 1),
	(25, '2026_02_18_042948_create_transactions_table', 1),
	(26, '2026_02_19_000001_create_assessment_histories_table', 1),
	(27, '2026_02_19_000002_create_assessment_history_enlistments_table', 1),
	(28, '2026_02_19_000003_create_assessment_history_fees_table', 1),
	(29, '2026_02_19_000004_create_assessment_history_students_table', 1),
	(30, '2026_02_28_133704_create_statuses_table', 1),
	(31, '2026_03_03_002338_create_student_accounts_table', 1),
	(32, '2026_03_04_113104_drop_account_status_from_students_table', 1),
	(33, '2026_03_04_113547_add_examination_permit_to_student_accounts_table', 1),
	(34, '2026_03_12_083701_create_table_website', 1),
	(35, '2026_03_12_123351_add_embedded_url_to_websites_table', 1),
	(36, '2026_03_16_000001_add_user_foreign_ids_to_users_table', 1),
	(37, '2026_03_18_130000_make_admission_scores_nullable', 1),
	(38, '2026_04_28_032637_teachers_table', 1),
	(39, '2026_04_28_032648_teachers_accounts', 1),
	(40, '2026_04_28_032658_teacher_offering', 1),
	(41, '2026_04_28_033524_grade_columns', 1),
	(42, '2026_04_28_033533_raw_scores', 1),
	(43, '2026_04_28_044613_grades', 1);

-- Dumping structure for table uca-nexus.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table uca-nexus.password_reset_tokens: ~0 rows (approximately)

-- Dumping structure for table uca-nexus.payment_accounts
CREATE TABLE IF NOT EXISTS `payment_accounts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table uca-nexus.payment_accounts: ~0 rows (approximately)

-- Dumping structure for table uca-nexus.payment_types
CREATE TABLE IF NOT EXISTS `payment_types` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table uca-nexus.payment_types: ~0 rows (approximately)

-- Dumping structure for table uca-nexus.programs
CREATE TABLE IF NOT EXISTS `programs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `code` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enrollment_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `department_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `programs_department_id_foreign` (`department_id`),
  CONSTRAINT `programs_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table uca-nexus.programs: ~7 rows (approximately)
INSERT INTO `programs` (`id`, `created_at`, `updated_at`, `code`, `description`, `status`, `enrollment_type`, `department_id`) VALUES
	(1, '2026-04-28 16:21:23', '2026-04-28 16:21:23', 'BSN', 'BS Nursing', 'active', 'semester', 1),
	(2, '2026-04-28 16:22:32', '2026-04-28 16:22:32', 'BSRT', 'BS Radiologic Technology', 'active', 'semester', 1),
	(3, '2026-04-28 16:42:04', '2026-04-28 16:42:04', 'STEM', 'Science, Technology, Engineering, and Mathematics', 'active', 'semester', 2),
	(4, '2026-04-28 16:42:57', '2026-04-28 16:42:57', 'ABM', 'Accounting, Business, and Management', 'active', 'semester', 2),
	(5, '2026-04-28 16:47:38', '2026-04-28 16:47:38', 'JHS', 'Junior High School', 'active', 'yearly', 3),
	(6, '2026-04-28 16:48:07', '2026-04-28 16:48:07', 'GRADE', 'Grade School', 'active', 'yearly', 4),
	(7, '2026-04-28 16:52:07', '2026-04-28 16:52:07', 'KIN', 'Kindergarten', 'active', 'yearly', 5);

-- Dumping structure for table uca-nexus.prospectuses
CREATE TABLE IF NOT EXISTS `prospectuses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `curriculum_id` bigint unsigned DEFAULT NULL,
  `level_id` bigint unsigned DEFAULT NULL,
  `term_id` bigint unsigned DEFAULT NULL,
  `subject_id` bigint unsigned DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `prospectuses_curriculum_id_foreign` (`curriculum_id`),
  KEY `prospectuses_level_id_foreign` (`level_id`),
  KEY `prospectuses_term_id_foreign` (`term_id`),
  KEY `prospectuses_subject_id_foreign` (`subject_id`),
  CONSTRAINT `prospectuses_curriculum_id_foreign` FOREIGN KEY (`curriculum_id`) REFERENCES `curricula` (`id`) ON DELETE SET NULL,
  CONSTRAINT `prospectuses_level_id_foreign` FOREIGN KEY (`level_id`) REFERENCES `levels` (`id`) ON DELETE SET NULL,
  CONSTRAINT `prospectuses_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE SET NULL,
  CONSTRAINT `prospectuses_term_id_foreign` FOREIGN KEY (`term_id`) REFERENCES `academic_terms` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table uca-nexus.prospectuses: ~0 rows (approximately)
INSERT INTO `prospectuses` (`id`, `created_at`, `updated_at`, `curriculum_id`, `level_id`, `term_id`, `subject_id`, `status`) VALUES
	(1, '2026-04-28 18:38:30', '2026-04-28 18:38:30', 1, 1, 1, 1, 'active'),
	(2, '2026-04-28 19:05:06', '2026-04-28 19:05:06', 1, 1, 2, 1, 'active'),
	(3, '2026-04-28 19:05:23', '2026-04-28 19:05:23', 1, 2, 1, 1, 'active'),
	(4, '2026-04-28 19:05:27', '2026-04-28 19:05:27', 1, 2, 2, 1, 'active'),
	(5, '2026-04-28 19:05:33', '2026-04-28 19:05:33', 1, 3, 1, 1, 'active'),
	(6, '2026-04-28 19:05:36', '2026-04-28 19:05:36', 1, 3, 2, 1, 'active'),
	(7, '2026-04-28 19:05:41', '2026-04-28 19:05:41', 1, 4, 1, 1, 'active'),
	(8, '2026-04-28 19:05:44', '2026-04-28 19:05:44', 1, 4, 2, 1, 'active'),
	(9, '2026-04-28 19:06:58', '2026-04-28 19:06:58', 2, 9, 3, 1, 'active'),
	(10, '2026-04-28 19:07:02', '2026-04-28 19:07:02', 2, 9, 4, 1, 'active'),
	(11, '2026-04-28 19:07:07', '2026-04-28 19:07:07', 2, 10, 4, 1, 'active'),
	(12, '2026-04-28 19:07:11', '2026-04-28 19:07:11', 2, 10, 3, 1, 'active'),
	(13, '2026-04-28 19:09:13', '2026-04-28 19:09:13', 2, 11, 3, 1, 'active'),
	(14, '2026-04-28 19:09:16', '2026-04-28 19:09:16', 2, 11, 4, 1, 'active'),
	(15, '2026-04-28 19:09:23', '2026-04-28 19:09:23', 2, 12, 4, 1, 'active'),
	(16, '2026-04-28 19:09:26', '2026-04-28 19:09:26', 2, 12, 3, 1, 'active'),
	(17, '2026-04-28 19:23:56', '2026-04-28 19:23:56', 3, 13, 5, 1, 'active');

-- Dumping structure for table uca-nexus.raw_scores
CREATE TABLE IF NOT EXISTS `raw_scores` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `student_id` bigint unsigned DEFAULT NULL,
  `column_id` bigint unsigned DEFAULT NULL,
  `score` decimal(7,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `raw_scores_student_id_foreign` (`student_id`),
  KEY `raw_scores_column_id_foreign` (`column_id`),
  CONSTRAINT `raw_scores_column_id_foreign` FOREIGN KEY (`column_id`) REFERENCES `grade_columns` (`id`) ON DELETE SET NULL,
  CONSTRAINT `raw_scores_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table uca-nexus.raw_scores: ~0 rows (approximately)

-- Dumping structure for table uca-nexus.schedules
CREATE TABLE IF NOT EXISTS `schedules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `proctor_id` bigint unsigned DEFAULT NULL,
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `process` enum('exam','interview','evaluation') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'exam',
  PRIMARY KEY (`id`),
  KEY `schedules_proctor_id_foreign` (`proctor_id`),
  CONSTRAINT `schedules_proctor_id_foreign` FOREIGN KEY (`proctor_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table uca-nexus.schedules: ~0 rows (approximately)
INSERT INTO `schedules` (`id`, `created_at`, `updated_at`, `proctor_id`, `date`, `start_time`, `end_time`, `status`, `process`) VALUES
	(1, '2026-04-28 20:54:48', '2026-04-28 20:54:48', 4, '2026-04-30', '12:54:00', '13:54:00', 'active', 'evaluation');

-- Dumping structure for table uca-nexus.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table uca-nexus.sessions: ~0 rows (approximately)
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('DS3OpnLgmifIB8PaBXfBIxmswgDxvf5Wnlpvk8Vg', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZlBSRkJ6YW1pWVdPZ2xxUzNzZ3JVWXR0WG9ySGQ4b2VKQ0NwaXN2USI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yZWdpc3RyYXIiO3M6NToicm91dGUiO3M6MTU6InJlZ2lzdHJhci5sb2dpbiI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==', 1777433344),
	('Gacb8fwi1VzkinoEOWnRk8zOysnC61yhWJgNeHkU', 4, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYmExOXY5U3gyQlBGRVhDdzhwZzh1azBGckUyVExGV01GNzhldzRYNCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pc3Npb24vc3R1ZGVudCI7czo1OiJyb3V0ZSI7czoxNzoiYWRtaXNzaW9uLnN0dWRlbnQiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo0O30=', 1777438836),
	('t6mxaTITttcVWz505OZXF0RUPBRlNpqCr4J4DubC', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTVVZOE9SeVRJcGZhd2VYU21RNmVGM2EzZVVGRXEzNUJ4ckRNb1hxYyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yZWdpc3RyYXIvY2xhc3NsaXN0IjtzOjU6InJvdXRlIjtzOjE5OiJyZWdpc3RyYXIuY2xhc3NsaXN0Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mjt9', 1777433367);

-- Dumping structure for table uca-nexus.statuses
CREATE TABLE IF NOT EXISTS `statuses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `enrollment_status` enum('open','close') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'close',
  `student_portal_status` enum('on','off') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'off',
  `visible_grade` enum('on','off') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'off',
  `submission_of_grade` enum('on','off') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'off',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table uca-nexus.statuses: ~0 rows (approximately)
INSERT INTO `statuses` (`id`, `enrollment_status`, `student_portal_status`, `visible_grade`, `submission_of_grade`, `created_at`, `updated_at`) VALUES
	(1, 'close', 'off', 'off', 'off', '2026-04-27 21:47:58', '2026-04-27 21:47:58');

-- Dumping structure for table uca-nexus.students
CREATE TABLE IF NOT EXISTS `students` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `student_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lrn` bigint NOT NULL,
  `department_id` bigint unsigned NOT NULL,
  `program_id` bigint unsigned NOT NULL,
  `level_id` bigint unsigned NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `middle_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sex` enum('Male','Female') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Male',
  `citizenship` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `religion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `birthdate` date NOT NULL,
  `place_of_birth` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `civil_status` enum('Single','Married','Widow/Widower') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Single',
  `student_type` enum('new','old') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `application_id` bigint unsigned NOT NULL,
  `status` enum('enrolled','withdrawn','dropped','graduated','regular','irregular') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'enrolled',
  PRIMARY KEY (`id`),
  UNIQUE KEY `students_student_number_unique` (`student_number`),
  KEY `students_department_id_foreign` (`department_id`),
  KEY `students_program_id_foreign` (`program_id`),
  KEY `students_level_id_foreign` (`level_id`),
  KEY `students_application_id_foreign` (`application_id`),
  CONSTRAINT `students_application_id_foreign` FOREIGN KEY (`application_id`) REFERENCES `applicants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `students_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `students_level_id_foreign` FOREIGN KEY (`level_id`) REFERENCES `levels` (`id`) ON DELETE CASCADE,
  CONSTRAINT `students_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table uca-nexus.students: ~0 rows (approximately)
INSERT INTO `students` (`id`, `created_at`, `updated_at`, `student_number`, `lrn`, `department_id`, `program_id`, `level_id`, `last_name`, `first_name`, `middle_name`, `sex`, `citizenship`, `religion`, `birthdate`, `place_of_birth`, `civil_status`, `student_type`, `application_id`, `status`) VALUES
	(1, '2026-04-28 20:59:10', '2026-04-28 20:59:10', 'JHS-26-00001', 12345678, 3, 5, 13, 'Ponse', 'Juanito', 'Reyes', 'Male', 'Filipino', 'Catholic', '2008-02-05', 'Lipa City', 'Single', 'new', 1, 'enrolled'),
	(2, '2026-04-28 20:59:14', '2026-04-28 20:59:14', 'JHS-26-00002', 201200001, 3, 5, 13, 'Navarro', 'Luis', 'Reyes', 'Male', 'Filipino', 'Christian', '2005-01-01', 'Quezon City', 'Single', 'new', 2, 'enrolled'),
	(3, '2026-04-28 20:59:15', '2026-04-28 20:59:15', 'JHS-26-00003', 201200002, 3, 5, 13, 'Dela Cruz', 'Andrea', 'Garcia', 'Female', 'Filipino', 'Christian', '2006-02-02', 'Quezon City', 'Single', 'new', 3, 'enrolled'),
	(4, '2026-04-28 20:59:16', '2026-04-28 20:59:16', 'ABM-26-00001', 201200006, 2, 4, 11, 'Morales', 'Katrina', 'Reyes', 'Female', 'Filipino', 'Christian', '2007-06-06', 'Quezon City', 'Single', 'new', 7, 'enrolled'),
	(5, '2026-04-28 20:59:16', '2026-04-28 20:59:16', 'BSN-26-00001', 201200007, 1, 1, 1, 'Salazar', 'Joshua', 'Garcia', 'Male', 'Filipino', 'Christian', '2005-07-07', 'Quezon City', 'Single', 'new', 8, 'enrolled'),
	(6, '2026-04-28 20:59:16', '2026-04-28 20:59:16', 'BSN-26-00002', 201200008, 1, 1, 1, 'Reyes', 'Angela', 'Torres', 'Female', 'Filipino', 'Christian', '2006-08-08', 'Quezon City', 'Single', 'new', 9, 'enrolled'),
	(7, '2026-04-28 20:59:17', '2026-04-28 20:59:17', 'BSN-26-00003', 201200009, 1, 1, 1, 'Garcia', 'Ramon', 'Mendoza', 'Male', 'Filipino', 'Christian', '2007-09-09', 'Quezon City', 'Single', 'new', 10, 'enrolled'),
	(8, '2026-04-28 20:59:17', '2026-04-28 20:59:17', 'BSN-26-00004', 201200010, 1, 1, 1, 'Castro', 'Bianca', 'Marquez', 'Female', 'Filipino', 'Christian', '2005-10-10', 'Quezon City', 'Single', 'new', 11, 'enrolled'),
	(9, '2026-04-28 21:00:02', '2026-04-28 21:00:02', 'STEM-26-00001', 201200003, 2, 3, 9, 'Torres', 'Mark', 'Torres', 'Male', 'Filipino', 'Christian', '2007-03-03', 'Quezon City', 'Single', 'new', 4, 'enrolled'),
	(10, '2026-04-28 21:00:02', '2026-04-28 21:00:02', 'ABM-26-00002', 201200004, 2, 4, 11, 'Ramos', 'Janelle', 'Mendoza', 'Female', 'Filipino', 'Christian', '2005-04-04', 'Quezon City', 'Single', 'new', 5, 'enrolled'),
	(11, '2026-04-28 21:00:02', '2026-04-28 21:00:02', 'ABM-26-00003', 201200005, 2, 4, 11, 'Bautista', 'Paolo', 'Marquez', 'Male', 'Filipino', 'Christian', '2006-05-05', 'Quezon City', 'Single', 'new', 6, 'enrolled');

-- Dumping structure for table uca-nexus.student_academic_histories
CREATE TABLE IF NOT EXISTS `student_academic_histories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `student_id` bigint unsigned NOT NULL,
  `elementary_school_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `elementary_school_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `elementary_inclusive_years` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `junior_school_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `junior_school_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `junior_inclusive_years` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `senior_school_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `senior_school_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `senior_inclusive_years` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `college_school_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `college_school_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `college_inclusive_years` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_academic_histories_student_id_foreign` (`student_id`),
  CONSTRAINT `student_academic_histories_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table uca-nexus.student_academic_histories: ~0 rows (approximately)
INSERT INTO `student_academic_histories` (`id`, `created_at`, `updated_at`, `student_id`, `elementary_school_name`, `elementary_school_address`, `elementary_inclusive_years`, `junior_school_name`, `junior_school_address`, `junior_inclusive_years`, `senior_school_name`, `senior_school_address`, `senior_inclusive_years`, `college_school_name`, `college_school_address`, `college_inclusive_years`) VALUES
	(1, '2026-04-28 20:59:12', '2026-04-28 20:59:12', 1, 'Tangob Elementray School', 'Tangob, Padre Garcia Batangas', '2012-2018', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A'),
	(2, '2026-04-28 20:59:15', '2026-04-28 20:59:15', 2, 'QC Central Elementary School', 'Quezon City', '2012-2018', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A'),
	(3, '2026-04-28 20:59:15', '2026-04-28 20:59:15', 3, 'QC Central Elementary School', 'Quezon City', '2012-2018', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A'),
	(4, '2026-04-28 20:59:16', '2026-04-28 20:59:16', 4, 'QC Central Elementary School', 'Quezon City', '2012-2018', 'Quezon City National High School', 'Quezon City', '2018-2022', 'Caloocan City Senior High School', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A'),
	(5, '2026-04-28 20:59:16', '2026-04-28 20:59:16', 5, 'QC Central Elementary School', 'Quezon City', '2012-2018', 'Quezon City National High School', 'Quezon City', '2018-2022', 'Taguig Integrated High School', 'Taguig City', '2022-2024', 'N/A', 'N/A', 'N/A'),
	(6, '2026-04-28 20:59:16', '2026-04-28 20:59:16', 6, 'QC Central Elementary School', 'Quezon City', '2012-2018', 'Quezon City National High School', 'Quezon City', '2018-2022', 'Quezon City National High School', 'Quezon City', '2022-2024', 'N/A', 'N/A', 'N/A'),
	(7, '2026-04-28 20:59:17', '2026-04-28 20:59:17', 7, 'QC Central Elementary School', 'Quezon City', '2012-2018', 'Quezon City National High School', 'Quezon City', '2018-2022', 'Manila Science High School', 'Manila', '2022-2024', 'N/A', 'N/A', 'N/A'),
	(8, '2026-04-28 20:59:17', '2026-04-28 20:59:17', 8, 'QC Central Elementary School', 'Quezon City', '2012-2018', 'Quezon City National High School', 'Quezon City', '2018-2022', 'Pasig City Senior High School', 'Pasig City', '2022-2024', 'N/A', 'N/A', 'N/A'),
	(9, '2026-04-28 21:00:02', '2026-04-28 21:00:02', 9, 'QC Central Elementary School', 'Quezon City', '2012-2018', 'Quezon City National High School', 'Quezon City', '2018-2022', 'Pasig City Senior High School', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A'),
	(10, '2026-04-28 21:00:02', '2026-04-28 21:00:02', 10, 'QC Central Elementary School', 'Quezon City', '2012-2018', 'Quezon City National High School', 'Quezon City', '2018-2022', 'Makati Senior High School', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A'),
	(11, '2026-04-28 21:00:03', '2026-04-28 21:00:03', 11, 'QC Central Elementary School', 'Quezon City', '2012-2018', 'Quezon City National High School', 'Quezon City', '2018-2022', 'Rizal National High School', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A');

-- Dumping structure for table uca-nexus.student_accounts
CREATE TABLE IF NOT EXISTS `student_accounts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `account_status` enum('on','off') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'off',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `examination_permit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_accounts_student_id_unique` (`student_id`),
  CONSTRAINT `student_accounts_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table uca-nexus.student_accounts: ~11 rows (approximately)
INSERT INTO `student_accounts` (`id`, `student_id`, `account_status`, `password`, `examination_permit`, `created_at`, `updated_at`) VALUES
	(1, 1, 'off', '$2y$12$8KyWMpaoiFR8ho1LPr5ZKejh0a66XbJXTZB5FUJr4Iu6ljUc3rcYK', NULL, '2026-04-28 20:59:13', '2026-04-28 20:59:13'),
	(2, 2, 'off', '$2y$12$1zgwLeNhGYtEl0GfLz77XuwduYwbr2mc71.lEy3Q3SstQWxdQIKyS', NULL, '2026-04-28 20:59:15', '2026-04-28 20:59:15'),
	(3, 3, 'off', '$2y$12$e6emnpwjnITx9RryCW6kPuj1RzSy6mNJJlMIBmuAJKY9x401DVC0i', NULL, '2026-04-28 20:59:16', '2026-04-28 20:59:16'),
	(4, 4, 'off', '$2y$12$rmCo7MzKxUVvhtPPciwuEe.pCYp4HLSTMhmVQGWFdT2YiaO0Db63y', NULL, '2026-04-28 20:59:16', '2026-04-28 20:59:16'),
	(5, 5, 'off', '$2y$12$ibiEgH7R3IVPQCwHNqAUL.DLTaNb164I4QzpAGqhk9MUoNcH30miW', NULL, '2026-04-28 20:59:16', '2026-04-28 20:59:16'),
	(6, 6, 'off', '$2y$12$oZk4/xN3f3MGPzuyzjbczeOiR8aYR..NBe9THYOuloLNiKD8cKIgu', NULL, '2026-04-28 20:59:17', '2026-04-28 20:59:17'),
	(7, 7, 'off', '$2y$12$w1zCEvrOinZdqdCWtVYn2uKcBWShTFNuRpJw0BaLjLQXBRTyJLaFC', NULL, '2026-04-28 20:59:17', '2026-04-28 20:59:17'),
	(8, 8, 'off', '$2y$12$tCNRpumLoiekVoRIxLYYJO1ud0JcFSpn4nykQRE43NKXlY6LXf2te', NULL, '2026-04-28 20:59:17', '2026-04-28 20:59:17'),
	(9, 9, 'off', '$2y$12$WIXcZ9ojA2w/zs53122jVuZdE.5yaEjNu8968ARV0NZX4Zpn5Q1Qe', NULL, '2026-04-28 21:00:02', '2026-04-28 21:00:02'),
	(10, 10, 'off', '$2y$12$iCfneqGrCIqXJdMQRhmM3OiX6CeBTB4nnWjTM0YcG83hRTkzi6DEW', NULL, '2026-04-28 21:00:02', '2026-04-28 21:00:02'),
	(11, 11, 'off', '$2y$12$OUc9et1wpBYgDmjMSGhUYuZ9L48qlKJClOsMUrN4wxKw0rJyGENdO', NULL, '2026-04-28 21:00:03', '2026-04-28 21:00:03');

-- Dumping structure for table uca-nexus.student_contacts
CREATE TABLE IF NOT EXISTS `student_contacts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `student_id` bigint unsigned NOT NULL,
  `zip_code` int NOT NULL,
  `present_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `permanent_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_contacts_email_unique` (`email`),
  KEY `student_contacts_student_id_foreign` (`student_id`),
  CONSTRAINT `student_contacts_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table uca-nexus.student_contacts: ~0 rows (approximately)
INSERT INTO `student_contacts` (`id`, `created_at`, `updated_at`, `student_id`, `zip_code`, `present_address`, `permanent_address`, `telephone_number`, `mobile_number`, `email`) VALUES
	(1, '2026-04-28 20:59:11', '2026-04-28 20:59:11', 1, 4224, 'Tangob, Padre Garcia Batangas', 'Tangob, Padre Garcia Batangas', 'N/A', '09937006750', 'markanthonylina05@gmail.com'),
	(2, '2026-04-28 20:59:14', '2026-04-28 20:59:14', 2, 1100, '21 Mabini St., Quezon City', '21 Mabini St., Quezon City', 'N/A', '09181000001', 'luis.navarro1@email.com'),
	(3, '2026-04-28 20:59:15', '2026-04-28 20:59:15', 3, 1100, '22 Mabini St., Quezon City', '22 Mabini St., Quezon City', 'N/A', '09181000002', 'andrea.delacruz2@email.com'),
	(4, '2026-04-28 20:59:16', '2026-04-28 20:59:16', 4, 1100, '26 Mabini St., Quezon City', '26 Mabini St., Quezon City', 'N/A', '09181000006', 'katrina.morales6@email.com'),
	(5, '2026-04-28 20:59:16', '2026-04-28 20:59:16', 5, 1100, '27 Mabini St., Quezon City', '27 Mabini St., Quezon City', 'N/A', '09181000007', 'joshua.salazar7@email.com'),
	(6, '2026-04-28 20:59:16', '2026-04-28 20:59:16', 6, 1100, '28 Mabini St., Quezon City', '28 Mabini St., Quezon City', 'N/A', '09181000008', 'angela.reyes8@email.com'),
	(7, '2026-04-28 20:59:17', '2026-04-28 20:59:17', 7, 1100, '29 Mabini St., Quezon City', '29 Mabini St., Quezon City', 'N/A', '09181000009', 'ramon.garcia9@email.com'),
	(8, '2026-04-28 20:59:17', '2026-04-28 20:59:17', 8, 1100, '30 Mabini St., Quezon City', '30 Mabini St., Quezon City', 'N/A', '09181000010', 'bianca.castro10@email.com'),
	(9, '2026-04-28 21:00:02', '2026-04-28 21:00:02', 9, 1100, '23 Mabini St., Quezon City', '23 Mabini St., Quezon City', 'N/A', '09181000003', 'mark.torres3@email.com'),
	(10, '2026-04-28 21:00:02', '2026-04-28 21:00:02', 10, 1100, '24 Mabini St., Quezon City', '24 Mabini St., Quezon City', 'N/A', '09181000004', 'janelle.ramos4@email.com'),
	(11, '2026-04-28 21:00:02', '2026-04-28 21:00:02', 11, 1100, '25 Mabini St., Quezon City', '25 Mabini St., Quezon City', 'N/A', '09181000005', 'paolo.bautista5@email.com');

-- Dumping structure for table uca-nexus.student_fees
CREATE TABLE IF NOT EXISTS `student_fees` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `fee_id` bigint unsigned NOT NULL,
  `academic_term_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `student_fees_student_id_foreign` (`student_id`),
  KEY `student_fees_fee_id_foreign` (`fee_id`),
  KEY `student_fees_academic_term_id_foreign` (`academic_term_id`),
  CONSTRAINT `student_fees_academic_term_id_foreign` FOREIGN KEY (`academic_term_id`) REFERENCES `academic_terms` (`id`) ON DELETE CASCADE,
  CONSTRAINT `student_fees_fee_id_foreign` FOREIGN KEY (`fee_id`) REFERENCES `fees` (`id`) ON DELETE CASCADE,
  CONSTRAINT `student_fees_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table uca-nexus.student_fees: ~0 rows (approximately)

-- Dumping structure for table uca-nexus.student_guardians
CREATE TABLE IF NOT EXISTS `student_guardians` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `student_id` bigint unsigned NOT NULL,
  `mother_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mother_occupation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mother_contact_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mother_monthly_income` int NOT NULL,
  `father_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `father_occupation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `father_contact_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `father_monthly_income` int NOT NULL,
  `guardian_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guardian_occupation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guardian_contact_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guardian_monthly_income` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `student_guardians_student_id_foreign` (`student_id`),
  CONSTRAINT `student_guardians_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table uca-nexus.student_guardians: ~0 rows (approximately)
INSERT INTO `student_guardians` (`id`, `created_at`, `updated_at`, `student_id`, `mother_name`, `mother_occupation`, `mother_contact_number`, `mother_monthly_income`, `father_name`, `father_occupation`, `father_contact_number`, `father_monthly_income`, `guardian_name`, `guardian_occupation`, `guardian_contact_number`, `guardian_monthly_income`) VALUES
	(1, '2026-04-28 20:59:12', '2026-04-28 20:59:12', 1, 'Juanita Reyes Ponse', 'Housewife', '09937006750', 100000, 'Peter Merionette Smith Ponse', 'Surgeon Doctor', '09937006750', 1000000, 'Juanita Reyes Ponse', 'Housewife', '09937006750', 100000),
	(2, '2026-04-28 20:59:14', '2026-04-28 20:59:14', 2, 'Maria Reyes Navarro', 'Teacher', '09172000001', 25100, 'Jose Navarro', 'Driver', '09193000001', 18090, 'Maria Reyes Navarro', 'Teacher', '09172000001', 25100),
	(3, '2026-04-28 20:59:15', '2026-04-28 20:59:15', 3, 'Maria Garcia Dela Cruz', 'Teacher', '09172000002', 25200, 'Jose Dela Cruz', 'Driver', '09193000002', 18180, 'Maria Garcia Dela Cruz', 'Teacher', '09172000002', 25200),
	(4, '2026-04-28 20:59:16', '2026-04-28 20:59:16', 4, 'Maria Reyes Morales', 'Teacher', '09172000006', 25600, 'Jose Morales', 'Driver', '09193000006', 18540, 'Maria Reyes Morales', 'Teacher', '09172000006', 25600),
	(5, '2026-04-28 20:59:16', '2026-04-28 20:59:16', 5, 'Maria Garcia Salazar', 'Teacher', '09172000007', 25700, 'Jose Salazar', 'Driver', '09193000007', 18630, 'Maria Garcia Salazar', 'Teacher', '09172000007', 25700),
	(6, '2026-04-28 20:59:16', '2026-04-28 20:59:16', 6, 'Maria Torres Reyes', 'Teacher', '09172000008', 25800, 'Jose Reyes', 'Driver', '09193000008', 18720, 'Maria Torres Reyes', 'Teacher', '09172000008', 25800),
	(7, '2026-04-28 20:59:17', '2026-04-28 20:59:17', 7, 'Maria Mendoza Garcia', 'Teacher', '09172000009', 25900, 'Jose Garcia', 'Driver', '09193000009', 18810, 'Maria Mendoza Garcia', 'Teacher', '09172000009', 25900),
	(8, '2026-04-28 20:59:17', '2026-04-28 20:59:17', 8, 'Maria Marquez Castro', 'Teacher', '09172000010', 26000, 'Jose Castro', 'Driver', '09193000010', 18900, 'Maria Marquez Castro', 'Teacher', '09172000010', 26000),
	(9, '2026-04-28 21:00:02', '2026-04-28 21:00:02', 9, 'Maria Torres Torres', 'Teacher', '09172000003', 25300, 'Jose Torres', 'Driver', '09193000003', 18270, 'Maria Torres Torres', 'Teacher', '09172000003', 25300),
	(10, '2026-04-28 21:00:02', '2026-04-28 21:00:02', 10, 'Maria Mendoza Ramos', 'Teacher', '09172000004', 25400, 'Jose Ramos', 'Driver', '09193000004', 18360, 'Maria Mendoza Ramos', 'Teacher', '09172000004', 25400),
	(11, '2026-04-28 21:00:02', '2026-04-28 21:00:02', 11, 'Maria Marquez Bautista', 'Teacher', '09172000005', 25500, 'Jose Bautista', 'Driver', '09193000005', 18450, 'Maria Marquez Bautista', 'Teacher', '09172000005', 25500);

-- Dumping structure for table uca-nexus.subjects
CREATE TABLE IF NOT EXISTS `subjects` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `code` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit` int NOT NULL,
  `lech` int NOT NULL,
  `lecu` int NOT NULL,
  `labh` int NOT NULL,
  `labu` int NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `education_level` enum('K12','college') COLLATE utf8mb4_unicode_ci DEFAULT 'college',
  `weight_category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table uca-nexus.subjects: ~0 rows (approximately)
INSERT INTO `subjects` (`id`, `created_at`, `updated_at`, `code`, `description`, `unit`, `lech`, `lecu`, `labh`, `labu`, `type`, `education_level`, `weight_category`, `status`) VALUES
	(1, '2026-04-28 18:15:13', '2026-04-28 18:15:13', 'MATH1', 'Mathematics 1', 1, 1, 1, 0, 0, 'lec', 'K12', 'Core', 'active');

-- Dumping structure for table uca-nexus.subject_offerings
CREATE TABLE IF NOT EXISTS `subject_offerings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `academic_term_id` bigint unsigned NOT NULL,
  `subject_id` bigint unsigned NOT NULL,
  `department_id` bigint unsigned NOT NULL,
  `program_id` bigint unsigned NOT NULL,
  `level_id` bigint unsigned DEFAULT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `class_size` int NOT NULL DEFAULT '40',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subject_offerings_academic_term_id_foreign` (`academic_term_id`),
  KEY `subject_offerings_subject_id_foreign` (`subject_id`),
  KEY `subject_offerings_department_id_foreign` (`department_id`),
  KEY `subject_offerings_program_id_foreign` (`program_id`),
  KEY `subject_offerings_level_id_foreign` (`level_id`),
  CONSTRAINT `subject_offerings_academic_term_id_foreign` FOREIGN KEY (`academic_term_id`) REFERENCES `academic_terms` (`id`) ON DELETE CASCADE,
  CONSTRAINT `subject_offerings_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `subject_offerings_level_id_foreign` FOREIGN KEY (`level_id`) REFERENCES `levels` (`id`) ON DELETE SET NULL,
  CONSTRAINT `subject_offerings_program_id_foreign` FOREIGN KEY (`program_id`) REFERENCES `programs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `subject_offerings_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table uca-nexus.subject_offerings: ~0 rows (approximately)

-- Dumping structure for table uca-nexus.teachers
CREATE TABLE IF NOT EXISTS `teachers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `code` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `middle_name` text COLLATE utf8mb4_unicode_ci,
  `last_name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `teachers_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table uca-nexus.teachers: ~0 rows (approximately)

-- Dumping structure for table uca-nexus.teacher_accounts
CREATE TABLE IF NOT EXISTS `teacher_accounts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `teacher_id` bigint unsigned DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `teacher_accounts_teacher_id_foreign` (`teacher_id`),
  CONSTRAINT `teacher_accounts_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table uca-nexus.teacher_accounts: ~0 rows (approximately)

-- Dumping structure for table uca-nexus.teacher_offerings
CREATE TABLE IF NOT EXISTS `teacher_offerings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `teacher_id` bigint unsigned DEFAULT NULL,
  `offering_id` bigint unsigned DEFAULT NULL,
  `academic_year_id` bigint unsigned DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `teacher_offerings_teacher_id_foreign` (`teacher_id`),
  KEY `teacher_offerings_offering_id_foreign` (`offering_id`),
  KEY `teacher_offerings_academic_year_id_foreign` (`academic_year_id`),
  CONSTRAINT `teacher_offerings_academic_year_id_foreign` FOREIGN KEY (`academic_year_id`) REFERENCES `academic_years` (`id`) ON DELETE SET NULL,
  CONSTRAINT `teacher_offerings_offering_id_foreign` FOREIGN KEY (`offering_id`) REFERENCES `subject_offerings` (`id`) ON DELETE SET NULL,
  CONSTRAINT `teacher_offerings_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table uca-nexus.teacher_offerings: ~0 rows (approximately)

-- Dumping structure for table uca-nexus.transactions
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `student_id` bigint unsigned NOT NULL,
  `academic_term_id` bigint unsigned NOT NULL,
  `cashier_id` bigint unsigned DEFAULT NULL,
  `or_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description_id` bigint unsigned DEFAULT NULL,
  `type_id` bigint unsigned DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `transactions_student_id_foreign` (`student_id`),
  KEY `transactions_academic_term_id_foreign` (`academic_term_id`),
  KEY `transactions_cashier_id_foreign` (`cashier_id`),
  KEY `transactions_description_id_foreign` (`description_id`),
  KEY `transactions_type_id_foreign` (`type_id`),
  CONSTRAINT `transactions_academic_term_id_foreign` FOREIGN KEY (`academic_term_id`) REFERENCES `academic_terms` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transactions_cashier_id_foreign` FOREIGN KEY (`cashier_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `transactions_description_id_foreign` FOREIGN KEY (`description_id`) REFERENCES `payment_accounts` (`id`) ON DELETE SET NULL,
  CONSTRAINT `transactions_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transactions_type_id_foreign` FOREIGN KEY (`type_id`) REFERENCES `payment_types` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table uca-nexus.transactions: ~0 rows (approximately)

-- Dumping structure for table uca-nexus.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department_id` bigint unsigned DEFAULT NULL,
  `student_id` bigint unsigned DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_department_id_foreign` (`department_id`),
  KEY `users_student_id_foreign` (`student_id`),
  CONSTRAINT `users_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  CONSTRAINT `users_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table uca-nexus.users: ~4 rows (approximately)
INSERT INTO `users` (`id`, `name`, `email`, `type`, `role`, `department_id`, `student_id`, `status`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Administrator', 'admin@gmail.com', 'admin', 'head', NULL, NULL, 'active', '2026-04-27 21:47:53', '$2y$12$4IwK7eO0jLeO9NNdGsgeS.iITosjsk0EgxSRZKO9988XhHCELgTMe', NULL, '2026-04-27 21:47:53', '2026-04-27 21:47:53'),
	(2, 'Registrar', 'registrar@gmail.com', 'registrar', 'head', NULL, NULL, 'active', '2026-04-27 21:47:53', '$2y$12$b0bjPbzojkHXlIRiJZp98ec8.FuAaG5da6.405Wb3JWWB94jovE/G', NULL, '2026-04-27 21:47:53', '2026-04-27 21:47:53'),
	(3, 'Accounting', 'accounting@gmail.com', 'accounting', 'head', NULL, NULL, 'active', '2026-04-27 21:47:53', '$2y$12$OHq0rJLvbgB12DkmRZ6hRumVuTrdfTcpTrk7d8Q1L/F0YzZy.Xlzq', NULL, '2026-04-27 21:47:53', '2026-04-27 21:47:53'),
	(4, 'Admissions', 'admission@gmail.com', 'admission', 'head', NULL, NULL, 'active', '2026-04-27 21:47:54', '$2y$12$v7rs7pLgYjUzwNOMIfjIMeAE4D/K6ijdLwBo2KcY7JUnhcRrF6xU6', NULL, '2026-04-27 21:47:54', '2026-04-27 21:47:54');

-- Dumping structure for table uca-nexus.websites
CREATE TABLE IF NOT EXISTS `websites` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `event_date` date DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `embedded_url` text COLLATE utf8mb4_unicode_ci,
  `days` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_open` tinyint(1) DEFAULT '1',
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `social_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table uca-nexus.websites: ~0 rows (approximately)

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
