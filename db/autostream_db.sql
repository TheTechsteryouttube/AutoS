-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 06, 2025 at 06:00 PM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `autostream_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `admin_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$0DBCqP8WaXvS9pc2xSgKn.5A6/TLxP6Ddo65tp/POoBMe8mzXvhGi');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
CREATE TABLE IF NOT EXISTS `bookings` (
  `booking_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int DEFAULT NULL,
  `spare_id` int DEFAULT NULL,
  `quantity` int DEFAULT '1',
  `booking_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `payment_method` varchar(50) DEFAULT NULL,
  `delivery_address` text,
  `delivery_city` varchar(100) DEFAULT NULL,
  `delivery_pincode` varchar(10) DEFAULT NULL,
  `assigned_partner_id` int DEFAULT NULL,
  `delivery_status` varchar(50) DEFAULT 'Pending',
  `current_location` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`booking_id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `user_id`, `spare_id`, `quantity`, `booking_date`, `payment_method`, `delivery_address`, `delivery_city`, `delivery_pincode`, `assigned_partner_id`, `delivery_status`, `current_location`) VALUES
(26, 8, 6, 3, '2025-10-06 17:58:03', 'cod', 'teh', 'asaf', '234234', NULL, 'Pending', NULL),
(25, 8, 6, 2, '2025-10-06 15:49:45', 'cod', 'teh', 'asaf', '234234', 1, 'Out for Delivery', 'idukki');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE IF NOT EXISTS `cart` (
  `cart_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `spare_id` int NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `added_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cart_id`),
  UNIQUE KEY `unique_cart_item` (`user_id`,`spare_id`),
  KEY `spare_id` (`spare_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `user_id`, `spare_id`, `quantity`, `added_at`) VALUES
(1, 8, 6, 5, '2025-09-25 13:09:46');

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

DROP TABLE IF EXISTS `companies`;
CREATE TABLE IF NOT EXISTS `companies` (
  `company_id` int NOT NULL AUTO_INCREMENT,
  `company_name` varchar(100) NOT NULL,
  PRIMARY KEY (`company_id`),
  UNIQUE KEY `company_name` (`company_name`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`company_id`, `company_name`) VALUES
(19, 'BAJAJ'),
(14, 'HONDA'),
(10, 'KTM'),
(20, 'SUZUKI'),
(11, 'TRIUMPH'),
(21, 'YAMAHA');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_partner`
--

DROP TABLE IF EXISTS `delivery_partner`;
CREATE TABLE IF NOT EXISTS `delivery_partner` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `delivery_partner`
--

INSERT INTO `delivery_partner` (`id`, `name`, `email`, `phone`, `password`, `status`, `created_at`) VALUES
(1, 'Ekart', 'midhunem570@gmail.com', '9834452312', '$2y$10$8IBjid/dzHuT.Z1FebHyweJgAfabYXqzUt4sQe7Nv7U9q0E1VHI2q', '', '2025-10-06 14:08:02');

-- --------------------------------------------------------

--
-- Table structure for table `models`
--

DROP TABLE IF EXISTS `models`;
CREATE TABLE IF NOT EXISTS `models` (
  `model_id` int NOT NULL AUTO_INCREMENT,
  `model_name` varchar(100) NOT NULL,
  `company_id` int NOT NULL,
  PRIMARY KEY (`model_id`),
  KEY `company_id` (`company_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `models`
--

INSERT INTO `models` (`model_id`, `model_name`, `company_id`) VALUES
(1, 'duke 200', 10),
(4, 'MT-15', 21),
(5, 'CBR 250', 14),
(6, 'pulsar 220f', 19),
(7, 'PULSAR NS200', 19),
(8, 'RS200', 19),
(9, 'R15 V2', 21),
(10, 'R15 V3', 19),
(11, 'SPEED 400', 11);

-- --------------------------------------------------------

--
-- Table structure for table `model_variants`
--

DROP TABLE IF EXISTS `model_variants`;
CREATE TABLE IF NOT EXISTS `model_variants` (
  `variant_id` int NOT NULL AUTO_INCREMENT,
  `model_id` int NOT NULL,
  `production_year` int NOT NULL,
  `engine_cc` decimal(6,2) DEFAULT NULL,
  `bhp` decimal(5,2) DEFAULT NULL,
  `torque` varchar(50) DEFAULT NULL,
  `fuel_type` varchar(50) DEFAULT NULL,
  `seat_height` int DEFAULT NULL,
  `discontinued_year` int DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `mileage` varchar(50) DEFAULT NULL,
  `variant_image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`variant_id`),
  KEY `model_id` (`model_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `model_variants`
--

INSERT INTO `model_variants` (`variant_id`, `model_id`, `production_year`, `engine_cc`, `bhp`, `torque`, `fuel_type`, `seat_height`, `discontinued_year`, `price`, `mileage`, `variant_image`) VALUES
(4, 1, 2012, 199.00, 22.00, '18nm', 'petrol', 150, 2015, 200000.00, '35', 'uploads/KTM/duke_200/1755886388_KTM_Duke_200_Road_Test-1200x900.jpg'),
(5, 4, 2021, 149.00, 18.10, '13.9nm', 'petrol', 810, 0, 178000.00, '48', 'uploads/YAMAHA/MT_15/1757410804_mt15bs6.png'),
(6, 1, 2021, 199.00, 25.50, '19.5nm', 'petrol', 822, 2023, 186000.00, '48', 'uploads/KTM/duke_200/1757413791_2021-KTM-200-Duke-1.webp'),
(7, 6, 2024, 220.00, 20.40, '18.55', 'petrol', 795, 0, 160000.00, '40', 'uploads/BAJAJ/pulsar_220f/1758623039_pulsar-220-right-side-view-8.avif'),
(8, 7, 2012, 199.00, 24.50, '18.74', 'petrol', 805, 2014, 143000.00, '35', 'uploads/BAJAJ/PULSAR_NS200/1758623349_bajaj-pulsar-200-ns68552a39c0759.avif'),
(9, 8, 2015, 199.00, 25.00, '18', 'petrol', 805, 2017, 178000.00, '35', 'uploads/BAJAJ/RS200/1758642756_29281_Bajaj_Pulsar_RS200_014_468x263.avif'),
(10, 9, 2011, 150.00, 16.70, '15', 'petrol', 800, 2018, 178000.00, '35', 'uploads/YAMAHA/R15_V2/1758642928_yamaha-yzfr15.avif'),
(11, 10, 2018, 150.00, 18.83, '14.1', 'petrol', 815, 2021, 178000.00, '35', 'uploads/BAJAJ/R15_V3/1758643145_yamaha-yzf-r15-v3-dual-channel-abs--bs-vi20200109152444.avif'),
(12, 11, 2023, 398.00, 39.50, '37.5', 'petrol', 800, 0, 251000.00, '29', 'uploads/TRIUMPH/SPEED_400/1758643634_triumph-speed-400-standard1740985228351.avif');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
CREATE TABLE IF NOT EXISTS `payments` (
  `payment_id` int NOT NULL AUTO_INCREMENT,
  `booking_id` int NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('upi','card','netbanking','wallet','cod') NOT NULL,
  `payment_status` enum('pending','completed','failed') DEFAULT 'pending',
  `payment_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`payment_id`),
  KEY `booking_id` (`booking_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `booking_id`, `amount`, `payment_method`, `payment_status`, `payment_date`) VALUES
(16, 21, 7680.00, 'cod', 'completed', '2025-10-06 15:03:31'),
(17, 24, 12800.00, 'wallet', 'completed', '2025-10-06 15:45:41'),
(18, 24, 12800.00, 'wallet', 'completed', '2025-10-06 15:48:03'),
(19, 25, 5120.00, 'cod', 'completed', '2025-10-06 15:49:51'),
(20, 26, 7680.00, 'cod', 'completed', '2025-10-06 17:58:11');

-- --------------------------------------------------------

--
-- Table structure for table `spares`
--

DROP TABLE IF EXISTS `spares`;
CREATE TABLE IF NOT EXISTS `spares` (
  `spare_id` int NOT NULL AUTO_INCREMENT,
  `model_id` int NOT NULL,
  `spare_name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text,
  `stock` int DEFAULT '0',
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`spare_id`),
  KEY `fk_model` (`model_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `spares`
--

INSERT INTO `spares` (`spare_id`, `model_id`, `spare_name`, `price`, `description`, `stock`, `image_path`, `created_at`, `updated_at`) VALUES
(6, 1, 'fuel injector', 2560.00, '0', 138, 'uploads/KTM/duke_200/spares/1758617154_71YYN8O6hRL.jpg', '2025-09-23 08:45:54', '2025-10-06 17:58:03');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(25) DEFAULT NULL,
  `username` varchar(20) DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `username`, `email`, `password`) VALUES
(3, 'Don Tomy', 'Don', 'dontomy@gmail.com', '$2y$10$fG7eSTck7BPigBuK9KTvZuAc.WjXT4/gdXpXHx/dFgiQEmNFX1dTm'),
(4, 'Nithin Abraham', 'nithin', 'nithin@gmail.com', '$2y$10$Q8vCujFXsPdY1NqifYLxx.DG1Jv4FN52kvqwmpnXji7.dwvXW8ES2'),
(5, 'Midhun', 'Midhun', 'midhunem570@gmail.com', '$2y$10$fwDrlBvB4lRf3xkgv7mFWe//60vdjUFqj/va09BEsZYTBE2AGx3qe'),
(6, 'abraham', 'abraham', 'abrahamnithin517@gmail.com', '$2y$10$87SxiUY51n0Ch3gK1a2HYO2gkVLW3O3HpK0JwDLIlahwsSVZu5Ot6'),
(7, 'sourav k', 'sourav ', 'sourav@gmail.com', '$2y$10$j/WKVOmPYeduDQOr5mukh.HlVC325mHd0bwUGHPXVN1OXDDU5g4wi'),
(8, 'shalbin', 'shalbin', 'midhunem470@gmail.com', '$2y$10$wp9XOUw6dbPmwV8UGqBGruAnyZArklfmEctSiS4AUUIjEcidnE3p.');

-- --------------------------------------------------------

--
-- Table structure for table `videos`
--

DROP TABLE IF EXISTS `videos`;
CREATE TABLE IF NOT EXISTS `videos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `variant_id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `filepath` varchar(255) NOT NULL,
  `thumbnail_path` varchar(255) DEFAULT NULL,
  `uploaded_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_videos_variant` (`variant_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `videos`
--

INSERT INTO `videos` (`id`, `variant_id`, `title`, `description`, `filepath`, `thumbnail_path`, `uploaded_at`, `created_at`) VALUES
(10, 4, 'iuafhg', 'aiywgfr', 'uploads/videos/MT-15/1757762903_videoplayback.mp4', 'uploads/thumbnails/MT-15/1757762903_af6b43a6-0f95-4945-bfe1-a957eb441e0b.__CR0,0,970,600_PT0_SX970_V1___.jpg', '2025-09-13 11:28:23', '2025-09-13 11:28:23'),
(12, 4, 'tyrd', '7tr', 'uploads/videos/duke_200/1757764022_videoplayback.mp4', 'uploads/thumbnails/duke_200/1757764022_af6b43a6-0f95-4945-bfe1-a957eb441e0b.__CR0,0,970,600_PT0_SX970_V1___.jpg', '2025-09-13 11:47:02', '2025-09-13 11:47:02');

-- --------------------------------------------------------

--
-- Table structure for table `video_interactions`
--

DROP TABLE IF EXISTS `video_interactions`;
CREATE TABLE IF NOT EXISTS `video_interactions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `video_id` int NOT NULL,
  `user_id` int NOT NULL,
  `type` enum('like','dislike','comment') NOT NULL,
  `comment` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `video_id` (`video_id`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `video_interactions`
--

INSERT INTO `video_interactions` (`id`, `video_id`, `user_id`, `type`, `comment`, `created_at`) VALUES
(42, 10, 8, 'dislike', NULL, '2025-10-06 13:05:29'),
(33, 12, 8, 'comment', 'sdfsd', '2025-09-29 07:03:53');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `models`
--
ALTER TABLE `models`
  ADD CONSTRAINT `models_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `companies` (`company_id`);

--
-- Constraints for table `model_variants`
--
ALTER TABLE `model_variants`
  ADD CONSTRAINT `model_variants_ibfk_1` FOREIGN KEY (`model_id`) REFERENCES `models` (`model_id`);

--
-- Constraints for table `spares`
--
ALTER TABLE `spares`
  ADD CONSTRAINT `fk_model` FOREIGN KEY (`model_id`) REFERENCES `models` (`model_id`) ON DELETE CASCADE;

--
-- Constraints for table `videos`
--
ALTER TABLE `videos`
  ADD CONSTRAINT `fk_videos_variant` FOREIGN KEY (`variant_id`) REFERENCES `model_variants` (`variant_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
