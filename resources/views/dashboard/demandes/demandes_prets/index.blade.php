@extends('layouts.dashboard', ['title' => 'Liste des demandes - Prêts & Servives', 'toolbar' => '_toolbar2', 'breadcrumb' =>
'Demandes-Prêts & Servives'])

@push('css')
<!-- Application vendor css url -->
<link rel="stylesheet" href="{{ asset('assets/dashboard/cssbundle/dataTables.min.css') }}">
@endpush

@section('content')
<div class="row g-3">
    @if ($demandeaccompagnements->count() == 0)
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">Liste des demande de Prêts & Servives </span></h6>
                <div class="dropdown morphing scale-left">
                    <a href="#" class="card-fullscreen" data-bs-toggle="tooltip" title="Card Full-Screen"><i
                            class="icon-size-fullscreen"></i></a>
                    {{-- <a href="{{ route('demandeaccompagnements.index') }}" class="btn btn-primary d-inline">Retour</a> --}}
                </div>
            </div>
            <div class="card-body" id="show_all_prets">
                <p class="text-secondary text-center h3">Aucune demande de Prêts/Servives disponible</p>
            </div>
        </div>
    </div>
    @else
    @foreach ($demandeaccompagnements as $serviceLibelle => $demandes)
        @php $tableId = 'table_' . Str::slug($serviceLibelle); @endphp
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Liste des demande de Prêts - <span class="fw-bold text-success">{{
                            $serviceLibelle }}</span></h6>
                    <div class="dropdown morphing scale-left">
                        <a href="#" class="card-fullscreen" data-bs-toggle="tooltip" title="Card Full-Screen"><i
                                class="icon-size-fullscreen"></i></a>
                        {{-- <a href="{{ route('demandeaccompagnements.index') }}" class="btn btn-primary d-inline">Retour</a> --}}
                    </div>
                </div>
                <div class="card-body" id="show_all_prets">
                    <div class="table-responsive">
                        <table id="{{ $tableId }}" class="table myDataTable table-hover align-middle mb-0 card-table">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Mutualiste</th>
                                    <th>Demande</th>
                                    <th>Retour</th>
                                    {{-- <th>Date limite</th> --}}
                                    <th>Attente</th>
                                    <th>Commentaire</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($demandes as $index => $demandeaccompagnement)
                                @php
                                $lienPhoto = $demandeaccompagnement->mutualiste->lien_photo
                                ? asset($demandeaccompagnement->mutualiste->lien_photo)
                                : asset('assets/dashboard/img/default-img.png');

                                $statusBadge = '';

                                if ($demandeaccompagnement->status == 1) {
                                $statusBadge = '<span class="badge bg-success"> Approuvée </span>';
                                } elseif ($demandeaccompagnement->status == 2) {
                                $statusBadge = '<span class="badge bg-primary"> En attente </span>';
                                } elseif ($demandeaccompagnement->status == 3) {
                                $statusBadge = '<span class="badge bg-danger"> Rejetée </span>';
                                }
                                @endphp

                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <img src="{{ $lienPhoto }}" class="avatar sm rounded me-2" alt="Photo Mutualiste">
                                        <span>{{ formatGender($demandeaccompagnement->mutualiste->genre) }} {{
                                            $demandeaccompagnement->mutualiste->nom }} {{
                                            $demandeaccompagnement->mutualiste->prenom }}</span><br>
                                        <p class="my-2">Grade : {{ $demandeaccompagnement->mutualiste->grade->libelle }}</p>
                                    </td>
                                    <td>{{ formatMontant($demandeaccompagnement->montant_voulue) }}</td>
                                    <td>{{ formatMontant($demandeaccompagnement->montant_apayer) }}</td>
                                    {{-- <td>{{ formatDate($demandeaccompagnements->date_limite) }}</td> --}}
                                    <td>{{ dateHistorique2($demandeaccompagnement->created_at) }}</td>
                                    <td style="white-space: pre-line;">{!!
                                        couperTexte2($demandeaccompagnement->commentaire,10) !!}</td>
                                    <td>{!! $statusBadge !!}</td>
                                    <td>
                                        @if ($demandeaccompagnement->status == 4)
                                        <a href="#deleteModal{{ $demandeaccompagnement->id }}" id="RestaurerDemandePret"
                                            class="btn btn-link btn-sm text-danger refreshIcon" data-bs-toggle="modal"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Restaurer"><i
                                                class="fa fa-refresh"></i></a>
                                        @else
                                        <a href="{{ route('demandeAccompagnement.detail', $demandeaccompagnement->id) }}"
                                            id="ShowDemandePret" class="btn btn-link btn-sm text-success infoIcon"
                                            data-bs-toggle="tooltip" data-bs-toggle="modal"
                                            data-bs-target="#info_demande_pret" data-bs-placement="top" title="Infos"><i
                                                class="fa fa-eye"></i></a>

                                        @if (Auth::user()->hasRole('super-administrateur'))
                                        <a href="#deleteModal{{ $demandeaccompagnement->id }}" id="DeleteDemandePret"
                                            class="btn btn-link btn-sm text-danger deleteIcon" data-bs-toggle="modal"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Supprimer"><i
                                                class="fa fa-trash"></i></a>
                                        @endif

                                        @endif
                                    </td>
                                </tr>

                                <!-- Modal delete-->
                                <div class="modal fade flip" id="deleteModal{{ $demandeaccompagnement->id }}" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-body p-5 text-center">
                                                <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                                                    colors="primary:#405189,secondary:#f06548"
                                                    style="width:90px;height:90px">
                                                </lord-icon>
                                                <div class="mt-4 text-center">
                                                    @if ($demandeaccompagnement->status == 2)
                                                    <h4>Vous êtes sur le point de restaurer <br>une demande de
                                                        prêt ?</h4>
                                                    <p class="text-muted fs-15 mb-4">En restaurant cette demande de
                                                        prêt, vous
                                                        ramenez
                                                        <br> toutes les informations la concernant de notre base de
                                                        données.
                                                    </p>
                                                    @else
                                                    <h4>Vous êtes sur le point de supprimer <br>une demande de
                                                        prêt ?</h4>
                                                    <p class="text-muted fs-15 mb-4">En supprimant cette demande de
                                                        prêt, vous
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
                                                        @if ($demandeaccompagnement->status == 2)
                                                        <form method="POST"
                                                            action="{{ route('demandeaccompagnement.restaure', $demandeaccompagnement->id) }}">
                                                            @method('PUT')
                                                            @csrf
                                                            <button type="submit" class="btn btn-danger"
                                                                id="delete-record">Oui,
                                                                restaurer</button>
                                                        </form>
                                                        @else
                                                        <form method="POST"
                                                            action="{{ route('demandeaccompagnements.destroy', $demandeaccompagnement->id) }}">
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
        @endforeach
    @endif

</div>

@endsection
@push('js')
<!-- Plugin Js -->
<script src="{{ asset('assets/dashboard/js/bundle/dataTables.bundle.js') }}"></script>
<!-- Vendor Script -->
<script>
    var langUrl = "{{ asset('DataTables/fr-FR.json') }}";

    $(document).ready(function() {
        @foreach ($demandeaccompagnements as $serviceLibelle => $demandes)
            var tableId = "{{ 'table_' . Str::slug($serviceLibelle) }}";
            $('#' + tableId).addClass('nowrap').dataTable({
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
                ],
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
        @endforeach
    });
</script>
@endpush
