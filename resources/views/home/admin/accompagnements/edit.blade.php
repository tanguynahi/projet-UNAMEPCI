@extends('layouts.home_dashboard', ['title' => "Modifier Demande d'accompagnement "])
@section('content')
    @push('css')
        {{-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> --}}
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
                                <span class="title">Modifier ma demande d'accompagement </span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content">
                    <div class="tab-pane fade active show" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <form action="{{route('modification.demandeaccompagnement',$demandeaccompagnement->id)}}" method="POST" class="rbt-profile-row rbt-default-form row row--15"
                            enctype="multipart/form-data">
                            @method('put')
                            @csrf

                            <div class="col-6  col-lg-6 col-md-6 col-sm-6 col-12">
                                <label>Service <span class="text-danger"> *</span> </label>
                                <div class="rbt-modern-select bg-transparent height-45">
                                    <select class="w-100 @error('service_id') is-invalid @enderror" name="service_id"
                                        id="service_id" autocomplete="service_id" autofocus>
                                        <option value="">Sélectionnez le service</option>
                                        @forelse ($services as $service)
                                            @if ($demandeaccompagnement->service_id == $service->id)
                                                <option value="{{ $service->id }}" selected>
                                                    {{ $service->libelle }}
                                                </option>
                                            @else
                                                <option value="{{ $service->id }}">{{ $service->libelle }}
                                                </option>
                                            @endif
                                        @empty
                                            <option value=""><span readonly>Aucune donnée</span></option>
                                        @endforelse
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

                            <div class="col-6  col-lg-6 col-md-6 col-sm-6 col-12">
                                <label>Montant <span class="text-danger"> *</span> </label>
                                <input type="number" min="10000" max="5000000" placeholder="Ex: 1000000"
                                    id="montant_voulue" name="montant_voulue"
                                     value="{{ old('montant_voulue', $demandeaccompagnement->montant_voulue) }}"
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
                                        value="{{ old('contact_tresormoney',$demandeaccompagnement->contact_tresormoney) }}" placeholder="Ex: 0707070707"
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
                            <div class="col-3 col-lg-3 col-md-3 col-sm-3 col-12">
                                <label>Montant à payer <span class="text-danger"> *</span> <span
                                        class="text-danger">(+10%)</span> </label>
                                <input type="number" min="10000" max="5000000" id="montant_apayer"
                                 value="{{ old('montant_apayer', $demandeaccompagnement->montant_apayer) }}"
                                    name="montant_apayer" class="@error('montant_apayer') is-invalid @enderror"
                                    autocomplete="montant_apayer" autofocus readonly>
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
                            <div class="col-2  col-lg-2 col-md-2 col-sm-2 col-12">
                                <label> pénalité de retard </label>
                                <div id="terme" class="rbt-modern-select bg-transparent height-55 mt-4">
                                    <span class="text-danger mx-2 text-center">
                                        (10%)
                                    </span>
                                </div>
                            </div>
                            <div class="col-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <label>commentaire</label>
                                <textarea class="@error('commentaire') is-invalid @enderror" name="commentaire" id="commentaire" cols="10"
                                    rows="5" placeholder="Entre votre commentaire"  >{{ old('commentaire', $demandeaccompagnement->commentaire) }}</textarea>
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
                                        <button type="submit" class="rbt-btn btn-gradient">Effectuer ma demande</button>
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
    @if (Session::has('message'))
        <script>
            Swal("Message", "{{ Session::get('message') }}", 'Success', {
                button: true,
                button: "OK",
            });
        </script>
    @endif
    @push('js')
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <!-- Bootstrap JS -->
        {{-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.0/js/bootstrap.bundle.min.js"></script> --}}
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
        <script>
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

            function calculerMontantAPayer() {
                var montantVoulueInput = $('#montant_voulue');
                var montantAPayerInput = $('#montant_apayer');
                // Récupérer la valeur de montant_voulue et la convertir en nombre
                var montantVoulue = parseFloat(montantVoulueInput.val());
                // Vérifier si montant_voulue est un nombre valide
                if (isNaN(montantVoulue)) {
                    montantAPayerInput.val('');
                    return;
                }
                // Calculer le montant à payer et l'arrondir au nombre entier le plus proche
                var montantAPayer = Math.round(montantVoulue + (montantVoulue * 0.10));
                // Mettre à jour la valeur de montant_apayer
                montantAPayerInput.val(montantAPayer);
            }
        </script>
    @endpush
@endsection
