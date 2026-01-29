@extends('layouts.home', ['title' => "resultat d'identification"])
@push('css')
    <style>
        .success-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            max-width: 500px;
        }

        .success-container h1 {
            color: #4CAF50;
            margin-bottom: 20px;
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

        .success-symbol {
            width: 80px;
            height: 80px;
            fill: #4CAF50;
            margin-bottom: 20px;
            animation: bounce 1s infinite;
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

        /* Media Queries for smaller screens */
        @media (max-width: 768px) {
            .success-container {
                padding: 15px;
                max-width: 100%;
            }

            .success-symbol {
                width: 60px;
                height: 60px;
            }

            .success-container a {
                padding: 8px 16px;
            }

            .success-container h1 {
                font-size: 24px;
            }
        }

        @media (max-width: 576px) {
            .success-symbol {
                width: 50px;
                height: 50px;
            }

            .success-container h1 {
                font-size: 20px;
            }
        }
    </style>
@endpush
@section('content')
    <div class="row justify-content-center mtb-3">
        <div class="col-12 col-sm-12 col-lg-8 col-md-12 col-mx-12">
            <!-- Start Instructor Profile  -->
            <div class="rbt-dashboard-content bg-color-white rbt-shadow-box">
                <div class="content">
                    <div class="section-title text-center">
                        <svg class="success-symbol" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                            <path
                                d="M12 0C5.371 0 0 5.371 0 12s5.371 12 12 12 12-5.371 12-12S18.629 0 12 0zm-1.27 18L5 11.27l1.41-1.41 4.32 4.32 7.88-7.88L20 8.12l-9.27 9.27z" />
                        </svg>
                        <h4 class="rbt-title-style-3">Félicitations !</h4>
                    </div>
                    <div class="section-title text-center">
                        <span class="title text-center">
                            @if ($identification->civilite == 'M.')
                                Monsieur
                            @elseif ($identification->civilite == 'Mlle')
                                Madame
                            @else
                                Mademoiselle
                            @endif
                            {{ $identification->nom ?? 'XXXXX' }} {{ $identification->prenom ?? 'XXXXX' }}
                        </span>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <p class="text-center">
                                Votre Demande d'
                                identification a été soumise avec succès. <br>

                                En attente de la validation de l'administrateur
                                </span>
                            </p>
                            <p class="text-center"> Une fois la validation de l'administrateur effectuée, vous serez notifié
                                par email sur votre
                                email :
                                <span class="text-primary mx-2">{{ $identification->email ?? '00000000' }}</span> <br>
                                pour la suite de votre identification.
                            </p>

                            <div class="col-12 mt--20 text-center">
                                <div class="rbt-form-group">
                                    <a class="rbt-btn btn-gradient" href="{{ route('accueil') }}">Accueil</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <script>
        document.getElementById('customButton').addEventListener('click', function() {
            document.getElementById('fileInput').click();
        });

        document.getElementById('fileInput').addEventListener('change', function(event) {
            if (event.target.files.length > 0) {
                alert('Fichier sélectionné : ' + event.target.files[0].name);
            }
        });
    </script>
@endsection
