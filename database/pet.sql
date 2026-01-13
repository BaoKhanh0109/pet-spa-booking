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
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    categoryID INT NOT NULL,
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
    service_categories INT NOT NULL,
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
    FOREIGN KEY (employeeID) REFERENCES employees(employeeID),
    FOREIGN KEY (service_categories) REFERENCES service_categories(categoryID)
);


CREATE TABLE appointment_services (
    appointment_servicesId INT PRIMARY KEY AUTO_INCREMENT,
    appointmentID INT NOT NULL,
    serviceID INT NOT NULL,
    FOREIGN KEY (appointmentID) REFERENCES appointments(appointmentID) ON DELETE CASCADE,
    FOREIGN KEY (serviceID) REFERENCES services(serviceID) ON DELETE CASCADE
);


USE pet_care_db;

