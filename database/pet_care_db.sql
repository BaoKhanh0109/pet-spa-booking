-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 30, 2025 at 07:04 PM
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
  `serviceID` int(11) DEFAULT NULL,
  `appointmentDate` datetime DEFAULT NULL,
  `endDate` datetime DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` varchar(50) DEFAULT 'Pending',
  `booking_type` enum('beauty','medical','pet_care') NOT NULL,
  `prefer_doctor` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`appointmentID`, `userID`, `petID`, `employeeID`, `serviceID`, `appointmentDate`, `endDate`, `note`, `created_at`, `updated_at`, `status`, `booking_type`, `prefer_doctor`) VALUES
(1, 2, 1, 1, 1, '2025-12-20 09:00:00', NULL, 'Bé bỏ ăn 2 ngày', '2025-12-30 20:19:33', '2025-12-30 20:19:33', 'Completed', 'medical', 1),
(2, 3, 3, 3, NULL, '2025-12-21 14:00:00', NULL, 'Cắt móng kỹ giúp em', '2025-12-30 20:19:33', '2025-12-30 20:19:33', 'Completed', 'beauty', NULL),
(3, 2, 2, 2, 2, '2025-12-28 10:00:00', NULL, 'Tiêm mũi nhắc lại', '2025-12-30 20:19:33', '2025-12-30 20:19:33', 'Pending', 'medical', NULL),
(4, 2, 1, 4, 5, '2025-12-31 08:00:00', '2026-01-03 18:00:00', 'Đi du lịch Tết, nhờ chăm sóc', '2025-12-30 20:19:33', '2025-12-30 13:35:07', 'approved', 'pet_care', NULL),
(5, 3, 3, 3, NULL, '2025-12-30 15:00:00', NULL, 'Spa thư giãn cuối năm', '2025-12-30 20:19:33', '2025-12-30 13:52:11', 'canceled', 'beauty', NULL),
(6, 3, 3, 1, 1, '2025-12-18 10:30:00', NULL, 'Kiểm tra định kỳ', '2025-12-30 20:19:33', '2025-12-30 20:19:33', 'Completed', 'medical', 1),
(8, 1, 4, 4, NULL, '2025-12-30 20:27:00', NULL, NULL, '2025-12-30 13:22:34', '2025-12-30 13:35:20', 'approved', 'beauty', 0),
(9, 4, 5, 3, NULL, '2025-12-26 14:32:00', NULL, NULL, '2025-12-30 17:30:44', '2025-12-30 17:30:44', 'Pending', 'beauty', 0),
(10, 4, 6, 2, 1, '2025-12-31 08:00:00', NULL, NULL, '2025-12-30 17:35:47', '2025-12-30 17:36:22', 'approved', 'medical', 1);

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
(1, 2, 3),
(2, 2, 4),
(3, 5, 3),
(4, 8, 3),
(5, 9, 4);

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

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `paymentID` int(11) NOT NULL,
  `appointmentID` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `paymentDate` datetime DEFAULT current_timestamp(),
  `paymentMethod` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`paymentID`, `appointmentID`, `amount`, `paymentDate`, `paymentMethod`) VALUES
(1, 1, 200000.00, '2025-12-30 20:19:33', 'Cash'),
(2, 2, 750000.00, '2025-12-30 20:19:33', 'Credit Card'),
(3, 6, 200000.00, '2025-12-30 20:19:33', 'Cash');

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
  `age` int(11) DEFAULT NULL,
  `petImage` text DEFAULT NULL,
  `medicalHistory` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pets`
--

