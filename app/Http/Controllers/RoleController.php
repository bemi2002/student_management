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

        return Inertia::render('Settings/Index', [
            'roles' => Role::with('permissions')->get(),
            'permissions' => Permission::all()
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

        return redirect()->back()->with([
            'success' => "Role '{$role->name}' created successfully!",
            'roles' => Role::with('permissions')->get(),
            'permissions' => Permission::all()
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

        return redirect()->back()->with([
            'success' => "Role '{$role->name}' updated successfully!",
            'roles' => Role::with('permissions')->get(),
            'permissions' => Permission::all()
        ]);
    }

    public function destroy($id)
    {
        $this->authorizeAction('delete');

        $role = Role::find($id);

        if (!$role) {
            return redirect()->back()->with('error', 'Role not found.');
        }

        $protectedRoles = ['admin', 'super-admin', 'Admin', 'Super-Admin'];
        if (in_array($role->name, $protectedRoles)) {
            return redirect()->back()->with('error', 'Protected system roles cannot be deleted.');
        }

        try {
            $role->users()->detach();

            DB::table('roles')->where('id', $role->id)->delete();

            app()[PermissionRegistrar::class]->forgetCachedPermissions();

            return redirect()->back()->with([
                'success' => "Role '{$role->name}' deleted successfully!",
                'roles' => Role::with('permissions')->get(),
                'permissions' => Permission::all()
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete role: ' . $e->getMessage());
        }
    }
}
