<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
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


    public function rules()
    {
        return [

            // ================= INFORMATIONS PERSONNELLES =================
            'typeAdhesion' => 'required|in:revision,nouvelle',
            'civilite' => 'required|string|max:10',
            'nom' => 'required|string|min:2|max:100',
            'prenom' => 'required|string|min:2|max:150',
            'contact' => 'required|string|min:8|max:20',
            'contact_2' => 'nullable|string|max:20',
            // 'fax' => 'nullable|string|max:50',
            'email' => 'nullable|email|unique:users,email',
            'date_naissance' => 'required|date',
            'lieu_naissance' => 'required|string|max:100',
            'nationalite' => 'required|string|max:100',
            'situation_matrimoniale' => 'required|string|max:50',
            // 'nombre_charge' => 'required|integer|min:0',
            'adresse' => 'required|string|max:255',
            'date_adhesion_unamepci' => 'required|date',

            // ================= PIECE D’IDENTITE =================
            'type_piece_id' => 'required|integer|exists:type_pieces,id',
            'numero_piece' => 'required|string|max:100',
            'date_etablissement_piece' => 'required|date',
            'lieu_etablissement_piece' => 'required|string|max:100',
            'date_expiration_piece' => ['required', 'date'],

            // ================= ONMCI =================
            'numero_inscription_ONMCI' => 'required|string|max:100',
            // 'pseudonyme_recon_ONMCI' => 'required|string|max:100',

            // ================= PROFESSION =================
            'matricule' => 'nullable|string|max:100',
            'raison_social_primaire' => 'required|string|max:150',
            'specialite_id' => 'required|integer|exists:specialites,id',
            'fonction' => 'required|string|max:100',
            'date_debut_metier' => 'required|date',
            'nombre_annee_experience' => 'required|integer|min:0',
            'statut_emploi' => 'required|string|max:50',
            // 'domaine_activite' => 'required|string|max:255',
            'nom_employeur_principale' => 'required|string|max:150',
            // 'montant_cotis_annuel' => 'required|numeric|min:0',

             'niveau_intervention' => ['required', 'string'],
            'precise_intervention' => ['nullable', 'string'],

            'ville_personnel_id' => ['required', 'exists:villes,id'],
            'commune_personnel' => ['nullable', 'string', 'max:255'],

            'date_recrutement' => 'required|date',

            // ================= ENTREPRISE =================
            'sigle' => 'required|string|max:150',
            'date_creation' => 'required|date',
            'numero_autorisation' => 'required|string|max:150',
            'num_immatriculation' => 'required|string|max:150',
            'forme_juridique_id' => 'required|integer|exists:forme_juridiques,id',
            'precise_forme_juridique' => 'nullable|string|max:150',
            'ville_id' => 'required|integer|exists:villes,id',
            'commune' => 'required|string|max:100',
            'quartier' => 'required|string|max:100',
            'rue' => 'required|string|max:150',
            'adresse_postale_entreprise' => 'required|string|max:255',
            'email_entreprise' => 'required|email',
            'telephone_entreprise' => 'required|string|max:20',
            'fax_entreprise' => 'nullable|string|max:50',
            'localisation_entreprise' => 'required|string|max:255',

            // ================= FREELANCE =================
            // 'raison_social_secondaire_freelance' => 'nullable|string|max:150',
            // 'fonction_occupe_freelance' => 'nullable|string|max:100',
            // 'type_contrat_freelance' => 'nullable|string|max:50',
            // 'telephone_freelance' => 'nullable|string|max:20',
            // 'localisation_freelance' => 'nullable|string|max:255',
            // 'adresse_postale_freelance' => 'nullable|string|max:255',
            // 'domaine_activite_freelance' => 'nullable|string|max:255',
            // 'fax_freelance' => 'nullable|string|max:50',

            // ================= RELATION / AUTEUR =================
            // 'relation_tiers' => 'required|boolean',
            // // 'nom_relation' => 'required_if:relation_tiers,1|string|max:150',
            // 'etre_auteur' => 'nullable|boolean',
            // 'nom_auteur' => 'nullable|string|max:150',

            // ================= SECURITE =================
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->mixedCase()->numbers()->symbols()
            ],

            // ================= FICHIERS =================
            'lien_photo' => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'pieces_joints_recto' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'pieces_joints_verso' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'photo_couverture' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
            'document_carte_inscript_ONMCI' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'document_autorisation_ouverture' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'photo_identite_1' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'signature' => 'required|image|mimes:png,jpg,jpeg|max:5120',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Le champ :attribute est obligatoire.',
            'email.email' => 'Veuillez fournir une adresse email valide.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'date' => 'Le champ :attribute doit être une date valide.',
            'integer' => 'Le champ :attribute doit être un nombre.',
            'numeric' => 'Le champ :attribute doit être un nombre.',
            'confirmed' => 'Les mots de passe ne correspondent pas.',
            'image' => 'Le champ :attribute doit être une image.',
            'mimes' => 'Le fichier :attribute doit être de type :values.',
            'max' => 'Le champ :attribute ne doit pas dépasser :max.',
            // 'nom_relation.required_if' => 'Le nom de la relation est obligatoire.',
        ];
    }

    // public function messages(): array
    // {
    //     return [
    //         'required' => 'Le champ :attribute est obligatoire.',
    //         'email' => 'L\'adresse email doit être valide.',
    //         'unique' => 'Cette valeur est déjà utilisée.',
    //         'date' => 'Le champ :attribute doit être une date valide.',
    //         'before' => 'La date doit être antérieure à aujourd\'hui.',
    //         'integer' => 'Le champ :attribute doit être un nombre entier.',
    //         'numeric' => 'Le champ :attribute doit être un nombre.',
    //         'min' => [
    //             'numeric' => 'Le champ :attribute doit être au moins :min.',
    //             'string' => 'Le champ :attribute doit avoir au moins :min caractères.',
    //         ],
    //         'max' => [
    //             'string' => 'Le champ :attribute ne peut pas dépasser :max caractères.',
    //         ],
    //         'size' => 'Le champ :attribute doit contenir exactement :size caractères.',
    //         'in' => 'La valeur sélectionnée pour :attribute est invalide.',
    //         'exists' => 'La valeur sélectionnée pour :attribute est invalide.',
    //         'mimes' => 'Le fichier doit être de type : :values.',
    //         'file' => 'Le champ :attribute doit être un fichier.',
    //         'confirmed' => 'La confirmation du mot de passe ne correspond pas.',
    //         'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
    //     ];
    // }


    //    public function attributes(): array
    // {
    //     return [
    //         'typeAdhesion' => 'type d\'adhésion',
    //         'civilite' => 'civilité',
    //         'nom' => 'nom',
    //         'prenom' => 'prénom',
    //         'contact' => 'contact principal',
    //         'contact_2' => 'contact secondaire',
    //         'fax' => 'fax',

    //         'date_naissance' => 'date de naissance',
    //         'lieu_naissance' => 'lieu de naissance',
    //         'nationalite' => 'nationalité',
    //         'situation_matrimoniale' => 'situation matrimoniale',
    //         'nombre_charge' => 'nombre de personnes à charge',
    //         'adresse' => 'adresse personnelle',
    //         'date_adhesion_unamepci' => 'date d\'adhésion UNAMEPCI',
    //         'type_piece_id' => 'type de pièce d\'identité',
    //         'numero_piece' => 'numéro de pièce',
    //         'date_etablissement_piece' => 'date d\'établissement de la pièce',
    //         'lieu_etablissement_piece' => 'lieu d\'établissement de la pièce',
    //         'numero_inscription_ONMCI' => 'numéro d\'inscription ONMCI',
    //         'pseudonyme_recon_ONMCI' => 'pseudonyme de reconnaissance ONMCI',
    //         'raison_social_primaire' => 'raison sociale principale',
    //         'specialite_id' => 'spécialité',
    //         'fonction' => 'fonction',
    //         'date_debut_metier' => 'date de début dans le métier',
    //         'nombre_annee_experience' => 'nombre d\'années d\'expérience',
    //         'statut_emploi' => 'statut d\'emploi',
    //         'domaine_activite' => 'domaine d\'activité',
    //         'nom_employeur_principale' => 'nom de l\'employeur principal',
    //         'montant_cotis_annuel' => 'montant cotisation annuelle',
    //         'date_recrutement' => 'date de recrutement',
    //         'sigle' => 'sigle de l\'entreprise',
    //         'date_creation' => 'date de création',
    //         'numero_autorisation' => 'numéro d\'autorisation',
    //         'num_immatriculation' => 'numéro d\'immatriculation',
    //         'forme_juridique_id' => 'forme juridique',
    //         'precise_forme_juridique' => 'précision forme juridique',
    //         'ville_id' => 'ville',
    //         'commune' => 'commune',
    //         'quartier' => 'quartier',
    //         'rue' => 'rue',
    //         'adresse_postale_entreprise' => 'adresse postale entreprise',
    //         'localisation_entreprise' => 'localisation entreprise',
    //         'email_entreprise' => 'email entreprise',
    //         'telephone_entreprise' => 'téléphone entreprise',
    //         'fax_entreprise' => 'fax entreprise',
    //         'raison_social_secondaire_freelance' => 'raison sociale freelance',
    //         'fonction_occupe_freelance' => 'fonction occupée freelance',
    //         'type_contrat_freelance' => 'type de contrat freelance',
    //         'telephone_freelance' => 'téléphone freelance',
    //         'fax_freelance' => 'fax freelance',
    //         'localisation_freelance' => 'localisation freelance',
    //         'adresse_postale_freelance' => 'adresse postale freelance',
    //         'domaine_activite_freelance' => 'domaine d\'activité freelance',
    //         'relation_tiers' => 'relation avec un tiers',
    //         'nom_relation' => 'nom du tiers',
    //         'etre_auteur' => 'auteur de l\'adhésion',
    //         'nom_auteur' => 'nom de l\'auteur',
    //         'lien_photo' => 'photo de profil',
    //         'photo_couverture' => 'photo de couverture',
    //         'document_carte_inscript_ONMCI' => 'carte d\'inscription ONMCI',
    //         'document_autorisation_ouverture' => 'autorisation d\'ouverture',
    //         'photo_identite_1' => 'photo d\'identité',
    //         'signature' => 'signature',
    //         'password' => 'mot de passe',
    //         'password_confirmation' => 'confirmation du mot de passe',
    //     ];
    // }
}
