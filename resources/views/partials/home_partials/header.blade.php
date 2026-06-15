    <!-- Start Header Area -->
    {{-- <header class="rbt-header rbt-header-4">
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

                        <div class="logo">
                            <a href="{{ route('accueil') }}">
                                @if ($parametre)
                                    <img src="{{ asset($parametre->lien_logo) }}" height="90" width="90"
                                        alt="logo UNAMEPCI">
                                @else
                                    <img src="{{ asset('assets/home/images/logo/logo.png') }}"
                                        alt="logo UNAMEPCI">
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
                                    alt="logo UNAMEPCI">
                            @else
                                <img src="{{ asset('assets/home/images/logo/logo.png') }}" alt="logo UNAMEPCI">
                            @endif
                        </a>
                    </div>
                    <div class="rbt-btn-close">
                        <button class="close-button rbt-round-btn"><i class="feather-x"></i></button>
                    </div>
                </div>
                <p class="description">Union Nationale des Médecins Privés de Côte d’Ivoire</p>

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
            </div>

        </div>
    </div> --}}




    <header class="rbt-header rbt-header-4">
        <div class="rbt-sticky-placeholder"></div>
        <style>
            .product-image img {
                width: 100%;
                height: 500%;
                object-fit: cover;
            }
        </style>
        <div class="rbt-header-top rbt-header-top-1 variation-height-50 header-space-betwween bg-color-white border-top-bar-primary-color rbt-border-bottom d-none d-xl-block"
            style="height: 110px; margin-top:-10px; padding:10px; position:center">
            <div class="container-fluid">
                <div class="rbt-header-sec align-items-center">

                    <div class="header-left mb-5 " style="margin-left: 80px;">
                        {{-- <div class="logo">
                            <a href="{{ route('accueil') }}" class="product-image">
                                @if ($parametre)
                                    <img src="{{ asset($parametre->lien_logo ?? 'assets/logo.jpg') }}"
                                        alt="logo MEA-CI">
                                @else
                                    <img src="{{ asset('assets/logo.jpg') }}" alt=" Logo Images">
                                @endif
                                <br>
                            </a>
                        </div> --}}
                        <div class="logo">
                            <a href="{{ route('accueil') }}">
                                @if ($parametre)
                                    <img src="{{ asset($parametre->lien_logo) }}" height="90" width="90"
                                        alt="logo UNAMEPCI">
                                @else
                                    <img src="{{ asset('assets/home/images/logo/logo.png') }}" alt="logo UNAMEPCI">
                                @endif
                            </a>
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
                                                href="{{ route('dashboard') }}">
                                                {{-- <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                                                </svg>
                                                <span data-text="Se connecter">Se connecter</span> --}}


                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                                                    <path
                                                        d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                                                </svg>
                                                <span
                                                    data-text="{{ auth()->user()->administrateur->nom }} {{ auth()->user()->administrateur->prenom }}"
                                                    style="color: white">{{ auth()->user()->administrateur->nom }}
                                                    {{ auth()->user()->administrateur->prenom }}</span>
                                            </a>
                                            {{-- <a class="rbt-btn rbt-switch-btn btn-xs mx-2"
                                                style="background-color:green;" href="{{ route('inscriptionPage') }}">
                                                <svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="20"
                                                    height="20" viewBox="0 0 512.000000 512.000000"
                                                    preserveAspectRatio="xMidYMid meet" fill="currentColor"
                                                    class="text-while">

                                                    <g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)"
                                                        fill="white" stroke="none">
                                                        <path d="M648 4955 c-202 -50 -361 -212 -404 -414 -21 -99 -20 -2314 1 -2414
                                                31 -148 138 -290 274 -361 120 -63 150 -66 667 -66 l460 0 -54 -161 c-46 -134
                                                -57 -159 -71 -153 -36 16 -99 16 -132 -1 -18 -10 -83 -55 -145 -100 -91 -68
                                                -114 -90 -124 -120 -24 -72 -11 -103 119 -283 349 -486 499 -687 524 -706 20
                                                -15 43 -21 80 -21 48 0 60 5 151 69 164 114 196 150 196 219 l0 34 123 43
                                                c170 59 352 139 482 212 167 93 312 193 345 239 61 85 61 199 1 290 -27 39
                                                -28 42 -14 82 25 71 6 135 -61 216 -31 36 -36 50 -36 92 l0 49 683 0 c760 0
                                                763 1 888 66 142 75 253 228 278 384 15 89 15 2281 1 2369 -31 188 -169 353
                                                -353 420 l-72 26 -1875 2 c-1566 1 -1884 -1 -1932 -12z m3845 -163 c115 -47
                                                214 -168 237 -291 14 -73 14 -2259 0 -2332 -23 -123 -122 -244 -237 -291 l-58
                                                -23 -740 -3 c-453 -2 -736 1 -730 7 6 5 97 70 204 145 l194 136 233 0 c241 0
                                                270 4 312 43 53 49 62 84 62 247 0 194 -16 237 -102 281 -34 18 -63 19 -385
                                                19 -398 0 -408 -2 -460 -81 -25 -38 -28 -53 -31 -138 l-4 -96 -360 -258 c-198
                                                -142 -362 -256 -364 -254 -3 2 7 46 21 98 28 105 32 193 11 243 -19 47 -73
                                                104 -120 129 -57 30 -165 30 -221 0 -91 -48 -112 -88 -214 -396 l-43 -127
                                                -482 0 c-523 0 -550 2 -642 56 -63 37 -139 125 -166 192 l-23 57 0 1180 0
                                                1180 23 57 c27 67 105 157 166 193 97 57 -9 54 2006 52 l1855 -2 58 -23z
                                                m-1223 -2432 c29 -29 26 -71 -7 -101 -16 -14 -199 -146 -408 -294 -470 -332
                                                -465 -328 -465 -368 0 -39 35 -77 70 -77 18 0 79 38 190 117 l163 116 34 -36
                                                c57 -62 52 -70 -119 -192 -194 -137 -208 -150 -208 -191 0 -42 42 -78 81 -70
                                                15 3 89 49 165 104 77 54 145 102 152 106 16 10 39 -8 58 -47 14 -25 14 -31 0
                                                -51 -8 -13 -92 -78 -186 -145 -104 -75 -175 -132 -180 -147 -11 -27 0 -63 24
                                                -84 36 -29 69 -16 209 84 75 53 144 99 152 102 26 10 49 -59 34 -103 -9 -29
                                                -30 -47 -123 -108 -200 -132 -425 -240 -664 -320 -96 -31 -116 -35 -127 -24
                                                -24 27 -455 629 -455 636 0 23 313 922 328 942 52 71 172 34 172 -52 0 -19
                                                -22 -122 -50 -228 -27 -107 -50 -206 -50 -220 0 -39 31 -72 68 -72 37 0 -13
                                                -34 587 397 347 250 488 345 508 346 15 0 36 -9 47 -20z" />
                                                        <path d="M1310 4495 c-305 -66 -549 -301 -631 -610 -28 -107 -31 -279 -5 -385
                                                77 -314 316 -552 631 -626 101 -24 277 -22 380 5 278 73 491 273 587 551 31
                                                91 32 102 33 245 0 127 -4 162 -23 228 -43 150 -119 273 -234 383 -113 107
                                                -247 178 -397 210 -83 17 -256 17 -341 -1z m388 -168 c410 -142 586 -619 366
                                                -994 l-26 -45 -20 33 c-38 63 -93 122 -152 166 l-60 44 3 187 c3 230 -8 273
                                                -98 363 -76 76 -145 102 -256 97 -130 -5 -223 -64 -286 -180 l-34 -63 -3 -165
                                                c-3 -108 0 -178 8 -205 11 -37 11 -40 -11 -55 -44 -28 -127 -111 -161 -161
                                                -18 -26 -37 -47 -43 -45 -18 5 -83 146 -101 219 -87 355 132 721 488 817 105
                                                29 283 23 386 -13z m-38 -610 c0 -119 -1 -125 -29 -169 -81 -127 -268 -112
                                                -332 27 -15 31 -19 65 -19 153 l0 112 190 0 190 0 0 -123z" />
                                                        <path d="M2642 4397 c-50 -19 -102 -65 -123 -111 -27 -60 -27 -312 0 -372 22
                                                -49 78 -97 130 -113 51 -15 1609 -16 1664 0 47 13 102 60 130 113 20 37 22 56
                                                22 186 0 131 -2 149 -22 187 -27 51 -82 96 -132 112 -55 16 -1623 14 -1669 -2z
                                                m1649 -156 c23 -18 24 -25 24 -141 0 -116 -1 -123 -24 -141 -22 -18 -50 -19
                                                -812 -19 -776 0 -789 0 -809 20 -18 18 -20 33 -20 140 0 107 2 122 20 140 20
                                                20 33 20 809 20 762 0 790 -1 812 -19z" />
                                                        <path d="M2661 3570 c-51 -12 -112 -60 -138 -109 -21 -40 -23 -56 -23 -193 0
                                                -163 8 -195 67 -250 63 -60 27 -58 916 -58 l813 0 51 25 c97 49 128 132 121
                                                319 -6 146 -33 201 -123 249 -40 22 -42 22 -845 24 -443 1 -820 -2 -839 -7z
                                                m1614 -152 c35 -16 48 -67 44 -175 -3 -72 -8 -95 -23 -112 l-19 -21 -791 0
                                                c-700 0 -794 2 -814 16 -21 14 -22 22 -22 139 0 122 0 124 26 144 26 21 33 21
                                                800 21 567 0 780 -3 799 -12z" />
                                                    </g>
                                                </svg>
                                                <span data-text="S'inscrire">S'inscrire</span>
                                            </a> --}}
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
                                        <a class="rbt-btn rbt-switch-btn btn-xs mx-2" style="background-color:green;"
                                            href="{{ route('inscriptionPage') }}">
                                            <svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="20"
                                                height="20" viewBox="0 0 512.000000 512.000000"
                                                preserveAspectRatio="xMidYMid meet" class="text-while">

                                                <g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)"
                                                    fill="white" stroke="none">
                                                    <path d="M648 4955 c-202 -50 -361 -212 -404 -414 -21 -99 -20 -2314 1 -2414
                                                31 -148 138 -290 274 -361 120 -63 150 -66 667 -66 l460 0 -54 -161 c-46 -134
                                                -57 -159 -71 -153 -36 16 -99 16 -132 -1 -18 -10 -83 -55 -145 -100 -91 -68
                                                -114 -90 -124 -120 -24 -72 -11 -103 119 -283 349 -486 499 -687 524 -706 20
                                                -15 43 -21 80 -21 48 0 60 5 151 69 164 114 196 150 196 219 l0 34 123 43
                                                c170 59 352 139 482 212 167 93 312 193 345 239 61 85 61 199 1 290 -27 39
                                                -28 42 -14 82 25 71 6 135 -61 216 -31 36 -36 50 -36 92 l0 49 683 0 c760 0
                                                763 1 888 66 142 75 253 228 278 384 15 89 15 2281 1 2369 -31 188 -169 353
                                                -353 420 l-72 26 -1875 2 c-1566 1 -1884 -1 -1932 -12z m3845 -163 c115 -47
                                                214 -168 237 -291 14 -73 14 -2259 0 -2332 -23 -123 -122 -244 -237 -291 l-58
                                                -23 -740 -3 c-453 -2 -736 1 -730 7 6 5 97 70 204 145 l194 136 233 0 c241 0
                                                270 4 312 43 53 49 62 84 62 247 0 194 -16 237 -102 281 -34 18 -63 19 -385
                                                19 -398 0 -408 -2 -460 -81 -25 -38 -28 -53 -31 -138 l-4 -96 -360 -258 c-198
                                                -142 -362 -256 -364 -254 -3 2 7 46 21 98 28 105 32 193 11 243 -19 47 -73
                                                104 -120 129 -57 30 -165 30 -221 0 -91 -48 -112 -88 -214 -396 l-43 -127
                                                -482 0 c-523 0 -550 2 -642 56 -63 37 -139 125 -166 192 l-23 57 0 1180 0
                                                1180 23 57 c27 67 105 157 166 193 97 57 -9 54 2006 52 l1855 -2 58 -23z
                                                m-1223 -2432 c29 -29 26 -71 -7 -101 -16 -14 -199 -146 -408 -294 -470 -332
                                                -465 -328 -465 -368 0 -39 35 -77 70 -77 18 0 79 38 190 117 l163 116 34 -36
                                                c57 -62 52 -70 -119 -192 -194 -137 -208 -150 -208 -191 0 -42 42 -78 81 -70
                                                15 3 89 49 165 104 77 54 145 102 152 106 16 10 39 -8 58 -47 14 -25 14 -31 0
                                                -51 -8 -13 -92 -78 -186 -145 -104 -75 -175 -132 -180 -147 -11 -27 0 -63 24
                                                -84 36 -29 69 -16 209 84 75 53 144 99 152 102 26 10 49 -59 34 -103 -9 -29
                                                -30 -47 -123 -108 -200 -132 -425 -240 -664 -320 -96 -31 -116 -35 -127 -24
                                                -24 27 -455 629 -455 636 0 23 313 922 328 942 52 71 172 34 172 -52 0 -19
                                                -22 -122 -50 -228 -27 -107 -50 -206 -50 -220 0 -39 31 -72 68 -72 37 0 -13
                                                -34 587 397 347 250 488 345 508 346 15 0 36 -9 47 -20z" />
                                                    <path d="M1310 4495 c-305 -66 -549 -301 -631 -610 -28 -107 -31 -279 -5 -385
                                                77 -314 316 -552 631 -626 101 -24 277 -22 380 5 278 73 491 273 587 551 31
                                                91 32 102 33 245 0 127 -4 162 -23 228 -43 150 -119 273 -234 383 -113 107
                                                -247 178 -397 210 -83 17 -256 17 -341 -1z m388 -168 c410 -142 586 -619 366
                                                -994 l-26 -45 -20 33 c-38 63 -93 122 -152 166 l-60 44 3 187 c3 230 -8 273
                                                -98 363 -76 76 -145 102 -256 97 -130 -5 -223 -64 -286 -180 l-34 -63 -3 -165
                                                c-3 -108 0 -178 8 -205 11 -37 11 -40 -11 -55 -44 -28 -127 -111 -161 -161
                                                -18 -26 -37 -47 -43 -45 -18 5 -83 146 -101 219 -87 355 132 721 488 817 105
                                                29 283 23 386 -13z m-38 -610 c0 -119 -1 -125 -29 -169 -81 -127 -268 -112
                                                -332 27 -15 31 -19 65 -19 153 l0 112 190 0 190 0 0 -123z" />
                                                    <path d="M2642 4397 c-50 -19 -102 -65 -123 -111 -27 -60 -27 -312 0 -372 22
                                                -49 78 -97 130 -113 51 -15 1609 -16 1664 0 47 13 102 60 130 113 20 37 22 56
                                                22 186 0 131 -2 149 -22 187 -27 51 -82 96 -132 112 -55 16 -1623 14 -1669 -2z
                                                m1649 -156 c23 -18 24 -25 24 -141 0 -116 -1 -123 -24 -141 -22 -18 -50 -19
                                                -812 -19 -776 0 -789 0 -809 20 -18 18 -20 33 -20 140 0 107 2 122 20 140 20
                                                20 33 20 809 20 762 0 790 -1 812 -19z" />
                                                    <path d="M2661 3570 c-51 -12 -112 -60 -138 -109 -21 -40 -23 -56 -23 -193 0
                                                -163 8 -195 67 -250 63 -60 27 -58 916 -58 l813 0 51 25 c97 49 128 132 121
                                                319 -6 146 -33 201 -123 249 -40 22 -42 22 -845 24 -443 1 -820 -2 -839 -7z
                                                m1614 -152 c35 -16 48 -67 44 -175 -3 -72 -8 -95 -23 -112 l-19 -21 -791 0
                                                c-700 0 -794 2 -814 16 -21 14 -22 22 -22 139 0 122 0 124 26 144 26 21 33 21
                                                800 21 567 0 780 -3 799 -12z" />
                                                </g>
                                            </svg>

                                            <span data-text="S'inscrire">S'inscrire</span>
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
        {{-- style="background-color: #1043b0;" --}}
        <div class="rbt-header-wrapper header-space-betwween bg-color-white header-sticky">
            <div class="container-fluid">
                <div class="mainbar-row rbt-navigation-start align-items-center">

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
                                <img src="{{ asset($parametre->lien_logo ?? 'assets/logo.jpg') }}" alt="logo ">
                            @else
                                <img src="{{ asset('assets/logo.jpg') }}" alt=" Logo Images">
                            @endif
                        </a>
                    </div>
                    <div class="rbt-btn-close">
                        <button class="close-button rbt-round-btn"><i class="feather-x"></i></button>
                    </div>
                </div>
                <p class="description">Union Nationale des Médecins Privés de Côte d’Ivoire</p>
            </div>

            <nav class="mainmenu-nav">
                <ul class="mainmenu">
                    <!-- Start Single Demo  -->
                    <div class="col-lg-12 col-xl-2 col-xxl-2 col-md-12 col-sm-12 col-12 single-mega-item">
                        <div class="demo-single">
                            <div class="inner">

                                <div class="content">
                                    <h4 class="title"><a href="{{ route('accueil') }}"
                                            @if (request()->routeIs('accueil')) class="active" style="color:blue;" @endif
                                            style="color:black;">Accueil <span class="btn-icon"><i
                                                    class="feather-arrow-right"></i></span></a></h4>
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
                                    <h4 class="title"><a href="{{ route('presentation') }}"
                                            @if (request()->routeIs('presentation')) class="active" style="color:blue;" @endif
                                            style="color:black;">Présentation <span class="btn-icon">
                                                <i class="feather-arrow-right"></i></span></a></h4>
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
                                    <h4 class="title"> <a href="{{ route('actualites') }}"
                                            @if (request()->routeIs('actualites') || request()->routeIs('detail.actualites')) class="active" style="color:blue;" @endif
                                            style="color:black;">Actualités
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
                                    <h4 class="title"> <a href="{{ route('projet') }}"
                                            @if (request()->routeIs('projet') ||
                                                    request()->routeIs('detail.projet') ||
                                                    request()->routeIs('mutualiste.detailproduit')) class="active" style="color:blue;" @endif
                                            style="color:black;">Projets<span class="btn-icon"><i
                                                    class="feather-arrow-right"></i></span></a></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Start Single Demo  -->
                    <div class="col-lg-12 col-xl-2 col-xxl-2 col-md-12 col-sm-12 col-12 single-mega-item">
                        <div class="demo-single">
                            <div class="inner">

                                <div class="content">
                                    <h4 class="title"> <a href="{{ route('contact') }}"
                                            @if (request()->routeIs('contact')) class="active" style="color:blue;" @endif
                                            style="color:black;">Nous Contacter<span class="btn-icon"><i
                                                    class="feather-arrow-right"></i></span></a></h4>
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
                                href="{{ route('dashboard') }}">

                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                                    <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                                </svg>
                                <span
                                    data-text="{{ auth()->user()->administrateur->nom }} {{ auth()->user()->administrateur->prenom }}"
                                    style="color: black">{{ auth()->user()->administrateur->nom }}
                                    {{ auth()->user()->administrateur->prenom }}</span>
                            </a>
                            <br>
                            <br>

                            <a class="rbt-btn btn-border-gradient radius-round btn-sm hover-transform-none w-100 justify-content-center text-center"
                                style="background-color:green;" href="{{ route('inscriptionPage') }}">
                                <svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                    viewBox="0 0 512.000000 512.000000" preserveAspectRatio="xMidYMid meet"
                                    class="text-while">

                                    <g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)"
                                        fill="white" stroke="none">
                                        <path d="M648 4955 c-202 -50 -361 -212 -404 -414 -21 -99 -20 -2314 1 -2414
                                                31 -148 138 -290 274 -361 120 -63 150 -66 667 -66 l460 0 -54 -161 c-46 -134
                                                -57 -159 -71 -153 -36 16 -99 16 -132 -1 -18 -10 -83 -55 -145 -100 -91 -68
                                                -114 -90 -124 -120 -24 -72 -11 -103 119 -283 349 -486 499 -687 524 -706 20
                                                -15 43 -21 80 -21 48 0 60 5 151 69 164 114 196 150 196 219 l0 34 123 43
                                                c170 59 352 139 482 212 167 93 312 193 345 239 61 85 61 199 1 290 -27 39
                                                -28 42 -14 82 25 71 6 135 -61 216 -31 36 -36 50 -36 92 l0 49 683 0 c760 0
                                                763 1 888 66 142 75 253 228 278 384 15 89 15 2281 1 2369 -31 188 -169 353
                                                -353 420 l-72 26 -1875 2 c-1566 1 -1884 -1 -1932 -12z m3845 -163 c115 -47
                                                214 -168 237 -291 14 -73 14 -2259 0 -2332 -23 -123 -122 -244 -237 -291 l-58
                                                -23 -740 -3 c-453 -2 -736 1 -730 7 6 5 97 70 204 145 l194 136 233 0 c241 0
                                                270 4 312 43 53 49 62 84 62 247 0 194 -16 237 -102 281 -34 18 -63 19 -385
                                                19 -398 0 -408 -2 -460 -81 -25 -38 -28 -53 -31 -138 l-4 -96 -360 -258 c-198
                                                -142 -362 -256 -364 -254 -3 2 7 46 21 98 28 105 32 193 11 243 -19 47 -73
                                                104 -120 129 -57 30 -165 30 -221 0 -91 -48 -112 -88 -214 -396 l-43 -127
                                                -482 0 c-523 0 -550 2 -642 56 -63 37 -139 125 -166 192 l-23 57 0 1180 0
                                                1180 23 57 c27 67 105 157 166 193 97 57 -9 54 2006 52 l1855 -2 58 -23z
                                                m-1223 -2432 c29 -29 26 -71 -7 -101 -16 -14 -199 -146 -408 -294 -470 -332
                                                -465 -328 -465 -368 0 -39 35 -77 70 -77 18 0 79 38 190 117 l163 116 34 -36
                                                c57 -62 52 -70 -119 -192 -194 -137 -208 -150 -208 -191 0 -42 42 -78 81 -70
                                                15 3 89 49 165 104 77 54 145 102 152 106 16 10 39 -8 58 -47 14 -25 14 -31 0
                                                -51 -8 -13 -92 -78 -186 -145 -104 -75 -175 -132 -180 -147 -11 -27 0 -63 24
                                                -84 36 -29 69 -16 209 84 75 53 144 99 152 102 26 10 49 -59 34 -103 -9 -29
                                                -30 -47 -123 -108 -200 -132 -425 -240 -664 -320 -96 -31 -116 -35 -127 -24
                                                -24 27 -455 629 -455 636 0 23 313 922 328 942 52 71 172 34 172 -52 0 -19
                                                -22 -122 -50 -228 -27 -107 -50 -206 -50 -220 0 -39 31 -72 68 -72 37 0 -13
                                                -34 587 397 347 250 488 345 508 346 15 0 36 -9 47 -20z" />
                                        <path d="M1310 4495 c-305 -66 -549 -301 -631 -610 -28 -107 -31 -279 -5 -385
                                                77 -314 316 -552 631 -626 101 -24 277 -22 380 5 278 73 491 273 587 551 31
                                                91 32 102 33 245 0 127 -4 162 -23 228 -43 150 -119 273 -234 383 -113 107
                                                -247 178 -397 210 -83 17 -256 17 -341 -1z m388 -168 c410 -142 586 -619 366
                                                -994 l-26 -45 -20 33 c-38 63 -93 122 -152 166 l-60 44 3 187 c3 230 -8 273
                                                -98 363 -76 76 -145 102 -256 97 -130 -5 -223 -64 -286 -180 l-34 -63 -3 -165
                                                c-3 -108 0 -178 8 -205 11 -37 11 -40 -11 -55 -44 -28 -127 -111 -161 -161
                                                -18 -26 -37 -47 -43 -45 -18 5 -83 146 -101 219 -87 355 132 721 488 817 105
                                                29 283 23 386 -13z m-38 -610 c0 -119 -1 -125 -29 -169 -81 -127 -268 -112
                                                -332 27 -15 31 -19 65 -19 153 l0 112 190 0 190 0 0 -123z" />
                                        <path d="M2642 4397 c-50 -19 -102 -65 -123 -111 -27 -60 -27 -312 0 -372 22
                                                -49 78 -97 130 -113 51 -15 1609 -16 1664 0 47 13 102 60 130 113 20 37 22 56
                                                22 186 0 131 -2 149 -22 187 -27 51 -82 96 -132 112 -55 16 -1623 14 -1669 -2z
                                                m1649 -156 c23 -18 24 -25 24 -141 0 -116 -1 -123 -24 -141 -22 -18 -50 -19
                                                -812 -19 -776 0 -789 0 -809 20 -18 18 -20 33 -20 140 0 107 2 122 20 140 20
                                                20 33 20 809 20 762 0 790 -1 812 -19z" />
                                        <path d="M2661 3570 c-51 -12 -112 -60 -138 -109 -21 -40 -23 -56 -23 -193 0
                                                -163 8 -195 67 -250 63 -60 27 -58 916 -58 l813 0 51 25 c97 49 128 132 121
                                                319 -6 146 -33 201 -123 249 -40 22 -42 22 -845 24 -443 1 -820 -2 -839 -7z
                                                m1614 -152 c35 -16 48 -67 44 -175 -3 -72 -8 -95 -23 -112 l-19 -21 -791 0
                                                c-700 0 -794 2 -814 16 -21 14 -22 22 -22 139 0 122 0 124 26 144 26 21 33 21
                                                800 21 567 0 780 -3 799 -12z" />
                                    </g>
                                </svg>

                                <span data-text="S'inscrire">S'inscrire</span>
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
                        <a class="rbt-btn rbt-switch-btn btn-gradient btn-xs" style="background-color:green;"
                            href="{{ route('inscriptionPage') }}">
                            <svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                viewBox="0 0 512.000000 512.000000" preserveAspectRatio="xMidYMid meet"
                                class="text-while">

                                <g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)" fill="white"
                                    stroke="none">
                                    <path d="M648 4955 c-202 -50 -361 -212 -404 -414 -21 -99 -20 -2314 1 -2414
                                                31 -148 138 -290 274 -361 120 -63 150 -66 667 -66 l460 0 -54 -161 c-46 -134
                                                -57 -159 -71 -153 -36 16 -99 16 -132 -1 -18 -10 -83 -55 -145 -100 -91 -68
                                                -114 -90 -124 -120 -24 -72 -11 -103 119 -283 349 -486 499 -687 524 -706 20
                                                -15 43 -21 80 -21 48 0 60 5 151 69 164 114 196 150 196 219 l0 34 123 43
                                                c170 59 352 139 482 212 167 93 312 193 345 239 61 85 61 199 1 290 -27 39
                                                -28 42 -14 82 25 71 6 135 -61 216 -31 36 -36 50 -36 92 l0 49 683 0 c760 0
                                                763 1 888 66 142 75 253 228 278 384 15 89 15 2281 1 2369 -31 188 -169 353
                                                -353 420 l-72 26 -1875 2 c-1566 1 -1884 -1 -1932 -12z m3845 -163 c115 -47
                                                214 -168 237 -291 14 -73 14 -2259 0 -2332 -23 -123 -122 -244 -237 -291 l-58
                                                -23 -740 -3 c-453 -2 -736 1 -730 7 6 5 97 70 204 145 l194 136 233 0 c241 0
                                                270 4 312 43 53 49 62 84 62 247 0 194 -16 237 -102 281 -34 18 -63 19 -385
                                                19 -398 0 -408 -2 -460 -81 -25 -38 -28 -53 -31 -138 l-4 -96 -360 -258 c-198
                                                -142 -362 -256 -364 -254 -3 2 7 46 21 98 28 105 32 193 11 243 -19 47 -73
                                                104 -120 129 -57 30 -165 30 -221 0 -91 -48 -112 -88 -214 -396 l-43 -127
                                                -482 0 c-523 0 -550 2 -642 56 -63 37 -139 125 -166 192 l-23 57 0 1180 0
                                                1180 23 57 c27 67 105 157 166 193 97 57 -9 54 2006 52 l1855 -2 58 -23z
                                                m-1223 -2432 c29 -29 26 -71 -7 -101 -16 -14 -199 -146 -408 -294 -470 -332
                                                -465 -328 -465 -368 0 -39 35 -77 70 -77 18 0 79 38 190 117 l163 116 34 -36
                                                c57 -62 52 -70 -119 -192 -194 -137 -208 -150 -208 -191 0 -42 42 -78 81 -70
                                                15 3 89 49 165 104 77 54 145 102 152 106 16 10 39 -8 58 -47 14 -25 14 -31 0
                                                -51 -8 -13 -92 -78 -186 -145 -104 -75 -175 -132 -180 -147 -11 -27 0 -63 24
                                                -84 36 -29 69 -16 209 84 75 53 144 99 152 102 26 10 49 -59 34 -103 -9 -29
                                                -30 -47 -123 -108 -200 -132 -425 -240 -664 -320 -96 -31 -116 -35 -127 -24
                                                -24 27 -455 629 -455 636 0 23 313 922 328 942 52 71 172 34 172 -52 0 -19
                                                -22 -122 -50 -228 -27 -107 -50 -206 -50 -220 0 -39 31 -72 68 -72 37 0 -13
                                                -34 587 397 347 250 488 345 508 346 15 0 36 -9 47 -20z" />
                                    <path d="M1310 4495 c-305 -66 -549 -301 -631 -610 -28 -107 -31 -279 -5 -385
                                                77 -314 316 -552 631 -626 101 -24 277 -22 380 5 278 73 491 273 587 551 31
                                                91 32 102 33 245 0 127 -4 162 -23 228 -43 150 -119 273 -234 383 -113 107
                                                -247 178 -397 210 -83 17 -256 17 -341 -1z m388 -168 c410 -142 586 -619 366
                                                -994 l-26 -45 -20 33 c-38 63 -93 122 -152 166 l-60 44 3 187 c3 230 -8 273
                                                -98 363 -76 76 -145 102 -256 97 -130 -5 -223 -64 -286 -180 l-34 -63 -3 -165
                                                c-3 -108 0 -178 8 -205 11 -37 11 -40 -11 -55 -44 -28 -127 -111 -161 -161
                                                -18 -26 -37 -47 -43 -45 -18 5 -83 146 -101 219 -87 355 132 721 488 817 105
                                                29 283 23 386 -13z m-38 -610 c0 -119 -1 -125 -29 -169 -81 -127 -268 -112
                                                -332 27 -15 31 -19 65 -19 153 l0 112 190 0 190 0 0 -123z" />
                                    <path d="M2642 4397 c-50 -19 -102 -65 -123 -111 -27 -60 -27 -312 0 -372 22
                                                -49 78 -97 130 -113 51 -15 1609 -16 1664 0 47 13 102 60 130 113 20 37 22 56
                                                22 186 0 131 -2 149 -22 187 -27 51 -82 96 -132 112 -55 16 -1623 14 -1669 -2z
                                                m1649 -156 c23 -18 24 -25 24 -141 0 -116 -1 -123 -24 -141 -22 -18 -50 -19
                                                -812 -19 -776 0 -789 0 -809 20 -18 18 -20 33 -20 140 0 107 2 122 20 140 20
                                                20 33 20 809 20 762 0 790 -1 812 -19z" />
                                    <path d="M2661 3570 c-51 -12 -112 -60 -138 -109 -21 -40 -23 -56 -23 -193 0
                                                -163 8 -195 67 -250 63 -60 27 -58 916 -58 l813 0 51 25 c97 49 128 132 121
                                                319 -6 146 -33 201 -123 249 -40 22 -42 22 -845 24 -443 1 -820 -2 -839 -7z
                                                m1614 -152 c35 -16 48 -67 44 -175 -3 -72 -8 -95 -23 -112 l-19 -21 -791 0
                                                c-700 0 -794 2 -814 16 -21 14 -22 22 -22 139 0 122 0 124 26 144 26 21 33 21
                                                800 21 567 0 780 -3 799 -12z" />
                                </g>
                            </svg>

                            <span data-text="S'inscrire">S'inscrire</span>
                        </a>
                    @endif
                </div>

            </div>

        </div>
    </div>

    {{-- fin mobile navbar --}}
