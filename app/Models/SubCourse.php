<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCourse extends Model
{
    use HasFactory;

    protected $fillable = [
    'course_id',
    'name',
    'resource_type',
    'resource_path'
];


    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
