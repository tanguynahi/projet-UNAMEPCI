@extends('layouts.home_dashboard', ['title' => 'Page d\'Erreur'])
@section('content')
    <div class="col-lg-9">
        <div class="rbt-error-area bg-gradient-11 rbt-section-gap">
            <div class="error-area">
                <div class="container">
                    <div class="row justify-content-center text-center">
                        <div class="col-10">
                            <h1 class="title" style="font-size: 2.5rem; color: #d9534f;">Oups ! Une erreur est survenue</h1>
                            <p class="error-message" style="font-size: 1.25rem; margin-bottom: 20px;">
                                Nous vous informons que, en raison de circonstances techniques, notre service est
                                momentanément indisponible. Nous nous excusons pour la gêne occasionnée et vous remercions
                                de votre compréhension.
                            </p>
                            <h2 class="code-error" style="font-size: 2rem; color: #5bc0de;">Code d'erreur :
                                <strong>{{ $code }}</strong></h2>
                            <p>
                                Nous vous recommandons de réessayer plus tard. Si le problème persiste, n’hésitez pas à nous
                                contacter pour toute assistance supplémentaire.
                            </p>
                            <p>
                                Merci de votre patience et de votre compréhension.
                            </p>
                            <a class="rbt-btn btn-gradient icon-hover" href="{{ route('accueil') }}">
                                <span class="btn-text">Retourner à l'accueil</span>
                                <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                            </a>
                            <div class="mt-4">
                                <i class="feather-alert-triangle" style="font-size: 80px; color: #d9534f;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
