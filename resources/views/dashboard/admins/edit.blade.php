@extends('layouts.dashboard', [
    'title' => "Modifier l'administrateur - {{ $administrateur->nom }} {{ $administrateur->prenom }}",
    'toolbar' => '_toolbar2',
    'breadcrumb' => 'Administrateurs / Modifier',
])

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/dashboard/cssbundle/dataTables.min.css') }}">
    <style>
        /* Vos styles existants (identiques) */
        .form-card {
            border: none;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.08);
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

        .form-floating>label {
            font-weight: 500;
            color: #6c757d;
        }

        .form-floating>.form-control,
        .form-floating>.form-select {
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .form-floating>.form-control:focus,
        .form-floating>.form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

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
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .photo-preview:hover {
            transform: scale(1.05);
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
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .remove-photo:hover {
            background: #b91c1c;
        }

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
        }

        .modal-header-custom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .modal-close {
            cursor: pointer;
            font-size: 1.5rem;
            color: #6c757d;
        }

        .modal-close:hover {
            color: #dc2626;
        }

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

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .required-message {
            background: #fef9e3;
            border-left: 4px solid #f59e0b;
            padding: 0.75rem;
            border-radius: 8px;
            margin-top: 1rem;
        }

        .current-photo {
            margin-bottom: 1rem;
            text-align: center;
        }

        .current-photo img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #667eea;
        }

        @media (max-width: 768px) {
            .action-buttons {
                flex-direction: column;
            }

            .btn-custom {
                justify-content: center;
            }
        }
    </style>
@endpush

