<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeRole extends Model
{
    use HasFactory;

    protected $table = 'employee_roles';
    protected $primaryKey = 'roleID';

    protected $fillable = [
        'roleName',
        'description',
    ];

    /**
     * Relationship with employees
     */
    public function employees()
    {
        return $this->hasMany(Employee::class, 'roleID', 'roleID');
    }
}
