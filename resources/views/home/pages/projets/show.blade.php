@extends('layouts.home', ['title' => 'Detail du Projet'])
@section('content')
    {{-- <a class="close_side_menu" href="javascript:void(0);"></a> --}}
    <div class="rbt-overlay-page-wrapper">
        <div class="breadcrumb-image-container breadcrumb-style-max-width">
            <div class="breadcrumb-image-wrapper">
                <img src="{{ asset('assets/home/images/bg/bg-image-10.jpg') }}" alt="image en fond">
            </div>
            <div class="container text-center">
                <div class="row">
                    <div class="col"></div>
                    <div class="col-lg-1 col-md-2 col-sm-2 offset-lg-2">
                        <a href="{{ back()->getTargetUrl() }}#projet" class="rbt-btn btn-sm offset-md-2 offset-sm-2">
                            Retour
                        </a>
                    </div>
                </div>
            </div>
            <div class="breadcrumb-content-top text-center">
                <h1 class="title">{{ $projet->libelle }}</h1>
            </div>
        </div>
        <div class="rbt-blog-details-area  mb-5 breadcrumb-style-max-width">
            <div class="blog-content-wrapper rbt-article-content-wrapper">
                <div class="content">
                    <div class="post-thumbnail mb--30 position-relative wp-block-image alignwide">
                        <figure>
                            <img src="{{ asset($projet->lien_photo) }}" alt="image presentation" height="100%"
                                width="100%">
                            {{-- si le projet a des Images  --}}
                            @if ($projet->imagesProjet->count() > 0)
                                <div class="row row--15">
                                    <div class="col-lg-12 col-12 col-xl-12 col-xxl-12 col-md-12 col-sm-12">
                                        <div class="swiper team-slide-activation-4 rbt-arrow-between rbt-dot-bottom-center">
                                            <h6 class="text-center color-primary"> Les Images Du {{ $projet->libelle }}
                                            </h6>
                                            <div class="swiper-wrapper">
                                                @foreach ($projet->imagesProjet as $imageProjet)
                                                    <div class="swiper-slide">
                                                        <div class="team team-style--bottom variation-2">
                                                            <div class="thumbnail">
                                                                <a href="#"><img
                                                                        src="{{ asset($imageProjet->lien_image) }}"
                                                                        alt="Image du projet {{ $loop->iteration }}"></a>
                                                            </div>
                                                            <div class="content">
                                                                <div class="inner">
                                                                    <h4 class="title">
                                                                        <a href="{{ asset($imageProjet->lien_image) }}">
                                                                            <i class="fa fa-eye"> voir</i>
                                                                        </a>
                                                                    </h4>
                                                                    <p class="designation">Image du projet
                                                                        {{ $loop->iteration }}</p>
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
                                            <div class="rbt-swiper-pagination"></div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <figcaption>{{ $projet->libelle }} </figcaption>
                        </figure>
                    </div>
                    <p>
                        {!! $projet->description !!}
                    </p>
                </div>
            </div>
        </div>
        {{-- les images des produit du projet --}}
        <div class="rbt-categories-area rbt-section mb-2" id="produits">
            @if ($nobre = $produitprojets->count() > null)
                <h5 class="text-center " style="color: rgb(129, 118, 133)"><u> Les Produits du Projet</u></h5>
            @endif
            <div class="container">
                <div class="row g-5 mb-5">
                    <!-- Start Category Box Layout  -->
                    @foreach ($produitprojets as $index => $produitprojet)
                        @php
                            $lienPhoto = $produitprojet->lien_photo
                                ? asset($produitprojet->lien_photo)
                                : asset('assets/home/images/course/course-list-03.jpg');
                        @endphp
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="rbt-cat-box rbt-cat-box-1 variation-3 text-justify">
                                <div class="inner">
                                    {{-- <a href="{{ route('mutualiste.detailproduit', $produitprojet->id) }}"> --}}
                                    <div class="thumbnail">
                                        <a href="{{ route('mutualiste.detailproduit', $produitprojet->id) }}">
                                            <img src="{{ $lienPhoto }}" alt="Image du Produit {{ $loop->iteration }}"
                                                style="height: 300px">
                                            <div class="read-more-btn">
                                                <span class="rbt-btn btn-sm btn-white radius-round">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                                                        <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0" />
                                                        <path
                                                            d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7" />
                                                    </svg>
                                                    Voir Plus
                                                </span>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="content">
                                        <h5 class="title text-center"><a
                                                href="{{ route('mutualiste.detailproduit', $produitprojet->id) }}">{{ $produitprojet->libelle }}</a>
                                        </h5>
                                        <p class="description text-center">
                                            <span>Montant: {{ formatMontant($produitprojet->cout) }}</span>
                                            <span class="lesson-time">Contribution:
                                                {{ formatMontant($produitprojet->contribution) }}/mois
                                            </span>
                                        </p>
                                        {{-- <p>
                                            <span>
                                                {!! couperTexte2($produitprojet->description, 10) !!}
                                            </span>
                                        </p> --}}
                                    </div>
                                    {{-- </a> --}}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
