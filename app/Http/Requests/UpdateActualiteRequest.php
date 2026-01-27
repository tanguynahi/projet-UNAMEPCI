<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateActualiteRequest extends FormRequest
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
            'description' => 'required|min:3',
            'date_actualite' => 'required|date',
        ];
    }

    public function messages()
    {
        return [
            // 'lien_photo.required' => 'La photo de l\'actualité est obligatoire.',
            'lien_photo.image'=> "Veuillez choisir une image.",
            'lien_photo.mimes'=> "Format de fichier nom pris en charge, veuillez choisir une image (jpg,jpeg,png)",
            'lien_photo.uploaded'=> "Taille de l'image choisie trop grande, veuillez choisir une autre image (poids <= 2mo).",
            'libelle.required' => 'Le libellé de l\'actualité  est obligatoire.',
            'libelle.string' => 'Le libellé de l\'actualité  doit être une chaîne de caractères.',
            'libelle.min' => 'Le libellé de \'actualité doit contenir au moins 3 caractères.',
            'description.required' => 'La description de l\'actualité  est obligatoire.',
            // 'description.string' => 'La description de l\'actualité  doit être une chaîne de caractères.',
            'description.min' => 'La description de l\'actualité  doit contenir au moins 3 caractères.',
            'date_actualite.required' => 'La date de l\'actualité  est obligatoire.',
            'date_actualite.date' => 'Format de La date de l\'actualité  incorrect.',
        ];
    }
}
