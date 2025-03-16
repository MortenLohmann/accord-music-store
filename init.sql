CREATE DATABASE IF NOT EXISTS accord_db;
CREATE USER IF NOT EXISTS 'accord_user'@'localhost' IDENTIFIED BY 'accord_password';
GRANT ALL PRIVILEGES ON accord_db.* TO 'accord_user'@'localhost';
FLUSH PRIVILEGES; 