-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 13, 2026 at 01:08 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `workey_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `job_id` bigint(20) UNSIGNED NOT NULL,
  `worker_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('pending','accepted','rejected','payment_pending','completed') DEFAULT 'pending',
  `arrived_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `job_id`, `worker_id`, `status`, `arrived_at`, `completed_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'completed', '2026-01-01 05:38:48', '2026-01-01 06:05:29', '2026-01-01 05:32:23', '2026-01-01 06:05:29'),
(2, 3, 1, 'completed', '2026-01-01 06:22:30', '2026-01-01 06:25:06', '2026-01-01 06:21:33', '2026-01-01 06:25:06'),
(3, 6, 1, 'completed', '2026-01-02 07:15:53', '2026-01-02 07:17:13', '2026-01-02 07:13:37', '2026-01-02 07:17:13'),
(4, 4, 1, 'completed', NULL, '2026-01-02 07:19:35', '2026-01-02 07:18:39', '2026-01-02 07:19:35'),
(5, 5, 1, 'rejected', NULL, NULL, '2026-01-02 07:23:52', '2026-01-02 07:24:12');

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
-- Table structure for table `deposit_requests`
--

CREATE TABLE `deposit_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `method` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `sender_number` varchar(255) NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `admin_note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `deposit_requests`
--

