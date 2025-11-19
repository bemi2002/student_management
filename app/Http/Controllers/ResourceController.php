<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\Course;
use App\Models\SpecificCourse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class ResourceController extends Controller
{
    // Allowed actions per role
    private $permissions = [
        'Admin'   => ['view','create','edit','delete'],
        'Teacher' => ['view','create','edit','delete'],
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

    // List all resources
    public function index()
    {
        $this->authorizeAction('view');

        $resources = Resource::with(['course', 'specificCourse'])->latest()->get();

        return Inertia::render('Resources/Index', [
            'resources' => $resources,
        ]);
    }

    // Show create form
    public function create()
    {
        $this->authorizeAction('create');

        return Inertia::render('Resources/Create', [
            'courses' => Course::all(),
            'specificCourses' => SpecificCourse::all(),
        ]);
    }

    // Store new resource
    public function store(Request $request)
    {
        $this->authorizeAction('create');

        $validated = $request->validate([
            'courses_id' => 'required|exists:courses,id',
            'specific_courses_id' => 'required|exists:specific_courses,id',
            'text' => 'required|string',
        ]);

        // Ensure proper column names
        Resource::create([
            'courses_id' => $validated['courses_id'],
            'specific_courses_id' => $validated['specific_courses_id'],
            'text' => $validated['text'],
        ]);

        return redirect()->route('resources.index')
                         ->with('success', 'Resource created successfully!');
    }

    // Show edit form
    public function edit(Resource $resource)
    {
        $this->authorizeAction('edit');

        return Inertia::render('Resources/Edit', [
            'resource' => $resource,
            'courses' => Course::all(),
            'specificCourses' => SpecificCourse::all(),
        ]);
    }

    // Update resource
    public function update(Request $request, Resource $resource)
    {
        $this->authorizeAction('edit');

        $validated = $request->validate([
            'courses_id' => 'required|exists:courses,id',
            'specific_courses_id' => 'required|exists:specific_courses,id',
            'text' => 'required|string',
        ]);

        $resource->update([
            'courses_id' => $validated['courses_id'],
            'specific_courses_id' => $validated['specific_courses_id'],
            'text' => $validated['text'],
        ]);

        return redirect()->route('resources.index')
                         ->with('success', 'Resource updated successfully!');
    }

    // Delete resource
    public function destroy(Resource $resource)
    {
        $this->authorizeAction('delete');

        $resource->delete();

        return redirect()->route('resources.index')
                         ->with('success', 'Resource deleted successfully!');
    }
}
