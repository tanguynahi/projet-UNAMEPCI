@extends('layouts.dashboard', ['title' => 'Liste des biens acquis', 'toolbar' => '_toolbar2', 'breadcrumb' => 'Biens-Acquis'])

@push('css')
    <!-- Application vendor css url -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard/cssbundle/dataTables.min.css') }}">
@endpush

@section('content')
    <div class="row g-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Liste des biens acquis</h6>
                    <div class="dropdown morphing scale-left">
                        <a href="#" class="card-fullscreen" data-bs-toggle="tooltip" title="Card Full-Screen"><i
                                class="icon-size-fullscreen"></i></a>
                        <a href="{{ route('projetmutualistes.create') }}" class="btn btn-primary d-inline">Ajouter une
                            acquisition</a>
                    </div>
                </div>
                <div class="card-body" id="show_all_admin">
                    <div class="table-responsive">
                        <table id="myTable" class="table myDataTable table-hover align-middle mb-0 card-table">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Mutualiste</th>
                                    <th>Bien acquis</th>
                                    <th>Montant</th>
                                    <th>Total à payer</th>
                                    <th>Date</th>
                                    <th>Commentaire</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($projetmutualistes as $index => $projetmutualiste)
                                    @php
                                        $lienImage = $projetmutualiste->produitProjet->lien_photo
                                            ? asset($projetmutualiste->produitProjet->lien_photo)
                                            : asset('assets/dashboard/img/default-img.png');
                                        $statusBadge = '';

                                        if ($projetmutualiste->status == 1) {
                                            $statusBadge = '<span class="badge bg-success"> Actif </span>';
                                        }

                                        if ($projetmutualiste->status == 2) {
                                            $statusBadge = '<span class="badge bg-danger"> Inactif </span>';
                                        }

                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <span>{{ formatGender($projetmutualiste->mutualiste->genre) }}
                                                {{ $projetmutualiste->mutualiste->nom }}
                                                {{ $projetmutualiste->mutualiste->prenom }}</span>
                                        </td>
                                        <td> <img src="{{ $lienImage }}" class="avatar sm rounded me-2"
                                                alt="Image projet">{{ $projetmutualiste->libelle }} <br> ({{ $projetmutualiste->produitProjet->projet->libelle }})</td>
                                        <td>{{ $projetmutualiste->montant_produit }}</td>
                                        <td>{{ $projetmutualiste->total_apayer }}</td>
                                        <td>{{ formatDate($projetmutualiste->date) }}</td>
                                        <td style="white-space: pre-line;">{{ $projetmutualiste->commentaire }}</td>
                                        <td>{!! $statusBadge !!}</td>
                                        <td>

                                            @if ($projetmutualiste->status == 2)
                                                <a href="#deleteModal{{ $projetmutualiste->id }}" id="RestaurerProjetMutualiste"
                                                    class="btn btn-link btn-sm text-danger refreshIcon"
                                                    data-bs-toggle="modal" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Restaurer"><i class="fa fa-refresh"></i></a>
                                            @else
                                                <a href="{{ route('projetmutualistes.show', $projetmutualiste->id) }}"
                                                    id="ShowProjetMutualiste" class="btn btn-link btn-sm text-success infoIcon"
                                                    data-bs-toggle="tooltip" data-bs-toggle="modal"
                                                    data-bs-target="#info_projetMutualiste" data-bs-placement="top"
                                                    title="Infos"><i class="fa fa-eye"></i></a>

                                                @if (Auth::user()->hasRole('super-administrateur'))
                                                    <a href="{{ route('projetmutualistes.edit', $projetmutualiste->id) }}"
                                                        id="EditRedevance" class="btn btn-link btn-sm text-primary editIcon"
                                                        data-bs-toggle="tooltip" data-bs-target="#edit_ProjetMutualiste"
                                                        title="Modifier"><i class="fa fa-pencil"></i></a>
                                                    <a href="#deleteModal{{ $projetmutualiste->id }}" id="DeleteProjetMutualiste"
                                                        class="btn btn-link btn-sm text-danger deleteIcon"
                                                        data-bs-toggle="modal" data-bs-toggle="tooltip"
                                                        data-bs-placement="top" title="Supprimer"><i
                                                            class="fa fa-trash"></i></a>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- Modal delete-->
                                    <div class="modal fade flip" id="deleteModal{{ $projetmutualiste->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-body p-5 text-center">
                                                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                                                        colors="primary:#405189,secondary:#f06548"
                                                        style="width:90px;height:90px">
                                                    </lord-icon>
                                                    <div class="mt-4 text-center">
                                                        @if ($projetmutualiste->status == 2)
                                                            <h4>Vous êtes sur le point de restaurer <br>une redevance ?</h4>
                                                            <p class="text-muted fs-15 mb-4">En restaurant cette redevance,
                                                                vous
                                                                ramener
                                                                <br> toutes les informations la concernant de notre base de
                                                                données.
                                                            </p>
                                                        @else
                                                            <h4>Vous êtes sur le point de supprimer <br>une redevance ?</h4>
                                                            <p class="text-muted fs-15 mb-4">En supprimant cette redevance,
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
                                                            @if ($projetmutualiste->status == 2)
                                                                <form method="POST"
                                                                    action="{{ route('projetmutualiste.restaure', $projetmutualiste->id) }}">
                                                                    @method('PUT')
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-danger"
                                                                        id="delete-record">Oui,
                                                                        restaurer</button>
                                                                </form>
                                                            @else
                                                                <form method="POST"
                                                                    action="{{ route('projetmutualistes.destroy', $projetmutualiste->id) }}">
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
