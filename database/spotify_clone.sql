-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 02, 2026 at 06:27 AM
-- Server version: 8.4.3
-- PHP Version: 8.5.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spotify_clone`
--

-- --------------------------------------------------------

--
-- Table structure for table `albums`
--

CREATE TABLE `albums` (
  `id` bigint UNSIGNED NOT NULL,
  `artist_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `release_year` date NOT NULL,
  `is_free` tinyint(1) NOT NULL DEFAULT '0',
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `albums`
--

INSERT INTO `albums` (`id`, `artist_id`, `title`, `image`, `release_year`, `is_free`, `slug`, `price`, `created_at`, `updated_at`) VALUES
(1, 1, 'JBL', 'albums/KRr6DMzIOol0gmFlfmXgBNbSqXiDnDixYtUml3sV.png', '2026-05-29', 1, 'jbl-6a19da9f3a226', NULL, '2026-05-29 15:27:43', '2026-05-29 15:27:43'),
(2, 1, 'Dépannage Auto', 'albums/sc29EBjVhgkPlHsDBHF97su3lci38VeEt2Cjx9BC.jpg', '2026-05-30', 1, 'depannage-auto-6a19f96742e38', NULL, '2026-05-29 17:39:03', '2026-05-29 17:39:03'),
(3, 1, 'Voiture', 'albums/yrs5sSXDwVWcgnzgxQGgDjav5Yn8bXf4eXyaNSFn.jpg', '2026-05-31', 0, 'voiture-6a19ff2770cd0', 10.00, '2026-05-29 18:03:35', '2026-05-29 18:03:35');

-- --------------------------------------------------------

--
-- Table structure for table `album_orders`
--

