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
        'Teacher'     => ['view','create','edit','delete'],
    ];

    /**
     * Check if the logged-in user has permission for the given action
     */
    private function checkPermission(string $action): bool
    {
        $user = Auth::user();
        if (!$user) return false;

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

        $courses = Course::all();

        return Inertia::render('Settings/Index', [
            'courses' => $courses
        ]);
    }

    // Store new course
    public function store(Request $request)
    {
        $this->authorizeAction('create');

        $validated = $request->validate([
            'course_name'    => 'required|string|max:255',
            'description'    => 'nullable|string',
            'duration_dates' => 'required|integer|min:1',
            'level'          => 'required|string|in:beginner,intermediate,advanced',
            'start_date'     => 'required|date',
            'end_date'       => 'required|date|after_or_equal:start_date',
        ]);

        Course::create($validated);

        return redirect()->back()->with([
            'success' => 'Course created successfully!',
            'courses' => Course::all()
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
            'level'          => 'required|string|in:beginner,intermediate,advanced',
            'start_date'     => 'required|date',
            'end_date'       => 'required|date|after_or_equal:start_date',
        ]);

        $course->update($validated);

        return redirect()->back()->with([
            'success' => 'Course updated successfully!',
            'courses' => Course::all()
        ]);
    }

    // Delete course
    public function destroy(Course $course)
    {
        $this->authorizeAction('delete');

        $course->delete();

        return redirect()->back()->with([
            'success' => 'Course deleted successfully!',
            'courses' => Course::all()
        ]);
    }
}