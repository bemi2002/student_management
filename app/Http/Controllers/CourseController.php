<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    // Define allowed actions per role
    private $permissions = [
        
        'Admin'       => ['view','create','edit','delete'],
        'Teacher'     => ['view','create','edit','delete'], // adjust as needed
    ];

    /**
     * Check if the logged-in user has permission for the given action
     */
    private function checkPermission(string $action): bool
    {
        $user = Auth::user();

        // Loop through roles and check if user has the role
        foreach ($this->permissions as $role => $allowedActions) {
            if ($user->hasRole($role)) {
                return in_array($action, $allowedActions);
            }
        }

        return false;
    }

    /**
     * DRY authorization helper
     */
    private function authorizeAction(string $action)
    {
        if (!$this->checkPermission($action)) {
            abort(403, 'Unauthorized access');
        }
    }

    public function index()
{
    $this->authorizeAction('view');

    $courses = Course::with('subCourses')->get()->map(function ($course) {
        // Map the camelCase relationship to snake_case for Vue
        $course->sub_courses = $course->subCourses;
        return $course;
    });

    return Inertia::render('Courses/Index', [
        'courses' => $courses
    ]);
}


    // Show create course form
    public function create()
    {
        $this->authorizeAction('create');

        return Inertia::render('Courses/Create');
    }

    // Store new course
    public function store(Request $request)
    {
        $this->authorizeAction('create');

        $validated = $request->validate([
          
            'course_name'    => 'required|string|max:255',
            'description'    => 'nullable|string',
            'duration_dates' => 'required|integer|min:1',
            
            'level'          => 'required|string|max:50',
            'start_date'     => 'required|date',
            'end_date'       => 'required|date',
        ]);

        Course::create($validated);

        return redirect()->route('courses.index')
                         ->with('success', 'Course created successfully!');
    }

    // Show edit course form
    public function edit(Course $course)
    {
        $this->authorizeAction('edit');

        return Inertia::render('Courses/Edit', [
            'course' => $course,
        ]);
    }

    // Update course
    public function update(Request $request, Course $course)
    {
        $this->authorizeAction('edit');

        $validated = $request->validate([
         
            'course_name'    => 'required|string|max:255',
            'description'    => 'nullable|string',
            'duration_dates' => 'required|integer|min:1',
        
            'level'          => 'required|string|max:50',
            'start_date'     => 'required|date',
            'end_date'     => 'required|date',
        ]);

        $course->update($validated);

        return redirect()->route('courses.index')
                         ->with('success', 'Course updated successfully!');
    }

    // Delete course
    public function destroy(Course $course)
    {
        $this->authorizeAction('delete');

        $course->delete();

        return redirect()->route('courses.index')
                         ->with('success', 'Course deleted successfully!');
    }
}


