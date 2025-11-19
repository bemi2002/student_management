<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Enrollmentss;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    private $permissions = [
        'Admin'   => ['view', 'create', 'edit', 'delete', 'assign', 'unassign'],
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

    // ==================== INDEX ====================
    public function index()
    {
        $this->authorizeAction('view');

        $teachers = Teacher::all();

        return Inertia::render('Teachers/Index', [
            'teachers' => $teachers
        ]);
    }

    // ==================== SHOW ====================
    public function show(Teacher $teacher)
{
    $this->authorizeAction('view');

    $availableEnrollments = Enrollmentss::whereDoesntHave('teachers', function($q) use ($teacher){
        $q->where('teacher_id', $teacher->id);
    })->get();

    return Inertia::render('Teachers/Show', [
        'teacher' => $teacher->load('enrollments.course'), // eager load enrollments + course
        'availableEnrollments' => $availableEnrollments,
    ]);
}


    // ==================== CREATE / STORE ====================
    public function create()
    {
        $this->authorizeAction('create');
        return Inertia::render('Teachers/Create');
    }

    public function store(Request $request)
    {
        $this->authorizeAction('create');

        $validated = $request->validate([
            
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email',
            'phone' => 'nullable|string|max:255',
            'specialization' => 'required|string',
            
            'availability_days' => 'nullable|string',
            'preferred_start_time' => 'nullable',
            'preferred_end_time' => 'nullable',
        ]);

        Teacher::create($validated);

        return redirect()->route('teachers.index')
                         ->with('success', 'Teacher created successfully!');
    }

    // ==================== EDIT / UPDATE ====================
    public function edit(Teacher $teacher)
    {
        $this->authorizeAction('edit');
        return Inertia::render('Teachers/Edit', [
            'teacher' => $teacher
        ]);
    }

    public function update(Request $request, Teacher $teacher)
    {
        $this->authorizeAction('edit');

        $validated = $request->validate([
            
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email,' . $teacher->id,
            'phone' => 'nullable|string|max:255',
            'specialization' => 'required|string',
            'availability_days' => 'nullable|string',
            'preferred_start_time' => 'nullable',
            'preferred_end_time' => 'nullable',
        ]);

        $teacher->update($validated);

        return redirect()->route('teachers.index')
                         ->with('success', 'Teacher updated successfully!');
    }

    // ==================== DELETE ====================
    public function destroy(Teacher $teacher)
    {
        $this->authorizeAction('delete');

        $teacher->enrollments()->detach();
        $teacher->delete();

        return redirect()->route('teachers.index')
                         ->with('success', 'Teacher deleted successfully!');
    }

    // ==================== ASSIGN / UNASSIGN ENROLLMENTS ====================
    public function assignEnrollment(Request $request, Teacher $teacher)
    {
        $this->authorizeAction('assign');

        $validated = $request->validate([
            'enrollment_id' => 'required|exists:enrollmentss,id',
        ]);

        $teacher->enrollments()->syncWithoutDetaching([$validated['enrollment_id']]);

        return redirect()->route('teachers.show', $teacher->id)
                         ->with('success', 'Enrollment assigned successfully!');
    }

    public function unassignEnrollment(Request $request, Teacher $teacher)
    {
        $this->authorizeAction('unassign');

        $validated = $request->validate([
            'enrollment_id' => 'required|exists:enrollmentss,id',
        ]);

        $teacher->enrollments()->detach($validated['enrollment_id']);

        return redirect()->route('teachers.show', $teacher->id)
                         ->with('success', 'Enrollment unassigned successfully!');
    }
}
