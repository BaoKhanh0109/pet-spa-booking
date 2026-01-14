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

    // Lịch hẹn được làm bởi 1 Nhân viên (Employee) - Tùy chọn
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employeeID', 'employeeID');
    }

    // Lịch hẹn có nhiều dịch vụ cụ thể (thông qua appointment_services)
    public function services()
    {
        return $this->belongsToMany(Service::class, 'appointment_services', 'appointmentID', 'serviceID')
            ->withPivot('appointment_servicesId');
    }

    // Helper method để lấy tất cả categories từ services
    public function getServiceCategories()
    {
        return $this->services()->with('category')->get()->pluck('category')->unique('categoryID');
    }

    // Accessor để lấy service category từ service đầu tiên
    public function getServiceCategoryAttribute()
    {
        $firstService = $this->services()->first();
        return $firstService ? $firstService->category : null;
    }

    // Accessor để lấy service category ID từ service đầu tiên
    public function getServiceCategoryIdAttribute()
    {
        $firstService = $this->services()->first();
        return $firstService ? $firstService->categoryID : null;
    }
}