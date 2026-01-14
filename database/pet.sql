drop DATABASE IF EXISTS pet_care_db;
create DATABASE pet_care_db;
use pet_care_db;

CREATE TABLE service_categories (
    categoryID INT PRIMARY KEY AUTO_INCREMENT,
    categoryName VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    capacity INT DEFAULT NULL COMMENT 'Sức chứa tối đa (chỉ dùng cho pet_care)'
);

CREATE TABLE services (
    serviceID INT PRIMARY KEY AUTO_INCREMENT,
    serviceName VARCHAR(100) NOT NULL,
    categoryID INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    description TEXT,
    duration INT NOT NULL DEFAULT 60 COMMENT 'Thời gian thực hiện (phút)',
    serviceImage VARCHAR(255),
    FOREIGN KEY (categoryID) REFERENCES service_categories(categoryID)
);

CREATE TABLE employee_roles (
    roleID INT PRIMARY KEY AUTO_INCREMENT,
    roleName VARCHAR(50) NOT NULL UNIQUE,
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE employees (
    employeeID INT PRIMARY KEY AUTO_INCREMENT,
    employeeName VARCHAR(100) NOT NULL,
    avatar TEXT,
    roleID INT,
    phoneNumber VARCHAR(20),
    email VARCHAR(100),
    info TEXT
);
CREATE TABLE work_schedules (
    scheduleID INT PRIMARY KEY AUTO_INCREMENT,
    employeeID INT NOT NULL,
    dayOfWeek VARCHAR(20),
    startTime TIME,
    endTime TIME,
    FOREIGN KEY (employeeID) REFERENCES employees(employeeID) ON DELETE CASCADE
);
CREATE TABLE employee_service (
    id INT PRIMARY KEY AUTO_INCREMENT,
    employeeID INT NOT NULL,
    serviceID INT NOT NULL,
    FOREIGN KEY (employeeID) REFERENCES employees(employeeID) ON DELETE CASCADE,
    FOREIGN KEY (serviceID) REFERENCES services(serviceID) ON DELETE CASCADE,
    UNIQUE(employeeID, serviceID) 
);
CREATE TABLE users (
    userID INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    google_id VARCHAR(100) NULL,
    remember_token VARCHAR(100) NULL,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address TEXT,
    role VARCHAR(50) DEFAULT 'user',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE TABLE pets (
    petID INT PRIMARY KEY AUTO_INCREMENT,
    userID INT NOT NULL,
    petName VARCHAR(50) NOT NULL,
    species VARCHAR(50),
    breed VARCHAR(50),
    weight DECIMAL(5, 2),
    backLength DECIMAL(5, 2) COMMENT 'Chiều dài lưng (cm)',
    age INT,
    petImage TEXT,
    medicalHistory TEXT,
    FOREIGN KEY (userID) REFERENCES users(userID) ON DELETE CASCADE
);
CREATE TABLE appointments (
    appointmentID INT PRIMARY KEY AUTO_INCREMENT,
    userID INT NOT NULL,
    petID INT NOT NULL,
    employeeID INT,
    appointmentDate DATETIME,
    endDate DATETIME,
    note TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    status VARCHAR(50) DEFAULT 'Pending',
    prefer_doctor TINYINT(1) DEFAULT 0,
    FOREIGN KEY (userID) REFERENCES users(userID),
    FOREIGN KEY (employeeID) REFERENCES employees(employeeID)
);


CREATE TABLE appointment_services (
    appointment_servicesId INT PRIMARY KEY AUTO_INCREMENT,
    appointmentID INT NOT NULL,
    serviceID INT NOT NULL,
    FOREIGN KEY (appointmentID) REFERENCES appointments(appointmentID) ON DELETE CASCADE,
    FOREIGN KEY (serviceID) REFERENCES services(serviceID) ON DELETE CASCADE
);


USE pet_care_db;

-- 1. Thêm Chức vụ nhân viên (Employee Roles)
INSERT INTO employee_roles (roleName, description) VALUES
('Bác sĩ thú y', 'Khám bệnh, chẩn đoán và điều trị cho thú cưng'),
('Chuyên viên Grooming', 'Cắt tỉa, tạo kiểu và làm đẹp cho thú cưng'),
('Nhân viên chăm sóc', 'Chăm sóc, trông giữ thú cưng'),
('Trợ lý bác sĩ', 'Hỗ trợ bác sĩ trong quá trình khám và điều trị'),
('Lễ tân', 'Tiếp đón và tư vấn khách hàng');

-- 2. Thêm Danh mục dịch vụ (Service Categories)
INSERT INTO service_categories (categoryName, description, capacity) VALUES
('Làm đẹp', 'Các dịch vụ spa, grooming, cắt tỉa lông', NULL),
('Y tế', 'Khám bệnh, tiêm vaccine, điều trị', NULL),
('Trông giữ', 'Dịch vụ lưu trú chăm sóc thú cưng 24/7', 20);

-- 2. Thêm Dịch vụ (Services)
INSERT INTO services (serviceName, description, price, categoryID, duration) VALUES
('Khám sức khỏe tổng quát', 'Kiểm tra nhiệt độ, tim phổi, tai mũi họng', 200000, 2, 30),
('Tiêm vaccine 7 bệnh', 'Vaccine phòng các bệnh phổ biến cho chó', 250000, 2, 15),
('Spa - Tắm gội', 'Tắm, sấy khô, chải lông, cắt móng', 300000, 1, 60),
('Cắt tỉa lông (Styling)', 'Cắt tỉa tạo kiểu thẩm mỹ', 450000, 1, 90),
('Trông giữ thú cưng (Ngày)', 'Giữ thú cưng 24h bao gồm ăn uống', 150000, 3, 1440);

-- 3. Thêm Nhân viên (Employees)
INSERT INTO employees (employeeName, roleID, phoneNumber, email) VALUES
('Bs. Nguyễn Văn An', 1, '0901111111', 'bs.an@petcare.com'),
('Bs. Lê Thị Lan', 1, '0902222222', 'bs.lan@petcare.com'),
('Trần Văn Hùng', 2, '0903333333', 'hung.groomer@petcare.com'),
('Phạm Thị Mai', 3, '0904444444', 'mai.care@petcare.com');
-- 4. Thêm Phân công dịch vụ cho nhân viên (employee_service) - MỚI
-- Bác sĩ An làm được Khám (1) và Tiêm (2)
INSERT INTO employee_service (employeeID, serviceID) VALUES 
(1, 1), (1, 2);
-- Bác sĩ Lan làm được Khám (1) và Tiêm (2)
INSERT INTO employee_service (employeeID, serviceID) VALUES 
(2, 1), (2, 2);
-- Hùng làm được Spa (3) và Cắt tỉa (4)
INSERT INTO employee_service (employeeID, serviceID) VALUES 
(3, 3), (3, 4);
-- Mai làm được Trông giữ (5) và Spa (3)
INSERT INTO employee_service (employeeID, serviceID) VALUES 
(4, 5), (4, 3);

-- 5. Thêm Lịch làm việc (Work Schedules)
INSERT INTO work_schedules (employeeID, dayOfWeek, startTime, endTime) VALUES
-- Bs An - Bác sĩ thú y
(1, 'Monday', '08:00:00', '17:00:00'),
(1, 'Tuesday', '08:00:00', '17:00:00'),
(1, 'Wednesday', '08:00:00', '17:00:00'),
(1, 'Thursday', '08:00:00', '17:00:00'),
(1, 'Friday', '08:00:00', '17:00:00'),
-- Bs Lan - Bác sĩ thú y
(2, 'Monday', '08:00:00', '17:00:00'),
(2, 'Tuesday', '08:00:00', '17:00:00'),
(2, 'Wednesday', '08:00:00', '17:00:00'),
(2, 'Thursday', '08:00:00', '17:00:00'),
(2, 'Saturday', '08:00:00', '12:00:00'),
-- Hùng - Chuyên viên Grooming (làm đẹp)
(3, 'Monday', '09:00:00', '18:00:00'),
(3, 'Tuesday', '09:00:00', '18:00:00'),
(3, 'Wednesday', '09:00:00', '18:00:00'),
(3, 'Thursday', '09:00:00', '18:00:00'),
(3, 'Friday', '09:00:00', '18:00:00'),
(3, 'Saturday', '09:00:00', '17:00:00'),
-- Mai - Nhân viên chăm sóc (spa + trông giữ)
(4, 'Monday', '09:00:00', '18:00:00'),
(4, 'Tuesday', '09:00:00', '18:00:00'),
(4, 'Wednesday', '09:00:00', '18:00:00'),
(4, 'Thursday', '09:00:00', '18:00:00'),
(4, 'Friday', '09:00:00', '18:00:00'),
(4, 'Saturday', '09:00:00', '17:00:00'),
(4, 'Sunday', '09:00:00', '17:00:00');

-- 6. Thêm Người dùng (Users)
-- Password mặc định là: '12345678' (đã hash bcrypt chuẩn Laravel)
INSERT INTO users (email, password, remember_token, name, phone, address, role) VALUES
('admin@petcare.com', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5NANClx/nbJFi', NULL, 'Quản Trị Hệ Thống', '0999000000', 'Hà Nội', 'admin'),
('nguyenvanA@gmail.com', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5NANClx/nbJFi', NULL, 'Nguyễn Văn A', '0988777666', '123 Xuân Thủy, Cầu Giấy', 'user'),
('tranthiB@gmail.com', '$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewY5NANClx/nbJFi', NULL, 'Trần Thị B', '0911223344', '456 Nguyễn Trãi, Thanh Xuân', 'user');

-- 7. Thêm Thú cưng (Pets)
INSERT INTO pets (userID, petName, species, breed, weight, backLength, age, medicalHistory, petImage) VALUES
(2, 'Milo', 'Chó', 'Poodle', 4.5, 25, 2, 'Dị ứng phấn hoa', 'milo.jpg'),
(2, 'Kitty', 'Mèo', 'Anh lông ngắn', 3.0, 22, 1, 'Đã triệt sản', 'kitty.jpg'),
(3, 'Lu', 'Chó', 'Golden', 25.0, 50, 4, 'Sức khỏe tốt', 'lu.jpg');

-- 8. Thêm Lịch hẹn (Appointments)
-- Loại dịch vụ sẽ lấy thông qua: appointments -> appointment_services -> services -> service_categories
INSERT INTO appointments (userID, petID, employeeID, appointmentDate, endDate, note, status, prefer_doctor) VALUES
-- Lịch 1: Y tế - Khám bệnh (Completed)
(2, 1, 1, '2025-12-20 09:00:00', NULL, 'Bé bỏ ăn 2 ngày', 'Completed', 1),
-- Lịch 2: Làm đẹp - Spa + Cắt tỉa (Completed)
(3, 3, 3, '2025-12-21 14:00:00', NULL, 'Cắt móng kỹ giúp em', 'Completed', 0),
-- Lịch 3: Y tế - Tiêm vaccine (Pending)
(2, 2, 2, '2025-12-28 10:00:00', NULL, 'Tiêm mũi nhắc lại', 'Pending', 0),
-- Lịch 4: Trông giữ - Pet Care (Pending)
(2, 1, 4, '2025-12-31 08:00:00', '2026-01-03 18:00:00', 'Đi du lịch Tết, nhờ chăm sóc', 'Pending', 0),
-- Lịch 5: Làm đẹp - Spa đơn (Pending)
(3, 3, 3, '2025-12-30 15:00:00', NULL, 'Spa thư giãn cuối năm', 'Pending', 0),
-- Lịch 6: Y tế - Khám tổng quát (Completed)
(3, 3, 1, '2025-12-18 10:30:00', NULL, 'Kiểm tra định kỳ', 'Completed', 1);

-- 9. Thêm Chi tiết dịch vụ trong lịch hẹn (Appointment Services)
-- Làm đẹp và Y tế dùng bảng này để lưu services đã chọn
INSERT INTO appointment_services (appointmentID, serviceID) VALUES
-- Lịch 1 (Y tế): Khám bệnh
(1, 1),
-- Lịch 2 (Làm đẹp): Spa + Cắt tỉa
(2, 3), 
(2, 4),
-- Lịch 3 (Y tế): Tiêm vaccine
(3, 2),
-- Lịch 4 (Trông giữ): Trông giữ thú cưng
(4, 5),
-- Lịch 5 (Làm đẹp): Spa đơn
(5, 3),
-- Lịch 6 (Y tế): Khám bệnh
(6, 1);