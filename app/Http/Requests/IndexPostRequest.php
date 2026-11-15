<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class IndexPostRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'published' => ['sometimes', 'boolean'],
            'user_id' => ['sometimes', 'integer', 'exists:users,id'],
            'tag_id' => ['sometimes', 'integer', 'exists:tags,id']
        ];
    }
}
