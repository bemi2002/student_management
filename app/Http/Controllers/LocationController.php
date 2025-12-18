<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    // ==================== PERMISSION CHECK ====================
    private function authorizeAction(string $permission)
    {
        $user = Auth::user();
        if (!$user || !$user->can($permission)) {
            abort(403, 'Unauthorized access');
        }
    }

    // ==================== INDEX ====================
    public function index()
    {
        $this->authorizeAction('view Locations');

        $locations = Location::all();

        return Inertia::render('Settings/Index', [
            'locations' => $locations,
            'permissions' => Auth::user()->getAllPermissions()->pluck('name'), // For frontend buttons
        ]);
    }

    // ==================== STORE ====================
    public function store(Request $request)
    {
        $this->authorizeAction('create Locations');

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:locations,name',
        ]);

        Location::create($validated);

        return redirect()->back()->with([
            'success' => 'Location created successfully!',
            'locations' => Location::all()
        ]);
    }

    // ==================== UPDATE ====================
    public function update(Request $request, Location $location)
    {
        $this->authorizeAction('edit Locations');

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:locations,name,' . $location->id,
        ]);

        $location->update($validated);

        return redirect()->back()->with([
            'success' => 'Location updated successfully!',
            'locations' => Location::all()
        ]);
    }

    // ==================== DELETE ====================
    public function destroy(Location $location)
    {
        $this->authorizeAction('delete Locations');

        $location->delete();

        return redirect()->back()->with([
            'success' => 'Location deleted successfully!',
            'locations' => Location::all()
        ]);
    }
}