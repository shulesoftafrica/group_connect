<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organizations = [
            [
                'username' => 'shulesoft_group',
                'name' => 'ShuleSoft Education Group',
                'description' => 'Leading education management group with multiple schools across East Africa',
                'is_active' => true,
            ],
            [
                'username' => 'excellence_edu',
                'name' => 'Excellence Education Network',
                'description' => 'Premium education network focused on academic excellence and innovation',
                'is_active' => true,
            ],
            [
                'username' => 'bright_futures',
                'name' => 'Bright Futures Academy Group',
                'description' => 'Community-focused education group empowering rural and urban students',
                'is_active' => true,
            ],
        ];

        foreach ($organizations as $orgData) {
            Organization::firstOrCreate(['username' => $orgData['username']], $orgData);
        }
    }
}
