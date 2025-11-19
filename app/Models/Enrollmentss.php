<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Enrollmentss extends Model
{
    protected $table = 'enrollmentss';

    protected $fillable = [
        'title',
        'description',
        'course_id',
        'course_type_id',
        'company_address_id',
        'student_capacity',
        'amount_to_be_paid',
        'completion_status',
        'telegram_link',
        'enrollment',
        'enrollment_date',
    ];

    protected $casts = [
        'enrollment_date' => 'date',
    ];

    // ---------------- RELATIONSHIPS ----------------

    
   
    public function students(): BelongsToMany
{
    return $this->belongsToMany(
        Student::class,
        'enrollmentss_student',
        'enrollmentss_id', // this must match pivot table column
        'student_id'
    )->withTimestamps();
}


    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function courseType(): BelongsTo
    {
        return $this->belongsTo(CourseType::class, 'course_type_id');
    }

    public function companyAddress(): BelongsTo
    {
        return $this->belongsTo(CompanyAddress::class, 'company_address_id');
    }


}