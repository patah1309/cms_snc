<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = env('SUPER_ADMIN_EMAIL', 'admin@snc.local');
        $password = env('SUPER_ADMIN_PASSWORD', 'Admin12345');

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => 'Super Admin',
                'password' => Hash::make($password),
                'role' => User::ROLE_SUPER_ADMIN,
            ]
        );

        if ($user->role !== User::ROLE_SUPER_ADMIN) {
            $user->role = User::ROLE_SUPER_ADMIN;
            $user->save();
        }
    }
}
