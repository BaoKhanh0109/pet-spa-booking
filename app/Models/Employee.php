<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    // 1. Khai báo tên bảng (Mặc định Laravel là 'employees' nhưng khai báo cho chắc)
    protected $table = 'employees';

    // 2. Khai báo khóa chính (QUAN TRỌNG: Database của bạn dùng employeeID)
    protected $primaryKey = 'employeeID';

    // 3. Tắt timestamp (Vì bảng này không có cột created_at, updated_at)
    public $timestamps = false;

    // 4. Các trường được phép lưu dữ liệu
    protected $fillable = [
        'employeeName',
        'position',     // Ví dụ: Bác sĩ, Nhân viên spa, Tiếp tân
        'phoneNumber',
        'email',
    ];

    // ==========================================
    // KHAI BÁO MỐI QUAN HỆ (RELATIONSHIPS)
    // ==========================================

    /**
     * Một nhân viên sẽ thực hiện nhiều cuộc hẹn (Appointments)
     * Quan hệ: 1 - Nhiều (One to Many)
     */
    public function appointments()
    {
        // hasMany(ModelCon, 'khóa_ngoại_ở_bảng_con', 'khóa_chính_ở_bảng_cha')
        return $this->hasMany(Appointment::class, 'employeeID', 'employeeID');
    }

    /**
     * Một nhân viên có nhiều lịch làm việc (WorkSchedules)
     * Quan hệ: 1 - Nhiều
     */
    public function workSchedules()
    {
        return $this->hasMany(WorkSchedule::class, 'employeeID', 'employeeID');
    }
}