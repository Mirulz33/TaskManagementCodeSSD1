<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
                'regex:/^(?!.*(<script|<\/script>|select\s|insert\s|delete\s|update\s|drop\s)).*$/i',
            ],
            'description' => [
                'nullable',
                'string',
                'regex:/^(?!.*(<script|<\/script>|select\s|insert\s|delete\s|update\s|drop\s)).*$/i',
            ],
            'user_id' => ['required', 'exists:users,id'],
            'status' => ['sometimes', 'in:pending,in-progress,completed'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.regex' => 'Your input contains invalid characters.',
            'description.regex' => 'Your input contains invalid characters.',
        ];
    }
}
