<?php

namespace App\Http\Requests;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class TaskListingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'search' => 'nullable|string',
            'due_date' => 'from_due|date|',
            'due_date' => 'to_due|date|',
            'priority' => ['nullable', new Enum(TaskPriority::class)],
            'status' => ['nullable', new Enum(TaskStatus::class)],
            'due_date' => 'nullable|date',
            'created_at' => 'nullable|date',
        ];
    }
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'search.string' => 'The search term must be a string.',
            'due_date.from_due.date' => 'The from_due date must be a valid date.',
            'due_date.to_due.date' => 'The to_due date must be a valid date.',
            'priority.enum' => 'The priority must be one of the following: Low, Medium, High.',
            'status.enum' => 'The status must be one of the following: Pending, InProgress, Completed, Overdue.',
            'due_date.date' => 'The due date must be a valid date.',
            'created_at.date' => 'The created_at must be a valid date.',
        ];
    }
}
