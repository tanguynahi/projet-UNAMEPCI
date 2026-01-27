<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApprouverDemandeProduitRequest extends FormRequest
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
            'total_apayer' => 'required|integer|min:1000',
            'date' => 'required|date',
            'commentaire_approbation' => 'nullable|string|min:3'
        ];
    }

   public function messages()
   {
        return [
            'total_apayer.required' => "Le Total à payer est requis",
            'total_apayer.integer' => "Le Total à payer doit être un nombre entier",
            'total_apayer.min' => "Le Total doit être d'au moins 1000 F CFA",

            'date.required' => "La date est requise",
            'date.date' => "La date est invalide",

            // 'commentaire_approbation.nullable' => "Le Total à payer est requis",
            'commentaire_approbation.string' => "Le Total à payer doit être une chaîne de caractères.",
            'commentaire_approbation.min' => "Le Total doit contenir au moins 3 caractères.",
        ];
   }
}
