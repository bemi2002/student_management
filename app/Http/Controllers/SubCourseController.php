<?php

namespace App\Http\Controllers;

use App\Models\SubCourse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class SubCourseController extends Controller
{
    // Show create form
    public function create(Request $request)
    {
        $course_id = $request->query('course_id'); // from ?course_id=
        return Inertia::render('SubCourses/Create', [
            'course_id' => $course_id,
        ]);
    }

    // Store subcourse
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id'     => 'required|exists:courses,id',
            'name'          => 'required|string|max:255',
            'resource_type' => 'required|in:file,url',
            'resource_path' => 'nullable', // can be file or URL
        ]);

        // Handle file upload
        if ($validated['resource_type'] === 'file' && $request->hasFile('resource_path')) {
            $validated['resource_path'] = $request->file('resource_path')->store('subcourses', 'public');
        } elseif ($validated['resource_type'] === 'url') {
            $validated['resource_path'] = $validated['resource_path'] ?? '';
        }

        SubCourse::create($validated);

        return redirect()->back()->with('success', 'SubCourse added!');
    }

    // Edit subcourse
    public function edit(SubCourse $subCourse)
    {
        return Inertia::render('SubCourses/Edit', [
            'subCourse' => $subCourse,
        ]);
    }

    // Update subcourse
    public function update(Request $request, SubCourse $subCourse)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'resource_type' => 'required|in:url,file',
            'resource_path' => 'nullable',
        ]);

        // Handle file upload
        if ($validated['resource_type'] === 'file' && $request->hasFile('resource_path')) {
            // Delete old file if exists
            if ($subCourse->resource_type === 'file' && $subCourse->resource_path) {
                Storage::disk('public')->delete($subCourse->resource_path);
            }

            $file = $request->file('resource_path');
            $validated['resource_path'] = $file->store('subcourses', 'public');
        }

        // Switching from file to URL
        if ($validated['resource_type'] === 'url') {
            // Delete old file if it exists
            if ($subCourse->resource_type === 'file' && $subCourse->resource_path) {
                Storage::disk('public')->delete($subCourse->resource_path);
            }
            $validated['resource_path'] = $validated['resource_path'] ?? '';
        }

        $subCourse->update($validated);

        return redirect()->route('courses.index')
                         ->with('success', 'SubCourse updated successfully!');
    }

    // Delete subcourse
    public function destroy(SubCourse $subCourse)
    {
        // Delete file if resource is a file
        if ($subCourse->resource_type === 'file' && $subCourse->resource_path) {
            Storage::disk('public')->delete($subCourse->resource_path);
        }

        $subCourse->delete();

        return redirect()->back()->with('success', 'SubCourse deleted!');
    }
}
