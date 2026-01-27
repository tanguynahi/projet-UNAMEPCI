@extends('layouts.home_dashboard', ['title' => "Formulaire d'accompagnement "])
@section('content')
    @push('css')

        <style>
            /* Style pour l'animation de chargement */
            button.loading {
                opacity: 0.6;
                pointer-events: none;
                position: relative;
            }

            button.loading::after {
                content: "";
                position: absolute;
                top: 50%;
                left: 50%;
                width: 20px;
                height: 20px;
                border: 3px solid #fff;
                border-top-color: transparent;
                border-radius: 50%;
                animation: spin 1s linear infinite;
                transform: translate(-50%, -50%);
            }

            @keyframes spin {
                to {
                    transform: rotate(360deg);
                }
            }
        </style>
    @endpush
    <div class="col-lg-9">
        <!-- Start Instructor Profile  -->
        <div class="rbt-dashboard-content bg-color-white rbt-shadow-box">
            <div class="content">
                <div class="section-title">
                    <div class="row">
                        <div class="col-lg-10 col-md-10 col-sm-10">
                            <h4 class="rbt-title-style-3">Demande d'accompagnement </h4>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <a href="{{ route('liste.demandeaccompagnement') }}" class="rbt-btn btn-sm">
                                Retour
                            </a>
                        </div>
                    </div>
                </div>
                <div class="advance-tab-button mb--30">
                    <ul class="nav nav-tabs tab-button-style-2 justify-content-start" id="settinsTab-4" role="tablist">
                        <li role="presentation">
                            <a href="#" class="tab-button active" id="profile-tab" data-bs-toggle="tab"
                                data-bs-target="#profile" role="tab" aria-controls="profile" aria-selected="true">
                                <span class="title">Formulaire de demande d'accompagement </span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content">
                    <div class="tab-pane fade active show" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <form action="{{ route('demandeaccompagnements.store') }}" method="POST" id="FormID"
                            class="rbt-profile-row rbt-default-form row row-15" enctype="multipart/form-data">
                            @method('post')
                            @csrf
                            <div class="col-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <label>Service <span class="text-danger">*</span> </label>
                                <div class="rbt-modern-select bg-transparent height-45">
                                    {{-- <select class="w-100 @error('service_id') is-invalid @enderror" name="service_id"
                                        id="service_id" autocomplete="service_id" autofocus>
                                        <option value="">Sélectionnez le service</option>
                                        @foreach ($services as $service)
                                            <option value="{{ $service->id }}"
                                                data-montant_maximum="{{ $service->montant_maximum }}">
                                                {{ $service->libelle }}
                                            </option>
                                        @endforeach
                                    </select> --}}
                                    <select class="w-100 @error('service_id') is-invalid @enderror" name="service_id"
                                        id="service_id" autocomplete="service_id" autofocus>
                                        <option value="">Sélectionnez le service</option>
                                        @foreach ($services as $service)
                                            <option value="{{ $service->id }}"
                                                data-montant_maximum="{{ $service->montant_maximum }}">
                                                {{ $service->libelle }}
                                            </option>
                                        @endforeach
                                    </select>

                                    @error('service_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>
                                                {{ $message }}
                                            </strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6 col-lg-6 col-md-6 col-sm-6 col-12">
                                <label>Montant <span class="text-danger"> *</span> </label>
                                <input type="number" min="1000" max="5000000" placeholder="Ex: 1000000"
                                    id="montant_voulue" name="montant_voulue" value="{{ old('montant_voulue') }}"
                                    class="@error('montant_voulue') is-invalid @enderror" autocomplete="montant_voulue"
                                    autofocus required>
                                @error('montant_voulue')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>
                                            {{ $message }}
                                        </strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                <div class="rbt-form-group">
                                    <label for="firstname">Numero Tresor Money <span class="text-danger"> *</span> </label>
                                    <input type="text" class="@error('contact_tresormoney') is-invalid @enderror"
                                        id="contact_tresormoney" name="contact_tresormoney"
                                        value="{{ old('contact_tresormoney') }}" placeholder="Ex: 0707070707"
                                        autocomplete="contact_tresormoney" autofocus required>
                                    @error('contact_tresormoney')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>
                                                {{ $message }}
                                            </strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-5 col-lg-5 col-md-5 col-sm-5 col-12">
                                <label>Montant à Rembourser <span id="ter"></span> </label>
                                <input type="number" min="10000" max="5000000" id="montant_apayer"
                                    value="{{ old('montant_apayer') }}" name="montant_apayer"
                                    class="@error('montant_apayer') is-invalid @enderror" autocomplete="montant_apayer"
                                    autofocus readonly>
                                @error('montant_apayer')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>
                                            {{ $message }}
                                        </strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-3  col-lg-3 col-md-3 col-sm-3 col-12">
                                <label>Délai de Remboursement
                                </label>
                                <div id="terme" class="rbt-modern-select bg-transparent height-55 mt-4">
                                    <span class="text-danger mx-2 text-center">
                                        60 mois
                                    </span>
                                </div>
                            </div>
                            {{-- <div class="col-2  col-lg-2 col-md-2 col-sm-2 col-12">
                                <label> pénalité de retard </label>
                                <div id="terme" class="rbt-modern-select bg-transparent height-55 mt-4">
                                    <span class="text-danger mx-2 text-center">
                                        (10%)
                                    </span>
                                </div>
                            </div> --}}
                            <div class="col-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <label>commentaire</label>
                                <textarea class="@error('commentaire') is-invalid @enderror" name="commentaire" id="commentaire" cols="10"
                                    rows="5" placeholder="Entre votre commentaire">{{ old('commentaire') }}</textarea>
                                @error('commentaire')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>
                                            {{ $message }}
                                        </strong>
                                    </span>
                                @enderror
                                <span class="text-danger">*</span>Champs Obligatoires
                            </div>
                            <div class="col-12 mt--20">
                                <div class="rbt-form-group">
                                    <span style="margin-left:50%;">
                                        <button type="submit" class="rbt-btn btn-gradient" id="Valid">Effectuer ma
                                            demande</button>
                                    </span>
                                </div>
                            </div>
                        </form>
                        @if (session('status') && session('message'))
                            <script>
                                document.addEventListener("DOMContentLoaded", function(event) {
                                    const Toast = Swal.mixin({
                                        toast: true,
                                        position: 'top-end',
                                        showConfirmButton: false,
                                        timer: 3000,
                                        timerProgressBar: true,
                                        didOpen: (toast) => {
                                            toast.addEventListener('mouseenter', Swal.stopTimer)
                                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                                        }
                                    });
                                    Toast.fire({
                                        icon: '{{ session('status') }}' === 'success' ? 'success' : 'error',
                                        title: '{{ session('message') }}'
                                    });
                                });
                            </script>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @php
        $url1 = urlAPI() . '/recuperation-infoInteret/:serviceId/:montant'; // liste des activites
    @endphp
    <input type="text" name="lienApi" value="{{ $url1 }}" id="lienApi" style="display: none">
    <input type="text" id="intnet" placeholder="Taux d'intérêt" readonly style="display: none">
    @if (Session::has('message'))
        <script>
            Swal("Message", "{{ Session::get('message') }}", 'Success', {
                button: true,
                button: "OK",
            });
        </script>
    @endif
@endsection
@push('js')
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
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
    {{-- script pour calcule montant --}}
    {{-- <script>
        $(document).ready(function() {
            $('#montant_voulue').on('input', function() {
                var montant = $(this).val();
                montant = montant.replace(/\s/g, '');
                $(this).val(montant);
                validationMontantVoulue();
                calculerMontantAPayer();

            });
            $('#contact_tresormoney').on('keypress', function(event) {
                // Permet uniquement les chiffres
                var charCode = (event.which) ? event.which : event.keyCode;
                if (charCode < 48 || charCode > 57) {
                    event.preventDefault();
                }
            });

            $('#contact_tresormoney').on('input', function() {
                validationContactTresorMoney();
            });
            montantInitial();
        });
        // si le montant inital est valide il met le change vert ou en rouge
        function montantInitial() {
            var montantVoulue = $('#montant_voulue');
            montantVoulue.removeClass('is-invalid');
            montantVoulue.next('.invalid-feedback').remove();

            validationMontantVoulue();
        }

        function validationMontantVoulue() {
            var montantVoulue = $('#montant_voulue');
            var montant = montantVoulue.val();
            // Supprimer tous les messages précédents
            montantVoulue.removeClass('is-invalid is-valid');
            montantVoulue.next('.invalid-feedback, .valid-feedback').remove();
            // Valider que le montant contient uniquement des chiffres
            if (!/^\d+$/.test(montant)) {
                montantVoulue.addClass('is-invalid');
                montantVoulue.after('<div class="invalid-feedback">Entrer le montant.</div>');
                return;
            }
            if (montant < 1000 || montant.trim() === "") {
                montantVoulue.addClass('is-invalid');
                montantVoulue.after(
                    '<div class="invalid-feedback">Le montant doit être supérieur ou égal à 1000 FCFA.</div>');
            } else if (montant % 100 !== 0) {
                montantVoulue.addClass('is-invalid');
                montantVoulue.after('<div class="invalid-feedback">Le montant doit être un multiple de 100.</div>');
            } else {
                montantVoulue.addClass('is-valid');
                montantVoulue.after('<div class="valid-feedback">Le montant est valide.</div>');
            }
        }
        // Fonction pour valider le champ 'contact'
        function validationContactTresorMoney() {
            var contactInput = $('#contact_tresormoney');
            var contactValue = contactInput.val();
            // Retirer les espaces du champ contact
            contactValue = contactValue.replace(/\s+/g, '');
            // Mettre à jour la valeur du champ avec les espaces retirés
            contactInput.val(contactValue);
            // Supprimer les messages d'erreur et de validation existants
            contactInput.removeClass('is-invalid is-valid');
            contactInput.next('.invalid-feedback, .valid-feedback').remove();
            // Vérifier si le champ est vide
            if (contactValue.trim() === "") {
                return;
            }
            // Valider que le contact contient uniquement des chiffres
            if (!/^\d+$/.test(contactValue)) {
                contactInput.addClass('is-invalid');
                contactInput.after(
                    '<div class="invalid-feedback">Le contact ne doit contenir que des chiffres, pas d\'espaces.</div>'
                );
            } else if (contactValue.length !== 10) {
                contactInput.addClass('is-invalid');
                contactInput.after(
                    '<div class="invalid-feedback">Le contact doit contenir exactement 10 chiffres, pas d\'espaces.</div>'
                );
            } else {
                contactInput.addClass('is-valid');
                contactInput.after('<div class="valid-feedback">Le contact est valide.</div>');
            }
        }
        // montant a payer
        function calculerMontantAPayer() {
            var montantVoulueInput = $('#montant_voulue');
            var montantAPayerInput = $('#montant_apayer');
            var interets = $('#intnet');
            var okl = parseFloat(interets.val()) / 100;
            var montantVoulue = parseFloat(montantVoulueInput.val());
            // Vérifier si montant_voulue est un nombre valide
            if (isNaN(montantVoulue)) {
                montantAPayerInput.val('');
                return;
            }
            console.log(document.getElementById("intnet"));
            // Calculer le montant à payer et l'arrondir au nombre entier le plus proche
            var montantAPayer = Math.round(montantVoulue + (montantVoulue * okl));
            montantAPayerInput.val(montantAPayer);
        }
    </script> --}}
    <script>
        document.getElementById('service_id').addEventListener('change', function() {
            var selectedOption = this.options[this.selectedIndex];
            var montantMaximum = selectedOption.getAttribute('data-montant_maximum');
            var montantInput = document.getElementById('montant_voulue');
            montantInput.setAttribute('max', montantMaximum);
        });
    </script>
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const serviceSelect = document.getElementById('service_id');
            const montantInput = document.getElementById('montant_voulue');
            const termeDisplay = document.getElementById('intnet');
            const termeDisplay = document.getElementById('ter');
            montantInput.addEventListener('input', function() {
                const selectedService = serviceSelect.value;
                const montant = montantInput.value;
                console.log(montant, selectedService);
                // Check if service and montant are selected
                if (selectedService && montant) {
                    // Make AJAX request
                    fetch(`/recuperation-infoInteret`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token for Laravel
                            },
                            body: JSON.stringify({
                                service_id: selectedService,
                                montant: montant
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.interest_rate) {
                                // termeDisplay.innerHTML =
                                //     `<span class="text-danger mx-2 text-center">(${data.interest_rate}%)</span>`;

                                termeDisplay.value = `${data.interest_rate}%`;


                            } else {
                                // termeDisplay.innerHTML =
                                //     `<span class="text-danger mx-2 text-center">(No interest rate found)</span>`;
                                termeDisplay.value = "Aucun taux d'intérêt trouvé";
                                // termeDisplay.value = "Aucun taux d'intérêt trouvé";
                            }
                        })
                        .catch(error => console.error('Error fetching interest rate:', error));
                }
            });
        });
    </script> --}}
    {{-- <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        document.addEventListener('DOMContentLoaded', function() {
            const serviceSelect = document.getElementById('service_id');
            const montantInput = document.getElementById('montant_voulue');
            const termeDisplay = document.getElementById('intnet');
            montantInput.addEventListener('input', function() {
                const selectedService = serviceSelect.value;
                const montant = montantInput.value;
                // console.log(montant, selectedService);
                if (selectedService && montant) {
                    fetch("{{ route('recup.interet') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({
                                service_id: selectedService,
                                montant: montant
                            })
                        })
                        .then(response => {
                            // Afficher la réponse brute dans la console
                            console.log('Raw response:', response);

                            // Vérifier si la réponse est correcte
                            if (!response.ok) {
                                throw new Error('Network response was not ok: ' + response.statusText);
                            }
                            return response.json();
                        })
                        // .then(response => response.json())
                        .then(data => {
                            if (data.success && data.interest_rate) {
                                termeDisplay.value = `${data.interest_rate}%`;
                            } else {
                                termeDisplay.value = "Aucun taux d'intérêt trouvé";
                            }
                        })
                        .catch(error => console.error('Error fetching interest rate:', error));
                }
            });
        });
    </script> --}}
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const serviceSelect = document.getElementById('service_id');
            const montantInput = document.getElementById('montant_voulue');
            const termeDisplay = document.getElementById('intnet');

            // Récupérer le jeton CSRF
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            montantInput.addEventListener('input', function() {
                const selectedService = serviceSelect.value;
                const montant = montantInput.value;

                if (selectedService && montant) {
                    console.log(selectedService, montant);
                    fetch("{{ route('recup.interet') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({
                                service_id: selectedService,
                                montant: montant
                            })
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Erreur réseau: ' + response.statusText);
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success && data.interest_rate) {
                                termeDisplay.value = `${data.interest_rate}%`;
                            } else {
                                termeDisplay.value = "Aucun taux d'intérêt trouvé";
                            }
                        })
                        .catch(error => console.error('Erreur lors de la récupération du taux d\'intérêt:',
                            error));
                }
            });
        });
    </script> --}}
    {{-- <script>
        $(document).ready(function() {

            $("#service_id").on('change', function() {
                let idservice = $(this).val();
                updateMontantAndUrl(idservice);
            });
            $("#montant_voulue").on('change', function() {
                let idservice = $("#service_id").val();
                updateMontantAndUrl(idservice);
            });
            let valeurInteret = $("#intnet");
            function updateMontantAndUrl(idservice) {
                let montant = parseFloat($("#montant_voulue").val().trim());

                if (!isNaN(montant) && idservice) {
                    let url = $("#lienApi").val();
                    url = url.replace(":serviceId", idservice).replace(":montant", montant);
                    console.log("URL construite:", url);
                    getVillePaysA(url);
                } else {
                    console.warn("Montant ou ID service non valides");
                }
            }
        });
        // function getVillePaysA(url) {
        //     $.ajax({
        //         type: "GET",
        //         url: url,
        //         contentType: "application/json",
        //         success: function(obj) {
        //             console.log(obj);
        //             $("#intnet").val(obj.interet);

        //         },
        //         error: function(data) {
        //             console.error("Erreur AJAX:", data);
        //             // ...
        //         }
        //     });
        // }
        function getVillePaysA(url) {
            $.ajax({
                type: "GET",
                url: url,
                contentType: "application/json",
                success: function(obj) {
                    try {
                        if (typeof obj !== "object") {
                            obj = JSON.parse(obj);
                        }
                        $("#intnet").val(obj.interet);
                    } catch (e) {
                        console.error("Erreur de parsing JSON:", e);
                        $("#intnet").val("Erreur de données");
                    }
                },
                error: function(data) {
                    console.error("Erreur AJAX:", data);
                    $("#intnet").val("Erreur de connexion");
                }
            });
        }
    </script> --}}

    {{-- <script>
        $(document).ready(function() {
            $("#service_id").on('change', function() {
                let idservice = $(this).val();
                updateMontantAndUrl(idservice);
            });

            $("#montant_voulue").on('change', function() {
                let idservice = $("#service_id").val();
                updateMontantAndUrl(idservice);
            });


            function updateMontantAndUrl(idservice) {
                let montant = parseFloat($("#montant_voulue").val().trim());

                if (!isNaN(montant) && idservice) {
                    let url = $("#lienApi").val();
                    url = url.replace(":serviceId", idservice).replace(":montant", montant);
                    console.log("URL construite:", url);
                    getVillePaysA(url);
                } else {
                    console.warn("Montant ou ID service non valides");
                }
            }
        });

        function getVillePaysA(url) {
            $.ajax({
                type: "GET",
                url: url,
                contentType: "application/json",
                success: function(obj) {
                    try {
                        console.log("Réponse brute:", obj); // Log pour voir la réponse brute
                        // Si la réponse n'est pas un objet JSON, on tente de parser
                        if (typeof obj !== "object") {
                            obj = JSON.parse(obj);
                        }
                        $("#intnet").val(obj.interet); // Mettre à jour l'input avec la valeur reçue
                        // console.log(document.getElementById("intnet"));
                    } catch (e) {
                        console.error("Erreur de parsing JSON:", e);
                        $("#intnet").val("Erreur de données");
                    }
                },
                error: function(data) {
                    console.error("Erreur AJAX:", data);
                    $("#intnet").val("Erreur de connexion");
                }
            });
        }
    </script> --}}
    <script>
        $(document).ready(function() {
            $('#montant_voulue').on('input', function() {
                var montant = $(this).val().replace(/\s/g, '');
                $(this).val(montant);
                validationMontantVoulue();
                triggerUpdate();
            });

            $('#service_id').on('change', function() {
                triggerUpdate();
            });

            function triggerUpdate() {
                let idservice = $("#service_id").val();
                let montant = parseFloat($("#montant_voulue").val().trim());
                if (!isNaN(montant) && idservice) {
                    let url = $("#lienApi").val().replace(":serviceId", idservice).replace(":montant", montant);
                    console.log("URL construite:", url);
                    getInterestRate(url);
                }
            }

            function validationMontantVoulue() {
                var montantVoulue = $('#montant_voulue');
                var montant = montantVoulue.val();
                montantVoulue.removeClass('is-invalid is-valid').next('.invalid-feedback, .valid-feedback')
                    .remove();
                if (!/^\d+$/.test(montant) || montant < 1000 || montant % 100 !== 0) {
                    montantVoulue.addClass('is-invalid').after(
                        '<div class="invalid-feedback">Le montant doit être un multiple de 100 et >= 1000.</div>'
                    );
                } else {
                    montantVoulue.addClass('is-valid').after(
                        '<div class="valid-feedback">Le montant est valide.</div>');
                }
            }

            function getInterestRate(url) {
                $.ajax({
                    type: "GET",
                    url: url,
                    contentType: "application/json",
                    success: function(response) {
                        try {
                            if (typeof response !== "object") response = JSON.parse(response);
                            console.log(response);
                            $("#intnet").val(response.interet);
                            $("#ter").html(
                                `<span class="text-danger mx-2 text-center">(${response.interet}%)</span>`
                            );
                            calculateMontantAPayer();
                        } catch (e) {
                            console.error("Erreur de parsing JSON:", e);
                            $("#intnet").val("Erreur de données");
                        }
                    },
                    error: function() {
                        console.error("Erreur AJAX");
                        $("#intnet").val("Erreur de connexion");
                    }
                });
            }

            function calculateMontantAPayer() {
                var montantVoulue = parseFloat($('#montant_voulue').val());
                var tauxInteret = parseFloat($('#intnet').val()) / 100;
                var montantAPayer = isNaN(montantVoulue) ? '' : Math.round(montantVoulue * (1 + tauxInteret));
                $('#montant_apayer').val(montantAPayer);
            }
            $('#contact_tresormoney').on('keypress', function(event) {
                if (event.which < 48 || event.which > 57) event.preventDefault();
            }).on('input', validationContactTresorMoney);

            function validationContactTresorMoney() {
                var contactInput = $('#contact_tresormoney').val().replace(/\s+/g, '');
                $('#contact_tresormoney').val(contactInput)
                    .removeClass('is-invalid is-valid').next('.invalid-feedback, .valid-feedback').remove();
                if (/^\d{10}$/.test(contactInput)) {
                    $('#contact_tresormoney').addClass('is-valid').after(
                        '<div class="valid-feedback">Le contact est valide.</div>');
                } else {
                    $('#contact_tresormoney').addClass('is-invalid').after(
                        '<div class="invalid-feedback">Le contact doit contenir 10 chiffres.</div>');
                }
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('FormID');
            const submitButton = document.getElementById('Valid');

            form.addEventListener('submit', function() {
                // Désactive le bouton de soumission et applique le texte de traitement
                submitButton.disabled = true;
                submitButton.classList.add('loading');
                submitButton.innerHTML = 'Traitement en cours...';
                return true; // Autorise la soumission du formulaire
            });
        });
    </script>
@endpush
