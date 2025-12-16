<?php

namespace App\Models; // Namespace phải là App\Models

use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    protected $table = 'pets';
    protected $primaryKey = 'petID';
    public $timestamps = false;

    protected $fillable = [
        'userID', 'petName', 'species', 'breed', 
        'weight', 'age', 'medicalHistory', 'petImage' 
    ];
    
    // Quan hệ với User (để kiểm tra chính chủ)
    public function user() {
        return $this->belongsTo(User::class, 'userID', 'userID');
    }
}