@extends('layouts.dashboard', [
    'title' => 'Gestion des permissions - Administrateur',
    'toolbar' => '_toolbar2',
    'breadcrumb' => 'Permissions Administrateur'
])

@push('css')
<!-- Application vendor css url -->
<link rel="stylesheet" href="{{ asset('assets/dashboard/cssbundle/dataTables.min.css') }}">
<style>
    /* Styles personnalisés */
    .info-card {
        border: none;
        box-shadow: 0 0 20px rgba(0,0,0,0.08);
        margin-bottom: 1.5rem;
    }

    /* En-tête */
    .card-header-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #e5e7eb;
    }

    .card-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1f2937;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .card-title i {
        color: #667eea;
        font-size: 1.2rem;
    }

    .btn-back {
        background: #f3f4f6;
        color: #374151;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
    }

    .btn-back:hover {
        background: #e5e7eb;
        transform: translateY(-2px);
    }

    /* Section informations */
    .info-section {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid #e2e8f0;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem;
        background: white;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .info-item:hover {
        transform: translateX(5px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .info-item i {
        font-size: 1.2rem;
        color: #667eea;
        width: 24px;
    }

    .info-item strong {
        color: #374151;
        font-weight: 600;
        min-width: 100px;
    }

    .info-item span {
        color: #6b7280;
    }

    /* Badges */
    .role-badge {
        display: inline-block;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
        margin: 0.25rem;
    }

    .permission-tag {
        display: inline-block;
        background: #e0e7ff;
        color: #4338ca;
        padding: 0.35rem 0.85rem;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 500;
        margin: 0.25rem;
        transition: all 0.3s ease;
    }

    .permission-tag:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 8px rgba(67, 56, 202, 0.2);
    }

    /* Section permissions */
    .permissions-section {
        margin-top: 1.5rem;
    }

    .section-title {
        font-size: 1rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-title i {
        color: #667eea;
    }

    /* Grille des permissions */
    .permissions-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        max-height: 500px;
        overflow-y: auto;
        padding: 1rem;
        background: #f9fafb;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
    }

    .permission-group {
        flex: 0 0 calc(50% - 0.5rem);
        background: white;
        border-radius: 10px;
        padding: 1rem;
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
    }

    .permission-group:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }

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

    .permission-list {
        display: flex;
        flex-direction: column;
        gap: 0.6rem;
    }

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

    /* Boutons d'action */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 1.5rem;
        padding-top: 1rem;
        border-top: 1px solid #e5e7eb;
    }

    .btn-secondary, .btn-submit {
        padding: 0.6rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        border: none;
        cursor: pointer;
        font-size: 0.875rem;
    }

    .btn-secondary {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #cbd5e1;
    }

    .btn-secondary:hover {
        background: #e2e8f0;
        transform: translateY(-2px);
        color: #1e293b;
    }

    .btn-submit {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    /* Modal de confirmation */
    .modal {
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

    .modal.show {
        display: flex;
    }

    .modal-content {
        background: white;
        border-radius: 16px;
        max-width: 450px;
        width: 90%;
        padding: 1.5rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        animation: zoomIn 0.3s ease;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .modal-header h5 {
        margin: 0;
        color: #1f2937;
        font-weight: 600;
    }

    .modal-close {
        cursor: pointer;
        font-size: 1.5rem;
        color: #6c757d;
        transition: all 0.3s ease;
        background: none;
        border: none;
    }

    .modal-close:hover {
        color: #dc2626;
    }

    .modal-icon {
        text-align: center;
        margin-bottom: 1rem;
    }

    .modal-body {
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .modal-body h5 {
        margin-bottom: 0.5rem;
        color: #1f2937;
    }

    .modal-body p {
        color: #6b7280;
        font-size: 0.875rem;
    }

    .modal-footer {
        display: flex;
        justify-content: center;
        gap: 0.75rem;
        padding-top: 0.5rem;
        border-top: 1px solid #e5e7eb;
    }

    .modal-btn {
        padding: 0.5rem 1.25rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        font-size: 0.875rem;
    }

    .modal-btn.cancel {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #cbd5e1;
    }

    .modal-btn.cancel:hover {
        background: #e2e8f0;
    }

    .modal-btn.confirm {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .modal-btn.confirm:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
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

    /* Animations */
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
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
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Message vide */
    .empty-message {
        text-align: center;
        padding: 2rem;
        background: #fef9e3;
        border-radius: 12px;
        color: #92400e;
        border: 1px dashed #fbbf24;
    }

    .empty-message i {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        display: block;
    }

    /* Scrollbar personnalisée */
    .permissions-grid::-webkit-scrollbar {
        width: 8px;
    }

    .permissions-grid::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    .permissions-grid::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }

    .permissions-grid::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .permission-group {
            flex: 0 0 100%;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-secondary, .btn-submit {
            justify-content: center;
        }

        .card-header-flex {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;
        }

        .btn-back {
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="row g-3 justify-content-center">
    <div class="col-12 col-lg-10 col-xl-9">
        <div class="card info-card">
            <div class="card-body">
                <!-- En-tête -->
                <div class="card-header-flex">
                    <h6 class="card-title">
                        <i class="bx bx-shield-alt"></i> Gestion des permissions
                    </h6>
                    <a href="{{ route('administrateurs.index') }}" class="btn-back">
                        <i class="bx bx-arrow-back"></i> Retour à la liste
                    </a>
                </div>

                <!-- Informations de l'administrateur -->
                <div class="info-section">
                    <div class="info-grid">
                        <div class="info-item">
                            <i class="bx bx-user-circle"></i>
                            <strong>Nom complet :</strong>
                            <span>{{ $administrateur->nom }} {{ $administrateur->prenom }}</span>
                        </div>
                        <div class="info-item">
                            <i class="bx bx-envelope"></i>
                            <strong>Email :</strong>
                            <span>{{ $administrateur->email }}</span>
                        </div>
                        <div class="info-item">
                            <i class="bx bx-user-tag"></i>
                            <strong>Rôles :</strong>
                            <span>
                                @forelse ($roleNames as $role)
                                    <span class="role-badge">{{ $role }}</span>
                                @empty
                                    <span class="text-muted">Aucun rôle</span>
                                @endforelse
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Permissions héritées des rôles -->
                @if ($permissionsViaRoles->isNotEmpty())
                    <div class="permissions-section">
                        <div class="section-title">
                            <i class="bx bx-git-branch"></i>
                            Permissions héritées des rôles
                            <span class="badge bg-secondary ms-2">{{ $permissionsViaRoles->count() }}</span>
                        </div>
                        <div class="info-section" style="background: #f0fdf4; border-color: #bbf7d0;">
                            @foreach ($permissionsViaRoles as $perm)
                                <span class="permission-tag" style="background: #dcfce7; color: #166534;">
                                    <i class="bx bx-check-circle"></i> {{ $perm->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Permissions directes actuelles -->
                @if ($directPermissions->isNotEmpty())
                    <div class="permissions-section">
                        <div class="section-title">
                            <i class="bx bx-star"></i>
                            Permissions directes actuelles
                            <span class="badge bg-secondary ms-2">{{ $directPermissions->count() }}</span>
                        </div>
                        <div class="info-section" style="background: #eff6ff; border-color: #bfdbfe;">
                            @foreach ($directPermissions as $perm)
                                <span class="permission-tag" style="background: #dbeafe; color: #1e40af;">
                                    <i class="bx bx-award"></i> {{ $perm->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Formulaire d'ajout/modification des permissions directes -->
                <form id="permissionForm" action="{{ route('administrateurs.permissions.store', $administrateur->id) }}"
                    method="POST">
                    @csrf

                    <div class="permissions-section">
                        <div class="section-title">
                            <i class="bx bx-list-ul"></i>
                            Permissions disponibles
                            @if(!$groupedPermissions->isEmpty())
                                <span class="badge bg-secondary ms-2">{{ $groupedPermissions->flatten()->count() }}</span>
                            @endif
                        </div>

                        @if ($groupedPermissions->isEmpty())
                            <div class="empty-message">
                                <i class="bx bx-check-shield"></i>
                                <strong>Toutes les permissions sont déjà attribuées</strong>
                                <p class="mb-0 mt-2">Toutes les permissions disponibles sont déjà attribuées via les rôles ou directement.</p>
                            </div>
                        @else
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
                                                    $checked = in_array($permission->name, $directPermissionNames);
                                                @endphp
                                                <div class="permission-item">
                                                    <input type="checkbox"
                                                           class="permission-checkbox"
                                                           name="permissions[]"
                                                           value="{{ $permission->name }}"
                                                           id="perm_{{ $permission->id }}"
                                                           {{ $checked ? 'checked' : '' }}>
                                                    <label class="permission-label" for="perm_{{ $permission->id }}">
                                                        {{ ucfirst(str_replace('_', ' ', $permission->name)) }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('administrateurs.index') }}" class="btn-secondary">
                            <i class="bx bx-x-circle"></i> Annuler
                        </a>
                        @if ($groupedPermissions->isNotEmpty())
                            @can('modifier-permissions-administrateurs')
                                <button type="button" class="btn-submit" id="openConfirmModal">
                                    <i class="bx bx-save"></i> Enregistrer les permissions
                                </button>
                            @endcan
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation -->
<div id="confirmModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h5>
                <i class="bx bx-question-mark"></i> Confirmation
            </h5>
            <button type="button" class="modal-close" id="closeModal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="modal-icon">
                <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                    colors="primary:#405189,secondary:#f06548" style="width:80px;height:80px;">
                </lord-icon>
            </div>
            <h5>Êtes-vous sûr de vouloir modifier les permissions ?</h5>
            <p>Les permissions sélectionnées seront attribuées directement à l'administrateur.</p>
            <p class="text-warning small">
                <i class="bx bx-info-circle"></i> Cette action peut affecter les accès de l'utilisateur.
            </p>
        </div>
        <div class="modal-footer">
            <button type="button" class="modal-btn cancel" id="cancelModal">Annuler</button>
            <button type="button" class="modal-btn confirm" id="confirmSubmit">Oui, modifier</button>
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
<script src="https://cdn.lordicon.com/lordicon.js"></script>

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
        const confirmModal = document.getElementById('confirmModal');
        const openModalBtn = document.getElementById('openConfirmModal');
        const closeModalBtn = document.getElementById('closeModal');
        const cancelModalBtn = document.getElementById('cancelModal');
        const confirmSubmitBtn = document.getElementById('confirmSubmit');
        const form = document.getElementById('permissionForm');
        const loaderOverlay = document.getElementById('loaderOverlay');

        // Ouvrir la modale
        if (openModalBtn) {
            openModalBtn.addEventListener('click', function() {
                confirmModal.classList.add('show');
            });
        }

        // Fermer la modale
        function closeModal() {
            confirmModal.classList.remove('show');
        }

        if (closeModalBtn) {
            closeModalBtn.addEventListener('click', closeModal);
        }

        if (cancelModalBtn) {
            cancelModalBtn.addEventListener('click', closeModal);
        }

        // Clic en dehors de la modale pour fermer
        window.addEventListener('click', function(e) {
            if (e.target === confirmModal) {
                closeModal();
            }
        });

        // Confirmation et envoi du formulaire
        if (confirmSubmitBtn) {
            confirmSubmitBtn.addEventListener('click', function() {
                // Fermer la modale
                closeModal();

                // Afficher le loader
                loaderOverlay.classList.add('show');

                // Désactiver le bouton d'envoi
                if (openModalBtn) {
                    openModalBtn.disabled = true;
                }

                // Soumettre le formulaire
                form.submit();
            });
        }

        // Animation pour les groupes de permissions
        const permissionGroups = document.querySelectorAll('.permission-group');
        permissionGroups.forEach((group, index) => {
            group.style.animation = `fadeIn 0.3s ease ${index * 0.05}s forwards`;
            group.style.opacity = '0';
        });

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

        // Compteur de permissions sélectionnées
        const checkboxes = document.querySelectorAll('.permission-checkbox');
        const updateSelectedCount = () => {
            const selectedCount = document.querySelectorAll('.permission-checkbox:checked').length;
            const totalCount = checkboxes.length;
            if (selectedCount > 0) {
                console.log(`${selectedCount} permission(s) sélectionnée(s) sur ${totalCount}`);
            }
        };

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectedCount);
        });
    });
</script>
@endpush
