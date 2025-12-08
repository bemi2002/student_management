<?php

namespace App\Http\Controllers;

use App\Models\CourseType;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class CourseTypeController extends Controller
{
    // Define allowed actions per role
    private $permissions = [
        'Admin' => ['view','create','edit','delete'],
        'Teacher' => ['view','create','edit','delete'],
    ];

    /**
     * Check if the logged-in user has permission for the given action
     */
    private function checkPermission(string $action): bool
    {
        $user = Auth::user();
        if (!$user) return false;

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
        $courseType = CourseType::all();

        return Inertia::render('Settings/Index', [
            'courseType' => $courseType
        ]);
    }

    public function store(Request $request)
    {
        $this->authorizeAction('create');

        $validated = $request->validate([
            'course_type' => 'required|string|in:morning,evening,weekend',
            'student_capacity' => 'required|integer|min:1',
        ]);

        CourseType::create($validated);

        return redirect()->back()->with([
            'success' => 'Course type created successfully!',
            'courseType' => CourseType::all()
        ]);
    }

    public function update(Request $request, CourseType $courseType)
    {
        $this->authorizeAction('edit');

        $validated = $request->validate([
            'course_type' => 'required|string|in:morning,evening,weekend',
            'student_capacity' => 'required|integer|min:1',
        ]);

        $courseType->update($validated);

        return redirect()->back()->with([
            'success' => 'Course type updated successfully!',
            'courseType' => CourseType::all()
        ]);
    }

    public function destroy(CourseType $courseType)
    {
        $this->authorizeAction('delete');

        $courseType->delete();

        return redirect()->back()->with([
            'success' => 'Course type deleted successfully!',
            'courseType' => CourseType::all()
        ]);
    }
}
