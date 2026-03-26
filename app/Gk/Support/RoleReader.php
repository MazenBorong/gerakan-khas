<?php

namespace App\Gk\Support;

use App\Models\User;
use App\Models\UserMeta;

final class RoleReader
{
    public static function forUser(User $u): string
    {
        return UserMeta::query()->where('user_id', $u->id)->value('role') ?? 'staff';
    }
}
