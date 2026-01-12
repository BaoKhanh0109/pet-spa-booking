-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 12, 2026 at 04:14 AM
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
-- Database: `pet_care_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `appointmentID` int(11) NOT NULL,
  `service_categories` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `petID` int(11) NOT NULL,
  `employeeID` int(11) DEFAULT NULL,
  `appointmentDate` datetime DEFAULT NULL,
  `endDate` datetime DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` varchar(50) DEFAULT 'Pending',
  `prefer_doctor` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`appointmentID`, `service_categories`, `userID`, `petID`, `employeeID`, `appointmentDate`, `endDate`, `note`, `created_at`, `updated_at`, `status`, `prefer_doctor`) VALUES
(1, 2, 2, 1, 1, '2025-12-20 09:00:00', NULL, 'Bé bỏ ăn 2 ngày', '2026-01-12 07:41:21', '2026-01-12 07:41:21', 'Completed', 1),
(2, 1, 3, 3, 3, '2025-12-21 14:00:00', NULL, 'Cắt móng kỹ giúp em', '2026-01-12 07:41:21', '2026-01-12 07:41:21', 'Completed', 0),
(3, 2, 2, 2, 2, '2025-12-28 10:00:00', NULL, 'Tiêm mũi nhắc lại', '2026-01-12 07:41:21', '2026-01-12 07:41:21', 'Pending', 0),
(4, 3, 2, 1, 4, '2025-12-31 08:00:00', '2026-01-03 18:00:00', 'Đi du lịch Tết, nhờ chăm sóc', '2026-01-12 07:41:21', '2026-01-12 07:41:21', 'Pending', 0),
(5, 1, 3, 3, 3, '2025-12-30 15:00:00', NULL, 'Spa thư giãn cuối năm', '2026-01-12 07:41:21', '2026-01-12 07:41:21', 'Pending', 0),
(6, 2, 3, 3, 1, '2025-12-18 10:30:00', NULL, 'Kiểm tra định kỳ', '2026-01-12 07:41:21', '2026-01-12 07:41:21', 'Completed', 1),
(7, 3, 4, 4, 4, '2026-01-12 00:00:00', '2026-01-13 00:00:00', NULL, '2026-01-12 02:04:50', '2026-01-12 02:04:50', 'Pending', 0);

-- --------------------------------------------------------

--
-- Table structure for table `appointment_services`
--

CREATE TABLE `appointment_services` (
  `appointment_servicesId` int(11) NOT NULL,
  `appointmentID` int(11) NOT NULL,
  `serviceID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment_services`
--

INSERT INTO `appointment_services` (`appointment_servicesId`, `appointmentID`, `serviceID`) VALUES
(1, 1, 1),
(2, 2, 3),
(3, 2, 4),
(4, 3, 2),
(5, 4, 5),
(6, 5, 3),
(7, 6, 1),
(8, 7, 5);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `employeeID` int(11) NOT NULL,
  `employeeName` varchar(100) NOT NULL,
  `avatar` text DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `phoneNumber` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `info` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`employeeID`, `employeeName`, `avatar`, `role`, `phoneNumber`, `email`, `info`) VALUES
