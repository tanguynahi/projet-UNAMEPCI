@extends('layouts.home_dashboard', ['title' => 'Espace de Contribution '])
@push('css')
    <link rel="stylesheet" href="{{ asset('mutualiste/styledatatable.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">

    <style>
        /* Masquer des colonnes pour mobile */
        @media only screen and (max-width: 768px) {
            table.dataTable thead {
                display: none;
            }

            table.dataTable tbody tr {
                display: block;
                width: 100%;
                margin-bottom: 0.625em;
            }

            table.dataTable tbody tr td {
                display: flex;
                justify-content: space-between;
                text-align: left;
                padding: 8px 10px;
                border-bottom: 1px solid #ddd;
                width: 100%;
            }

            table.dataTable tbody tr td:before {
                content: attr(data-label);
                font-weight: bold;
                width: 50%;
                text-align: left;
                display: inline-block;
            }
        }

        .input-group-text {
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
        }
    </style>
@endpush
@section('content')
    <div class="col-lg-9">
        <div class="rbt-dashboard-content bg-color-white rbt-shadow-box">
            <div class="content">
                <div class="section-title">
                    <h4 class="rbt-title-style-3">Mes Cotisations</h4>
                </div>

                <div class="rbt-dashboard-filter-wrapper">
                    <div class="rbt-dashboard-table table-responsive">
                        <table id="datatable-buttons" class="rbt-table table table-borderless">
                            <thead>
                                <tr>
                                    <th>Libelle</th>
                                    <th>Montant</th>
                                    <th>Date debut</th>
                                    <th>Date Fin</th>
                                    <th class="d-none d-md-table-cell">Frequence</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- @foreach ($cotisations as $cotisationMutualiste)
                                    <tr>
                                        <th><span class="h6 mb--5">{{ $cotisationMutualiste->cotisation->libelle }}</span>
                                        </th>
                                        <td>{{ formatMontant($cotisationMutualiste->montant) }}</td>
                                        <td>{{ formatDate($cotisationMutualiste->date_debut) }}</td>
                                        <td>{{ formatDate($cotisationMutualiste->date_fin) }}</td>
                                        <td class="d-none d-md-table-cell">{{ $cotisationMutualiste->frequence_paiement }}
                                        </td>
                                        <td>
                                            @if ($cotisationMutualiste->status == 2)
                                                <span
                                                    style="color: blue; background-color: rgba(0, 0, 255, 0.3); border: 1px solid rgb(18, 18, 209); padding: 1px; border-radius: 5px;">
                                                    EN ATTENTE
                                                </span>
                                            @elseif($cotisationMutualiste->status == 1)
                                                <span
                                                    style="color: rgba(54, 143, 54, 0.615); background-color: rgba(54, 143, 54, 0.1); border: 1px solid rgba(54, 143, 54, 0.615); padding: 1px; border-radius: 5px;">
                                                    SOLDE
                                                </span>
                                            @elseif ($cotisationMutualiste->date_fin < $dateActuel)
                                                <span
                                                    style="color: rgba(243, 239, 5, 0.689); background-color: rgba(253, 244, 6, 0.311); border: 1px solid rgba(54, 143, 54, 0.615); padding: 1px; border-radius: 5px;">
                                                    TERMINER
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($cotisationMutualiste->status == 2)
                                                @if ($cotisationMutualiste->frequence_paiement == 'Annuelle')
                                                    <a class="rbg-success-opacity text-center modal-trigger"
                                                        href="javascript:void(0)" title="Paiement" data-bs-toggle="modal"
                                                        data-bs-target="#modalPaiementAnnuel"
                                                        data-cotisation-id="{{ $cotisationMutualiste->id }}"
                                                        data-montant-total="{{ $cotisationMutualiste->montant }}"
                                                        data-libelle="{{ $cotisationMutualiste->cotisation->libelle }}">
                                                        <i class="fa fa-credit-card"></i>
                                                    </a>
                                                @else
                                                    <a class="rbg-success-opacity text-center"
                                                        href="{{ route('paiementCotisation.mutualiste', $cotisationMutualiste->id) }}"
                                                        title="Paiement">
                                                        <i class="fa fa-credit-card"></i>
                                                    </a>
                                                @endif
                                            @endif
                                            @if (empty($cotisationMutualiste->administrateur_id) || $cotisationMutualiste->status == 5)
                                                <a class="rbg-success-opacity text-center mx-2"
                                                    href="{{ route('resume.cotisationMutual', $cotisationMutualiste->cotisation_id) }}"
                                                    title="detail des paiements">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23"
                                                        class="me-3" fill="currentColor" viewBox="0 0 16 16">
                                                        <path
                                                            d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z" />
                                                        <path
                                                            d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0" />
                                                    </svg>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach --}}

                                @foreach ($cotisations as $cotisationMutualiste)
                                   
                                    @php
                                        $dateActuel = \Carbon\Carbon::now(); // IMPORTANT
                                        $dateDebut = \Carbon\Carbon::parse($cotisationMutualiste->date_debut);
                                        $dateFin = \Carbon\Carbon::parse($cotisationMutualiste->date_fin);
                                    @endphp

                                    {{-- Afficher seulement les cotisations en cours --}}
                                    @if ($dateActuel->greaterThanOrEqualTo($dateDebut) && $dateActuel->lessThanOrEqualTo($dateFin))
                                        <tr>
                                            <th>
                                                <span
                                                    class="h6 mb--5">{{ $cotisationMutualiste->cotisation->libelle }}</span>
                                            </th>
                                            <td>{{ formatMontant($cotisationMutualiste->montant) }}</td>
                                            <td>{{ formatDate($cotisationMutualiste->date_debut) }}</td>
                                            <td>{{ formatDate($cotisationMutualiste->date_fin) }}</td>
                                            <td class="d-none d-md-table-cell">
                                                {{ $cotisationMutualiste->frequence_paiement }}
                                            </td>

                                            <td>

                                                @if ($dateActuel->greaterThan($dateFin))
                                                    <span class="badge bg-warning text-dark">TERMINÉE</span>
                                                @elseif ($cotisationMutualiste->status == 2)
                                                    <span class="badge bg-primary">EN ATTENTE</span>
                                                @elseif ($cotisationMutualiste->status == 1)
                                                    <span class="badge bg-success">SOLDE</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if ($cotisationMutualiste->status == 2)
                                                    <form action="{{ route('espaPay', 2) }}" method="POST"
                                                        id="paiementForm">
                                                        @csrf
                                                        @method('POST')
                                                        <input type="hidden" name="montant"
                                                            value="{{ $cotisationMutualiste->montant }}">
                                                        <input type="hidden" name="idCotisation"
                                                            value="{{ $cotisationMutualiste->id }}">
                                                        <button type="submit" class="rbg-success-opacity text-center"
                                                            title="Paiement">
                                                            <i class="fa fa-credit-card"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                                @if (empty($cotisationMutualiste->administrateur_id) || $cotisationMutualiste->status == 5)
                                                    <a class="rbg-success-opacity text-center mx-2"
                                                        href="{{ route('resume.cotisationMutual', $cotisationMutualiste->cotisation_id) }}"
                                                        title="detail des paiements">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="23"
                                                            height="23" class="me-3" fill="currentColor"
                                                            viewBox="0 0 16 16">
                                                            <path
                                                                d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z" />
                                                            <path
                                                                d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0" />
                                                        </svg>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal pour le paiement annuel -->
    {{-- <div class="modal fade" id="modalPaiementAnnuel" tabindex="-1" aria-labelledby="modalPaiementAnnuelLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPaiementAnnuelLabel">Paiement de la cotisation annuelle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formPaiementAnnuel" action="{{ route('paiementCotisationAnnuelle.mutualiste',$cotisationMutualiste->id) }}" method="POST">
                    @csrf
                    @method('POST')
                    <div class="modal-body">
                        <div class="mb-3">
                            <p class="mb-1">Cotisation : <strong id="modalLibelle"></strong></p>
                            <p class="mb-1">Montant total : <strong id="modalMontantTotal"></strong></p>
                            <p class="text-muted small">Vous pouvez verser un montant partiel ou le montant total.</p>
                        </div>

                        <div class="mb-3">
                            <label for="montantVerse" class="form-label">Montant à verser <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="montantVerse" name="montant" min="0"
                                    step="100" required placeholder="Entrez le montant">
                                <span class="input-group-text">FCFA</span>
                            </div>
                            <div class="form-text">
                                <small>Montant maximum : <span id="montantMax"></span> FCFA</small>
                            </div>
                        </div>

                        <input type="hidden" id="cotisationId" name="cotisation_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Procéder au paiement</button>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}
@endsection
@include('sweetalert::alert')

@push('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

    <script>
        $(document).ready(function() {
            console.log("Document ready - script chargé");

            // Définir la fonction de formatage du montant
            function formatMontant(montant) {
                let nombre = parseFloat(montant);
                if (isNaN(nombre)) {
                    return montant;
                }
                return nombre.toLocaleString('fr-FR');
            }

            // Gestionnaire d'événement pour l'ouverture de la modal
            $('#modalPaiementAnnuel').on('show.bs.modal', function(event) {
                console.log("Modal en cours d'ouverture");

                var button = $(event.relatedTarget);
                var cotisationId = button.attr('data-cotisation-id');
                var montantTotal = button.attr('data-montant-total');
                var libelle = button.attr('data-libelle');

                console.log('Données récupérées:', {
                    id: cotisationId,
                    montant: montantTotal,
                    libelle: libelle
                });

                var modal = $(this);

                // Mettre à jour les données de la modal
                modal.find('#modalLibelle').text(libelle);
                modal.find('#modalMontantTotal').text(formatMontant(montantTotal) + ' FCFA');
                modal.find('#montantMax').text(formatMontant(montantTotal));
                modal.find('#cotisationId').val(cotisationId);

                // Mettre à jour l'action du formulaire
                var formAction = "{{ route('paiementCotisationAnnuelle.mutualiste', ':id') }}".replace(
                    ':id',
                    cotisationId);
                modal.find('#formPaiementAnnuel').attr('action', formAction);

                // Définir le max de l'input
                modal.find('#montantVerse').attr('max', montantTotal);
                modal.find('#montantVerse').attr('placeholder', 'Maximum ' + formatMontant(montantTotal) +
                    ' FCFA');

                // Réinitialiser la valeur de l'input
                modal.find('#montantVerse').val('');
            });

            // Validation du montant saisi
            $(document).on('input', '#montantVerse', function() {
                var maxMontant = parseFloat($(this).attr('max'));
                var saisie = parseFloat($(this).val());

                if (saisie > maxMontant) {
                    $(this).val(maxMontant);
                    alert('Le montant ne peut pas dépasser ' + formatMontant(maxMontant) + ' FCFA');
                }
            });

            // Initialiser DataTable avec l'option destroy pour éviter la réinitialisation
            $('#datatable-buttons').DataTable({
                responsive: true,
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.11.5/i18n/fr-FR.json"
                },
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "Tous"]
                ],
                destroy: true // Détruit la table existante avant de créer une nouvelle
            });

            // Ajouter un événement de clic pour tester si le lien fonctionne
            $('.modal-trigger').on('click', function() {
                console.log("Lien modal cliqué", $(this).data());
            });
        });
    </script>
@endpush
