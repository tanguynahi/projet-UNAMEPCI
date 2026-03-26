{{-- @extends('layouts.home', ['title' => "Formulaire d'identification"])
@push('css')
    <style>
        /* Styles généraux optimisés */
        .profile-image-container {
            width: 168px;
            height: 168px;
            position: relative;
            margin: 0 auto 30px;
            border-radius: 50%;
            overflow: hidden;
            cursor: pointer;
            border: 3px solid #f8f9fa;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .profile-image-container:hover {
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .profile-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .profile-image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .profile-image-container:hover .profile-image-overlay {
            opacity: 1;
        }

        .file-input {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
            z-index: 10;
        }

        /* Sections du formulaire */
        .form-section {
            background: #fff;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border: 1px solid #eaeaea;
        }

        .form-section-title {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #4a6cf7;
        }

        /* Champs de formulaire */
        .form-control-custom {
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: 12px 15px;
            height: auto;
            transition: all 0.3s;
        }

        .form-control-custom:focus {
            border-color: #4a6cf7;
            box-shadow: 0 0 0 0.2rem rgba(74, 108, 247, 0.25);
        }

        .required-field::after {
            content: " *";
            color: #dc3545;
        }

        /* Upload de documents */
        .document-upload {
            border: 2px dashed #e0e0e0;
            padding: 20px;
            border-radius: 6px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
        }

        .document-upload:hover {
            border-color: #4a6cf7;
            background: rgba(74, 108, 247, 0.05);
        }

        .document-upload input[type="file"] {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        /* Toggle Oui/Non */
        .oui-non-toggle {
            display: flex;
            gap: 20px;
            margin: 15px 0;
        }

        .toggle-option {
            position: relative;
            cursor: pointer;
            padding: 10px 30px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            transition: all 0.3s;
            text-align: center;
            flex: 1;
        }

        .toggle-option input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-option.active {
            border-color: #4a6cf7;
            background: rgba(74, 108, 247, 0.1);
            color: #4a6cf7;
            font-weight: 600;
        }

        /* Champs conditionnels */
        .conditional-field {
            margin-top: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 6px;
            border-left: 4px solid #4a6cf7;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Navigation entre sections */
        .form-navigation {
            position: sticky;
            top: 20px;
            z-index: 100;
        }

        .form-nav-item {
            padding: 10px 15px;
            margin-bottom: 5px;
            background: #f8f9fa;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .form-nav-item:hover {
            background: #e9ecef;
        }

        .form-nav-item.active {
            background: #4a6cf7;
            color: white;
            border-left-color: #2541b2;
        }

        /* Style pour les cartes de type d'adhésion */
        .form-check-card {
            position: relative;
        }

        .form-check-card .form-check-input {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .form-check-card .card {
            transition: all 0.3s;
            cursor: pointer;
        }

        .form-check-card .form-check-input:checked+label .card {
            border-color: #4a6cf7 !important;
            background: rgba(74, 108, 247, 0.05);
        }

        .form-check-card .form-check-input:checked+label .card i {
            color: #4a6cf7 !important;
        }

        .form-check-card .form-check-input:checked+label .card .card-title {
            color: #4a6cf7;
            font-weight: 600;
        }

        /* Style pour les select avec flèche */
        .select-custom {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 16px;
            padding-right: 40px;
        }

        /* Ajustement pour les champs conditionnels */
        .conditional-hidden {
            display: none !important;
        }

        /* Style pour le bouton de soumission */
        .submit-btn {
            background: linear-gradient(135deg, #4a6cf7 0%, #2541b2 100%);
            border: none;
            padding: 12px 40px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(74, 108, 247, 0.4);
        }

        /* Responsive */
        @media (max-width: 767px) {
            .form-section {
                padding: 15px;
            }

            .oui-non-toggle {
                flex-direction: column;
                gap: 10px;
            }

            .form-check-card {
                margin-bottom: 15px;
            }
        }
    </style>
    <style>
        canvas {
            border: 1px solid #000;
            cursor: crosshair;
        }

        button {
            margin: 5px;
        }
    </style>
@endpush

@section('content')
    <div class="rbt-breadcrumb-default bg-gradient-1">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-inner text-center">
                        <h2 class="title">Formulaire d'Identification</h2>
                        <p class="text-black mb-0">Veuillez remplir ce formulaire complet pour votre identification</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div id="validation-error-summary"
            style="background:#fff3f3;border:1px solid #dc3545;border-radius:8px;padding:16px 20px;margin-bottom:20px;color:#dc3545;">
            <strong><i class="fas fa-exclamation-triangle"></i> Veuillez corriger les erreurs suivantes :</strong>
            <ul style="margin:8px 0 0 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('inscriptTraite') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('POST')

        <div class="container mt-4">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-navigation">
                        <div class="form-nav-item active" data-section="section-personnel">
                            <i class="feather-user me-2"></i> Informations Personnelles
                        </div>
                        <div class="form-nav-item" data-section="section-documents-identite">
                            <i class="feather-file-text me-2"></i> Documents d'Identification
                        </div>
                        <div class="form-nav-item" data-section="section-professionnel">
                            <i class="feather-briefcase me-2"></i> Informations Professionnelles
                        </div>
                        <div class="form-nav-item" data-section="section-entreprise">
                            <i class="feather-home me-2"></i> Informations Entreprise
                        </div>
                        <div class="form-nav-item" data-section="section-freelance">
                            <i class="feather-users me-2"></i> Informations Freelance
                        </div>
                        <div class="form-nav-item" data-section="section-relations">
                            <i class="feather-link me-2"></i> Relations et Auteur
                        </div>
                        <div class="form-nav-item" data-section="section-documents">
                            <i class="feather-file me-2"></i> Documents à télécharger
                        </div>
                    </div>
                </div>

                <div class="col-md-9">
                    <!-- Photo de profil -->
                    <div class="row justify-content-center mb-4">
                        <div class="col-auto text-center">
                            <div class="profile-image-container">
                                <img id="profile-image-preview"
                                    src="{{ asset('assets/home/images/profil/profildefaut.jpg') }}" class="profile-image"
                                    alt="Photo de profil">
                                <div class="profile-image-overlay">Cliquez pour changer</div>
                                <input type="file" id="avatar" name="avatar" accept="image/*"
                                    class="file-input @error('avatar') is-invalid @enderror">
                                @error('avatar')
                                    <span class="invalid-feedback d-block text-center" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 1: Informations Personnelles -->
                    <div class="form-section active-section" id="section-personnel">
                        <h3 class="form-section-title">
                            <i class="feather-user me-2"></i> Informations Personnelles
                        </h3>

                        <!-- Type d'adhésion -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label required-field d-block mb-3">Type d'adhésion</label>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <div class="form-check-card">
                                            <input class="form-check-input" type="radio" name="typeAdhesion"
                                                id="typeAdhesion_nouveau" value="nouveau"
                                                {{ old('typeAdhesion') == 'nouveau' ? 'checked' : '' }} required>
                                            <label class="form-check-label w-100 h-100" for="typeAdhesion_nouveau">
                                                <div class="card border border-light h-100">
                                                    <div class="card-body text-center">
                                                        <i class="feather-user-plus fs-2 mb-2 text-muted"></i>
                                                        <h6 class="card-title">Nouveau</h6>
                                                        <p class="card-text small text-muted">Première adhésion</p>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>

                                </div>
                                @error('typeAdhesion')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3">
                            <!-- Identité -->
                            <div class="col-md-4">
                                <label for="civilite" class="form-label required-field">Civilité</label>
                                <div class="rbt-modern-select bg-transparent height-45">
                                    <select
                                        class="form-control form-control-custom select-custom @error('civilite') is-invalid @enderror"
                                        name="civilite" id="civilite" required>
                                        <option value="">Sélectionnez...</option>
                                        <option value="M." {{ old('civilite') == 'M.' ? 'selected' : '' }}>M.</option>
                                        <option value="Mme" {{ old('civilite') == 'Mme' ? 'selected' : '' }}>Mme
                                        </option>
                                        <option value="Mlle" {{ old('civilite') == 'Mlle' ? 'selected' : '' }}>Mlle
                                        </option>
                                    </select>
                                </div>
                                @error('civilite')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="nom" class="form-label required-field">Nom</label>
                                <input type="text" id="nom" name="nom" value="{{ old('nom') }}"
                                    class="form-control form-control-custom @error('nom') is-invalid @enderror" required
                                    placeholder="Votre nom">
                                @error('nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="prenom" class="form-label required-field">Prénom</label>
                                <input type="text" id="prenom" name="prenom" value="{{ old('prenom') }}"
                                    class="form-control form-control-custom @error('prenom') is-invalid @enderror" required
                                    placeholder="Votre prénom">
                                @error('prenom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Contacts -->
                            <div class="col-md-4">
                                <label for="contact" class="form-label required-field">Contact Principal</label>
                                <div class="input-group">
                                    <span class="input-group-text">+225</span>
                                    <input type="tel" id="contact" name="contact" value="{{ old('contact') }}"
                                        class="form-control form-control-custom @error('contact') is-invalid @enderror"
                                        required pattern="[0-9]{10}" maxlength="10" placeholder="0700000000">
                                </div>
                                <small class="text-muted">Ce numéro servira d'identifiant</small>
                                @error('contact')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="contact_2" class="form-label">Contact Secondaire</label>
                                <div class="input-group">
                                    <span class="input-group-text">+225</span>
                                    <input type="tel" id="contact_2" name="contact_2"
                                        value="{{ old('contact_2') }}"
                                        class="form-control form-control-custom @error('contact_2') is-invalid @enderror"
                                        pattern="[0-9]{10}" maxlength="10" placeholder="0700000000">
                                </div>
                                @error('contact_2')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="fax" class="form-label">Fax</label>
                                <input type="text" id="fax" name="fax" value="{{ old('fax') }}"
                                    class="form-control form-control-custom @error('fax') is-invalid @enderror"
                                    placeholder="Numéro de fax">
                                @error('fax')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label for="email" class="form-label required-field">Email</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}"
                                    class="form-control form-control-custom @error('email') is-invalid @enderror" required
                                    placeholder="votre@email.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Naissance -->
                            <div class="col-md-3">
                                <label for="date_naissance" class="form-label required-field">Date de Naissance</label>
                                <input type="date" id="date_naissance" name="date_naissance"
                                    value="{{ old('date_naissance') }}"
                                    class="form-control form-control-custom @error('date_naissance') is-invalid @enderror"
                                    required max="{{ date('Y-m-d') }}">
                                @error('date_naissance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label for="lieu_naissance" class="form-label required-field">Lieu de Naissance</label>
                                <input type="text" id="lieu_naissance" name="lieu_naissance"
                                    value="{{ old('lieu_naissance') }}"
                                    class="form-control form-control-custom @error('lieu_naissance') is-invalid @enderror"
                                    required placeholder="Ville, Pays">
                                @error('lieu_naissance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Nationalité et situation familiale -->
                            <div class="col-md-4">
                                <label for="nationalite" class="form-label required-field">Nationalité</label>
                                <input type="text" id="nationalite" name="nationalite"
                                    value="{{ old('nationalite') }}"
                                    class="form-control form-control-custom @error('nationalite') is-invalid @enderror"
                                    required placeholder="Votre nationalité">
                                @error('nationalite')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="situation_matrimoniale" class="form-label required-field">Situation
                                    Matrimoniale</label>
                                <div class="rbt-modern-select bg-transparent height-45">
                                    <select
                                        class="form-control form-control-custom select-custom @error('situation_matrimoniale') is-invalid @enderror"
                                        name="situation_matrimoniale" id="situation_matrimoniale" required>
                                        <option value="">Sélectionnez...</option>
                                        <option value="Célibataire"
                                            {{ old('situation_matrimoniale') == 'Célibataire' ? 'selected' : '' }}>
                                            Célibataire
                                        </option>
                                        <option value="Marié(e)"
                                            {{ old('situation_matrimoniale') == 'Marié(e)' ? 'selected' : '' }}>Marié(e)
                                        </option>
                                        <option value="Divorcé(e)"
                                            {{ old('situation_matrimoniale') == 'Divorcé(e)' ? 'selected' : '' }}>
                                            Divorcé(e)
                                        </option>
                                        <option value="Veuf/Veuve"
                                            {{ old('situation_matrimoniale') == 'Veuf/Veuve' ? 'selected' : '' }}>
                                            Veuf/Veuve
                                        </option>
                                    </select>
                                </div>
                                @error('situation_matrimoniale')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="nombre_charge" class="form-label">Nombre de personnes à charge</label>
                                <input type="number" id="nombre_charge" name="nombre_charge"
                                    value="{{ old('nombre_charge', 0) }}"
                                    class="form-control form-control-custom @error('nombre_charge') is-invalid @enderror"
                                    min="0" placeholder="0">
                                @error('nombre_charge')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="adresse" class="form-label required-field">Adresse personnelle</label>
                                <input type="text" id="adresse" name="adresse" value="{{ old('adresse') }}"
                                    class="form-control form-control-custom @error('adresse') is-invalid @enderror"
                                    required placeholder="Votre adresse personnelle">
                                @error('adresse')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Date adhésion UNAMEPCI -->
                            <div class="col-md-6">
                                <label for="date_adhesion_unamepci" class="form-label">Date d'adhésion UNAMEPCI</label>
                                <input type="date" id="date_adhesion_unamepci" name="date_adhesion_unamepci"
                                    value="{{ old('date_adhesion_unamepci') }}"
                                    class="form-control form-control-custom @error('date_adhesion_unamepci') is-invalid @enderror"
                                    max="{{ date('Y-m-d') }}">
                                @error('date_adhesion_unamepci')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Documents d'Identification -->
                    <div class="form-section conditional-hidden" id="section-documents-identite">
                        <h3 class="form-section-title">
                            <i class="feather-file-text me-2"></i> Documents d'Identification
                        </h3>

                        <div class="row g-3">
                            <!-- Type de pièce -->
                            <div class="col-md-6">
                                <label for="type_piece_id" class="form-label required-field">Type de pièce
                                    d'identité</label>
                                <div class="rbt-modern-select bg-transparent height-45">
                                    <select
                                        class="form-control form-control-custom select-custom @error('type_piece_id') is-invalid @enderror"
                                        name="type_piece_id" id="type_piece_id" required>
                                        <option value="">Sélectionnez...</option>
                                        @foreach ($typePieces as $typePiece)
                                            <option value="{{ $typePiece->id }}"
                                                {{ old('type_piece_id') == $typePiece->id ? 'selected' : '' }}>
                                                {{ $typePiece->libelle }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('type_piece_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="numero_piece" class="form-label required-field">Numéro de la pièce</label>
                                <input type="text" id="numero_piece" name="numero_piece"
                                    value="{{ old('numero_piece') }}"
                                    class="form-control form-control-custom @error('numero_piece') is-invalid @enderror"
                                    required placeholder="Numéro de la pièce">
                                @error('numero_piece')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Dates et lieu -->
                            <div class="col-md-4">
                                <label for="date_etablissement_piece" class="form-label required-field">Date
                                    d'établissement</label>
                                <input type="date" id="date_etablissement_piece" name="date_etablissement_piece"
                                    value="{{ old('date_etablissement_piece') }}"
                                    class="form-control form-control-custom @error('date_etablissement_piece') is-invalid @enderror"
                                    required max="{{ date('Y-m-d') }}">
                                @error('date_etablissement_piece')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-8">
                                <label for="lieu_etablissement_piece" class="form-label required-field">Lieu
                                    d'établissement</label>
                                <input type="text" id="lieu_etablissement_piece" name="lieu_etablissement_piece"
                                    value="{{ old('lieu_etablissement_piece') }}"
                                    class="form-control form-control-custom @error('lieu_etablissement_piece') is-invalid @enderror"
                                    required placeholder="Lieu où la pièce a été établie">
                                @error('lieu_etablissement_piece')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- ONMCI -->
                            <div class="col-md-6">
                                <label for="numero_inscription_ONMCI" class="form-label">N° d'inscription ONMCI</label>
                                <input type="text" id="numero_inscription_ONMCI" name="numero_inscription_ONMCI"
                                    value="{{ old('numero_inscription_ONMCI') }}"
                                    class="form-control form-control-custom @error('numero_inscription_ONMCI') is-invalid @enderror"
                                    placeholder="Numéro d'inscription ONMCI">
                                @error('numero_inscription_ONMCI')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="pseudonyme_recon_ONMCI" class="form-label">Pseudonyme de reconnaissance
                                    ONMCI</label>
                                <input type="text" id="pseudonyme_recon_ONMCI" name="pseudonyme_recon_ONMCI"
                                    value="{{ old('pseudonyme_recon_ONMCI') }}"
                                    class="form-control form-control-custom @error('pseudonyme_recon_ONMCI') is-invalid @enderror"
                                    placeholder="Pseudonyme ONMCI">
                                @error('pseudonyme_recon_ONMCI')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Upload des pièces -->
                            <div class="col-md-6">
                                <label class="form-label required-field">Recto de la pièce</label>
                                <div class="document-upload" id="rectoUpload">
                                    <input type="file" name="pieces_joints_recto" id="pieces_joints_recto"
                                        class="@error('pieces_joints_recto') is-invalid @enderror" required>
                                    <div class="upload-content">
                                        <i class="feather-upload fs-4 mb-2"></i>
                                        <p class="mb-1 upload-text">Cliquez pour télécharger le recto</p>
                                        <small class="text-muted">Format: JPEG, PNG, PDF (max 2MB)</small>
                                    </div>
                                </div>
                                @error('pieces_joints_recto')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label required-field">Verso de la pièce</label>
                                <div class="document-upload" id="versoUpload">
                                    <input type="file" name="pieces_joints_verso" id="pieces_joints_verso"
                                        class="@error('pieces_joints_verso') is-invalid @enderror" required>
                                    <div class="upload-content">
                                        <i class="feather-upload fs-4 mb-2"></i>
                                        <p class="mb-1 upload-text">Cliquez pour télécharger le verso</p>
                                        <small class="text-muted">Format: JPEG, PNG, PDF (max 2MB)</small>
                                    </div>
                                </div>
                                @error('pieces_joints_verso')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Informations Professionnelles -->
                    <div class="form-section conditional-hidden" id="section-professionnel">
                        <h3 class="form-section-title">
                            <i class="feather-briefcase me-2"></i> Informations Professionnelles Principales
                        </h3>

                        <div class="row g-3">
                            <!-- Matricule et raison sociale -->
                            <div class="col-md-6">
                                <label for="matricule" class="form-label required-field">Matricule</label>
                                <input type="text" id="matricule" name="matricule" value="{{ old('matricule') }}"
                                    class="form-control form-control-custom @error('matricule') is-invalid @enderror"
                                    required placeholder="Votre matricule">
                                @error('matricule')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="raison_social_primaire" class="form-label required-field">Raison sociale
                                    principale</label>
                                <input type="text" id="raison_social_primaire" name="raison_social_primaire"
                                    value="{{ old('raison_social_primaire') }}"
                                    class="form-control form-control-custom @error('raison_social_primaire') is-invalid @enderror"
                                    placeholder="Raison sociale" required>
                                @error('raison_social_primaire')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Spécialité et fonction -->
                            <div class="col-md-6">
                                <label for="specialite_id" class="form-label required-field">Spécialité</label>
                                <div class="rbt-modern-select bg-transparent height-45">
                                    <select
                                        class="form-control form-control-custom select-custom @error('specialite_id') is-invalid @enderror"
                                        name="specialite_id" id="specialite_id" required>
                                        <option value="">Sélectionnez...</option>
                                        @foreach ($specialites as $specialite)
                                            <option value="{{ $specialite->id }}"
                                                {{ old('specialite_id') == $specialite->id ? 'selected' : '' }}>
                                                {{ $specialite->libelle }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('specialite_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="fonction" class="form-label">Fonction occupée</label>
                                <input type="text" id="fonction" name="fonction" value="{{ old('fonction') }}"
                                    class="form-control form-control-custom @error('fonction') is-invalid @enderror"
                                    placeholder="Votre fonction">
                                @error('fonction')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Expérience -->
                            <div class="col-md-6">
                                <label for="date_debut_metier" class="form-label">Date de début dans le métier</label>
                                <input type="date" id="date_debut_metier" name="date_debut_metier"
                                    value="{{ old('date_debut_metier') }}"
                                    class="form-control form-control-custom @error('date_debut_metier') is-invalid @enderror"
                                    max="{{ date('Y-m-d') }}">
                                @error('date_debut_metier')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="nombre_annee_experience" class="form-label required-field ">Nombre d'années
                                    d'expérience</label>
                                <input type="number" id="nombre_annee_experience" name="nombre_annee_experience"
                                    value="{{ old('nombre_annee_experience') }}"
                                    class="form-control form-control-custom @error('nombre_annee_experience') is-invalid @enderror"
                                    min="0" placeholder="0" required>
                                @error('nombre_annee_experience')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="statut_emploi" class="form-label required-field">Statut d'emploi</label>
                                <div class="rbt-modern-select bg-transparent height-45">
                                    <select
                                        class="form-control form-control-custom select-custom @error('statut_emploi') is-invalid @enderror"
                                        name="statut_emploi" id="statut_emploi" required>
                                        <option value="">Sélectionnez...</option>

                                        <option value="Stage" {{ old('statut_emploi') == 'Stage' ? 'selected' : '' }}>
                                            Stage</option>
                                        <option value="CDD" {{ old('statut_emploi') == 'CDD' ? 'selected' : '' }}>
                                            CDD</option>
                                        <option value="CDI" {{ old('statut_emploi') == 'CDI' ? 'selected' : '' }}>
                                            CDI</option>
                                        <option value="Retraité"
                                            {{ old('statut_emploi') == 'Retraité' ? 'selected' : '' }}>
                                            Retraité</option>
                                    </select>
                                </div>
                                @error('statut_emploi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Domaine d'activité et employeur -->
                            <div class="col-md-6">
                                <label for="domaine_activite" class="form-label">Domaine d'activité</label>
                                <input type="text" id="domaine_activite" name="domaine_activite"
                                    value="{{ old('domaine_activite') }}"
                                    class="form-control form-control-custom @error('domaine_activite') is-invalid @enderror"
                                    placeholder="Domaine d'activité principal">
                                @error('domaine_activite')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="nom_employeur_principale" class="form-label">Nom de l'employeur
                                    principal</label>
                                <input type="text" id="nom_employeur_principale" name="nom_employeur_principale"
                                    value="{{ old('nom_employeur_principale') }}"
                                    class="form-control form-control-custom @error('nom_employeur_principale') is-invalid @enderror"
                                    placeholder="Nom de l'employeur">
                                @error('nom_employeur_principale')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>



                            <div class="col-md-6">
                                <label for="date_recrutement" class="form-label">Date de recrutement</label>
                                <input type="date" id="date_recrutement" name="date_recrutement"
                                    value="{{ old('date_recrutement') }}"
                                    class="form-control form-control-custom @error('date_recrutement') is-invalid @enderror"
                                    max="{{ date('Y-m-d') }}">
                                @error('date_recrutement')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 4: Informations sur l'Entreprise -->
                    <div class="form-section conditional-hidden" id="section-entreprise">
                        <h3 class="form-section-title">
                            <i class="feather-home me-2"></i> Informations sur l'Entreprise
                        </h3>

                        <div class="row g-3">
                            <!-- Identité entreprise -->
                            <div class="col-md-6">
                                <label for="sigle" class="form-label required-field">Sigle de l'entreprise</label>
                                <input type="text" id="sigle" name="sigle" value="{{ old('sigle') }}"
                                    class="form-control form-control-custom @error('sigle') is-invalid @enderror"
                                    placeholder="Sigle" required>
                                @error('sigle')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="date_creation" class="form-label">Date de création</label>
                                <input type="date" id="date_creation" name="date_creation"
                                    value="{{ old('date_creation') }}"
                                    class="form-control form-control-custom @error('date_creation') is-invalid @enderror"
                                    max="{{ date('Y-m-d') }}">
                                @error('date_creation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Autorisation et immatriculation -->
                            <div class="col-md-6">
                                <label for="numero_autorisation" class="form-label">N° d'autorisation</label>
                                <input type="text" id="numero_autorisation" name="numero_autorisation"
                                    value="{{ old('numero_autorisation') }}"
                                    class="form-control form-control-custom @error('numero_autorisation') is-invalid @enderror"
                                    placeholder="Numéro d'autorisation">
                                @error('numero_autorisation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="num_immatriculation" class="form-label">N° d'immatriculation</label>
                                <input type="text" id="num_immatriculation" name="num_immatriculation"
                                    value="{{ old('num_immatriculation') }}"
                                    class="form-control form-control-custom @error('num_immatriculation') is-invalid @enderror"
                                    placeholder="Numéro d'immatriculation">
                                @error('num_immatriculation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Forme juridique -->
                            <div class="col-md-6">
                                <label for="forme_juridique_id" class="form-label required-field">Forme juridique</label>
                                <div class="rbt-modern-select bg-transparent height-45">
                                    <select
                                        class="form-control form-control-custom select-custom @error('forme_juridique_id') is-invalid @enderror"
                                        name="forme_juridique_id" id="forme_juridique_id" required>
                                        <option value="">Sélectionnez...</option>
                                        @foreach ($formeJuridiques as $forme)
                                            <option value="{{ $forme->id }}"
                                                {{ old('forme_juridique_id') == $forme->id ? 'selected' : '' }}>
                                                {{ $forme->libelle }} ( {{ $forme->description }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('forme_juridique_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 conditional-hidden" id="precise-forme-juridique-container">
                                <label for="precise_forme_juridique" class="form-label">Précision forme juridique</label>
                                <input type="text" id="precise_forme_juridique" name="precise_forme_juridique"
                                    value="{{ old('precise_forme_juridique') }}"
                                    class="form-control form-control-custom @error('precise_forme_juridique') is-invalid @enderror"
                                    placeholder="Précisions supplémentaires">
                                @error('precise_forme_juridique')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Adresse entreprise -->
                            <div class="col-md-4">
                                <label for="ville_id" class="form-label required-field">Ville</label>
                                <div class="rbt-modern-select bg-transparent height-45">
                                    <select
                                        class="form-control form-control-custom select-custom @error('ville_id') is-invalid @enderror"
                                        name="ville_id" id="ville_id" required>
                                        <option value="">Sélectionnez...</option>
                                        @foreach ($villes as $ville)
                                            <option value="{{ $ville->id }}"
                                                {{ old('ville_id') == $ville->id ? 'selected' : '' }}>
                                                {{ $ville->libelle }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('ville_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="commune" class="form-label">Commune</label>
                                <input type="text" id="commune" name="commune" value="{{ old('commune') }}"
                                    class="form-control form-control-custom @error('commune') is-invalid @enderror"
                                    placeholder="Commune">
                                @error('commune')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="quartier" class="form-label">Quartier</label>
                                <input type="text" id="quartier" name="quartier" value="{{ old('quartier') }}"
                                    class="form-control form-control-custom @error('quartier') is-invalid @enderror"
                                    placeholder="Quartier">
                                @error('quartier')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Suite adresse -->
                            <div class="col-md-6">
                                <label for="rue" class="form-label">Rue</label>
                                <input type="text" id="rue" name="rue" value="{{ old('rue') }}"
                                    class="form-control form-control-custom @error('rue') is-invalid @enderror"
                                    placeholder="Nom de la rue">
                                @error('rue')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="adresse_postale_entreprise" class="form-label">Adresse postale
                                    entreprise</label>
                                <input type="text" id="adresse_postale_entreprise" name="adresse_postale_entreprise"
                                    value="{{ old('adresse_postale_entreprise') }}"
                                    class="form-control form-control-custom @error('adresse_postale_entreprise') is-invalid @enderror"
                                    placeholder="Adresse postale">
                                @error('adresse_postale_entreprise')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Contacts entreprise -->
                            <div class="col-md-6">
                                <label for="email_entreprise" class="form-label">Email entreprise</label>
                                <input type="email" id="email_entreprise" name="email_entreprise"
                                    value="{{ old('email_entreprise') }}"
                                    class="form-control form-control-custom @error('email_entreprise') is-invalid @enderror"
                                    placeholder="entreprise@email.com">
                                @error('email_entreprise')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="telephone_entreprise" class="form-label">Téléphone entreprise</label>
                                <div class="input-group">
                                    <span class="input-group-text">+225</span>
                                    <input type="tel" id="telephone_entreprise" name="telephone_entreprise"
                                        value="{{ old('telephone_entreprise') }}"
                                        class="form-control form-control-custom @error('telephone_entreprise') is-invalid @enderror"
                                        pattern="[0-9]{10}" maxlength="10" placeholder="0700000000">
                                </div>
                                @error('telephone_entreprise')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="fax_entreprise" class="form-label">Fax entreprise</label>
                                <input type="text" id="fax_entreprise" name="fax_entreprise"
                                    value="{{ old('fax_entreprise') }}"
                                    class="form-control form-control-custom @error('fax_entreprise') is-invalid @enderror"
                                    placeholder="Fax entreprise">
                                @error('fax_entreprise')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="localisation_entreprise" class="form-label required-field">Localisation
                                    entreprise</label>
                                <input type="text" id="localisation_entreprise" name="localisation_entreprise"
                                    value="{{ old('localisation_entreprise') }}"
                                    class="form-control form-control-custom @error('localisation_entreprise') is-invalid @enderror"
                                    required placeholder="Localisation de l'entreprise">
                                @error('localisation_entreprise')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 5: Informations Freelance -->
                    <div class="form-section conditional-hidden" id="section-freelance">
                        <h3 class="form-section-title">
                            <i class="feather-users me-2"></i> Informations Freelance/Activité Secondaire
                        </h3>

                        <div class="row g-3">
                            <!-- Raison sociale freelance -->
                            <div class="col-md-6">
                                <label for="raison_social_secondaire_freelance" class="form-label">Raison sociale
                                    freelance</label>
                                <input type="text" id="raison_social_secondaire_freelance"
                                    name="raison_social_secondaire_freelance"
                                    value="{{ old('raison_social_secondaire_freelance') }}"
                                    class="form-control form-control-custom @error('raison_social_secondaire_freelance') is-invalid @enderror"
                                    placeholder="Raison sociale freelance">
                                @error('raison_social_secondaire_freelance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Fonction freelance -->
                            <div class="col-md-6">
                                <label for="fonction_occupe_freelance" class="form-label">Fonction occupée
                                    (freelance)</label>
                                <input type="text" id="fonction_occupe_freelance" name="fonction_occupe_freelance"
                                    value="{{ old('fonction_occupe_freelance') }}"
                                    class="form-control form-control-custom @error('fonction_occupe_freelance') is-invalid @enderror"
                                    placeholder="Fonction freelance">
                                @error('fonction_occupe_freelance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Type de contrat freelance -->
                            <div class="col-md-6">
                                <label for="type_contrat_freelance" class="form-label">Type de contrat (freelance)</label>
                                <div class="rbt-modern-select bg-transparent height-45">
                                    <select
                                        class="form-control form-control-custom select-custom @error('type_contrat_freelance') is-invalid @enderror"
                                        name="type_contrat_freelance" id="type_contrat_freelance">
                                        <option value="">Sélectionnez...</option>
                                        <option value="CDI"
                                            {{ old('type_contrat_freelance') == 'CDI' ? 'selected' : '' }}>CDI</option>
                                        <option value="CDD"
                                            {{ old('type_contrat_freelance') == 'CDD' ? 'selected' : '' }}>CDD</option>
                                        <option value="Prestation"
                                            {{ old('type_contrat_freelance') == 'Prestation' ? 'selected' : '' }}>
                                            Prestation
                                        </option>
                                        <option value="Consultant"
                                            {{ old('type_contrat_freelance') == 'Consultant' ? 'selected' : '' }}>
                                            Consultant
                                        </option>
                                        <option value="Autre"
                                            {{ old('type_contrat_freelance') == 'Autre' ? 'selected' : '' }}>Autre
                                        </option>
                                    </select>
                                </div>
                                @error('type_contrat_freelance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Contacts freelance -->
                            <div class="col-md-6">
                                <label for="telephone_freelance" class="form-label">Téléphone freelance</label>
                                <div class="input-group">
                                    <span class="input-group-text">+225</span>
                                    <input type="tel" id="telephone_freelance" name="telephone_freelance"
                                        value="{{ old('telephone_freelance') }}"
                                        class="form-control form-control-custom @error('telephone_freelance') is-invalid @enderror"
                                        pattern="[0-9]{10}" maxlength="10" placeholder="0700000000">
                                </div>
                                @error('telephone_freelance')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Localisation freelance -->
                            <div class="col-md-6">
                                <label for="localisation_freelance" class="form-label">Localisation freelance</label>
                                <input type="text" id="localisation_freelance" name="localisation_freelance"
                                    value="{{ old('localisation_freelance') }}"
                                    class="form-control form-control-custom @error('localisation_freelance') is-invalid @enderror"
                                    placeholder="Localisation">
                                @error('localisation_freelance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="adresse_postale_freelance" class="form-label">Adresse postale
                                    freelance</label>
                                <input type="text" id="adresse_postale_freelance" name="adresse_postale_freelance"
                                    value="{{ old('adresse_postale_freelance') }}"
                                    class="form-control form-control-custom @error('adresse_postale_freelance') is-invalid @enderror"
                                    placeholder="Adresse postale freelance">
                                @error('adresse_postale_freelance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Domaine activité freelance -->
                            <div class="col-md-6">
                                <label for="domaine_activite_freelance" class="form-label">Domaine d'activité
                                    (freelance)</label>
                                <input type="text" id="domaine_activite_freelance" name="domaine_activite_freelance"
                                    value="{{ old('domaine_activite_freelance') }}"
                                    class="form-control form-control-custom @error('domaine_activite_freelance') is-invalid @enderror"
                                    placeholder="Domaine d'activité freelance">
                                @error('domaine_activite_freelance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="fax_freelance" class="form-label">Fax freelance</label>
                                <input type="text" id="fax_freelance" name="fax_freelance"
                                    value="{{ old('fax_freelance') }}"
                                    class="form-control form-control-custom @error('fax_freelance') is-invalid @enderror"
                                    placeholder="Fax freelance">
                                @error('fax_freelance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 6: Relations et Information sur l'Auteur -->
                    <div class="form-section conditional-hidden" id="section-relations">
                        <h3 class="form-section-title">
                            <i class="feather-link me-2"></i> Relations et Information sur l'Auteur
                        </h3>

                        <!-- Question 1: Relation avec un tiers -->
                        <div class="mb-4">
                            <label class="form-label d-block">Avez-vous une relation avec un tiers ?</label>
                            <div class="oui-non-toggle">
                                <label class="toggle-option" id="toggleRelationNon">
                                    <input type="radio" name="relation_tiers" value="0"
                                        {{ old('relation_tiers', 0) == 0 ? 'checked' : '' }}>
                                    Non
                                </label>
                                <label class="toggle-option" id="toggleRelationOui">
                                    <input type="radio" name="relation_tiers" value="1"
                                        {{ old('relation_tiers') == 1 ? 'checked' : '' }}>
                                    Oui
                                </label>
                            </div>

                            <div class="conditional-field mt-3 conditional-hidden" id="relationField">
                                <label for="nom_relation" class="form-label">Nom du tiers</label>
                                <input type="text" class="form-control form-control-custom" name="nom_relation"
                                    id="nom_relation" value="{{ old('nom_relation') }}"
                                    placeholder="Nom du tiers avec qui vous avez une relation">
                            </div>
                        </div>

                        <!-- Question 2: L'auteur de l'adhésion -->
                        <div id="questionAuteurSection">
                            <label class="form-label d-block">Qui est l'auteur de votre adhésion ?</label>

                            <div class="conditional-field p-3 mb-3 conditional-hidden" id="auteurTiersSection">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="etre_auteur"
                                        id="auteur_tiers_oui" value="1"
                                        {{ old('etre_auteur') == 1 && old('relation_tiers') == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="auteur_tiers_oui">
                                        C'est le tiers mentionné ci-dessus
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="etre_auteur"
                                        id="auteur_tiers_non" value="0"
                                        {{ old('etre_auteur') == 0 && old('relation_tiers') == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="auteur_tiers_non">
                                        C'est une autre personne
                                    </label>
                                </div>
                            </div>

                            <!-- Champ pour le nom de l'auteur (si différent du tiers ou si pas de tiers) -->
                            <div class="conditional-field mt-3" id="nomAuteurContainer">
                                <label for="nom_auteur" class="form-label ">Nom de l'auteur</label>
                                <input type="text" class="form-control form-control-custom" name="nom_auteur"
                                    id="nom_auteur" value="{{ old('nom_auteur') }}"
                                    placeholder="Nom de la personne qui a effectué votre adhésion">
                            </div>
                        </div>
                    </div>

                    <!-- Section 7: Documents à télécharger -->
                    <div class="form-section conditional-hidden" id="section-documents">
                        <h3 class="form-section-title">
                            <i class="feather-file me-2"></i> Documents à télécharger
                        </h3>

                        <div class="row g-3">
                            <!-- Photo de couverture -->
                            <div class="col-md-6">
                                <label for="photo_couverture" class="form-label">Photo de couverture</label>
                                <div class="document-upload" id="couvertureUpload">
                                    <input type="file" name="photo_couverture" id="photo_couverture"
                                        class="@error('photo_couverture') is-invalid @enderror" accept=".jpeg ,.png">
                                    <div class="upload-content">
                                        <i class="feather-upload fs-4 mb-2"></i>
                                        <p class="mb-1 upload-text">Cliquez pour télécharger</p>
                                        <small class="text-muted">Format: JPEG, PNG (max 2MB)</small>
                                    </div>
                                </div>
                                @error('photo_couverture')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Carte inscription ONMCI -->
                            <div class="col-md-6">
                                <label for="document_carte_inscript_ONMCI" class="form-label required-field">Carte
                                    d'inscription
                                    ONMCI</label>
                                <div class="document-upload" id="carteOnmciUpload">
                                    <input type="file" name="document_carte_inscript_ONMCI"
                                        id="document_carte_inscript_ONMCI"
                                        class="@error('document_carte_inscript_ONMCI') is-invalid @enderror"
                                        accept="application/pdf,.pdf" required>
                                    <div class="upload-content">
                                        <i class="feather-upload fs-4 mb-2"></i>
                                        <p class="mb-1 upload-text">Cliquez pour télécharger</p>
                                        <small class="text-muted">Format: PDF (max 2MB)</small>
                                    </div>
                                </div>
                                @error('document_carte_inscript_ONMCI')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Autorisation d'ouverture -->
                            <div class="col-md-6">
                                <label for="document_autorisation_ouverture"
                                    class="form-label required-field">Autorisation
                                    d'ouverture</label>
                                <div class="document-upload" id="autorisationUpload">
                                    <input type="file" name="document_autorisation_ouverture"
                                        id="document_autorisation_ouverture"
                                        class="@error('document_autorisation_ouverture') is-invalid @enderror"
                                        accept="application/pdf,.pdf" required>
                                    <div class="upload-content">
                                        <i class="feather-upload fs-4 mb-2"></i>
                                        <p class="mb-1 upload-text">Cliquez pour télécharger</p>
                                        <small class="text-muted">Format: PDF (max 2MB)</small>
                                    </div>
                                </div>
                                @error('document_autorisation_ouverture')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Photos d'identité supplémentaires -->
                            <div class="col-md-6">
                                <label for="photo_identite_1" class="form-label required-field">Deux (2) Photos d'identité
                                    meme tirage</label>
                                <div class="document-upload" id="photoIdentite1Upload">
                                    <input type="file" name="photo_identite_1" id="photo_identite_1"
                                        class="@error('photo_identite_1') is-invalid @enderror"
                                        accept="application/pdf,.pdf" required>
                                    <div class="upload-content">
                                        <i class="feather-upload fs-4 mb-2"></i>
                                        <p class="mb-1 upload-text">Cliquez pour télécharger</p>
                                        <small class="text-muted">Format: PDF (max 2MB)</small>
                                    </div>
                                </div>
                                @error('photo_identite_1')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>


                        </div>
                        <br>
                        <h3 class="form-section-title">
                            <i class="feather-file me-2 mt-2"></i>Signature
                        </h3>

                        <div class="row g-3">


                            <div class="col-12 col-lg-12 col-md-12 col-sm-12 text-center">
                                <h6 for="signature" class="fw-bold">Votre signature <span class="text-danger">*</span>
                                </h6>
                                <canvas id="signature-pad" width="300" height="300"
                                    class="@error('signature') is-invalid @enderror"></canvas>
                                <br>

                                @error('signature')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <br>
                                <div class="row">
                                    <div class="col-12 col-lg-12 col-md-12 col-sm-12">
                                        <button id="save-btn" class="btn btn-primary">Enregistrer la
                                            signature</button>
                                        <button id="clear-btn" class="btn btn-danger">Effacer</button>
                                        <input type="file" name="signature" id="signature" style="display: none"
                                            value="{{ old('signature') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Boutons de navigation et soumission -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <button type="button" class="rbt-btn btn-secondary" id="prevSection">
                                    <i class="feather-arrow-left me-2"></i>Précédent
                                </button>

                                <button type="button" class="rbt-btn btn-primary" id="nextSection">
                                    Suivant<i class="feather-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-12 mt-4 text-center">
                            <div class="rbt-form-group">
                                <button type="submit" class="rbt-btn btn-primary " id="submitBtn">
                                    <i class="feather-check-circle me-2"></i>Soumettre le formulaire
                                </button>
                            </div>
                            <p class="text-muted mt-2"><span class="text-danger">*</span> Champs obligatoires</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const submitBtn = document.getElementById('submitBtn');

            form.addEventListener('submit', function() {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="feather-loader me-2"></i> Envoi en cours...';
            });
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const canvas = document.getElementById('signature-pad');
            const context = canvas.getContext('2d');
            let drawing = false;

            // Gestionnaires pour les événements de souris
            canvas.addEventListener('mousedown', startDrawing);
            canvas.addEventListener('mousemove', draw);
            canvas.addEventListener('mouseup', stopDrawing);
            canvas.addEventListener('mouseout', stopDrawing);

            // Gestionnaires pour les événements tactiles
            canvas.addEventListener('touchstart', startDrawing);
            canvas.addEventListener('touchmove', draw);
            canvas.addEventListener('touchend', stopDrawing);

            function startDrawing(event) {
                event.preventDefault();
                drawing = true;
                const {
                    offsetX,
                    offsetY
                } = getEventPosition(event);
                context.beginPath();
                context.moveTo(offsetX, offsetY);
            }

            function draw(event) {
                event.preventDefault();
                if (!drawing) return;
                const {
                    offsetX,
                    offsetY
                } = getEventPosition(event);
                context.lineTo(offsetX, offsetY);
                context.stroke();
            }

            function stopDrawing(event) {
                event.preventDefault();
                drawing = false;
            }

            function getEventPosition(event) {
                if (event.touches && event.touches[0]) {
                    const rect = canvas.getBoundingClientRect();
                    return {
                        offsetX: event.touches[0].clientX - rect.left,
                        offsetY: event.touches[0].clientY - rect.top
                    };
                } else {
                    return {
                        offsetX: event.offsetX,
                        offsetY: event.offsetY
                    };
                }
            }

            function isCanvasBlank(canvas) {
                const blank = document.createElement('canvas');
                blank.width = canvas.width;
                blank.height = canvas.height;
                return canvas.toDataURL() === blank.toDataURL();
            }

            document.getElementById('save-btn').addEventListener('click', (event) => {
                event.preventDefault(); // Prevent form submission

                if (isCanvasBlank(canvas)) {
                    Swal.fire({
                        title: 'Erreur!',
                        text: 'Veuillez entrer une signature.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                const dataURL = canvas.toDataURL('image/png');
                const blob = dataURLToBlob(dataURL);
                const file = new File([blob], 'signature.png', {
                    type: 'image/png'
                });
                const fileInput = document.getElementById('signature');
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                fileInput.files = dataTransfer.files;

                // Trigger a change event for the file input
                const fileInputChangeEvent = new Event('change', {
                    bubbles: true
                });
                fileInput.dispatchEvent(fileInputChangeEvent);

                // Optionally provide user feedback
                Swal.fire({
                    title: 'Succès!',
                    text: 'Signature enregistrée.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            });

            document.getElementById('clear-btn').addEventListener('click', (event) => {
                event.preventDefault(); // Prevent form submission
                context.clearRect(0, 0, canvas.width, canvas.height);
            });

            function dataURLToBlob(dataURL) {
                const byteString = atob(dataURL.split(',')[1]);
                const mimeString = dataURL.split(',')[0].split(':')[1].split(';')[0];
                const ab = new ArrayBuffer(byteString.length);
                const ia = new Uint8Array(ab);
                for (let i = 0; i < byteString.length; i++) {
                    ia[i] = byteString.charCodeAt(i);
                }
                return new Blob([ab], {
                    type: mimeString
                });
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            /* =========================
               NAVIGATION MULTI-SECTIONS
            ========================== */
            const sections = [
                'section-personnel',
                'section-documents-identite',
                'section-professionnel',
                'section-entreprise',
                'section-freelance',
                'section-relations',
                'section-documents'
            ];

            let currentSection = 0;

            function showSection(index) {
                $('.form-section').addClass('conditional-hidden');
                $('#' + sections[index]).removeClass('conditional-hidden');

                $('html, body').animate({
                    scrollTop: $('#' + sections[index]).offset().top - 100
                }, 400);
            }

            function updateNavigation() {
                $('.form-nav-item').removeClass('active');
                $('.form-nav-item[data-section="' + sections[currentSection] + '"]').addClass('active');
            }

            // Initialisation
            showSection(currentSection);
            updateNavigation();

            // Navigation par les onglets
            $('.form-nav-item').on('click', function() {
                const index = sections.indexOf($(this).data('section'));
                if (index !== -1) {
                    currentSection = index;
                    showSection(currentSection);
                    updateNavigation();
                }
            });

            // Boutons suivant/précédent
            $('#nextSection').on('click', function() {
                if (currentSection < sections.length - 1) {
                    currentSection++;
                    showSection(currentSection);
                    updateNavigation();
                }
            });

            $('#prevSection').on('click', function() {
                if (currentSection > 0) {
                    currentSection--;
                    showSection(currentSection);
                    updateNavigation();
                }
            });

            /* =========================
               UPLOAD DOCUMENTS
            ========================== */
            // Gestion de l'avatar
            $('#avatar').on('change', function(e) {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#profile-image-preview').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(file);
                }
            });

            // Gestion des uploads de documents
            $('.document-upload').on('click', function() {
                $(this).find('input[type="file"]').trigger('click');
            });

            $('.document-upload input[type="file"]').on('change', function() {
                const file = this.files[0];
                const container = $(this).closest('.document-upload');
                const text = container.find('.upload-text');

                if (file) {
                    text.text(file.name);
                    container.css('border-color', '#4a6cf7');
                }
            });

            /* =========================
               AUTRES FONCTIONNALITÉS
            ========================== */
            // Gestion des toggle Oui/Non
            $('.toggle-option').click(function() {
                const parent = $(this).closest('.oui-non-toggle');
                parent.find('.toggle-option').removeClass('active');
                $(this).addClass('active');
                $(this).find('input[type="radio"]').prop('checked', true);

                // Logique spécifique pour les relations
                if ($(this).find('input[name="relation_tiers"]').length) {
                    handleRelationToggle();
                }
            });

            function handleRelationToggle() {
                const hasRelation = $('input[name="relation_tiers"]:checked').val() == '1';

                if (hasRelation) {
                    $('#relationField').removeClass('conditional-hidden');
                    $('#auteurTiersSection').removeClass('conditional-hidden');
                    $('#nomAuteurContainer').addClass('conditional-hidden');
                } else {
                    $('#relationField').addClass('conditional-hidden');
                    $('#auteurTiersSection').addClass('conditional-hidden');
                    $('#nomAuteurContainer').removeClass('conditional-hidden');
                }
            }

            // Initialiser les toggles
            $('input[type="radio"]:checked').each(function() {
                $(this).closest('.toggle-option').addClass('active');
            });
            handleRelationToggle();

            // Gestion de l'auteur
            $('input[name="etre_auteur"]').change(function() {
                const tiersIsAuthor = $(this).val() == '1';
                if (tiersIsAuthor) {
                    $('#nomAuteurContainer').addClass('conditional-hidden');
                } else {
                    $('#nomAuteurContainer').removeClass('conditional-hidden');
                }
            });

            // Gestion de la forme juridique
            $('#forme_juridique_id').change(function() {
                if ($(this).val() == '16') {
                    $('#precise-forme-juridique-container').removeClass('conditional-hidden');
                } else {
                    $('#precise-forme-juridique-container').addClass('conditional-hidden');
                }
            });

            // Cartes de type d'adhésion
            $('.form-check-card input[type="radio"]').change(function() {
                $('.form-check-card').each(function() {
                    const card = $(this);
                    if (card.find('input:checked').length > 0) {
                        card.find('.card').addClass('border-primary bg-light');
                        card.find('.card i').addClass('text-primary').removeClass('text-muted');
                        card.find('.card .card-title').addClass('text-primary');
                    } else {
                        card.find('.card').removeClass('border-primary bg-light');
                        card.find('.card i').removeClass('text-primary').addClass('text-muted');
                        card.find('.card .card-title').removeClass('text-primary');
                    }
                });
            });

            // Initialiser les cartes
            $('.form-check-card input[type="radio"]:checked').trigger('change');

            // Labels dynamiques pour le type de pièce
            $('#type_piece_id').change(function() {
                const label = $(this).find('option:selected').text();
                if (label && label !== 'Sélectionnez...') {
                    $('#rectoUpload .upload-text').text('Recto ' + label);
                    $('#versoUpload .upload-text').text('Verso ' + label);
                }
            });

            // Initialiser les labels si déjà sélectionné
            if ($('#type_piece_id').val()) {
                $('#type_piece_id').trigger('change');
            }
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Afficher les erreurs de validation Laravel
            @if ($errors->any())
                setTimeout(function() {
                    // Afficher une alerte avec le nombre d'erreurs
                    alert('Veuillez corriger les ' + {{ $errors->count() }} +
                        ' erreur(s) dans le formulaire.');

                    // Chercher et afficher la première section avec erreur
                    let hasDisplayedSection = false;

                    // Convertir les erreurs en tableau pour JavaScript
                    const errors = @json($errors->toArray());

                    // Parcourir les erreurs
                    Object.keys(errors).forEach(function(key, index) {
                        const field = document.querySelector('[name="' + key + '"]');
                        if (field) {
                            const section = field.closest('.form-section');
                            if (section && section.classList.contains('conditional-hidden')) {
                                section.classList.remove('conditional-hidden');
                                hasDisplayedSection = true;

                                // Mettre à jour la navigation
                                const sectionId = section.id;
                                const navItem = document.querySelector(
                                    '.form-nav-item[data-section="' + sectionId + '"]');
                                if (navItem) {
                                    document.querySelectorAll('.form-nav-item').forEach(item => {
                                        item.classList.remove('active');
                                    });
                                    navItem.classList.add('active');
                                }

                                // Scroll vers le champ seulement pour la première erreur
                                if (index === 0) {
                                    field.scrollIntoView({
                                        behavior: 'smooth',
                                        block: 'center'
                                    });
                                }
                            }
                        }
                    });

                    // Si aucune section n'a été affichée mais qu'il y a des erreurs
                    if (!hasDisplayedSection && Object.keys(errors).length > 0) {
                        const firstSection = document.querySelector('.form-section');
                        if (firstSection) {
                            firstSection.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        }
                    }

                }, 500);
            @endif

            // Afficher les messages de session
            @if (session('success'))
                showNotification('{{ session('success') }}', 'success');
            @endif

            @if (session('error'))
                showNotification('{{ session('error') }}', 'error');
            @endif

            @if (session('info'))
                showNotification('{{ session('info') }}', 'info');
            @endif

            function showNotification(message, type) {
                // Créer l'élément de notification
                const notification = document.createElement('div');
                notification.className = `alert alert-${type} alert-dismissible fade show`;
                notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                min-width: 300px;
                max-width: 400px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                border-radius: 8px;
            `;

                // Icône selon le type
                let icon = 'ℹ️';
                if (type === 'success') icon = '✅';
                if (type === 'error') icon = '❌';

                notification.innerHTML = `
                <div class="d-flex align-items-center">
                    <span style="font-size: 1.5rem; margin-right: 10px;">${icon}</span>
                    <div style="flex: 1;">${message}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;

                // Ajouter au body
                document.body.appendChild(notification);

                // Auto-fermer après 5 secondes
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, 5000);
            }
        });
    </script>
@endpush --}}


{{-- @extends('layouts.home', ['title' => "Formulaire d'identification"])
@push('css')
    <style>
        /* Styles généraux optimisés */
        .profile-image-container {
            width: 168px;
            height: 168px;
            position: relative;
            margin: 0 auto 30px;
            border-radius: 50%;
            overflow: hidden;
            cursor: pointer;
            border: 3px solid #f8f9fa;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .profile-image-container:hover {
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .profile-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .profile-image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .profile-image-container:hover .profile-image-overlay {
            opacity: 1;
        }

        .file-input {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
            z-index: 10;
        }

        /* Sections du formulaire */
        .form-section {
            background: #fff;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border: 1px solid #eaeaea;
        }

        .form-section-title {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #4a6cf7;
        }

        /* Champs de formulaire */
        .form-control-custom {
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: 12px 15px;
            height: auto;
            transition: all 0.3s;
        }

        .form-control-custom:focus {
            border-color: #4a6cf7;
            box-shadow: 0 0 0 0.2rem rgba(74, 108, 247, 0.25);
        }

        .required-field::after {
            content: " *";
            color: #dc3545;
        }

        /* Upload de documents */
        .document-upload {
            border: 2px dashed #e0e0e0;
            padding: 20px;
            border-radius: 6px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
        }

        .document-upload:hover {
            border-color: #4a6cf7;
            background: rgba(74, 108, 247, 0.05);
        }

        .document-upload input[type="file"] {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        /* Toggle Oui/Non */
        .oui-non-toggle {
            display: flex;
            gap: 20px;
            margin: 15px 0;
        }

        .toggle-option {
            position: relative;
            cursor: pointer;
            padding: 10px 30px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            transition: all 0.3s;
            text-align: center;
            flex: 1;
        }

        .toggle-option input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-option.active {
            border-color: #4a6cf7;
            background: rgba(74, 108, 247, 0.1);
            color: #4a6cf7;
            font-weight: 600;
        }

        /* Champs conditionnels */
        .conditional-field {
            margin-top: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 6px;
            border-left: 4px solid #4a6cf7;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Navigation entre sections */
        .form-navigation {
            position: sticky;
            top: 20px;
            z-index: 100;
        }

        .form-nav-item {
            padding: 10px 15px;
            margin-bottom: 5px;
            background: #f8f9fa;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .form-nav-item:hover {
            background: #e9ecef;
        }

        .form-nav-item.active {
            background: #4a6cf7;
            color: white;
            border-left-color: #2541b2;
        }

        /* Style pour les cartes de type d'adhésion */
        .form-check-card {
            position: relative;
        }

        .form-check-card .form-check-input {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .form-check-card .card {
            transition: all 0.3s;
            cursor: pointer;
        }

        .form-check-card .form-check-input:checked+label .card {
            border-color: #4a6cf7 !important;
            background: rgba(74, 108, 247, 0.05);
        }

        .form-check-card .form-check-input:checked+label .card i {
            color: #4a6cf7 !important;
        }

        .form-check-card .form-check-input:checked+label .card .card-title {
            color: #4a6cf7;
            font-weight: 600;
        }

        /* Style pour les select avec flèche */
        .select-custom {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 16px;
            padding-right: 40px;
        }

        /* Ajustement pour les champs conditionnels */
        .conditional-hidden {
            display: none !important;
        }

        /* Style pour le bouton de soumission */
        .submit-btn {
            background: linear-gradient(135deg, #4a6cf7 0%, #2541b2 100%);
            border: none;
            padding: 12px 40px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(74, 108, 247, 0.4);
        }

        /* Responsive */
        @media (max-width: 767px) {
            .form-section {
                padding: 15px;
            }

            .oui-non-toggle {
                flex-direction: column;
                gap: 10px;
            }

            .form-check-card {
                margin-bottom: 15px;
            }
        }
    </style>
    <style>
        canvas {
            border: 1px solid #000;
            cursor: crosshair;
        }

        button {
            margin: 5px;
        }
    </style>
@endpush

@section('content')
    <div class="rbt-breadcrumb-default bg-gradient-1">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-inner text-center">
                        <h2 class="title">Formulaire d'Identification</h2>
                        <p class="text-black mb-0">Veuillez remplir ce formulaire complet pour votre identification</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div id="validation-error-summary"
            style="background:#fff3f3;border:1px solid #dc3545;border-radius:8px;padding:16px 20px;margin-bottom:20px;color:#dc3545;">
            <strong><i class="fas fa-exclamation-triangle"></i> Veuillez corriger les erreurs suivantes :</strong>
            <ul style="margin:8px 0 0 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('inscriptTraite') }}" method="POST" enctype="multipart/form-data" id="mainForm">
        @csrf
        @method('POST')

        <div class="container mt-4">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-navigation">
                        <div class="form-nav-item active" data-section="section-personnel">
                            <i class="feather-user me-2"></i> Informations Personnelles
                        </div>
                        <div class="form-nav-item" data-section="section-documents-identite">
                            <i class="feather-file-text me-2"></i> Documents d'Identification
                        </div>
                        <div class="form-nav-item" data-section="section-professionnel">
                            <i class="feather-briefcase me-2"></i> Informations Professionnelles
                        </div>
                        <div class="form-nav-item" data-section="section-entreprise">
                            <i class="feather-home me-2"></i> Informations Entreprise
                        </div>
                        <div class="form-nav-item" data-section="section-freelance">
                            <i class="feather-users me-2"></i> Informations Freelance
                        </div>
                        <div class="form-nav-item" data-section="section-relations">
                            <i class="feather-link me-2"></i> Relations et Auteur
                        </div>
                        <div class="form-nav-item" data-section="section-documents">
                            <i class="feather-file me-2"></i> Documents à télécharger
                        </div>
                    </div>
                </div>

                <div class="col-md-9">
                    <!-- Photo de profil -->
                    <div class="row justify-content-center mb-4">
                        <div class="col-auto text-center">
                            <div class="profile-image-container">
                                <img id="profile-image-preview"
                                    src="{{ asset('assets/home/images/profil/profildefaut.jpg') }}" class="profile-image"
                                    alt="Photo de profil">
                                <div class="profile-image-overlay">Cliquez pour changer</div>
                                <input type="file" id="avatar" name="avatar" accept="image/*"
                                    class="file-input @error('avatar') is-invalid @enderror">
                                @error('avatar')
                                    <span class="invalid-feedback d-block text-center" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 1: Informations Personnelles -->
                    <div class="form-section active-section" id="section-personnel">
                        <h3 class="form-section-title">
                            <i class="feather-user me-2"></i> Informations Personnelles
                        </h3>

                        <!-- Type d'adhésion -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label required-field d-block mb-3">Type d'adhésion</label>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <div class="form-check-card">
                                            <input class="form-check-input" type="radio" name="typeAdhesion"
                                                id="typeAdhesion_nouveau" value="nouveau"
                                                {{ old('typeAdhesion') == 'nouveau' ? 'checked' : '' }} required>
                                            <label class="form-check-label w-100 h-100" for="typeAdhesion_nouveau">
                                                <div class="card border border-light h-100">
                                                    <div class="card-body text-center">
                                                        <i class="feather-user-plus fs-2 mb-2 text-muted"></i>
                                                        <h6 class="card-title">Nouveau</h6>
                                                        <p class="card-text small text-muted">Première adhésion</p>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @error('typeAdhesion')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3">
                            <!-- Identité -->
                            <div class="col-md-4">
                                <label for="civilite" class="form-label required-field">Civilité</label>
                                <div class="rbt-modern-select bg-transparent height-45">
                                    <select
                                        class="form-control form-control-custom select-custom @error('civilite') is-invalid @enderror"
                                        name="civilite" id="civilite" required>
                                        <option value="">Sélectionnez...</option>
                                        <option value="M." {{ old('civilite') == 'M.' ? 'selected' : '' }}>M.</option>
                                        <option value="Mme" {{ old('civilite') == 'Mme' ? 'selected' : '' }}>Mme
                                        </option>
                                        <option value="Mlle" {{ old('civilite') == 'Mlle' ? 'selected' : '' }}>Mlle
                                        </option>
                                    </select>
                                </div>
                                @error('civilite')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="nom" class="form-label required-field">Nom</label>
                                <input type="text" id="nom" name="nom" value="{{ old('nom') }}"
                                    class="form-control form-control-custom @error('nom') is-invalid @enderror" required
                                    placeholder="Votre nom">
                                @error('nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="prenom" class="form-label required-field">Prénom</label>
                                <input type="text" id="prenom" name="prenom" value="{{ old('prenom') }}"
                                    class="form-control form-control-custom @error('prenom') is-invalid @enderror" required
                                    placeholder="Votre prénom">
                                @error('prenom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Contacts -->
                            <div class="col-md-4">
                                <label for="contact" class="form-label required-field">Contact Principal</label>
                                <div class="input-group">
                                    <span class="input-group-text">+225</span>
                                    <input type="tel" id="contact" name="contact" value="{{ old('contact') }}"
                                        class="form-control form-control-custom @error('contact') is-invalid @enderror"
                                        required pattern="[0-9]{10}" maxlength="10" placeholder="0700000000">
                                </div>
                                <small class="text-muted">Ce numéro servira d'identifiant</small>
                                @error('contact')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="contact_2" class="form-label">Contact Secondaire</label>
                                <div class="input-group">
                                    <span class="input-group-text">+225</span>
                                    <input type="tel" id="contact_2" name="contact_2"
                                        value="{{ old('contact_2') }}"
                                        class="form-control form-control-custom @error('contact_2') is-invalid @enderror"
                                        pattern="[0-9]{10}" maxlength="10" placeholder="0700000000">
                                </div>
                                @error('contact_2')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="fax" class="form-label">Fax</label>
                                <input type="text" id="fax" name="fax" value="{{ old('fax') }}"
                                    class="form-control form-control-custom @error('fax') is-invalid @enderror"
                                    placeholder="Numéro de fax">
                                @error('fax')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label for="email" class="form-label required-field">Email</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}"
                                    class="form-control form-control-custom @error('email') is-invalid @enderror" required
                                    placeholder="votre@email.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Naissance -->
                            <div class="col-md-3">
                                <label for="date_naissance" class="form-label required-field">Date de Naissance</label>
                                <input type="date" id="date_naissance" name="date_naissance"
                                    value="{{ old('date_naissance') }}"
                                    class="form-control form-control-custom @error('date_naissance') is-invalid @enderror"
                                    required max="{{ date('Y-m-d') }}">
                                @error('date_naissance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label for="lieu_naissance" class="form-label required-field">Lieu de Naissance</label>
                                <input type="text" id="lieu_naissance" name="lieu_naissance"
                                    value="{{ old('lieu_naissance') }}"
                                    class="form-control form-control-custom @error('lieu_naissance') is-invalid @enderror"
                                    required placeholder="Ville, Pays">
                                @error('lieu_naissance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Nationalité et situation familiale -->
                            <div class="col-md-4">
                                <label for="nationalite" class="form-label required-field">Nationalité</label>
                                <input type="text" id="nationalite" name="nationalite"
                                    value="{{ old('nationalite') }}"
                                    class="form-control form-control-custom @error('nationalite') is-invalid @enderror"
                                    required placeholder="Votre nationalité">
                                @error('nationalite')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="situation_matrimoniale" class="form-label required-field">Situation
                                    Matrimoniale</label>
                                <div class="rbt-modern-select bg-transparent height-45">
                                    <select
                                        class="form-control form-control-custom select-custom @error('situation_matrimoniale') is-invalid @enderror"
                                        name="situation_matrimoniale" id="situation_matrimoniale" required>
                                        <option value="">Sélectionnez...</option>
                                        <option value="Célibataire"
                                            {{ old('situation_matrimoniale') == 'Célibataire' ? 'selected' : '' }}>
                                            Célibataire
                                        </option>
                                        <option value="Marié(e)"
                                            {{ old('situation_matrimoniale') == 'Marié(e)' ? 'selected' : '' }}>Marié(e)
                                        </option>
                                        <option value="Divorcé(e)"
                                            {{ old('situation_matrimoniale') == 'Divorcé(e)' ? 'selected' : '' }}>
                                            Divorcé(e)
                                        </option>
                                        <option value="Veuf/Veuve"
                                            {{ old('situation_matrimoniale') == 'Veuf/Veuve' ? 'selected' : '' }}>
                                            Veuf/Veuve
                                        </option>
                                    </select>
                                </div>
                                @error('situation_matrimoniale')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="nombre_charge" class="form-label">Nombre de personnes à charge</label>
                                <input type="number" id="nombre_charge" name="nombre_charge"
                                    value="{{ old('nombre_charge', 0) }}"
                                    class="form-control form-control-custom @error('nombre_charge') is-invalid @enderror"
                                    min="0" placeholder="0">
                                @error('nombre_charge')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="adresse" class="form-label required-field">Adresse personnelle</label>
                                <input type="text" id="adresse" name="adresse" value="{{ old('adresse') }}"
                                    class="form-control form-control-custom @error('adresse') is-invalid @enderror"
                                    required placeholder="Votre adresse personnelle">
                                @error('adresse')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Date adhésion UNAMEPCI -->
                            <div class="col-md-6">
                                <label for="date_adhesion_unamepci" class="form-label">Date d'adhésion UNAMEPCI</label>
                                <input type="date" id="date_adhesion_unamepci" name="date_adhesion_unamepci"
                                    value="{{ old('date_adhesion_unamepci') }}"
                                    class="form-control form-control-custom @error('date_adhesion_unamepci') is-invalid @enderror"
                                    max="{{ date('Y-m-d') }}">
                                @error('date_adhesion_unamepci')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Documents d'Identification -->
                    <div class="form-section conditional-hidden" id="section-documents-identite">
                        <h3 class="form-section-title">
                            <i class="feather-file-text me-2"></i> Documents d'Identification
                        </h3>

                        <div class="row g-3">
                            <!-- Type de pièce -->
                            <div class="col-md-6">
                                <label for="type_piece_id" class="form-label required-field">Type de pièce
                                    d'identité</label>
                                <div class="rbt-modern-select bg-transparent height-45">
                                    <select
                                        class="form-control form-control-custom select-custom @error('type_piece_id') is-invalid @enderror"
                                        name="type_piece_id" id="type_piece_id" required>
                                        <option value="">Sélectionnez...</option>
                                        @foreach ($typePieces as $typePiece)
                                            <option value="{{ $typePiece->id }}"
                                                {{ old('type_piece_id') == $typePiece->id ? 'selected' : '' }}>
                                                {{ $typePiece->libelle }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('type_piece_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="numero_piece" class="form-label required-field">Numéro de la pièce</label>
                                <input type="text" id="numero_piece" name="numero_piece"
                                    value="{{ old('numero_piece') }}"
                                    class="form-control form-control-custom @error('numero_piece') is-invalid @enderror"
                                    required placeholder="Numéro de la pièce">
                                @error('numero_piece')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Dates et lieu -->
                            <div class="col-md-4">
                                <label for="date_etablissement_piece" class="form-label required-field">Date
                                    d'établissement</label>
                                <input type="date" id="date_etablissement_piece" name="date_etablissement_piece"
                                    value="{{ old('date_etablissement_piece') }}"
                                    class="form-control form-control-custom @error('date_etablissement_piece') is-invalid @enderror"
                                    required max="{{ date('Y-m-d') }}">
                                @error('date_etablissement_piece')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-8">
                                <label for="lieu_etablissement_piece" class="form-label required-field">Lieu
                                    d'établissement</label>
                                <input type="text" id="lieu_etablissement_piece" name="lieu_etablissement_piece"
                                    value="{{ old('lieu_etablissement_piece') }}"
                                    class="form-control form-control-custom @error('lieu_etablissement_piece') is-invalid @enderror"
                                    required placeholder="Lieu où la pièce a été établie">
                                @error('lieu_etablissement_piece')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- ONMCI -->
                            <div class="col-md-6">
                                <label for="numero_inscription_ONMCI" class="form-label">N° d'inscription ONMCI</label>
                                <input type="text" id="numero_inscription_ONMCI" name="numero_inscription_ONMCI"
                                    value="{{ old('numero_inscription_ONMCI') }}"
                                    class="form-control form-control-custom @error('numero_inscription_ONMCI') is-invalid @enderror"
                                    placeholder="Numéro d'inscription ONMCI">
                                @error('numero_inscription_ONMCI')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="pseudonyme_recon_ONMCI" class="form-label">Pseudonyme de reconnaissance
                                    ONMCI</label>
                                <input type="text" id="pseudonyme_recon_ONMCI" name="pseudonyme_recon_ONMCI"
                                    value="{{ old('pseudonyme_recon_ONMCI') }}"
                                    class="form-control form-control-custom @error('pseudonyme_recon_ONMCI') is-invalid @enderror"
                                    placeholder="Pseudonyme ONMCI">
                                @error('pseudonyme_recon_ONMCI')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Upload des pièces -->
                            <div class="col-md-6">
                                <label class="form-label required-field">Recto de la pièce</label>
                                <div class="document-upload" id="rectoUpload">
                                    <input type="file" name="pieces_joints_recto" id="pieces_joints_recto"
                                        class="@error('pieces_joints_recto') is-invalid @enderror" required>
                                    <div class="upload-content">
                                        <i class="feather-upload fs-4 mb-2"></i>
                                        <p class="mb-1 upload-text">Cliquez pour télécharger le recto</p>
                                        <small class="text-muted">Format: JPEG, PNG, PDF (max 2MB)</small>
                                    </div>
                                </div>
                                @error('pieces_joints_recto')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label required-field">Verso de la pièce</label>
                                <div class="document-upload" id="versoUpload">
                                    <input type="file" name="pieces_joints_verso" id="pieces_joints_verso"
                                        class="@error('pieces_joints_verso') is-invalid @enderror" required>
                                    <div class="upload-content">
                                        <i class="feather-upload fs-4 mb-2"></i>
                                        <p class="mb-1 upload-text">Cliquez pour télécharger le verso</p>
                                        <small class="text-muted">Format: JPEG, PNG, PDF (max 2MB)</small>
                                    </div>
                                </div>
                                @error('pieces_joints_verso')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Informations Professionnelles -->
                    <div class="form-section conditional-hidden" id="section-professionnel">
                        <h3 class="form-section-title">
                            <i class="feather-briefcase me-2"></i> Informations Professionnelles Principales
                        </h3>

                        <div class="row g-3">
                            <!-- Matricule et raison sociale -->
                            <div class="col-md-6">
                                <label for="matricule" class="form-label required-field">Matricule</label>
                                <input type="text" id="matricule" name="matricule" value="{{ old('matricule') }}"
                                    class="form-control form-control-custom @error('matricule') is-invalid @enderror"
                                    required placeholder="Votre matricule">
                                @error('matricule')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="raison_social_primaire" class="form-label required-field">Raison sociale
                                    principale</label>
                                <input type="text" id="raison_social_primaire" name="raison_social_primaire"
                                    value="{{ old('raison_social_primaire') }}"
                                    class="form-control form-control-custom @error('raison_social_primaire') is-invalid @enderror"
                                    placeholder="Raison sociale" required>
                                @error('raison_social_primaire')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Spécialité et fonction -->
                            <div class="col-md-6">
                                <label for="specialite_id" class="form-label required-field">Spécialité</label>
                                <div class="rbt-modern-select bg-transparent height-45">
                                    <select
                                        class="form-control form-control-custom select-custom @error('specialite_id') is-invalid @enderror"
                                        name="specialite_id" id="specialite_id" required>
                                        <option value="">Sélectionnez...</option>
                                        @foreach ($specialites as $specialite)
                                            <option value="{{ $specialite->id }}"
                                                {{ old('specialite_id') == $specialite->id ? 'selected' : '' }}>
                                                {{ $specialite->libelle }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('specialite_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="fonction" class="form-label">Fonction occupée</label>
                                <input type="text" id="fonction" name="fonction" value="{{ old('fonction') }}"
                                    class="form-control form-control-custom @error('fonction') is-invalid @enderror"
                                    placeholder="Votre fonction">
                                @error('fonction')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Expérience -->
                            <div class="col-md-6">
                                <label for="date_debut_metier" class="form-label">Date de début dans le métier</label>
                                <input type="date" id="date_debut_metier" name="date_debut_metier"
                                    value="{{ old('date_debut_metier') }}"
                                    class="form-control form-control-custom @error('date_debut_metier') is-invalid @enderror"
                                    max="{{ date('Y-m-d') }}">
                                @error('date_debut_metier')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="nombre_annee_experience" class="form-label required-field ">Nombre d'années
                                    d'expérience</label>
                                <input type="number" id="nombre_annee_experience" name="nombre_annee_experience"
                                    value="{{ old('nombre_annee_experience') }}"
                                    class="form-control form-control-custom @error('nombre_annee_experience') is-invalid @enderror"
                                    min="-1" placeholder="0" required>
                                @error('nombre_annee_experience')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="statut_emploi" class="form-label required-field">Statut d'emploi</label>
                                <div class="rbt-modern-select bg-transparent height-45">
                                    <select
                                        class="form-control form-control-custom select-custom @error('statut_emploi') is-invalid @enderror"
                                        name="statut_emploi" id="statut_emploi" required>
                                        <option value="">Sélectionnez...</option>
                                        <option value="Stage" {{ old('statut_emploi') == 'Stage' ? 'selected' : '' }}>
                                            Stage</option>
                                        <option value="CDD" {{ old('statut_emploi') == 'CDD' ? 'selected' : '' }}>
                                            CDD</option>
                                        <option value="CDI" {{ old('statut_emploi') == 'CDI' ? 'selected' : '' }}>
                                            CDI</option>
                                        <option value="Retraité"
                                            {{ old('statut_emploi') == 'Retraité' ? 'selected' : '' }}>
                                            Retraité</option>
                                    </select>
                                </div>
                                @error('statut_emploi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Domaine d'activité et employeur -->
                            <div class="col-md-6">
                                <label for="domaine_activite" class="form-label">Domaine d'activité</label>
                                <input type="text" id="domaine_activite" name="domaine_activite"
                                    value="{{ old('domaine_activite') }}"
                                    class="form-control form-control-custom @error('domaine_activite') is-invalid @enderror"
                                    placeholder="Domaine d'activité principal">
                                @error('domaine_activite')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="nom_employeur_principale" class="form-label">Nom de l'employeur
                                    principal</label>
                                <input type="text" id="nom_employeur_principale" name="nom_employeur_principale"
                                    value="{{ old('nom_employeur_principale') }}"
                                    class="form-control form-control-custom @error('nom_employeur_principale') is-invalid @enderror"
                                    placeholder="Nom de l'employeur">
                                @error('nom_employeur_principale')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="date_recrutement" class="form-label">Date de recrutement</label>
                                <input type="date" id="date_recrutement" name="date_recrutement"
                                    value="{{ old('date_recrutement') }}"
                                    class="form-control form-control-custom @error('date_recrutement') is-invalid @enderror"
                                    max="{{ date('Y-m-d') }}">
                                @error('date_recrutement')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 4: Informations sur l'Entreprise -->
                    <div class="form-section conditional-hidden" id="section-entreprise">
                        <h3 class="form-section-title">
                            <i class="feather-home me-2"></i> Informations sur l'Entreprise
                        </h3>

                        <div class="row g-3">
                            <!-- Identité entreprise -->
                            <div class="col-md-6">
                                <label for="sigle" class="form-label required-field">Sigle de l'entreprise</label>
                                <input type="text" id="sigle" name="sigle" value="{{ old('sigle') }}"
                                    class="form-control form-control-custom @error('sigle') is-invalid @enderror"
                                    placeholder="Sigle" required>
                                @error('sigle')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="date_creation" class="form-label">Date de création</label>
                                <input type="date" id="date_creation" name="date_creation"
                                    value="{{ old('date_creation') }}"
                                    class="form-control form-control-custom @error('date_creation') is-invalid @enderror"
                                    max="{{ date('Y-m-d') }}">
                                @error('date_creation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Autorisation et immatriculation -->
                            <div class="col-md-6">
                                <label for="numero_autorisation" class="form-label">N° d'autorisation</label>
                                <input type="text" id="numero_autorisation" name="numero_autorisation"
                                    value="{{ old('numero_autorisation') }}"
                                    class="form-control form-control-custom @error('numero_autorisation') is-invalid @enderror"
                                    placeholder="Numéro d'autorisation">
                                @error('numero_autorisation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="num_immatriculation" class="form-label">N° d'immatriculation</label>
                                <input type="text" id="num_immatriculation" name="num_immatriculation"
                                    value="{{ old('num_immatriculation') }}"
                                    class="form-control form-control-custom @error('num_immatriculation') is-invalid @enderror"
                                    placeholder="Numéro d'immatriculation">
                                @error('num_immatriculation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Forme juridique -->
                            <div class="col-md-6">
                                <label for="forme_juridique_id" class="form-label required-field">Forme juridique</label>
                                <div class="rbt-modern-select bg-transparent height-45">
                                    <select
                                        class="form-control form-control-custom select-custom @error('forme_juridique_id') is-invalid @enderror"
                                        name="forme_juridique_id" id="forme_juridique_id" required>
                                        <option value="">Sélectionnez...</option>
                                        @foreach ($formeJuridiques as $forme)
                                            <option value="{{ $forme->id }}"
                                                {{ old('forme_juridique_id') == $forme->id ? 'selected' : '' }}>
                                                {{ $forme->libelle }} ( {{ $forme->description }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('forme_juridique_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 conditional-hidden" id="precise-forme-juridique-container">
                                <label for="precise_forme_juridique" class="form-label">Précision forme juridique</label>
                                <input type="text" id="precise_forme_juridique" name="precise_forme_juridique"
                                    value="{{ old('precise_forme_juridique') }}"
                                    class="form-control form-control-custom @error('precise_forme_juridique') is-invalid @enderror"
                                    placeholder="Précisions supplémentaires">
                                @error('precise_forme_juridique')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Adresse entreprise -->
                            <div class="col-md-4">
                                <label for="ville_id" class="form-label required-field">Ville</label>
                                <div class="rbt-modern-select bg-transparent height-45">
                                    <select
                                        class="form-control form-control-custom select-custom @error('ville_id') is-invalid @enderror"
                                        name="ville_id" id="ville_id" required>
                                        <option value="">Sélectionnez...</option>
                                        @foreach ($villes as $ville)
                                            <option value="{{ $ville->id }}"
                                                {{ old('ville_id') == $ville->id ? 'selected' : '' }}>
                                                {{ $ville->libelle }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('ville_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="commune" class="form-label">Commune</label>
                                <input type="text" id="commune" name="commune" value="{{ old('commune') }}"
                                    class="form-control form-control-custom @error('commune') is-invalid @enderror"
                                    placeholder="Commune">
                                @error('commune')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="quartier" class="form-label">Quartier</label>
                                <input type="text" id="quartier" name="quartier" value="{{ old('quartier') }}"
                                    class="form-control form-control-custom @error('quartier') is-invalid @enderror"
                                    placeholder="Quartier">
                                @error('quartier')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Suite adresse -->
                            <div class="col-md-6">
                                <label for="rue" class="form-label">Rue</label>
                                <input type="text" id="rue" name="rue" value="{{ old('rue') }}"
                                    class="form-control form-control-custom @error('rue') is-invalid @enderror"
                                    placeholder="Nom de la rue">
                                @error('rue')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="adresse_postale_entreprise" class="form-label">Adresse postale
                                    entreprise</label>
                                <input type="text" id="adresse_postale_entreprise" name="adresse_postale_entreprise"
                                    value="{{ old('adresse_postale_entreprise') }}"
                                    class="form-control form-control-custom @error('adresse_postale_entreprise') is-invalid @enderror"
                                    placeholder="Adresse postale">
                                @error('adresse_postale_entreprise')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Contacts entreprise -->
                            <div class="col-md-6">
                                <label for="email_entreprise" class="form-label">Email entreprise</label>
                                <input type="email" id="email_entreprise" name="email_entreprise"
                                    value="{{ old('email_entreprise') }}"
                                    class="form-control form-control-custom @error('email_entreprise') is-invalid @enderror"
                                    placeholder="entreprise@email.com">
                                @error('email_entreprise')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="telephone_entreprise" class="form-label">Téléphone entreprise</label>
                                <div class="input-group">
                                    <span class="input-group-text">+225</span>
                                    <input type="tel" id="telephone_entreprise" name="telephone_entreprise"
                                        value="{{ old('telephone_entreprise') }}"
                                        class="form-control form-control-custom @error('telephone_entreprise') is-invalid @enderror"
                                        pattern="[0-9]{10}" maxlength="10" placeholder="0700000000">
                                </div>
                                @error('telephone_entreprise')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="fax_entreprise" class="form-label">Fax entreprise</label>
                                <input type="text" id="fax_entreprise" name="fax_entreprise"
                                    value="{{ old('fax_entreprise') }}"
                                    class="form-control form-control-custom @error('fax_entreprise') is-invalid @enderror"
                                    placeholder="Fax entreprise">
                                @error('fax_entreprise')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="localisation_entreprise" class="form-label required-field">Localisation
                                    entreprise</label>
                                <input type="text" id="localisation_entreprise" name="localisation_entreprise"
                                    value="{{ old('localisation_entreprise') }}"
                                    class="form-control form-control-custom @error('localisation_entreprise') is-invalid @enderror"
                                    required placeholder="Localisation de l'entreprise">
                                @error('localisation_entreprise')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 5: Informations Freelance -->
                    <div class="form-section conditional-hidden" id="section-freelance">
                        <h3 class="form-section-title">
                            <i class="feather-users me-2"></i> Informations Freelance/Activité Secondaire
                        </h3>

                        <div class="row g-3">
                            <!-- Raison sociale freelance -->
                            <div class="col-md-6">
                                <label for="raison_social_secondaire_freelance" class="form-label">Raison sociale
                                    freelance</label>
                                <input type="text" id="raison_social_secondaire_freelance"
                                    name="raison_social_secondaire_freelance"
                                    value="{{ old('raison_social_secondaire_freelance') }}"
                                    class="form-control form-control-custom @error('raison_social_secondaire_freelance') is-invalid @enderror"
                                    placeholder="Raison sociale freelance">
                                @error('raison_social_secondaire_freelance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Fonction freelance -->
                            <div class="col-md-6">
                                <label for="fonction_occupe_freelance" class="form-label">Fonction occupée
                                    (freelance)</label>
                                <input type="text" id="fonction_occupe_freelance" name="fonction_occupe_freelance"
                                    value="{{ old('fonction_occupe_freelance') }}"
                                    class="form-control form-control-custom @error('fonction_occupe_freelance') is-invalid @enderror"
                                    placeholder="Fonction freelance">
                                @error('fonction_occupe_freelance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Type de contrat freelance -->
                            <div class="col-md-6">
                                <label for="type_contrat_freelance" class="form-label">Type de contrat (freelance)</label>
                                <div class="rbt-modern-select bg-transparent height-45">
                                    <select
                                        class="form-control form-control-custom select-custom @error('type_contrat_freelance') is-invalid @enderror"
                                        name="type_contrat_freelance" id="type_contrat_freelance">
                                        <option value="">Sélectionnez...</option>
                                        <option value="CDI"
                                            {{ old('type_contrat_freelance') == 'CDI' ? 'selected' : '' }}>CDI</option>
                                        <option value="CDD"
                                            {{ old('type_contrat_freelance') == 'CDD' ? 'selected' : '' }}>CDD</option>
                                        <option value="Prestation"
                                            {{ old('type_contrat_freelance') == 'Prestation' ? 'selected' : '' }}>
                                            Prestation
                                        </option>
                                        <option value="Consultant"
                                            {{ old('type_contrat_freelance') == 'Consultant' ? 'selected' : '' }}>
                                            Consultant
                                        </option>
                                        <option value="Autre"
                                            {{ old('type_contrat_freelance') == 'Autre' ? 'selected' : '' }}>Autre
                                        </option>
                                    </select>
                                </div>
                                @error('type_contrat_freelance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Contacts freelance -->
                            <div class="col-md-6">
                                <label for="telephone_freelance" class="form-label">Téléphone freelance</label>
                                <div class="input-group">
                                    <span class="input-group-text">+225</span>
                                    <input type="tel" id="telephone_freelance" name="telephone_freelance"
                                        value="{{ old('telephone_freelance') }}"
                                        class="form-control form-control-custom @error('telephone_freelance') is-invalid @enderror"
                                        pattern="[0-9]{10}" maxlength="10" placeholder="0700000000">
                                </div>
                                @error('telephone_freelance')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Localisation freelance -->
                            <div class="col-md-6">
                                <label for="localisation_freelance" class="form-label">Localisation freelance</label>
                                <input type="text" id="localisation_freelance" name="localisation_freelance"
                                    value="{{ old('localisation_freelance') }}"
                                    class="form-control form-control-custom @error('localisation_freelance') is-invalid @enderror"
                                    placeholder="Localisation">
                                @error('localisation_freelance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="adresse_postale_freelance" class="form-label">Adresse postale
                                    freelance</label>
                                <input type="text" id="adresse_postale_freelance" name="adresse_postale_freelance"
                                    value="{{ old('adresse_postale_freelance') }}"
                                    class="form-control form-control-custom @error('adresse_postale_freelance') is-invalid @enderror"
                                    placeholder="Adresse postale freelance">
                                @error('adresse_postale_freelance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Domaine activité freelance -->
                            <div class="col-md-6">
                                <label for="domaine_activite_freelance" class="form-label">Domaine d'activité
                                    (freelance)</label>
                                <input type="text" id="domaine_activite_freelance" name="domaine_activite_freelance"
                                    value="{{ old('domaine_activite_freelance') }}"
                                    class="form-control form-control-custom @error('domaine_activite_freelance') is-invalid @enderror"
                                    placeholder="Domaine d'activité freelance">
                                @error('domaine_activite_freelance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="fax_freelance" class="form-label">Fax freelance</label>
                                <input type="text" id="fax_freelance" name="fax_freelance"
                                    value="{{ old('fax_freelance') }}"
                                    class="form-control form-control-custom @error('fax_freelance') is-invalid @enderror"
                                    placeholder="Fax freelance">
                                @error('fax_freelance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 6: Relations et Information sur l'Auteur -->
                    <div class="form-section conditional-hidden" id="section-relations">
                        <h3 class="form-section-title">
                            <i class="feather-link me-2"></i> Relations et Information sur l'Auteur
                        </h3>

                        <!-- Question 1: Relation avec un tiers -->
                        <div class="mb-4">
                            <label class="form-label d-block">Avez-vous une relation avec un tiers ?</label>
                            <div class="oui-non-toggle">
                                <label class="toggle-option" id="toggleRelationNon">
                                    <input type="radio" name="relation_tiers" value="0"
                                        {{ old('relation_tiers', 0) == 0 ? 'checked' : '' }}>
                                    Non
                                </label>
                                <label class="toggle-option" id="toggleRelationOui">
                                    <input type="radio" name="relation_tiers" value="1"
                                        {{ old('relation_tiers') == 1 ? 'checked' : '' }}>
                                    Oui
                                </label>
                            </div>

                            <div class="conditional-field mt-3 conditional-hidden" id="relationField">
                                <label for="nom_relation" class="form-label">Nom du tiers</label>
                                <input type="text" class="form-control form-control-custom" name="nom_relation"
                                    id="nom_relation" value="{{ old('nom_relation') }}"
                                    placeholder="Nom du tiers avec qui vous avez une relation">
                            </div>
                        </div>

                        <!-- Question 2: L'auteur de l'adhésion -->
                        <div id="questionAuteurSection">
                            <label class="form-label d-block">Qui est l'auteur de votre adhésion ?</label>

                            <div class="conditional-field p-3 mb-3 conditional-hidden" id="auteurTiersSection">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="etre_auteur"
                                        id="auteur_tiers_oui" value="1"
                                        {{ old('etre_auteur') == 1 && old('relation_tiers') == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="auteur_tiers_oui">
                                        C'est le tiers mentionné ci-dessus
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="etre_auteur"
                                        id="auteur_tiers_non" value="0"
                                        {{ old('etre_auteur') == 0 && old('relation_tiers') == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="auteur_tiers_non">
                                        C'est une autre personne
                                    </label>
                                </div>
                            </div>

                            <!-- Champ pour le nom de l'auteur (si différent du tiers ou si pas de tiers) -->
                            <div class="conditional-field mt-3" id="nomAuteurContainer">
                                <label for="nom_auteur" class="form-label ">Nom de l'auteur</label>
                                <input type="text" class="form-control form-control-custom" name="nom_auteur"
                                    id="nom_auteur" value="{{ old('nom_auteur') }}"
                                    placeholder="Nom de la personne qui a effectué votre adhésion">
                            </div>
                        </div>
                    </div>

                    <!-- Section 7: Documents à télécharger -->
                    <div class="form-section conditional-hidden" id="section-documents">
                        <h3 class="form-section-title">
                            <i class="feather-file me-2"></i> Documents à télécharger
                        </h3>

                        <div class="row g-3">
                            <!-- Photo de couverture -->
                            <div class="col-md-6">
                                <label for="photo_couverture" class="form-label">Photo de couverture</label>
                                <div class="document-upload" id="couvertureUpload">
                                    <input type="file" name="photo_couverture" id="photo_couverture"
                                        class="@error('photo_couverture') is-invalid @enderror" accept=".jpeg ,.png">
                                    <div class="upload-content">
                                        <i class="feather-upload fs-4 mb-2"></i>
                                        <p class="mb-1 upload-text">Cliquez pour télécharger</p>
                                        <small class="text-muted">Format: JPEG, PNG (max 2MB)</small>
                                    </div>
                                </div>
                                @error('photo_couverture')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Carte inscription ONMCI -->
                            <div class="col-md-6">
                                <label for="document_carte_inscript_ONMCI" class="form-label ">Carte
                                    d'inscription
                                    ONMCI</label>
                                <div class="document-upload" id="carteOnmciUpload">
                                    <input type="file" name="document_carte_inscript_ONMCI"
                                        id="document_carte_inscript_ONMCI"
                                        class="@error('document_carte_inscript_ONMCI') is-invalid @enderror"
                                        accept="application/pdf,.pdf" >
                                    <div class="upload-content">
                                        <i class="feather-upload fs-4 mb-2"></i>
                                        <p class="mb-1 upload-text">Cliquez pour télécharger</p>
                                        <small class="text-muted">Format: PDF (max 2MB)</small>
                                    </div>
                                </div>
                                @error('document_carte_inscript_ONMCI')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Autorisation d'ouverture -->
                            <div class="col-md-6">
                                <label for="document_autorisation_ouverture"
                                    class="form-label required-field">Autorisation
                                    d'ouverture</label>
                                <div class="document-upload" id="autorisationUpload">
                                    <input type="file" name="document_autorisation_ouverture"
                                        id="document_autorisation_ouverture"
                                        class="@error('document_autorisation_ouverture') is-invalid @enderror"
                                        accept="application/pdf,.pdf" required>
                                    <div class="upload-content">
                                        <i class="feather-upload fs-4 mb-2"></i>
                                        <p class="mb-1 upload-text">Cliquez pour télécharger</p>
                                        <small class="text-muted">Format: PDF (max 2MB)</small>
                                    </div>
                                </div>
                                @error('document_autorisation_ouverture')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Photos d'identité supplémentaires -->
                            <div class="col-md-6">
                                <label for="photo_identite_1" class="form-label required-field">Deux (2) Photos d'identité
                                    meme tirage</label>
                                <div class="document-upload" id="photoIdentite1Upload">
                                    <input type="file" name="photo_identite_1" id="photo_identite_1"
                                        class="@error('photo_identite_1') is-invalid @enderror"
                                        accept="application/pdf,.pdf" required>
                                    <div class="upload-content">
                                        <i class="feather-upload fs-4 mb-2"></i>
                                        <p class="mb-1 upload-text">Cliquez pour télécharger</p>
                                        <small class="text-muted">Format: PDF (max 2MB)</small>
                                    </div>
                                </div>
                                @error('photo_identite_1')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <br>
                        <h3 class="form-section-title">
                            <i class="feather-file me-2 mt-2"></i>Signature
                        </h3>

                        <div class="row g-3">
                            <div class="col-12 col-lg-12 col-md-12 col-sm-12 text-center">
                                <h6 for="signature" class="fw-bold">Votre signature <span class="text-danger">*</span>
                                </h6>
                                <canvas id="signature-pad" width="300" height="300"
                                    class="@error('signature') is-invalid @enderror"></canvas>
                                <br>
                                @error('signature')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <br>
                                <div class="row">
                                    <div class="col-12 col-lg-12 col-md-12 col-sm-12">
                                        <button id="save-btn" class="btn btn-primary">Enregistrer la
                                            signature</button>
                                        <button id="clear-btn" class="btn btn-danger">Effacer</button>
                                        <input type="file" name="signature" id="signature" style="display: none"
                                            value="{{ old('signature') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Boutons de navigation -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <button type="button" class="rbt-btn btn-secondary" id="prevSection" style="display: none;">
                                    <i class="feather-arrow-left me-2"></i>Précédent
                                </button>

                                <button type="button" class="rbt-btn btn-primary" id="nextSection">
                                    Suivant<i class="feather-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Message d'information -->
                    <div class="row mt-3">
                        <div class="col-12 text-center">
                            <p class="text-muted"><span class="text-danger">*</span> Champs obligatoires</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            /* =========================
               NAVIGATION MULTI-SECTIONS
            ========================== */
            const sections = [
                'section-personnel',
                'section-documents-identite',
                'section-professionnel',
                'section-entreprise',
                'section-freelance',
                'section-relations',
                'section-documents'
            ];

            let currentSection = 0;

            function showSection(index) {
                $('.form-section').addClass('conditional-hidden');
                $('#' + sections[index]).removeClass('conditional-hidden');

                $('html, body').animate({
                    scrollTop: $('#' + sections[index]).offset().top - 100
                }, 400);

                // Mettre à jour l'état des boutons
                updateButtons();
            }

            function updateNavigation() {
                $('.form-nav-item').removeClass('active');
                $('.form-nav-item[data-section="' + sections[currentSection] + '"]').addClass('active');
            }

            function updateButtons() {
                // Gérer le bouton Précédent
                if (currentSection === 0) {
                    $('#prevSection').hide();
                } else {
                    $('#prevSection').show();
                }

                // Gérer le bouton Suivant / Soumettre
                if (currentSection === sections.length - 1) {
                    // Dernière section : remplacer par bouton de soumission
                    $('#nextSection').html('Soumettre le formulaire <i class="feather-check-circle ms-2"></i>');
                    $('#nextSection').removeClass('btn-primary').addClass('btn-success');
                    $('#nextSection').off('click').on('click', function() {
                        // Vérifier la signature avant soumission
                        const signatureFile = document.getElementById('signature');
                        const signatureCanvas = document.getElementById('signature-pad');
                        const isCanvasBlank = !signatureCanvas || signatureCanvas.toDataURL() === document.createElement('canvas').toDataURL();

                        if (!signatureFile.files.length && isCanvasBlank) {
                            Swal.fire({
                                title: 'Erreur!',
                                text: 'Veuillez enregistrer votre signature avant de soumettre.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                            return;
                        }

                        // Soumettre le formulaire
                        $('form').submit();
                    });
                } else {
                    // Pas la dernière section : bouton suivant normal
                    $('#nextSection').html('Suivant <i class="feather-arrow-right ms-2"></i>');
                    $('#nextSection').removeClass('btn-success').addClass('btn-primary');
                    $('#nextSection').off('click').on('click', function() {
                        // Validation optionnelle de la section courante
                        if (validateCurrentSection()) {
                            if (currentSection < sections.length - 1) {
                                currentSection++;
                                showSection(currentSection);
                                updateNavigation();
                            }
                        }
                    });
                }
            }

            // Fonction de validation de la section courante
            function validateCurrentSection() {
                const currentSectionElement = $('#' + sections[currentSection]);
                const requiredFields = currentSectionElement.find('[required]');
                let isValid = true;

                requiredFields.each(function() {
                    if (!$(this).val() && $(this).attr('type') !== 'file') {
                        isValid = false;
                        $(this).addClass('is-invalid');
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });

                // Validation spécifique pour les fichiers requis
                const requiredFiles = currentSectionElement.find('input[type="file"][required]');
                requiredFiles.each(function() {
                    if (!this.files || this.files.length === 0) {
                        isValid = false;
                        $(this).addClass('is-invalid');
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });

                if (!isValid) {
                    Swal.fire({
                        title: 'Champs incomplets',
                        text: 'Veuillez remplir tous les champs obligatoires avant de continuer.',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                }

                return isValid;
            }

            // Initialisation
            showSection(currentSection);
            updateNavigation();

            // Navigation par les onglets
            $('.form-nav-item').on('click', function() {
                const targetIndex = sections.indexOf($(this).data('section'));
                if (targetIndex !== -1) {
                    // Si on essaie d'aller plus loin, valider d'abord
                    if (targetIndex > currentSection) {
                        if (validateCurrentSection()) {
                            currentSection = targetIndex;
                            showSection(currentSection);
                            updateNavigation();
                        }
                    } else {
                        currentSection = targetIndex;
                        showSection(currentSection);
                        updateNavigation();
                    }
                }
            });

            // Bouton précédent
            $('#prevSection').on('click', function() {
                if (currentSection > 0) {
                    currentSection--;
                    showSection(currentSection);
                    updateNavigation();
                }
            });

            /* =========================
               UPLOAD DOCUMENTS
            ========================== */
            // Gestion de l'avatar
            $('#avatar').on('change', function(e) {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#profile-image-preview').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(file);
                }
            });

            // Gestion des uploads de documents
            $('.document-upload').on('click', function() {
                $(this).find('input[type="file"]').trigger('click');
            });

            $('.document-upload input[type="file"]').on('change', function() {
                const file = this.files[0];
                const container = $(this).closest('.document-upload');
                const text = container.find('.upload-text');

                if (file) {
                    text.text(file.name);
                    container.css('border-color', '#4a6cf7');
                    $(this).removeClass('is-invalid');
                }
            });

            /* =========================
               AUTRES FONCTIONNALITÉS
            ========================== */
            // Gestion des toggle Oui/Non
            $('.toggle-option').click(function() {
                const parent = $(this).closest('.oui-non-toggle');
                parent.find('.toggle-option').removeClass('active');
                $(this).addClass('active');
                $(this).find('input[type="radio"]').prop('checked', true);

                // Logique spécifique pour les relations
                if ($(this).find('input[name="relation_tiers"]').length) {
                    handleRelationToggle();
                }
            });

            function handleRelationToggle() {
                const hasRelation = $('input[name="relation_tiers"]:checked').val() == '1';

                if (hasRelation) {
                    $('#relationField').removeClass('conditional-hidden');
                    $('#auteurTiersSection').removeClass('conditional-hidden');
                    $('#nomAuteurContainer').addClass('conditional-hidden');
                } else {
                    $('#relationField').addClass('conditional-hidden');
                    $('#auteurTiersSection').addClass('conditional-hidden');
                    $('#nomAuteurContainer').removeClass('conditional-hidden');
                }
            }

            // Initialiser les toggles
            $('input[type="radio"]:checked').each(function() {
                $(this).closest('.toggle-option').addClass('active');
            });
            handleRelationToggle();

            // Gestion de l'auteur
            $('input[name="etre_auteur"]').change(function() {
                const tiersIsAuthor = $(this).val() == '1';
                if (tiersIsAuthor) {
                    $('#nomAuteurContainer').addClass('conditional-hidden');
                } else {
                    $('#nomAuteurContainer').removeClass('conditional-hidden');
                }
            });

            // Gestion de la forme juridique
            $('#forme_juridique_id').change(function() {
                if ($(this).val() == '16') {
                    $('#precise-forme-juridique-container').removeClass('conditional-hidden');
                } else {
                    $('#precise-forme-juridique-container').addClass('conditional-hidden');
                }
            });

            // Cartes de type d'adhésion
            $('.form-check-card input[type="radio"]').change(function() {
                $('.form-check-card').each(function() {
                    const card = $(this);
                    if (card.find('input:checked').length > 0) {
                        card.find('.card').addClass('border-primary bg-light');
                        card.find('.card i').addClass('text-primary').removeClass('text-muted');
                        card.find('.card .card-title').addClass('text-primary');
                    } else {
                        card.find('.card').removeClass('border-primary bg-light');
                        card.find('.card i').removeClass('text-primary').addClass('text-muted');
                        card.find('.card .card-title').removeClass('text-primary');
                    }
                });
            });

            // Initialiser les cartes
            $('.form-check-card input[type="radio"]:checked').trigger('change');

            // Labels dynamiques pour le type de pièce
            $('#type_piece_id').change(function() {
                const label = $(this).find('option:selected').text();
                if (label && label !== 'Sélectionnez...') {
                    $('#rectoUpload .upload-text').text('Recto ' + label);
                    $('#versoUpload .upload-text').text('Verso ' + label);
                }
            });

            // Initialiser les labels si déjà sélectionné
            if ($('#type_piece_id').val()) {
                $('#type_piece_id').trigger('change');
            }
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Afficher les erreurs de validation Laravel
            @if ($errors->any())
                setTimeout(function() {
                    // Afficher une alerte avec le nombre d'erreurs
                    Swal.fire({
                        title: 'Erreur de validation',
                        text: 'Veuillez corriger les {{ $errors->count() }} erreur(s) dans le formulaire.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });

                    // Chercher et afficher la première section avec erreur
                    let hasDisplayedSection = false;

                    // Convertir les erreurs en tableau pour JavaScript
                    const errors = @json($errors->toArray());

                    // Parcourir les erreurs
                    Object.keys(errors).forEach(function(key, index) {
                        const field = document.querySelector('[name="' + key + '"]');
                        if (field) {
                            const section = field.closest('.form-section');
                            if (section && section.classList.contains('conditional-hidden')) {
                                section.classList.remove('conditional-hidden');
                                hasDisplayedSection = true;

                                // Mettre à jour la navigation
                                const sectionId = section.id;
                                const navItem = document.querySelector(
                                    '.form-nav-item[data-section="' + sectionId + '"]');
                                if (navItem) {
                                    document.querySelectorAll('.form-nav-item').forEach(item => {
                                        item.classList.remove('active');
                                    });
                                    navItem.classList.add('active');
                                }

                                // Scroll vers le champ seulement pour la première erreur
                                if (index === 0) {
                                    field.scrollIntoView({
                                        behavior: 'smooth',
                                        block: 'center'
                                    });
                                }
                            }
                        }
                    });

                    // Si aucune section n'a été affichée mais qu'il y a des erreurs
                    if (!hasDisplayedSection && Object.keys(errors).length > 0) {
                        const firstSection = document.querySelector('.form-section');
                        if (firstSection) {
                            firstSection.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        }
                    }

                }, 500);
            @endif

            // Afficher les messages de session
            @if (session('success'))
                Swal.fire({
                    title: 'Succès!',
                    text: '{{ session('success') }}',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    title: 'Erreur!',
                    text: '{{ session('error') }}',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            @endif

            @if (session('info'))
                Swal.fire({
                    title: 'Information',
                    text: '{{ session('info') }}',
                    icon: 'info',
                    confirmButtonText: 'OK'
                });
            @endif
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const canvas = document.getElementById('signature-pad');
            const context = canvas.getContext('2d');
            let drawing = false;

            // Configuration du canvas
            context.lineWidth = 2;
            context.lineCap = 'round';
            context.strokeStyle = '#000';

            // Gestionnaires pour les événements de souris
            canvas.addEventListener('mousedown', startDrawing);
            canvas.addEventListener('mousemove', draw);
            canvas.addEventListener('mouseup', stopDrawing);
            canvas.addEventListener('mouseout', stopDrawing);

            // Gestionnaires pour les événements tactiles
            canvas.addEventListener('touchstart', startDrawing);
            canvas.addEventListener('touchmove', draw);
            canvas.addEventListener('touchend', stopDrawing);

            function startDrawing(event) {
                event.preventDefault();
                drawing = true;
                const {
                    offsetX,
                    offsetY
                } = getEventPosition(event);
                context.beginPath();
                context.moveTo(offsetX, offsetY);
            }

            function draw(event) {
                event.preventDefault();
                if (!drawing) return;
                const {
                    offsetX,
                    offsetY
                } = getEventPosition(event);
                context.lineTo(offsetX, offsetY);
                context.stroke();
            }

            function stopDrawing(event) {
                event.preventDefault();
                drawing = false;
            }

            function getEventPosition(event) {
                if (event.touches && event.touches[0]) {
                    const rect = canvas.getBoundingClientRect();
                    return {
                        offsetX: event.touches[0].clientX - rect.left,
                        offsetY: event.touches[0].clientY - rect.top
                    };
                } else {
                    return {
                        offsetX: event.offsetX,
                        offsetY: event.offsetY
                    };
                }
            }

            function isCanvasBlank(canvas) {
                const blank = document.createElement('canvas');
                blank.width = canvas.width;
                blank.height = canvas.height;
                return canvas.toDataURL() === blank.toDataURL();
            }

            document.getElementById('save-btn').addEventListener('click', (event) => {
                event.preventDefault();

                if (isCanvasBlank(canvas)) {
                    Swal.fire({
                        title: 'Erreur!',
                        text: 'Veuillez entrer une signature.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                const dataURL = canvas.toDataURL('image/png');
                const blob = dataURLToBlob(dataURL);
                const file = new File([blob], 'signature.png', {
                    type: 'image/png'
                });
                const fileInput = document.getElementById('signature');
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                fileInput.files = dataTransfer.files;

                Swal.fire({
                    title: 'Succès!',
                    text: 'Signature enregistrée.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            });

            document.getElementById('clear-btn').addEventListener('click', (event) => {
                event.preventDefault();
                context.clearRect(0, 0, canvas.width, canvas.height);
                document.getElementById('signature').value = '';
            });

            function dataURLToBlob(dataURL) {
                const byteString = atob(dataURL.split(',')[1]);
                const mimeString = dataURL.split(',')[0].split(':')[1].split(';')[0];
                const ab = new ArrayBuffer(byteString.length);
                const ia = new Uint8Array(ab);
                for (let i = 0; i < byteString.length; i++) {
                    ia[i] = byteString.charCodeAt(i);
                }
                return new Blob([ab], {
                    type: mimeString
                });
            }
        });
    </script>
@endpush --}}





@extends('layouts.home', ['title' => "Formulaire d'identification"])
@push('css')
    <style>
        /* Styles généraux optimisés */
        .profile-image-container {
            width: 168px;
            height: 168px;
            position: relative;
            margin: 0 auto 30px;
            border-radius: 50%;
            overflow: hidden;
            cursor: pointer;
            border: 3px solid #f8f9fa;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .profile-image-container:hover {
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .profile-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .profile-image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .profile-image-container:hover .profile-image-overlay {
            opacity: 1;
        }

        .file-input {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
            z-index: 10;
        }

        /* Sections du formulaire */
        .form-section {
            background: #fff;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border: 1px solid #eaeaea;
        }

        .form-section-title {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #4a6cf7;
        }

        /* Champs de formulaire */
        .form-control-custom {
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            padding: 12px 15px;
            height: auto;
            transition: all 0.3s;
        }

        .form-control-custom:focus {
            border-color: #4a6cf7;
            box-shadow: 0 0 0 0.2rem rgba(74, 108, 247, 0.25);
        }

        .required-field::after {
            content: " *";
            color: #dc3545;
        }

        /* Upload de documents */
        .document-upload {
            border: 2px dashed #e0e0e0;
            padding: 20px;
            border-radius: 6px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
        }

        .document-upload:hover {
            border-color: #4a6cf7;
            background: rgba(74, 108, 247, 0.05);
        }

        .document-upload input[type="file"] {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        /* Toggle Oui/Non */
        .oui-non-toggle {
            display: flex;
            gap: 20px;
            margin: 15px 0;
        }

        .toggle-option {
            position: relative;
            cursor: pointer;
            padding: 10px 30px;
            border: 2px solid #e0e0e0;
            border-radius: 6px;
            transition: all 0.3s;
            text-align: center;
            flex: 1;
        }

        .toggle-option input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-option.active {
            border-color: #4a6cf7;
            background: rgba(74, 108, 247, 0.1);
            color: #4a6cf7;
            font-weight: 600;
        }

        /* Champs conditionnels */
        .conditional-field {
            margin-top: 15px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 6px;
            border-left: 4px solid #4a6cf7;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Navigation entre sections */
        .form-navigation {
            position: sticky;
            top: 20px;
            z-index: 100;
        }

        .form-nav-item {
            padding: 10px 15px;
            margin-bottom: 5px;
            background: #f8f9fa;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }

        .form-nav-item:hover {
            background: #e9ecef;
        }

        .form-nav-item.active {
            background: #4a6cf7;
            color: white;
            border-left-color: #2541b2;
        }

        /* Style pour les cartes de type d'adhésion */
        .form-check-card {
            position: relative;
        }

        .form-check-card .form-check-input {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .form-check-card .card {
            transition: all 0.3s;
            cursor: pointer;
        }

        .form-check-card .form-check-input:checked+label .card {
            border-color: #4a6cf7 !important;
            background: rgba(74, 108, 247, 0.05);
        }

        .form-check-card .form-check-input:checked+label .card i {
            color: #4a6cf7 !important;
        }

        .form-check-card .form-check-input:checked+label .card .card-title {
            color: #4a6cf7;
            font-weight: 600;
        }

        /* Style pour les select avec flèche */
        .select-custom {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 16px;
            padding-right: 40px;
        }

        /* Ajustement pour les champs conditionnels */
        .conditional-hidden {
            display: none !important;
        }

        /* Style pour le bouton de soumission */
        .submit-btn {
            background: linear-gradient(135deg, #4a6cf7 0%, #2541b2 100%);
            border: none;
            padding: 12px 40px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(74, 108, 247, 0.4);
        }

        /* ========== LOADER OVERLAY STYLES ========== */
        .loader-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(74, 108, 247, 0.95) 0%, rgba(37, 65, 178, 0.95) 100%);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
        }

        .loader-overlay.hidden {
            display: none;
        }

        .loader-content {
            text-align: center;
            color: white;
            animation: fadeInUp 0.5s ease;
        }

        /* Caducée médical (symbole de la médecine) */
        .medical-symbol {
            width: 120px;
            height: 120px;
            margin: 0 auto 30px;
            position: relative;
            animation: pulse 1.5s ease-in-out infinite;
        }

        .medical-symbol svg {
            width: 100%;
            height: 100%;
            filter: drop-shadow(0 0 15px rgba(255, 255, 255, 0.5));
        }

        .loader-text {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 20px;
            letter-spacing: 1px;
        }

        .loader-subtext {
            font-size: 16px;
            opacity: 0.9;
            margin-bottom: 30px;
        }

        .loader-spinner {
            width: 50px;
            height: 50px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top: 3px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }

        .loader-progress {
            width: 300px;
            max-width: 80%;
            height: 4px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 4px;
            margin: 20px auto 0;
            overflow: hidden;
        }

        .loader-progress-bar {
            height: 100%;
            background: white;
            width: 0%;
            border-radius: 4px;
            animation: progress 2s ease-out forwards;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes pulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.9; }
            100% { transform: scale(1); opacity: 1; }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes progress {
            0% { width: 0%; }
            30% { width: 30%; }
            60% { width: 70%; }
            90% { width: 90%; }
            100% { width: 100%; }
        }

        /* Désactivation des champs pendant le chargement */
        .form-loading {
            pointer-events: none;
            opacity: 0.6;
        }

        .btn-loading {
            position: relative;
            pointer-events: none;
            opacity: 0.7;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            right: 15px;
            margin-top: -10px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        /* Responsive */
        @media (max-width: 767px) {
            .form-section {
                padding: 15px;
            }

            .oui-non-toggle {
                flex-direction: column;
                gap: 10px;
            }

            .form-check-card {
                margin-bottom: 15px;
            }

            .loader-text {
                font-size: 22px;
            }

            .medical-symbol {
                width: 80px;
                height: 80px;
            }
        }
    </style>
    <style>
        canvas {
            border: 1px solid #000;
            cursor: crosshair;
        }

        button {
            margin: 5px;
        }
    </style>
@endpush

@section('content')
    <div class="rbt-breadcrumb-default bg-gradient-1">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-inner text-center">
                        <h2 class="title">Formulaire d'Identification</h2>
                        <p class="text-black mb-0">Veuillez remplir ce formulaire complet pour votre identification</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div id="validation-error-summary"
            style="background:#fff3f3;border:1px solid #dc3545;border-radius:8px;padding:16px 20px;margin-bottom:20px;color:#dc3545;">
            <strong><i class="fas fa-exclamation-triangle"></i> Veuillez corriger les erreurs suivantes :</strong>
            <ul style="margin:8px 0 0 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('inscriptTraite') }}" method="POST" enctype="multipart/form-data" id="mainForm">
        @csrf
        @method('POST')

        <div class="container mt-4">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-navigation">
                        <div class="form-nav-item active" data-section="section-personnel">
                            <i class="feather-user me-2"></i> Informations Personnelles
                        </div>
                        <div class="form-nav-item" data-section="section-documents-identite">
                            <i class="feather-file-text me-2"></i> Documents d'Identification
                        </div>
                        <div class="form-nav-item" data-section="section-professionnel">
                            <i class="feather-briefcase me-2"></i> Informations Professionnelles
                        </div>
                        <div class="form-nav-item" data-section="section-entreprise">
                            <i class="feather-home me-2"></i> Informations Entreprise
                        </div>
                        <div class="form-nav-item" data-section="section-freelance">
                            <i class="feather-users me-2"></i> Informations Freelance
                        </div>
                        <div class="form-nav-item" data-section="section-relations">
                            <i class="feather-link me-2"></i> Relations et Auteur
                        </div>
                        <div class="form-nav-item" data-section="section-documents">
                            <i class="feather-file me-2"></i> Documents à télécharger
                        </div>
                    </div>
                </div>

                <div class="col-md-9">
                    <!-- Photo de profil -->
                    <div class="row justify-content-center mb-4">
                        <div class="col-auto text-center">
                            <div class="profile-image-container">
                                <img id="profile-image-preview"
                                    src="{{ asset('assets/home/images/profil/profildefaut.jpg') }}" class="profile-image"
                                    alt="Photo de profil">
                                <div class="profile-image-overlay">Cliquez pour changer</div>
                                <input type="file" id="avatar" name="avatar" accept="image/*"
                                    class="file-input @error('avatar') is-invalid @enderror">
                                @error('avatar')
                                    <span class="invalid-feedback d-block text-center" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 1: Informations Personnelles -->
                    <div class="form-section active-section" id="section-personnel">
                        <h3 class="form-section-title">
                            <i class="feather-user me-2"></i> Informations Personnelles
                        </h3>

                        <!-- Type d'adhésion -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label required-field d-block mb-3">Type d'adhésion</label>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <div class="form-check-card">
                                            <input class="form-check-input" type="radio" name="typeAdhesion"
                                                id="typeAdhesion_nouveau" value="nouveau"
                                                {{ old('typeAdhesion') == 'nouveau' ? 'checked' : '' }} required>
                                            <label class="form-check-label w-100 h-100" for="typeAdhesion_nouveau">
                                                <div class="card border border-light h-100">
                                                    <div class="card-body text-center">
                                                        <i class="feather-user-plus fs-2 mb-2 text-muted"></i>
                                                        <h6 class="card-title">Nouveau</h6>
                                                        <p class="card-text small text-muted">Première adhésion</p>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @error('typeAdhesion')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3">
                            <!-- Identité -->
                            <div class="col-md-4">
                                <label for="civilite" class="form-label required-field">Civilité</label>
                                <div class="rbt-modern-select bg-transparent height-45">
                                    <select
                                        class="form-control form-control-custom select-custom @error('civilite') is-invalid @enderror"
                                        name="civilite" id="civilite" required>
                                        <option value="">Sélectionnez...</option>
                                        <option value="M." {{ old('civilite') == 'M.' ? 'selected' : '' }}>M.</option>
                                        <option value="Mme" {{ old('civilite') == 'Mme' ? 'selected' : '' }}>Mme
                                        </option>
                                        <option value="Mlle" {{ old('civilite') == 'Mlle' ? 'selected' : '' }}>Mlle
                                        </option>
                                    </select>
                                </div>
                                @error('civilite')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="nom" class="form-label required-field">Nom</label>
                                <input type="text" id="nom" name="nom" value="{{ old('nom') }}"
                                    class="form-control form-control-custom @error('nom') is-invalid @enderror" required
                                    placeholder="Votre nom">
                                @error('nom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="prenom" class="form-label required-field">Prénom</label>
                                <input type="text" id="prenom" name="prenom" value="{{ old('prenom') }}"
                                    class="form-control form-control-custom @error('prenom') is-invalid @enderror" required
                                    placeholder="Votre prénom">
                                @error('prenom')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Contacts -->
                            <div class="col-md-4">
                                <label for="contact" class="form-label required-field">Contact Principal</label>
                                <div class="input-group">
                                    <span class="input-group-text">+225</span>
                                    <input type="tel" id="contact" name="contact" value="{{ old('contact') }}"
                                        class="form-control form-control-custom @error('contact') is-invalid @enderror"
                                        required pattern="[0-9]{10}" maxlength="10" placeholder="0700000000">
                                </div>

                                @error('contact')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="contact_2" class="form-label">Contact Secondaire</label>
                                <div class="input-group">
                                    <span class="input-group-text">+225</span>
                                    <input type="tel" id="contact_2" name="contact_2"
                                        value="{{ old('contact_2') }}"
                                        class="form-control form-control-custom @error('contact_2') is-invalid @enderror"
                                        pattern="[0-9]{10}" maxlength="10" placeholder="0700000000">
                                </div>
                                @error('contact_2')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="fax" class="form-label">Fax</label>
                                <input type="text" id="fax" name="fax" value="{{ old('fax') }}"
                                    class="form-control form-control-custom @error('fax') is-invalid @enderror"
                                    placeholder="Numéro de fax">
                                @error('fax')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label for="email" class="form-label required-field">Email     <small class="text-muted">Ce email servira d'identifiant (login)</small></label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}"
                                    class="form-control form-control-custom @error('email') is-invalid @enderror" required
                                    placeholder="votre@email.com">

                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Naissance -->
                            <div class="col-md-3">
                                <label for="date_naissance" class="form-label required-field">Date de Naissance</label>
                                <input type="date" id="date_naissance" name="date_naissance"
                                    value="{{ old('date_naissance') }}"
                                    class="form-control form-control-custom @error('date_naissance') is-invalid @enderror"
                                    required max="{{ date('Y-m-d') }}">
                                @error('date_naissance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label for="lieu_naissance" class="form-label required-field">Lieu de Naissance</label>
                                <input type="text" id="lieu_naissance" name="lieu_naissance"
                                    value="{{ old('lieu_naissance') }}"
                                    class="form-control form-control-custom @error('lieu_naissance') is-invalid @enderror"
                                    required placeholder="Ville, Pays">
                                @error('lieu_naissance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Nationalité et situation familiale -->
                            <div class="col-md-4">
                                <label for="nationalite" class="form-label required-field">Nationalité</label>
                                <input type="text" id="nationalite" name="nationalite"
                                    value="{{ old('nationalite') }}"
                                    class="form-control form-control-custom @error('nationalite') is-invalid @enderror"
                                    required placeholder="Votre nationalité">
                                @error('nationalite')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="situation_matrimoniale" class="form-label required-field">Situation
                                    Matrimoniale</label>
                                <div class="rbt-modern-select bg-transparent height-45">
                                    <select
                                        class="form-control form-control-custom select-custom @error('situation_matrimoniale') is-invalid @enderror"
                                        name="situation_matrimoniale" id="situation_matrimoniale" required>
                                        <option value="">Sélectionnez...</option>
                                        <option value="Célibataire"
                                            {{ old('situation_matrimoniale') == 'Célibataire' ? 'selected' : '' }}>
                                            Célibataire
                                        </option>
                                        <option value="Marié(e)"
                                            {{ old('situation_matrimoniale') == 'Marié(e)' ? 'selected' : '' }}>Marié(e)
                                        </option>
                                        <option value="Divorcé(e)"
                                            {{ old('situation_matrimoniale') == 'Divorcé(e)' ? 'selected' : '' }}>
                                            Divorcé(e)
                                        </option>
                                        <option value="Veuf/Veuve"
                                            {{ old('situation_matrimoniale') == 'Veuf/Veuve' ? 'selected' : '' }}>
                                            Veuf/Veuve
                                        </option>
                                    </select>
                                </div>
                                @error('situation_matrimoniale')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="nombre_charge" class="form-label">Nombre de personnes à charge</label>
                                <input type="number" id="nombre_charge" name="nombre_charge"
                                    value="{{ old('nombre_charge', 0) }}"
                                    class="form-control form-control-custom @error('nombre_charge') is-invalid @enderror"
                                    min="0" placeholder="0">
                                @error('nombre_charge')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="adresse" class="form-label required-field">Adresse personnelle</label>
                                <input type="text" id="adresse" name="adresse" value="{{ old('adresse') }}"
                                    class="form-control form-control-custom @error('adresse') is-invalid @enderror"
                                    required placeholder="Votre adresse personnelle">
                                @error('adresse')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Date adhésion UNAMEPCI -->
                            <div class="col-md-6">
                                <label for="date_adhesion_unamepci" class="form-label">Date d'adhésion UNAMEPCI</label>
                                <input type="date" id="date_adhesion_unamepci" name="date_adhesion_unamepci"
                                    value="{{ old('date_adhesion_unamepci') }}"
                                    class="form-control form-control-custom @error('date_adhesion_unamepci') is-invalid @enderror"
                                    max="{{ date('Y-m-d') }}">
                                @error('date_adhesion_unamepci')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Documents d'Identification -->
                    <div class="form-section conditional-hidden" id="section-documents-identite">
                        <h3 class="form-section-title">
                            <i class="feather-file-text me-2"></i> Documents d'Identification
                        </h3>

                        <div class="row g-3">
                            <!-- Type de pièce -->
                            <div class="col-md-6">
                                <label for="type_piece_id" class="form-label required-field">Type de pièce
                                    d'identité</label>
                                <div class="rbt-modern-select bg-transparent height-45">
                                    <select
                                        class="form-control form-control-custom select-custom @error('type_piece_id') is-invalid @enderror"
                                        name="type_piece_id" id="type_piece_id" required>
                                        <option value="">Sélectionnez...</option>
                                        @foreach ($typePieces as $typePiece)
                                            <option value="{{ $typePiece->id }}"
                                                {{ old('type_piece_id') == $typePiece->id ? 'selected' : '' }}>
                                                {{ $typePiece->libelle }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('type_piece_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="numero_piece" class="form-label required-field">Numéro de la pièce</label>
                                <input type="text" id="numero_piece" name="numero_piece"
                                    value="{{ old('numero_piece') }}"
                                    class="form-control form-control-custom @error('numero_piece') is-invalid @enderror"
                                    required placeholder="Numéro de la pièce">
                                @error('numero_piece')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Dates et lieu -->
                            <div class="col-md-4">
                                <label for="date_etablissement_piece" class="form-label required-field">Date
                                    d'établissement</label>
                                <input type="date" id="date_etablissement_piece" name="date_etablissement_piece"
                                    value="{{ old('date_etablissement_piece') }}"
                                    class="form-control form-control-custom @error('date_etablissement_piece') is-invalid @enderror"
                                    required max="{{ date('Y-m-d') }}">
                                @error('date_etablissement_piece')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-8">
                                <label for="lieu_etablissement_piece" class="form-label required-field">Lieu
                                    d'établissement</label>
                                <input type="text" id="lieu_etablissement_piece" name="lieu_etablissement_piece"
                                    value="{{ old('lieu_etablissement_piece') }}"
                                    class="form-control form-control-custom @error('lieu_etablissement_piece') is-invalid @enderror"
                                    required placeholder="Lieu où la pièce a été établie">
                                @error('lieu_etablissement_piece')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- ONMCI -->
                            <div class="col-md-6">
                                <label for="numero_inscription_ONMCI" class="form-label">N° d'inscription ONMCI</label>
                                <input type="text" id="numero_inscription_ONMCI" name="numero_inscription_ONMCI"
                                    value="{{ old('numero_inscription_ONMCI') }}"
                                    class="form-control form-control-custom @error('numero_inscription_ONMCI') is-invalid @enderror"
                                    placeholder="Numéro d'inscription ONMCI">
                                @error('numero_inscription_ONMCI')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="pseudonyme_recon_ONMCI" class="form-label">Pseudonyme de reconnaissance
                                    ONMCI</label>
                                <input type="text" id="pseudonyme_recon_ONMCI" name="pseudonyme_recon_ONMCI"
                                    value="{{ old('pseudonyme_recon_ONMCI') }}"
                                    class="form-control form-control-custom @error('pseudonyme_recon_ONMCI') is-invalid @enderror"
                                    placeholder="Pseudonyme ONMCI">
                                @error('pseudonyme_recon_ONMCI')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Upload des pièces -->
                            <div class="col-md-6">
                                <label class="form-label required-field">Recto de la pièce</label>
                                <div class="document-upload" id="rectoUpload">
                                    <input type="file" name="pieces_joints_recto" id="pieces_joints_recto"
                                        class="@error('pieces_joints_recto') is-invalid @enderror" required>
                                    <div class="upload-content">
                                        <i class="feather-upload fs-4 mb-2"></i>
                                        <p class="mb-1 upload-text">Cliquez pour télécharger le recto</p>
                                        <small class="text-muted">Format: JPEG, PNG, PDF (max 2MB)</small>
                                    </div>
                                </div>
                                @error('pieces_joints_recto')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label required-field">Verso de la pièce</label>
                                <div class="document-upload" id="versoUpload">
                                    <input type="file" name="pieces_joints_verso" id="pieces_joints_verso"
                                        class="@error('pieces_joints_verso') is-invalid @enderror" required>
                                    <div class="upload-content">
                                        <i class="feather-upload fs-4 mb-2"></i>
                                        <p class="mb-1 upload-text">Cliquez pour télécharger le verso</p>
                                        <small class="text-muted">Format: JPEG, PNG, PDF (max 2MB)</small>
                                    </div>
                                </div>
                                @error('pieces_joints_verso')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Informations Professionnelles -->
                    <div class="form-section conditional-hidden" id="section-professionnel">
                        <h3 class="form-section-title">
                            <i class="feather-briefcase me-2"></i> Informations Professionnelles Principales
                        </h3>

                        <div class="row g-3">
                            <!-- Matricule et raison sociale -->
                            <div class="col-md-6">
                                <label for="matricule" class="form-label required-field">Matricule</label>
                                <input type="text" id="matricule" name="matricule" value="{{ old('matricule') }}"
                                    class="form-control form-control-custom @error('matricule') is-invalid @enderror"
                                    required placeholder="Votre matricule">
                                @error('matricule')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="raison_social_primaire" class="form-label required-field">Raison sociale
                                    principale</label>
                                <input type="text" id="raison_social_primaire" name="raison_social_primaire"
                                    value="{{ old('raison_social_primaire') }}"
                                    class="form-control form-control-custom @error('raison_social_primaire') is-invalid @enderror"
                                    placeholder="Raison sociale" required>
                                @error('raison_social_primaire')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Spécialité et fonction -->
                            <div class="col-md-6">
                                <label for="specialite_id" class="form-label required-field">Spécialité</label>
                                <div class="rbt-modern-select bg-transparent height-45">
                                    <select
                                        class="form-control form-control-custom select-custom @error('specialite_id') is-invalid @enderror"
                                        name="specialite_id" id="specialite_id" required>
                                        <option value="">Sélectionnez...</option>
                                        @foreach ($specialites as $specialite)
                                            <option value="{{ $specialite->id }}"
                                                {{ old('specialite_id') == $specialite->id ? 'selected' : '' }}>
                                                {{ $specialite->libelle }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('specialite_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="fonction" class="form-label">Fonction occupée</label>
                                <input type="text" id="fonction" name="fonction" value="{{ old('fonction') }}"
                                    class="form-control form-control-custom @error('fonction') is-invalid @enderror"
                                    placeholder="Votre fonction">
                                @error('fonction')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Expérience -->
                            <div class="col-md-6">
                                <label for="date_debut_metier" class="form-label">Date de début dans le métier</label>
                                <input type="date" id="date_debut_metier" name="date_debut_metier"
                                    value="{{ old('date_debut_metier') }}"
                                    class="form-control form-control-custom @error('date_debut_metier') is-invalid @enderror"
                                    max="{{ date('Y-m-d') }}">
                                @error('date_debut_metier')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="nombre_annee_experience" class="form-label required-field ">Nombre d'années
                                    d'expérience</label>
                                <input type="number" id="nombre_annee_experience" name="nombre_annee_experience"
                                    value="{{ old('nombre_annee_experience') }}"
                                    class="form-control form-control-custom @error('nombre_annee_experience') is-invalid @enderror"
                                    min="-1" placeholder="0" required>
                                @error('nombre_annee_experience')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="statut_emploi" class="form-label required-field">Statut d'emploi</label>
                                <div class="rbt-modern-select bg-transparent height-45">
                                    <select
                                        class="form-control form-control-custom select-custom @error('statut_emploi') is-invalid @enderror"
                                        name="statut_emploi" id="statut_emploi" required>
                                        <option value="">Sélectionnez...</option>
                                        <option value="Stage" {{ old('statut_emploi') == 'Stage' ? 'selected' : '' }}>
                                            Stage</option>
                                        <option value="CDD" {{ old('statut_emploi') == 'CDD' ? 'selected' : '' }}>
                                            CDD</option>
                                        <option value="CDI" {{ old('statut_emploi') == 'CDI' ? 'selected' : '' }}>
                                            CDI</option>
                                        <option value="Retraité"
                                            {{ old('statut_emploi') == 'Retraité' ? 'selected' : '' }}>
                                            Retraité</option>
                                    </select>
                                </div>
                                @error('statut_emploi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Domaine d'activité et employeur -->
                            <div class="col-md-6">
                                <label for="domaine_activite" class="form-label">Domaine d'activité</label>
                                <input type="text" id="domaine_activite" name="domaine_activite"
                                    value="{{ old('domaine_activite') }}"
                                    class="form-control form-control-custom @error('domaine_activite') is-invalid @enderror"
                                    placeholder="Domaine d'activité principal">
                                @error('domaine_activite')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="nom_employeur_principale" class="form-label">Nom de l'employeur
                                    principal</label>
                                <input type="text" id="nom_employeur_principale" name="nom_employeur_principale"
                                    value="{{ old('nom_employeur_principale') }}"
                                    class="form-control form-control-custom @error('nom_employeur_principale') is-invalid @enderror"
                                    placeholder="Nom de l'employeur">
                                @error('nom_employeur_principale')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="date_recrutement" class="form-label">Date de recrutement</label>
                                <input type="date" id="date_recrutement" name="date_recrutement"
                                    value="{{ old('date_recrutement') }}"
                                    class="form-control form-control-custom @error('date_recrutement') is-invalid @enderror"
                                    max="{{ date('Y-m-d') }}">
                                @error('date_recrutement')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 4: Informations sur l'Entreprise -->
                    <div class="form-section conditional-hidden" id="section-entreprise">
                        <h3 class="form-section-title">
                            <i class="feather-home me-2"></i> Informations sur l'Entreprise
                        </h3>

                        <div class="row g-3">
                            <!-- Identité entreprise -->
                            <div class="col-md-6">
                                <label for="sigle" class="form-label required-field">Sigle de l'entreprise</label>
                                <input type="text" id="sigle" name="sigle" value="{{ old('sigle') }}"
                                    class="form-control form-control-custom @error('sigle') is-invalid @enderror"
                                    placeholder="Sigle" required>
                                @error('sigle')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="date_creation" class="form-label">Date de création</label>
                                <input type="date" id="date_creation" name="date_creation"
                                    value="{{ old('date_creation') }}"
                                    class="form-control form-control-custom @error('date_creation') is-invalid @enderror"
                                    max="{{ date('Y-m-d') }}">
                                @error('date_creation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Autorisation et immatriculation -->
                            <div class="col-md-6">
                                <label for="numero_autorisation" class="form-label">N° d'autorisation</label>
                                <input type="text" id="numero_autorisation" name="numero_autorisation"
                                    value="{{ old('numero_autorisation') }}"
                                    class="form-control form-control-custom @error('numero_autorisation') is-invalid @enderror"
                                    placeholder="Numéro d'autorisation">
                                @error('numero_autorisation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="num_immatriculation" class="form-label">N° d'immatriculation</label>
                                <input type="text" id="num_immatriculation" name="num_immatriculation"
                                    value="{{ old('num_immatriculation') }}"
                                    class="form-control form-control-custom @error('num_immatriculation') is-invalid @enderror"
                                    placeholder="Numéro d'immatriculation">
                                @error('num_immatriculation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Forme juridique -->
                            <div class="col-md-6">
                                <label for="forme_juridique_id" class="form-label required-field">Forme juridique</label>
                                <div class="rbt-modern-select bg-transparent height-45">
                                    <select
                                        class="form-control form-control-custom select-custom @error('forme_juridique_id') is-invalid @enderror"
                                        name="forme_juridique_id" id="forme_juridique_id" required>
                                        <option value="">Sélectionnez...</option>
                                        @foreach ($formeJuridiques as $forme)
                                            <option value="{{ $forme->id }}"
                                                {{ old('forme_juridique_id') == $forme->id ? 'selected' : '' }}>
                                                {{ $forme->libelle }} ( {{ $forme->description }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('forme_juridique_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 conditional-hidden" id="precise-forme-juridique-container">
                                <label for="precise_forme_juridique" class="form-label">Précision forme juridique</label>
                                <input type="text" id="precise_forme_juridique" name="precise_forme_juridique"
                                    value="{{ old('precise_forme_juridique') }}"
                                    class="form-control form-control-custom @error('precise_forme_juridique') is-invalid @enderror"
                                    placeholder="Précisions supplémentaires">
                                @error('precise_forme_juridique')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Adresse entreprise -->
                            <div class="col-md-4">
                                <label for="ville_id" class="form-label required-field">Ville</label>
                                <div class="rbt-modern-select bg-transparent height-45">
                                    <select
                                        class="form-control form-control-custom select-custom @error('ville_id') is-invalid @enderror"
                                        name="ville_id" id="ville_id" required>
                                        <option value="">Sélectionnez...</option>
                                        @foreach ($villes as $ville)
                                            <option value="{{ $ville->id }}"
                                                {{ old('ville_id') == $ville->id ? 'selected' : '' }}>
                                                {{ $ville->libelle }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('ville_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="commune" class="form-label">Commune</label>
                                <input type="text" id="commune" name="commune" value="{{ old('commune') }}"
                                    class="form-control form-control-custom @error('commune') is-invalid @enderror"
                                    placeholder="Commune">
                                @error('commune')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <label for="quartier" class="form-label">Quartier</label>
                                <input type="text" id="quartier" name="quartier" value="{{ old('quartier') }}"
                                    class="form-control form-control-custom @error('quartier') is-invalid @enderror"
                                    placeholder="Quartier">
                                @error('quartier')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Suite adresse -->
                            <div class="col-md-6">
                                <label for="rue" class="form-label">Rue</label>
                                <input type="text" id="rue" name="rue" value="{{ old('rue') }}"
                                    class="form-control form-control-custom @error('rue') is-invalid @enderror"
                                    placeholder="Nom de la rue">
                                @error('rue')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="adresse_postale_entreprise" class="form-label">Adresse postale
                                    entreprise</label>
                                <input type="text" id="adresse_postale_entreprise" name="adresse_postale_entreprise"
                                    value="{{ old('adresse_postale_entreprise') }}"
                                    class="form-control form-control-custom @error('adresse_postale_entreprise') is-invalid @enderror"
                                    placeholder="Adresse postale">
                                @error('adresse_postale_entreprise')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Contacts entreprise -->
                            <div class="col-md-6">
                                <label for="email_entreprise" class="form-label">Email entreprise</label>
                                <input type="email" id="email_entreprise" name="email_entreprise"
                                    value="{{ old('email_entreprise') }}"
                                    class="form-control form-control-custom @error('email_entreprise') is-invalid @enderror"
                                    placeholder="entreprise@email.com">
                                @error('email_entreprise')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="telephone_entreprise" class="form-label">Téléphone entreprise</label>
                                <div class="input-group">
                                    <span class="input-group-text">+225</span>
                                    <input type="tel" id="telephone_entreprise" name="telephone_entreprise"
                                        value="{{ old('telephone_entreprise') }}"
                                        class="form-control form-control-custom @error('telephone_entreprise') is-invalid @enderror"
                                        pattern="[0-9]{10}" maxlength="10" placeholder="0700000000">
                                </div>
                                @error('telephone_entreprise')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="fax_entreprise" class="form-label">Fax entreprise</label>
                                <input type="text" id="fax_entreprise" name="fax_entreprise"
                                    value="{{ old('fax_entreprise') }}"
                                    class="form-control form-control-custom @error('fax_entreprise') is-invalid @enderror"
                                    placeholder="Fax entreprise">
                                @error('fax_entreprise')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="localisation_entreprise" class="form-label required-field">Localisation
                                    entreprise</label>
                                <input type="text" id="localisation_entreprise" name="localisation_entreprise"
                                    value="{{ old('localisation_entreprise') }}"
                                    class="form-control form-control-custom @error('localisation_entreprise') is-invalid @enderror"
                                    required placeholder="Localisation de l'entreprise">
                                @error('localisation_entreprise')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 5: Informations Freelance -->
                    <div class="form-section conditional-hidden" id="section-freelance">
                        <h3 class="form-section-title">
                            <i class="feather-users me-2"></i> Informations Freelance/Activité Secondaire
                        </h3>

                        <div class="row g-3">
                            <!-- Raison sociale freelance -->
                            <div class="col-md-6">
                                <label for="raison_social_secondaire_freelance" class="form-label">Raison sociale
                                    freelance</label>
                                <input type="text" id="raison_social_secondaire_freelance"
                                    name="raison_social_secondaire_freelance"
                                    value="{{ old('raison_social_secondaire_freelance') }}"
                                    class="form-control form-control-custom @error('raison_social_secondaire_freelance') is-invalid @enderror"
                                    placeholder="Raison sociale freelance">
                                @error('raison_social_secondaire_freelance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Fonction freelance -->
                            <div class="col-md-6">
                                <label for="fonction_occupe_freelance" class="form-label">Fonction occupée
                                    (freelance)</label>
                                <input type="text" id="fonction_occupe_freelance" name="fonction_occupe_freelance"
                                    value="{{ old('fonction_occupe_freelance') }}"
                                    class="form-control form-control-custom @error('fonction_occupe_freelance') is-invalid @enderror"
                                    placeholder="Fonction freelance">
                                @error('fonction_occupe_freelance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Type de contrat freelance -->
                            <div class="col-md-6">
                                <label for="type_contrat_freelance" class="form-label">Type de contrat (freelance)</label>
                                <div class="rbt-modern-select bg-transparent height-45">
                                    <select
                                        class="form-control form-control-custom select-custom @error('type_contrat_freelance') is-invalid @enderror"
                                        name="type_contrat_freelance" id="type_contrat_freelance">
                                        <option value="">Sélectionnez...</option>
                                        <option value="CDI"
                                            {{ old('type_contrat_freelance') == 'CDI' ? 'selected' : '' }}>CDI</option>
                                        <option value="CDD"
                                            {{ old('type_contrat_freelance') == 'CDD' ? 'selected' : '' }}>CDD</option>
                                        <option value="Prestation"
                                            {{ old('type_contrat_freelance') == 'Prestation' ? 'selected' : '' }}>
                                            Prestation
                                        </option>
                                        <option value="Consultant"
                                            {{ old('type_contrat_freelance') == 'Consultant' ? 'selected' : '' }}>
                                            Consultant
                                        </option>
                                        <option value="Autre"
                                            {{ old('type_contrat_freelance') == 'Autre' ? 'selected' : '' }}>Autre
                                        </option>
                                    </select>
                                </div>
                                @error('type_contrat_freelance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Contacts freelance -->
                            <div class="col-md-6">
                                <label for="telephone_freelance" class="form-label">Téléphone freelance</label>
                                <div class="input-group">
                                    <span class="input-group-text">+225</span>
                                    <input type="tel" id="telephone_freelance" name="telephone_freelance"
                                        value="{{ old('telephone_freelance') }}"
                                        class="form-control form-control-custom @error('telephone_freelance') is-invalid @enderror"
                                        pattern="[0-9]{10}" maxlength="10" placeholder="0700000000">
                                </div>
                                @error('telephone_freelance')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Localisation freelance -->
                            <div class="col-md-6">
                                <label for="localisation_freelance" class="form-label">Localisation freelance</label>
                                <input type="text" id="localisation_freelance" name="localisation_freelance"
                                    value="{{ old('localisation_freelance') }}"
                                    class="form-control form-control-custom @error('localisation_freelance') is-invalid @enderror"
                                    placeholder="Localisation">
                                @error('localisation_freelance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="adresse_postale_freelance" class="form-label">Adresse postale
                                    freelance</label>
                                <input type="text" id="adresse_postale_freelance" name="adresse_postale_freelance"
                                    value="{{ old('adresse_postale_freelance') }}"
                                    class="form-control form-control-custom @error('adresse_postale_freelance') is-invalid @enderror"
                                    placeholder="Adresse postale freelance">
                                @error('adresse_postale_freelance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Domaine activité freelance -->
                            <div class="col-md-6">
                                <label for="domaine_activite_freelance" class="form-label">Domaine d'activité
                                    (freelance)</label>
                                <input type="text" id="domaine_activite_freelance" name="domaine_activite_freelance"
                                    value="{{ old('domaine_activite_freelance') }}"
                                    class="form-control form-control-custom @error('domaine_activite_freelance') is-invalid @enderror"
                                    placeholder="Domaine d'activité freelance">
                                @error('domaine_activite_freelance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="fax_freelance" class="form-label">Fax freelance</label>
                                <input type="text" id="fax_freelance" name="fax_freelance"
                                    value="{{ old('fax_freelance') }}"
                                    class="form-control form-control-custom @error('fax_freelance') is-invalid @enderror"
                                    placeholder="Fax freelance">
                                @error('fax_freelance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Section 6: Relations et Information sur l'Auteur -->
                    <div class="form-section conditional-hidden" id="section-relations">
                        <h3 class="form-section-title">
                            <i class="feather-link me-2"></i> Relations et Information sur l'Auteur
                        </h3>

                        <!-- Question 1: Relation avec un tiers -->
                        <div class="mb-4">
                            <label class="form-label d-block">Avez-vous une relation avec un tiers ?</label>
                            <div class="oui-non-toggle">
                                <label class="toggle-option" id="toggleRelationNon">
                                    <input type="radio" name="relation_tiers" value="0"
                                        {{ old('relation_tiers', 0) == 0 ? 'checked' : '' }}>
                                    Non
                                </label>
                                <label class="toggle-option" id="toggleRelationOui">
                                    <input type="radio" name="relation_tiers" value="1"
                                        {{ old('relation_tiers') == 1 ? 'checked' : '' }}>
                                    Oui
                                </label>
                            </div>

                            <div class="conditional-field mt-3 conditional-hidden" id="relationField">
                                <label for="nom_relation" class="form-label">Nom du tiers</label>
                                <input type="text" class="form-control form-control-custom" name="nom_relation"
                                    id="nom_relation" value="{{ old('nom_relation') }}"
                                    placeholder="Nom du tiers avec qui vous avez une relation">
                            </div>
                        </div>

                        <!-- Question 2: L'auteur de l'adhésion -->
                        <div id="questionAuteurSection">
                            <label class="form-label d-block">Qui est l'auteur de votre adhésion ?</label>

                            <div class="conditional-field p-3 mb-3 conditional-hidden" id="auteurTiersSection">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="etre_auteur"
                                        id="auteur_tiers_oui" value="1"
                                        {{ old('etre_auteur') == 1 && old('relation_tiers') == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="auteur_tiers_oui">
                                        C'est le tiers mentionné ci-dessus
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="etre_auteur"
                                        id="auteur_tiers_non" value="0"
                                        {{ old('etre_auteur') == 0 && old('relation_tiers') == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="auteur_tiers_non">
                                        C'est une autre personne
                                    </label>
                                </div>
                            </div>

                            <!-- Champ pour le nom de l'auteur (si différent du tiers ou si pas de tiers) -->
                            <div class="conditional-field mt-3" id="nomAuteurContainer">
                                <label for="nom_auteur" class="form-label ">Nom de l'auteur</label>
                                <input type="text" class="form-control form-control-custom" name="nom_auteur"
                                    id="nom_auteur" value="{{ old('nom_auteur') }}"
                                    placeholder="Nom de la personne qui a effectué votre adhésion">
                            </div>
                        </div>
                    </div>

                    <!-- Section 7: Documents à télécharger -->
                    <div class="form-section conditional-hidden" id="section-documents">
                        <h3 class="form-section-title">
                            <i class="feather-file me-2"></i> Documents à télécharger
                        </h3>

                        <div class="row g-3">
                            <!-- Photo de couverture -->
                            <div class="col-md-6">
                                <label for="photo_couverture" class="form-label">Photo de couverture</label>
                                <div class="document-upload" id="couvertureUpload">
                                    <input type="file" name="photo_couverture" id="photo_couverture"
                                        class="@error('photo_couverture') is-invalid @enderror" accept=".jpeg ,.png">
                                    <div class="upload-content">
                                        <i class="feather-upload fs-4 mb-2"></i>
                                        <p class="mb-1 upload-text">Cliquez pour télécharger</p>
                                        <small class="text-muted">Format: JPEG, PNG (max 2MB)</small>
                                    </div>
                                </div>
                                @error('photo_couverture')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Carte inscription ONMCI -->
                            <div class="col-md-6">
                                <label for="document_carte_inscript_ONMCI" class="form-label ">Carte
                                    d'inscription
                                    ONMCI</label>
                                <div class="document-upload" id="carteOnmciUpload">
                                    <input type="file" name="document_carte_inscript_ONMCI"
                                        id="document_carte_inscript_ONMCI"
                                        class="@error('document_carte_inscript_ONMCI') is-invalid @enderror"
                                        accept="application/pdf,.pdf" >
                                    <div class="upload-content">
                                        <i class="feather-upload fs-4 mb-2"></i>
                                        <p class="mb-1 upload-text">Cliquez pour télécharger</p>
                                        <small class="text-muted">Format: PDF (max 2MB)</small>
                                    </div>
                                </div>
                                @error('document_carte_inscript_ONMCI')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Autorisation d'ouverture -->
                            <div class="col-md-6">
                                <label for="document_autorisation_ouverture"
                                    class="form-label required-field">Autorisation
                                    d'ouverture</label>
                                <div class="document-upload" id="autorisationUpload">
                                    <input type="file" name="document_autorisation_ouverture"
                                        id="document_autorisation_ouverture"
                                        class="@error('document_autorisation_ouverture') is-invalid @enderror"
                                        accept="application/pdf,.pdf" required>
                                    <div class="upload-content">
                                        <i class="feather-upload fs-4 mb-2"></i>
                                        <p class="mb-1 upload-text">Cliquez pour télécharger</p>
                                        <small class="text-muted">Format: PDF (max 2MB)</small>
                                    </div>
                                </div>
                                @error('document_autorisation_ouverture')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Photos d'identité supplémentaires -->
                            <div class="col-md-6">
                                <label for="photo_identite_1" class="form-label required-field">Deux (2) Photos d'identité
                                    meme tirage</label>
                                <div class="document-upload" id="photoIdentite1Upload">
                                    <input type="file" name="photo_identite_1" id="photo_identite_1"
                                        class="@error('photo_identite_1') is-invalid @enderror"
                                        accept="application/pdf,.pdf" required>
                                    <div class="upload-content">
                                        <i class="feather-upload fs-4 mb-2"></i>
                                        <p class="mb-1 upload-text">Cliquez pour télécharger</p>
                                        <small class="text-muted">Format: PDF (max 2MB)</small>
                                    </div>
                                </div>
                                @error('photo_identite_1')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <br>
                        <h3 class="form-section-title">
                            <i class="feather-file me-2 mt-2"></i>Signature
                        </h3>

                        <div class="row g-3">
                            <div class="col-12 col-lg-12 col-md-12 col-sm-12 text-center">
                                <h6 for="signature" class="fw-bold">Votre signature <span class="text-danger">*</span>
                                </h6>
                                <canvas id="signature-pad" width="300" height="300"
                                    class="@error('signature') is-invalid @enderror"></canvas>
                                <br>
                                @error('signature')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <br>
                                <div class="row">
                                    <div class="col-12 col-lg-12 col-md-12 col-sm-12">
                                        <button type="button" id="save-btn" class="btn btn-primary">Enregistrer la
                                            signature</button>
                                        <button type="button" id="clear-btn" class="btn btn-danger">Effacer</button>
                                        <input type="file" name="signature" id="signature" style="display: none"
                                            value="{{ old('signature') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Boutons de navigation -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <button type="button" class="rbt-btn btn-secondary" id="prevSection" style="display: none;">
                                    <i class="feather-arrow-left me-2"></i>Précédent
                                </button>

                                <button type="button" class="rbt-btn btn-primary" id="nextSection">
                                    Suivant<i class="feather-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Message d'information -->
                    <div class="row mt-3">
                        <div class="col-12 text-center">
                            <p class="text-muted"><span class="text-danger">*</span> Champs obligatoires</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Loader Overlay (Flashscreen médical) -->
    <div id="loaderOverlay" class="loader-overlay hidden">
        <div class="loader-content">
            <div class="medical-symbol">
                <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Caducée (symbole médical) -->
                    <path d="M50 15 L50 85 M30 35 L70 35 M30 65 L70 65" stroke="white" stroke-width="4" stroke-linecap="round"/>
                    <circle cx="50" cy="50" r="12" stroke="white" stroke-width="3" fill="none"/>
                    <path d="M38 28 L50 15 L62 28" stroke="white" stroke-width="3" fill="none" stroke-linecap="round"/>
                    <path d="M38 72 L50 85 L62 72" stroke="white" stroke-width="3" fill="none" stroke-linecap="round"/>
                    <!-- Serpent du caducée -->
                    <path d="M30 45 Q40 40 45 45 Q50 50 55 45 Q60 40 70 45" stroke="white" stroke-width="2.5" fill="none" stroke-linecap="round"/>
                    <path d="M30 55 Q40 60 45 55 Q50 50 55 55 Q60 60 70 55" stroke="white" stroke-width="2.5" fill="none" stroke-linecap="round"/>
                </svg>
            </div>
            <div class="loader-text">Traitement en cours</div>
            <div class="loader-subtext">Veuillez patienter, nous traitons votre demande...</div>
            <div class="loader-spinner"></div>
            <div class="loader-progress">
                <div class="loader-progress-bar"></div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            /* =========================
               NAVIGATION MULTI-SECTIONS
            ========================== */
            const sections = [
                'section-personnel',
                'section-documents-identite',
                'section-professionnel',
                'section-entreprise',
                'section-freelance',
                'section-relations',
                'section-documents'
            ];

            let currentSection = 0;
            let isSubmitting = false;

            function showSection(index) {
                $('.form-section').addClass('conditional-hidden');
                $('#' + sections[index]).removeClass('conditional-hidden');

                $('html, body').animate({
                    scrollTop: $('#' + sections[index]).offset().top - 100
                }, 400);

                // Mettre à jour l'état des boutons
                updateButtons();
            }

            function updateNavigation() {
                $('.form-nav-item').removeClass('active');
                $('.form-nav-item[data-section="' + sections[currentSection] + '"]').addClass('active');
            }

            function updateButtons() {
                // Gérer le bouton Précédent
                if (currentSection === 0) {
                    $('#prevSection').hide();
                } else {
                    $('#prevSection').show();
                }

                // Gérer le bouton Suivant / Soumettre
                if (currentSection === sections.length - 1) {
                    // Dernière section : remplacer par bouton de soumission
                    $('#nextSection').html('Soumettre le formulaire <i class="feather-check-circle ms-2"></i>');
                    $('#nextSection').removeClass('btn-primary').addClass('btn-success');
                    $('#nextSection').off('click').on('click', function() {
                        // Vérifier la signature avant soumission
                        const signatureFile = document.getElementById('signature');
                        const signatureCanvas = document.getElementById('signature-pad');
                        const isCanvasBlank = !signatureCanvas || signatureCanvas.toDataURL() === document.createElement('canvas').toDataURL();

                        if (!signatureFile.files.length && isCanvasBlank) {
                            Swal.fire({
                                title: 'Erreur!',
                                text: 'Veuillez enregistrer votre signature avant de soumettre.',
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                            return;
                        }

                        // Vérifier tous les champs requis
                        if (validateAllSections()) {
                            submitForm();
                        }
                    });
                } else {
                    // Pas la dernière section : bouton suivant normal
                    $('#nextSection').html('Suivant <i class="feather-arrow-right ms-2"></i>');
                    $('#nextSection').removeClass('btn-success').addClass('btn-primary');
                    $('#nextSection').off('click').on('click', function() {
                        // Validation optionnelle de la section courante
                        if (validateCurrentSection()) {
                            if (currentSection < sections.length - 1) {
                                currentSection++;
                                showSection(currentSection);
                                updateNavigation();
                            }
                        }
                    });
                }
            }

            // Validation de toutes les sections
            function validateAllSections() {
                let isValid = true;
                let firstInvalidSection = null;

                sections.forEach(function(section, index) {
                    const sectionElement = $('#' + section);
                    const requiredFields = sectionElement.find('[required]');
                    const requiredFiles = sectionElement.find('input[type="file"][required]');

                    requiredFields.each(function() {
                        if (!$(this).val() && $(this).attr('type') !== 'file') {
                            isValid = false;
                            $(this).addClass('is-invalid');
                            if (firstInvalidSection === null) firstInvalidSection = index;
                        } else {
                            $(this).removeClass('is-invalid');
                        }
                    });

                    requiredFiles.each(function() {
                        if (!this.files || this.files.length === 0) {
                            isValid = false;
                            $(this).addClass('is-invalid');
                            if (firstInvalidSection === null) firstInvalidSection = index;
                        } else {
                            $(this).removeClass('is-invalid');
                        }
                    });
                });

                if (!isValid && firstInvalidSection !== null) {
                    // Aller à la première section avec erreur
                    currentSection = firstInvalidSection;
                    showSection(currentSection);
                    updateNavigation();
                    Swal.fire({
                        title: 'Champs incomplets',
                        text: 'Veuillez remplir tous les champs obligatoires avant de soumettre.',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                }

                return isValid;
            }

            // Validation de la section courante
            function validateCurrentSection() {
                const currentSectionElement = $('#' + sections[currentSection]);
                const requiredFields = currentSectionElement.find('[required]');
                let isValid = true;

                requiredFields.each(function() {
                    if (!$(this).val() && $(this).attr('type') !== 'file') {
                        isValid = false;
                        $(this).addClass('is-invalid');
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });

                // Validation spécifique pour les fichiers requis
                const requiredFiles = currentSectionElement.find('input[type="file"][required]');
                requiredFiles.each(function() {
                    if (!this.files || this.files.length === 0) {
                        isValid = false;
                        $(this).addClass('is-invalid');
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });

                if (!isValid) {
                    Swal.fire({
                        title: 'Champs incomplets',
                        text: 'Veuillez remplir tous les champs obligatoires avant de continuer.',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                }

                return isValid;
            }

            // Fonction de soumission avec overlay
            function submitForm() {
                if (isSubmitting) return;
                isSubmitting = true;

                // Afficher l'overlay de chargement
                const loaderOverlay = document.getElementById('loaderOverlay');
                loaderOverlay.classList.remove('hidden');

                // Désactiver le bouton de soumission
                const submitBtn = $('#nextSection');
                submitBtn.prop('disabled', true);
                submitBtn.addClass('btn-loading');

                // Ajouter une classe pour désactiver les interactions sur le formulaire
                $('form').addClass('form-loading');

                // Soumettre le formulaire
                const form = document.getElementById('mainForm');
                form.submit();

                // Timeout de sécurité (si la soumission prend trop de temps)
                setTimeout(function() {
                    if (isSubmitting) {
                        console.log('La soumission prend du temps, l\'utilisateur patiente...');
                    }
                }, 5000);
            }

            // Initialisation
            showSection(currentSection);
            updateNavigation();

            // Navigation par les onglets
            $('.form-nav-item').on('click', function() {
                if (isSubmitting) return;

                const targetIndex = sections.indexOf($(this).data('section'));
                if (targetIndex !== -1) {
                    // Si on essaie d'aller plus loin, valider d'abord
                    if (targetIndex > currentSection) {
                        if (validateCurrentSection()) {
                            currentSection = targetIndex;
                            showSection(currentSection);
                            updateNavigation();
                        }
                    } else {
                        currentSection = targetIndex;
                        showSection(currentSection);
                        updateNavigation();
                    }
                }
            });

            // Bouton précédent
            $('#prevSection').on('click', function() {
                if (isSubmitting) return;

                if (currentSection > 0) {
                    currentSection--;
                    showSection(currentSection);
                    updateNavigation();
                }
            });

            /* =========================
               UPLOAD DOCUMENTS
            ========================== */
            // Gestion de l'avatar
            $('#avatar').on('change', function(e) {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#profile-image-preview').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(file);
                }
            });

            // Gestion des uploads de documents
            $('.document-upload').on('click', function() {
                if (isSubmitting) return;
                $(this).find('input[type="file"]').trigger('click');
            });

            $('.document-upload input[type="file"]').on('change', function() {
                const file = this.files[0];
                const container = $(this).closest('.document-upload');
                const text = container.find('.upload-text');

                if (file) {
                    text.text(file.name);
                    container.css('border-color', '#4a6cf7');
                    $(this).removeClass('is-invalid');
                }
            });

            /* =========================
               AUTRES FONCTIONNALITÉS
            ========================== */
            // Gestion des toggle Oui/Non
            $('.toggle-option').click(function() {
                if (isSubmitting) return;

                const parent = $(this).closest('.oui-non-toggle');
                parent.find('.toggle-option').removeClass('active');
                $(this).addClass('active');
                $(this).find('input[type="radio"]').prop('checked', true);

                // Logique spécifique pour les relations
                if ($(this).find('input[name="relation_tiers"]').length) {
                    handleRelationToggle();
                }
            });

            function handleRelationToggle() {
                const hasRelation = $('input[name="relation_tiers"]:checked').val() == '1';

                if (hasRelation) {
                    $('#relationField').removeClass('conditional-hidden');
                    $('#auteurTiersSection').removeClass('conditional-hidden');
                    $('#nomAuteurContainer').addClass('conditional-hidden');
                } else {
                    $('#relationField').addClass('conditional-hidden');
                    $('#auteurTiersSection').addClass('conditional-hidden');
                    $('#nomAuteurContainer').removeClass('conditional-hidden');
                }
            }

            // Initialiser les toggles
            $('input[type="radio"]:checked').each(function() {
                $(this).closest('.toggle-option').addClass('active');
            });
            handleRelationToggle();

            // Gestion de l'auteur
            $('input[name="etre_auteur"]').change(function() {
                if (isSubmitting) return;

                const tiersIsAuthor = $(this).val() == '1';
                if (tiersIsAuthor) {
                    $('#nomAuteurContainer').addClass('conditional-hidden');
                } else {
                    $('#nomAuteurContainer').removeClass('conditional-hidden');
                }
            });

            // Gestion de la forme juridique
            $('#forme_juridique_id').change(function() {
                if (isSubmitting) return;

                if ($(this).val() == '16') {
                    $('#precise-forme-juridique-container').removeClass('conditional-hidden');
                } else {
                    $('#precise-forme-juridique-container').addClass('conditional-hidden');
                }
            });

            // Cartes de type d'adhésion
            $('.form-check-card input[type="radio"]').change(function() {
                if (isSubmitting) return;

                $('.form-check-card').each(function() {
                    const card = $(this);
                    if (card.find('input:checked').length > 0) {
                        card.find('.card').addClass('border-primary bg-light');
                        card.find('.card i').addClass('text-primary').removeClass('text-muted');
                        card.find('.card .card-title').addClass('text-primary');
                    } else {
                        card.find('.card').removeClass('border-primary bg-light');
                        card.find('.card i').removeClass('text-primary').addClass('text-muted');
                        card.find('.card .card-title').removeClass('text-primary');
                    }
                });
            });

            // Initialiser les cartes
            $('.form-check-card input[type="radio"]:checked').trigger('change');

            // Labels dynamiques pour le type de pièce
            $('#type_piece_id').change(function() {
                if (isSubmitting) return;

                const label = $(this).find('option:selected').text();
                if (label && label !== 'Sélectionnez...') {
                    $('#rectoUpload .upload-text').text('Recto ' + label);
                    $('#versoUpload .upload-text').text('Verso ' + label);
                }
            });

            // Initialiser les labels si déjà sélectionné
            if ($('#type_piece_id').val()) {
                $('#type_piece_id').trigger('change');
            }

            // Empêcher la soumission multiple par Enter
            $('form').on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    return false;
                }
            });
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Afficher les erreurs de validation Laravel
            @if ($errors->any())
                // Cacher l'overlay si présent
                const loaderOverlay = document.getElementById('loaderOverlay');
                if (loaderOverlay) {
                    loaderOverlay.classList.add('hidden');
                }

                setTimeout(function() {
                    Swal.fire({
                        title: 'Erreur de validation',
                        text: 'Veuillez corriger les {{ $errors->count() }} erreur(s) dans le formulaire.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });

                    // Chercher et afficher la première section avec erreur
                    let hasDisplayedSection = false;

                    // Convertir les erreurs en tableau pour JavaScript
                    const errors = @json($errors->toArray());

                    // Parcourir les erreurs
                    Object.keys(errors).forEach(function(key, index) {
                        const field = document.querySelector('[name="' + key + '"]');
                        if (field) {
                            const section = field.closest('.form-section');
                            if (section && section.classList.contains('conditional-hidden')) {
                                section.classList.remove('conditional-hidden');
                                hasDisplayedSection = true;

                                // Mettre à jour la navigation
                                const sectionId = section.id;
                                const navItem = document.querySelector(
                                    '.form-nav-item[data-section="' + sectionId + '"]');
                                if (navItem) {
                                    document.querySelectorAll('.form-nav-item').forEach(item => {
                                        item.classList.remove('active');
                                    });
                                    navItem.classList.add('active');
                                }

                                // Scroll vers le champ seulement pour la première erreur
                                if (index === 0) {
                                    field.scrollIntoView({
                                        behavior: 'smooth',
                                        block: 'center'
                                    });
                                }
                            }
                        }
                    });

                    // Si aucune section n'a été affichée mais qu'il y a des erreurs
                    if (!hasDisplayedSection && Object.keys(errors).length > 0) {
                        const firstSection = document.querySelector('.form-section');
                        if (firstSection) {
                            firstSection.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        }
                    }

                }, 500);
            @endif

            // Afficher les messages de session
            @if (session('success'))
                // Cacher l'overlay
                const loaderOverlay = document.getElementById('loaderOverlay');
                if (loaderOverlay) {
                    loaderOverlay.classList.add('hidden');
                }

                Swal.fire({
                    title: 'Succès!',
                    text: '{{ session('success') }}',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    // Redirection après succès si nécessaire
                    // window.location.href = '{{ route('some.route') }}';
                });
            @endif

            @if (session('error'))
                const loaderOverlay = document.getElementById('loaderOverlay');
                if (loaderOverlay) {
                    loaderOverlay.classList.add('hidden');
                }

                Swal.fire({
                    title: 'Erreur!',
                    text: '{{ session('error') }}',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            @endif

            @if (session('info'))
                const loaderOverlay = document.getElementById('loaderOverlay');
                if (loaderOverlay) {
                    loaderOverlay.classList.add('hidden');
                }

                Swal.fire({
                    title: 'Information',
                    text: '{{ session('info') }}',
                    icon: 'info',
                    confirmButtonText: 'OK'
                });
            @endif
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const canvas = document.getElementById('signature-pad');
            const context = canvas.getContext('2d');
            let drawing = false;

            // Configuration du canvas
            context.lineWidth = 2;
            context.lineCap = 'round';
            context.strokeStyle = '#000';

            // Gestionnaires pour les événements de souris
            canvas.addEventListener('mousedown', startDrawing);
            canvas.addEventListener('mousemove', draw);
            canvas.addEventListener('mouseup', stopDrawing);
            canvas.addEventListener('mouseout', stopDrawing);

            // Gestionnaires pour les événements tactiles
            canvas.addEventListener('touchstart', startDrawing);
            canvas.addEventListener('touchmove', draw);
            canvas.addEventListener('touchend', stopDrawing);

            function startDrawing(event) {
                event.preventDefault();
                drawing = true;
                const {
                    offsetX,
                    offsetY
                } = getEventPosition(event);
                context.beginPath();
                context.moveTo(offsetX, offsetY);
            }

            function draw(event) {
                event.preventDefault();
                if (!drawing) return;
                const {
                    offsetX,
                    offsetY
                } = getEventPosition(event);
                context.lineTo(offsetX, offsetY);
                context.stroke();
            }

            function stopDrawing(event) {
                event.preventDefault();
                drawing = false;
            }

            function getEventPosition(event) {
                if (event.touches && event.touches[0]) {
                    const rect = canvas.getBoundingClientRect();
                    return {
                        offsetX: event.touches[0].clientX - rect.left,
                        offsetY: event.touches[0].clientY - rect.top
                    };
                } else {
                    return {
                        offsetX: event.offsetX,
                        offsetY: event.offsetY
                    };
                }
            }

            function isCanvasBlank(canvas) {
                const blank = document.createElement('canvas');
                blank.width = canvas.width;
                blank.height = canvas.height;
                return canvas.toDataURL() === blank.toDataURL();
            }

            document.getElementById('save-btn').addEventListener('click', (event) => {
                event.preventDefault();

                if (isCanvasBlank(canvas)) {
                    Swal.fire({
                        title: 'Erreur!',
                        text: 'Veuillez entrer une signature.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                const dataURL = canvas.toDataURL('image/png');
                const blob = dataURLToBlob(dataURL);
                const file = new File([blob], 'signature.png', {
                    type: 'image/png'
                });
                const fileInput = document.getElementById('signature');
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                fileInput.files = dataTransfer.files;

                Swal.fire({
                    title: 'Succès!',
                    text: 'Signature enregistrée.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            });

            document.getElementById('clear-btn').addEventListener('click', (event) => {
                event.preventDefault();
                context.clearRect(0, 0, canvas.width, canvas.height);
                document.getElementById('signature').value = '';
            });

            function dataURLToBlob(dataURL) {
                const byteString = atob(dataURL.split(',')[1]);
                const mimeString = dataURL.split(',')[0].split(':')[1].split(';')[0];
                const ab = new ArrayBuffer(byteString.length);
                const ia = new Uint8Array(ab);
                for (let i = 0; i < byteString.length; i++) {
                    ia[i] = byteString.charCodeAt(i);
                }
                return new Blob([ab], {
                    type: mimeString
                });
            }
        });
    </script>
@endpush
