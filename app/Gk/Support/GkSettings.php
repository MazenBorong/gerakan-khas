<?php

namespace App\Gk\Support;

use App\Models\GkSetting;

final class GkSettings
{
    private static ?GkSetting $row = null;

    public static function record(): GkSetting
    {
        if (self::$row instanceof GkSetting) {
            return self::$row;
        }
        $first = GkSetting::query()->first();
        if ($first) {
            self::$row = $first;

            return self::$row;
        }
        self::$row = GkSetting::query()->create([
            'max_wfh_per_day' => (int) config('gk.max_wfh_per_day'),
            'lead_days_ahead' => (int) config('gk.lead_days_ahead'),
            'malaysia_holidays_url' => null,
            'default_new_user_role' => 'staff',
        ]);

        return self::$row;
    }

    public static function forgetCached(): void
    {
        self::$row = null;
    }

    public static function leadDaysAhead(): int
    {
        return (int) self::record()->lead_days_ahead;
    }

    public static function maxWfhPerDay(): int
    {
        return (int) self::record()->max_wfh_per_day;
    }

    public static function malaysiaHolidaysUrlTemplate(): string
    {
        $url = self::record()->malaysia_holidays_url;

        return is_string($url) && $url !== ''
            ? $url
            : (string) config('gk.malaysia_holidays_url');
    }

    public static function defaultNewUserRole(): string
    {
        $r = (string) self::record()->default_new_user_role;

        return in_array($r, ['staff', 'lead'], true) ? $r : 'staff';
    }
}
