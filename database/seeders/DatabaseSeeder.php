<?php

namespace Database\Seeders;


use Illuminate\Support\Facades\Hash;
use App\Models\User;


use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'approved' => true,
            'approved_at' => now(),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Casse',
            'email' => 'casse@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'casse',
            'approved' => true,
            'approved_at' => now(),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Client',
            'email' => 'client@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'client',
            'approved' => true,
            'approved_at' => now(),
            'email_verified_at' => now(),
        ]);

        $this->call(MarqueModeleSeeder::class);
    }
}
