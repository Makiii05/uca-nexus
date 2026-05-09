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
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table uca-nexus.websites: ~24 rows (approximately)
REPLACE INTO `websites` (`id`, `created_at`, `updated_at`, `type`, `image_url`, `title`, `description`, `event_date`, `location`, `embedded_url`, `days`, `is_open`, `start_time`, `end_time`, `email`, `contact`, `social_link`) VALUES
	(2, '2026-03-12 03:52:08', '2026-03-12 03:52:08', 'carousel', 'images/69b2a8e8bc3d5.jpg', 'Unciano Colleges Antipolo, Inc.', 'Empowering Minds, Building Futures', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL),
	(3, '2026-03-12 03:53:35', '2026-03-12 03:53:35', 'carousel', 'images/69b2a93f29a24.jpg', 'Your Future Start Here', 'Join Our Growing Community of Achievers.', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL),
	(4, '2026-03-12 03:54:35', '2026-03-12 03:54:35', 'carousel', 'images/69b2a97b26ad6.jpg', 'Excellence in Education', 'Quality Learning Since 1985', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL),
	(5, '2026-03-12 03:56:05', '2026-03-12 03:56:05', 'event', 'images/69b2a9d5b9381.jpg', '35th Commencement Exercises', 'Celebrating the achievements of our graduates as they receive their diplomas and embark on new journeys.', '2025-11-05', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL),
	(6, '2026-03-12 03:57:01', '2026-03-12 03:57:01', 'event', 'images/69b2aa0d296f5.jpg', 'Capping, White Coat, Pinning, and Candle Lighting Ceremonies 2025', 'A sacred tradition honoring our healthcare students as they transition into clinical practice.', '2025-09-04', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL),
	(7, '2026-03-12 03:57:48', '2026-03-12 03:57:48', 'event', 'images/69b2aa3c0e923.jpg', 'Uncianian\'s Sports Event 2024', 'Annual intramurals featuring basketball, volleyball, and other sports competitions among departments.', '2024-12-19', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL),
	(8, '2026-03-12 03:58:15', '2026-03-12 03:58:15', 'mission', NULL, 'Our Mission', 'To provide quality and affordable education that develops competent, morally upright, and socially responsible individuals who are equipped with the knowledge, skills, and values necessary for lifelong learning and global competitiveness.', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL),
	(9, '2026-03-12 03:58:34', '2026-03-12 03:58:34', 'vision', NULL, 'Our Vision', 'To be a leading institution of higher learning in the region, recognized for academic excellence, innovative research, and community service, producing graduates who are catalysts of positive change in society.', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL),
	(10, '2026-03-12 03:58:56', '2026-03-12 03:58:56', 'goal', NULL, 'Leadership', 'Inspiring others to achieve shared goals and make a positive impact.', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL),
	(11, '2026-03-12 03:59:11', '2026-03-12 03:59:11', 'goal', NULL, 'Critical Thinking', 'Analyzing situations and making informed, logical decisions.', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL),
	(12, '2026-03-12 03:59:23', '2026-03-12 03:59:23', 'goal', NULL, 'Innovation', 'Creating new solutions and embracing technological advancement.', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL),
	(13, '2026-03-12 03:59:36', '2026-03-12 03:59:36', 'goal', NULL, 'Integrity', 'Upholding honesty, ethics, and moral values in all endeavors.', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL),
	(14, '2026-03-12 04:02:48', '2026-03-12 04:02:48', 'program', 'images/69b2ab68b5501.jpg', 'Bachelor of Science in Nursing', 'Prepare for a rewarding healthcare career with our comprehensive nursing program featuring clinical rotations, hands-on patient care, and board exam preparation.', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL),
	(15, '2026-03-12 04:03:21', '2026-03-12 04:03:21', 'program', 'images/69b2ab893e667.webp', 'Bachelor of Science in Physical Therapy', 'Master rehabilitation sciences and help patients recover mobility through evidence-based therapeutic interventions and clinical practice.', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL),
	(16, '2026-03-12 04:03:56', '2026-03-12 04:03:56', 'program', 'images/69b2abac80a1a.webp', 'Bachelor in Science in Medical Technology', 'Become a clinical laboratory scientist skilled in diagnostic testing, laboratory procedures, and medical analysis to support healthcare delivery.', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL),
	(19, '2026-03-12 04:30:18', '2026-03-12 04:30:18', 'contact', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '286970174', NULL),
	(20, '2026-03-12 04:30:40', '2026-03-12 04:30:40', 'contact', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, '8697-0174', NULL),
	(21, '2026-03-12 04:30:56', '2026-03-12 04:30:56', 'email', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 'ucai.onlineenrollment@gmail.com', NULL, NULL),
	(22, '2026-03-12 04:31:08', '2026-03-12 04:31:08', 'email', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 'ucai.onlinepayment@gmail.com', NULL, NULL),
	(23, '2026-03-12 04:31:41', '2026-03-12 04:31:41', 'social_link', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, 'https://facebook.com/UncianoCollegesInc'),
	(24, '2026-03-12 04:32:50', '2026-03-12 04:32:50', 'office_hour', NULL, NULL, NULL, NULL, NULL, NULL, 'Monday - Friday', 1, '08:00:00', '17:00:00', NULL, NULL, NULL),
	(25, '2026-03-12 04:33:11', '2026-03-12 04:33:11', 'office_hour', NULL, NULL, NULL, NULL, NULL, NULL, 'Saturday', 1, '08:00:00', '12:00:00', NULL, NULL, NULL),
	(27, '2026-03-12 04:43:40', '2026-03-12 04:45:19', 'location', NULL, NULL, NULL, NULL, '75 L. Sumulong Memorial Circle, Antipolo, 1870 Rizal', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3440.055328403227!2d121.16969092481187!3d14.580166468029896!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397c0ab26f059cd%3A0x641cf1649079f713!2sUnciano%20Colleges%20Inc.!5e0!3m2!1sen!2sph!4v1773319376921!5m2!1sen!2sph', NULL, 1, NULL, NULL, NULL, NULL, NULL),
	(29, '2026-05-03 17:02:35', '2026-05-03 17:02:35', 'office_hour', NULL, NULL, NULL, NULL, NULL, NULL, 'Sunday', 0, NULL, NULL, NULL, NULL, NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
