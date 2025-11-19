<?php

namespace App\Http\Controllers;

use App\Models\Enrollmentss;
use App\Models\Student;
use App\Models\RejectedEnrollment;
use App\Models\Course;
use App\Models\CourseType;
use App\Models\CompanyAddress;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EnrollmentssController extends Controller
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

    // ==================== INDEX ====================
    public function index()
    {
        $this->authorizeAction('view');

        $enrollments = Enrollmentss::with(['course', 'courseType', 'companyAddress'])->get();

        return Inertia::render('Enrollmentss/Index', [
            'enrollments' => $enrollments,
            'courses' => Course::select('id', 'course_name')->get(),
            'courseTypes' => CourseType::select('id', 'course_type')->get(),
            'companyAddresses' => CompanyAddress::select('id', 'company_name')->get(),
        ]);
    }

    // ==================== SHOW ====================
    public function show($id, Request $request)
    {
        $this->authorizeAction('view');

        // Load the enrollment with relationships
        $enrollment = Enrollmentss::with(['course', 'courseType', 'companyAddress', 'students'])
            ->findOrFail($id);

        // Return Inertia response for page loads
        return Inertia::render('Enrollmentss/Show', [
            'enrollment' => $enrollment,
            'availableStudentsUrl' => route('enrollmentss.available-students', $enrollment),
        ]);
    }

    // ==================== CREATE ====================
    public function create()
    {
        $this->authorizeAction('create');

        return Inertia::render('Enrollmentss/Create', [
            'courses' => Course::select('id', 'course_name')->get(),
            'courseTypes' => CourseType::select('id', 'course_type')->get(),
            'companyAddresses' => CompanyAddress::select('id', 'company_name')->get(),
        ]);
    }

    // ==================== STORE ====================
    public function store(Request $request)
    {
        $this->authorizeAction('create');

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
        ]);

        Enrollmentss::create($validated);

        return redirect()->route('enrollmentss.index')
            ->with('success', 'Enrollment created successfully');
    }

    // ==================== EDIT ====================
      public function edit(Enrollmentss $enrollmentss)
    {
        $this->authorizeAction('edit');

        return Inertia::render('Enrollmentss/Edit', [
            'enrollmentss' => $enrollmentss, // must match prop in Vue
            'courses' => Course::select('id', 'course_name')->get(),
            'courseTypes' => CourseType::select('id', 'course_type')->get(),
            'companyAddresses' => CompanyAddress::select('id', 'company_name')->get(),
        ]);
    }
    
    // ==================== UPDATE ====================
public function update(Request $request, Enrollmentss $enrollmentss)
{
    $this->authorizeAction('edit');

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
    ]);

    $enrollmentss->update($validated);

    // ✅ Return redirect to index page
    return redirect()->route('enrollmentss.index')
                     ->with('success', 'Enrollment updated successfully');
}


    // ==================== DELETE ====================
    public function destroy(Enrollmentss $enrollmentss)
    {
        $this->authorizeAction('delete');

        // Detach related students first
        if ($enrollmentss->students()->count() > 0) {
            $enrollmentss->students()->detach();
        }

        $enrollmentss->delete();

        // Return JSON for Inertia router.delete
        return response()->json([
            'success' => true,
            'message' => 'Enrollment deleted successfully',
        ]);
    }


    // ==================== AVAILABLE STUDENTS ====================
    public function availableStudents(Enrollmentss $enrollmentss)
    {
        try {
            // Use whereDoesntHave to avoid ambiguous column issues
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

        } catch (\Exception $e) {
            Log::error('Error fetching available students: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load available students',
                'data' => []
            ], 500);
        }
    }

    // ==================== ASSIGN STUDENT - FIXED VERSION ====================
    public function assignStudent(Request $request, $enrollmentId)
    {
        try {
            $this->authorizeAction('assign');

            // Find the enrollment
            $enrollment = Enrollmentss::findOrFail($enrollmentId);

            $validated = $request->validate([
                'student_id' => 'required|exists:students,id',
            ]);

            // Check if student is already assigned
            $alreadyAssigned = $enrollment->students()
                ->where('student_id', $validated['student_id'])
                ->exists();

            if ($alreadyAssigned) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student is already assigned to this enrollment.'
                ], 422);
            }

            // Check capacity
            if ($enrollment->student_capacity && $enrollment->students()->count() >= $enrollment->student_capacity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot assign student: enrollment capacity reached.'
                ], 422);
            }

            // Use attach to assign the student
            $enrollment->students()->attach($validated['student_id']);

            // Load the assigned student data for frontend
            $student = Student::select('id', 'full_name', 'email', 'contact_phone')
                ->find($validated['student_id']);

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student not found after assignment.'
                ], 500);
            }

            // Remove from rejected enrollments if exists
            RejectedEnrollment::where('student_id', $validated['student_id'])
                ->where('enrollmentss_id', $enrollment->id)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Student assigned successfully',
                'student' => $student
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error assigning student to enrollment ' . $enrollmentId . ': ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Server error while assigning student: ' . $e->getMessage()
            ], 500);
        }
    }

    // ==================== UNASSIGN STUDENT ====================
    public function unassignStudent(Request $request, $enrollmentId)
    {
        try {
            $this->authorizeAction('unassign');

            // Find the enrollment
            $enrollment = Enrollmentss::findOrFail($enrollmentId);

            $validated = $request->validate([
                'student_id' => 'required|exists:students,id',
                'reason' => 'required|string|max:500',
            ]);

            // Check if student is actually assigned
            $isAssigned = $enrollment->students()
                ->where('student_id', $validated['student_id'])
                ->exists();

            if (!$isAssigned) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student is not assigned to this enrollment.'
                ], 422);
            }

            // Detach the student
            $enrollment->students()->detach($validated['student_id']);

            // Log the reason (optional)
            Log::info("Student {$validated['student_id']} removed from enrollment {$enrollmentId}. Reason: {$validated['reason']}");

            return response()->json([
                'success' => true,
                'message' => 'Student removed from enrollment successfully'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Unassign student error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove student: ' . $e->getMessage()
            ], 500);
        }
    }
}