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
            'email' => 'adminn@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'approved' => true,
            'approved_at' => now(),
            'email_verified_at' => now(),
        ]);
    }
}