INSERT INTO `deposit_requests` (`id`, `user_id`, `method`, `amount`, `sender_number`, `transaction_id`, `status`, `admin_note`, `created_at`, `updated_at`) VALUES
(1, 1, 'bkash', 50.00, '01768802953', '@Humayuun', 'approved', NULL, '2026-01-01 05:28:57', '2026-01-01 05:29:05'),
(2, 2, 'bkash', 57774.00, '123143543654', '474574747', 'approved', NULL, '2026-01-01 05:34:55', '2026-01-01 05:35:11'),
(3, 1, 'bkash', 50.00, '657658768768', '7yiuihhihg', 'approved', NULL, '2026-01-02 07:22:34', '2026-01-02 07:23:00');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employer_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `wage` decimal(10,2) NOT NULL,
  `wage_type` enum('daily','hourly') NOT NULL,
  `payment_type` enum('cash','in_app') NOT NULL DEFAULT 'cash',
  `work_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `duration` varchar(255) DEFAULT NULL,
  `worker_count` int(11) NOT NULL DEFAULT 1,
  `description` text DEFAULT NULL,
  `location_name` varchar(255) NOT NULL,
  `lat` decimal(10,8) NOT NULL,
  `lng` decimal(11,8) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'open',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `employer_id`, `title`, `category`, `wage`, `wage_type`, `payment_type`, `work_date`, `start_time`, `end_time`, `duration`, `worker_count`, `description`, `location_name`, `lat`, `lng`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, '567657', 'রাজমিস্ত্রি', 500.00, 'daily', 'cash', '2026-01-02', '08:00:00', '17:00:00', '1', 1, 'tytyuyuyuty', 'Sagardighi, Murshidabad', 24.36110841, 87.97315703, 'filled', '2026-01-01 05:16:34', '2026-01-01 05:33:58'),
(2, 2, '6567457', 'হেল্পার/লেবার', 1000.00, 'daily', 'in_app', '2026-01-02', '08:00:00', '17:00:00', '1', 1, 'rtyrtyrtrtur', 'এমইএস, Zia Colony Road', 23.81471562, 90.40597916, 'open', '2026-01-01 06:18:35', '2026-01-01 06:18:35'),
(3, 2, '74575676', 'রাজমিস্ত্রি', 1000.00, 'daily', 'in_app', '2026-01-02', '08:00:00', '17:00:00', '1', 1, 'rtyrtyrtu', 'Naogaon, Naogaon Sadar Upazila', 24.77177233, 88.81896973, 'filled', '2026-01-01 06:20:53', '2026-01-01 06:22:17'),
(4, 2, '২ রুম রং করতে হবে।', 'রং মিস্ত্রি', 700.00, 'daily', 'in_app', '2026-01-03', '08:00:00', '17:00:00', '1', 1, '১. রং করার অভিজ্ঞতা থাকতে হবে।\r\n২. অবশ্যই ভালো ভাবে কাজ করতে হবে যেন আসবাব পত্রের ক্ষতি না হয়।\r\n৩. বাসায় বাচ্চা আছে তাই শব্দ যতটা সম্ভব কম করতে হবে।', 'Rangpur Road, Nawabbari', 24.85015016, 89.37312736, 'completed', '2026-01-02 06:37:41', '2026-01-02 07:19:35'),
(5, 2, 'ধুদ্দুদ্ধ', 'রাজমিস্ত্রি', 500.00, 'daily', 'cash', '2026-01-03', '07:00:00', '18:00:00', '1', 1, 'উভিভিচ্চ', 'Shantahar Road, Dottobari', 24.85360556, 89.37076486, 'open', '2026-01-02 07:10:08', '2026-01-02 07:10:08'),
(6, 2, '২ রুম এর রং করতে হবে', 'রাজমিস্ত্রি', 500.00, 'daily', 'cash', '2026-01-03', '07:00:00', '18:00:00', '1', 1, 'Xhhxxbzb', 'Sherpur, Sherpur Upazila, Bogura District, Rajshahi Division, 5840, Bangladesh', 24.66486730, 89.42025560, 'filled', '2026-01-02 07:12:39', '2026-01-02 07:14:15'),
(7, 2, '২ রুম রং করতে হবে', 'রং মিস্ত্রি', 700.00, 'daily', 'cash', '2026-01-03', '09:00:00', '17:00:00', '1', 1, 'Dndjh hdjdvdj djjdhdjdh', 'Sherpur Road, Bejora Bazar', 24.84466992, 89.37284923, 'open', '2026-01-02 07:50:40', '2026-01-02 07:50:40');

-- --------------------------------------------------------

--
-- Table structure for table `job_applications`
--

CREATE TABLE `job_applications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `job_id` bigint(20) UNSIGNED NOT NULL,
  `worker_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
(4, '2025_12_29_124647_add_worker_fields_to_users_table', 1),
(5, '2025_12_29_125743_create_applications_table', 1),
(6, '2025_12_29_150032_create_transactions_table', 1),
(7, '2025_12_29_152803_create_job_applications_table', 1),
(8, '2025_12_29_153916_create_reviews_table', 1),
(9, '2025_12_30_101130_add_verification_fields_to_users_table', 1),
(10, '2025_12_30_101225_create_settings_table', 1),
(11, '2025_12_30_110203_create_deposit_requests_table', 1),
(12, '2025_12_30_151609_create_reports_table', 1),
(13, '2025_12_31_155830_create_notifications_table', 1),
(14, '2026_01_01_110851_update_jobs_and_applications_table', 1),
(15, '2026_01_01_114832_add_payment_pending_to_applications_status', 2);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('00267df9-6ae4-4f22-8aa9-c7762c82afb3', 'App\\Notifications\\JobNotification', 'App\\Models\\User', 2, '{\"title\":\"\\u09a8\\u09a4\\u09c1\\u09a8 \\u0986\\u09ac\\u09c7\\u09a6\\u09a8 \\u098f\\u09b8\\u09c7\\u099b\\u09c7!\",\"message\":\"Humayun \\u0986\\u09aa\\u09a8\\u09be\\u09b0 \\\"\\u09e8 \\u09b0\\u09c1\\u09ae \\u09b0\\u0982 \\u0995\\u09b0\\u09a4\\u09c7 \\u09b9\\u09ac\\u09c7\\u0964\\\" \\u0995\\u09be\\u099c\\u09c7\\u09b0 \\u099c\\u09a8\\u09cd\\u09af \\u0986\\u09ac\\u09c7\\u09a6\\u09a8 \\u0995\\u09b0\\u09c7\\u099b\\u09c7\\u09a8\\u0964\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/employer\\/applicants\",\"type\":\"apply\"}', '2026-01-02 07:19:05', '2026-01-02 07:18:39', '2026-01-02 07:19:05'),
('01e49e96-4958-46f7-ab31-c2ccc2c4d895', 'App\\Notifications\\JobNotification', 'App\\Models\\User', 1, '{\"title\":\"\\u0986\\u09ac\\u09c7\\u09a6\\u09a8 \\u0997\\u09c3\\u09b9\\u09c0\\u09a4!\",\"message\":\"\\u0985\\u09ad\\u09bf\\u09a8\\u09a8\\u09cd\\u09a6\\u09a8! \\\"74575676\\\" \\u0995\\u09be\\u099c\\u09c7 \\u0986\\u09aa\\u09a8\\u09be\\u09b0 \\u0986\\u09ac\\u09c7\\u09a6\\u09a8 \\u0997\\u09cd\\u09b0\\u09b9\\u09a3 \\u0995\\u09b0\\u09be \\u09b9\\u09df\\u09c7\\u099b\\u09c7\\u0964\",\"link\":\"#\",\"type\":\"success\"}', '2026-01-02 06:49:22', '2026-01-01 06:22:17', '2026-01-02 06:49:22'),
('086be8ff-d7e1-4f59-873b-f793a0ad8131', 'App\\Notifications\\JobNotification', 'App\\Models\\User', 1, '{\"title\":\"\\u09aa\\u09c7\\u09ae\\u09c7\\u09a8\\u09cd\\u099f \\u0986\\u09aa\\u09a1\\u09c7\\u099f!\",\"message\":\"\\u09ae\\u09be\\u09b2\\u09bf\\u0995 \\u09aa\\u09c7\\u09ae\\u09c7\\u09a8\\u09cd\\u099f \\u09a6\\u09bf\\u09df\\u09c7\\u099b\\u09c7\\u09a8 \\u09ac\\u09b2\\u09c7 \\u099c\\u09be\\u09a8\\u09bf\\u09df\\u09c7\\u099b\\u09c7\\u09a8\\u0964 \\u09a6\\u09df\\u09be \\u0995\\u09b0\\u09c7 \\u099f\\u09be\\u0995\\u09be \\u09ac\\u09c1\\u099d\\u09c7 \\u09aa\\u09c7\\u09b2\\u09c7 \\u0995\\u09a8\\u09ab\\u09be\\u09b0\\u09cd\\u09ae \\u0995\\u09b0\\u09c1\\u09a8\\u0964\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/worker\\/applied\",\"type\":\"info\"}', '2026-01-02 06:49:22', '2026-01-01 06:01:09', '2026-01-02 06:49:22'),
('0a70b3fb-0aea-4f63-ac18-1bd2ed18ba9f', 'App\\Notifications\\JobNotification', 'App\\Models\\User', 1, '{\"title\":\"\\u09ad\\u09c7\\u09b0\\u09bf\\u09ab\\u09bf\\u0995\\u09c7\\u09b6\\u09a8 \\u09aa\\u09c7\\u09a8\\u09cd\\u09a1\\u09bf\\u0982\",\"message\":\"\\u0986\\u09aa\\u09a8\\u09be\\u09b0 \\u09ad\\u09c7\\u09b0\\u09bf\\u09ab\\u09bf\\u0995\\u09c7\\u09b6\\u09a8 \\u09b0\\u09bf\\u0995\\u09cb\\u09af\\u09bc\\u09c7\\u09b8\\u09cd\\u099f \\u099c\\u09ae\\u09be \\u09b9\\u09af\\u09bc\\u09c7\\u099b\\u09c7\\u0964 \\u0985\\u09cd\\u09af\\u09be\\u09a1\\u09ae\\u09bf\\u09a8 \\u09b0\\u09bf\\u09ad\\u09bf\\u0989 \\u0995\\u09b0\\u09be\\u09b0 \\u09aa\\u09b0 \\u0986\\u09aa\\u09a8\\u09be\\u0995\\u09c7 \\u099c\\u09be\\u09a8\\u09be\\u09a8\\u09cb \\u09b9\\u09ac\\u09c7\\u0964\",\"link\":\"http:\\/\\/192.168.1.116:8000\\/worker\\/notifications\",\"type\":\"info\"}', '2026-01-01 05:27:04', '2026-01-01 05:26:34', '2026-01-01 05:27:04'),
('0d1e445e-4da7-4151-913e-fab26a8fe512', 'App\\Notifications\\JobNotification', 'App\\Models\\User', 2, '{\"title\":\"\\u09a8\\u09a4\\u09c1\\u09a8 \\u0986\\u09ac\\u09c7\\u09a6\\u09a8 \\u098f\\u09b8\\u09c7\\u099b\\u09c7!\",\"message\":\"Humayun \\u0986\\u09aa\\u09a8\\u09be\\u09b0 \\\"\\u09a7\\u09c1\\u09a6\\u09cd\\u09a6\\u09c1\\u09a6\\u09cd\\u09a7\\\" \\u0995\\u09be\\u099c\\u09c7\\u09b0 \\u099c\\u09a8\\u09cd\\u09af \\u0986\\u09ac\\u09c7\\u09a6\\u09a8 \\u0995\\u09b0\\u09c7\\u099b\\u09c7\\u09a8\\u0964\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/employer\\/applicants\",\"type\":\"apply\"}', '2026-01-02 07:33:18', '2026-01-02 07:23:52', '2026-01-02 07:33:18'),
('1802daa8-f410-48fc-853d-d557875c24d6', 'App\\Notifications\\JobNotification', 'App\\Models\\User', 2, '{\"title\":\"\\u0995\\u09be\\u099c \\u09aa\\u09cb\\u09b8\\u09cd\\u099f \\u09b8\\u09ab\\u09b2!\",\"message\":\"\\u0986\\u09aa\\u09a8\\u09be\\u09b0 \\\"74575676\\\" \\u0995\\u09be\\u099c\\u099f\\u09bf \\u09b8\\u09ab\\u09b2\\u09ad\\u09be\\u09ac\\u09c7 \\u09aa\\u09be\\u09ac\\u09b2\\u09bf\\u09b6 \\u09b9\\u09df\\u09c7\\u099b\\u09c7\\u0964\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/employer\\/home\",\"type\":\"success\"}', '2026-01-02 05:58:17', '2026-01-01 06:20:53', '2026-01-02 05:58:17'),
('19bb8691-1e4a-4e12-8441-d442764d7cd0', 'App\\Notifications\\JobNotification', 'App\\Models\\User', 1, '{\"title\":\"\\u0986\\u09ac\\u09c7\\u09a6\\u09a8 \\u0997\\u09c3\\u09b9\\u09c0\\u09a4!\",\"message\":\"\\u0985\\u09ad\\u09bf\\u09a8\\u09a8\\u09cd\\u09a6\\u09a8! \\\"\\u09e8 \\u09b0\\u09c1\\u09ae \\u098f\\u09b0 \\u09b0\\u0982 \\u0995\\u09b0\\u09a4\\u09c7 \\u09b9\\u09ac\\u09c7\\\" \\u0995\\u09be\\u099c\\u09c7 \\u0986\\u09aa\\u09a8\\u09be\\u09b0 \\u0986\\u09ac\\u09c7\\u09a6\\u09a8 \\u0997\\u09cd\\u09b0\\u09b9\\u09a3 \\u0995\\u09b0\\u09be \\u09b9\\u09af\\u09bc\\u09c7\\u099b\\u09c7\\u0964\",\"link\":\"#\",\"type\":\"success\"}', NULL, '2026-01-02 07:14:15', '2026-01-02 07:14:15'),
('1b8bacee-f109-4748-8f93-a2f66b45c2f6', 'App\\Notifications\\JobNotification', 'App\\Models\\User', 2, '{\"title\":\"\\u0995\\u09be\\u099c \\u09aa\\u09cb\\u09b8\\u09cd\\u099f \\u09b8\\u09ab\\u09b2!\",\"message\":\"\\u0986\\u09aa\\u09a8\\u09be\\u09b0 \\\"567657\\\" \\u0995\\u09be\\u099c\\u099f\\u09bf \\u09b8\\u09ab\\u09b2\\u09ad\\u09be\\u09ac\\u09c7 \\u09aa\\u09be\\u09ac\\u09b2\\u09bf\\u09b6 \\u09b9\\u09af\\u09bc\\u09c7\\u099b\\u09c7\\u0964\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/employer\\/home\",\"type\":\"success\"}', '2026-01-02 05:58:17', '2026-01-01 05:16:37', '2026-01-02 05:58:17'),
('1ba68b94-3bf9-418c-b2aa-c22ec51899cd', 'App\\Notifications\\JobNotification', 'App\\Models\\User', 1, '{\"title\":\"\\u0986\\u09ac\\u09c7\\u09a6\\u09a8 \\u0997\\u09c3\\u09b9\\u09c0\\u09a4!\",\"message\":\"\\u0985\\u09ad\\u09bf\\u09a8\\u09a8\\u09cd\\u09a6\\u09a8! \\\"567657\\\" \\u0995\\u09be\\u099c\\u09c7 \\u0986\\u09aa\\u09a8\\u09be\\u09b0 \\u0986\\u09ac\\u09c7\\u09a6\\u09a8 \\u0997\\u09cd\\u09b0\\u09b9\\u09a3 \\u0995\\u09b0\\u09be \\u09b9\\u09af\\u09bc\\u09c7\\u099b\\u09c7\\u0964\",\"link\":\"#\",\"type\":\"success\"}', '2026-01-02 06:49:22', '2026-01-01 05:33:58', '2026-01-02 06:49:22'),
('48e362e4-de1d-41c6-b564-038eb03dd704', 'App\\Notifications\\JobNotification', 'App\\Models\\User', 2, '{\"title\":\"\\u09a8\\u09a4\\u09c1\\u09a8 \\u0986\\u09ac\\u09c7\\u09a6\\u09a8 \\u098f\\u09b8\\u09c7\\u099b\\u09c7!\",\"message\":\"Humayun \\u0986\\u09aa\\u09a8\\u09be\\u09b0 \\\"74575676\\\" \\u0995\\u09be\\u099c\\u09c7\\u09b0 \\u099c\\u09a8\\u09cd\\u09af \\u0986\\u09ac\\u09c7\\u09a6\\u09a8 \\u0995\\u09b0\\u09c7\\u099b\\u09c7\\u09a8\\u0964\",\"link\":\"http:\\/\\/192.168.1.116:8000\\/employer\\/applicants\",\"type\":\"apply\"}', '2026-01-02 05:58:17', '2026-01-01 06:21:33', '2026-01-02 05:58:17'),
('4be10993-cf81-4254-ae4d-70b39b313957', 'App\\Notifications\\JobNotification', 'App\\Models\\User', 2, '{\"title\":\"\\u0995\\u09be\\u099c \\u09b8\\u09ab\\u09b2\\u09ad\\u09be\\u09ac\\u09c7 \\u09b8\\u09ae\\u09cd\\u09aa\\u09a8\\u09cd\\u09a8!\",\"message\":\"Humayun \\u09aa\\u09c7\\u09ae\\u09c7\\u09a8\\u09cd\\u099f \\u09ac\\u09c1\\u099d\\u09c7 \\u09aa\\u09c7\\u09df\\u09c7\\u099b\\u09c7\\u09a8\\u0964 \\u0995\\u09be\\u099c\\u099f\\u09bf\\u09b0 \\u09b8\\u09cd\\u099f\\u09cd\\u09af\\u09be\\u099f\\u09be\\u09b8 \\u098f\\u0996\\u09a8 \\u0995\\u09ae\\u09aa\\u09cd\\u09b2\\u09bf\\u099f\\u0964\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/employer\\/home\",\"type\":\"success\"}', '2026-01-02 07:17:41', '2026-01-02 07:17:13', '2026-01-02 07:17:41'),
('5e0a3dd2-f50b-4a33-a579-66868faa91ad', 'App\\Notifications\\JobNotification', 'App\\Models\\User', 2, '{\"title\":\"\\u0995\\u09b0\\u09cd\\u09ae\\u09c0 \\u09aa\\u09cc\\u099b\\u09c7\\u099b\\u09c7\",\"message\":\"Humayun \\u0995\\u09be\\u099c\\u09c7\\u09b0 \\u09b2\\u09cb\\u0995\\u09c7\\u09b6\\u09a8\\u09c7 \\u09aa\\u09cc\\u0981\\u099b\\u09c7 \\u0997\\u09bf\\u09af\\u09bc\\u09c7\\u099b\\u09c7\\u09a8\\u0964\",\"link\":\"http:\\/\\/192.168.1.116:8000\\/employer\\/home\",\"type\":\"arrived\"}', '2026-01-02 05:58:17', '2026-01-01 05:38:48', '2026-01-02 05:58:17'),
('64f8ace7-a716-48dd-b952-59d1dcec4316', 'App\\Notifications\\JobNotification', 'App\\Models\\User', 2, '{\"title\":\"\\u09a8\\u09a4\\u09c1\\u09a8 \\u0986\\u09ac\\u09c7\\u09a6\\u09a8 \\u098f\\u09b8\\u09c7\\u099b\\u09c7!\",\"message\":\"Humayun \\u0986\\u09aa\\u09a8\\u09be\\u09b0 \\\"\\u09e8 \\u09b0\\u09c1\\u09ae \\u098f\\u09b0 \\u09b0\\u0982 \\u0995\\u09b0\\u09a4\\u09c7 \\u09b9\\u09ac\\u09c7\\\" \\u0995\\u09be\\u099c\\u09c7\\u09b0 \\u099c\\u09a8\\u09cd\\u09af \\u0986\\u09ac\\u09c7\\u09a6\\u09a8 \\u0995\\u09b0\\u09c7\\u099b\\u09c7\\u09a8\\u0964\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/employer\\/applicants\",\"type\":\"apply\"}', '2026-01-02 07:16:06', '2026-01-02 07:13:37', '2026-01-02 07:16:06'),
('6882c0b8-a682-4a2d-a23a-2226bf118806', 'App\\Notifications\\JobNotification', 'App\\Models\\User', 2, '{\"title\":\"\\u0995\\u09be\\u099c \\u09aa\\u09cb\\u09b8\\u09cd\\u099f \\u09b8\\u09ab\\u09b2!\",\"message\":\"\\u0986\\u09aa\\u09a8\\u09be\\u09b0 \\\"\\u09e8 \\u09b0\\u09c1\\u09ae \\u098f\\u09b0 \\u09b0\\u0982 \\u0995\\u09b0\\u09a4\\u09c7 \\u09b9\\u09ac\\u09c7\\\" \\u0995\\u09be\\u099c\\u099f\\u09bf \\u09b8\\u09ab\\u09b2\\u09ad\\u09be\\u09ac\\u09c7 \\u09aa\\u09be\\u09ac\\u09b2\\u09bf\\u09b6 \\u09b9\\u09af\\u09bc\\u09c7\\u099b\\u09c7\\u0964\",\"link\":\"http:\\/\\/192.168.1.116:8000\\/employer\\/home\",\"type\":\"success\"}', '2026-01-02 07:16:06', '2026-01-02 07:12:39', '2026-01-02 07:16:06'),
('734d98a7-7004-42b4-978b-1b5fe913d9e7', 'App\\Notifications\\JobNotification', 'App\\Models\\User', 1, '{\"title\":\"\\u09b0\\u09bf\\u099a\\u09be\\u09b0\\u09cd\\u099c \\u09b0\\u09bf\\u0995\\u09cb\\u09af\\u09bc\\u09c7\\u09b8\\u09cd\\u099f \\u09b8\\u09ab\\u09b2\",\"message\":\"\\u0986\\u09aa\\u09a8\\u09be\\u09b0 50 \\u099f\\u09be\\u0995\\u09be\\u09b0 \\u09b0\\u09bf\\u099a\\u09be\\u09b0\\u09cd\\u099c \\u09b0\\u09bf\\u0995\\u09cb\\u09af\\u09bc\\u09c7\\u09b8\\u09cd\\u099f\\u099f\\u09bf \\u09aa\\u09c7\\u09a8\\u09cd\\u09a1\\u09bf\\u0982 \\u0986\\u099b\\u09c7\\u0964\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/worker\\/notifications\",\"type\":\"wallet\"}', NULL, '2026-01-02 07:22:35', '2026-01-02 07:22:35'),
('7d8b8049-2ba6-47a5-a112-f4f5c9f00d51', 'App\\Notifications\\JobNotification', 'App\\Models\\User', 2, '{\"title\":\"\\u0995\\u09be\\u099c \\u09aa\\u09cb\\u09b8\\u09cd\\u099f \\u09b8\\u09ab\\u09b2!\",\"message\":\"\\u0986\\u09aa\\u09a8\\u09be\\u09b0 \\\"\\u09a7\\u09c1\\u09a6\\u09cd\\u09a6\\u09c1\\u09a6\\u09cd\\u09a7\\\" \\u0995\\u09be\\u099c\\u099f\\u09bf \\u09b8\\u09ab\\u09b2\\u09ad\\u09be\\u09ac\\u09c7 \\u09aa\\u09be\\u09ac\\u09b2\\u09bf\\u09b6 \\u09b9\\u09af\\u09bc\\u09c7\\u099b\\u09c7\\u0964\",\"link\":\"http:\\/\\/192.168.1.116:8000\\/employer\\/home\",\"type\":\"success\"}', '2026-01-02 07:16:06', '2026-01-02 07:10:08', '2026-01-02 07:16:06'),
('899b812e-b6f9-4af1-876d-7ebc8c702d20', 'App\\Notifications\\JobNotification', 'App\\Models\\User', 2, '{\"title\":\"\\u0995\\u09be\\u099c \\u09aa\\u09cb\\u09b8\\u09cd\\u099f \\u09b8\\u09ab\\u09b2!\",\"message\":\"\\u0986\\u09aa\\u09a8\\u09be\\u09b0 \\\"6567457\\\" \\u0995\\u09be\\u099c\\u099f\\u09bf \\u09b8\\u09ab\\u09b2\\u09ad\\u09be\\u09ac\\u09c7 \\u09aa\\u09be\\u09ac\\u09b2\\u09bf\\u09b6 \\u09b9\\u09df\\u09c7\\u099b\\u09c7\\u0964\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/employer\\/home\",\"type\":\"success\"}', '2026-01-02 05:58:17', '2026-01-01 06:18:35', '2026-01-02 05:58:17'),
('8d86ee18-c641-41da-a47c-c6596d865606', 'App\\Notifications\\JobNotification', 'App\\Models\\User', 1, '{\"title\":\"\\u09aa\\u09c7\\u09ae\\u09c7\\u09a8\\u09cd\\u099f \\u0986\\u09aa\\u09a1\\u09c7\\u099f!\",\"message\":\"\\u09ae\\u09be\\u09b2\\u09bf\\u0995 \\u09aa\\u09c7\\u09ae\\u09c7\\u09a8\\u09cd\\u099f \\u09a6\\u09bf\\u09df\\u09c7\\u099b\\u09c7\\u09a8 \\u09ac\\u09b2\\u09c7 \\u099c\\u09be\\u09a8\\u09bf\\u09df\\u09c7\\u099b\\u09c7\\u09a8\\u0964 \\u09a6\\u09df\\u09be \\u0995\\u09b0\\u09c7 \\u099f\\u09be\\u0995\\u09be \\u09ac\\u09c1\\u099d\\u09c7 \\u09aa\\u09c7\\u09b2\\u09c7 \\u0995\\u09a8\\u09ab\\u09be\\u09b0\\u09cd\\u09ae \\u0995\\u09b0\\u09c1\\u09a8\\u0964\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/worker\\/applied\",\"type\":\"info\"}', '2026-01-02 06:49:22', '2026-01-01 06:01:06', '2026-01-02 06:49:22'),
('93f482a4-80cf-48c0-9455-6c7a1a57b47a', 'App\\Notifications\\JobNotification', 'App\\Models\\User', 1, '{\"title\":\"\\u09aa\\u09c7\\u09ae\\u09c7\\u09a8\\u09cd\\u099f \\u0986\\u09aa\\u09a1\\u09c7\\u099f!\",\"message\":\"\\u09ae\\u09be\\u09b2\\u09bf\\u0995 \\u09aa\\u09c7\\u09ae\\u09c7\\u09a8\\u09cd\\u099f \\u09a6\\u09bf\\u09af\\u09bc\\u09c7\\u099b\\u09c7\\u09a8 \\u09ac\\u09b2\\u09c7 \\u099c\\u09be\\u09a8\\u09bf\\u09af\\u09bc\\u09c7\\u099b\\u09c7\\u09a8\\u0964 \\u099f\\u09be\\u0995\\u09be \\u09ac\\u09c1\\u099d\\u09c7 \\u09aa\\u09c7\\u09b2\\u09c7 \\u0995\\u09a8\\u09ab\\u09be\\u09b0\\u09cd\\u09ae \\u0995\\u09b0\\u09c1\\u09a8\\u0964\",\"link\":\"http:\\/\\/192.168.1.116:8000\\/worker\\/applied\",\"type\":\"info\"}', NULL, '2026-01-02 07:16:38', '2026-01-02 07:16:38'),
('9429bb07-559a-4b75-bd75-399b0de8a446', 'App\\Notifications\\JobNotification', 'App\\Models\\User', 2, '{\"title\":\"\\u09a8\\u09a4\\u09c1\\u09a8 \\u0986\\u09ac\\u09c7\\u09a6\\u09a8 \\u098f\\u09b8\\u09c7\\u099b\\u09c7!\",\"message\":\"Humayun \\u0986\\u09aa\\u09a8\\u09be\\u09b0 \\\"567657\\\" \\u0995\\u09be\\u099c\\u09c7\\u09b0 \\u099c\\u09a8\\u09cd\\u09af \\u0986\\u09ac\\u09c7\\u09a6\\u09a8 \\u0995\\u09b0\\u09c7\\u099b\\u09c7\\u09a8\\u0964\",\"link\":\"http:\\/\\/192.168.1.116:8000\\/employer\\/applicants\",\"type\":\"apply\"}', '2026-01-02 05:58:17', '2026-01-01 05:32:23', '2026-01-02 05:58:17'),
('970cec73-4b63-4c96-bbf6-ab925d44b3f2', 'App\\Notifications\\JobNotification', 'App\\Models\\User', 1, '{\"title\":\"\\u09b0\\u09bf\\u099a\\u09be\\u09b0\\u09cd\\u099c \\u09b0\\u09bf\\u0995\\u09cb\\u09af\\u09bc\\u09c7\\u09b8\\u09cd\\u099f \\u09b8\\u09ab\\u09b2\",\"message\":\"\\u0986\\u09aa\\u09a8\\u09be\\u09b0 50 \\u099f\\u09be\\u0995\\u09be\\u09b0 \\u09b0\\u09bf\\u099a\\u09be\\u09b0\\u09cd\\u099c \\u09b0\\u09bf\\u0995\\u09cb\\u09af\\u09bc\\u09c7\\u09b8\\u09cd\\u099f\\u099f\\u09bf \\u09aa\\u09c7\\u09a8\\u09cd\\u09a1\\u09bf\\u0982 \\u0986\\u099b\\u09c7\\u0964\",\"link\":\"http:\\/\\/192.168.1.116:8000\\/worker\\/notifications\",\"type\":\"wallet\"}', '2026-01-01 05:32:33', '2026-01-01 05:28:57', '2026-01-01 05:32:33'),
('9ae15734-2c59-4bef-90e9-a40618e16b8d', 'App\\Notifications\\JobNotification', 'App\\Models\\User', 2, '{\"title\":\"\\u0995\\u09b0\\u09cd\\u09ae\\u09c0 \\u09aa\\u09cc\\u099b\\u09c7\\u099b\\u09c7\",\"message\":\"Humayun \\u0995\\u09be\\u099c\\u09c7\\u09b0 \\u09b2\\u09cb\\u0995\\u09c7\\u09b6\\u09a8\\u09c7 \\u09aa\\u09cc\\u0981\\u099b\\u09c7 \\u0997\\u09bf\\u09af\\u09bc\\u09c7\\u099b\\u09c7\\u09a8\\u0964\",\"link\":\"http:\\/\\/192.168.1.116:8000\\/employer\\/home\",\"type\":\"arrived\"}', '2026-01-02 05:58:17', '2026-01-01 06:22:30', '2026-01-02 05:58:17'),
('9e5b5ff2-0c3b-4025-8003-3749299fe66e', 'App\\Notifications\\JobNotification', 'App\\Models\\User', 1, '{\"title\":\"\\u0986\\u09ac\\u09c7\\u09a6\\u09a8 \\u09ac\\u09be\\u09a4\\u09bf\\u09b2\",\"message\":\"\\u09a6\\u09c1\\u0983\\u0996\\u09bf\\u09a4, \\\"\\u09a7\\u09c1\\u09a6\\u09cd\\u09a6\\u09c1\\u09a6\\u09cd\\u09a7\\\" \\u0995\\u09be\\u099c\\u09c7 \\u0986\\u09aa\\u09a8\\u09be\\u09b0 \\u0986\\u09ac\\u09c7\\u09a6\\u09a8\\u099f\\u09bf \\u0997\\u09cd\\u09b0\\u09b9\\u09a3 \\u0995\\u09b0\\u09be \\u09b9\\u09af\\u09bc\\u09a8\\u09bf\\u0964\",\"link\":\"#\",\"type\":\"danger\"}', NULL, '2026-01-02 07:24:12', '2026-01-02 07:24:12'),
('a8e192ff-1f8d-4865-9589-1a84ca63df87', 'App\\Notifications\\JobNotification', 'App\\Models\\User', 1, '{\"title\":\"\\u09aa\\u09c7\\u09ae\\u09c7\\u09a8\\u09cd\\u099f \\u0986\\u09aa\\u09a1\\u09c7\\u099f!\",\"message\":\"\\\"\\u09e8 \\u09b0\\u09c1\\u09ae \\u09b0\\u0982 \\u0995\\u09b0\\u09a4\\u09c7 \\u09b9\\u09ac\\u09c7\\u0964\\\" \\u0995\\u09be\\u099c\\u09c7\\u09b0 \\u099f\\u09be\\u0995\\u09be \\u0993\\u09af\\u09bc\\u09be\\u09b2\\u09c7\\u099f\\u09c7 \\u09af\\u09cb\\u0997 \\u09b9\\u09af\\u09bc\\u09c7\\u099b\\u09c7\\u0964\",\"link\":\"http:\\/\\/192.168.1.116:8000\\/worker\\/applied\",\"type\":\"info\"}', NULL, '2026-01-02 07:19:35', '2026-01-02 07:19:35'),
('ab437644-ce3d-4732-bf6c-c170ae411b41', 'App\\Notifications\\JobNotification', 'App\\Models\\User', 2, '{\"title\":\"\\u0995\\u09be\\u099c \\u09b8\\u09ab\\u09b2\\u09ad\\u09be\\u09ac\\u09c7 \\u09b8\\u09ae\\u09cd\\u09aa\\u09a8\\u09cd\\u09a8!\",\"message\":\"Humayun \\u09aa\\u09c7\\u09ae\\u09c7\\u09a8\\u09cd\\u099f \\u09ac\\u09c1\\u099d\\u09c7 \\u09aa\\u09c7\\u09df\\u09c7\\u099b\\u09c7\\u09a8\\u0964 \\u0995\\u09be\\u099c\\u099f\\u09bf\\u09b0 \\u09b8\\u09cd\\u099f\\u09cd\\u09af\\u09be\\u099f\\u09be\\u09b8 \\u098f\\u0996\\u09a8 \\u0995\\u09ae\\u09aa\\u09cd\\u09b2\\u09bf\\u099f\\u0964\",\"link\":\"http:\\/\\/192.168.1.116:8000\\/employer\\/home\",\"type\":\"success\"}', '2026-01-02 05:58:17', '2026-01-01 06:05:29', '2026-01-02 05:58:17'),
('ac42a536-e3df-4ca5-a90b-49a418721d08', 'App\\Notifications\\JobNotification', 'App\\Models\\User', 2, '{\"title\":\"\\u0995\\u09b0\\u09cd\\u09ae\\u09c0 \\u09aa\\u09cc\\u099b\\u09c7\\u099b\\u09c7\",\"message\":\"Humayun \\u0995\\u09be\\u099c\\u09c7\\u09b0 \\u09b2\\u09cb\\u0995\\u09c7\\u09b6\\u09a8\\u09c7 \\u09aa\\u09cc\\u0981\\u099b\\u09c7 \\u0997\\u09bf\\u09af\\u09bc\\u09c7\\u099b\\u09c7\\u09a8\\u0964\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/employer\\/home\",\"type\":\"arrived\"}', '2026-01-02 07:16:06', '2026-01-02 07:15:53', '2026-01-02 07:16:06'),
('c7f2fb94-6189-4488-a138-1fbcda532719', 'App\\Notifications\\JobNotification', 'App\\Models\\User', 1, '{\"title\":\"\\u09aa\\u09c7\\u09ae\\u09c7\\u09a8\\u09cd\\u099f \\u0986\\u09aa\\u09a1\\u09c7\\u099f!\",\"message\":\"\\\"74575676\\\" \\u0995\\u09be\\u099c\\u09c7\\u09b0 \\u099f\\u09be\\u0995\\u09be \\u0993\\u09df\\u09be\\u09b2\\u09c7\\u099f\\u09c7 \\u09af\\u09cb\\u0997 \\u09b9\\u09df\\u09c7\\u099b\\u09c7\\u0964\",\"link\":\"http:\\/\\/127.0.0.1:8000\\/worker\\/applied\",\"type\":\"info\"}', '2026-01-02 06:49:22', '2026-01-01 06:25:06', '2026-01-02 06:49:22'),
('dbb563ba-d075-4da8-89c5-a85a01917fde', 'App\\Notifications\\JobNotification', 'App\\Models\\User', 2, '{\"title\":\"\\u0995\\u09be\\u099c \\u09aa\\u09cb\\u09b8\\u09cd\\u099f \\u09b8\\u09ab\\u09b2!\",\"message\":\"\\u0986\\u09aa\\u09a8\\u09be\\u09b0 \\\"\\u09e8 \\u09b0\\u09c1\\u09ae \\u09b0\\u0982 \\u0995\\u09b0\\u09a4\\u09c7 \\u09b9\\u09ac\\u09c7\\\" \\u0995\\u09be\\u099c\\u099f\\u09bf \\u09b8\\u09ab\\u09b2\\u09ad\\u09be\\u09ac\\u09c7 \\u09aa\\u09be\\u09ac\\u09b2\\u09bf\\u09b6 \\u09b9\\u09af\\u09bc\\u09c7\\u099b\\u09c7\\u0964\",\"link\":\"http:\\/\\/192.168.1.116:8000\\/employer\\/home\",\"type\":\"success\"}', NULL, '2026-01-02 07:50:40', '2026-01-02 07:50:40'),
('f9a4bc8e-c90b-4d11-97c3-d1e594867ba0', 'App\\Notifications\\JobNotification', 'App\\Models\\User', 2, '{\"title\":\"\\u0995\\u09be\\u099c \\u09aa\\u09cb\\u09b8\\u09cd\\u099f \\u09b8\\u09ab\\u09b2!\",\"message\":\"\\u0986\\u09aa\\u09a8\\u09be\\u09b0 \\\"\\u09e8 \\u09b0\\u09c1\\u09ae \\u09b0\\u0982 \\u0995\\u09b0\\u09a4\\u09c7 \\u09b9\\u09ac\\u09c7\\u0964\\\" \\u0995\\u09be\\u099c\\u099f\\u09bf \\u09b8\\u09ab\\u09b2\\u09ad\\u09be\\u09ac\\u09c7 \\u09aa\\u09be\\u09ac\\u09b2\\u09bf\\u09b6 \\u09b9\\u09df\\u09c7\\u099b\\u09c7\\u0964\",\"link\":\"http:\\/\\/192.168.1.116:8000\\/employer\\/home\",\"type\":\"success\"}', '2026-01-02 06:37:50', '2026-01-02 06:37:43', '2026-01-02 06:37:50'),
('f9ba1d6a-71c9-4fd3-86dd-00ae238bd4b7', 'App\\Notifications\\JobNotification', 'App\\Models\\User', 1, '{\"title\":\"\\u0986\\u09ac\\u09c7\\u09a6\\u09a8 \\u0997\\u09c3\\u09b9\\u09c0\\u09a4!\",\"message\":\"\\u0985\\u09ad\\u09bf\\u09a8\\u09a8\\u09cd\\u09a6\\u09a8! \\\"\\u09e8 \\u09b0\\u09c1\\u09ae \\u09b0\\u0982 \\u0995\\u09b0\\u09a4\\u09c7 \\u09b9\\u09ac\\u09c7\\u0964\\\" \\u0995\\u09be\\u099c\\u09c7 \\u0986\\u09aa\\u09a8\\u09be\\u09b0 \\u0986\\u09ac\\u09c7\\u09a6\\u09a8 \\u0997\\u09cd\\u09b0\\u09b9\\u09a3 \\u0995\\u09b0\\u09be \\u09b9\\u09af\\u09bc\\u09c7\\u099b\\u09c7\\u0964\",\"link\":\"#\",\"type\":\"success\"}', NULL, '2026-01-02 07:19:32', '2026-01-02 07:19:32');

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
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reporter_id` bigint(20) UNSIGNED NOT NULL,
  `reported_id` bigint(20) UNSIGNED NOT NULL,
  `reason` text NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `worker_id` bigint(20) UNSIGNED NOT NULL,
  `employer_id` bigint(20) UNSIGNED NOT NULL,
  `job_id` bigint(20) UNSIGNED DEFAULT NULL,
  `rating` int(11) NOT NULL DEFAULT 5,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `worker_id`, `employer_id`, `job_id`, `rating`, `comment`, `created_at`, `updated_at`) VALUES
