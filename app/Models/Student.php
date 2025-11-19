<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
    'full_name',
    'email',
    'contact_phone',
    'registration_date',
    'interested_course',
    'heard_about_us',
    'status',
    'termination_date',
    'location_id'
];

    

    // Relationship with enrollments
    public function enrollments(): BelongsToMany
    {
        return $this->belongsToMany(
            Enrollmentss::class,
            'enrollmentss_student', // pivot table
            'student_id',           // FK for Student in pivot
            'enrollmentss_id'       // FK for Enrollmentss in pivot
        )->withTimestamps();
    }

    // Relationship with rejected enrollments
    public function rejectedEnrollments(): HasMany
    {
        return $this->hasMany(RejectedEnrollment::class, 'student_id');
    }

    // Relationship with location
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    // Scope: Students not assigned to a specific enrollment
    public function scopeNotAssignedToEnrollment($query, $enrollmentId)
    {
        return $query->whereDoesntHave('enrollments', function ($q) use ($enrollmentId) {
            $q->where('enrollmentss.id', $enrollmentId);
        });
    }

    // Scope: Students not in rejected enrollments
    public function scopeNotInRejectedEnrollments($query, $enrollmentId)
    {
        return $query->whereDoesntHave('rejectedEnrollments', function ($q) use ($enrollmentId) {
            $q->where('enrollmentss_id', $enrollmentId);
        });
    }

    public function enrollmentss()
{
    return $this->belongsToMany(Enrollmentss::class, 'enrollmentss_student', 'student_id', 'enrollmentss_id')
                ->withTimestamps();
}
}
