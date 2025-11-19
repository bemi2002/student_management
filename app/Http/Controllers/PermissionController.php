<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    // Define allowed actions per role
    private $permissions = [
        'Admin'       => ['view', 'create', 'edit', 'delete'],
        'Super-Admin' => ['view', 'create', 'edit', 'delete'], // optional
        
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

    // List all permissions
    public function index()
    {
        $this->authorizeAction('view');

        $permissions = Permission::all();

        return Inertia::render('Permissions/List', [
            'permissions' => $permissions
        ]);
    }

    // Show create permission form
    public function create()
    {
        $this->authorizeAction('create');

        return Inertia::render('Permissions/Create');
    }

    // Store a new permission
    public function store(Request $request)
    {
        $this->authorizeAction('create');

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
        ]);

        Permission::create($validated);

        return redirect()->route('permissions.index')
                         ->with('success', 'Permission created successfully!');
    }

    // Show edit permission form
    public function edit(Permission $permission)
    {
        $this->authorizeAction('edit');

        return Inertia::render('Permissions/Edit', [
            'permission' => $permission
        ]);
    }

    // Update an existing permission
    public function update(Request $request, Permission $permission)
    {
        $this->authorizeAction('edit');

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
        ]);

        $permission->update($validated);

        return redirect()->route('permissions.index')
                         ->with('success', 'Permission updated successfully!');
    }

    // Delete a permission
    public function destroy(Permission $permission)
    {
        $this->authorizeAction('delete');

        $permission->delete();

        return redirect()->route('permissions.index')
                         ->with('success', 'Permission deleted successfully!');
    }
}
