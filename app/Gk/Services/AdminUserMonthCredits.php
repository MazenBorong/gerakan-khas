<?php

namespace App\Gk\Services;

use App\Gk\Support\PlanEntryDayRanges;
use App\Models\PlanEntry;
use App\Models\PlanMonth;
use Illuminate\Support\Collection;

final class AdminUserMonthCredits
{
    public static function build(int $year, int $month, Collection $users): array
    {
        $pm = PlanMonth::query()->where(compact('year', 'month'))->first();
        $blank = ['lines' => []];
        if (! $pm) {
            return $users->mapWithKeys(fn ($u) => [$u->id => $blank])->all();
        }
        $entries = PlanEntry::query()->where('plan_month_id', $pm->id)
            ->whereIn('user_id', $users->pluck('id'))
            ->orderBy('user_id')->orderBy('day')->get(['user_id', 'day', 'status']);

        return $users->mapWithKeys(fn ($u) => [
            $u->id => ['lines' => self::linesForUser($entries->where('user_id', $u->id), $month)],
        ])->all();
    }

    private static function linesForUser(Collection $entries, int $month): array
    {
        $lines = [];
        foreach (array_keys(config('gk.statuses')) as $status) {
            $days = $entries->where('status', $status)->pluck('day')->unique()->sort()->values()->all();
            if ($days === []) {
                continue;
            }
            $ranges = PlanEntryDayRanges::pack($days);
            $spans = array_map(fn ($r) => PlanEntryDayRanges::label($r[0], $r[1], $month), $ranges);
            $n = count($days);
            $lab = config('gk.statuses')[$status];
            $lines[] = [
                'code' => $status,
                'text' => "{$n} {$lab} (".implode(', ', $spans).')',
            ];
        }

        return $lines;
    }
}
