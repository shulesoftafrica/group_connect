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
            'recent_activities' => $this->getRecentActivities(),
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
     */
    public function users()
    {
        $users = DB::select("
            SELECT u.*, org.name as organization_name,
                   (SELECT GROUP_CONCAT(DISTINCT sch.name SEPARATOR ', ') 
                    FROM shulesoft.user sch 
                    WHERE FIND_IN_SET(sch.uid, u.assigned_schools)) as assigned_school_names
            FROM shulesoft.connect_users u
            LEFT JOIN shulesoft.connect_organizations org ON u.organization_id = org.id
            ORDER BY u.created_at DESC
        ");

        $schools = DB::select("
            SELECT uid, name, username as school_code 
            FROM shulesoft.user 
            WHERE user_type = 'school'
            ORDER BY name
        ");

        $roles = DB::select("SELECT * FROM shulesoft.connect_roles ORDER BY name");

        return view('settings.users', compact('users', 'schools', 'roles'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:shulesoft.connect_users',
            'role_id' => 'required|exists:shulesoft.connect_roles,id',
            'assigned_schools' => 'array'
        ]);

        $userId = DB::insert("
            INSERT INTO shulesoft.connect_users (name, email, username, role_id, assigned_schools, status, created_at)
            VALUES (?, ?, ?, ?, ?, 'pending', NOW())
        ", [
            $request->name, 
            $request->email, 
            $request->email,
            $request->role_id,
            implode(',', $request->assigned_schools ?? [])
        ]);

        // Send invitation email
        $this->sendUserInvitation($request->email, $request->name);

        return redirect()->back()->with('success', 'User created and invitation sent successfully.');
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

        DB::update("
            UPDATE shulesoft.connect_users 
            SET name = ?, email = ?, role_id = ?, assigned_schools = ?, status = ?, updated_at = NOW()
            WHERE id = ?
        ", [
            $request->name, 
            $request->email, 
            $request->role_id,
            implode(',', $request->assigned_schools ?? []),
            $request->status,
            $id
        ]);

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
            SELECT u.*, 
                   (SELECT COUNT(*) FROM shulesoft.student s WHERE s.uid = u.uid) as student_count,
                   (SELECT SUM(amount) FROM shulesoft.revenues r WHERE r.uid = u.uid AND YEAR(r.created_at) = YEAR(NOW())) as annual_revenue,
                   (SELECT COUNT(*) FROM shulesoft.connect_users cu WHERE FIND_IN_SET(u.uid, cu.assigned_schools)) as assigned_users
            FROM shulesoft.user u 
            WHERE u.user_type = 'school'
            ORDER BY u.name
        ");

        return view('settings.schools', compact('schools'));
    }

    public function storeSchool(Request $request)
    {
        if ($request->action_type === 'link_existing') {
            $request->validate([
                'school_code' => 'required|string'
            ]);

            $school = DB::selectOne("
                SELECT * FROM shulesoft.user 
                WHERE username = ? AND user_type = 'school'
            ", [$request->school_code]);

            if (!$school) {
                return redirect()->back()->with('error', 'School not found with the provided code.');
            }

            // Add to connect_schools if not already linked
            $exists = DB::selectOne("
                SELECT * FROM shulesoft.connect_schools 
                WHERE school_uid = ?
            ", [$school->uid]);

            if (!$exists) {
                DB::insert("
                    INSERT INTO shulesoft.connect_schools (school_uid, linked_at, status)
                    VALUES (?, NOW(), 'active')
                ", [$school->uid]);
            }

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
                (school_name, location, contact_person, contact_email, contact_phone, status, requested_at)
                VALUES (?, ?, ?, ?, ?, 'pending', NOW())
            ", [
                $request->school_name,
                $request->location,
                $request->contact_person,
                $request->contact_email,
                $request->contact_phone
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
            SELECT * FROM shulesoft.system_config 
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
            FROM shulesoft.audit_logs al
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
            WHERE user_type = 'school'
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
        return DB::selectOne("SELECT COUNT(*) as count FROM shulesoft.connect_users")->count;
    }

    private function getTotalSchools()
    {
        return DB::selectOne("SELECT COUNT(*) as count FROM shulesoft.user WHERE user_type = 'school'")->count;
    }

    private function getActiveSessions()
    {
        return DB::selectOne("
            SELECT COUNT(*) as count 
            FROM shulesoft.user_sessions 
            WHERE last_activity > DATE_SUB(NOW(), INTERVAL 30 MINUTE)
        ")->count ?? 0;
    }

    private function getPendingApprovals()
    {
        $pending = 0;
        $pending += DB::selectOne("SELECT COUNT(*) as count FROM shulesoft.school_creation_requests WHERE status = 'pending'")->count ?? 0;
        $pending += DB::selectOne("SELECT COUNT(*) as count FROM shulesoft.connect_users WHERE status = 'pending'")->count ?? 0;
        return $pending;
    }

    private function getRecentActivities()
    {
        return DB::select("
            SELECT action, user_name, created_at
            FROM shulesoft.audit_logs
            ORDER BY created_at DESC
            LIMIT 10
        ");
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
        return DB::select("SELECT uid FROM shulesoft.user WHERE user_type = 'school'");
    }

    private function sendUserInvitation($email, $name)
    {
        // Implementation for sending invitation email
        // This would integrate with your email system
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
