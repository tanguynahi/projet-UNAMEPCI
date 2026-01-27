<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGradeRequest extends FormRequest
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
            'libelle' => 'required|string|min:3|unique:grades,libelle',
            'description' => 'nullable|string|min:3|max:255'
        ];
    }

    public function messages()
    {
        return [
            'libelle.required' => 'Le libellé du Grade est obligatoire.',
            'libelle.string' => 'Le libellé du Grade doit être une chaîne de caractères.',
            'libelle.min' => 'Le libellé du Grade doit contenir au moins 3 caractères.',
            'libelle.unique' => 'Ce Grade existe déjà dans notre système.',

            'description.string' => 'La description du Grade doit être une chaîne de caractères.',
            'description.min' => 'La description du Grade doit contenir au moins 3 caractères.',
            'description.max' => 'La description du Grade doit contenir au maximum 255 caractères.',
        ];
    }
}
