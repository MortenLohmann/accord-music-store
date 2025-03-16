<?php
namespace App\Models;

use App\Config\Database;

class Product {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function find($id) {
        $sql = "SELECT * FROM products WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    
    public function getFeaturedProduct() {
        return [
            'album_title' => 'LIVE IN DALLAS 1991',
            'artist' => 'Van Halen',
            'format' => 'Rød Vinyl, Album',
            'media_count' => '1',
            'genre' => 'Heavy Metal',
            'release_date' => '2025-03-28',
            'price' => '139.95',
            'status' => 'Ny'
        ];
    }
    
    public function getAllProducts() {
        $sql = "SELECT * FROM products ORDER BY release_date DESC";
        $result = $this->db->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
} 