<?php

use App\Gk\Services\SyncMalaysiaPublicHolidays;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('gk:sync-malaysia-holidays {year=2026}', function (int $year) {
    $n = SyncMalaysiaPublicHolidays::run($year);
    $this->info("Upserted {$n} holidays for {$year}.");
})->purpose('Sync Malaysia public holidays from the configured JSON API');
