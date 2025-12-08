<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    // Define allowed actions per role
    private $permissions = [
        'Admin' => ['view','create','edit','delete'],
        'Teacher' => ['view','create','edit','delete'],
    ];

    /**
     * Check if the logged-in user has permission for the given action
     */
    private function checkPermission(string $action): bool
    {
        $user = Auth::user();
        if (!$user) return false;

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
        $locations = Location::all();

        return Inertia::render('Settings/Index', [
            'locations' => $locations
        ]);
    }

    public function store(Request $request)
    {
        $this->authorizeAction('create');

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:locations,name',
        ]);

        Location::create($validated);

        return redirect()->back()->with([
            'success' => 'Location created successfully!',
            'locations' => Location::all()
        ]);
    }

    public function update(Request $request, Location $location)
    {
        $this->authorizeAction('edit');

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:locations,name,' . $location->id,
        ]);

        $location->update($validated);

        return redirect()->back()->with([
            'success' => 'Location updated successfully!',
            'locations' => Location::all()
        ]);
    }

    public function destroy(Location $location)
    {
        $this->authorizeAction('delete');

        $location->delete();

        return redirect()->back()->with([
            'success' => 'Location deleted successfully!',
            'locations' => Location::all()
        ]);
    }
}