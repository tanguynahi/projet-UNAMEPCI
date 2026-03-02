@extends('layouts.home_dashboard', ['title' => 'Proprietes Acquis'])
@push('css')
    <style>
        .rotate {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }




        #spinner-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            /* fond sombre transparent */
            z-index: 9999;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .loader {
            border: 8px solid #f3f3f3;
            border-top: 8px solid #3498db;
            /* couleur du spinner */
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
@endpush
@section('content')
    <div class="col-lg-9">
        <div class="rbt-dashboard-content bg-color-white rbt-shadow-box">
            <div class="content">
                <div id="spinner-container" class="spinner-container" style="{{ session('mess') ? '' : 'display: none' }}">
                    <span class="loader"></span>
                    <h6 class="mt-3 color-primary">{{ session('mess') ?? 'xxxxxx' }}</h6>
                </div>
                <input type="text" name="codeP" id="codeP" value="{{ session('codeP') }}" style="display: none">


                <div class="section-title">
                    <h4 class="rbt-title-style-3"> PAIEMENT </h4>
                </div>
                <div class="row g-5">
                    <p class="text-center"><i><u>Rassurez-vous que les informations ci-dessous sont corrects</u></i></p>
                    <div class="col-lg-6 col-md-6 col-6 ">
                        <h5 class="">Informations Personnel
                        </h5>
                        <span>Nom et Prénoms : <strong> <i>{{ $mutualiste->nom ?? 'xxxxxx' }}
                                    {{ $mutualiste->prenom ?? 'xxxxxx' }} </i></strong></span> <br>
                        <span>Email : <strong> <i>{{ $mutualiste->email ?? 'xxxxxx' }} </i></strong></span> <br>
                        <span>Contact : <strong> <i>{{ $mutualiste->contact ?? 'xxxxxx' }} </i></strong></span> <br>

                    </div>
                    <div class="col-lg-6 col-md-6 col-6 ">
                        <h5 class="">Panier ( Facturations)
                        </h5>
                        <span>Libelle : <strong> <i>{{ $libelle ?? 'xxxxxx' }}</i></strong></span> <br>
                        <span>Sous total : <strong> <i>
                                    {{ formatMontant($montant ?? 0) }}
                                </i></strong></span> <br>
                        <span>Total (F CFA) : <strong> <i> {{ formatMontant($montant ?? 0) }} </i></strong></span> <br>
                        {{-- <p>Rassurez-vous que les informations ci-dessous sont corrects</p> --}}
                    </div>
                    <div class="col-lg-12 col-md-12 col-12 ">

                        <h5 class="text-center">Moyen de Paiement
                        </h5>


                        <h6 class="text-center">Tresor Money</h6>
                        <center>
                            <img id="loadingImage" src="{{ asset('assets/tremo.png') }}" alt="Logo Tremo">
                        </center>
                    </div>
                    <form action="{{ route('pasHubTrait', $id) }}" onsubmit="startLoading()" method="post">
                        @csrf
                        @method('POST')
                        <div class="row">

                            @if (!empty($facturationID))
                                <input type="text" name="facturationID" value="{{ $facturationID }}"
                                    style="display: none">
                            @endif
                            <input type="text" name="idcorrespondant" value="{{ $idCorrespondant }}"
                                style="display: none">
                            <p class="text-center"><i><u>Veuillez entrer le numéro Tresor Money à débiter. </u></i></p>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="rbt-form-group">
                                    <label for="firstname">Numero TresorMoney</label>
                                    <input id="numero" name="numero" type="tel" value="{{ old('numero') }}"
                                        class="@error('numero') is-invalid @enderror" required placeholder="9999999999"
                                        onKeyPress="if(this.value.length==10) return false;">
                                    @error('numero')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            @if ($id == 2)
                                <div class="col-lg-12 col-md-12 col-sm-12 col-12"
                                    @if ($id != 2) style="display: none" @endif>
                                    @if (ValeurNatureCotisationMu($idCorrespondant) == 'Annuelle')
                                        <div class="rbt-form-group">
                                            <label for="firstname">Montant </label>
                                            <input id="montant" name="montant" type="text"
                                                value="{{ old('montant', $montant) }}"
                                                class="@error('montant') is-invalid @enderror" required>
                                            @error('montant')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    @else
                                        <div class="rbt-form-group">
                                            <label for="firstname">Montant </label>
                                            <strong> <i>
                                                    {{ formatMontant($montant ?? 0) }}
                                                </i></strong>

                                            <input type="text" name="montant" value="{{ $montant }}"
                                                style="display: none">
                                        </div>
                                    @endif
                                </div>
                            @else
                                <input type="text" name="montant" value="{{ $montant }}" style="display: none">
                            @endif
                            <div class="col-12 justify-content-center text-center mt-3">
                                <button type="submit" class="text-center rbt-btn btn-gradient">PAIEMENT</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        function startLoading() {
            document.getElementById('loadingImage').classList.add('rotate');
        }
    </script>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('codeP'))
                // Récupère la valeur depuis la session
                const codeP = "{{ session('codeP') }}";

                // Génère l'URL avec un placeholder
                const url = "{{ route('newResultatPaym', ['codePaiement' => 'CODE_PLACEHOLDER', 'ind' => 1]) }}"
                    .replace('CODE_PLACEHOLDER', codeP);

                // Exécute après 50 secondes (50 000 ms)
                setTimeout(function() {
                    // Appel GET (redirection ou AJAX selon besoin)
                    window.location.href = url;
                }, 50000); // 50 000 ms = 50s
            @endif
        });
    </script>
@endpush
