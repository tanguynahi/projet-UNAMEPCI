@extends('layouts.dashboard', [
    'title' => 'Modifier un rôle',
    'toolbar' => '_toolbar2',
    'breadcrumb' => 'Modifier rôle',
])

@push('css')
    <style>
        /* Styles généraux */
        .form-card {
            border: none;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.08);
        }

        /* En-tête du formulaire */
        .form-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 1rem 1.5rem;
            border-radius: 12px 12px 0 0;
            color: white;
        }

        /* Conteneur des permissions */
        .permissions-container {
            max-height: 500px;
            overflow-y: auto;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 1rem;
            background: #f9fafb;
        }

        /* Grille des permissions en col-6 */
        .permissions-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }

        /* Chaque groupe de permission occupe 2 colonnes */
        .permission-group {
            flex: 0 0 calc(50% - 0.5rem);
            background: white;
            border-radius: 10px;
            padding: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
        }

        .permission-group:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
            border-color: #667eea;
        }

        /* Titre du groupe */
        .permission-group h6 {
            color: #1f2937;
            font-weight: 600;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e5e7eb;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .permission-group h6::before {
            content: "📁";
            margin-right: 0.5rem;
            font-size: 1rem;
        }

        .permission-count {
            background: #e5e7eb;
            padding: 0.2rem 0.5rem;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: normal;
        }

        /* Liste des permissions */
        .permission-list {
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
        }

        /* Élément de permission */
        .permission-item {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.25rem 0;
            transition: all 0.2s ease;
        }

        .permission-item:hover {
            background: #f8f9fa;
            padding-left: 0.5rem;
            border-radius: 6px;
        }

        /* Checkbox personnalisée */
        .permission-checkbox {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #667eea;
            margin: 0;
        }

        .permission-label {
            font-size: 0.85rem;
            color: #374151;
            cursor: pointer;
            user-select: none;
            margin: 0;
            flex: 1;
        }

        .permission-label:hover {
            color: #667eea;
        }

        /* En-tête des permissions */
        .permissions-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .permissions-title {
            font-weight: 600;
            color: #1f2937;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .permissions-title i {
            color: #667eea;
            font-size: 1.2rem;
        }

        /* Bouton sélectionner tout */
        .btn-select-all {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
        }

        .btn-select-all:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        /* Champ libellé */
        .form-group-half {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
            display: block;
        }

        .required {
            color: #dc2626;
            font-weight: bold;
            margin-left: 0.25rem;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #d1d5db;
            padding: 0.6rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        /* Message d'information */
        .form-info {
            text-align: center;
            padding: 2rem;
            color: #6c757d;
            background: #f8f9fa;
            border-radius: 8px;
            margin: 0;
        }

        /* Style pour la scrollbar */
        .permissions-container::-webkit-scrollbar {
            width: 8px;
        }

        .permissions-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .permissions-container::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        .permissions-container::-webkit-scrollbar-thumb:hover {
            background: #555;
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

        /* Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .permission-group {
            animation: fadeInUp 0.3s ease forwards;
            opacity: 0;
        }

        .permission-group:nth-child(1) {
            animation-delay: 0.05s;
        }

        .permission-group:nth-child(2) {
            animation-delay: 0.1s;
        }

        .permission-group:nth-child(3) {
            animation-delay: 0.15s;
        }

        .permission-group:nth-child(4) {
            animation-delay: 0.2s;
        }

        .permission-group:nth-child(5) {
            animation-delay: 0.25s;
        }

        .permission-group:nth-child(6) {
            animation-delay: 0.3s;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .permission-group {
                flex: 0 0 100%;
            }

            .permissions-header {
                flex-direction: column;
                align-items: stretch;
            }

            .btn-select-all {
                justify-content: center;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-custom {
                justify-content: center;
            }

            .col-8 {
                width: 100%;
            }
        }

        /* Badge pour le rôle actuel */
        .role-badge {
            background: #f3f4f6;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            color: #6b7280;
            margin-left: 0.5rem;
        }
    </style>
@endpush

@section('content')
    <div class="row g-3 justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
            <div class="card form-card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <h6 class="card-title mb-0">
                        <i class="bx bx-edit-alt"></i> Formulaire de modification
                    </h6>
                    <div class="d-flex gap-2">
                        <a href="#" class="card-fullscreen" data-bs-toggle="tooltip" title="Plein écran">
                            <i class="icon-size-fullscreen"></i>
                        </a>
                        <a href="{{ route('roles.index') }}" class="btn btn-primary btn-sm">
                            <i class="bx bx-arrow-back"></i> Retour
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="form-header mb-4">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div>
                                <h5 class="mb-1 text-white">
                                    <i class="bx bx-user-tag"></i> Modification du rôle
                                </h5>
                                <p class="mb-0 text-white-50">
                                    Rôle en cours d'édition : <strong>{{ $role->name }}</strong>
                                </p>
                            </div>
                            <div class="role-badge bg-white text-dark">
                                ID: {{ $role->id }}
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('roles.update', $role->id) }}" method="POST" id="edit_role_form"
                        enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')

                        <!-- Champ Libellé -->
                        <div class="form-group-half">
                            <label for="name" class="form-label">
                                <i class="bx bx-tag"></i> Libellé du rôle <span class="required">*</span>
                            </label>
                            <input type="text" id="name" name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                placeholder="Ex: Administrateur, Gestionnaire, Utilisateur..."
                                value="{{ old('name', $role->name) }}" required>
                            <small class="text-muted">Choisissez un nom unique et descriptif pour ce rôle.</small>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Permissions -->
                        <div class="form-group">
                            <div class="permissions-header">
                                <span class="permissions-title">
                                    <i class="bx bx-lock-alt"></i> Permissions <span class="required">*</span>
                                </span>
                                <button type="button" class="btn-select-all" id="togglePermissions">
                                    <i class="bx bx-checkbox-checked"></i> Sélectionner tout
                                </button>
                            </div>

                            @if (isset($groupedPermissions) && $groupedPermissions->count() > 0)
                                <div class="permissions-container">
                                    <div class="permissions-grid">
                                        @foreach ($groupedPermissions as $action => $permissions)
                                            <div class="permission-group">
                                                <h6>
                                                    {{ ucfirst(str_replace('_', ' ', $action)) }}
                                                    <span class="permission-count">{{ count($permissions) }}</span>
                                                </h6>
                                                <div class="permission-list">
                                                    @foreach ($permissions as $permission)
                                                        @php
                                                            $checked = in_array(
                                                                $permission->name,
                                                                old('permissions', $rolePermissions ?? []),
                                                            );
                                                        @endphp
                                                        <div class="permission-item">
                                                            <input type="checkbox" class="permission-checkbox"
                                                                name="permissions[]" value="{{ $permission->name }}"
                                                                id="perm_{{ $permission->id }}"
                                                                {{ $checked ? 'checked' : '' }}>
                                                            <label for="perm_{{ $permission->id }}"
                                                                class="permission-label">
                                                                {{ ucfirst(str_replace('_', ' ', $permission->name)) }}
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="form-info">
                                    <i class="bx bx-info-circle"></i> Aucune permission disponible.
                                </div>
                            @endif

                            @error('permissions')
                                <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                            @enderror
                            @error('permissions.*')
                                <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Message champs obligatoires -->
                        <div class="alert alert-light mt-3">
                            <i class="bx bx-info-circle"></i>
                            <span class="text-danger fw-bold">*</span> Champs obligatoires
                        </div>

                        <!-- Boutons d'action -->
                        <div class="action-buttons">
                            <button type="reset" class="btn btn-secondary btn-custom">
                                <i class="bx bx-reset"></i> Annuler
                            </button>
                            <button type="submit" id="update_role_btn" class="btn btn-primary btn-custom">
                                <i class="bx bx-save"></i> Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gestion du bouton sélectionner tout
            const toggleButton = document.getElementById('togglePermissions');
            const checkboxes = document.querySelectorAll('.permission-checkbox');

            if (toggleButton) {
                // Vérifier si toutes les cases sont cochées initialement
                const allChecked = checkboxes.length > 0 && Array.from(checkboxes).every(cb => cb.checked);
                if (allChecked) {
                    toggleButton.innerHTML = '<i class="bx bx-checkbox"></i> Désélectionner tout';
                }

                toggleButton.addEventListener('click', function() {
                    const isAllChecked = Array.from(checkboxes).every(cb => cb.checked);
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = !isAllChecked;
                    });

                    // Mettre à jour le texte du bouton
                    if (!isAllChecked) {
                        toggleButton.innerHTML = '<i class="bx bx-checkbox"></i> Désélectionner tout';
                    } else {
                        toggleButton.innerHTML = '<i class="bx bx-checkbox-checked"></i> Sélectionner tout';
                    }
                });
            }

            // Clic sur le label pour cocher/décocher
            const permissionLabels = document.querySelectorAll('.permission-label');
            permissionLabels.forEach(label => {
                label.addEventListener('click', function(e) {
                    e.preventDefault();
                    const checkbox = this.previousElementSibling;
                    if (checkbox && checkbox.type === 'checkbox') {
                        checkbox.checked = !checkbox.checked;
                    }
                });
            });

            // Animation pour les groupes de permissions
            const permissionGroups = document.querySelectorAll('.permission-group');
            permissionGroups.forEach((group, index) => {
                group.style.animationDelay = `${index * 0.05}s`;
            });

            // Confirmation avant réinitialisation
            const resetButton = document.querySelector('button[type="reset"]');
            if (resetButton) {
                resetButton.addEventListener('click', function(e) {
                    if (confirm('Êtes-vous sûr de vouloir annuler les modifications ?')) {
                        return true;
                    }
                    e.preventDefault();
                    return false;
                });
            }

            // Validation du formulaire
            const form = document.getElementById('edit_role_form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const selectedPermissions = document.querySelectorAll('.permission-checkbox:checked');
                    if (selectedPermissions.length === 0) {
                        e.preventDefault();
                        alert('Veuillez sélectionner au moins une permission pour ce rôle.');
                    }
                });
            }

            // Tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endpush
