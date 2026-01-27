-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 27, 2026 at 08:13 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `beautify_aliza_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `booking_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time DEFAULT NULL,
  `service_address` text NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `payment_method` enum('cash_on_delivery','card','easypaisa','jazzcash','other') NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','confirmed','completed','cancelled','no_show') DEFAULT 'pending',
  `created_at` datetime DEFAULT current_timestamp(),
  `cancelled_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `user_id`, `booking_date`, `start_time`, `end_time`, `service_address`, `contact_number`, `payment_method`, `total_amount`, `status`, `created_at`, `cancelled_at`) VALUES
(1, 1, '2026-01-30', '01:51:00', '02:51:00', 'fdsa', '0923982', 'jazzcash', 1200.00, 'completed', '2026-01-27 10:51:47', NULL),
(2, 1, '2026-01-29', '11:13:00', NULL, 'fdsa', '0923982', 'other', 1000.00, 'pending', '2026-01-27 11:14:05', NULL),
(3, 1, '2026-01-28', '11:29:00', '12:59:00', 'dsa', '0923982', 'other', 1200.00, 'cancelled', '2026-01-27 11:29:28', '2026-01-27 11:31:41');

-- --------------------------------------------------------

--
-- Table structure for table `booking_services`
--

CREATE TABLE `booking_services` (
  `booking_service_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `price_at_booking` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `booking_services`
--

INSERT INTO `booking_services` (`booking_service_id`, `booking_id`, `service_id`, `quantity`, `price_at_booking`) VALUES
(1, 1, 24, 1, 1200.00),
(2, 2, 5, 1, 1000.00),
(3, 3, 3, 1, 1200.00);

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `service_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `service_name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `duration_minutes` int(11) DEFAULT 60,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `service_type` enum('Men','Women','Both') NOT NULL DEFAULT 'Both'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`service_id`, `category_id`, `service_name`, `description`, `price`, `duration_minutes`, `image_url`, `created_at`, `service_type`) VALUES
(1, 1, 'Engagement Makeup', 'Professional makeup for engagement events.', 1500.00, 90, '1769492113_service.jpg', '2026-01-27 10:36:36', 'Both'),
(2, 1, 'Nikkah Makeup', 'Makeup for Nikkah ceremonies.', 2000.00, 120, '1769492113_service.jpg', '2026-01-27 10:36:36', 'Both'),
(3, 1, 'Mehndi Makeup', 'Traditional Mehndi event makeup.', 1200.00, 90, '1769492113_service.jpg', '2026-01-27 10:36:36', 'Both'),
(4, 1, 'Mayun Makeup', 'Mayun event makeup for brides.', 1200.00, 90, '1769492113_service.jpg', '2026-01-27 10:36:36', 'Both'),
(5, 1, 'Party Makeup', 'Glamorous makeup for parties.', 1000.00, 60, '1769492113_service.jpg', '2026-01-27 10:36:36', 'Both'),
(6, 1, 'Soft Makeup', 'Soft natural makeup for casual events.', 800.00, 60, '1769492113_service.jpg', '2026-01-27 10:36:36', 'Both'),
(7, 1, 'Glam Makeup', 'Glam makeup for photoshoots and events.', 1800.00, 90, '1769492113_service.jpg', '2026-01-27 10:36:36', 'Both'),
(8, 1, 'Bridal Makeup', 'Complete bridal makeup package.', 5000.00, 180, '1769492113_service.jpg', '2026-01-27 10:36:36', 'Both'),
(9, 2, 'Blowdry', 'Professional blowdry styling.', 500.00, 45, '1769492113_service.jpg', '2026-01-27 10:36:36', 'Both'),
(10, 2, 'Hairstyle', 'Custom hairstyle for events.', 800.00, 60, '1769492113_service.jpg', '2026-01-27 10:36:36', 'Both'),
(11, 2, 'Hair Cut', 'Trim or new haircut.', 600.00, 45, '1769492113_service.jpg', '2026-01-27 10:36:36', 'Both'),
(12, 2, 'Bridal Hairstyle', 'Special bridal hairstyle.', 2000.00, 120, '1769492113_service.jpg', '2026-01-27 10:36:36', 'Both'),
(13, 2, 'Hair Dye Application Only', 'Hair coloring service only.', 1500.00, 90, '1769492113_service.jpg', '2026-01-27 10:36:36', 'Both'),
(14, 3, 'Cleanser', 'Basic skin cleansing facial.', 800.00, 45, '1769492113_service.jpg', '2026-01-27 10:36:36', 'Both'),
(15, 3, 'Face Polisher', 'Exfoliation and polishing facial.', 1000.00, 60, '1769492113_service.jpg', '2026-01-27 10:36:36', 'Both'),
(16, 3, 'Dermaclear Facial', 'Advanced dermaclear facial.', 1500.00, 90, '1769492113_service.jpg', '2026-01-27 10:36:36', 'Both'),
(17, 3, 'Dermaclear Facial with Polisher', 'Dermaclear facial plus polisher.', 1800.00, 120, '1769492113_service.jpg', '2026-01-27 10:36:36', 'Both'),
(18, 3, 'Janssen Whitening Facial', 'Skin whitening facial using Janssen products.', 2000.00, 120, '1769492113_service.jpg', '2026-01-27 10:36:36', 'Both'),
(19, 3, 'Janssen Whitening Facial with Polisher', 'Janssen whitening with polisher.', 2200.00, 150, '1769492113_service.jpg', '2026-01-27 10:36:36', 'Both'),
(20, 3, 'HydraFacial', 'HydraFacial treatment for deep skin hydration.', 3000.00, 150, '1769492113_service.jpg', '2026-01-27 10:36:36', 'Both'),
(21, 4, 'Classic Manicure', 'Basic manicure treatment.', 700.00, 45, '1769492113_service.jpg', '2026-01-27 10:36:36', 'Both'),
(22, 4, 'Classic Pedicure', 'Basic pedicure treatment.', 700.00, 45, '1769492113_service.jpg', '2026-01-27 10:36:36', 'Both'),
(23, 4, 'Classic Mani Pedi', 'Manicure + Pedicure combo.', 1200.00, 90, '1769492113_service.jpg', '2026-01-27 10:36:36', 'Both'),
(24, 4, 'Signature Manicure', 'Luxury manicure with additional care.', 1200.00, 60, '1769492113_service.jpg', '2026-01-27 10:36:36', 'Both'),
(25, 4, 'Signature Pedicure', 'Luxury pedicure treatment.', 1200.00, 60, '1769492113_service.jpg', '2026-01-27 10:36:36', 'Both'),
(26, 4, 'Signature Mani Pedi', 'Luxury combo manicure & pedicure.', 2000.00, 120, '1769492113_service.jpg', '2026-01-27 10:36:36', 'Both');

