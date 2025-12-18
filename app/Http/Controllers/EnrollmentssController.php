<?php

namespace App\Http\Controllers;

use App\Models\Enrollmentss;
use App\Models\Student;
use App\Models\EnrollmentDropout;
use App\Models\Course;
use App\Models\CourseType;
use App\Models\CompanyAddress;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EnrollmentssController extends Controller
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
        $this->authorizeAction('view Enrollments');

        $enrollments = Enrollmentss::with(['course', 'courseType', 'companyAddress'])->get();

        return Inertia::render('Enrollmentss/Index', [
            'enrollments' => $enrollments,
            'courses' => Course::select('id', 'course_name')->get(),
            'courseTypes' => CourseType::select('id', 'course_type')->get(),
            'companyAddresses' => CompanyAddress::select('id', 'city')->get(),
        ]);
    }

    // ==================== SHOW ====================
    public function show($id)
    {
        $this->authorizeAction('view Enrollments');

        $enrollment = Enrollmentss::with([
            'course',
            'courseType',
            'companyAddress',
            'students' => function ($query) {
                $query->select('students.id', 'full_name', 'email', 'contact_phone');
            }
        ])->findOrFail($id);

        $assignedStudents = $enrollment->students;
        $dropoutHistory = $this->getDropoutHistory($id);

        return Inertia::render('Enrollmentss/Show', [
            'enrollment' => $enrollment,
            'assignedStudents' => $assignedStudents,
            'dropoutHistory' => $dropoutHistory,
            'availableStudentsUrl' => route('enrollmentss.available-students', $enrollment),
        ]);
    }

    // ==================== CREATE ====================
    public function create()
    {
        $this->authorizeAction('create Enrollments');

        return Inertia::render('Enrollmentss/Create', [
            'courses' => Course::select('id', 'course_name')->get(),
            'courseTypes' => CourseType::select('id', 'course_type')->get(),
            'companyAddresses' => CompanyAddress::select('id', 'city')->get(),
        ]);
    }

    // ==================== STORE ====================
    public function store(Request $request)
    {
        $this->authorizeAction('create Enrollments');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'course_id' => 'nullable|exists:courses,id',
            'course_type_id' => 'nullable|exists:courses_type,id',
            'company_address_id' => 'nullable|exists:company_addresses,id',
            'student_capacity' => 'nullable|integer|min:0',
            'amount_to_be_paid' => 'nullable|numeric|min:0',
            'completion_status' => 'nullable|string|max:50',
            'telegram_link' => 'nullable|string|max:255',
            'enrollment_date' => 'nullable|date',
            'organization' => 'nullable|string|max:255',
        ]);

        Enrollmentss::create($validated);

        return redirect()->route('enrollmentss.index')
            ->with('success', 'Enrollment created successfully');
    }

    // ==================== EDIT ====================
    public function edit(Enrollmentss $enrollmentss)
    {
        $this->authorizeAction('edit Enrollments');

        return Inertia::render('Enrollmentss/Edit', [
            'enrollmentss' => $enrollmentss,
            'courses' => Course::select('id', 'course_name')->get(),
            'courseTypes' => CourseType::select('id', 'course_type')->get(),
            'companyAddresses' => CompanyAddress::select('id', 'city')->get(),
        ]);
    }

    // ==================== UPDATE ====================
    public function update(Request $request, Enrollmentss $enrollmentss)
    {
        $this->authorizeAction('edit Enrollments');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'course_id' => 'nullable|exists:courses,id',
            'course_type_id' => 'nullable|exists:courses_type,id',
            'company_address_id' => 'nullable|exists:company_addresses,id',
            'student_capacity' => 'nullable|integer|min:0',
            'amount_to_be_paid' => 'nullable|numeric|min:0',
            'completion_status' => 'nullable|string|max:50',
            'telegram_link' => 'nullable|string|max:255',
            'enrollment_date' => 'nullable|date',
            'organization' => 'nullable|string|max:255',
        ]);

        $enrollmentss->update($validated);

        return redirect()->route('enrollmentss.index')
                         ->with('success', 'Enrollment updated successfully');
    }

    // ==================== DELETE ====================
    public function destroy(Enrollmentss $enrollmentss)
    {
        $this->authorizeAction('delete Enrollments');

        if ($enrollmentss->students()->count() > 0) {
            $enrollmentss->students()->detach();
        }

        $enrollmentss->delete();

        return redirect()->route('enrollmentss.index')
            ->with('success', 'Enrollment deleted successfully');
    }

    // ==================== AVAILABLE STUDENTS ====================
    public function availableStudents(Enrollmentss $enrollmentss)
    {
        $this->authorizeAction('view Enrollments'); // Added authorization

        $students = Student::whereDoesntHave('enrollmentss', function($query) use ($enrollmentss) {
                $query->where('enrollmentss_id', $enrollmentss->id);
            })
            ->select('id', 'full_name', 'email', 'contact_phone')
            ->get();

        $assignedCount = $enrollmentss->students()->count();

        return response()->json([
            'success' => true,
            'data' => $students,
            'assigned_count' => $assignedCount,
        ]);
    }

    // ==================== ASSIGN STUDENT ====================
    public function assignStudent(Request $request, $enrollmentId)
    {
        $this->authorizeAction('assign Student'); // Fixed: uppercase 'S' in 'Student'

        $enrollment = Enrollmentss::findOrFail($enrollmentId);

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
        ]);

        if ($enrollment->students()->where('student_id', $validated['student_id'])->exists()) {
            return response()->json(['success' => false, 'message' => 'Student already assigned'], 422);
        }

        if ($enrollment->student_capacity && $enrollment->students()->count() >= $enrollment->student_capacity) {
            return response()->json(['success' => false, 'message' => 'Enrollment capacity reached'], 422);
        }

        $enrollment->students()->attach($validated['student_id']);
        $this->removeFromRejectedEnrollments($validated['student_id'], $enrollment->id);

        $student = Student::select('id', 'full_name', 'email', 'contact_phone')->find($validated['student_id']);

        return response()->json([
            'success' => true,
            'message' => 'Student assigned successfully',
            'student' => $student
        ]);
    }

    // ==================== UNASSIGN STUDENT ====================
    public function unassignStudent(Request $request, $enrollmentId)
    {
        $this->authorizeAction('unassign Student'); // Fixed: uppercase 'S' in 'Student'

        $enrollment = Enrollmentss::findOrFail($enrollmentId);

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'reason' => 'required|string|max:500',
        ]);

        if (!$enrollment->students()->where('student_id', $validated['student_id'])->exists()) {
            return response()->json(['success' => false, 'message' => 'Student not assigned'], 422);
        }

        $enrollment->students()->detach($validated['student_id']);
        $this->createRejectedEnrollmentRecord($validated['student_id'], $enrollmentId, $validated['reason']);

        return response()->json(['success' => true, 'message' => 'Student removed successfully']);
    }

    // ==================== PRIVATE METHODS ====================
    private function getDropoutHistory($enrollmentId)
    {
        return EnrollmentDropout::with(['student:id,full_name,email,contact_phone'])
            ->where('enrollment_id', $enrollmentId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    private function removeFromRejectedEnrollments($studentId, $enrollmentId)
    {
        EnrollmentDropout::where('student_id', $studentId)
            ->where('enrollment_id', $enrollmentId)
            ->delete();
    }

    private function createRejectedEnrollmentRecord($studentId, $enrollmentId, $reason)
    {
        EnrollmentDropout::create([
            'student_id' => $studentId,
            'enrollment_id' => $enrollmentId,
            'reason' => $reason,
        ]);
    }

    // ==================== LIST SIMPLE ====================
    public function listSimple()
    {
        $this->authorizeAction('view Enrollments'); // Added authorization
        
        return Enrollmentss::select('id', 'title')->orderBy('title')->get();
    }
}