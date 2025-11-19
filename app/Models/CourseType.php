<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseType extends Model
{
    use HasFactory;

    protected $table = 'courses_type';

    protected $fillable = [
        'course_type',
        'student_capacity',
    ];
}