INSERT INTO `pets` (`petID`, `userID`, `petName`, `species`, `breed`, `weight`, `age`, `petImage`, `medicalHistory`) VALUES
(1, 2, 'Milo', 'Chó', 'Poodle', 4.50, 2, 'milo.jpg', 'Dị ứng phấn hoa'),
(2, 2, 'Kitty', 'Mèo', 'Anh lông ngắn', 3.00, 1, 'kitty.jpg', 'Đã triệt sản'),
(3, 3, 'Lu', 'Chó', 'Golden', 25.00, 4, 'lu.jpg', 'Sức khỏe tốt'),
(4, 1, 'Vàng', 'Chó', 'chó cỏ', 4.00, 2, 'pets/fTvtgJbEwxi2aYZtAaCz8shnVohzal37hCPHU3Qe.jpg', NULL),
(5, 4, 'Vàng', 'Chó', 'Chó cỏ', 4.00, 2, 'pets/wegzd6iuaHsBgO7er6C2WFdVm4lMw8sEfJ3oZGrU.jpg', 'Không'),
(6, 4, 'Phy', 'Mèo', 'Mèo Ai Cập', 2.00, 2, 'pets/cP0D7yz2oA9z20BgMIdsSUNJfElcCac4vAsgXlzz.jpg', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `reviewID` int(11) NOT NULL,
  `paymentID` int(11) NOT NULL,
  `reviewDate` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`reviewID`, `paymentID`, `reviewDate`) VALUES
