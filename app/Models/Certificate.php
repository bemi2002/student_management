<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    protected $fillable = [
        'student_id','course_id','teacher_id','status',
        'teacher_approved_at','teacher_approved_by',
        'admin_approved_at','admin_approved_by',
        'certificate_generated_at','certificate_path'
    ];

    public function student(): BelongsTo { return $this->belongsTo(User::class, 'student_id'); }
    public function teacher(): BelongsTo { return $this->belongsTo(User::class, 'teacher_id'); }
    public function course(): BelongsTo { return $this->belongsTo(Course::class); }
}

