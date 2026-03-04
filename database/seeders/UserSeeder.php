<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@kikundi.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Create demo user
        User::create([
            'name' => 'Demo User',
            'email' => 'demo@kikundi.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $this->command->info('Users created successfully!');
        $this->command->info('Admin: admin@kikundi.com / password');
        $this->command->info('Demo: demo@kikundi.com / password');
    }
}