(1, 1, 2, NULL, 4, 'সময়ে কাজ শেষ করেছেন। ধন্যবাদ।', '2026-01-01 06:10:06', '2026-01-01 06:10:06'),
(2, 1, 2, NULL, 5, 'অসাধারণ এবং সুন্দর কাজ!', '2026-01-01 06:15:17', '2026-01-01 06:15:17'),
(3, 1, 2, 3, 2, 'খুবই প্রফেশনাল আচরণ।', '2026-01-01 06:26:22', '2026-01-01 06:26:22'),
(4, 1, 2, 1, 5, 'সময়ে কাজ শেষ করেছেন। ধন্যবাদ।', '2026-01-01 06:26:30', '2026-01-01 06:26:30'),
(5, 1, 2, 6, 5, 'অসাধারণ এবং সুন্দর কাজ!', '2026-01-02 07:17:35', '2026-01-02 07:17:35');

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

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'bkash_number', '01768802953', '2026-01-02 07:22:14', '2026-01-02 07:22:14'),
(2, 'nagad_number', '01768802953', '2026-01-02 07:22:14', '2026-01-02 07:22:14'),
(3, 'apply_fee', '5', '2026-01-02 07:22:14', '2026-01-02 07:23:37'),
(4, 'min_wallet_balance', '50', '2026-01-02 07:22:14', '2026-01-02 07:22:14'),
(5, 'registration_enabled', '1', '2026-01-02 07:22:14', '2026-01-02 07:22:14'),
(6, 'support_phone', '01700000000', '2026-01-02 07:22:14', '2026-01-02 07:22:14'),
(7, 'maintenance_mode', '0', '2026-01-02 07:22:14', '2026-01-02 07:22:14');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `type` enum('deposit','debit') NOT NULL,
  `purpose` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'completed',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `amount`, `type`, `purpose`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 50.00, 'deposit', 'Wallet Deposit (Bkash)', 'completed', '2026-01-01 05:29:05', '2026-01-01 05:29:05'),
