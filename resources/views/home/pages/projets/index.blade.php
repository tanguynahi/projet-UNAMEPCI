@extends('layouts.home', ['title' => 'Projet'])
@section('content')
    <a class="close_side_menu" href="javascript:void(0);"></a>
    @if ($projets->count() > null)
        <div class="rbt-page-banner-wrapper">
            <!-- Start Banner BG Image  -->
            <div class="rbt-banner-image"></div>
            <!-- End Banner BG Image  -->
            <div class="rbt-banner-content">
                <!-- Start Banner Content Top  -->
                <div class="rbt-banner-content-top">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12">
                                <!-- Start Breadcrumb Area  -->
                                <ul class="page-list">
                                    <li class="rbt-breadcrumb-item"><a href="{{ route('accueil') }}">Accueil</a></li>
                                    <li>
                                        <div class="icon-right"><i class="feather-chevron-right"></i></div>
                                    </li>
                                    <li class="rbt-breadcrumb-item active">Liste des Projets</li>
                                </ul>
                                <!-- End Breadcrumb Area  -->
                                <div class=" title-wrapper">
                                    <h1 class="title mb--0">Liste des Projets</h1>
                                    <a href="#" class="rbt-badge-2">
                                        <div class="image">🎉</div> {{ $projets->count() }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Banner Content Top  -->
            </div>
        </div>
    @else
        <div class="alert alert-primary mt-5 mb-5" role="alert">
            <marquee behavior="" direction="">
                <h1> Aucune donnée chargée sur la page</h1>
            </marquee>
        </div>
    @endif
    <div class="rbt-counterup-area rbt-section-overlayping-top rbt-section-gapBottom">
        <div class="container">
            <div class="row g-5">
                <!-- Start Single Event  -->
                @foreach ($projets as $projet)
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="rbt-card event-grid-card variation-01 rbt-hover">
                            <div class="rbt-card-img">
                                <a href="{{ route('detail.projet', $projet->id) }}">
                                    <img src="{{ asset($projet->lien_photo) }}" style="height: 200px;" alt="Image Projet">
                                    <div class="rbt-badge-3 bg-white">
                                        <span>{{ $projet->produitsProjet->count() }}</span>
                                        <span>Produit(s)</span>
                                    </div>
                                </a>
                            </div>
                            <div class="rbt-card-body">
                                <h4 class="rbt-card-title"><a
                                        href="{{ route('detail.projet', $projet->id) }}">{{ $projet->libelle }}</a></h4>
                                <div class="read-more-btn">
                                    <a class="rbt-btn btn-border hover-icon-reverse btn-sm radius-round"
                                        href="{{ route('detail.projet', $projet->id) }}">
                                        <span class="icon-reverse-wrapper">
                                            <span class="btn-text">Detail</span>
                                            <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                            <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="row">
                <div class="col-lg-12 mt--60">
                    {{ $projets->links('vendor.pagination.custom') }}
                </div>
            </div>
        </div>
    </div>
    <div class="rbt-separator-mid">
        <div class="container">
            <hr class="rbt-separator m-0">
        </div>
    </div>
@endsection
