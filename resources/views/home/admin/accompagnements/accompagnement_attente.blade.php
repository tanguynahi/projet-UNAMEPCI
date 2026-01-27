@extends('layouts.home_dashboard', ['title' => "Page d'accompagement en attente "])
@section('content')
    <div class="col-lg-9">
        <!-- Start Instructor Profile  -->
        <div class="rbt-dashboard-content bg-color-white rbt-shadow-box">
            <div class="content">

                <div class="section-title">
                    <h4 class="rbt-title-style-3">VOTRE DEMANDE D'ACCOMPAGNEMENT </h4>
                </div>

                <div class="advance-tab-button mb--30">
                    <ul class="nav nav-tabs tab-button-style-2 justify-content-start" id="settinsTab-4" role="tablist">
                        <li role="presentation">
                            <a href="#" class="tab-button active" id="profile-tab" data-bs-toggle="tab"
                                data-bs-target="#profile" role="tab" aria-controls="profile" aria-selected="true">
                                <span class="title">En attente de la validation de l'administrateur </span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="tab-content">
                    <div class="tab-pane fade active show" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <p>
                            Nous sommes actuellement en train de traiter votre demande d'accompagenement. <br>
                            Vous recevrez une reponse dans un bref delai.</p>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="rbt-form-group">
                                <label for="firstname">Montant accompagnement * <svg xmlns="http://www.w3.org/2000/svg"
                                        width="16" height="16" fill="currentColor" class="bi bi-hourglass-split"
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M2.5 15a.5.5 0 1 1 0-1h1v-1a4.5 4.5 0 0 1 2.557-4.06c.29-.139.443-.377.443-.59v-.7c0-.213-.154-.451-.443-.59A4.5 4.5 0 0 1 3.5 3V2h-1a.5.5 0 0 1 0-1h11a.5.5 0 0 1 0 1h-1v1a4.5 4.5 0 0 1-2.557 4.06c-.29.139-.443.377-.443.59v.7c0 .213.154.451.443.59A4.5 4.5 0 0 1 12.5 13v1h1a.5.5 0 0 1 0 1zm2-13v1c0 .537.12 1.045.337 1.5h6.326c.216-.455.337-.963.337-1.5V2zm3 6.35c0 .701-.478 1.236-1.011 1.492A3.5 3.5 0 0 0 4.5 13s.866-1.299 3-1.48zm1 0v3.17c2.134.181 3 1.48 3 1.48a3.5 3.5 0 0 0-1.989-3.158C8.978 9.586 8.5 9.052 8.5 8.351z" />
                                    </svg></label>
                                    <p class="text-danger fw-bold">{{ formatMontant($demandeAccompagnement->montant_voulue) }}</p>
                            </div>
                        </div>
                        <br><br>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="rbt-form-group">
                                <label for="firstname">Numero Tresor Money *</label>
                                <p class="fw-bold">{{ formatPhoneNumber($demandeAccompagnement->contact_tresormoney) }}</p>
                            </div>
                        </div>
                        <br>
                        <div class="col-12 mt--20">
                            <div class="rbt-form-group">
                                <a class="rbt-btn btn-gradient" href="{{ route('liste.demandeaccompagnement') }}">Retour</a>
                            </div>
                        </div>
                        <!-- End Profile Row  -->
                    </div>
                </div>





            </div>
        </div>
        <!-- End Instructor Profile  -->

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
