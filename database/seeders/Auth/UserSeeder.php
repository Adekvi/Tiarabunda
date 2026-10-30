<?php

namespace Database\Seeders\Auth;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@tiarabunda.ac.id',
                'password' => 'SuperAdmin!2026',
                'role' => 'superadmin',
            ],
            [
                'name' => 'Admin Konten',
                'email' => 'admin@tiarabunda.ac.id',
                'password' => 'AdminKonten!2026',
                'role' => 'admin',
            ],
            [
                'name' => 'User Demo',
                'email' => 'user@tiarabunda.ac.id',
                'password' => 'UserDemo!2026',
                'role' => 'user',
            ],
        ];

        foreach ($users as $data) {
            User::query()->updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make($data['password']),
                    // 'password' => $data['password'],
                    'role' => $data['role'],
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
