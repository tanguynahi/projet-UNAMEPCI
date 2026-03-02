@extends('layouts.home_dashboard', ['title' => 'Resultat Paiement'])
@section('content')
    <div class="col-lg-9">
        <div class="rbt-dashboard-content bg-color-white rbt-shadow-box mb--60">
            <div class="content">
                <div class="section-title">
                    <h4 class="rbt-title-style-3 text-center text-primary">Resultat Paiement</h4>
                </div>
                <style>
                    .success-container {
                        background-color: #fff;
                        padding: 20px;
                        border-radius: 8px;
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                        text-align: center;
                        position: relative;
                    }

                    .success-container p {
                        color: #333;
                        margin-bottom: 20px;
                    }

                    .success-container a {
                        display: inline-block;
                        padding: 10px 20px;
                        background-color: gray;
                        color: #fff;
                        text-decoration: none;
                        border-radius: 4px;
                    }

                    .success-container a:hover {
                        background-color: #45a049;
                    }

                    .success-symbol,
                    .failure-symbol {
                        width: 200px;
                        height: 200px;
                        margin-bottom: 20px;
                        animation: bounce 1s infinite;
                    }

                    .success-symbol {
                        fill: #4CAF50;
                    }

                    .failure-symbol {
                        fill: rgba(229, 62, 62, 0.951);
                    }

                    @keyframes bounce {

                        0%,
                        100% {
                            transform: translateY(0);
                        }

                        50% {
                            transform: translateY(-10px);
                        }
                    }

                    @media (max-width: 768px) {
                        .success-container {
                            padding: 15px;
                        }

                        .success-symbol,
                        .failure-symbol {
                            width: 80px;
                            height: 80px;
                        }

                        .row {
                            padding: 20px;
                        }
                    }

                    @media (max-width: 576px) {
                        .success-container {
                            padding: 10px;
                        }

                        .success-symbol,
                        .failure-symbol {
                            width: 60px;
                            height: 60px;
                        }

                        .row {
                            padding: 10px;
                        }

                        h4 {
                            font-size: 1.2rem;
                        }

                        .col-md-6 {
                            width: 100%;
                        }
                    }






                    #spinner-container {
                        /* position: fixed; */
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


                <div class="row g-5 justify-content-center">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                        <div class="rbt-counterup variation-01 rbt-hover-03 rbt-border-dashed bg-primary-opacity">
                            <a href="#">
                                <div class="inner ">
                                    <input type="text" value="{{ $ind }}" id="indexCont" style="display: none;">
                                    <input type="text" value="{{ $paiementinit->code_paiement ?? $codePaiement }}"
                                        id="codeP" style="display: none;">
                                    @if ($ind < 15 && $code == 203)
                                        <div id="spinner-container" class="spinner-container">
                                            {{-- <div class="d-flex justify-content-center align-items-center"> --}}
                                            <span class="loader"></span>

                                            <h6 class=" mt-3 color-primary">{{ $mess ?? 'xxxxxx' }}</h6>
                                        </div>
                                    @else
                                        @if ($code == 200 || $code == 201 )
                                            @if ($code == 200)
                                                <svg class="success-symbol" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 24 24" width="350" height="350">
                                                    <path
                                                        d="M12 0C5.371 0 0 5.371 0 12s5.371 12 12 12 12-5.371 12-12S18.629 0 12 0zm-1.27 18L5 11.27l1.41-1.41 4.32 4.32 7.88-7.88L20 8.12l-9.27 9.27z" />
                                                </svg>
                                            @elseif ($code == 201)
                                                <svg class="failure-symbol" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 24 24">
                                                    <path
                                                        d="M12 0C5.371 0 0 5.371 0 12s5.371 12 12 12 12-5.371 12-12S18.629 0 12 0zm5.656 15.656l-1.414 1.414L12 13.414l-4.242 4.242-1.414-1.414L10.586 12 6.343 7.757l1.414-1.414L12 10.586l4.242-4.243 1.414 1.414L13.414 12l4.242 4.242z" />
                                                </svg>
                                            @endif
                                            <h6 class=" mt-3 color-primary">{{ $code ?? 'xxxxxx' }}</h6>
                                            <h6 class=" mt-3 color-primary">{{ $mess ?? 'xxxxxx' }}</h6>
                                        @endif
                                    @endif



                                </div>

                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    {{-- $ind --}}
@endsection

<script>
    let btn = document.querySelector("#init-pay");
    btn.addEventListener("click", paymentINI);

    function paymentINI() {
        setTimeout(state_pay, 10000);
    }
    let btn1 = document.querySelector("#refreshID");

    function state_pay() {
        /* appel chaque 10s */
        v = $('#monInput').val();
        console.log(v);
        if (v == 2) {
            setTimeout(state_pay, 10000);
            btn1.click();
        }
    }
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        let indexInput = document.getElementById('indexCont');
        let codePInput = document.getElementById('codeP');

        let compteur = parseInt(indexInput.value); // valeur initiale
        const codePaiement = codePInput.value;

        function appelerRoute() {
            if (compteur < 15) {
                compteur++; // on incrémente le compteur
                indexInput.value = compteur; // on met à jour l'input si nécessaire

                // Génère l'URL avec les nouvelles valeurs
                const url =
                    "{{ route('newResultatPaym', ['codePaiement' => 'CODE_PLACEHOLDER', 'ind' => 'compteur']) }}"
                    .replace('CODE_PLACEHOLDER', codePaiement)
                    .replace('compteur', compteur);

                // Redirection vers la nouvelle URL
                window.location.href = url;

                // Si tu veux faire ça en AJAX au lieu de rediriger, dis-le-moi
            }
        }

        // Appelle la fonction toutes les 50 secondes
        setInterval(appelerRoute, 50000); // 50 000 ms = 50s
    });
</script>
