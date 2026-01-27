<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCotisationMutualisteRequest extends FormRequest
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
            'cotisation_id' => 'required|integer|exists:cotisations,id',
            'mutualiste_id' => 'required|integer|exists:mutualistes,id',
        ];
    }
    public function messages(): array
    {
        return [
            'cotisation_id.required' => 'Le champ cotisation est requis.',
            'cotisation_id.integer' => 'Le champ cotisation doit être un entier.',
            'cotisation_id.exists' => 'La cotisation sélectionnée n\'existe pas dans notre base de données.',

            'mutualiste_id.required' => 'Le champ mutualiste est requis.',
            'mutualiste_id.integer' => 'Le champ mutualiste doit être un entier.',
            'mutualiste_id.exists' => 'Le mutualiste sélectionné n\'existe pas dans notre base de données.',
        ];
    }
}
