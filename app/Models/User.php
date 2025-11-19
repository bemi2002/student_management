<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class User extends Authenticatable
{
    use Notifiable, HasRoles; // Important: Spatie roles

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // ----------------------------
    // TEACHER RELATIONSHIPS
    // ----------------------------

    /**
     * Get all enrollments for this teacher.
     */
    public function teacherEnrollments(): HasMany
    {
        return $this->hasMany(\App\Models\Enrollment::class, 'teacher_id');
    }

    /**
     * Get all courses this teacher teaches via enrollments.
     */
    public function specificCourses(): HasManyThrough
    {
        return $this->hasManyThrough(
            \App\Models\Course::class,       // Final model
            \App\Models\Enrollment::class,   // Intermediate table
            'teacher_id',                     // Foreign key on Enrollment table
            'id',                             // Foreign key on Course table
            'id',                             // Local key on User table
            'course_id'                       // Local key on Enrollment table
        )->distinct();
    }

    // ----------------------------
    // STUDENT RELATIONSHIPS
    // ----------------------------

    /**
     * Get all enrollments for this student.
     */
    public function studentEnrollments(): HasMany
    {
        return $this->hasMany(\App\Models\Enrollment::class, 'student_id');
    }
}
