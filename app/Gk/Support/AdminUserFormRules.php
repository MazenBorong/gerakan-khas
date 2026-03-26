<?php

namespace App\Gk\Support;

final class AdminUserFormRules
{
    public static function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'email' => ['required', 'string', 'email:rfc', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'max:255'],
            'role' => ['required', 'in:admin,lead,staff'],
        ];
    }

    public static function messages(): array
    {
        return [
            'name.required' => 'Enter a name.',
            'email.required' => 'Enter an email.',
            'email.email' => 'Use a valid email.',
            'email.unique' => 'That email is already in use.',
            'password.required' => 'Set a password.',
            'password.min' => 'Use at least 8 characters.',
        ];
    }
}
