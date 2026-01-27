<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjetMutualisteRequest extends FormRequest
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
            'mutualiste_id' => 'required|integer|exists:mutualistes,id',
            'produit_projet_id' => 'required|integer|exists:produit_projets,id',
            'montant_produit' => 'required|numeric|gt:0',
            'total_apayer' => 'required|numeric|gt:0',
            'date' => 'required|date',
            'commentaire' => 'nullable|string|min:3',
        ];
    }

    public function messages(): array
    {
        return [
            'mutualiste_id.required' => 'Le mutualiste est requis.',
            'mutualiste_id.integer' => 'L\'identifiant du mutualiste doit être un nombre entier.',
            'mutualiste_id.exists' => 'Le mutualiste sélectionné est invalide.',
            'produit_projet_id.required' => 'Le bien est requis.',
            'produit_projet_id.integer' => 'L\'identifiant du bien doit être un nombre entier.',
            'produit_projet_id.exists' => 'Le bien sélectionné est invalide.',
            'montant_produit.required' => 'Le montant du bien est requis.',
            'montant_produit.numeric' => 'Le montant du bien doit être numérique.',
            'montant_produit.gt' => 'Le montant du bien doit être supérieur à zéro.',
            'total_apayer.required' => 'Le total à payer est requis.',
            'total_apayer.numeric' => 'Le total à payer doit être numérique.',
            'total_apayer.gt' => 'Le total à payer doit être supérieur à zéro.',
            'date.required' => 'La date est requise.',
            'date.date' => 'La date n\'est pas au format valide.',
            'commentaire.string' => 'Le commentaire doit être une chaîne de caractères.',
            'commentaire.min' => 'Le commentaire doit avoir au moins 3 caractères.',
        ];
    }
}
