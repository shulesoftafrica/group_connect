<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
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
            "Default Password: {$pass}\n\n" .
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

        // Insert the message into the shulesoft.email table
        DB::insert("
            INSERT INTO shulesoft.email (body, subject, user_id, email, schema_name, created_at, status)
            VALUES (?, ?, ?, ?, ?, NOW(), 0)
        ", [
            $message, // body
            "Welcome to ShuleSoft", // subject
            Auth::user()->id, // user_id
            $request->email, // email
            'shulesoft' // schema_name
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
            \Log::info('Onboarding registration started', [
                'request_data' => $request->except(['password', 'password_confirmation']),
                'contact_email' => $request->input('contact_email'),
                'contact_phone' => $request->input('contact_phone'),
                'org_name' => $request->input('org_name')
            ]);
            
            // Pre-validation check to catch duplicates that Laravel validation might miss
            \Log::info('Starting pre-validation database checks', [
                'email_to_check' => $request->input('contact_email'),
                'phone_to_check' => $request->input('contact_phone'),
                'org_to_check' => $request->input('org_name')
            ]);
            
            // Check for existing email with detailed logging
            if ($request->filled('contact_email')) {
                $emailToCheck = $request->input('contact_email');
                \Log::info('Checking for duplicate email', ['email' => $emailToCheck]);
                
                $existingEmail = DB::selectOne("SELECT id, name, email FROM shulesoft.connect_users WHERE email = ?", [$emailToCheck]);
                
                if ($existingEmail) {
                    \Log::error('DUPLICATE EMAIL DETECTED - BLOCKING REGISTRATION', [
                        'attempted_email' => $emailToCheck,
                        'existing_user_id' => $existingEmail->id,
                        'existing_user_name' => $existingEmail->name,
                        'blocking_registration' => true
                    ]);
                    
                    return response()->json([
                        'success' => false,
                        'message' => "The email address '{$emailToCheck}' is already registered with another account (User: {$existingEmail->name}). Please use a different email address or contact support if this is your account.",
                        'field' => 'contact_email',
                        'error_type' => 'duplicate_email',
                        'existing_user' => $existingEmail->name
                    ], 422);
                } else {
                    \Log::info('Email check passed - no duplicate found', ['email' => $emailToCheck]);
                }
            }
            
            // Check for existing phone with detailed logging
            if ($request->filled('contact_phone')) {
                $phoneToCheck = $request->input('contact_phone');
                \Log::info('Checking for duplicate phone', ['phone' => $phoneToCheck]);
                
                $existingPhone = DB::selectOne("SELECT id, name, phone FROM shulesoft.connect_users WHERE phone = ?", [$phoneToCheck]);
                
                if ($existingPhone) {
                    \Log::error('DUPLICATE PHONE DETECTED - BLOCKING REGISTRATION', [
                        'attempted_phone' => $phoneToCheck,
                        'existing_user_id' => $existingPhone->id,
                        'existing_user_name' => $existingPhone->name,
                        'blocking_registration' => true
                    ]);
                    
                    return response()->json([
                        'success' => false,
                        'message' => "The phone number '{$phoneToCheck}' is already registered with another account (User: {$existingPhone->name}). Please use a different phone number or contact support if this is your account.",
                        'field' => 'contact_phone',
                        'error_type' => 'duplicate_phone',
                        'existing_user' => $existingPhone->name
                    ], 422);
                } else {
                    \Log::info('Phone check passed - no duplicate found', ['phone' => $phoneToCheck]);
                }
            }
            
            // Check for existing organization name
            if ($request->filled('org_name')) {
                $orgToCheck = $request->input('org_name');
                \Log::info('Checking for duplicate organization name', ['org_name' => $orgToCheck]);
                
                $existingOrg = DB::selectOne("SELECT id, name FROM shulesoft.connect_organizations WHERE LOWER(name) = LOWER(?)", [$orgToCheck]);
                
                if ($existingOrg) {
                    \Log::error('DUPLICATE ORGANIZATION NAME DETECTED - BLOCKING REGISTRATION', [
                        'attempted_org_name' => $orgToCheck,
                        'existing_org_id' => $existingOrg->id,
                        'blocking_registration' => true
                    ]);
                    
                    return response()->json([
                        'success' => false,
                        'message' => "An organization with the name '{$orgToCheck}' already exists. Please choose a different organization name.",
                        'field' => 'org_name',
                        'error_type' => 'duplicate_organization'
                    ], 422);
                } else {
                    \Log::info('Organization name check passed - no duplicate found', ['org_name' => $orgToCheck]);
                }
            }
            
            \Log::info('All pre-validation checks passed - proceeding with Laravel validation');
            
            // Validate the incoming request
            $validated = $request->validate([
                'org_name' => [
                    'required',
                    'string',
                    'max:255',
                    'regex:/^[a-zA-Z0-9\s.&\'-]+$/',
                    Rule::unique('shulesoft.connect_organizations', 'name')
                ],
                'org_email' => [
                    'required',
                    'email',
                    'max:255'
                ],
                'contact_name' => [
                    'required',
                    'string',
                    'max:255',
                    'regex:/^[a-zA-Z\s.\'-]+$/'
                ],
                'contact_email' => [
                    'required',
                    'email',
                    'max:255',
                    Rule::unique('shulesoft.connect_users', 'email')
                ],
                'contact_phone' => [
                    'required',
                    'string',
                    'regex:/^[\+]?[0-9\s\-\(\)]{10,}$/',
                    Rule::unique('shulesoft.connect_users', 'phone')
                ],
                'schools_count' => [
                    'required',
                    'integer',
                    'min:2'
                ],
                'usage_status' => [
                    'required',
                    'in:all,some,none'
                ],
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'confirmed'
                ]
            ], [
                'org_name.unique' => 'An organization with this name already exists.',
                'contact_email.unique' => 'A user with this email address already exists.',
                'contact_phone.unique' => 'A user with this phone number already exists.',
                'org_name.regex' => 'Organization name can only contain letters, numbers, spaces, dots, ampersands, hyphens, and apostrophes.',
                'contact_name.regex' => 'Contact name can only contain letters, spaces, dots, hyphens, and apostrophes.',
                'contact_phone.regex' => 'Please enter a valid phone number.',
                'schools_count.min' => 'You must manage at least 2 schools to use Group Connect.',
            ]);

            // Additional database validation with enhanced logging
            $validationErrors = $this->validateOnboardingData($validated);
            if (!empty($validationErrors)) {
                \Log::warning('Additional validation failed during onboarding', [
                    'errors' => $validationErrors,
                    'contact_email' => $validated['contact_email'],
                    'contact_phone' => $validated['contact_phone'],
                    'org_name' => $validated['org_name']
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed: ' . implode(', ', $validationErrors),
                    'validation_errors' => $validationErrors
                ], 422);
            }

            \Log::info('All validation passed, starting database transaction', [
                'contact_email' => $validated['contact_email'],
                'org_name' => $validated['org_name']
            ]);

            DB::beginTransaction();

            // 1. Generate unique username for organization
            $baseUsername = strtolower(str_replace([' ', '.', '&', "'", '-'], '_', $validated['org_name']));
            $baseUsername = preg_replace('/[^a-z0-9_]/', '', $baseUsername); // Remove any other special chars
            $baseUsername = preg_replace('/_+/', '_', $baseUsername); // Replace multiple underscores with single
            $baseUsername = trim($baseUsername, '_'); // Trim leading/trailing underscores
            
            $orgUsername = $this->generateUniqueOrgUsername($baseUsername);

            // 2. Create the organization with existing table structure
            $organizationId = DB::table('shulesoft.connect_organizations')->insertGetId([
                'username' => $orgUsername,
                'name' => $validated['org_name'],
                'description' => "Organization created via onboarding wizard. Contact: {$validated['contact_name']} ({$validated['contact_email']}). Schools: {$validated['schools_count']}. Usage Status: {$validated['usage_status']}",
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // 2. Create the main user account
            $defaultRole = DB::selectOne("SELECT id FROM shulesoft.connect_roles WHERE name = 'owner' LIMIT 1");
            $roleId = $defaultRole ? $defaultRole->id : 16;

            // FINAL FAIL-SAFE CHECK: Double-check for duplicates right before insertion
            \Log::info('Final fail-safe check before user insertion', [
                'email' => $validated['contact_email'],
                'phone' => $validated['contact_phone']
            ]);
            
            $finalEmailCheck = DB::selectOne("SELECT id, name FROM shulesoft.connect_users WHERE email = ?", [$validated['contact_email']]);
            if ($finalEmailCheck) {
                throw new \Exception("CRITICAL: Duplicate email detected in final check - {$validated['contact_email']} already exists for user {$finalEmailCheck->name} (ID: {$finalEmailCheck->id})");
            }
            
            $finalPhoneCheck = DB::selectOne("SELECT id, name FROM shulesoft.connect_users WHERE phone = ?", [$validated['contact_phone']]);
            if ($finalPhoneCheck) {
                throw new \Exception("CRITICAL: Duplicate phone detected in final check - {$validated['contact_phone']} already exists for user {$finalPhoneCheck->name} (ID: {$finalPhoneCheck->id})");
            }
            
            \Log::info('Final fail-safe check passed - proceeding with user insertion');

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
            try {
                $this->processSchoolsByUsageStatus($validated, $organizationId, $userId);
                \Log::info('Schools processed successfully for organization: ' . $organizationId);
            } catch (\Exception $e) {
                \Log::error('Error processing schools for organization ' . $organizationId . ': ' . $e->getMessage());
                // Continue - school processing issues shouldn't block account creation
            }

            // 4. Send welcome notifications
            try {
                $this->sendOnboardingNotifications($validated, $organizationId);
                \Log::info('Notifications sent successfully for organization: ' . $organizationId);
            } catch (\Exception $e) {
                \Log::error('Error sending notifications for organization ' . $organizationId . ': ' . $e->getMessage());
                // Continue - notification issues shouldn't block account creation
            }

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
            
            $errors = $e->validator->errors()->all();
            \Log::warning('Onboarding validation failed', [
                'errors' => $errors,
                'request_data' => $request->except(['password', 'password_confirmation'])
            ]);
            
            // Create more user-friendly error message
            $primaryError = $errors[0] ?? 'Validation failed';
            $additionalErrors = count($errors) > 1 ? ' (and ' . (count($errors) - 1) . ' other validation error' . (count($errors) > 2 ? 's' : '') . ')' : '';
            
            return response()->json([
                'success' => false,
                'message' => $primaryError . $additionalErrors,
                'errors' => $errors,
                'validation_fields' => array_keys($e->validator->errors()->toArray())
            ], 422);

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            \Log::error('Database error during onboarding: ' . $e->getMessage(), [
                'sql_state' => $e->getCode(),
                'error_info' => $e->errorInfo ?? null,
                'request_email' => $request->input('contact_email'),
                'request_phone' => $request->input('contact_phone'),
                'request_org' => $request->input('org_name')
            ]);
            
            $errorMessage = 'A database error occurred. Please try again.';
            
            // Handle specific database constraint violations with better pattern matching
            $exceptionMessage = $e->getMessage();
            
            if (str_contains($exceptionMessage, 'connect_users_email_unique') || 
                (str_contains($exceptionMessage, 'duplicate key') && str_contains($exceptionMessage, 'email'))) {
                
                // Extract the email from the error message if possible
                $email = $request->input('contact_email');
                $errorMessage = "The email address '{$email}' is already registered with another account. Please use a different email address or contact support if this is your account.";
                
            } elseif (str_contains($exceptionMessage, 'connect_users_phone_unique') || 
                      (str_contains($exceptionMessage, 'duplicate key') && str_contains($exceptionMessage, 'phone'))) {
                
                $phone = $request->input('contact_phone');
                $errorMessage = "The phone number '{$phone}' is already registered with another account. Please use a different phone number or contact support if this is your account.";
                
            } elseif (str_contains($exceptionMessage, 'connect_organizations_name_unique') || 
                      (str_contains($exceptionMessage, 'duplicate key') && str_contains($exceptionMessage, 'name'))) {
                
                $orgName = $request->input('org_name');
                $errorMessage = "An organization with the name '{$orgName}' already exists. Please choose a different organization name.";
                
            } elseif (str_contains($exceptionMessage, 'connect_organizations_username_unique') || 
                      (str_contains($exceptionMessage, 'duplicate key') && str_contains($exceptionMessage, 'username'))) {
                
                $errorMessage = 'There was a conflict with the organization username. Please try a different organization name or contact support.';
                
            } elseif (str_contains($exceptionMessage, 'foreign key constraint')) {
                $errorMessage = 'There was an error linking related data. Please contact support with error code: FK_CONSTRAINT.';
                
            } elseif (str_contains($exceptionMessage, 'column') && str_contains($exceptionMessage, 'cannot be null')) {
                $errorMessage = 'Required information is missing. Please ensure all required fields are completed and try again.';
                
            } elseif (str_contains($exceptionMessage, 'Data too long for column')) {
                $errorMessage = 'Some of the information provided is too long. Please shorten your input and try again.';
                
            } elseif (str_contains($exceptionMessage, 'SQLSTATE[23505]') || str_contains($exceptionMessage, 'duplicate key')) {
                $errorMessage = 'This information already exists in our system. Please check your email, phone number, or organization name and use different values.';
                
            } elseif (str_contains($exceptionMessage, 'SQLSTATE[42S02]')) {
                $errorMessage = 'Database structure issue detected. Please contact support with error code: TABLE_NOT_FOUND.';
                
            } elseif (str_contains($exceptionMessage, 'Connection refused') || 
                      str_contains($exceptionMessage, 'server has gone away')) {
                $errorMessage = 'Unable to connect to the database. Please try again in a few moments.';
            }
            
            // Add debug info in development
            if (config('app.debug') && config('app.env') !== 'production') {
                $errorMessage .= ' [SQL Error: ' . $e->getCode() . ' - ' . substr($exceptionMessage, 0, 200) . '...]';
            }

            return response()->json([
                'success' => false,
                'message' => $errorMessage,
                'error_code' => $e->getCode(),
                'constraint_violation' => str_contains($exceptionMessage, 'duplicate key') ? 'duplicate_entry' : null,
                'debug_info' => config('app.debug') ? [
                    'sql_state' => $e->getCode(),
                    'message' => substr($exceptionMessage, 0, 500),
                    'constraint' => $this->extractConstraintName($exceptionMessage)
                ] : null
            ], 422);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Onboarding error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Provide more detailed error messages based on exception type
            $errorMessage = 'An error occurred during account creation. Please try again.';
            
            // Check for specific error patterns and provide user-friendly messages
            if (str_contains($e->getMessage(), 'SQLSTATE[23505]')) {
                $errorMessage = 'This information already exists in our system. Please check your email, phone number, or organization name and try again.';
            } elseif (str_contains($e->getMessage(), 'SQLSTATE[23000]')) {
                $errorMessage = 'A data integrity error occurred. Please check that all required fields are filled correctly and try again.';
            } elseif (str_contains($e->getMessage(), 'Connection refused') || str_contains($e->getMessage(), 'could not connect')) {
                $errorMessage = 'Unable to connect to the database. Please try again in a few moments.';
            } elseif (str_contains($e->getMessage(), 'timeout')) {
                $errorMessage = 'The request timed out. Please try again.';
            } elseif (str_contains($e->getMessage(), 'Mail') || str_contains($e->getMessage(), 'SMTP')) {
                $errorMessage = 'Account created successfully, but there was an issue sending the welcome email. You can still log in normally.';
            } elseif (str_contains($e->getMessage(), 'Role') || str_contains($e->getMessage(), 'role_id')) {
                $errorMessage = 'There was an issue assigning user permissions. Please contact support.';
            }
            
            // In development mode, include more detailed error information
            if (config('app.debug') && config('app.env') !== 'production') {
                $errorMessage .= ' [Debug: ' . $e->getMessage() . ' in ' . basename($e->getFile()) . ':' . $e->getLine() . ']';
            }
            
            return response()->json([
                'success' => false,
                'message' => $errorMessage,
                'error_type' => get_class($e),
                'debug_info' => config('app.debug') ? [
                    'message' => $e->getMessage(),
                    'file' => basename($e->getFile()),
                    'line' => $e->getLine()
                ] : null
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
                \Log::info('Processing mixed school environment', [
                    'organization_id' => $organizationId,
                    'mixed_schools_count' => count($validated['mixed_schools'] ?? []),
                    'mixed_schools_data' => $validated['mixed_schools'] ?? []
                ]);
                
                if (!empty($validated['mixed_schools'])) {
                    foreach ($validated['mixed_schools'] as $index => $school) {
                        if ($school['type'] === 'existing' && !empty($school['login_code'])) {
                            \Log::info("Linking existing school via login code", [
                                'school_index' => $index,
                                'login_code' => $school['login_code'],
                                'organization_id' => $organizationId
                            ]);
                            $this->linkExistingSchool($school['login_code'], $organizationId, $userId);
                        } elseif ($school['type'] === 'new') {
                            \Log::info("Creating new school request", [
                                'school_index' => $index,
                                'school_name' => $school['school_name'] ?? 'Unknown',
                                'organization_id' => $organizationId
                            ]);
                            $this->createSchoolRequest($school, $organizationId, $userId);
                        }
                    }
                } else {
                    \Log::warning('Mixed schools array is empty', [
                        'organization_id' => $organizationId,
                        'validated_keys' => array_keys($validated)
                    ]);
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
        try {
            \Log::info('Creating school request', [
                'school_name' => $schoolData['school_name'] ?? 'Unknown',
                'organization_id' => $organizationId,
                'user_id' => $userId,
                'school_data' => $schoolData
            ]);

            // Get organization details for email
            $organization = DB::selectOne("SELECT name FROM shulesoft.connect_organizations WHERE id = ?", [$organizationId]);
            $organizationName = $organization ? $organization->name : 'Unknown Organization';

            $result = DB::insert("
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

            if ($result) {
                \Log::info('School request created successfully, sending notification email', [
                    'school_name' => $schoolData['school_name'] ?? 'Unknown',
                    'organization_id' => $organizationId,
                    'contact_email' => $schoolData['contact_email'] ?? ''
                ]);

                // Send notification email to the school contact
                try {
                    if (!empty($schoolData['contact_email'])) {
                        $emailData = [
                            'school_name' => $schoolData['school_name'] ?? '',
                            'location' => $schoolData['location'] ?? '',
                            'contact_person' => $schoolData['contact_person'] ?? '',
                            'contact_email' => $schoolData['contact_email'] ?? '',
                            'contact_phone' => $schoolData['contact_phone'] ?? '',
                            'organization_name' => $organizationName,
                            'request_id' => 'REQ-' . date('Ymd') . '-' . $organizationId . '-' . time()
                        ];

                        \Illuminate\Support\Facades\Mail::send('emails.school-registration-request', $emailData, function ($message) use ($schoolData) {
                            $message->to($schoolData['contact_email'], $schoolData['contact_person'] ?? 'School Administrator')
                                ->subject('School Registration Request - ShuleSoft Group Connect');
                        });

                        \Log::info('School registration notification email sent successfully', [
                            'school_name' => $schoolData['school_name'] ?? 'Unknown',
                            'contact_email' => $schoolData['contact_email'],
                            'organization_name' => $organizationName
                        ]);
                    } else {
                        \Log::warning('No contact email provided for school request - skipping notification', [
                            'school_name' => $schoolData['school_name'] ?? 'Unknown'
                        ]);
                    }
                } catch (\Exception $e) {
                    \Log::error('Failed to send school registration notification email', [
                        'error' => $e->getMessage(),
                        'school_name' => $schoolData['school_name'] ?? 'Unknown',
                        'contact_email' => $schoolData['contact_email'] ?? '',
                        'file' => $e->getFile(),
                        'line' => $e->getLine()
                    ]);
                    // Don't throw - email failure shouldn't break the registration process
                }
            }

            return $result;
        } catch (\Exception $e) {
            \Log::error('Failed to create school request', [
                'error' => $e->getMessage(),
                'school_data' => $schoolData,
                'organization_id' => $organizationId,
                'user_id' => $userId
            ]);
            // Don't throw - let the process continue
            return false;
        }
    }

    /**
     * Send onboarding notifications (Direct Email + SMS)
     */
    private function sendOnboardingNotifications($validated, $organizationId)
    {
        // Prepare email data for the welcome email
        $welcomeEmailData = [
            'contact_name' => $validated['contact_name'],
            'org_name' => $validated['org_name'],
            'contact_email' => $validated['contact_email'],
            'login_url' => url('/login'),
            'organization_id' => $organizationId
        ];

        // Send welcome email directly to the new user
        try {
            Mail::send('emails.welcome-registration', $welcomeEmailData, function ($message) use ($validated) {
                $message->to($validated['contact_email'], $validated['contact_name'])
                    ->subject('Welcome to ShuleSoft Group Connect - Registration Successful');
            });
            \Log::info('Welcome email sent successfully to: ' . $validated['contact_email']);
        } catch (\Exception $e) {
            \Log::error('Failed to send welcome email to ' . $validated['contact_email'] . ': ' . $e->getMessage(), [
                'email' => $validated['contact_email'],
                'error_type' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            // Continue with other notifications even if email fails
            // Note: The main registration process will still succeed
        }

        // SMS welcome message to the organization
        $smsWelcomeMessage = "Dear {$validated['contact_name']}, Welcome to ShuleSoft Group Connect! Your organization '{$validated['org_name']}' has been successfully registered. Your 30-day trial has started. Login: " . url('/login') . " Email: {$validated['contact_email']}. Need help? Contact our support team. Thank you, ShuleSoft Team";

        // Send SMS/WhatsApp to the user
        DB::insert("
            INSERT INTO shulesoft.sms (phone_number, body, sent_from, status, created_at)
            VALUES (?, ?, 'sms', 0, NOW()), (?, ?, 'whatsapp', 0, NOW())
        ", [
            $validated['contact_phone'], $smsWelcomeMessage,
            $validated['contact_phone'], $smsWelcomeMessage
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

        // Send SMS/WhatsApp to ShuleSoft team
        DB::insert("
            INSERT INTO shulesoft.sms (phone_number, body, sent_from, status, created_at)
            VALUES (?, ?, 'sms', 0, NOW()), (?, ?, 'whatsapp', 0, NOW())
        ", [
            '0714825469', $staffMessage,
            '0714825469', $staffMessage
        ]);

        // Send email notifications to ShuleSoft team (queued via database)
        DB::insert("
            INSERT INTO shulesoft.email (body, subject, user_id, email, schema_name, created_at, status)
            VALUES (?, ?, ?, ?, ?, NOW(), 0), (?, ?, ?, ?, ?, NOW(), 0)
        ", [
            $staffMessage, // body
            "New Organization Registration - {$validated['org_name']}", // subject
            null, // user_id
            'admin@shulesoft.africa', // admin email
            'shulesoft', // schema_name
            $staffMessage, // body (copy for sales)
            "New Organization Registration - {$validated['org_name']}", // subject
            null, // user_id
            'sales@shulesoft.africa', // sales email
            'shulesoft' // schema_name
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

    /**
     * Generate unique organization username
     */
    private function generateUniqueOrgUsername($baseUsername)
    {
        $username = $baseUsername;
        $counter = 1;

        // Check if base username exists
        while (DB::selectOne("SELECT id FROM shulesoft.connect_organizations WHERE username = ?", [$username])) {
            $username = $baseUsername . '_' . $counter;
            $counter++;
            
            // Prevent infinite loop
            if ($counter > 1000) {
                $username = $baseUsername . '_' . time() . '_' . mt_rand(1000, 9999);
                break;
            }
        }

        return $username;
    }

    /**
     * Additional validation for onboarding data
     */
    private function validateOnboardingData($validated)
    {
        $errors = [];

        // Check for existing organization name (case-insensitive)
        $existingOrg = DB::selectOne("SELECT id FROM shulesoft.connect_organizations WHERE LOWER(name) = LOWER(?)", [$validated['org_name']]);
        if ($existingOrg) {
            $errors[] = 'An organization with this name already exists.';
        }

        // Check for existing user email
        $existingUserEmail = DB::selectOne("SELECT id FROM shulesoft.connect_users WHERE email = ?", [$validated['contact_email']]);
        if ($existingUserEmail) {
            $errors[] = 'A user with this email address already exists.';
        }

        // Check for existing user phone
        $existingUserPhone = DB::selectOne("SELECT id FROM shulesoft.connect_users WHERE phone = ?", [$validated['contact_phone']]);
        if ($existingUserPhone) {
            $errors[] = 'A user with this phone number already exists.';
        }

        return $errors;
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

        \Log::info('Step 3 validation starting', [
            'usage_status' => $usageStatus,
            'session_data_keys' => array_keys($onboardingData),
            'request_data_keys' => array_keys($request->all())
        ]);

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
            $rules['mixed_schools.*.contact_phone'] = 'required_if:mixed_schools.*.type,new|string|max:20|regex:/^[\+]?[0-9\-\(\)\s]+$/';
        } else { // none
            $rules['new_schools'] = 'required|array|min:1';
            $rules['new_schools.*.school_name'] = 'required|string|max:255';
            $rules['new_schools.*.location'] = 'required|string|max:255';
            $rules['new_schools.*.contact_person'] = 'required|string|max:255';
            $rules['new_schools.*.contact_email'] = 'required|email|max:255';
            $rules['new_schools.*.contact_phone'] = 'required|string|max:20|regex:/^[\+]?[0-9\-\(\)\s]+$/';
        }

        $validated = $request->validate($rules);

        // Enhanced validation checks
        $validationErrors = $this->performEnhancedSchoolValidation($validated, $usageStatus);
        if (!empty($validationErrors)) {
            \Log::warning('Step 3 enhanced validation failed', [
                'usage_status' => $usageStatus,
                'errors' => $validationErrors
            ]);
            return back()->withErrors($validationErrors)->withInput();
        }

        // Log the validated data for debugging
        \Log::info('Step 3 validation completed successfully', [
            'usage_status' => $usageStatus,
            'validated_keys' => array_keys($validated),
            'school_count' => $this->getSchoolCount($validated, $usageStatus),
            'school_data' => $usageStatus === 'some' ? ($validated['mixed_schools'] ?? []) : 
                            ($usageStatus === 'all' ? ($validated['shulesoft_schools'] ?? []) : 
                            ($validated['new_schools'] ?? []))
        ]);

        // Store in session
        $onboardingData = array_merge($onboardingData, $validated);
        session(['onboarding_data' => $onboardingData]);

        return redirect()->route('onboarding.step4')->with('success', 'School details saved successfully!');
    }

    /**
     * Perform enhanced validation for school data
     */
    private function performEnhancedSchoolValidation($validated, $usageStatus)
    {
        $errors = [];
        
        // Get school data based on usage status
        $schools = [];
        if ($usageStatus === 'all') {
            $schools = $validated['shulesoft_schools'] ?? [];
        } elseif ($usageStatus === 'some') {
            $schools = $validated['mixed_schools'] ?? [];
        } else {
            $schools = $validated['new_schools'] ?? [];
        }

        // Track login codes and emails to check for duplicates
        $loginCodes = [];
        $emails = [];
        $schoolNames = [];

        foreach ($schools as $index => $school) {
            // Check for duplicate login codes within the submission
            if (isset($school['login_code']) && !empty($school['login_code'])) {
                if (in_array($school['login_code'], $loginCodes)) {
                    $errors[] = "Duplicate login code '{$school['login_code']}' found in your submission.";
                } else {
                    $loginCodes[] = $school['login_code'];
                }
            }

            // Check for duplicate email addresses within the submission
            if (isset($school['contact_email']) && !empty($school['contact_email'])) {
                if (in_array(strtolower($school['contact_email']), $emails)) {
                    $errors[] = "Duplicate email address '{$school['contact_email']}' found in your submission.";
                } else {
                    $emails[] = strtolower($school['contact_email']);
                }
            }

            // Check for duplicate school names within the submission
            if (isset($school['school_name']) && !empty($school['school_name'])) {
                $schoolNameLower = strtolower(trim($school['school_name']));
                if (in_array($schoolNameLower, $schoolNames)) {
                    $errors[] = "Duplicate school name '{$school['school_name']}' found in your submission.";
                } else {
                    $schoolNames[] = $schoolNameLower;
                }
            }

            // Validate phone number format for new schools
            if (isset($school['contact_phone']) && !empty($school['contact_phone'])) {
                if (!preg_match('/^[\+]?[0-9\-\(\)\s]+$/', $school['contact_phone'])) {
                    $schoolIdentifier = isset($school['school_name']) ? $school['school_name'] : 'school ' . ($index + 1);
                    $errors[] = "Invalid phone number format for '{$schoolIdentifier}'. Please use a valid phone number.";
                }
            }

            // Additional validation for school names (no special characters except common ones)
            if (isset($school['school_name']) && !empty($school['school_name'])) {
                if (!preg_match('/^[a-zA-Z0-9\s\-\.\&\']+$/', $school['school_name'])) {
                    $errors[] = "School name '{$school['school_name']}' contains invalid characters. Please use only letters, numbers, spaces, hyphens, periods, ampersands, and apostrophes.";
                }
            }
        }

        // Validate minimum requirements
        if (empty($schools)) {
            $errors[] = 'At least one school must be provided.';
        }

        // Check if we have at least one school for each usage type
        if ($usageStatus === 'some') {
            $hasExisting = false;
            $hasNew = false;
            foreach ($schools as $school) {
                if (isset($school['type'])) {
                    if ($school['type'] === 'existing') $hasExisting = true;
                    if ($school['type'] === 'new') $hasNew = true;
                }
            }
            
            if (!$hasExisting && !$hasNew) {
                $errors[] = 'For mixed usage, you must provide at least one existing or new school.';
            }
        }

        return $errors;
    }

    /**
     * Get count of schools based on usage status
     */
    private function getSchoolCount($validated, $usageStatus)
    {
        if ($usageStatus === 'all') {
            return count($validated['shulesoft_schools'] ?? []);
        } elseif ($usageStatus === 'some') {
            return count($validated['mixed_schools'] ?? []);
        } else {
            return count($validated['new_schools'] ?? []);
        }
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
        try {
            $validated = $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);

            // Get all onboarding data from session
            $onboardingData = session('onboarding_data', []);
            $onboardingData = array_merge($onboardingData, $validated);

            \Log::info('Step-by-step onboarding completion started', [
                'session_data' => \Illuminate\Support\Arr::except($onboardingData, ['password', 'password_confirmation']),
                'contact_email' => $onboardingData['contact_email'] ?? null,
                'contact_phone' => $onboardingData['contact_phone'] ?? null,
                'org_name' => $onboardingData['org_name'] ?? null
            ]);

            // CRITICAL: Pre-validation checks for step-by-step onboarding
            if (isset($onboardingData['contact_email'])) {
                $emailToCheck = $onboardingData['contact_email'];
                \Log::info('Checking for duplicate email in step-by-step onboarding', ['email' => $emailToCheck]);
                
                $existingEmail = DB::selectOne("SELECT id, name, email FROM shulesoft.connect_users WHERE email = ?", [$emailToCheck]);
                if ($existingEmail) {
                    \Log::error('DUPLICATE EMAIL DETECTED IN STEP-BY-STEP ONBOARDING - BLOCKING', [
                        'attempted_email' => $emailToCheck,
                        'existing_user_id' => $existingEmail->id,
                        'existing_user_name' => $existingEmail->name,
                        'blocking_registration' => true
                    ]);
                    
                    // Clear session data since registration failed
                    session()->forget('onboarding_data');
                    
                    return redirect()->route('onboarding.step1')->with('error', 
                        "The email address '{$emailToCheck}' is already registered with another account. Please use a different email address or contact support if this is your account."
                    );
                }
            }

            if (isset($onboardingData['contact_phone'])) {
                $phoneToCheck = $onboardingData['contact_phone'];
                \Log::info('Checking for duplicate phone in step-by-step onboarding', ['phone' => $phoneToCheck]);
                
                $existingPhone = DB::selectOne("SELECT id, name, phone FROM shulesoft.connect_users WHERE phone = ?", [$phoneToCheck]);
                if ($existingPhone) {
                    \Log::error('DUPLICATE PHONE DETECTED IN STEP-BY-STEP ONBOARDING - BLOCKING', [
                        'attempted_phone' => $phoneToCheck,
                        'existing_user_id' => $existingPhone->id,
                        'existing_user_name' => $existingPhone->name,
                        'blocking_registration' => true
                    ]);
                    
                    // Clear session data since registration failed
                    session()->forget('onboarding_data');
                    
                    return redirect()->route('onboarding.step1')->with('error', 
                        "The phone number '{$phoneToCheck}' is already registered with another account (User: {$existingPhone->name}). Please use a different phone number or contact support if this is your account."
                    );
                }
            }

            if (isset($onboardingData['org_name'])) {
                $orgToCheck = $onboardingData['org_name'];
                \Log::info('Checking for duplicate organization in step-by-step onboarding', ['org_name' => $orgToCheck]);
                
                $existingOrg = DB::selectOne("SELECT id, name FROM shulesoft.connect_organizations WHERE LOWER(name) = LOWER(?)", [$orgToCheck]);
                if ($existingOrg) {
                    \Log::error('DUPLICATE ORGANIZATION DETECTED IN STEP-BY-STEP ONBOARDING - BLOCKING', [
                        'attempted_org_name' => $orgToCheck,
                        'existing_org_id' => $existingOrg->id,
                        'blocking_registration' => true
                    ]);
                    
                    // Clear session data since registration failed
                    session()->forget('onboarding_data');
                    
                    return redirect()->route('onboarding.step1')->with('error', 
                        "An organization with the name '{$orgToCheck}' already exists. Please choose a different organization name."
                    );
                }
            }

            \Log::info('All validation checks passed in step-by-step onboarding - proceeding with creation');

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
            $defaultRole = DB::selectOne("SELECT id FROM shulesoft.connect_roles WHERE name = 'owner' LIMIT 1");
            $roleId = $defaultRole ? $defaultRole->id : 16;

            // FINAL FAIL-SAFE CHECK for step-by-step onboarding
            \Log::info('Final fail-safe check in step-by-step onboarding before user insertion', [
                'email' => $onboardingData['contact_email'],
                'phone' => $onboardingData['contact_phone']
            ]);
            
            $finalEmailCheck = DB::selectOne("SELECT id, name FROM shulesoft.connect_users WHERE email = ?", [$onboardingData['contact_email']]);
            if ($finalEmailCheck) {
                throw new \Exception("CRITICAL STEP-BY-STEP: Duplicate email detected in final check - {$onboardingData['contact_email']} already exists for user {$finalEmailCheck->name} (ID: {$finalEmailCheck->id})");
            }
            
            $finalPhoneCheck = DB::selectOne("SELECT id, name FROM shulesoft.connect_users WHERE phone = ?", [$onboardingData['contact_phone']]);
            if ($finalPhoneCheck) {
                throw new \Exception("CRITICAL STEP-BY-STEP: Duplicate phone detected in final check - {$onboardingData['contact_phone']} already exists for user {$finalPhoneCheck->name} (ID: {$finalPhoneCheck->id})");
            }
            
            \Log::info('Final fail-safe check passed in step-by-step onboarding - proceeding with user insertion');

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
            \Log::error('Step-by-step onboarding error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'session_data' => array_except($onboardingData ?? [], ['password', 'password_confirmation'])
            ]);
            
            $errorMessage = 'An error occurred during account creation. Please try again.';
            
            // Check for specific error patterns
            if (str_contains($e->getMessage(), 'CRITICAL') && str_contains($e->getMessage(), 'Duplicate email')) {
                $errorMessage = 'This email address is already registered. Please use a different email address.';
                // Clear session data since registration failed
                session()->forget('onboarding_data');
                return redirect()->route('onboarding.step1')->with('error', $errorMessage);
            } elseif (str_contains($e->getMessage(), 'CRITICAL') && str_contains($e->getMessage(), 'Duplicate phone')) {
                $errorMessage = 'This phone number is already registered. Please use a different phone number.';
                // Clear session data since registration failed
                session()->forget('onboarding_data');
                return redirect()->route('onboarding.step1')->with('error', $errorMessage);
            } elseif (str_contains($e->getMessage(), 'SQLSTATE[23505]')) {
                $errorMessage = 'This information already exists in our system. Please check your email, phone number, or organization name.';
                // Clear session data since registration failed
                session()->forget('onboarding_data');
                return redirect()->route('onboarding.step1')->with('error', $errorMessage);
            }
            
            return back()->with('error', $errorMessage);
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

    /**
     * Extract constraint name from PostgreSQL error message
     */
    private function extractConstraintName($errorMessage)
    {
        // Look for constraint name in PostgreSQL error message format
        if (preg_match('/violates unique constraint "([^"]+)"/', $errorMessage, $matches)) {
            return $matches[1];
        }
        
        if (preg_match('/constraint "([^"]+)"/', $errorMessage, $matches)) {
            return $matches[1];
        }
        
        return null;
    }

    /**
     * Get user-friendly error message based on constraint name
     */
    private function getConstraintErrorMessage($constraintName, $errorMessage)
    {
        switch ($constraintName) {
            case 'connect_users_email_unique':
            case 'users_email_unique':
                return 'A user with this email address already exists. Please use a different email or contact support if this is your account.';
                
            case 'connect_users_phone_unique':
            case 'users_phone_unique':
                return 'A user with this phone number already exists. Please use a different phone number or contact support if this is your account.';
                
            case 'connect_organizations_name_unique':
            case 'organizations_name_unique':
                return 'An organization with this name already exists. Please choose a different name.';
                
            case 'connect_organizations_username_unique':
            case 'organizations_username_unique':
                return 'There was a conflict with the organization username. Please try again or contact support.';
                
            default:
                // Check for general patterns if specific constraint not matched
                if (str_contains($errorMessage, 'email') && str_contains($errorMessage, 'unique')) {
                    return 'This email address is already registered. Please use a different email.';
                }
                if (str_contains($errorMessage, 'phone') && str_contains($errorMessage, 'unique')) {
                    return 'This phone number is already registered. Please use a different phone number.';
                }
                if (str_contains($errorMessage, 'name') && str_contains($errorMessage, 'unique')) {
                    return 'This name is already taken. Please choose a different name.';
                }
                
                return 'A database constraint error occurred. This might be due to duplicate information. Please check your details and try again.';
        }
    }
}
