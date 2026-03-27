-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 27, 2026 at 06:56 AM
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
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `faq_id` int(11) NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`faq_id`, `question`, `answer`, `created_at`) VALUES
(2, 'What is your return policy?', 'We offer a 30-day return policy on all products. Please contact support to initiate a return.', '2026-03-27 05:50:01'),
(3, 'How long does shipping take?', 'Shipping typically takes 5-7 business days for domestic orders. International shipping may take longer.', '2026-03-27 05:50:01'),
(4, 'Do you offer international shipping?', 'Yes, we ship to most countries worldwide. Shipping costs and times vary by location.', '2026-03-27 05:50:01'),
(5, 'How can I track my order?', 'Once your order is shipped, you will receive a tracking number via email.', '2026-03-27 05:50:01'),
(6, 'What payment methods are accepted?', 'We accept Visa, MasterCard, PayPal, and other major payment methods.', '2026-03-27 05:50:01'),
(7, 'Can I change or cancel my order?', 'Orders can be modified or canceled within 24 hours of placing them. Contact support immediately.', '2026-03-27 05:50:01'),
(8, 'How do I reset my password?', 'Click on \"Forgot Password\" at login and follow the instructions to reset your password.', '2026-03-27 05:50:01'),
(9, 'Is my personal information safe?', 'Yes, we use secure encryption and never share your personal information with third parties.', '2026-03-27 05:50:01'),
(10, 'Do you offer gift cards?', 'Yes, we offer digital and physical gift cards. You can purchase them online or in-store.', '2026-03-27 05:50:01'),
(11, 'How do I contact customer support?', 'You can reach us via email, phone, or live chat from our website.', '2026-03-27 05:50:01'),
(12, 'Are there any discounts or promotions?', 'We regularly offer discounts and promotions. Subscribe to our newsletter to stay updated.', '2026-03-27 05:50:01'),
(13, 'Can I change my shipping address after ordering?', 'Yes, if the order has not been shipped yet. Contact support immediately to update the address.', '2026-03-27 05:50:01'),
(14, 'Do you have a loyalty program?', 'Yes, join our loyalty program to earn points and receive exclusive benefits.', '2026-03-27 05:50:01'),
(15, 'How do I report a defective product?', 'Contact customer support with your order details and photos of the defective item.', '2026-03-27 05:50:01'),
(16, 'What are your business hours?', 'Our support team is available Monday to Friday, 9 AM to 6 PM local time.', '2026-03-27 05:50:01'),
(17, 'Do you provide assembly instructions?', 'Yes, all products come with detailed assembly instructions. Videos are available on our website.', '2026-03-27 05:50:01'),
(18, 'Can I request a product that is out of stock?', 'You can join our waitlist for out-of-stock items, and we will notify you when available.', '2026-03-27 05:50:01'),
(19, 'How do I apply a discount code?', 'Enter your discount code at checkout to apply it to your order total.', '2026-03-27 05:50:01'),
(20, 'Are there any warranty options?', 'Yes, most products come with a 1-year warranty. Extended warranty options are also available.', '2026-03-27 05:50:01'),
(21, 'Do you offer bulk order discounts?', 'Yes, for large or corporate orders, please contact our sales team for pricing.', '2026-03-27 05:50:01');

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
-- Table structure for table `stories`
--

