<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherEnrollment extends Model
{
    use HasFactory;

    protected $table = 'teacher_enrollment';

    protected $fillable = [
        'teacher_id',
        'courses_id',
        'specific_courses_id',
        'enrollment_id',
    ];

    // Relationships
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
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
