<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class BatchController extends Controller
{
    // Define allowed actions per role
    private $permissions = [
        'Super-Admin' => ['view', 'create', 'edit', 'delete'],
        'Admin'       => ['view', 'create', 'edit', 'delete'],
        'Teacher'     => ['view',], // Only view permission
    ];

    /**
     * Check if the logged-in user has permission for the given action
     */
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

    // List all batches
    public function index()
    {
        $this->authorizeAction('view');

        $user = Auth::user();

        if ($user->hasRole('Teacher')) {
            // Return an empty collection for new teachers
            $batches = collect([]);
        } else {
            // Admin/Super-Admin sees all
            $batches = Batch::all();
        }

        return Inertia::render('Batches/Index', [
            'batches' => $batches,
        ]);
    }

    // Show create batch form
    public function create()
    {
        $this->authorizeAction('create');

        return Inertia::render('Batches/Create');
    }

    // Store new batch
    public function store(Request $request)
    {
        $this->authorizeAction('create');

        $validated = $request->validate([
            'batch' => 'required|string|max:255',
        ]);

        Batch::create($validated);

        return redirect()->route('batches.index')
                         ->with('success', 'Batch created successfully!');
    }

    // Show edit batch form
    public function edit(Batch $batch)
    {
        $this->authorizeAction('edit');

        return Inertia::render('Batches/Edit', [
            'batch' => $batch,
        ]);
    }

    // Update batch
    public function update(Request $request, Batch $batch)
    {
        $this->authorizeAction('edit');

        $validated = $request->validate([
            'batch' => 'required|string|max:255',
        ]);

        $batch->update($validated);

        return redirect()->route('batches.index')
                         ->with('success', 'Batch updated successfully!');
    }

    // Delete batch
    public function destroy(Batch $batch)
    {
        $this->authorizeAction('delete');

        $batch->delete();

        return redirect()->route('batches.index')
                         ->with('success', 'Batch deleted successfully!');
    }
}
