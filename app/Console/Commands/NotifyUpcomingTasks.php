<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Notifications\TaskDueNotification;
use Carbon\Carbon;

class NotifyUpcomingTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notify-upcoming-tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        $dueTime = $now->copy()->addDay()->startOfHour();

        $tasks = Task::with('user') // assuming task belongsTo user
            ->whereBetween('due_date', [$dueTime, $dueTime->copy()->endOfHour()])
            ->get();

        if ($tasks->isEmpty()) {
            $this->info('No tasks have due_date ≃ now (within this minute).');
            return;
        }


        foreach ($tasks as $task) {
            $task->user->notify(new TaskDueNotification($task));
        }

        $this->info('Notifications sent for upcoming tasks.');
    }
}
