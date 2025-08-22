<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\School;
use App\Models\Organization;
use App\Models\DemoRequest;

class SettingsController extends Controller
{
    /**
     * Main Settings Dashboard
     */
    public function index()
    {
        $data = [
            'total_users' => $this->getTotalUsers(),
            'total_schools' => $this->getTotalSchools(),
            'active_sessions' => $this->getActiveSessions(),
            'pending_approvals' => $this->getPendingApprovals(),
            
            'system_status' => $this->getSystemStatus(),
        ];

        return view('settings.index', compact('data'));
    }

    /**
     * Academic Year Management
     */
    public function academicYears()
    {
        $academicYears = DB::select("
            SELECT ay.*, u.name as school_name, u.username as school_code 
            FROM shulesoft.academic_year ay
            LEFT JOIN shulesoft.user u ON ay.uid = u.uid
            ORDER BY ay.id DESC
        ");

        return view('settings.academic-years', compact('academicYears'));
    }

    public function storeAcademicYear(Request $request)
    {
        $request->validate([
            'year_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'schools' => 'array'
        ]);

        $schoolUids = $request->schools ?? $this->getAllSchoolUids();

        foreach ($schoolUids as $uid) {
            DB::insert("
                INSERT INTO shulesoft.academic_year (uid, year_name, start_date, end_date, status, created_at)
                VALUES (?, ?, ?, ?, 'active', NOW())
            ", [$uid, $request->year_name, $request->start_date, $request->end_date]);
        }

        return redirect()->back()->with('success', 'Academic year created successfully for selected schools.');
    }

    /**
     * User Management
     * 
     * strongly to be checked its logic
     */
    public function users()
    {
        $users = DB::select("
            SELECT u.*,
               (SELECT STRING_AGG(DISTINCT sch.sname, ', ')
                FROM shulesoft.setting sch
                JOIN shulesoft.connect_schools cs ON cs.school_setting_uid = sch.uid
                WHERE cs.connect_organization_id = u.connect_organization_id and cs.connect_user_id=u.id) as assigned_school_names
            FROM shulesoft.connect_users u
            WHERE u.connect_organization_id = ?
            ORDER BY u.created_at DESC
        ", [Auth::user()->connect_organization_id]);

        $user = Auth::user();
        $schools =$user->schools()->active()->get();
        $roles = DB::select("SELECT * FROM shulesoft.connect_roles ORDER BY name");

        return view('settings.users', compact('users', 'schools', 'roles'));
    }

    public function storeUser(Request $request)
    {
      $valid =  $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:connect_users',
            'role_id' => 'required|exists:connect_roles,id',
            'assigned_schools' => 'array'
        ]);
        
        $pass = substr(str_shuffle('23456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz'), 0, 8);
        $defaultPassword = Hash::make($pass); // Replace with the actual default password logic if needed

        // Check for duplicate email in the database
        $existingUser = DB::selectOne("
            SELECT id FROM shulesoft.connect_users 
            WHERE email = ? AND connect_organization_id = ?
        ", [$request->email, Auth::user()->connect_organization_id]);

        if ($existingUser) {
            return redirect()->back()->with('error', 'A user with this email already exists.');
        }

        // Insert new user into the database
        DB::insert("
            INSERT INTO shulesoft.connect_users (name, email, password, role_id, assigned_schools, connect_organization_id, status, created_at)
            VALUES (?, ?, ?, ?, ?, ?, 'pending', NOW())
        ", [
            $request->name,  
            $request->email,
            $defaultPassword,
            $request->role_id,
            implode(',', $request->assigned_schools ?? []),
            Auth::user()->connect_organization_id
        ]);
        if (!empty($request->assigned_schools)) {
            foreach ($request->assigned_schools as $school_setting_uid) {
            DB::statement("
                INSERT INTO shulesoft.connect_schools (
                school_setting_uid,
                connect_organization_id,
                connect_user_id,
                is_active,
                shulesoft_code,
                settings,
                created_by,
                created_at
                ) VALUES (?, ?, ?, true, ?, ?, ?, NOW())
                ON CONFLICT (school_setting_uid, connect_organization_id, connect_user_id) DO NOTHING
            ", [
                $school_setting_uid, // school_setting_uid, set as needed
                Auth::user()->connect_organization_id,
                Auth::user()->id,
                null, // shulesoft_code, set as needed
                json_encode([]), // settings, default empty
                Auth::user()->id
            ]);
            }
        }

        // Prepare the message content
        $loginUrl = url('/login');
       
        $allocatedSchool = implode(', ', $request->assigned_schools ?? []);
        $message = "Dear {$request->name},\n\n" .
            "You have been registered by " . Auth::user()->name . " and allocated to the school(s): {$allocatedSchool}.\n\n" .
            "Your login URL is: {$loginUrl}\n" .
            "Username: {$request->email}\n" .
            "Default Password: {$defaultPassword}\n\n" .
            "Please log in and change your password immediately.\n\n" .
            "Thank you,\nShuleSoft Team";

        // Insert the message into the shulesoft.sms table for both WhatsApp and SMS
        DB::insert("
            INSERT INTO shulesoft.sms (phone_number, body, sent_from, status, created_at)
            VALUES (?, ?, 'whatsapp', 0, NOW()), (?, ?, 'sms', 0, NOW())
        ", [
            $request->phone, $message,
            $request->phone, $message
        ]);

