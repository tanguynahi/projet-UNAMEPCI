@extends('layouts.home_login', ['title' => 'Page de Connexion'])
@section('content')
    <div class="rbt-elements-area bg-color-white rbt-section-gap"
        style="background: linear-gradient(rgba(255, 255, 255, 0.5), rgba(255, 255, 255, 0.5)), url('{{ asset('assets/home/images/millfond.jpg') }}'); height: 100%; width: 100%; background-size: cover;">
        <div class="container">
            <div class="row gy-5 row--30">
                <!-- Image section -->
                <div class="col-lg-6 d-none d-lg-block">
                    <div class="thumbnail">
                        <img class="w-100 radius-6" src="{{ asset('assets/home/images/arrierPLa.png') }}"
                            style="height: 550px; width:550px;" alt="Connexion Images">
                    </div>
                </div>
                <!-- Form section -->
                <div class="col-lg-6" style="margin-top:150px;">
                    <div class="rbt-contact-form contact-form-style-1">
                        <h3 class="title text-center">Connexion</h3>
                        <form action="{{ route('connexion.mutualiste') }}" method="POST">
                            @csrf
                            <div>
                                <label>Email *</label>
                                <input name="email" type="email" id="email" value="{{ old('email') }}"
                                    autocomplete="email" required class="@error('email') is-invalid @enderror">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror


                            </div>
                            <div>
                                <label>Mot de passe *</label>
                                <input name="password" type="password" id="password"
                                    class="@error('password') is-invalid @enderror " value="{{ old('password') }}"
                                    minlength="6" required>
                                @error('password')
                                    <strong>{{ $message }}</strong>
                                @enderror
                            </div>
                            <div class="row mb--30">
                                <div class="col-lg-6">
                                    <div class="rbt-checkbox">
                                        <input type="checkbox" id="rememberme" name="rememberme">
                                        <label for="rememberme">Souviens-toi de moi</label>
                                    </div>
                                </div>
                                <div class="col-lg-6" style="display: none;">
                                    <div class="rbt-lost-password text-end">
                                        @if (Route::has('password.request'))
                                            <a class="rbt-btn-link" href="{{ route('password.request') }}">Mot de passe
                                                Oublié ?</a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="form-submit-group">
                                <button type="submit" class="rbt-btn btn-md hover-icon-reverse w-100"
                                    style="background-color:green;">
                                    <span class="icon-reverse-wrapper">
                                        <span class="btn-text">CONNEXION</span>
                                        <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                        <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
