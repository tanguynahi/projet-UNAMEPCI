<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProduitProjetRequest extends FormRequest
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
            'lien_photo' => 'nullable|image|mimes:jpg,jpeg,png',
            'libelle' => 'required|string|min:3',
            'cout' => 'required|integer',
            'contribution' => 'required|integer',
            'quantite' => 'nullable|integer',
            'description' => 'required|string|min:3',
        ];
    }

    public function messages()
    {
        return [
            'lien_photo.image'=> "Veuillez choisir une image.",
            'lien_photo.mimes'=> "Format de fichier nom pris en charge, veuillez choisir une image (jpg,jpeg,png)",
            'lien_photo.uploaded'=> "Taille de l'image choisie trop grande, veuillez choisir une autre image (poids <= 2mo).",
            'libelle.required' => 'Le libellé du produit  est obligatoire.',
            'libelle.string' => 'Le libellé du produit  doit être une chaîne de caractères.',
            'libelle.min' => 'Le libellé du produit doit contenir au moins 3 caractères.',
            'cout.required' => 'Le cout du produit  est obligatoire.',
            'cout.integer' => 'Le cout du produit  doit être un nombre entier.',
            'contribution.required' => 'La contribution du produit  est obligatoire.',
            'contribution.integer' => 'La contribution du produit  doit être un nombre entier.',
            // 'quantite.required' => 'La quantité du produit  est obligatoire.',
            'quantite.integer' => 'La quantité du produit  doit être un nombre entier.',
            'description.required' => 'La description du produit  est obligatoire.',
            'description.string' => 'La description du produit  doit être une chaîne de caractères.',
            'description.min' => 'La description du produit  doit contenir au moins 3 caractères.',
        ];
    }
}
