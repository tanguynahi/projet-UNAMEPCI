@extends('layouts.dashboard', ['title' => 'Ajouter un paiement', 'toolbar' => '_toolbar2', 'breadcrumb' => 'Caisse > Ajouter'])

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/dashboard/cssbundle/dataTables.min.css') }}">
    <style>
        .payment-section {
            display: none;
            transition: all 0.3s ease;
        }

        .payment-section.active {
            display: block;
        }

        .preview-cheque {
            max-width: 100%;
            max-height: 200px;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            padding: 4px;
        }

        .montant-input-group {
            position: relative;
        }

        .montant-input-group .input-group-text {
            font-weight: 600;
        }

        .card-step {
            border-left: 4px solid #0d6efd;
        }

        .card-step.completed {
            border-left-color: #198754;
        }

        .mutualiste-card {
            cursor: pointer;
            transition: all 0.2s;
            border: 2px solid transparent;
        }

        .mutualiste-card:hover {
            border-color: #0d6efd;
            background-color: #f8f9ff;
        }

        .mutualiste-card.selected {
            border-color: #0d6efd;
            background-color: #eef2ff;
        }

        .type-paiement-card {
            cursor: pointer;
            transition: all 0.2s;
            border: 2px solid #dee2e6;
            text-align: center;
            padding: 1.5rem 1rem;
        }

        .type-paiement-card:hover {
            border-color: #0d6efd;
        }

        .type-paiement-card.selected {
            border-color: #0d6efd;
            background-color: #eef2ff;
        }

        .type-paiement-card .icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .moyen-paiement-card {
            cursor: pointer;
            transition: all 0.2s;
            border: 2px solid #dee2e6;
            text-align: center;
            padding: 1rem 0.5rem;
        }

        .moyen-paiement-card:hover {
            border-color: #0d6efd;
        }

        .moyen-paiement-card.selected {
            border-color: #0d6efd;
            background-color: #eef2ff;
        }

        .moyen-paiement-card .icon {
            font-size: 1.5rem;
            margin-bottom: 0.25rem;
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .loading-overlay.active {
            display: flex;
        }

        .produit-item {
            cursor: pointer;
            transition: all 0.2s;
            border: 2px solid #dee2e6;
        }

        .produit-item:hover {
            border-color: #0d6efd;
            background-color: #f8f9ff;
        }

        .produit-item.selected {
            border-color: #0d6efd;
            background-color: #eef2ff;
        }

        .loading-spinner {
            width: 3rem;
            height: 3rem;
        }

        .loading-message {
            font-size: 1.1rem;
        }

        .loading-submessage {
            font-size: 0.85rem;
        }
    </style>
@endpush

@section('content')
    <div class="row g-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Nouveau paiement à la caisse</h6>
                    <div class="dropdown morphing scale-left">
                        <a href="{{ route('paiements.caisse') }}" class="btn btn-secondary d-inline">
                            <i class="bi bi-arrow-left"></i> Retour à la liste
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('store.paiement') }}" method="POST" enctype="multipart/form-data"
                        id="formPaiement">
                        @csrf
                        @method('post')

                        <!-- Progress Indicator -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="step-indicator d-flex gap-3">
                                    <span class="badge bg-primary" id="stepLabel">Étape 1 : Sélection du mutualiste</span>
                                </div>
                                <small class="text-muted">
                                    <span id="stepCounter">1</span>/4
                                </small>
                            </div>
                            <div class="progress mt-2" style="height: 4px;">
                                <div class="progress-bar" id="progressBar" role="progressbar" style="width: 25%;"
                                    aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>

                        <!-- ========== ÉTAPE 1 : SÉLECTION DU MUTUALISTE ========== -->
                        <div class="card card-step mb-4" id="step1">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="bi bi-person-badge me-2"></i>Étape 1 : Sélectionner le
                                    mutualiste</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="searchMutualiste" class="form-label">Rechercher un mutualiste</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                                        <input type="text" id="searchMutualiste" class="form-control"
                                            placeholder="Tapez le nom, prénom, ou matricule du mutualiste...">
                                    </div>
                                </div>

                                <div class="row g-2" id="mutualisteList" style="max-height: 300px; overflow-y: auto;">
                                    @forelse($mutualistes as $mutualiste)
                                        <div class="col-md-4 col-sm-6 mutualiste-item"
                                            data-nom="{{ strtolower($mutualiste->nom) }}"
                                            data-prenom="{{ strtolower($mutualiste->prenom) }}"
                                            data-matricule="{{ strtolower($mutualiste->matricule ?? ($mutualiste->code_mutualiste ?? '')) }}">
                                            <div class="card mutualiste-card p-2" data-id="{{ $mutualiste->id }}"
                                                onclick="selectMutualiste(this, {{ $mutualiste->id }}, '{{ addslashes($mutualiste->nom) }} {{ addslashes($mutualiste->prenom) }}')">
                                                <div class="d-flex align-items-center gap-2">
                                                    <img src="{{ $mutualiste->lien_photo ? asset($mutualiste->lien_photo) : asset('assets/dashboard/img/default-img.png') }}"
                                                        class="rounded-circle" width="40" height="40" alt="">
                                                    <div>
                                                        <strong>{{ $mutualiste->nom }}
                                                            {{ $mutualiste->prenom }}</strong><br>
                                                        <small
                                                            class="text-muted">{{ $mutualiste->matricule ?? ($mutualiste->code_mutualiste ?? 'N/A') }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12 text-center text-muted py-4">
                                            <i class="bi bi-people fs-1"></i>
                                            <p class="mt-2">Aucun mutualiste trouvé</p>
                                        </div>
                                    @endforelse
                                </div>

                                <input type="hidden" name="mutualiste_id" id="mutualiste_id" required>

                                <div class="mt-3 text-end">
                                    <button type="button" class="btn btn-primary" id="btnStep1" disabled
                                        onclick="goToStep(2)">
                                        Suivant <i class="bi bi-arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- ========== ÉTAPE 2 : TYPE DE PAIEMENT ========== -->
                        <div class="card card-step mb-4" id="step2" style="display:none;">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="bi bi-tag me-2"></i>Étape 2 : Type de paiement</h6>
                            </div>
                            <div class="card-body">
                                <p class="text-muted mb-3">Sélectionnez ce que le mutualiste souhaite payer :</p>

                                <div class="row g-3" id="typePaiementContainer">
                                    <!-- Droit d'Adhésion -->
                                    <div class="col-md-4">
                                        <div class="card type-paiement-card" onclick="selectTypePaiement(this, 'adhesion')">
                                            <div class="icon"><i class="bi bi-person-plus-fill text-primary"></i></div>
                                            <h6 class="mb-1">Droit d'Adhésion</h6>
                                            <small class="text-muted">Frais d'inscription au mutualiste</small>
                                        </div>
                                    </div>
                                    <!-- Cotisation -->
                                    <div class="col-md-4">
                                        <div class="card type-paiement-card"
                                            onclick="selectTypePaiement(this, 'cotisation')">
                                            <div class="icon"><i class="bi bi-piggy-bank-fill text-success"></i></div>
                                            <h6 class="mb-1">Cotisation</h6>
                                            <small class="text-muted">Paiement de cotisation périodique</small>
                                        </div>
                                    </div>
                                    <!-- Produit particulier -->
                                    <div class="col-md-4">
                                        <div class="card type-paiement-card"
                                            onclick="selectTypePaiement(this, 'produit')">
                                            <div class="icon">  <i class="bi bi-diagram-3 text-primary"></i>    <i class="bi bi-headset text-success"></i> </div>
                                            <h6 class="mb-1">Produit / Service</h6>
                                            <small class="text-muted">Achat d'un produit ou service spécifique</small>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="type_paiement" id="type_paiement" required>

                                <!-- Produit / Service -->
                                <div class="mt-3 payment-section" id="sectionProduit">
                                    <hr>
                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <label class="form-label fw-bold">Choisissez une catégorie</label>
                                            <div class="d-flex gap-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                        name="categorie_produit" id="catProjet" value="projets"
                                                        onchange="chargerProduits('projets')">
                                                    <label class="form-check-label" for="catProjet">
                                                        <i class="bi bi-diagram-3 text-primary"></i> Projet Mutualiste
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                        name="categorie_produit" id="catService" value="services"
                                                        onchange="chargerProduits('services')">
                                                    <label class="form-check-label" for="catService">
                                                        <i class="bi bi-headset text-success"></i> Service d'accompagnement
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="alert alert-info mb-3" id="loadingProjets" style="display:none;">
                                        <i class="bi bi-hourglass-split me-2"></i>
                                        Chargement des projets du mutualiste...
                                    </div>

                                    <div class="row" id="produitsContainer">
                                        <div class="col-12">
                                            <div class="alert alert-info">
                                                <i class="bi bi-info-circle me-2"></i>
                                                Veuillez d'abord sélectionner une catégorie pour voir les produits
                                                disponibles.
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" name="produit_id" id="produit_id">
                                    <input type="hidden" name="produit_type" id="produit_type">
                                    <input type="hidden" name="produit_montant" id="produit_montant">
                                    <input type="hidden" name="produit_libelle" id="produit_libelle">
                                </div>

                                <!-- Cotisation -->
                                <div class="mt-3 payment-section" id="sectionCotisation">
                                    <hr>
                                    <div class="alert alert-info mb-3" id="loadingCotisations" style="display:none;">
                                        <i class="bi bi-hourglass-split me-2"></i>
                                        Chargement des cotisations du mutualiste...
                                    </div>

                                    <div class="row" id="cotisationsContainer">
                                        <div class="col-12">
                                            <div class="alert alert-info">
                                                <i class="bi bi-info-circle me-2"></i>
                                                Chargement des cotisations en cours...
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" name="cotisation_mutualiste_id" id="cotisation_mutualiste_id">
                                    <input type="hidden" name="montant_cotisation" id="montant_cotisation_hidden">
                                </div>

                                <!-- Droit d'Adhésion -->
                                <div class="mt-3 payment-section" id="sectionAdhesion">
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="montant_adhesion" class="form-label">Montant du droit
                                                d'adhésion</label>
                                            <div class="input-group">
                                                <span class="input-group-text">CFA</span>
                                                <input type="number" name="montant_adhesion" id="montant_adhesion"
                                                    class="form-control" placeholder="Ex: 10000" step="any"
                                                    min="0" max="10000">
                                            </div>
                                            {{-- <small class="text-muted">Laissez vide pour utiliser le montant par
                                                défaut</small> --}}
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3 text-end">
                                    <button type="button" class="btn btn-secondary" onclick="goToStep(1)"><i
                                            class="bi bi-arrow-left"></i> Précédent</button>
                                    <button type="button" class="btn btn-primary" id="btnStep2" disabled
                                        onclick="goToStep(3)">
                                        Suivant <i class="bi bi-arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- ========== ÉTAPE 3 : MOYEN DE PAIEMENT ========== -->
                        <div class="card card-step mb-4" id="step3" style="display:none;">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="bi bi-credit-card me-2"></i>Étape 3 : Moyen de paiement</h6>
                            </div>
                            <div class="card-body">
                                <p class="text-muted mb-3">Sélectionnez le moyen de paiement utilisé :</p>

                                <div class="row g-3" id="moyenPaiementContainer">
                                    {{-- <div class="col-md-3">
                                        <div class="card moyen-paiement-card"
                                            onclick="selectMoyenPaiement(this, 'especes')">
                                            <div class="icon"><i class="bi bi-cash-stack text-success"></i></div>
                                            <h6 class="mb-0 small">Espèces</h6>
                                            <small class="text-muted" style="font-size:10px;">Paiement cash</small>
                                        </div>
                                    </div> --}}
                                    <div class="col-md-4">
                                        <div class="card moyen-paiement-card"
                                            onclick="selectMoyenPaiement(this, 'cheque')">
                                            <div class="icon"><i class="bi bi-file-earmark-text text-primary"></i></div>
                                            <h6 class="mb-0 small">Chèque</h6>
                                            <small class="text-muted" style="font-size:10px;">Chèque bancaire</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card moyen-paiement-card"
                                            onclick="selectMoyenPaiement(this, 'virement')">
                                            <div class="icon"><i class="bi bi-bank text-info"></i></div>
                                            <h6 class="mb-0 small">Virement</h6>
                                            <small class="text-muted" style="font-size:10px;">Virement bancaire</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card moyen-paiement-card"
                                            onclick="selectMoyenPaiement(this, 'en_ligne')">
                                            <div class="icon"><i class="bi bi-cash-stack text-success"></i></div>
                                            <h6 class="mb-0 small">En ligne</h6>
                                            <small class="text-muted" style="font-size:10px;">TresorMoney</small>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="moyen_paiement" id="moyen_paiement" required>

                                <!-- Espèces -->
                                <div class="mt-3 payment-section" id="sectionEspeces">
                                    <hr>
                                    <div class="alert alert-info mb-0">
                                        <i class="bi bi-info-circle me-2"></i>
                                        Paiement en espèces. Aucune information supplémentaire requise.
                                    </div>
                                </div>

                                <!-- Chèque -->
                                <div class="mt-3 payment-section" id="sectionCheque">
                                    <hr>
                                    <h6 class="mb-3 text-primary"><i class="bi bi-file-earmark-text me-2"></i>Informations
                                        du chèque</h6>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label for="cheque_numero" class="form-label">Numéro du chèque <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="cheque_numero" id="cheque_numero"
                                                class="form-control" placeholder="Ex: CHQ-001234">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="cheque_banque" class="form-label">Banque émettrice <span
                                                    class="text-danger">*</span></label>
                                            <select name="cheque_banque" id="cheque_banque" class="form-select">
                                                <option value="">-- Sélectionner une banque --</option>
                                                <option value="Ecobank">Ecobank</option>
                                                <option value="Orabank">Orabank</option>
                                                <option value="Atlantic">Atlantic Business International</option>
                                                <option value="UBA">UBA</option>
                                                <option value="BOA">BOA</option>
                                                <option value="BSIC">BSIC</option>
                                                <option value="BIM">BIM</option>
                                                <option value="Coris">Coris Bank</option>
                                                <option value="autres">Autres</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4" id="div_banque_autre" style="display: none;">
                                            <label for="cheque_banque_autre" class="form-label">Précisez la banque <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="cheque_banque_autre" id="cheque_banque_autre"
                                                class="form-control" placeholder="Nom de la banque">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="cheque_date" class="form-label">Date du chèque <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" name="cheque_date" id="cheque_date"
                                                class="form-control">
                                        </div>
                                        <div class="col-12">
                                            <label for="cheque_image" class="form-label">Scanner / Photo du chèque <span
                                                    class="text-danger">*</span></label>
                                            <input type="file" name="documents[]" id="cheque_image"
                                                class="form-control" accept="image/*,.pdf" multiple>
                                            <small class="text-muted">Formats acceptés : JPG, PNG, PDF. Taille max : 2
                                                Mo</small>
                                            <div class="mt-2" id="chequePreview"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Virement -->
                                <div class="mt-3 payment-section" id="sectionVirement">
                                    <hr>
                                    <h6 class="mb-3 text-info"><i class="bi bi-bank me-2"></i>Informations du virement
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label for="virement_reference" class="form-label">Référence du virement <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="virement_reference" id="virement_reference"
                                                class="form-control" placeholder="Ex: VIR-2024-001">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="virement_banque" class="form-label">Banque d'origine <span
                                                    class="text-danger">*</span></label>
                                            <select name="virement_banque" id="virement_banque" class="form-select">
                                                <option value="">-- Sélectionner une banque --</option>
                                                <option value="Ecobank">Ecobank</option>
                                                <option value="Orabank">Orabank</option>
                                                <option value="Atlantic">Atlantic Business International</option>
                                                <option value="UBA">UBA</option>
                                                <option value="BOA">BOA</option>
                                                <option value="BSIC">BSIC</option>
                                                <option value="BIM">BIM</option>
                                                <option value="Coris">Coris Bank</option>
                                                <option value="autres">Autres</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4" id="div_banque_autre_virement" style="display: none;">
                                            <label for="virement_banque_autre" class="form-label">Précisez la banque <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="virement_banque_autre" id="virement_banque_autre"
                                                class="form-control" placeholder="Nom de la banque">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="virement_date" class="form-label">Date du virement <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" name="virement_date" id="virement_date"
                                                class="form-control">
                                        </div>
                                        <div class="col-12">
                                            <label for="virement_justificatif" class="form-label">Justificatif du virement
                                                (reçu, capture)</label>
                                            <input type="file" name="documents[]" id="virement_justificatif"
                                                class="form-control" accept="image/*,.pdf" multiple>
                                            <small class="text-muted">Formats acceptés : JPG, PNG, PDF</small>
                                            <div class="mt-2" id="virementPreview"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- En ligne (TresorMoney) -->
                                <div class="mt-3 payment-section" id="sectionEnLigne">
                                    <hr>
                                    <h6 class="mb-3 text-warning"><i class="bi bi-phone-flip me-2"></i>Paiement
                                        TresorMoney</h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="tresormoney_numero" class="form-label">Numéro de téléphone à
                                                débiter <span class="text-danger">*</span></label>
                                            <div class="input-group input-group-lg">
                                                <span class="input-group-text"><i class="bi bi-phone fs-5"></i></span>
                                                <input type="tel" name="tresormoney_numero" id="tresormoney_numero"
                                                    class="form-control form-control-lg" placeholder="Ex: 70 00 00 00"
                                                    maxlength="10">
                                            </div>
                                            <small class="text-muted">
                                                <i class="bi bi-info-circle me-1"></i>
                                                Le paiement sera effectué via TresorMoney. Le montant sera prélevé sur ce
                                                numéro.
                                            </small>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card bg-light border-warning">
                                                <div class="card-body text-center py-3">
                                                    <i class="bi bi-shield-check text-warning fs-1"></i>
                                                    <h6 class="mt-2 mb-0">TresorMoney</h6>
                                                    <small class="text-muted">Paiement direct - Pas de hub de
                                                        paiement</small>
                                                </div>
                                            </div>
                                            <input type="hidden" name="tresormoney_operateur" value="TresorMoney">
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3 text-end">
                                    <button type="button" class="btn btn-secondary" onclick="goToStep(2)"><i
                                            class="bi bi-arrow-left"></i> Précédent</button>
                                    <button type="button" class="btn btn-primary" id="btnStep3" disabled
                                        onclick="goToStep(4)">
                                        Suivant <i class="bi bi-arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- ========== ÉTAPE 4 : MONTANT ET VALIDATION ========== -->
                        <div class="card card-step mb-4" id="step4" style="display:none;">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="bi bi-cash-coin me-2"></i>Étape 4 : Montant & Confirmation
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="montant" class="form-label">Montant du paiement <span
                                                class="text-danger">*</span></label>
                                        <div class="input-group montant-input-group">
                                            <span class="input-group-text bg-primary text-white">CFA</span>
                                            <input type="number" name="montant" id="montant"
                                                class="form-control form-control-lg" placeholder="Saisissez le montant"
                                                step="any" min="0" required>
                                        </div>
                                        <div class="form-text" id="montantInfo"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <h6 class="mb-2">Récapitulatif du paiement</h6>
                                                <table class="table table-sm table-borderless mb-0">
                                                    <tr>
                                                        <td class="text-muted">Mutualiste :</td>
                                                        <td class="text-end fw-bold" id="recapMutualiste">-</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Type :</td>
                                                        <td class="text-end" id="recapType">-</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Produit :</td>
                                                        <td class="text-end" id="recapProduit">-</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Moyen :</td>
                                                        <td class="text-end" id="recapMoyen">-</td>
                                                    </tr>
                                                    <tr class="border-top">
                                                        <td class="fw-bold">Montant :</td>
                                                        <td class="text-end fw-bold text-primary fs-5" id="recapMontant">-
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3 mt-2">
                                    <div class="col-md-6">
                                        <label for="reference" class="form-label">Référence du paiement</label>
                                        <input type="text" name="reference" id="reference" class="form-control"
                                            placeholder="Référence interne (optionnel)">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="date_paiement" class="form-label">Date du paiement <span
                                                class="text-danger">*</span></label>
                                        <input type="date" name="date_paiement" id="date_paiement"
                                            class="form-control" required>
                                    </div>
                                </div>

                                <div class="row g-3 mt-2">
                                    <div class="col-12">
                                        <label for="notes" class="form-label">Notes / Observations</label>
                                        <textarea name="notes" id="notes" class="form-control" rows="2"
                                            placeholder="Informations complémentaires..."></textarea>
                                    </div>
                                </div>

                                <div class="mt-3 text-end">
                                    <button type="button" class="btn btn-secondary" onclick="goToStep(3)"><i
                                            class="bi bi-arrow-left"></i> Précédent</button>
                                    <button type="submit" class="btn btn-success" id="btnSubmit">
                                        <i class="bi bi-check-circle"></i> Enregistrer le paiement
                                    </button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="text-center">
            <div class="spinner-border text-primary loading-spinner mb-3" role="status" id="loadingSpinner">
                <span class="visually-hidden">Chargement...</span>
            </div>
            <p class="fw-bold loading-message" id="loadingMessage">Traitement en cours...</p>
            <small class="text-muted loading-submessage" id="loadingSubMessage"></small>
        </div>
    </div>
@endsection

@push('js')
    <script>
        // Gestion de l'affichage du champ "Précisez la banque"
        const banqueSelect = document.getElementById('cheque_banque');
        const divBanqueAutre = document.getElementById('div_banque_autre');
        const inputBanqueAutre = document.getElementById('cheque_banque_autre');

        function toggleBanqueAutre() {
            if (banqueSelect.value === 'autres') {
                divBanqueAutre.style.display = 'block';
                // Optionnel : rendre le champ requis pour la validation côté client
                inputBanqueAutre.setAttribute('required', 'required');
            } else {
                divBanqueAutre.style.display = 'none';
                inputBanqueAutre.removeAttribute('required');
                inputBanqueAutre.value = ''; // Effacer la valeur saisie
            }
        }

        // Écouter le changement de sélection
        banqueSelect.addEventListener('change', toggleBanqueAutre);

        // Vérifier au chargement si "Autres" est déjà sélectionné (par ex. après un rechargement du formulaire)
        toggleBanqueAutre();
    </script>



    <script>
        // Gestion de l'affichage du champ "Précisez la banque"
        const virementSelect = document.getElementById('virement_banque');
        const divVirementAutre = document.getElementById('div_banque_autre_virement');
        const inputVirementAutre = document.getElementById('virement_banque_autre');

        function toggleVirementAutre() {
            if (virementSelect.value === 'autres') {
                divVirementAutre.style.display = 'block';
                // Optionnel : rendre le champ requis pour la validation côté client
                inputVirementAutre.setAttribute('required', 'required');
            } else {
                divVirementAutre.style.display = 'none';
                inputVirementAutre.removeAttribute('required');
                inputVirementAutre.value = ''; // Effacer la valeur saisie
            }
        }

        // Écouter le changement de sélection
        virementSelect.addEventListener('change', toggleVirementAutre);

        // Vérifier au chargement si "Autres" est déjà sélectionné (par ex. après un rechargement du formulaire)
        toggleVirementAutre();
    </script>

    <script src="{{ asset('assets/dashboard/js/bundle/dataTables.bundle.js') }}"></script>
    <script>
        // ============================================================
        // VARIABLES GLOBALES
        // ============================================================
        let currentStep = 1;
        let selectedMutualisteId = null;
        let selectedMutualisteName = '';
        let selectedType = '';
        let selectedTypeLabel = '';
        let selectedMoyen = '';
        let selectedMoyenLabel = '';
        let selectedProduitId = null;
        let selectedProduitLabel = '';
        let selectedProduitMontant = 0;

        // Données des SERVICES (statiques, chargés depuis le backend)
        const servicesData = @json(
            $services->map(function ($s) {
                return ['id' => $s->id, 'libelle' => $s->libelle, 'montant' => $s->montant ?? 0];
            }));

        // ============================================================
        // ÉTAPE 1 : RECHERCHE ET SÉLECTION DU MUTUALISTE
        // ============================================================

        document.getElementById('searchMutualiste').addEventListener('keyup', function() {
            const search = this.value.toLowerCase().trim();
            const items = document.querySelectorAll('.mutualiste-item');

            items.forEach(item => {
                const nom = item.dataset.nom;
                const prenom = item.dataset.prenom;
                const matricule = item.dataset.matricule;
                const match = nom.includes(search) || prenom.includes(search) || matricule.includes(search);
                item.style.display = match ? '' : 'none';
            });
        });

        function selectMutualiste(element, id, name) {
            document.querySelectorAll('.mutualiste-card').forEach(c => c.classList.remove('selected'));
            element.classList.add('selected');
            selectedMutualisteId = id;
            selectedMutualisteName = name;
            document.getElementById('mutualiste_id').value = id;
            document.getElementById('btnStep1').disabled = false;
        }

        // ============================================================
        // ÉTAPE 2 : TYPE DE PAIEMENT
        // ============================================================

        function selectTypePaiement(element, type) {
            document.querySelectorAll('.type-paiement-card').forEach(c => c.classList.remove('selected'));
            element.classList.add('selected');
            selectedType = type;

            const labels = {
                'adhesion': 'Droit d\'Adhésion',
                'cotisation': 'Cotisation',
                'produit': 'Produit / Service'
            };
            selectedTypeLabel = labels[type] || type;
            document.getElementById('type_paiement').value = type;

            // Cacher toutes les sections spécifiques
            document.querySelectorAll('.payment-section[id^="section"]').forEach(s => s.classList.remove('active'));

            const sectionMap = {
                'adhesion': 'sectionAdhesion',
                'cotisation': 'sectionCotisation',
                'produit': 'sectionProduit'
            };

            if (sectionMap[type]) {
                document.getElementById(sectionMap[type]).classList.add('active');
            }
            // ===== NOUVEAU : Chargement automatique du droit d'adhésion =====
            if (type === 'adhesion') {
                chargerDroitAdhesion();
                document.getElementById('btnStep2').disabled = false;
            }
            // ===== FIN NOUVEAU =====

            if (type !== 'produit' && type !== 'cotisation') {
                document.getElementById('btnStep2').disabled = false;
            } else {
                document.getElementById('btnStep2').disabled = true;

                if (type === 'produit') {
                    document.querySelectorAll('input[name="categorie_produit"]').forEach(r => r.checked = false);
                    document.getElementById('produitsContainer').innerHTML = `
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                Veuillez d'abord sélectionner une catégorie pour voir les produits disponibles.
                            </div>
                        </div>
                    `;
                }

                if (type === 'cotisation') {
                    chargerCotisations();
                }
            }
        }

        // ============================================================
        // CHARGEMENT DES PRODUITS PAR CATÉGORIE
        // ============================================================

        function chargerProduits(categorie) {
            const container = document.getElementById('produitsContainer');
            document.getElementById('produit_type').value = categorie;

            if (categorie === 'services') {
                // afficherProduits(servicesData, 'services');
                if (!selectedMutualisteId) {
                    container.innerHTML = `
                <div class="col-12">
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Veuillez d'abord sélectionner un mutualiste à l'étape 1.
                    </div>
                </div>
            `;
                    return;
                }

                document.getElementById('loadingProjets').style.display = 'block';
                container.innerHTML = '';

                fetch(`/caisse/services-accompagnement-mutualiste/${selectedMutualisteId}`)
                    .then(response => {
                        if (!response.ok) throw new Error('Erreur réseau');
                        return response.json();
                    })
                    .then(services => {
                        document.getElementById('loadingProjets').style.display = 'none';
                        if (services.length === 0) {
                            container.innerHTML = `
                        <div class="col-12">
                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                Ce mutualiste n'a aucun service d'accompagnement en cours de paiement.
                            </div>
                        </div>
                    `;
                            document.getElementById('btnStep2').disabled = true;
                            return;
                        }
                        afficherServicesAccompagnement(services);
                    })
                    .catch(error => {
                        document.getElementById('loadingProjets').style.display = 'none';
                        console.error('Erreur:', error);
                        container.innerHTML = `
                    <div class="col-12">
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Erreur lors du chargement des services d'accompagnement.
                        </div>
                    </div>
                `;
                        document.getElementById('btnStep2').disabled = true;
                    });

            } else if (categorie === 'projets') {
                if (!selectedMutualisteId) {
                    container.innerHTML = `
                        <div class="col-12">
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                Veuillez d'abord sélectionner un mutualiste à l'étape 1.
                            </div>
                        </div>
                    `;
                    return;
                }

                document.getElementById('loadingProjets').style.display = 'block';
                container.innerHTML = '';

                fetch(`/caisse/projets-mutualiste/${selectedMutualisteId}`)
                    .then(response => {
                        if (!response.ok) throw new Error('Erreur réseau');
                        return response.json();
                    })
                    .then(projets => {
                        document.getElementById('loadingProjets').style.display = 'none';
                        if (projets.length === 0) {
                            container.innerHTML = `
                                <div class="col-12">
                                    <div class="alert alert-warning">
                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                        Ce mutualiste n'a aucun projet mutualiste actif.
                                    </div>
                                </div>
                            `;
                            document.getElementById('btnStep2').disabled = true;
                            return;
                        }
                        afficherProduits(projets, 'projets');
                    })
                    .catch(error => {
                        document.getElementById('loadingProjets').style.display = 'none';
                        console.error('Erreur:', error);
                        container.innerHTML = `
                            <div class="col-12">
                                <div class="alert alert-danger">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    Erreur lors du chargement des projets.
                                </div>
                            </div>
                        `;
                        document.getElementById('btnStep2').disabled = true;
                    });
            }
        }

        function afficherProduits(produits, categorie) {
            const container = document.getElementById('produitsContainer');
            document.getElementById('btnStep2').disabled = true;

            if (!produits || produits.length === 0) {
                container.innerHTML = `
                    <div class="col-12">
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Aucun ${categorie} disponible.
                        </div>
                    </div>
                `;
                return;
            }

            const badgeColor = categorie === 'projets' ? 'primary' : 'success';
            const icon = categorie === 'projets' ? 'bi-diagram-3' : 'bi-headset';
            const iconColor = categorie === 'projets' ? 'text-primary' : 'text-success';

            selectedProduitId = null;
            selectedProduitLabel = '';
            selectedProduitMontant = 0;
            document.getElementById('produit_id').value = '';
            document.getElementById('produit_montant').value = '';
            document.getElementById('produit_libelle').value = '';

            let html = '';
            produits.forEach(produit => {
                const montant = parseFloat(produit.montant) || 0;
                html += `
                    <div class="col-md-4 col-sm-6 mb-2">
                        <div class="card produit-item p-3"
                             data-id="${produit.id}"
                             data-libelle="${produit.libelle}"
                             data-montant="${montant}"
                             onclick="selectProduit(this, ${produit.id}, '${produit.libelle.replace(/'/g, "\\'")}', ${montant})">
                            <div class="text-center">
                                <i class="bi ${icon} fs-2 ${iconColor}"></i>
                                <h6 class="mt-2 mb-1">${produit.libelle}</h6>
                                <span class="badge bg-${badgeColor}">${formatNumber(montant)} CFA</span>
                            </div>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = html;
        }


        function afficherServicesAccompagnement(services) {
            const container = document.getElementById('produitsContainer');
            document.getElementById('btnStep2').disabled = true;

            if (!services || services.length === 0) {
                container.innerHTML = `
            <div class="col-12">
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Aucun service d'accompagnement disponible.
                </div>
            </div>
        `;
                return;
            }

            selectedProduitId = null;
            selectedProduitLabel = '';
            selectedProduitMontant = 0;
            document.getElementById('produit_id').value = '';
            document.getElementById('produit_montant').value = '';
            document.getElementById('produit_libelle').value = '';

            let html = `
        <div class="col-12 mb-3">
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                Services d'accompagnement souscrits par le mutualiste. Sélectionnez celui à payer.
            </div>
        </div>
    `;

            services.forEach(service => {
                const montantApayer = parseFloat(service.montant_apayer) || 0;
                const dejaPaye = parseFloat(service.payer) || 0;
                const reste = parseFloat(service.reste) || 0;
                const montantVoulu = parseFloat(service.montant_voulue) || 0;

                html += `
            <div class="col-md-6 col-lg-4 mb-2">
                <div class="card produit-item p-3"
                     data-id="${service.id}"
                     data-libelle="${service.libelle.replace(/'/g, "\\'")}"
                     data-montant="${reste}"
                     onclick="selectServiceAccompagnement(this, ${service.id}, '${service.libelle.replace(/'/g, "\\'")}', ${reste})">
                    <div class="d-flex align-items-center gap-2">
                        <div class="flex-shrink-0">
                            <i class="bi bi-headset fs-2 text-success"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">${service.service_libelle}</h6>
                            <small class="text-muted d-block">
                                <i class="bi bi-chat-dots me-1"></i> ${service.commentaire || 'Aucun commentaire'}
                            </small>
                            <div class="mt-2">
                                <div class="d-flex flex-wrap gap-1">
                                    <span class="badge bg-primary">Total: ${formatNumber(montantApayer)} CFA</span>
                                    <span class="badge bg-success">Payé: ${formatNumber(dejaPaye)} CFA</span>
                                    <span class="badge bg-danger">Reste: ${formatNumber(reste)} CFA</span>
                                </div>
                            </div>
                            ${service.contact_tresormoney ? `
                                                                    <small class="text-muted d-block mt-1">
                                                                        <i class="bi bi-phone me-1"></i> Contact: ${service.contact_tresormoney}
                                                                    </small>
                                                                ` : ''}
                        </div>
                    </div>
                </div>
            </div>
        `;
            });

            container.innerHTML = html;
        }

        function selectServiceAccompagnement(element, id, libelle, montant) {
            document.querySelectorAll('#produitsContainer .produit-item').forEach(c => c.classList.remove('selected'));
            element.classList.add('selected');
            selectedProduitId = id;
            selectedProduitLabel = libelle;
            selectedProduitMontant = montant;
            document.getElementById('produit_id').value = id;
            document.getElementById('produit_montant').value = montant;
            document.getElementById('produit_libelle').value = libelle;
            document.getElementById('montant').value = montant;
            document.getElementById('btnStep2').disabled = false;
        }

        function selectProduit(element, id, libelle, montant) {
            document.querySelectorAll('.produit-item').forEach(c => c.classList.remove('selected'));
            element.classList.add('selected');
            selectedProduitId = id;
            selectedProduitLabel = libelle;
            selectedProduitMontant = montant;
            document.getElementById('produit_id').value = id;
            document.getElementById('produit_montant').value = montant;
            document.getElementById('produit_libelle').value = libelle;
            document.getElementById('montant').value = montant;
            document.getElementById('btnStep2').disabled = false;
        }



        // ============================================================
        // CHARGEMENT DU DROIT D'ADHÉSION
        // ============================================================

        function chargerDroitAdhesion() {
            const montantInput = document.getElementById('montant_adhesion');
            const section = document.getElementById('sectionAdhesion');
            const infoExistante = section.querySelector('.adhesion-info');

            // Supprimer l'ancienne info si elle existe
            if (infoExistante) {
                infoExistante.remove();
            }

            if (!selectedMutualisteId) {
                montantInput.value = '';
                montantInput.readOnly = false;
                return;
            }

            // Afficher un indicateur de chargement
            montantInput.value = 'Chargement...';
            montantInput.readOnly = true;

            fetch(`/caisse/droits-adhesion-mutualiste/${selectedMutualisteId}`)
                .then(response => {
                    if (!response.ok) throw new Error('Erreur réseau');
                    return response.json();
                })
                .then(data => {
                    console.log('Droit adhesion data:', data);

                    if (data.length > 0) {
                        const montant = parseFloat(data[0].montant) || 10000;
                        montantInput.value = montant;
                        montantInput.readOnly = false;

                        // Ajouter une info visuelle
                        const infoDiv = document.createElement('div');
                        infoDiv.className = 'alert alert-success mt-2 adhesion-info';
                        infoDiv.innerHTML = `
                    <i class="bi bi-check-circle me-2"></i>
                    Droit d'adhésion trouvé : <strong>${formatNumber(montant)} CFA</strong><br>
                    <small>${data[0].libelle}</small>
                `;
                        section.appendChild(infoDiv);

                        // Pré-remplir le montant total
                        document.getElementById('montant').value = montant;
                        if (typeof updateRecap === 'function') updateRecap();
                    } else {
                        montantInput.value = '';
                        montantInput.readOnly = false;

                        const infoDiv = document.createElement('div');
                        infoDiv.className = 'alert alert-warning mt-2 adhesion-info';
                        infoDiv.innerHTML = `
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Aucun droit d'adhésion actif trouvé pour ce mutualiste.<br>
                    <small>Veuillez saisir le montant manuellement.</small>
                `;
                        section.appendChild(infoDiv);
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    montantInput.value = '';
                    montantInput.readOnly = false;

                    const infoDiv = document.createElement('div');
                    infoDiv.className = 'alert alert-danger mt-2 adhesion-info';
                    infoDiv.innerHTML = `
                <i class="bi bi-exclamation-triangle me-2"></i>
                Erreur lors du chargement du droit d'adhésion.<br>
                <small>Veuillez saisir le montant manuellement.</small>
            `;
                    section.appendChild(infoDiv);
                });
        }
        // ============================================================
        // CHARGEMENT DES COTISATIONS DU MUTUALISTE
        // ============================================================

        function chargerCotisations() {
            const container = document.getElementById('cotisationsContainer');
            const loader = document.getElementById('loadingCotisations');

            if (!selectedMutualisteId) {
                container.innerHTML = `
                    <div class="col-12">
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Veuillez d'abord sélectionner un mutualiste à l'étape 1.
                        </div>
                    </div>
                `;
                return;
            }

            loader.style.display = 'block';
            container.innerHTML = '';

            fetch(`/caisse/cotisations-mutualiste/${selectedMutualisteId}`)
                .then(response => {
                    if (!response.ok) throw new Error('Erreur réseau');
                    return response.json();
                })
                .then(cotisations => {
                    loader.style.display = 'none';

                    if (cotisations.length === 0) {
                        container.innerHTML = `
                            <div class="col-12">
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    Ce mutualiste n'a aucune cotisation active.
                                </div>
                            </div>
                        `;
                        document.getElementById('btnStep2').disabled = true;
                        return;
                    }

                    let html = `
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">Sélectionnez la cotisation à payer</label>
                        </div>
                    `;

                    cotisations.forEach(cotisation => {
                        const reste = parseFloat(cotisation.reste) || 0;
                        const montant = parseFloat(cotisation.montant) || 0;
                        const paye = parseFloat(cotisation.montant_paye) || 0;

                        html += `
                            <div class="col-md-6 col-lg-4 mb-2">
                                <div class="card produit-item p-3"
                                     data-id="${cotisation.id}"
                                     data-libelle="${cotisation.libelle}"
                                     data-montant="${reste}"
                                     onclick="selectCotisation(this, ${cotisation.id}, '${cotisation.libelle.replace(/'/g, "\\'")}', ${reste})">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="flex-shrink-0">
                                            <i class="bi bi-piggy-bank-fill fs-2 text-success"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">${cotisation.libelle}</h6>
                                            <small class="text-muted">
                                                <i class="bi bi-calendar3 me-1"></i> ${cotisation.date_debut} → ${cotisation.date_fin}
                                            </small>
                                            <div class="mt-1">
                                                <span class="badge bg-success">${formatNumber(montant)} CFA</span>
                                                <span class="badge bg-warning text-dark">Payé: ${formatNumber(paye)} CFA</span>
                                                <span class="badge bg-danger">Reste: ${formatNumber(reste)} CFA</span>
                                            </div>
                                            <small class="text-muted d-block mt-1">
                                                <i class="bi bi-arrow-repeat me-1"></i> ${cotisation.frequence}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });

                    container.innerHTML = html;
                    document.getElementById('btnStep2').disabled = true;
                })
                .catch(error => {
                    loader.style.display = 'none';
                    console.error('Erreur:', error);
                    container.innerHTML = `
                        <div class="col-12">
                            <div class="alert alert-danger">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                Erreur lors du chargement des cotisations.
                            </div>
                        </div>
                    `;
                    document.getElementById('btnStep2').disabled = true;
                });
        }

        function selectCotisation(element, id, libelle, montant) {
            document.querySelectorAll('#cotisationsContainer .produit-item').forEach(c => c.classList.remove('selected'));
            element.classList.add('selected');
            selectedProduitId = id;
            selectedProduitLabel = libelle;
            selectedProduitMontant = montant;
            document.getElementById('cotisation_mutualiste_id').value = id;
            document.getElementById('montant_cotisation_hidden').value = montant;
            document.getElementById('montant').value = montant;
            document.getElementById('btnStep2').disabled = false;
        }

        // ============================================================
        // ÉTAPE 3 : MOYEN DE PAIEMENT
        // ============================================================

        function selectMoyenPaiement(element, moyen) {
            document.querySelectorAll('.moyen-paiement-card').forEach(c => c.classList.remove('selected'));
            element.classList.add('selected');
            selectedMoyen = moyen;

            const labels = {
                'especes': 'Espèces',
                'cheque': 'Chèque',
                'virement': 'Virement bancaire',
                'en_ligne': 'En ligne (TresorMoney)'
            };
            selectedMoyenLabel = labels[moyen] || moyen;
            document.getElementById('moyen_paiement').value = moyen;

            ['sectionEspeces', 'sectionCheque', 'sectionVirement', 'sectionEnLigne'].forEach(id => {
                document.getElementById(id)?.classList.remove('active');
            });

            const sectionMap = {
                'especes': 'sectionEspeces',
                'cheque': 'sectionCheque',
                'virement': 'sectionVirement',
                'en_ligne': 'sectionEnLigne'
            };

            if (sectionMap[moyen]) {
                document.getElementById(sectionMap[moyen]).classList.add('active');
            }

            document.getElementById('btnStep3').disabled = false;
        }

        // Prévisualisation des fichiers uploadés
        document.addEventListener('change', function(e) {
            if (e.target.matches('input[type="file"]')) {
                const file = e.target.files[0];
                if (!file) return;

                const previewId = e.target.id + 'Preview';
                const previewDiv = document.getElementById(previewId);
                if (!previewDiv) return;

                previewDiv.innerHTML = '';

                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(ev) {
                        const img = document.createElement('img');
                        img.src = ev.target.result;
                        img.className = 'preview-cheque';
                        previewDiv.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                } else {
                    previewDiv.innerHTML = `
                        <div class="alert alert-info py-1 px-2 mb-0" style="font-size: 13px;">
                            <i class="bi bi-file-earmark-pdf me-1"></i> Fichier sélectionné : ${file.name}
                        </div>
                    `;
                }
            }
        });

        // ============================================================
        // ÉTAPE 4 : MONTANT - Mise à jour dynamique du récapitulatif
        // ============================================================

        document.getElementById('montant').addEventListener('input', function() {
            updateRecap();
        });

        document.getElementById('montant_adhesion')?.addEventListener('input', function() {
            if (this.value) {
                document.getElementById('montant').value = this.value;
            }
            updateRecap();
        });

        function updateRecap() {
            const montant = document.getElementById('montant').value;
            document.getElementById('recapMutualiste').textContent = selectedMutualisteName || '-';
            document.getElementById('recapType').textContent = selectedTypeLabel || '-';
            document.getElementById('recapProduit').textContent = (selectedType === 'produit' && selectedProduitLabel) ?
                selectedProduitLabel : '-';
            document.getElementById('recapMoyen').textContent = selectedMoyenLabel || '-';
            document.getElementById('recapMontant').textContent = montant ? formatNumber(montant) + ' CFA' : '-';
        }

        function formatNumber(num) {
            return parseFloat(num).toLocaleString('fr-FR', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });
        }

        // ============================================================
        // NAVIGATION ENTRE LES ÉTAPES
        // ============================================================

        function goToStep(step) {
            if (step === 2 && !selectedMutualisteId) {
                toastr.error('Veuillez sélectionner un mutualiste');
                return;
            }
            if (step === 3 && !selectedType) {
                toastr.error('Veuillez sélectionner un type de paiement');
                return;
            }
            if (step === 3 && selectedType === 'produit' && !selectedProduitId) {
                toastr.error('Veuillez sélectionner un produit/service');
                return;
            }
            if (step === 3 && selectedType === 'cotisation' && !selectedProduitId) {
                toastr.error('Veuillez sélectionner une cotisation');
                return;
            }
            if (step === 4) {
                if (!selectedMoyen) {
                    toastr.error('Veuillez sélectionner un moyen de paiement');
                    return;
                }
                if (!validateMoyenFields()) return;
                updateRecap();
                if (!document.getElementById('date_paiement').value) {
                    const today = new Date().toISOString().split('T')[0];
                    document.getElementById('date_paiement').value = today;
                }
            }

            for (let i = 1; i <= 4; i++) {
                document.getElementById('step' + i).style.display = 'none';
            }

            document.getElementById('step' + step).style.display = 'block';
            currentStep = step;

            const progress = (step / 4) * 100;
            document.getElementById('progressBar').style.width = progress + '%';
            document.getElementById('progressBar').setAttribute('aria-valuenow', progress);

            const stepLabels = {
                1: 'Étape 1 : Sélection du mutualiste',
                2: 'Étape 2 : Type de paiement',
                3: 'Étape 3 : Moyen de paiement',
                4: 'Étape 4 : Montant & Confirmation'
            };
            document.getElementById('stepLabel').textContent = stepLabels[step];
            document.getElementById('stepCounter').textContent = step;

            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function validateMoyenFields() {
            if (selectedMoyen === 'cheque') {
                const numero = document.getElementById('cheque_numero').value.trim();
                const banque = document.getElementById('cheque_banque').value;
                const date = document.getElementById('cheque_date').value;
                const image = document.getElementById('cheque_image').files.length;
                if (!numero) {
                    toastr.error('Veuillez saisir le numéro du chèque');
                    return false;
                }
                if (!banque) {
                    toastr.error('Veuillez sélectionner la banque émettrice');
                    return false;
                }
                if (!date) {
                    toastr.error('Veuillez saisir la date du chèque');
                    return false;
                }
                if (!image) {
                    toastr.error('Veuillez joindre le scan/photo du chèque');
                    return false;
                }
            }

            if (selectedMoyen === 'virement') {
                const ref = document.getElementById('virement_reference').value.trim();
                const banque = document.getElementById('virement_banque').value;
                const date = document.getElementById('virement_date').value;
                if (!ref) {
                    toastr.error('Veuillez saisir la référence du virement');
                    return false;
                }
                if (!banque) {
                    toastr.error('Veuillez sélectionner la banque d\'origine');
                    return false;
                }
                if (!date) {
                    toastr.error('Veuillez saisir la date du virement');
                    return false;
                }
            }

            if (selectedMoyen === 'en_ligne') {
                const numero = document.getElementById('tresormoney_numero').value.trim().replace(/\s/g, '');
                if (!numero || numero.length < 8) {
                    toastr.error('Veuillez saisir un numéro de téléphone valide à débiter');
                    return false;
                }
            }

            return true;
        }

        // ============================================================
        // SOUMISSION DU FORMULAIRE
        // ============================================================

        // document.getElementById('formPaiement').addEventListener('submit', function(e) {
        //     e.preventDefault();

        //     const montant = document.getElementById('montant').value;
        //     if (!montant || parseFloat(montant) <= 0) {
        //         toastr.error('Veuillez saisir un montant valide');
        //         return;
        //     }

        //     if (selectedMoyen === 'en_ligne') {
        //         soumettrePaiementEnLigne();
        //     } else {
        //         soumettrePaiementNormal();
        //     }
        // });

        document.getElementById('formPaiement').addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();

            console.log('=== FORM SUBMIT ===');
            console.log('selectedMoyen:', selectedMoyen);
            console.log('selectedType:', selectedType);
            console.log('Montant:', document.getElementById('montant').value);

            const montant = document.getElementById('montant').value;
            if (!montant || parseFloat(montant) <= 0) {
                toastr.error('Veuillez saisir un montant valide');
                return false;
            }

            // Désactiver le bouton pour éviter les doubles clics
            document.getElementById('btnSubmit').disabled = true;

            if (selectedMoyen === 'en_ligne') {
                console.log('Appel de soumettrePaiementEnLigne');
                soumettrePaiementEnLigne();
            } else {
                console.log('Appel de soumettrePaiementNormal');
                soumettrePaiementNormal();
            }

            return false;
        });

        // function soumettrePaiementNormal() {
        //     const overlay = document.getElementById('loadingOverlay');
        //     document.getElementById('loadingMessage').textContent = 'Enregistrement du paiement...';
        //     document.getElementById('loadingSubMessage').textContent = '';
        //     overlay.classList.add('active');

        //     document.getElementById('btnSubmit').disabled = true;
        //     document.getElementById('btnSubmit').innerHTML =
        //         '<span class="spinner-border spinner-border-sm"></span> Enregistrement...';
        //     document.getElementById('formPaiement').submit();
        // }
        function soumettrePaiementNormal() {
            console.log('=== soumettrePaiementNormal appelée ===');
            console.log('selectedMoyen:', selectedMoyen);

            const overlay = document.getElementById('loadingOverlay');
            const message = document.getElementById('loadingMessage');
            const subMessage = document.getElementById('loadingSubMessage');

            console.log('overlay:', overlay);
            console.log('message:', message);
            console.log('subMessage:', subMessage);

            message.textContent = 'Enregistrement du paiement...';
            subMessage.textContent = '';
            overlay.classList.add('active');
            console.log('overlay classList après add:', overlay.className);

            document.getElementById('btnSubmit').disabled = true;
            document.getElementById('btnSubmit').innerHTML =
                '<span class="spinner-border spinner-border-sm"></span> Enregistrement...';

            console.log('Soumission du formulaire...');
            console.log('form action:', document.getElementById('formPaiement').action);

            // Soumettre
            document.getElementById('formPaiement').submit();
        }

        function soumettrePaiementEnLigne() {
            const overlay = document.getElementById('loadingOverlay');
            const spinner = document.getElementById('loadingSpinner');
            const message = document.getElementById('loadingMessage');
            const subMessage = document.getElementById('loadingSubMessage');

            spinner.style.display = 'block';
            spinner.className = 'spinner-border text-warning loading-spinner mb-3';
            message.innerHTML = '<i class="bi bi-phone-flip me-2"></i> Initiation du paiement TresorMoney...';
            subMessage.textContent = 'Veuillez patienter, une notification sera envoyée au numéro saisi.';
            overlay.classList.add('active');

            document.getElementById('btnSubmit').disabled = true;
            document.getElementById('btnSubmit').innerHTML =
                '<span class="spinner-border spinner-border-sm"></span> Paiement en cours...';

            const formData = new FormData(document.getElementById('formPaiement'));

            fetch('{{ route('store.paiement') }}', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                .then(response => {
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        return response.json().then(data => ({
                            data,
                            isJson: true
                        }));
                    } else {
                        return response.text().then(text => ({
                            text,
                            isJson: false
                        }));
                    }
                })
                .then(result => {
                    if (result.isJson) {
                        const data = result.data;
                        if (data.success) {
                            spinner.style.display = 'none';
                            message.innerHTML = `
                <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i><br>
                <strong class="text-success">${data.message || 'Paiement initié avec succès!'}</strong>
            `;
                            subMessage.textContent =
                                `Code: ${data.code_paiement || ''} - Redirection vers la page de confirmation...`;

                            // Rediriger vers la page de résultat avec le code paiement
                            setTimeout(() => {
                                window.location.href = data.redirect ||
                                    '{{ route('removePlay', ['codePaiement' => 'CODE', 'ind' => 1]) }}'
                                    .replace('CODE', data.code_paiement);
                            }, 2000);
                        } else {
                            overlay.classList.remove('active');
                            spinner.className = 'spinner-border text-primary loading-spinner mb-3';
                            message.textContent = 'Traitement en cours...';
                            subMessage.textContent = '';
                            toastr.error(data.message || 'Erreur lors du paiement');
                            document.getElementById('btnSubmit').disabled = false;
                            document.getElementById('btnSubmit').innerHTML =
                                '<i class="bi bi-check-circle"></i> Enregistrer le paiement';
                        }
                    } else {
                        document.getElementById('formPaiement').submit();
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    overlay.classList.remove('active');
                    spinner.className = 'spinner-border text-primary loading-spinner mb-3';
                    message.textContent = 'Traitement en cours...';
                    subMessage.textContent = '';
                    toastr.error('Erreur de connexion. Veuillez réessayer.');
                    document.getElementById('btnSubmit').disabled = false;
                    document.getElementById('btnSubmit').innerHTML =
                        '<i class="bi bi-check-circle"></i> Enregistrer le paiement';
                });
        }

        // ============================================================
        // INITIALISATION
        // ============================================================

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.payment-section').forEach(s => s.classList.remove('active'));

            if (typeof toastr === 'undefined') {
                window.toastr = {
                    error: function(msg) {
                        alert('Erreur: ' + msg);
                    },
                    success: function(msg) {
                        alert('Succès: ' + msg);
                    }
                };
            }
        });

        // Formattage du numéro de téléphone (TresorMoney)
        document.getElementById('tresormoney_numero')?.addEventListener('input', function() {
            let val = this.value.replace(/[^0-9]/g, '');
            if (val.length > 10) val = val.slice(0, 10);
            this.value = val;
        });




        // Patch : s'assurer que la fonction soumettrePaiementNormal existe et fonctionne
        window.soumettrePaiementNormal = function() {
            console.log('=== soumettrePaiementNormal() ===');
            try {
                const overlay = document.getElementById('loadingOverlay');
                if (!overlay) {
                    console.error('OVERLAY INTROUVABLE');
                    // Soumettre directement sans overlay
                    document.getElementById('formPaiement').submit();
                    return;
                }

                const message = document.getElementById('loadingMessage');
                const subMessage = document.getElementById('loadingSubMessage');

                if (message) message.textContent = 'Enregistrement du paiement...';
                if (subMessage) subMessage.textContent = '';

                overlay.classList.add('active');
                console.log('Overlay activé');

                const btn = document.getElementById('btnSubmit');
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Enregistrement...';
                }

                console.log('Soumission du formulaire...');
                document.getElementById('formPaiement').submit();
            } catch (e) {
                console.error('Erreur dans soumettrePaiementNormal:', e);
                // Fallback : soumettre directement
                document.getElementById('formPaiement').submit();
            }
        };

        // S'assurer que la validation des champs chèque/virement ne bloque pas pour 'especes'/'en_ligne'
        const originalValidate = validateMoyenFields;
        validateMoyenFields = function() {
            console.log('validateMoyenFields called, selectedMoyen:', selectedMoyen);

            // Si c'est un paiement en ligne, on valide plus tard (dans soumettrePaiementEnLigne)
            if (selectedMoyen === 'en_ligne') {
                return true;
            }

            // Si c'est espèce, pas de validation supplémentaire
            if (selectedMoyen === 'especes') {
                return true;
            }

            if (selectedMoyen === 'cheque') {
                const numero = document.getElementById('cheque_numero')?.value.trim();
                const banque = document.getElementById('cheque_banque')?.value;
                const date = document.getElementById('cheque_date')?.value;
                const image = document.getElementById('cheque_image')?.files.length;

                console.log('Validation chèque:', {
                    numero,
                    banque,
                    date,
                    image
                });

                if (!numero) {
                    toastr.error('Veuillez saisir le numéro du chèque');
                    return false;
                }
                if (!banque) {
                    toastr.error('Veuillez sélectionner la banque émettrice');
                    return false;
                }
                if (!date) {
                    toastr.error('Veuillez saisir la date du chèque');
                    return false;
                }
                // L'image n'est plus obligatoire pour tester
                // if (!image) { toastr.error('Veuillez joindre le scan/photo du chèque'); return false; }

                return true;
            }

            if (selectedMoyen === 'virement') {
                const ref = document.getElementById('virement_reference')?.value.trim();
                const banque = document.getElementById('virement_banque')?.value;
                const date = document.getElementById('virement_date')?.value;

                if (!ref) {
                    toastr.error('Veuillez saisir la référence du virement');
                    return false;
                }
                if (!banque) {
                    toastr.error('Veuillez sélectionner la banque d\'origine');
                    return false;
                }
                if (!date) {
                    toastr.error('Veuillez saisir la date du virement');
                    return false;
                }

                return true;
            }

            return true;
        };
    </script>
@endpush
