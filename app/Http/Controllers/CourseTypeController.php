<?php

namespace App\Http\Controllers;

use App\Models\CourseType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CourseTypeController extends Controller
{
    public function index()
    {
        return Inertia::render('CourseType/Index', [
            'items' => CourseType::all()
        ]);
    }

    public function create()
    {
        return Inertia::render('CourseType/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_type' => 'required|string|max:255',
            'student_capacity' => 'required|integer',
        ]);

        CourseType::create($validated);

        return redirect()->route('course-type.index')
            ->with('success', 'Course Type created successfully.');
    }

    public function edit(CourseType $courseType)
    {
        return Inertia::render('CourseType/Edit', [
            'item' => $courseType
        ]);
    }

    public function update(Request $request, CourseType $courseType)
    {
        $validated = $request->validate([
            'course_type' => 'required|string|max:255',
            'student_capacity' => 'required|integer',
        ]);

        $courseType->update($validated);

        return redirect()->route('course-type.index')
            ->with('success', 'Course Type updated successfully.');
    }

    public function destroy(CourseType $courseType)
    {
        $courseType->delete();

        return redirect()->route('course-type.index')
            ->with('success', 'Course Type deleted successfully.');
    }
}
