<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDocumentProduitRequest extends FormRequest
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
            'type_document_id' => 'required|integer|exists:type_documents,id',
            // 'produit_projet_id' => 'required|integer|exists:produit_projets,id',
        ];
    }

    public function messages()
    {
        return [
            'type_document_id.required' => 'Le type de document est requis.',
            'type_document_id.integer' => 'L\identifiant du type de document doit être un nombre entier.',
            'type_document_id.exists' => 'Le type de document sélectionnée est invalide.',

            // 'produit_projet_id.required' => 'Le produit est requis.',
            // 'produit_projet_id.integer' => 'L\identifiant du produit doit être un nombre entier.',
            // 'produit_projet_id.exists' => 'Le produit acssocié est invalide.',
        ];
    }
}
