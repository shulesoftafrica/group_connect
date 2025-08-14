<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Organization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organization = Organization::where('username', 'shulesoft_group')->first();
        $ownerRole = Role::where('name', 'owner')->first();
        $adminRole = Role::where('name', 'central_super_admin')->first();
        $managerRole = Role::where('name', 'group_academic')->first();

        if (!$organization || !$ownerRole) {
            $this->command->error('Organization or roles not found. Please run organization and role seeders first.');
            return;
        }

        // Create owner user
        User::create([
            'name' => 'System Administrator',
            'email' => 'admin@shulesoft.com',
            'password' => Hash::make('password'),
            'role_id' => $ownerRole->id,
            'connect_organization_id' => $organization->id,
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Create admin users
        if ($adminRole) {
            User::create([
                'name' => 'Regional Admin',
                'email' => 'regional@shulesoft.com',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id,
                'connect_organization_id' => $organization->id,
                'status' => 'active',
                'email_verified_at' => now(),
            ]);
        }

        // Create manager users
        if ($managerRole) {
            User::create([
                'name' => 'School Manager',
                'email' => 'manager@shulesoft.com',
                'password' => Hash::make('password'),
                'role_id' => $managerRole->id,
                'connect_organization_id' => $organization->id,
                'status' => 'active',
                'email_verified_at' => now(),
            ]);
        }
    }
}
