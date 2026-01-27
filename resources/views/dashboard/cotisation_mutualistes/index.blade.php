@extends('layouts.dashboard', ['title' => 'Liste des cotisations Attribuer', 'toolbar' => '_toolbar2', 'breadcrumb' => 'Cotisations Mutualistes'])

@push('css')
    <!-- Application vendor css url -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard/cssbundle/dataTables.min.css') }}">
@endpush

@section('content')
    <div class="row g-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Liste des cotisations Des Mutualistes</h6>
                    <div class="dropdown morphing scale-left">
                        <a href="#" class="card-fullscreen" data-bs-toggle="tooltip" title="Card Full-Screen"><i
                                class="icon-size-fullscreen"></i></a>
                        <a href="{{ route('cotisationmutualistes.create') }}" class="btn btn-primary d-inline">Attribuer une
                            cotisation </a>
                    </div>
                </div>
                <div class="card-body" id="show_all_admin">
                    <div class="table-responsive">
                        <table id="myTable" class="table myDataTable table-hover align-middle mb-0 card-table">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Mutualiste</th>
                                    <th>Cotisation</th>
                                    <th>Montant</th>
                                    <th>Fréquence de paiement</th>
                                    <th>Date debut</th>
                                    <th>Date Fin</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cotisationMutualistes as $index => $cotisationMutualiste)
                                    @php
                                        $imgUrl = $cotisationMutualiste->mutualiste->lien_photo
                                            ? asset($cotisationMutualiste->mutualiste->lien_photo)
                                            : asset('assets/dashboard/img/default-img.png');
                                        // les status  1 solde a l'instant , 2 en attente ,3 supprimer ,4 solde ne s'affiche pas chez le mutualiste
                                        $statusBadge = '';

                                        if ($cotisationMutualiste->status == 3) {
                                            $statusBadge = '<span class="badge bg-danger">Inactif</span>';
                                        } elseif ($cotisationMutualiste->status == 5) {
                                            $statusBadge = '<span class="badge bg-success">Terminer</span>';
                                        } else {
                                            $statusBadge = '<span class="badge bg-primary">En Cours</span>';
                                        }

                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <img src="{{ $imgUrl }}" class="avatar sm rounded me-2"
                                                alt="profile-image">
                                            <span>{{ $cotisationMutualiste->mutualiste->nom }}
                                                {{ $cotisationMutualiste->mutualiste->prenom }}</span> <br>
                                            <strong>{{ $cotisationMutualiste->mutualiste->contact }}</strong>
                                        </td>
                                        <td>
                                            <span>{{ $cotisationMutualiste->cotisation->libelle }}</span>
                                        </td>
                                        <td>{{ formatMontant($cotisationMutualiste->montant) }}</td>
                                        <td>{{ $cotisationMutualiste->frequence_paiement }}</td>
                                        <td>{{ formatDate($cotisationMutualiste->date_debut) }}</td>
                                        <td>{{ formatDate($cotisationMutualiste->date_fin) }}</td>

                                        <td>{!! $statusBadge !!}</td>
                                        <td>
                                            <a href="{{ route('lignePaiement.cotis', ['idCoti' => $cotisationMutualiste->cotisation_id, 'idMutual' => $cotisationMutualiste->mutualiste_id]) }}"
                                                id="EditDirection" class="btn btn-link btn-sm text-primary editIcon"
                                                data-bs-toggle="tooltip" data-bs-target="#edit_direction"
                                                title="Detail Paiement"><i class="bi bi-eye"></i></a>
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
@endpush