(1, 1, '2025-12-20 10:30:00'),
(2, 2, '2025-12-21 16:00:00'),
(3, 3, '2025-12-18 11:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `review_details`
--

CREATE TABLE `review_details` (
  `review_detailsId` int(11) NOT NULL,
  `reviewID` int(11) NOT NULL,
  `serviceID` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `review_details`
--

INSERT INTO `review_details` (`review_detailsId`, `reviewID`, `serviceID`, `rating`, `comment`) VALUES
(1, 1, 1, 5, 'Bác sĩ An rất nhẹ nhàng, khám kỹ.'),
(2, 2, 3, 4, 'Sạch sẽ thơm tho nhưng làm hơi lâu.'),
(3, 2, 4, 5, 'Cắt kiểu này rất đẹp, ưng ý.'),
(4, 3, 1, 5, 'Kiểm tra rất chi tiết, yên tâm!');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `serviceID` int(11) NOT NULL,
  `serviceName` varchar(100) NOT NULL,
  `serviceImg` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` enum('beauty','medical','pet_care') NOT NULL DEFAULT 'beauty',
  `duration` int(11) NOT NULL DEFAULT 60 COMMENT 'Thời gian thực hiện (phút)',
  `serviceImage` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`serviceID`, `serviceName`, `serviceImg`, `description`, `price`, `category`, `duration`, `serviceImage`) VALUES
(1, 'Khám sức khỏe tổng quát', NULL, 'Kiểm tra nhiệt độ, tim phổi, tai mũi họng', 200000.00, 'medical', 30, 'services/2kkiJt73tC1wqZab7GSRfBKjdFYRTlhQseiALcY8.jpg'),
(2, 'Tiêm vaccine 7 bệnh', NULL, 'Vaccine phòng các bệnh phổ biến cho chó', 250000.00, 'medical', 15, 'services/7AZXCRdAJ6oGavbwTCIgL1FVeZwK8UWYua3dQeBV.jpg'),
(3, 'Spa - Tắm gội', NULL, 'Tắm, sấy khô, chải lông, cắt móng', 300000.00, 'beauty', 60, 'services/NtbZWow5wDxshQUwTJ8nipIdPZ7YDp3LjZ9rVpMA.jpg'),
(4, 'Cắt tỉa lông (Styling)', NULL, 'Cắt tỉa tạo kiểu thẩm mỹ', 450000.00, 'beauty', 90, 'services/T6C5MDNNI9Kbjgb90W2caNaiVEBtcd5hUUZUHt0m.jpg'),
(5, 'Trông giữ thú cưng (Ngày)', NULL, 'Giữ thú cưng 24h bao gồm ăn uống', 150000.00, 'pet_care', 1440, 'services/1D161XJMHOEl1XQSqrU5dPQJm66tQSvxDxu9mQBe.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userID` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
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

INSERT INTO `users` (`userID`, `email`, `password`, `remember_token`, `name`, `phone`, `address`, `role`, `created_at`, `updated_at`) VALUES
(1, 'admin@petcare.com', '12345678', NULL, 'Quản Trị Hệ Thống', '0999000000', 'Hà Nội', 'admin', '2025-12-30 20:19:33', '2025-12-30 20:19:33'),
(2, 'nguyenvanA@gmail.com', '12345678', NULL, 'Nguyễn Văn A', '0988777666', '123 Xuân Thủy, Cầu Giấy', 'user', '2025-12-30 20:19:33', '2025-12-30 20:19:33'),
(3, 'tranthiB@gmail.com', '12345678', NULL, 'Trần Thị B', '0911223344', '456 Nguyễn Trãi, Thanh Xuân', 'user', '2025-12-30 20:19:33', '2025-12-30 20:19:33'),
(4, 'khanh@gmail.com', '$2y$12$898m4VyFcKsgtb.kWw5UCeQSv.AbCG5Mtp6KN11F8NTUEjdIDBfu6', 'OD94keVFeoqHuFQ780moKLQ8msqCMddWKrscnzNZuyl8gPzkosyebMjYgpm6', 'khanh', '123456789', 'Vĩnh Long', 'admin', '2025-12-30 17:14:21', '2025-12-31 00:59:25');

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
  ADD KEY `petID` (`petID`),
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
  ADD PRIMARY KEY (`employeeID`);

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
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`paymentID`),
  ADD UNIQUE KEY `appointmentID` (`appointmentID`),
  ADD UNIQUE KEY `appointmentID_2` (`appointmentID`);

--
-- Indexes for table `pets`
--
ALTER TABLE `pets`
  ADD PRIMARY KEY (`petID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`reviewID`),
  ADD UNIQUE KEY `paymentID` (`paymentID`);

--
-- Indexes for table `review_details`
--
ALTER TABLE `review_details`
  ADD PRIMARY KEY (`review_detailsId`),
  ADD KEY `reviewID` (`reviewID`),
  ADD KEY `serviceID` (`serviceID`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`serviceID`);

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
  MODIFY `appointmentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `appointment_services`
--
ALTER TABLE `appointment_services`
  MODIFY `appointment_servicesId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `employeeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `employee_service`
--
ALTER TABLE `employee_service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `paymentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pets`
--
ALTER TABLE `pets`
  MODIFY `petID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `reviewID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `review_details`
--
ALTER TABLE `review_details`
  MODIFY `review_detailsId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `serviceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`petID`) REFERENCES `pets` (`petID`),
  ADD CONSTRAINT `appointments_ibfk_3` FOREIGN KEY (`employeeID`) REFERENCES `employees` (`employeeID`);

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
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`appointmentID`) REFERENCES `appointments` (`appointmentID`) ON DELETE CASCADE;

--
-- Constraints for table `pets`
--
ALTER TABLE `pets`
  ADD CONSTRAINT `pets_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`userID`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`paymentID`) REFERENCES `payments` (`paymentID`) ON DELETE CASCADE;

--
-- Constraints for table `review_details`
--
ALTER TABLE `review_details`
  ADD CONSTRAINT `review_details_ibfk_1` FOREIGN KEY (`reviewID`) REFERENCES `reviews` (`reviewID`) ON DELETE CASCADE,
  ADD CONSTRAINT `review_details_ibfk_2` FOREIGN KEY (`serviceID`) REFERENCES `services` (`serviceID`) ON DELETE CASCADE;

--
-- Constraints for table `work_schedules`
--
ALTER TABLE `work_schedules`
  ADD CONSTRAINT `work_schedules_ibfk_1` FOREIGN KEY (`employeeID`) REFERENCES `employees` (`employeeID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
