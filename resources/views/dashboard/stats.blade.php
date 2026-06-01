@extends('layouts.dashboard', ['title' => 'FPM MUTUALPAY', 'breadcrumb' => 'STATISTIQUES', 'toolbar' => '_toolbar'])

@section('content')
<div class="container-fluid">
    <!-- Ligne des cartes -->
    <div class="row">
        <div class="col-md">
            <div class="card">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar rounded-circle no-thumbnail bg-light"><i class="fa fa-money fa-lg"></i></div>
                    <div class="flex-fill ms-3">
                        <div class="small text-uppercase">Total Général</div>
                        <div><span class="h6 mb-0 fw-bold">{{ number_format($montantTotal, 0, ',', ' ') }}</span> <small>Fcfa</small></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md">
            <div class="card">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar rounded-circle no-thumbnail bg-light"><i class="fa fa-user-plus fa-lg"></i></div>
                    <div class="flex-fill ms-3">
                        <div class="small text-uppercase">Adhésions</div>
                        <div><span class="h6 mb-0 fw-bold">{{ number_format($mntAdhesion, 0, ',', ' ') }}</span> <small>Fcfa</small></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md">
            <div class="card">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar rounded-circle no-thumbnail bg-light"><i  class="fa fa-money fa-lg"></i></div>
                    <div class="flex-fill ms-3">
                        <div class="small text-uppercase">Cotisations</div>
                        <div><span class="h6 mb-0 fw-bold">{{ number_format($mntCotisation, 0, ',', ' ') }}</span> <small>Fcfa</small></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md">
            <div class="card">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar rounded-circle no-thumbnail bg-light"><i  class="fa fa-money fa-lg"></i></div>
                    <div class="flex-fill ms-3">
                        <div class="small text-uppercase">Prêts</div>
                        <div><span class="h6 mb-0 fw-bold">{{ number_format($mntPret, 0, ',', ' ') }}</span> <small>Fcfa</small></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md">
            <div class="card">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar rounded-circle no-thumbnail bg-light"><i  class="fa fa-money fa-lg"></i></div>
                    <div class="flex-fill ms-3">
                        <div class="small text-uppercase">Projets</div>
                        <div><span class="h6 mb-0 fw-bold">{{ number_format($mntProjet, 0, ',', ' ') }}</span> <small>Fcfa</small></div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="col-md">
            <div class="card">
                <div class="card-body d-flex align-items-center">
                    <div class="avatar rounded-circle no-thumbnail bg-light"><i  class="fa fa-money fa-lg"></i></div>
                    <div class="flex-fill ms-3">
                        <div class="small text-uppercase">Renouvellements  </div>
                        <div><span class="h6 mb-0 fw-bold">{{ number_format($mntCarte, 0, ',', ' ') }}</span> <small>Fcfa</small></div>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>

    <!-- Graphique -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Évolution des paiements par mois (tous types)</h5>
                </div>
                <div class="card-body">
                    <canvas id="paiementsChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Sélecteur mutualiste + liste de ses paiements -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Paiements par mutualiste</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="mutualisteSelect" class="form-label">Choisir un mutualiste</label>
                            <select id="mutualisteSelect" class="form-select">
                                <option value="">-- Sélectionnez --</option>
                                @foreach($mutualistes as $mut)
                                    <option value="{{ $mut->id }}">{{ $mut->nom }} {{ $mut->prenom }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div id="paiementsListe">
                        <div class="alert alert-info">Sélectionnez un mutualiste pour voir ses paiements.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 1. Graphique
    const labels = @json($labels);
    const dataValues = @json($data);

    const ctx = document.getElementById('paiementsChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Montant total (Fcfa)',
                data: dataValues,
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13,110,253,0.1)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString() + ' Fcfa';
                        }
                    }
                }
            }
        }
    });

    // 2. Chargement des paiements d'un mutualiste (AJAX)
    const selectMutualiste = document.getElementById('mutualisteSelect');
    const paiementsDiv = document.getElementById('paiementsListe');

    selectMutualiste.addEventListener('change', function() {
        const mutualisteId = this.value;
        if (!mutualisteId) {
            paiementsDiv.innerHTML = '<div class="alert alert-info">Sélectionnez un mutualiste pour voir ses paiements.</div>';
            return;
        }

        // Afficher un loader
        paiementsDiv.innerHTML = '<div class="text-center"><i class="fa fa-spinner fa-spin"></i> Chargement...</div>';

        fetch(`/admin/mutualiste-paiements/${mutualisteId}`)  // ← route à créer
            .then(response => response.json())
            .then(data => {
                if (data.length === 0) {
                    paiementsDiv.innerHTML = '<div class="alert alert-warning">Aucun paiement pour ce mutualiste.</div>';
                    return;
                }
                let html = '<table class="table table-bordered table-striped">';
                html += '<thead><tr><th>Date</th><th>Type</th><th>Montant (Fcfa)</th><th>Statut</th></tr></thead><tbody>';
                data.forEach(p => {
                    html += `<tr>
                        <td>${p.date}</td>
                        <td>${p.type_paiement}</td>
                        <td>${Number(p.montant).toLocaleString()}</td>
                        <td>${p.status == 1 ? 'Actif' : 'Inactif'}</td>
                    </tr>`;
                });
                html += '</tbody></table>';
                paiementsDiv.innerHTML = html;
            })
            .catch(error => {
                console.error(error);
                paiementsDiv.innerHTML = '<div class="alert alert-danger">Erreur lors du chargement.</div>';
            });
    });
</script>
@endpush