(2, 1, 10.00, 'debit', 'আবেদন ফি: 567657', 'completed', '2026-01-01 05:32:23', '2026-01-01 05:32:23'),
(3, 2, 57774.00, 'deposit', 'Wallet Deposit (Bkash)', 'completed', '2026-01-01 05:35:11', '2026-01-01 05:35:11'),
(4, 1, 10.00, 'debit', 'আবেদন ফি: 74575676', 'completed', '2026-01-01 06:21:33', '2026-01-01 06:21:33'),
(5, 1, 10.00, 'debit', 'আবেদন ফি: ২ রুম এর রং করতে হবে', 'completed', '2026-01-02 07:13:36', '2026-01-02 07:13:36'),
(6, 1, 10.00, 'debit', 'আবেদন ফি: ২ রুম রং করতে হবে।', 'completed', '2026-01-02 07:18:39', '2026-01-02 07:18:39'),
(7, 1, 50.00, 'deposit', 'Wallet Deposit (Bkash)', 'completed', '2026-01-02 07:23:00', '2026-01-02 07:23:00'),
(8, 1, 5.00, 'debit', 'আবেদন ফি: ধুদ্দুদ্ধ', 'completed', '2026-01-02 07:23:52', '2026-01-02 07:23:52');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','worker','employer') NOT NULL DEFAULT 'employer',
  `category` varchar(255) DEFAULT NULL,
  `expected_wage` decimal(10,2) DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT NULL,
  `business_name` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `latitude` varchar(255) DEFAULT NULL,
  `longitude` varchar(255) DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `otp_code` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `total_earnings` decimal(10,2) NOT NULL DEFAULT 0.00,
  `location_name` varchar(255) DEFAULT NULL,
  `nid_status` varchar(255) NOT NULL DEFAULT 'pending',
  `nid_number` varchar(255) DEFAULT NULL,
  `nid_photo_front` varchar(255) DEFAULT NULL,
  `nid_photo_back` varchar(255) DEFAULT NULL,
  `verification_status` enum('unverified','pending','verified','rejected') NOT NULL DEFAULT 'unverified',
  `rejection_reason` text DEFAULT NULL,
  `is_banned` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `phone`, `email`, `balance`, `email_verified_at`, `password`, `role`, `category`, `expected_wage`, `profile_photo`, `business_name`, `address`, `latitude`, `longitude`, `is_verified`, `otp_code`, `remember_token`, `created_at`, `updated_at`, `total_earnings`, `location_name`, `nid_status`, `nid_number`, `nid_photo_front`, `nid_photo_back`, `verification_status`, `rejection_reason`, `is_banned`) VALUES
