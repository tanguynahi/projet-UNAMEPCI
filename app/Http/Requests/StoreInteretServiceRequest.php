<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInteretServiceRequest extends FormRequest
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
            'id_service' => 'required|integer|exists:services,id',
            'montant_debut' => 'required|integer|min:1|max:50000000|',
            'montant_fin' => 'required|integer|min:1|max:50000000',
            'taux_interet' => 'required|integer|min:1|max:100',
        ];
    }
    public function messages(): array
    {
        return [
            'id_service.required' => 'Le champ service est obligatoire.',
            'id_service.integer' => 'Le champ service doit être un nombre entier.',
            'id_service.exists' => 'Le service sélectionné n\'existe pas dans la base de données.',

            'montant_debut.required' => 'Le champ montant de minimun est obligatoire.',
            'montant_debut.integer' => 'Le champ montant de minimun doit être un nombre entier.',
            'montant_debut.min' => 'Le champ montant de minimun doit être au moins 1.',
            'montant_debut.max' => 'Le champ montant de minimun ne  doit pas  être plus de 5 000 000 Fcfa.',

            'montant_fin.required' => 'Le champ montant de maximun est obligatoire.',
            'montant_fin.integer' => 'Le champ montant de maximun doit être un nombre entier.',
            'montant_fin.min' => 'Le champ montant de maximun doit être au moins 1.',
            'montant_fin.max' => 'Le champ montant de maximun ne  doit pas  être plus de 5 000 000 Fcfa.',

            'taux_interet.required' => 'Le champ taux d\'intérêt est obligatoire.',
            'taux_interet.integer' => 'Le champ taux d\'intérêt doit être un nombre entier.',
            'taux_interet.min' => 'Le champ taux d\'intérêt doit être au moins 1.',
            'taux_interet.max' => 'Le champ taux d\'intérêt ne doit pas etre plus de 100.',
        ];
    }
}
