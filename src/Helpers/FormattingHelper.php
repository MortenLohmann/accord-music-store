<?php
namespace App\Helpers;

class FormattingHelper {
    public static function formatDanishPrice($price) {
        return number_format((float)$price, 2, ',', '.') . ' kr';
    }
    
    public static function formatDanishDate($date) {
        $months = array(
            '01' => 'JANUAR', '02' => 'FEBRUAR', '03' => 'MARTS',
            '04' => 'APRIL', '05' => 'MAJ', '06' => 'JUNI',
            '07' => 'JULI', '08' => 'AUGUST', '09' => 'SEPTEMBER',
            '10' => 'OKTOBER', '11' => 'NOVEMBER', '12' => 'DECEMBER'
        );
        
        $date_parts = explode('-', $date);
        if (count($date_parts) === 3) {
            return $date_parts[2] . '. ' . $months[$date_parts[1]] . ' ' . $date_parts[0];
        }
        return $date;
    }
    
    public static function sanitizeOutput($text) {
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }
} 