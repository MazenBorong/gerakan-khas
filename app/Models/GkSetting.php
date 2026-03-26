<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GkSetting extends Model
{
    protected $fillable = [
        'max_wfh_per_day',
        'lead_days_ahead',
        'malaysia_holidays_url',
        'default_new_user_role',
    ];

    protected function casts(): array
    {
        return [
            'max_wfh_per_day' => 'integer',
            'lead_days_ahead' => 'integer',
        ];
    }
}
