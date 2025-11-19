<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecificCourse extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'teacher_id',
        
        'batch_id',
        'specific_courses_code',
        'courses_name',
        'description',
        
    
     
   
        'difficulty_level',
        'resources',
    ];

    

    // Relationships
    public function course() {
        return $this->belongsTo(Course::class);
    }

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }

    public function location() {
        return $this->belongsTo(Location::class);
    }

    public function batch() {
        return $this->belongsTo(Batch::class);
    }
}


