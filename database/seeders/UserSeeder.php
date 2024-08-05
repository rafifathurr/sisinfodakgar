<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::create([
            'username' => 'kasubditdakgar',
            'name' => 'Kasubditdakgar',
            'email' => 'kasubditdakgar@polri.go.id',
            'password' => bcrypt('kasubditdakgar2024!@'),
        ]);

        $admin->assignRole('kasubdit');

        $user = User::create([
            'username' => 'urminsubditdakgar',
            'name' => 'Urmin Dakgar',
            'email' => 'urminsubditdakgar@polri.go.id',
            'password' => bcrypt('urminsubditdakgar2024!@'),
        ]);

        $user->assignRole('admin');

        $user = User::create([
            'username' => 'staffsubditdakgar',
            'name' => 'Staff Dakgar',
            'email' => 'staffsubditdakgar@polri.go.id',
            'password' => bcrypt('staffsubditdakgar2024!@'),
        ]);

        $user->assignRole('user');
    }
}
