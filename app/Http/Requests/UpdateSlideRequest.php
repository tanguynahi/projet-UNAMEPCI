<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSlideRequest extends FormRequest
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
            'lien_image' => 'nullable|image|mimes:jpg,jpeg,png',
            'titre' => 'required|string|min:3',
            'sous_titre' => 'nullable|string|min:3',
            'categorie' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            // 'lien_image.nullable' => 'L\image est requise.',
            'lien_image.image'=> "Veuillez choisir une image.",
            'lien_image.mimes'=> "Format de fichier nom pris en charge, veuillez choisir une image (jpg,jpeg,png)",
            'lien_image.uploaded'=> "Taille de l'image choisie trop grande, veuillez choisir une autre image (poids <= 2mo).",
            'titre.required' => 'Le titre est requis.',
            'titre.string' => 'Le titre doit être une chaîne de caractères.',
            'titre.min' => 'Le titre doit contenir au moins 3 caractères.',
            // 'sous_titre.required' => 'Le sous-titre est requis.',
            'sous_titre.string' => 'Le sous-titre doit être une chaîne de caractères.',
            'sous_titre.min' => 'Le sous-titre  doit contenir au moins 3 caractères.',
            'categorie.required' => 'La catégorie est requise.',
            'categorie.string' => 'La catégorie doit être une chaîne de caractères.',
        ];
    }
}