CREATE TABLE `album_orders` (
  `id` bigint UNSIGNED NOT NULL,
  `purchase_id` bigint UNSIGNED NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `artists`
--

CREATE TABLE `artists` (
  `id` bigint UNSIGNED NOT NULL,
  `surname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `artists`
--

INSERT INTO `artists` (`id`, `surname`, `user_id`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Black M', 1, 'Chanteur français professionnel.', '2026-05-29 12:15:48', '2026-05-29 12:15:48');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-7da314fb55f37ab4195c1876f4526f38', 'i:1;', 1780298769),
('laravel-cache-7da314fb55f37ab4195c1876f4526f38:timer', 'i:1780298769;', 1780298769);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `track_id` bigint UNSIGNED NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` bigint UNSIGNED NOT NULL,
  `track_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `artist_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `follows`
--

CREATE TABLE `follows` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `artist_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `genres`
--

CREATE TABLE `genres` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `genres`
--

INSERT INTO `genres` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Pop', 'pop', '2026-06-01 05:04:50', '2026-06-01 05:04:50'),
(2, 'Rock', 'rock', '2026-06-01 05:04:50', '2026-06-01 05:04:50'),
(3, 'Hip-Hop / Rap', 'hip-hop-rap', '2026-06-01 05:04:51', '2026-06-01 05:04:51'),
(4, 'R&B / Soul', 'rb-soul', '2026-06-01 05:04:51', '2026-06-01 05:04:51'),
(5, 'Electronic / EDM', 'electronic-edm', '2026-06-01 05:04:52', '2026-06-01 05:04:52'),
(6, 'Dance / Club', 'dance-club', '2026-06-01 05:04:52', '2026-06-01 05:04:52'),
(7, 'Reggae', 'reggae', '2026-06-01 05:04:52', '2026-06-01 05:04:52'),
(8, 'Reggaeton / Urban', 'reggaeton-urban', '2026-06-01 05:04:52', '2026-06-01 05:04:52'),
(9, 'Afrobeats', 'afrobeats', '2026-06-01 05:04:53', '2026-06-01 05:04:53'),
(10, 'Amapiano', 'amapiano', '2026-06-01 05:04:53', '2026-06-01 05:04:53'),
(11, 'Jazz', 'jazz', '2026-06-01 05:04:53', '2026-06-01 05:04:53'),
(12, 'Blues', 'blues', '2026-06-01 05:04:53', '2026-06-01 05:04:53'),
(13, 'Classical', 'classical', '2026-06-01 05:04:54', '2026-06-01 05:04:54'),
(14, 'Country', 'country', '2026-06-01 05:04:54', '2026-06-01 05:04:54'),
(15, 'Folk / Acoustic', 'folk-acoustic', '2026-06-01 05:04:54', '2026-06-01 05:04:54'),
(16, 'Metal', 'metal', '2026-06-01 05:04:54', '2026-06-01 05:04:54'),
(17, 'Punk', 'punk', '2026-06-01 05:04:55', '2026-06-01 05:04:55'),
(18, 'Alternative', 'alternative', '2026-06-01 05:04:55', '2026-06-01 05:04:55'),
(19, 'Indie', 'indie', '2026-06-01 05:04:55', '2026-06-01 05:04:55'),
(20, 'Gospel / Christian', 'gospel-christian', '2026-06-01 05:04:55', '2026-06-01 05:04:55'),
(21, 'Funk / Disco', 'funk-disco', '2026-06-01 05:04:55', '2026-06-01 05:04:55'),
(22, 'Latin', 'latin', '2026-06-01 05:04:55', '2026-06-01 05:04:55'),
(23, 'Salsa', 'salsa', '2026-06-01 05:04:55', '2026-06-01 05:04:55'),
(24, 'Bachata', 'bachata', '2026-06-01 05:04:55', '2026-06-01 05:04:55'),
(25, 'K-Pop', 'k-pop', '2026-06-01 05:04:55', '2026-06-01 05:04:55'),
(26, 'J-Pop', 'j-pop', '2026-06-01 05:04:55', '2026-06-01 05:04:55'),
(27, 'Trap', 'trap', '2026-06-01 05:04:55', '2026-06-01 05:04:55'),
(28, 'Drill', 'drill', '2026-06-01 05:04:55', '2026-06-01 05:04:55'),
(29, 'Boom Bap', 'boom-bap', '2026-06-01 05:04:55', '2026-06-01 05:04:55'),
(30, 'Lo-Fi', 'lo-fi', '2026-06-01 05:04:55', '2026-06-01 05:04:55'),
(31, 'House', 'house', '2026-06-01 05:04:55', '2026-06-01 05:04:55'),
(32, 'Techno', 'techno', '2026-06-01 05:04:55', '2026-06-01 05:04:55'),
(33, 'Trance', 'trance', '2026-06-01 05:04:55', '2026-06-01 05:04:55'),
(34, 'Dubstep', 'dubstep', '2026-06-01 05:04:55', '2026-06-01 05:04:55'),
(35, 'Drum & Bass', 'drum-bass', '2026-06-01 05:04:55', '2026-06-01 05:04:55'),
(36, 'Ambient', 'ambient', '2026-06-01 05:04:55', '2026-06-01 05:04:55'),
(37, 'Synthwave', 'synthwave', '2026-06-01 05:04:56', '2026-06-01 05:04:56'),
(38, 'Ska', 'ska', '2026-06-01 05:04:56', '2026-06-01 05:04:56'),
(39, 'Gothic', 'gothic', '2026-06-01 05:04:56', '2026-06-01 05:04:56'),
(40, 'Grime', 'grime', '2026-06-01 05:04:56', '2026-06-01 05:04:56'),
(41, 'Dancehall', 'dancehall', '2026-06-01 05:04:56', '2026-06-01 05:04:56'),
(42, 'Zouk', 'zouk', '2026-06-01 05:04:56', '2026-06-01 05:04:56'),
(43, 'Kizomba', 'kizomba', '2026-06-01 05:04:56', '2026-06-01 05:04:56'),
(44, 'Sega / Maloya', 'sega-maloya', '2026-06-01 05:04:56', '2026-06-01 05:04:56'),
(45, 'Coupe-Decale', 'coupe-decale', '2026-06-01 05:04:56', '2026-06-01 05:04:56'),
(46, 'Makossa', 'makossa', '2026-06-01 05:04:56', '2026-06-01 05:04:56'),
(47, 'Highlife', 'highlife', '2026-06-01 05:04:56', '2026-06-01 05:04:56'),
(48, 'Bossa Nova', 'bossa-nova', '2026-06-01 05:04:56', '2026-06-01 05:04:56'),
(49, 'Flamenco', 'flamenco', '2026-06-01 05:04:57', '2026-06-01 05:04:57'),
(50, 'Cinematic', 'cinematic', '2026-06-01 05:04:57', '2026-06-01 05:04:57');

-- --------------------------------------------------------

--
-- Table structure for table `genre_tracks`
--

CREATE TABLE `genre_tracks` (
  `id` bigint UNSIGNED NOT NULL,
  `genre_id` bigint UNSIGNED NOT NULL,
  `track_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `genre_tracks`
--

INSERT INTO `genre_tracks` (`id`, `genre_id`, `track_id`) VALUES
(1, 14, 5);

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000001_create_cache_table', 1),
(2, '0001_01_01_000002_create_jobs_table', 1),
(3, '2026_05_26_063456_create_roles_table', 1),
(4, '2026_05_26_070253_create_users_table', 1),
(5, '2026_05_26_070254_add_two_factor_columns_to_users_table', 1),
(6, '2026_05_26_070902_create_artists_table', 1),
(7, '2026_05_26_070918_create_types_table', 1),
(8, '2026_05_26_070940_create_subscriptions_table', 1),
(9, '2026_05_26_071030_create_social_media_table', 1),
(10, '2026_05_26_071151_create_genres_table', 1),
(11, '2026_05_26_071210_create_albums_table', 1),
(12, '2026_05_26_071220_create_tracks_table', 1),
(13, '2026_05_26_071235_create_genre_tracks_table', 1),
(14, '2026_05_26_071244_create_favorites_table', 1),
(15, '2026_05_26_071254_create_comments_table', 1),
(16, '2026_05_26_071311_create_follows_table', 1),
(17, '2026_05_26_071323_create_purchases_table', 1),
(18, '2026_05_26_071337_create_album_orders_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `amount_paid` decimal(10,2) NOT NULL,
  `album_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` enum('admin','artist','user') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'user', '2026-05-28 15:34:26', '2026-05-28 15:34:26'),
(2, 'artist', '2026-05-28 15:34:56', '2026-05-28 15:34:56'),
(3, 'admin', '2026-05-28 15:35:19', '2026-05-28 15:35:19');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('XUjzF1xHdUIvsyMMYwUC5oG6LrTwBup8lJZMYQpJ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiIzakpWcjgyWnF6U0ZhMHF4Z25yVldHT1k0QWdwZkE5ckp6ZTBzRmZzIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC90cmFja3NcL2NyZWF0ZSIsInJvdXRlIjoidHJhY2tzLmNyZWF0ZSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1780345754);

-- --------------------------------------------------------

--
-- Table structure for table `social_media`
--

CREATE TABLE `social_media` (
  `id` bigint UNSIGNED NOT NULL,
  `artist_id` bigint UNSIGNED NOT NULL,
  `sociaux` enum('facebook','instagram','youtube','tiktok') COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` bigint UNSIGNED NOT NULL,
  `type_id` bigint UNSIGNED NOT NULL,
  `starts_at` datetime NOT NULL,
  `ends_at` datetime NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `artist_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tracks`
--

CREATE TABLE `tracks` (
  `id` bigint UNSIGNED NOT NULL,
  `album_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration` int NOT NULL,
  `is_free` tinyint(1) NOT NULL DEFAULT '0',
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tracks`
--

INSERT INTO `tracks` (`id`, `album_id`, `title`, `file_path`, `duration`, `is_free`, `slug`, `created_at`, `updated_at`) VALUES
(2, 3, 'Moteur bruit', 'tracks/6a1a2a78e02a4.mp3', 7, 1, 'moteur-bruit-6a1a2a78e026c', '2026-05-29 21:08:25', '2026-05-29 21:08:25'),
(3, 1, 'Succes', 'tracks/6a1dbbc5b88d9.mp3', 2, 1, 'succes-6a1dbbc5b8649', '2026-06-01 14:05:12', '2026-06-01 14:05:12'),
(4, 1, 'Error', 'tracks/6a1dbe28df9f3.mp3', 2, 1, 'error-6a1dbe28df9b1', '2026-06-01 14:15:20', '2026-06-01 14:15:20'),
(5, 2, 'Succes country', 'tracks/6a1dbff054126.mp3', 2, 1, 'succes-country-6a1dbff05410e', '2026-06-01 14:22:56', '2026-06-01 14:22:56');

-- --------------------------------------------------------

--
-- Table structure for table `types`
--

CREATE TABLE `types` (
  `id` bigint UNSIGNED NOT NULL,
  `name` enum('basic','premium','gold') COLLATE utf8mb4_unicode_ci NOT NULL,
  `album_limit` int NOT NULL,
  `track_limit` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL,
  `pdp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pdc` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `slug`, `role_id`, `pdp`, `pdc`, `created_at`, `updated_at`) VALUES
(1, 'Angelo', 'angelo@gmail.com', '$2y$12$W2DJu5.K6G1up1wFoR/YHeO5UvmJV8WZd6E85SGi0iuAqlpIPgoce', NULL, NULL, NULL, 'angelo', 1, 'artists/mpficquUxbe3KgdSi2inCpXYPtLxjfsl4TJnaGsc.jpg', NULL, '2026-05-28 15:54:15', '2026-05-29 12:15:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `albums`
--
ALTER TABLE `albums`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `albums_slug_unique` (`slug`),
  ADD KEY `albums_artist_id_foreign` (`artist_id`);

--
-- Indexes for table `album_orders`
--
ALTER TABLE `album_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `album_orders_purchase_id_foreign` (`purchase_id`);

--
-- Indexes for table `artists`
--
ALTER TABLE `artists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `artists_user_id_foreign` (`user_id`);

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
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comments_user_id_foreign` (`user_id`),
  ADD KEY `comments_track_id_foreign` (`track_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `favorites_track_id_foreign` (`track_id`),
  ADD KEY `favorites_user_id_foreign` (`user_id`),
  ADD KEY `favorites_artist_id_foreign` (`artist_id`);

--
-- Indexes for table `follows`
--
ALTER TABLE `follows`
  ADD PRIMARY KEY (`id`),
  ADD KEY `follows_user_id_foreign` (`user_id`),
  ADD KEY `follows_artist_id_foreign` (`artist_id`);

--
-- Indexes for table `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `genres_slug_unique` (`slug`);

--
-- Indexes for table `genre_tracks`
--
ALTER TABLE `genre_tracks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `genre_tracks_genre_id_foreign` (`genre_id`),
  ADD KEY `genre_tracks_track_id_foreign` (`track_id`);

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
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchases_user_id_foreign` (`user_id`),
  ADD KEY `purchases_album_id_foreign` (`album_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `social_media`
--
ALTER TABLE `social_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `social_media_artist_id_foreign` (`artist_id`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subscriptions_type_id_foreign` (`type_id`),
  ADD KEY `subscriptions_artist_id_foreign` (`artist_id`);

--
-- Indexes for table `tracks`
--
ALTER TABLE `tracks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tracks_slug_unique` (`slug`),
  ADD KEY `tracks_album_id_foreign` (`album_id`);

--
-- Indexes for table `types`
--
ALTER TABLE `types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_slug_unique` (`slug`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `albums`
--
ALTER TABLE `albums`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `album_orders`
--
ALTER TABLE `album_orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `artists`
--
ALTER TABLE `artists`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `follows`
--
ALTER TABLE `follows`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `genres`
--
ALTER TABLE `genres`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `genre_tracks`
--
ALTER TABLE `genre_tracks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `social_media`
--
ALTER TABLE `social_media`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tracks`
--
ALTER TABLE `tracks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `types`
--
ALTER TABLE `types`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `albums`
--
ALTER TABLE `albums`
  ADD CONSTRAINT `albums_artist_id_foreign` FOREIGN KEY (`artist_id`) REFERENCES `artists` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `album_orders`
--
ALTER TABLE `album_orders`
  ADD CONSTRAINT `album_orders_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `artists`
--
ALTER TABLE `artists`
  ADD CONSTRAINT `artists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_track_id_foreign` FOREIGN KEY (`track_id`) REFERENCES `tracks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_artist_id_foreign` FOREIGN KEY (`artist_id`) REFERENCES `artists` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `favorites_track_id_foreign` FOREIGN KEY (`track_id`) REFERENCES `tracks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorites_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `follows`
--
ALTER TABLE `follows`
  ADD CONSTRAINT `follows_artist_id_foreign` FOREIGN KEY (`artist_id`) REFERENCES `artists` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `follows_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `genre_tracks`
--
ALTER TABLE `genre_tracks`
  ADD CONSTRAINT `genre_tracks_genre_id_foreign` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `genre_tracks_track_id_foreign` FOREIGN KEY (`track_id`) REFERENCES `tracks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `purchases_album_id_foreign` FOREIGN KEY (`album_id`) REFERENCES `albums` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchases_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `social_media`
--
ALTER TABLE `social_media`
  ADD CONSTRAINT `social_media_artist_id_foreign` FOREIGN KEY (`artist_id`) REFERENCES `artists` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_artist_id_foreign` FOREIGN KEY (`artist_id`) REFERENCES `artists` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subscriptions_type_id_foreign` FOREIGN KEY (`type_id`) REFERENCES `types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tracks`
--
ALTER TABLE `tracks`
  ADD CONSTRAINT `tracks_album_id_foreign` FOREIGN KEY (`album_id`) REFERENCES `albums` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
