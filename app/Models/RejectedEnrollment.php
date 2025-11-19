<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RejectedEnrollment extends Model
{
    use HasFactory;

    protected $table = 'rejected_enrollments';

    protected $fillable = [
        'student_id',
        'enrollment_id',
        'reason',
        'rejected_by'
    ];

    // Relationship with student
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // Relationship with enrollment
    public function enrollment()
    {
        return $this->belongsTo(Enrollmentss::class, 'enrollment_id');
    }
}
