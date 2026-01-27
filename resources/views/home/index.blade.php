@extends('layouts.home', ['title' => 'Accueil'])
@section('content')
    <a class="close_side_menu" href="javascript:void(0);"></a>
    @if (
        $slides->count() == null &&
            $actualites->count() == null &&
            $annonces->count() == null &&
            $partenaires->count() == null &&
            $projets->count() == null)
        <div class="alert alert-primary mt-5 mb-5" role="alert">
            <marquee behavior="" direction="">
                <h1> Aucune donnée chargée sur la page</h1>
            </marquee>
        </div>
    @else
        <div class="rbt-slider-main-wrapper position-relative">
            <!-- Start Banner Area  -->
            <div class="swiper rbt-banner-activation rbt-slider-animation rbt-arrow-between">
                <div class="swiper-wrapper">
                    <!-- Start Single Banner  -->
                    @foreach ($slides as $slide)
                        @php
                            $lienImage = $slide->lien_image
                                ? asset($slide->lien_image)
                                : asset('assets/home/images/bg/bg-image-25.jpg');
                        @endphp
                        <div class="swiper-slide">
                            <div class="rbt-banner-area rbt-banner-6 variation-03 bg_image " data-gradient-overlay="2"
                                style="@if ($slide->lien_image == null) background-image:url('{{ asset('assets/home/images/bg/bg-image-25.jpg') }}') @endif
                            background-image:url('{{ $lienImage }}')">
                                <div class="wrapper w-100">
                                    <div class="container">
                                        <div class="row align-items-center">
                                            <div class="col-lg-12">
                                                <div class="inner text-center">
                                                    <div class="section-title">
                                                        <span class="subtitle bg-white-opacity d-inline-block">
                                                            {{ $slide->titre }}
                                                        </span>
                                                    </div>
                                                    <h1 class="title w-700">
                                                        <strong class="color-white">
                                                            {{ $slide->sous_titre }}
                                                        </strong>
                                                    </h1>
                                                    <div class="section-title">
                                                        <span class="subtitle bg-white-opacity d-inline-block"> </span>
                                                    </div>
                                                    <div class="button-group mt--30">
                                                        @if (Auth::check())
                                                            @if (Auth::user()->mutualiste)
                                                                <a class="rbt-btn btn-gradient rbt-marquee-btn radius-round"
                                                                    href="{{ route('espace.accueil') }}">
                                                                    <span data-text="Mon Espace">Mon Espace</span>
                                                                </a>
                                                            @else
                                                                <a class="rbt-btn btn-gradient rbt-marquee-btn radius-round"
                                                                    href="{{ route('connexion') }}">
                                                                    <span data-text="Se connecter">Se connecter</span>
                                                                </a>
                                                            @endif
                                                        @else
                                                            <a class="rbt-btn btn-gradient rbt-marquee-btn radius-round"
                                                                href="{{ route('connexion') }}">
                                                                <span data-text="Se connecter">Se connecter</span>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
            </div>
            <div class="swiper rbt-swiper-thumb rbtmySwiperThumb">
                <div class="swiper-wrapper">
                    @foreach ($slides as $slide)
                        @php
                            $lienImage = $slide->lien_image
                                ? asset($slide->lien_image)
                                : asset('assets/home/images/bg/bg-image-25.jpg');
                        @endphp
                        <div class="swiper-slide">
                            <img src="{{ $lienImage }}" alt="Image {{ $slide->categorie }}" />
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        @include('home.pages.projets.pageAfficheProjet')

        <style>
            /* Ajoutez ceci dans votre fichier CSS */
            .card-hidden {
                display: none;
            }
        </style>
        @if ($actualites->count() > null)
            <div class="rbt-rbt-card-area rbt-section-gap bg-color-white" id="actualites">
                <div class="container">
                    <div class="row row--15 align-items-center mb--30">
                        <div class="col-lg-12">
                            <div class="section-title text-center">
                                @if ($int = $actualites->count() > null)
                                    <h2 class="title"> Actualités</h2>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- Start Card Area -->
                    <div class="row row--15">
                        @foreach ($actualites as $actualite)
                            <div class="col-lg-4 col-md-6 col-sm-6 col-12 mt--30 " data-sal-delay="150" data-sal="slide-up"
                                data-sal-duration="800">
                                <div class="rbt-card variation-03 rbt-hover">
                                    <h6> {{ formatDate($actualite->date_actualite) }}</h6>
                                    <div class="rbt-card-img">
                                        <a class="thumbnail-link" href="{{ route('detail.actualites', $actualite->id) }}">
                                            <img src="{{ asset($actualite->lien_photo) }}" style="height: 320px"
                                                alt=" image Actualite">
                                            <span class="rbt-btn btn-white icon-hover">
                                                <span class="btn-text">Détail</span>
                                                <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                            </span>
                                        </a>
                                    </div>
                                    <div class="rbt-card-body">
                                        <h5 class="rbt-card-title">
                                            <a href="{{ route('detail.actualites', $actualite->id) }}">
                                            </a>
                                            {{ $actualite->libelle }}
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if ($int = $actualites->count() > 2)
                        <center>
                            <div class="button-group mt--30">
                                <a class="rbt-btn btn-gradient rbt-marquee-btn radius-round"
                                    href="{{ route('actualites') }}" data-text="Voir plus">Voir plus</a>
                            </div>
                        </center>
                    @endif
                    <!-- End Card Area -->
                </div>
            </div>
        @endif
        {{-- end actualites --}}
        {{-- start annnonce  --}}
        <div class="rbt-banner-area ">
            <div class="container">
                <div class="section-title text-center">
                    @if ($not = $annonces->count() > null)
                        <h2 class="title">Annonces</h2> <br>
                    @endif
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="swiper viral-banner-activation rbt-arrow-between">
                            <div class="swiper-wrapper">
                                <!-- Start Single Banner  -->
                                @foreach ($annonces as $annonce)
                                    @php
                                        $lienImage = $annonce->lien_image
                                            ? asset($annonce->lien_image)
                                            : asset('assets/home/images/bg/bg-image-25.jpg');
                                    @endphp
                                    <div class="swiper-slide">
                                        <div class="thumbnail">
                                            <a href="{{ $lienImage }}">
                                                <img class="rbt-radius w-100" src="{{ $lienImage }}"
                                                    alt="Image {{ $annonce->categorie }}" style="height:600px;">
                                            </a>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- end annonce --}}
        <div class="rbt-banner-area mb-5 mt-5">
            <div class="container">
                <div class="row mb-5">
                    <div class="col-lg-12">
                        <div class="section-title text-center">
                            @if ($partenaires->count() > null)
                                <h2 class="title">Partenaires</h2>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- Start Card Area -->
                <div class="row g-5">
                    <!-- Start Service Grid  -->
                    @if ($partenaires->count() < 7)
                        @foreach ($partenaires as $partenaire)
                            @php
                                // dd($partenaires->count());
                                $lienImage = $partenaire->lien_image
                                    ? asset($partenaire->lien_image)
                                    : asset('assets/home/images/bg/bg-image-25.jpg');
                            @endphp
                            <div class="col-lg-2 col-xl-2 col-xxl-2 col-md-3 col-sm-4 col-6">
                                <div class="service-card service-card-5 variation-2">
                                    <div class="inner">
                                        <div class="icon">
                                            <a href="{{ $lienImage }}">
                                                <img src="{{ $lienImage }}" alt="{{ $partenaire->categorie }}">
                                            </a>
                                        </div>
                                        <div class="content">
                                            <h6 class="title text-center"><a
                                                    href="{{ $lienImage }}">{{ $partenaire->titre }}</a>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-lg-12 col-xl-12 col-xxl-12 col-md-12 col-sm-12 col-12">
                            <div class="swiper team-slide-activation-4 rbt-arrow-between rbt-dot-bottom-center">
                                <div class="swiper-wrapper">
                                    @foreach ($partenaires as $partenaire)
                                        @php
                                            $lienImage = $partenaire->lien_image
                                                ? asset($partenaire->lien_image)
                                                : asset('assets/home/images/bg/bg-image-25.jpg');
                                        @endphp
                                        <div class="swiper-slide service-card service-card-5 variation-2">
                                            <div class="inner">
                                                <div class="icon">
                                                    <a href="{{ $lienImage }}">
                                                        <img src="{{ asset($partenaire->lien_image) }}"
                                                            alt="Image du projet {{ $loop->iteration }}">
                                                    </a>
                                                </div>
                                                <div class="content">
                                                    <h6 class="title text-center">
                                                        <a href="{{ $lienImage }}">{{ $partenaire->titre }}</a>
                                                    </h6>
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
                    @endif
                </div>
            </div>
        </div>
    @endif
@endsection
