<!DOCTYPE html>
@php
    use App\Models\DroitAdhesion;
    $mutualisteId = auth()->user()->mutualiste->id;
    $droit_adhesions = DroitAdhesion::where('mutualiste_id', $mutualisteId)->first();
    $status = $droit_adhesions->status;
    $contCoti = NbreCotisation();
@endphp
<html lang="fr">

<head>
    @php
        use App\Models\Parametre;
        $parametre = Parametre::whereId(1)->first();
    @endphp
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ $title }}</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset($parametre->lien_logo) }}">
    <!-- CSS
 ============================================ -->
    <link rel="stylesheet" href="{{ asset('assets/home/css/vendor/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/home/css/vendor/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/home/css/vendor/slick-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/home/css/plugins/sal.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/home/css/plugins/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/home/css/plugins/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/home/css/plugins/euclid-circulara.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/home/css/plugins/swiper.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/home/css/plugins/magnify.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/home/css/plugins/odometer.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/home/css/plugins/animation.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/home/css/plugins/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/home/css/plugins/jquery-ui.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/home/css/plugins/magnigy-popup.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/home/css/plugins/plyr.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/home/css/style.css') }}">
    {{-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.0/css/bootstrap.min.css" rel="stylesheet"> --}}
    @stack('css')
</head>

<body class="rbt-header-sticky">


    @include('partials.home_partials.header')
    <div class="rbt-dashboard-area rbt-section-gapBottom">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    @include('partials.home_dashboard_partials.header-admin')
                    <div class="row g-5">
                        @include('partials.home_dashboard_partials.sidebar')
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('sweetalert::alert')

    @stack('js')
    @include('partials.home_partials.footer')
