@extends('layouts.home', ['title' => "page d'inscription"])
@section('content')
    {{-- <div class="col-lg-9"> --}}
        <div class="rbt-error-area bg-gradient-11 rbt-section-gap">
            <div class="error-area">
                <div class="container">
                    <div class="row justify-content-center text-center">
                        <div class="col-10">
                            <h1 class="title">{{ $code }}</h1>
                            <br>
                            <p>
                                {!! $mess !!}

                            </p>
                            <a class="rbt-btn btn-gradient icon-hover" href="{{ route('accueil') }}">
                                <span class="btn-text">Retourner à l'accueil</span>
                                <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {{-- </div> --}}
@endsection
