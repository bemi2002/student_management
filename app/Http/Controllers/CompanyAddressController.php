<?php

namespace App\Http\Controllers;

use App\Models\CompanyAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CompanyAddressController extends Controller
{
    private $permissions = [
        'Admin' => ['view', 'create', 'edit', 'delete'],
        'Super-Admin' => ['view', 'create', 'edit', 'delete'],
    ];

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

    private function authorizeAction(string $action)
    {
        if (!$this->checkPermission($action)) {
            abort(403, 'Unauthorized access');
        }
    }

    public function index()
    {
        $this->authorizeAction('view');
        $companies = CompanyAddress::all();
        return Inertia::render('Settings/Index', compact('companies'));
    }

    public function store(Request $request)
    {
        $this->authorizeAction('create');

        $validated = $request->validate([
            'city'            => 'nullable|string|max:255',
            'sub_city'        => 'nullable|string|max:255',
            'building_name'   => 'nullable|string|max:255',
            'building_number' => 'nullable|string|max:255',
        ]);

        CompanyAddress::create($validated);

        return redirect()->back()->with([
            'success' => 'Company created successfully!',
            'companies' => CompanyAddress::all()
        ]);
    }

    public function update(Request $request, CompanyAddress $companyAddress)
    {
        $this->authorizeAction('edit');

        $validated = $request->validate([
            'city'            => 'nullable|string|max:255',
            'sub_city'        => 'nullable|string|max:255',
            'building_name'   => 'nullable|string|max:255',
            'building_number' => 'nullable|string|max:255',
        ]);

        $companyAddress->update($validated);

        return redirect()->back()->with([
            'success' => 'Company updated successfully!',
            'companies' => CompanyAddress::all()
        ]);
    }

    public function destroy(CompanyAddress $companyAddress)
    {
        $this->authorizeAction('delete');

        $companyAddress->delete();

        return redirect()->back()->with([
            'success' => 'Company deleted successfully!',
            'companies' => CompanyAddress::all()
        ]);
    }
}
