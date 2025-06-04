<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */


    public function run(): void
    {
        $users = [
            [
                'name' => 'Lila Ahmed',
                'email' => 'lila123@example.com',
                'password' => bcrypt('password123'),
            ],
            [
                'name' => 'Sara Ali',
                'email' => 'sara@example.com',
                'password' => bcrypt('password456'),
            ],
            [
                'name' => 'Omar Nasser',
                'email' => 'omar@example.com',
                'password' => bcrypt('password789'),
            ],
        ];

        foreach ($users as $userData) {
            User::factory()->create($userData);
        }
    }
}
