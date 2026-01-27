@extends('layouts.dashboard', ['title' => 'Liste des services', 'toolbar' => '_toolbar2', 'breadcrumb' => 'services'])

@push('css')
    <!-- Application vendor css url -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard/cssbundle/dataTables.min.css') }}">
@endpush

@section('content')
    <div class="row g-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <a href="{{ route('services.index') }}" class="btn btn-primary d-inline">Retour</a>
                    <h6 class="card-title mb-0">Liste des interet du service : {{ $libelle }}</h6>
                    <div class="dropdown morphing scale-left">
                        <a href="#" class="card-fullscreen" data-bs-toggle="tooltip" title="Card Full-Screen"><i
                                class="icon-size-fullscreen"></i></a>
                        <a href="{{ route('ajouter.interetService', $id) }}" class="btn btn-primary d-inline">Ajouter un
                            interet</a>
                    </div>
                </div>
                <div class="card-body" id="show_all_admin">
                    <div class="table-responsive">
                        <table id="myTable" class="table myDataTable table-hover align-middle mb-0 card-table">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Montant debut</th>
                                    <th>Montant fin</th>
                                    <th>Taux d'interêt</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($InteretServices as $index => $interet)
                                    @php
                                        $statusBadge =
                                            $interet->status == 1
                                                ? '<span class="badge bg-success"> Actif </span>'
                                                : '<span class="badge bg-danger"> Inactif </span>';
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <span>{{ formatMontant($interet->montant_debut) }}</span>
                                        </td>
                                        <td>
                                            <span>{{ formatMontant($interet->montant_fin) }}</span>
                                        </td>
                                        <td style="white-space: pre-line;">{{ $interet->taux_interet ?? 0 }} %</td>
                                        <td>{!! $statusBadge !!}</td>
                                        <td>
                                            @if ($interet->status == 2)
                                                <a href="#deleteModal{{ $interet->id }}" id="RestaurerDirection"
                                                    class="btn btn-link btn-sm text-danger refreshIcon"
                                                    data-bs-toggle="modal" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Restaurer"><i class="fa fa-refresh"></i></a>
                                            @else
                                                <a href="{{ route('services.edit', $interet->id) }}" id="EditDirection"
                                                    class="btn btn-link btn-sm text-primary editIcon"
                                                    data-bs-toggle="tooltip" data-bs-target="#edit_direction"
                                                    title="Modifier"><i class="fa fa-pencil"></i></a>
                                                @if (Auth::user()->hasRole('super-administrateur'))
                                                    <a href="#deleteModal{{ $interet->id }}" id="DeleteDirection"
                                                        class="btn btn-link btn-sm text-danger deleteIcon"
                                                        data-bs-toggle="modal" data-bs-toggle="tooltip"
                                                        data-bs-placement="top" data-bs-target="#delete_direction"
                                                        title="Supprimer"><i class="fa fa-trash"></i></a>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                    <!-- Modal delete-->
                                    <div class="modal fade flip" id="deleteModal{{ $interet->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-body p-5 text-center">
                                                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                                                        colors="primary:#405189,secondary:#f06548"
                                                        style="width:90px;height:90px">
                                                    </lord-icon>
                                                    <div class="mt-4 text-center">
                                                        @if ($interet->status == 2)
                                                            <h4>Vous êtes sur le point de restaurer <br>une direction ?</h4>
                                                            <p class="text-muted fs-15 mb-4">En restaurant cette interet,
                                                                vous
                                                                ramener
                                                                <br> toutes les informations la concernant de notre base de
                                                                données.
                                                            </p>
                                                        @else
                                                            <h4>Vous êtes sur le point de supprimer <br>une direction ?</h4>
                                                            <p class="text-muted fs-15 mb-4">En supprimant cette direction,
                                                                vous
                                                                supprimez
                                                                <br> toutes les informations le concernant de notre base de
                                                                données.
                                                            </p>
                                                        @endif
                                                        <div class="hstack gap-2 justify-content-center remove">
                                                            <button
                                                                class="btn btn-link link-success fw-medium text-decoration-none"
                                                                id="deleteRecord-close" data-bs-dismiss="modal"><i
                                                                    class="ri-close-line me-1 align-middle"></i>
                                                                Fermer</button>
                                                            @if ($interet->status == 2)
                                                                <form method="POST"
                                                                    action="{{ route('interet.restaure', $interet->id) }}">
                                                                    @method('PUT')
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-danger"
                                                                        id="delete-record">Oui,
                                                                        restaurer</button>
                                                                </form>
                                                            @else
                                                                <form method="POST"
                                                                    action="{{ route('directions.destroy', $interet->id) }}">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button class="btn btn-danger" id="delete-record">Oui,
                                                                        supprimer</button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end modal -->
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
