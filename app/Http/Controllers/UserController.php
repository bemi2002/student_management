<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Define allowed actions per role
    private $permissions = [
        'Admin' => ['view', 'create', 'edit', 'delete'],
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

    // Fetch all users for Settings.vue
    public function index()
    {
        $this->authorizeAction('view');

        $users = User::with('roles')->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles->map(fn($r) => [
                    'id' => $r->id,
                    'name' => $r->name
                ]),
                'created_at' => $user->created_at,
            ];
        });

        $roles = Role::all();

        return Inertia::render('Settings', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorizeAction('create');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'roles' => 'array'
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password'])
        ]);

        if (!empty($validated['roles'])) {
            $roles = Role::whereIn('id', $validated['roles'])->get();
            $user->syncRoles($roles);
        }

        // Redirect back to Settings.vue
        return back()->with([
            'success' => 'User created successfully!',
            'users' => User::with('roles')->get(),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $this->authorizeAction('edit');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'roles' => 'array'
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email']
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        $roles = !empty($validated['roles'])
            ? Role::whereIn('id', $validated['roles'])->get()
            : [];
        $user->syncRoles($roles);

        return back()->with([
            'success' => 'User updated successfully!',
            'users' => User::with('roles')->get(),
        ]);
    }

    public function destroy(User $user)
    {
        $this->authorizeAction('delete');

        $user->delete();

        return back()->with([
            'success' => 'User deleted successfully!',
            'users' => User::with('roles')->get(),
        ]);
    }
}
