-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS accord_db;

-- Create user if it doesn't exist and grant privileges
CREATE USER IF NOT EXISTS 'accord_user'@'localhost' IDENTIFIED BY 'accord_password';
GRANT ALL PRIVILEGES ON accord_db.* TO 'accord_user'@'localhost';
FLUSH PRIVILEGES;

-- Switch to the accord_db database
USE accord_db;

-- Create products table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    artist_name VARCHAR(255) NOT NULL,
    album_title VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    release_date DATETIME NOT NULL,
    image_path VARCHAR(255),
    format VARCHAR(50),
    genre VARCHAR(100),
    condition_status VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample product data
INSERT INTO products (artist_name, album_title, description, price, release_date, image_path, format, genre, condition_status)
VALUES (
    'Van Halen',
    'Live in Dallas 1991',
    'Experience the raw energy and virtuosity of Van Halen in this exclusive live recording from their 1991 Dallas performance. This rare gem captures the band at their peak, featuring Eddie Van Halens legendary guitar work and David Lee Roths charismatic showmanship. The recording includes all their classic hits performed with unmatched intensity and precision.',
    139.95,
    '2025-03-28 08:00:00',
    'images/van-halen-dallas-1991.jpg',
    'Vinyl',
    'Rock',
    'Mint'
);

-- Create users table (for future use)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create orders table (for future use)
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    total_amount DECIMAL(10, 2) NOT NULL,
    status VARCHAR(50) DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Create order_items table (for future use)
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
); 