(1, 'Humayun Ahmed', '+8801768802953', 'humayunahmed04032002@gmail.com', 1755.00, NULL, '$2y$12$8Z6CRWQfYBdncNe7JlVBD.Rfw/G6mB.YgXxsYglawy9E4xwWCQJu.', 'worker', 'নির্মাণ শ্রমিক', 500.00, 'profile_photos/8o3YJY6FuUllIDEhGzQq34ZlDCVtHZEgMebbgnCr.jpg', NULL, 'Bogura', NULL, NULL, 0, NULL, NULL, '2026-01-01 05:10:20', '2026-01-13 06:07:15', 0.00, NULL, 'pending', '474637373377', 'verifications/nid/ZMDsbdaQ7NZXESHTf6H7gpqN89p91QcO4RkIl3ef.jpg', 'verifications/nid/AfyokkqzDifWuKSUUxEHsBwFNurZ00Z6wDEZpg0V.jpg', 'verified', NULL, 0),
(2, 'yrtytrytry', '+88012345678', NULL, 55074.00, NULL, '$2y$12$5mle05aZE0Qwc3oYv220vOGI5JC9f6oLKBh4oGUdPhUBdSnBxec2y', 'employer', NULL, NULL, NULL, 'ty8867', '7686758675', NULL, NULL, 0, NULL, NULL, '2026-01-01 05:11:23', '2026-01-02 06:37:41', 0.00, NULL, 'pending', NULL, NULL, NULL, 'unverified', NULL, 0),
(3, 'Admin Humayun', '+8801700000000', 'admin@gmail.com', 0.00, NULL, '$2y$12$ze6pM6.40jQzemJVqNXxXuqTXQ.PMdA.Xp4MCF0M36epXj4MwLV3e', 'admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '2026-01-01 05:21:04', '2026-01-01 05:21:04', 0.00, NULL, 'pending', NULL, NULL, NULL, 'unverified', NULL, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `applications_job_id_foreign` (`job_id`),
  ADD KEY `applications_worker_id_foreign` (`worker_id`);

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
-- Indexes for table `deposit_requests`
--
ALTER TABLE `deposit_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `deposit_requests_transaction_id_unique` (`transaction_id`),
  ADD KEY `deposit_requests_user_id_foreign` (`user_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_employer_id_foreign` (`employer_id`);

--
-- Indexes for table `job_applications`
--
ALTER TABLE `job_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_applications_job_id_foreign` (`job_id`),
  ADD KEY `job_applications_worker_id_foreign` (`worker_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reports_reporter_id_foreign` (`reporter_id`),
  ADD KEY `reports_reported_id_foreign` (`reported_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_worker_id_foreign` (`worker_id`),
  ADD KEY `reviews_employer_id_foreign` (`employer_id`),
  ADD KEY `reviews_job_id_foreign` (`job_id`);

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
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transactions_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_phone_unique` (`phone`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `deposit_requests`
--
ALTER TABLE `deposit_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `job_applications`
--
ALTER TABLE `job_applications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_job_id_foreign` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applications_worker_id_foreign` FOREIGN KEY (`worker_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `deposit_requests`
--
ALTER TABLE `deposit_requests`
  ADD CONSTRAINT `deposit_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `jobs_employer_id_foreign` FOREIGN KEY (`employer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `job_applications`
--
ALTER TABLE `job_applications`
  ADD CONSTRAINT `job_applications_job_id_foreign` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `job_applications_worker_id_foreign` FOREIGN KEY (`worker_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_reported_id_foreign` FOREIGN KEY (`reported_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reports_reporter_id_foreign` FOREIGN KEY (`reporter_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_employer_id_foreign` FOREIGN KEY (`employer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_job_id_foreign` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_worker_id_foreign` FOREIGN KEY (`worker_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
