@extends('layouts.home', ['title' => 'Presentation'])
@section('content')
    <a class="close_side_menu" href="javascript:void(0);"></a>
    <!-- Start Banner Area -->
    @if ($parametre->lien_video == null && $parametre->lien_photo_directeur == null && $parametre->mot_du_directeur == null)
        <div class="alert alert-primary mt-5 mb-5" role="alert">
            <marquee behavior="" direction="">
                <h1> Aucune donnée chargée sur la page</h1>
            </marquee>
        </div>
    @else
        <div class="rbt-banner-area rbt-banner-8 variation-02">
            <div class="container">
                <div class="row g-5 align-items-center">
                    <div class="col-lg-10 offset-lg-1">
                        <div class="content">
                            <div class="inner text-center">
                                <div class="rbt-new-badge rbt-new-badge-one">
                                    <span class="rbt-new-badge-icon">🏆</span> La meilleure plateforme en ligne dédiée a UNAMEPCI

                                </div>
                                <h1 class="title">Nous prônons
                                    <span class="header-caption">
                                        <span class="cd-headline clip is-full-width">
                                            <span class="cd-words-wrapper">
                                                <b class="is-visible theme-gradient">la solidarité.</b>
                                                <b class="is-hidden theme-gradient">l'engagement.</b>
                                                <b class="is-hidden theme-gradient">l'excellence.</b>
                                                <b class="is-hidden theme-gradient">l'intégrité.</b>
                                                <b class="is-hidden theme-gradient">l'innovation.</b>
                                                <b class="is-hidden theme-gradient">le respect.</b>
                                            </span>
                                        </span>
                                    </span>
                                </h1>
                                <p class="description has-medium-font-size mt--20">Découvrez la référence en ligne pour les
                                    mutualistes de l'Union Nationale des Medecins Prives de Côte d'Ivoire  : un soutien dédié, des services personnalisés.
                                </p>
                                <div class="slider-btn rbt-button-group justify-content-center">
                                    @if (Auth::check())
                                        @if (Auth::user()->mutualiste)
                                            <a class="rbt-btn btn-gradient hover-icon-reverse"
                                                href="{{ route('espace.accueil') }}">
                                                <span class="icon-reverse-wrapper">
                                                    <span class="btn-text" data-text="Mon Espace">Mon Espace</span>
                                                    <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                                    <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                                </span>
                                            </a>
                                        @else
                                            <a class="rbt-btn btn-gradient hover-icon-reverse"
                                                href="{{ route('connexion') }}">
                                                <span class="icon-reverse-wrapper">
                                                    <span class="btn-text">Connecter vous</span>
                                                    <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                                    <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                                </span>
                                            </a>
                                        @endif
                                    @else
                                        <a class="rbt-btn btn-gradient hover-icon-reverse" href="{{ route('connexion') }}">
                                            <span class="icon-reverse-wrapper">
                                                <span class="btn-text">Connecter vous</span>
                                                <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                                <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                            </span>
                                        </a>
                                    @endif
                                    <a class="rbt-btn hover-icon-reverse btn-white" href="{{ route('contact') }}">
                                        <span class="icon-reverse-wrapper">
                                            <span class="btn-text">
                                                Contactez-nous</span>
                                            <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                            <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Banner Area -->
        <!-- Start Video Area  -->
        <!--<div class="rbt-video-area">-->
        <!--    <div class="container">-->
        <!--        <div class="row">-->
        <!--            <div class="col-lg-12">-->
        <!--                <div class="video-popup-wrapper">-->
        <!--                    <img class="w-100 rbt-radius"-->
        <!--                        src="{{ asset('assets/home/images/presentation/presentation6.jpg') }}" alt="Video Images"-->
        <!--                        style="height:800px;">-->
        <!--                    <a class="rbt-btn rounded-player-2 popup-video position-to-top with-animation btn-theme-color"-->
        <!--                        href="{{ $parametre->lien_video }}">-->
        <!--                        {{-- https://www.youtube.com/watch?v=LPTg5sunA8w --}}-->
        <!--                        <span class="play-icon"></span>-->
        <!--                    </a>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </div>-->
        <!--    </div>-->
        <!--</div>-->
        @php
    $videoId = '';

    if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&]+)/', $parametre->lien_video, $matches)) {
        $videoId = $matches[1];
    }

    $thumbnail = $videoId ? "https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg" : asset('assets/home/images/presentation/presentation6.jpg');
