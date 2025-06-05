<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskListingRequest;
use App\Http\Requests\TaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\TaskPriority;
use App\TaskStatus;


/**
 * @OA\Info(
 *     title="Task Management API",
 *     version="1.0.0",
 *     description="API for managing tasks with filtering, sorting, and pagination."
 * )
 *
 * @OA\Tag(
 *     name="Tasks",
 *     description="Operations related to tasks"
 * )
 */

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    /**
     * @OA\Get(
     *     path="/api/tasks",
     *     summary="List all tasks with optional filters and sorting",
     *     tags={"Tasks"},
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter tasks by status",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="from_due",
     *         in="query",
     *         description="Start due date (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="to_due",
     *         in="query",
     *         description="End due date (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="priority",
     *         in="query",
     *         description="Order by priority (High, Medium, Low)",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="due_date",
     *         in="query",
     *         description="Sort by due date (asc )",
     *         required=false,
     *     ),
     *     @OA\Parameter(
     *         name="created_at",
     *         in="query",
     *         description="Sort by created date (asc )",
     *         required=false,
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search by title or description",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Results per page",
     *         required=false,
     *         @OA\Schema(type="integer", default=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Task")),
     *             @OA\Property(property="meta", type="object")
     *         )
     *     )
     * )
     */

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

    /**
     * @OA\Post(
     *     path="/api/tasks",
     *     summary="Create a new task",
     *     tags={"Tasks"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "description", "due_date"},
     *             @OA\Property(property="title", type="string", example="Write documentation"),
     *             @OA\Property(property="description", type="string", example="Use Swagger to document API"),
     *             @OA\Property(property="due_date", type="string", format="date", example="2025-06-15"),
     *             @OA\Property(property="status", type="string", example="Pending"),
     *             @OA\Property(property="priority", type="string", example="High")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Task created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Task created successfully"),
     *             @OA\Property(property="task", ref="#/components/schemas/Task")
     *         )
     *     )
     * )
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

    /**
     * @OA\Put(
     *     path="/api/tasks/{id}",
     *     summary="Update task status",
     *     tags={"Tasks"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the task to update",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"status"},
     *             @OA\Property(property="status", type="string", example="Completed")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task status updated",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Task status updated"),
     *             @OA\Property(property="task", ref="#/components/schemas/Task")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Invalid status transition",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Task must be In Progress before it can be marked Completed.")
     *         )
     *     )
     * )
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
    /**
     * @OA\Delete(
     *     path="/api/tasks/{id}",
     *     summary="Delete a task (soft delete)",
     *     tags={"Tasks"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the task to delete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Task soft deleted",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Task soft deleted")
     *         )
     *     )
     * )
     */

    public function destroy(string $id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return response()->json(['message' => 'Task soft deleted']);
    }
}
