<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
        ]);

        User::create([
            'name' => 'Admin AM',
            'email' => 'adminam@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin_am',
        ]);

        User::create([
            'name' => 'Admin GA',
            'email' => 'admin-ga@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin_ga',
        ]);

        User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);
    }
}
