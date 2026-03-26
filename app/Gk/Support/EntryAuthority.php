<?php

namespace App\Gk\Support;

use App\Models\PlanEntry;
use App\Models\User;

final class EntryAuthority
{
    public static function canTouch(User $u, PlanEntry $e, string $role): bool
    {
        if ($role === 'admin' || $role === 'lead') {
            return true;
        }

        return (int) $e->user_id === (int) $u->id;
    }

    public static function canTouchUserId(User $u, int $targetUserId, string $role): bool
    {
        if ($role === 'admin' || $role === 'lead') {
            return true;
        }

        return (int) $targetUserId === (int) $u->id;
    }
}
