<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTypePaiementRequest extends FormRequest
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
            'libelle' => 'required|string|min:3|unique:type_paiements,libelle',
            'description' => 'nullable|string|min:3|max:255'
        ];
    }

    public function messages()
    {
        return [
            'libelle.required' => 'Le libellé du Type de paiement est obligatoire.',
            'libelle.string' => 'Le libellé du Type de paiement doit être une chaîne de caractères.',
            'libelle.min' => 'Le libellé du Type de paiement doit contenir au moins 3 caractères.',
            'libelle.unique' => 'Ce Type de paiement existe déjà dans notre système.',

            'description.string' => 'La description du Type de paiement doit être une chaîne de caractères.',
            'description.min' => 'La description du Type de paiement doit contenir au moins 3 caractères.',
            'description.max' => 'La description du Type de paiement doit contenir au maximum 255 caractères.',
        ];
    }
}
