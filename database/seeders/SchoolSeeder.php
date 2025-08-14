<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\User;
use App\Models\Organization;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $organization = Organization::where('username', 'shulesoft_group')->first();
        $adminUser = User::where('email', 'admin@shulesoft.com')->first();

        if (!$organization || !$adminUser) {
            $this->command->error('Organization or admin user not found. Please run organization and user seeders first.');
            return;
        }

        $schools = [
            [
                'shulesoft_code' => 'GVA001',
                'connect_organization_id' => $organization->id,
                'connect_user_id' => $adminUser->id,
                'created_by' => $adminUser->id,
                'is_active' => true,
                'settings' => [
                    'name' => 'Green Valley Academy',
                    'location' => 'Kampala Central',
                    'region' => 'Central',
                    'address' => 'Plot 123, Green Valley Road, Kampala',
                    'contact_phone' => '+256712345001',
                    'contact_email' => 'admin@greenvalley.sch.ug',
                    'principal_name' => 'Dr. Sarah Namugga',
                    'school_type' => 'Secondary',
                    'status' => 'active',
                    'total_students' => 856,
                    'fee_collection_percentage' => 96.8,
                    'academic_index' => 94.2,
                    'attendance_percentage' => 92.5,
                    'latitude' => 0.3476,
                    'longitude' => 32.5825,
                ],
            ],
            [
                'shulesoft_code' => 'SSS002',
                'connect_organization_id' => $organization->id,
                'connect_user_id' => $adminUser->id,
                'created_by' => $adminUser->id,
                'is_active' => true,
                'settings' => [
                    'name' => 'Sunrise Secondary School',
                    'location' => 'Jinja',
                    'region' => 'Eastern',
                    'address' => 'Jinja-Kamuli Road, Jinja',
                    'contact_phone' => '+256712345002',
                    'contact_email' => 'info@sunrise.sch.ug',
                    'principal_name' => 'Mr. Peter Okello',
                    'school_type' => 'Secondary',
                    'status' => 'active',
                    'total_students' => 634,
                    'fee_collection_percentage' => 89.2,
                    'academic_index' => 88.7,
                    'attendance_percentage' => 89.3,
                    'latitude' => 0.4372,
                    'longitude' => 33.2042,
                ],
            ],
            [
                'shulesoft_code' => 'MVP003',
                'connect_organization_id' => $organization->id,
                'connect_user_id' => $adminUser->id,
                'created_by' => $adminUser->id,
                'is_active' => true,
                'settings' => [
                    'name' => 'Mountain View Primary',
                    'location' => 'Mbale',
                    'region' => 'Eastern',
                    'address' => 'Mbale-Soroti Highway, Mbale',
                    'contact_phone' => '+256712345003',
                    'contact_email' => 'head@mountainview.sch.ug',
                    'principal_name' => 'Mrs. Grace Nakato',
                    'school_type' => 'Primary',
                    'status' => 'active',
                    'total_students' => 423,
                    'fee_collection_percentage' => 78.5,
                    'academic_index' => 82.1,
                    'attendance_percentage' => 85.7,
                    'latitude' => 1.0827,
                    'longitude' => 34.1753,
                ],
            ],
            [
                'shulesoft_code' => 'RSA004',
                'connect_organization_id' => $organization->id,
                'connect_user_id' => $adminUser->id,
                'created_by' => $adminUser->id,
                'is_active' => true,
                'settings' => [
                    'name' => 'Riverside Academy',
                    'location' => 'Entebbe',
                    'region' => 'Central',
                    'address' => 'Entebbe Road, Entebbe',
                    'contact_phone' => '+256712345004',
                    'contact_email' => 'admin@riverside.sch.ug',
                    'principal_name' => 'Dr. John Ssemakula',
                    'school_type' => 'Combined',
                    'status' => 'active',
                    'total_students' => 967,
                    'fee_collection_percentage' => 92.3,
                    'academic_index' => 90.8,
                    'attendance_percentage' => 91.2,
                    'latitude' => 0.0567,
                    'longitude' => 32.4664,
                ],
            ],
            [
                'shulesoft_code' => 'HLS005',
                'connect_organization_id' => $organization->id,
                'connect_user_id' => $adminUser->id,
                'created_by' => $adminUser->id,
                'is_active' => true,
                'settings' => [
                    'name' => 'Highland Secondary',
                    'location' => 'Mbarara',
                    'region' => 'Western',
                    'address' => 'Mbarara-Masaka Road, Mbarara',
                    'contact_phone' => '+256712345005',
                    'contact_email' => 'info@highland.sch.ug',
                    'principal_name' => 'Mrs. Betty Tumuhairwe',
                    'school_type' => 'Secondary',
                    'status' => 'active',
                    'total_students' => 542,
                    'fee_collection_percentage' => 85.7,
                    'academic_index' => 86.4,
                    'attendance_percentage' => 87.9,
                    'latitude' => -0.6115,
                    'longitude' => 30.6590,
                ],
            ],
            [
                'shulesoft_code' => 'UPS006',
                'connect_organization_id' => $organization->id,
                'connect_user_id' => $adminUser->id,
                'created_by' => $adminUser->id,
                'is_active' => true,
                'settings' => [
                    'name' => 'Unity Primary School',
                    'location' => 'Gulu',
                    'region' => 'Northern',
                    'address' => 'Gulu-Kitgum Road, Gulu',
                    'contact_phone' => '+256712345006',
                    'contact_email' => 'head@unity.sch.ug',
                    'principal_name' => 'Mr. David Okwera',
                    'school_type' => 'Primary',
                    'status' => 'active',
                    'total_students' => 389,
                    'fee_collection_percentage' => 72.1,
                    'academic_index' => 76.8,
                    'attendance_percentage' => 82.4,
                    'latitude' => 2.7823,
                    'longitude' => 32.2992,
                ],
            ]
        ];

        foreach ($schools as $schoolData) {
            School::create($schoolData);
        }
    }
}
