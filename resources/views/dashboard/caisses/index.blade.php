{{-- @extends('layouts.dashboard', ['title' => 'Listes des paiement a la caisse', 'toolbar' => '_toolbar2', 'breadcrumb' => 'Caisse'])

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/dashboard/cssbundle/dataTables.min.css') }}">
@endpush

@section('content')
    <div class="row g-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Liste des paiements a la caisse</h6>
                    <div class="dropdown morphing scale-left">
                        <a href="#" class="card-fullscreen" data-bs-toggle="tooltip" title="Card Full-Screen"><i
                                class="icon-size-fullscreen"></i></a>
                        <a href="{{ route('ajouter.paiement') }}" class="btn btn-primary d-inline">Ajouter un paiement </a>
                    </div>
                </div>

                <div class="card-body" id="show_all_grades_piece">
                    <div class="table-responsive">
                        <table id="myTable" class="table myDataTable table-hover align-middle mb-0 card-table">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Mutualiste</th>
                                    <th>Reference</th>
                                    <th>Produit</th>
                                    <th>Montant</th>
                                    <th>Moyen de paiement</th>
                                    <th>Date de paiement</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    use App\Models\ProjetMutualiste;
                                    use App\Models\DemandeAccompagnement;
                                    use App\Models\Service;
                                @endphp
                                @foreach ($paiements as $index => $paiement)
                                    @php
                                        $projets = ProjetMutualiste::where('id', $paiement->produit_id)->first();

                                        if ($paiement->type_paiement_id == 3) {
                                            $etap = DemandeAccompagnement::where(
                                                'id',
                                                $paiement->correspondance_id,
                                            )->value('service_id');
                                            $libelPret = Service::where('id', $etap)->value('libelle');
                                        }
                                        $statusBadge = '';

                                        if ($paiement->status == 1) {
                                            $statusBadge = '<span class="badge bg-success"> Approuvée </span>';
                                        }

                                        if ($paiement->status == 2) {
                                            $statusBadge = '<span class="badge bg-primary"> En attente </span>';
                                        }

                                        if ($paiement->status == 3) {
                                            $statusBadge = '<span class="badge bg-danger"> Rejetée </span>';
                                        }

                                        $imgUrl = $paiement->mutualiste->lien_photo
                                            ? asset($paiement->mutualiste->lien_photo)
                                            : asset('assets/dashboard/img/default-img.png');
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <img src="{{ $imgUrl }}" class="avatar sm rounded me-2"
                                                alt="profile-image">
                                            <span>
                                                {{ formatGender($paiement->mutualiste->genre) }}
                                                {{ $paiement->mutualiste->nom }} {{ $paiement->mutualiste->prenom }}</span>
                                        </td>
                                        <td>{{ $paiement->reference ?? $paiement->code_paiement?? "XXXXXXXXX" }}</td>
                                        <td>{{ $projets->libelle ?? ($libelPret ?? $paiement->typePaiement->libelle ?? 'Droit d Adhesion')??'Droit d Adhesion' }}</td>
                                        <td>{{ formatMontant($paiement->montant_initial) }}</td>
                                        <td>{{ $paiement->moyen_paiement ?? "en Ligne"}}</td>
                                        <td>{{ $paiement->date_paiement_initial ?? "XXXXXXXXX"}}</td>
                                        <td>{!! $statusBadge !!}</td>
                                        <td>
                                            <a href="{{ route('detail.paiementcash', $paiement->id) }}" id="ShowGrade"
                                                class="btn btn-link btn-sm text-success infoIcon" data-bs-toggle="tooltip"
                                                data-bs-toggle="modal" data-bs-target="#info_type_piece"
                                                data-bs-placement="top" title="Infos"><i class="fa fa-eye"></i></a>
                                            @if ($paiement->p_cash == 1)
                                                <a href="{{ route('galerie.docPaiement', $paiement->id) }}" id="EditCompte"
                                                    class="btn btn-link btn-sm text-primary editIcon"
                                                    data-bs-target="#edit_type_piece" title="Images"><i
                                                        class="bi bi-images"></i></a>
                                            @endif

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <!-- Plugin Js -->
    <script src="{{ asset('assets/dashboard/js/bundle/dataTables.bundle.js') }}"></script>
    <!-- Vendor Script -->
    <script>
        // Data Table
        var langUrl = "{{ asset('DataTables/fr-FR.json') }}";

        $(document).ready(function() {
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
                    // [5, 10, 25, 50, 100, 150, 200, 250],
                ],
                dom: 'Bfrtip', // Add this line to include Buttons
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
        });
    </script>
@endpush --}}




@extends('layouts.dashboard', ['title' => 'Listes des paiement a la caisse', 'toolbar' => '_toolbar2', 'breadcrumb' => 'Caisse'])

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/dashboard/cssbundle/dataTables.min.css') }}">
@endpush

