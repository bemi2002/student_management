<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnrollmentDropout extends Model
{
    protected $fillable = [
        'student_id',
        'enrollment_id',
        'reason'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function enrollment()
    {
        return $this->belongsTo(Enrollmentss::class);
    }
}