<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskListingRequest;
use App\Http\Requests\TaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\StoreTaskResource;
use App\Http\Resources\TaskResource;
use App\Http\Resources\UpdateTaskResource;
use App\Models\Task;
use App\TaskPriority;
use App\TaskStatus;



class TaskController extends Controller
{


    public function index(TaskListingRequest $request)
    {
        $query = Task::query();
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has(['from_due', 'to_due'])) {
            $query->whereBetween('due_date', [$request->from_due, $request->to_due]);
        }

        if ($request->has('priority')) {

            $query->orderByRaw("FIELD(priority, 'High', 'Medium', 'Low') ");
        }

        if ($request->has('due_date')) {
            $query->orderby('due_date', 'asc');
        }
        if ($request->has('created_at')) {
            $query->orderby('created_at', 'asc');
        }
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                    ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }

        $perPage = $request->input('per_page', 15);
        $tasks = $query->paginate($perPage);

        return response()->json($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */



    public function store(TaskRequest $request)
    {


        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'status' => $request->status ?? TaskStatus::Pending->value,
            'priority' => $request->priority ?? TaskPriority::Medium->value,
        ]);

        // Attach the authenticated user to the task
        $task->users()->attach(auth()->user()->id);

        return response()->json([
            'message' => 'Task created successfully',
            'task' => new StoreTaskResource($task),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */



    public function update(UpdateTaskRequest $request, string $id)
    {
        // Find the task or fail
        $task = Task::findOrFail($id);

        if (
            $request->status === TaskStatus::Completed->value &&
            $task->status->value !== TaskStatus::InProgress->value
        ) {
            return response()->json([
                'message' => 'Task must be In Progress before it can be marked Completed.'
            ], 422);
        }

        // Update using enum value (string)
        $task->update([
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'Task status updated',
            'task' => new UpdateTaskResource($task),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */


    public function destroy(string $id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return response()->json(['message' => 'Task soft deleted']);
    }
}
