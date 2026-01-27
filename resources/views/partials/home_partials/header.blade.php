    <!-- Start Header Area -->
    <header class="rbt-header rbt-header-4">
        <div class="rbt-sticky-placeholder"></div>
        <!-- Start Header Top -->
        <div
            class="rbt-header-top rbt-header-top-1 variation-height-50 header-space-betwween bg-color-white border-top-bar-primary-color rbt-border-bottom d-none d-xl-block">
            <div class="container-fluid">
                <div class="rbt-header-sec align-items-center ">
                    <div class="rbt-header-sec-col rbt-header-left">
                        <div class="rbt-header-content">
                            <div class="rbt-separator"></div>
                        </div>
                    </div>
                    <div class="rbt-header-sec-col rbt-header-right">
                        <div class="rbt-header-content">
                            <div class="rbt-separator"></div>
                            <div class="header-info">
                                <div class="header-right-btn d-flex">
                                    @if (Auth::check())
                                        @if (Auth::user()->mutualiste)
                                            <a class="rbt-btn rbt-switch-btn btn-xs mx-2"
                                                style="background-color:green;" href="{{ route('espace.accueil') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                                                </svg>
                                                <span
                                                    data-text="{{ auth()->user()->mutualiste->nom }} {{ auth()->user()->mutualiste->prenom }}"
                                                    style="color: white">{{ auth()->user()->mutualiste->nom }}
                                                    {{ auth()->user()->mutualiste->prenom }}</span>
                                            </a>
                                        @else
                                            <a class="rbt-btn rbt-switch-btn btn-xs" style="background-color:green;"
                                                href="{{ route('connexion') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                                                </svg>
                                                <span data-text="Se connecter">Se connecter</span>
                                            </a>
                                        @endif
                                    @else
                                        <a class="rbt-btn rbt-switch-btn btn-xs" style="background-color:green;"
                                            href="{{ route('connexion') }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                                                <path
                                                    d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                                            </svg>
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
        <!-- End Header Top -->

        <div class="rbt-header-wrapper header-space-betwween bg-color-white header-sticky">
            <div class="container-fluid">
                <div class="mainbar-row rbt-navigation-start align-items-center">
                    <div class="header-left">
                        {{-- logo --}}
                        <div class="logo">
                            <a href="{{ route('accueil') }}">
                                @if ($parametre)
                                    <img src="{{ asset($parametre->lien_logo) }}" height="90" width="90"
                                        alt="logo FPM">
                                @else
                                    <img src="{{ asset('assets/home/images/logo/logo.png') }}"
                                        alt="Education Logo Images">
                                @endif
                            </a>
                        </div>
                    </div>
                    <div class="rbt-main-navigation d-none d-xl-block">
                        <nav class="mainmenu-nav">
                            <ul class="mainmenu">
                                <li class="with-megamenu has-menu-child-item position-static">
                                    <a href="{{ route('accueil') }}">
                                        Accueil
                                    </a>
                                </li>
                                <li class="with-megamenu has-menu-child-item">
                                    <a href="{{ route('presentation') }}">
                                        Présentation
                                    </a>
                                </li>
                                <li class="has-dropdown has-menu-child-item">
                                    <a href="{{ route('actualites') }}">
                                        Actualités
                                    </a>
                                </li>
                                <li class="with-megamenu has-menu-child-item position-static">
                                    <a href="{{ route('projet') }}">
                                        Projets
                                    </a>
                                </li>
                                <li class="with-megamenu has-menu-child-item position-static">
                                    <a href="{{ route('contact') }}">
                                        Nous Contacter
                                    </a>
                                </li>

                            </ul>


                        </nav>
                    </div>
                    <div class="header-right">
                        <div class="mobile-menu-bar d-block d-xl-none">
                            <div class="hamberger">
                                <button class="hamberger-button rbt-round-btn">
                                    <i class="feather-menu"></i>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile navbar -->
    <div class="popup-mobile-menu">
        <div class="inner-wrapper">
            <div class="inner-top">
                <div class="content">
                    <div class="logo">
                        <a href="route('accueil')">
                            @if ($parametre)
                                <img src="{{ asset($parametre->lien_logo) }}" height="90" width="90"
                                    alt="logo FPM">
                            @else
                                <img src="{{ asset('assets/home/images/logo/logo.png') }}" alt="Education Logo Images">
                            @endif
                            {{-- <img src="{{ asset($parametre->lien_logo) }}" alt="logo fpm"> --}}
                        </a>
                    </div>
                    <div class="rbt-btn-close">
                        <button class="close-button rbt-round-btn"><i class="feather-x"></i></button>
                    </div>
                </div>
                <p class="description">Mutualplay la plateforme mutuelle millitaire</p>

            </div>

            <nav class="mainmenu-nav">
                <ul class="mainmenu">
                    <!-- Start Single Demo  -->
                    <div class="col-lg-12 col-xl-2 col-xxl-2 col-md-12 col-sm-12 col-12 single-mega-item">
                        <div class="demo-single">
                            <div class="inner">

                                <div class="content">
                                    <h4 class="title"><a href="{{ route('accueil') }}">Accueil <span
                                                class="btn-icon"><i class="feather-arrow-right"></i></span></a></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Demo  -->
                    <!-- Start Single Demo  -->
                    <div class="col-lg-12 col-xl-2 col-xxl-2 col-md-12 col-sm-12 col-12 single-mega-item">
                        <div class="demo-single">
                            <div class="inner">

                                <div class="content">
                                    <h4 class="title"><a href="{{ route('presentation') }}">Présentation <span
                                                class="btn-icon"><i class="feather-arrow-right"></i></span></a></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Demo  -->
                    <!-- Start Single Demo  -->
                    <div class="col-lg-12 col-xl-2 col-xxl-2 col-md-12 col-sm-12 col-12 single-mega-item">
                        <div class="demo-single">
                            <div class="inner">

                                <div class="content">
                                    <h4 class="title"> <a href="{{ route('actualites') }}">Actualités
                                            <span class="btn-icon"><i class="feather-arrow-right"></i></span></a></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Demo  -->
                    <!-- Start Single Demo  -->
                    <div class="col-lg-12 col-xl-2 col-xxl-2 col-md-12 col-sm-12 col-12 single-mega-item">
                        <div class="demo-single">
                            <div class="inner">

                                <div class="content">
                                    <h4 class="title"> <a href="{{ route('projet') }}">Projets<span
                                                class="btn-icon"><i class="feather-arrow-right"></i></span></a></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Start Single Demo  -->
                    <div class="col-lg-12 col-xl-2 col-xxl-2 col-md-12 col-sm-12 col-12 single-mega-item">
                        <div class="demo-single">
                            <div class="inner">

                                <div class="content">
                                    <h4 class="title"> <a href="{{ route('contact') }}">Nous Contacter<span
                                                class="btn-icon"><i class="feather-arrow-right"></i></span></a></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Single Demo  -->
                </ul>
            </nav>

            <div class="mobile-menu-bottom">
                <div class="rbt-btn-wrapper mb--20">
                    @if (Auth::check())
                        @if (Auth::user()->mutualiste)
                            <a class="rbt-btn rbt-switch-btn btn-xs mx-2" style="background-color:green;"
                                href="{{ route('espace.accueil') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                                    <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                                </svg>
                                <span
                                    data-text="{{ auth()->user()->mutualiste->nom }} {{ auth()->user()->mutualiste->prenom }}"
                                    style="color: white">{{ auth()->user()->mutualiste->nom }}
                                    {{ auth()->user()->mutualiste->prenom }}</span>
                            </a> <br>
                            @else
                                <a class="rbt-btn btn-border-gradient radius-round btn-sm hover-transform-none w-100 justify-content-center text-center"
                                    href="{{ route('connexion') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                                    </svg>
                                    <span data-text="Se connecter">Se connecter</span>
                                </a>
                        @endif
                    @else
                        <a class="rbt-btn rbt-switch-btn btn-gradient btn-xs" href="{{ route('connexion') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                            </svg>
                            <span data-text="Se connecter">Se connecter</span>
                        </a>
                    @endif
                </div>

                {{-- <div class="social-share-wrapper">
                    <span class="rbt-short-title d-block">Suivez-nous </span>
                    <ul class="social-icon social-default transparent-with-border justify-content-start mt--20">
                        <li><a href="#">
                                <i class="feather-facebook"></i>
                            </a>
                        </li>
                        <li><a href="#">
                                <i class="feather-twitter"></i>
                            </a>
                        </li>
                        <li><a href="#">
                                <i class="feather-instagram"></i>
                            </a>
                        </li>
                        <li><a href="">
                                <i class="feather-linkedin"></i>
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="feather-youtube"></i>
                            </a>
                        </li>
                    </ul>
                </div> --}}
            </div>

        </div>
    </div>

    {{-- fin mobile navbar --}}
