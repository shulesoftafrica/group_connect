<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShulesoftSetting extends Model
{
    use HasFactory;

    protected $table = 'shulesoft.setting';

    protected $fillable = [
        'uid',
        'schema_name',
        'lat',
        'lng',
        // Add other fields as necessary
    ];

    /**
     * Get the schools associated with this setting.
     */
    public function schools()
    {
        return $this->hasMany(School::class, 'school_setting_uid', 'uid');
    }
}

