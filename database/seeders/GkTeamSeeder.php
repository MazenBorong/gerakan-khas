<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserMeta;
use Database\Seeders\Data\GkTeamSeedRows;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

final class GkTeamSeeder extends Seeder
{
    public function run(): void
    {
        $hash = Hash::make('password');
        foreach (GkTeamSeedRows::all() as [$name, $slug, $role]) {
            $u = User::updateOrCreate(
                ['email' => $slug.'@borong.com'],
                ['name' => $name, 'password' => $hash],
            );
            UserMeta::updateOrCreate(['user_id' => $u->id], ['role' => $role]);
        }
    }
}
