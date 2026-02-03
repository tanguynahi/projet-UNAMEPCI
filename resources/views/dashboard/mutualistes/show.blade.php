@extends('layouts.dashboard', ['title' => 'Détail du mutualiste', 'toolbar' => '_toolbar2', 'breadcrumb' => 'Mutualistes'])

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/dashboard/cssbundle/dataTables.min.css') }}">
    <style>
        .profile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 30px;
        }

        .profile-img {
            width: 150px;
            height: 150px;
            border: 5px solid white;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        .info-card {
            border-left: 4px solid #667eea;
            background: #f8f9fa;
        }

        .section-title {
            color: #667eea;
            border-bottom: 2px solid #eaeaea;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .badge-status {
            font-size: 0.85em;
            padding: 5px 15px;
        }

        .badge-online {
            background-color: #10b981;
        }

        .badge-offline {
            background-color: #6b7280;
        }

        .document-preview {
            max-width: 200px;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .document-preview:hover {
            transform: scale(1.05);
        }

        .signature-container {
            background: white;
            border: 1px solid #eaeaea;
            border-radius: 5px;
            padding: 15px;
            max-width: 300px;
            margin: 0 auto;
        }
    </style>
@endpush

@section('content')
    <div class="row g-4">
        <!-- En-tête du profil -->
        <div class="col-12">
            <div class="profile-header">
                <div class="row align-items-center">
                    <div class="col-md-3 text-center">
                        @if ($mutualiste->lien_photo)
                            <img src="{{ asset($mutualiste->lien_photo) }}" alt="Photo de profil"
                                class="profile-img rounded-circle">
                        @else
                            <div
                                class="profile-img rounded-circle d-flex align-items-center justify-content-center bg-white">
                                <i class="icon-user" style="font-size: 60px; color: #667eea;"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h2 class="mb-2">{{ $mutualiste->civilite }} {{ $mutualiste->prenom }} {{ $mutualiste->nom }}</h2>
                        <p class="mb-1">
                            <i class="icon-briefcase me-2"></i>{{ $mutualiste->fonction ?? 'Non spécifié' }}
                        </p>
                        <p class="mb-1">
                            <i class="icon-envelope me-2"></i>{{ $mutualiste->email }}
                        </p>
                        <p class="mb-1">
                            <i class="icon-phone me-2"></i>{{ $mutualiste->contact ?? 'Non spécifié' }}
                        </p>
                        <p class="mb-0">
                            <i class="icon-map-pin me-2"></i>{{ $mutualiste->adresse ?? 'Adresse non spécifiée' }}
                        </p>
                    </div>
                    <div class="col-md-3 text-end">
                        <span
                            class="badge {{ $mutualiste->disponibilite == 'en ligne' ? 'badge-online' : 'badge-offline' }} badge-status">
                            <i
                                class="{{ $mutualiste->disponibilite == 'en ligne' ? 'icon-wifi' : 'icon-wifi-off' }} me-1"></i>
                            {{ ucfirst($mutualiste->disponibilite) }}
                        </span>
                        <p class="mt-3 mb-0">
                            <small>Matricule : {{ $mutualiste->matricule }}</small>
                        </p>
                        @if ($mutualiste->date_adhesion_unamepci)
                            <p class="mb-0">
                                <small>Adhésion : {{ $mutualiste->date_adhesion_unamepci }}</small>
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations personnelles -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="icon-user me-2"></i>Informations personnelles
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Date de naissance</label>
                            <p class="mb-0">
                                {{ $mutualiste->date_naissance ? $mutualiste->date_naissance : 'Non spécifiée' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Lieu de naissance</label>
                            <p class="mb-0">{{ $mutualiste->lieu_naissance ?? 'Non spécifié' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Nationalité</label>
                            <p class="mb-0">{{ $mutualiste->nationalite ?? 'Non spécifiée' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Situation matrimoniale</label>
                            <p class="mb-0">{{ $mutualiste->situation_matrimoniale ?? 'Non spécifiée' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Nombre de charges</label>
                            <p class="mb-0">{{ $mutualiste->nombre_charge ?? 'Non spécifié' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Fax</label>
                            <p class="mb-0">{{ $mutualiste->fax ?? 'Non spécifié' }}</p>
                        </div>
                        <div class="col-12">
                            <label class="form-label text-muted small mb-1">Contact supplémentaire</label>
                            <p class="mb-0">{{ $mutualiste->contact_2 ?? 'Non spécifié' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Document d'identification -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="icon-credit-card me-2"></i>Document d'identification
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Type de pièce</label>
                            <p class="mb-0">{{ $mutualiste->typePiece->libelle ?? 'Non spécifié' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Numéro de pièce</label>
                            <p class="mb-0">{{ $mutualiste->numero_piece ?? 'Non spécifié' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Date d'établissement</label>
                            <p class="mb-0">
                                {{ $mutualiste->date_etablissement_piece ? $mutualiste->date_etablissement_piece : 'Non spécifiée' }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Lieu d'établissement</label>
                            <p class="mb-0">{{ $mutualiste->lieu_etablissement_piece ?? 'Non spécifié' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Numéro ONMCI</label>
                            <p class="mb-0">{{ $mutualiste->numero_inscription_ONMCI ?? 'Non spécifié' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Pseudonyme ONMCI</label>
                            <p class="mb-0">{{ $mutualiste->pseudonyme_recon_ONMCI ?? 'Non spécifié' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations professionnelles principales -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="icon-briefcase me-2"></i>Informations professionnelles principales
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-muted small mb-1">Raison sociale</label>
                            <p class="mb-0">{{ $mutualiste->raison_social_primaire ?? 'Non spécifiée' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-muted small mb-1">Spécialité</label>
                            <p class="mb-0">{{ $mutualiste->specialite->libelle ?? 'Non spécifiée' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-muted small mb-1">Fonction</label>
                            <p class="mb-0">{{ $mutualiste->fonction ?? 'Non spécifiée' }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-muted small mb-1">Date début métier</label>
                            <p class="mb-0">
                                {{ $mutualiste->date_debut_metier ? $mutualiste->date_debut_metier : 'Non spécifiée' }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-muted small mb-1">Expérience</label>
                            <p class="mb-0">{{ $mutualiste->nombre_annee_experience ?? '0' }} années</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-muted small mb-1">Statut emploi</label>
                            <p class="mb-0">{{ $mutualiste->statut_emploi ?? 'Non spécifié' }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label text-muted small mb-1">Date recrutement</label>
                            <p class="mb-0">
                                {{ $mutualiste->date_recrutement ? $mutualiste->date_recrutement : 'Non spécifiée' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-muted small mb-1">Montant cotisation annuel</label>
                            <p class="mb-0">
                                {{ $mutualiste->montant_cotis_annuel ? number_format($mutualiste->montant_cotis_annuel, 0, ',', ' ') . ' FCFA' : 'Non spécifié' }}
                            </p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-muted small mb-1">Nom employeur</label>
                            <p class="mb-0">{{ $mutualiste->nom_employeur_principale ?? 'Non spécifié' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-muted small mb-1">Domaine d'activité</label>
                            <p class="mb-0">{{ $mutualiste->domaine_activite ?? 'Non spécifié' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations entreprise -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="icon-building me-2"></i>Informations de l'entreprise
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Sigle</label>
                            <p class="mb-0">{{ $mutualiste->sigle ?? 'Non spécifié' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Date création</label>
                            <p class="mb-0">
                                {{ $mutualiste->date_creation ? $mutualiste->date_creation : 'Non spécifiée' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Numéro autorisation</label>
                            <p class="mb-0">{{ $mutualiste->numero_autorisation ?? 'Non spécifié' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Immatriculation</label>
                            <p class="mb-0">{{ $mutualiste->num_immatriculation ?? 'Non spécifié' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Forme juridique</label>
                            <p class="mb-0">{{ $mutualiste->formeJuridique->libelle ?? 'Non spécifiée' }}</p>
                        </div>
                        @if ($mutualiste->precise_forme_juridique)
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted small mb-1">Précision forme</label>
                                <p class="mb-0">{{ $mutualiste->precise_forme_juridique ?? 'Non spécifiée' }}</p>
                            </div>
                        @endif
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Ville</label>
                            <p class="mb-0">{{ $mutualiste->ville->libelle ?? 'Non spécifiée' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Commune</label>
                            <p class="mb-0">{{ $mutualiste->commune ?? 'Non spécifiée' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Quartier</label>
                            <p class="mb-0">{{ $mutualiste->quartier ?? 'Non spécifié' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Rue</label>
                            <p class="mb-0">{{ $mutualiste->rue ?? 'Non spécifiée' }}</p>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label text-muted small mb-1">Adresse complète</label>
                            <p class="mb-0">{{ $mutualiste->adresse_postale_entreprise ?? 'Non spécifiée' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Localisation entreprise</label>
                            <p class="mb-0">{{ $mutualiste->localisation_entreprise ?? 'Non spécifié' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Téléphone entreprise</label>
                            <p class="mb-0">{{ $mutualiste->telephone_entreprise ?? 'Non spécifié' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Email entreprise</label>
                            <p class="mb-0">{{ $mutualiste->email_entreprise ?? 'Non spécifié' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small mb-1">Fax entreprise</label>
                            <p class="mb-0">{{ $mutualiste->fax_entreprise ?? 'Non spécifié' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Informations freelance et relations -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="icon-users me-2"></i>Activités secondaires & Relations
                    </h6>
                </div>
                <div class="card-body">
                    <!-- Section Freelance -->
                    @if ($mutualiste->raison_social_secondaire_freelance)
                        <div class="mb-4">
                            <h6 class="section-title">Activité Freelance</h6>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <label class="form-label text-muted small mb-1">Raison sociale</label>
                                    <p class="mb-0">{{ $mutualiste->raison_social_secondaire_freelance }}</p>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label text-muted small mb-1">Fonction</label>
                                    <p class="mb-0">{{ $mutualiste->fonction_occupe_freelance ?? 'Non spécifiée' }}</p>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label text-muted small mb-1">Type de contrat</label>
                                    <p class="mb-0">{{ $mutualiste->type_contrat_freelance ?? 'Non spécifié' }}</p>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label text-muted small mb-1">Téléphone freelance</label>
                                    <p class="mb-0">{{ $mutualiste->telephone_freelance ?? 'Non spécifié' }}</p>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label text-muted small mb-1">Fax freelance</label>
                                    <p class="mb-0">{{ $mutualiste->fax_freelance ?? 'Non spécifié' }}</p>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label text-muted small mb-1">Localisation freelance</label>
                                    <p class="mb-0">{{ $mutualiste->localisation_freelance ?? 'Non spécifié' }}</p>
                                </div>
                                <div class="col-12 mb-2">
                                    <label class="form-label text-muted small mb-1">Adresse postale freelance</label>
                                    <p class="mb-0">{{ $mutualiste->adresse_postale_freelance ?? 'Non spécifié' }}</p>
                                </div>
                                <div class="col-12 mb-2">
                                    <label class="form-label text-muted small mb-1">Domaine d'activité freelance</label>
                                    <p class="mb-0">{{ $mutualiste->domaine_activite_freelance ?? 'Non spécifié' }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Section Relations -->
                    <div class="row">
                        @if ($mutualiste->relation_tiers)
                            <div class="col-md-6 mb-3">
                                <div class="info-card p-3">
                                    <label class="form-label text-muted small mb-1">Relation avec tiers</label>
                                    <p class="mb-0">{{ $mutualiste->nom_relation ?? 'Non spécifié' }}</p>
                                </div>
                            </div>
                        @endif

                        @if ($mutualiste->etre_auteur)
                            <div class="col-md-6 mb-3">
                                <div class="info-card p-3">
                                    <label class="form-label text-muted small mb-1">Auteur</label>
                                    <p class="mb-0">{{ $mutualiste->nom_auteur ?? 'Non spécifié' }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Documents -->
        @if (
            $mutualiste->pieces_joints_recto ||
                $mutualiste->pieces_joints_verso ||
                $mutualiste->photo_couverture ||
                $mutualiste->document_carte_inscript_ONMCI ||
                $mutualiste->document_autorisation_ouverture ||
                $mutualiste->photo_identite_1 ||
                $mutualiste->signature)
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="icon-file-text me-2"></i>Documents
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @if ($mutualiste->pieces_joints_recto)
                                <div class="col-md-3">
                                    <label class="form-label text-muted small d-block mb-2">Pièce d'identité
                                        (Recto)</label>
                                    <a href="{{ asset($mutualiste->pieces_joints_recto) }}" target="_blank">
                                        <img src="{{ asset($mutualiste->pieces_joints_recto) }}" alt="Recto pièce"
                                            class="img-thumbnail document-preview">
                                    </a>
                                </div>
                            @endif

                            @if ($mutualiste->pieces_joints_verso)
                                <div class="col-md-3">
                                    <label class="form-label text-muted small d-block mb-2">Pièce d'identité
                                        (Verso)</label>
                                    <a href="{{ asset($mutualiste->pieces_joints_verso) }}" target="_blank">
                                        <img src="{{ asset($mutualiste->pieces_joints_verso) }}" alt="Verso pièce"
                                            class="img-thumbnail document-preview">
                                    </a>
                                </div>
                            @endif

                            @if ($mutualiste->photo_identite_1)
                                <div class="col-md-3">
                                    <label class="form-label text-muted small d-block mb-2">Photo d'identité</label>
                                    <a href="{{ asset($mutualiste->photo_identite_1) }}" target="_blank">
                                        <img src="{{ asset($mutualiste->photo_identite_1) }}" alt="Photo identité"
                                            class="img-thumbnail document-preview">
                                    </a>
                                </div>
                            @endif

                            @if ($mutualiste->photo_couverture)
                                <div class="col-md-3">
                                    <label class="form-label text-muted small d-block mb-2">Photo de couverture</label>
                                    <a href="{{ asset($mutualiste->photo_couverture) }}" target="_blank">
                                        <img src="{{ asset($mutualiste->photo_couverture) }}" alt="Photo couverture"
                                            class="img-thumbnail document-preview">
                                    </a>
                                </div>
                            @endif

                            @if ($mutualiste->document_carte_inscript_ONMCI)
                                <div class="col-md-3">
                                    <label class="form-label text-muted small d-block mb-2">Carte d'inscription
                                        ONMCI</label>
                                    <a href="{{ asset($mutualiste->document_carte_inscript_ONMCI) }}" target="_blank">
                                        <img src="{{ asset($mutualiste->document_carte_inscript_ONMCI) }}"
                                            alt="Carte ONMCI" class="img-thumbnail document-preview">
                                    </a>
                                </div>
                            @endif

                            @if ($mutualiste->document_autorisation_ouverture)
                                <div class="col-md-3">
                                    <label class="form-label text-muted small d-block mb-2">Autorisation
                                        d'ouverture</label>
                                    <a href="{{ asset($mutualiste->document_autorisation_ouverture) }}" target="_blank">
                                        <img src="{{ asset($mutualiste->document_autorisation_ouverture) }}"
                                            alt="Autorisation ouverture" class="img-thumbnail document-preview">
                                    </a>
                                </div>
                            @endif

                            @if ($mutualiste->signature)
                                <div class="col-md-3">
                                    <label class="form-label text-muted small d-block mb-2">Signature</label>
                                    <div class="signature-container">
                                        <a href="{{ asset($mutualiste->signature) }}" target="_blank">
                                            <img src="{{ asset($mutualiste->signature) }}" alt="Signature"
                                                class="img-fluid document-preview">
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Actions -->
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Dernière mise à jour :
                                {{ $mutualiste->updated_at->format('d/m/Y H:i') }}</small>
                        </div>
                        <div>
                            <a href="{{ route('mutualistes.index') }}" class="btn btn-outline-secondary ms-2">
                                <i class="icon-arrow-left me-1"></i>Retour à la liste
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/dashboard/js/bundle/dataTables.bundle.js') }}"></script>
    <script>
        // Fonction pour afficher les images en plein écran
        document.querySelectorAll('.document-preview').forEach(img => {
            img.addEventListener('click', function(e) {
                e.preventDefault();
                const modal = `
                    <div class="modal fade" id="imageModal" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-body text-center">
                                    <img src="${this.src}" class="img-fluid" style="max-height: 80vh;">
                                </div>
                                <div class="modal-footer">
                                    <a href="${this.parentElement.href}" download class="btn btn-primary">
                                        <i class="icon-download me-1"></i>Télécharger
                                    </a>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                document.body.insertAdjacentHTML('beforeend', modal);
                const modalInstance = new bootstrap.Modal(document.getElementById('imageModal'));
                modalInstance.show();

                // Supprimer le modal du DOM après fermeture
                document.getElementById('imageModal').addEventListener('hidden.bs.modal', function() {
                    this.remove();
                });
            });
        });
    </script>
@endpush
