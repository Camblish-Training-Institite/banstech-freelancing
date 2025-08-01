<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role; // Import Role model
use Spatie\Permission\Models\Permission; // Import Permission model

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear cached roles and permissions (recommended when seeding permissions)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Create Permissions (if they don't exist yet)
        Permission::findOrCreate('manage projects');
        Permission::findOrCreate('view project details');
        Permission::findOrCreate('create milestones');
        Permission::findOrCreate('update milestones');
        Permission::findOrCreate('assign freelancers');
        Permission::findOrCreate('send messages');
        Permission::findOrCreate('request project management'); // Optional permission

        // You might also want permissions for common CRUD operations like:
        // Permission::findOrCreate('create_user');
        // Permission::findOrCreate('view_user');
        // Permission::findOrCreate('update_user');
        // Permission::findOrCreate('delete_user');

        // 2. Create Roles and Assign Permissions
        // Example: Project Manager Role
        $projectManagerRole = Role::findOrCreate('project-manager');
        $projectManagerRole->givePermissionTo([
            'manage projects',
            'view project details',
            'create milestones',
            'update milestones',
            'assign freelancers',
            'send messages',
            'request project management', // Optional
        ]);

        // Example: Admin Role (if you want an overall admin)
        $adminRole = Role::findOrCreate('admin');
        // Grant all existing permissions to the admin role
        $adminRole->givePermissionTo(Permission::all());
        
        
        // Example: Regular User Role (if you have one, with minimal permissions)
        $UserRole = Role::findOrCreate('freelancer-client'); // No permissions by default for a basic user role
        $UserRole->givePermissionTo([
            'manage projects',
            'view project details',
            'create milestones',
            'update milestones',
            'assign freelancers',
            'send messages',
            'request project management', // Optional
        ]);


        // 3. Assign Roles to Users (Optional, but good for initial testing)
        // Find a user and assign the role
        // \App\Models\User::find(1)->assignRole('admin'); // Assign 'admin' role to user with ID 1
        // \App\Models\User::where('email', 'john@example.com')->first()->assignRole('project_manager');
    
    }
}
