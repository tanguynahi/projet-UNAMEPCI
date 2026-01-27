@extends('layouts.dashboard', ['title' => 'Infos Paiement Cash - projet', 'toolbar' => '_toolbar2', 'breadcrumb' => 'Infos-paiement-Projet'])

@push('css')
@endpush

@section('content')
    <div class="row g-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Informations sur le paiement</h6>
                    <div class="dropdown morphing scale-left">
                        <a href="#" class="card-fullscreen" data-bs-toggle="tooltip" title="Card Full-Screen"><i
                                class="icon-size-fullscreen"></i></a>
                        <a href="{{ route('paiements.caisse') }}" class="btn btn-primary d-inline">Retour</a>
                    </div>
                </div>
                <div class="card-body" id="show_all_produits">
                    <div class="row mb-5">

                        <div class="col-lg-6 col-md-6 col-sm-6">
                            @if (!empty($produit))
                                <div class="row mb-3">
                                    <h6><u>Informations produit</u></h6>

                                    <img src="/{{ $produit->lien_photo }}" class="img-thumbnail"
                                        style="height: 80px; width: 100px;" alt="Image du produit">

                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <h4 class="mt-4 mt-lg-0"><strong>{{ $produit->libelle }}</strong>
                                        </h4>
                                        <p class="mb-0 text-primary fs-6 fw-bold">
                                            <span
                                                class="fs-6 text-muted fw-light  me-2">Coût</span>{{ formatMontant($produit->montant_produit) }}
                                        </p>
                                        <p class="mb-0 text-primary fs-6 fw-bold">
                                            <span class="fs-6 text-muted fw-light  me-2">Contribution</span> <span
                                                class="fs-6 fw-normal">{{ formatMontant($produit->total_apayer) }}
                                                / Mois</span>
                                        </p>
                                    </div>
                                </div>
                                <hr>
                            @endif
                            <div class="row align-items-center mb-3">
                                <h6><u>Informations Paiements</u> : @if(!empty($libelle)) <span class="text-primary">{{ $libelle ?? "" }}</span> @endif</h6>
                                <div class="col-6 col-lg-6 col-sm-6 col-md-6">
                                    <p> <span> Réference :</span>
                                        <u>
                                            <span class="text-uppercase fw-bold">
                                                {{ $paiement->reference ?? $paiement->codePaiement }}</span></u>
                                    </p>
                                </div>
                                <div class="col-6 col-lg-6 col-sm-6 col-md-6">
                                    <p> <span> Montant :</span>
                                        <u>
                                            <span class="text-uppercase fw-bold">
                                                {{ formatMontant($paiement->montant_initial) }}</span></u>
                                    </p>
                                </div>
                                <div class="col-6 col-lg-6 col-sm-6 col-md-6">
                                    <p> <span> Mode de paiement :</span>
                                        <u>
                                            <span class="text-uppercase fw-bold">
                                                {{ $paiement->moyen_paiement }}</span></u>
                                    </p>
                                </div>
                                <div class="col-6 col-lg-6 col-sm-6 col-md-6">
                                    <p> <span> Date de paiement :</span>
                                        <u>
                                            <span class="text-uppercase fw-bold">
                                                {{ formatDate($paiement->date_paiement_initial ?? '000-000-000') }}</span></u>
                                    </p>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 pt-2">
                                    @if ($paiement->status == 1)
                                        <p><span class="fs-5">État : </span><u><span
                                                    class="text-success fs-5 fw-bold">Approuvée</span></u></p>
                                    @endif

                                    @if ($paiement->status == 2)
                                        <p><span class="fs-5">État : </span><u><span class="text-primary fs-5 fw-bold">En
                                                    attente</span></u></p>
                                    @endif

                                    @if ($paiement->status == 3)
                                        <p><span class="fs-5">État : </span><u><span
                                                    class="text-danger fs-5 fw-bold">Rejetée</span></u></p>
                                    @endif
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-8">
                                    <div class="d-flex">
                                        @if ($paiement->status == 2)
                                            <a class="btn btn-outline-danger mx-2 fw-bold" data-bs-toggle="modal"
                                                data-bs-target="#rejeterDemandeModalOK"><i class="fa fa-close me-1"></i>
                                                Rejetée</a>
                                            <a class="btn btn-outline-success mx-2 fw-bold" data-bs-toggle="modal"
                                                data-bs-target="#approuverDemandeModalOK">
                                                <i class="fa fa-check me-1"></i> Approuvée
                                            </a>
                                        @endif
                                    </div>
                                </div>

                                {{-- MODAL DE VALIDATION DE DEMANDE DE SOUSCRIPTION POUR PRODUIT --}}
                                <!-- Modal approuverDemandeModal-->
                                @if ($paiement->status == 2)
                                    <div class="modal fade flip" id="approuverDemandeModalOK" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-body p-5 text-center">
                                                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                                                        colors="primary:#405189,secondary:#f06548"
                                                        style="width:90px;height:90px">
                                                    </lord-icon>
                                                    <div class="mt-4 text-center">
                                                        <h4>Vous êtes sur le point d'approuver <br>ce paiement?
                                                        </h4>
                                                        <p class="text-muted fs-15 mb-4">
                                                            Voulez-vous, poursuivre cette opération
                                                        </p>
                                                        <div class="hstack gap-2 justify-content-center remove">
                                                            <form
                                                                action="{{ route('accepter.paiementcash', $paiement->id) }}"
                                                                method="post">
                                                                @method('post')
                                                                @csrf
                                                                <button
                                                                    class="btn btn-link link-success fw-medium text-decoration-none"
                                                                    id="AnnulerRecord-close" data-bs-dismiss="modal"><i
                                                                        class="ri-close-line me-1 align-middle"></i>
                                                                    Annuler</button>

                                                                <button type="submit" class="btn btn-danger">Oui</button>
                                                            </form>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal fade flip" id="rejeterDemandeModalOK" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-body p-5 text-center">
                                                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                                                        colors="primary:#405189,secondary:#f06548"
                                                        style="width:90px;height:90px">
                                                    </lord-icon>
                                                    <div class="mt-4 text-center">
                                                        <h4>Vous êtes sur le point de rejetée <br>ce paiement ?
                                                        </h4>
                                                        <p class="text-muted fs-15 mb-4">
                                                            Voulez-vous, poursuivre cette opération
                                                        </p>
                                                        <div class="hstack gap-2 justify-content-center remove">
                                                            <form
                                                                action="{{ route('refuser.paiementcash', $paiement->id) }}"
                                                                method="POST">
                                                                @method('post')
                                                                @csrf
                                                                <button
                                                                    class="btn btn-link link-success fw-medium text-decoration-none"
                                                                    id="AnnulerRecord-close" data-bs-dismiss="modal"><i
                                                                        class="ri-close-line me-1 align-middle"></i>
                                                                    Annuler</button>
                                                                <button type="submit" class="btn btn-danger">Oui</button>

                                                            </form>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <p><i class="fa fa-calendar me-1"></i> Soumit le : <span
                                            class="fw-bold">{{ formatDateTime($paiement->created_at) }}</span></p>
                                </div>
                            </div>

                            @if ($paiement->status == 2)
                                <form action="#" method="POST" id="rejeter_demande_produit"
                                    class="needs-validation @if ($errors->has('commentaire')) was-validated @endif"
                                    novalidate style="@if (!$errors->has('commentaire')) display: none; @endif">
                                    @csrf
                                    @method('PUT')
                                    <div class="row align-items-center">
                                        <div class="col-10">
                                            <label for="commentaire" class="form-label">Motif du rejet<span
                                                    class="text-danger fw-bold">*</span></label>
                                            <textarea name="commentaire" id="commentaire"
                                                class="form-control no-resize @error('commentaire') is-invalid @enderror" rows="4"
                                                placeholder="Entrer le motif" autocomplete="commentaire" autofocus required>{{ old('commentaire') }}</textarea>
                                            @error('commentaire')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <p><span class="text-danger fw-bold">*</span>Champs obligatoires.</p>
                                    </div>
                                    <div class="row g-3 ">
                                        <div class="col-10">
                                            <div class="mx-auto d-flex justify-content-center">

                                                <a id="btn_annuler" href=""
                                                    class="btn btn-secondary w-25 mx-2">Annuler</a>
                                                <a id="btn_valider_rejet" class="btn btn-primary w-25 mx-2">Valider</a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            @endif
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="row mb-5">
                                <h6><u>Informations Mutualiste</u></h6>
                                <img src="/{{ $paiement->mutualiste->lien_photo }}" class="img-thumbnail"
                                    style="height: 80px; width: 100px;" alt="Image du mutualiste">

                                <div class="col-lg-6 col-md-6">

                                    <h5 class="mt-4 mt-lg-0">{{ $paiement->mutualiste->nom }}
                                        {{ $paiement->mutualiste->prenom }}
                                    </h5>
                                    <p class="mb-0 text-primary fs-6 fw-bold">
                                        <i class="fa fa-envelope text-muted"></i> <a class=""
                                            href="mailto:{{ $paiement->mutualiste->user->email }}">
                                            {{ $paiement->mutualiste->user->email }}</a>
                                    </p>
                                    <p class="mb-0 text-primary fs-6 fw-bold">
                                        <i class="fa fa-phone text-muted"></i> <a
                                            href="tel:{{ $paiement->mutualiste->contact }}">
                                            {{ $paiement->mutualiste->contact }}</a>
                                    </p>

                                </div>
                                <div class="row mb-3">
                                    <h6 class="mb-3"><u>Documents</u></h6>
                                    @foreach ($documentPaiements as $imageProduit)
                                        @php
                                            $extension = strtolower(
                                                pathinfo($imageProduit->lien_photo, PATHINFO_EXTENSION),
                                            );
                                        @endphp
                                        <div class="col-lg-3 col-md-4 col-sm-6">
                                            <a href="{{ asset($imageProduit->lien_photo) }}" target="_target">
                                                @if ($extension == 'pdf')
                                                    <img src="{{ asset('assets/home/images/message/PDF_file_icon.svg.png') }}"
                                                        class="img-thumbnail" style="height: 80px; width: 100px;"
                                                        alt="Image du produit">
                                                @elseif (in_array($extension, ['jpg', 'jpeg', 'png', 'jfif', 'heic', 'heif', 'webp']))
                                                    <img src="{{ asset($imageProduit->lien_photo) }}"
                                                        class="img-thumbnail" style="height: 80px; width: 100px;"
                                                        alt="Image du produit">
                                                @else
                                                    <img src="{{ asset('assets/home/images/message/inconnu.png') }}"
                                                        class="img-thumbnail" style="height: 80px; width: 100px;"
                                                        alt="Image du produit">
                                                @endif
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
        // Initialisation des messages d'erreur
        $('#total_apayer').siblings('.form-text').text('Veuillez saisir le montant total.').addClass(
            'text-danger');
        $('#date').siblings('.form-text').text('Veuillez saisir la date.').addClass('text-danger');
        $('#commentaire_approbation').siblings('.invalid-feedback').remove();
        $('#commentaire').siblings('.invalid-feedback').remove();

        $('#btn_rejeter').click(function() {
            $('#rejeter_demande_produit').show();
            $('#accepter_demande_produit').hide();
            resetForms(); // Réinitialiser les formulaires
        });

        // $('#btn_approuver').click(function(e) {
        //     e.preventDefault(); // Empêche le comportement par défaut du lien
        //     $('#rejeter_demande_produit').hide();
        //     $('#accepter_demande_produit').show();
        //     resetForms(); // Réinitialiser les formulaires

        // });

        $('#btn_annuler').click(function(e) {
            e.preventDefault(); // Empêche le comportement par défaut du lien
            $('#rejeter_demande_produit').hide();
            $('#accepter_demande_produit').hide();
            resetForms(); // Réinitialiser les formulaires
        });

        $('#btn_valider_rejet').click(function(e) {
            e.preventDefault();
            var validationStatusCom = {
                isValidCom: true
            };

            validateCommentaireRejet(validationStatusCom)

            if (validationStatusCom.isValidCom) {
                $('#rejeterDemandeModal').modal('show');
            }
        });

        $('#btn_annuler_approbation').click(function(e) {
            e.preventDefault(); // Empêche le comportement par défaut du lien
            $('#accepter_demande_produit').hide();
            $('#rejeter_demande_produit').hide();
            resetForms(); // Réinitialiser les formulaires
        });


        $('#btn_valider_approbation').click(function(e) {
            e.preventDefault();
            var validationStatus = {
                isValid: true
            };

            validateTotalAPayer(validationStatus);
            validateDate(validationStatus);
            validateCommentaire(validationStatus);

            // if (validationStatus.isValid) {
            //     $('#approuverDemandeModal').modal('show');
            // }
        });


        // Affiche un message par défaut lorsque le champ est vide
        $('#total_apayer').siblings('.form-text').text('Veuillez saisir le montant total.').addClass(
            'text-danger');
        $('#date').siblings('.form-text').text('Veuillez saisir la date.').addClass('text-danger');

        initializeAmount();

        // Ecouteur d'événement pour vérifier le montant total à chaque saisie
        // Ecouteurs d'événements pour la saisie
        // Ecouteurs d'événements pour la saisie
        $('#total_apayer').on('input', function() {
            var validationStatus = {
                isValid: true
            };
            validateTotalAPayer(validationStatus);
        });

        $('#date').on('input', function() {
            var validationStatus = {
                isValid: true
            };
            validateDate(validationStatus);
        });

        $('#commentaire_approbation').on('input', function() {
            var validationStatus = {
                isValid: true
            };
            validateCommentaire(validationStatus);
        });


        $('#commentaire').on('input', function() {
            var validationStatusCom = {
                isValidCom: true
            };
            validateCommentaireRejet(validationStatusCom);
        });

        function validateCommentaireRejet(validationStatusCom) {
            // var validationStatusCom = {
            //     isValidCom: true
            // };

            var commentaireRejetInput = $('#commentaire');
            var commentaireRejetValue = commentaireRejetInput.val();

            commentaireRejetInput.removeClass('is-invalid is-valid');
            commentaireRejetInput.next('.invalid-feedback').remove();

            if (commentaireRejetValue.trim() === "") {
                commentaireRejetInput.addClass('is-invalid');
                commentaireRejetInput.after(
                    '<div class="invalid-feedback">Veuillez entrer le motif.</div>');
                validationStatusCom.isValidCom = false;
            } else if (!/^[a-zA-Z0-9\s'àâäçéèêëîïôöùûüÿÀÂÄÇÉÈÊËÎÏÔÖÙÛÜŸ-]+$/.test(commentaireRejetValue)) {
                commentaireRejetInput.addClass('is-invalid');
                commentaireRejetInput.after(
                    '<div class="invalid-feedback">Veuillez entrer un texte valide.</div>');
                validationStatusCom.isValidCom = false;
            } else if (commentaireRejetValue.length < 3) {
                commentaireRejetInput.addClass('is-invalid');
                commentaireRejetInput.after('<div class="invalid-feedback">Minimum 3 caractères.</div>');
                validationStatusCom.isValidCom = false;
            } else if (commentaireRejetValue.length >= 3) {
                commentaireRejetInput.addClass('is-valid');
            }
        }

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
            } else if (totalAPayer < 1000 || totalAPayer.trim() === "") {
                totalAPayerInput.addClass('is-invalid');
                totalAPayerInput.after(
                    '<div class="invalid-feedback">Le montant total doit être supérieur ou égal à 1000 FCFA.</div>'
                );
                validationStatus.isValid = false;
            } else if (totalAPayer % 100 !== 0) {
                totalAPayerInput.addClass('is-invalid');
                totalAPayerInput.after(
                    '<div class="invalid-feedback">Le montant total doit être un multiple de 100.</div>');
                validationStatus.isValid = false;
            } else {
                totalAPayerInput.addClass('is-valid');
            }
        }


        // Fonction pour valider la date
        function validateDate(validationStatus) {
            var dateInput = $('#date');
            var dateValue = dateInput.val();

            dateInput.removeClass('is-valid is-invalid');
            dateInput.next('.invalid-feedback').remove();

            if (dateValue === '') {
                dateInput.addClass('is-invalid');
                dateInput.after('<div class="invalid-feedback">Entrer la date s\'il vous plaît.</div>');
                validationStatus.isValid = false;
            } else {
                dateInput.addClass('is-valid');
            }
        }

        function validateCommentaire(validationStatus) {
            var commentaireApprobationInput = $('#commentaire_approbation');
            var commentaireValue = commentaireApprobationInput.val();

            commentaireApprobationInput.removeClass('is-invalid is-valid');
            commentaireApprobationInput.next('.invalid-feedback').remove();

            if (commentaireValue.trim() === "") {
                return; // Pas de validation nécessaire si vide
            }

            if (!/^[a-zA-Z0-9\s'àâäçéèêëîïôöùûüÿÀÂÄÇÉÈÊËÎÏÔÖÙÛÜŸ-]+$/.test(commentaireValue)) {
                commentaireApprobationInput.addClass('is-invalid');
                commentaireApprobationInput.after(
                    '<div class="invalid-feedback">Veuillez entrer un texte valide.</div>');
                validationStatus.isValid = false;
            } else if (commentaireValue.length < 3) {
                commentaireApprobationInput.addClass('is-invalid');
                commentaireApprobationInput.after(
                    '<div class="invalid-feedback">Minimum 3 caractères requis.</div>');
                validationStatus.isValid = false;
            } else {
                commentaireApprobationInput.addClass('is-valid');
            }
        };

        // Réinitialiser les formulaires
        function resetForms() {
            $('#accepter_demande_produit')[0].reset(); // Réinitialiser le formulaire d'approbation
            $('#accepter_demande_produit').find('.form-text').text(''); // Effacer les messages d'erreur

            $('#rejeter_demande_produit')[0].reset(); // Réinitialiser le formulaire de rejet
            $('#rejeter_demande_produit').find('.form-text').text(''); // Effacer les messages d'erreur

            // Réinitialiser les états de validation des champs
            $('#total_apayer').removeClass('is-valid is-invalid');
            $('#total_apayer').siblings('.form-text').text('Veuillez saisir le montant total.').removeClass(
                'text-success text-danger');

            $('#date').removeClass('is-valid is-invalid');
            $('#date').siblings('.form-text').text('Veuillez saisir la date.').removeClass(
                'text-success text-danger');

            // Autres champs à réinitialiser si nécessaire
        }

        $("#rejeter_demande").click(function(e) {
            e.preventDefault();
            var validationStatusCom = {
                isValidCom: true
            };

            validateCommentaireRejet(validationStatusCom);

            if (validationStatusCom.isValidCom) {
                $("#rejeter_demande_produit").submit();
            } else {
                $('#rejeterDemandeModal').modal('hide');
            }
        });

        // $("#valider_demande").click(function(e) {
        //     e.preventDefault();
        //     var validationStatus = {
        //         isValid: true
        //     };

        validateTotalAPayer(validationStatus);
        validateDate(validationStatus);
        validateCommentaire(validationStatus);

        // if (validationStatus.isValid) {
        //     $("#accepter_demande_produit").submit();
        // } else {
        //     $('#approuverDemandeModal').modal('hide');
        // }
        });

        });
    </script>
@endpush
