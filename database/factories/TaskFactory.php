<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{

    protected $model = \App\Models\Task::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['pending', 'inprogress', 'completed', 'overdue'];
        $priorities = ['Low', 'Medium', 'High'];

        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->optional()->text(255),
            'due_date' => $this->faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'status' => Arr::random($statuses),
            'priority' => $this->faker->numberBetween(0, 5),
            'priority' => $this->faker->randomElement($priorities),

        ];
    }
}
