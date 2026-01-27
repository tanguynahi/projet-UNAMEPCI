<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjetRequest extends FormRequest
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
            'description' => 'required|string|min:3',
        ];
    }

    public function messages()
    {
        return [
            'lien_photo.image'=> "Veuillez choisir une image.",
            'lien_photo.mimes'=> "Format de fichier nom pris en charge, veuillez choisir une image (jpg,jpeg,png)",
            'lien_photo.uploaded'=> "Taille de l'image choisie trop grande, veuillez choisir une autre image (poids <= 2mo).",
            'libelle.required' => 'Le libellé du projet  est obligatoire.',
            'libelle.string' => 'Le libellé du projet  doit être une chaîne de caractères.',
            'libelle.min' => 'Le libellé du projet doit contenir au moins 3 caractères.',
            'description.required' => 'La description du projet  est obligatoire.',
            'description.string' => 'La description du projet  doit être une chaîne de caractères.',
            'description.min' => 'La description du projet  doit contenir au moins 3 caractères.',
        ];
    }
}
