<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoremessageRequest extends FormRequest
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
            //
           'content' => "required|text",
        ];
    }
    public function messages(): array
    {
        return [
            'content.required' => 'Le message est requis.',
            'content.string' => 'Le message doit être une chaîne de caractères.',
        ];
    }
}
