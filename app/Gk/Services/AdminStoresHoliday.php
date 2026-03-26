<?php

namespace App\Gk\Services;

use App\Models\Holiday;

final class AdminStoresHoliday
{
    public static function run(string $onDate, ?string $label): void
    {
        Holiday::query()->updateOrCreate(
            ['on_date' => $onDate],
            ['label' => $label ?: null],
        );
    }
}