        return redirect()->back()->with('success', 'User created and invitation sent successfully.');
    }

    public function editUser($id)
    {
        try {
            // Get user data
            $user = DB::selectOne("
                SELECT * FROM shulesoft.connect_users 
                WHERE id = ? AND connect_organization_id = ?
            ", [$id, Auth::user()->connect_organization_id]);

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            // Get user's current schools
            $userSchools = !empty($user->assigned_schools) ? explode(',', $user->assigned_schools) : [];

            // Get available schools and roles
            $schools = DB::select("
                SELECT s.*, cs.school_name 
                FROM shulesoft.connect_schools cs
                LEFT JOIN shulesoft.setting s ON cs.school_setting_uid = s.uid
                WHERE cs.connect_organization_id = ? AND cs.is_active = 1
                ORDER BY COALESCE(cs.school_name, s.school_name)
            ", [Auth::user()->connect_organization_id]);

            $roles = DB::select("SELECT * FROM shulesoft.connect_roles ORDER BY name");

            // Generate HTML for the modal body
            $html = view('settings.partials.edit-user-form', compact('user', 'schools', 'roles', 'userSchools'))->render();

            return response()->json(['html' => $html]);
            
        } catch (\Exception $e) {
            \Log::error('Error in editUser: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to load user data. Please try again.'], 500);
        }
    }

    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'role_id' => 'required|exists:connect_roles,id',
            'assigned_schools' => 'array',
            'status' => 'required|in:active,inactive,pending'
        ]);

        // Verify user belongs to current organization
        $user = DB::selectOne("
            SELECT * FROM shulesoft.connect_users 
            WHERE id = ? AND connect_organization_id = ?
        ", [$id, Auth::user()->connect_organization_id]);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found or access denied.');
        }

        // Update user data
        DB::update("
            UPDATE shulesoft.connect_users 
            SET name = ?, email = ?, role_id = ?, assigned_schools = ?, status = ?, updated_at = NOW()
            WHERE id = ? AND connect_organization_id = ?
        ", [
            $request->name, 
            $request->email, 
            $request->role_id,
            implode(',', $request->assigned_schools ?? []),
            $request->status,
            $id,
            Auth::user()->connect_organization_id
        ]);

        // Update connect_schools table if schools changed
        if ($request->has('assigned_schools')) {
            // Remove existing school assignments
            DB::delete("
                DELETE FROM shulesoft.connect_schools 
                WHERE connect_user_id = ? AND connect_organization_id = ?
            ", [$id, Auth::user()->connect_organization_id]);

            // Add new assignments
            if (!empty($request->assigned_schools)) {
                foreach ($request->assigned_schools as $school_setting_uid) {
                    DB::insert("
                        INSERT INTO shulesoft.connect_schools (
                            school_setting_uid,
                            connect_organization_id,
                            connect_user_id,
                            is_active,
                            shulesoft_code,
                            settings,
                            created_by,
                            created_at
                        ) VALUES (?, ?, ?, true, ?, ?, ?, NOW())
                    ", [
                        $school_setting_uid,
                        Auth::user()->connect_organization_id,
                        $id,
                        null,
                        json_encode([]),
                        Auth::user()->id
                    ]);
                }
            }
        }

        // Send notification if requested
        if ($request->has('send_notification')) {
            $loginUrl = url('/login');
            $allocatedSchools = !empty($request->assigned_schools) ? 
                implode(', ', $request->assigned_schools) : 'None';
            
            $message = "Dear {$request->name},\n\n" .
                "Your account has been updated by " . Auth::user()->name . ".\n\n" .
                "Updated details:\n" .
                "Name: {$request->name}\n" .
                "Email: {$request->email}\n" .
                "Status: {$request->status}\n" .
                "Assigned Schools: {$allocatedSchools}\n\n" .
                "Login URL: {$loginUrl}\n\n" .
                "Thank you,\nShuleSoft Team";

            // Insert notification into sms table
            if (!empty($user->phone)) {
                DB::insert("
                    INSERT INTO shulesoft.sms (phone_number, body, sent_from, status, created_at)
                    VALUES (?, ?, 'email', 0, NOW()), (?, ?, 'sms', 0, NOW()), (?, ?, 'whatsapp', 0, NOW())
                ", [
                    $request->email, $message,
                    $user->phone, $message,
                    $user->phone, $message
                ]);
            } else {
                DB::insert("
                    INSERT INTO shulesoft.sms (phone_number, body, sent_from, status, created_at)
                    VALUES (?, ?, 'email', 0, NOW())
                ", [$request->email, $message]);
            }
        }

        return redirect()->back()->with('success', 'User updated successfully.');
    }

    public function deleteUser($id)
    {
        DB::delete("DELETE FROM shulesoft.connect_users WHERE id = ?", [$id]);
        return redirect()->back()->with('success', 'User deleted successfully.');
    }

    /**
     * School Management
     */
    public function schools()
    {
        $schools = DB::select("
            SELECT 
            cs.*, 
            (
                SELECT COUNT(*) 
                FROM shulesoft.student s 
                WHERE s.schema_name = st.schema_name
            ) as student_count,
            (
                SELECT SUM(p.amount) 
                FROM shulesoft.payments p 
                WHERE p.schema_name = st.schema_name
            ) as annual_revenue,
            (
                SELECT COUNT(*) 
                FROM shulesoft.users cu 
                WHERE cu.\"table\" in ('teacher','user') AND cu.status = 1 and cu.schema_name = st.schema_name
            ) as assigned_users,
             st.sname
            FROM shulesoft.connect_schools cs
            LEFT JOIN shulesoft.setting st ON cs.school_setting_uid = st.uid
            ORDER BY cs.school_setting_uid
        ");

        return view('settings.schools', compact('schools'));
    }

    public function validateLoginCode(Request $request)
    {
        $request->validate([
            'login_code' => 'required|string'
        ]);
        if($request->has('login_codes')) {
            $loginCodes = $request->input('login_codes');
            print_r($loginCodes);
            exit;
        }
        $loginCode = $request->login_code;

        // Check if the code exists in shulesoft.setting table
        $setting = DB::table('shulesoft.setting')
            ->where('login_code', $loginCode)
            ->first();

        if ($setting) {
            return response()->json(['valid' => true]);
        } else {
            return response()->json([
                'valid' => false,
                'message' => 'This code is not valid, school cannot be onboarded.'
            ]);
        }
    }
    public function storeSchool(Request $request)
    {
        if ($request->action_type === 'link_existing') {
            $validated = $request->validate([
                'school_code' => 'required|string'
            ]);
  // Check if the code exists in shulesoft.setting table
        $setting = \DB::table('shulesoft.setting')
            ->where('login_code', $validated['school_code'])
            ->first();

        if (!$setting) {
            return redirect()->back()
            ->with('error', 'Invalid code supplied.');
        }

        // Record information in shulesoft.connect_schools
        \DB::table('shulesoft.connect_schools')->insert([
            'school_setting_uid' => $setting->uid,
            'connect_organization_id' => Auth::user()->connect_organization_id,
            'connect_user_id' => Auth::id(),
            'is_active' => true,
            'shulesoft_code' => $validated['school_code'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
          

            return redirect()->back()->with('success', 'School linked successfully.');
        } else {
            $request->validate([
                'school_name' => 'required|string|max:255',
                'location' => 'required|string|max:255',
                'contact_person' => 'required|string|max:255',
                'contact_email' => 'required|email',
                'contact_phone' => 'required|string|max:20'
            ]);

            // Create school creation request
            DB::insert("
                INSERT INTO shulesoft.school_creation_requests 
                (school_name, location, contact_person, contact_email, contact_phone, connect_user_id, status, requested_at, created_at)
                VALUES (?, ?, ?, ?, ?, ?, 'pending', NOW(), NOW())
            ", [
                $request->school_name,
                $request->location,
                $request->contact_person,
                $request->contact_email,
                $request->contact_phone,
                Auth::user()->id
            ]);

            // Message to the contact person
            $contactMessage = "Dear {$request->contact_person},\n\n" .
                "A request has been submitted by " . Auth::user()->name . " to onboard your school '{$request->school_name}' to ShuleSoft for proper management. " .
                "Our team will contact you shortly to proceed with the onboarding process.\n\n" .
                "Thank you,\nShuleSoft Team";

            DB::insert("
                INSERT INTO shulesoft.sms (phone_number, body, sent_from, status, created_at)
                VALUES (?, ?, 'email', 0, NOW()), (?, ?, 'sms', 0, NOW()), (?, ?, 'whatsapp', 0, NOW())
            ", [
                $request->contact_email, $contactMessage,
                $request->contact_phone, $contactMessage,
                $request->contact_phone, $contactMessage
            ]);

            // Message to the applicant
            $applicantMessage = "Dear " . Auth::user()->name . ",\n\n" .
                "Your request to onboard the school '{$request->school_name}' has been submitted successfully. " .
                "Our team is working on it and will get back to you shortly.\n\n" .
                "Thank you,\nShuleSoft Team";

            DB::insert("
                INSERT INTO shulesoft.sms (phone_number, body, sent_from, status, created_at)
                VALUES (?, ?, 'email', 0, NOW()), (?, ?, 'sms', 0, NOW()), (?, ?, 'whatsapp', 0, NOW())
            ", [
                Auth::user()->email, $applicantMessage,
                Auth::user()->phone, $applicantMessage,
                Auth::user()->phone, $applicantMessage
            ]);

            // Message to ShuleSoft staff
            $staffMessage = "A new school onboarding request has been submitted:\n\n" .
                "School Name: {$request->school_name}\n" .
                "Location: {$request->location}\n" .
                "Contact Person: {$request->contact_person}\n" .
                "Contact Email: {$request->contact_email}\n" .
                "Contact Phone: {$request->contact_phone}\n" .
                "Organization: " . Auth::user()->organization->name . "\n" .
                "Requested By: " . Auth::user()->name . "\n\n" .
                "Please act quickly to engage the school, allow them to fill the required forms, and proceed with onboarding.";

            DB::insert("
                INSERT INTO shulesoft.sms (phone_number, body, sent_from, status, created_at)
                VALUES (?, ?, 'sms', 0, NOW()), (?, ?, 'whatsapp', 0, NOW())
            ", [
                '0714825469', $staffMessage,
                '0714825469', $staffMessage
            ]);

            return redirect()->back()->with('success', 'School creation request submitted successfully.');
        }
    }

    /**
     * Role & Permission Management
     */
    public function rolesPermissions()
    {
        $roles = DB::select("
            SELECT r.*, 
                   (SELECT COUNT(*) FROM shulesoft.connect_users u WHERE u.role_id = r.id) as user_count
            FROM shulesoft.connect_roles r
            ORDER BY r.name
        ");

        $permissions = DB::select("
            SELECT * FROM shulesoft.connect_permissions 
            ORDER BY module, name
        ");

        $rolePermissions = DB::select("
            SELECT rp.*, r.name as role_name, p.name as permission_name
            FROM shulesoft.connect_role_permissions rp
            JOIN shulesoft.connect_roles r ON rp.role_id = r.id
            JOIN shulesoft.connect_permissions p ON rp.permission_id = p.id
        ");

        return view('settings.roles-permissions', compact('roles', 'permissions', 'rolePermissions'));
    }

    public function storeRole(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:connect_roles',
            'description' => 'required|string',
            'permissions' => 'array'
        ]);

        $roleId = DB::getPdo()->lastInsertId();
        DB::insert("
            INSERT INTO shulesoft.connect_roles (name, description, created_at)
            VALUES (?, ?, NOW())
        ", [$request->name, $request->description]);

        $roleId = DB::getPdo()->lastInsertId();

        // Assign permissions
        if ($request->permissions) {
            foreach ($request->permissions as $permissionId) {
                DB::insert("
                    INSERT INTO shulesoft.connect_role_permissions (role_id, permission_id)
                    VALUES (?, ?)
                ", [$roleId, $permissionId]);
            }
        }

        return redirect()->back()->with('success', 'Role created successfully.');
    }

    /**
     * System Configuration
     */
    public function systemConfig()
    {
        $config = DB::selectOne("
            SELECT * FROM shulesoft.configurations 
            WHERE id = 1
        ") ?? (object)[
            'group_name' => 'ShuleSoft Group',
            'primary_color' => '#0d6efd',
            'secondary_color' => '#6c757d',
            'logo_url' => '',
            'notification_email' => '',
            'sms_provider' => '',
            'email_provider' => ''
        ];

        return view('settings.system-config', compact('config'));
    }

    public function updateSystemConfig(Request $request)
    {
        $request->validate([
            'group_name' => 'required|string|max:255',
            'primary_color' => 'required|string|max:7',
            'secondary_color' => 'required|string|max:7',
            'notification_email' => 'required|email'
        ]);

        DB::statement("
            INSERT INTO shulesoft.system_config (id, group_name, primary_color, secondary_color, logo_url, notification_email, sms_provider, email_provider, updated_at)
            VALUES (1, ?, ?, ?, ?, ?, ?, ?, NOW())
            ON DUPLICATE KEY UPDATE
            group_name = VALUES(group_name),
            primary_color = VALUES(primary_color),
            secondary_color = VALUES(secondary_color),
            logo_url = VALUES(logo_url),
            notification_email = VALUES(notification_email),
            sms_provider = VALUES(sms_provider),
            email_provider = VALUES(email_provider),
            updated_at = NOW()
        ", [
            $request->group_name,
            $request->primary_color,
            $request->secondary_color,
            $request->logo_url,
            $request->notification_email,
            $request->sms_provider,
            $request->email_provider
        ]);

        return redirect()->back()->with('success', 'System configuration updated successfully.');
    }

    /**
     * Audit Logs
     */
    public function auditLogs()
    {
        $logs = DB::select("
            SELECT al.*, u.name as user_name
            FROM shulesoft.log al
            LEFT JOIN shulesoft.connect_users u ON al.user_id = u.id
            ORDER BY al.created_at DESC
            LIMIT 1000
        ");

        return view('settings.audit-logs', compact('logs'));
    }

    /**
     * Bulk Operations
     */
    public function bulkOperations()
    {
        $schools = DB::select("
            SELECT uid, name, username as school_code 
            FROM shulesoft.user 
            WHERE usertype = 'school'
            ORDER BY name
        ");

        return view('settings.bulk-operations', compact('schools'));
    }

    public function processBulkOperation(Request $request)
    {
        $request->validate([
            'operation_type' => 'required|in:message,settings_update,policy_push',
            'target_schools' => 'required|array',
            'content' => 'required_if:operation_type,message,policy_push'
        ]);

        switch ($request->operation_type) {
            case 'message':
                $this->sendBulkMessage($request->target_schools, $request->content, $request->message_type ?? 'notification');
                break;
            case 'settings_update':
                $this->updateBulkSettings($request->target_schools, $request->settings ?? []);
                break;
            case 'policy_push':
                $this->pushBulkPolicy($request->target_schools, $request->content, $request->policy_type);
                break;
        }

        return redirect()->back()->with('success', 'Bulk operation completed successfully.');
    }

    /**
     * Handle Onboarding Wizard Form Submission
     */
    public function submitOnboarding(Request $request)
    {
        try {
            // Validate the incoming request
            $validated = $request->validate([
                'org_name' => 'required|string|max:255|regex:/^[a-zA-Z0-9\s.&\'-]+$/',
                'org_email' => 'required|email|max:255',
                'contact_name' => 'required|string|max:255|regex:/^[a-zA-Z\s.\'-]+$/',
                'contact_email' => 'required|email|max:255',
                'contact_phone' => 'required|string|regex:/^[\+]?[0-9\s\-\(\)]{10,}$/',
                'schools_count' => 'required|integer|min:2',
                'usage_status' => 'required|in:all,some,none',
                'password' => 'required|string|min:8|confirmed',
                
                // Dynamic school data validation
                // 'shulesoft_schools' => 'array',
                // 'shulesoft_schools.*.login_code' => 'string|max:50',
                // 'mixed_shulesoft_schools' => 'array',
                // 'mixed_shulesoft_schools.*.login_code' => 'string|max:50',
                // 'mixed_shulesoft_schools.*.school_name' => 'string|max:255',
                // 'non_shulesoft_schools' => 'array',
                // 'non_shulesoft_schools.*.school_name' => 'required_with:non_shulesoft_schools|string|max:255',
                // 'non_shulesoft_schools.*.location' => 'required_with:non_shulesoft_schools|string|max:255',
                // 'non_shulesoft_schools.*.contact_person' => 'required_with:non_shulesoft_schools|string|max:255',
                // 'non_shulesoft_schools.*.contact_email' => 'required_with:non_shulesoft_schools|email|max:255',
                // 'non_shulesoft_schools.*.contact_phone' => 'required_with:non_shulesoft_schools|string|max:20',
                // 'new_schools' => 'array',
                // 'new_schools.*.school_name' => 'required_with:new_schools|string|max:255',
                // 'new_schools.*.location' => 'required_with:new_schools|string|max:255',
                // 'new_schools.*.contact_person' => 'required_with:new_schools|string|max:255',
                // 'new_schools.*.contact_email' => 'required_with:new_schools|email|max:255',
                // 'new_schools.*.contact_phone' => 'required_with:new_schools|string|max:20',
            ]);

            DB::beginTransaction();

            // 1. Create the organization with existing table structure
            $organizationId = DB::table('shulesoft.connect_organizations')->insertGetId([
                'username' => strtolower(str_replace(' ', '_', $validated['org_name'])) . '_' . time(),
                'name' => $validated['org_name'],
                'description' => "Organization created via onboarding wizard. Contact: {$validated['contact_name']} ({$validated['contact_email']}). Schools: {$validated['schools_count']}. Usage Status: {$validated['usage_status']}",
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // 2. Create the main user account
            $defaultRole = DB::selectOne("SELECT id FROM shulesoft.connect_roles WHERE name = 'owner' LIMIT 1");
            $roleId = $defaultRole ? $defaultRole->id : 1;

            $userId = DB::table('shulesoft.connect_users')->insertGetId([
                'name' => $validated['contact_name'],
                'email' => $validated['contact_email'],
                'password' => Hash::make($validated['password']),
                'phone' => $validated['contact_phone'],
                'role_id' => $roleId,
                'connect_organization_id' => $organizationId,
                'status' => 'active',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // 3. Process schools based on usage status
            $this->processSchoolsByUsageStatus($validated, $organizationId, $userId);

            // 4. Send welcome notifications
            $this->sendOnboardingNotifications($validated, $organizationId);

            // 5. Log the onboarding activity (if log table exists)
            try {
                DB::insert("
                    INSERT INTO shulesoft.log (user_id, action, description, created_at)
                    VALUES (?, 'onboarding_completed', ?, NOW())
                ", [
                    $userId,
                    "Organization '{$validated['org_name']}' completed onboarding with {$validated['schools_count']} schools"
                ]);
            } catch (\Exception $e) {
                // Log table might not exist, continue without logging
                \Log::info('Could not log onboarding activity: ' . $e->getMessage());
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Account created successfully! Welcome to ShuleSoft Group Connect.',
                'redirect' => route('login') . '?email=' . urlencode($validated['contact_email'])
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . implode(', ', $e->validator->errors()->all())
            ], 422);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Onboarding error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during account creation. Please try again.'
            ], 500);
        }
    }

    /**
     * Process schools based on usage status
     */
    private function processSchoolsByUsageStatus($validated, $organizationId, $userId)
    {
        switch ($validated['usage_status']) {
            case 'all':
                // All schools use ShuleSoft - link existing schools
                if (!empty($validated['shulesoft_schools'])) {
                    foreach ($validated['shulesoft_schools'] as $school) {
                        $this->linkExistingSchool($school['login_code'], $organizationId, $userId);
                    }
                }
                break;

            case 'some':
                // Mixed environment
                if (!empty($validated['mixed_shulesoft_schools'])) {
                    foreach ($validated['mixed_shulesoft_schools'] as $school) {
                        if (!empty($school['login_code'])) {
                            $this->linkExistingSchool($school['login_code'], $organizationId, $userId);
                        } else {
                            $this->createSchoolRequest($school, $organizationId, $userId);
                        }
                    }
                }
                break;

            case 'none':
                // No schools use ShuleSoft - create requests for all
                if (!empty($validated['non_shulesoft_schools'])) {
                    foreach ($validated['non_shulesoft_schools'] as $school) {
                        $this->createSchoolRequest($school, $organizationId, $userId);
                    }
                }
                if (!empty($validated['new_schools'])) {
                    foreach ($validated['new_schools'] as $school) {
                        $this->createSchoolRequest($school, $organizationId, $userId);
                    }
                }
                break;
        }
    }

    /**
     * Link an existing ShuleSoft school
     */
    private function linkExistingSchool($loginCode, $organizationId, $userId)
    {
        // Check if the code exists in shulesoft.setting table
        $setting = DB::table('shulesoft.setting')
            ->where('login_code', $loginCode)
            ->first();

        if ($setting) {
            // Record information in shulesoft.connect_schools
            DB::table('shulesoft.connect_schools')->insert([
                'school_setting_uid' => $setting->uid,
                'connect_organization_id' => $organizationId,
                'connect_user_id' => $userId,
                'is_active' => true,
                'shulesoft_code' => $loginCode,
                'created_by' => $userId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            // Log invalid code but don't fail the whole process
            \Log::warning("Invalid school login code provided during onboarding: {$loginCode}");
        }
    }

    /**
     * Create a school onboarding request
     */
    private function createSchoolRequest($schoolData, $organizationId, $userId)
    {
        DB::insert("
            INSERT INTO shulesoft.school_creation_requests 
            (school_name, location, contact_person, contact_email, contact_phone, connect_user_id, status, requested_at, created_at)
            VALUES (?, ?, ?, ?, ?, ?, 'pending', NOW(), NOW())
        ", [
            $schoolData['school_name'] ?? '',
            $schoolData['location'] ?? '',
            $schoolData['contact_person'] ?? '',
            $schoolData['contact_email'] ?? '',
            $schoolData['contact_phone'] ?? '',
            $userId
        ]);
    }

    /**
     * Send onboarding notifications
     */
    private function sendOnboardingNotifications($validated, $organizationId)
    {
        // Welcome message to the organization
        $welcomeMessage = "Dear {$validated['contact_name']},\n\n" .
            "Welcome to ShuleSoft Group Connect! Your organization '{$validated['org_name']}' has been successfully registered.\n\n" .
            "Your 30-day trial has started. You can now:\n" .
            "• Manage your school network\n" .
            "• Add team members\n" .
            "• Configure school settings\n" .
            "• Access comprehensive reports\n\n" .
            "Login URL: " . url('/login') . "\n" .
            "Email: {$validated['contact_email']}\n\n" .
            "Need help? Contact our support team.\n\n" .
            "Thank you,\nShuleSoft Team";

        DB::insert("
            INSERT INTO shulesoft.sms (phone_number, body, sent_from, status, created_at)
            VALUES (?, ?, 'email', 0, NOW()), (?, ?, 'sms', 0, NOW()), (?, ?, 'whatsapp', 0, NOW())
        ", [
            $validated['contact_email'], $welcomeMessage,
            $validated['contact_phone'], $welcomeMessage,
            $validated['contact_phone'], $welcomeMessage
        ]);

        // Notification to ShuleSoft team
        $staffMessage = "New organization onboarded:\n\n" .
            "Organization: {$validated['org_name']}\n" .
            "Contact: {$validated['contact_name']}\n" .
            "Email: {$validated['contact_email']}\n" .
            "Phone: {$validated['contact_phone']}\n" .
            "Schools: {$validated['schools_count']}\n" .
            "Usage Status: {$validated['usage_status']}\n" .
            "Trial Period: 30 days\n\n" .
            "Please follow up for onboarding support.";

        DB::insert("
            INSERT INTO shulesoft.sms (phone_number, body, sent_from, status, created_at)
            VALUES (?, ?, 'sms', 0, NOW()), (?, ?, 'whatsapp', 0, NOW())
        ", [
            '0714825469', $staffMessage,
            '0714825469', $staffMessage
        ]);
    }

    /**
     * Helper Methods
     */
    private function getTotalUsers()
    {
        return DB::selectOne("SELECT COUNT(*) as count FROM shulesoft.connect_users where connect_organization_id=".Auth::user()->connect_organization_id)->count;
    }

    private function getTotalSchools()
    {
        return DB::selectOne("SELECT COUNT(*) as count FROM shulesoft.connect_schools WHERE connect_organization_id=".Auth::user()->connect_organization_id)->count;
    }

    private function getActiveSessions()
    {
        return DB::selectOne("
            SELECT COUNT(*) as count 
            FROM shulesoft.sessions 
            WHERE last_activity > (EXTRACT(EPOCH FROM NOW()) - 1800)
        ")->count ?? 0;
    }

    private function getPendingApprovals()
    {
        $pending = 0;
       // $pending += DB::selectOne("SELECT COUNT(*) as count FROM shulesoft.school_creation_requests WHERE status = 'pending'")->count ?? 0;
        $pending += DB::selectOne("SELECT COUNT(*) as count FROM shulesoft.connect_users WHERE status = 'pending'")->count ?? 0;
        return $pending;
    }

  

    private function getSystemStatus()
    {
        return [
            'database' => 'healthy',
            'api_integration' => 'healthy',
            'notifications' => 'healthy',
            'backup_status' => 'healthy'
        ];
    }

    private function getAllSchoolUids()
    {
        return DB::select("SELECT uid FROM shulesoft.user WHERE usertype = 'school'");
    }


    private function sendBulkMessage($schools, $content, $type)
    {
        foreach ($schools as $schoolUid) {
            DB::insert("
                INSERT INTO shulesoft.notifications (school_uid, message, type, status, created_at)
                VALUES (?, ?, ?, 'sent', NOW())
            ", [$schoolUid, $content, $type]);
        }
    }

    private function updateBulkSettings($schools, $settings)
    {
        foreach ($schools as $schoolUid) {
            foreach ($settings as $key => $value) {
                DB::statement("
                    INSERT INTO shulesoft.school_settings (school_uid, setting_key, setting_value, updated_at)
                    VALUES (?, ?, ?, NOW())
                    ON DUPLICATE KEY UPDATE
                    setting_value = VALUES(setting_value),
                    updated_at = NOW()
                ", [$schoolUid, $key, $value]);
            }
        }
    }

    private function pushBulkPolicy($schools, $content, $type)
    {
        foreach ($schools as $schoolUid) {
            DB::insert("
                INSERT INTO shulesoft.policies (school_uid, policy_type, content, status, created_at)
                VALUES (?, ?, ?, 'active', NOW())
            ", [$schoolUid, $type, $content]);
        }
    }

    // ==================================================
    // NEW PAGE-BASED ONBOARDING SYSTEM
    // ==================================================

    /**
     * Start onboarding process - redirect to step 1
     */
    public function onboardingStart()
    {
        return redirect()->route('onboarding.step1');
    }

    /**
     * Step 1: Organization Information
     */
    public function onboardingStep1(Request $request)
    {
        // Get data from session if returning from next step
        $data = session('onboarding_data', []);
        return view('onboarding.step1', compact('data'));
    }

    public function saveStep1(Request $request)
    {
        $validated = $request->validate([
            'org_name' => 'required|string|max:255|regex:/^[a-zA-Z0-9\s.&\'-]+$/',
            'org_email' => 'required|email|max:255',
            'contact_name' => 'required|string|max:255|regex:/^[a-zA-Z\s.\'-]+$/',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|regex:/^[\+]?[0-9\s\-\(\)]{10,}$/',
        ]);

        // Store in session
        $onboardingData = session('onboarding_data', []);
        $onboardingData = array_merge($onboardingData, $validated);
        session(['onboarding_data' => $onboardingData]);

        return redirect()->route('onboarding.step2')->with('success', 'Organization information saved successfully!');
    }

    /**
     * Step 2: School Count & Usage
     */
    public function onboardingStep2(Request $request)
    {
        // Check if step 1 is completed
        $data = session('onboarding_data', []);
        if (!isset($data['org_name'])) {
            return redirect()->route('onboarding.step1')->with('error', 'Please complete organization information first.');
        }
        
        return view('onboarding.step2', compact('data'));
    }

    public function saveStep2(Request $request)
    {
        $validated = $request->validate([
            'schools_count' => 'required|integer|min:2',
            'usage_status' => 'required|in:all,some,none',
        ]);

        // Store in session
        $onboardingData = session('onboarding_data', []);
        $onboardingData = array_merge($onboardingData, $validated);
        session(['onboarding_data' => $onboardingData]);

        return redirect()->route('onboarding.step3')->with('success', 'School information saved successfully!');
    }

    /**
     * Step 3: School Details
     */
    public function onboardingStep3(Request $request)
    {
        // Check if previous steps are completed
        $data = session('onboarding_data', []);
        if (!isset($data['org_name']) || !isset($data['usage_status'])) {
            return redirect()->route('onboarding.step1')->with('error', 'Please complete previous steps first.');
        }
        
        return view('onboarding.step3', compact('data'));
    }

    public function saveStep3(Request $request)
    {
        // Get onboarding data from session
        $onboardingData = session('onboarding_data', []);
        $usageStatus = $onboardingData['usage_status'] ?? '';

        // Dynamic validation based on usage status
        $rules = [];
        if ($usageStatus === 'all') {
            $rules['shulesoft_schools'] = 'required|array|min:1';
            $rules['shulesoft_schools.*.login_code'] = 'required|string|max:50';
        } elseif ($usageStatus === 'some') {
            $rules['mixed_schools'] = 'required|array|min:1';
            $rules['mixed_schools.*.type'] = 'required|in:existing,new';
            $rules['mixed_schools.*.login_code'] = 'required_if:mixed_schools.*.type,existing|string|max:50';
            $rules['mixed_schools.*.school_name'] = 'required_if:mixed_schools.*.type,new|string|max:255';
            $rules['mixed_schools.*.location'] = 'required_if:mixed_schools.*.type,new|string|max:255';
            $rules['mixed_schools.*.contact_person'] = 'required_if:mixed_schools.*.type,new|string|max:255';
            $rules['mixed_schools.*.contact_email'] = 'required_if:mixed_schools.*.type,new|email|max:255';
            $rules['mixed_schools.*.contact_phone'] = 'required_if:mixed_schools.*.type,new|string|max:20';
        } else { // none
            $rules['new_schools'] = 'required|array|min:1';
            $rules['new_schools.*.school_name'] = 'required|string|max:255';
            $rules['new_schools.*.location'] = 'required|string|max:255';
            $rules['new_schools.*.contact_person'] = 'required|string|max:255';
            $rules['new_schools.*.contact_email'] = 'required|email|max:255';
            $rules['new_schools.*.contact_phone'] = 'required|string|max:20';
        }

        $validated = $request->validate($rules);

        // Store in session
        $onboardingData = array_merge($onboardingData, $validated);
        session(['onboarding_data' => $onboardingData]);

        return redirect()->route('onboarding.step4')->with('success', 'School details saved successfully!');
    }

    /**
     * Step 4: Account Setup
     */
    public function onboardingStep4(Request $request)
    {
        // Check if previous steps are completed
        $data = session('onboarding_data', []);
        if (!isset($data['org_name']) || !isset($data['usage_status'])) {
            return redirect()->route('onboarding.step1')->with('error', 'Please complete previous steps first.');
        }
        
        return view('onboarding.step4', compact('data'));
    }

    public function completeOnboarding(Request $request)
    {
        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Get all onboarding data from session
        $onboardingData = session('onboarding_data', []);
        $onboardingData = array_merge($onboardingData, $validated);

        try {
            DB::beginTransaction();

            // Create organization
            $organizationId = DB::table('shulesoft.connect_organizations')->insertGetId([
                'username' => strtolower(str_replace(' ', '_', $onboardingData['org_name'])) . '_' . time(),
                'name' => $onboardingData['org_name'],
                'description' => "Organization: {$onboardingData['org_name']}. Contact: {$onboardingData['contact_name']} ({$onboardingData['contact_email']}). Schools: {$onboardingData['schools_count']}. Usage: {$onboardingData['usage_status']}",
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Create main user
            $defaultRole = DB::selectOne("SELECT id FROM shulesoft.connect_roles WHERE name = 'Administrator' LIMIT 1");
            $roleId = $defaultRole ? $defaultRole->id : 1;

            $userId = DB::table('shulesoft.connect_users')->insertGetId([
                'name' => $onboardingData['contact_name'],
                'email' => $onboardingData['contact_email'],
                'password' => Hash::make($onboardingData['password']),
                'phone' => $onboardingData['contact_phone'],
                'role_id' => $roleId,
                'connect_organization_id' => $organizationId,
                'status' => 'active',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Process schools based on usage status
            $this->processSchoolsByUsageStatus($onboardingData, $organizationId, $userId);

            // Send notifications
            $this->sendOnboardingNotifications($onboardingData, $organizationId);

            DB::commit();

            // Clear session data
            session()->forget('onboarding_data');

            return redirect()->route('onboarding.success')->with('success', 'Account created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Onboarding error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred. Please try again.');
        }
    }

    /**
     * Success page
     */
    public function onboardingSuccess()
    {
        return view('onboarding.success');
    }

    /**
     * Store demo request
     */
    public function storeDemoRequest(Request $request)
    {
        try {
            \Log::info('Demo request received', $request->all());
            
            $validated = $request->validate([
                'organization_name' => 'required|string|max:255',
                'organization_contact' => 'required|string|max:255', 
                'contact_name' => 'required|string|max:255',
                'contact_phone' => 'required|string|max:20',
                'contact_email' => 'required|email|max:255',
                'organization_address' => 'required|string',
                'organization_country' => 'required|string|max:255',
                'total_schools' => 'required|integer|min:1'
            ]);

            \Log::info('Validation passed', $validated);

            // Create demo request
            $demoRequest = DemoRequest::create($validated);
            
            \Log::info('Demo request created', ['id' => $demoRequest->id]);
            
            // Generate approval token
            $token = $demoRequest->generateApprovalToken();

            \Log::info('Token generated', ['token' => $token]);

            // Send email to sales team
            $this->sendDemoRequestEmail($demoRequest, $token);

            \Log::info('Email sent successfully');

            return response()->json([
                'success' => true,
                'message' => 'Demo request submitted successfully! Our sales team will contact you soon.'
            ]);

        } catch (\Exception $e) {
            \Log::error('Demo request error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while submitting your request. Please try again.'
            ], 500);
        }
    }

    /**
     * Approve demo request
     */
    public function approveDemoRequest($token)
    {
        try {
            $demoRequest = DemoRequest::where('approval_token', $token)
                ->where('status', 'pending')
                ->firstOrFail();

            // Generate credentials
            $username = 'demo_' . strtolower(str_replace(' ', '_', $demoRequest->organization_name)) . '_' . rand(1000, 9999);
            $password = Str::random(12);

            // Create user account
            $user = User::create([
                'name' => $demoRequest->contact_name,
                'email' => $demoRequest->contact_email,
                'password' => Hash::make($password),
                'status' => 'active'
            ]);

            // Update demo request
            $demoRequest->update([
                'status' => 'approved',
                'approved_at' => now(),
                'credentials' => [
                    'username' => $username,
                    'password' => $password,
                    'user_id' => $user->id
                ]
            ]);

            // Send credentials to applicant
            $this->sendDemoCredentials($demoRequest, $username, $password);

            return redirect()->route('demo.approval.success')
                ->with('success', 'Demo request approved successfully! Credentials have been sent to the applicant.');

        } catch (\Exception $e) {
            return back()->with('error', 'An error occurred while approving the demo request.');
        }
    }

    /**
     * Send demo request notification email to sales team
     */
    private function sendDemoRequestEmail($demoRequest, $token)
    {
        $approvalUrl = route('demo.approve', ['token' => $token]);
        
        $emailData = [
            'organization_name' => $demoRequest->organization_name,
            'contact_name' => $demoRequest->contact_name,
            'contact_email' => $demoRequest->contact_email,
            'contact_phone' => $demoRequest->contact_phone,
            'organization_contact' => $demoRequest->organization_contact,
            'organization_address' => $demoRequest->organization_address,
            'organization_country' => $demoRequest->organization_country,
            'total_schools' => $demoRequest->total_schools,
            'approval_url' => $approvalUrl,
            'submitted_at' => $demoRequest->created_at->format('M d, Y H:i:s')
        ];

        Mail::send('emails.demo-request', $emailData, function ($message) {
            $message->to('sales@shulesoft.africa')
                ->subject('New Demo Request - ShuleSoft Group Connect');
        });
    }

    /**
     * Send demo credentials to applicant
     */
    private function sendDemoCredentials($demoRequest, $username, $password)
    {
        $emailData = [
            'contact_name' => $demoRequest->contact_name,
            'organization_name' => $demoRequest->organization_name,
            'username' => $username,
            'password' => $password,
            'login_url' => route('login')
        ];

        Mail::send('emails.demo-credentials', $emailData, function ($message) use ($demoRequest) {
            $message->to($demoRequest->contact_email)
                ->subject('Your ShuleSoft Group Connect Demo Access');
        });
    }

    /**
     * Demo approval success page
     */
    public function demoApprovalSuccess()
    {
        return view('demo.approval-success');
    }
}
