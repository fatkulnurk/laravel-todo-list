<?php

namespace App\Http\Requests\Tasks;

use App\Enums\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:60000',
            'status' => ['sometimes', 'required', Rule::enum(TaskStatus::class)],
        ];
    }

    public function messages(): array
    {
        $allowedStatuses = implode(', ', array_map(fn($case) => $case->value, TaskStatus::cases()));

        return [
            'status.enum' => "The selected status is invalid. Available options: {$allowedStatuses}.",
        ];
    }
}
