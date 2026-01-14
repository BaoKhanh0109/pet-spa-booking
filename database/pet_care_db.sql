-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 14, 2026 at 11:24 AM
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

INSERT INTO `appointments` (`appointmentID`, `userID`, `petID`, `employeeID`, `appointmentDate`, `endDate`, `note`, `created_at`, `updated_at`, `status`, `prefer_doctor`) VALUES
(1, 2, 1, 1, '2026-01-14 10:00:00', NULL, 'Khám định kỳ', '2026-01-13 21:25:56', '2026-01-13 21:25:56', 'Pending', 1),
(2, 3, 3, 3, '2026-01-15 14:00:00', NULL, 'Cắt móng kỹ giúp em', '2026-01-13 21:25:56', '2026-01-13 21:25:56', 'Pending', 0),
(3, 2, 2, 2, '2026-01-16 09:30:00', NULL, 'Tiêm mũi nhắc lại', '2026-01-13 21:25:56', '2026-01-13 21:25:56', 'Pending', 0),
(4, 3, 5, 3, '2026-01-17 10:00:00', NULL, 'Spa thư giãn', '2026-01-13 21:25:56', '2026-01-13 21:25:56', 'Pending', 0),
(5, 4, 4, 4, '2026-01-18 08:00:00', '2026-01-20 18:00:00', 'Đi công tác 3 ngày', '2026-01-13 21:25:56', '2026-01-13 21:25:56', 'Pending', 0),
(13, 6, 8, 3, '2026-01-15 09:30:00', NULL, NULL, '2026-01-14 17:04:07', '2026-01-14 17:04:07', 'Pending', 0);

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
(5, 4, 3),
(6, 5, 5),
(15, 13, 4);

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `employeeID` int(11) NOT NULL,
  `employeeName` varchar(100) NOT NULL,
  `avatar` text DEFAULT NULL,
  `roleID` int(11) DEFAULT NULL,
  `phoneNumber` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `info` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`employeeID`, `employeeName`, `avatar`, `roleID`, `phoneNumber`, `email`, `info`) VALUES
(1, 'Nguyễn Văn An', 'employees/1JbK17IVnl31aXv0tpVkWbrnwhtl9Gzb7OFZZWqL.jpg', 1, '0901111111', 'bs.an@petcare.com', NULL),
(2, 'Lê Thị Lan', 'employees/TKBY5R40we0lAEskHbeLiEm0UM625no4reJ6sH0Y.jpg', 1, '0902222222', 'bs.lan@petcare.com', NULL),
(3, 'Trần Văn Hùng', 'employees/7Wa1gbHEyn5U4pkHrgFBl9FUdA8YQ5gJZ5rQ4bv2.jpg', 2, '0903333333', 'hung.groomer@petcare.com', NULL),
(4, 'Phạm Thị Như', 'employees/ycog7A5AfK13kPTf1BnPFLSbhJp0nRMdJTTOZAsO.jpg', 3, '0904444444', 'mai.care@petcare.com', NULL);

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
(1, 'Bác sĩ thú y', 'Khám bệnh, chẩn đoán và điều trị cho thú cưng', '2026-01-13 21:25:56', '2026-01-13 21:25:56'),
(2, 'Chuyên viên chăm sóc', 'Cắt tỉa, tạo kiểu và làm đẹp cho thú cưng', '2026-01-13 21:25:56', '2026-01-14 11:22:19'),
(3, 'Nhân viên trông giữ', 'Chăm sóc, trông giữ thú cưng', '2026-01-13 21:25:56', '2026-01-14 16:27:37');

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
(7, 4, 3),
(8, 4, 5);

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
(3, 3, 'Lu', 'Chó', 'Golden Retriever', 25.00, 50.00, 4, 'lu.jpg', 'Sức khỏe tốt'),
(4, 4, 'Vàng', 'Chó', 'Chó cỏ', 10.00, 35.00, 2, 'vang.jpg', NULL),
(5, 3, 'Max', 'Chó', 'Husky', 22.00, 55.00, 3, 'max.jpg', 'Tiêm phòng đầy đủ'),
(6, 2, 'Miu', 'Mèo', 'Munchkin', 2.50, 20.00, 1, 'miu.jpg', 'Chân ngắn, khỏe mạnh'),
(8, 6, 'Vàng', 'Chó', 'Chó cỏ', 15.00, 50.00, 2, 'pets/3IXujMrATtcCurWERPUejztQIXW4YzapJfXEY7mg.jpg', NULL),
(9, 6, 'Bún', 'Mèo', 'mèo anh lông ngắn', 4.00, 30.00, 2, 'pets/P7x5ocmj7Gb59fnP5q615HmmYX6rAAaeyRPi8HLi.jpg', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `serviceID` int(11) NOT NULL,
  `serviceName` varchar(100) NOT NULL,
  `categoryID` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `duration` int(11) NOT NULL DEFAULT 60 COMMENT 'Thời gian thực hiện (phút)',
  `serviceImage` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`serviceID`, `serviceName`, `categoryID`, `price`, `description`, `duration`, `serviceImage`) VALUES
