<?php

namespace App\Gk\Support;

final class AllowedStatus
{
    public static function normalize(string $s): string
    {
        if ($s === '') {
            return '';
        }
        abort_unless(array_key_exists($s, config('gk.statuses')), 422);

        return $s;
    }
}
