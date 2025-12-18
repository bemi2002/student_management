<?php

namespace App\Http\Controllers;

use App\Models\CourseType;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class CourseTypeController extends Controller
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
        $this->authorizeAction('view Course Types');

        $courseTypes = CourseType::all();

        return Inertia::render('Settings/Index', [
            'courseTypes' => $courseTypes,
            'permissions' => Auth::user()->getAllPermissions()->pluck('name'), // For frontend buttons
        ]);
    }

    // ==================== STORE ====================
    public function store(Request $request)
    {
        $this->authorizeAction('create Course Types');

        $validated = $request->validate([
            'course_type' => 'required|string|in:morning,evening,weekend',
            'student_capacity' => 'required|integer|min:1',
        ]);

        CourseType::create($validated);

        return redirect()->back()->with([
            'success' => 'Course type created successfully!',
            'courseTypes' => CourseType::all()
        ]);
    }

    // ==================== UPDATE ====================
    public function update(Request $request, CourseType $courseType)
    {
        $this->authorizeAction('edit Course Types');

        $validated = $request->validate([
            'course_type' => 'required|string|in:morning,evening,weekend',
            'student_capacity' => 'required|integer|min:1',
        ]);

        $courseType->update($validated);

        return redirect()->back()->with([
            'success' => 'Course type updated successfully!',
            'courseTypes' => CourseType::all()
        ]);
    }

    // ==================== DELETE ====================
    public function destroy(CourseType $courseType)
    {
        $this->authorizeAction('delete Course Types');

        $courseType->delete();

        return redirect()->back()->with([
            'success' => 'Course type deleted successfully!',
            'courseTypes' => CourseType::all()
        ]);
    }
}