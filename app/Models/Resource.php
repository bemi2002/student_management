<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;

    // app/Models/Resource.php
protected $fillable = [
    'courses_id',         // plural
    'specific_courses_id', // plural
    'text',
];


    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function specificCourse()
    {
        return $this->belongsTo(SpecificCourse::class);
    }
}


