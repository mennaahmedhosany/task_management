<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\TaskStatus;

class TaskRequest extends FormRequest
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

    protected function prepareForValidation(): void
    {
        if ($this->has('status')) {
            $this->merge([
                'status' => strtolower($this->input('status')),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date|after:today',
            'status' => ['required', new Enum(TaskStatus::class)],
        ];
    }
}
