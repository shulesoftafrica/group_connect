<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\School;
use App\Models\Organization;

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
                   (SELECT STRING_AGG(DISTINCT sch.name, ', ')
                    FROM shulesoft.user sch
                    WHERE ',' || u.name || ',' LIKE '%,' || sch.uid || ',%') as assigned_school_names
            FROM shulesoft.connect_users u
            ORDER BY u.created_at DESC
        ");

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
        $authUser = Auth::user();
        $schools = $authUser->schools()->active()->get();
        $roles = DB::select("SELECT * FROM shulesoft.connect_roles ORDER BY name");

        // Generate HTML for the modal body
        $html = view('settings.partials.edit-user-form', compact('user', 'schools', 'roles', 'userSchools'))->render();

        return response()->json(['html' => $html]);
    }

    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'role_id' => 'required|exists:shulesoft.connect_roles,id',
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
            'name' => 'required|string|max:255|unique:shulesoft.connect_roles',
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
}
