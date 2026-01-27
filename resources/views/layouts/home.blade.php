<!DOCTYPE html>
<html lang="fr">
@php
    use App\Models\Parametre;
    $parametre = Parametre::whereId(1)->first();
    // dd($parametre);
@endphp
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ $title }}</title>
    <meta name="robots" content="FPM, MUTUALPAY" />
    <meta name="keyword" content="FPM, MUTUALPAY">
    <meta name="description" content="FPM-MUTUALPAY PAGE VITRINE">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon -->
    @if ($parametre)
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset($parametre->lien_logo) }}">
    @else
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/home/images/favicon.png') }}">
    @endif
    <!-- CSS   ============================================ -->
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
    @stack('css')
</head>

@include('partials.home_partials.header')
@yield('content')
@stack('js')
@include('sweetalert::alert')
@include('partials.home_partials.footer')
