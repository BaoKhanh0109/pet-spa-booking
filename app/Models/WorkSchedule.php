<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkSchedule extends Model
{
    use HasFactory;

    // 1. Khai báo tên bảng
    protected $table = 'work_schedules';

    // 2. Khai báo khóa chính
    protected $primaryKey = 'scheduleID';

    // 3. Tắt timestamp (Bảng này không có created_at/updated_at)
    public $timestamps = false;

    // 4. Các trường được phép lưu
    protected $fillable = [
        'employeeID',
        'dayOfWeek',   // Thứ 2, Thứ 3... hoặc Monday, Tuesday
        'startTime',   // Giờ bắt đầu ca (VD: 08:00:00)
        'endTime',     // Giờ kết thúc ca (VD: 17:00:00)
    ];

    // ==========================================
    // KHAI BÁO MỐI QUAN HỆ
    // ==========================================

    /**
     * Lịch làm việc này thuộc về 1 nhân viên cụ thể
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employeeID', 'employeeID');
    }
}