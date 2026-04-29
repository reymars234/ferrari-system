-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 25, 2026 at 03:51 PM
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
-- Database: `ferrari_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_password_reset_tokens`
--

CREATE TABLE `admin_password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `module` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `module`, `description`, `ip_address`, `user_agent`, `old_values`, `new_values`, `created_at`, `updated_at`) VALUES
(1, NULL, 'PASSWORD_RESET', 'Auth', 'Password reset: reygagag1@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-24 23:57:47', '2026-04-24 23:57:47'),
(2, 1, 'LOGIN', 'Auth', 'User logged in: reygagag1@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-24 23:57:57', '2026-04-24 23:57:57'),
(3, 1, 'LOGOUT', 'Auth', 'User logged out.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-24 23:58:06', '2026-04-24 23:58:06'),
(4, 1, 'ADMIN_LOGIN', 'Auth', 'Admin logged in: reygagag1@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-25 00:06:59', '2026-04-25 00:06:59'),
(5, 1, 'LOGOUT', 'Auth', 'User logged out.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-25 00:07:15', '2026-04-25 00:07:15'),
(6, NULL, 'REGISTER', 'Auth', 'New user registered: rcabasan729@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-25 00:08:32', '2026-04-25 00:08:32'),
(7, NULL, 'EMAIL_VERIFIED', 'Auth', 'Email verified for: rcabasan729@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-25 00:09:00', '2026-04-25 00:09:00'),
(8, NULL, 'LOGOUT', 'Auth', 'User logged out.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-25 00:09:21', '2026-04-25 00:09:21'),
(9, NULL, 'PASSWORD_RESET_REQUESTED', 'Auth', 'Reset link: rcabasan729@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-25 00:09:53', '2026-04-25 00:09:53'),
(10, NULL, 'PASSWORD_RESET_REQUESTED', 'Auth', 'Reset link sent: rcabasan729@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-25 00:19:02', '2026-04-25 00:19:02'),
(11, NULL, 'PASSWORD_RESET', 'Auth', 'Password reset: rcabasan729@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-25 00:19:48', '2026-04-25 00:19:48'),
(12, NULL, 'LOGIN', 'Auth', 'User logged in: rcabasan729@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-25 00:20:15', '2026-04-25 00:20:15'),
(13, NULL, 'LOGOUT', 'Auth', 'User logged out.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-25 00:32:16', '2026-04-25 00:32:16'),
(14, NULL, 'PASSWORD_RESET_REQUESTED', 'Auth', 'Reset link sent: rcabasan729@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-25 00:56:30', '2026-04-25 00:56:30'),
(15, NULL, 'PASSWORD_RESET', 'Auth', 'Password reset: rcabasan729@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-25 00:57:02', '2026-04-25 00:57:02'),
(16, NULL, 'LOGIN', 'Auth', 'User logged in: rcabasan729@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-25 00:57:17', '2026-04-25 00:57:17'),
(17, NULL, 'LOGOUT', 'Auth', 'User logged out.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-25 00:57:25', '2026-04-25 00:57:25'),
(18, NULL, 'ADMIN_PASSWORD_RESET', 'Auth', 'Admin password reset via email: reygagag1@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-25 01:33:22', '2026-04-25 01:33:22'),
(19, 1, 'ADMIN_LOGIN', 'Auth', 'Admin logged in: reygagag1@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-25 01:34:09', '2026-04-25 01:34:09'),
(20, 1, 'LOGOUT', 'Auth', 'User logged out.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-25 01:34:42', '2026-04-25 01:34:42'),
(21, NULL, 'REGISTER', 'Auth', 'New user registered: mojicajosh25@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-25 02:15:33', '2026-04-25 02:15:33'),
(22, NULL, 'EMAIL_VERIFIED', 'Auth', 'Email verified for: mojicajosh25@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-25 02:15:54', '2026-04-25 02:15:54'),
(23, NULL, 'LOGOUT', 'Auth', 'User logged out.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-25 02:24:27', '2026-04-25 02:24:27'),
(24, NULL, 'REGISTER', 'Auth', 'New user registered: joeyaquino323@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-25 02:26:28', '2026-04-25 02:26:28'),
(25, NULL, 'EMAIL_VERIFIED', 'Auth', 'Email verified for: joeyaquino323@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-25 02:27:03', '2026-04-25 02:27:03'),
(26, NULL, 'ORDER_CREATED', 'Orders', 'Order #1 COD', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-25 02:30:23', '2026-04-25 02:30:23'),
(27, NULL, 'ORDER_CANCELLED', 'Orders', 'Order #1 (Ferrari 296 GTB) cancelled.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-25 02:30:49', '2026-04-25 02:30:49'),
(28, NULL, 'ORDER_CREATED', 'Orders', 'Order #2 COD', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-25 02:49:08', '2026-04-25 02:49:08'),
(29, NULL, 'ORDER_CANCELLED', 'Orders', 'Order #2 (Ferrari Roma) cancelled.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-25 02:49:34', '2026-04-25 02:49:34'),
(30, NULL, 'LOGOUT', 'Auth', 'User logged out.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-25 02:49:49', '2026-04-25 02:49:49'),
(31, NULL, 'ADMIN_PASSWORD_RESET', 'Auth', 'Admin password reset via email: reygagag1@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-25 02:51:02', '2026-04-25 02:51:02'),
(32, 1, 'ADMIN_LOGIN', 'Auth', 'Admin logged in: reygagag1@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-25 02:51:12', '2026-04-25 02:51:12'),
(33, 1, 'LOGOUT', 'Auth', 'User logged out.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-25 02:56:07', '2026-04-25 02:56:07'),
(34, 1, 'ADMIN_LOGIN', 'Auth', 'Admin logged in: reygagag1@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-25 03:08:48', '2026-04-25 03:08:48'),
(35, 1, 'USER_DELETED', 'Users', 'User deleted: user@ferrari.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-25 03:29:33', '2026-04-25 03:29:33'),
(36, 1, 'USER_DELETED', 'Users', 'User deleted: joeyaquino323@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-25 03:34:14', '2026-04-25 03:34:14'),
(37, 1, 'USER_DELETED', 'Users', 'User deleted: rcabasan729@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-25 03:34:37', '2026-04-25 03:34:37'),
(38, 1, 'USER_DELETED', 'Users', 'User deleted: mojicajosh25@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-25 03:35:47', '2026-04-25 03:35:47'),
(39, 1, 'LOGOUT', 'Auth', 'User logged out.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-25 04:16:56', '2026-04-25 04:16:56'),
(40, 1, 'ADMIN_LOGIN', 'Auth', 'Admin logged in: reygagag1@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', NULL, NULL, '2026-04-25 05:08:10', '2026-04-25 05:08:10');

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
-- Table structure for table `cars`
--

CREATE TABLE `cars` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(15,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 1,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `rarity` varchar(255) NOT NULL DEFAULT 'common',
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cars`
--

