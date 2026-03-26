<?php

namespace App\Gk\Services;

use App\Models\UserMeta;
use Illuminate\Support\Facades\Auth;

final class AdminUpdatesUserRole
{
    public static function run(int $userId, string $role): void
    {
        abort_unless(in_array($role, ['admin', 'lead', 'staff'], true), 422);
        abort_if((int) Auth::id() === $userId, 403);
        UserMeta::updateOrCreate(['user_id' => $userId], ['role' => $role]);
    }
}
