<?php

namespace App\Helpers;

class PricingHelper
{
    public static function getPetSize($weight, $backLength = null)
    {
        if ($backLength !== null && $backLength > 0) {
            if ($backLength <= 20) {
                return 'XS';
            } elseif ($backLength <= 30) {
                return 'S';
            } elseif ($backLength <= 40) {
                return 'M';
            } elseif ($backLength <= 50) {
                return 'L';
            } elseif ($backLength <= 60) {
                return 'XL';
            } else {
                return 'XXL';
            }
        }
        
        if ($weight <= 0) {
            return 'S';
        }
        
        if ($weight <= 3) {
            return 'XS';
        } elseif ($weight <= 10) {
            return 'S';
        } elseif ($weight <= 20) {
            return 'M';
        } elseif ($weight <= 30) {
            return 'L';
        } elseif ($weight <= 40) {
            return 'XL';
        } else {
            return 'XXL';
        }
    }

    public static function calculatePriceBySize($basePrice, $size)
    {
        $multipliers = [
            'XS'  => 0.8,
            'S'   => 1.0,
            'M'   => 1.2,
            'L'   => 1.4,
            'XL'  => 1.6,
            'XXL' => 1.8,
        ];

        $multiplier = $multipliers[$size] ?? 1.0;
        return round($basePrice * $multiplier, -3);
    }

    public static function getAllSizePrices($basePrice)
    {
        return [
            'XS'  => self::calculatePriceBySize($basePrice, 'XS'),
            'S'   => self::calculatePriceBySize($basePrice, 'S'),
            'M'   => self::calculatePriceBySize($basePrice, 'M'),
            'L'   => self::calculatePriceBySize($basePrice, 'L'),
            'XL'  => self::calculatePriceBySize($basePrice, 'XL'),
            'XXL' => self::calculatePriceBySize($basePrice, 'XXL'),
        ];
    }

    /**
     * Lấy tên hiển thị của size
     * 
     * @param string $size
     * @return string
     */
    public static function getSizeLabel($size)
    {
        $labels = [
            'XS'  => 'Rất nhỏ (≤ 3kg hoặc ≤ 20cm)',
            'S'   => 'Nhỏ (3-10kg hoặc 20-30cm)',
            'M'   => 'Trung bình (10-20kg hoặc 30-40cm)',
            'L'   => 'Lớn (20-30kg hoặc 40-50cm)',
            'XL'  => 'Rất lớn (30-40kg hoặc 50-60cm)',
            'XXL' => 'Khổng lồ (> 40kg hoặc > 60cm)',
        ];

        return $labels[$size] ?? 'Không xác định';
    }
}
