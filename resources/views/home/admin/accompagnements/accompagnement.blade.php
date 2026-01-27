@extends('layouts.home_dashboard', ['title' => "Page d'accompagement "])
@push('css')
    <link rel="stylesheet" href="{{ asset('mutualiste/styledatatable.css') }}">
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css"> --}}
@endpush
@section('content')
    <div class="col-lg-9 col-sm-12 col-12 col-md-12">
        <div class="rbt-dashboard-content bg-color-white rbt-shadow-box">
            <div class="content">
                <div class="section-title">
                    <div class="row align-items-start">
                        <div class="col-8">
                            <h4 class="rbt-title-style-3">Prêt et Service </h4>
                        </div>
                        <div class="col-4">
                            <div
                                class="call-to-btn text-center text-sm-center text-md-center text-lg-end position-relative">
                                <a class="rbt-btn btn-sm rbt-switch-btn rbt-switch-y"
                                    href="{{ route('demandeaccompagnements.create') }}">
                                    <span data-text="Nouvelle Demande">Nouvelle Demande</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="rbt-dashboard-table table-responsive mobile-table-750">
                    <table id="datatable-buttons" class="rbt-table table table-borderless">
                        <thead>
                            <tr>
                                {{-- <th>Numero</th> --}}
                                <th>Service</th>
                                <th>Montant demandé</th>
                                <th>A Remboursé</th>
                                <th>Motif</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($demandeaccompagnements as $index => $demandeaccompagnement)
                                <tr>
                                    <td>
                                        {!! Str::limit($demandeaccompagnement->service->libelle ?? "XXXXXX", 10) !!}
                                    </td>

                                    <td>{{ formatMontant($demandeaccompagnement->montant_voulue) }}</td>
                                    <td>{{ formatMontant($demandeaccompagnement->montant_apayer) }}</td>

                                    <td>
                                        @if ($demandeaccompagnement->status == 3)
                                            {!! Str::limit($demandeaccompagnement->rejet, 11) !!}
                                        @else
                                            {!! Str::limit($demandeaccompagnement->commentaire, 11) !!}
                                        @endif
                                    </td>
                                    <td>
                                        {{-- {{ formatDate2($demandeaccompagnement->created_at) }} --}}
                                         {{-- <br> --}}
                                        {{ formatJour($demandeaccompagnement->created_at) }}
                                    </td>
                                    <td>
                                        @if ($demandeaccompagnement->status == 1)
                                            <span class="rbt-badge-5 bg-color-success-opacity color-success">Acceptée</span>
                                        @elseif ($demandeaccompagnement->status == 2)
                                            <span class="rbt-badge-5 bg-color-primary-opacity color-primary">En
                                                cours</span>
                                        @elseif ($demandeaccompagnement->status == 3)
                                            <span class="rbt-badge-5 bg-color-danger-opacity color-danger">Rejetée</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{-- @if ($demandeaccompagnement->status == 1 || $demandeaccompagnement->status == 2) --}}
                                        <a href="{{ route('detail.demandeaccompagnement', $demandeaccompagnement->id) }}"
                                            title="detail">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                                                fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                                <path
                                                    d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z" />
                                                <path
                                                    d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0" />
                                            </svg>
                                            {{-- Voir --}}
                                        </a>
                                        {{-- @else
                                            <a
                                                href="{{ route('demandeaccompagnement.modifier', $demandeaccompagnement->id) }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16"
                                                    title="editer">
                                                    <path
                                                        d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325" />
                                                </svg>
                                                Modifier
                                            </a> --}}

                                        {{-- @endif --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="col-lg-9 col-md-12 col-sm-12">
        <div class="rbt-dashboard-content bg-color-white rbt-shadow-box">
            <div class="content">
                <div class="section-title">
                    <div class="row align-items-start">
                        <div class="col-8">
                            <h4 class="rbt-title-style-3">Prêt et Service</h4>
                        </div>
                        <div class="col-4 text-end">
                            <a class="rbt-btn btn-sm rbt-switch-btn rbt-switch-y w-100 text-center"
                                href="{{ route('demandeaccompagnements.create') }}">
                                <span data-text="Nouvelle Demande">Nouvelle Demande</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="datatable-buttons" class="table table-borderless rbt-table">
                        <thead>
                            <tr>
                                <th>Numero</th>
                                <th>Service</th>
                                <th>Date</th>
                                <th>Montant demandé</th>
                                <th>Motif</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($demandeaccompagnements as $index => $demandeaccompagnement)
                                <tr>
                                    <th>{{ $index + 1 }}</th>
                                    <td>{{ $demandeaccompagnement->service->libelle }}</td>
                                    <td>
                                        {{ formatDate2($demandeaccompagnement->created_at) }}<br>
                                        {{ formatJour($demandeaccompagnement->created_at) }}
                                    </td>
                                    <td>{{ formatMontant($demandeaccompagnement->montant_voulue) }}</td>
                                    <td>
                                        @if ($demandeaccompagnement->status == 3)
                                            {{ $demandeaccompagnement->rejet }}
                                        @else
                                            {{ $demandeaccompagnement->commentaire }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($demandeaccompagnement->status == 1)
                                            <span class="badge bg-success-opacity text-success">Acceptée</span>
                                        @elseif ($demandeaccompagnement->status == 2)
                                            <span class="badge bg-primary-opacity text-primary">En cours</span>
                                        @elseif ($demandeaccompagnement->status == 3)
                                            <span class="badge bg-danger-opacity text-danger">Rejetée</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($demandeaccompagnement->status == 1)
                                            <a
                                                href="{{ route('detail.demandeaccompagnement', $demandeaccompagnement->id) }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                                    <path
                                                        d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z" />
                                                    <path
                                                        d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0" />
                                                </svg>
                                                Voir
                                            </a>
                                        @else
                                            <a
                                                href="{{ route('demandeaccompagnement.modifier', $demandeaccompagnement->id) }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16"
                                                    title="editer">
                                                    <path
                                                        d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325" />
                                                </svg>
                                                Modifier
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
    </div> --}}
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/fr-FR.json"
                }
            });
        });
    </script>
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script> --}}


    <script>
        function removeIdOnMobile() {
            const table = document.getElementById("datatable-buttons");
            if (window.innerWidth < 768 && table) {
                table.removeAttribute("id");
            }
        }

        removeIdOnMobile();
        window.addEventListener("resize", removeIdOnMobile);
    </script>
@endpush
