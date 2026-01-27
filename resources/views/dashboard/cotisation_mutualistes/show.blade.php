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
                    <h6 class="card-title mb-0">Ligne des Paiement de la cotisation {{ $libelleCotisation }} <br>
                        de {{ $mutualiste->nom }} {{ $mutualiste->prenom }} </h6>
                    <div class="dropdown morphing scale-left">
                        <a href="#" class="card-fullscreen" data-bs-toggle="tooltip"
                            title="Card Full-Screen"><i class="icon-size-fullscreen"></i></a>
                        <a href="{{ route('cotisationmutualistes.index') }}" class="btn btn-primary d-inline">Retour</a>
                    </div>
                </div>
                <div class="card-body" id="show_all_admin">
                    <div class="table-responsive">
                        <table id="myTable" class="table myDataTable table-hover align-middle mb-0 card-table">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Montant</th>
                                    <th>Fréquence de paiement</th>
                                    <th>Date De paiement</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($PaiementCotisations as $index => $cotisationMutualiste)
                                    
                                    <tr>
                                        <td>{{ $index + 1 }}</td>

                                        <td>{{ formatMontant($cotisationMutualiste->montant) }}</td>
                                        <td>{{ $cotisationMutualiste->frequence_paiement }}</td>
                                        <td>{{ datePaiementCotisa($cotisationMutualiste->updated_at) }}</td>

                                        <td><span class="badge bg-success">Solde</span></td>
                                        
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
