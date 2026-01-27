<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @php
        use App\Models\Parametre;
        $parametre = Parametre::whereId(1)->first();
    @endphp
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="FPM-MUTUALPAY TABLEAU DE BORD">
    <meta name="keyword" content="FPM, MUTUALPAY">
    <link rel="icon" href="{{ asset($parametre->lien_logo) }}" type="image/x-icon">
    <title>{{ $title }}</title>
    <!-- Application vendor css url -->
    <!-- project css file  -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard/css/luno-style.css') }}">
    <!-- fontawesome 4.7.0 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Jquery Core Js -->
    <script src="{{ asset('assets/dashboard/js/plugins.js') }}"></script>
</head>

<body id="layout-1" data-luno="theme-blue">
    <!-- start: body area -->
    <div class="wrapper">
        <!-- Sign In version 1 -->
        <!-- start: page body -->
        <div class="page-body auth px-xl-4 px-sm-2 px-0 py-lg-2 py-1">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>
        <script src="https://unpkg.com/bootstrap-show-password@1.2.1/dist/bootstrap-show-password.min.js"></script>
        <script>
            $(function() {
                $('#password').password()
            })
        </script>
    </div>

    <!-- Jquery Page Js -->
    <!-- Jquery Page Js -->
    <script src="{{ asset('assets/dashboard/js/theme.js') }}"></script>
    <!-- Plugin Js -->
    <!-- Vendor Script -->
    @include('sweetalert::alert')

    <!-- Toast script -->
    {{-- <script>
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
    </script> --}}
</body>

</html>