(1, 'Khám sức khỏe tổng quát', 2, 200000.00, 'Kiểm tra nhiệt độ, tim phổi, tai mũi họng', 30, 'services/LBMUvH6Pjyy0YhFywPlkSXN3Z2pPOlui0jgqV3QY.jpg'),
(2, 'Tiêm vaccine 7 bệnh', 2, 250000.00, 'Vaccine phòng các bệnh phổ biến cho chó', 15, 'services/cLAkLM92X1oVVPebjbfssQex4urz29QTPb6Yb0kb.jpg'),
(3, 'Spa - Tắm gội', 1, 300000.00, 'Tắm, sấy khô, chải lông, cắt móng', 60, 'services/CZi0PSJEjFd6Xf66W8WPXyQQ3hTvdiDu85A5tqOX.jpg'),
(4, 'Cắt tỉa lông', 1, 450000.00, 'Cắt tỉa tạo kiểu thẩm mỹ', 90, 'services/Q46vkanVWowPaBFTPRMujbs2zl5jM7mGxX5pYpjB.jpg'),
(5, 'Trông giữ thú cưng', 3, 150000.00, 'Giữ thú cưng 24h bao gồm ăn uống', 1440, 'services/cqvG1PAYg8BHgQYxcEQGNXaAVPK1mUZdugmRFqIp.jpg');

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
(1, 'Làm đẹp', 'Dịch vụ spa, grooming, cắt tỉa', NULL),
(2, 'Y tế', 'Dịch vụ khám bệnh, tiêm phòng', NULL),
(3, 'Trông giữ', 'Dịch vụ trông giữ thú cưng', 20);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `google_id` varchar(100) DEFAULT NULL,
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

INSERT INTO `users` (`userID`, `email`, `password`, `google_id`, `remember_token`, `name`, `phone`, `address`, `role`, `created_at`, `updated_at`) VALUES
(2, 'user1@gmail.com', '$2y$12$LQv3c1yycn1FvTTLW4HKpuP9Tc1xJbEz/.k.x8tDp6YrZ1EzDqmJe', NULL, NULL, 'Nguyễn Văn A', '0911111111', 'Quận 1, TP.HCM', 'user', '2026-01-13 21:25:56', '2026-01-13 21:25:56'),
(3, 'user2@gmail.com', '$2y$12$LQv3c1yycn1FvTTLW4HKpuP9Tc1xJbEz/.k.x8tDp6YrZ1EzDqmJe', NULL, NULL, 'Trần Thị B', '0922222222', 'Quận 3, TP.HCM', 'user', '2026-01-13 21:25:56', '2026-01-13 21:25:56'),
(4, 'user3@gmail.com', '$2y$12$LQv3c1yycn1FvTTLW4HKpuP9Tc1xJbEz/.k.x8tDp6YrZ1EzDqmJe', NULL, NULL, 'Lê Văn C', '0933333333', 'Quận 7, TP.HCM', 'user', '2026-01-13 21:25:56', '2026-01-13 21:25:56'),
(6, 'duongbaokhanh123tv@gmail.com', '$2y$12$IgBpHUKiEQZj/271wWvHEerpLHprtecIuaIL9iK1Nb/VdCmneGhs6', '104212654730297708473', NULL, 'Khanh Dương', '', '', 'user', '2026-01-13 22:57:19', '2026-01-13 22:57:19'),
(7, 'admin@gmail.com', '$2y$12$zrARCfsEu9dC3XPqMEtPG.q1VxYALrI1Ve2IRFAI6A6GzeaRtAsym', NULL, NULL, 'Bảo Khanh', '0396569746', 'Vĩnh Long', 'admin', '2026-01-14 11:16:36', '2026-01-14 16:31:50');

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
  ADD KEY `employeeID` (`employeeID`);

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
  ADD PRIMARY KEY (`employeeID`),
  ADD KEY `roleID` (`roleID`);

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
  ADD UNIQUE KEY `email` (`email`);

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
  MODIFY `appointmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `appointment_services`
--
ALTER TABLE `appointment_services`
  MODIFY `appointment_servicesId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

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
-- AUTO_INCREMENT for table `pets`
--
ALTER TABLE `pets`
  MODIFY `petID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`employeeID`) REFERENCES `employees` (`employeeID`);

--
-- Constraints for table `appointment_services`
--
ALTER TABLE `appointment_services`
  ADD CONSTRAINT `appointment_services_ibfk_1` FOREIGN KEY (`appointmentID`) REFERENCES `appointments` (`appointmentID`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointment_services_ibfk_2` FOREIGN KEY (`serviceID`) REFERENCES `services` (`serviceID`) ON DELETE CASCADE;

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`roleID`) REFERENCES `employee_roles` (`roleID`) ON DELETE SET NULL;

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
