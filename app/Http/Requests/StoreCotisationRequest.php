<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCotisationRequest extends FormRequest
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
            'direction_id' => 'nullable|integer',
            'libelle' => 'required|string|min:3',
            'frequence_paiement' => 'required|string|min:3',
            'montant_a_payer' => 'required|integer|min:1', // Changer min:2 à min:1
            'date_debut' => 'required|date|before_or_equal:date_fin', // Vérifie que date_debut est avant date_fin
            'date_fin' => 'required|date|after_or_equal:date_debut', // Vérifie que date_fin est après date_debut
        ];
    }

    public function messages(): array
    {
        return [
            'direction_id.integer' => 'La direction doit être un entier.',
            'libelle.required' => 'Le champ libelle est obligatoire.',
            'libelle.min' => 'Le champ libelle doit contenir au moins 3 caractères.',
            'frequence_paiement.required' => 'Le champ fréquence de paiement est obligatoire.',
            'frequence_paiement.min' => 'Le champ fréquence de paiement doit contenir au moins 3 caractères.',
            'montant_a_payer.required' => 'Le montant à payer est obligatoire.',
            'montant_a_payer.integer' => 'Le montant à payer doit être un entier.',
            'montant_a_payer.min' => 'Le montant à payer doit être supérieur ou égal à 1.',
            'date_debut.required' => 'La date de début est obligatoire.',
            'date_debut.date' => 'La date de début doit être une date valide.',
            'date_debut.before_or_equal' => 'La date de début doit être avant ou égale à la date de fin.',
            'date_fin.required' => 'La date de fin est obligatoire.',
            'date_fin.date' => 'La date de fin doit être une date valide.',
            'date_fin.after_or_equal' => 'La date de fin doit être après ou égale à la date de début.',
        ];
    }
}
