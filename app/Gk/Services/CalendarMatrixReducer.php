<?php

namespace App\Gk\Services;

final class CalendarMatrixReducer
{
    public static function fold(iterable $entries, array $days): array
    {
        $matrix = [];
        $entryIds = [];
        $wfh = [];
        $leave = [];
        foreach ($days as $x) {
            $wfh[$x['day']] = 0;
            $leave[$x['day']] = 0;
        }
        foreach ($entries as $e) {
            $k = $e->user_id.'-'.$e->day;
            $matrix[$k] = $e->status;
            $entryIds[$k] = $e->id;
            if ($e->status === 'wfh') {
                $wfh[$e->day]++;
            }
            if ($e->status === 'leave') {
                $leave[$e->day]++;
            }
        }

        return compact('matrix', 'entryIds', 'wfh', 'leave');
    }
}
