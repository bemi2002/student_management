<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
       
        'first_name',
        'last_name',
        'email',
        'phone',
        'specialization',
       
        'availability_days',
        'preferred_start_time',
        'preferred_end_time',
    ];

    // ==================== ENROLLMENTS RELATION ====================
    public function enrollments()
    {
        return $this->belongsToMany(
            Enrollmentss::class,          // Related model
            'enrollmentss_teacher',       // Pivot table
            'teacher_id',                 // Foreign key on pivot table for this model
            'enrollmentss_id'             // Foreign key on pivot table for the other model
        )->withTimestamps();
    }
}
