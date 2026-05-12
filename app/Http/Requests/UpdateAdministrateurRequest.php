<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateAdministrateurRequest extends FormRequest
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

            'nom' => "required|string|min:2",

            'prenom' => "required|string|min:2",

            'email' => [
                'required',
                'email',

                Rule::unique('administrateurs', 'email')
                    ->ignore($this->administrateur),

                function ($attribute, $value, $fail) {

                    if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $value)) {

                        $fail("L'adresse email n'est pas valide.");
                    }
                },
            ],

            "ville_id" => "required|integer|exists:villes,id",

            "lien_photo" => "nullable|image|mimes:jpg,jpeg,png|max:2048",

            "adresse" => "nullable|string|min:3",

            "genre" => "required|string|min:5",

            'contact' => [
                'required',
                'string',
                'min:10',
                'max:10',

                Rule::unique('administrateurs', 'contact')
                    ->ignore($this->administrateur),
            ],

            'role_id' => 'required|exists:roles,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages()
    {
        return [

            'nom.required' => 'Le nom est obligatoire.',
            'nom.string' => 'Le nom doit être une chaîne de caractères.',
            'nom.min' => 'Le nom doit comporter au moins 2 caractères.',

            'prenom.required' => 'Le prénom est obligatoire.',
            'prenom.string' => 'Le prénom doit être une chaîne de caractères.',
            'prenom.min' => 'Le prénom doit comporter au moins 2 caractères.',

            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email n\'est pas valide.',
            'email.unique' => 'L\'adresse email est déjà utilisée.',

            'ville_id.required' => 'La ville est obligatoire.',
            'ville_id.integer' => 'L\'identifiant de la ville doit être un entier.',
            'ville_id.exists' => 'La ville sélectionnée n\'existe pas.',

            'lien_photo.image' => "Veuillez choisir une image.",
            'lien_photo.mimes' => "Format de fichier non pris en charge, veuillez choisir une image (jpg, jpeg, png).",
            'lien_photo.max' => "La taille de l'image ne doit pas dépasser 2 Mo.",
            'lien_photo.uploaded' => "La taille de l'image choisie est trop grande.",

            'adresse.string' => 'L\'adresse doit être une chaîne de caractères.',
            'adresse.min' => 'L\'adresse doit comporter au moins 3 caractères.',

            'genre.required' => 'Le genre est obligatoire.',
            'genre.string' => 'Le genre doit être une chaîne de caractères.',
            'genre.min' => 'Le genre doit comporter au moins 5 caractères.',

            'contact.required' => 'Le contact est obligatoire.',
            'contact.min' => 'Le contact doit comporter au moins 10 chiffres.',
            'contact.max' => 'Le contact ne doit pas comporter plus de 10 chiffres.',
            'contact.unique' => 'Le contact est déjà utilisé.',

            'role_id.required' => 'Le rôle est obligatoire.',
            'role_id.exists' => 'Le rôle sélectionné est invalide.',
        ];
    }
}
