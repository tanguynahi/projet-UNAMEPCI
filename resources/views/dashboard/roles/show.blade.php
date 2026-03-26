@extends('layouts.dashboard', [
    'title' => 'Information d\'un rôle',
    'toolbar' => '_toolbar2',
    'breadcrumb' => 'Informations Rôle'
])

@push('css')
<style>
    /* Styles principaux */
    .role-info-card {
        border: none;
        box-shadow: 0 0 20px rgba(0,0,0,0.08);
    }

    .role-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 1.5rem;
        border-radius: 12px 12px 0 0;
        color: white;
    }

    .role-name {
        font-size: 1.5rem;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .role-name i {
        font-size: 2rem;
    }

    .role-badge {
        background: rgba(255,255,255,0.2);
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        display: inline-block;
    }

    .permissions-section {
        margin-top: 2rem;
    }

    .permissions-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid #e5e7eb;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .permissions-title i {
        color: #667eea;
    }

    .permissions-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 1.25rem;
    }

    .permission-group {
        flex: 0 0 calc(50% - 0.625rem);
        background: #f8fafc;
        border-radius: 12px;
        padding: 1rem;
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
    }

    .permission-group:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        border-color: #cbd5e1;
    }

    .permission-group h6 {
        color: #1e293b;
        font-weight: 600;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .permission-group h6 i {
        color: #667eea;
        font-size: 1rem;
    }

    .permission-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .permission-tag {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 500;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .permission-tag:hover {
        transform: scale(1.05);
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
    }

    .permission-tag::before {
        content: "✓";
        font-weight: bold;
    }

    .no-permissions {
        text-align: center;
        padding: 3rem;
        background: #fef2f2;
        border-radius: 12px;
        color: #dc2626;
        font-weight: 500;
        border: 1px dashed #fecaca;
    }

    .no-permissions i {
        font-size: 2rem;
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-actions {
        display: flex;
        justify-content: center;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e5e7eb;
    }

    .btn-primary-custom, .btn-secondary-custom {
        padding: 0.6rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-primary-custom {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
    }

    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        color: white;
    }

    .btn-secondary-custom {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #cbd5e1;
    }

    .btn-secondary-custom:hover {
        background: #e2e8f0;
        transform: translateY(-2px);
        color: #1e293b;
    }

    .stats-badge {
        background: #f1f5f9;
        padding: 0.5rem 1rem;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #475569;
        font-size: 0.875rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .permission-group {
            flex: 0 0 100%;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-primary-custom, .btn-secondary-custom {
            justify-content: center;
        }

        .role-name {
            font-size: 1.2rem;
            flex-direction: column;
            text-align: center;
        }
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

    .permission-group {
        animation: fadeIn 0.4s ease forwards;
        opacity: 0;
    }

    .permission-group:nth-child(1) { animation-delay: 0.05s; }
    .permission-group:nth-child(2) { animation-delay: 0.1s; }
    .permission-group:nth-child(3) { animation-delay: 0.15s; }
    .permission-group:nth-child(4) { animation-delay: 0.2s; }
    .permission-group:nth-child(5) { animation-delay: 0.25s; }
    .permission-group:nth-child(6) { animation-delay: 0.3s; }
</style>
@endpush

@section('content')
    <div class="row g-3 justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
            <div class="card role-info-card">
                <!-- En-tête avec actions -->
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <h6 class="card-title mb-0">
                        <i class="bx bx-info-circle"></i> Détails du rôle
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
                    <!-- Section informations du rôle -->
                    <div class="role-header mb-4">
                        <div class="role-name">
                            <i class="bx bx-user-tag"></i>
                            <span>{{ $role->name }}</span>
                        </div>
                        <div class="mt-3 d-flex gap-2 flex-wrap">
                            <div class="stats-badge">
                                <i class="bx bx-lock"></i>
                                <span>ID: {{ $role->id }}</span>
                            </div>
                            <div class="stats-badge">
                                <i class="bx bx-calendar"></i>
                                <span>Créé le: {{ $role->created_at ? $role->created_at->format('d/m/Y') : 'N/A' }}</span>
                            </div>
                            @if($role->updated_at)
                            <div class="stats-badge">
                                <i class="bx bx-time"></i>
                                <span>Modifié le: {{ $role->updated_at->format('d/m/Y') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Section permissions -->
                    <div class="permissions-section">
                        <div class="permissions-title">
                            <i class="bx bx-check-shield"></i>
                            Permissions associées
                            @if(!empty($rolePermissions))
                                <span class="badge bg-primary rounded-pill ms-2">
                                    {{ count($rolePermissions) }} permission(s)
                                </span>
                            @endif
                        </div>

                        @if (empty($rolePermissions))
                            <div class="no-permissions">
                                <i class="bx bx-lock-open-alt"></i>
                                Aucune permission attribuée à ce rôle.
                            </div>
                        @else
                            <div class="permissions-grid">
                                @foreach ($groupedPermissions as $action => $permissions)
                                    @php
                                        // Filtrer les permissions du rôle pour ce groupe
                                        $rolePermsForAction = $permissions->filter(function ($perm) use (
                                            $rolePermissions,
                                        ) {
                                            return in_array($perm->name, $rolePermissions);
                                        });
                                    @endphp
                                    @if ($rolePermsForAction->isNotEmpty())
                                        <div class="permission-group">
                                            <h6>
                                                <i class="bx bx-folder"></i>
                                                {{ ucfirst(str_replace('_', ' ', $action)) }}
                                                <span class="badge bg-secondary float-end">
                                                    {{ $rolePermsForAction->count() }}
                                                </span>
                                            </h6>
                                            <div class="permission-tags">
                                                @foreach ($rolePermsForAction as $permission)
                                                    <span class="permission-tag">
                                                        {{ ucfirst(str_replace('_', ' ', $permission->name)) }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="form-actions">
                        <a href="{{ route('roles.index') }}" class="btn-secondary-custom">
                            <i class="bx bx-list-ul"></i> Retour à la liste
                        </a>
                        @can('modifier-role')
                            <a href="{{ route('roles.edit', $role->id) }}" class="btn-primary-custom">
                                <i class="bx bx-edit"></i> Modifier ce rôle
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animation pour les tags de permission
            const permissionTags = document.querySelectorAll('.permission-tag');
            permissionTags.forEach((tag, index) => {
                tag.style.animation = `fadeIn 0.3s ease ${index * 0.02}s forwards`;
                tag.style.opacity = '0';
            });

            // Tooltips initialization (si vous utilisez Bootstrap)
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Effet de compteur pour le nombre de permissions
            const permissionCount = {{ count($rolePermissions ?? []) }};
            if (permissionCount > 0) {
                const countBadge = document.querySelector('.permissions-title .badge');
                if (countBadge) {
                    let currentCount = 0;
                    const incrementInterval = setInterval(() => {
                        if (currentCount < permissionCount) {
                            currentCount++;
                            countBadge.textContent = `${currentCount} permission(s)`;
                        } else {
                            clearInterval(incrementInterval);
                        }
                    }, 20);
                }
            }
        });
    </script>
@endpush
