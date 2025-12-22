<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Roles
        $adminRole = \App\Models\Role::firstOrCreate(
            ['name' => 'admin'],
            ['label' => 'Administrator']
        );

        $customerRole = \App\Models\Role::firstOrCreate(
            ['name' => 'customer'],
            ['label' => 'Customer']
        );

        // Create Admin User
        // Create Admin User
        User::firstOrCreate(
            ['email' => 'admin@dyzulk.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role_id' => $adminRole->id,
                'email_verified_at' => now(),
            ]
        );

        // Create Regular User
        // Create Regular User
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'first_name' => 'Test',
                'last_name' => 'User',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role_id' => $customerRole->id,
            ]
        );

        // Create specific test user for password reset
        // User::firstOrCreate(
        //     ['email' => 'santulitam2024@gmail.com'],
        //     [
        //         'first_name' => 'Santul',
        //         'last_name' => 'Itam',
        //         'password' => \Illuminate\Support\Facades\Hash::make('password'),
        //         'role_id' => $customerRole->id,
        //     ]
        // );
    }
}
