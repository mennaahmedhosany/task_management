<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\TaskPriority;
use Illuminate\Validation\Rules\Enum;
use App\TaskStatus;

use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Task::query();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has(['from_due', 'to_due'])) {
            $query->whereBetween('due_date', [$request->from_due, $request->to_due]);
        }

        if ($request->has('sort_by')) {
            $sortOptions = ['priority', 'due_date', 'created_at'];
            $sortBy = in_array($request->sort_by, $sortOptions) ? $request->sort_by : 'created_at';
            $query->orderBy($sortBy);
        }


        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                    ->orWhere('description', 'like', '%' . $searchTerm . '%');
            });
        }
        $tasks = $query->get();
        return response()->json(['tasks' => $tasks]);
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
            'task' => $task
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
            'task' => $task
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
