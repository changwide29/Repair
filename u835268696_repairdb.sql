-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 22, 2026 at 12:19 PM
-- Server version: 11.8.9-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u835268696_repairdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` varchar(255) NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` smallint(5) UNSIGNED NOT NULL,
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
(4, '2026_10_18_091729_create_repair_tickets_table', 1);

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
-- Table structure for table `repair_tickets`
--

CREATE TABLE `repair_tickets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_name` varchar(255) NOT NULL COMMENT 'ชื่อลูกค้า',
  `customer_email` varchar(255) NOT NULL COMMENT 'อีเมลลูกค้า',
  `device_name` varchar(255) NOT NULL COMMENT 'ชื่ออุปกรณ์/รุ่น',
  `problem_description` text NOT NULL COMMENT 'อาการเสีย',
  `image_path` varchar(255) DEFAULT NULL,
  `status` enum('pending','in_progress','completed') NOT NULL DEFAULT 'pending' COMMENT 'สถานะงานซ่อม',
  `admin_note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `repair_tickets`
--

INSERT INTO `repair_tickets` (`id`, `customer_name`, `customer_email`, `device_name`, `problem_description`, `image_path`, `status`, `admin_note`, `created_at`, `updated_at`) VALUES
(1, 'ลูกค้าทดสอบ', '674230002@webmail.npru.ac.th', 'IPAD', 'พัง', NULL, 'pending', NULL, '2026-10-18 10:18:33', '2026-10-18 10:18:33'),
(2, 'ลูกค้าทดสอบ', '674230002@webmail.npru.ac.th', 'IPAD 10', 'พัง', NULL, 'pending', NULL, '2026-10-18 10:21:10', '2026-10-18 10:21:10'),
(3, 'ลูกค้าทดสอบ', '674230002@webmail.npru.ac.th', 'IPAD', 'พัง', NULL, 'pending', NULL, '2026-10-18 10:23:55', '2026-10-18 10:23:55'),
(4, 'ลูกค้าทดสอบ', '674230002@webmail.npru.ac.th', 'IPAD', 'ำำำ', NULL, 'pending', NULL, '2026-10-18 10:27:55', '2026-10-18 10:27:55'),
(5, 'ลูกค้าทดสอบ', '674230002@webmail.npru.ac.th', 'กกก', 'ดดด', NULL, 'in_progress', NULL, '2026-10-18 10:28:01', '2026-10-18 13:26:38'),
(7, 'ลูกค้าทดสอบ', '674230002@webmail.npru.ac.th', 'IPAD', 'กก', NULL, 'completed', NULL, '2026-10-18 10:40:39', '2026-10-18 13:15:04'),
(8, 'แพรว ยิ้มแป้น', '674230034@webmail.npru.ac.th', 'IPAD 10', 'อ๋อง', 'repair_images/EpnL0MTwjFuLmLs512Ha942erJC9ehKF00aVkD14.jpg', 'in_progress', NULL, '2026-10-18 13:12:36', '2026-10-18 13:26:28');

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
('hAy6CGmNnARguHTXUtp0QuYV150ZJae2JFbrsaqQ', 1, '27.130.212.204', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJqc2FMWTZDbTgxMUVYR0tkRzIxMlZOdFk1QTJzdXJ1NGJIVXBtMEdBIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJ1cmwiOnsiaW50ZW5kZWQiOiJodHRwczpcL1wvY2dyYWNpbmd0aC5jb21cL3JlcGFpciJ9LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cHM6XC9cL2NncmFjaW5ndGguY29tXC9yZXBhaXJcL2FkbWluIiwicm91dGUiOiJhZG1pbi5pbmRleCJ9LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwicGFzc3dvcmRfaGFzaF93ZWIiOiIzMjU2ZDA4NGJiNjU3MGJiMjAzMWMwMWVhMjZmNGJmZjk1ZTQwMTUxNzhhNDYzOGE2MWQ3MWZmYmIwZTFkZjkyIn0=', 1792373712),
('vyDsYb227KItU7p1UcpyFte21HsvTmINBxz28Brs', NULL, '27.130.212.204', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/155.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiIwN2p3MVJlRTAzY0lUZ3M4VG9QSEw2MUFrR0VsZ1BRWFdoVGNNSDd0IiwidXJsIjp7ImludGVuZGVkIjoiaHR0cHM6XC9cL2NncmFjaW5ndGguY29tXC9yZXBhaXIifSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC9jZ3JhY2luZ3RoLmNvbVwvcmVwYWlyXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1792671006);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` enum('admin','technical','user') NOT NULL DEFAULT 'user',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin CGRacing', 'admin@cgracingth.com', 'admin', NULL, '$2y$12$TyS./4EWTw61EfIUXwUk.uU//OiBQ1gLlXOv9l//suQKq181BLhpC', NULL, '2026-10-18 09:58:45', '2026-10-18 10:02:10'),
(2, 'ช่างซ่อม CGRacing', 'tech@cgracingth.com', 'technical', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, '2026-10-18 09:58:45', '2026-10-18 09:58:45'),
(3, 'ลูกค้าทดสอบ', '674230002@webmail.npru.ac.th', 'user', NULL, '$2y$12$fozZohuf9bX3kO8YkMjaLOSLXHb2G0dg/qK9tBsi1CnX9digxY2pW', NULL, '2026-10-18 09:58:45', '2026-10-18 10:02:32'),
(4, 'แพรว ยิ้มแป้น', '674230034@webmail.npru.ac.th', 'user', NULL, '$2y$12$nSwMlMZTwyKEKtbx8bPFHuYaNIycaCVGMPjBBXovdS80gbT2TeqyK', NULL, '2026-10-18 12:54:21', '2026-10-18 12:54:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

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
-- Indexes for table `repair_tickets`
--
ALTER TABLE `repair_tickets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

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
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `repair_tickets`
--
ALTER TABLE `repair_tickets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
