@extends('layouts.dashboard', ['title' => 'Formulaire d\'insription - Mutualiste', 'toolbar' => '_toolbar2', 'breadcrumb' => 'Inscription - Mutualiste', 'spinnerMessage' => 'Création du compte mutualiste en cours. Merci de patienter....'])

@push('css')
    <!-- Application vendor css url -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard/cssbundle/dataTables.min.css') }}">
@endpush

@section('content')
    <!-- Spinner container -->
    <div class="row g-3 justify-content-center">
        <div class="col-10">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Formulaire d'insription</h6>
                    <div class="dropdown morphing scale-left">
                        <a href="#" class="card-fullscreen" data-bs-toggle="tooltip" title="Card Full-Screen"><i
                                class="icon-size-fullscreen"></i></a>
                        <a href="{{ route('mutualistes.index') }}" class="btn btn-primary d-inline">Retour</a>
                    </div>
                </div>
                <div class="card-body" id="show_all_mutualiste">
                    <h6 class="fw-bold">Informations du mutualiste</h6>
                    <form action="{{ route('mutualistes.store') }}" method="POST" id="add_mutualiste_form"
                        enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        <div class="row g-3">
                            <div class="col-lg-3 col-md-3">
                                <div class="form-floating">
                                    <input type="text" id="matricule" name="matricule"
                                        class="form-control @error('matricule') is-invalid @enderror"
                                        value="{{ old('matricule') }}" placeholder="Matricule" autocomplete="matricule"
                                        autofocus required>
                                    <label>Entrez le matricule<span class="text-danger fw-bold">*</span></label>
                                    @error('matricule')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4">
                                <div class="form-floating">
                                    <input type="text" id="nom" name="nom"
                                        class="form-control @error('nom') is-invalid @enderror" value="{{ old('nom') }}"
                                        placeholder="Nom" autocomplete="nom" autofocus required>
                                    <label>Entrez le nom<span class="text-danger fw-bold">*</span></label>
                                    @error('nom')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-5 col-md-5">
                                <div class="form-floating">
                                    <input type="text" id="prenom" name="prenom"
                                        class="form-control @error('prenom') is-invalid @enderror"
                                        value="{{ old('prenom') }}" placeholder="Prénom(s)" autocomplete="prenom" autofocus
                                        required>
                                    <label>Entrez le Prénom(s)<span class="text-danger fw-bold">*</span></label>
                                    @error('prenom')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-8">
                                <div class="form-floating">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email') }}" placeholder="Email"
                                        autocomplete="email" autofocus required>
                                    <label>Entrez l'adresse email<span class="text-danger fw-bold">*</span></label>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4">
                                <div class="form-floating">
                                    <div class="form-floating">
                                        <input type="text" class="form-control @error('contact') is-invalid @enderror"
                                            id="contact" name="contact" value="{{ old('contact') }}" minlength="10"
                                            maxlenghth="10" placeholder="Ex: 0777007700" autocomplete="contact" autofocus>
                                        <label>Entrez le contact</label>
                                        @error('contact')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>


                            <div class="col-lg-3 col-md-3">
                                <div class="form-floating">
                                    <select id="specialite_id" name="specialite_id"
                                        class="form-select @error('specialite_id') is-invalid @enderror" required>

                                        <option value="">Sélectionnez une spécialité</option>

                                        @foreach ($specialites as $specialite)
                                            <option value="{{ $specialite->id }}"
                                                {{ old('specialite_id') == $specialite->id ? 'selected' : '' }}>
                                                {{ $specialite->libelle }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <label>Choisissez la spécialité <span class="text-danger fw-bold">*</span></label>

                                    @error('specialite_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4">
                                <div class="form-floating">
                                    <select id="ville_id" name="ville_id"
                                        class="form-select @error('ville_id') is-invalid @enderror" required>

                                        <option value="">Sélectionnez une Ville </option>

                                        @foreach ($villes as $ville)
                                            <option value="{{ $ville->id }}"
                                                {{ old('ville_id') == $ville->id ? 'selected' : '' }}>
                                                {{ $ville->libelle }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <label>Choisissez la Ville </label>

                                    @error('ville_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-5 col-md-5">
                                <div class="form-floating">
                                    <input type="text" id="fonctions" name="fonctions"
                                        class="form-control @error('fonctions') is-invalid @enderror"
                                        value="{{ old('fonctions') }}" placeholder="fonctions" autocomplete="fonctions"
                                        autofocus required>
                                    <label>Entrez la Fonction<span class="text-danger fw-bold">*</span></label>
                                    @error('fonctions')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-lg-6 col-md-6">
                                <label class="fw-bold">
                                    Avez-vous réglé votre droit d’adhésion (incluant la carte membre) ?
                                    <span class="text-danger">*</span>
                                </label>

                                <div class="d-flex gap-4 mt-2">
                                    <div class="form-check">
                                        <input class="form-check-input @error('droit_adhesion') is-invalid @enderror"
                                            type="radio" name="droit_adhesion" id="droit_adhesion_oui" value="1"
                                            {{ old('droit_adhesion') === '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="droit_adhesion_oui">Oui</label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input @error('droit_adhesion') is-invalid @enderror"
                                            type="radio" name="droit_adhesion" id="droit_adhesion_non" value="0"
                                            {{ old('droit_adhesion') === '0' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="droit_adhesion_non">Non</label>
                                    </div>
                                </div>

                                @error('droit_adhesion')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- <div class="col-lg-4 col-md-4">
                                <label class="fw-bold">Avez-vous payé la taxe de la carte membre ? <span
                                        class="text-danger">*</span></label>

                                <div class="d-flex gap-4 mt-2">
                                    <div class="form-check">
                                        <input class="form-check-input @error('carte_membre') is-invalid @enderror"
                                            type="radio" name="carte_membre" id="carte_membre_oui" value="1"
                                            {{ old('carte_membre') === '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="carte_membre_oui">Oui</label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input @error('carte_membre') is-invalid @enderror"
                                            type="radio" name="carte_membre" id="carte_membre_non" value="0"
                                            {{ old('carte_membre') === '0' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="carte_membre_non">Non</label>
                                    </div>
                                </div>

                                @error('carte_membre')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div> --}}

                            <div class="col-lg-6 col-md-6">
                                <label class="fw-bold">Avez-vous payé la cotisation annuelle de l’année en cours ?<span
                                        class="text-danger">*</span></label>

                                <div class="d-flex gap-4 mt-2">
                                    <div class="form-check">
                                        <input class="form-check-input @error('cotisation_annuelle') is-invalid @enderror"
                                            type="radio" name="cotisation_annuelle" id="cotisation_oui" value="1"
                                            {{ old('cotisation_annuelle') === '1' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="cotisation_oui">Oui</label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input @error('cotisation_annuelle') is-invalid @enderror"
                                            type="radio" name="cotisation_annuelle" id="cotisation_non" value="0"
                                            {{ old('cotisation_annuelle') === '0' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="cotisation_non">Non</label>
                                    </div>
                                </div>

                                @error('cotisation_annuelle')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>



                        </div>
                        <div class="mt-3">
                            <p><span class="text-danger fw-bold">*</span> Champs obligatoires.</p>
                        </div>
                        <div class="row g-3 ">
                            <div class="mx-auto d-flex justify-content-center">

                                <button type="reset" class="btn btn-secondary w-25 mx-2">Annuler</button>
                                <button type="submit" id="add_mutualiste_btn"
                                    class="btn btn-primary w-25 mx-2">Enregistrer</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <!-- Plugin Js -->

    <!-- Vendor Script -->
    <script>
        $(document).ready(function() {
            $('#add_mutualiste_form').on('submit', function(e) {
                e.preventDefault(); // Empêche la soumission par défaut du formulaire

                // Masquer le bouton de soumission
                $('#add_mutualiste_btn').prop('disabled', true);

                // Afficher le spinner
                $('#spinner-container').css('display', 'flex');

                // Soumettre le formulaire une fois que le spinner est affiché
                this.submit();
            });
        });
    </script>
@endpush
