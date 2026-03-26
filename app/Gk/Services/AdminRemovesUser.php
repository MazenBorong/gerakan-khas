<?php

namespace App\Gk\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

final class AdminRemovesUser
{
    public static function run(int $userId): void
    {
        abort_if((int) Auth::id() === $userId, 403);
        User::query()->whereKey($userId)->delete();
    }
}