@endphp

        <div class="rbt-video-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="video-popup-wrapper">
                    <img class="w-100 rbt-radius"
                        src="{{ $thumbnail }}"
                        alt="Vidéo de présentation"
                        style="height:800px; object-fit:cover;">

                    <a class="rbt-btn rounded-player-2 popup-video position-to-top with-animation btn-theme-color"
                        href="{{ $parametre->lien_video }}">
                        <span class="play-icon"></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

        <!-- End Video Area  -->
        <div class="rbt-about-area about-style-1 bg-color-white rbt-section-gap">
            <div class="container">
                <div class="row g-5 align-items-center">
                    <div class="col-lg-6">
                        <div class="content">
                            @if ($parametre->lien_photo_directeur > null)
                                <img src="{{ asset($parametre->lien_photo_directeur) }}" style="height:350px;"
                                    alt="Image du directeur">
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-6" data-sal="slide-up" data-sal-duration="700">
                        <div class="inner pl--50 pl_sm--5">
                            <div class="content text-start">
                                @if ($parametre->mot_du_directeur > null)
                                    <span class="rbt-badge-6 bg-primary-opacity">Administration</span>
                                    <h3 class="title">Mot du Directeur</h3>
                                @endif
                                <p class="description mt--30">
                                    {!! $parametre->mot_du_directeur !!}
                                </p>
                                <div class="read-more-btn mt--40">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--<div class="rbt-team-area bg-color-white rbt-section-gapBottom">-->
        <!--    <div class="container">-->
        <!--        <div class="row mb--60">-->
        <!--            <div class="col-lg-12">-->
        <!--                <div class="section-title text-center">-->
        <!--                    <h5 class="title">L'equipe du FPM</h5>-->
        <!--                    <p class="description mt--10">Quelques responsable-->
        <!--                    </p>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </div>-->
        <!--        <div class="row row--15 mt_dec--30">-->
                    <!-- Start Single Team  -->
        <!--            <div class="col-lg-4 col-md-6 col-12 mt--30">-->
        <!--                <div class="rbt-team team-style-default rbt-hover-02">-->
        <!--                    <div class="inner">-->
        <!--                        <div class="thumbnail">-->
        <!--                            <img src="{{ asset('assets/home/images/presentation/presentation3.jpg') }}"-->
        <!--                                alt="Corporate Template">-->
        <!--                        </div>-->
        <!--                        <div class="content">-->
        <!--                            <h2 class="title">Koffi Jeans </h2>-->
        <!--                            <h6 class="subtitle theme-gradient"> Directeur des Ressources Humaines </h6>-->
        <!--                            <span class="team-form">-->
        <!--                                <i class="feather-map-pin"></i>-->
        <!--                                <span class="location">Abidjan Angré 8ème Tranche</span>-->
        <!--                            </span>-->
        <!--                            <p class="description">Lieutenant (Gendarmerie), d'escadrons Alpha 4 (Angré)</p>-->
        <!--                            <ul class="social-icon social-default icon-naked mt--20">-->
        <!--                                <li><a href="#">-->
        <!--                                        <i class="feather-facebook"></i>-->
        <!--                                    </a>-->
        <!--                                </li>-->
        <!--                                <li><a href="#">-->
        <!--                                        <i class="feather-twitter"></i>-->
        <!--                                    </a>-->
        <!--                                </li>-->
        <!--                                <li><a href="#">-->
        <!--                                        <i class="feather-instagram"></i>-->
        <!--                                    </a>-->
        <!--                                </li>-->
        <!--                            </ul>-->
        <!--                        </div>-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--            </div>-->
                    <!-- End Single Team  -->
                    <!-- Start Single Team  -->
        <!--            <div class="col-lg-4 col-md-6 col-12 mt--30">-->
        <!--                <div class="rbt-team team-style-default rbt-hover-02">-->
        <!--                    <div class="inner">-->
        <!--                        <div class="thumbnail">-->
        <!--                            <img src="{{ asset('assets/home/images/presentation/armand.jpg') }}"-->
        <!--                                alt="Corporate Template">-->
        <!--                        </div>-->
        <!--                        <div class="content">-->
        <!--                            <h2 class="title">Nahi Armand </h2>-->
        <!--                            <h6 class="subtitle theme-gradient"> Chef Comptable </h6>-->
        <!--                            <span class="team-form">-->
        <!--                                <i class="feather-map-pin"></i>-->
        <!--                                <span class="location">Abidjan Cocody Abatta</span>-->
        <!--                            </span>-->
        <!--                            <p class="description">Adjudant-chef major (militaire),Battail blinde A16 (Plateau)</p>-->
        <!--                            <ul class="social-icon social-default icon-naked mt--20">-->
        <!--                                <li><a href="#">-->
        <!--                                        <i class="feather-facebook"></i>-->
        <!--                                    </a>-->
        <!--                                </li>-->
        <!--                                <li><a href="#">-->
        <!--                                        <i class="feather-twitter"></i>-->
        <!--                                    </a>-->
        <!--                                </li>-->
        <!--                                <li><a href="#">-->
        <!--                                        <i class="feather-instagram"></i>-->
        <!--                                    </a>-->
        <!--                                </li>-->
        <!--                            </ul>-->
        <!--                        </div>-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--            </div>-->
                    <!-- End Single Team  -->
                    <!-- Start Single Team  -->
        <!--            <div class="col-lg-4 col-md-6 col-12 mt--30">-->
        <!--                <div class="rbt-team team-style-default rbt-hover-02">-->
        <!--                    <div class="inner">-->
        <!--                        <div class="thumbnail">-->
        <!--                            <img src="{{ asset('assets/home/images/presentation/leticia1.jpg') }}"-->
        <!--                                alt="Corporate Template">-->
        <!--                        </div>-->
        <!--                        <div class="content">-->
        <!--                            <h2 class="title">Man Leticia </h2>-->
        <!--                            <h6 class="subtitle theme-gradient"> Trésorière</h6>-->
        <!--                            <span class="team-form">-->
        <!--                                <i class="feather-map-pin"></i>-->
        <!--                                <span class="location">Abidjan Angré 7ème Tranche </span>-->
        <!--                            </span>-->
        <!--                            <p class="description">Capitaine (Gendarmerie),Battail blinde Agban (Adjame)</p>-->
        <!--                            <ul class="social-icon social-default icon-naked mt--20">-->
        <!--                                <li><a href="#">-->
        <!--                                        <i class="feather-facebook"></i>-->
        <!--                                    </a>-->
        <!--                                </li>-->
        <!--                                <li><a href="#">-->
        <!--                                        <i class="feather-twitter"></i>-->
        <!--                                    </a>-->
        <!--                                </li>-->
        <!--                                <li><a href="#">-->
        <!--                                        <i class="feather-instagram"></i>-->
        <!--                                    </a>-->
        <!--                                </li>-->
        <!--                            </ul>-->
        <!--                        </div>-->
        <!--                    </div>-->
        <!--                </div>-->
        <!--            </div>-->
                    <!-- End Single Team  -->
        <!--        </div>-->
        <!--    </div>-->
        <!--</div>-->
    @endif
@endsection
