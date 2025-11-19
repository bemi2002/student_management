<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentEnrollment extends Model
{
    use HasFactory;

    protected $table = 'student_enrollment';

    protected $fillable = [
        'students_id',
        'courses_id',
        'specific_courses_id',
        'enrollment_id',
    ];

    // Relationships
    public function student()
    {
        return $this->belongsTo(Student::class, 'students_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'courses_id');
    }

    public function specificCourse()
    {
        return $this->belongsTo(SpecificCourse::class, 'specific_courses_id');
    }
}

