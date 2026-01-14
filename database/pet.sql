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
    info TEXT,
    FOREIGN KEY (roleID) REFERENCES employee_roles(roleID) ON DELETE SET NULL
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

-- ================================================
-- INSERT SAMPLE DATA
-- ================================================

-- Service Categories
INSERT INTO service_categories (categoryID, categoryName, description, capacity) VALUES
(1, 'Làm đẹp', 'Dịch vụ spa, grooming, cắt tỉa', NULL),
(2, 'Y tế', 'Dịch vụ khám bệnh, tiêm phòng', NULL),
(3, 'Trông giữ', 'Dịch vụ trông giữ thú cưng', 10);

-- Services
INSERT INTO services (serviceID, serviceName, description, categoryID, price, duration, serviceImage) VALUES
(1, 'Khám sức khỏe tổng quát', 'Kiểm tra nhiệt độ, tim phổi, tai mũi họng', 2, 200000.00, 30, NULL),
(2, 'Tiêm vaccine 7 bệnh', 'Vaccine phòng các bệnh phổ biến cho chó', 2, 250000.00, 15, NULL),
(3, 'Spa - Tắm gội', 'Tắm, sấy khô, chải lông, cắt móng', 1, 300000.00, 60, NULL),
(4, 'Cắt tỉa lông (Styling)', 'Cắt tỉa tạo kiểu thẩm mỹ', 1, 450000.00, 90, NULL),
(5, 'Trông giữ thú cưng (Ngày)', 'Giữ thú cưng 24h bao gồm ăn uống', 3, 150000.00, 1440, NULL);

-- Employee Roles
INSERT INTO employee_roles (roleID, roleName, description) VALUES
(1, 'Bác sĩ thú y', 'Khám bệnh, chẩn đoán và điều trị cho thú cưng'),
(2, 'Chuyên viên Grooming', 'Cắt tỉa, tạo kiểu và làm đẹp cho thú cưng'),
(3, 'Nhân viên chăm sóc', 'Chăm sóc, trông giữ thú cưng'),
(4, 'Trợ lý bác sĩ', 'Hỗ trợ bác sĩ trong quá trình khám và điều trị'),
(5, 'Lễ tân', 'Tiếp đón và tư vấn khách hàng');

-- Employees
INSERT INTO employees (employeeID, employeeName, avatar, roleID, phoneNumber, email, info) VALUES
(1, 'Bs. Nguyễn Văn An', NULL, 1, '0901111111', 'bs.an@petcare.com', NULL),
(2, 'Bs. Lê Thị Lan', NULL, 1, '0902222222', 'bs.lan@petcare.com', NULL),
(3, 'Trần Văn Hùng', NULL, 2, '0903333333', 'hung.groomer@petcare.com', NULL),
(4, 'Phạm Thị Mai', NULL, 3, '0904444444', 'mai.care@petcare.com', NULL);

-- Work Schedules
INSERT INTO work_schedules (employeeID, dayOfWeek, startTime, endTime) VALUES
-- Bác sĩ Nguyễn Văn An (T2-T6: 8h-17h)
(1, 'Monday', '08:00:00', '17:00:00'),
(1, 'Tuesday', '08:00:00', '17:00:00'),
(1, 'Wednesday', '08:00:00', '17:00:00'),
(1, 'Thursday', '08:00:00', '17:00:00'),
(1, 'Friday', '08:00:00', '17:00:00'),
-- Bác sĩ Lê Thị Lan (T2-T5: 8h-17h, T7: 8h-12h)
(2, 'Monday', '08:00:00', '17:00:00'),
(2, 'Tuesday', '08:00:00', '17:00:00'),
(2, 'Wednesday', '08:00:00', '17:00:00'),
(2, 'Thursday', '08:00:00', '17:00:00'),
(2, 'Saturday', '08:00:00', '12:00:00'),
-- Trần Văn Hùng - Groomer (T2-T6: 9h-18h, T7: 9h-17h)
(3, 'Monday', '09:00:00', '18:00:00'),
(3, 'Tuesday', '09:00:00', '18:00:00'),
(3, 'Wednesday', '09:00:00', '18:00:00'),
(3, 'Thursday', '09:00:00', '18:00:00'),
(3, 'Friday', '09:00:00', '18:00:00'),
(3, 'Saturday', '09:00:00', '17:00:00'),
-- Phạm Thị Mai - Nhân viên chăm sóc (T2-CN: 9h-18h, T7: 9h-17h)
(4, 'Monday', '09:00:00', '18:00:00'),
(4, 'Tuesday', '09:00:00', '18:00:00'),
(4, 'Wednesday', '09:00:00', '18:00:00'),
(4, 'Thursday', '09:00:00', '18:00:00'),
(4, 'Friday', '09:00:00', '18:00:00'),
(4, 'Saturday', '09:00:00', '17:00:00'),
(4, 'Sunday', '09:00:00', '17:00:00');

-- Employee Service (Nhân viên - Dịch vụ)
INSERT INTO employee_service (employeeID, serviceID) VALUES
(1, 1), -- Bs. An - Khám sức khỏe
(1, 2), -- Bs. An - Tiêm vaccine
(2, 1), -- Bs. Lan - Khám sức khỏe
(2, 2), -- Bs. Lan - Tiêm vaccine
(3, 3), -- Hùng - Spa tắm gội
(3, 4), -- Hùng - Cắt tỉa lông
(4, 3), -- Mai - Spa tắm gội
(4, 5); -- Mai - Trông giữ

-- Users
INSERT INTO users (userID, email, password, name, phone, address, role) VALUES
(1, 'admin@petcare.com', '$2y$12$LQv3c1yycn1FvTTLW4HKpuP9Tc1xJbEz/.k.x8tDp6YrZ1EzDqmJe', 'Admin', '0900000000', 'Hà Nội', 'admin'),
(2, 'user1@gmail.com', '$2y$12$LQv3c1yycn1FvTTLW4HKpuP9Tc1xJbEz/.k.x8tDp6YrZ1EzDqmJe', 'Nguyễn Văn A', '0911111111', 'Quận 1, TP.HCM', 'user'),
(3, 'user2@gmail.com', '$2y$12$LQv3c1yycn1FvTTLW4HKpuP9Tc1xJbEz/.k.x8tDp6YrZ1EzDqmJe', 'Trần Thị B', '0922222222', 'Quận 3, TP.HCM', 'user'),
(4, 'user3@gmail.com', '$2y$12$LQv3c1yycn1FvTTLW4HKpuP9Tc1xJbEz/.k.x8tDp6YrZ1EzDqmJe', 'Lê Văn C', '0933333333', 'Quận 7, TP.HCM', 'user');

-- Pets
INSERT INTO pets (petID, userID, petName, species, breed, weight, backLength, age, petImage, medicalHistory) VALUES
(1, 2, 'Milo', 'Chó', 'Poodle', 4.50, 25.00, 2, 'milo.jpg', 'Dị ứng phấn hoa'),
(2, 2, 'Kitty', 'Mèo', 'Anh lông ngắn', 3.00, 22.00, 1, 'kitty.jpg', 'Đã triệt sản'),
(3, 3, 'Lu', 'Chó', 'Golden Retriever', 25.00, 50.00, 4, 'lu.jpg', 'Sức khỏe tốt'),
(4, 4, 'Vàng', 'Chó', 'Chó cỏ', 10.00, 35.00, 2, 'vang.jpg', NULL),
(5, 3, 'Max', 'Chó', 'Husky', 22.00, 55.00, 3, 'max.jpg', 'Tiêm phòng đầy đủ'),
(6, 2, 'Miu', 'Mèo', 'Munchkin', 2.50, 20.00, 1, 'miu.jpg', 'Chân ngắn, khỏe mạnh');

-- Appointments
INSERT INTO appointments (appointmentID, userID, petID, employeeID, appointmentDate, endDate, note, status, prefer_doctor) VALUES
(1, 2, 1, 1, '2026-01-14 10:00:00', NULL, 'Khám định kỳ', 'Pending', 1),
(2, 3, 3, 3, '2026-01-15 14:00:00', NULL, 'Cắt móng kỹ giúp em', 'Pending', 0),
(3, 2, 2, 2, '2026-01-16 09:30:00', NULL, 'Tiêm mũi nhắc lại', 'Pending', 0),
(4, 3, 5, 3, '2026-01-17 10:00:00', NULL, 'Spa thư giãn', 'Pending', 0),
(5, 4, 4, 4, '2026-01-18 08:00:00', '2026-01-20 18:00:00', 'Đi công tác 3 ngày', 'Pending', 0);

-- Appointment Services
INSERT INTO appointment_services (appointmentID, serviceID) VALUES
(1, 1), -- Appointment 1 - Khám sức khỏe
(2, 3), -- Appointment 2 - Spa tắm gội
(2, 4), -- Appointment 2 - Cắt tỉa lông
(3, 2), -- Appointment 3 - Tiêm vaccine
(4, 3), -- Appointment 4 - Spa tắm gội
(5, 5); -- Appointment 5 - Trông giữ

