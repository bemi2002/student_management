<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Location;
use App\Models\EnrollmentDropout;
use App\Models\Enrollmentss;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    // ==================== PERMISSION CHECK ====================
    private function authorizeAction(string $permission)
    {
        $user = Auth::user();
        if (!$user->can($permission)) {
            abort(403, 'Unauthorized access');
        }
    }

    // ==================== INDEX ====================
    public function index()
    {
        $this->authorizeAction('view Students');

        $students = Student::with('location')->get();

        return Inertia::render('Students/Index', [
            'students' => $students,
            'permissions' => Auth::user()->getAllPermissions()->pluck('name'), // For frontend
        ]);
    }

    // ==================== CREATE ====================
    public function create()
    {
        $this->authorizeAction('create Students');

        $locations = Location::all();
        return Inertia::render('Students/Create', ['locations' => $locations]);
    }

    // ==================== STORE ====================
    public function store(Request $request)
    {
        $this->authorizeAction('create Students');

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

        return redirect()->route('students.index')
            ->with('success', 'Student created successfully.');
    }

    // ==================== EDIT ====================
    public function edit(Student $student)
    {
        $this->authorizeAction('edit Students');

        $locations = Location::all();
        return Inertia::render('Students/Edit', [
            'student' => $student,
            'locations' => $locations
        ]);
    }

    // ==================== UPDATE ====================
    public function update(Request $request, Student $student)
    {
        $this->authorizeAction('edit Students');

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

        return redirect()->route('students.index')
            ->with('success', 'Student updated successfully.');
    }

    // ==================== DELETE ====================
    public function destroy(Student $student)
    {
        $this->authorizeAction('delete Students');

        $student->delete();

        return redirect()->route('students.index')
            ->with('success', 'Student deleted successfully.');
    }

    // ==================== SHOW ====================
    public function show(Student $student)
    {
        $this->authorizeAction('view Students');

        $student->load([
            'enrollments.course',
            'rejectedEnrollments.enrollment.course',
            'location',
            'dropoutHistory.enrollment.course'
        ]);

        return Inertia::render('Students/Show', [
            'student' => $student,
            'enrollments' => $student->enrollments,
            'rejectedEnrollments' => $student->rejectedEnrollments,
            'dropout_history' => $student->dropoutHistory,
            'permissions' => Auth::user()->getAllPermissions()->pluck('name'),
        ]);
    }

    // ==================== ASSIGN TO ENROLLMENT ====================
    public function assignToEnrollment(Request $request, Student $student)
    {
        $this->authorizeAction('assign');

        $validated = $request->validate([
            'enrollment_id' => 'required|exists:enrollmentss,id',
        ]);

        $student->enrollments()->syncWithoutDetaching([$validated['enrollment_id']]);

        return back()->with('success', 'Student assigned successfully.');
    }

    // ==================== UNASSIGN FROM ENROLLMENT ====================
    public function unassignFromEnrollment(Request $request, Student $student)
    {
        $this->authorizeAction('unassign');

        $validated = $request->validate([
            'enrollment_id' => 'required|exists:enrollmentss,id',
            'reason' => 'required|string|max:500',
        ]);

        $student->enrollments()->detach($validated['enrollment_id']);

        EnrollmentDropout::create([
            'student_id' => $student->id,
            'enrollment_id' => $validated['enrollment_id'],
            'reason' => $validated['reason'],
        ]);

        return back()->with('success', 'Student unassigned and dropout recorded successfully.');
    }

    // ==================== GET AVAILABLE ENROLLMENTS ====================
    public function getAvailableEnrollments(Student $student)
    {
        $this->authorizeAction('assign');

        $currentEnrollmentIds = $student->enrollments()->pluck('enrollmentss.id');

        $availableEnrollments = Enrollmentss::with('course')
            ->whereNotIn('id', $currentEnrollmentIds)
            ->get();

        return response()->json($availableEnrollments);
    }
}
