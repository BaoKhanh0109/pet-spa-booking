<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceCategory extends Model
{
    use HasFactory;

    protected $table = 'service_categories';
    protected $primaryKey = 'categoryID';
    public $timestamps = false;

    protected $fillable = [
        'categoryName',
        'description',
        'capacity'
    ];

    public function services()
    {
        return $this->hasMany(Service::class, 'categoryID', 'categoryID');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'service_categories', 'categoryID');
    }
}
