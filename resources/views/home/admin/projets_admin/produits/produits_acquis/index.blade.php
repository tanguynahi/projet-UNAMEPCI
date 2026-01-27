@extends('layouts.home_dashboard', ['title' => 'Proprietes Acquis'])
@section('content')
    <div class="col-lg-9">
        <div class="rbt-dashboard-content bg-color-white rbt-shadow-box">
            <div class="content">
                <div class="section-title">
                    <h4 class="rbt-title-style-3">Propriétés</h4>
                </div>
                <div class="row g-5">
                        <div class="col-lg-6 col-md-6 col-12">
                            <div class="rbt-card variation-01 rbt-hover">
                                <div class="rbt-card-img">
                                    <a href="{{ asset($projetMutualiste->produitProjet->lien_photo) }}">
                                        <img src="{{ asset($projetMutualiste->produitProjet->lien_photo) }}"
                                            style="height: 200px; " alt=" image {{ $projetMutualiste->produitProjet->libelle }}">
                                    </a>
                                </div>
                                <div class="rbt-card-body">
                                    <div class="rbt-card-top">
                                        <div class="rbt-review">
                                            <h6>{{ $projetMutualiste->produitProjet->projet->libelle }} |
                                                {{ $projetMutualiste->produitProjet->libelle }}</h6>
                                        </div>
                                    </div>
                                    <div class="rbt-review">
                                        <span class="rating-count">
                                            {!! couperTexte2($projetMutualiste->produitProjet->description, 5) !!}
                                        </span>
                                    </div>
                                    <span>Coût : {{ formatMontant($projetMutualiste->montant_produit) }}</span> <br>
                                    <span>à Payer :
                                        <span>{{ formatMontant($projetMutualiste->total_apayer) }}</span></span><br>
                                    <span>Date : {{ formatDate($projetMutualiste->date) }}</span> <br>
                                    <div class="row mt-5 justify-content-center">
                                        <div class="col-4">
                                            <a href="{{ route('produitacquis.detail', $projetMutualiste->id) }}"
                                                title="detail" class="btn btn-lg btn-primary">
                                                <i class="fa fa-eye"></i>
                                                Détail
                                            </a>
                                        </div>
                                        <div class="col-4">
                                            <a href="{{ route('produitacquis.paiement', $projetMutualiste->id) }}"
                                                title="paiemnts" class="btn btn-lg btn-primary">
                                                <i class="fa fa-credit-card"></i>
                                                Paiement
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                <!-- End Single Course  -->
            </div>
        </div>
    </div>
    <!-- End Enrole Course  -->
    </div>
@endsection
