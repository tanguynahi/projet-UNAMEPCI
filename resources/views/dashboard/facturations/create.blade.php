@extends('layouts.dashboard', ['title' => 'Ajouter un facturation', 'toolbar' => '_toolbar2', 'breadcrumb' => 'Ajouter-Facturation'])

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/dashboard/cssbundle/select2.min.css') }}">
    <style>
        .input_periode {
            display: none;
        }
    </style>
@endpush

@section('content')
    <div class="row g-3 justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Renseignez le formulaire</h6>
                    <div class="dropdown morphing scale-left">
                        <a href="#" class="card-fullscreen" data-bs-toggle="tooltip" title="Card Full-Screen"><i
                                class="icon-size-fullscreen"></i></a>
                        <a href="{{ route('facturations.index') }}" class="btn btn-primary d-inline">Retour</a>
                    </div>
                </div>
                <div class="card-body" id="show_create">
                    <h6 class="fw-bold">Informations de base</h6>
                    <form action="{{ route('facturations.store') }}" method="POST" id="add_facturations_form"
                        class="needs-validation" novalidate>
                        @csrf
                        <div class="row g-3 mb-3">
                            <div class="col-lg-4 col-md-4 mb-3">
                                <div class="form-floating">
                                    <select
                                        class="form-control show-tick ms select2 @error('mutualiste_id') is-invalid @enderror"
                                        data-placeholder="Select" id="mutualiste_id" name="mutualiste_id"
                                        autocomplete="mutualiste_id" autofocus required style="height: 60px">
                                        <option disabled="">Sélectionner le mutualiste</option>
                                        @foreach ($mutualistes as $mutualiste)
                                            <option value="{{ $mutualiste->id }}"
                                                {{ old('mutualiste_id', $mutualiste->id) == $mutualiste->id ? 'selected' : '' }}>
                                                {{ $mutualiste->nom }} {{ $mutualiste->prenom }}
                                                ({{ $mutualiste->contact }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <label>Liste des Mutualistes<span class="text-danger fw-bold">*</span></label>
                                    @error('mutualiste_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 mb-3">
                                <div class="form-floating">
                                    <select
                                        class="form-control show-tick ms select2 @error('projet_mutualiste_id') is-invalid @enderror"
                                        data-placeholder="Select" id="projet_mutualiste_id" name="projet_mutualiste_id"
                                        autocomplete="projet_mutualiste_id" autofocus required style="height: 60px;">
                                        <option value="">Sélectionnez le bien</option>
                                        {{-- @if (old('projet_mutualiste_id'))
                                            <option value="{{ old('projet_mutualiste_id') }}" selected>
                                                {{ old('projet_mutualiste_id_text') }}
                                            </option>
                                        @endif --}}
                                    </select>
                                    <label>Liste des biens<span class="text-danger fw-bold">*</span></label>

                                    @error('projet_mutualiste_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 mb-3">
                                <div class="form-floating">
                                    <input type="number" id="cout_produit" name="cout_produit"
                                        class="form-control @error('cout_produit') is-invalid @enderror"
                                        value="{{ old('cout_produit') }}" placeholder="Total à Payer"
                                        autocomplete="cout_produit" autofocus required readonly>
                                    <input type="hidden" id="cout_produit_hidden" name="cout_produit"
                                        value="{{ old('cout_produit') }}" required>
                                    <label>Montant Affaire<span class="text-danger fw-bold">*</span></label>
                                    @error('cout_produit')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 mb-3">
                                <div class="form-floating">
                                    <input type="date" id="date" name="date"
                                        class="form-control @error('date') is-invalid @enderror"
                                        value="{{ old('date') }}" placeholder="Date" autocomplete="date" autofocus
                                        required readonly>
                                    <input type="hidden" id="date_hidden" name="date" value="{{ old('date') }}"
                                        required>
                                    <label>Date<span class="text-danger fw-bold">*</span></label>
                                    @error('date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            {{-- <div class="col-lg-4 col-md-4 mb-3">
                                <div class="form-floating">
                                    <select
                                        class="form-control show-tick ms select2 @error('redevance_id') is-invalid @enderror"
                                        data-placeholder="Select" id="redevance_id" name="redevance_id"
                                        autocomplete="redevance_id" autofocus required style="height: 60px">
                                        <option value="">Sélectionner la redevance</option>
                                        @foreach ($redevances as $redevance)
                                            <option value="{{ $redevance->id }}"
                                                {{ old('redevance_id', $redevance->id) == $redevance->id ? 'selected' : '' }}>
                                                {{ $redevance->libelle }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label>Liste des Redevances<span class="text-danger fw-bold">*</span></label>
                                    @error('redevance_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div> --}}

                            <div class="col-lg-4 col-md-4 mb-3">
                                <div class="form-floating">
                                    <select
                                        class="form-control show-tick ms select2 @error('redevance_id') is-invalid @enderror"
                                        data-placeholder="Select" id="redevance_id" name="redevance_id"
                                        autocomplete="redevance_id" autofocus required style="height: 60px">
                                        <option value="">Sélectionner la redevance</option>
                                        @foreach ($redevances as $redevance)
                                            <option value="{{ $redevance->id }}"
                                                data-periode="{{ $redevance->periode->libelle }}"
                                                {{ old('redevance_id', $redevance->id) == $redevance->id ? 'selected' : '' }}>
                                                {{ $redevance->libelle }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label>Liste des Redevances<span class="text-danger fw-bold">*</span></label>
                                    @error('redevance_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 mb-3">
                                <div class="form-floating">
                                    <input type="texte" id="total_apayer" name="total_apayer"
                                        class="form-control @error('total_apayer') is-invalid @enderror"
                                        value="{{ old('total_apayer') }}" placeholder="Total à Payer"
                                        autocomplete="total_apayer" autofocus required>
                                    <label>Total à Payer<span class="text-danger fw-bold">*</span></label>
                                    @error('total_apayer')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 mb-3">
                                <div class="form-floating">
                                    <input type="texte" id="periode_id_show" name="periode_id_show"
                                        class="form-control @error('periode_id_show') is-invalid @enderror"
                                        value="{{ old('periode_id_show') }}" placeholder="Période" readonly>
                                    <input type="hidden" class="form-control @error('periode_id') is-invalid @enderror"
                                        value="{{ old('periode_id') }}" id="periode_id" name="periode_id"
                                        value="" required>
                                    <label>Période<span class="text-danger fw-bold">*</span></label>
                                    @error('periode_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 input_date_faturation">
                                <div class="form-floating">
                                    <input type="date" id="date_facturation" name="date_facturation"
                                        class="form-control @error('date_facturation') is-invalid @enderror"
                                        value="{{ old('date_facturation') }}" placeholder="Date facturation"
                                        autocomplete="date_facturation" autofocus required>
                                    <label>Date facturation<span class="text-danger fw-bold">*</span></label>
                                    @error('date_facturation')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 mb-3 ">
                                <div class="form-floating">
                                    <input type="texte" id="montant_periodique" name="montant_periodique"
                                        class="form-control @error('montant_periodique') is-invalid @enderror"
                                        value="{{ old('montant_periodique') }}" placeholder="Total à Payer"
                                        autocomplete="montant_periodique" autofocus required>
                                    <label>Montant Périodique<span class="text-danger fw-bold">*</span></label>
                                    @error('montant_periodique')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 mb-3 input_periode">
                                <div class="form-floating">
                                    <input type="number" id="frequence" name="frequence"
                                        class="form-control @error('frequence') is-invalid @enderror"
                                        value="{{ old('frequence') }}" placeholder="Fréquence" autocomplete="frequence"
                                        autofocus required>
                                    <label>Fréquence<span class="text-danger fw-bold">*</span></label>
                                    @error('frequence')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 ">
                                <div class="form-floating">
                                    <input type="date" id="date_debut" name="date_debut"
                                        class="form-control @error('date_debut') is-invalid @enderror"
                                        value="{{ old('date_debut') }}" placeholder="Date début"
                                        autocomplete="date_debut" autofocus required>
                                    <label>Date début<span class="text-danger fw-bold">*</span></label>
                                    @error('date_debut')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- <div class="col-lg-2 col-md-2  ">
                                <div class="form-floating">
                                    <input type="date" id="date_fin" name="date_fin"
                                        class="form-control @error('date_fin') is-invalid @enderror"
                                        value="{{ old('date_fin') }}" placeholder="Date fin" autocomplete="date_fin"
                                        autofocus required>
                                    <label>Date fin<span class="text-danger fw-bold">*</span></label>
                                    @error('date_fin')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div> --}}

                            <div class="col-lg-2 col-md-2" id="date_fin_container">
                                <div class="form-floating">
                                    <input type="date" id="date_fin_paiement" name="date_fin_paiement"
                                        class="form-control @error('date_fin_paiement') is-invalid @enderror"
                                        value="{{ old('date_fin_paiement') }}" placeholder="Date fin"
                                        autocomplete="date_fin_paiement" autofocus>
                                    <label>Date fin paiement <span class="text-danger fw-bold">*</span></label>
                                    @error('date_fin_paiement')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            {{-- <div class="col-lg-4 col-md-4 mb-4 " id="montantJour">
                                <div class="form-floating">
                                    <input type="texte" id="montant_periodique_jour" name="montant_periodique_jour"
                                        class="form-control @error('montant_periodique_jour') is-invalid @enderror"
                                        value="{{ old('montant_periodique_jour') }}" placeholder="Total à Payer"
                                        autocomplete="montant_periodique_jour" autofocus required>
                                    <label>Montant Périodique<span class="text-danger fw-bold">*</span></label>
                                    @error('montant_periodique_jour')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div> --}}



                            <div class="col-lg-3 col-md-3 input_periode" style=" margin-right: 9px;">
                                <button class="btn btn-lg btn-primary w-100" id="calculerDateDeFin">Calculer</button>
                            </div>
                            <div class="col-lg-2 col-md-2 input_periode ">
                                <div class="form-floating">
                                    <input type="date" id="date_fin" name="date_fin"
                                        class="form-control @error('date_fin') is-invalid @enderror"
                                        value="{{ old('date_fin') }}" placeholder="Date fin" autocomplete="date_fin"
                                        autofocus required readonly>
                                    <label>Date fin<span class="text-danger fw-bold">*</span></label>
                                    @error('date_fin')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 input_periode">
                                <div class="form-floating">
                                    <input type="date" id="date_facturation_2" name="date_facturation_2"
                                        class="form-control @error('date_facturation_2') is-invalid @enderror"
                                        value="{{ old('date_facturation_2') }}" placeholder="Date facturation"
                                        autocomplete="date_facturation_2" autofocus required>
                                    <label>Date facturation<span class="text-danger fw-bold">*</span></label>
                                    @error('date_facturation_2')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        {{-- <div class="row g-3 mb-3">

                        </div> --}}
                        <div class="mt-3">
                            <p><span class="text-danger fw-bold">*</span>Champs obligatoires.</p>
                        </div>
                        <div class="row g-3 ">
                            <div class="mx-auto d-flex justify-content-center">

                                <a href="{{ route('facturations.index') }}"
                                    class="btn btn-secondary w-25 mx-2">Annuler</a>
                                <button type="submit" id="add_facturation_btn"
                                    class="btn btn-primary w-25 mx-2">Enregistrer</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <!-- Plugin Js -->
    <script src="{{ asset('assets/dashboard/js/bundles/select2.bundle.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>

    <!-- Vendor Script -->
    <script>
        // Select2 selectbox
        $('.select2').select2();
        $(".search-select").select2({
            allowClear: true
        });
        $("#max-select").select2({
            placeholder: "Select",
            maximumSelectionSize: 2,
        });
        $("#loading-select").select2({
            placeholder: "Select",
            minimumInputLength: 1,
            query: function(query) {
                var data = {
                        results: []
                    },
                    i, j, s;
                for (i = 1; i < 5; i++) {
                    s = "";
                    for (j = 0; j < i; j++) {
                        s = s + query.term;
                    }
                    data.results.push({
                        id: query.term + i,
                        text: s
                    });
                }
                query.callback(data);
            }
        });
        var data = [{
            id: 0,
            tag: 'enhancement'
        }, {
            id: 1,
            tag: 'bug'
        }, {
            id: 2,
            tag: 'duplicate'
        }, {
            id: 3,
            tag: 'invalid'
        }, {
            id: 4,
            tag: 'wontfix'
        }];

        function format(item) {
            return item.tag;
        }
        $("#array-select").select2({
            placeholder: "Select",
            data: {
                results: data,
                text: 'tag'
            },
            formatSelection: format,
            formatResult: format
        });
    </script>
    <script>
        $(document).ready(function() {
            // Vérifiez si le champ projet_mutualiste_id est vide au chargement de la page
            function checkInitialProjetMutualiste() {
                var initialProjetMutualisteId = $('#projet_mutualiste_id').val()
                if (initialProjetMutualisteId === "") {
                    $('#cout_produit').val('');
                    $('#cout_produit_hidden').val('');
                    $('#date').val('');
                    $('#date_hidden').val('');
                }
            }

            checkInitialProjetMutualiste();

            //affiche rou masquer champ avec class input_periode
            // Fonction pour vérifier et afficher ou masquer les éléments en fonction de la période et de la redevance
            function checkPeriodeDisplay(periodeId, redevanceId) {
                if (redevanceId && periodeId != 1 && periodeId != 2 && periodeId != 6) {
                    $('.input_periode').show();
                    $('.input_date_faturation').hide();
                } else {
                    $('.input_periode').hide();
                    $('.input_date_faturation').show();
                }
            }

            // Affiche un message par défaut lorsque le champ est vide
            $('#total_apayer').siblings('.form-text').text('Veuillez entrer le montant total.').addClass(
                'text-danger');
            // $('#montant_periodique').siblings('.form-text').text('Veuillez saisir le montant périodique.').addClass(
            //     'text-danger');

            initializeAmount();
            initializeMontantPeriodique();

            // Fonction pour valider le montant
            function initializeAmount() {
                var totalAPayerInput = $('#total_apayer');
                // $('#amount_paid').val(0);
                // Réinitialiser les messages d'erreur
                totalAPayerInput.removeClass('is-invalid');
                totalAPayerInput.next('.invalid-feedback').remove();
                var validationStatus = {
                    isValid: true
                };
                validateTotalAPayer(validationStatus);
            }

            // Fonction pour valider le montant
            function initializeMontantPeriodique() {
                var montantPeriodique = $('#montant_periodique');
                // $('#montant_periode').val(0);
                // Réinitialiser les messages d'erreur
                montantPeriodique.removeClass('is-invalid');
                montantPeriodique.next('.invalid-feedback').remove();
                var validationStatusMontantPerriodique = {
                    isValid: true
                };
                validateMontantPeriodique(validationStatusMontantPerriodique);
            }

            // Fonction pour valider le montant total à payer
            function validateTotalAPayer(validationStatus) {
                var totalAPayerInput = $('#total_apayer');
                var totalAPayer = totalAPayerInput.val();

                // Filtrer les caractères non numériques
                totalAPayerInput.val(totalAPayer.replace(/\D/g, ''));

                totalAPayerInput.removeClass('is-invalid is-valid');
                totalAPayerInput.next('.invalid-feedback').remove();

                if (!/^\d+$/.test(totalAPayer)) {
                    totalAPayerInput.addClass('is-invalid');
                    totalAPayerInput.after(
                        '<div class="invalid-feedback">Entrer le montant total à payer (en chiffres).</div>');
                    validationStatus.isValid = false;
                } else if (totalAPayer < 500 || totalAPayer.trim() === "") {
                    totalAPayerInput.addClass('is-invalid');
                    totalAPayerInput.after(
                        '<div class="invalid-feedback">Le montant total doit être supérieur ou égal à 500 FCFA.</div>'
                    );
                    validationStatus.isValid = false;
                } else if (totalAPayer % 100 !== 0) {
                    totalAPayerInput.addClass('is-invalid');
                    totalAPayerInput.after(
                        '<div class="invalid-feedback">Le montant total doit être un multiple de 100.</div>');
                    validationStatus.isValid = false;
                } else {
                    validationStatus.isValid = true;
                    totalAPayerInput.removeClass('is-invalid');
                    totalAPayerInput.addClass('is-valid');
                }
            }

            // Fonction pour valider le montant periodique
            function validateMontantPeriodique(validationStatusMontantPerriodique) {
                var montantPeriodiqueInput = $('#montant_periodique');
                var montantPeriodique = montantPeriodiqueInput.val();

                // Filtrer les caractères non numériques
                montantPeriodiqueInput.val(montantPeriodique.replace(/\D/g, ''));

                montantPeriodiqueInput.removeClass('is-invalid is-valid');
                montantPeriodiqueInput.next('.invalid-feedback').remove();

                if (!/^\d+$/.test(montantPeriodique)) {
                    montantPeriodiqueInput.addClass('is-invalid');
                    montantPeriodiqueInput.after(
                        '<div class="invalid-feedback">Entrer le montant périodique (en chiffres).</div>');
                    validationStatusMontantPerriodique.isValid = false;
                } else if (montantPeriodique < 500 || montantPeriodique.trim() === "") {
                    montantPeriodiqueInput.addClass('is-invalid');
                    montantPeriodiqueInput.after(
                        '<div class="invalid-feedback">Le montant périodique doit être supérieur ou égal à 500 FCFA.</div>'
                    );
                    validationStatusMontantPerriodique.isValid = false;
                } else if (montantPeriodique % 100 !== 0) {
                    montantPeriodiqueInput.addClass('is-invalid');
                    montantPeriodiqueInput.after(
                        '<div class="invalid-feedback">Le montant périodique doit être un multiple de 100.</div>'
                    );
                    validationStatusMontantPerriodique.isValid = false;
                } else {
                    validationStatusMontantPerriodique.isValid = true;
                    montantPeriodiqueInput.removeClass('is-invalid');
                    montantPeriodiqueInput.addClass('is-valid');
                }
            }

            // Ecouteur d'événement pour vérifier le montant total à chaque saisie
            // Ecouteurs d'événements pour la saisie
            $('#total_apayer').on('input', function() {
                var validationStatus = {
                    isValid: true
                };
                validateTotalAPayer(validationStatus);
            });

            $('#montant_periodique').on('input', function() {
                var validationStatusMontantPerriodique = {
                    isValid: true
                };
                validateMontantPeriodique(validationStatusMontantPerriodique);
            });

            // projets mutualistes
            // projets mutualistes
            function getProjetMutualistes(mutualisteId, changerMutualiste) {
                $.ajax({
                    type: "GET",
                    url: "/projets-aquis-mutualiste/" + mutualisteId,
                    dataType: 'json',
                    success: function(data) {
                        $('#projet_mutualiste_id').empty();
                        $('#projet_mutualiste_id').append(
                            '<option value="">Sélectionnez le bien</option>');
                        $.each(data, function(key, value) {
                            $('#projet_mutualiste_id').append('<option value="' + value
                                .projet_mutualiste_id + '">' + value.libelle + ' (' + value
                                .projet + ')</option>');
                        });
                        // Sélectionner la valeur précédemment sélectionnée en cas d'erreur de validation
                        var oldProjetMutualisteId = "{{ old('projet_mutualiste_id') }}";
                        if (oldProjetMutualisteId && !changerMutualiste) {
                            $('#projet_mutualiste_id').val(oldProjetMutualisteId);
                            getProjetDetails(oldProjetMutualisteId);
                        } else {
                            // Réinitialiser les champs lorsque le mutualiste change
                            $('#cout_produit').val('');
                            $('#cout_produit_hidden').val('');
                            $('#date').val('');
                            $('#date_hidden').val('');
                        }


                    },
                });
            }

            function getProjetDetails(projetAcquisId) {
                $.ajax({
                    type: "GET",
                    url: "/projet-aquis-details/" + projetAcquisId,
                    dataType: 'json',
                    success: function(data) {
                        if (data && data.total_apayer) {
                            // console.log('date :' + data.date);
                            $('#cout_produit').val(data.total_apayer);
                            $('#cout_produit_hidden').val(data.total_apayer);
                            $('#date').val(data.date);
                            $('#date_hidden').val(data.date);
                        } else {
                            $('#cout_produit').val('');
                            $('#cout_produit_hidden').val('');
                            $('#date').val('');
                            $('#date_hidden').val();
                        }
                    },
                    error: function() {
                        $('#cout_produit').val('');
                        $('#cout_produit_hidden').val('');
                        $('#date').val('');
                        $('#date_hidden').val();
                    }
                });
            }

            var initialMutualisteId = $('#mutualiste_id').val();
            if (initialMutualisteId) {
                getProjetMutualistes(initialMutualisteId);
            }

            $('#mutualiste_id').on('change', function() {
                var mutualisteId = $(this).val();
                if (mutualisteId) {
                    // Vider les champs visibles
                    $('#cout_produit').val('');
                    $('#date').val('');

                    // Vider les champs cachés
                    $('#cout_produit_hidden').val('');
                    $('#date_hidden').val('');

                    // Vider les options du projet_mutualiste_id
                    $('#projet_mutualiste_id').empty().append(
                        '<option value="">Sélectionnez le bien</option>');
                    var changerMutualiste = true;

                    // Récupérer les projets mutualistes associés au nouvel mutualisteId
                    getProjetMutualistes(mutualisteId, changerMutualiste);
                } else {
                    // Si aucun mutualiste sélectionné, réinitialiser tout
                    $('#cout_produit').val('');
                    $('#date').val('');
                    $('#cout_produit_hidden').val('');
                    $('#date_hidden').val('');
                    $('#projet_mutualiste_id').empty().append(
                        '<option value="">Sélectionnez le bien</option>');
                }
            });



            $('#projet_mutualiste_id').on('change', function() {
                var projetAcquisId = $(this).val();
                if (projetAcquisId) {
                    getProjetDetails(projetAcquisId);
                } else {
                    $('#cout_produit').val('');
                    $('#cout_produit_hidden').val('');
                    $('#date').val('');
                    $('#date_hidden').val('');
                }
            });

            // Vérifiez la valeur initiale de la période et de la redevance au chargement de la page
            var initialPeriodeId = $('#periode_id').val();
            var initialRedevanceId = $('#redevance_id').val();
            getRedevancePeriodes(initialRedevanceId);
            checkPeriodeDisplay(initialPeriodeId, initialRedevanceId);


            // Fonction pour récupérer les périodes et mettre à jour les champs
            function getRedevancePeriodes(redevanceId) {
                $.ajax({
                    type: "GET",
                    url: "/redevance-periodes/" + redevanceId,
                    dataType: 'json',
                    success: function(data) {
                        $('#redevance_').empty();
                        if (data.length > 0) {
                            var periode = data[0]; // Utiliser la première période
                            $('#periode_id_show').val(periode.libelle);
                            $('#periode_id').val(periode.id);

                            console.log(periode.libelle);
                            if (periode.libelle == "Journalière") {
                                $('#date_fin_container').show();
                                $('#montantJour').show();

                            } else {
                                $('#date_fin_container').hide();
                                $('#montantJour').hide();
                            }

                            checkPeriodeDisplay(periode.id, redevanceId);
                        } else {
                            $('#periode_id_show').val('');
                            $('#periode_id').val('');
                            checkPeriodeDisplay('', redevanceId);
                        }
                    },
                    error: function() {
                        $('#periode_id_show').val('');
                        $('#periode_id').val('');
                        checkPeriodeDisplay('', redevanceId);
                    }
                });
            }

            // Ecoutez les changements sur le champ redevance_id
            $('#redevance_id').on('change', function() {
                var redevanceId = $(this).val();
                if (redevanceId) {
                    $('#periode_id_show').val('');
                    $('#periode_id').val('');

                    getRedevancePeriodes(redevanceId);
                } else {
                    $('#periode_id_show').val('');
                    $('#periode_id').val('');
                    checkPeriodeDisplay('', redevanceId);
                }
            });


            initializeFreqeunce();

            // Fonction pour valider le frequence
            function initializeFreqeunce() {
                var frequence = $('#frequence');
                // $('#montant_periode').val(0);
                // Réinitialiser les messages d'erreur
                frequence.removeClass('is-invalid');
                frequence.next('.invalid-feedback').remove();
                var validationStatusFrequence = {
                    isValid: true
                };
                validateFrequence(validationStatusFrequence);
            }

            // Fonction pour valider la fréquence
            function validateFrequence(validationStatusFrequence) {
                var frequenceInput = $('#frequence');
                var frequence = frequenceInput.val();

                // Filtrer les caractères non numériques
                frequenceInput.val(frequence.replace(/\D/g, ''));

                frequenceInput.removeClass('is-invalid is-valid');
                frequenceInput.next('.invalid-feedback').remove();

                if (!/^\d+$/.test(frequence)) {
                    frequenceInput.addClass('is-invalid');
                    frequenceInput.after(
                        '<div class="invalid-feedback">Entrer la fréquence.</div>');
                    validationStatusFrequence.isValid = false;
                } else if (frequence < 1 || frequence.trim() === "") {
                    frequenceInput.addClass('is-invalid');
                    frequenceInput.after(
                        '<div class="invalid-feedback">La fréquence doit être supérieur ou égal à 1.</div>'
                    );
                    validationStatusFrequence.isValid = false;
                } else {
                    validationStatusFrequence.isValid = true;

                    frequenceInput.addClass('is-valid');
                    frequenceInput.removeClass('is-invalid');
                    // frequenceInput.next('.invalid-feedback').remove();
                }
            }

            $('#frequence').on('input', function() {
                var validationStatusFrequence = {
                    isValid: true
                };
                validateFrequence(validationStatusFrequence);
            });


            initializeDateDebut();
            // Fonction pour initialiser message alert pour date de debut
            function initializeDateDebut() {
                var dateDebut = $('#date_debut');
                // Réinitialiser les messages d'erreur
                dateDebut.removeClass('is-invalid');
                dateDebut.next('.invalid-feedback').remove();
                var validationStatusDateDebut = {
                    isValid: true
                };
                validateDateDebut(validationStatusDateDebut);
            }

            function validateDateDebut(validationStatusDateDebut) {
                var dateDebutInput = $('#date_debut');
                var dateDebut = dateDebutInput.val();

                dateDebutInput.removeClass('is-invalid is-valid');
                dateDebutInput.next('.invalid-feedback').remove();

                if (!dateDebut) {
                    dateDebutInput.addClass('is-invalid');
                    dateDebutInput.after(
                        '<div class="invalid-feedback">Veuillez entrer une date valide.</div>'
                    );
                    validationStatusDateDebut.isValid = false;
                } else {
                    // Vérifier si la date est valide
                    var dateObject = new Date(dateDebut);
                    if (isNaN(dateObject.getTime())) {
                        dateDebutInput.addClass('is-invalid');
                        dateDebutInput.after(
                            '<div class="invalid-feedback">La date entrée est invalide.</div>'
                        );
                        validationStatusDateDebut.isValid = false;
                    } else {
                        validationStatusDateDebut.isValid = true;
                        dateDebutInput.addClass('is-valid');
                    }
                }
            }

            $('#date_debut').on('input', function() {
                var validationStatusDateDebut = {
                    isValid: true
                };
                validateDateDebut(validationStatusDateDebut);
            });

            // validate date facturation
            function validateDateFacturation(validationStatusDateFacturation) {
                var dateFacturationInput = $('#date_facturation');
                var dateFacturation = dateFacturationInput.val();

                dateFacturationInput.removeClass('is-invalid is-valid');
                dateFacturationInput.next('.invalid-feedback').remove();

                if (!dateFacturation) {
                    dateFacturationInput.addClass('is-invalid');
                    dateFacturationInput.after(
                        '<div class="invalid-feedback">Veuillez entrer une date valide.</div>'
                    );
                    validationStatusDateFacturation.isValid = false;
                } else {
                    // Vérifier si la date est valide
                    var dateObject = new Date(dateFacturation);
                    if (isNaN(dateObject.getTime())) {
                        dateFacturationInput.addClass('is-invalid');
                        dateFacturationInput.after(
                            '<div class="invalid-feedback">La date entrée est invalide.</div>'
                        );
                        validationStatusDateFacturation.isValid = false;
                    } else {
                        validationStatusDateFacturation.isValid = true;
                        dateFacturationInput.addClass('is-valid');
                    }
                }
            }

            $('#date_facturation').on('input', function() {
                var validationStatusDateFacturation = {
                    isValid: true
                };
                validateDateFacturation(validationStatusDateFacturation);
            });

            // Validated   date facturation 2
            function validateDateFacturation2(validationStatusDateFacturation2) {
                var dateFacturation2Input = $('#date_facturation_2');
                var dateFacturation2 = dateFacturation2Input.val();

                dateFacturation2Input.removeClass('is-invalid is-valid');
                dateFacturation2Input.next('.invalid-feedback').remove();

                if (!dateFacturation2) {
                    dateFacturation2Input.addClass('is-invalid');
                    dateFacturation2Input.after(
                        '<div class="invalid-feedback">Veuillez entrer une date valide.</div>'
                    );
                    validationStatusDateFacturation2.isValid = false;
                } else {
                    // Vérifier si la date est valide
                    var dateObject = new Date(dateFacturation2);
                    if (isNaN(dateObject.getTime())) {
                        dateFacturation2Input.addClass('is-invalid');
                        dateFacturation2Input.after(
                            '<div class="invalid-feedback">La date entrée est invalide.</div>'
                        );
                        validationStatusDateFacturation2.isValid = false;
                    } else {
                        validationStatusDateFacturation2.isValid = true;
                        dateFacturation2Input.addClass('is-valid');
                    }
                }
            }

            $('#date_facturation_2').on('input', function() {
                var validationStatusDateFacturation2 = {
                    isValid: true
                };
                validateDateFacturation2(validationStatusDateFacturation2);
            });



            $('#calculerDateDeFin').click(function(e) {
                e.preventDefault();

                const inputPeriode = $('#periode_id');
                const inputFrequence = $('#frequence');
                const inputDateDebut = $('#date_debut');
                const inputDateFin = $('#date_fin');

                // Récupérer les valeurs des champs
                let periode = parseInt(inputPeriode.val());
                const dateDebut = new Date(inputDateDebut.val());
                let dateFin = new Date(dateDebut);
                let frequence = parseInt(inputFrequence.val());

                console.log('frequence : ' + frequence + ' dateDebut : ' + dateDebut + ' dateFin : ' +
                    dateFin + ' periode : ' + periode);

                // Réinitialiser les messages d'erreur
                $('.error-message').remove(); // Supprimer tous les messages d'erreur existants


                switch (periode) {
                    case 3:
                        dateFin.setDate(dateFin.getDate() + 7 * frequence);
                        break;
                    case 4:
                        dateFin.setMonth(dateFin.getMonth() + frequence);
                        break;
                    case 5:
                        dateFin.setFullYear(dateFin.getFullYear() + frequence);
                        break;
                    default:
                        dateFin = '';
                }


                if (dateFin) {
                    // console.log(dateDebut);
                    // console.log(dateFin);
                    $('#date_fin').val(dateFin.toISOString().split('T')[0]);
                    // inputDateFin.value = dateFin.toISOString().split('T')[0];
                }

            });




            // validate date fin de paiement si periode est journaliere
            function validateDateFinPaiement(validationStatusDateFinPaiement) {
                var dateFinPaiemenyInput = $('#date_fin_paiement');
                var dateFinPay = dateFinPaiemenyInput.val();

                dateFinPaiemenyInput.removeClass('is-invalid is-valid');
                dateFinPaiemenyInput.next('.invalid-feedback').remove();

                if (!dateFinPay) {
                    dateFinPaiemenyInput.addClass('is-invalid');
                    dateFinPaiemenyInput.after(
                        '<div class="invalid-feedback">Veuillez entrer une date valide.</div>'
                    );
                    validationStatusDateFinPaiement.isValid = false;
                } else {
                    // Vérifier si la date est valide
                    var dateObject = new Date(dateFinPay);
                    if (isNaN(dateObject.getTime())) {
                        dateFinPaiemenyInput.addClass('is-invalid');
                        dateFinPaiemenyInput.after(
                            '<div class="invalid-feedback">La date entrée est invalide.</div>'
                        );
                        validationStatusDateFinPaiement.isValid = false;
                    } else {
                        validationStatusDateFinPaiement.isValid = true;
                        dateFinPaiemenyInput.addClass('is-valid');
                    }
                }
            }

            $('#date_fin_paiement').on('input', function() {
                var validationStatusDateFinPaiement = {
                    isValid: true
                };
                validateDateFinPaiement(validationStatusDateFinPaiement);
            });

        });
    </script>
@endpush
