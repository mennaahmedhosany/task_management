<?php

namespace App\Schemas;

/**
 * @OA\Schema(
 *     schema="Task",
 *     type="object",
 *     title="Task",
 *     required={"id", "title", "status"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Finish Swagger Integration"),
 *     @OA\Property(property="description", type="string", example="Document all API endpoints using Swagger"),
 *     @OA\Property(property="status", type="string", example="in_progress"),
 *     @OA\Property(property="priority", type="string", example="high"),
 *     @OA\Property(property="due_date", type="string", format="date", example="2025-06-30"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-06-01T10:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-06-01T12:00:00Z")
 * )
 */
class TaskSchema {}
