<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    protected $table = 'pets';
    protected $primaryKey = 'petID';
    public $timestamps = false;

    protected $fillable = [
        'userID', 'petName', 'species', 'breed', 
        'weight', 'backLength', 'age', 'medicalHistory', 'petImage' 
    ];
    
    public function user() {
        return $this->belongsTo(User::class, 'userID', 'userID');
    }

    public function appointments() {
        return $this->hasMany(Appointment::class, 'petID', 'petID');
    }
}