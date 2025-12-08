<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarEvent extends Model
{
    protected $fillable = [
        'title',
        'content',
        'start',
        'end',
        'enrollmentss_id',
    ];

    // Relationship to Enrollmentss
    public function enrollmentss()
    {
        return $this->belongsTo(\App\Models\Enrollmentss::class, 'enrollmentss_id');
    }
}
