@extends('layouts.home_dashboard', ['title' => 'Parametre Mutualiste'])
@section('content')
    <style>
        /* Styles pour afficher l'image en cercle */
        #image-preview {
            margin-bottom: -5px;
            width: 110px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            display: none;
            margin-top: 10px;
        }

        .image-container {
            margin-bottom: -5px;
            position: relative;
            width: 110px;
            height: 70px;
            border-radius: 50%;
            overflow: hidden;
            cursor: pointer;
        }

        .image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .image-container input[type="file"] {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        /* image de couverture  */
        #image-couverture {
            margin-bottom: -5px;
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none;
            margin-top: 10px;
        }

        /* Styles pour les sections */
        .form-section {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #2a41e8;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #2a41e8;
        }
    </style>
    <div class="col-lg-9">
        <!-- Start Instructor Profile  -->
        <div class="rbt-dashboard-content bg-color-white rbt-shadow-box">
            <div class="content">

                <div class="section-title">
                    <h4 class="rbt-title-style-3">Parametre</h4>
                </div>
                <div class="advance-tab-button mb--30">
                    <ul class="nav nav-tabs tab-button-style-2 justify-content-start" id="settinsTab-4" role="tablist">
                        <li role="presentation">
                            <a href="#" class="tab-button active" id="profile-tab" data-bs-toggle="tab"
                                data-bs-target="#profile" role="tab" aria-controls="profile" aria-selected="true">
                                <span class="title">Profil</span>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#" class="tab-button" id="password-tab" data-bs-toggle="tab"
                                data-bs-target="#password" role="tab" aria-controls="password" aria-selected="false">
                                <span class="title">Mot de passe</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content">
                    <div class="tab-pane fade active show" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <form action="{{ route('mutualistes.modification', $mutualiste->id) }}"
                            class="rbt-profile-row rbt-default-form row row--15" enctype="multipart/form-data"
                            method="POST">
                            @method('put')
                            @csrf
                            <div class="rbt-dashboard-content-wrapper">
                                <div class="tutor-bg-photo bg_image  height-245">
                                    @if (auth()->user()->mutualiste->photo_couverture)
                                        <img src="{{ asset($mutualiste->photo_couverture) }}"
                                            style="height:100%; width:100%" alt="Image par defaut Couverture"
                                            id="defautCouverture">
                                    @else
                                        <img src="{{ asset('assets/home/images/banner/plane.jpg') }}"
                                            style="height:100%; width:100%" alt="Image par defaut Couverture"
                                            id="defautCouverture">
                                    @endif
                                    <img id="image-couverture" src="{{ asset($mutualiste->photo_couverture) ?? '#' }}"
                                        style="height:100%; width:100% display:none;" alt="photo de Couverture">
                                    <input type="file" id="photo_couverture" accept="image/*" style="display:none; "
                                        name="photo_couverture">
                                </div>
                                <div class="rbt-tutor-information">
                                    <div class="rbt-tutor-information-left">
                                        <div class="thumbnail rbt-avatars size-lg position-relative">
                                            @if (auth()->user()->mutualiste->lien_photo)
                                                <img id="defaut" src="{{ asset($mutualiste->lien_photo) }}"
                                                    style="height: 140px; width:150px;" alt="Image de profil par défaut">
                                            @else
                                                <img id="defaut" src="{{ asset('assets/icons/user.png') }}"
                                                    style="height: 140px; width:150px;" alt="Image de profil par défaut">
                                            @endif
                                            <input type="file" id="lien_photo" accept="image/*" style="display:none; "
                                                name="lien_photo">
                                            <img id="image-preview" src="#" style="height: 140px; width:150px;"
                                                alt="photo de profil">
                                            <div class="rbt-edit-photo-inner">
                                                <button class="rbt-edit-photo" title="Modifier photo" id="customButton"
                                                    style="  display: inline-block;">
                                                    <i class="feather-camera"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="rbt-tutor-information-right">
                                        <div class="tutor-btn">
                                            <button class="rbt-btn btn-sm btn-border color-white radius-round-10"
                                                title="Modifier photo de couverture" id="CouverturePhoto"
                                                style="display: inline-block;">
                                                <i class="feather-camera"></i>
                                                Modifier Photo de couverture
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- SECTION 1: Informations personnelles -->
                            <div class="row form-section ">
                                <h5 class="section-title">Informations personnelles</h5>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="matricule">Matricule</label>
                                        <input id="matricule" type="text" name="matricule"
                                            value="{{ old('matricule', $mutualiste->matricule) }}"
                                            class="@error('matricule') is-invalid @enderror" readonly>
                                        @error('matricule')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-6 mb--20 col-lg-6 col-md-6 col-sm-6 col-6">
                                    <label>Civilite</label>
                                    <div class="rbt-modern-select bg-transparent height-45">
                                        <select class="w-100 @error('civilite') is-invalid @enderror" name="civilite"
                                            id="civilite" autocomplete="civilite" autofocus>
                                            @foreach (['M.', 'Mme', 'Mlle'] as $value)
                                                <option value="{{ $value }}"
                                                    {{ old('civilite', $mutualiste->civilite) == $value ? 'selected' : '' }}>
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('civilite')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="nom">Nom</label>
                                        <input id="nom" name="nom" type="text"
                                            value="{{ old('nom', $mutualiste->nom) }}"
                                            class=" @error('nom') is-invalid @enderror" required>
                                        @error('nom')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="prenom">Prénoms</label>
                                        <input id="prenom" name="prenom" type="text"
                                            value="{{ old('prenom', $mutualiste->prenom) }}"
                                            class=" @error('prenom') is-invalid @enderror" required>
                                        @error('prenom')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="date_naissance">Date de naissance</label>
                                        <input id="date_naissance" type="date"
                                            value="{{ old('date_naissance', $mutualiste->date_naissance) }}"
                                            name="date_naissance" class=" @error('date_naissance') is-invalid @enderror">
                                        @error('date_naissance')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="lieu_naissance">Lieu de naissance</label>
                                        <input id="lieu_naissance" type="text"
                                            value="{{ old('lieu_naissance', $mutualiste->lieu_naissance) }}"
                                            name="lieu_naissance" class=" @error('lieu_naissance') is-invalid @enderror">
                                        @error('lieu_naissance')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="nationalite">Nationalité</label>
                                        <input id="nationalite" type="text" name="nationalite"
                                            value="{{ old('nationalite', $mutualiste->nationalite) }}"
                                            class=" @error('nationalite') is-invalid @enderror">
                                        @error('nationalite')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="situation_matrimoniale">Situation matrimoniale</label>
                                        <input id="situation_matrimoniale" type="text" name="situation_matrimoniale"
                                            value="{{ old('situation_matrimoniale', $mutualiste->situation_matrimoniale) }}"
                                            class=" @error('situation_matrimoniale') is-invalid @enderror">
                                        @error('situation_matrimoniale')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                {{-- <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="nombre_charge">Nombre de charges</label>
                                        <input id="nombre_charge" type="number" name="nombre_charge"
                                            value="{{ old('nombre_charge', $mutualiste->nombre_charge) }}"
                                            class=" @error('nombre_charge') is-invalid @enderror">
                                        @error('nombre_charge')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div> --}}

                                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                    <div class="rbt-form-group">
                                        <label for="date_adhesion_unamepci">Date d'adhésion UNAMEPCI</label>
                                        <input id="date_adhesion_unamepci" type="date" name="date_adhesion_unamepci"
                                            value="{{ old('date_adhesion_unamepci', $mutualiste->date_adhesion_unamepci) }}"
                                            class=" @error('date_adhesion_unamepci') is-invalid @enderror">
                                        @error('date_adhesion_unamepci')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- SECTION 2: Coordonnées -->
                            <div class="form-section row">
                                <h5 class="section-title">Coordonnées</h5>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="email">Email</label>
                                        <input id="email" name="email" type="email"
                                            value="{{ old('email', $mutualiste->email) }}"
                                            class=" @error('email') is-invalid @enderror" required>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="contact">Téléphone 1</label>
                                        <input id="contact" type="tel" name="contact"
                                            value="{{ old('contact', $mutualiste->contact) }}"
                                            class=" @error('contact') is-invalid @enderror">
                                        @error('contact')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="contact_2">Téléphone 2</label>
                                        <input id="contact_2" type="tel" name="contact_2"
                                            value="{{ old('contact_2', $mutualiste->contact_2) }}"
                                            class=" @error('contact_2') is-invalid @enderror">
                                        @error('contact_2')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                {{-- <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="fax">Fax</label>
                                        <input id="fax" type="text" name="fax"
                                            value="{{ old('fax', $mutualiste->fax) }}"
                                            class=" @error('fax') is-invalid @enderror">
                                        @error('fax')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div> --}}

                                <div class="col-6 mb--20 col-md-6 col-sm-6 col-6">
                                    <label>Ville ( d'habitation)</label>
                                    <div class="rbt-modern-select bg-transparent height-45">
                                        <select class="w-100 @error('ville_personnel_id') is-invalid @enderror"
                                            name="ville_personnel_id" id="ville_personnel_id"
                                            autocomplete="ville_personnel_id" autofocus>
                                            <option value="">Sélectionnez la ville</option>
                                            @foreach ($villes as $ville)
                                                <option value="{{ $ville->id }}"
                                                    {{ old('ville_personnel_id', $mutualiste->ville_personnel_id) == $ville->id ? 'selected' : '' }}>
                                                    {{ $ville->libelle }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('ville_personnel_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="commune_personnel">Commune </label>
                                        <input id="commune_personnel" name="commune_personnel" type="text"
                                            value="{{ old('commune_personnel', $mutualiste->commune_personnel) }}"
                                            class=" @error('commune_personnel') is-invalid @enderror">
                                        @error('commune_personnel')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="adresse">Adresse</label>
                                        <input id="adresse" name="adresse" type="text"
                                            value="{{ old('adresse', $mutualiste->adresse) }}"
                                            class=" @error('adresse') is-invalid @enderror">
                                        @error('adresse')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>


                            <!-- SECTION 3: Pièce d'identité -->
                            <div class="form-section row">
                                <h5 class="section-title">Pièce d'identité</h5>

                                <div class="col-6 mb--20 col-lg-6 col-md-6 col-sm-6 col-6">
                                    <label>Type de pièce</label>
                                    <div class="rbt-modern-select bg-transparent height-45">
                                        <select class="w-100 @error('type_piece_id') is-invalid @enderror"
                                            name="type_piece_id" id="type_piece_id" autocomplete="type_piece_id"
                                            autofocus>
                                            @foreach ($typePieces as $typePiece)
                                                <option value="{{ $typePiece->id }}"
                                                    {{ old('type_piece_id', $mutualiste->type_piece_id) == $typePiece->id ? 'selected' : '' }}>
                                                    {{ $typePiece->libelle }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('type_piece_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="numero_piece">Numéro de pièce</label>
                                        <input id="numero_piece" type="text" name="numero_piece"
                                            value="{{ old('numero_piece', $mutualiste->numero_piece) }}"
                                            class=" @error('numero_piece') is-invalid @enderror">
                                        @error('numero_piece')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-4 col-4">
                                    <div class="rbt-form-group">
                                        <label for="date_etablissement_piece">Établie le</label>
                                        <input id="date_etablissement_piece" type="date"
                                            name="date_etablissement_piece"
                                            value="{{ old('date_etablissement_piece', $mutualiste->date_etablissement_piece) }}"
                                            class=" @error('date_etablissement_piece') is-invalid @enderror">
                                        @error('date_etablissement_piece')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-4">
                                    <div class="rbt-form-group">
                                        <label for="date_expiration_piece">expire le</label>
                                        <input id="date_expiration_piece" type="date" name="date_expiration_piece"
                                            value="{{ old('date_expiration_piece', $mutualiste->date_expiration_piece) }}"
                                            class=" @error('date_expiration_piece') is-invalid @enderror">
                                        @error('date_expiration_piece')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-4 col-4">
                                    <div class="rbt-form-group">
                                        <label for="lieu_etablissement_piece">Lieu d'établissement</label>
                                        <input id="lieu_etablissement_piece" type="text"
                                            name="lieu_etablissement_piece"
                                            value="{{ old('lieu_etablissement_piece', $mutualiste->lieu_etablissement_piece) }}"
                                            class=" @error('lieu_etablissement_piece') is-invalid @enderror">
                                        @error('lieu_etablissement_piece')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="pieces_joints_recto">Pièce recto</label>
                                        <input id="pieces_joints_recto" type="file" name="pieces_joints_recto"
                                            class=" @error('pieces_joints_recto') is-invalid @enderror">
                                        @if ($mutualiste->pieces_joints_recto)
                                            <div class="mt-2">
                                                <a href="{{ asset($mutualiste->pieces_joints_recto) }}" target="_blank"
                                                    class="text-primary">
                                                    Voir le document actuel
                                                </a>
                                            </div>
                                        @endif
                                        @error('pieces_joints_recto')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="pieces_joints_verso">Pièce verso</label>
                                        <input id="pieces_joints_verso" type="file" name="pieces_joints_verso"
                                            class=" @error('pieces_joints_verso') is-invalid @enderror">
                                        @if ($mutualiste->pieces_joints_verso)
                                            <div class="mt-2">
                                                <a href="{{ asset($mutualiste->pieces_joints_verso) }}" target="_blank"
                                                    class="text-primary">
                                                    Voir le document actuel
                                                </a>
                                            </div>
                                        @endif
                                        @error('pieces_joints_verso')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- SECTION 4: Informations ONMCI -->
                            <div class="form-section row">
                                <h5 class="section-title">Informations ONMCI</h5>

                                <div class="col-lg-6 col-md-6 col-sm-12 col-6">
                                    <div class="rbt-form-group">
                                        <label for="numero_inscription_ONMCI">Numéro d'inscription ONMCI</label>
                                        <input id="numero_inscription_ONMCI" type="text"
                                            name="numero_inscription_ONMCI"
                                            value="{{ old('numero_inscription_ONMCI', $mutualiste->numero_inscription_ONMCI) }}"
                                            class=" @error('numero_inscription_ONMCI') is-invalid @enderror">
                                        @error('numero_inscription_ONMCI')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                {{-- <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="pseudonyme_recon_ONMCI">Pseudonyme reconnaissance ONMCI</label>
                                        <input id="pseudonyme_recon_ONMCI" type="text" name="pseudonyme_recon_ONMCI"
                                            value="{{ old('pseudonyme_recon_ONMCI', $mutualiste->pseudonyme_recon_ONMCI) }}"
                                            class=" @error('pseudonyme_recon_ONMCI') is-invalid @enderror">
                                        @error('pseudonyme_recon_ONMCI')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div> --}}

                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    @php
                                        $extenCart = strtolower(
                                            pathinfo($mutualiste->document_carte_inscript_ONMCI, PATHINFO_EXTENSION),
                                        );
                                    @endphp
                                    <div class="rbt-form-group">
                                        <label for="document_carte_inscript_ONMCI">Carte d'inscription ONMCI</label>
                                        <input id="document_carte_inscript_ONMCI" type="file"
                                            name="document_carte_inscript_ONMCI"
                                            class=" @error('document_carte_inscript_ONMCI') is-invalid @enderror">
                                        @if ($mutualiste->document_carte_inscript_ONMCI)
                                            <div class="mt-2">
                                                @if ($extenCart == 'pdf')
                                                    <a href="{{ route('visualise.pdf', ['fichier' => lienPdf($mutualiste->document_carte_inscript_ONMCI)]) }}"
                                                        target="_blank" class="document-link">
                                                        <i class="fa fa-eye me-1"></i> Voir le document actuel
                                                    </a>
                                                @elseif (in_array($extenCart, ['jpg', 'jpeg', 'png', 'jfif', 'heic', 'heif', 'webp']))
                                                    <a href="{{ asset($mutualiste->document_carte_inscript_ONMCI) }}"
                                                        target="_blank" class="text-primary">
                                                        Voir le document actuel
                                                    </a>
                                                @endif
                                            </div>
                                        @endif
                                        @error('document_carte_inscript_ONMCI')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- SECTION 5: Activité principale -->
                            <div class="form-section row">
                                <h5 class="section-title">Activité principale</h5>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="raison_social_primaire">Raison sociale</label>
                                        <input id="raison_social_primaire" type="text" name="raison_social_primaire"
                                            value="{{ old('raison_social_primaire', $mutualiste->raison_social_primaire) }}"
                                            class=" @error('raison_social_primaire') is-invalid @enderror">
                                        @error('raison_social_primaire')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-6 mb--20 col-lg-6 col-md-6 col-sm-6 col-6">
                                    <label>Spécialité</label>
                                    <div class="rbt-modern-select bg-transparent height-45">
                                        <select class="w-100 @error('specialite_id') is-invalid @enderror"
                                            name="specialite_id" id="specialite_id" autocomplete="specialite_id"
                                            autofocus>
                                            @foreach ($specialites as $specialite)
                                                <option value="{{ $specialite->id }}"
                                                    {{ old('specialite_id', $mutualiste->specialite_id) == $specialite->id ? 'selected' : '' }}>
                                                    {{ $specialite->libelle }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('specialite_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>



                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="fonction">Fonction</label>
                                        <input id="fonction" type="text" name="fonction"
                                            value="{{ old('fonction', $mutualiste->fonction) }}"
                                            class=" @error('fonction') is-invalid @enderror">
                                        @error('fonction')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="date_debut_metier">Date début métier</label>
                                        <input id="date_debut_metier" type="date" name="date_debut_metier"
                                            value="{{ old('date_debut_metier', $mutualiste->date_debut_metier) }}"
                                            class=" @error('date_debut_metier') is-invalid @enderror"
                                            max="{{ date('Y-m-d') }}">
                                        @error('date_debut_metier')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="nombre_annee_experience">Nombre d'années d'expérience</label>
                                        <input id="nombre_annee_experience" type="number" name="nombre_annee_experience"
                                            value="{{ old('nombre_annee_experience', $mutualiste->nombre_annee_experience) }}"
                                            class=" @error('nombre_annee_experience') is-invalid @enderror"
                                            min="0" placeholder="0" readonly>
                                        @error('nombre_annee_experience')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="nom_employeur_principale">Nom employeur principal</label>
                                        <input id="nom_employeur_principale" type="text"
                                            name="nom_employeur_principale"
                                            value="{{ old('nom_employeur_principale', $mutualiste->nom_employeur_principale) }}"
                                            class=" @error('nom_employeur_principale') is-invalid @enderror">
                                        @error('nom_employeur_principale')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="statut_emploi">Statut emploi</label>
                                        <div class="rbt-modern-select bg-transparent height-45">
                                            <select class="w-100 @error('statut_emploi') is-invalid @enderror"
                                                name="statut_emploi" id="statut_emploi" autocomplete="statut_emploi"
                                                autofocus>

                                                <option value="">Sélectionnez...</option>
                                                <option value="Stage"
                                                    {{ old('statut_emploi', $mutualiste->statut_emploi) == 'Stage' ? 'selected' : '' }}>
                                                    Stage</option>
                                                <option value="CDD"
                                                    {{ old('statut_emploi', $mutualiste->statut_emploi) == 'CDD' ? 'selected' : '' }}>
                                                    CDD</option>
                                                <option value="CDI"
                                                    {{ old('statut_emploi', $mutualiste->statut_emploi) == 'CDI' ? 'selected' : '' }}>
                                                    CDI</option>

                                                <option value="Retraité"
                                                    {{ old('statut_emploi', $mutualiste->statut_emploi) == 'Retraité' ? 'selected' : '' }}>
                                                    Retraité</option>
                                                <option value="Vacation"
                                                    {{ old('statut_emploi', $mutualiste->statut_emploi) == 'Vacation' ? 'selected' : '' }}>
                                                    Vacation</option>
                                            </select>
                                            @error('statut_emploi')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>



                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <label for="niveau_intervention" class="form-label required-field">
                                        Niveau D’Intervention
                                    </label>

                                    <div class="rbt-modern-select bg-transparent height-45">
                                        <select
                                            class="form-control form-control-custom select-custom @error('niveau_intervention') is-invalid @enderror"
                                            name="niveau_intervention" id="niveau_intervention" required>

                                            <option value="">Sélectionnez...</option>

                                            <option value="cabinet_medicaux"
                                                {{ old('niveau_intervention', $mutualiste->niveau_intervention) == 'cabinet_medicaux' ? 'selected' : '' }}>
                                                CABINET MEDICAUX
                                            </option>

                                            <option value="centre"
                                                {{ old('niveau_intervention', $mutualiste->niveau_intervention) == 'centre' ? 'selected' : '' }}>
                                                CENTRE
                                            </option>

                                            <option value="clinique"
                                                {{ old('niveau_intervention', $mutualiste->niveau_intervention) == 'clinique' ? 'selected' : '' }}>
                                                CLINIQUE
                                            </option>

                                            <option value="polyclinique"
                                                {{ old('niveau_intervention', $mutualiste->niveau_intervention) == 'polyclinique' ? 'selected' : '' }}>
                                                POLYCLINIQUE
                                            </option>

                                            <option value="centre_imagerie"
                                                {{ old('niveau_intervention', $mutualiste->niveau_intervention) == 'centre_imagerie' ? 'selected' : '' }}>
                                                CENTRE D’IMAGERIE
                                            </option>

                                            <option value="laboratoire"
                                                {{ old('niveau_intervention', $mutualiste->niveau_intervention) == 'laboratoire' ? 'selected' : '' }}>
                                                LABORATOIRE
                                            </option>

                                            <option value="autre"
                                                {{ old('niveau_intervention', $mutualiste->niveau_intervention) == 'autre' ? 'selected' : '' }}>
                                                AUTRE
                                            </option>
                                        </select>
                                    </div>

                                    @error('niveau_intervention')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>


                                <div class="col-md-6" id="div_precise_intervention" style="display: none;">
                                    <label for="precise_intervention" class="form-label">
                                        Préciser Niveau d'intervention
                                    </label>

                                    <input type="text" id="precise_intervention" name="precise_intervention"
                                        value="{{ old('precise_intervention', $mutualiste->precise_intervention) }}"
                                        class="form-control form-control-custom @error('precise_intervention') is-invalid @enderror"
                                        placeholder="Précisez ici">

                                    @error('precise_intervention')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="domaine_activite">Domaine d'activité</label>
                                        <input id="domaine_activite" type="text" name="domaine_activite"
                                            value="{{ old('domaine_activite', $mutualiste->domaine_activite) }}"
                                            class=" @error('domaine_activite') is-invalid @enderror">
                                        @error('domaine_activite')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div> --}}

                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="date_recrutement">Date de recrutement</label>
                                        <input id="date_recrutement" type="date" name="date_recrutement"
                                            value="{{ old('date_recrutement', $mutualiste->date_recrutement) }}"
                                            class=" @error('date_recrutement') is-invalid @enderror">
                                        @error('date_recrutement')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                {{-- <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="montant_cotis_annuel">Montant cotisation annuelle</label>
                                        <input id="montant_cotis_annuel" type="number" step="0.01"
                                            name="montant_cotis_annuel"
                                            value="{{ old('montant_cotis_annuel', $mutualiste->montant_cotis_annuel) }}"
                                            class=" @error('montant_cotis_annuel') is-invalid @enderror">
                                        @error('montant_cotis_annuel')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div> --}}
                            </div>

                            <!-- SECTION 6: Informations entreprise -->
                            <div class="form-section row">
                                <h5 class="section-title">Informations entreprise</h5>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="sigle">Sigle</label>
                                        <input id="sigle" type="text" name="sigle"
                                            value="{{ old('sigle', $mutualiste->sigle) }}"
                                            class=" @error('sigle') is-invalid @enderror">
                                        @error('sigle')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="date_creation">Date de création</label>
                                        <input id="date_creation" type="date" name="date_creation"
                                            value="{{ old('date_creation', $mutualiste->date_creation) }}"
                                            class=" @error('date_creation') is-invalid @enderror">
                                        @error('date_creation')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="numero_autorisation">Numéro d'autorisation</label>
                                        <input id="numero_autorisation" type="text" name="numero_autorisation"
                                            value="{{ old('numero_autorisation', $mutualiste->numero_autorisation) }}"
                                            class=" @error('numero_autorisation') is-invalid @enderror">
                                        @error('numero_autorisation')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="num_immatriculation">Numéro d'immatriculation</label>
                                        <input id="num_immatriculation" type="text" name="num_immatriculation"
                                            value="{{ old('num_immatriculation', $mutualiste->num_immatriculation) }}"
                                            class=" @error('num_immatriculation') is-invalid @enderror">
                                        @error('num_immatriculation')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-6 mb--20 col-lg-6 col-md-6 col-sm-6 col-6">
                                    <label>Forme juridique</label>
                                    <div class="rbt-modern-select bg-transparent height-45">
                                        <select class="w-100 @error('forme_juridique_id') is-invalid @enderror"
                                            name="forme_juridique_id" id="forme_juridique_id"
                                            autocomplete="forme_juridique_id" autofocus>
                                            @foreach ($formeJuridiques as $formeJuridique)
                                                <option value="{{ $formeJuridique->id }}"
                                                    {{ old('forme_juridique_id', $mutualiste->forme_juridique_id) == $formeJuridique->id ? 'selected' : '' }}>
                                                    {{ $formeJuridique->libelle }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('forme_juridique_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-6" id="precise_forme_juridique_block"
                                    style="display: none;">
                                    <div class="rbt-form-group">
                                        <label for="precise_forme_juridique">Précisez forme juridique</label>
                                        <input id="precise_forme_juridique" type="text" name="precise_forme_juridique"
                                            value="{{ old('precise_forme_juridique', $mutualiste->precise_forme_juridique) }}"
                                            class=" @error('precise_forme_juridique') is-invalid @enderror">
                                        @error('precise_forme_juridique')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>



                                <div class="col-6 mb--20 col-md-6 col-sm-6 col-6">
                                    <label>Ville</label>
                                    <div class="rbt-modern-select bg-transparent height-45">
                                        <select class="w-100 @error('ville_id') is-invalid @enderror" name="ville_id"
                                            id="ville_id" autocomplete="ville_id" autofocus>
                                            <option value="">Sélectionnez la ville</option>
                                            @foreach ($villes as $ville)
                                                <option value="{{ $ville->id }}"
                                                    {{ old('ville_id', $mutualiste->ville_id) == $ville->id ? 'selected' : '' }}>
                                                    {{ $ville->libelle }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('ville_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>


                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="commune">Commune</label>
                                        <input id="commune" type="text" name="commune"
                                            value="{{ old('commune', $mutualiste->commune) }}"
                                            class=" @error('commune') is-invalid @enderror">
                                        @error('commune')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="quartier">Quartier</label>
                                        <input id="quartier" type="text" name="quartier"
                                            value="{{ old('quartier', $mutualiste->quartier) }}"
                                            class=" @error('quartier') is-invalid @enderror">
                                        @error('quartier')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="rue">Rue</label>
                                        <input id="rue" type="text" name="rue"
                                            value="{{ old('rue', $mutualiste->rue) }}"
                                            class=" @error('rue') is-invalid @enderror">
                                        @error('rue')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="adresse_postale_entreprise">Adresse postale entreprise</label>
                                        <input id="adresse_postale_entreprise" type="text"
                                            name="adresse_postale_entreprise"
                                            value="{{ old('adresse_postale_entreprise', $mutualiste->adresse_postale_entreprise) }}"
                                            class=" @error('adresse_postale_entreprise') is-invalid @enderror">
                                        @error('adresse_postale_entreprise')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="localisation_entreprise">Localisation entreprise</label>
                                        <input id="localisation_entreprise" type="text" name="localisation_entreprise"
                                            value="{{ old('localisation_entreprise', $mutualiste->localisation_entreprise) }}"
                                            class=" @error('localisation_entreprise') is-invalid @enderror">
                                        @error('localisation_entreprise')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="email_entreprise">Email entreprise</label>
                                        <input id="email_entreprise" type="email" name="email_entreprise"
                                            value="{{ old('email_entreprise', $mutualiste->email_entreprise) }}"
                                            class=" @error('email_entreprise') is-invalid @enderror">
                                        @error('email_entreprise')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="telephone_entreprise">Téléphone entreprise</label>
                                        <input id="telephone_entreprise" type="tel" name="telephone_entreprise"
                                            value="{{ old('telephone_entreprise', $mutualiste->telephone_entreprise) }}"
                                            class=" @error('telephone_entreprise') is-invalid @enderror">
                                        @error('telephone_entreprise')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="fax_entreprise">Fax entreprise</label>
                                        <input id="fax_entreprise" type="text" name="fax_entreprise"
                                            value="{{ old('fax_entreprise', $mutualiste->fax_entreprise) }}"
                                            class=" @error('fax_entreprise') is-invalid @enderror">
                                        @error('fax_entreprise')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    @php
                                        $extenCouver = strtolower(
                                            pathinfo($mutualiste->document_autorisation_ouverture, PATHINFO_EXTENSION),
                                        );
                                    @endphp
                                    <div class="rbt-form-group">
                                        <label for="document_autorisation_ouverture">Document autorisation
                                            d'ouverture</label>
                                        <input id="document_autorisation_ouverture" type="file"
                                            name="document_autorisation_ouverture"
                                            class=" @error('document_autorisation_ouverture') is-invalid @enderror">
                                        @if ($mutualiste->document_autorisation_ouverture)
                                            <div class="mt-2">
                                                @if ($extenCouver == 'pdf')
                                                    <a href="{{ route('visualise.pdf', ['fichier' => lienPdf($mutualiste->document_autorisation_ouverture)]) }}"
                                                        target="_blank" class="document-link">
                                                        <i class="fa fa-eye me-1"></i> Voir le document actuel
                                                    </a>
                                                @elseif (in_array($extenCouver, ['jpg', 'jpeg', 'png', 'jfif', 'heic', 'heif', 'webp']))
                                                    <a href="{{ asset($mutualiste->document_autorisation_ouverture) }}"
                                                        target="_blank" class="text-primary">
                                                        Voir le document actuel
                                                    </a>
                                                @endif
                                            </div>
                                        @endif
                                        @error('document_autorisation_ouverture')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- SECTION 7: Relations -->
                            {{-- <div class="form-section row">
                                <h5 class="section-title">Relations</h5>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="relation_tiers"
                                                id="relation_tiers" value="1"
                                                {{ old('relation_tiers', $mutualiste->relation_tiers) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="relation_tiers">
                                                Relation avec tiers
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="nom_relation">Nom de la relation</label>
                                        <input id="nom_relation" type="text" name="nom_relation"
                                            value="{{ old('nom_relation', $mutualiste->nom_relation) }}"
                                            class=" @error('nom_relation') is-invalid @enderror">
                                        @error('nom_relation')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="etre_auteur"
                                                id="etre_auteur" value="1"
                                                {{ old('etre_auteur', $mutualiste->etre_auteur) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="etre_auteur">
                                                Être auteur
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="nom_auteur">Nom de l'auteur</label>
                                        <input id="nom_auteur" type="text" name="nom_auteur"
                                            value="{{ old('nom_auteur', $mutualiste->nom_auteur) }}"
                                            class=" @error('nom_auteur') is-invalid @enderror">
                                        @error('nom_auteur')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div> --}}

                            <!-- SECTION 8: Travail freelance -->
                            {{-- <div class="form-section row">
                                <h5 class="section-title">Travail freelance</h5>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="raison_social_secondaire_freelance">Raison sociale secondaire</label>
                                        <input id="raison_social_secondaire_freelance" type="text"
                                            name="raison_social_secondaire_freelance"
                                            value="{{ old('raison_social_secondaire_freelance', $mutualiste->raison_social_secondaire_freelance) }}"
                                            class=" @error('raison_social_secondaire_freelance') is-invalid @enderror">
                                        @error('raison_social_secondaire_freelance')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="fonction_occupe_freelance">Fonction occupée</label>
                                        <input id="fonction_occupe_freelance" type="text"
                                            name="fonction_occupe_freelance"
                                            value="{{ old('fonction_occupe_freelance', $mutualiste->fonction_occupe_freelance) }}"
                                            class=" @error('fonction_occupe_freelance') is-invalid @enderror">
                                        @error('fonction_occupe_freelance')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="type_contrat_freelance">Type de contrat</label>
                                        <input id="type_contrat_freelance" type="text" name="type_contrat_freelance"
                                            value="{{ old('type_contrat_freelance', $mutualiste->type_contrat_freelance) }}"
                                            class=" @error('type_contrat_freelance') is-invalid @enderror">
                                        @error('type_contrat_freelance')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="telephone_freelance">Téléphone freelance</label>
                                        <input id="telephone_freelance" type="tel" name="telephone_freelance"
                                            value="{{ old('telephone_freelance', $mutualiste->telephone_freelance) }}"
                                            class=" @error('telephone_freelance') is-invalid @enderror">
                                        @error('telephone_freelance')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="fax_freelance">Fax freelance</label>
                                        <input id="fax_freelance" type="text" name="fax_freelance"
                                            value="{{ old('fax_freelance', $mutualiste->fax_freelance) }}"
                                            class=" @error('fax_freelance') is-invalid @enderror">
                                        @error('fax_freelance')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="localisation_freelance">Localisation freelance</label>
                                        <input id="localisation_freelance" type="text" name="localisation_freelance"
                                            value="{{ old('localisation_freelance', $mutualiste->localisation_freelance) }}"
                                            class=" @error('localisation_freelance') is-invalid @enderror">
                                        @error('localisation_freelance')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="adresse_postale_freelance">Adresse postale freelance</label>
                                        <input id="adresse_postale_freelance" type="text"
                                            name="adresse_postale_freelance"
                                            value="{{ old('adresse_postale_freelance', $mutualiste->adresse_postale_freelance) }}"
                                            class=" @error('adresse_postale_freelance') is-invalid @enderror">
                                        @error('adresse_postale_freelance')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="domaine_activite_freelance">Domaine d'activité freelance</label>
                                        <input id="domaine_activite_freelance" type="text"
                                            name="domaine_activite_freelance"
                                            value="{{ old('domaine_activite_freelance', $mutualiste->domaine_activite_freelance) }}"
                                            class=" @error('domaine_activite_freelance') is-invalid @enderror">
                                        @error('domaine_activite_freelance')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div> --}}

                            <!-- SECTION 9: Documents supplémentaires -->
                            {{-- <div class="form-section row">
                                <h5 class="section-title">Documents supplémentaires</h5>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                    <div class="rbt-form-group">
                                        <label for="photo_identite_1">Photo d'identité</label>
                                        <input id="photo_identite_1" type="file" name="photo_identite_1"
                                            class=" @error('photo_identite_1') is-invalid @enderror">
                                        @if ($mutualiste->photo_identite_1)
                                            <div class="mt-2">
                                                <a href="{{ asset($mutualiste->photo_identite_1) }}" target="_blank"
                                                    class="text-primary">
                                                    Voir la photo actuelle
                                                </a>
                                            </div>
                                        @endif
                                        @error('photo_identite_1')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div> --}}

                            <div class="col-6 mt--20">
                                <div class="rbt-form-group">
                                    <button type="submit" class="rbt-btn btn-gradient">Modifier les informations</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                        <form action="{{ route('mutualistes.changepassword') }}" method="POST"
                            class="rbt-profile-row rbt-default-form row row--15">
                            @csrf
                            <div class="col-6">
                                <div class="rbt-form-group">
                                    <label for="currentpassword">Mot de passe actuel</label>
                                    <input id="password" name="password" type="password"
                                        placeholder="Mot de passe actuel"
                                        class=" @error('password') is-invalid @enderror">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="rbt-form-group">
                                    <label for="currentpassword">Nouveau Mot de passe</label>
                                    <input id="new_password" name="new_password" type="password"
                                        placeholder="Nouveau Mot de passe"
                                        class=" @error('new_password') is-invalid @enderror">
                                    @error('new_password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="rbt-form-group">
                                    <label for="retypenewpassword">Confirmer le nouveau mot de passe</label>
                                    <input id="new_password_confirmation" type="password"
                                        name="new_password_confirmation" placeholder="Confirmer le nouveau mot de passe"
                                        autocomplete="new-password">
                                </div>
                            </div>
                            <div class="col-6 mt--10">
                                <div class="rbt-form-group">
                                    <button type="submit" class="rbt-btn btn-gradient">Modifier Mot de passe</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const formeJuridique = document.getElementById('forme_juridique_id');
            const preciseField = document.getElementById('precise_forme_juridique_block');

            function togglePreciseField() {

                // Récupère le texte de l'option sélectionnée
                const selectedText = formeJuridique.options[formeJuridique.selectedIndex]
                    .text
                    .trim()
                    .toLowerCase();

                // Vérifie si "Autre" est sélectionné
                if (selectedText === 'autre') {
                    preciseField.style.display = 'block';
                } else {
                    preciseField.style.display = 'none';
                    document.getElementById('precise_forme_juridique').value = '';
                }
            }

            // Exécute au chargement
            togglePreciseField();

            // Exécute au changement
            formeJuridique.addEventListener('change', togglePreciseField);

        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const niveauIntervention = document.getElementById('niveau_intervention');
            const divPrecise = document.getElementById('div_precise_intervention');

            function togglePreciseField() {

                if (niveauIntervention.value === 'autre') {
                    divPrecise.style.display = 'block';
                } else {
                    divPrecise.style.display = 'none';
                }
            }

            // Vérification au chargement
            togglePreciseField();

            // Vérification au changement
            niveauIntervention.addEventListener('change', togglePreciseField);

        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const dateDebut = document.getElementById('date_debut_metier');
            const experience = document.getElementById('nombre_annee_experience');

            function calculExperience() {

                if (!dateDebut.value) {
                    experience.value = '';
                    return;
                }

                const debut = new Date(dateDebut.value);
                const aujourdHui = new Date();

                let annees = aujourdHui.getFullYear() - debut.getFullYear();

                // Vérifie si la date anniversaire est passée cette année
                const moisActuel = aujourdHui.getMonth();
                const jourActuel = aujourdHui.getDate();

                const moisDebut = debut.getMonth();
                const jourDebut = debut.getDate();

                if (
                    moisActuel < moisDebut ||
                    (moisActuel === moisDebut && jourActuel < jourDebut)
                ) {
                    annees--;
                }

                // Empêche les valeurs négatives
                experience.value = annees >= 0 ? annees : 0;
            }

            // Calcul automatique au changement
            dateDebut.addEventListener('change', calculExperience);

            // Calcul au chargement si ancienne valeur
            calculExperience();

        });
    </script>

    <script>
        document.getElementById('customButton').addEventListener('click', function(event) {
            event.preventDefault();
            document.getElementById('lien_photo').click();
        });

        document.getElementById('lien_photo').addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imagePreview = document.getElementById('image-preview');
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                    document.getElementById('defaut').style.display = 'none';
                }
                reader.readAsDataURL(file);
            }
        });

        document.getElementById('CouverturePhoto').addEventListener('click', function(event) {
            event.preventDefault();
            document.getElementById('photo_couverture').click();
        });

        document.getElementById('photo_couverture').addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imageCouverture = document.getElementById('image-couverture');
                    imageCouverture.src = e.target.result;
                    imageCouverture.style.display = 'block';
                    document.getElementById('defautCouverture').style.display = 'none';
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush
