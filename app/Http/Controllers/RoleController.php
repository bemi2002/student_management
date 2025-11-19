<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\PermissionRegistrar;

class RoleController extends Controller
{
    private $permissions = [
        'Admin'       => ['view','create','edit','delete'],
        'Super-Admin' => ['view','create','edit','delete'],
    ];

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

    private function authorizeAction(string $action)
    {
        if (!$this->checkPermission($action)) {
            abort(403, 'Unauthorized access');
        }
    }

    public function index()
    {
        $this->authorizeAction('view');
        $roles = Role::with('permissions')->get();

        return Inertia::render('Roles/Index', [
            'roles' => $roles,
        ]);
    }

    public function create()
    {
        $this->authorizeAction('create');

        $permissions = Permission::all();

        return Inertia::render('Roles/Create', [
            'permissions' => $permissions
        ]);
    }

    public function store(Request $request)
    {
        $this->authorizeAction('create');

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'array',
        ]);

        $role = Role::create(['name' => $validated['name']]);
        if (!empty($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }

        // Clear permission cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('roles.index')->with('success', "Role '{$role->name}' created successfully.");
    }

    public function edit($id)
    {
        $this->authorizeAction('edit');

        $role = Role::with('permissions')->findOrFail($id);
        $permissions = Permission::all();

        return Inertia::render('Roles/Edit', [
            'role' => $role,
            'permissions' => $permissions,
            'rolePermissions' => $role->permissions->pluck('id')->toArray()
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->authorizeAction('edit');

        $role = Role::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'array',
        ]);

        $role->update(['name' => $validated['name']]);
        $role->syncPermissions($validated['permissions'] ?? []);

        // Clear permission cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('roles.index')->with('success', "Role '{$role->name}' updated successfully.");
    }

    public function destroy($id)
    {
        $this->authorizeAction('delete');

        $role = Role::find($id);

        if (!$role) {
            return redirect()->route('roles.index')->with('error', 'Role not found.');
        }

        $protectedRoles = ['admin', 'super-admin', 'Admin', 'Super-Admin'];
        if (in_array($role->name, $protectedRoles)) {
            return redirect()->route('roles.index')->with('error', 'Protected system roles cannot be deleted.');
        }

        try {
            // Detach all users
            $role->users()->detach();

            // Delete role permanently using DB
            DB::table('roles')->where('id', $role->id)->delete();

            // Clear Spatie permission cache
            app()[PermissionRegistrar::class]->forgetCachedPermissions();

            return redirect()->route('roles.index')->with('success', "Role '{$role->name}' deleted permanently.");
        } catch (\Exception $e) {
            return redirect()->route('roles.index')->with('error', 'Failed to delete role: ' . $e->getMessage());
        }
    }
}
