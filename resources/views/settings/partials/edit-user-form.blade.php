<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="edit_name" class="form-label">Full Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="edit_name" name="name" value="{{ $user->name }}" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="edit_email" class="form-label">Email Address <span class="text-danger">*</span></label>
            <input type="email" class="form-control" id="edit_email" name="email" value="{{ $user->email }}" required>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="edit_role_id" class="form-label">Role <span class="text-danger">*</span></label>
            <select class="form-select" id="edit_role_id" name="role_id" required>
                <option value="">Select Role</option>
                @foreach($roles as $role)
                <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                    {{ $role->name }}
                </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="edit_status" class="form-label">Status <span class="text-danger">*</span></label>
            <select class="form-select" id="edit_status" name="status" required>
                <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ $user->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                <option value="pending" {{ $user->status == 'pending' ? 'selected' : '' }}>Pending</option>
            </select>
        </div>
    </div>
</div>

<div class="mb-3">
    <label for="edit_assigned_schools" class="form-label">Assign Schools</label>
    <select class="form-select" id="edit_assigned_schools" name="assigned_schools[]" multiple>
        @foreach($schools as $school)
        <option value="{{ $school->school_setting_uid }}" 
                {{ in_array($school->school_setting_uid, $userSchools) ? 'selected' : '' }}>
            ({{ $school->schoolSetting->sname }})
        </option>
        @endforeach
    </select>
    <div class="form-text">Hold Ctrl/Cmd to select multiple schools</div>
</div>

<div class="mb-3">
    <div class="form-check">
        <input class="form-check-input" type="checkbox" id="edit_send_notification" name="send_notification">
        <label class="form-check-label" for="edit_send_notification">
            Send update notification to user
        </label>
    </div>
</div>

<div class="alert alert-info">
    <i class="bi bi-info-circle me-2"></i>
    <strong>User ID:</strong> {{ $user->id }} | 
    <strong>Created:</strong> {{ date('M j, Y', strtotime($user->created_at)) }}
    @if($user->updated_at)
    | <strong>Last Updated:</strong> {{ date('M j, Y', strtotime($user->updated_at)) }}
    @endif
</div>
