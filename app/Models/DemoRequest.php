<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DemoRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_name',
        'organization_contact',
        'contact_name',
        'contact_phone',
        'contact_email',
        'organization_address',
        'organization_country',
        'total_schools',
        'status',
        'approval_token',
        'approved_at',
        'credentials'
    ];

    protected $casts = [
        'credentials' => 'array',
        'approved_at' => 'datetime'
    ];

    public function generateApprovalToken()
    {
        $this->approval_token = Str::random(64);
        $this->save();
        return $this->approval_token;
    }
}
