@extends('layouts.home_dashboard', ['title' => 'liste des differents produits du projet'])
@section('content')
    <div class="col-lg-9">
        <div class="rbt-rbt-card-area rbt-section-gap bg-color-extra2">
            <div class="container">
                <!-- Start Card Area -->
                @foreach ($produitprojets as $index => $produitprojet)
                    <div class="row row--15">
                        @php
                            $lienPhoto = $produitprojet->lien_photo
                                ? asset($produitprojet->lien_photo)
                                : asset('assets/home/images/course/course-list-03.jpg');
                            $statusBadge =
                                $produitprojet->status == 1
                                    ? '<span class="badge bg-success"> Actif </span>'
                                    : '<span class="badge bg-danger"> Inactif </span>';
                        @endphp
                        <div class="col-lg-12 col-md-12 col-sm-12 col-12 " data-sal-delay="150" data-sal="slide-up"
                            data-sal-duration="800">
                            <div class="rbt-card variation-01 rbt-hover card-list-2">
                                <div class="rbt-card-img">
                                    <a href="#">
                                        <img src="{{ $lienPhoto }}" alt="Image Du Produit ">
                                    </a>
                                </div>
                                <div class="rbt-card-body">
                                    <h4 class="rbt-card-title">
                                        <a href="#">{{ $produitprojet->libelle }}</a>
                                    </h4>
                                    <span class="lesson-number"> {!! couperTexte2($produitprojet->description, 10) !!} </span> <br>
                                    <span class="lesson-time">{{ $produitprojet->piece }} pieces <br>
                                        <br>
                                    </span>
                                    <span class="lesson-time"> Cout :
                                        {{ formatMontant($produitprojet->cout) }}
                                    </span><br>
                                    <span class="lesson-time">Contribution:
                                        {{ formatMontant($produitprojet->contribution) }}/mois
                                    </span>
                                    <div class="text-center">
                                        <a class="rbt-btn btn-md mx-5"
                                            href="{{ route('demandeproduits.create', ['produit_projet_id' => $produitprojet->id]) }}">Souscrire</a>
                                        <a class="rbt-btn btn-md mx-5"
                                            href="{{ route('mutualiste.detailproduit', $produitprojet->id) }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                                <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0" />
                                                <path
                                                    d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7" />
                                            </svg>
                                            Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <!-- End Card Area -->
            </div>
        </div>
    </div>
@endsection
