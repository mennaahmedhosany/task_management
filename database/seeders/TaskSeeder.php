<?php

namespace Database\Seeders;

use App\Models\Task;
use Database\Factories\TaskFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Task::factory()->create([
            'title' => 'Complete project proposal',
            'description' => 'Draft and finalize the project proposal document for the new client.',
            'due_date' => '2025-06-10',
            'status' => 'pending',
        ]);
    }
}
