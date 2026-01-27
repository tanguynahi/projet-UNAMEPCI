<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentProduitRequest extends FormRequest
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
            'produit_projet_id' => 'required|exists:produit_projets,id',
            'type_document_id' => 'required|exists:type_documents,id',
        ];
    }

    public function messages(): array
    {
        return [
            'produit_projet_id.required' => 'L\'id  du produit est obligatoire.',
            // 'produit_projet_id.integer' => 'L\'id  du produit doit être un entier.',
            'produit_projet_id.exists' => 'Le produit sélectionné est invalide.',

            'type_document_id.required' => 'Le type de document est obligatoire.',
            // 'type_document_id.integer' => 'Le type de document doit être un entier.',
            'type_document_id.exists' => 'Le type de document sélectionné est invalide.',
        ];
    }
}
