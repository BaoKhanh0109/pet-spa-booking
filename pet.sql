drop DATABASE IF EXISTS pet_care_db;
create DATABASE pet_care_db;
use pet_care_db;

CREATE TABLE services (
    serviceID INT PRIMARY KEY AUTO_INCREMENT,
    serviceName VARCHAR(100) NOT NULL,
    serviceImg TEXT,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    category ENUM('beauty', 'medical', 'pet_care') NOT NULL DEFAULT 'beauty',
    duration INT NOT NULL DEFAULT 60 COMMENT 'Thời gian thực hiện (phút)',
    serviceImage VARCHAR(255)
);

CREATE TABLE employees (
    employeeID INT PRIMARY KEY AUTO_INCREMENT,
    employeeName VARCHAR(100) NOT NULL,
    avatar TEXT,
    role VARCHAR(50),
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
    serviceID INT,
    appointmentDate DATETIME,
    endDate DATETIME,
    note TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(50) DEFAULT 'Pending',
    booking_type ENUM('beauty', 'medical', 'pet_care') NOT NULL,
    prefer_doctor TINYINT(1) DEFAULT 0,
    FOREIGN KEY (userID) REFERENCES users(userID),
    FOREIGN KEY (petID) REFERENCES pets(petID),
    FOREIGN KEY (employeeID) REFERENCES employees(employeeID)
);
CREATE TABLE payments (
    paymentID INT PRIMARY KEY AUTO_INCREMENT,
    appointmentID INT NOT NULL UNIQUE,
    amount DECIMAL(10, 2) NOT NULL,
    paymentDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    paymentMethod VARCHAR(50),
    FOREIGN KEY (appointmentID) REFERENCES appointments(appointmentID) ON DELETE CASCADE,
    UNIQUE(appointmentID)
);
CREATE TABLE reviews (
    reviewID INT PRIMARY KEY AUTO_INCREMENT,
    paymentID INT NOT NULL,
    reviewDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (paymentID) REFERENCES payments(paymentID) ON DELETE CASCADE,
    UNIQUE(paymentID)
);

CREATE TABLE appointment_services (
    appointment_servicesId INT PRIMARY KEY AUTO_INCREMENT,
    appointmentID INT NOT NULL,
    serviceID INT NOT NULL,
    FOREIGN KEY (appointmentID) REFERENCES appointments(appointmentID) ON DELETE CASCADE,
    FOREIGN KEY (serviceID) REFERENCES services(serviceID) ON DELETE CASCADE
);

CREATE TABLE review_details (
    review_detailsId INT PRIMARY KEY AUTO_INCREMENT,
    reviewID INT NOT NULL,
    serviceID INT NOT NULL,
    rating INT CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    FOREIGN KEY (reviewID) REFERENCES reviews(reviewID) ON DELETE CASCADE,
    FOREIGN KEY (serviceID) REFERENCES services(serviceID) ON DELETE CASCADE
);

USE pet_care_db;

-- 1. Thêm Dịch vụ (Services)
INSERT INTO services (serviceName, description, price, category, duration) VALUES
('Khám sức khỏe tổng quát', 'Kiểm tra nhiệt độ, tim phổi, tai mũi họng', 200000, 'medical', 30),
('Tiêm vaccine 7 bệnh', 'Vaccine phòng các bệnh phổ biến cho chó', 250000, 'medical', 15),
('Spa - Tắm gội', 'Tắm, sấy khô, chải lông, cắt móng', 300000, 'beauty', 60),
('Cắt tỉa lông (Styling)', 'Cắt tỉa tạo kiểu thẩm mỹ', 450000, 'beauty', 90),
('Trông giữ thú cưng (Ngày)', 'Giữ thú cưng 24h bao gồm ăn uống', 150000, 'pet_care', 1440);

-- 2. Thêm Nhân viên (Employees)
INSERT INTO employees (employeeName, role, phoneNumber, email) VALUES
('Bs. Nguyễn Văn An', 'Bác sĩ thú y', '0901111111', 'bs.an@petcare.com'),
('Bs. Lê Thị Lan', 'Bác sĩ thú y', '0902222222', 'bs.lan@petcare.com'),
('Trần Văn Hùng', 'Chuyên viên Grooming', '0903333333', 'hung.groomer@petcare.com'),
('Phạm Thị Mai', 'Nhân viên chăm sóc', '0904444444', 'mai.care@petcare.com');

-- 3. Thêm Phân công dịch vụ cho nhân viên (employee_service) - MỚI
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

-- 4. Thêm Lịch làm việc (Work Schedules)
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

