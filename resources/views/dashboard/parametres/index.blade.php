@extends('layouts.dashboard', ['title' => 'Paramètres', 'toolbar' => '_toolbar2', 'breadcrumb' => 'Paramètres'])

@push('css')
    <!-- plugin css file  -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard/cssbundle/summernote.min.css') }}" />
@endpush

@section('content')
    <div class="row g-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Paramètres</h6>
                    <div class="dropdown morphing scale-left">
                        <a href="#" class="card-fullscreen" data-bs-toggle="tooltip" title="Card Full-Screen"><i
                                class="icon-size-fullscreen"></i></a>
                        <a href="{{ route('dashboard') }}" class="btn btn-primary d-inline">Retour</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-lg-4 col-md-4">
            <div class="list-group list-group-custom sticky-top me-xl-4" style="top: 100px;">
                <a class="list-group-item list-group-item-action" href="#list-item-1">Mon profil</a>
                <a class="list-group-item list-group-item-action" href="#list-item-2">Changer Mot de passe</a>
                <a class="list-group-item list-group-item-action" href="#list-item-3">Informations du site</a>
                <a class="list-group-item list-group-item-action" href="#list-item-4">Réseaux sociaux</a>
            </div>
        </div>
        <div class="col-xxl-8 col-lg-8 col-md-8">
            <div id="list-item-1" class="card fieldset border border-muted mt-5">
                <!-- form: profile details -->
                <span class="fieldset-tile text-muted bg-body">Détail du profil:</span>
                <form action="#" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-3">
                                <label class="col-form-label">Photo</label>
                                <div class="col-md-6 col-sm-6">
                                    @if (Auth::check() && auth()->user()->administrateur->lien_photo)
                                        <div class="image-input avatar xxl rounded-4"
                                            style="background-image: url({{ auth()->user()->administrateur->lien_photo }})">
                                            <div class="avatar-wrapper rounded-4"
                                                style="background-image: url(url({{ auth()->user()->administrateur->lien_photo }})">
                                            </div>
                                            <div class="file-input">
                                                <input type="file"
                                                    class="form-control @error('lien_photo') is-invalid @enderror"
                                                    name="lien_photo" id="lien_photo">
                                                <label for="lien_photo" class="fa fa-pencil shadow text-muted"></label>
                                                @error('lien_photo')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    @else
                                        <div class="image-input avatar xxl rounded-4"
                                            style="background-image: url({{ asset('../assets/icons/user2.png') }})">
                                            <div class="avatar-wrapper rounded-4"
                                                style="background-image: url({{ asset('../assets/icons/user2.png') }})">
                                            </div>
                                            <div class="file-input">
                                                <input type="file" class="form-control" name="lien_photo"
                                                    id="lien_photo">
                                                <label for="lien_photo" class="fa fa-pencil shadow text-muted"></label>
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <div class="form-floating">
                                        <input type="text" id="nom" name="nom"
                                            class="form-control @error('nom') is-invalid @enderror"
                                            value="{{ old('nom', $administrateur->nom) }}" placeholder="Nom"
                                            autocomplete="nom" autofocus required>
                                        <label>Entrez le nom<span class="text-danger fw-bold">*</span></label>
                                        @error('nom')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <div class="form-floating">
                                        <input type="text" id="prenom" name="prenom"
                                            class="form-control @error('prenom') is-invalid @enderror"
                                            value="{{ old('prenom', $administrateur->prenom) }}" placeholder="Prénom(s)"
                                            autocomplete="prenom" autofocus required>
                                        <label>Entrez le Prénom(s)<span class="text-danger fw-bold">*</span></label>
                                        @error('prenom')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <div class="form-floating">
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email"
                                            value="{{ old('email', $administrateur->email) }}" placeholder="Email"
                                            autocomplete="email" autofocus required>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <label>Entrez une adresse email<span class="text-danger fw-bold">*</span></label>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <div class="form-floating">
                                        <div class="form-floating">
                                            <input type="text"
                                                class="form-control @error('contact') is-invalid @enderror" id="contact"
                                                name="contact" value="{{ old('contact', $administrateur->contact) }}"
                                                minlength="10" maxlenghth="10" placeholder="Ex: 0777007700"
                                                autocomplete="contact" autofocus required>
                                            @error('contact')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <label>Entrez un contact<span class="text-danger fw-bold">*</span></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 mb-3">
                                    <div class="form-floating">
                                        <select class="form-select form-control @error('genre') is-invalid @enderror"
                                            id="genre" name="genre" autocomplete="genre" autofocus required>
                                            <option value="">Sélectionnez le genre</option>
                                            @foreach (['Homme', 'Femme'] as $genre)
                                                <option value="{{ $genre }} "
                                                    {{ old('genre', $administrateur->genre) == $genre ? 'selected' : '' }}>
                                                    {{ $genre }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('genre')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <label for="floatingSelect">Genre<span
                                                class="text-danger fw-bold">*</span></label>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3 mb-3">
                                    <div class="form-floating">
                                        <select class="form-select form-control @error('ville_id') is-invalid @enderror"
                                            id="ville_id" name="ville_id" autocomplete="ville_id" autofocus required>
                                            <option value="">Sélectionner une ville</option>
                                            @foreach ($villes as $ville)
                                                <option value="{{ $ville->id }} "
                                                    {{ old('ville_id', $administrateur->ville_id) == $ville->id ? 'selected' : '' }}>
                                                    {{ $ville->libelle }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('ville_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <label for="floatingSelect">Ville<span
                                                class="text-danger fw-bold">*</span></label>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <div class="form-floating">
                                        <div class="form-floating">
                                            <input type="text"
                                                class="form-control @error('adresse') is-invalid @enderror" id="adresse"
                                                name="adresse" value="{{ old('adresse', $administrateur->adresse) }}"
                                                placeholder="adresse" autocomplete="adresse" autofocus>
                                            @error('adresse')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <label>Entrez une adresse</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <a href="{{ route('dashboard') }}" class="btn btn-lg btn-light me-2">Annuler</a>
                            <button class="btn btn-lg btn-primary" type="submit">Enregistrer</button>
                        </div>
                    </div>
                </form>
            </div>
            <div id="list-item-2" class="card fieldset border border-muted mt-5">
                <!-- form: Change Password -->
                <span class="fieldset-tile text-muted bg-body">Changer le mot de passe</span>
                <form action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-lg-4 col-md-12">
                                    <div class="form-group">
                                        <input type="text" class="form-control"
                                            value="{{ $administrateur->nom }} {{ $administrateur->prenom }}" disabled
                                            placeholder="Nom et prénoms">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-12">
                                    <div class="form-group">
                                        <input type="email" name="email" id="email" class="form-control"
                                            value="{{ old('email', $administrateur->email) }}" disabled
                                            placeholder="Email">
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-12">
                                    <div class="form-group">
                                        <input type="contact" value="{{ old('email', $administrateur->contact) }}"
                                            class="form-control" disabled placeholder="Contact">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <h6 class="border-top pt-2 mt-2 mb-3">Changer le mot de passe</h6>
                                    <div class="mb-3">
                                        <input type="password" name="password" class="form-control form-control-lg  @error('password') is-invalid @enderror"
                                            placeholder="Mot de passe actuel">
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="mb-1">
                                                <input type="password" name="new_password" id="new_password"
                                                    class="form-control form-control-lg  @error('new_password') is-invalid @enderror" min="6"
                                                    placeholder="Nouveau mot de passe">
                                                @error('new_password')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div>
                                                <input type="password" name="password_confirmation" id="password-confirm"
                                                    class="form-control form-control-lg  @error('password_confirmation') is-invalid @enderror" min="6"
                                                    placeholder="Confirmer le nouveau mot de passe" required autocomplete="password_confirmation">
                                                @error('password_confirmation')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <a href="{{ route('dashboard') }}" class="btn btn-lg btn-light me-2">Annuler</a>
                            <button class="btn btn-lg btn-primary" type="submit">Enregistrer</button>
                        </div>
                    </div>
                </form>
            </div>
            <div id="list-item-3" class="card fieldset border border-muted mt-5">
                <!-- form: profile details -->
                <span class="fieldset-tile text-muted bg-body">Infos site:</span>
                <form action="{{ route('parametre.update', $parametre->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6 col-sm-6">
                                    <div class="row">
                                        <label class="col-form-label">Logo du site</label>
                                        <div class="col-6">
                                            @if (!empty($parametre->lien_logo))
                                                <div class="image-input avatar xxl rounded-4"
                                                    style="background-image: url({{ $parametre->lien_logo }})">
                                                    <div class="avatar-wrapper rounded-4"
                                                        style="background-image: url(url({{ $parametre->lien_logo }})">
                                                    </div>
                                                    <div class="file-input">
                                                        <input type="file"
                                                            class="form-control @error('lien_logo') is-invalid @enderror"
                                                            name="lien_logo" id="lien_logo">
                                                        <label for="lien_logo"
                                                            class="fa fa-pencil shadow text-muted"></label>
                                                    </div>
                                                    @error('lien_logo')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            @else
                                                <div class="image-input avatar xxl rounded-4"
                                                    style="background-image: url({{ asset('../assets/dashboard/img/default-img.pngg') }})">
                                                    <div class="avatar-wrapper rounded-4"
                                                        style="background-image: url({{ asset('../assets/dashboard/img/default-img.png') }})">
                                                    </div>
                                                    <div class="file-input">
                                                        <input type="file"
                                                            class="form-control @error('lien_logo') is-invalid @enderror"
                                                            name="lien_logo" id="lien_logo">
                                                        <label for="lien_logo"
                                                            class="fa fa-pencil shadow text-muted"></label>
                                                    </div>
                                                    @error('lien_logo')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <div class="row">
                                        <label class="col-form-label">Photo du Directeur</label>
                                        <div class="col-6">
                                            @if (!empty($parametre->lien_photo_directeur))
                                                <div class="image-input avatar xxl rounded-4"
                                                    style="background-image: url({{ $parametre->lien_photo_directeur }})">
                                                    <div class="avatar-wrapper rounded-4"
                                                        style="background-image: url(url({{ $parametre->lien_photo_directeur }})">
                                                    </div>
                                                    <div class="file-input">
                                                        <input type="file"
                                                            class="form-control @error('lien_photo_directeur') is-invalid @enderror"
                                                            name="lien_photo_directeur" id="lien_photo_directeur">
                                                        <label for="lien_photo_directeur"
                                                            class="fa fa-pencil shadow text-muted"></label>
                                                    </div>
                                                    @error('lien_photo_directeur')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            @else
                                                <div class="image-input avatar xxl rounded-4"
                                                    style="background-image: url({{ asset('../assets/dashboard/img/default-img.pngg') }})">
                                                    <div class="avatar-wrapper rounded-4"
                                                        style="background-image: url({{ asset('../assets/dashboard/img/default-img.png') }})">
                                                    </div>
                                                    <div class="file-input">
                                                        <input type="file"
                                                            class="form-control @error('lien_photo_directeur') is-invalid @enderror"
                                                            name="lien_photo_directeur" id="lien_photo_directeur">
                                                        <label for="lien_photo_directeur"
                                                            class="fa fa-pencil shadow text-muted"></label>
                                                    </div>
                                                    @error('lien_photo_directeur')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <div class="form-floating">
                                        <input type="text" id="nom_site_web" name="nom_site_web"
                                            class="form-control @error('nom_site_web') is-invalid @enderror"
                                            value="{{ old('nom_site_web', $parametre->nom_site_web) }}"
                                            placeholder="Nom de la platforme" autocomplete="nom_site_web" autofocus
                                            required>
                                        <label>Entrez le nom de la plateforme<span
                                                class="text-danger fw-bold">*</span></label>
                                        @error('nom_site_web')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <div class="form-floating">
                                        <div class="form-floating">
                                            <input type="text"
                                                class="form-control @error('adresse_site') is-invalid @enderror"
                                                id="adresse_site" name="adresse_site"
                                                value="{{ old('adresse_site', $parametre->adresse) }}" placeholder="adresse"
                                                autocomplete="adresse_site" autofocus>
                                            @error('adresse_site')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <label>Entrez une adresse</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <div class="form-floating">
                                        <div class="form-floating">
                                            <input type="text"
                                                class="form-control @error('contact_1') is-invalid @enderror"
                                                id="contact_1" name="contact_1"
                                                value="{{ old('contact_1', $parametre->contact_1) }}" minlength="10"
                                                maxlenghth="10" placeholder="Ex: 0777007700" autocomplete="contact_1"
                                                autofocus required>
                                            @error('contact_1')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <label>Entrez le contact 1<span class="text-danger fw-bold">*</span></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <div class="form-floating">
                                        <div class="form-floating">
                                            <input type="text"
                                                class="form-control @error('contact_2') is-invalid @enderror"
                                                id="contact_2" name="contact_2"
                                                value="{{ old('contact_2', $parametre->contact_2) }}" minlength="10"
                                                maxlenghth="10" placeholder="Ex: 0777007700" autocomplete="contact_2"
                                                autofocus>
                                            @error('contact_2')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <label>Entrez le contact 2<span class="text-danger fw-bold"></span></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <div class="form-floating">
                                        <input type="email_1" class="form-control @error('email_1') is-invalid @enderror"
                                            id="email_1" name="email_1"
                                            value="{{ old('email_1', $parametre->email_1) }}" placeholder="Email 1"
                                            autocomplete="email_1" autofocus required>
                                        @error('email_1')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <label>Entrez l'email 1<span class="text-danger fw-bold">*</span></label>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <div class="form-floating">
                                        <input type="email_2" class="form-control @error('email_2') is-invalid @enderror"
                                            id="email_1" name="email_2"
                                            value="{{ old('email_2', $parametre->email_2) }}" placeholder="Email 2"
                                            autocomplete="email_2" autofocus>
                                        @error('email_2')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <label>Entrez l'email 2<span class="text-danger fw-bold"></span></label>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <div class="form-floating">
                                        <input type="url" id="lien_video" name="lien_video"
                                            class="form-control @error('lien_video') is-invalid @enderror"
                                            value="{{ old('lien_video', $parametre->lien_video) }}"
                                            placeholder="Lien-Vidéo de présentation" autocomplete="lien_video" autofocus>
                                        <label for="lien_video">Lien-Vidéo de présentation<span
                                                class="text-danger fw-bold"></span></label>
                                        @error('lien_video')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <div class="form-floating">
                                        <input type="url" id="lien_google_map" name="lien_google_map"
                                            class="form-control @error('lien_google_map') is-invalid @enderror"
                                            value="{{ old('lien_google_map', $parametre->lien_google_map) }}"
                                            placeholder="Lien google map" autocomplete="lien_google_map" autofocus>
                                        <label for="lien_google_map">Lien google map<span
                                                class="text-danger fw-bold"></span></label>
                                        @error('lien_google_map')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12">
                                    <label for="mot_du_directeur" class="form-label">Mot du Directeur<span
                                            class="text-danger fw-bold">*</span></label>
                                    <textarea id="summernote" name="mot_du_directeur"
                                        class="form-control no-resize @error('mot_du_directeur') is-invalid @enderror" autocomplete="mot_du_directeur"
                                        autofocus required>{{ old('mot_du_directeur', $parametre->mot_du_directeur) }}</textarea>
                                    @error('mot_du_directeur')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <a href="{{ route('dashboard') }}" class="btn btn-lg btn-light me-2">Annuler</a>
                            <input class="btn btn-lg btn-primary" type="submit" value="Enregistrer">
                        </div>
                    </div>
                </form>
            </div>
            <div id="list-item-4" class="card fieldset border border-muted mt-5">
                <!-- form: Social Profiles -->
                <span class="fieldset-tile text-muted bg-body">Réseaux sociaux</span>
                <form action="{{ route('parametre.update.lien.reseaux', $parametre->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="card">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <label class="form-label">Facebook</label>
                                    <input type="text" name="lien_facebook" id="lien_facebook" value="{{ old('lien_facebook',$parametre->lien_facebook) }}"
                                        class="form-control form-control-lg @error('lien_facebook') is-invalid @enderror">
                                    @error('lien_facebook')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <label class="form-label">Twitter</label>
                                    <input type="text" name="lien_twitter" id="lien_twitter" value="{{ old('lien_twitter',$parametre->lien_twitter) }}"
                                        class="form-control form-control-lg @error('lien_twitter') is-invalid @enderror">
                                    @error('lien_twitter')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <label class="form-label">Instagram</label>
                                    <input type="text" name="lien_instagram" id="lien_instagram" value="{{ old('lien_instagram',$parametre->lien_instagram) }}"
                                        class="form-control form-control-lg @error('lien_instagram') is-invalid @enderror">
                                    @error('lien_instagram')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <label class="form-label">LinkedIn</label>
                                    <input type="text" name="lien_linkedin" id="lien_linkedin" value="{{ old('lien_linkedin',$parametre->lien_linkedin) }}"
                                        class="form-control form-control-lg @error('lien_instagram') is-invalid @enderror">
                                    @error('lien_linkedin')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <label class="form-label">Youtube</label>
                                    <input type="text" name="lien_youtube" id="lien_youtube" value="{{ old('lien_youtube',$parametre->lien_youtube) }}"
                                        class="form-control form-control-lg  @error('lien_youtube') is-invalid @enderror">
                                    @error('lien_youtube')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <label class="form-label">WhatsApp</label>
                                    <input type="text" name="lien_whatsapp" id="lien_whatsapp"  value="{{ old('lien_whatsapp',$parametre->lien_whatsapp) }}"
                                        class="form-control form-control-lg  @error('lien_whatsapp') is-invalid @enderror">
                                    @error('lien_whatsapp')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <a href="{{ route('dashboard') }}" class="btn btn-lg btn-light me-2">Annuler</a>
                            <button class="btn btn-lg btn-primary" type="submit">Enregistrer</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <!-- Plugin Js -->
    <script src="{{ asset('assets/dashboard/js/bundle/summernote.bundle.js') }}"></script>
    <!-- Vendor Script -->
    <script>
        $(document).ready(function() {
            $('#summernote').summernote({
                height: 150,
                placeholder: 'Mot du Directeur'
            });
            $('.note-editor .note-btn').on('click', function() {
                $(this).next().toggleClass("show");
            });
        });
    </script>
@endpush
