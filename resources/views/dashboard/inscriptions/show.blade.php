@extends('layouts.dashboard', ['title' => 'Détails Inscription', 'toolbar' => '_toolbar2', 'breadcrumb' => 'Détails Inscription'])
@push('css')
    <style>
        .info-section {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 25px;
            background: #f9f9f9;
        }

        .info-section h6 {
            border-bottom: 2px solid #405189;
            padding-bottom: 10px;
            margin-bottom: 20px;
            color: #405189;
        }

        .info-item {
            margin-bottom: 15px;
        }

        .info-label {
            font-weight: 600;
            color: #495057;
            min-width: 200px;
            display: inline-block;
        }

        .info-value {
            color: #212529;
            font-weight: 500;
        }

        .badge-adhesion {
            font-size: 0.9rem;
            padding: 5px 12px;
            border-radius: 20px;
        }

        .badge-nouveau {
            background-color: #28a745;
            color: white;
        }

        .badge-revision {
            background-color: #17a2b8;
            color: white;
        }

        .badge-modification {
            background-color: #ffc107;
            color: #212529;
        }

        .document-link {
            color: #405189;
            text-decoration: none;
            font-weight: 500;
        }

        .document-link:hover {
            text-decoration: underline;
            color: #2c3e8f;
        }

        /* Styles pour la modal */
        .confirmation-modal .modal-header {
            background-color: #405189;
            color: white;
        }

        .confirmation-modal .modal-footer {
            justify-content: center;
        }

        .confirmation-modal .btn-confirm {
            min-width: 100px;
        }
    </style>
@endpush

@section('content')
    <div class="row g-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0">Détails de l'inscription</h6>
                        <div class="dropdown morphing scale-left">
                            <a href="#" class="card-fullscreen" data-bs-toggle="tooltip" title="Plein écran">
                                <i class="icon-size-fullscreen"></i>
                            </a>
                            <a href="{{ route('inscriptions.index') }}" class="btn btn-primary d-inline">
                                <i class="fa fa-arrow-left me-1"></i> Retour
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Bannière Type d'adhésion -->
                    <div class="alert alert-info mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="alert-heading mb-1">
                                    @php
                                        $badgeClass = '';
                                        $typeText = '';
                                        switch ($inscription->typeAdhesion) {
                                            case 'nouveau':
                                                $badgeClass = 'badge-nouveau';
                                                $typeText = 'Nouvelle adhésion';
                                                break;
                                            case 'revision':
                                                $badgeClass = 'badge-revision';
                                                $typeText = 'Révision d\'adhésion';
                                                break;
                                            case 'modification':
                                                $badgeClass = 'badge-modification';
                                                $typeText = 'Modification d\'adhésion';
                                                break;
                                            default:
                                                $badgeClass = 'badge-secondary';
                                                $typeText = 'Non spécifié';
                                        }
                                    @endphp
                                    <span class="badge {{ $badgeClass }} badge-adhesion me-2">{{ $typeText }}</span>
                                    <span class="fw-bold">{{ $inscription->nom }} {{ $inscription->prenom }}</span>
                                </h5>
                                <p class="mb-0">Matricule: <strong>{{ $inscription->matricule }}</strong> |
                                    Soumise le: <strong>{{ formatDateTime($inscription->created_at) }}</strong></p>
                            </div>
                            <div class="text-end">
                                @if ($inscription->status == 1)
                                    <span class="badge bg-success fs-6">Approuvée</span>
                                @elseif($inscription->status == 2)
                                    <span class="badge bg-warning fs-6">En attente</span>
                                @elseif($inscription->status == 3)
                                    <span class="badge bg-danger fs-6">Rejetée</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Colonne de gauche -->
                        <div class="col-lg-6 col-md-12">
                            <!-- Section Informations personnelles -->
                            <div class="info-section">
                                <h6><i class="fa fa-user me-2"></i>Informations personnelles</h6>
                                <div class="row">
                                    <div class="col-md-6 info-item">
                                        <span class="info-label">Nom & Prénom:</span>
                                        <span class="info-value">{{ $inscription->civilite }} {{ $inscription->nom }}
                                            {{ $inscription->prenom }}</span>
                                    </div>
                                    <div class="col-md-6 info-item">
                                        <span class="info-label">Matricule:</span>
                                        <span class="info-value text-danger fw-bold">{{ $inscription->matricule }}</span>
                                    </div>
                                    <div class="col-md-6 info-item">
                                        <span class="info-label">Contact principal:</span>
                                        <span class="info-value">
                                            <i class="fa fa-phone text-primary me-1"></i>
                                            <a href="tel:{{ $inscription->contact }}" class="text-decoration-none">
                                                {{ $inscription->contact ?? 'Non renseigné' }}
                                            </a>
                                        </span>
                                    </div>
                                    @if ($inscription->contact_2)
                                        <div class="col-md-6 info-item">
                                            <span class="info-label">Contact secondaire:</span>
                                            <span class="info-value">
                                                <i class="fa fa-phone text-primary me-1"></i>
                                                <a href="tel:{{ $inscription->contact_2 }}" class="text-decoration-none">
                                                    {{ $inscription->contact_2 }}
                                                </a>
                                            </span>
                                        </div>
                                    @endif
                                    <div class="col-md-6 info-item">
                                        <span class="info-label">Email:</span>
                                        <span class="info-value">
                                            <i class="fa fa-envelope text-primary me-1"></i>
                                            <a href="mailto:{{ $inscription->email }}" class="text-decoration-none">
                                                {{ $inscription->email }}
                                            </a>
                                        </span>
                                    </div>
                                    @if ($inscription->fax)
                                        <div class="col-md-6 info-item">
                                            <span class="info-label">Fax:</span>
                                            <span class="info-value">{{ $inscription->fax }}</span>
                                        </div>
                                    @endif
                                    <div class="col-md-6 info-item">
                                        <span class="info-label">Date de naissance:</span>
                                        <span
                                            class="info-value">{{ formatDate($inscription->date_naissance) ?? 'Non renseigné' }}
                                            à {{ $inscription->lieu_naissance ?? '' }}</span>
                                    </div>
                                    <div class="col-md-6 info-item">
                                        <span class="info-label">Nationalité:</span>
                                        <span class="info-value">{{ $inscription->nationalite ?? 'Non renseigné' }}</span>
                                    </div>
                                    <div class="col-md-6 info-item">
                                        <span class="info-label">Situation matrimoniale:</span>
                                        <span
                                            class="info-value">{{ $inscription->situation_matrimoniale ?? 'Non renseigné' }}</span>
                                    </div>
                                    @if ($inscription->nombre_charge)
                                        <div class="col-md-6 info-item">
                                            <span class="info-label">Nombre de charges:</span>
                                            <span class="info-value">{{ $inscription->nombre_charge }}</span>
                                        </div>
                                    @endif
                                    <div class="col-md-12 info-item">
                                        <span class="info-label">Adresse:</span>
                                        <span class="info-value">{{ $inscription->adresse ?? 'Non renseigné' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Section Document d'identification -->
                            <div class="info-section">
                                <h6><i class="fa fa-id-card me-2"></i>Document d'identification</h6>
                                <div class="row">
                                    @if ($inscription->typePiece)
                                        <div class="col-md-6 info-item">
                                            <span class="info-label">Type de pièce:</span>
                                            <span class="info-value">{{ $inscription->typePiece->libelle }}</span>
                                        </div>
                                    @endif
                                    @if ($inscription->numero_piece)
                                        <div class="col-md-6 info-item">
                                            <span class="info-label">Numéro:</span>
                                            <span class="info-value">{{ $inscription->numero_piece }}</span>
                                        </div>
                                    @endif
                                    @if ($inscription->date_etablissement_piece)
                                        <div class="col-md-6 info-item">
                                            <span class="info-label">Date d'établissement:</span>
                                            <span
                                                class="info-value">{{ formatDate($inscription->date_etablissement_piece) }}</span>
                                        </div>
                                    @endif
                                    @if ($inscription->lieu_etablissement_piece)
                                        <div class="col-md-6 info-item">
                                            <span class="info-label">Lieu d'établissement:</span>
                                            <span class="info-value">{{ $inscription->lieu_etablissement_piece }}</span>
                                        </div>
                                    @endif
                                    @if ($inscription->numero_inscription_ONMCI)
                                        <div class="col-md-6 info-item">
                                            <span class="info-label">N° Inscription ONMCI:</span>
                                            <span class="info-value">{{ $inscription->numero_inscription_ONMCI }}</span>
                                        </div>
                                    @endif
                                    @if ($inscription->pseudonyme_recon_ONMCI)
                                        <div class="col-md-6 info-item">
                                            <span class="info-label">Pseudonyme ONMCI:</span>
                                            <span class="info-value">{{ $inscription->pseudonyme_recon_ONMCI }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @if ($inscription->signature)
                                <div class="info-section">
                                    <h6><i class="bi bi-pencil-square me-2"></i> Signature</h6>
                                    <div class="row">
                                        @if ($inscription->signature)
                                            @php
                                                $extension = strtolower(
                                                    pathinfo($inscription->signature, PATHINFO_EXTENSION),
                                                );
                                            @endphp
                                            <div class="col-md-12 info-item">
                                                @if ($extension == 'pdf')
                                                    <a href="{{ route('visualise.pdf', ['fichier' => lienPdf($inscription->signature)]) }}"
                                                        target="_blank" class="document-link">
                                                        <i class="fas fa-eye me-1"></i> Voir
                                                    </a>
                                                @elseif (in_array($extension, ['jpg', 'jpeg', 'png', 'jfif', 'heic', 'heif', 'webp']))
                                                    <img src="{{ asset($inscription->signature) }}" alt="Signature "
                                                        style=" width:350px; height:350px;">
                                                @endif
                                                {{-- <span class="info-label">Domaine d'activité:</span> --}}
                                                {{-- <span
                                                    class="info-value">{{ $inscription->domaine_activite_freelance }}</span> --}}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Colonne de droite -->
                        <div class="col-lg-6 col-md-12">
                            <!-- Section Informations activité principale -->
                            <div class="info-section">
                                <h6><i class="fa fa-briefcase me-2"></i>Activité principale</h6>
                                <div class="row">
                                    @if ($inscription->raison_social_primaire)
                                        <div class="col-md-12 info-item">
                                            <span class="info-label">Raison sociale:</span>
                                            <span class="info-value">{{ $inscription->raison_social_primaire }}</span>
                                        </div>
                                    @endif
                                    @if ($inscription->specialite)
                                        <div class="col-md-6 info-item">
                                            <span class="info-label">Spécialité:</span>
                                            <span class="info-value">{{ $inscription->specialite->libelle }}</span>
                                        </div>
                                    @endif
                                    @if ($inscription->fonction)
                                        <div class="col-md-6 info-item">
                                            <span class="info-label">Fonction:</span>
                                            <span class="info-value">{{ $inscription->fonction }}</span>
                                        </div>
                                    @endif
                                    @if ($inscription->date_debut_metier)
                                        <div class="col-md-6 info-item">
                                            <span class="info-label">Début du métier:</span>
                                            <span
                                                class="info-value">{{ formatDate($inscription->date_debut_metier) }}</span>
                                        </div>
                                    @endif
                                    @if ($inscription->nombre_annee_experience)
                                        <div class="col-md-6 info-item">
                                            <span class="info-label">Années d'expérience:</span>
                                            <span class="info-value">{{ $inscription->nombre_annee_experience }}</span>
                                        </div>
                                    @endif
                                    @if ($inscription->nom_employeur_principale)
                                        <div class="col-md-6 info-item">
                                            <span class="info-label">Employeur:</span>
                                            <span class="info-value">{{ $inscription->nom_employeur_principale }}</span>
                                        </div>
                                    @endif
                                    @if ($inscription->statut_emploi)
                                        <div class="col-md-6 info-item">
                                            <span class="info-label">Statut emploi:</span>
                                            <span class="info-value">{{ $inscription->statut_emploi }}</span>
                                        </div>
                                    @endif
                                    @if ($inscription->domaine_activite)
                                        <div class="col-md-6 info-item">
                                            <span class="info-label">Domaine d'activité:</span>
                                            <span class="info-value">{{ $inscription->domaine_activite }}</span>
                                        </div>
                                    @endif
                                    @if ($inscription->date_recrutement)
                                        <div class="col-md-6 info-item">
                                            <span class="info-label">Date de recrutement:</span>
                                            <span
                                                class="info-value">{{ formatDate($inscription->date_recrutement) }}</span>
                                        </div>
                                    @endif
                                    {{-- @if ($inscription->montant_cotis_annuel)
                                        <div class="col-md-6 info-item">
                                            <span class="info-label">Cotisation annuelle:</span>
                                            <span
                                                class="info-value">{{ formatMontant($inscription->montant_cotis_annuel) }}</span>
                                        </div>
                                    @endif --}}
                                </div>

                                <!-- Sous-section Entreprise -->
                                @if ($inscription->sigle || $inscription->formeJuridique || $inscription->ville)
                                    <div class="mt-4 pt-3 border-top">
                                        <h6 class="text-muted mb-3">Informations entreprise</h6>
                                        <div class="row">
                                            @if ($inscription->sigle)
                                                <div class="col-md-6 info-item">
                                                    <span class="info-label">Sigle:</span>
                                                    <span class="info-value">{{ $inscription->sigle }}</span>
                                                </div>
                                            @endif
                                            @if ($inscription->date_creation)
                                                <div class="col-md-6 info-item">
                                                    <span class="info-label">Date création:</span>
                                                    <span
                                                        class="info-value">{{ formatDate($inscription->date_creation) }}</span>
                                                </div>
                                            @endif
                                            @if ($inscription->numero_autorisation)
                                                <div class="col-md-6 info-item">
                                                    <span class="info-label">N° autorisation:</span>
                                                    <span
                                                        class="info-value">{{ $inscription->numero_autorisation }}</span>
                                                </div>
                                            @endif
                                            @if ($inscription->num_immatriculation)
                                                <div class="col-md-6 info-item">
                                                    <span class="info-label">N° immatriculation:</span>
                                                    <span
                                                        class="info-value">{{ $inscription->num_immatriculation }}</span>
                                                </div>
                                            @endif
                                            @if ($inscription->formeJuridique)
                                                <div class="col-md-6 info-item">
                                                    <span class="info-label">Forme juridique:</span>
                                                    <span
                                                        class="info-value">{{ $inscription->formeJuridique->libelle }}</span>
                                                </div>
                                            @endif
                                            @if ($inscription->precise_forme_juridique)
                                                <div class="col-md-6 info-item">
                                                    <span class="info-label">Précision:</span>
                                                    <span
                                                        class="info-value">{{ $inscription->precise_forme_juridique }}</span>
                                                </div>
                                            @endif
                                            @if ($inscription->ville)
                                                <div class="col-md-6 info-item">
                                                    <span class="info-label">Ville:</span>
                                                    <span class="info-value">{{ $inscription->ville->libelle }}</span>
                                                </div>
                                            @endif
                                            @if ($inscription->commune)
                                                <div class="col-md-6 info-item">
                                                    <span class="info-label">Commune:</span>
                                                    <span class="info-value">{{ $inscription->commune }}</span>
                                                </div>
                                            @endif
                                            @if ($inscription->quartier)
                                                <div class="col-md-6 info-item">
                                                    <span class="info-label">Quartier:</span>
                                                    <span class="info-value">{{ $inscription->quartier }}</span>
                                                </div>
                                            @endif
                                            @if ($inscription->adresse_postale_entreprise)
                                                <div class="col-md-12 info-item">
                                                    <span class="info-label">Adresse postale:</span>
                                                    <span
                                                        class="info-value">{{ $inscription->adresse_postale_entreprise }}</span>
                                                </div>
                                            @endif
                                            @if ($inscription->telephone_entreprise)
                                                <div class="col-md-6 info-item">
                                                    <span class="info-label">Téléphone entreprise:</span>
                                                    <span
                                                        class="info-value">{{ $inscription->telephone_entreprise }}</span>
                                                </div>
                                            @endif
                                            @if ($inscription->email_entreprise)
                                                <div class="col-md-6 info-item">
                                                    <span class="info-label">Email entreprise:</span>
                                                    <span class="info-value">{{ $inscription->email_entreprise }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Section Activité freelance -->
                            @if ($inscription->raison_social_secondaire_freelance || $inscription->fonction_occupe_freelance)
                                <div class="info-section">
                                    <h6><i class="fa fa-user-tie me-2"></i>Activité freelance</h6>
                                    <div class="row">
                                        @if ($inscription->raison_social_secondaire_freelance)
                                            <div class="col-md-12 info-item">
                                                <span class="info-label">Raison sociale:</span>
                                                <span
                                                    class="info-value">{{ $inscription->raison_social_secondaire_freelance }}</span>
                                            </div>
                                        @endif
                                        @if ($inscription->fonction_occupe_freelance)
                                            <div class="col-md-6 info-item">
                                                <span class="info-label">Fonction occupée:</span>
                                                <span
                                                    class="info-value">{{ $inscription->fonction_occupe_freelance }}</span>
                                            </div>
                                        @endif
                                        @if ($inscription->type_contrat_freelance)
                                            <div class="col-md-6 info-item">
                                                <span class="info-label">Type de contrat:</span>
                                                <span class="info-value">{{ $inscription->type_contrat_freelance }}</span>
                                            </div>
                                        @endif
                                        @if ($inscription->telephone_freelance)
                                            <div class="col-md-6 info-item">
                                                <span class="info-label">Téléphone:</span>
                                                <span class="info-value">{{ $inscription->telephone_freelance }}</span>
                                            </div>
                                        @endif
                                        @if ($inscription->adresse_postale_freelance)
                                            <div class="col-md-12 info-item">
                                                <span class="info-label">Adresse postale:</span>
                                                <span
                                                    class="info-value">{{ $inscription->adresse_postale_freelance }}</span>
                                            </div>
                                        @endif
                                        @if ($inscription->domaine_activite_freelance)
                                            <div class="col-md-12 info-item">
                                                <span class="info-label">Domaine d'activité:</span>
                                                <span
                                                    class="info-value">{{ $inscription->domaine_activite_freelance }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Section Documents -->
                    <div class="info-section mt-3">
                        <h6><i class="fa fa-folder me-2"></i>Documents joints</h6>
                        <div class="row">
                            @php
                                $extensi = strtolower(pathinfo($inscription->avatar, PATHINFO_EXTENSION));
                            @endphp
                            @if ($inscription->avatar)
                                <div class="col-md-3 col-sm-6 info-item">
                                    <span class="info-label">Photo profil:</span><br>

                                    @if ($extensi == 'pdf')
                                        {{-- <a href="{{ route('visualise.pdf', ['fichier' => lienPdf($inscription->avatar)]) }}"
                                            target="_blank" class="document-link">
                                            <i class="fas fa-eye me-1"></i> Voir
                                        </a> --}}
                                        <a href="{{ asset($inscription->avatar) }}" target="_blank"
                                            class="document-link">
                                            <i class="fa fa-eye me-1"></i>Voir
                                        </a>
                                    @elseif (in_array($extensi, ['jpg', 'jpeg', 'png', 'jfif', 'heic', 'heif', 'webp']))
                                        <img src="{{ asset($inscription->avatar) }}" alt="avatar "
                                            style=" width:50px; height:50px;">
                                    @endif

                                </div>
                            @endif



                            @if ($inscription->photo_couverture)
                                @php
                                    $exten = strtolower(pathinfo($inscription->photo_couverture, PATHINFO_EXTENSION));
                                @endphp
                                <div class="col-md-3 col-sm-6 info-item">
                                    <span class="info-label">Photo couverture:</span><br>
                                    {{-- <a href="{{ asset($inscription->photo_couverture) }}" target="_blank"
                                        class="document-link">
                                        <i class="fa fa-eye me-1"></i>Voir
                                    </a> --}}
                                    @if ($exten == 'pdf')
                                        {{-- <a href="{{ route('visualise.pdf', ['fichier' => lienPdf($inscription->photo_couverture)]) }}"
                                            target="_blank" class="document-link">
                                            <i class="fas fa-eye me-1"></i> Voir
                                        </a> --}}
                                        <a href="{{ asset($inscription->photo_couverture) }}" target="_blank"
                                            class="document-link">
                                            <i class="fa fa-eye me-1"></i>Voir
                                        </a>
                                    @elseif (in_array($exten, ['jpg', 'jpeg', 'png', 'jfif', 'heic', 'heif', 'webp']))
                                        <img src="{{ asset($inscription->photo_couverture) }}" alt="photo_couverture "
                                            style=" width:50px; height:50px;">
                                    @endif
                                </div>
                            @endif
                            @if ($inscription->pieces_joints_recto)
                                @php
                                    $extenRect = strtolower(
                                        pathinfo($inscription->pieces_joints_recto, PATHINFO_EXTENSION),
                                    );
                                @endphp
                                <div class="col-md-3 col-sm-6 info-item">
                                    <span class="info-label">Pièce recto:</span><br>
                                    {{-- <a href="{{ asset($inscription->pieces_joints_recto) }}" target="_blank"
                                        class="document-link">
                                        <i class="fa fa-eye me-1"></i>Voir
                                    </a> --}}
                                    @if ($extenRect == 'pdf')
                                        <a href="{{ asset($inscription->pieces_joints_recto) }}" target="_blank"
                                            class="document-link">
                                            <i class="fa fa-eye me-1"></i>Voir
                                        </a>
                                    @elseif (in_array($extenRect, ['jpg', 'jpeg', 'png', 'jfif', 'heic', 'heif', 'webp']))
                                        <img src="{{ asset($inscription->pieces_joints_recto) }}"
                                            alt="pieces_joints_recto " style=" width:50px; height:50px;">
                                    @endif

                                    {{-- <a href="{{ route('visualise.pdf', ['fichier' => lienPdf($inscription->lien_image)]) }}"
                                        target="_blank" class="document-link">
                                        <i class="fas fa-eye me-1"></i> Voir
                                    </a> --}}
                                </div>
                            @endif
                            @if ($inscription->pieces_joints_verso)
                                @php
                                    $extenVerso = strtolower(
                                        pathinfo($inscription->pieces_joints_verso, PATHINFO_EXTENSION),
                                    );
                                @endphp
                                <div class="col-md-3 col-sm-6 info-item">
                                    <span class="info-label">Pièce verso:</span><br>
                                    {{-- <a href="{{ asset($inscription->pieces_joints_verso) }}" target="_blank"
                                        class="document-link">
                                        <i class="fa fa-eye me-1"></i>Voir
                                    </a> --}}
                                    @if ($extenVerso == 'pdf')
                                        <a href="{{ asset($inscription->pieces_joints_verso) }}" target="_blank"
                                            class="document-link">
                                            <i class="fa fa-eye me-1"></i>Voir
                                        </a>
                                    @elseif (in_array($extenVerso, ['jpg', 'jpeg', 'png', 'jfif', 'heic', 'heif', 'webp']))
                                        <img src="{{ asset($inscription->pieces_joints_verso) }}"
                                            alt="pieces_joints_verso " style=" width:50px; height:50px;">
                                    @endif
                                </div>
                            @endif
                            @if ($inscription->document_carte_inscript_ONMCI)
                                @php
                                    $extenCart = strtolower(
                                        pathinfo($inscription->document_carte_inscript_ONMCI, PATHINFO_EXTENSION),
                                    );
                                @endphp
                                <div class="col-md-3 col-sm-6 info-item">
                                    <span class="info-label">Carte ONMCI:</span><br>
                                    {{-- <a href="{{ asset($inscription->document_carte_inscript_ONMCI) }}" target="_blank"
                                        class="document-link">
                                        <i class="fa fa-eye me-1"></i>Voir
                                    </a> --}}

                                    @if ($extenCart == 'pdf')
                                        <a href="{{ route('visualise.pdf', ['fichier' => lienPdf($inscription->document_carte_inscript_ONMCI)]) }}"
                                            target="_blank" class="document-link">
                                            <i class="fa fa-eye me-1"></i> Voir
                                        </a>
                                    @elseif (in_array($extenCart, ['jpg', 'jpeg', 'png', 'jfif', 'heic', 'heif', 'webp']))
                                        <img src="{{ asset($inscription->document_carte_inscript_ONMCI) }}"
                                            alt="document_carte_inscript_ONMCI " style=" width:50px; height:50px;">
                                    @endif
                                </div>
                            @endif
                            @if ($inscription->document_autorisation_ouverture)
                                @php
                                    $extenCouver = strtolower(
                                        pathinfo($inscription->document_autorisation_ouverture, PATHINFO_EXTENSION),
                                    );
                                @endphp
                                <div class="col-md-3 col-sm-6 info-item">
                                    <span class="info-label">Autorisation:</span><br>
                                    {{-- <a href="{{ asset($inscription->document_autorisation_ouverture) }}" target="_blank"
                                        class="document-link">
                                        <i class="fa fa-eye me-1"></i>Voir
                                    </a> --}}

                                    @if ($extenCouver == 'pdf')
                                        <a href="{{ route('visualise.pdf', ['fichier' => lienPdf($inscription->document_autorisation_ouverture)]) }}"
                                            target="_blank" class="document-link">
                                            <i class="fa fa-eye me-1"></i> Voir
                                        </a>
                                    @elseif (in_array($extenCouver, ['jpg', 'jpeg', 'png', 'jfif', 'heic', 'heif', 'webp']))
                                        <img src="{{ asset($inscription->document_autorisation_ouverture) }}"
                                            alt="document_autorisation_ouverture " style=" width:50px; height:50px;">
                                    @endif
                                </div>
                            @endif
                            @if ($inscription->photo_identite_1)
                                @php
                                    $extenPiece = strtolower(
                                        pathinfo($inscription->photo_identite_1, PATHINFO_EXTENSION),
                                    );
                                @endphp
                                <div class="col-md-6 col-sm-6 info-item">
                                    <span class="info-label">deux (02 )Photos identités (Meme tirage):</span><br>
                                    {{-- <a href="{{ asset($inscription->photo_identite_1) }}" target="_blank"
                                        class="document-link">
                                        <i class="fa fa-eye me-1"></i>Voir
                                    </a> --}}

                                    @if ($extenPiece == 'pdf')
                                        <a href="{{ route('visualise.pdf', ['fichier' => lienPdf($inscription->photo_identite_1)]) }}"
                                            target="_blank" class="document-link">
                                            <i class="fa fa-eye me-1"></i> Voir
                                        </a>
                                    @elseif (in_array($extenPiece, ['jpg', 'jpeg', 'png', 'jfif', 'heic', 'heif', 'webp']))
                                        <img src="{{ asset($inscription->photo_identite_1) }}" alt="photo_identite_1 "
                                            style=" width:50px; height:50px;">
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Section Relations -->
                    @if ($inscription->relation_tiers || $inscription->etre_auteur)
                        <div class="info-section mt-3">
                            <h6><i class="fa fa-users me-2"></i>Relations professionnelles</h6>
                            <div class="row">
                                @if ($inscription->relation_tiers)
                                    <div class="col-md-6 info-item">
                                        <span class="info-label">Relation avec un tiers:</span>
                                        <span class="info-value">Oui</span>
                                    </div>
                                    @if ($inscription->nom_relation)
                                        <div class="col-md-6 info-item">
                                            <span class="info-label">Nom du tiers:</span>
                                            <span class="info-value">{{ $inscription->nom_relation }}</span>
                                        </div>
                                    @endif
                                @endif
                                @if ($inscription->etre_auteur)
                                    <div class="col-md-6 info-item">
                                        <span class="info-label">Être auteur:</span>
                                        <span class="info-value">Oui</span>
                                    </div>
                                @else
                                    <div class="col-md-6 info-item">
                                        <span class="info-label">Être auteur:</span>
                                        <span class="info-value">Non</span>
                                    </div>
                                @endif
                                @if ($inscription->nom_auteur)
                                    <div class="col-md-6 info-item">
                                        <span class="info-label">Nom d'auteur:</span>
                                        <span class="info-value">{{ $inscription->nom_auteur }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Section Historique -->
                    <div class="info-section mt-3">
                        <h6><i class="fa fa-history me-2"></i>Historique</h6>
                        <div class="row">
                            @if ($inscription->date_adhesion_unamepci)
                                <div class="col-md-6 info-item">
                                    <span class="info-label">Date adhésion UNAMEPCI:</span>
                                    <span class="info-value">{{ formatDate($inscription->date_adhesion_unamepci) }}</span>
                                </div>
                            @endif
                            <div class="col-md-6 info-item">
                                <span class="info-label">Date soumission:</span>
                                <span class="info-value">{{ formatDateTime($inscription->created_at) }}</span>
                            </div>
                            <div class="col-md-6 info-item">
                                <span class="info-label">Dernière modification:</span>
                                <span class="info-value">{{ formatDateTime($inscription->updated_at) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Section Actions (validation/rejet) -->
                    @if ($inscription->status == 2)
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-end">
                                    <button id="btn_rejeter" class="btn btn-outline-danger mx-2 fw-bold"
                                        style="display: none">
                                        <i class="fa fa-times me-1"></i> Rejeter
                                    </button>
                                    <button id="btn_approuver" class="btn btn-outline-success mx-2 fw-bold">
                                        <i class="fa fa-check me-1"></i> Approuver
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Formulaire de rejet -->
                        <form action="{{ route('inscriptions.rejeter', $inscription->id) }}" method="POST"
                            id="rejeter_inscription_form"
                            class="needs-validation mt-3 @if ($errors->has('commentaire')) was-validated @endif"
                            novalidate style="@if (!$errors->has('commentaire')) display: none; @endif">
                            @csrf
                            @method('PUT')
                            <div class="card border-danger">
                                <div class="card-header bg-danger text-white">
                                    <h6 class="mb-0">Motif du rejet</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="commentaire" class="form-label">Commentaire <span
                                                class="text-danger">*</span></label>
                                        <textarea name="commentaire" id="commentaire" class="form-control @error('commentaire') is-invalid @enderror"
                                            rows="3" placeholder="Veuillez indiquer le motif du rejet..." required>{{ old('commentaire') }}</textarea>
                                        @error('commentaire')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <button type="button" id="btn_annuler_rejet" class="btn btn-secondary me-2">
                                            Annuler
                                        </button>
                                        <button type="submit" class="btn btn-danger">
                                            Confirmer le rejet
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @endif

                    <!-- Affichage du commentaire si rejeté ou approuvé -->
                    @if (in_array($inscription->status, [1, 3]) && $inscription->commentaire)
                        <div class="alert {{ $inscription->status == 1 ? 'alert-success' : 'alert-danger' }} mt-3">
                            <h6 class="alert-heading">
                                @if ($inscription->status == 1)
                                    <i class="fa fa-check-circle me-1"></i> Commentaire d'approbation
                                @else
                                    <i class="fa fa-times-circle me-1"></i> Motif du rejet
                                @endif
                            </h6>
                            <p class="mb-0">{{ $inscription->commentaire }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>


    <!-- Modal de confirmation pour l'approbation -->
    <div class="modal fade confirmation-modal" id="approvalConfirmationModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fa fa-check-circle me-2"></i>Confirmation d'approbation
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="mb-4">
                        <i class="fa fa-question-circle fa-4x text-primary mb-3"></i>
                        <h5>Êtes-vous sûr de vouloir approuver cette inscription ?</h5>
                        <p class="text-muted mt-2">Cette action ne pourra pas être annulée.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-confirm" data-bs-dismiss="modal">
                        <i class="fa fa-times me-1"></i> Non
                    </button>
                    <form action="{{ route('inscriptions.approuver', $inscription->id) }}" method="POST"
                        id="approvalForm">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-success btn-confirm">
                            <i class="fa fa-check me-1"></i> Oui
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            // Gestion des boutons d'action
            $('#btn_rejeter').click(function() {
                $('#rejeter_inscription_form').slideToggle();
            });

            $('#btn_approuver').click(function() {
                $('#approvalConfirmationModal').modal('show');
            });

            $('#btn_annuler_rejet').click(function() {
                $('#rejeter_inscription_form').slideUp();
            });

            // Validation du formulaire de rejet
            $('#rejeter_inscription_form').submit(function(e) {
                if ($('#commentaire').val().trim() === '') {
                    e.preventDefault();
                    $('#commentaire').addClass('is-invalid');
                    $('#commentaire').siblings('.invalid-feedback').remove();
                    $('#commentaire').after(
                        '<div class="invalid-feedback">Veuillez indiquer le motif du rejet.</div>'
                    );
                }
            });

            // Si il y a des erreurs dans le formulaire de rejet, on l'affiche
            @if ($errors->has('commentaire'))
                $('#rejeter_inscription_form').show();
            @endif
        });
    </script>
@endpush
