-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 09, 2025 at 06:22 PM
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
-- Database: `mr_freddie_repair_shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `appointment_date` date NOT NULL,
  `status` enum('scheduled','on-going','completed','cancelled') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `customer_id`, `service_id`, `staff_id`, `appointment_date`, `status`, `created_at`) VALUES
(1, 1, 2, NULL, '2025-01-22', 'cancelled', '2025-01-08 14:30:34'),
(2, 1, 3, 4, '2025-01-16', 'cancelled', '2025-01-09 02:19:33'),
(3, 2, 4, 4, '2025-01-10', 'completed', '2025-01-09 02:30:39'),
(4, 1, 1, 4, '2025-01-23', 'completed', '2025-01-09 03:28:44'),
(5, 1, 3, NULL, '2025-01-11', 'cancelled', '2025-01-09 05:58:51'),
(6, 1, 2, 4, '2025-01-10', 'on-going', '2025-01-09 06:26:13'),
(7, 2, 2, 4, '2025-01-10', 'completed', '2025-01-09 06:27:05'),
(8, 2, 1, NULL, '2025-01-11', 'scheduled', '2025-01-09 06:29:58'),
(9, 1, 2, NULL, '2025-01-11', 'scheduled', '2025-01-09 06:43:03'),
(10, 1, 1, NULL, '2025-01-10', 'cancelled', '2025-01-09 14:10:57'),
(11, 1, 1, 4, '2025-01-10', 'completed', '2025-01-09 14:13:15'),
(12, 1, 4, NULL, '2025-01-31', 'scheduled', '2025-01-09 15:53:42'),
(13, 1, 2, NULL, '2025-01-31', 'scheduled', '2025-01-09 15:54:03'),
(14, 1, 4, NULL, '2025-01-16', 'scheduled', '2025-01-09 16:05:42'),
(15, 1, 4, NULL, '2025-01-28', 'scheduled', '2025-01-09 16:06:15'),
(16, 1, 2, NULL, '0000-00-00', 'scheduled', '2025-01-09 16:06:27'),
(17, 1, 2, NULL, '2025-01-31', 'scheduled', '2025-01-09 16:06:53'),
(18, 2, 4, NULL, '2025-01-31', 'scheduled', '2025-01-09 16:09:26');

-- --------------------------------------------------------

--
-- Table structure for table `availability`
--

CREATE TABLE `availability` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `availability`
--

INSERT INTO `availability` (`id`, `date`, `is_available`, `created_at`) VALUES
(1, '2025-01-02', 1, '2025-01-09 15:02:19'),
(4, '2025-01-10', 1, '2025-01-09 15:02:25'),
(7, '2025-01-11', 1, '2025-01-09 15:02:36');

-- --------------------------------------------------------

--
-- Table structure for table `calendar`
--

CREATE TABLE `calendar` (
  `date` date NOT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `calendar`
--

INSERT INTO `calendar` (`date`, `is_available`) VALUES
('2025-01-01', 1),
('2025-01-02', 1),
('2025-01-09', 1),
('2025-01-10', 1),
('2025-01-11', 1),
('2025-01-12', 1),
('2025-01-13', 1),
('2025-01-14', 1),
('2025-01-15', 1),
('2025-01-16', 1),
('2025-01-17', 1),
('2025-01-18', 1),
('2025-01-19', 1),
('2025-01-20', 1),
('2025-01-21', 1),
('2025-01-22', 1),
('2025-01-23', 1),
('2025-01-24', 1),
('2025-01-25', 1),
('2025-01-28', 1),
('2025-01-29', 1),
('2025-01-30', 1),
('2025-01-31', 1);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `appointment_id`, `rating`, `comment`, `created_at`) VALUES
(1, 4, 4, 'payter ah', '2025-01-09 04:37:40');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `description`, `price`) VALUES
(1, 'Shoe Repair', 'General shoe repair service', 50.00),
(2, 'Shoe Cleaning', 'Professional shoe cleaning service', 30.00),
(3, 'Shoe Polishing', 'Shoe polishing service', 20.00),
(4, 'Leather Conditioning', 'Leather conditioning for shoes and other leather items', 40.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `age` int(11) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `role` enum('customer','staff','admin') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `first_name`, `middle_name`, `last_name`, `age`, `email`, `phone_number`, `role`, `created_at`) VALUES
(1, 'meg', '$2y$10$VR9Sj8924ZXqwPS5IZRxbOZR4IT5YwqaA/bQsV9sQzCfgkxE2v7I2', 'Meg Ryan', 'Doromal', 'Rojoo', 21, 'megryan@gmail.com', '09483205316', 'customer', '2025-01-08 14:27:42'),
(2, 'michael', '$2y$10$rhDlHaF/ik4WjQmMRTfpcunL7a8AG6xKxAoPixMfjEmmwUF99B6Mi', 'Michael', 'Doromal', 'Santiago', 21, 'santiago@gmail.com', '09483205316', 'customer', '2025-01-09 02:06:24'),
(3, 'japet', '$2y$10$XSW0fwWlfg9XG3Q6Es8sgOQHkYBz5HqUHEksoLl67newew.38eWTy', 'Japeth', 'Ambot', 'Dejan', 21, 'japet@gmail.com', '09483205316', 'admin', '2025-01-09 03:36:39'),
(4, 'anton', '$2y$10$hGMQI/TGUOpYUDVgJmP1Fe/K.YqCGIwczATdkwz6cUHk45X3KFgZq', 'Anthony', 'Ambot', 'Angeles', 21, 'anton@gmail.com', '09483202352', 'staff', '2025-01-09 04:28:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `service_id` (`service_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `availability`
--
ALTER TABLE `availability`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `date` (`date`);

--
-- Indexes for table `calendar`
--
ALTER TABLE `calendar`
  ADD PRIMARY KEY (`date`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointment_id` (`appointment_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `availability`
--
ALTER TABLE `availability`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`),
  ADD CONSTRAINT `appointments_ibfk_3` FOREIGN KEY (`staff_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
