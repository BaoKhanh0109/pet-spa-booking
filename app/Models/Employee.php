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
        'avatar',
        'roleID',
        'phoneNumber',
        'email',
        'info',
    ];

    public function role()
    {
        return $this->belongsTo(EmployeeRole::class, 'roleID', 'roleID');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'employee_service', 'employeeID', 'serviceID');
    }
    
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'employeeID', 'employeeID');
    }

    public function workSchedules()
    {
        return $this->hasMany(WorkSchedule::class, 'employeeID', 'employeeID');
    }
}