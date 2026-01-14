<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $table = 'appointments';
    protected $primaryKey = 'appointmentID';
    public $timestamps = true; 

    protected $fillable = [
        'service_categories',
        'userID',
        'petID',
        'employeeID',
        'appointmentDate',
        'endDate',
        'note',
        'status',
        'prefer_doctor'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'userID');
    }

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