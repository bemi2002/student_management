<?php

namespace App\Http\Controllers;

use App\Models\CompanyAddress;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class CompanyAddressController extends Controller
{
    // Allowed actions per role
    private $permissions = [
        'Admin' => ['view', 'create', 'edit', 'delete'],
        'Super-Admin' => ['view', 'create', 'edit', 'delete'],
    ];

    // Check role permissions
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

    // Abort if unauthorized
    private function authorizeAction(string $action)
    {
        if (!$this->checkPermission($action)) {
            abort(403, 'Unauthorized access');
        }
    }

    // List all company addresses
    public function index()
    {
        $this->authorizeAction('view');

        $companies = CompanyAddress::all();

        return Inertia::render('CompanyAddresses/Index', [
            'companies' => $companies,
        ]);
    }

    // Show create page
    public function create()
    {
        $this->authorizeAction('create');
        return Inertia::render('CompanyAddresses/Create');
    }

    // Store new company address
    public function store(Request $request)
    {
        $this->authorizeAction('create');

        $validated = $request->validate([
            'company_name'   => 'required|string|max:255',
            'email'          => 'required|email|max:255|unique:company_address,email',
            'contact_phone'  => 'nullable|string|max:20',
            'city'           => 'nullable|string|max:255',
            'sub_city'       => 'nullable|string|max:255',
            'building_name'  => 'nullable|string|max:255',
            'building_number'=> 'nullable|string|max:255',
            'website'        => 'nullable|string|max:255',
        ]);

        CompanyAddress::create($validated);

        return redirect()->route('company-addresses.index')
            ->with('success', 'Company address created successfully!');
    }

    // Show edit page
    public function edit(CompanyAddress $companyAddress)
    {
        $this->authorizeAction('edit');

        return Inertia::render('CompanyAddresses/Edit', [
            'company' => $companyAddress,
        ]);
    }

    // Update company address
    public function update(Request $request, CompanyAddress $companyAddress)
    {
        $this->authorizeAction('edit');

        $validated = $request->validate([
            'company_name'   => 'required|string|max:255',
            'email'          => 'required|email|max:255|unique:company_address,email,' . $companyAddress->id,
            'contact_phone'  => 'nullable|string|max:20',
            'city'           => 'nullable|string|max:255',
            'sub_city'       => 'nullable|string|max:255',
            'building_name'  => 'nullable|string|max:255',
            'building_number'=> 'nullable|string|max:255',
            'website'        => 'nullable|string|max:255',
        ]);

        $companyAddress->update($validated);

        return redirect()->route('company-addresses.index')
            ->with('success', 'Company address updated successfully!');
    }

    // Delete company address
    public function destroy(CompanyAddress $companyAddress)
    {
        $this->authorizeAction('delete');

        $companyAddress->delete();

        return redirect()->route('company-addresses.index')
            ->with('success', 'Company address deleted successfully!');
    }
}
