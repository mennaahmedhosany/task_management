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
        User::factory()->create([
            'name' => 'lila Ahmed',
            'email' => 'lila@example.com',
            'password' => bcrypt('password123'),
            "password_confirmation": "password123",

        ]);
    }
}
