
## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
# task_management

This is a Task Management API built with Laravel, designed to help users manage their tasks effectively with features like status updates, prioritization, soft deletion, full-text search, and automated email notifications.

# Authentication Endpoints

🔐 Register User
Method: POST

Endpoint: /api/register
     
📥 Request Parameters :
      username (string): name. 
      email (string): User's email address.
      password (string): User's password.  

🔐 Login User
Method: POST

Endpoint: /api/Login
     
📥 Request Parameters :
      name (string): name. 
      password (string): User's password.  

🔐 Logout User
Method: POST

Endpoint: /api/Logout
     
📥 Request Parameters :
    null


# Basic Features Endpoints:

Create Task

Method: POST

Endpoint: /api/tasks

Authentication: ✅ Required — Bearer Token (Passport)

📥 Request Parameters :
        title (string): Task title.
        description (string): Task description.
        dueDate (date): Task due date. Must be a valid future date.
        priority (enum): Task priority (Low,  Medium, High). (Optional, default = Medium)


Update Task

Method: put

Endpoint: /api/tasks

Authentication: ✅ Required — Bearer Token (Passport)

📥 Request Parameters :
        id: int
        status (enum): Task status ( pending,inpregress,completed,overdue). (required, default = pending)


Task Deletion

 Method: post 

 Endpoint: /api/tasks

Authentication: ✅ Required — Bearer Token (Passport)

📥 Request Parameters :
          id: int


Task Listing:     
       
   Method: get

   Endpoint: /api/tasks

Authentication: ✅ Required — Bearer Token (Passport)

 📥 Request Parameters :

        status (enum): Filter by task status (pending, inprogress, completed, overdue). (Optional)
        from_due (date): Start date for due date range filter. (Optional)
        to_due (date): End date for due date range filter. (Optional)
        sort_by (string): Sort tasks by one of the following: priority, due_date, or created_at. (Optional; default = created_at)
        search (string): Keyword to search in task title or description. (Optional)


# Advanced Features:

This feature automatically notifies users via email 24 hours before a task's due date.

            📦 How It Works:

            The system uses Laravel’s queue system to send email notifications in the background.

            A custom Artisan command runs every hour to check for tasks that are due in the next 24 hours.

            If any are found, notification emails are queued for delivery.

            Setup Instructions:

            Queue Configuration:
            Ensure the queue system is set up (e.g., using database, redis, etc.)

            php artisan queue:work
            Schedule the Command:
            In App\Console\Kernel.php, schedule the custom command to run hourly:

            php

            protected function schedule(Schedule $schedule)
            {
                $schedule->command('tasks:notify')->hourly();
            }
            Create the Command:
            A custom command like this should exist:

            php artisan make:command NotifyUpcomingTasks

            Email Template:
            Customize the Mailable class and view file used for the task notification.

            📧 Notification Trigger:

            When the command runs, it finds tasks with due_date = now() + 1 day and sends notifications to their assigned users.

            ✅ Requirements:

            Valid email addresses for all users.

            Queue worker running in the background.


Task Search:     
       
   Method: get

   Endpoint: /api/tasks

Authentication: ✅ Required — Bearer Token (Passport)

 📥 Request Parameters :

        search (string): Keyword to search in task title or description. (Optional)



Task Prioritization:     
       
   Method: get

   Endpoint: /api/tasks

Authentication: ✅ Required — Bearer Token (Passport)

 📥 Request Parameters :

        sort_by (string): Sort tasks by one of the following: priority, due_date, or created_at. (Optional; default = created_at)
        
# API Documentation :

his API allows you to manage tasks with features including filtering, sorting, pagination, and CRUD operations.

bash

http://127.0.0.1:8000/api/documentation#/Tasks/be68905d866e856db6c738172b8d929a


# Rate Limiting Middleware

To prevent abuse, the `POST /api/tasks` route is protected by a custom rate limiter.

# Configuration

Defined in a custom `ServiceProvider`:

```php
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

RateLimiter::for('task-creation', function ($request) {
    return Limit::perMinute(5)->by(optional($request->user())->id ?: $request->ip());
});

# Laravel API Resources  :
  this project uses Laravel API Resources to provide a clean and consistent JSON response for the Task update endpoint.