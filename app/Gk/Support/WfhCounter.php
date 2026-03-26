<?php

namespace App\Gk\Support;

use App\Models\PlanEntry;

final class WfhCounter
{
    public static function othersWfh(int $planMonthId, int $day, int $forUserId): int
    {
        return PlanEntry::query()
            ->where('plan_month_id', $planMonthId)
            ->where('day', $day)
            ->where('status', 'wfh')
            ->where('user_id', '!=', $forUserId)
            ->count();
    }
}
