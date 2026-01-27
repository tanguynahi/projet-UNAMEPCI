<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFacturationRequest extends FormRequest
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
            'mutualiste_id' => 'required|exists:mutualistes,id',
            'projet_mutualiste_id' => 'required|exists:projet_mutualistes,id',
            'redevance_id' => 'required|exists:redevances,id',
            'periode_id' => 'required|exists:periodes,id',
            'total_apayer' => 'required|numeric|min:500|multiple_of:100',
        ];
    }

    /**
     * Obtenez les messages d'erreur personnalisés pour les règles de validation.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'mutualiste_id.required' => 'Le mutualiste est requis.',
            'mutualiste_id.exists' => 'Le mutualiste sélectionné est invalide.',
            'projet_mutualiste_id.required' => 'Le bien est requis.',
            'projet_mutualiste_id.exists' => 'Le bien sélectionné est invalide.',
            'redevance_id.required' => 'La redevance est requise.',
            'redevance_id.exists' => 'La redevance sélectionnée est invalide.',
            'periode_id.required' => 'La période est requise.',
            'periode_id.exists' => 'La période sélectionnée est invalide.',
            'total_apayer.required' => 'Le montant total à payer est requis.',
            'total_apayer.numeric' => 'Le montant total à payer doit être un nombre.',
            'total_apayer.min' => 'Le montant total à payer doit être au moins 500 F CFA.',
            'total_apayer.multiple_of' => 'Le montant total à payer doit être un multiple de 100.',
        ];
    }
}
