<?php

namespace App\Http\Controllers;

use App\Models\TeacherEnrollment;
use App\Models\Teacher;
use App\Models\Course;
use App\Models\SpecificCourse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class TeacherEnrollmentController extends Controller
{
    private $permissions = [
        'Admin' => ['view', 'create', 'edit', 'delete'],
        'Teacher' => ['view', 'create', 'edit', 'delete'],
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

    // INDEX
    public function index()
    {
        $this->authorizeAction('view');

        $enrollments = TeacherEnrollment::with(['teacher', 'course', 'specificCourse'])->get();

        return Inertia::render('TeacherEnrollments/Index', [
            'enrollments' => $enrollments,
        ]);
    }

    // CREATE
    public function create()
    {
        $this->authorizeAction('create');

        return Inertia::render('TeacherEnrollments/Create', [
            'teachers' => Teacher::all(),
            'courses' => Course::all(),
            'specificCourses' => SpecificCourse::all(),
        ]);
    }

    // STORE
    public function store(Request $request)
    {
        $this->authorizeAction('create');

        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'courses_id' => 'required|exists:courses,id',
            'specific_courses_id' => 'required|exists:specific_courses,id',
            'enrollment_id' => 'required|string|max:255', // text input
        ]);

        TeacherEnrollment::create($validated);

        return redirect()->route('teacher-enrollments.index')
                         ->with('success', 'Teacher Enrollment created successfully!');
    }

    // EDIT
    public function edit(TeacherEnrollment $teacherEnrollment)
    {
        $this->authorizeAction('edit');

        return Inertia::render('TeacherEnrollments/Edit', [
            'enrollment' => $teacherEnrollment,
            'teachers' => Teacher::all(),
            'courses' => Course::all(),
            'specificCourses' => SpecificCourse::all(),
        ]);
    }

    // UPDATE
    public function update(Request $request, TeacherEnrollment $teacherEnrollment)
    {
        $this->authorizeAction('edit');

        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'courses_id' => 'required|exists:courses,id',
            'specific_courses_id' => 'required|exists:specific_courses,id',
            'enrollment_id' => 'required|string|max:255', // text input
        ]);

        $teacherEnrollment->update($validated);

        return redirect()->route('teacher-enrollments.index')
                         ->with('success', 'Teacher Enrollment updated successfully!');
    }

    // DELETE
    public function destroy(TeacherEnrollment $teacherEnrollment)
    {
        $this->authorizeAction('delete');

        $teacherEnrollment->delete();

        return redirect()->route('teacher-enrollments.index')
                         ->with('success', 'Teacher Enrollment deleted successfully!');
    }
}
