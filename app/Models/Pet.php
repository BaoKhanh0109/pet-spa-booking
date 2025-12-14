<?php

namespace App\Models; // Namespace phải là App\Models

use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    protected $table = 'pets';
    protected $primaryKey = 'petID';
    public $timestamps = false; // Tắt timestamps theo DB của bạn

    protected $fillable = [
        'customerID', 'petName', 'species', 'breed', 
        'weight', 'age', 'petImage', 'medicalHistory'
    ];
}