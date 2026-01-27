<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaiementRequest extends FormRequest
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
            'reference' => "required|string|min:2",
            'montant' => "required|integer|min:2",
            'montant_total' => "required|integer|min:2",
            'frais' => "required|integer|min:2",
            'moyen_paiement' => "required|string|min:2",
            "mutualiste_id" => "required|integer|exists:mutualiste,id",
            "type_paiemen_id" => "required|integer|exists:type_paiemen,id",
        ];
    }
    public function messages()
    {
        return [
            'reference.required' => ' la reference obligatoire.',
            'reference.string' => 'la reference doit est une chaine de caractere',
            'reference.min' => 'la refernce doit etre au moins 2 caracteres.',
            'montant.required' => 'le montant est obligatoire',
            'montant.integer' => 'le montant doit etre de type entier',
            'montant.min' => 'le montant doit etre au moins 2 caracteres.',
            'montant_total.required' => 'le montant total est obligatoire',
            'montant_total.integer' => 'le montant total doit etre de type entier',
            'montant_total.min' => 'le montant total doit etre au moins 2 caracteres.',
            'frais.required' => 'le frais est obligatoire',
            'frais.integer' => 'le frais doit etre de type entier',
            'frais.min' => 'le frais doit etre au moins 2 caracteres.',
            'moyen_paiement.required' => ' le moyen paiement obligatoire.',
            'moyen_paiement.string' => 'le moyen paiement doit est une chaine de caractere',
            'moyen_paiement.min' => 'le moyen paiement doit etre au moins 2 caracteres.',
            'mutualiste_id.required' => 'le mutualiste id est obligatoire',
            'mutualiste_id.integer' => 'le mutualiste id doit etre de type entier',
            'mutualiste_id.min' => 'le mutualiste id doit etre au moins 2 caracteres.',
            'type_paiemen_id.required' => 'le type paiement id est obligatoire',
            'type_paiemen_id.integer' => 'le type paiement id doit etre de type entier',
            'type_paiemen_id.min' => 'le type paiement id doit etre au moins 2 caracteres.',
        ];
    }
}
