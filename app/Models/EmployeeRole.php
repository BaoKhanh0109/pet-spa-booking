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
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get all active roles
     */
    public static function getActiveRoles()
    {
        return self::where('is_active', true)
            ->orderBy('roleName')
            ->get();
    }

    /**
     * Relationship with employees
     */
    public function employees()
    {
        return $this->hasMany(Employee::class, 'role', 'roleName');
    }
}
