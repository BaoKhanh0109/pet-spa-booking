<?php

namespace App\Helpers;

class PricingHelper
{
    /**
     * Xác định size thú cưng dựa trên cân nặng VÀ chiều dài lưng
     * 
     * @param float $weight Cân nặng (kg)
     * @param float $backLength Chiều dài lưng (cm)
     * @return string 'XS', 'S', 'M', 'L', 'XL', hoặc 'XXL'
     */
    public static function getPetSize($weight, $backLength = null)
    {
        // Nếu có chiều dài lưng, ưu tiên dùng để xác định size chính xác hơn
        if ($backLength !== null && $backLength > 0) {
            if ($backLength <= 20) {
                return 'XS'; // Rất nhỏ: <= 20cm
            } elseif ($backLength <= 30) {
                return 'S';  // Nhỏ: 20-30cm
            } elseif ($backLength <= 40) {
                return 'M';  // Trung bình: 30-40cm
            } elseif ($backLength <= 50) {
                return 'L';  // Lớn: 40-50cm
            } elseif ($backLength <= 60) {
                return 'XL'; // Rất lớn: 50-60cm
            } else {
                return 'XXL'; // Khổng lồ: > 60cm
            }
        }
        
        // Nếu không có chiều dài lưng, dùng cân nặng
        if ($weight <= 0) {
            return 'S'; // Mặc định
        }
        
        if ($weight <= 3) {
            return 'XS'; // Rất nhỏ: <= 3kg (Chihuahua, Pomeranian)
        } elseif ($weight <= 10) {
            return 'S';  // Nhỏ: 3-10kg (Poodle, Shih Tzu)
        } elseif ($weight <= 20) {
            return 'M';  // Trung bình: 10-20kg (Corgi, Beagle)
        } elseif ($weight <= 30) {
            return 'L';  // Lớn: 20-30kg (Border Collie, Bulldog)
        } elseif ($weight <= 40) {
            return 'XL'; // Rất lớn: 30-40kg (Labrador, Golden Retriever)
        } else {
            return 'XXL'; // Khổng lồ: > 40kg (Tibetan Mastiff, Great Dane)
        }
    }

    /**
     * Tính giá dịch vụ theo size thú cưng
     * 
     * @param float $basePrice Giá cơ bản (size S)
     * @param string $size Size thú cưng ('XS', 'S', 'M', 'L', 'XL', 'XXL')
     * @return float Giá sau khi tính theo size
     */
    public static function calculatePriceBySize($basePrice, $size)
    {
        $multipliers = [
            'XS'  => 0.8,   // Size XS: -20% (nhỏ hơn chuẩn)
            'S'   => 1.0,   // Size S: giá gốc (100%)
            'M'   => 1.2,   // Size M: +20%
            'L'   => 1.4,   // Size L: +40%
            'XL'  => 1.6,   // Size XL: +60%
            'XXL' => 1.8,   // Size XXL: +80%
        ];

        $multiplier = $multipliers[$size] ?? 1.0;
        return round($basePrice * $multiplier, -3); // Làm tròn đến hàng nghìn
    }

    /**
     * Lấy thông tin giá cho tất cả các size
     * 
     * @param float $basePrice Giá cơ bản
     * @return array ['XS' => price, 'S' => price, ...]
     */
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
