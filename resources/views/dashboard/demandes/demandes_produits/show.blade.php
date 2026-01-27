@extends('layouts.dashboard', ['title' => 'Infos demande - projet', 'toolbar' => '_toolbar2', 'breadcrumb' => 'Infos-Demande-Projet'])

@push('css')
@endpush

@section('content')
    <div class="row g-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Informations sur la demande</h6>
                    <div class="dropdown morphing scale-left">
                        <a href="#" class="card-fullscreen" data-bs-toggle="tooltip" title="Card Full-Screen"><i
                                class="icon-size-fullscreen"></i></a>
                        <a href="{{ route('demandeproduits.index') }}" class="btn btn-primary d-inline">Retour</a>
                    </div>
                </div>
                <div class="card-body" id="show_all_produits">
                    <div class="row mb-5">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="row mb-3">
                                <h6><u>Informations produit</u></h6>

                                <img src="/{{ $demandeproduit->produitProjet->lien_photo }}" class="img-thumbnail"
                                    style="height: 80px; width: 100px;" alt="Image du produit">

                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <h4 class="mt-4 mt-lg-0"><strong>{{ $demandeproduit->produitProjet->libelle }}</strong>
                                    </h4>
                                    <p class="mb-0 text-primary fs-6 fw-bold">
                                        <span
                                            class="fs-6 text-muted fw-light  me-2">Coût</span>{{ formatMontant($demandeproduit->produitProjet->cout) }}
                                    </p>
                                    <p class="mb-0 text-primary fs-6 fw-bold">
                                        <span class="fs-6 text-muted fw-light  me-2">Contribution</span> <span
                                            class="fs-6 fw-normal">{{ formatMontant($demandeproduit->produitProjet->contribution) }}
                                            / Mois</span>
                                    </p>
                                </div>
                            </div>
                            <div class="row align-items-center mb-3">

                                <div class="col-lg-4 col-md-4 col-sm-12 pt-2">
                                    @if ($demandeproduit->status == 1)
                                        <p><span class="fs-5">État : </span><u><span
                                                    class="text-success fs-5 fw-bold">Approuvée</span></u></p>
                                    @endif

                                    @if ($demandeproduit->status == 2)
                                        <p><span class="fs-5">État : </span><u><span class="text-primary fs-5 fw-bold">En
                                                    attente</span></u></p>
                                    @endif

                                    @if ($demandeproduit->status == 3)
                                        <p><span class="fs-5">État : </span><u><span
                                                    class="text-danger fs-5 fw-bold">Rejetée</span></u></p>
                                    @endif
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-8">
                                    <div class="d-flex">
                                        @if ($demandeproduit->status == 2)
                                            <a id="btn_rejeter" class="btn btn-outline-danger mx-2 fw-bold"><i
                                                    class="fa fa-close me-1"></i>
                                                Rejetée</a>
                                            <a id="btn_approuver" class="btn btn-outline-success mx-2 fw-bold"><i
                                                    class="fa fa-check me-1"></i> Approuvée</a>
                                        @endif
                                    </div>
                                </div>

                                {{-- MODAL DE VALIDATION DE DEMANDE DE SOUSCRIPTION POUR PRODUIT --}}
                                <!-- Modal approuverDemandeModal-->
                                @if ($demandeproduit->status == 2)
                                    <div class="modal fade flip" id="approuverDemandeModal" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-body p-5 text-center">
                                                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                                                        colors="primary:#405189,secondary:#f06548"
                                                        style="width:90px;height:90px">
                                                    </lord-icon>
                                                    <div class="mt-4 text-center">
                                                        <h4>Vous êtes sur le point d'approuvée <br>une demande de
                                                            souscription ?
                                                        </h4>
                                                        <p class="text-muted fs-15 mb-4">
                                                            Voulez-vous, poursuivre cette opération
                                                        </p>
                                                        <div class="hstack gap-2 justify-content-center remove">
                                                            <button
                                                                class="btn btn-link link-success fw-medium text-decoration-none"
                                                                id="AnnulerRecord-close" data-bs-dismiss="modal"><i
                                                                    class="ri-close-line me-1 align-middle"></i>
                                                                Annuler</button>

                                                            <button type="submit" class="btn btn-danger"
                                                                id="valider_demande">Oui</button>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end modal -->

                                    <div class="modal fade flip" id="rejeterDemandeModal" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-body p-5 text-center">
                                                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                                                        colors="primary:#405189,secondary:#f06548"
                                                        style="width:90px;height:90px">
                                                    </lord-icon>
                                                    <div class="mt-4 text-center">
                                                        <h4>Vous êtes sur le point de rejetée <br>une demande de
                                                            souscription ?
                                                        </h4>
                                                        <p class="text-muted fs-15 mb-4">
                                                            Voulez-vous, poursuivre cette opération
                                                        </p>
                                                        <div class="hstack gap-2 justify-content-center remove">
                                                            <button
                                                                class="btn btn-link link-success fw-medium text-decoration-none"
                                                                id="AnnulerRecord-close" data-bs-dismiss="modal"><i
                                                                    class="ri-close-line me-1 align-middle"></i>
                                                                Annuler</button>

                                                            <button type="submit" class="btn btn-danger"
                                                                id="rejeter_demande">Oui</button>

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
                                            class="fw-bold">{{ formatDateTime($demandeproduit->created_at) }}</span></p>
                                </div>
                                <div class="col-6">

                                </div>
                            </div>
                            @if ($demandeproduit->status == 3)
                                <div class="row">
                                    <h6><u>Motif du rejet :</u></h6>
                                    <div class="col-12">
                                        <p class="fs-6">{{ $demandeproduit->commentaire }}</p>
                                    </div>
                                </div>
                            @elseif ($demandeproduit->status == 1 && !is_null($demandeproduit->commentaire))
                            <div class="row">
                                <h6><u>Commentaire :</u></h6>
                                <div class="col-12">
                                    <p class="fs-6">{{ $demandeproduit->commentaire }}</p>
                                </div>
                            </div>
                            @endif
                            @if ($demandeproduit->status == 2)
                                <form action="{{ route('demandeproduit.rejeter', $demandeproduit->id) }}" method="POST"
                                    id="rejeter_demande_produit"
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

                                <form action="{{ route('demandeproduit.approuver', $demandeproduit->id) }}"
                                    method="POST" id="accepter_demande_produit"
                                    class="needs-validation @if ($errors->has('commentaire_approbation')) was-validated @endif"
                                    novalidate style="@if (!$errors->has('commentaire_approbation')) display: none; @endif">
                                    @csrf
                                    @method('PUT')

                                    <div class="row mb-3">
                                        <div class="col-lg-6 col-md-6 mb-4">
                                            <div class="form-floating">
                                                <input type="number" id="total_apayer" name="total_apayer"
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
                                        <div class="col-lg-6 col-md-6 mb-3">
                                            <div class="form-floating">
                                                <input type="date" id="date" name="date"
                                                    class="form-control @error('date') is-invalid @enderror"
                                                    value="{{ old('date') }}" placeholder="Date" autocomplete="date"
                                                    autofocus required>
                                                <label>Date affaire<span class="text-danger fw-bold">*</span></label>
                                                @error('date')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row align-items-center">

                                        <div class="col-lg-12 col-md-12">
                                            <label for="commentaire_approbation" class="form-label">Commentaire<span
                                                    class="text-danger fw-bold"></span></label>
                                            <textarea name="commentaire_approbation" id="commentaire_approbation"
                                                class="form-control no-resize @error('commentaire_approbation') is-invalid @enderror" rows="4"
                                                placeholder="Entrer commentaire" autocomplete="commentaire_approbation" autofocus>{{ old('commentaire_approbation') }}</textarea>
                                            @error('commentaire_approbation')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <p><span class="text-danger fw-bold">*</span>Champs obligatoires.</p>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-10">
                                            <div class="mx-auto d-flex justify-content-center">
                                                <a id="btn_annuler_approbation" href=""
                                                    class="btn btn-secondary w-25 mx-2">Annuler</a>
                                                <a id="btn_valider_approbation"
                                                    class="btn btn-outline-success mx-2 fw-bold"><i
                                                        class="fa fa-check me-1"></i> Valider</a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            @endif
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="row mb-5">
                                <h6><u>Informations Mutualiste</u></h6>
                                <img src="/{{ $demandeproduit->mutualiste->lien_photo }}" class="img-thumbnail"
                                    style="height: 80px; width: 100px;" alt="Image du mutualiste">

                                <div class="col-lg-6 col-md-6">

                                    <h5 class="mt-4 mt-lg-0">{{ $demandeproduit->mutualiste->nom }}
                                        {{ $demandeproduit->mutualiste->prenom }}
                                    </h5>
                                    <p class="mb-0 text-primary fs-6 fw-bold">
                                        <i class="fa fa-envelope text-muted"></i> <a class=""
                                            href="mailto:{{ $demandeproduit->mutualiste->user->email }}">
                                            {{ $demandeproduit->mutualiste->user->email }}</a>
                                    </p>
                                    <p class="mb-0 text-primary fs-6 fw-bold">
                                        <i class="fa fa-phone text-muted"></i> <a
                                            href="tel:{{ $demandeproduit->mutualiste->contact }}">
                                            {{ $demandeproduit->mutualiste->contact }}</a>
                                    </p>

                                </div>
                            </div>
                            <div class="row mb-3">
                                <h6 class="mb-3"><u>Documents</u></h6>
                                @if ($demandeproduit->documentProduitMutualistes->isNotEmpty())
                                    <div class="col-lg-12 col-md-12">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th scope="col">N°</th>
                                                    <th scope="col">Document</th>
                                                    <th scope="col">Fichier</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($demandeproduit->documentProduitMutualistes as $index => $documentProduitMutualiste)
                                                    <tr>
                                                        <th scope="row">{{ $index + 1 }}</th>
                                                        <td>{{ $documentProduitMutualiste->typeDocument->libelle }}</td>
                                                        <td>
                                                            <a href="/{{ $documentProduitMutualiste->lien_document }}"
                                                                class="link-underline-danger fw-bold" target="_blank">
                                                                Voir <i class="fa fa-paste text-primary"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p>Aucun document associé</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <h6 class="mb-3"><u>Description</u></h6>
                        <div class="col-lg-8 col-md-8 col-sm-6">
                            {!! $demandeproduit->produitProjet->description !!}
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="row gap-2">
                                @php
                                    $imageProduits = $demandeproduit->produitProjet->imageProjets;
                                @endphp

                                @forelse ($imageProduits as $imageProduit)
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <img src="/{{ $imageProduit->lien_image }}" class="img-thumbnail"
                                            style="height: 80px; width: 100px;" alt="Image du produit">
                                    </div>
                                @empty
                                    {{-- <p class="text-muted">Aucune image associée a ce produit</p> --}}
                                @endforelse
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

            $('#btn_approuver').click(function(e) {
                e.preventDefault(); // Empêche le comportement par défaut du lien
                $('#rejeter_demande_produit').hide();
                $('#accepter_demande_produit').show();
                resetForms(); // Réinitialiser les formulaires

            });

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

                if (validationStatus.isValid) {
                    $('#approuverDemandeModal').modal('show');
                }
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

            $("#valider_demande").click(function(e) {
                e.preventDefault();
                var validationStatus = {
                    isValid: true
                };

                validateTotalAPayer(validationStatus);
                validateDate(validationStatus);
                validateCommentaire(validationStatus);

                if (validationStatus.isValid) {
                    $("#accepter_demande_produit").submit();
                } else {
                    $('#approuverDemandeModal').modal('hide');
                }
            });

        });
    </script>
@endpush
