@extends('layouts.dashboard', ['title' => 'Liste des administrateurs', 'toolbar' => '_toolbar2', 'breadcrumb' => 'Administrateurs'])

@push('css')
    <!-- Application vendor css url -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard/cssbundle/dataTables.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
@endpush

@section('content')
    <div class="row g-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Liste des administrateurs</h6>
                    <div class="dropdown morphing scale-left">
                        <a href="#" class="card-fullscreen" data-bs-toggle="tooltip" title="Card Full-Screen"><i
                                class="icon-size-fullscreen"></i></a>
                        <a href="{{ route('administrateurs.create') }}" class="btn btn-primary d-inline">Ajouter un
                            administrateur</a>
                    </div>
                </div>
                <div class="card-body" id="show_all_admin">
                    <div class="table-responsive">
                        <table id="myTable" class="table myDataTable table-hover align-middle mb-0 card-table">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Administrateur</th>
                                    <th>Email</th>
                                    <th>Contact</th>

                                    <th>Disponibilité</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($administrateurs as $index => $administrateur)
                                    @php
                                        $imgUrl = $administrateur->lien_photo
                                            ? asset($administrateur->lien_photo)
                                            : asset('assets/dashboard/img/default-img.png');
                                        $statusBadge =
                                            $administrateur->status == 1
                                                ? '<span class="badge bg-success"> Compte Actif </span>'
                                                : '<span class="badge bg-danger"> Compte Inactif </span>';
                                        $isOnline =
                                            $administrateur->disponibilite == 'en ligne'
                                                ? '<span class="badge bg-success"> En ligne </span>'
                                                : '<span class="badge bg-danger"> Hors Ligne </span>';
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <img src="{{ $imgUrl }}" class="avatar sm rounded me-2"
                                                alt="profile-image">
                                            <span>{{ $administrateur->nom }} {{ $administrateur->prenom }}</span>
                                        </td>
                                        <td>{{ $administrateur->email }}</td>
                                        <td>{{ formatPhoneNumber($administrateur->contact, '-') }}</td>

                                        <td>{!! $isOnline !!}</td>
                                        <td>{!! $statusBadge !!}</td>
                                        <td>
                                            @can('afficher-permissions-administrateurs')
                                                <a href="{{ route('administrateurs.permissions', $administrateur->id) }}"
                                                    id="Permissions" class="btn btn-link btn-sm text-success"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Permissions">
                                                    <i class="fas fa-shield-alt"></i>
                                                </a>
                                            @endcan


                                            @can('infos-administrateurs')
                                                <a href="{{ route('administrateurs.show', $administrateur->id) }}"
                                                    id="ShowAdmin" class="btn btn-link btn-sm text-success infoIcon"
                                                    data-bs-toggle="tooltip" data-bs-toggle="modal" data-bs-target="#info_admin"
                                                    data-bs-placement="top" title="Infos"><i class="fa fa-eye"></i></a>
                                            @endcan
                                            @can('modifier-administrateurs')
                                                <a href="{{ route('administrateurs.edit', $administrateur->id) }}"
                                                    id="EditAdmin" class="btn btn-link btn-sm text-primary editIcon"
                                                    data-bs-target="#edit_admin" title="Modifier"><i
                                                        class="fa fa-pencil"></i></a>
                                            @endcan
                                            @can('supprimer-administrateurs')
                                                <a href="#deleteModal{{ $administrateur->id }}" id="DeleteAdministrateur"
                                                    class="btn btn-link btn-sm text-danger deleteIcon" data-bs-toggle="modal"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Supprimer"><i
                                                        class="fa fa-trash"></i></a>
                                            @endcan
                                        </td>
                                    </tr>

                                    <!-- Modal delete-->
                                    <div class="modal fade flip" id="deleteModal{{ $administrateur->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-body p-5 text-center">
                                                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                                                        colors="primary:#405189,secondary:#f06548"
                                                        style="width:90px;height:90px">
                                                    </lord-icon>
                                                    <div class="mt-4 text-center">
                                                        <h4>Vous êtes sur le point de supprimer <br>un administrateur ?</h4>
                                                        <p class="text-muted fs-15 mb-4">
                                                            En supprimant cet administrateur, vous supprimez <br>
                                                            toutes les informations le concernant de notre base de données.
                                                            <br>
                                                            <strong>NB :</strong> son email ne pourra plus être utilisé
                                                            comme identifiant pour se connecter à la plateforme.
                                                        </p>
                                                        <div class="hstack gap-2 justify-content-center remove">
                                                            <button
                                                                class="btn btn-link link-success fw-medium text-decoration-none"
                                                                id="deleteRecord-close" data-bs-dismiss="modal"><i
                                                                    class="ri-close-line me-1 align-middle"></i>
                                                                Fermer</button>

                                                            <form method="POST"
                                                                action="{{ route('administrateurs.destroy', $administrateur->id) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                {{-- <input name="_method" type="hidden" value="DELETE"> --}}
                                                                <button class="btn btn-danger" id="delete-record">Oui,
                                                                    supprimer</button>
                                                            </form>
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
