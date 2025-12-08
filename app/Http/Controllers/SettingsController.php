<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseType;
use App\Models\Location;
use App\Models\User;
use App\Models\CompanyAddress;                           
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SettingsController extends Controller
{
    public function index()
    {
        return Inertia::render('Settings/Index', [
            'companies' => CompanyAddress::all(),
            'courses' => Course::all(),
            'courseType' => CourseType::all(),
            'locations' => Location::all(),
            'roles' => Role::with('permissions')->get(),
            'users' => User::all()->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'plain_password' => $user->plain_password,
                    'showPassword' => false,
                    'roles' => $user->roles // Add this line to include user roles
                ];
            }),
            'permissions' => Permission::all() // Add this line to pass permissions
        ]);
    }
}