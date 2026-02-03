<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MutualisteEspaceUpdateRequest extends FormRequest
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
        $mutualisteId = $this->route('mutualiste');

        // Si le paramètre est l'objet Mutualiste (route model binding)
        if ($mutualisteId instanceof \App\Models\Mutualiste) {
            $mutualisteId = $mutualisteId->id;
        }

        return [
            // Informations personnelles
            // 'matricule' => 'required|string|max:50|unique:mutualistes,matricule,' . $mutualisteId,
            'civilite' => 'required|string|in:M.,Mme,Mlle',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'date_naissance' => 'nullable|date',
            'lieu_naissance' => 'nullable|string|max:255',
            'nationalite' => 'nullable|string|max:100',
            'situation_matrimoniale' => 'nullable|string|max:100',
            'nombre_charge' => 'nullable|integer|min:0',
            'date_adhesion_unamepci' => 'nullable|date',

            // Coordonnées
            // 'email' => 'required|email|max:255|unique:mutualistes,email,' . $mutualisteId,
            // 'contact' => 'nullable|string|max:20|unique:mutualistes,contact,' . $mutualisteId,
            // 'contact_2' => 'nullable|string|max:20|unique:mutualistes,contact_2,' . $mutualisteId,
            'fax' => 'nullable|string|max:50',
            'ville_id' => 'nullable|exists:villes,id',
            'adresse' => 'nullable|string|max:500',

            // Pièce d'identité
            'type_piece_id' => 'nullable|exists:type_pieces,id',
            'numero_piece' => 'nullable|string|max:100',
            'date_etablissement_piece' => 'nullable|date',
            'lieu_etablissement_piece' => 'nullable|string|max:255',

            // Informations ONMCI
            'numero_inscription_ONMCI' => 'nullable|string|max:100',
            'pseudonyme_recon_ONMCI' => 'nullable|string|max:255',

            // Activité principale
            'raison_social_primaire' => 'nullable|string|max:500',
            'specialite_id' => 'nullable|exists:specialites,id',
            'fonction' => 'nullable|string|max:255',
            'date_debut_metier' => 'nullable|date',
            'nombre_annee_experience' => 'nullable|integer|min:0',
            'nom_employeur_principale' => 'nullable|string|max:255',
            'statut_emploi' => 'nullable|string|max:100',
            'domaine_activite' => 'nullable|string|max:500',
            'date_recrutement' => 'nullable|date',
         

            // Informations entreprise
            'sigle' => 'nullable|string|max:100',
            'date_creation' => 'nullable|date',
            'numero_autorisation' => 'nullable|string|max:100',
            'num_immatriculation' => 'nullable|string|max:100',
            'forme_juridique_id' => 'nullable|exists:forme_juridiques,id',
            'precise_forme_juridique' => 'nullable|string|max:255',
            'commune' => 'nullable|string|max:255',
            'quartier' => 'nullable|string|max:255',
            'rue' => 'nullable|string|max:255',
            'adresse_postale_entreprise' => 'nullable|string|max:500',
            'localisation_entreprise' => 'nullable|string|max:500',
            'email_entreprise' => 'nullable|email|max:255',
            'telephone_entreprise' => 'nullable|string|max:20',
            'fax_entreprise' => 'nullable|string|max:50',

            // Relations
            'relation_tiers' => 'nullable|boolean',
            'nom_relation' => 'nullable|string|max:255',
            'etre_auteur' => 'nullable|boolean',
            'nom_auteur' => 'nullable|string|max:255',

            // Travail freelance
            'raison_social_secondaire_freelance' => 'nullable|string|max:500',
            'fonction_occupe_freelance' => 'nullable|string|max:255',
            'type_contrat_freelance' => 'nullable|string|max:100',
            'telephone_freelance' => 'nullable|string|max:20',
            'fax_freelance' => 'nullable|string|max:50',
            'localisation_freelance' => 'nullable|string|max:500',
            'adresse_postale_freelance' => 'nullable|string|max:500',
            'domaine_activite_freelance' => 'nullable|string|max:500',

            // Fichiers
            'photo_couverture' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:5120',
            'lien_photo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:5120',
            'pieces_joints_recto' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'pieces_joints_verso' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'document_carte_inscript_ONMCI' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'document_autorisation_ouverture' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'photo_identite_1' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:5120',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            // Informations personnelles
            'matricule.required' => 'Le matricule est obligatoire.',
            'matricule.unique' => 'Ce matricule est déjà utilisé.',
            'civilite.required' => 'La civilité est obligatoire.',
            'civilite.in' => 'La civilité doit être M., Mme ou Mlle.',
            'nom.required' => 'Le nom est obligatoire.',
            'prenom.required' => 'Le prénom est obligatoire.',
            'date_naissance.date' => 'La date de naissance doit être une date valide.',
            'nombre_charge.integer' => 'Le nombre de charges doit être un nombre entier.',
            'nombre_charge.min' => 'Le nombre de charges ne peut pas être négatif.',
            'date_adhesion_unamepci.date' => 'La date d\'adhésion UNAMEPCI doit être une date valide.',

            // Coordonnées
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'L\'email doit être une adresse email valide.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'contact.unique' => 'Ce numéro de téléphone est déjà utilisé.',
            'contact_2.unique' => 'Ce deuxième numéro de téléphone est déjà utilisé.',
            'ville_id.exists' => 'La ville sélectionnée n\'existe pas.',

            // Pièce d'identité
            'type_piece_id.exists' => 'Le type de pièce sélectionné n\'existe pas.',
            'date_etablissement_piece.date' => 'La date d\'établissement de la pièce doit être une date valide.',

            // Activité principale
            'specialite_id.exists' => 'La spécialité sélectionnée n\'existe pas.',
            'nombre_annee_experience.integer' => 'Le nombre d\'années d\'expérience doit être un nombre entier.',
            'nombre_annee_experience.min' => 'Le nombre d\'années d\'expérience ne peut pas être négatif.',
            'montant_cotis_annuel.numeric' => 'Le montant de cotisation annuel doit être un nombre.',
            'montant_cotis_annuel.min' => 'Le montant de cotisation annuel ne peut pas être négatif.',
            'date_recrutement.date' => 'La date de recrutement doit être une date valide.',
            'date_debut_metier.date' => 'La date de début de métier doit être une date valide.',

            // Informations entreprise
            'forme_juridique_id.exists' => 'La forme juridique sélectionnée n\'existe pas.',
            'email_entreprise.email' => 'L\'email de l\'entreprise doit être une adresse email valide.',
            'date_creation.date' => 'La date de création doit être une date valide.',

            // Fichiers
            'photo_couverture.image' => 'La photo de couverture doit être une image.',
            'photo_couverture.mimes' => 'La photo de couverture doit être au format jpg, jpeg, png ou gif.',
            'photo_couverture.max' => 'La photo de couverture ne doit pas dépasser 5 Mo.',

            'lien_photo.image' => 'La photo de profil doit être une image.',
            'lien_photo.mimes' => 'La photo de profil doit être au format jpg, jpeg, png ou gif.',
            'lien_photo.max' => 'La photo de profil ne doit pas dépasser 5 Mo.',

            'pieces_joints_recto.file' => 'Le document recto doit être un fichier.',
            'pieces_joints_recto.mimes' => 'Le document recto doit être au format jpg, jpeg, png ou pdf.',
            'pieces_joints_recto.max' => 'Le document recto ne doit pas dépasser 5 Mo.',

            'pieces_joints_verso.file' => 'Le document verso doit être un fichier.',
            'pieces_joints_verso.mimes' => 'Le document verso doit être au format jpg, jpeg, png ou pdf.',
            'pieces_joints_verso.max' => 'Le document verso ne doit pas dépasser 5 Mo.',

            'document_carte_inscript_ONMCI.file' => 'La carte d\'inscription ONMCI doit être un fichier.',
            'document_carte_inscript_ONMCI.mimes' => 'La carte d\'inscription ONMCI doit être au format jpg, jpeg, png ou pdf.',
            'document_carte_inscript_ONMCI.max' => 'La carte d\'inscription ONMCI ne doit pas dépasser 5 Mo.',

            'document_autorisation_ouverture.file' => 'Le document d\'autorisation d\'ouverture doit être un fichier.',
            'document_autorisation_ouverture.mimes' => 'Le document d\'autorisation d\'ouverture doit être au format jpg, jpeg, png ou pdf.',
            'document_autorisation_ouverture.max' => 'Le document d\'autorisation d\'ouverture ne doit pas dépasser 5 Mo.',

            'photo_identite_1.image' => 'La photo d\'identité doit être une image.',
            'photo_identite_1.mimes' => 'La photo d\'identité doit être au format jpg, jpeg, png ou gif.',
            'photo_identite_1.max' => 'La photo d\'identité ne doit pas dépasser 5 Mo.',

            // Champs généraux
            '*.required' => 'Ce champ est obligatoire.',
            '*.string' => 'Ce champ doit être une chaîne de caractères.',
            '*.max' => 'Ce champ ne doit pas dépasser :max caractères.',
            '*.date' => 'Ce champ doit être une date valide.',
            '*.integer' => 'Ce champ doit être un nombre entier.',
            '*.numeric' => 'Ce champ doit être un nombre.',
            '*.boolean' => 'Ce champ doit être vrai ou faux.',
            '*.exists' => 'La valeur sélectionnée n\'existe pas.',
            '*.unique' => 'Cette valeur est déjà utilisée.',
            '*.email' => 'Ce champ doit être une adresse email valide.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes()
    {
        return [
            'matricule' => 'matricule',
            'civilite' => 'civilité',
            'nom' => 'nom',
            'prenom' => 'prénom',
            'date_naissance' => 'date de naissance',
            'lieu_naissance' => 'lieu de naissance',
            'nationalite' => 'nationalité',
            'situation_matrimoniale' => 'situation matrimoniale',
            'nombre_charge' => 'nombre de charges',
            'date_adhesion_unamepci' => 'date d\'adhésion UNAMEPCI',
            'email' => 'email',
            'contact' => 'téléphone',
            'contact_2' => 'téléphone 2',
            'fax' => 'fax',
            'ville_id' => 'ville',
            'adresse' => 'adresse',
            'type_piece_id' => 'type de pièce',
            'numero_piece' => 'numéro de pièce',
            'date_etablissement_piece' => 'date d\'établissement de la pièce',
            'lieu_etablissement_piece' => 'lieu d\'établissement de la pièce',
            'numero_inscription_ONMCI' => 'numéro d\'inscription ONMCI',
            'pseudonyme_recon_ONMCI' => 'pseudonyme reconnaissance ONMCI',
            'raison_social_primaire' => 'raison sociale primaire',
            'specialite_id' => 'spécialité',
            'fonction' => 'fonction',
            'date_debut_metier' => 'date de début de métier',
            'nombre_annee_experience' => 'nombre d\'années d\'expérience',
            'nom_employeur_principale' => 'nom de l\'employeur principal',
            'statut_emploi' => 'statut d\'emploi',
            'domaine_activite' => 'domaine d\'activité',
            'date_recrutement' => 'date de recrutement',
            'montant_cotis_annuel' => 'montant de cotisation annuel',
            'sigle' => 'sigle',
            'date_creation' => 'date de création',
            'numero_autorisation' => 'numéro d\'autorisation',
            'num_immatriculation' => 'numéro d\'immatriculation',
            'forme_juridique_id' => 'forme juridique',
            'precise_forme_juridique' => 'précision de la forme juridique',
            'commune' => 'commune',
            'quartier' => 'quartier',
            'rue' => 'rue',
            'adresse_postale_entreprise' => 'adresse postale de l\'entreprise',
            'localisation_entreprise' => 'localisation de l\'entreprise',
            'email_entreprise' => 'email de l\'entreprise',
            'telephone_entreprise' => 'téléphone de l\'entreprise',
            'fax_entreprise' => 'fax de l\'entreprise',
            'relation_tiers' => 'relation avec tiers',
            'nom_relation' => 'nom de la relation',
            'etre_auteur' => 'être auteur',
            'nom_auteur' => 'nom de l\'auteur',
            'raison_social_secondaire_freelance' => 'raison sociale secondaire freelance',
            'fonction_occupe_freelance' => 'fonction occupée en freelance',
            'type_contrat_freelance' => 'type de contrat freelance',
            'telephone_freelance' => 'téléphone freelance',
            'fax_freelance' => 'fax freelance',
            'localisation_freelance' => 'localisation freelance',
            'adresse_postale_freelance' => 'adresse postale freelance',
            'domaine_activite_freelance' => 'domaine d\'activité freelance',
            'photo_couverture' => 'photo de couverture',
            'lien_photo' => 'photo de profil',
            'pieces_joints_recto' => 'pièce recto',
            'pieces_joints_verso' => 'pièce verso',
            'document_carte_inscript_ONMCI' => 'carte d\'inscription ONMCI',
            'document_autorisation_ouverture' => 'document d\'autorisation d\'ouverture',
            'photo_identite_1' => 'photo d\'identité',
        ];
    }
}
