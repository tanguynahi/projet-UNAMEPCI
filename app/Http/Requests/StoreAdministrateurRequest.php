<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdministrateurRequest extends FormRequest
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
            'nom'=>"required|string|min:2",
            'prenom'=>"required|string|min:2",
            'password'=> "required|string|min:6",
            'email' => [
                'required',
                'email',
                'unique:administrateurs,email',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $value)) {
                        $fail("L'adresse email n'est pas valide.");
                    }
                },
            ],

            "ville_id" => "required|integer|exists:villes,id",
            "lien_photo" => "nullable|image|mimes:jpg,jpeg,png",
            "adresse" => "nullable|string|min:3",
            "genre" => "required|string|min:5",
            'contact' => 'required|string|min:10|max:10|unique:administrateurs,contact',
        ];
    }

    public function messages()
    {
        return [
            'nom.required' => 'Le nom est obligatoire.',
            'nom.string' => 'Le nom doit être une chaîne de caractères.',
            'nom.min' => 'Le nom doit comporter au moins 2 caractères.',
            'prenom.required' => 'Le prenom est obligatoire.',
            'prenom.string' => 'Le prenom doit être une chaîne de caractères.',
            'prenom.min' => 'Le prenom doit comporter au moins 2 caractères.',
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email n\'est pas valide.',
            'email.unique' => 'L\'adresse email est déjà utilisée.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.string' => 'Le mot de passe doit être une chaîne de caractères.',
            'password.min' => 'Le mot de passe doit comporter au moins 6 caractères.',
            'ville_id.required' => 'La ville est obligatoire.',
            'ville_id.integer' => 'L\'identifiant de la ville doit être un entier.',
            'ville_id.exists' => 'La ville sélectionnée n\'existe pas.',
            // 'lien_photo.required' => 'La photo est obligatoire.',
            'lien_photo.image'=> "Veuillez choisir une image.",
            'lien_photo.mimes'=> "Format de fichier nom pris en charge, veuillez choisir une image (jpg,jpeg,png)",
            'lien_photo.uploaded'=> "Taille de l'image choisie trop grande, veuillez choisir une autre image (poids <= 2mo).",
            // 'adresse.required' => 'L\'adresse est obligatoire.',
            'adresse.string' => 'L\'adresse doit être une chaîne de caractères.',
            'adresse.min' => 'L\'adresse doit comporter au moins 3 caractères.',
            'genre.required' => 'Le genre est obligatoire.',
            'genre.string' => 'Le genre doit être une chaîne de caractères.',
            'genre.min' => 'Le genre doit comporter au moins 5 caractères.',
            'contact.required' => 'Le contact est obligatoire.',
            // 'contact.digits' => 'Le contact doit contenir des chiffres.',
            // 'contact.integer' => 'Le contact doit être composer de 10 chiffres.',
            'contact.min' => 'Le contact doit comporter au moins 10 chiffres.',
            'contact.max' => 'Le contact ne doit pas comporter plus de 10 chiffres.',
            'contact.unique' => 'Le contact est déjà utilisé.',
        ];
    }
}
