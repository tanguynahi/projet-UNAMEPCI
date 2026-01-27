@extends('layouts.dashboard', ['title' => 'Liste des demandes - projets', 'toolbar' => '_toolbar2', 'breadcrumb' => 'Demandes-projets'])

@push('css')
    <!-- Application vendor css url -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard/cssbundle/dataTables.min.css') }}">
@endpush

@section('content')
    <div class="row g-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Liste des demandes - projets</h6>
                    <div class="dropdown morphing scale-left">
                        <a href="#" class="card-fullscreen" data-bs-toggle="tooltip" title="Card Full-Screen"><i
                                class="icon-size-fullscreen"></i></a>
                        <a href="{{ route('demandeproduits.index') }}" class="btn btn-primary d-inline">Retour</a>
                    </div>
                </div>
                <div class="card-body" id="show_all_produits">
                    <div class="table-responsive">
                        <table id="myTable" class="table myDataTable table-hover align-middle mb-0 card-table">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Mutualiste</th>
                                    <th>Projet</th>
                                    <th>Coût</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($demandeproduits as $index => $demandeproduit)
                                    @php
                                        $lienPhotoMutualiste = $demandeproduit->mutualiste->lien_photo
                                            ? asset($demandeproduit->mutualiste->lien_photo)
                                            : asset('assets/dashboard/img/default-img.png');

                                            $lienPhoto = $demandeproduit->produitProjet->lien_photo
                                            ? asset($demandeproduit->produitProjet->lien_photo)
                                            : asset('assets/dashboard/img/default-img.png');

                                        $statusBadge = '';

                                        if ($demandeproduit->status == 1) {
                                            $statusBadge = '<span class="badge bg-success"> Approuvée </span>';
                                        }

                                        if ($demandeproduit->status == 2) {
                                            $statusBadge = '<span class="badge bg-primary"> En attente </span>';
                                        }

                                        if ($demandeproduit->status == 3) {
                                            $statusBadge = '<span class="badge bg-danger"> Rejetée </span>';
                                        }
                                    @endphp

                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <img src="{{ $lienPhotoMutualiste }}" class="avatar sm rounded me-2"
                                                alt="Photo Mutualiste">
                                            <span>{{ formatGender($demandeproduit->mutualiste->genre) }} {{ $demandeproduit->mutualiste->nom }} {{ $demandeproduit->mutualiste->prenom }}</span>
                                        </td>
                                        <td>
                                            <img src="{{ $lienPhoto }}" class="avatar sm rounded me-2"
                                                alt="Image actualité">
                                            <span>{{ $demandeproduit->produitProjet->libelle }}</span><br>
                                            <span>({{ $demandeproduit->produitProjet->projet->libelle }})</span>
                                        </td>
                                        <td>{{ formatMontant($demandeproduit->produitProjet->cout) }}</td>
                                        <td>
                                            {!! $statusBadge !!}
                                        </td>
                                        <td>
                                            @if ($demandeproduit->status == 4)
                                                <a href="#deleteModal{{ $demandeproduit->id }}" id="RestaurerDemandeProduit"
                                                    class="btn btn-link btn-sm text-danger refreshIcon"
                                                    data-bs-toggle="modal" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Restaurer"><i class="fa fa-refresh"></i></a>
                                            @else
                                                <a href="{{ route('demandeproduits.show', $demandeproduit->id) }}"
                                                    id="ShowDemandeProduit"
                                                    class="btn btn-link btn-sm text-success infoIcon"
                                                    data-bs-toggle="tooltip" data-bs-toggle="modal"
                                                    data-bs-target="#info_demande_produit" data-bs-placement="top"
                                                    title="Infos"><i class="fa fa-eye"></i></a>

                                                @if (Auth::user()->hasRole('super-administrateur'))
                                                    <a href="#deleteModal{{ $demandeproduit->id }}"
                                                        id="DeleteDemandeProduit"
                                                        class="btn btn-link btn-sm text-danger deleteIcon"
                                                        data-bs-toggle="modal" data-bs-toggle="tooltip"
                                                        data-bs-placement="top" title="Supprimer"><i
                                                            class="fa fa-trash"></i></a>
                                                @endif

                                            @endif
                                        </td>
                                    </tr>

                                    <!-- Modal delete-->
                                    <div class="modal fade flip" id="deleteModal{{ $demandeproduit->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-body p-5 text-center">
                                                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                                                        colors="primary:#405189,secondary:#f06548"
                                                        style="width:90px;height:90px">
                                                    </lord-icon>
                                                    <div class="mt-4 text-center">
                                                        @if ($demandeproduit->status == 2)
                                                            <h4>Vous êtes sur le point de restaurer <br>une demande de
                                                                souscription ?</h4>
                                                            <p class="text-muted fs-15 mb-4">En restaurant cette demande de
                                                                souscription, vous
                                                                ramener
                                                                <br> toutes les informations la concernant de notre base de
                                                                données.
                                                            </p>
                                                        @else
                                                            <h4>Vous êtes sur le point de supprimer <br>une demande de
                                                                souscription ?</h4>
                                                            <p class="text-muted fs-15 mb-4">En supprimant cette demande de
                                                                souscription, vous
                                                                supprimez
                                                                <br> toutes les informations la concernant de notre base de
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
                                                            @if ($demandeproduit->status == 2)
                                                                <form method="POST"
                                                                    action="{{ route('demandeproduit.restaure', $demandeproduit->id) }}">
                                                                    @method('PUT')
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-danger"
                                                                        id="delete-record">Oui,
                                                                        restaurer</button>
                                                                </form>
                                                            @else
                                                                <form method="POST"
                                                                    action="{{ route('demandeproduits.destroy', $demandeproduit->id) }}">
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
