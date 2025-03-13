-- Create database
CREATE DATABASE IF NOT EXISTS accord_db;
USE accord_db;

-- Create products table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    artist VARCHAR(255) NOT NULL,
    album_title VARCHAR(255) NOT NULL,
    format VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image_url VARCHAR(255),
    release_date DATE,
    genre VARCHAR(100),
    media_count INT DEFAULT 1,
    description TEXT,
    artist_bio TEXT,
    status VARCHAR(50) DEFAULT 'In Stock',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample data
INSERT INTO products (artist, album_title, format, price, image_url, release_date, genre, media_count) VALUES
('Van Halen', 'Live in Dallas 1991', 'CD, Album', 139.95, 'van-halen-album.jpg', '2025-03-28', 'Heavy', 1),
('Pink Floyd', 'The Dark Side of the Moon', 'Vinyl, LP', 299.95, 'pink-floyd-dsotm.jpg', '2023-05-15', 'Rock', 1),
('Daft Punk', 'Random Access Memories', 'Vinyl, LP', 349.95, 'daft-punk-ram.jpg', '2023-08-10', 'Electronic', 2),
('Miles Davis', 'Kind of Blue', 'CD, Album', 129.95, 'miles-davis-kob.jpg', '2022-11-20', 'Jazz', 1),
('Metallica', 'Master of Puppets', 'Vinyl, LP', 279.95, 'metallica-mop.jpg', '2023-09-05', 'Metal', 1);

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