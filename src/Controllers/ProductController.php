<?php
namespace App\Controllers;

use App\Models\Product;
use App\Services\OpenAIService;
use App\Helpers\FormattingHelper;

class ProductController {
    private $productModel;
    private $openAIService;
    
    public function __construct() {
        $this->productModel = new Product();
        $this->openAIService = new OpenAIService();
    }
    
    public function showFeaturedProduct() {
        $product = $this->productModel->getFeaturedProduct();
        
        // Format price and date
        $product['formatted_price'] = FormattingHelper::formatDanishPrice($product['price']);
        $product['formatted_date'] = FormattingHelper::formatDanishDate($product['release_date']);
        
        // Render the template
        require_once __DIR__ . '/../../templates/product/featured.php';
    }
    
    public function showAllProducts() {
        $products = $this->productModel->getAllProducts();
        require_once __DIR__ . '/../../templates/product/list.php';
    }
    
    public function showProduct($id) {
        $product = $this->productModel->find($id);
        if (!$product) {
            header('HTTP/1.0 404 Not Found');
            require_once __DIR__ . '/../../templates/error/404.php';
            return;
        }
        require_once __DIR__ . '/../../templates/product/detail.php';
    }
} 