CREATE TABLE `stories` (
  `story_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stories`
--

INSERT INTO `stories` (`story_id`, `title`, `content`, `image_url`, `created_at`) VALUES
(2, 'Beautify Your Home', 'Discover tips and tricks to make your home more beautiful and cozy.', '1774589887_beautify.jpg', '2026-03-27 05:39:34'),
(3, 'Spring Makeover', 'Revamp your living space with fresh colors and textures for spring.', '1774589887_beautify.jpg', '2026-03-27 05:39:34'),
(4, 'Garden Bliss', 'Ideas to beautify your garden and make it a relaxing retreat.', '1774589887_beautify.jpg', '2026-03-27 05:39:34'),
(5, 'Minimalist Style', 'Learn how minimalist designs can enhance your space and peace of mind.', '1774589887_beautify.jpg', '2026-03-27 05:39:34'),
(6, 'DIY Wall Art', 'Create stunning wall art that transforms any room easily.', '1774589887_beautify.jpg', '2026-03-27 05:39:34'),
(7, 'Cozy Corners', 'Tips to create cozy reading or relaxation corners in your home.', '1774589887_beautify.jpg', '2026-03-27 05:39:34'),
(8, 'Bathroom Refresh', 'Simple ways to beautify and upgrade your bathroom.', '1774589887_beautify.jpg', '2026-03-27 05:39:34'),
(9, 'Kitchen Magic', 'Turn your kitchen into a stylish and functional space.', '1774589887_beautify.jpg', '2026-03-27 05:39:34'),
(10, 'Outdoor Elegance', 'Beautify your outdoor spaces with lights, plants, and furniture.', '1774589887_beautify.jpg', '2026-03-27 05:39:34'),
(11, 'Home Office Upgrade', 'Make your home office inspiring and productive.', '1774589887_beautify.jpg', '2026-03-27 05:39:34'),
(12, 'Living Room Makeover', 'Transform your living room with colors, textures, and layouts.', '1774589887_beautify.jpg', '2026-03-27 05:39:34'),
(13, 'Bedroom Serenity', 'Ideas to make your bedroom a serene and stylish retreat.', '1774589887_beautify.jpg', '2026-03-27 05:39:34'),
(14, 'Accent Pieces', 'Use accent pieces to bring personality and charm to any room.', '1774589887_beautify.jpg', '2026-03-27 05:39:34'),
(15, 'Lighting Magic', 'Beautify your home with the right lighting for every space.', '1774589887_beautify.jpg', '2026-03-27 05:39:34'),
(16, 'Artful Spaces', 'Incorporate art and decor to create visually stunning rooms.', '1774589887_beautify.jpg', '2026-03-27 05:39:34'),
(17, 'Storage Solutions', 'Stylish storage ideas that keep your home organized.', '1774589887_beautify.jpg', '2026-03-27 05:39:34'),
(18, 'Color Harmony', 'Choose color palettes that beautify your home effortlessly.', '1774589887_beautify.jpg', '2026-03-27 05:39:34'),
(19, 'Furniture Arrangement', 'Tips on arranging furniture for maximum style and comfort.', '1774589887_beautify.jpg', '2026-03-27 05:39:34'),
(20, 'Eco-Friendly Decor', 'Beautify your space with sustainable and eco-friendly decor.', '1774589887_beautify.jpg', '2026-03-27 05:39:34'),
(21, 'Seasonal Touches', 'Update your home decor according to the seasons beautifully.', '1774589887_beautify.jpg', '2026-03-27 05:39:34');

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
(1, 'NASIR ABBAS', '2026-01-13', '32402-5400821-7', '03176526827', 'nasiryt.827@gmail.com', 'nasir12', '$2y$10$nZhnRn6hYl5dlijGCQELSOIy523nl6rUWzCW8L6xVyqosn3LRSgXe', 'p/o box basti cheena tehsil jampur district rajanpur\r\nAp04', '2026-01-27 10:10:02', '2026-01-27 10:10:02'),
(2, 'Client One', '2026-02-22', '32402-8443638-5', '03266029044', 'guest12@gmail.com', 'testuser12', '$2y$10$8W32BkG1CF/i0ZRJeXlgGeMaYhjyCCW8NJfvKSAKwTb6sUQxZaVT2', 'XYZ', '2026-02-23 21:17:05', '2026-02-23 21:17:05');

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
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`faq_id`);

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
-- Indexes for table `stories`
--
ALTER TABLE `stories`
  ADD PRIMARY KEY (`story_id`);

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
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `faq_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

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
-- AUTO_INCREMENT for table `stories`
--
ALTER TABLE `stories`
  MODIFY `story_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
