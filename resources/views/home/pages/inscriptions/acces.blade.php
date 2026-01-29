@extends('layouts.home', ['title' => 'Création de compte'])
@push('css')
    <style>
        .account-creation-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: 0 auto;
        }

        .account-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .account-header .user-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .account-header .user-icon i {
            font-size: 40px;
            color: white;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        .email-display {
            background-color: #f8f9fa;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 12px 15px;
            font-weight: 500;
            color: #405189;
        }

        .password-strength {
            margin-top: 5px;
            font-size: 12px;
        }

        .password-strength-bar {
            height: 4px;
            border-radius: 2px;
            margin-top: 5px;
            transition: all 0.3s ease;
        }

        .password-strength-text {
            font-size: 12px;
            margin-top: 3px;
        }

        .password-toggle {
            cursor: pointer;
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }

        .form-group {
            position: relative;
        }

        .btn-create-account {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-create-account:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .password-requirements {
            background-color: #f8f9fa;
            border-left: 4px solid #405189;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .password-requirements h6 {
            color: #405189;
            margin-bottom: 10px;
        }

        .password-requirements ul {
            margin-bottom: 0;
            padding-left: 20px;
        }

        .password-requirements li {
            font-size: 13px;
            margin-bottom: 5px;
            color: #666;
        }

        .password-requirements li.valid {
            color: #28a745;
        }

        .password-requirements li.valid:before {
            content: "✓ ";
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .account-creation-container {
                padding: 20px;
                margin: 0 15px;
            }

            .account-header .user-icon {
                width: 60px;
                height: 60px;
            }

            .account-header .user-icon i {
                font-size: 30px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="row justify-content-center mt-5">
        <div class="col-12 col-sm-10 col-md-8 col-lg-6">
            <div class="account-creation-container">
                <!-- En-tête avec icône utilisateur -->
                <div class="account-header">
                    <div class="user-icon">
                        <i class="fa fa-user-plus"></i>
                    </div>
                    <h3 class="mb-2">Création de votre compte</h3>
                    <p class="text-muted">Complétez les informations pour activer votre compte mutualiste</p>
                </div>

                <!-- Information du mutualiste -->
                <div class="alert alert-info mb-4">
                    <div class="d-flex align-items-center">
                        <i class="fa fa-info-circle me-3 fa-lg"></i>
                        <div>
                            <h6 class="alert-heading mb-1">Bienvenue {{ $mutualiste->civilite }} {{ $mutualiste->nom }}
                                {{ $mutualiste->prenom }}</h6>
                            <p class="mb-0">Votre compte sera créé avec l'adresse email ci-dessous.</p>
                        </div>
                    </div>
                </div>
                {{-- {{ route('compte.creer', $mutualiste->id) }} --}}
                <form action="{{ route('compte.creer', $mutualiste->id) }}" method="POST" id="createAccountForm">
                    @csrf
                    @method('post')

                    <!-- Champ email (lecture seule) -->
                    <div class="mb-4">
                        <label class="form-label">Email de connexion</label>
                        <div class="email-display">
                            <i class="fa fa-envelope me-2 text-primary"></i>
                            {{ $mutualiste->email }}
                        </div>
                        <small class="text-muted mt-1 d-block">Cette adresse email sera utilisée pour vous connecter</small>
                    </div>

                    <!-- Exigences pour le mot de passe -->
                    <div class="password-requirements mb-4">
                        <h6><i class="fa fa-shield-alt me-2"></i>Exigences de sécurité</h6>
                        <ul id="passwordRequirements">
                            <li id="reqLength">Au moins 8 caractères</li>
                            <li id="reqUppercase">Au moins une majuscule</li>
                            <li id="reqLowercase">Au moins une minuscule</li>
                            <li id="reqNumber">Au moins un chiffre</li>
                            <li id="reqSpecial">Au moins un caractère spécial</li>
                        </ul>
                    </div>

                    <!-- Champ mot de passe -->
                    <div class="form-group mb-4">
                        <label for="password" class="form-label">Mot de passe <span class="text-danger">*</span></label>
                        <div class="position-relative">
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" placeholder="Saisissez votre mot de passe" required>
                            <span class="password-toggle" id="togglePassword">
                                <i class="fa fa-eye"></i>
                            </span>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Indicateur de force du mot de passe -->
                        <div class="password-strength">
                            <div class="password-strength-bar" id="passwordStrengthBar"></div>
                            <div class="password-strength-text" id="passwordStrengthText"></div>
                        </div>
                    </div>

                    <!-- Champ confirmation mot de passe -->
                    <div class="form-group mb-4">
                        <label for="password_confirmation" class="form-label">Confirmer le mot de passe <span
                                class="text-danger">*</span></label>
                        <div class="position-relative">
                            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                                id="password_confirmation" name="password_confirmation"
                                placeholder="Ressaisissez votre mot de passe" required>
                            <span class="password-toggle" id="toggleConfirmPassword">
                                <i class="fa fa-eye"></i>
                            </span>
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div id="passwordMatch" class="mt-2"></div>
                    </div>

                    <!-- Checkbox conditions d'utilisation -->
                    <div class="form-check mb-4">
                        <input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox" id="terms"
                            name="terms" required>
                        <label class="form-check-label" for="terms">
                            J'accepte les <a href="#" class="text-primary">conditions d'utilisation</a> et la <a
                                href="#" class="text-primary">politique de confidentialité</a>
                        </label>
                        @error('terms')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Bouton de soumission -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-create-account" id="submitBtn">
                            <i class="fa fa-user-check me-2"></i>Créer mon compte
                        </button>
                    </div>

                    <!-- Lien de retour -->
                    <div class="text-center mt-4">
                        <a href="{{ route('accueil') }}" class="text-decoration-none">
                            <i class="fa fa-arrow-left me-1"></i>Retour à l'accueil
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('password_confirmation');
            const togglePassword = document.getElementById('togglePassword');
            const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
            const passwordStrengthBar = document.getElementById('passwordStrengthBar');
            const passwordStrengthText = document.getElementById('passwordStrengthText');
            const passwordMatch = document.getElementById('passwordMatch');
            const submitBtn = document.getElementById('submitBtn');

            // Exigences de mot de passe
            const requirements = {
                length: document.getElementById('reqLength'),
                uppercase: document.getElementById('reqUppercase'),
                lowercase: document.getElementById('reqLowercase'),
                number: document.getElementById('reqNumber'),
                special: document.getElementById('reqSpecial')
            };

            // Fonction pour basculer la visibilité du mot de passe
            function togglePasswordVisibility(input, toggle) {
                toggle.addEventListener('click', function() {
                    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                    input.setAttribute('type', type);
                    this.querySelector('i').classList.toggle('fa-eye');
                    this.querySelector('i').classList.toggle('fa-eye-slash');
                });
            }

            // Initialiser les bascules
            togglePasswordVisibility(passwordInput, togglePassword);
            togglePasswordVisibility(confirmPasswordInput, toggleConfirmPassword);

            // Vérifier la force du mot de passe
            function checkPasswordStrength(password) {
                let strength = 0;

                // Vérifier les exigences
                const hasLength = password.length >= 8;
                const hasUppercase = /[A-Z]/.test(password);
                const hasLowercase = /[a-z]/.test(password);
                const hasNumber = /[0-9]/.test(password);
                const hasSpecial = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);

                // Mettre à jour l'UI des exigences
                updateRequirementUI('length', hasLength);
                updateRequirementUI('uppercase', hasUppercase);
                updateRequirementUI('lowercase', hasLowercase);
                updateRequirementUI('number', hasNumber);
                updateRequirementUI('special', hasSpecial);

                // Calculer le score de force
                if (hasLength) strength += 20;
                if (hasUppercase) strength += 20;
                if (hasLowercase) strength += 20;
                if (hasNumber) strength += 20;
                if (hasSpecial) strength += 20;

                // Mettre à jour la barre et le texte
                updateStrengthUI(strength);
            }

            function updateRequirementUI(type, isValid) {
                const element = requirements[type];
                if (isValid) {
                    element.classList.add('valid');
                } else {
                    element.classList.remove('valid');
                }
            }

            function updateStrengthUI(strength) {
                let color = '#dc3545';
                let text = 'Très faible';

                if (strength >= 20) {
                    color = '#ffc107';
                    text = 'Faible';
                }
                if (strength >= 40) {
                    color = '#fd7e14';
                    text = 'Moyen';
                }
                if (strength >= 60) {
                    color = '#20c997';
                    text = 'Bon';
                }
                if (strength >= 80) {
                    color = '#28a745';
                    text = 'Très bon';
                }
                if (strength === 100) {
                    color = '#198754';
                    text = 'Excellent';
                }

                passwordStrengthBar.style.width = strength + '%';
                passwordStrengthBar.style.backgroundColor = color;
                passwordStrengthText.textContent = `Force du mot de passe: ${text}`;
                passwordStrengthText.style.color = color;
            }

            // Vérifier la correspondance des mots de passe
            function checkPasswordMatch() {
                const password = passwordInput.value;
                const confirmPassword = confirmPasswordInput.value;

                if (confirmPassword === '') {
                    passwordMatch.innerHTML = '';
                    return;
                }

                if (password === confirmPassword) {
                    passwordMatch.innerHTML =
                        '<span class="text-success"><i class="fa fa-check-circle me-1"></i>Les mots de passe correspondent</span>';
                    return true;
                } else {
                    passwordMatch.innerHTML =
                        '<span class="text-danger"><i class="fa fa-times-circle me-1"></i>Les mots de passe ne correspondent pas</span>';
                    return false;
                }
            }

            // Événements pour vérifier en temps réel
            passwordInput.addEventListener('input', function() {
                checkPasswordStrength(this.value);
                if (confirmPasswordInput.value !== '') {
                    checkPasswordMatch();
                }
            });

            confirmPasswordInput.addEventListener('input', function() {
                checkPasswordMatch();
            });

            // Validation du formulaire avant soumission
            document.getElementById('createAccountForm').addEventListener('submit', function(e) {
                const password = passwordInput.value;
                const confirmPassword = confirmPasswordInput.value;
                const terms = document.getElementById('terms').checked;

                // Vérifier la correspondance
                if (password !== confirmPassword) {
                    e.preventDefault();
                    passwordMatch.innerHTML =
                        '<span class="text-danger"><i class="fa fa-exclamation-triangle me-1"></i>Veuillez confirmer votre mot de passe correctement</span>';
                    confirmPasswordInput.focus();
                    return;
                }

                // Vérifier les termes
                if (!terms) {
                    e.preventDefault();
                    alert('Veuillez accepter les conditions d\'utilisation');
                    return;
                }

                // Vérifier la force du mot de passe (optionnel)
                const hasLength = password.length >= 8;
                const hasUppercase = /[A-Z]/.test(password);
                const hasLowercase = /[a-z]/.test(password);
                const hasNumber = /[0-9]/.test(password);

                if (!hasLength || !hasUppercase || !hasLowercase || !hasNumber) {
                    const proceed = confirm(
                        'Votre mot de passe est faible. Souhaitez-vous continuer quand même ?');
                    if (!proceed) {
                        e.preventDefault();
                        passwordInput.focus();
                    }
                }
            });
        });
    </script>
@endpush
