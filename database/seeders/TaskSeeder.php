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
        $customTasks = [
            [
                'title' => 'Complete project proposal',
                'description' => 'Draft and finalize the project proposal document for the new client.',
                'due_date' => '2025-06-10',
                'status' => 'Pending',
                'priority' => 'High',
            ],
            [
                'title' => 'Review design mockups',
                'description' => 'Check the UI/UX team’s wireframes and provide feedback.',
                'due_date' => '2025-06-12',
                'status' => 'InProgress',
                'priority' => 'Medium',
            ],
            [
                'title' => 'Team meeting',
                'description' => null, // testing nullable description
                'due_date' => '2025-06-15',
                'status' => 'Completed',
                'priority' => 'Low',
            ],
        ];

        foreach ($customTasks as $taskData) {
            Task::factory()->create($taskData);
        }
    }
}
