<?php

namespace App\Http\Controllers;

use App\Models\SpecificCourse;
use App\Models\Course;
use App\Models\Teacher;
use App\Models\Location;
use App\Models\Batch;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class SpecificCourseController extends Controller
{
    
    private $permissions = [
        'Admin'   => ['view','create','edit','delete'],
        'Teacher' => ['view','create','edit','delete'], // adjust if needed
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

        $specificCourses = SpecificCourse::with(['course','teacher','location','batch'])->get();

        return Inertia::render('SpecificCourses/Index', [
            'specificCourses' => $specificCourses
        ]);
    }

    
    public function create()
    {
        $this->authorizeAction('create');

        return Inertia::render('SpecificCourses/Create', [
            'courses' => Course::all(),
            'teachers' => Teacher::all(),
            'locations' => Location::all(),
            'batches' => Batch::all(),
        ]);
    }

    
    public function store(Request $request)
    {
        $this->authorizeAction('create');

        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'teacher_id' => 'required|exists:teachers,id',
           
            'batch_id' => 'required|exists:batches,id',
            'specific_courses_code' => 'required|string|unique:specific_courses,specific_courses_code',

            'description' => 'nullable|string',
            
          
           
            
            'difficulty_level' => 'required|in:beginner,intermediate,advanced,expert',
            'resources' => 'nullable|string',
        ]);

        SpecificCourse::create($validated);

        return redirect()->route('specific-courses.index')
                         ->with('success', 'Specific course created successfully!');
    }

    
    public function edit(SpecificCourse $specificCourse)
    {
        $this->authorizeAction('edit');

        return Inertia::render('SpecificCourses/Edit', [
            'specificCourse' => $specificCourse,
            'courses' => Course::all(),
            'teachers' => Teacher::all(),
            
            'batches' => Batch::all(),
        ]);
    }

    
    public function update(Request $request, SpecificCourse $specificCourse)
    {
        $this->authorizeAction('edit');

        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'teacher_id' => 'required|exists:teachers,id',
          
            'batch_id' => 'required|exists:batches,id',
            'specific_courses_code' => 'required|string|unique:specific_courses,specific_courses_code,'.$specificCourse->id,
           
            'description' => 'nullable|string',
          
           
           
            
            'difficulty_level' => 'required|in:beginner,intermediate,advanced,expert',
            'resources' => 'nullable|string',
        ]);

        $specificCourse->update($validated);

        return redirect()->route('specific-courses.index')
                         ->with('success', 'Specific course updated successfully!');
    }

    public function destroy(SpecificCourse $specificCourse)
    {
        $this->authorizeAction('delete');

        $specificCourse->delete();

        return redirect()->route('specific-courses.index')
                         ->with('success', 'Specific course deleted successfully!');
    }
}
