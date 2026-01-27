<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
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
            'libelle' => "required|string|min:3",
            'description' => "nullable|string|min:3",
            'montant_maximum' => "required|string|min:2",
        ];
    }
    public function messages(): array
    {
        return [
            'libelle.required' => 'Le champ libellé est obligatoire.',
            'libelle.string' => 'Le champ libellé doit être une chaîne de caractères.',
            'libelle.min' => 'Le champ libellé doit contenir au moins 3 caractères.',

            'description.string' => 'Le champ description doit être une chaîne de caractères.',
            'description.min' => 'Le champ description doit contenir au moins 3 caractères.',

            'montant_maximum.required' => 'Le champ montant maximum est obligatoire.',
            'montant_maximum.string' => 'Le champ montant maximum doit être une chaîne de caractères.',
            'montant_maximum.min' => 'Le champ montant maximum doit contenir au moins 2 caractères.',
        ];
    }
}
