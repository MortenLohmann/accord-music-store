<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// Error handling
error_reporting(E_ALL);
ini_set('display_errors', $_ENV['APP_DEBUG'] === 'true' ? 1 : 0);

// Simple router
$request = $_SERVER['REQUEST_URI'];
$path = parse_url($request, PHP_URL_PATH);

// Route handling
switch ($path) {
    case '/':
        $controller = new App\Controllers\ProductController();
        $controller->showFeaturedProduct();
        break;
        
    case '/products':
        $controller = new App\Controllers\ProductController();
        $controller->showAllProducts();
        break;
        
    case (preg_match('/^\/product\/(\d+)$/', $path, $matches) ? true : false):
        $controller = new App\Controllers\ProductController();
        $controller->showProduct($matches[1]);
        break;
        
    default:
        header('HTTP/1.0 404 Not Found');
        require __DIR__ . '/../templates/error/404.php';
        break;
} 