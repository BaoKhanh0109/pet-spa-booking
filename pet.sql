drop DATABASE IF EXISTS pet_care_db;
create DATABASE pet_care_db;
use pet_care_db;

CREATE TABLE services (
    serviceID INT PRIMARY KEY AUTO_INCREMENT,
    serviceName VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL
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
    dayOfWeek VARCHAR(20),
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
    petImage VARCHAR(255),
    medicalHistory TEXT,
    FOREIGN KEY (userID) REFERENCES users(userID) ON DELETE CASCADE
);
CREATE TABLE appointments (
    appointmentID INT PRIMARY KEY AUTO_INCREMENT,
    userID INT NOT NULL,
    petID INT NOT NULL,
    employeeID INT,
    serviceID INT NOT NULL,
    appointmentDate DATETIME,
    note TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(50) DEFAULT 'Pending',
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