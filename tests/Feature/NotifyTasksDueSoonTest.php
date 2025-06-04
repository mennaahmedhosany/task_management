<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Notifications\TaskDueSoonNotification;
use App\Models\Task;
use Illuminate\Notifications\AnonymousNotifiable;

class TaskDueSoonNotificationTest extends TestCase
{
    public function test_notification_contains_task_data()
    {
        $task = new Task([
            'id' => 123,
            'title' => 'Test Task',
            'due_date' => now()->addDay(),
        ]);

        $notification = new TaskDueSoonNotification($task);

        $mailData = $notification->toMail(new AnonymousNotifiable());

        $this->assertStringContainsString('Test Task', $mailData->subject);
        $this->assertStringContainsString('123', (string) $mailData->viewData['task']->id);
    }
}
