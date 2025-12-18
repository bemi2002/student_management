<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    // ==================== PERMISSION CHECK ====================
    private function authorizeAction(string $permission)
    {
        $user = Auth::user();
        if (!$user || !$user->can($permission)) {
            abort(403, 'Unauthorized access');
        }
    }

    // ==================== INDEX ====================
    public function index()
    {
        $this->authorizeAction('view Courses');

        $courses = Course::all();

        return Inertia::render('Courses/Index', [
            'courses' => $courses,
            'permissions' => Auth::user()->getAllPermissions()->pluck('name'), // For frontend buttons
        ]);
    }

    // ==================== STORE ====================
    public function store(Request $request)
    {
        $this->authorizeAction('create Courses');

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

    // ==================== UPDATE ====================
    public function update(Request $request, Course $course)
    {
        $this->authorizeAction('edit Courses');

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

    // ==================== DELETE ====================
    public function destroy(Course $course)
    {
        $this->authorizeAction('delete Courses');

        $course->delete();

        return redirect()->back()->with([
            'success' => 'Course deleted successfully!',
            'courses' => Course::all()
        ]);
    }
}
