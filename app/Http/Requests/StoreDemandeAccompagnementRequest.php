<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDemandeAccompagnementRequest extends FormRequest
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

            "service_id"=>'required|integer|exists:services,id',
            "contact_tresormoney"=>'required|string|min:10|max:10',
            "montant_voulue"=>'required|integer',
            "montant_apayer"=>'required|integer',
            "commentaire"=>'nullable|string',
        ];
    }
    public function messages(): array
    {
        return [
            'service_id.required' => 'Le champ service est requis.',
            'service_id.integer' => 'Le service doit être un nombre entier.',
            'service_id.exists' => 'Le service sélectionné est invalide.',

            'contact_tresormoney.required' => 'Le contact Tresormoney est requis.',
            'contact_tresormoney.string' => 'Le contact Tresormoney doit être une chaîne de caractères.',
            'contact_tresormoney.min' => 'Le contact Tresormoney doit contenir au moins 10 caractères.',
            'contact_tresormoney.max' => 'Le contact Tresormoney ne peut pas dépasser 10 caractères.',

            'montant_voulue.required' => 'Le montant est requis.',
            'montant_voulue.integer' => 'Le montant doit être un nombre entier.',

            'montant_apayer.required' => 'Le montant à payer est requis.',
            'montant_apayer.integer' => 'Le montant à payer doit être un nombre entier.',

            'commentaire.string' => 'Le commentaire doit être une chaîne de caractères.',
        ];
    }

}
