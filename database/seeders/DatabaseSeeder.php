<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // -------------------
        // Create Roles
        // -------------------
        $roles = ['Super-Admin', 'Admin', 'User'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // -------------------
        // Create Permissions
        // -------------------
        $permissions = [
            'view Students', 'create Students', 'edit Students', 'delete Students',
            'view Courses', 'create Courses', 'edit Courses', 'delete Courses',
            'view Locations', 'create Locations', 'edit Locations', 'delete Locations',
            'view Teachers', 'create Teachers', 'edit Teachers', 'delete Teachers',
            'view Company Addresses', 'create Company Addresses', 'edit Company Addresses', 'delete Company Addresses',
            'view Enrollments', 'create Enrollments', 'edit Enrollments', 'delete Enrollments',
            'Edit course_type','delete course_type', 'create course_type', 'view course_type',
            'unassign Student','assign Student', 'view settings'// Changed: lowercase 'u' in 'unassign' to match
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // -------------------
        // Assign permissions to roles
        // -------------------
        $superAdminRole = Role::where('name', 'Super-Admin')->first();
        $superAdminRole->syncPermissions($permissions); // Super Admin gets all

        $adminRole = Role::where('name', 'Admin')->first();
        $adminRole->syncPermissions([
            'view Students','create Students','edit Students',
            'view Courses','create Courses','edit Courses',
            'view Locations','create Locations','edit Locations',
            'view Teachers','create Teachers','edit Teachers',
            'view Company Addresses','create Company Addresses','edit Company Addresses',
            'view Enrollments','create Enrollments','edit Enrollments',
            'Edit course_type','delete course_type', 'create course_type', 'view course_type',
            'unassign Student','assign Student', 'view settings' // Changed to match exactly
        ]); // Admin has limited rights (no deletes)

        // -------------------
        // Create test users
        // -------------------
        $superAdmin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => bcrypt('password'),
        ]);
        $superAdmin->assignRole('Super-Admin');

        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmai.com',
            'password' => bcrypt('admin12345'),
        ]);
        $admin->assignRole('Admin');

        
    }
}