-- --------------------------------------------------------

--
-- Table structure for table `service_categories`
--

CREATE TABLE `service_categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_categories`
--

INSERT INTO `service_categories` (`category_id`, `category_name`, `description`, `image_url`, `created_at`) VALUES
(1, 'Makeup Services', 'Professional makeup services for all occasions.', '1769491710_service.jpg', '2026-01-27 10:32:04'),
(2, 'Hair Styling Service', 'Haircuts, blow-dry, bridal and party hairstyles.', '1769491710_service.jpg', '2026-01-27 10:32:04'),
(3, 'Facial Services', 'Skin care treatments including facial cleansing and rejuvenation.', '1769491710_service.jpg', '2026-01-27 10:32:04'),
(4, 'Nails Services', 'Manicure, pedicure, and nail treatments.', '1769491710_service.jpg', '2026-01-27 10:32:04');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `dob` date NOT NULL,
  `cnic` varchar(20) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `dob`, `cnic`, `phone`, `email`, `username`, `password_hash`, `address`, `created_at`, `updated_at`) VALUES
(1, 'NASIR ABBAS', '2026-01-13', '32402-5400821-7', '03176526827', 'nasiryt.827@gmail.com', 'nasir12', '$2y$10$nZhnRn6hYl5dlijGCQELSOIy523nl6rUWzCW8L6xVyqosn3LRSgXe', 'p/o box basti cheena tehsil jampur district rajanpur\r\nAp04', '2026-01-27 10:10:02', '2026-01-27 10:10:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `idx_user_date` (`user_id`,`booking_date`),
  ADD KEY `idx_date_status` (`booking_date`,`status`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Indexes for table `booking_services`
--
ALTER TABLE `booking_services`
  ADD PRIMARY KEY (`booking_service_id`),
  ADD UNIQUE KEY `uk_booking_service` (`booking_id`,`service_id`),
  ADD KEY `service_id` (`service_id`),
  ADD KEY `idx_booking` (`booking_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`service_id`),
  ADD UNIQUE KEY `uk_service_per_category` (`category_id`,`service_name`),
  ADD KEY `idx_category` (`category_id`),
  ADD KEY `idx_service_name` (`service_name`);

--
-- Indexes for table `service_categories`
--
ALTER TABLE `service_categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `uk_category_name` (`category_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cnic` (`cnic`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `booking_services`
--
ALTER TABLE `booking_services`
  MODIFY `booking_service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `service_categories`
--
ALTER TABLE `service_categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `booking_services`
--
ALTER TABLE `booking_services`
  ADD CONSTRAINT `booking_services_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `booking_services_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`service_id`) ON UPDATE CASCADE;

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `service_categories` (`category_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
