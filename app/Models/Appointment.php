<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    // 1. Khai báo tên bảng (Bắt buộc nếu tên bảng không phải số nhiều của Model)
    protected $table = 'appointments';

    // 2. Khai báo khóa chính (QUAN TRỌNG: Vì bạn dùng appointmentID chứ không phải id)
    protected $primaryKey = 'appointmentID';

    // 3. Bật tự động cập nhật ngày giờ (để lưu thời điểm tạo/cập nhật)
    public $timestamps = true; 

    // 4. Các cột được phép lưu dữ liệu (Mass Assignment)
    protected $fillable = [
        'service_categories', // FK tới service_categories
        'userID',
        'petID',
        'employeeID', // Có thể null nếu chưa phân công nhân viên
        'appointmentDate',
        'endDate',
        'note',
        'status', // Pending, Confirmed, Completed, Cancelled
        'prefer_doctor'
    ];

    // ==========================================
    // KHAI BÁO MỐI QUAN HỆ (RELATIONSHIPS)
    // ==========================================

    // 1. Lịch hẹn thuộc về 1 Khách hàng (User)
    public function user()
    {
        // belongsTo(Model, 'khóa_ngoại_bảng_này', 'khóa_chính_bảng_kia')
        return $this->belongsTo(User::class, 'userID', 'userID');
    }

    // 2. Lịch hẹn thuộc về 1 Thú cưng (Pet)
    public function pet()
    {
        return $this->belongsTo(Pet::class, 'petID', 'petID');
    }

    // 3. Lịch hẹn thuộc về 1 Service Category
    public function serviceCategory()
    {
        return $this->belongsTo(ServiceCategory::class, 'service_categories', 'categoryID');
    }

    // 4. Lịch hẹn được làm bởi 1 Nhân viên (Employee) - Tùy chọn
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employeeID', 'employeeID');
    }

    // 5. Lịch hẹn có nhiều dịch vụ cụ thể (thông qua appointment_services)
    public function services()
    {
        return $this->belongsToMany(Service::class, 'appointment_services', 'appointmentID', 'serviceID');
    }
}