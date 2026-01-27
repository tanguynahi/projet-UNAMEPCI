<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FinaliserInscriptionMutualisteRequest extends FormRequest
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
            'corp_id' => "required|integer|exists:corps,id",
            'grade_id' => "required|integer|exists:grades,id",
            'ville_id' => "required|integer|exists:villes,id",
            'type_piece_id' => "required|integer|exists:type_pieces,id",
            'numero_piece' => "required|string|min:5|max:20|unique:mutualistes,numero_piece",
            'date_etablissement_piece' => "required|date",
            'lieu_etablissement_piece' => "required|string|min:2|max:100",
            'unite' => "nullable|string|min:2|max:50",
            'contact' => 'required|string|min:10|max:10',
            'contact_2' => 'nullable|string|min:10|max:10|unique:mutualistes,contact_2',
            'password' => "required|string|min:6|confirmed",
            "adresse" => "nullable|string|min:3",
            "genre" => "required|string|min:5",
            'date_naissance' => "required|date",
            'lieu_naissance' => "required|string|min:2|max:100",
            "lien_photo" => "nullable|image|mimes:jpg,jpeg,png",
            "documents.*" => "nullable|image|mimes:jpg,jpeg,png,pdf,docx,dot,dotx,eml",

        ];
    }

    public function messages(): array
    {
        return [
            'corp_id.required' => 'Le corps est obligatoire.',
            'corp_id.integer' => 'Le corps doit être un entier.',
            'corp_id.exists' => 'Le corps sélectionné est invalide.',
            'grade_id.required' => 'Le grade est obligatoire.',
            'grade_id.integer' => 'Le grade doit être un entier.',
            'grade_id.exists' => 'Le grade sélectionné est invalide.',
            'ville_id.required' => 'La ville est obligatoire.',
            'ville_id.integer' => 'L\identifiant ville doit être un entier.',
            'ville_id.exists' => 'La ville sélectionnée est invalide.',
            'type_piece_id.required' => 'Le type de pièce est obligatoire.',
            'type_piece_id.integer' => 'Le type de pièce doit être un entier.',
            'type_piece_id.exists' => 'Le type de pièce sélectionné est invalide.',
            'numero_piece.required' => 'Le numéro de pièce est obligatoire.',
            'numero_piece.string' => 'Le numéro de pièce doit être une chaîne de caractères.',
            'numero_piece.min' => 'Le numéro de pièce doit avoir au moins 5 caractères.',
            'numero_piece.max' => 'Le numéro de pièce ne peut pas dépasser 20 caractères.',
            'numero_piece.unique' => 'Le numéro de pièce a déjà été pris.',
            'date_etablissement_piece.required' => 'La date d\'établissement de la pièce est obligatoire.',
            'date_etablissement_piece.date' => 'La date d\'établissement de la pièce doit être une date valide.',
            'lieu_etablissement_piece.required' => 'Le lieu d\'établissement de la pièce est obligatoire.',
            'lieu_etablissement_piece.string' => 'Le lieu d\'établissement de la pièce doit être une chaîne de caractères.',
            'lieu_etablissement_piece.min' => 'Le lieu d\'établissement de la pièce doit avoir au moins 2 caractères.',
            'lieu_etablissement_piece.max' => 'Le lieu d\'établissement de la pièce ne peut pas dépasser 100 caractères.',
            'unite.string' => 'L\'unité doit être une chaîne de caractères.',
            'unite.min' => 'L\'unité doit avoir au moins 2 caractères.',
            'unite.max' => 'L\'unité ne peut pas dépasser 50 caractères.',
            'contact.required' => 'Le contact n° 1 est obligatoire.',
            'contact.string' => 'Le contact n° 1 doit être une chaîne de caractères.',
            'contact.min' => 'Le contact n° 1 doit avoir 10 caractères.',
            'contact.max' => 'Le contact n° 1 doit avoir 10 caractères.',
            'contact_2.string' => 'Le contact n° 2 doit être une chaîne de caractères.',
            'contact_2.min' => 'Le contact n° 2 doit avoir 10 caractères.',
            'contact_2.max' => 'Le contact n° 2 doit avoir 10 caractères.',
            'contact_2.unique' => 'Le contact n° 2 a déjà été pris.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.string' => 'Le mot de passe doit être une chaîne de caractères.',
            'password.min' => 'Le mot de passe doit avoir au moins 6 caractères.',
            'password.confirmed' => 'Les mots de passes ne correspondent pas.',
            'adresse.string' => 'L\'adresse doit être une chaîne de caractères.',
            'adresse.min' => 'L\'adresse doit avoir au moins 3 caractères.',
            'genre.required' => 'Le genre est obligatoire.',
            'genre.string' => 'Le genre doit être une chaîne de caractères.',
            'genre.min' => 'Le genre doit avoir au moins 5 caractères.',
            'date_naissance.required' => 'La date de naissance est obligatoire.',
            'date_naissance.date' => 'La date de naissance doit être une date valide.',
            'lieu_naissance.required' => 'Le lieu de naissance est obligatoire.',
            'lieu_naissance.string' => 'Le lieu de naissance doit être une chaîne de caractères.',
            'lieu_naissance.min' => 'Le lieu de naissance doit avoir au moins 2 caractères.',
            'lieu_naissance.max' => 'Le lieu de naissance ne peut pas dépasser 100 caractères.',
            'lien_photo.image' => 'La photo doit être une image.',
            'lien_photo.mimes' => 'La photo doit être un fichier de type: jpg, jpeg, png.',

            // 'documents.required' => 'Le champ document est requis.',
            'documents.image' => 'Le fichier doit être une image (jpg, jpeg, png).',
            'documents.mimes' => 'Le fichier doit être de type :values.',
        ];
    }
}
