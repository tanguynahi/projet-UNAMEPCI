<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateParametreRequest extends FormRequest
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
            'lien_logo' => 'nullable|image|mimes:jpg,jpeg,png',
            'lien_photo_directeur' => 'nullable|image|mimes:jpg,jpeg,png',
            'nom_site_web' => 'required|string|min:3',
            'mot_du_directeur' => 'required|string|min:3',
            'email_1' => [
                'required',
                'email',
                // 'unique:parametres,email_1',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $value)) {
                        $fail("L'adresse email 1 n'est pas valide.");
                    }
                },
            ],
            'email_2' => [
                'nullable',
                'email',
                // 'unique:parametres,email_2',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $value)) {
                        $fail("L'adresse email 2 n'est pas valide.");
                    }
                },
            ],
            "adresse_site" => "nullable|string|min:3",
            'contact_1' => 'required|string|min:10|max:10',
            'contact_2' => 'nullable|string|min:10|max:10',
            // 'contact_1' => 'required|string|min:10|max:10|unique:parametres,contact_1',
            // 'contact_2' => 'nullable|string|min:10|max:10|unique:parametres,contact_2',
            'lien_google_map' => 'nullable|url',
            'lien_video' => 'nullable|url',
        ];
    }

    public function messages()
    {
        return [
            // 'lien_logo.required' => 'Le logo est requis.',
            'lien_logo.image' => "Veuillez choisir une image.",
            'lien_logo.mimes' => "Format de fichier nom pris en charge, veuillez choisir une image (jpg,jpeg,png)",
            // 'lien_logo.uploaded' => "Taille de l'image choisie trop grande, veuillez choisir une autre image (poids <= 2mo).",
            // 'lien_logo.max' => 'La taille de l\'image est trop grande. Veuillez choisir une image de taille inférieure à 2 Mo.',

            // 'lien_photo_directeur.required' => 'La photo du Directeur est requise.',
            'lien_photo_directeur.image' => "Veuillez choisir une image.",
            'lien_photo_directeur.mimes' => "Format de fichier nom pris en charge, veuillez choisir une image (jpg,jpeg,png)",
            // 'lien_photo_directeur.uploaded' => "Taille de l'image choisie trop grande, veuillez choisir une autre image (poids <= 2mo).",
            // 'lien_photo_directeur.max' => 'La taille de l\'image est trop grande. Veuillez choisir une image de taille inférieure à 2 Mo.',

            'nom_site_web.required' => 'Le nom du site web  est requis.',
            'nom_site_web.string' => 'Le nom du site web  doit être une chaîne de caractères.',
            'nom_site_web.min' => 'Le nom du site web doit contenir au moins 3 caractères.',

            'mot_du_directeur.required' => 'Le mot du Directeur  est requis.',
            'mot_du_directeur.string' => 'Le mot du Directeur  doit être une chaîne de caractères.',
            'mot_du_directeur.min' => 'Le mot du Directeur  doit contenir au moins 3 caractères.',
            'adresse_site.string' => 'L\'adresse doit être une chaîne de caractères.',
            'adresse_site.min' => 'L\'adresse doit comporter au moins 3 caractères.',

            'email_1.required' => 'L\'adresse email 1 est requise.',
            'email_1.email' => 'L\'adresse email 1 n\'est pas valide.',
            'email_1.unique' => 'L\'adresse email 1 est déjà utilisée.',
            // 'email_1.nullable' => 'L\'adresse email est requise.',
            'email_2.email' => 'L\'adresse email 2 n\'est pas valide.',
            'email_2.unique' => 'L\'adresse email 2 est déjà utilisée.',

            'contact_1.required' => 'Le contact 1 est requis.',
            'contact_1.min' => 'Le contact 1 doit comporter au moins 10 chiffres.',
            'contact_1.max' => 'Le contact 1 ne doit pas comporter plus de 10 chiffres.',
            'contact_1.unique' => 'Le contact 1 est déjà utilisé.',

            'contact_2.min' => 'Le contact 2 doit comporter au moins 10 chiffres.',
            'contact_2.max' => 'Le contact 2 ne doit pas comporter plus de 10 chiffres.',
            'contact_2.unique' => 'Le contact 2 est déjà utilisé.',
            'lien_google_map.url' => 'Le lien google map n\'est pas une URL valide.',
            'lien_video.url' => 'Le lien vidéo n\'est pas une URL valide.',
        ];
    }
}
