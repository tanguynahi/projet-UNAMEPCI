@extends('layouts.dashboard', [
    'title' => 'Mon Profil',
    'toolbar' => '_toolbar2',
    'breadcrumb' => 'Mon Profil',
])

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/dashboard/cssbundle/dataTables.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        /* Styles personnalisés */
        .profile-card {
            border: none;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 1.5rem;
        }

        /* En-tête du profil */
        .profile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 2rem;
            border-radius: 12px 12px 0 0;
            color: white;
            text-align: center;
            position: relative;
        }

        .profile-avatar {
            position: relative;
            display: inline-block;
            margin-bottom: 1rem;
        }

        .profile-avatar img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .profile-avatar img:hover {
            transform: scale(1.05);
        }

        .profile-name {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .profile-email {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        /* Onglets */
        .profile-tabs {
            display: flex;
            border-bottom: 2px solid #e5e7eb;
            margin-bottom: 1.5rem;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .tab-btn {
            padding: 0.75rem 1.5rem;
            background: none;
            border: none;
            font-weight: 500;
            color: #6b7280;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            border-radius: 8px 8px 0 0;
        }

        .tab-btn:hover {
            color: #667eea;
            background: #f3f4f6;
        }

        .tab-btn.active {
            color: #667eea;
            background: #f3f4f6;
        }

        .tab-btn.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 2px;
            background: #667eea;
        }

        .tab-content {
            display: none;
            animation: fadeIn 0.3s ease;
        }

        .tab-content.active {
            display: block;
        }

        /* Formulaires */
        .form-section {
            background: #f9fafb;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
        }

        .form-section-title {
            font-size: 1rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 1.25rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e5e7eb;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-section-title i {
            color: #667eea;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        .form-group label .required {
            color: #dc2626;
            margin-left: 0.25rem;
        }

        .form-control {
            width: 100%;
            padding: 0.6rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .form-control:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-control.is-invalid {
            border-color: #dc2626;
        }

        .invalid-feedback {
            color: #dc2626;
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }

        /* Champ mot de passe avec icône Font Awesome */
        .password-wrapper {
            position: relative;
        }

        .password-wrapper .form-control {
            padding-right: 45px;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
            background: transparent;
            border: none;
            padding: 0;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            z-index: 10;
        }

        .toggle-password:hover {
            color: #667eea;
        }

        /* Force du mot de passe */
        .password-strength {
            margin-top: 0.5rem;
            font-size: 0.75rem;
        }

        .strength-bar {
            height: 4px;
            background: #e2e8f0;
            border-radius: 4px;
            margin-top: 0.25rem;
            overflow: hidden;
        }

        .strength-bar .progress {
            height: 100%;
            width: 0%;
            transition: width 0.3s ease;
        }

        /* Message d'erreur confirmation */
        .password-match-error {
            color: #dc2626;
            font-size: 0.75rem;
            margin-top: 0.25rem;
            display: none;
        }

        .password-match-error.show {
            display: block;
        }

        .password-match-success {
            color: #10b981;
            font-size: 0.75rem;
            margin-top: 0.25rem;
            display: none;
            align-items: center;
            gap: 0.25rem;
        }

        .password-match-success.show {
            display: flex;
        }

        /* Input group pour login */
        .input-group {
            position: relative;
            display: flex;
            align-items: stretch;
        }

        .input-group-text {
            display: flex;
            align-items: center;
            padding: 0.375rem 0.75rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #6c757d;
            text-align: center;
            white-space: nowrap;
            background-color: #e9ecef;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            border-left: none;
        }

        .input-group .form-control:disabled {
            border-right: none;
            border-radius: 0.375rem 0 0 0.375rem;
            background-color: #f3f4f6;
        }

        /* Liste des exigences */
        .requirements-list {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .requirement {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.2rem 0.5rem;
            background: #f3f4f6;
            border-radius: 20px;
            font-size: 0.7rem;
            color: #6b7280;
            transition: all 0.3s ease;
        }

        .requirement.valid {
            background: #d1fae5;
            color: #065f46;
        }

        .strength-status {
            padding: 0.2rem 0.5rem;
            border-radius: 20px;
            background: #f3f4f6;
            font-size: 0.7rem;
            font-weight: 500;
        }

        .strength-status.very-weak {
            background: #fee2e2;
            color: #991b1b;
        }

        .strength-status.weak {
            background: #fed7aa;
            color: #9b2c1d;
        }

        .strength-status.medium {
            background: #fef3c7;
            color: #92400e;
        }

        .strength-status.strong {
            background: #d1fae5;
            color: #065f46;
        }

        .strength-status.very-strong {
            background: #a7f3d0;
            color: #047857;
        }

        /* Boutons */
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid #e5e7eb;
        }

        .btn-custom {
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: none;
            cursor: pointer;
            font-size: 0.875rem;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary-custom {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #cbd5e1;
        }

        .btn-secondary-custom:hover {
            background: #e2e8f0;
            transform: translateY(-2px);
        }

        /* Alertes */
        .alert {
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1rem;
            animation: fadeIn 0.3s ease;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border-left: 4px solid #10b981;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #dc2626;
        }

        /* Modal confirmation */
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
        }

        .modal-close {
            cursor: pointer;
            font-size: 1.5rem;
            color: #6c757d;
            background: none;
            border: none;
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
        }

        /* Animations */
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

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .profile-tabs {
                flex-direction: column;
            }

            .tab-btn {
                text-align: center;
            }

            .form-actions {
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
            <!-- Messages -->
            @if (session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card profile-card">
                <!-- En-tête du profil -->
                <div class="profile-header">
                    <div class="profile-avatar">
                        <img id="avatarPreview"
                            src="{{ $user->lien_photo ? asset($user->lien_photo) : asset('assets/dashboard/img/default-avatar.png') }}"
                            alt="Photo de profil">
                    </div>
                    <div class="profile-name">{{ $user->nom }} {{ $user->prenom }}</div>
                    <div class="profile-email">{{ $user->email }}</div>
                </div>

                <div class="card-body">
                    <!-- Onglets -->
                    <div class="profile-tabs">
                        <button class="tab-btn active" data-tab="info">
                            <i class="fas fa-user"></i> Informations personnelles
                        </button>
                        <button class="tab-btn" data-tab="password">
                            <i class="fas fa-lock"></i> Changer le mot de passe
                        </button>
                    </div>

                    <!-- Onglet Informations personnelles -->
                    <div class="tab-content active" id="tab-info">
                        <form action="{{ route('administrateurs.profil.traitement') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('POST')

                            <div class="form-section">
                                <div class="form-section-title">
                                    <i class="fas fa-user-circle"></i> Identité
                                </div>
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label>Nom <span class="required">*</span></label>
                                        <input type="text" name="nom"
                                            class="form-control @error('nom') is-invalid @enderror"
                                            value="{{ old('nom', $user->nom) }}" required>
                                        @error('nom')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Prénom(s) <span class="required">*</span></label>
                                        <input type="text" name="prenom"
                                            class="form-control @error('prenom') is-invalid @enderror"
                                            value="{{ old('prenom', $user->prenom) }}" required>
                                        @error('prenom')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Email <span class="required">*</span></label>
                                        <input type="email" name="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email', $user->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Contact <span class="required">*</span></label>
                                        <input type="tel" name="contact"
                                            class="form-control @error('contact') is-invalid @enderror"
                                            value="{{ old('contact', $user->contact) }}" required maxlength="10">
                                        @error('contact')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Adresse</label>
                                        <input type="text" name="adresse"
                                            class="form-control @error('adresse') is-invalid @enderror"
                                            value="{{ old('adresse', $user->adresse) }}">
                                        @error('adresse')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Genre <span class="required">*</span></label>
                                        <select name="genre" class="form-control @error('genre') is-invalid @enderror"
                                            required>
                                            <option value="">Sélectionnez</option>
                                            <option value="Homme"
                                                {{ old('genre', $user->genre) == 'Homme' ? 'selected' : '' }}>Homme
                                            </option>
                                            <option value="Femme"
                                                {{ old('genre', $user->genre) == 'Femme' ? 'selected' : '' }}>Femme
                                            </option>
                                        </select>
                                        @error('genre')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-section">
                                <div class="form-section-title">
                                    <i class="fas fa-image"></i> Photo de profil
                                </div>
                                <div class="form-group">
                                    <label>Photo</label>
                                    <input type="file" name="lien_photo" id="profilePhoto" class="form-control"
                                        accept="image/*">
                                    <small class="text-muted">Formats acceptés : JPG, PNG, GIF (Max 2MB)</small>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="button" class="btn-secondary-custom btn-custom" id="cancelProfileBtn">
                                    <i class="fas fa-undo-alt"></i> Annuler
                                </button>
                                <button type="submit" class="btn-primary-custom btn-custom">
                                    <i class="fas fa-save"></i> Enregistrer les modifications
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Onglet Changer le mot de passe -->
                    <div class="tab-content" id="tab-password">
                        <form id="passwordForm" action="{{ route('administrateurs.profil.traitementAcces') }}"
                            method="POST">
                            @csrf
                            @method('POST')

                            <div class="form-section">
                                <div class="form-section-title">
                                    <i class="fas fa-shield-alt"></i> Sécurité
                                </div>

                                <div class="form-grid">
                                    <!-- Login (Email) - Lecture seule -->
                                    <div class="form-group">
                                        <label>
                                            <i class="fas fa-envelope"></i> Login (Email)
                                        </label>
                                        <div class="input-group">
                                            <input type="email" value="{{ $user->user->email ?? 'Non renseigné' }}"
                                                class="form-control" disabled>
                                            <span class="input-group-text">
                                                <i class="fas fa-lock"></i>
                                            </span>
                                        </div>
                                        <small class="text-muted">L'adresse email ne peut pas être modifiée ici</small>
                                    </div>

                                    <!-- Mot de passe actuel -->
                                    <div class="form-group">
                                        <label>
                                            Mot de passe actuel <span class="required">*</span>
                                        </label>
                                        <div class="password-wrapper">
                                            <input type="password" name="current_password" id="current_password"
                                                class="form-control @error('current_password') is-invalid @enderror"
                                                placeholder="Entrez votre mot de passe actuel"
                                                autocomplete="current-password" required>
                                            <button type="button" class="toggle-password"
                                                data-target="current_password">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        @error('current_password')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Veuillez entrer votre mot de passe actuel pour confirmer
                                            les modifications</small>
                                    </div>

                                    <!-- Nouveau mot de passe -->
                                    <div class="form-group">
                                        <label>
                                            Nouveau mot de passe <span class="required">*</span>
                                        </label>
                                        <div class="password-wrapper">
                                            <input type="password" name="new_password" id="new_password"
                                                class="form-control @error('new_password') is-invalid @enderror"
                                                placeholder="Entrez votre nouveau mot de passe"
                                                autocomplete="new-password" required>
                                            <button type="button" class="toggle-password" data-target="new_password">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>

                                        <!-- Indicateur de force du mot de passe -->
                                        <div class="password-strength mt-2">
                                            <div class="strength-bar">
                                                <div class="progress" style="width: 0%;"></div>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mt-1">
                                                <span class="strength-text" style="font-size: 0.75rem;">Force du mot de
                                                    passe</span>
                                                <span class="strength-status">-</span>
                                            </div>
                                        </div>

                                        <div class="password-requirements mt-2">
                                            <small class="text-muted d-block">Le mot de passe doit contenir :</small>
                                            <div class="requirements-list">
                                                <span class="requirement" id="req-length"><i class="fas fa-check-circle"></i> Au moins 8 caractères</span>
                                                <span class="requirement" id="req-lowercase"><i class="fas fa-check-circle"></i> Au moins une minuscule</span>
                                                <span class="requirement" id="req-uppercase"><i class="fas fa-check-circle"></i> Au moins une majuscule</span>
                                                <span class="requirement" id="req-number"><i class="fas fa-check-circle"></i> Au moins un chiffre</span>
                                                <span class="requirement" id="req-special"><i class="fas fa-check-circle"></i> Au moins un caractère spécial (@$#&!)</span>
                                            </div>
                                        </div>

                                        @error('new_password')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Confirmation du nouveau mot de passe -->
                                    <div class="form-group">
                                        <label>
                                            Confirmer le nouveau mot de passe <span class="required">*</span>
                                        </label>
                                        <div class="password-wrapper">
                                            <input type="password" name="new_password_confirmation"
                                                id="new_password_confirmation" class="form-control"
                                                placeholder="Confirmez votre nouveau mot de passe"
                                                autocomplete="new-password" required>
                                            <button type="button" class="toggle-password"
                                                data-target="new_password_confirmation">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        <div class="password-match-error" id="passwordMatchError">
                                            <i class="fas fa-times-circle"></i> Les mots de passe ne correspondent pas
                                        </div>
                                        <div class="password-match-success" id="passwordMatchSuccess">
                                            <i class="fas fa-check-circle"></i> Les mots de passe correspondent
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="button" class="btn-secondary-custom btn-custom" id="cancelPasswordBtn">
                                    <i class="fas fa-undo-alt"></i> Annuler
                                </button>
                                <button type="submit" class="btn-primary-custom btn-custom" >
                                    <i class="fas fa-key"></i> Changer le mot de passe
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmation -->
    <div id="confirmModal" class="modal-custom">
        <div class="modal-content-custom">
            <div class="modal-header-custom">
                <h5>
                    <i class="fas fa-question-circle"></i> Confirmation
                </h5>
                <button type="button" class="modal-close" id="closeModal">&times;</button>
            </div>
            <div class="modal-body-custom">
                <p id="modalMessage">Êtes-vous sûr de vouloir enregistrer les modifications ?</p>
            </div>
            <div class="modal-footer-custom">
                <button type="button" class="btn-secondary-custom btn-custom" id="cancelModalBtn">
                    <i class="fas fa-times"></i> Annuler
                </button>
                <button type="button" class="btn-primary-custom btn-custom" id="confirmModalBtn">
                    <i class="fas fa-check"></i> Confirmer
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // ==================== GESTION DES ONGLETS ====================
            $('.tab-btn').on('click', function() {
                const tabId = $(this).data('tab');
                $('.tab-btn').removeClass('active');
                $('.tab-content').removeClass('active');
                $(this).addClass('active');
                $(`#tab-${tabId}`).addClass('active');
            });

            // ==================== GESTION DES ICÔNES ŒIL (Font Awesome) ====================
            document.querySelectorAll('.toggle-password').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('data-target');
                    const input = document.getElementById(targetId);
                    const icon = this.querySelector('i');

                    if (input) {
                        if (input.type === 'password') {
                            input.type = 'text';
                            icon.classList.remove('fa-eye');
                            icon.classList.add('fa-eye-slash');
                        } else {
                            input.type = 'password';
                            icon.classList.remove('fa-eye-slash');
                            icon.classList.add('fa-eye');
                        }
                    }
                });
            });

            // ==================== VALIDATION MOT DE PASSE ====================
            const newPasswordInput = document.getElementById('new_password');
            const confirmPasswordInput = document.getElementById('new_password_confirmation');
            const passwordMatchError = document.getElementById('passwordMatchError');
            const passwordMatchSuccess = document.getElementById('passwordMatchSuccess');

            function validatePasswordMatch() {
                if (newPasswordInput && confirmPasswordInput) {
                    const newPassword = newPasswordInput.value;
                    const confirmPassword = confirmPasswordInput.value;

                    if (confirmPassword.length > 0) {
                        if (newPassword !== confirmPassword) {
                            confirmPasswordInput.classList.add('is-invalid');
                            passwordMatchError.classList.add('show');
                            passwordMatchSuccess.classList.remove('show');
                            return false;
                        } else {
                            confirmPasswordInput.classList.remove('is-invalid');
                            passwordMatchError.classList.remove('show');
                            passwordMatchSuccess.classList.add('show');
                            return true;
                        }
                    } else {
                        confirmPasswordInput.classList.remove('is-invalid');
                        passwordMatchError.classList.remove('show');
                        passwordMatchSuccess.classList.remove('show');
                        return true;
                    }
                }
                return true;
            }

            if (newPasswordInput && confirmPasswordInput) {
                newPasswordInput.addEventListener('input', validatePasswordMatch);
                confirmPasswordInput.addEventListener('input', validatePasswordMatch);
            }

            // ==================== FORCE DU MOT DE PASSE ====================
            if (newPasswordInput) {
                const strengthBar = document.querySelector('#tab-password .strength-bar .progress');
                const strengthStatus = document.querySelector('#tab-password .strength-status');

                const reqLength = document.getElementById('req-length');
                const reqLowercase = document.getElementById('req-lowercase');
                const reqUppercase = document.getElementById('req-uppercase');
                const reqNumber = document.getElementById('req-number');
                const reqSpecial = document.getElementById('req-special');

                function checkPasswordStrength(password) {
                    let requirements = {
                        length: password.length >= 8,
                        lowercase: /[a-z]/.test(password),
                        uppercase: /[A-Z]/.test(password),
                        number: /[0-9]/.test(password),
                        special: /[$@#&!]/.test(password)
                    };

                    // Mettre à jour les exigences visuellement
                    if (reqLength) reqLength.classList.toggle('valid', requirements.length);
                    if (reqLowercase) reqLowercase.classList.toggle('valid', requirements.lowercase);
                    if (reqUppercase) reqUppercase.classList.toggle('valid', requirements.uppercase);
                    if (reqNumber) reqNumber.classList.toggle('valid', requirements.number);
                    if (reqSpecial) reqSpecial.classList.toggle('valid', requirements.special);

                    // Compter les exigences remplies
                    const strength = Object.values(requirements).filter(Boolean).length;
                    const percentage = (strength / 5) * 100;

                    // Mettre à jour la barre de progression
                    if (strengthBar) {
                        strengthBar.style.width = percentage + '%';
                    }

                    // Déterminer le niveau de force
                    let level = '';
                    let statusText = '';

                    if (percentage <= 20) {
                        level = 'very-weak';
                        statusText = 'Très faible';
                        if (strengthBar) strengthBar.style.background = '#dc2626';
                    } else if (percentage <= 40) {
                        level = 'weak';
                        statusText = 'Faible';
                        if (strengthBar) strengthBar.style.background = '#f59e0b';
                    } else if (percentage <= 60) {
                        level = 'medium';
                        statusText = 'Moyen';
                        if (strengthBar) strengthBar.style.background = '#eab308';
                    } else if (percentage <= 80) {
                        level = 'strong';
                        statusText = 'Fort';
                        if (strengthBar) strengthBar.style.background = '#10b981';
                    } else {
                        level = 'very-strong';
                        statusText = 'Très fort';
                        if (strengthBar) strengthBar.style.background = '#22c55e';
                    }

                    if (strengthStatus) {
                        strengthStatus.textContent = statusText;
                        strengthStatus.className = `strength-status ${level}`;
                    }

                    return strength >= 4;
                }

                newPasswordInput.addEventListener('input', function() {
                    checkPasswordStrength(this.value);
                    validatePasswordMatch();
                });
            }

            // ==================== APERÇU PHOTO ====================
            const photoInput = document.getElementById('profilePhoto');
            const avatarPreview = document.getElementById('avatarPreview');

            if (photoInput) {
                photoInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
                        if (!validTypes.includes(file.type)) {
                            alert('Veuillez sélectionner une image valide (JPG, PNG, GIF)');
                            this.value = '';
                            return;
                        }
                        if (file.size > 2 * 1024 * 1024) {
                            alert('La taille de l\'image ne doit pas dépasser 2MB');
                            this.value = '';
                            return;
                        }

                        const reader = new FileReader();
                        reader.onload = function(e) {
                            avatarPreview.src = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            // ==================== VALIDATION TÉLÉPHONE ====================
            const contactInput = document.querySelector('input[name="contact"]');
            if (contactInput) {
                contactInput.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '');
                    if (this.value.length > 10) {
                        this.value = this.value.slice(0, 10);
                    }
                });
            }

            // ==================== MODALE DE CONFIRMATION ====================
            const confirmModal = document.getElementById('confirmModal');
            const closeModalBtn = document.getElementById('closeModal');
            const cancelModalBtn = document.getElementById('cancelModalBtn');
            const confirmModalBtn = document.getElementById('confirmModalBtn');
            const loaderOverlay = document.getElementById('loaderOverlay');

            let currentForm = null;
            let currentCallback = null;

            function openModal(message, form, callback) {
                const modalMessage = document.getElementById('modalMessage');
                if (modalMessage) modalMessage.textContent = message;
                currentForm = form;
                currentCallback = callback;
                if (confirmModal) confirmModal.classList.add('show');
            }

            function closeModal() {
                if (confirmModal) confirmModal.classList.remove('show');
                currentForm = null;
                currentCallback = null;
            }

            if (closeModalBtn) closeModalBtn.addEventListener('click', closeModal);
            if (cancelModalBtn) cancelModalBtn.addEventListener('click', closeModal);

            window.addEventListener('click', function(e) {
                if (e.target === confirmModal) closeModal();
            });

            if (confirmModalBtn) {
                confirmModalBtn.addEventListener('click', function() {
                    if (currentForm && currentCallback) {
                        closeModal();
                        currentCallback();
                    }
                });
            }

            // ==================== VALIDATION FORMULAIRE MOT DE PASSE ====================
            function validatePasswordForm() {
                const currentPassword = document.getElementById('current_password');
                const newPassword = document.getElementById('new_password');
                const confirmPassword = document.getElementById('new_password_confirmation');

                if (!currentPassword.value.trim()) {
                    alert('Veuillez entrer votre mot de passe actuel.');
                    currentPassword.focus();
                    return false;
                }

                if (!newPassword.value.trim()) {
                    alert('Veuillez entrer un nouveau mot de passe.');
                    newPassword.focus();
                    return false;
                }

                if (newPassword.value.length < 8) {
                    alert('Le nouveau mot de passe doit contenir au moins 8 caractères.');
                    newPassword.focus();
                    return false;
                }

                if (newPassword.value !== confirmPassword.value) {
                    alert('Les mots de passe ne correspondent pas.');
                    confirmPassword.focus();
                    return false;
                }

                return true;
            }

            // ==================== SOUMISSION FORMULAIRE MOT DE PASSE ====================
            const openPasswordModal = document.getElementById('openPasswordModal');
            const passwordForm = document.getElementById('passwordForm');

            if (openPasswordModal) {
                openPasswordModal.addEventListener('click', function() {
                    if (validatePasswordForm()) {
                        openModal('Êtes-vous sûr de vouloir changer votre mot de passe ?', passwordForm, function() {
                            if (loaderOverlay) loaderOverlay.classList.add('show');
                            passwordForm.submit();
                        });
                    }
                });
            }

            // ==================== BOUTONS ANNULER ====================
            const cancelProfileBtn = document.getElementById('cancelProfileBtn');
            const cancelPasswordBtn = document.getElementById('cancelPasswordBtn');

            if (cancelProfileBtn) {
                cancelProfileBtn.addEventListener('click', function() {
                    if (confirm('Êtes-vous sûr de vouloir annuler toutes les modifications ?')) {
                        location.reload();
                    }
                });
            }

            if (cancelPasswordBtn) {
                cancelPasswordBtn.addEventListener('click', function() {
                    if (confirm('Êtes-vous sûr de vouloir annuler ?')) {
                        location.reload();
                    }
                });
            }

            // ==================== AUTO-FERMETURE DES ALERTES ====================
            setTimeout(() => {
                $('.alert').fadeOut(500, function() {
                    $(this).remove();
                });
            }, 5000);
        });
    </script>
@endpush
