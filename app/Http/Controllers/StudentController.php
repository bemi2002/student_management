<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Location; // <-- Import Location model
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    private $permissions = [
        'Super-Admin' => ['view','create','edit','delete','assign','unassign'],
        'Admin'       => ['view','create','edit','delete','assign','unassign'],
    ];

    private function checkPermission(string $action): bool
    {
        $user = Auth::user();
        foreach ($this->permissions as $role => $allowedActions) {
            if ($user->hasRole($role)) return in_array($action, $allowedActions);
        }
        return false;
    }

    private function authorizeAction(string $action)
    {
        if (!$this->checkPermission($action)) abort(403, 'Unauthorized access');
    }

    public function index()
    {
        $this->authorizeAction('view');
        $students = Student::with('location')->get(); // load location
        return Inertia::render('Students/Index', ['students' => $students]);
    }

    public function create()
    {
        $this->authorizeAction('create');

        $locations = Location::all(); // fetch locations
        return Inertia::render('Students/Create', ['locations' => $locations]);
    }

    public function store(Request $request)
    {
        $this->authorizeAction('create');

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'contact_phone' => 'nullable|string|max:20',
            'registration_date' => 'nullable|date',
            'interested_course' => 'nullable|string|max:255',
            'heard_about_us' => 'nullable|string|max:255',
            'status' => 'required|string',
            'termination_date' => 'nullable|date',
            'location_id' => 'required|exists:locations,id', // validate location
        ]);

        Student::create($validated);

        return redirect()->route('students.index')->with('success', 'Student created successfully.');
    }

    public function edit(Student $student)
    {
        $this->authorizeAction('edit');

        $locations = Location::all(); // fetch locations
        return Inertia::render('Students/Edit', [
            'student' => $student,
            'locations' => $locations
        ]);
    }

    public function update(Request $request, Student $student)
    {
        $this->authorizeAction('edit');

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $student->id,
            'contact_phone' => 'nullable|string|max:20',
            'registration_date' => 'nullable|date',
            'interested_course' => 'nullable|string|max:255',
            'heard_about_us' => 'nullable|string|max:255',
            'status' => 'required|string',
            'termination_date' => 'nullable|date',
            'location_id' => 'required|exists:locations,id', // validate location
        ]);

        $student->update($validated);

        return redirect()->route('students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        $this->authorizeAction('delete');
        $student->delete();

        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
    }

    public function show(Student $student)
    {
        $this->authorizeAction('view');

        $student->load([
            'enrollments.course',
            'rejectedEnrollments.enrollment.course',
            'location'
        ]);

        return Inertia::render('Students/Show', [
            'student' => $student,
            'enrollments' => $student->enrollments,
            'rejectedEnrollments' => $student->rejectedEnrollments,
        ]);
    }

    public function assignToEnrollment(Request $request, Student $student)
    {
        $this->authorizeAction('assign');

        $validated = $request->validate([
            'enrollment_id' => 'required|exists:enrollmentss,id',
        ]);

        $student->enrollments()->syncWithoutDetaching([$validated['enrollment_id']]);

        return back()->with('success', 'Student assigned successfully.');
    }

    public function unassignFromEnrollment(Request $request, Student $student)
    {
        $this->authorizeAction('unassign');

        $validated = $request->validate([
            'enrollment_id' => 'required|exists:enrollmentss,id',
        ]);

        $student->enrollments()->detach($validated['enrollment_id']);

        return back()->with('success', 'Student unassigned successfully.');
    }
}
