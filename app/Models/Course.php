<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
      
        'course_name',
        'description',
        'duration_dates',
        'price',
        'level',
        'start_date',
        'end_date',
    ];
    // app/Models/Course.php
public function subCourses()
{
    return $this->hasMany(SubCourse::class);
}

}
