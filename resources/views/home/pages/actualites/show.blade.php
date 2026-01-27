@extends('layouts.home', ['title' => 'Detail Actualites'])
@section('content')
    <a class="close_side_menu" href="javascript:void(0);"></a>
    <!-- Start breadcrumb Area -->
    <div class="rbt-breadcrumb-default ptb--100 ptb_md--50 ptb_sm--30 bg-gradient-1">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb-inner text-center">
                        <h2 class="title">Detail de l'actualite</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumb Area -->
    <div class="rbt-single-product-area rbt-single-product rbt-section-gap">
        <div class="container">
            <div class="col-lg-2 col-md-2 col-sm-2 mb-3 col-12">
                <a href="{{ back()->getTargetUrl() }}#actualites" class="rbt-btn btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                        class="bi bi-arrow-left-square" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm11.5 5.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z" />
                    </svg>
                    Retour
                </a>
            </div>
            <div class="row g-5 row--30 align-items-center">
                <div class="col-lg-6">
                    <div class="thumbnail">
                        <a href="{{ asset($actualite->lien_photo) }}">
                            <img class="w-100 radius-10" src="{{ asset($actualite->lien_photo) }}" style="height: 455px"
                                alt="Images {{ $actualite->libelle }}">
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="content">
                        <h2 class="title mt--10 mb--10">{{ $actualite->libelle }}</h2>
                        <span class="rbt-label-style description">{{ formatDate($actualite->date_actualite) }}</span>
                        <p class="mt--20">
                            {!! $actualite->description !!}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
