<?php

namespace App\Gk\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

final class LoginAction
{
    public static function attempt(string $email, string $password): void
    {
        if (! Auth::attempt(['email' => $email, 'password' => $password])) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }
    }
}
