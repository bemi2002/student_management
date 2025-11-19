<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Course;
use App\Models\SpecificCourse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    private $permissions = [
        'Admin'   => ['view', 'create', 'edit', 'delete'],
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

    // List all enrollments
    public function index()
    {
        $this->authorizeAction('view');

        $enrollments = Enrollment::with(['student', 'teacher', 'course', 'specificCourse'])->get();

        return Inertia::render('Enrollments/Index', [
            'enrollments' => $enrollments,
        ]);
    }

    // Show create form
    public function create()
    {
        $this->authorizeAction('create');

        return Inertia::render('Enrollments/Create', [
            'students' => Student::all(),
            'teachers' => Teacher::all(),
            'courses' => Course::all(),
            'specificCourses' => SpecificCourse::all(),
        ]);
    }

    // Store new enrollment
    public function store(Request $request)
    {
        $this->authorizeAction('create');

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'teacher_id' => 'required|exists:teachers,id',
            'course_id' => 'required|exists:courses,id',
            'specific_course_id' => 'required|exists:specific_courses,id',
            'enrollment_date' => 'required|date',
            'fee_status' => 'required|in:half,full',
            'completion_status' => 'required|in:started,ongoing,completed',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Enrollment::create($validated);

        return redirect()->route('enrollments.index')->with('success', 'Enrollment created successfully!');
    }

    // Show edit form
    public function edit(Enrollment $enrollment)
    {
        $this->authorizeAction('edit');

        return Inertia::render('Enrollments/Edit', [
            'enrollment' => $enrollment,
            'students' => Student::all(),
            'teachers' => Teacher::all(),
            'courses' => Course::all(),
            'specificCourses' => SpecificCourse::all(),
        ]);
    }

    // Update enrollment
    public function update(Request $request, Enrollment $enrollment)
    {
        $this->authorizeAction('edit');

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'teacher_id' => 'required|exists:teachers,id',
            'course_id' => 'required|exists:courses,id',
            'specific_course_id' => 'required|exists:specific_courses,id',
            'enrollment_date' => 'required|date',
            'fee_status' => 'required|in:half,full',
            'completion_status' => 'required|in:started,ongoing,completed',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $enrollment->update($validated);

        return redirect()->route('enrollments.index')->with('success', 'Enrollment updated successfully!');
    }

    // Delete enrollment
    public function destroy(Enrollment $enrollment)
    {
        $this->authorizeAction('delete');

        $enrollment->delete();

        return redirect()->route('enrollments.index')->with('success', 'Enrollment deleted successfully!');
    }
}
