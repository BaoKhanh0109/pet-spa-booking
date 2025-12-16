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

    // 3. Tắt tự động cập nhật ngày giờ (Nếu bảng của bạn không có created_at, updated_at)
    public $timestamps = false; 

    // 4. Các cột được phép lưu dữ liệu (Mass Assignment)
    protected $fillable = [
        'userID',
        'petID',
        'employeeID', // Có thể null nếu chưa phân công nhân viên
        'serviceID',
        'appointmentDate',
        'note',
        'status', // Pending, Confirmed, Completed, Cancelled
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

    // 3. Lịch hẹn thuộc về 1 Dịch vụ (Service)
    public function service()
    {
        return $this->belongsTo(Service::class, 'serviceID', 'serviceID');
    }

    // 4. Lịch hẹn được làm bởi 1 Nhân viên (Employee) - Tùy chọn
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employeeID', 'employeeID');
    }
}