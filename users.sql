-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 16, 2026 at 09:24 AM
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
-- Database: `crud-4-layer`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Ibrahim Khan', 'ibrahim@gmail.com', NULL, '$2y$10$N3UURKmQuqmeIvctuILBBu6PHm1ooQIg7shOKeQAxyO7QI98/Qd46', NULL, '2026-02-09 02:25:19', '2026-02-09 02:25:19'),
(2, 'Ibrahim Khan', 'ibrahimkhan360@gmail.com', NULL, '$2y$10$ChWjHGzAIdoh78ZNmfRB7OTnXmPhY.JWNlC7gSeHWYlFa0oA5BuP6', NULL, '2026-02-10 04:34:08', '2026-02-10 04:34:08'),
(3, 'Steel Thornton', 'revyx@mailinator.com', NULL, '$2y$10$e69wnHozzY7Zo6bJq8Fzbu/V5f4oUMWna7pBDDB1P.FufwCeuglA2', NULL, '2026-02-10 04:35:54', '2026-02-10 04:35:54'),
(4, 'Harrison Vazquez', 'nezac@mailinator.com', NULL, '$2y$10$zZsxh0yRRPUKu9qXbZFqC.aYmNnyI1aO9Z0wnq6fyogUayskFOfIy', NULL, '2026-02-10 04:36:13', '2026-02-10 04:36:13'),
(5, 'Dolan Good', 'vaporuf@mailinator.com', NULL, '$2y$10$NzRoeI.tVoWPogu5MpHVpODsnPtKnZpGJBEi32Hl7eDFQQB3Ls9Yq', NULL, '2026-02-10 04:37:16', '2026-02-10 04:37:16'),
(6, 'Joseph Rosario', 'visexytes@mailinator.com', NULL, '$2y$10$S.U37Tbq.aIxC1maW4KhHOatQ2TsqkXSIZMBn5QWdr/oCduluCKn.', NULL, '2026-02-10 04:41:20', '2026-02-10 04:41:20');

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
