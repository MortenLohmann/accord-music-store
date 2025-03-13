# Accord Music Store

A simple website for a music store with MySQL database integration.

## Setup Instructions

### Prerequisites
- PHP (7.4 or higher recommended)
- MySQL (5.7 or higher recommended)
- Web server (Apache, Nginx, etc.)

### Database Setup
1. Open MySQL command line or a tool like phpMyAdmin
2. Run the SQL commands in `setup_database.sql` to create the database and tables
3. This will create a database called `accord_music_store` with sample data

### Configuration
1. Edit the `db_connect.php` file and update the following variables:
   - `$servername` - Your MySQL server hostname (usually "localhost")
   - `$username` - Your MySQL username
   - `$password` - Your MySQL password
   - `$dbname` - The database name (default: "accord_music_store")

### Running the Website
1. Place all files in your web server's document root (or a subdirectory)
2. Make sure your web server and MySQL are running
3. Open `products.php` in your browser to see the product listing

## File Structure
- `index.html` - Static homepage (original)
- `products.php` - Dynamic product listing from database
- `product.php` - Individual product details page
- `db_connect.php` - Database connection settings
- `setup_database.sql` - SQL script to create database and tables
- `styles.css` - CSS styling for the website

## Notes
- This is a basic implementation using PHP and MySQL
- For a production website, consider adding:
  - User authentication
  - Admin panel
  - Security measures (prepared statements already used)
  - More sophisticated product filtering and search
  - Shopping cart functionality 