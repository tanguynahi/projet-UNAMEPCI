@extends('layouts.home', ['title' => 'Contact'])
@section('content')
    <a class="close_side_menu" href="javascript:void(0);"></a>
    @if (
        $parametre->contact_1 == null &&
            $parametre->contact_2 == null &&
            $parametre->email_1 == null &&
            $parametre->email_2 == null &&
            $parametre->adresse == null &&
            $parametre->lien_google_map == null)
        <div class="alert alert-primary mt-5 mb-5" role="alert">
            <marquee behavior="" direction="">
                <h1> Aucune donnée chargée sur la page</h1>
            </marquee>
        </div>
    @else
        <div class="rbt-conatct-area bg-gradient-11 rbt-section-gap">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="section-title text-center mb--60">
                            <span class="subtitle bg-secondary-opacity">Contactez nous</span>
                            <h2 class="title">Vous pouvez nous contacter pour <br>plus d'information </h2>
                        </div>
                    </div>
                </div>
                <div class="row g-5">
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12 sal-animate" data-sal="slide-up" data-sal-delay="150"
                        data-sal-duration="800">
                        <div class="rbt-address">
                            <div class="icon">
                                <i class="feather-headphones"></i>
                            </div>
                            <div class="inner">
                                <h4 class="title">
                                    Numéro de Téléphone
                                </h4>
                                <p><a href="#">{{ $parametre->contact_1 }}</a></p>
                                <p><a href="#">{{ $parametre->contact_2 }}</a></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12 sal-animate" data-sal="slide-up" data-sal-delay="200"
                        data-sal-duration="800">
                        <div class="rbt-address">
                            <div class="icon">
                                <i class="feather-mail"></i>
                            </div>
                            <div class="inner">
                                <h4 class="title">Adresse Email</h4>
                                <p><a href="#">{{ $parametre->email_1 }}</a></p>
                                <p><a href="#">{{ $parametre->email_2 }}</a></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-6 col-12 sal-animate" data-sal="slide-up" data-sal-delay="250"
                        data-sal-duration="800">
                        <div class="rbt-address">
                            <div class="icon">
                                <i class="feather-map-pin"></i>
                            </div>
                            <div class="inner">
                                <h4 class="title">Location </h4>
                                <p>{{ $parametre->adresse }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- fin information de contact --}}
        {{-- debut du formulaire de contact --}}
        <div class="rbt-contact-address">
            <div class="container">
                <div class="row g-5">
                    <div class="col-lg-6">
                        <div class="thumbnail">
                            <img class="w-100 radius-6" src="{{ asset('assets/home/images/about/contact4.jpg') }}"
                                style="height: 600px;" alt="Contact Images">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="rbt-contact-form contact-form-style-1 max-width-auto">
                            <h3 class="title"> Veuillez remplir le formulaire</h3>
                            <form  method="POST" action="{{ route('mutualistes.contacter') }}"
                            >
                                @csrf
                                <div class="form-group">
                                    <input name="contact_name" id="contact-name" type="text">
                                    <label>Nom</label>
                                    <span class="focus-border"></span>
                                </div>
                                <div class="form-group">
                                    <input name="contact_email" type="email">
                                    <label>Email</label>
                                    <span class="focus-border"></span>
                                </div>
                                <div class="form-group">
                                    <input type="text" id="Objet" name="objet" required>
                                    <label>Objet</label>
                                    <span class="focus-border"></span>
                                </div>
                                <div class="form-group">
                                    <textarea name="contact_message" id="contact-message" required></textarea>
                                    <label>Message</label>
                                    <span class="focus-border"></span>
                                </div>
                                <div class="form-submit-group">
                                    <button type="submit" 
                                        class="rbt-btn btn-md btn-gradient hover-icon-reverse w-100">
                                        <span class="icon-reverse-wrapper">
                                            <span class="btn-text">ENVOYER</span>
                                            <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                            <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                        </span>
                                    </button>
                                    {{-- <button type="submit">
                                        Envoyer
                                    </button> --}}
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- debut du formulaire de contact --}}
        {{-- debut google map --}}
        <div class="rbt-google-map bg-color-white rbt-section-gapTop">
            <iframe class="w-100" src="{{ $parametre->lien_google_map }}" height="600" style="border:0"></iframe>
        </div>
    @endif
@endsection
