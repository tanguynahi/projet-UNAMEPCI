<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaxeRequest extends FormRequest
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
            'libelle'     => 'required|string|min:3|max:255',
            'montant'     => 'required|numeric|min:0',
            'description' => 'nullable|string|min:3',
        ];
    }
    public function messages()
    {
        return [
            'libelle.required' => 'Le libellé est obligatoire.',
            'libelle.string'   => 'Le libellé doit être un texte.',
            'libelle.min'      => 'Le libellé doit contenir au moins 3 caractères.',

            'montant.required' => 'Le montant est obligatoire.',
            'montant.numeric'  => 'Le montant doit être un nombre.',
            'montant.min'      => 'Le montant ne peut pas être négatif.',

            'description.string' => 'La description doit être un texte.',
            'description.min'    => 'La description doit contenir au moins 3 caractères.',
        ];
    }
}
