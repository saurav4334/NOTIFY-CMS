<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $roles = Role::pluck('id', 'slug');

        $users = [
            ['Super Admin', 'admin@notifysms.com.bd', 'super_admin'],
            ['Content Manager', 'content@notifysms.com.bd', 'content_manager'],
            ['Support Admin', 'support@notifysms.com.bd', 'support_admin'],
        ];

        foreach ($users as [$name, $email, $roleSlug]) {
            User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'role_id' => $roles[$roleSlug] ?? null,
                    'password' => Hash::make('password'),
                    'is_active' => true,
                    'email_verified_at' => now(),
                ],
            );
        }
    }
}
