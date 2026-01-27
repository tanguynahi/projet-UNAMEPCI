@extends('layouts.home_dashboard', ['title' => 'Detail Paiement | FPM Mutualpay'])
@section('content')
    <div class="col-lg-9">
        <div class="rbt-dashboard-content bg-color-white rbt-shadow-box">
            <div class="content">
                <div class="section-title">
                    <a href="{{ back()->getTargetUrl() }}" style="padding-bottom:12px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor"
                            class="bi bi-arrow-left-square-fill" viewBox="0 0 16 16">
                            <path
                                d="M16 14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2zm-4.5-6.5H5.707l2.147-2.146a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 0 0 .708-.708L5.707 8.5H11.5a.5.5 0 0 0 0-1" />
                        </svg>
                    </a>
                    <br>
                    <span class="rbt-title-style-3">Reference de paiement </span>
                    <hr>
                </div>
                <div class="row">
                    <div class="col-6 col-lg-6 col-sm-6 mb-4">
                        <span><u>Informations sur le produit</u></span>
                        <br>
                        <strong>{{ $produitMutualiste->libelle }}</strong> <br>
                        <span>{{ $produitMutualiste->commentaire }}</span>
                    </div>
                    <div class="col-6 col-lg-6 col-sm-6">
                        <span><u>Informations sur l'affaire</u></span>
                        <br>
                        <span>Montant Affaire : </span> <strong>{{formatMontant( $produitMutualiste->total_apayer) }}</strong> <br>
                        <span>date</span> : <strong>{{ formatDate($produitMutualiste->date) }}</strong>
                    </div>
                    <hr>
                    <br>
                    <div class="col-12 col-lg-12 col-sm-12">
                        <strong><u>Redevance du paiement</u></strong> <br>
                        <div class="row mb-4 mt-2">
                            <div class="col-3 col-lg-3 col-sm-3">
                                <span>Description:</span> <strong>{{ $facturation->redevance->libelle }}</strong>
                            </div>
                            <div class="col-3 col-lg-3 col-sm-3">
                                <span>Type:</span> <strong>{{ $facturation->periode->libelle }}</strong>
                            </div>
                            <div class="col-3 col-lg-3 col-sm-3">
                                <span>Total a payer:</span> <strong>{{ formatMontant($facturation->total_apayer) }}</strong>
                            </div>
                            <div class="col-3 col-lg-3 col-sm-3">
                                <span>Reste a payer:</span> <strong>{{ formatMontant($facturation->reste_apayer) }}</strong>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="col-12 col-lg-12 col-sm-12">
                        <strong><u>Paiement</u></strong> <br>
                        <div class="row mb-3 mt-2">
                            <div class="col-3 col-lg-3 col-sm-3">
                                <span>Reference:</span> <strong>{{ $lignePaiements->reference }}</strong>
                            </div>
                            <div class="col-3 col-lg-3 col-sm-3 mb-3">
                                <span>Montant:</span> <strong>{{ formatMontant($lignePaiements->montant_initial) }}
                                </strong>
                            </div>
                            <div class="col-3 col-lg-3 col-sm-3 mb-3">
                                <span>Date & heure:</span>
                                <strong>{{ formatDate($lignePaiements->date_paiement_initial) }}</strong>
                            </div>
                            <div class="col-3 col-lg-3 col-sm-3 mb-3">
                                <span>Moyen:</span> <strong>{{ $lignePaiements->moyen_paiement }}</strong>
                            </div>
                            @if (!empty($lignePaiements->contact_paiement))
                                <div class="col-6 col-lg-6 col-sm-6 mb-3">
                                    <span>Numero de
                                        Transation:</span><strong>{{ $lignePaiements->contact_paiement }}</strong>
                                </div>
                            @endif

                            <div class="col-6 col-lg-6 col-sm-6 mb-3">
                                <span>Etat:</span> <strong>
                                    @if ($lignePaiements->status == 2)
                                        <span class=" text-primary">EN ATTENTE</span>
                                    @elseif($lignePaiements->status == 3)
                                    <span class="text-danger">ECHEC</span>
                                    @else
                                    <span class="text-success">VALIDE</span>
                                    @endif
                                </strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
@endpush
