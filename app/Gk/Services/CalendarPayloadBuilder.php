<?php

namespace App\Gk\Services;

use App\Gk\Support\DayMetaFactory;
use App\Gk\Support\GkSettings;
use App\Gk\Support\RoleReader;
use App\Models\PlanEntry;
use App\Models\PlanMonth;
use App\Models\User;

final class CalendarPayloadBuilder
{
    public function build(int $y, int $m, User $actor): array
    {
        $pm = PlanMonth::firstOrCreate(['year' => $y, 'month' => $m]);
        $days = DayMetaFactory::for($y, $m);
        $users = User::query()
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn ($u) => ['id' => (int) $u->id, 'name' => (string) $u->name])
            ->values()
            ->all();
        $entries = PlanEntry::query()->where('plan_month_id', $pm->id)->get();
        $agg = CalendarMatrixReducer::fold($entries, $days);
        $lead = GkSettings::leadDaysAhead();
        $firstBookable = now()->startOfDay()->addDays($lead);

        return array_merge([
            'year' => $y,
            'month' => $m,
            'today_day' => (now()->year === $y && now()->month === $m) ? now()->day : null,
            'actor_id' => $actor->id,
            'role' => RoleReader::forUser($actor),
            'days' => $days,
            'users' => $users,
            'statuses' => config('gk.statuses'),
            'rules' => [
                'max_wfh_per_day' => GkSettings::maxWfhPerDay(),
                'lead_days_ahead' => $lead,
                'first_bookable_on_or_after' => $firstBookable->toDateString(),
            ],
        ], $agg);
    }
}
