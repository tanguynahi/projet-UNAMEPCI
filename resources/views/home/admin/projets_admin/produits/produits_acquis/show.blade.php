@extends('layouts.home_dashboard', ['title' => 'Detail Affaires'])
@section('content')
    <div class="col-lg-9">
        <!-- Start Enrole Course  -->
        <div class="rbt-dashboard-content bg-color-white rbt-shadow-box">
            <div class="content">
                <div class="section-title">
                    <h4 class="rbt-title-style-3">Affaire conclue</h4>
                    @if (!empty($retour))
                        <a href="{{ route('prodAcquis.mutuID', $retour) }}" style="padding-bottom:12px;">
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

                    <h6 class="rbt-title-style-4">Detail affaire n°{{ $projetMutualiste->id }}</h6>
                </div>
                <hr style="color: gray;" class="mtb-4">
                <div class="container px-4 mtb-5">
                    <div class="row gx-5">
                        <div class="col-6">
                            <span><u>Informations sur le biens</u></span> <br>
                            <img src="{{ asset($projetMutualiste->produitProjet->lien_photo) }}" alt="Image Produit"
                                style="height: 300px width:200px">
                            <br>
                            <br>
                            {{-- <span>Etat:</span> --}}
                        </div>
                        <div class="col-6">
                            <div class="mb-2"><u>Informations sur l'affaire</u></div>
                            <span class="fw-bold text-center"
                                style="color:black;">{{ $projetMutualiste->produitProjet->libelle }}</span> <br>
                            <span>Montant Affaire : <span class="fw-bold"
                                    style="color:black;">{{ formatMontant($projetMutualiste->total_apayer) }}</span></span>
                            <br>
                            <span>Date Affaire : <span class="fw-bold"
                                    style="color:black;">{{ formatDate($projetMutualiste->date) }}</span></span> <br>
                            @if ($projetMutualiste->commentaire)
                                <span>Commentaire : <br> <span>{{ $projetMutualiste->commentaire }}</span></span> <br>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <span class="mb-5"><u>Description</u></span> <br>
                            {!! couperTexte2($projetMutualiste->produitProjet->description, 25) !!}
                            <a href="{{ route('mutualiste.detailproduit', $projetMutualiste->id) }}#description_produit"
                                class="text-primary"> voir plus</a>
                        </div>
                        <div class="col-4">
                            @php
                                $imageProduits = $projetMutualiste->produitProjet->imageProjets;
                            @endphp

                            @forelse ($imageProduits as $imageProduit)
                                {{-- <div class="col-lg-3 col-md-4 col-sm-6"> --}}
                                <img src="{{ asset($imageProduit->lien_image) }}" class="img-thumbnail"
                                    style="height: 80px; width: 100px;" alt="Image du produit">
                                {{-- </div> --}}
                            @empty
                                {{-- <p class="text-muted">Aucune image associée a ce produit</p> --}}
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="row mt-4 mb-3">
                    <div class="col-12">
                        <span><u><b>Redevances à payer</b></u></span>
                    </div>
                </div>
                <div class="rbt-dashboard-table table-responsive mobile-table-750">
                    <table class="rbt-table table table-borderless">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>Redevance</th>
                                <th>Total à Payer</th>
                                <th>Total Payé</th>
                                <th>Reste a payer</th>
                                <th>Echéance</th>
                                <th>date debut</th>
                                <th>date fin</th>
                                <th>status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($facturations as $index => $facturation)
                                {{-- @if (\Carbon\Carbon::parse($facturation->created_at)->isToday())
                                @endif --}}
                                <tr>
                                    <td> <b>{{ $index + 1 }}</b></td>
                                    <td>{{ $facturation->redevance->libelle }}</td>
                                    <td>{{ formatMontant($facturation->total_apayer) }}</td>
                                    <td>{{ formatMontant($facturation->total_payer) }}</td>
                                    <td>{{ formatMontant($facturation->reste_apayer) }}</td>
                                    <td>{{ $facturation->periode->libelle }}</td>
                                    <td>{{ $facturation->date_debut ? formatDate($facturation->date_debut) : 'Non défini' }}
                                    </td>
                                    <td>{{ $facturation->date_fin ? formatDate($facturation->date_fin) : 'Non défini' }}
                                    </td>
                                    {{-- <td>
                                        @if ($facturation->status == 1)
                                            <span
                                                style="color: blue; background-color: rgba(0, 0, 255, 0.3); border: 1px solid rgb(18, 18, 209); padding: 1px; border-radius: 5px; text-transform:uppercase;">
                                                Impayé
                                            </span>
                                        @elseif ($facturation->status == 3)
                                            <span
                                                style="color: rgba(54, 143, 54, 0.615); background-color: rgba(54, 143, 54, 0.1); border: 1px solid rgba(54, 143, 54, 0.615); padding: 1px; border-radius: 5px;">
                                                Payer
                                            </span>
                                        @elseif ($facturation->status == 4)
                                            <span
                                                style="color: rgba(54, 143, 54, 0.615); background-color: rgba(54, 143, 54, 0.1); border: 1px solid rgba(54, 143, 54, 0.615); padding: 1px; border-radius: 5px;">
                                                Terminer
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($facturation->reste_apayer != 0)
                                            <a
                                                href="{{ route('fichePaiement.mutualiste', ['idfact' => $facturation->id, 'idprod' => $projetMutualiste->id]) }}">
                                                <button type="button" class="btn btn-primary">
                                                    <i class="fa fa-credit-card me-2"></i>
                                                    Payer
                                                </button>
                                            </a>
                                        @endif
                                    </td> --}}
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
                {{-- <span>TOTAL A PAYER : <b>{{ formatMontant($sommes) }}</b></span>
                <br>
                <span>RESTE A PAYER : <b>{{ formatMontant($sommes) }}</b></span> --}}
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
            </div>
        </div>
    </div>
    <!-- End Enrole Course  -->
@endsection
