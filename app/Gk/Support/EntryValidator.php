<?php

namespace App\Gk\Support;

use App\Models\PlanMonth;
use Carbon\Carbon;

final class EntryValidator
{
    public static function assertEditableDay(array $dayMeta, string $status): void
    {
        if ($status === '') {
            return;
        }
        if ($dayMeta['weekend'] || $dayMeta['holiday']) {
            abort(422, 'non_working_day');
        }
    }

    public static function assertLeadTime(int $y, int $m, int $d, string $role): void
    {
        if (in_array($role, ['lead', 'admin'], true)) {
            return;
        }
        $day = Carbon::parse(sprintf('%04d-%02d-%02d', $y, $m, $d))->startOfDay();
        $today = now()->startOfDay();
        if ($day->lt($today)) {
            abort(422, 'past_date');
        }
        $cutoff = $today->copy()->addDays(GkSettings::leadDaysAhead());
        if ($day->lt($cutoff)) {
            abort(422, 'too_soon');
        }
    }

    public static function assertWfhCap(PlanMonth $pm, int $day, int $targetUserId, string $role, string $status): void
    {
        if ($status !== 'wfh' || in_array($role, ['lead', 'admin'], true)) {
            return;
        }
        if (WfhCounter::othersWfh($pm->id, $day, $targetUserId) >= GkSettings::maxWfhPerDay()) {
            abort(422, 'wfh_full');
        }
    }
}