@section('content')
    <div class="row g-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Liste des paiements a la caisse</h6>
                    <div class="dropdown morphing scale-left">
                        <a href="#" class="card-fullscreen" data-bs-toggle="tooltip" title="Card Full-Screen"><i
                                class="icon-size-fullscreen"></i></a>
                        <a href="{{ route('ajouter.paiement') }}" class="btn btn-primary d-inline">Ajouter un paiement </a>
                    </div>
                </div>

                <div class="card-body" id="show_all_grades_piece">
                    <div class="table-responsive">
                        <table id="myTable" class="table myDataTable table-hover align-middle mb-0 card-table">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Mutualiste</th>
                                    <th>Référence</th>
                                    <th>Produit / Service</th>
                                    <th>Montant</th>
                                    <th>Moyen de paiement</th>
                                    <th>Date de paiement</th>
                                    <th>Statut</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($paiements as $index => $paiement)
                                    @php
                                        // Détermination du libellé du produit/service
                                        $produitLibelle = '';

                                        if ($paiement->type_paiement_id == 1) {
                                            $produitLibelle = 'Droit d\'adhésion';
                                        } elseif ($paiement->type_paiement_id == 2) {
                                            $produitLibelle = 'Cotisation';
                                        } elseif ($paiement->type_paiement_id == 3) {
                                            // Projet mutualiste
                                            if (!empty($paiement->produit_id)) {
                                                $projet = \App\Models\ProjetMutualiste::find($paiement->produit_id);
                                                if ($projet) {
                                                    $produitLibelle = $projet->libelle . ' (Projet)';
                                                }
                                            }
                                            // Service d'accompagnement
    if (empty($produitLibelle) && !empty($paiement->correspondance_id)) {
        $demande = \App\Models\DemandeAccompagnement::find(
            $paiement->correspondance_id,
        );
        if ($demande && $demande->service_id) {
            $service = \App\Models\Service::find($demande->service_id);
            if ($service) {
                $produitLibelle = $service->libelle . ' (Accompagnement)';
            }
        }
    }
    if (empty($produitLibelle)) {
        $produitLibelle = 'Produit particulier';
    }
} elseif ($paiement->typePaiement) {
    $produitLibelle = $paiement->typePaiement->libelle;
} else {
    $produitLibelle = 'Paiement';
}

// Badge de statut




 $statusBadge = '';

                                        if ($paiement->status == 1) {
                                            $statusBadge = '<span class="badge bg-success"> Approuvée </span>';
                                        }

                                        if ($paiement->status == 2) {
                                            $statusBadge = '<span class="badge bg-primary"> En attente </span>';
                                        }

                                        if ($paiement->status == 3) {
                                            $statusBadge = '<span class="badge bg-danger"> Rejetée </span>';
                                        }



// Photo
$imgUrl = $paiement->mutualiste->lien_photo
    ? asset($paiement->mutualiste->lien_photo)
    : asset('assets/dashboard/img/default-img.png');

// Moyen de paiement lisible
$moyenPaiement = match ($paiement->moyen_paiement) {
    'cash' => 'Espèces',
    'cheque' => 'Chèque',
    'virement' => 'Virement',
    'en_ligne' => 'En ligne (TresorMoney)',
    default => $paiement->moyen_paiement ?? 'Non renseigné',
};

// === Gestion sécurisée de la date ===
$datePaiement =
    $paiement->date_paiement_initial ?? ($paiement->created_at ?? null);
$formattedDate = 'Non définie';

if ($datePaiement) {
    try {
        // Si c'est déjà une instance Carbon
                                                if ($datePaiement instanceof \Carbon\Carbon) {
                                                    $formattedDate = $datePaiement->format('d/m/Y');
                                                }
                                                // Si c'est une string au format Y-m-d
        elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $datePaiement)) {
            $formattedDate = \Carbon\Carbon::parse($datePaiement)->format(
                'd/m/Y',
            );
        }
        // Si c'est une string au format d/m/Y (éviter double parsing)
                                                elseif (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $datePaiement)) {
                                                    $formattedDate = $datePaiement; // déjà au bon format
                                                }
                                                // Autre format tenté avec createFromFormat
                                                else {
                                                    $carbon = \Carbon\Carbon::createFromFormat('d/m/Y', $datePaiement);
                                                    if ($carbon) {
                                                        $formattedDate = $carbon->format('d/m/Y');
                                                    } else {
                                                        $formattedDate = $datePaiement;
                                                    }
                                                }
                                            } catch (\Exception $e) {
                                                $formattedDate = $datePaiement; // fallback brut
                                            }
                                        }

                                        $reference =
                                            $paiement->reference ?? ($paiement->code_paiement ?? 'Non définie');
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <img src="{{ $imgUrl }}" class="avatar sm rounded me-2"
                                                alt="profile-image">
                                            <span>{{ formatGender($paiement->mutualiste->genre) }}
                                                {{ $paiement->mutualiste->nom }} {{ $paiement->mutualiste->prenom }}</span>
                                        </td>
                                        <td>{{ $reference }}</td>
                                        <td>{{ $produitLibelle }}</td>
                                        <td>{{ formatMontant($paiement->montant_initial) }}</td>
                                        <td>{{ $moyenPaiement }}</td>
                                        <td>{{ $formattedDate }}</td>
                                        <td>{!! $statusBadge !!}</td>
                                        <td>
                                            <a href="{{ route('detail.paiementcash', $paiement->id) }}"
                                                class="btn btn-link btn-sm text-success infoIcon" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="Voir détails">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            @if ($paiement->p_cash == 1)
                                                <a href="{{ route('galerie.docPaiement', $paiement->id) }}"
                                                    class="btn btn-link btn-sm text-primary editIcon"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Images">
                                                    <i class="bi bi-images"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/dashboard/js/bundle/dataTables.bundle.js') }}"></script>
    <script>
        var langUrl = "{{ asset('DataTables/fr-FR.json') }}";

        $(document).ready(function() {
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
                    [5, 10, 25, 50, 100],
                    [5, 10, 25, 50, 100]
                ],
                dom: 'Bfrtip',
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
            });
        });
    </script>
@endpush
