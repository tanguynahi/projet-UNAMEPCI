@extends('layouts.dashboard-login',['title' => 'FPM - Page de connexion'])

@section('content')
    <div class="row g-0 justify-content-center">
        <div class="col-lg-6 d-flex justify-content-center align-items-center">
            <div class="card shadow-sm w-100 p-4 p-md-5" style="max-width: 32rem;">
                <!-- Form -->
                <form action="{{ route('connexion.administrateur') }}" method="POST" class="row g-3">
                    @csrf
                    <div class="col-12 text-center mb-5">
                        <h1>Se connecter</h1>
                        <span class="text-muted">Remplissez le formulaire de connexion.</span>
                    </div>
                    <div class="col-12">
                        <div class="mb-2">
                            <label class="form-label">Adresse e-mail</label>
                            <input type="email" class="form-control form-control-lg  @error('email') is-invalid @enderror"
                                id="email" name="email" value="{{ old('email') }}" placeholder="nom@example.com"
                                autocomplete="email" autofocus required>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-2">
                            <div class="form-label">
                                <span class="d-flex justify-content-between align-items-center"> Mot de
                                    passe
                                    @if (Route::has('password.request'))
                                        <a class="text-primary" href="{{ route('password.request') }}">Mot de passe oublié
                                            ?</a>
                                    @endif
                                </span>
                            </div>
                            <input class="form-control form-control-lg  @error('password') is-invalid @enderror" id="password"
                                type="password" name="password" id="password"  value="{{ old('password') }}" placeholder="Entrer le mot de passe" minlength="6" required>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="flexCheckDefault" name="remember"
                                {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="flexCheckDefault"> Souviens-toi de moi
                            </label>
                        </div>
                    </div>
                    <div class="col-12 text-center mt-4">
                        <button class="btn btn-lg btn-block btn-dark lift text-uppercase" type="submit" title="">Se
                            connecter</button>
                    </div>
                </form>
                <!-- End Form -->
            </div>
        </div>
    </div> <!-- End Row -->
@endsection
