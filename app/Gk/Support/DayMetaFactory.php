<?php

namespace App\Gk\Support;

use App\Models\Holiday;
use Carbon\Carbon;

final class DayMetaFactory
{
    public static function for(int $y, int $m): array
    {
        $start = Carbon::create($y, $m, 1)->startOfMonth();
        $end = $start->copy()->endOfMonth();
        $labels = [];
        foreach (Holiday::query()->whereBetween('on_date', [$start, $end])->get(['on_date', 'label']) as $h) {
            $labels[$h->on_date->toDateString()] = $h->label;
        }
        $out = [];
        for ($d = 1; $d <= $end->day; $d++) {
            $date = Carbon::create($y, $m, $d);
            $ds = $date->toDateString();
            $out[] = [
                'day' => $d,
                'weekday' => $date->format('D'),
                'weekend' => $date->isWeekend(),
                'holiday' => $labels[$ds] ?? null,
            ];
        }

        return $out;
    }
}
