@extends('layouts.home_dashboard', ['title' => 'Espace de Contribution '])
@push('css')
    <link rel="stylesheet" href="{{ asset('mutualiste/styledatatable.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">

    <style>
        /* Masquer des colonnes pour mobile */
        @media only screen and (max-width: 768px) {
            table.dataTable thead {
                display: none;
            }

            table.dataTable tbody tr {
                display: block;
                width: 100%;
                margin-bottom: 0.625em;
            }

            table.dataTable tbody tr td {
                display: flex;
                justify-content: space-between;
                text-align: left;
                padding: 8px 10px;
                border-bottom: 1px solid #ddd;
                width: 100%;
            }

            table.dataTable tbody tr td:before {
                content: attr(data-label);
                font-weight: bold;
                width: 50%;
                text-align: left;
                display: inline-block;
            }
        }
    </style>
@endpush
@section('content')
    <div class="col-lg-9">
        <div class="rbt-dashboard-content bg-color-white rbt-shadow-box">
            <div class="content">
                <div class="section-title">
                    <h4 class="rbt-title-style-3">Mes Cotisations
                    </h4>
                </div>
                {{-- <div class="rbt-dashboard-filter-wrapper">
                    <div class="rbt-dashboard-table table-responsive mobile-table-750">
                        <table id="datatable-buttons" class="rbt-table table table-borderless">
                            <thead>
                                <tr>
                                    <th>Libelle</th>
                                    <th>Montant</th>
                                    <th>Date debut</th>
                                    <th>Date Fin</th>
                                    <th>Frequence</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cotisations as $cotisationMutualiste)
                                    <tr>
                                        <th>
                                            <span class="h6 mb--5">{{ $cotisationMutualiste->cotisation->libelle }}</span>
                                        </th>
                                        <td>
                                            {{ formatMontant($cotisationMutualiste->montant) }}
                                        </td>
                                        <td>
                                            {{ formatDate($cotisationMutualiste->date_debut) }}
                                        </td>
                                        <td>
                                            {{ formatDate($cotisationMutualiste->date_fin) }}
                                        </td>
                                        <td>
                                            {{ $cotisationMutualiste->frequence_paiement }}
                                        </td>
                                        <td>
                                            @if ($cotisationMutualiste->status == 2)
                                                <span
                                                    style="color: blue; background-color: rgba(0, 0, 255, 0.3); border: 1px solid rgb(18, 18, 209); padding: 1px; border-radius: 5px;">
                                                    EN ATTENTE
                                                </span>
                                            @elseif($cotisationMutualiste->status == 1)
                                                <span
                                                    style="color: rgba(54, 143, 54, 0.615); background-color: rgba(54, 143, 54, 0.1); border: 1px solid rgba(54, 143, 54, 0.615); padding: 1px; border-radius: 5px;">
                                                    SOLDE
                                                </span>
                                            @elseif ($cotisationMutualiste->date_fin < $dateActuel)
                                                <span
                                                    style="color: rgba(243, 239, 5, 0.689); background-color: rgba(253, 244, 6, 0.311); border: 1px solid rgba(54, 143, 54, 0.615); padding: 1px; border-radius: 5px;">
                                                    TERMINER
                                                </span>
                                            @endif

                                        </td>
                                        <td>
                                            @if ($cotisationMutualiste->status == 2)
                                                <a class="rbg-success-opacity  text-center"
                                                    href="{{ route('paiementCotisation.mutualiste', $cotisationMutualiste->id) }}"
                                                    title="Paiement">
                                                    <i class="fa fa-credit-card"></i>

                                                </a>
                                            @endif
                                            @if (empty($cotisationMutualiste->administrateur_id))
                                                <a class="rbg-success-opacity  text-center mx-2" href="{{ route('resume.cotisationMutual',$cotisationMutualiste->cotisation_id) }}" title="detail des paiements">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" class="me-3"
                                                        fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                                        <path
                                                            d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z" />
                                                        <path
                                                            d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0" />
                                                    </svg>
                                                </a>
                                            @endif


                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div> --}}
                <div class="rbt-dashboard-filter-wrapper">
                    <div class="rbt-dashboard-table table-responsive">
                        <table id="datatable-buttons" class="rbt-table table table-borderless">
                            <thead>
                                <tr>
                                    <th>Libelle</th>
                                    <th>Montant</th>
                                    <th>Date debut</th>
                                    <th>Date Fin</th>
                                    <th class="d-none d-md-table-cell">Frequence</th> <!-- Masqué sur mobile -->
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- @php
                                    // dd($cotisations);
                                @endphp --}}
                                @foreach ($cotisations as $cotisationMutualiste)
                                    <tr>
                                        <th><span class="h6 mb--5">{{ $cotisationMutualiste->cotisation->libelle }}</span>
                                        </th>
                                        <td>{{ formatMontant($cotisationMutualiste->montant) }}</td>
                                        <td>{{ formatDate($cotisationMutualiste->date_debut) }}</td>
                                        <td>{{ formatDate($cotisationMutualiste->date_fin) }}</td>
                                        <td class="d-none d-md-table-cell">{{ $cotisationMutualiste->frequence_paiement }}
                                        </td> <!-- Masqué sur mobile -->
                                        <td>
                                            @if ($cotisationMutualiste->status == 2)
                                                <span
                                                    style="color: blue; background-color: rgba(0, 0, 255, 0.3); border: 1px solid rgb(18, 18, 209); padding: 1px; border-radius: 5px;">
                                                    EN ATTENTE
                                                </span>
                                            @elseif($cotisationMutualiste->status == 1)
                                                <span
                                                    style="color: rgba(54, 143, 54, 0.615); background-color: rgba(54, 143, 54, 0.1); border: 1px solid rgba(54, 143, 54, 0.615); padding: 1px; border-radius: 5px;">
                                                    SOLDE
                                                </span>
                                            @elseif ($cotisationMutualiste->date_fin < $dateActuel)
                                                <span
                                                    style="color: rgba(243, 239, 5, 0.689); background-color: rgba(253, 244, 6, 0.311); border: 1px solid rgba(54, 143, 54, 0.615); padding: 1px; border-radius: 5px;">
                                                    TERMINER
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($cotisationMutualiste->status == 2)
                                                <a class="rbg-success-opacity text-center"
                                                    href="{{ route('paiementCotisation.mutualiste', $cotisationMutualiste->id) }}"
                                                    title="Paiement">
                                                    <i class="fa fa-credit-card"></i>
                                                </a>
                                            @endif
                                            @if (empty($cotisationMutualiste->administrateur_id) || $cotisationMutualiste->status == 5)
                                                <a class="rbg-success-opacity text-center mx-2"
                                                    href="{{ route('resume.cotisationMutual', $cotisationMutualiste->cotisation_id) }}"
                                                    title="detail des paiements">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23"
                                                        class="me-3" fill="currentColor" viewBox="0 0 16 16">
                                                        <path
                                                            d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z" />
                                                        <path
                                                            d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0" />
                                                    </svg>
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
@include('sweetalert::alert')
@push('js')
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                responsive: true
            });
        });
    </script>
@endpush
