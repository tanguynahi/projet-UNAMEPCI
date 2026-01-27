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
            /* border-radius: 50%; */
            object-fit: cover;
            display: none;
            margin-top: 10px;
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
                                <div class="tutor-bg-photo bg_image  height-245" {{-- style="background-image: url('{{ asset($mutualiste->photo_couverture) }}')" --}}>
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
                                <!-- End Tutor Information  -->
                            </div>
                            <!-- Start Profile Row  -->
                            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="rbt-form-group">
                                    <label for="firstname">Matricule</label>
                                    <input id="matricule" type="text" name="matricule"
                                        value="{{ old('matricule', $mutualiste->matricule) }}"
                                        class="@error('matricule') is-invalid @enderror" readonly>
                                    @error('matricule')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>
                                                {{ $message }}
                                            </strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6 mb--20 col-lg-6 col-md-6 col-sm-6 col-12">
                                <label for="">Genre</label>
                                <div class="rbt-modern-select bg-transparent height-45">
                                    <select class="w-100 @error('genre') is-invalid @enderror" name="genre"
                                        id="genre" autocomplete="genre" autofocus>
                                        <option value="{{ $mutualiste->genre }}"
                                            {{ old('genre', $mutualiste->genre) == $mutualiste->genre ? 'selected' : '' }}>
                                            {{ $mutualiste->genre }}</option>
                                        @foreach (['Homme', 'Femme'] as $value)
                                            @if ($value != $mutualiste->genre)
                                                <option value="{{ $value }}"
                                                    {{ old('genre') == $value ? 'selected' : '' }}>{{ $value }}
                                                </option>
                                            @endif
                                        @endforeach
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
                            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="rbt-form-group">
                                    <label for="firstname">Nom</label>
                                    <input id="nom" name="nom" type="text"
                                        value="{{ old('nom', $mutualiste->nom) }}"
                                        class=" @error('nom') is-invalid @enderror" required>
                                    @error('nom')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>
                                                {{ $message }}
                                            </strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="rbt-form-group">
                                    <label for="lastname">Prenoms</label>
                                    <input id="prenom" name="prenom" type="text"
                                        value="{{ old('prenom', $mutualiste->prenom) }}"
                                        class=" @error('prenom') is-invalid @enderror" required>
                                    @error('prenom')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>
                                                {{ $message }}
                                            </strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="rbt-form-group">
                                    <label for="lastname">date de naissance</label>
                                    <input id="date_naissance" type="date"
                                        value="{{ old('date_naissance', $mutualiste->date_naissance) }}"
                                        name="date_naissance" class=" @error('date_naissance') is-invalid @enderror">
                                    @error('date_naissance')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>
                                                {{ $message }}
                                            </strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="rbt-form-group">
                                    <label for="lastname">lieu de naissance</label>
                                    <input id="lieu_naissance" type="text"
                                        value="{{ old('lieu_naissance', $mutualiste->lieu_naissance) }}"
                                        name="lieu_naissance" class=" @error('lieu_naissance') is-invalid @enderror">
                                    @error('lieu_naissance')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>
                                                {{ $message }}
                                            </strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6 mb--20 col-lg-6 col-md-6 col-sm-6 col-12">
                                <label>Corps*</label>
                                <div class="rbt-modern-select bg-transparent height-45">
                                    <select class="w-100 @error('corp_id') is-invalid @enderror" name="corp_id"
                                        id="corp" autocomplete="corp_id" autofocus>
                                        @foreach ($corps as $corp)
                                            <option value="{{ $corp->id }}"
                                                {{ old('corp_id', $mutualiste->corp_id) == $corp->id ? 'selected' : '' }}>
                                                {{ $corp->libelle }}</option>
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
                            <div class="col-6 mb--20 col-lg-6 col-md-6 col-sm-6 col-12">
                                <label>Grade*</label>
                                <div class="rbt-modern-select bg-transparent height-45">
                                    <select class="w-100 @error('grade_id') is-invalid @enderror" name="grade_id"
                                        id="grade" autocomplete="grade" autofocus>
                                        @foreach ($grades as $grade)
                                            <option value="{{ $grade->id }}"
                                                {{ old('grade_id', $mutualiste->grade_id) == $grade->id ? 'selected' : '' }}>
                                                {{ $grade->libelle }}</option>
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
                            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="rbt-form-group">
                                    <label for="phonenumber">Unité</label>
                                    <input id="unite" type="text" name="unite"
                                        value="{{ old('unite', $mutualiste->unite) }}"
                                        class=" @error('unite') is-invalid @enderror">
                                    @error('unite')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>
                                                {{ $message }}
                                            </strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="rbt-form-group">
                                    <label for="firstname">email</label>
                                    <input id="email" name="email" type="text"
                                        value="{{ old('email', $mutualiste->email) }}"
                                        class=" @error('email') is-invalid @enderror" required>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>
                                                {{ $message }}
                                            </strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="rbt-form-group">
                                    <label for="phonenumber">Telephone 1</label>
                                    <input id="contact" type="tel" name="contact"
                                        value="{{ old('contact', $mutualiste->contact) }}"
                                        class=" @error('contact') is-invalid @enderror">
                                    @error('contact')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>
                                                {{ $message }}
                                            </strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="rbt-form-group">
                                    <label for="phonenumber">Telephone 2</label>
                                    <input id="contact_2" type="tel" name="contact_2"
                                        value="{{ old('contact_2', $mutualiste->contact_2) }}"
                                        class=" @error('contact_2') is-invalid @enderror">
                                    @error('contact_2')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>
                                                {{ $message }}
                                            </strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6 mb--20 col-lg-6 col-md-6 col-sm-6 col-12">
                                <label>Type de Pièces*</label>
                                <div class="rbt-modern-select bg-transparent height-45">
                                    <select class="w-100 @error('type_piece_id') is-invalid @enderror"
                                        name="type_piece_id" id="type_piece_id" autocomplete="type_piece_id" autofocus>

                                        @foreach ($typePieces as $typePiece)
                                            <option value="{{ $typePiece->id }}"
                                                {{ old('type_piece_id', $mutualiste->type_piece_id) == $typePiece->id ? 'selected' : '' }}>
                                                {{ $typePiece->libelle }}</option>
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

                            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="rbt-form-group">
                                    <label for="skill">N° de Piece d'identite</label>
                                    <input id="numero_piece" type="text" name="numero_piece"
                                        value="{{ old('numero_piece', $mutualiste->numero_piece) }}"
                                        class=" @error('numero_piece') is-invalid @enderror">
                                    @error('numero_piece')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>
                                                {{ $message }}
                                            </strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="rbt-form-group">
                                    <label for="skill">Etablie le</label>
                                    <input id="date_etablissement_piece" type="date" name="date_etablissement_piece"
                                        value="{{ old('date_etablissement_piece', $mutualiste->date_etablissement_piece) }}"
                                        class=" @error('date_etablissement_piece') is-invalid @enderror">
                                    @error('date_etablissement_piece')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>
                                                {{ $message }}
                                            </strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="rbt-form-group">
                                    <label for="skill">Lieu d'etablissement de la pièce</label>
                                    <input id="lieu_etablissement_piece" type="text" name="lieu_etablissement_piece"
                                        value="{{ old('lieu_etablissement_piece', $mutualiste->lieu_etablissement_piece) }}"
                                        class=" @error('lieu_etablissement_piece') is-invalid @enderror">
                                    @error('lieu_etablissement_piece')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>
                                                {{ $message }}
                                            </strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            @php
                                $docs = $mutualiste->documents;
                                $documents = json_decode($docs, true);
                            @endphp
                            @foreach ($documents as $index => $document)
                                @php
                                    $extension = strtolower(pathinfo($document, PATHINFO_EXTENSION));
                                @endphp
                                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="rbt-form-group">
                                        <label for="documents">Document {{ $index === 0 ? 'resto' : 'verso' }}</label>
                                        @if (is_array($document))
                                            @foreach ($document as $img)
                                                @if ($extension == 'pdf')
                                                    <a href="{{ asset($img) }}" class="text-primary" target="_target">
                                                        <img src="{{ asset('assets/home/images/message/PDF_file_icon.svg.png') }}"
                                                            style="height: 30px; width:30px;" alt="PDF icon">
                                                        <br>
                                                        fichier(s) joints
                                                    </a>
                                                @elseif (in_array($extension, ['jpg', 'jpeg', 'png']))
                                                    <a href="{{ asset($img) }}" class="text-primary" target="_target">
                                                        <img src="{{ asset($img) }}" style="height: 40px; width:50px;"
                                                            alt="image jointe">
                                                    </a>
                                                @else
                                                    <a href="{{ asset($img) }}" class="text-danger" target="_target">
                                                        <img src="{{ asset('assets/home/images/message/inconnu.png') }}"
                                                            style="height: 30px; width:30px;" alt="default icon"> <br>
                                                        <i class="fa fa-exclamation-circle"></i>
                                                        fichier joints
                                                    </a>
                                                @endif
                                            @endforeach
                                        @else
                                            @if ($extension == 'pdf')
                                                <a href="{{ asset($document) }}" class="text-primary" target="_target">
                                                    <img src="{{ asset('assets/home/images/message/PDF_file_icon.svg.png') }}"
                                                        style="height: 30px; width:30px;" alt="PDF icon">
                                                    <br>
                                                    fichier(s) joints
                                                </a>
                                            @elseif (in_array($extension, ['jpg', 'jpeg', 'png']))
                                                <a href="{{ asset($document) }}" class="text-primary" target="_target">
                                                    <img src="{{ asset($document) }}" style="height: 40px; width:50px;"
                                                        alt="image jointe">
                                                </a>
                                            @else
                                                <a href="{{ asset($document) }}" class="text-danger" target="_target">
                                                    <img src="{{ asset('assets/home/images/message/inconnu.png') }}"
                                                        style="height: 30px; width:30px;" alt="default icon"> <br>
                                                    <i class="fa fa-exclamation-circle"></i>
                                                    fichier joints
                                                </a>
                                            @endif
                                        @endif
                                        <input id="documents_{{ $index }}" type="file" name="documents[]"
                                            class=" @error('documents.' . $index) is-invalid @enderror" multiple
                                            onchange="checkFileCount(this)">

                                        @error('documents.' . $index)
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            @endforeach

                            <div class="col-6 mb--20 col-md-6 col-sm-6 col-12">
                                <label>Ville</label>
                                <div class="rbt-modern-select bg-transparent height-45">
                                    <select class="w-100 @error('ville_id') is-invalid @enderror" name="ville_id"
                                        id="ville_id" autocomplete="ville_id" autofocus>
                                        <option value="">Sélectionnez la ville</option>
                                        @foreach ($villes as $ville)
                                            <option value="{{ $ville->id }}"
                                                {{ old('ville_id', $mutualiste->ville_id) == $ville->id ? 'selected' : '' }}>
                                                {{ $ville->libelle }}</option>
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
                            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="rbt-form-group">
                                    <label for="skill">Adresse</label>
                                    <input id="adresse" name="adresse" type="text"
                                        value="{{ old('adresse', $mutualiste->adresse) }}"
                                        class=" @error('adresse') is-invalid @enderror">
                                    @error('adresse')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>
                                                {{ $message }}
                                            </strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-12 mt--20">
                                <div class="rbt-form-group">
                                    <button type="submit" class="rbt-btn btn-gradient">Modifier
                                        l'information</button>
                                </div>
                            </div>
                        </form>
                        <!-- End Profile Row  -->
                    </div>
                    <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                        <!-- Start Profile Row  -->
                        <form action="{{ route('mutualistes.changepassword') }}" method="POST"
                            class="rbt-profile-row rbt-default-form row row--15">
                            {{-- @method('post') --}}
                            @csrf
                            <div class="col-12">
                                <div class="rbt-form-group">
                                    <label for="currentpassword">Mot de passe actuel</label>
                                    <input id="password" name="password" type="password"
                                        placeholder="Mot de passe actuel"
                                        value="{{ old('password', $mutualiste->password) }}"
                                        class=" @error('password') is-invalid @enderror">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>
                                                {{ $message }}
                                            </strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="rbt-form-group">
                                    <label for="currentpassword">Nouveau Mot de passe actuel</label>
                                    <input id="new_password" name="new_password" type="password"
                                        placeholder="Nouveau Mot de passe actuel"
                                        value="{{ old('new_password', $mutualiste->new_password) }}"
                                        class=" @error('new_password') is-invalid @enderror">
                                    @error('new_password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>
                                                {{ $message }}
                                            </strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="rbt-form-group">
                                    <label for="retypenewpassword">Re-taper le nouveau mot de passe</label>
                                    <input id="new_password_confirmation" type="password"
                                        name="new_password_confirmation" placeholder="Re-taper le nouveau mot de passe"
                                        autocomplete="new-password">
                                </div>
                            </div>
                            <div class="col-12 mt--10">
                                <div class="rbt-form-group">
                                    <button type="submit" class="rbt-btn btn-gradient">Modifier Mot de passe</button>
                                </div>
                            </div>
                        </form>
                        <!-- End Profile Row  -->
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
@push('js')
    <script>
        function checkFileCount(input) {
            const maxFiles = 1; // Limite maximale de fichiers
            if (input.files.length > maxFiles) {
                alert('Vous ne pouvez télécharger qu\'un maximum de ' + maxFiles + ' fichiers.');
                input.value = '';
            }
        }
    </script>
    <script>
        document.getElementById('customButton').addEventListener('click', function(event) {
            event.preventDefault(); // Empêche la soumission du formulaire
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
    </script>
    <script>
        document.getElementById('CouverturePhoto').addEventListener('click', function(event) {
            event.preventDefault(); // Empêche la soumission du formulaire
            document.getElementById('photo_couverture').click();
        });
        document.getElementById('photo_couverture').addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imageCouverture = document.getElementById('image-couverture');
                    const defautCouverture = document.getElementById('defautCouverture');

                    imageCouverture.src = e.target.result;
                    imageCouverture.style.display = 'block';
                    document.getElementById('defautCouverture').style.display = 'none';
                    // defautCouverture.style.display = 'none';
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush
