<?php

namespace App\Gk\Services;

use App\Gk\Support\GkSettings;
use App\Models\Holiday;
use Illuminate\Support\Facades\Http;

final class SyncMalaysiaPublicHolidays
{
    public static function run(int $year = 2026): int
    {
        $url = sprintf(GkSettings::malaysiaHolidaysUrlTemplate(), $year);
        $res = Http::timeout(25)->acceptJson()->get($url);
        $res->throw();
        $items = $res->json('holidays') ?? [];
        $n = 0;
        foreach ($items as $row) {
            $day = $row['observed_date'] ?? $row['date'] ?? null;
            if (! $day) {
                continue;
            }
            $label = trim((string) ($row['local_name'] ?? $row['name'] ?? ''));
            Holiday::query()->updateOrCreate(
                ['on_date' => $day],
                ['label' => $label !== '' ? $label : null],
            );
            $n++;
        }

        return $n;
    }
}
