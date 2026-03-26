<?php

namespace App\Gk\Services;

use App\Models\User;
use App\Models\UserMeta;

final class AdminRegistersUser
{
    public static function run(string $name, string $email, string $password, string $role): void
    {
        abort_unless(in_array($role, ['admin', 'lead', 'staff'], true), 422);
        $u = User::query()->create([
            'name' => $name,
            'email' => strtolower(trim($email)),
            'password' => $password,
        ]);
        UserMeta::query()->create(['user_id' => $u->id, 'role' => $role]);
    }
}
