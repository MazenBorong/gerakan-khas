<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserMeta;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

final class GkSeeder extends Seeder
{
    public function run(): void
    {
        $u = User::updateOrCreate(
            ['email' => 'aidiel@borong.com'],
            ['name' => 'Aidiel', 'password' => Hash::make('password')],
        );
        UserMeta::updateOrCreate(['user_id' => $u->id], ['role' => 'admin']);
    }
}
