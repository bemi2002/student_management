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
    private $permissions = [
        'Admin' => ['view', 'create', 'edit', 'delete'],
      
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

        return Inertia::render('Users/Index', [
            'users' => $users
        ]);
    }

   
    public function create()
    {
        $this->authorizeAction('create');

        $roles = Role::all();

        return Inertia::render('Users/Create', [
            'roles' => $roles
        ]);
    }

    
    public function store(Request $request)
    {
        $this->authorizeAction('create');

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'roles' => 'array'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        if (!empty($request->roles)) {
            $roles = Role::whereIn('id', $request->roles)->get();
            $user->syncRoles($roles);
        }

        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }

    
    public function edit($id)
    {
        $this->authorizeAction('edit');

        $user = User::findOrFail($id);
        $roles = Role::all();
        $userRoles = $user->roles->pluck('id')->toArray();

        return Inertia::render('Users/Edit', [
            'user' => $user,
            'roles' => $roles,
            'userRoles' => $userRoles
        ]);
    }

    // Update user
    public function update(Request $request, $id)
    {
        $this->authorizeAction('edit');

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
            'roles' => 'array'
        ]);

        $user = User::findOrFail($id);

        $user->update([
            'name' => $request->name,
            'email' => $request->email
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        $roles = !empty($request->roles)
            ? Role::whereIn('id', $request->roles)->get()
            : [];
        $user->syncRoles($roles);

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    // Delete user
    public function destroy($id)
    {
        $this->authorizeAction('delete');

        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully!');
    }
}
