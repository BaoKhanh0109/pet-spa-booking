drop DATABASE IF EXISTS pet_care_db;
create DATABASE pet_care_db;
use pet_care_db;

CREATE TABLE services (
    serviceID INT PRIMARY KEY AUTO_INCREMENT,
    serviceName VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL -- Định dạng tiền tệ
);
CREATE TABLE employees (
    employeeID INT PRIMARY KEY AUTO_INCREMENT,
    employeeName VARCHAR(100) NOT NULL,
    position VARCHAR(50),
    phoneNumber VARCHAR(20),
    email VARCHAR(100)
);
CREATE TABLE work_schedules (
    scheduleID INT PRIMARY KEY AUTO_INCREMENT,
    employeeID INT NOT NULL,
    dayOfWeek VARCHAR(20), -- Ví dụ: 'Monday', 'Tuesday'
    startTime TIME,
    endTime TIME,
    isAvailable BOOLEAN DEFAULT 1,
    FOREIGN KEY (employeeID) REFERENCES employees(employeeID) ON DELETE CASCADE
);
CREATE TABLE users (
    userID INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    contactNumber VARCHAR(20),
    address TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
CREATE TABLE pets (
    petID INT PRIMARY KEY AUTO_INCREMENT,
    userID INT NOT NULL,
    petName VARCHAR(50) NOT NULL,
    species VARCHAR(50), -- Chó, Mèo...
    breed VARCHAR(50),   -- Giống
    weight DECIMAL(5, 2),
    age INT,
    petImage VARCHAR(255), -- Lưu đường dẫn ảnh
    medicalHistory TEXT,
    FOREIGN KEY (userID) REFERENCES users(userID) ON DELETE CASCADE
);
CREATE TABLE appointments (
    appointmentID INT PRIMARY KEY AUTO_INCREMENT,
    userID INT NOT NULL,
    petID INT NOT NULL,
    employeeID INT,
    serviceID INT NOT NULL,
    appointmentDate DATETIME, -- Thời gian hẹn (quan trọng dù hình không vẽ)
    note TEXT,
    status VARCHAR(50) DEFAULT 'Pending', -- Pending, Confirmed, Completed, Cancelled
    FOREIGN KEY (userID) REFERENCES users(userID),
    FOREIGN KEY (petID) REFERENCES pets(petID),
    FOREIGN KEY (employeeID) REFERENCES employees(employeeID),
    FOREIGN KEY (serviceID) REFERENCES services(serviceID)
);
CREATE TABLE payments (
    paymentID INT PRIMARY KEY AUTO_INCREMENT,
    appointmentID INT NOT NULL UNIQUE, -- Quan hệ 1-1
    amount DECIMAL(10, 2) NOT NULL,
    paymentDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    paymentMethod VARCHAR(50), -- Cash, Credit Card, Momo...
    FOREIGN KEY (appointmentID) REFERENCES appointments(appointmentID) ON DELETE CASCADE
);
CREATE TABLE reviews (
    reviewID INT PRIMARY KEY AUTO_INCREMENT,
    appointmentID INT NOT NULL,
    rating INT CHECK (rating >= 1 AND rating <= 5), -- Giới hạn 1-5 sao
    comment TEXT,
    reviewDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (appointmentID) REFERENCES appointments(appointmentID) ON DELETE CASCADE
);