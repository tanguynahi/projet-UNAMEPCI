@extends('layouts.home', ['title' => "page d'inscription"])
@section('content')
    <div class="rbt-breadcrumb-default bg-gradient-1">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-inner text-center">
                        <h2 class="title">Inscription</h2>
                        <ul class="page-list">
                            <li class="rbt-breadcrumb-item active">Bienvenue <h1>{{ $mutualiste->nom }}
                                    {{ $mutualiste->prenom }}</h1>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        /* Styles pour afficher l'image en cercle */
        #image-preview {
            width: 168px;
            height: 168px;
            border-radius: 50%;
            object-fit: cover;
            display: none;
            margin-top: 10px;
        }

        .image-container {
            position: relative;
            width: 168px;
            height: 168px;
            border-radius: 40%;
            overflow: hidden;
            cursor: pointer;
        }

        #customButton {
            position: absolute;
        }

        .image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* position: relative; */

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
    </style>
    <form action="{{ route('finaliser.inscription', $mutualiste->id) }}" method="POST" enctype="multipart/form-data">
        @method('put')
        @csrf
        <div class="rbt-elements-area bg-color-white rbt-section-gap">
            <div class="container">
                <div class="row gy-5 row--30">
                    <div class="col-12 mb--20">
                        <div class="image-container">
                            <img id="defaut" src="{{ asset('assets/home/images/profil/profildefaut.jpg') }}"
                                height="100%" width="100%" alt="Image par défaut">
                            <div class="rbt-edit-photo-inner" style="position: absolute; bottom: 10px; right: 10px;">
                                <button class="rbt-edit-photo" title="Modifier photo" id="customButton"
                                    style="background: none; border: none;">
                                    <i class="feather-camera"
                                        style="font-size: 24px; color: black; background-color: rgba(150, 138, 138, 0.767); padding: 5px; border-radius: 50%;"></i>
                                </button>
                            </div>
                            <input type="file" id="lien_photo" name="lien_photo" accept="image/*"
                                class="input-type form-control @error('lien_photo') is-invalid @enderror">
                            <img id="image-preview" src="#" alt="photo de profil" height="100%" width="100%">
                            @error('lien_photo')
                                <span class="invalid-feedback" role="alert">
                                    <strong>
                                        {{ $message }}
                                    </strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                </div>
                <div class="row gy-5 row--30">
                    <div class="col-lg-6">
                        <div id="billing-form">
                            <div class="row">
                                <label for="shiping_address"> Information Personnelle</label>
                                <br><br>
                                <div class="row g-6">
                                    <div class="col-6 mb--20">
                                        <label>Nom</label>
                                        <div id="nom" class="rbt-modern-select bg-transparent height-55 mt-4">
                                            {{ $mutualiste->nom }}</div>
                                    </div>
                                    <div class="col-6 mb--20">
                                        <label>Prénoms</label>

                                        <div id="prenom" class="rbt-modern-select bg-transparent height-55 mt-4">
                                            {{ $mutualiste->prenom }}
                                        </div>
                                    </div>
                                    <div class="col-6 mb--20">
                                        <label for="">Genre <span class="text-danger">*</span></label>
                                        <div class="rbt-modern-select bg-transparent height-45">
                                            <select class="w-100 @error('genre') is-invalid @enderror" name="genre"
                                                id="genre" autocomplete="genre" autofocus>
                                                <option value="">Sélectionnez le genre</option>
                                                <option value="Homme">Homme</option>
                                                <option value="Femme">Femme</option>
                                            </select>
                                            @error('genre')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>
                                                        {{ $message }}
                                                    </strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-6 mb--20">
                                        <label>Né(e) le <span class="text-danger">*</span></label>
                                        <input type="date" placeholder="" id="date_naissance" name="date_naissance"
                                            value="{{ old('date_naissance') }}"required
                                            class="@error('date_naissance') is-invalid @enderror"
                                            autocomplete="date_naissance" autofocus>
                                        @error('date_naissance')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>
                                                    {{ $message }}
                                                </strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-6 mb--20">
                                        <label>A <span class="text-danger">*</span></label>
                                        <input type="text" placeholder="Lieu de Naissance" id="lieu_naissance"
                                            name="lieu_naissance" value="{{ old('lieu_naissance') }}" required
                                            class="@error('lieu_naissance') is-invalid @enderror"
                                            autocomplete="lieu_naissance" autofocus>
                                        @error('lieu_naissance')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>
                                                    {{ $message }}
                                                </strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-6 mb--20">
                                        <label>Type de Pièces <span class="text-danger">*</span></label>
                                        <div class="rbt-modern-select bg-transparent height-45">
                                            <select class="w-100 @error('type_piece_id') is-invalid @enderror"
                                                name="type_piece_id" id="type_piece_id" autocomplete="type_piece_id"
                                                autofocus>
                                                <option value="">Type de pièce</option>
                                                @foreach ($typePieces as $typePiece)
                                                    <option value="{{ $typePiece->id }}">{{ $typePiece->libelle }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('type_piece_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>
                                                        {{ $message }}
                                                    </strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6 mb--20">
                                        <label>N° Pièces d'identité <span class="text-danger">*</span></label>
                                        <input type="text" placeholder="Numero de Pièce" id="numero_piece"
                                            name="numero_piece" value="{{ old('numero_piece') }}"required
                                            class="@error('numero_piece') is-invalid @enderror"
                                            autocomplete="numero_piece" autofocus>
                                        @error('numero_piece')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>
                                                    {{ $message }}
                                                </strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 col-6">
                                        <label>Etablie le <span class="text-danger">*</span></label>
                                        <input type="date" placeholder="20/11/2012" name="date_etablissement_piece"
                                            id="date_etablissement_piece" value="{{ old('date_etablissement_piece') }}"
                                            required class="@error('date_etablissement_piece') is-invalid @enderror"
                                            autocomplete="date_etablissement_piece" autofocus>
                                        @error('date_etablissement_piece')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>
                                                    {{ $message }}
                                                </strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 col-6">
                                        <label>A <span class="text-danger">*</span></label>
                                        <input type="text" placeholder="Lieu d'etablissement"
                                            name="lieu_etablissement_piece" id="lieu_etablissement_piece"
                                            value="{{ old('lieu_etablissement_piece') }}" required
                                            class="@error('lieu_etablissement_piece') is-invalid @enderror"
                                            autocomplete="lieu_etablissement_piece" autofocus>
                                        @error('lieu_etablissement_piece')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>
                                                    {{ $message }}
                                                </strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 col-6">
                                        <label>Charger (Resto & Verso) <span class="text-danger">*</span></label>
                                        <input type="file" name="documents[]" id="documents"
                                            value="{{ old('documents') }}" required
                                            class="@error('documents') is-invalid @enderror"
                                            style="border: 2px solid #e7e4f2; padding: 25px; border-radius: 5px;"
                                            autocomplete="documents" autofocus multiple onchange="checkFileCount()">
                                            <span class="text-danger" id="affichePiece" style="display: none;"> Vous ne pouvez télécharger que 2 fichiers.</span>
                                        @error('documents')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>
                                                    {{ $message }}
                                                </strong>
                                            </span>
                                        @enderror
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        {{-- <div > --}}
                        <div id="billing-form">
                            <div class="row">
                                <label for="shiping_address"> Information Professionnelle</label>
                                <br><br>
                                <div class="row g-6">
                                    <div class="col-6 mb--20">
                                        <label>Matricule <span class="text-danger">*</span></label>
                                        {{-- <input type="text" placeholder="Matricule" id="matricule" name="matricule"
                                            value="{{ $mutualiste->matricule }}" readonly
                                            class="@error('matricule') is-invalid @enderror" disabled>
                                        @error('matricule')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>
                                                    {{ $message }}
                                                </strong>
                                            </span>
                                        @enderror --}}
                                        <div id="matricule" class="rbt-modern-select bg-transparent height-55 mt-4">
                                            {{ $mutualiste->matricule }}
                                        </div>
                                    </div>
                                    <div class="col-6 mb--20">
                                        <label>Unité <span class="text-danger">*</span></label>
                                        <input type="text" placeholder="Unité" id="unite" name="unite"
                                            value="{{ old('unite') }}" required
                                            class="@error('unite') is-invalid @enderror" autocomplete="unite" autofocus>
                                        @error('unite')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>
                                                    {{ $message }}
                                                </strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-6 mb--20">
                                        <label>Grade <span class="text-danger">*</span></label>
                                        <div class="rbt-modern-select bg-transparent height-45">
                                            <select class="w-100 @error('grade_id') is-invalid @enderror" name="grade_id"
                                                id="grade" autocomplete="grade" autofocus>
                                                <option value="">Sélectionnez le grade</option>
                                                @foreach ($grades as $grade)
                                                    <option value="{{ $grade->id }}">{{ $grade->libelle }}
                                                    </option>
                                                @endforeach

                                            </select>
                                            @error('grade_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>
                                                        {{ $message }}
                                                    </strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-6 mb--20">
                                        <label>Corps <span class="text-danger">*</span></label>
                                        <div class="rbt-modern-select bg-transparent height-45">
                                            <select class="w-100 @error('corp_id') is-invalid @enderror" name="corp_id"
                                                id="corp" autocomplete="corp_id" autofocus>
                                                <option value="">Sélectionnez le corps</option>
                                                @foreach ($corps as $corp)
                                                    <option value="{{ $corp->id }}">{{ $corp->libelle }}</option>
                                                @endforeach
                                            </select>
                                            @error('corp_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>
                                                        {{ $message }}
                                                    </strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="col-6 mb--20">
                                        <label>Ville <span class="text-danger">*</span></label>
                                        <div class="rbt-modern-select bg-transparent height-45">
                                            <select class="w-100 @error('ville_id') is-invalid @enderror" name="ville_id"
                                                id="ville_id" autocomplete="ville_id" autofocus>
                                                <option value="">Sélectionnez la ville</option>
                                                @foreach ($villes as $ville)
                                                    <option value="{{ $ville->id }}">{{ $ville->libelle }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('ville_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>
                                                        {{ $message }}
                                                    </strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="col-6 mb--20">
                                        <label>Adresse <span class="text-danger">*</span></label>
                                        <input type="text" placeholder="Cocody, Angre 7eme tranche " id="adresse"
                                            name="adresse" value="{{ old('adresse') }}"
                                            class="@error('adresse') is-invalid @enderror" required
                                            autocomplete="adresse" autofocus>
                                        @error('adresse')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>
                                                    {{ $message }}
                                                </strong>
                                            </span>
                                        @enderror
                                    </div>


                                    <div class="col-6 mb--20">
                                        <label>Contact <span class="text-danger">*</span></label>
                                        @if ($mutualiste->contact)
                                            <input type="text" placeholder="Entre votre numero de Telephone"
                                                id="contact" name="contact" value="{{ $mutualiste->contact }}"
                                                readonly class="@error('contact') is-invalid @enderror">
                                            @error('contact')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>
                                                        {{ $message }}
                                                    </strong>
                                                </span>
                                            @enderror
                                        @else
                                            <input type="text" placeholder="Entre votre numero de Telephone"
                                                id="contact" name="contact" value="{{ old('contact') }}" required
                                                class="@error('contact') is-invalid @enderror" required
                                                autocomplete="contact" autofocus>
                                            @error('contact')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>
                                                        {{ $message }}
                                                    </strong>
                                                </span>
                                            @enderror
                                        @endif

                                    </div>
                                    <div class="col-6 mb--20">
                                        <label>Contact 2</label>
                                        <input type="text" placeholder="Entre votre numero de Telephone"
                                            id="contact_2" name="contact_2" value="{{ old('contact_2') }}"
                                            class="@error('contact_2') is-invalid @enderror" autocomplete="contact_2"
                                            autofocus>
                                        @error('contact_2')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>
                                                    {{ $message }}
                                                </strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label>Mot de Passe <span class="text-danger">*</span></label>
                                        <input type="password" placeholder="XXXXXXXXX" name="password" id="password"
                                            class="@error('password') is-invalid @enderror" required>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>
                                                    {{ $message }}
                                                </strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label>Confirmez mot de passe <span class="text-danger">*</span></label>
                                        <input type="password" placeholder="XXXXXXXXXX" name="password_confirmation"
                                            id="password_confirmation" required autocomplete="new-password">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="col-12 mt--20 text-center">
                    <div class="rbt-form-group">
                        <span>
                            <button type="submit" class="rbt-btn text-center btn-primary"
                                style="padding-left:20%;padding-right:20%;">Valider</button>
                        </span>
                    </div>

                </div>
            </div>
        </div>

    </form>


@endsection
@push('js')
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        document.getElementById('lien_photo').addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imagePreview = document.getElementById('image-preview');
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';

                    // document.getElementById('lien_photo').style.display = 'none';
                    document.getElementById('defaut').style.display = 'none';

                }
                reader.readAsDataURL(file);
            }
        });
    </script>
    <script>
        function checkFileCount() {
            var input = document.getElementById('documents');
            var test = document.getElementById('affichePiece');
            if (input.files.length > 2) {
                // alert('Vous ne pouvez télécharger que 2 fichiers.');
                test.style.display = 'block';
                input.value = '';
            }
        }
    </script>
@endpush