-- 5. Thêm Người dùng (Users)
-- Password mặc định là: 'password' (đã hash bcrypt chuẩn Laravel)
INSERT INTO users (email, password, name, phone, address, role) VALUES
('admin@petcare.com', '12345678', 'Quản Trị Hệ Thống', '0999000000', 'Hà Nội', 'admin'),
('nguyenvanA@gmail.com', '12345678', 'Nguyễn Văn A', '0988777666', '123 Xuân Thủy, Cầu Giấy', 'user'),
('tranthiB@gmail.com', '12345678', 'Trần Thị B', '0911223344', '456 Nguyễn Trãi, Thanh Xuân', 'user');

-- 6. Thêm Thú cưng (Pets)
INSERT INTO pets (userID, petName, species, breed, weight, age, medicalHistory, petImage) VALUES
(2, 'Milo', 'Chó', 'Poodle', 4.5, 2, 'Dị ứng phấn hoa', 'milo.jpg'),
(2, 'Kitty', 'Mèo', 'Anh lông ngắn', 3.0, 1, 'Đã triệt sản', 'kitty.jpg'),
(3, 'Lu', 'Chó', 'Golden', 25.0, 4, 'Sức khỏe tốt', 'lu.jpg');

-- 7. Thêm Lịch hẹn (Appointments)
INSERT INTO appointments (userID, petID, employeeID, serviceID, appointmentDate, endDate, note, status, booking_type, prefer_doctor) VALUES
-- Lịch 1: Medical - Khám bệnh (Completed)
(2, 1, 1, 1, '2025-12-20 09:00:00', NULL, 'Bé bỏ ăn 2 ngày', 'Completed', 'medical', 1),
-- Lịch 2: Beauty - Spa + Cắt tỉa (Completed) - không cần serviceID vì dùng appointment_services
(3, 3, 3, NULL, '2025-12-21 14:00:00', NULL, 'Cắt móng kỹ giúp em', 'Completed', 'beauty', NULL),
-- Lịch 3: Medical - Tiêm vaccine (Pending)
(2, 2, 2, 2, '2025-12-28 10:00:00', NULL, 'Tiêm mũi nhắc lại', 'Pending', 'medical', NULL),
-- Lịch 4: Pet Care - Trông giữ (Pending)
(2, 1, 4, 5, '2025-12-31 08:00:00', '2026-01-03 18:00:00', 'Đi du lịch Tết, nhờ chăm sóc', 'Pending', 'pet_care', NULL),
-- Lịch 5: Beauty - Spa đơn (Pending)
(3, 3, 3, NULL, '2025-12-30 15:00:00', NULL, 'Spa thư giãn cuối năm', 'Pending', 'beauty', NULL),
-- Lịch 6: Medical - Khám tổng quát (Completed)
(3, 3, 1, 1, '2025-12-18 10:30:00', NULL, 'Kiểm tra định kỳ', 'Completed', 'medical', 1);

-- 8. Thêm Chi tiết dịch vụ trong lịch hẹn (Appointment Services)
-- Chỉ beauty booking mới dùng bảng này
INSERT INTO appointment_services (appointmentID, serviceID) VALUES
(2, 3), -- Lịch 2 (beauty): Spa
(2, 4), -- Lịch 2 (beauty): Cắt tỉa
(5, 3); -- Lịch 5 (beauty): Spa

-- 9. Thêm Thanh toán (Payments)
-- Chỉ tạo thanh toán cho các lịch đã Completed (1, 2, 6)
INSERT INTO payments (appointmentID, amount, paymentMethod) VALUES
(1, 200000, 'Cash'),       -- Lịch 1: Khám bệnh
(2, 750000, 'Credit Card'), -- Lịch 2: Spa + Cắt tỉa (300k + 450k)
(6, 200000, 'Cash');       -- Lịch 6: Khám tổng quát

-- 10. Thêm Đánh giá (Reviews)
INSERT INTO reviews (paymentID, reviewDate) VALUES
(1, '2025-12-20 10:30:00'),
(2, '2025-12-21 16:00:00'),
(3, '2025-12-18 11:30:00');

-- 11. Thêm Chi tiết đánh giá (Review Details)
INSERT INTO review_details (reviewID, serviceID, rating, comment) VALUES
(1, 1, 5, 'Bác sĩ An rất nhẹ nhàng, khám kỹ.'),
(2, 3, 4, 'Sạch sẽ thơm tho nhưng làm hơi lâu.'),
(2, 4, 5, 'Cắt kiểu này rất đẹp, ưng ý.'),
(3, 1, 5, 'Kiểm tra rất chi tiết, yên tâm!');