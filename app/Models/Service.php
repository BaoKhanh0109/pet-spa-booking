<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $table = 'services';
    protected $primaryKey = 'serviceID';
    public $timestamps = false;
    
    protected $fillable = [
        'serviceName',
        'description',
        'price',
        'categoryID',
        'duration',
        'serviceImage'
    ];
    
    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'categoryID', 'categoryID');
    }
    
    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_service', 'serviceID', 'employeeID');
    }

    public function appointments()
    {
        return $this->belongsToMany(Appointment::class, 'appointment_services', 'serviceID', 'appointmentID');
    }
}
