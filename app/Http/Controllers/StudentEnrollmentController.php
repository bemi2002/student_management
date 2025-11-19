<?php

namespace App\Http\Controllers;

use App\Models\StudentEnrollment;
use App\Models\Student;
use App\Models\Course;
use App\Models\SpecificCourse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class StudentEnrollmentController extends Controller
{
   
    private $permissions = [
        'Super-Admin' => ['view','create','edit','delete'], // optional
        'Admin'       => ['view','create','edit','delete'], 
    ];

   
    private function checkPermission(string $action): bool
    {
        $user = Auth::user();

        foreach ($this->permissions as $role => $allowedActions) {
            if ($user->hasRole($role)) {
                return in_array($action, $allowedActions);
            }
        }

        return false;
    }

    
    private function authorizeAction(string $action)
    {
        if (!$this->checkPermission($action)) {
            abort(403, 'Unauthorized access');
        }
    }

    
    public function index()
    {
        $this->authorizeAction('view');

        $enrollments = StudentEnrollment::with(['student', 'course', 'specificCourse'])->get();

        return Inertia::render('StudentEnrollments/Index', [
            'enrollments' => $enrollments,
        ]);
    }

   
    public function create()
    {
        $this->authorizeAction('create');

        return Inertia::render('StudentEnrollments/Create', [
            'students' => Student::all(),
            'courses' => Course::all(),
            'specificCourses' => SpecificCourse::all(),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorizeAction('create');

        $validated = $request->validate([
            'students_id' => 'required|exists:students,id',
            'courses_id' => 'required|exists:courses,id',
            'specific_courses_id' => 'required|exists:specific_courses,id',
            'enrollment_id' => 'required|integer',
        ]);

        StudentEnrollment::create($validated);

        return redirect()->route('student-enrollments.index')
                         ->with('success', 'Enrollment created successfully!');
    }

    
    public function edit(StudentEnrollment $studentEnrollment)
    {
        $this->authorizeAction('edit');

        return Inertia::render('StudentEnrollments/Edit', [
            'enrollment' => $studentEnrollment,
            'students' => Student::all(),
            'courses' => Course::all(),
            'specificCourses' => SpecificCourse::all(),
        ]);
    }

   
    public function update(Request $request, StudentEnrollment $studentEnrollment)
    {
        $this->authorizeAction('edit');

        $validated = $request->validate([
            'students_id' => 'required|exists:students,id',
            'courses_id' => 'required|exists:courses,id',
            'specific_courses_id' => 'required|exists:specific_courses,id',
            'enrollment_id' => 'required|integer',
        ]);

        $studentEnrollment->update($validated);

        return redirect()->route('student-enrollments.index')
                         ->with('success', 'Enrollment updated successfully!');
    }

    public function destroy(StudentEnrollment $studentEnrollment)
    {
        $this->authorizeAction('delete');

        $studentEnrollment->delete();

        return redirect()->route('student-enrollments.index')
                         ->with('success', 'Enrollment deleted successfully!');
    }
}


