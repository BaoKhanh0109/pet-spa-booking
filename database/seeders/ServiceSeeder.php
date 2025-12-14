<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('services')->insert([
            [
                'serviceName' => 'Cắt tỉa lông cơ bản',
                'description' => 'Cắt tỉa tạo kiểu, chải lông rụng, vệ sinh tai.',
                'price' => 250000
            ],
            [
                'serviceName' => 'Tắm thú cưng (Dưới 5kg)',
                'description' => 'Tắm gội massage, sấy khô, xịt thơm.',
                'price' => 150000
            ],
            [
                'serviceName' => 'Khách sạn thú cưng (1 ngày)',
                'description' => 'Giữ thú cưng 24h, bao gồm 3 bữa ăn và dắt đi dạo.',
                'price' => 300000
            ],
            [
                'serviceName' => 'Combo Spa toàn diện',
                'description' => 'Tắm, cắt tỉa, lấy cao răng, cắt móng.',
                'price' => 500000
            ]
        ]);
    }
}
