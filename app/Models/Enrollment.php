<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $table = 'enrollments';

    protected $fillable = [
        'student_id',
        'teacher_id',
        'course_id',
        'specific_course_id',
        'enrollment_date',
        'fee_status',
        'completion_status',
        'title',
        'description',
    ];

    // Relationships

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function specificCourse()
    {
        return $this->belongsTo(SpecificCourse::class);
    }
}
