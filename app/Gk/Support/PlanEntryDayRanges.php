<?php

namespace App\Gk\Support;

final class PlanEntryDayRanges
{
    public static function pack(array $days): array
    {
        if ($days === []) {
            return [];
        }
        sort($days);
        $out = [];
        $a = $b = $days[0];
        for ($i = 1, $n = count($days); $i < $n; $i++) {
            $d = $days[$i];
            if ($d === $b + 1) {
                $b = $d;

                continue;
            }
            $out[] = [$a, $b];
            $a = $b = $d;
        }
        $out[] = [$a, $b];

        return $out;
    }

    public static function label(int $from, int $to, int $month): string
    {
        if ($from === $to) {
            return "{$from}/{$month}";
        }

        return "{$from}/{$month} – {$to}/{$month}";
    }
}
