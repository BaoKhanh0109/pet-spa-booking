<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    // 1. Khai báo tên bảng (nếu không theo chuẩn số nhiều)
    protected $table = 'services';

    // 2. Khai báo khóa chính (QUAN TRỌNG vì bạn dùng serviceID)
    protected $primaryKey = 'serviceID';

    // 3. Các trường cho phép điền dữ liệu (Mass Assignment)
    protected $fillable = [
        'serviceName',
        'serviceImage',
        'description',
        'price',
        'category',
        'duration',
        'serviceImage'
    ];

    // 4. Nếu bạn không muốn dùng created_at/updated_at thì set false, 
    // nhưng khuyên nên giữ để theo dõi lịch sử.
    public $timestamps = false;
        public function employees()
        {
            return $this->belongsToMany(Employee::class, 'employee_service', 'serviceID', 'employeeID');
        }
    }
?>