<?php

namespace App\Gk\Services;

use App\Models\Holiday;

final class AdminRemovesHoliday
{
    public static function run(int $id): void
    {
        Holiday::query()->whereKey($id)->delete();
    }
}
