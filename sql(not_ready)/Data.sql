create table users(
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(50) NOT NULL,
    phone_number VARCHAR(50) UNIQUE,
    profile_pic VARCHAR(255),
    is_verified BOOLEAN DEFAULT FALSE,
    role ENUM('admin', 'user') DEFAULT 'user',
    address VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON CURRENT_TIMESTAMP
);