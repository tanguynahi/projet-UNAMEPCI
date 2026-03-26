@extends('layouts.dashboard', [
    'title' => 'Ajout d\'un rôle',
    'toolbar' => '_toolbar2',
    'breadcrumb' => 'Ajouter rôle'
])

@push('css')
<style>
    .permissions-container {
        max-height: 500px;
        overflow-y: auto;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 1rem;
        background: #f9fafb;
    }

    .permissions-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .permission-group {
        flex: 0 0 calc(50% - 0.5rem);
        background: white;
        border-radius: 8px;
        padding: 1rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .permission-group:hover {
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .permission-group h6 {
        color: #1f2937;
        font-weight: 600;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e5e7eb;
        text-transform: uppercase;
        font-size: 0.875rem;
        letter-spacing: 0.5px;
    }

    .permission-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .permission-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.25rem 0;
    }

    .permission-checkbox {
        width: 18px;
        height: 18px;
        cursor: pointer;
        accent-color: #0d6efd;
    }

    .permission-label {
        font-size: 0.875rem;
        color: #374151;
        cursor: pointer;
        user-select: none;
        margin: 0;
    }

    .permission-label:hover {
        color: #0d6efd;
    }

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
    }

    .btn-select-all {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-select-all:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .required {
        color: #dc2626;
        font-weight: bold;
    }

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
    }
</style>
@endpush

@section('content')
    <div class="row g-3 justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Renseignez le formulaire</h6>
                    <div class="dropdown morphing scale-left">
                        <a href="#" class="card-fullscreen" data-bs-toggle="tooltip" title="Card Full-Screen">
                            <i class="icon-size-fullscreen"></i>
                        </a>
                        <a href="{{ route('roles.index') }}" class="btn btn-primary btn-sm">Retour</a>
                    </div>
                </div>

                <div class="card-body" id="show_create">
                    <h6 class="fw-bold mb-4">Informations d'un rôle</h6>

                    <form action="{{ route('roles.store') }}"
                          method="POST"
                          id="add_type_paiement_form"
                          enctype="multipart/form-data"
                          class="needs-validation"
                          novalidate>
                        @csrf

                        <!-- Champ Libellé -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <label for="name" class="form-label fw-bold">
                                    Libellé <span class="required">*</span>
                                </label>
                                <input type="text"
                                       id="name"
                                       name="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       placeholder="Ex: Administrateur, Gestionnaire, Utilisateur..."
                                       value="{{ old('name') }}"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Permissions -->
                        <div class="form-group">
                            <div class="permissions-header">
                                <span class="permissions-title">
                                    <i class="bx bx-lock"></i> Permissions <span class="required">*</span>
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
                                                    <i class="bx bx-folder"></i>
                                                    {{ ucfirst(str_replace('_', ' ', $action)) }}
                                                    <span class="badge bg-secondary float-end">{{ count($permissions) }}</span>
                                                </h6>
                                                <div class="permission-list">
                                                    @foreach ($permissions as $permission)
                                                        <div class="permission-item">
                                                            <input type="checkbox"
                                                                   class="permission-checkbox"
                                                                   name="permissions[]"
                                                                   value="{{ $permission->name }}"
                                                                   id="perm_{{ $permission->id }}"
                                                                   {{ in_array($permission->name, old('permissions', [])) ? 'checked' : '' }}>
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
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            @error('permissions.*')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Champs obligatoires -->
                        <div class="alert alert-light mt-3">
                            <i class="bx bx-info-circle"></i>
                            <span class="text-danger fw-bold">*</span> Champs obligatoires.
                        </div>

                        <!-- Boutons d'action -->
                        <div class="row mt-4">
                            <div class="col-12 d-flex justify-content-center gap-3">
                                <button type="reset" class="btn btn-secondary px-4">
                                    <i class="bx bx-reset"></i> Annuler
                                </button>
                                <button type="submit" id="add_admin_btn" class="btn btn-primary px-4">
                                    <i class="bx bx-save"></i> Enregistrer
                                </button>
                            </div>
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
        const toggleButton = document.getElementById('togglePermissions');
        const checkboxes = document.querySelectorAll('.permission-checkbox');

        if (toggleButton) {
            toggleButton.addEventListener('click', function() {
                const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                checkboxes.forEach(checkbox => {
                    checkbox.checked = !allChecked;
                });

                // Changer le texte du bouton
                if (!allChecked) {
                    toggleButton.innerHTML = '<i class="bx bx-checkbox-checked"></i> Désélectionner tout';
                } else {
                    toggleButton.innerHTML = '<i class="bx bx-checkbox"></i> Sélectionner tout';
                }
            });
        }

        // Mettre à jour le texte du bouton si toutes les cases sont cochées initialement
        if (checkboxes.length > 0 && toggleButton) {
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            if (allChecked) {
                toggleButton.innerHTML = '<i class="bx bx-checkbox-checked"></i> Désélectionner tout';
            }
        }

        // Animation pour les groupes de permissions
        const permissionGroups = document.querySelectorAll('.permission-group');
        permissionGroups.forEach((group, index) => {
            group.style.animation = `fadeInUp 0.3s ease ${index * 0.05}s forwards`;
            group.style.opacity = '0';
        });

        // Ajouter le style d'animation
        const style = document.createElement('style');
        style.textContent = `
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
        `;
        document.head.appendChild(style);

        // Gestionnaire pour les icônes et la lisibilité
        const permissionLabels = document.querySelectorAll('.permission-label');
        permissionLabels.forEach(label => {
            label.addEventListener('click', function() {
                const checkbox = this.previousElementSibling;
                if (checkbox) {
                    checkbox.checked = !checkbox.checked;
                }
            });
        });
    });
</script>
@endpush
