<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $models = ['clients', 'notes', 'attachments', 'activity-logs'];
        $actions = ['view', 'create', 'edit', 'delete'];

        $allPermissions = [];
        foreach ($models as $model) {
            foreach ($actions as $action) {
                $permissionName = "{$action}-{$model}";
                Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'api']);
                $allPermissions[] = $permissionName;
            }
        }

        // Create Roles and Assign Permissions
        
        // Admin Role
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'api']);
        $adminRole->syncPermissions($allPermissions);

        // Manager Role (View Only)
        $managerRole = Role::firstOrCreate(['name' => 'Manager', 'guard_name' => 'api']);
        $viewPermissions = array_filter($allPermissions, function($permission) {
            return str_starts_with($permission, 'view-');
        });
        $managerRole->syncPermissions($viewPermissions);

        // Assign 'Admin' role to all users who have 'admin' in the legacy 'role' column
        \App\Models\User::where('role', 'admin')->get()->each(function ($user) use ($adminRole) {
            $user->assignRole($adminRole);
        });
    }
}
