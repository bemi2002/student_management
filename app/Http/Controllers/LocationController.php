<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    // Define allowed actions for Admins
    private $permissions = [
        'Admin' => ['view', 'create', 'edit', 'delete'],
    ];

    // Check if the logged-in user has permission for the given action
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

    // Authorization helper
    private function authorizeAction(string $action)
    {
        if (!$this->checkPermission($action)) {
            abort(403, 'Unauthorized access');
        }
    }

    // Display a listing of locations
    public function index()
    {
        $this->authorizeAction('view');

        $locations = Location::all();

        return Inertia::render('Locations/Index', [
            'locations' => $locations,
        ]);
    }

    // Show the form for creating a new location
    public function create()
    {
        $this->authorizeAction('create');

        return Inertia::render('Locations/Create');
    }

    
    public function store(Request $request)
    {
        $this->authorizeAction('create');

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Location::create($request->all());

        return redirect()->route('locations.index')->with('success', 'Location created successfully!');
    }

    // Show the form for editing a location
    public function edit(Location $location)
    {
        $this->authorizeAction('edit');

        return Inertia::render('Locations/Edit', [
            'location' => $location,
        ]);
    }

    // Update the specified location
    public function update(Request $request, Location $location)
    {
        $this->authorizeAction('edit');

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $location->update($request->all());

        return redirect()->route('locations.index')->with('success', 'Location updated successfully!');
    }

    // Delete the specified location
    public function destroy(Location $location)
    {
        $this->authorizeAction('delete');

        $location->delete();

        return redirect()->route('locations.index')->with('success', 'Location deleted successfully!');
    }
}
