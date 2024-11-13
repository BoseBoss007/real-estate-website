CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone_number VARCHAR(20) NOT NULL
);

CREATE TABLE properties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    name VARCHAR(255) NOT NULL,
    location VARCHAR(255) NOT NULL,
    facilities TEXT,
    price DECIMAL(10, 2) NOT NULL,
    bhk INT NOT NULL,
    carpet_area DECIMAL(10, 2) NOT NULL,
    property_type ENUM('Flat', 'House', 'Shop') NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE property_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    property_id INT,
    image_data LONGBLOB,
    FOREIGN KEY (property_id) REFERENCES properties(id)
);


CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone_number VARCHAR(20) NOT NULL
);
