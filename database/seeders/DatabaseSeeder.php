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

        // Helper to generate avatar
        $generateAvatar = function ($name, $email) {
            $filename = 'avatars/' . \Illuminate\Support\Str::slug($email) . '_' . time() . '.png';
            $avatar = \Laravolt\Avatar\Facade::create($name)->getImageObject()->encode(new \Intervention\Image\Encoders\PngEncoder());
            \Illuminate\Support\Facades\Storage::disk('public')->put($filename, $avatar);
            return $filename;
        };

        // Create Admin User
        User::firstOrCreate(
            ['email' => 'admin@dyzulk.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'User',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role_id' => $adminRole->id,
                'email_verified_at' => now(),
                'avatar' => $generateAvatar('Admin User', 'admin@dyzulk.com'),
            ]
        );

        // Create Regular User
        User::firstOrCreate(
            ['email' => 'user@dyzulk.com'],
            [
                'first_name' => 'User',
                'last_name' => 'Customer',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role_id' => $customerRole->id,
                'email_verified_at' => now(),
                'avatar' => $generateAvatar('User Customer', 'user@dyzulk.com'),
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
        $this->call([
            LegalPageSeeder::class,
        ]);
    }
}
