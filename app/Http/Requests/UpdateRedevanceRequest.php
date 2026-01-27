<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRedevanceRequest extends FormRequest
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
            'periode_id' => 'required|integer|exists:periodes,id',
            'libelle' => 'required|string|min:3',
            'description' => 'nullable|string|min:3|max:255'
        ];
    }

    public function messages()
    {
        return [
            'periode_id.required' => 'La période est requise.',
            'periode_id.integer' => 'L\identifiant de la période doit être un nombre entier.',
            'periode_id.exists' => 'La période sélectionnée est invalide.',

            'libelle.required' => 'Le libellé de la redevance est obligatoire.',
            'libelle.string' => 'Le libellé de la redevance doit être une chaîne de caractères.',
            'libelle.min' => 'Le libellé de la redevance doit contenir au moins 3 caractères.',
            // 'libelle.unique' => 'Cette redevance existe déjà dans notre système.',

            'description.string' => 'La description de la redevance doit être une chaîne de caractères.',
            'description.min' => 'La description de la redevance doit contenir au moins 3 caractères.',
            'description.max' => 'La description de la redevance doit contenir au maximum 255 caractères.',
        ];
    }
}
