@extends('layouts.home', ['title' => 'Detail du produit '])
@section('content')

    <a class="close_side_menu" href="javascript:void(0);"></a>
    <div class="rbt-single-product-area  rbt-section-gap">
        <div class="container">
            <div class="row">
                <div class="col"></div>
                <div class="col-lg-1 col-md-2 col-sm-2 offset-lg-2 " style="margin-bottom: 50px;">
                    <a href="{{ back()->getTargetUrl() }}#produits" class="rbt-btn btn-sm offset-md-2 offset-sm-2">
                        Retour
                    </a>
                </div>
            </div>
            <div class="row g-5 row--30 align-items-top">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="thumbnail">
                            <a href="{{ asset($produitprojet->lien_photo) }}">
                                <img class="w-100 radius-10" src="{{ asset($produitprojet->lien_photo) }}"
                                    style="height:100%" alt="Images {{ $produitprojet->libelle }}"
                                    title="Images {{ $produitprojet->libelle }}">
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <h2 class="title mt--10 mb--10"> {{ $produitprojet->libelle }}</h2>
                        <div class="rbt-price justify-content-start mt--10">
                            <span class="current-price theme-gradient"> Cout :
                                {{ formatMontant($produitprojet->cout) }}</span>
                        </div>
                        <b><u>Contribution</u> : {{ formatMontant($produitprojet->contribution) }} / Mois</b> <br>
                        <b><u>Nombre(s)</u> : {{ $produitprojet->quantite }}</b>
                        <div class="row mt-5">
                            @if ($produitprojet->imageProjets !== null && $produitprojet->imageProjets->count() > 0)
                                @if ($produitprojet->imageProjets->count() < 3)
                                    <div class="wp-block-gallery columns-2 is-cropped">
                                        <ul class="blocks-gallery-grid">
                                            @foreach ($produitprojet->imageProjets as $imageProjet)
                                                <li class="blocks-gallery-item">
                                                    <figure>
                                                        <a href="{{ asset($imageProjet->lien_image) }}">
                                                            <img class="radius-4"
                                                                src="{{ asset($imageProjet->lien_image) }}"
                                                                style="height: 180px; width:200px;" alt="Image n°
                                                                            {{ $loop->iteration }} du
                                                                            {{ $produitprojet->libelle }}">
                                                        </a>
                                                    </figure>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @else
                                    <div class="row row--15">
                                        <div class="col-lg-12 col-md-12 col-xl-12 col-xxl-12 col-sm-12 col-12">
                                            <div
                                                class="swiper team-slide-activation-4 rbt-arrow-between rbt-dot-bottom-center">
                                                <div class="swiper-wrapper">
                                                    @foreach ($produitprojet->imageProjets as $imageProjet)
                                                        <div class="swiper-slide">
                                                            <div class="team team-style--bottom variation-2">
                                                                <div class="thumbnail">
                                                                    <a href="{{ asset($imageProjet->lien_image) }}">
                                                                        <img src="{{ asset($imageProjet->lien_image) }}"
                                                                            alt="Image n°
                                                                            {{ $loop->iteration }} du
                                                                            {{ $produitprojet->libelle }}"
                                                                            style="height:140px; width:200px;">
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <div class="rbt-swiper-arrow rbt-arrow-left">
                                                    <div class="custom-overfolow">
                                                        <i class="rbt-icon feather-arrow-left"></i>
                                                        <i class="rbt-icon-top feather-arrow-left"></i>
                                                    </div>
                                                </div>
                                                <div class="rbt-swiper-arrow rbt-arrow-right">
                                                    <div class="custom-overfolow">
                                                        <i class="rbt-icon feather-arrow-right"></i>
                                                        <i class="rbt-icon-top feather-arrow-right"></i>
                                                    </div>
                                                </div>
                                                <div class="rbt-swiper-pagination"></div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-5 row--30 mb-1 align-items-top" id="description_produit">
                <div class="col-lg-12">
                    <div class="content">
                        <p class="mt--20">
                            {!! $produitprojet->description !!}
                        </p>
                        <div class="product-action mb-2">
                            <div class="addto-cart-btn">
                                @if (Auth::check())
                                    @if (Auth::user()->mutualiste)
                                        @php
                                            $mutualiste = auth()->user()->mutualiste;
                                            $status = $mutualiste->droitAdhesion->status;
                                        @endphp
                                        @if ($status == 1)
                                            <p class="text-center">
                                                <a class="rbt-btn btn-gradient"
                                                    href="{{ route('demandeproduits.create', ['produit_projet_id' => $produitprojet->id]) }}">
                                                    <span class="btn-text">Souscrire</span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
                                                        <path
                                                            d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l1.313 7h8.17l1.313-7zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2" />
                                                    </svg>
                                                </a>
                                            </p>
                                        @else
                                            <div class="alert alert-primary w-100 justify-content-center" role="alert">
                                                <p class="text-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2"
                                                        viewBox="0 0 16 16" role="img" aria-label="Warning:">
                                                        <path
                                                            d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                                    </svg>
                                                    Veuillez vous acquitter de votre droit d'adhésion pour pouvoir
                                                    souscrire à
                                                    ce produit.
                                                </p>
                                            </div>
                                            <div class="rbt-price align-items-center mt--10  w-100 justify-content-center">
                                                <a class="rbt-btn d-flex offset-md-2 offset-sm-2"
                                                    href="{{ route('espace.accueil') }}">
                                                    <span data-text="Mon Espace">Mon Espace</span>
                                                </a>
                                                <span class="off-price">
                                                    <a class="rbt-btn btn-gradient">
                                                        <span class="btn-text">Souscrire </span>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                            height="16" fill="currentColor" class="bi bi-cart"
                                                            viewBox="0 0 16 16">
                                                            <path
                                                                d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l1.313 7h8.17l1.313-7zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2" />
                                                        </svg>
                                                    </a>
                                                </span>
                                            </div>
                                        @endif
                                    @else
                                        <div class="alert alert-primary  w-100 justify-content-center " role="alert">
                                            <p class="text-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2"
                                                    viewBox="0 0 16 16" role="img" aria-label="Warning:">
                                                    <path
                                                        d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                                </svg>
                                                Connecter vous pour pouvoir souscrire au produit
                                            </p>
                                        </div>
                                        <div class="rbt-price  align-items-center mt--10  w-100 justify-content-center">
                                            <a class="rbt-btn  offset-md-2 offset-sm-2 " href="{{ route('connexion') }}">
                                                <span data-text="Se connecter">Se connecter</span>
                                            </a>
                                            <span class="off-price ">
                                                <a class="rbt-btn btn-gradient">
                                                    <span class="btn-text">Souscrire </span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
                                                        <path
                                                            d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l1.313 7h8.17l1.313-7zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2" />
                                                    </svg>
                                                </a>
                                            </span>
                                        </div>
                                    @endif
                                @else
                                    <div class="alert alert-primary  w-100 justify-content-center " role="alert">
                                        <p class="text-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2"
                                                viewBox="0 0 16 16" role="img" aria-label="Warning:">
                                                <path
                                                    d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                            </svg>
                                            Connecter vous pour pouvoir souscrire au produit
                                        </p>
                                    </div>
                                    <div class="rbt-price  align-items-center mt--10  w-100 justify-content-center">
                                        <a class="rbt-btn  offset-md-2 offset-sm-2 " href="{{ route('connexion') }}">
                                            <span data-text="Se connecter">Se connecter</span>
                                        </a>
                                        <span class="off-price ">
                                            <a class="rbt-btn btn-gradient">
                                                <span class="btn-text">Souscrire </span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
                                                    <path
                                                        d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l1.313 7h8.17l1.313-7zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2" />
                                                </svg>
                                            </a>
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
