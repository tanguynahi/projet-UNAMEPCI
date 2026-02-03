<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMutualisteRequest extends FormRequest
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
            'nom' => "required|string|min:2",
            'prenom' => "required|string|min:2",
            'email' => [
                'required',
                'email',
                'unique:mutualistes,email',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $value)) {
                        $fail("L'adresse email n'est pas valide.");
                    }
                },
            ],
            'matricule' => 'required|string|min:10|max:15|unique:mutualistes,matricule',
            'contact' => 'nullable|string|min:10|max:10|unique:mutualistes,contact',


            'specialite_id'        => 'required',
            'exists:specialites,id',
            'ville_id'             => 'nullable',
            'exists:villes,id',
            'fonctions'            => 'required',
            'string',
            'min:3',
            'droit_adhesion'       => 'required',
            'in:0,1',
            'carte_membre'         => 'required',
            'in:0,1',
            'cotisation_annuelle'  => 'required',
            'in:0,1',

            // "role" => "required|string",
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom est requis.',
            'nom.string' => 'Le nom doit être une chaîne de caractères.',
            'nom.min' => 'Le nom doit avoir au moins 2 caractères.',
            'prenom.required' => 'Le prénom est requis.',
            'prenom.string' => 'Le prénom doit être une chaîne de caractères.',
            'prenom.min' => 'Le prénom doit avoir au moins 2 caractères.',
            'email.required' => "L'adresse email est requise.",
            'email.email' => "L'adresse email doit être valide.",
            'email.unique' => "L'adresse email est déjà utilisée.",
            'matricule.required' => 'Le matricule est requis.',
            'matricule.string' => 'Le matricule doit être une chaîne de caractères.',
            'matricule.min' => 'Le matricule doit avoir au moins 10 caractères.',
            'matricule.max' => 'Le matricule ne doit pas dépasser 15 caractères.',
            'matricule.unique' => 'Le matricule est déjà utilisé.',
            'contact.string' => 'Le contact doit être une chaîne de caractères.',
            'contact.min' => 'Le contact doit avoir au moins 10 caractères.',
            'contact.max' => 'Le contact ne doit pas dépasser 10 caractères.',
            'contact.unique' => 'Le contact est déjà utilisé.',


            'specialite_id.required' => 'Veuillez sélectionner une spécialité.',
            'specialite_id.exists'   => 'La spécialité sélectionnée est invalide.',


            'ville_id.exists'   => 'La ville sélectionnée est invalide.',

            'fonctions.required' => 'Veuillez renseigner votre fonction.',
            'fonctions.min'      => 'La fonction doit contenir au moins 3 caractères.',

            'droit_adhesion.required' => 'Veuillez indiquer si le droit d’adhésion a été payé.',
            'droit_adhesion.in'       => 'Valeur invalide pour le droit d’adhésion.',

            'carte_membre.required' => 'Veuillez indiquer si la carte membre a été payée.',
            'carte_membre.in'       => 'Valeur invalide pour la carte membre.',

            'cotisation_annuelle.required' => 'Veuillez indiquer si la cotisation annuelle a été payée.',
            'cotisation_annuelle.in'       => 'Valeur invalide pour la cotisation annuelle.',
        ];
    }
}
