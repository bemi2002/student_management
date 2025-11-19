<?php

namespace App\Http\Controllers;

use App\Models\CompanyLocation;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class CompanyLocationController extends Controller
{
    // Define allowed actions per role
    private $permissions = [
        'Admin'   => ['view','create','edit','delete'],
        'Manager' => ['view','create','edit','delete'], // adjust as needed
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

    // List all company locations
    public function index()
    {
        $this->authorizeAction('view');

        $locations = CompanyLocation::all();

        return Inertia::render('CompanyLocations/Index', [
            'locations' => $locations,
        ]);
    }

    // Show create company location form
    public function create()
    {
        $this->authorizeAction('create');

        return Inertia::render('CompanyLocations/Create');
    }

    // Store new company location
    public function store(Request $request)
    {
        $this->authorizeAction('create');

        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'email'        => 'required|email|unique:company_locations,email',
            'phone'        => 'required|string|max:20',
        ]);

        CompanyLocation::create($validated);

        return redirect()->route('company-locations.index')
                         ->with('success', 'Company location created successfully!');
    }

    // Show edit company location form
    public function edit(CompanyLocation $companyLocation)
    {
        $this->authorizeAction('edit');

        return Inertia::render('CompanyLocations/Edit', [
            'location' => $companyLocation,
        ]);
    }

    // Update company location
    public function update(Request $request, CompanyLocation $companyLocation)
    {
        $this->authorizeAction('edit');

        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'email'        => 'required|email|unique:company_locations,email,' . $companyLocation->id,
            'phone'        => 'required|string|max:20',
        ]);

        $companyLocation->update($validated);

        return redirect()->route('company-locations.index')
                         ->with('success', 'Company location updated successfully!');
    }

    // Delete company location
    public function destroy(CompanyLocation $companyLocation)
    {
        $this->authorizeAction('delete');

        $companyLocation->delete();

        return redirect()->route('company-locations.index')
                         ->with('success', 'Company location deleted successfully!');
    }
}
