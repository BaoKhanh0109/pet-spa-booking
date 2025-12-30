<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';

    protected $primaryKey = 'employeeID';

    public $timestamps = false;

    protected $fillable = [
        'employeeName',
        'position',     // Ví dụ: Bác sĩ, Nhân viên spa, Tiếp tân
        'phoneNumber',
        'email',
    ];

    public function services()
    {
        return $this->belongsToMany(Service::class, 'employee_service', 'employeeID', 'serviceID');
    }
    
    public function appointments()
    {
        // hasMany(ModelCon, 'khóa_ngoại_ở_bảng_con', 'khóa_chính_ở_bảng_cha')
        return $this->hasMany(Appointment::class, 'employeeID', 'employeeID');
    }

    public function workSchedules()
    {
        return $this->hasMany(WorkSchedule::class, 'employeeID', 'employeeID');
    }
}