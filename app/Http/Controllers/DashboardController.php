<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Course;
use App\Models\Enrollmentss;
use App\Models\Batch;
use App\Models\Resource;
use App\Models\SpecificCourse;
use App\Models\TeacherEnrollment;
use App\Models\StudentEnrollment;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $counts = [
            'students' => Student::count(),
            'teachers' => Teacher::count(),
            'courses' => Course::count(),
            'enrollments' => Enrollmentss::count(),
           
        ];

        return Inertia::render('Dashboard', compact('counts'));
    }
}