INSERT INTO `cars` (`id`, `name`, `description`, `price`, `image`, `stock`, `is_available`, `rarity`, `is_featured`, `created_at`, `updated_at`) VALUES
(1, 'Ferrari SF90 Stradale', 'The most powerful Ferrari production car ever made. 986 hp hybrid V8 engine.', 48500000.00, NULL, 1, 1, 'common', 0, '2026-04-24 23:55:03', '2026-04-24 23:55:03'),
(2, 'Ferrari 296 GTB', 'The new era of Ferrari performance with plug-in hybrid technology.', 32000000.00, NULL, 1, 1, 'common', 0, '2026-04-24 23:55:03', '2026-04-24 23:55:03'),
(3, 'Ferrari Roma', 'A modern interpretation of the carefree, pleasurable lifestyle of Dolce Vita Rome.', 22000000.00, NULL, 1, 1, 'common', 0, '2026-04-24 23:55:03', '2026-04-24 23:55:03'),
(4, 'Ferrari Portofino M', 'The most versatile Ferrari GT. Open-top driving with grand touring comfort.', 24500000.00, NULL, 1, 1, 'common', 0, '2026-04-24 23:55:03', '2026-04-24 23:55:03'),
(5, 'Ferrari F8 Tributo', 'A tribute to the most powerful V8 in Ferrari history. 710 hp twin-turbo.', 29000000.00, NULL, 1, 1, 'common', 0, '2026-04-24 23:55:03', '2026-04-24 23:55:03'),
(6, 'Ferrari 812 Superfast', 'The most powerful and fastest road-going Ferrari ever. 789 hp naturally aspirated V12.', 38000000.00, NULL, 1, 1, 'common', 0, '2026-04-24 23:55:03', '2026-04-24 23:55:03'),
(7, 'Ferrari GTC4Lusso', 'Four-seater four-wheel drive GT with a V12 engine. The ultimate family Ferrari.', 35000000.00, NULL, 1, 1, 'common', 0, '2026-04-24 23:55:03', '2026-04-24 23:55:03'),
(8, 'Ferrari Monza SP1', 'A single-seat Icona series barchetta inspired by legendary racing Ferraris of the 1950s.', 55000000.00, NULL, 1, 1, 'common', 0, '2026-04-24 23:55:03', '2026-04-24 23:55:03'),
(9, 'Ferrari LaFerrari Aperta', 'The ultimate open-top hybrid hypercar. 950 hp combined power. Only 210 units ever made.', 280000000.00, NULL, 1, 1, 'common', 0, '2026-04-24 23:55:03', '2026-04-24 23:55:03');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `car_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `chat_type` enum('user_driver','admin_driver') NOT NULL DEFAULT 'user_driver',
  `sender_id` bigint(20) UNSIGNED NOT NULL,
  `receiver_id` bigint(20) UNSIGNED NOT NULL,
  `body` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
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
(4, '2026_04_18_015416_create_cars_table', 1),
(5, '2026_04_18_015434_create_orders_table', 1),
(6, '2026_04_18_015454_create_audit_logs_table', 1),
(7, '2026_04_19_081522_add_driver_fields_to_users_table', 1),
(8, '2026_04_19_081525_add_driver_to_orders_table', 1),
(9, '2026_04_19_081527_create_cart_items_table', 1),
(10, '2026_04_19_081530_create_messages_table', 1),
(11, '2026_04_19_102656_add_user_id_to_cart_items_table', 1),
(12, '2026_04_19_104203_add_car_id_to_cart_items_table', 1),
(13, '2026_04_19_131013_add_driver_payment_to_orders_table', 1),
(14, '2026_04_20_002026_add_payment_fields_to_orders_table', 1),
(15, '2026_04_20_020353_add_chat_type_to_messages_table', 1),
(16, '2026_04_20_033022_add_refund_cod_fields_to_orders_table', 1),
(17, '2026_04_20_100210_add_rarity_to_cars_table', 1),
(18, '2026_04_22_111548_add_coordinates_to_orders_table', 1),
(19, '2026_04_25_081733_create_admin_password_reset_tokens_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `car_id` bigint(20) UNSIGNED NOT NULL,
  `buyer_name` varchar(255) NOT NULL,
  `buyer_address` varchar(255) NOT NULL,
  `delivery_latitude` decimal(10,8) DEFAULT NULL,
  `delivery_longitude` decimal(11,8) DEFAULT NULL,
  `buyer_contact` varchar(20) NOT NULL,
  `total_price` decimal(15,2) NOT NULL,
  `status` enum('pending','processing','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `payment_method` varchar(255) NOT NULL DEFAULT 'cod',
  `payment_status` varchar(255) NOT NULL DEFAULT 'unpaid',
  `payment_reference` varchar(255) DEFAULT NULL,
  `refund_status` varchar(255) DEFAULT NULL,
  `refund_reference` varchar(255) DEFAULT NULL,
  `refunded_at` timestamp NULL DEFAULT NULL,
  `cancel_reason` text DEFAULT NULL,
  `cod_paid` tinyint(1) NOT NULL DEFAULT 0,
  `cod_paid_at` timestamp NULL DEFAULT NULL,
  `cod_confirmed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `admin_accepted` tinyint(4) NOT NULL DEFAULT 0,
  `admin_accepted_at` timestamp NULL DEFAULT NULL,
  `delivery_notes` text DEFAULT NULL,
  `estimated_delivery` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `driver_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
('HzCzqH7Gi2dsJIovB6x9ByroVmNFtgvnmmx2OZ0s', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoieG1qUVNUNG90T2tpcE9kM3BZMnRXVnpnanZ0SnNPUzBLQ29JWEtpUiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbiI7czo1OiJyb3V0ZSI7czoxNToiYWRtaW4uZGFzaGJvYXJkIjt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1777122494);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `license_number` varchar(50) DEFAULT NULL,
  `vehicle_info` varchar(255) DEFAULT NULL,
  `driver_status` enum('available','busy','offline') NOT NULL DEFAULT 'available',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `role` enum('user','driver','admin') DEFAULT 'user',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `otp` varchar(255) DEFAULT NULL,
  `otp_expires_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `contact_number`, `address`, `license_number`, `vehicle_info`, `driver_status`, `is_active`, `role`, `email_verified_at`, `otp`, `otp_expires_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'System Admin', 'reygagag1@gmail.com', NULL, NULL, NULL, NULL, 'available', 1, 'admin', '2026-04-24 23:55:02', NULL, NULL, '$2y$12$qt.ICUPtt62ICZAVsLrnMeykLMRfkQ2/5dqnECnhJhXvWYngJ2veG', 'OmF8zJE11gpvZ0UU4UHicCGHV1Gqlf4XynSIIUnfQuZUYMG2LU8Bd25QEVnE', '2026-04-24 23:55:02', '2026-04-25 02:51:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_password_reset_tokens`
--
ALTER TABLE `admin_password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `audit_logs_user_id_foreign` (`user_id`);

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
-- Indexes for table `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cart_items_user_id_car_id_unique` (`user_id`,`car_id`),
  ADD KEY `cart_items_car_id_foreign` (`car_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_order_id_foreign` (`order_id`),
  ADD KEY `messages_sender_id_foreign` (`sender_id`),
  ADD KEY `messages_receiver_id_foreign` (`receiver_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_car_id_foreign` (`car_id`),
  ADD KEY `orders_driver_id_foreign` (`driver_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

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
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `cars`
--
ALTER TABLE `cars`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_car_id_foreign` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_car_id_foreign` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_driver_id_foreign` FOREIGN KEY (`driver_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
