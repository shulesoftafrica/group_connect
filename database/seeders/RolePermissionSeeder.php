<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Permissions
        $permissions = [
            // Dashboard permissions
            ['name' => 'dashboard.view', 'display_name' => 'View Dashboard', 'module' => 'dashboard', 'action' => 'view'],
            ['name' => 'dashboard.export', 'display_name' => 'Export Dashboard Data', 'module' => 'dashboard', 'action' => 'export'],
            
            // Schools permissions
            ['name' => 'schools.view', 'display_name' => 'View Schools', 'module' => 'schools', 'action' => 'view'],
            ['name' => 'schools.create', 'display_name' => 'Create Schools', 'module' => 'schools', 'action' => 'create'],
            ['name' => 'schools.edit', 'display_name' => 'Edit Schools', 'module' => 'schools', 'action' => 'edit'],
            ['name' => 'schools.delete', 'display_name' => 'Delete Schools', 'module' => 'schools', 'action' => 'delete'],
            
            // Academics permissions
            ['name' => 'academics.view', 'display_name' => 'View Academics', 'module' => 'academics', 'action' => 'view'],
            ['name' => 'academics.edit', 'display_name' => 'Edit Academics', 'module' => 'academics', 'action' => 'edit'],
            ['name' => 'academics.export', 'display_name' => 'Export Academic Data', 'module' => 'academics', 'action' => 'export'],
            
            // Finance permissions
            ['name' => 'finance.view', 'display_name' => 'View Finance', 'module' => 'finance', 'action' => 'view'],
            ['name' => 'finance.edit', 'display_name' => 'Edit Finance', 'module' => 'finance', 'action' => 'edit'],
            ['name' => 'finance.export', 'display_name' => 'Export Finance Data', 'module' => 'finance', 'action' => 'export'],
            
            // Operations permissions
            ['name' => 'operations.view', 'display_name' => 'View Operations', 'module' => 'operations', 'action' => 'view'],
            ['name' => 'operations.edit', 'display_name' => 'Edit Operations', 'module' => 'operations', 'action' => 'edit'],
            
            // HR permissions
            ['name' => 'hr.view', 'display_name' => 'View HR', 'module' => 'hr', 'action' => 'view'],
            ['name' => 'hr.edit', 'display_name' => 'Edit HR', 'module' => 'hr', 'action' => 'edit'],
            
            // Communications permissions
            ['name' => 'communications.view', 'display_name' => 'View Communications', 'module' => 'communications', 'action' => 'view'],
            ['name' => 'communications.send', 'display_name' => 'Send Communications', 'module' => 'communications', 'action' => 'send'],
            
            // Settings permissions
            ['name' => 'settings.view', 'display_name' => 'View Settings', 'module' => 'settings', 'action' => 'view'],
            ['name' => 'settings.edit', 'display_name' => 'Edit Settings', 'module' => 'settings', 'action' => 'edit'],
            
            // User management permissions
            ['name' => 'users.view', 'display_name' => 'View Users', 'module' => 'users', 'action' => 'view'],
            ['name' => 'users.create', 'display_name' => 'Create Users', 'module' => 'users', 'action' => 'create'],
            ['name' => 'users.edit', 'display_name' => 'Edit Users', 'module' => 'users', 'action' => 'edit'],
            ['name' => 'users.delete', 'display_name' => 'Delete Users', 'module' => 'users', 'action' => 'delete'],
            
            // Organization permissions
            ['name' => 'organizations.view', 'display_name' => 'View Organizations', 'module' => 'organizations', 'action' => 'view'],
            ['name' => 'organizations.create', 'display_name' => 'Create Organizations', 'module' => 'organizations', 'action' => 'create'],
            ['name' => 'organizations.edit', 'display_name' => 'Edit Organizations', 'module' => 'organizations', 'action' => 'edit'],
            ['name' => 'organizations.delete', 'display_name' => 'Delete Organizations', 'module' => 'organizations', 'action' => 'delete'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission['name']], $permission);
        }

        // Create Roles
        $roles = [
            [
                'name' => 'owner',
                'display_name' => 'Owner',
                'description' => 'Full access to all strategic, operational, and financial data',
                'menu_access' => ['dashboard', 'schools', 'academics', 'operations', 'finance', 'hr', 'communications', 'digital_learning', 'settings', 'reports'],
                'permissions' => Permission::all()->pluck('name')->toArray()
            ],
            [
                'name' => 'group_accountant',
                'display_name' => 'Group Accountant',
                'description' => 'Access to finance-related dashboards and reports only',
                'menu_access' => ['dashboard', 'finance', 'reports'],
                'permissions' => ['dashboard.view', 'finance.view', 'finance.export']
            ],
            [
                'name' => 'group_it_officer',
                'display_name' => 'Group IT Officer',
                'description' => 'Access to system usage and technical dashboards only',
                'menu_access' => ['dashboard', 'settings', 'reports'],
                'permissions' => ['dashboard.view', 'settings.view', 'users.view']
            ],
            [
                'name' => 'central_super_admin',
                'display_name' => 'Central Super Admin',
                'description' => 'Manages users and schools within the group',
                'menu_access' => ['dashboard', 'schools', 'settings', 'users'],
                'permissions' => ['dashboard.view', 'schools.view', 'schools.create', 'schools.edit', 'settings.view', 'settings.edit', 'users.view', 'users.create', 'users.edit', 'users.delete']
            ],
            [
                'name' => 'group_academic',
                'display_name' => 'Group Academic',
                'description' => 'Manages academic related dashboards and reports only',
                'menu_access' => ['dashboard', 'academics', 'reports'],
                'permissions' => ['dashboard.view', 'academics.view', 'academics.edit', 'academics.export']
            ],
        ];

        foreach ($roles as $roleData) {
            $permissions = $roleData['permissions'];
            unset($roleData['permissions']);
            
            $role = Role::firstOrCreate(['name' => $roleData['name']], $roleData);
            
            // Attach permissions
            $permissionIds = Permission::whereIn('name', $permissions)->pluck('id');
            $role->permissions()->sync($permissionIds);
        }
    }
}
