<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'student_id',
        'batches_id',
        'course_id',
        'resources',
        'specific_courses_id',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batches_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function resource()
    {
        return $this->belongsTo(Resource::class, 'resources'); // ✅ matches DB column
    }

    public function specificCourse()
    {
        return $this->belongsTo(SpecificCourse::class, 'specific_courses_id');
    }
}
