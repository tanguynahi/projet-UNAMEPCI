<!DOCTYPE html>
@php
    $mode_theme = 'light';
@endphp
<html lang="fr" data-theme="{{ $mode_theme }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="FPM-MUTUALPAY TABLEAU DE BORD">
    <meta name="keyword" content="FPM, MUTUALPAY">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('assets/dashboard/img/favicon.ico') }}" type="image/x-icon"> <!-- Favicon-->
    <title>{{ $title }}</title>
    <!-- Application vendor css url -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard/cssbundle/daterangepicker.min.css') }}">
    <!-- project css file  -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard/css/luno-style.css') }}">
    <!-- my style -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard/css/mystyle.css') }}">
    <!-- Jquery Core Js -->
    <script src="{{ asset('assets/dashboard/js/plugins.js') }}"></script>
    <!-- Toast notification -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script> -->

    <!-- Personnal style -->
    @stack('css')
</head>

<body class="layout-1" data-luno="theme-blue">

    <!-- start: spinner -->
    @if (!empty($spinnerMessage))
        @include('partials.spinner.spinner', ['spinnerMessage' => $spinnerMessage])
    @endif

    <!-- start: sidebar -->
    @include('partials.dashboard_partials._sidebar')

    <!-- start: body area -->
    <div class="wrapper">
        <!-- start: page header -->
        @include('partials.dashboard_partials._header')

        <!-- start: page toolbar -->
        @if ($toolbar === '_toolbar')
            @include('partials.dashboard_partials._toolbar')
        @else
            {{-- @include('partials.dashboard_partials._toolbar2') --}}
        @endif

        <!-- start: page body -->
        <!-- start: page body -->
        <div class="page-body">
            <div class="chat-app">
                @yield('content')
            </div>
        </div>

        <!-- start: page footer -->
        @include('partials.dashboard_partials._footer')
    </div>

    <!-- Jquery Page Js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <!-- Jquery Page Js -->
    <script src="{{ asset('assets/dashboard/js/theme.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />


    @include('sweetalert::alert')

    @stack('js')

    <!-- Toast script -->
    <script>
        @if (Session::has('success'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-center",
                "showDuration": "200",
                "hideDuration": "1000",
                "timeOut": "3000",
            }
            toastr.success("{{ session('success') }}");
        @endif

        @if (Session::has('error'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-center",
                "showDuration": "200",
                "hideDuration": "1000",
                "timeOut": "3000",
            }
            toastr.error("{{ session('error') }}");
        @endif

        @if (Session::has('info'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-center",
            }
            toastr.info("{{ session('info') }}");
        @endif

        @if (Session::has('warning'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-center",
            }
            toastr.warning("{{ session('warning') }}");
        @endif
    </script>
</body>

</html>
