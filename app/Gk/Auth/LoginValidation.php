<?php

namespace App\Gk\Auth;

final class LoginValidation
{
    public static function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email:rfc', 'max:255'],
            'password' => ['required', 'string'],
        ];
    }

    public static function messages(): array
    {
        return [
            'email.required' => 'Enter your email address.',
            'email.email' => 'Enter a valid email address.',
            'password.required' => 'Enter your password.',
        ];
    }

    public static function attributes(): array
    {
        return [
            'email' => 'email address',
            'password' => 'password',
        ];
    }
}
