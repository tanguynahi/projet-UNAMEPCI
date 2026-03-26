<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreinscriptionUtilisateurRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Vous pouvez modifier selon votre logique d'authentification
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Informations personnelles
            'typeAdhesion' => ['required', 'string', 'in:nouveau,revision,modification'],
            'civilite' => ['required', 'string', 'in:M.,Mme,Mlle'],
            'nom' => ['required', 'string', 'max:100'],
            'prenom' => ['required', 'string', 'max:100'],
            'contact' => ['required', 'string', 'max:15', 'unique:mutualistes,contact'],
            'contact_2' => ['nullable', 'string', 'max:15'],
            'fax' => ['nullable', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255', 'unique:mutualistes,email'],
            'date_naissance' => ['required', 'date'],
            'lieu_naissance' => ['required', 'string', 'max:255'],
            'nationalite' => ['required', 'string', 'max:100'],
            'situation_matrimoniale' => ['required', 'string'],
            'nombre_charge' => ['nullable', 'integer', 'min:0'],
            'date_adhesion_unamepci' => ['nullable', 'date'],

            // Documents d'identité
            'type_piece_id' => ['required', 'exists:type_pieces,id'],
            'numero_piece' => ['required', 'string', 'max:50'],
            'date_etablissement_piece' => ['required', 'date'],
            'lieu_etablissement_piece' => ['required', 'string', 'max:255'],
            'numero_inscription_ONMCI' => ['nullable', 'string', 'max:100'],
            'pseudonyme_recon_ONMCI' => ['nullable', 'string', 'max:100'],

            // Informations professionnelles
            'matricule' => ['required', 'string', 'max:50', 'unique:mutualistes,matricule'],
            'raison_social_primaire' => ['required', 'string', 'max:255'],
            'specialite_id' => ['required', 'exists:specialites,id'],
            'fonction' => ['nullable', 'string', 'max:100'],
            'date_debut_metier' => ['nullable', 'date'],
            'nombre_annee_experience' => ['required', 'integer', 'min:-1'],
            'statut_emploi' => ['required', 'string'],
            'domaine_activite' => ['nullable', 'string', 'max:255'],
            'nom_employeur_principale' => ['nullable', 'string', 'max:255'],
            // 'montant_cotis_annuel' => ['nullable', 'numeric', 'min:0'],
            'date_recrutement' => ['nullable', 'date'],

            // Informations entreprise
            'sigle' => ['required', 'string', 'max:50'],
            'date_creation' => ['nullable', 'date'],
            'numero_autorisation' => ['nullable', 'string', 'max:100'],
            'num_immatriculation' => ['nullable', 'string', 'max:100'],
            'forme_juridique_id' => ['required', 'exists:forme_juridiques,id'],
            'precise_forme_juridique' => ['nullable', 'string', 'max:255'],
            'ville_id' => ['required', 'exists:villes,id'],
            'commune' => ['nullable', 'string', 'max:100'],
            'quartier' => ['nullable', 'string', 'max:100'],
            'rue' => ['nullable', 'string', 'max:255'],
            'adresse_postale_entreprise' => ['nullable', 'string', 'max:255'],
            'localisation_entreprise' => ['nullable', 'string', 'max:255'],
            'email_entreprise' => ['nullable', 'email', 'max:255'],
            'telephone_entreprise' => ['nullable', 'string', 'max:15'],
            'fax_entreprise' => ['nullable', 'string', 'max:50'],
            'adresse' => ['required', 'string', 'max:255'],

            // Informations freelance
            'raison_social_secondaire_freelance' => ['nullable', 'string', 'max:255'],
            'fonction_occupe_freelance' => ['nullable', 'string', 'max:100'],
            'type_contrat_freelance' => ['nullable', 'string'],
            'telephone_freelance' => ['nullable', 'string', 'max:15'],
            'localisation_freelance' => ['nullable', 'string', 'max:255'],
            'adresse_postale_freelance' => ['nullable', 'string', 'max:255'],
            'domaine_activite_freelance' => ['nullable', 'string', 'max:255'],
            'fax_freelance' => ['nullable', 'string', 'max:50'],

            // Relations et auteur
            'relation_tiers' => ['nullable', 'in:0,1'],
            'nom_relation' => ['nullable', 'required_if:relation_tiers,1', 'string', 'max:255'],
            'nom_auteur' => ['nullable', 'string', 'max:255'],

            // Fichiers
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:5120'],
            'signature' => ['required', 'file', 'mimes:jpeg,png,jpg,gif'],
            'pieces_joints_recto' => ['required', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:5120'],
            'pieces_joints_verso' => ['required', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:5120'],
            'photo_couverture' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:5120'],
            'document_carte_inscript_ONMCI' => ['nullable', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:5120'],
            'document_autorisation_ouverture' => ['required', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:5120'],
            'photo_identite_1' => ['required', 'file', 'mimes:jpeg,png,jpg,pdf', 'max:5120'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            // Messages génériques
            'required' => 'Le champ :attribute est obligatoire.',
            'string' => 'Le champ :attribute doit être une chaîne de caractères.',
            'max' => 'Le champ :attribute ne doit pas dépasser :max caractères.',
            'email' => 'Le champ :attribute doit être une adresse email valide.',
            'date' => 'Le champ :attribute doit être une date valide.',
            'numeric' => 'Le champ :attribute doit être un nombre.',
            'integer' => 'Le champ :attribute doit être un nombre entier.',
            'min' => 'Le champ :attribute doit être au minimum :min.',
            'in' => 'La valeur sélectionnée pour :attribute est invalide.',
            'exists' => 'La valeur sélectionnée pour :attribute est invalide.',
            'unique' => 'Cette valeur pour :attribute est déjà utilisée.',
            'image' => 'Le fichier :attribute doit être une image.',
            'file' => 'Le champ :attribute doit être un fichier.',
            'mimes' => 'Le fichier :attribute doit être de type :values.',

            // Messages spécifiques
            'required_if' => 'Le champ :attribute est obligatoire lorsque :other est :value.',
            'contact.unique' => 'Ce numéro de contact est déjà utilisé par un autre adhérent.',
            'email.unique' => 'Cette adresse email est déjà utilisée par un autre adhérent.',
            'matricule.unique' => 'Ce matricule est déjà utilisé par un autre adhérent.',

            // Messages pour les fichiers
            'avatar.required' => 'La photo de profil est obligatoire.',
            'signature.required' => 'La signature est obligatoire.',
            'avatar.max' => 'La photo de profil ne doit pas dépasser 5 Mo.',
            'pieces_joints_recto.required' => 'Le recto de la pièce d\'identité est obligatoire.',
            'pieces_joints_recto.max' => 'Le recto de la pièce d\'identité ne doit pas dépasser 5 Mo.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            // Informations personnelles
            'typeAdhesion' => 'type d\'adhésion',
            'civilite' => 'civilité',
            'nom' => 'nom',
            'prenom' => 'prénom',
            'contact' => 'contact principal',
            'contact_2' => 'contact secondaire',
            'fax' => 'fax',
            'email' => 'email',
            'date_naissance' => 'date de naissance',
            'lieu_naissance' => 'lieu de naissance',
            'nationalite' => 'nationalité',
            'situation_matrimoniale' => 'situation matrimoniale',
            'nombre_charge' => 'nombre de personnes à charge',
            'date_adhesion_unamepci' => 'date d\'adhésion UNAMEPCI',

            // Documents d'identité
            'type_piece_id' => 'type de pièce d\'identité',
            'numero_piece' => 'numéro de la pièce',
            'date_etablissement_piece' => 'date d\'établissement de la pièce',
            'lieu_etablissement_piece' => 'lieu d\'établissement de la pièce',
            'numero_inscription_ONMCI' => 'numéro d\'inscription ONMCI',
            'pseudonyme_recon_ONMCI' => 'pseudonyme de reconnaissance ONMCI',

            // Informations professionnelles
            'matricule' => 'matricule',
            'raison_social_primaire' => 'raison sociale principale',
            'specialite_id' => 'spécialité',
            'fonction' => 'fonction',
            'date_debut_metier' => 'date de début dans le métier',
            'nombre_annee_experience' => 'nombre d\'années d\'expérience',
            'statut_emploi' => 'statut d\'emploi',
            'domaine_activite' => 'domaine d\'activité',
            'nom_employeur_principale' => 'nom de l\'employeur principal',
            // 'montant_cotis_annuel' => 'montant de cotisation annuelle',
            'date_recrutement' => 'date de recrutement',

            // Informations entreprise
            'sigle' => 'sigle',
            'date_creation' => 'date de création',
            'numero_autorisation' => 'numéro d\'autorisation',
            'num_immatriculation' => 'numéro d\'immatriculation',
            'forme_juridique_id' => 'forme juridique',
            'precise_forme_juridique' => 'précision forme juridique',
            'ville_id' => 'ville',
            'commune' => 'commune',
            'quartier' => 'quartier',
            'rue' => 'rue',
            'adresse_postale_entreprise' => 'adresse postale entreprise',
            'localisation_entreprise' => 'email entreprise',
            'email_entreprise' => 'email entreprise',
            'telephone_entreprise' => 'téléphone entreprise',
            'fax_entreprise' => 'fax entreprise',
            'adresse' => 'adresse personnelle',

            // Informations freelance
            'raison_social_secondaire_freelance' => 'raison sociale freelance',
            'fonction_occupe_freelance' => 'fonction occupée freelance',
            'type_contrat_freelance' => 'type de contrat freelance',
            'telephone_freelance' => 'téléphone freelance',
            'localisation_freelance' => 'localisation freelance',
            'adresse_postale_freelance' => 'adresse postale freelance',
            'domaine_activite_freelance' => 'domaine d\'activité freelance',
            'fax_freelance' => 'fax freelance',

            // Relations et auteur
            'relation_tiers' => 'relation avec un tiers',
            'nom_relation' => 'nom du tiers',
            'nom_auteur' => 'nom de l\'auteur',

            // Fichiers
            'avatar' => 'photo de profil',
            'pieces_joints_recto' => 'recto de la pièce',
            'pieces_joints_verso' => 'verso de la pièce',
            'photo_couverture' => 'photo de couverture',
            'document_carte_inscript_ONMCI' => 'carte d\'inscription ONMCI',
            'document_autorisation_ouverture' => 'autorisation d\'ouverture',
            'photo_identite_1' => 'photo d\'identité supplémentaire 1',
            'photo_identite_2' => 'photo d\'identité supplémentaire 2',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Vous pouvez préparer ou formater les données avant la validation ici
        // Par exemple, nettoyer les numéros de téléphone
        if ($this->has('contact')) {
            $this->merge([
                'contact' => preg_replace('/[^0-9]/', '', $this->contact),
            ]);
        }

        if ($this->has('contact_2')) {
            $this->merge([
                'contact_2' => preg_replace('/[^0-9]/', '', $this->contact_2),
            ]);
        }

        // Assurer que relation_tiers est soit 0 soit 1
        if (!$this->has('relation_tiers')) {
            $this->merge([
                'relation_tiers' => '0',
            ]);
        }
    }
}
