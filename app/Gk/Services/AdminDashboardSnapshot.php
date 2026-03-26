<?php

namespace App\Gk\Services;

use App\Models\Holiday;
use App\Models\PlanEntry;
use App\Models\PlanMonth;
use Carbon\Carbon;

final class AdminDashboardSnapshot
{
    public static function build(Carbon $today): array
    {
        $pm = PlanMonth::query()->where('year', $today->year)->where('month', $today->month)->first();
        $keys = array_keys(config('gk.statuses'));
        $todayPeople = [];
        $monthStats = ['wfh' => 0, 'leave' => 0, 'sl' => 0, 'last_day' => 0];
        if ($pm) {
            $todayPeople = PlanEntry::query()->where('plan_month_id', $pm->id)->where('day', $today->day)
                ->whereIn('status', $keys)->with(['user' => fn ($q) => $q->select('id', 'name')])
                ->orderBy('user_id')->get()->map(fn (PlanEntry $e) => [
                    'name' => $e->user->name,
                    'status' => $e->status,
                    'label' => config('gk.statuses')[$e->status] ?? $e->status,
                ])->values()->all();
            $rows = PlanEntry::query()->where('plan_month_id', $pm->id)->whereIn('status', array_keys($monthStats))
                ->selectRaw('status, count(*) as c')->groupBy('status')->pluck('c', 'status');
            foreach (array_keys($monthStats) as $k) {
                $monthStats[$k] = (int) ($rows[$k] ?? 0);
            }
        }
        $nextHoliday = Holiday::query()->whereDate('on_date', '>', $today->toDateString())->orderBy('on_date')->first();

        return [
            'todayPeople' => $todayPeople,
            'monthStats' => $monthStats,
            'nextHoliday' => $nextHoliday,
            'todayLabel' => $today->format('l, j F Y'),
            'monthLabel' => $today->format('F Y'),
        ];
    }
}
