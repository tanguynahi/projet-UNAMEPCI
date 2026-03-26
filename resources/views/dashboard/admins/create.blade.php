@extends('layouts.dashboard', [
    'title' => "Formulaire d'inscription - Administrateur",
    'toolbar' => '_toolbar2',
    'breadcrumb' => 'Inscription - Administrateur'
])

@push('css')
    <!-- Application vendor css url -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard/cssbundle/dataTables.min.css') }}">
    <style>
        /* Styles personnalisés */
        .form-card {
            border: none;
            box-shadow: 0 0 20px rgba(0,0,0,0.08);
        }

        .form-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 1rem 1.5rem;
            border-radius: 12px 12px 0 0;
            color: white;
        }

        .form-floating {
            margin-bottom: 0.5rem;
            position: relative;
        }

        .form-floating > label {
            font-weight: 500;
            color: #6c757d;
        }

        .form-floating > .form-control,
        .form-floating > .form-select {
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .form-floating > .form-control:focus,
        .form-floating > .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-floating > .form-control.is-invalid:focus,
        .form-floating > .form-select.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }

        /* Style pour le champ mot de passe avec icône */
        .password-wrapper {
            position: relative;
            width: 100%;
        }

        .password-wrapper .form-control {
            padding-right: 45px;
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
            z-index: 10;
            background: transparent;
            border: none;
            padding: 0;
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }

        .toggle-password:hover {
            color: #667eea;
        }

        .toggle-password:focus {
            outline: none;
        }

        /* Adaptation pour form-floating */
        .form-floating .password-wrapper {
            height: 100%;
        }

        .form-floating .toggle-password {
            top: 50%;
            right: 12px;
        }

        /* Message d'erreur pour confirmation mot de passe */
        .password-match-error {
            color: #dc2626;
            font-size: 0.8rem;
            margin-top: 0.25rem;
            display: none;
        }

        .password-match-error.show {
            display: block;
        }

        .is-invalid-custom {
            border-color: #dc2626 !important;
        }

        /* Section photo avec aperçu */
        .photo-upload {
            background: #f8fafc;
            border-radius: 12px;
            padding: 1.5rem;
            border: 1px dashed #cbd5e1;
            transition: all 0.3s ease;
        }

        .photo-upload:hover {
            border-color: #667eea;
            background: #f1f5f9;
        }

        .photo-label {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
            display: block;
        }

        /* Aperçu de l'image */
        .photo-preview-container {
            margin-top: 1rem;
            text-align: center;
            display: none;
        }

        .photo-preview {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #667eea;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .photo-preview:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 16px rgba(0,0,0,0.15);
        }

        .photo-info {
            margin-top: 0.5rem;
            font-size: 0.75rem;
            color: #6c757d;
        }

        .remove-photo {
            margin-top: 0.5rem;
            background: #dc2626;
            color: white;
            border: none;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .remove-photo:hover {
            background: #b91c1c;
            transform: translateY(-1px);
        }

        /* Boutons d'action */
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .btn-custom {
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-custom:hover {
            transform: translateY(-2px);
        }

        .btn-custom:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        /* Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card-body {
            animation: fadeIn 0.4s ease forwards;
        }

        @keyframes zoomIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .photo-preview {
            animation: zoomIn 0.3s ease forwards;
        }

        /* Modal personnalisée */
        .modal-custom {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.3s ease;
        }

        .modal-custom.show {
            display: flex;
        }

        .modal-content-custom {
            background: white;
            border-radius: 16px;
            max-width: 450px;
            width: 90%;
            padding: 1.5rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            animation: zoomIn 0.3s ease;
        }

        .modal-header-custom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .modal-header-custom h5 {
            margin: 0;
            color: #1f2937;
            font-weight: 600;
        }

        .modal-close {
            cursor: pointer;
            font-size: 1.5rem;
            color: #6c757d;
            transition: all 0.3s ease;
        }

        .modal-close:hover {
            color: #dc2626;
        }

        .modal-body-custom {
            margin-bottom: 1.5rem;
            color: #374151;
        }

        .modal-footer-custom {
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            padding-top: 0.5rem;
            border-top: 1px solid #e5e7eb;
        }

        /* Loader */
        .loader-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 10000;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            gap: 1rem;
        }

        .loader-overlay.show {
            display: flex;
        }

        .loader-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        .loader-text {
            color: white;
            font-size: 1rem;
            font-weight: 500;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .action-buttons {
                flex-direction: column;
            }

            .btn-custom {
                justify-content: center;
            }
        }

        /* Style pour le select */
        .form-select {
            cursor: pointer;
        }

        /* Message d'information */
        .required-message {
            background: #fef9e3;
            border-left: 4px solid #f59e0b;
            padding: 0.75rem;
            border-radius: 8px;
            margin-top: 1rem;
        }

        /* Style pour les erreurs */
        .invalid-feedback {
            font-size: 0.8rem;
            margin-top: 0.25rem;
        }

        /* Amélioration des champs */
        .form-control, .form-select {
            font-size: 0.95rem;
        }

        /* Style pour le titre */
        .card-title i {
            margin-right: 0.5rem;
        }

        /* Force de mot de passe */
        .password-strength {
            margin-top: 0.5rem;
            font-size: 0.75rem;
        }

        .strength-bar {
            height: 3px;
            background: #e2e8f0;
            border-radius: 3px;
            margin-top: 0.25rem;
            transition: all 0.3s ease;
        }

        .strength-bar .progress {
            height: 100%;
            border-radius: 3px;
            transition: width 0.3s ease;
        }
    </style>
@endpush

@section('content')
    <div class="row g-3 justify-content-center">
        <div class="col-12 col-lg-10 col-xl-9">
            <div class="card form-card">
                <!-- En-tête -->
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <h6 class="card-title mb-0">
                        <i class="bx bx-user-plus"></i> Formulaire d'inscription
                    </h6>
                    <div class="d-flex gap-2">
                        <a href="#" class="card-fullscreen" data-bs-toggle="tooltip" title="Plein écran">
                            <i class="icon-size-fullscreen"></i>
                        </a>
                        <a href="{{ route('administrateurs.index') }}" class="btn btn-primary btn-sm">
                            <i class="bx bx-arrow-back"></i> Retour
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="form-header mb-4">
                        <h5 class="mb-1 text-white">
                            <i class="bx bx-info-circle"></i> Informations de l'administrateur
                        </h5>
                        <p class="mb-0 text-white-50">
                            Veuillez remplir tous les champs obligatoires pour créer un nouvel administrateur
                        </p>
                    </div>

                    <form action="{{ route('administrateurs.store') }}"
                          method="POST"
                          id="add_admin_form"
                          enctype="multipart/form-data"
                          class="needs-validation"
                          novalidate>
                        @csrf

                        <div class="row g-4">
                            <!-- Identité -->
                            <div class="col-12">
                                <h6 class="fw-bold mb-3">
                                    <i class="bx bx-user-circle"></i> Identité
                                </h6>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text"
                                           id="nom"
                                           name="nom"
                                           class="form-control @error('nom') is-invalid @enderror"
                                           value="{{ old('nom') }}"
                                           placeholder="Nom"
                                           autocomplete="family-name"
                                           required>
                                    <label>
                                        Nom <span class="text-danger fw-bold">*</span>
                                    </label>
                                    @error('nom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text"
                                           id="prenom"
                                           name="prenom"
                                           class="form-control @error('prenom') is-invalid @enderror"
                                           value="{{ old('prenom') }}"
                                           placeholder="Prénom(s)"
                                           autocomplete="given-name"
                                           required>
                                    <label>
                                        Prénom(s) <span class="text-danger fw-bold">*</span>
                                    </label>
                                    @error('prenom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Localisation et rôle -->
                            <div class="col-12 mt-3">
                                <h6 class="fw-bold mb-3">
                                    <i class="bx bx-map-pin"></i> Localisation & Rôle
                                </h6>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select @error('ville_id') is-invalid @enderror"
                                            id="ville_id"
                                            name="ville_id"
                                            autocomplete="address-level2"
                                            required>
                                        <option value="">Sélectionner une ville</option>
                                        @foreach ($villes as $ville)
                                            <option value="{{ $ville->id }}" {{ old('ville_id') == $ville->id ? 'selected' : '' }}>
                                                {{ $ville->libelle }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label>
                                        Ville <span class="text-danger fw-bold">*</span>
                                    </label>
                                    @error('ville_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select id="role_id"
                                            name="role_id"
                                            class="form-select @error('role_id') is-invalid @enderror"
                                            required>
                                        <option value="">Sélectionnez un rôle</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}"
                                                {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label>
                                        Rôle <span class="text-danger fw-bold">*</span>
                                    </label>
                                    @error('role_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Coordonnées -->
                            <div class="col-12 mt-3">
                                <h6 class="fw-bold mb-3">
                                    <i class="bx bx-phone-call"></i> Coordonnées
                                </h6>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text"
                                           class="form-control @error('adresse') is-invalid @enderror"
                                           id="adresse"
                                           name="adresse"
                                           value="{{ old('adresse') }}"
                                           placeholder="Adresse"
                                           autocomplete="street-address">
                                    <label>Adresse</label>
                                    @error('adresse')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="tel"
                                           class="form-control @error('contact') is-invalid @enderror"
                                           id="contact"
                                           name="contact"
                                           value="{{ old('contact') }}"
                                           minlength="10"
                                           maxlength="10"
                                           placeholder="Ex: 0777007700"
                                           autocomplete="tel"
                                           required>
                                    <label>
                                        Contact <span class="text-danger fw-bold">*</span>
                                    </label>
                                    @error('contact')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select @error('genre') is-invalid @enderror"
                                            id="genre"
                                            name="genre"
                                            required>
                                        <option value="">Sélectionnez le genre</option>
                                        <option value="Homme" {{ old('genre') == 'Homme' ? 'selected' : '' }}>Homme</option>
                                        <option value="Femme" {{ old('genre') == 'Femme' ? 'selected' : '' }}>Femme</option>
                                    </select>
                                    <label>
                                        Genre <span class="text-danger fw-bold">*</span>
                                    </label>
                                    @error('genre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           id="email"
                                           name="email"
                                           value="{{ old('email') }}"
                                           placeholder="Email"
                                           autocomplete="email"
                                           required>
                                    <label>
                                        Email <span class="text-danger fw-bold">*</span>
                                    </label>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Sécurité avec œil pour mot de passe -->
                            <div class="col-12 mt-3">
                                <h6 class="fw-bold mb-3">
                                    <i class="bx bx-lock-alt"></i> Sécurité
                                </h6>
                            </div>

                            <div class="col-md-12">
                                <div class="form-floating">
                                    <div class="password-wrapper">
                                        <input type="password"
                                               class="form-control @error('password') is-invalid @enderror"
                                               id="password"
                                               name="password"
                                               placeholder="Mot de passe"
                                               autocomplete="new-password"
                                               required>
                                        <button type="button" class="toggle-password" data-target="password">
                                            <i class="bx bx-hide"></i>
                                        </button>
                                    </div>
                                    <label>
                                        Mot de passe <span class="text-danger fw-bold">*</span>
                                    </label>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="password-strength">
                                    <small class="text-muted">Le mot de passe doit contenir au moins 8 caractères</small>
                                    <div class="strength-bar">
                                        <div class="progress" style="width: 0%; height: 100%; background: #dc2626;"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Confirmation mot de passe avec validation en temps réel -->
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <div class="password-wrapper">
                                        <input type="password"
                                               class="form-control"
                                               id="password_confirmation"
                                               name="password_confirmation"
                                               placeholder="Confirmer le mot de passe"
                                               autocomplete="new-password"
                                               required>
                                        <button type="button" class="toggle-password" data-target="password_confirmation">
                                            <i class="bx bx-hide"></i>
                                        </button>
                                    </div>
                                    <label>
                                        Confirmer le mot de passe <span class="text-danger fw-bold">*</span>
                                    </label>
                                    <div class="password-match-error" id="passwordMatchError">
                                        <i class="bx bx-error-circle"></i> Les mots de passe ne correspondent pas
                                    </div>
                                </div>
                            </div>

                            <!-- Photo avec aperçu -->
                            <div class="col-12 mt-3">
                                <h6 class="fw-bold mb-3">
                                    <i class="bx bx-image"></i> Photo de profil
                                </h6>
                            </div>

                            <div class="col-12">
                                <div class="photo-upload">
                                    <label for="formFile" class="photo-label">
                                        <i class="bx bx-cloud-upload"></i> Photo
                                    </label>
                                    <input class="form-control @error('lien_photo') is-invalid @enderror"
                                           id="formFile"
                                           name="lien_photo"
                                           type="file"
                                           accept="image/jpeg,image/png,image/gif,image/jpg">
                                    <small class="text-muted">Formats acceptés : JPG, PNG, GIF (Max 2MB)</small>

                                    <!-- Conteneur pour l'aperçu de l'image -->
                                    <div class="photo-preview-container" id="photoPreviewContainer">
                                        <img class="photo-preview" id="photoPreview" src="#" alt="Aperçu de la photo">
                                        <div class="photo-info">
                                            <span id="photoName"></span>
                                            <span id="photoSize"></span>
                                        </div>
                                        <button type="button" class="remove-photo" id="removePhoto">
                                            <i class="bx bx-trash"></i> Supprimer
                                        </button>
                                    </div>

                                    @error('lien_photo')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Message champs obligatoires -->
                        <div class="required-message mt-4">
                            <i class="bx bx-info-circle"></i>
                            <span class="text-danger fw-bold">*</span> Champs obligatoires
                        </div>

                        <!-- Boutons d'action -->
                        <div class="action-buttons mt-4">
                            <button type="reset" class="btn btn-secondary btn-custom">
                                <i class="bx bx-reset"></i> Annuler
                            </button>
                            <button type="button" id="submitBtn" class="btn btn-primary btn-custom">
                                <i class="bx bx-save"></i> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmation -->
    <div id="confirmModal" class="modal-custom">
        <div class="modal-content-custom">
            <div class="modal-header-custom">
                <h5>
                    <i class="bx bx-question-mark"></i> Confirmation
                </h5>
                <span class="modal-close" id="closeModal">&times;</span>
            </div>
            <div class="modal-body-custom">
                <p>Êtes-vous sûr de vouloir enregistrer cet administrateur ?</p>
                <p class="text-muted small">Vérifiez bien les informations avant de confirmer.</p>
            </div>
            <div class="modal-footer-custom">
                <button type="button" class="btn btn-secondary" id="cancelModalBtn">Annuler</button>
                <button type="button" class="btn btn-primary" id="confirmModalBtn">
                    <i class="bx bx-check"></i> Confirmer
                </button>
            </div>
        </div>
    </div>

    <!-- Loader -->
    <div id="loaderOverlay" class="loader-overlay">
        <div class="loader-spinner"></div>
        <div class="loader-text">Enregistrement en cours...</div>
    </div>
@endsection

@push('js')
    <!-- Plugin Js -->
    <script src="{{ asset('assets/dashboard/js/bundle/dataTables.bundle.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Data Table initialization (si nécessaire)
            var langUrl = "{{ asset('DataTables/fr-FR.json') }}";

            if ($('#myTable').length) {
                $('#myTable').addClass('nowrap').dataTable({
                    responsive: true,
                    searching: true,
                    paging: true,
                    ordering: true,
                    info: false,
                    language: {
                        url: langUrl,
                    },
                    lengthMenu: [
                        [5, 10, 25, 50, 100],
                    ],
                    dom: 'Bfrtip',
                    buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                });
            }

            // Variables
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('password_confirmation');
            const passwordMatchError = document.getElementById('passwordMatchError');
            const submitBtn = document.getElementById('submitBtn');
            const form = document.getElementById('add_admin_form');
            const confirmModal = document.getElementById('confirmModal');
            const loaderOverlay = document.getElementById('loaderOverlay');
            const closeModal = document.getElementById('closeModal');
            const cancelModalBtn = document.getElementById('cancelModalBtn');
            const confirmModalBtn = document.getElementById('confirmModalBtn');

            let isPasswordValid = false;

            // Validation en temps réel de la confirmation du mot de passe
            function validatePasswordMatch() {
                const password = passwordInput.value;
                const confirmPassword = confirmPasswordInput.value;

                if (confirmPassword.length > 0) {
                    if (password !== confirmPassword) {
                        confirmPasswordInput.classList.add('is-invalid-custom');
                        passwordMatchError.classList.add('show');
                        isPasswordValid = false;
                        return false;
                    } else {
                        confirmPasswordInput.classList.remove('is-invalid-custom');
                        passwordMatchError.classList.remove('show');
                        isPasswordValid = true;
                        return true;
                    }
                } else {
                    confirmPasswordInput.classList.remove('is-invalid-custom');
                    passwordMatchError.classList.remove('show');
                    isPasswordValid = true;
                    return true;
                }
            }

            // Écouter les changements sur les deux champs de mot de passe
            if (passwordInput && confirmPasswordInput) {
                passwordInput.addEventListener('input', validatePasswordMatch);
                confirmPasswordInput.addEventListener('input', validatePasswordMatch);
            }

            // Gestion de l'icône œil pour afficher/masquer le mot de passe
            $('.toggle-password').on('click', function() {
                const targetId = $(this).data('target');
                const input = $('#' + targetId);
                const icon = $(this).find('i');

                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    icon.removeClass('bx-hide').addClass('bx-show');
                } else {
                    input.attr('type', 'password');
                    icon.removeClass('bx-show').addClass('bx-hide');
                }
            });

            // Force du mot de passe
            const strengthBar = document.querySelector('.strength-bar .progress');

            if (passwordInput) {
                passwordInput.addEventListener('input', function() {
                    const password = this.value;
                    let strength = 0;

                    if (password.length >= 8) strength++;
                    if (password.match(/[a-z]+/)) strength++;
                    if (password.match(/[A-Z]+/)) strength++;
                    if (password.match(/[0-9]+/)) strength++;
                    if (password.match(/[$@#&!]+/)) strength++;

                    const percentage = (strength / 5) * 100;
                    strengthBar.style.width = percentage + '%';

                    if (percentage <= 20) {
                        strengthBar.style.background = '#dc2626';
                    } else if (percentage <= 40) {
                        strengthBar.style.background = '#f59e0b';
                    } else if (percentage <= 60) {
                        strengthBar.style.background = '#eab308';
                    } else if (percentage <= 80) {
                        strengthBar.style.background = '#10b981';
                    } else {
                        strengthBar.style.background = '#22c55e';
                    }

                    // Revalider la confirmation
                    validatePasswordMatch();
                });
            }

            // Gestion de l'aperçu de l'image
            const photoInput = document.getElementById('formFile');
            const photoPreview = document.getElementById('photoPreview');
            const photoPreviewContainer = document.getElementById('photoPreviewContainer');
            const photoNameSpan = document.getElementById('photoName');
            const photoSizeSpan = document.getElementById('photoSize');
            const removePhotoBtn = document.getElementById('removePhoto');

            if (photoInput) {
                photoInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];

                    if (file) {
                        // Vérification du type de fichier
                        const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
                        if (!validTypes.includes(file.type)) {
                            alert('Veuillez sélectionner une image valide (JPG, PNG, GIF)');
                            this.value = '';
                            photoPreviewContainer.style.display = 'none';
                            return;
                        }

                        // Vérification de la taille du fichier (2MB max)
                        if (file.size > 2 * 1024 * 1024) {
                            alert('La taille de l\'image ne doit pas dépasser 2MB');
                            this.value = '';
                            photoPreviewContainer.style.display = 'none';
                            return;
                        }

                        // Affichage de l'aperçu
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            photoPreview.src = e.target.result;
                            photoPreviewContainer.style.display = 'block';

                            // Affichage des informations du fichier
                            const fileName = file.name.length > 30 ? file.name.substring(0, 27) + '...' : file.name;
                            const fileSize = (file.size / 1024).toFixed(2) + ' KB';
                            photoNameSpan.textContent = fileName;
                            photoSizeSpan.textContent = fileSize;
                        };
                        reader.readAsDataURL(file);
                    } else {
                        photoPreviewContainer.style.display = 'none';
                    }
                });
            }

            // Bouton pour supprimer la photo
            if (removePhotoBtn) {
                removePhotoBtn.addEventListener('click', function() {
                    photoInput.value = '';
                    photoPreview.src = '#';
                    photoPreviewContainer.style.display = 'none';
                    photoNameSpan.textContent = '';
                    photoSizeSpan.textContent = '';
                });
            }

            // Validation du formulaire avant d'ouvrir la modale
            function validateFormBeforeSubmit() {
                // Vérifier la confirmation du mot de passe
                if (!isPasswordValid && confirmPasswordInput.value.length > 0) {
                    alert('Les mots de passe ne correspondent pas.');
                    confirmPasswordInput.focus();
                    return false;
                }

                // Vérifier la longueur du mot de passe
                if (passwordInput.value.length < 8) {
                    alert('Le mot de passe doit contenir au moins 8 caractères.');
                    passwordInput.focus();
                    return false;
                }

                // Vérifier les champs requis
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;

                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                if (!isValid) {
                    alert('Veuillez remplir tous les champs obligatoires.');
                    return false;
                }

                return true;
            }

            // Bouton d'enregistrement - ouvre la modale
            if (submitBtn) {
                submitBtn.addEventListener('click', function(e) {
                    e.preventDefault();

                    if (validateFormBeforeSubmit()) {
                        confirmModal.classList.add('show');
                    }
                });
            }

            // Fermer la modale
            function closeModalFunction() {
                confirmModal.classList.remove('show');
            }

            if (closeModal) {
                closeModal.addEventListener('click', closeModalFunction);
            }

            if (cancelModalBtn) {
                cancelModalBtn.addEventListener('click', closeModalFunction);
            }

            // Clic en dehors de la modale pour fermer
            window.addEventListener('click', function(e) {
                if (e.target === confirmModal) {
                    closeModalFunction();
                }
            });

            // Confirmation et envoi du formulaire
            if (confirmModalBtn) {
                confirmModalBtn.addEventListener('click', function() {
                    // Fermer la modale
                    closeModalFunction();

                    // Afficher le loader
                    loaderOverlay.classList.add('show');

                    // Désactiver le bouton d'envoi
                    submitBtn.disabled = true;

                    // Soumettre le formulaire
                    form.submit();
                });
            }

            // Validation du numéro de téléphone
            const contact = document.getElementById('contact');
            if (contact) {
                contact.addEventListener('input', function(e) {
                    this.value = this.value.replace(/[^0-9]/g, '');
                    if (this.value.length > 10) {
                        this.value = this.value.slice(0, 10);
                    }
                });
            }

            // Confirmation avant réinitialisation
            const resetButton = document.querySelector('button[type="reset"]');
            if (resetButton) {
                resetButton.addEventListener('click', function(e) {
                    if (!confirm('Êtes-vous sûr de vouloir annuler toutes les modifications ?')) {
                        e.preventDefault();
                    } else {
                        // Réinitialiser l'aperçu de l'image
                        setTimeout(() => {
                            if (photoInput) {
                                photoInput.value = '';
                                photoPreview.src = '#';
                                if (photoPreviewContainer) {
                                    photoPreviewContainer.style.display = 'none';
                                }
                            }
                            // Réinitialiser la validation du mot de passe
                            if (confirmPasswordInput) {
                                confirmPasswordInput.classList.remove('is-invalid-custom');
                                passwordMatchError.classList.remove('show');
                            }
                        }, 100);
                    }
                });
            }

            // Désactiver l'envoi si les mots de passe ne correspondent pas
            if (confirmPasswordInput) {
                confirmPasswordInput.addEventListener('input', function() {
                    if (submitBtn) {
                        if (passwordInput.value !== confirmPasswordInput.value && confirmPasswordInput.value.length > 0) {
                            // Optionnel: désactiver le bouton
                        }
                    }
                });
            }
        });
    </script>
@endpush
