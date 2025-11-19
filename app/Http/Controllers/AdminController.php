<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Batch;
use App\Models\Course;
use App\Models\Resource;
use App\Models\SpecificCourse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // ✅ Define role permissions
    private $permissions = [
        'Super-Admin' => ['view', 'create', 'edit', 'delete'],
        'Admin'       => ['view', 'create', 'edit', 'delete'],
    ];

    // ✅ Check user permission
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

    // ✅ Abort if unauthorized
    private function authorizeAction(string $action)
    {
        if (!$this->checkPermission($action)) {
            abort(403, 'Unauthorized access');
        }
    }

    // ✅ INDEX — list all admins
    public function index()
    {
        $this->authorizeAction('view');

        $admins = Admin::with(['teacher', 'student', 'batch', 'course', 'specificCourse'])
            ->orderBy('id', 'desc')
            ->get();

        return Inertia::render('Admins/Index', [
            'admins' => $admins,
        ]);
    }

    // ✅ CREATE — show creation form
    public function create()
    {
        $this->authorizeAction('create');

        return Inertia::render('Admins/Create', [
            'teachers' => Teacher::all(),
            'students' => Student::all(),
            'batches' => Batch::all(),
            'courses' => Course::all(),
            'resources' => Resource::all(),
            'specificCourses' => SpecificCourse::all(),
        ]);
    }

    // ✅ STORE — save a new admin record
    public function store(Request $request)
    {
        $this->authorizeAction('create');

        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'student_id' => 'required|exists:students,id',
            'batches_id' => 'required|exists:batches,id',
            'course_id' => 'required|exists:courses,id',
            'resources' => 'required|exists:resources,id', // ✅ matches your DB
            'specific_courses_id' => 'required|exists:specific_courses,id',
        ]);

        Admin::create($validated);

        return redirect()->route('admins.index')
                         ->with('success', 'Admin record created successfully!');
    }

    // ✅ EDIT — show edit form
    public function edit(Admin $admin)
    {
        $this->authorizeAction('edit');

        return Inertia::render('Admins/Edit', [
            'admin' => $admin,
            'teachers' => Teacher::all(),
            'students' => Student::all(),
            'batches' => Batch::all(),
            'courses' => Course::all(),
            'resources' => Resource::all(),
            'specificCourses' => SpecificCourse::all(),
        ]);
    }

    // ✅ UPDATE — apply updates
    public function update(Request $request, Admin $admin)
    {
        $this->authorizeAction('edit');

        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'student_id' => 'required|exists:students,id',
            'batches_id' => 'required|exists:batches,id',
            'course_id' => 'required|exists:courses,id',
            'resources' => 'required|exists:resources,id',
            'specific_courses_id' => 'required|exists:specific_courses,id',
        ]);

        $admin->update($validated);

        return redirect()->route('admins.index')
                         ->with('success', 'Admin record updated successfully!');
    }

    // ✅ DESTROY — delete record
    public function destroy(Admin $admin)
    {
        $this->authorizeAction('delete');

        $admin->delete();

        return redirect()->route('admins.index')
                         ->with('success', 'Admin record deleted successfully!');
    }
}
