@extends('layouts.home_dashboard', ['title' => 'Page Projet'])
@section('content')
    <div class="col-lg-9">
        @if ($nombre = $projets->count() > null)
            <div class="rbt-rbt-blog-area bg-color-white">
                <div class="container">
                    @include('home.pages.projets.adminAfficheProjet')
                </div>
            </div>
        @else
            <div class="alert alert-primary mt-5 mb-5" role="alert">
                <marquee behavior="" direction="">
                    <h1> Aucune donnée chargée sur la page</h1>
                </marquee>
            </div>
        @endif
    </div>
@endsection
