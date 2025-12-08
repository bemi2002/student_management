<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Location;
use App\Models\EnrollmentDropout; // Import the dropout model
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
        $students = Student::with('location')->get();
        return Inertia::render('Students/Index', ['students' => $students]);
    }

    public function create()
    {
        $this->authorizeAction('create');

        $locations = Location::all();
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
            'termination_date' => 'nullable|date',
            'location_id' => 'required|exists:locations,id',
        ]);

        Student::create($validated);

        return redirect()->route('students.index')->with('success', 'Student created successfully.');
    }

    public function edit(Student $student)
    {
        $this->authorizeAction('edit');

        $locations = Location::all();
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
            'termination_date' => 'nullable|date',
            'location_id' => 'required|exists:locations,id',
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
            'location',
            'dropoutHistory.enrollment.course' // Load dropout history with enrollment and course
        ]);

        return Inertia::render('Students/Show', [
            'student' => $student,
            'enrollments' => $student->enrollments,
            'rejectedEnrollments' => $student->rejectedEnrollments,
            'dropout_history' => $student->dropoutHistory, // Pass dropout history to frontend
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
            'reason' => 'required|string|max:500', // Add reason for dropout
        ]);

        // Remove from enrollments
        $student->enrollments()->detach($validated['enrollment_id']);

        // Create dropout record
        EnrollmentDropout::create([
            'student_id' => $student->id,
            'enrollment_id' => $validated['enrollment_id'],
            'reason' => $validated['reason'],
        ]);

        return back()->with('success', 'Student unassigned and dropout recorded successfully.');
    }

    // New method to get available enrollments for assignment
    public function getAvailableEnrollments(Student $student)
    {
        $this->authorizeAction('assign');

        // Get enrollments that the student is not currently enrolled in
        $currentEnrollmentIds = $student->enrollments()->pluck('enrollmentss.id');
        
        $availableEnrollments = \App\Models\Enrollmentss::with('course')
            ->whereNotIn('id', $currentEnrollmentIds)
            ->get();

        return response()->json($availableEnrollments);
    }
}