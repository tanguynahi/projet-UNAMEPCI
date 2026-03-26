@extends('layouts.dashboard', [
    'title' => "Détails de l'administrateur",
    'toolbar' => '_toolbar2',
    'breadcrumb' => 'Détails Administrateur',
])

@push('css')
    <!-- Application vendor css url -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard/cssbundle/dataTables.min.css') }}">
    <style>
        /* Styles personnalisés */
        .detail-card {
            border: none;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.08);
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
            flex-wrap: wrap;
            gap: 1rem;
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

        /* Boutons d'action */
        .btn-back, .btn-edit {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
        }

        .btn-back {
            background: #f3f4f6;
            color: #374151;
        }

        .btn-back:hover {
            background: #e5e7eb;
            transform: translateY(-2px);
        }

        .btn-edit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            margin-left: 0.5rem;
        }

        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        /* Photo de profil */
        .photo-container {
            display: flex;
            justify-content: center;
            margin-bottom: 1.5rem;
        }

        .admin-photo {
            position: relative;
            display: inline-block;
        }

        .admin-photo img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #667eea;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .admin-photo img:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
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
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
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
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .info-item i {
            font-size: 1.2rem;
            color: #667eea;
            width: 24px;
        }

        .info-item strong {
            color: #374151;
            font-weight: 600;
            min-width: 120px;
        }

        .info-item span {
            color: #6b7280;
        }

        .info-item .highlight {
            color: #e65c00;
            font-weight: 500;
        }

        /* Badges */
        .role-badge {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 0.35rem 0.85rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            margin: 0.25rem;
            transition: all 0.3s ease;
        }

        .role-badge:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
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

        /* Section titre */
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

        /* Bouton gestion permissions */
        .btn-manage-permissions {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .btn-manage-permissions:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
            color: white;
        }

        /* Bouton retour */
        .btn-cancel {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #6c757d;
            color: white;
            padding: 0.6rem 1.5rem;
            border-radius: 10px;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .btn-cancel:hover {
            background: #5a6268;
            transform: translateY(-2px);
            color: white;
        }

        /* Form actions */
        .form-actions {
            display: flex;
            justify-content: center;
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid #e5e7eb;
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

        .alert-error ul {
            margin: 0;
            padding-left: 1.5rem;
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

        /* Responsive */
        @media (max-width: 768px) {
            .card-header-flex {
                flex-direction: column;
                align-items: stretch;
            }

            .card-header-flex div {
                display: flex;
                justify-content: center;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .info-item {
                flex-direction: column;
                text-align: center;
            }

            .info-item strong {
                min-width: auto;
            }

            .btn-back, .btn-edit {
                justify-content: center;
            }

            .form-actions {
                flex-direction: column;
                align-items: center;
            }
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 1.5rem;
            background: #fef9e3;
            border-radius: 12px;
            color: #92400e;
        }
    </style>
@endpush

@section('content')
    <div class="row g-3 justify-content-center">
        <div class="col-12 col-lg-10 col-xl-9">
            <!-- Messages de succès/erreur -->
            @if (session('success'))
                <div class="alert alert-success">
                    <i class="bx bx-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error">
                    <i class="bx bx-error-circle"></i>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card detail-card">
                <div class="card-body">
                    <!-- En-tête -->
                    <div class="card-header-flex">
                        <h6 class="card-title">
                            <i class="bx bx-user-circle"></i> Détails de l'administrateur
                        </h6>
                        <div>
                            <a href="{{ route('administrateurs.index') }}" class="btn-back">
                                <i class="bx bx-arrow-back"></i> Retour
                            </a>

                        </div>
                    </div>

                    <!-- Photo de profil -->
                    @if ($administrateur->lien_photo)
                        <div class="photo-container">
                            <div class="admin-photo">
                                @php
                                    $imgUrl = $administrateur->lien_photo
                                        ? asset($administrateur->lien_photo)
                                        : asset('assets/dashboard/img/default-img.png');
                                @endphp
                                <img src="{{ $imgUrl }}"
                                    alt="Photo de {{ $administrateur->nom }} {{ $administrateur->prenom }}">
                            </div>
                        </div>
                    @endif

                    <!-- Informations personnelles -->
                    <div class="info-section">
                        <div class="section-title">
                            <i class="bx bx-info-circle"></i> Informations personnelles
                        </div>
                        <div class="info-grid">
                            <div class="info-item">
                                <i class="bx bx-user"></i>
                                <strong>Nom complet :</strong>
                                <span>{{ $administrateur->nom }} {{ $administrateur->prenom }}</span>
                            </div>
                            <div class="info-item">
                                <i class="bx bx-envelope"></i>
                                <strong>Email :</strong>
                                <span>{{ $administrateur->email }}</span>
                            </div>
                            <div class="info-item">
                                <i class="bx bx-phone"></i>
                                <strong>Contact :</strong>
                                <span>{{ $administrateur->contact ?? 'Non renseigné' }}</span>
                            </div>
                            <div class="info-item">
                                <i class="bx bx-map"></i>
                                <strong>Adresse :</strong>
                                <span>{{ $administrateur->adresse ?? 'Non renseignée' }}</span>
                            </div>
                            <div class="info-item">
                                <i class="bx bx-male-female"></i>
                                <strong>Genre :</strong>
                                <span>
                                    @if ($administrateur->genre)
                                        {{ ucfirst($administrateur->genre) }}
                                    @else
                                        Non renseigné
                                    @endif
                                </span>
                            </div>

                            <div class="info-item">
                                <i class="bx bx-calendar"></i>
                                <strong>Date création :</strong>
                                <span>
                                    {{ $administrateur->created_at ? $administrateur->created_at->format('d/m/Y à H:i') : 'Non renseignée' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Rôles -->
                    <div class="section-title">
                        <i class="bx bx-user-tag"></i> Rôles
                    </div>
                    <div class="info-section" style="background: #f0fdf4;">
                        @forelse ($roleNames as $role)
                            <span class="role-badge">
                                <i class="bx bx-check-circle"></i> {{ $role }}
                            </span>
                        @empty
                            <div class="empty-state">
                                <i class="bx bx-info-circle"></i>
                                <p class="mb-0">Aucun rôle attribué</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Permissions héritées des rôles -->
                    @if ($permissionsViaRoles->isNotEmpty())
                        <div class="section-title mt-3">
                            <i class="bx bx-git-branch"></i> Permissions héritées des rôles
                        </div>
                        <div class="info-section" style="background: #eff6ff;">
                            @foreach ($permissionsViaRoles as $perm)
                                <span class="permission-tag">
                                    <i class="bx bx-check-shield"></i> {{ $perm->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    <!-- Permissions directes -->
                    @php
                        $directPermissions = $administrateur->user->getDirectPermissions();
                    @endphp
                    @if ($directPermissions->isNotEmpty())
                        <div class="section-title mt-3">
                            <i class="bx bx-star"></i> Permissions directes
                        </div>
                        <div class="info-section" style="background: #fef3c7;">
                            @foreach ($directPermissions as $perm)
                                <span class="permission-tag" style="background: #fed7aa; color: #9b2c1d;">
                                    <i class="bx bx-award"></i> {{ $perm->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    <!-- Lien vers la gestion des permissions directes -->
                    @can('afficher-permissions-administrateurs')
                        <div class="text-center mt-3">
                            <a href="{{ route('administrateurs.permissions', $administrateur->id) }}" class="btn-manage-permissions">
                                <i class="bx bx-lock-alt"></i> Gérer les permissions directes
                            </a>
                        </div>
                    @endcan

                    <!-- Bouton retour -->
                    <div class="form-actions">
                        <a href="{{ route('administrateurs.index') }}" class="btn-cancel">
                            <i class="bx bx-list-ul"></i> Retour à la liste
                        </a>
                    </div>
                </div>
            </div>
        </div>
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

            // Auto-fermeture des alertes après 5 secondes
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        alert.remove();
                    }, 300);
                }, 5000);
            });
        });
    </script>
@endpush