@section('content')
    <div class="row g-3 justify-content-center">
        <div class="col-12 col-lg-10 col-xl-9">
            <div class="card form-card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <h6 class="card-title mb-0">
                        <i class="bx bx-edit-alt"></i> Modification de l'administrateur
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
                            <i class="bx bx-info-circle"></i> Modifier les informations
                        </h5>
                        <p class="mb-0 text-white-50">
                            Modifiez les champs nécessaires.
                        </p>
                    </div>

                    <form action="{{ route('administrateurs.update', $administrateur->id) }}" method="POST"
                        id="edit_admin_form" enctype="multipart/form-data" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="row g-4">
                            <!-- Identité -->
                            <div class="col-12">
                                <h6 class="fw-bold mb-3">
                                    <i class="bx bx-user-circle"></i> Identité
                                </h6>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" id="nom" name="nom"
                                        class="form-control @error('nom') is-invalid @enderror"
                                        value="{{ old('nom', $administrateur->nom) }}" placeholder="Nom" required>
                                    <label>Nom <span class="text-danger fw-bold">*</span></label>
                                    @error('nom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" id="prenom" name="prenom"
                                        class="form-control @error('prenom') is-invalid @enderror"
                                        value="{{ old('prenom', $administrateur->prenom) }}" placeholder="Prénom(s)"
                                        required>
                                    <label>Prénom(s) <span class="text-danger fw-bold">*</span></label>
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
                                    <select class="form-select @error('ville_id') is-invalid @enderror" id="ville_id"
                                        name="ville_id" required>
                                        <option value="">Sélectionner une ville</option>
                                        @foreach ($villes as $ville)
                                            <option value="{{ $ville->id }}"
                                                {{ old('ville_id', $administrateur->ville_id) == $ville->id ? 'selected' : '' }}>
                                                {{ $ville->libelle }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label>Ville <span class="text-danger fw-bold">*</span></label>
                                    @error('ville_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select id="role_id" name="role_id"
                                        class="form-select @error('role_id') is-invalid @enderror" required>

                                        <option value="">Sélectionnez un rôle</option>

                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}"
                                                {{ old('role_id', $administrateur->user->roles->first()?->id) == $role->id ? 'selected' : '' }}>
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
                                    <input type="text" class="form-control @error('adresse') is-invalid @enderror"
                                        id="adresse" name="adresse"
                                        value="{{ old('adresse', $administrateur->adresse) }}" placeholder="Adresse">
                                    <label>Adresse</label>
                                    @error('adresse')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="tel" class="form-control @error('contact') is-invalid @enderror"
                                        id="contact" name="contact"
                                        value="{{ old('contact', $administrateur->contact) }}" minlength="10"
                                        maxlength="10" placeholder="Ex: 0777007700" required>
                                    <label>Contact <span class="text-danger fw-bold">*</span></label>
                                    @error('contact')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select class="form-select @error('genre') is-invalid @enderror" id="genre"
                                        name="genre" required>
                                        <option value="">Sélectionnez le genre</option>
                                        <option value="Homme"
                                            {{ old('genre', $administrateur->genre) == 'Homme' ? 'selected' : '' }}>Homme
                                        </option>
                                        <option value="Femme"
                                            {{ old('genre', $administrateur->genre) == 'Femme' ? 'selected' : '' }}>Femme
                                        </option>
                                    </select>
                                    <label>Genre <span class="text-danger fw-bold">*</span></label>
                                    @error('genre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email', $administrateur->email) }}"
                                        placeholder="Email" required>
                                    <label>Email <span class="text-danger fw-bold">*</span></label>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Photo de profil -->
                            <div class="col-12 mt-3">
                                <h6 class="fw-bold mb-3">
                                    <i class="bx bx-image"></i> Photo de profil
                                </h6>
                            </div>

                            <div class="col-12">
                                <div class="photo-upload">
                                    @if ($administrateur->lien_photo)
                                        <div class="current-photo">
                                            <p class="mb-1">Photo actuelle :</p>
                                            <img src="{{ asset($administrateur->lien_photo) }}" alt="Photo actuelle">
                                            <div class="form-check mt-2">
                                                <input class="form-check-input" type="checkbox" name="delete_photo"
                                                    id="delete_photo" value="1">
                                                <label class="form-check-label" for="delete_photo">
                                                    Supprimer cette photo
                                                </label>
                                            </div>
                                        </div>
                                    @endif

                                    <label for="formFile" class="photo-label">
                                        <i class="bx bx-cloud-upload"></i> Changer la photo (optionnel)
                                    </label>
                                    <input class="form-control @error('lien_photo') is-invalid @enderror" id="formFile"
                                        name="lien_photo" type="file"
                                        accept="image/jpeg,image/png,image/gif,image/jpg">
                                    <small class="text-muted">Formats acceptés : JPG, PNG, GIF (Max 2MB)</small>

                                    <div class="photo-preview-container" id="photoPreviewContainer">
                                        <img class="photo-preview" id="photoPreview" src="#" alt="Aperçu">
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

                        <div class="required-message mt-4">
                            <i class="bx bx-info-circle"></i>
                            <span class="text-danger fw-bold">*</span> Champs obligatoires
                        </div>

                        <div class="action-buttons mt-4">
                            <a href="{{ route('administrateurs.index') }}" class="btn btn-secondary btn-custom">
                                <i class="bx bx-x-circle"></i> Annuler
                            </a>
                            <button type="button" id="submitBtn" class="btn btn-primary btn-custom">
                                <i class="bx bx-save"></i> Mettre à jour
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
                <h5><i class="bx bx-question-mark"></i> Confirmation</h5>
                <span class="modal-close" id="closeModal">&times;</span>
            </div>
            <div class="modal-body-custom">
                <p>Êtes-vous sûr de vouloir modifier cet administrateur ?</p>
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
        <div class="loader-text">Mise à jour en cours...</div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/dashboard/js/bundle/dataTables.bundle.js') }}"></script>
    <script>
        $(document).ready(function() {
            // DataTable (optionnelle)
            var langUrl = "{{ asset('DataTables/fr-FR.json') }}";
            if ($('#myTable').length) {
                $('#myTable').addClass('nowrap').dataTable({
                    responsive: true,
                    searching: true,
                    paging: true,
                    ordering: true,
                    info: false,
                    language: {
                        url: langUrl
                    },
                    lengthMenu: [
                        [5, 10, 25, 50, 100]
                    ],
                    dom: 'Bfrtip',
                    buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
                });
            }

            // Éléments DOM
            const submitBtn = document.getElementById('submitBtn');
            const form = document.getElementById('edit_admin_form');
            const confirmModal = document.getElementById('confirmModal');
            const loaderOverlay = document.getElementById('loaderOverlay');
            const closeModal = document.getElementById('closeModal');
            const cancelModalBtn = document.getElementById('cancelModalBtn');
            const confirmModalBtn = document.getElementById('confirmModalBtn');

            // Gestion photo
            const photoInput = document.getElementById('formFile');
            const photoPreview = document.getElementById('photoPreview');
            const photoPreviewContainer = document.getElementById('photoPreviewContainer');
            const photoNameSpan = document.getElementById('photoName');
            const photoSizeSpan = document.getElementById('photoSize');
            const removePhotoBtn = document.getElementById('removePhoto');
            const deletePhotoCheckbox = document.getElementById('delete_photo');

            if (photoInput) {
                photoInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
                        if (!validTypes.includes(file.type)) {
                            alert('Veuillez sélectionner une image valide (JPG, PNG, GIF)');
                            this.value = '';
                            photoPreviewContainer.style.display = 'none';
                            return;
                        }
                        if (file.size > 2 * 1024 * 1024) {
                            alert('La taille de l\'image ne doit pas dépasser 2MB');
                            this.value = '';
                            photoPreviewContainer.style.display = 'none';
                            return;
                        }
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            photoPreview.src = e.target.result;
                            photoPreviewContainer.style.display = 'block';
                            const fileName = file.name.length > 30 ? file.name.substring(0, 27) +
                                '...' : file.name;
                            const fileSize = (file.size / 1024).toFixed(2) + ' KB';
                            photoNameSpan.textContent = fileName;
                            photoSizeSpan.textContent = fileSize;
                        };
                        reader.readAsDataURL(file);
                        if (deletePhotoCheckbox) deletePhotoCheckbox.checked = false;
                    } else {
                        photoPreviewContainer.style.display = 'none';
                    }
                });
            }

            if (removePhotoBtn) {
                removePhotoBtn.addEventListener('click', function() {
                    photoInput.value = '';
                    photoPreview.src = '#';
                    photoPreviewContainer.style.display = 'none';
                    photoNameSpan.textContent = '';
                    photoSizeSpan.textContent = '';
                });
            }

            // Validation basique avant modale
            function validateForm() {
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
                }
                return isValid;
            }

            // Bouton Mettre à jour → ouvre la modale
            if (submitBtn) {
                submitBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (validateForm()) {
                        confirmModal.classList.add('show');
                    }
                });
            }

            // Fermeture modale
            function closeModalFunction() {
                confirmModal.classList.remove('show');
            }
            if (closeModal) closeModal.addEventListener('click', closeModalFunction);
            if (cancelModalBtn) cancelModalBtn.addEventListener('click', closeModalFunction);
            window.addEventListener('click', function(e) {
                if (e.target === confirmModal) closeModalFunction();
            });

            // Confirmation → envoi formulaire
            if (confirmModalBtn) {
                confirmModalBtn.addEventListener('click', function() {
                    closeModalFunction();
                    loaderOverlay.classList.add('show');
                    submitBtn.disabled = true;
                    form.submit();
                });
            }

            // Validation téléphone (chiffres, 10 caractères)
            const contact = document.getElementById('contact');
            if (contact) {
                contact.addEventListener('input', function(e) {
                    this.value = this.value.replace(/[^0-9]/g, '');
                    if (this.value.length > 10) this.value = this.value.slice(0, 10);
                });
            }

            // Confirmation annulation
            const cancelBtn = document.querySelector('a.btn-secondary');
            if (cancelBtn) {
                cancelBtn.addEventListener('click', function(e) {
                    if (!confirm(
                        'Les modifications non enregistrées seront perdues. Annuler quand même ?')) {
                        e.preventDefault();
                    }
                });
            }
        });
    </script>
@endpush