(1, 'Bs. Nguyễn Văn An', NULL, 'Bác sĩ thú y', '0901111111', 'bs.an@petcare.com', NULL),
(2, 'Bs. Lê Thị Lan', NULL, 'Bác sĩ thú y', '0902222222', 'bs.lan@petcare.com', NULL),
(3, 'Trần Văn Hùng', NULL, 'Chuyên viên Grooming', '0903333333', 'hung.groomer@petcare.com', NULL),
(4, 'Phạm Thị Mai', NULL, 'Nhân viên chăm sóc', '0904444444', 'mai.care@petcare.com', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `employee_roles`
--

CREATE TABLE `employee_roles` (
  `roleID` int(11) NOT NULL,
  `roleName` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_roles`
--

INSERT INTO `employee_roles` (`roleID`, `roleName`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Bác sĩ thú y', 'Khám bệnh, chẩn đoán và điều trị cho thú cưng', '2026-01-12 07:41:20', '2026-01-12 07:41:20'),
(2, 'Chuyên viên Grooming', 'Cắt tỉa, tạo kiểu và làm đẹp cho thú cưng', '2026-01-12 07:41:20', '2026-01-12 07:41:20'),
(3, 'Nhân viên chăm sóc', 'Chăm sóc, trông giữ thú cưng', '2026-01-12 07:41:20', '2026-01-12 07:41:20'),
(4, 'Trợ lý bác sĩ', 'Hỗ trợ bác sĩ trong quá trình khám và điều trị', '2026-01-12 07:41:20', '2026-01-12 07:41:20'),
(5, 'Lễ tân', 'Tiếp đón và tư vấn khách hàng', '2026-01-12 07:41:20', '2026-01-12 07:41:20');

-- --------------------------------------------------------

--
-- Table structure for table `employee_service`
--

CREATE TABLE `employee_service` (
  `id` int(11) NOT NULL,
  `employeeID` int(11) NOT NULL,
  `serviceID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_service`
--

INSERT INTO `employee_service` (`id`, `employeeID`, `serviceID`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 2, 1),
(4, 2, 2),
(5, 3, 3),
(6, 3, 4),
(8, 4, 3),
(7, 4, 5);

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
(1, '2026_01_12_023659_add_google_id_to_users_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pets`
--

CREATE TABLE `pets` (
  `petID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `petName` varchar(50) NOT NULL,
  `species` varchar(50) DEFAULT NULL,
  `breed` varchar(50) DEFAULT NULL,
  `weight` decimal(5,2) DEFAULT NULL,
  `backLength` decimal(5,2) DEFAULT NULL COMMENT 'Chiều dài lưng (cm)',
  `age` int(11) DEFAULT NULL,
  `petImage` text DEFAULT NULL,
  `medicalHistory` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pets`
--

INSERT INTO `pets` (`petID`, `userID`, `petName`, `species`, `breed`, `weight`, `backLength`, `age`, `petImage`, `medicalHistory`) VALUES
(1, 2, 'Milo', 'Chó', 'Poodle', 4.50, 25.00, 2, 'milo.jpg', 'Dị ứng phấn hoa'),
(2, 2, 'Kitty', 'Mèo', 'Anh lông ngắn', 3.00, 22.00, 1, 'kitty.jpg', 'Đã triệt sản'),
(3, 3, 'Lu', 'Chó', 'Golden', 25.00, 50.00, 4, 'lu.jpg', 'Sức khỏe tốt'),
(4, 4, 'Vàng', 'Chó', 'Chó cỏ', 10.00, 50.00, 2, 'pets/KwmMRXog6ukvYNHlkZCBB3YjZVQ4bF5JRyuMhzQJ.jpg', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `serviceID` int(11) NOT NULL,
  `serviceName` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `categoryID` int(11) NOT NULL,
  `duration` int(11) NOT NULL DEFAULT 60 COMMENT 'Thời gian thực hiện (phút)',
  `serviceImage` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`serviceID`, `serviceName`, `description`, `price`, `categoryID`, `duration`, `serviceImage`) VALUES
(1, 'Khám sức khỏe tổng quát', 'Kiểm tra nhiệt độ, tim phổi, tai mũi họng', 200000.00, 2, 30, NULL),
(2, 'Tiêm vaccine 7 bệnh', 'Vaccine phòng các bệnh phổ biến cho chó', 250000.00, 2, 15, NULL),
(3, 'Spa - Tắm gội', 'Tắm, sấy khô, chải lông, cắt móng', 300000.00, 1, 60, NULL),
(4, 'Cắt tỉa lông (Styling)', 'Cắt tỉa tạo kiểu thẩm mỹ', 450000.00, 1, 90, NULL),
(5, 'Trông giữ thú cưng (Ngày)', 'Giữ thú cưng 24h bao gồm ăn uống', 150000.00, 3, 1440, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `service_categories`
--

CREATE TABLE `service_categories` (
  `categoryID` int(11) NOT NULL,
  `categoryName` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL COMMENT 'Sức chứa tối đa (chỉ dùng cho pet_care)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_categories`
--

INSERT INTO `service_categories` (`categoryID`, `categoryName`, `description`, `capacity`) VALUES
(1, 'Làm đẹp', 'Các dịch vụ spa, grooming, cắt tỉa lông', NULL),
(2, 'Y tế', 'Khám bệnh, tiêm vaccine, điều trị', NULL),
(3, 'Trông giữ', 'Dịch vụ lưu trú chăm sóc thú cưng 24/7', 20);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text DEFAULT NULL,
  `role` varchar(50) DEFAULT 'user',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userID`, `email`, `google_id`, `password`, `remember_token`, `name`, `phone`, `address`, `role`, `created_at`, `updated_at`) VALUES
(1, 'admin@petcare.com', NULL, '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5NANClx/nbJFi', NULL, 'Quản Trị Hệ Thống', '0999000000', 'Hà Nội', 'admin', '2026-01-12 07:41:21', '2026-01-12 07:41:21'),
(2, 'nguyenvanA@gmail.com', NULL, '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5NANClx/nbJFi', NULL, 'Nguyễn Văn A', '0988777666', '123 Xuân Thủy, Cầu Giấy', 'user', '2026-01-12 07:41:21', '2026-01-12 07:41:21'),
(3, 'tranthiB@gmail.com', NULL, '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5NANClx/nbJFi', NULL, 'Trần Thị B', '0911223344', '456 Nguyễn Trãi, Thanh Xuân', 'user', '2026-01-12 07:41:21', '2026-01-12 07:41:21'),
(4, 'khanh@gmail.com', NULL, '$2y$12$YDGjZiMuBRYC6mZtzN3tHuI6fAcoHsItreOXg5f88LiFS54/bxkA2', NULL, 'Như Yến Nguyễn', '0707739351', '577,Dương Quang Đông', 'admin', '2026-01-12 01:51:05', '2026-01-12 08:59:28'),
(5, 'nhynhu5124@gmail.com', '110909300767006277968', '$2y$12$.Z3ZEh.Wx5HKMWRKRo3skejVdMLFk7xtt7xbP/1Qy1iiqct8hq4lK', NULL, 'Nguyễn Huỳnh Yến Như', '', '', 'customer', '2026-01-12 03:09:09', '2026-01-12 03:09:09'),
(6, 'nhynhu51@gmail.com', '111868168693569975832', '$2y$12$4fVtvi7LgOwf0xa3EhntEe6MwoUHHZqd1V8tFk6KzzWTAABd2aUHK', NULL, 'Yến Như Nguyễn', '', '', 'customer', '2026-01-12 03:12:57', '2026-01-12 03:12:57');

-- --------------------------------------------------------

--
-- Table structure for table `work_schedules`
--

CREATE TABLE `work_schedules` (
  `scheduleID` int(11) NOT NULL,
  `employeeID` int(11) NOT NULL,
  `dayOfWeek` varchar(20) DEFAULT NULL,
  `startTime` time DEFAULT NULL,
  `endTime` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `work_schedules`
--

INSERT INTO `work_schedules` (`scheduleID`, `employeeID`, `dayOfWeek`, `startTime`, `endTime`) VALUES
(1, 1, 'Monday', '08:00:00', '17:00:00'),
(2, 1, 'Tuesday', '08:00:00', '17:00:00'),
(3, 1, 'Wednesday', '08:00:00', '17:00:00'),
(4, 1, 'Thursday', '08:00:00', '17:00:00'),
(5, 1, 'Friday', '08:00:00', '17:00:00'),
(6, 2, 'Monday', '08:00:00', '17:00:00'),
(7, 2, 'Tuesday', '08:00:00', '17:00:00'),
(8, 2, 'Wednesday', '08:00:00', '17:00:00'),
(9, 2, 'Thursday', '08:00:00', '17:00:00'),
(10, 2, 'Saturday', '08:00:00', '12:00:00'),
(11, 3, 'Monday', '09:00:00', '18:00:00'),
(12, 3, 'Tuesday', '09:00:00', '18:00:00'),
(13, 3, 'Wednesday', '09:00:00', '18:00:00'),
(14, 3, 'Thursday', '09:00:00', '18:00:00'),
(15, 3, 'Friday', '09:00:00', '18:00:00'),
(16, 3, 'Saturday', '09:00:00', '17:00:00'),
(17, 4, 'Monday', '09:00:00', '18:00:00'),
(18, 4, 'Tuesday', '09:00:00', '18:00:00'),
(19, 4, 'Wednesday', '09:00:00', '18:00:00'),
(20, 4, 'Thursday', '09:00:00', '18:00:00'),
(21, 4, 'Friday', '09:00:00', '18:00:00'),
(22, 4, 'Saturday', '09:00:00', '17:00:00'),
(23, 4, 'Sunday', '09:00:00', '17:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`appointmentID`),
  ADD KEY `userID` (`userID`),
  ADD KEY `employeeID` (`employeeID`),
  ADD KEY `service_categories` (`service_categories`);

--
-- Indexes for table `appointment_services`
--
ALTER TABLE `appointment_services`
  ADD PRIMARY KEY (`appointment_servicesId`),
  ADD KEY `appointmentID` (`appointmentID`),
  ADD KEY `serviceID` (`serviceID`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`employeeID`);

--
-- Indexes for table `employee_roles`
--
ALTER TABLE `employee_roles`
  ADD PRIMARY KEY (`roleID`),
  ADD UNIQUE KEY `roleName` (`roleName`);

--
-- Indexes for table `employee_service`
--
ALTER TABLE `employee_service`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employeeID` (`employeeID`,`serviceID`),
  ADD KEY `serviceID` (`serviceID`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pets`
--
ALTER TABLE `pets`
  ADD PRIMARY KEY (`petID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`serviceID`),
  ADD KEY `categoryID` (`categoryID`);

--
-- Indexes for table `service_categories`
--
ALTER TABLE `service_categories`
  ADD PRIMARY KEY (`categoryID`),
  ADD UNIQUE KEY `categoryName` (`categoryName`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `users_google_id_unique` (`google_id`);

--
-- Indexes for table `work_schedules`
--
ALTER TABLE `work_schedules`
  ADD PRIMARY KEY (`scheduleID`),
  ADD KEY `employeeID` (`employeeID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `appointmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `appointment_services`
--
ALTER TABLE `appointment_services`
  MODIFY `appointment_servicesId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `employeeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `employee_roles`
--
ALTER TABLE `employee_roles`
  MODIFY `roleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `employee_service`
--
ALTER TABLE `employee_service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pets`
--
ALTER TABLE `pets`
  MODIFY `petID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `serviceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `service_categories`
--
ALTER TABLE `service_categories`
  MODIFY `categoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `work_schedules`
--
ALTER TABLE `work_schedules`
  MODIFY `scheduleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`),
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`employeeID`) REFERENCES `employees` (`employeeID`),
  ADD CONSTRAINT `appointments_ibfk_3` FOREIGN KEY (`service_categories`) REFERENCES `service_categories` (`categoryID`);

--
-- Constraints for table `appointment_services`
--
ALTER TABLE `appointment_services`
  ADD CONSTRAINT `appointment_services_ibfk_1` FOREIGN KEY (`appointmentID`) REFERENCES `appointments` (`appointmentID`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointment_services_ibfk_2` FOREIGN KEY (`serviceID`) REFERENCES `services` (`serviceID`) ON DELETE CASCADE;

--
-- Constraints for table `employee_service`
--
ALTER TABLE `employee_service`
  ADD CONSTRAINT `employee_service_ibfk_1` FOREIGN KEY (`employeeID`) REFERENCES `employees` (`employeeID`) ON DELETE CASCADE,
  ADD CONSTRAINT `employee_service_ibfk_2` FOREIGN KEY (`serviceID`) REFERENCES `services` (`serviceID`) ON DELETE CASCADE;

--
-- Constraints for table `pets`
--
ALTER TABLE `pets`
  ADD CONSTRAINT `pets_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE;

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_ibfk_1` FOREIGN KEY (`categoryID`) REFERENCES `service_categories` (`categoryID`);

--
-- Constraints for table `work_schedules`
--
ALTER TABLE `work_schedules`
  ADD CONSTRAINT `work_schedules_ibfk_1` FOREIGN KEY (`employeeID`) REFERENCES `employees` (`employeeID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
