@extends('layouts.dashboard', ['title' => 'Liste des facturations', 'toolbar' => '_toolbar2', 'breadcrumb' => 'Facturations'])

@push('css')
    <!-- Application vendor css url -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard/cssbundle/dataTables.min.css') }}">
@endpush

@section('content')
    <div class="row g-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Liste des facturations</h6>
                    <div class="dropdown morphing scale-left">
                        <a href="#" class="card-fullscreen" data-bs-toggle="tooltip" title="Card Full-Screen"><i
                                class="icon-size-fullscreen"></i></a>
                        <a href="{{ route('facturations.create') }}" class="btn btn-primary d-inline">Ajouter une
                            facturation</a>
                    </div>
                </div>
                <div class="card-body" id="show_all_facturations">
                    <div class="table-responsive">
                        <table id="myTable" class="table myDataTable table-hover align-middle mb-0 card-table">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Mutualiste</th>
                                    <th>Bien acquis</th>
                                    <th>Redevance</th>
                                    <th>Total à payer</th>
                                    <th>Période</th>
                                    <th>Montant périodique</th>
                                    <th>Reste à payer</th>
                                    <th>Date-début : </th>
                                    <th>Date-fin : </th>
                                    <th>Date-facturation : </th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($facturations as $index => $facturation)
                                    @php
                                        $lienImage = $facturation->produitProjet->lien_photo
                                            ? asset($facturation->produitProjet->lien_photo)
                                            : asset('assets/dashboard/img/default-img.png');
                                        $statusBadge = '';

                                        if ($facturation->status == 1) {
                                            $statusBadge = '<span class="badge bg-success"> En cours </span>';
                                        }
                                        if ($facturation->status == 2) {
                                            $statusBadge = '<span class="badge bg-danger"> Inactif </span>';
                                        }
                                        if ($facturation->status == 4) {
                                            $statusBadge = '<span class="badge bg-success">Terminer</span>';
                                        }
                                        // 1 impayer , 3 payer , 2 suprpimer  , 4 solder terminer
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <span>{{ formatGender($facturation->mutualiste->genre) }}
                                                {{ $facturation->mutualiste->nom }}
                                                {{ $facturation->mutualiste->prenom }} <br>
                                                ({{ $facturation->mutualiste->contact }})
                                            </span>
                                        </td>
                                        <td> <img src="{{ $lienImage }}" class="avatar sm rounded me-2"
                                                alt="Image projet">{{ $facturation->projetMutualiste->libelle }} <br>
                                            ({{ $facturation->produitProjet->projet->libelle }})</td>
                                        <td>{{ $facturation->redevance->libelle }}</td>
                                        <td class="text-danger">{{ formatMontant($facturation->total_apayer) }}</td>
                                        <td>{{ $facturation->periode->libelle }}</td>
                                        <td class="text-secondary">
                                            {{ $facturation->montant_periodique ? formatMontant($facturation->montant_periodique) : '' }}
                                        </td>
                                        <td>{{ formatMontant($facturation->reste_apayer) }}</td>
                                        <td>{{ $facturation->date_debut ? formatDate($facturation->date_debut) : '' }}</td>
                                        <td>{{ $facturation->date_fin ? formatDate($facturation->date_fin) : '' }}</td>
                                        <td>{{ formatDate($facturation->date_facturation) }}</td>
                                        <td>{!! $statusBadge !!}</td>
                                        <td>
                                            @if ($facturation->status == 2)
                                                <a href="#deleteModal{{ $facturation->id }}" id="RestaurerFacturation"
                                                    class="btn btn-link btn-sm text-danger refreshIcon"
                                                    data-bs-toggle="modal" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Restaurer"><i class="fa fa-refresh"></i></a>
                                            @else
                                                <a href="{{ route('facturations.show', $facturation->id) }}"
                                                    id="ShowFacturation" class="btn btn-link btn-sm text-success infoIcon"
                                                    data-bs-toggle="tooltip" data-bs-toggle="modal"
                                                    data-bs-target="#info_facturation" data-bs-placement="top"
                                                    title="Infos"><i class="fa fa-eye"></i></a>

                                                @if (Auth::user()->hasRole('super-administrateur'))
                                                    <a href="{{ route('facturations.edit', $facturation->id) }}"
                                                        id="EditFacturation"
                                                        class="btn btn-link btn-sm text-primary editIcon"
                                                        data-bs-toggle="tooltip" data-bs-target="#edit_Facturatione"
                                                        title="Modifier"><i class="fa fa-pencil"></i></a>
                                                    <a href="#deleteModal{{ $facturation->id }}" id="DeleteFacturation"
                                                        class="btn btn-link btn-sm text-danger deleteIcon"
                                                        data-bs-toggle="modal" data-bs-toggle="tooltip"
                                                        data-bs-placement="top" title="Supprimer"><i
                                                            class="fa fa-trash"></i></a>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- Modal delete-->
                                    <div class="modal fade flip" id="deleteModal{{ $facturation->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-body p-5 text-center">
                                                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                                                        colors="primary:#405189,secondary:#f06548"
                                                        style="width:90px;height:90px">
                                                    </lord-icon>
                                                    <div class="mt-4 text-center">
                                                        @if ($facturation->status == 2)
                                                            <h4>Vous êtes sur le point de restaurer <br>une facturation ?
                                                            </h4>
                                                            <p class="text-muted fs-15 mb-4">En restaurant cette
                                                                facturation,
                                                                vous
                                                                ramener
                                                                <br> toutes les informations la concernant de notre base de
                                                                données.
                                                            </p>
                                                        @else
                                                            <h4>Vous êtes sur le point de supprimer <br>une facturation ?
                                                            </h4>
                                                            <p class="text-muted fs-15 mb-4">En supprimant cette
                                                                facturation,
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


                                                            {{-- <input name="_method" type="hidden" value="DELETE"> --}}
                                                            @if ($facturation->status == 2)
                                                                <form method="POST"
                                                                    action="{{ route('facturation.restaure', $facturation->id) }}">
                                                                    @method('PUT')
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-danger"
                                                                        id="delete-record">Oui,
                                                                        restaurer</button>
                                                                </form>
                                                            @else
                                                                <form method="POST"
                                                                    action="{{ route('facturations.destroy', $facturation->id) }}">
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
