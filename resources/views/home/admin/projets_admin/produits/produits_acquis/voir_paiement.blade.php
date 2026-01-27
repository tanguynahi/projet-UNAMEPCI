@extends('layouts.home_dashboard', ['title' => 'Detail Affaires'])
@push('css')
    <link rel="stylesheet" href="{{ asset('mutualiste/styledatatable.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
@endpush
@section('content')
    <div class="col-lg-9">
        <!-- Start Enrole Course  -->
        <div class="rbt-dashboard-content bg-color-white rbt-shadow-box">
            <div class="content">
                <div class="section-title">
                    <h4 class="rbt-title-style-3">Affaire conclue</h4>
                    @if (!empty($retour))
                        <a href="{{ route('prodAcquis.mutuID', $retour) }}" style="padding-bottom:12px;">
                            {{--  --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor"
                                class="bi bi-arrow-left-square-fill" viewBox="0 0 16 16">
                                <path
                                    d="M16 14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2zm-4.5-6.5H5.707l2.147-2.146a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 0 0 .708-.708L5.707 8.5H11.5a.5.5 0 0 0 0-1" />
                            </svg>
                        </a>
                    @else
                        <a href="{{ back()->getTargetUrl() }}" style="padding-bottom:12px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor"
                                class="bi bi-arrow-left-square-fill" viewBox="0 0 16 16">
                                <path
                                    d="M16 14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2zm-4.5-6.5H5.707l2.147-2.146a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 0 0 .708-.708L5.707 8.5H11.5a.5.5 0 0 0 0-1" />
                            </svg>
                        </a>
                    @endif

                    <br>
                </div>
                <h6 class="rbt-title-style-4">Detail des paiement de la propriete <span style="text-transform: uppercase;">
                        {{ $projetMutualiste->produitProjet->projet->libelle }}
                        {{ $projetMutualiste->produitProjet->libelle }} </span> </h6>
                <hr style="color: gray;" class="mtb-4">

                <div class="row mt-4 mb-3">
                    <div class="col-12">
                        <span><u><b>Redevances à payer</b></u></span>
                    </div>
                </div>
                <div class="rbt-dashboard-table table-responsive mobile-table-750">
                    <table id="datatable-buttons" class="rbt-table table table-borderless">
                        <thead>
                            <tr>
                                <th>Redevance</th>
                                <th>Total à Payer</th>
                                <th>Total Payé</th>
                                <th>Reste a payer</th>
                                <th>Echéance</th>
                                <th>date debut</th>
                                <th>date fin</th>
                                <th>statut</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($facturations as $index => $facturation)
                                <tr>
                                    {{-- <td> <b>{{ $index + 1 }}</b></td> --}}
                                    <td>{{ $facturation->redevance->libelle }}</td>
                                    <td>{{ formatMontant($facturation->total_apayer) }}</td>
                                    <td>{{ formatMontant($facturation->total_payer) }}</td>
                                    <td>{{ formatMontant($facturation->reste_apayer) }}</td>
                                    <td> <b>{{ $facturation->periode->libelle }}</b></td>
                                    <td>{{ $facturation->date_debut ? formatDate($facturation->date_debut) : 'Non défini' }}
                                    </td>
                                    <td>{{ $facturation->date_fin ? formatDate($facturation->date_fin) : 'Non défini' }}
                                    </td>

                                    <td>
                                        @if ($facturation->status == 1)
                                            <span
                                                style="color: blue; background-color: rgba(0, 0, 255, 0.3); border: 1px solid rgb(18, 18, 209); padding: 1px; border-radius: 5px; text-transform:uppercase;">
                                                Impayé
                                            </span>
                                        @elseif ($facturation->status == 3)
                                            <span
                                                style="color: rgba(54, 143, 54, 0.615); background-color: rgba(54, 143, 54, 0.1); border: 1px solid rgba(54, 143, 54, 0.615); padding: 1px; border-radius: 5px;">
                                                {{-- // les id des periodes : 1 immediat, 2 journaliere, 3 hebdomadaire ,4 mensuelle, 5 Annuelle , 6 Aperiodique --}}
                                                @if ($facturation->periode_id == 2)
                                                    Payer pour Aujourd'hui
                                                @elseif ($facturation->periode_id == 3)
                                                    Payer pour cette semaine
                                                @elseif ($facturation->periode_id == 4)
                                                    Payer pour ce mois
                                                @elseif ($facturation->periode_id == 5)
                                                    Payer pour année
                                                @else
                                                    SOLDE
                                                @endif
                                            </span>
                                        @elseif ($facturation->status == 4)
                                            <span
                                                style="color: rgba(54, 143, 54, 0.615); background-color: rgba(54, 143, 54, 0.1); border: 1px solid rgba(54, 143, 54, 0.615); padding: 1px; border-radius: 5px;">
                                                Terminer
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($facturation->total_apayer > $facturation->total_payer)
                                            <a href="{{ route('fichePaiement.mutualiste', ['idfact' => $facturation->id, 'idprod' => $projetMutualiste->id]) }}"
                                                class="btn btn-primary ">
                                                <i class="fa fa-credit-card me-2"></i>
                                                Payer
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-4 justify-content-stard">
                        <span>TOTAL A PAYER : <b>{{ formatMontant($sommes) }}</b></span>
                    </div>
                    <div class="col-4 justify-content-center">
                        <span>TOTAL PAYER : <b>{{ formatMontant($reste) }}</b></span>
                    </div>
                    <div class="col-4 justify-content-end">
                        <span>MONTANT RESTANT : <b>{{ formatMontant($sommes - $reste) }}</b></span>
                    </div>
                </div>

                <hr style="color: gray;" class="mtb-4">

                <div class="row mt-4 mb-3">
                    <div class="col-12">
                        <span><u><b>Paiements éffectués pour ce bien</b></u></span>
                    </div>
                </div>

                <div class="rbt-dashboard-table table-responsive mobile-table-750">
                    <table id="myDataTable" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>Redevance</th>
                                <th>Reference</th>
                                <th>Moyen</th>
                                <th>Montant</th>
                                <th>Date & Heure</th>
                                <th>Statut</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        @foreach ($lignePaiements as $lignePaiement)
                            <tbody>
                                <td>
                                    @php
                                        $libelle = '';
                                        foreach ($facturations as $facturation) {
                                            if ($lignePaiement->correspondance_id == $facturation->id) {
                                                $libelle = $facturation->redevance->libelle;
                                            }
                                        }
                                    @endphp
                                    @if (!empty($libelle))
                                        {{ $libelle }}
                                    @endif
                                </td>
                                <td>{{ $lignePaiement->reference }}</td>
                                <td>{{ $lignePaiement->moyen_paiement }}</td>
                                <td>{{ formatMontant($lignePaiement->montant_initial) }}</td>
                                <td>{{ $lignePaiement->date_paiement_initial }}</td>
                                <td>
                                    @if ($lignePaiement->status == 2)
                                        <span class="badge bg-primary">EN ATTENTE</span>
                                        {{-- <span class="badge bg-success">VALIDE</span>
                                        <span class="badge bg-danger">ECHEC</span> --}}
                                    @elseif($lignePaiement->status == 3)
                                        <span class="badge bg-danger">ECHEC</span>
                                    @else
                                        <span class="badge bg-success">VALIDE</span>
                                    @endif
                                </td>
                                <td>
                                    {{-- paieLign.show --}}
                                    <a href="{{ route('paieLign.show', $lignePaiement->id) }}" class="mx-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                            <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0" />
                                            <path
                                                d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7" />
                                        </svg>
                                    </a>
                                    @if (!empty($lignePaiement->reference))
                                        <a href="{{ route('docs.paiement', $lignePaiement->id) }}" target="target">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                fill="currentColor" class="bi bi-card-image" viewBox="0 0 16 16">
                                                <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0" />
                                                <path
                                                    d="M1.5 2A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2zm13 1a.5.5 0 0 1 .5.5v6l-3.775-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12v.54L1 12.5v-9a.5.5 0 0 1 .5-.5z" />
                                            </svg>
                                        </a>
                                    @endif
                                </td>
                            </tbody>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- End Enrole Course  -->
@endsection
