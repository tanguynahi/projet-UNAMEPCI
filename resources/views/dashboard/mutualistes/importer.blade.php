{{-- @extends('layouts.dashboard', ['title' => 'Importer des membres', 'toolbar' => '_toolbar2', 'breadcrumb' => 'Membres > Importer des membres'])

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/dashboard/cssbundle/dropify.min.css') }}">
    <style>
        .recap-card {
            background-color: #f8f9fc;
            border-left: 4px solid #0d6efd;
        }

        .duplicate-list {
            max-height: 250px;
            overflow-y: auto;
            font-size: 0.9rem;
        }

        .badge-duplicate {
            background-color: #dc3545;
            color: white;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 0.75rem;
        }

        .badge-error {
            background-color: #dc3545;
            color: white;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 0.75rem;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #333;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 0.75rem;
        }

        .example-link {
            text-decoration: none;
            font-weight: 500;
        }

        .example-link:hover {
            text-decoration: underline;
        }

        .payment-summary {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            color: white;
            border-radius: 12px;
            padding: 20px;
            margin-top: 16px;
        }

        .payment-summary .amount {
            font-size: 2rem;
            font-weight: 700;
        }

        .payment-summary small {
            opacity: 0.85;
        }

        .btn-payment {
            background: #28a745;
            border: none;
            color: white;
            font-weight: 600;
            padding: 12px 32px;
            border-radius: 50px;
            font-size: 1.05rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 14px rgba(40, 167, 69, 0.35);
        }

        .btn-payment:hover {
            background: #218838;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.45);
            color: white;
        }

        .btn-payment:disabled {
            background: #6c757d;
            box-shadow: none;
            transform: none;
            cursor: not-allowed;
            opacity: 0.65;
        }

        .payment-status-badge {
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .payment-status-badge.pending {
            background: #fff3cd;
            color: #856404;
        }

        .payment-status-badge.paid {
            background: #d4edda;
            color: #155724;
        }

        .payment-status-badge.failed {
            background: #f8d7da;
            color: #721c24;
        }

        .payment-status-badge.blocked {
            background: #f8d7da;
            color: #721c24;
        }

        .phone-input-group {
            position: relative;
        }

        .phone-input-group .phone-prefix {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-weight: 600;
            color: #0d6efd;
            z-index: 5;
        }

        .phone-input-group input {
            padding-left: 60px !important;
        }

        #add_mutualiste_btn {
            display: none;
        }

        #add_mutualiste_btn.visible {
            display: inline-block;
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            z-index: 9999;
            display: none;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .loading-overlay.active {
            display: flex;
        }

        .loading-overlay .spinner-border {
            width: 4rem;
            height: 4rem;
        }

        .loading-overlay p {
            margin-top: 16px;
            font-weight: 600;
            color: #0d6efd;
        }

        .validation-summary {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 8px;
            padding: 12px 16px;
        }

        .validation-summary.error {
            background: #f8d7da;
            border-color: #dc3545;
        }

        .validation-summary.success {
            background: #d4edda;
            border-color: #28a745;
        }

        .error-text {
            color: #dc3545;
            font-weight: 500;
        }
    </style>
@endpush

@section('content')
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner-border text-primary" role="status"></div>
        <p id="loadingMessage">Traitement en cours, veuillez patienter...</p>
    </div>

    <div class="row g-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Importer la liste des membres</h6>
                    <div class="dropdown morphing scale-left">
                        <a href="#" class="card-fullscreen" data-bs-toggle="tooltip" title="Card Full-Screen">
                            <i class="icon-size-fullscreen"></i>
                        </a>
                        <a href="{{ route('mutualistes.index') }}" class="btn btn-primary d-inline">Retour</a>
                    </div>
                </div>
                <div class="card-body" id="show_all_admin">
                    <form action="{{ route('paiement.initier') }}" method="POST" id="add_mutualiste_form"
                        enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        @method('POST')

                        <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                            <h6 class="fw-bold text-danger">Charger le fichier contenant la liste des membres *</h6>
                            <a href="#" id="downloadExampleBtn" class="example-link text-primary">
                                <i class="bi bi-file-earmark-excel"></i> Télécharger un exemple de fichier Excel
                            </a>
                        </div>

                        <div class="row g-3">
                            <div class="col-lg-12 col-md-12">
                                <div class="form-floating">
                                    <input type="file" name="fichier" id="fichier"
                                        class="form-select form-control @error('fichier') is-invalid @enderror dropify"
                                        data-height="200" accept=".xls,.xlsx" autofocus required />
                                    @error('fichier')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Zone de récapitulatif -->
                        <div id="recapArea" style="display: none;" class="mt-4">
                            <div class="card recap-card">
                                <div class="card-header bg-transparent fw-bold">
                                    <i class="bi bi-info-circle-fill"></i> Récapitulatif du fichier
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <div class="border rounded p-2 text-center">
                                                <span class="text-muted">Nombre total de lignes</span>
                                                <h4 class="mb-0" id="totalLines">0</h4>
                                                <small>(hors en-tête)</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="border rounded p-2 text-center">
                                                <span class="text-muted">Membres uniques</span>
                                                <h4 class="mb-0" id="uniqueCount">0</h4>
                                                <small>(après dédoublonnage)</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="border rounded p-2 text-center">
                                                <span class="text-muted">Montant total à payer</span>
                                                <h4 class="mb-0 text-success" id="totalAmount">0 F CFA</h4>
                                                <small>(10 000 FCFA par membre unique)</small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ========== NOUVEAU : Messages de validation ========== -->
                                    <div id="validationMessages" class="mt-3" style="display: none;">
                                        <div id="validationContent"></div>
                                    </div>

                                    <div class="mt-3">
                                        <div class="fw-bold">
                                            🔁 Conflits détectés dans le fichier :
                                            <span id="conflictsBadge" class="badge bg-secondary ms-2">0</span>
                                        </div>
                                        <div id="duplicatesList" class="duplicate-list mt-2">
                                            <span class="text-muted">Aucun conflit détecté.</span>
                                        </div>
                                    </div>

                                    <!-- Section paiement -->
                                    <div id="paymentFields" style="display: none;" class="mt-3">
                                        <hr>


                                        <input type="hidden" name="montant" id="input_montant" value="0">
                                        <input type="hidden" name="nombre_membres" id="input_nombre_membres"
                                            value="0">
                                        <input type="hidden" name="description" id="input_description" value="">
                                        <input type="hidden" name="type_paiement" value="adhesion">

                                        <div
                                            class="payment-summary d-flex justify-content-between align-items-center flex-wrap">
                                            <div>
                                                <span class="fw-semibold">💳 Paiement des frais d'adhésion</span>
                                                <div class="amount mt-1" id="paymentAmountDisplay">0 F CFA</div>
                                                <small>· 10 000 FCFA/membre</small>
                                            </div>
                                            <div class="mt-3 mt-md-0 d-flex align-items-center gap-3">
                                                <span id="paymentStatusBadge" class="payment-status-badge pending">
                                                    ⏳ En attente
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Message blocage si conflits -->
                                        <div id="blockMessage" style="display: none;" class="mt-3 alert alert-danger">
                                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                            <strong>Paiement bloqué :</strong> Veuillez corriger les conflits ci-dessus
                                            avant de procéder au paiement.
                                        </div>

                                        <!-- Numéro TresorMoney -->
                                        <div class="mt-3">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold">📱 Numéro TresorMoney <span
                                                            class="text-danger">*</span></label>
                                                    <div class="phone-input-group">
                                                        <span class="phone-prefix">+225</span>
                                                        <input type="tel" name="tresormoney_numero"
                                                            id="tresormoney_numero" class="form-control form-control-lg"
                                                            placeholder="07 00 00 00 00" pattern="[0-9]{10}"
                                                            maxlength="10" required>
                                                    </div>
                                                    <small class="text-muted">Numéro à 10 chiffres (ex: 0701020304)</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <p><span class="text-danger fw-bold">*</span> Champs obligatoires.</p>
                        </div>
                        <div class="row g-3">
                            <div class="mx-auto d-flex justify-content-center gap-3">
                                <button type="reset" class="btn btn-secondary w-25">Annuler</button>
                                <button type="submit" id="payNowBtn" class="btn btn-success w-25"
                                    style="display: none;" disabled>
                                    <i class="bi bi-credit-card-2-front me-1"></i> Payer maintenant
                                </button>
                                <a href="{{ route('mutualistes.index') }}" id="goToListBtn" class="btn btn-primary"
                                    style="display: none;">
                                    <i class="bi bi-arrow-left me-1"></i> Retour à la liste
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/dashboard/js/bundle/dropify.bundle.js') }}"></script>
    <script src="https://cdn.sheetjs.com/xlsx-0.20.2/package/dist/xlsx.full.min.js"></script>

    <script>
        $(function() {
            $('.dropify').dropify({
                messages: {
                    'default': 'Faites glisser un fichier ici ou cliquez ici',
                    'replace': 'Faites glisser et déposer ou cliquez pour remplacer',
                    'remove': 'Supprimer',
                    'error': 'Ooops, quelque chose a mal tourné.'
                },
                error: {
                    'fileSize': 'La taille du fichier est trop grande.',
                    'imageFormat': 'Le format du fichier n\'est pas autorisé (.xls,.xlsx) seulement).'
                },
            });
        });

        // ---- Variables ----
        let paymentData = {
            totalLines: 0,
            uniqueCount: 0,
            totalAmount: 0
        };
        let formSubmitted = false;
        let hasConflicts = false;

        // ---- Fonctions d'affichage ----
        function showLoading(message) {
            document.getElementById('loadingMessage').textContent = message || 'Traitement en cours, veuillez patienter...';
            document.getElementById('loadingOverlay').classList.add('active');
        }

        function hideLoading() {
            document.getElementById('loadingOverlay').classList.remove('active');
        }

        /**
         * Analyse les conflits dans le fichier :
         * - Doublons de matricules
         * - Doublons de contacts
         * - Doublons d'emails
         */
        function analyzeConflicts(rows, headers) {
            const colIdx = {
                matricule: headers.indexOf('numero_matricule'),
                noms: headers.indexOf('noms'),
                prenoms: headers.indexOf('prenoms'),
                contact: headers.indexOf('contact'),
                email: headers.indexOf('email')
            };

            const conflicts = {
                matriculeDuplicates: new Map(), // matricule -> [{fullName, rowIndex}]
                contactDuplicates: new Map(), // contact -> [{fullName, rowIndex}]
                emailDuplicates: new Map(), // email -> [{fullName, rowIndex}]
                fullNameMap: new Map() // rowIndex -> fullName
            };

            const matriculeSeen = new Map();
            const contactSeen = new Map();
            const emailSeen = new Map();

            for (let i = 1; i < rows.length; i++) {
                const row = rows[i];
                if (!row || row.length === 0) continue;

                const matricule = colIdx.matricule !== -1 ? (row[colIdx.matricule] || "").toString().trim() : "";
                const noms = colIdx.noms !== -1 ? (row[colIdx.noms] || "").toString().trim() : "";
                const prenoms = colIdx.prenoms !== -1 ? (row[colIdx.prenoms] || "").toString().trim() : "";
                const contact = colIdx.contact !== -1 ? (row[colIdx.contact] || "").toString().trim() : "";
                const email = colIdx.email !== -1 ? (row[colIdx.email] || "").toString().trim() : "";

                if (matricule === "") continue;

                const fullName = `${noms} ${prenoms}`.trim() || "Nom inconnu";
                conflicts.fullNameMap.set(i, fullName);
                const entry = {
                    fullName,
                    rowIndex: i,
                    contact,
                    email
                };

                // Vérifier matricule
                if (matriculeSeen.has(matricule)) {
                    if (!conflicts.matriculeDuplicates.has(matricule)) {
                        // Première occurrence (celle déjà vue)
                        const firstIndex = matriculeSeen.get(matricule);
                        const firstName = conflicts.fullNameMap.get(firstIndex) || "Nom inconnu";
                        conflicts.matriculeDuplicates.set(matricule, [{
                                fullName: firstName,
                                rowIndex: firstIndex,
                                contact: row[colIdx.contact] || '',
                                email: row[colIdx.email] || ''
                            },
                            entry
                        ]);
                    } else {
                        conflicts.matriculeDuplicates.get(matricule).push(entry);
                    }
                } else {
                    matriculeSeen.set(matricule, i);
                }

                // Vérifier contact (s'il est renseigné)
                if (contact && contact.length >= 10) {
                    if (contactSeen.has(contact)) {
                        if (!conflicts.contactDuplicates.has(contact)) {
                            const firstIndex = contactSeen.get(contact);
                            const firstName = conflicts.fullNameMap.get(firstIndex) || "Nom inconnu";
                            conflicts.contactDuplicates.set(contact, [{
                                    fullName: firstName,
                                    rowIndex: firstIndex
                                },
                                entry
                            ]);
                        } else {
                            conflicts.contactDuplicates.get(contact).push(entry);
                        }
                    } else {
                        contactSeen.set(contact, i);
                    }
                }

                // Vérifier email (s'il est renseigné)
                if (email && email.includes('@')) {
                    if (emailSeen.has(email)) {
                        if (!conflicts.emailDuplicates.has(email)) {
                            const firstIndex = emailSeen.get(email);
                            const firstName = conflicts.fullNameMap.get(firstIndex) || "Nom inconnu";
                            conflicts.emailDuplicates.set(email, [{
                                    fullName: firstName,
                                    rowIndex: firstIndex
                                },
                                entry
                            ]);
                        } else {
                            conflicts.emailDuplicates.get(email).push(entry);
                        }
                    } else {
                        emailSeen.set(email, i);
                    }
                }
            }

            return conflicts;
        }

        /**
         * Affiche les conflits dans l'interface
         */
        function displayConflicts(conflicts) {
            const totalConflicts = conflicts.matriculeDuplicates.size + conflicts.contactDuplicates.size + conflicts
                .emailDuplicates.size;
            hasConflicts = totalConflicts > 0;

            document.getElementById('conflictsBadge').textContent = totalConflicts;
            document.getElementById('conflictsBadge').className = `badge ms-2 ${hasConflicts ? 'bg-danger' : 'bg-success'}`;

            let html = '';

            // Doublons de matricules
            if (conflicts.matriculeDuplicates.size > 0) {
                html += '<div class="mb-2"><strong class="text-danger">📛 Matricules en double :</strong></div>';
                html += '<ul class="list-group list-group-flush mb-3">';
                for (const [matricule, occurrences] of conflicts.matriculeDuplicates) {
                    const namesList = occurrences.map(o => `${o.fullName}`).join(', ');
                    html += `
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div>
                                <span class="badge-duplicate me-2">Doublon matricule</span>
                                <strong>${matricule}</strong><br>
                                <small class="text-danger">Noms concernés : ${namesList}</small>
                            </div>
                            <span class="badge bg-danger">${occurrences.length} fois</span>
                        </li>
                    `;
                }
                html += '</ul>';
            }

            // Doublons de contacts
            if (conflicts.contactDuplicates.size > 0) {
                html += '<div class="mb-2"><strong class="text-danger">📞 Contacts en double :</strong></div>';
                html += '<ul class="list-group list-group-flush mb-3">';
                for (const [contact, occurrences] of conflicts.contactDuplicates) {
                    const namesList = occurrences.map(o => `${o.fullName}`).join(', ');
                    html += `
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div>
                                <span class="badge-warning me-2">Doublon contact</span>
                                <strong>${contact}</strong><br>
                                <small class="text-warning">Noms concernés : ${namesList}</small>
                            </div>
                            <span class="badge bg-warning text-dark">${occurrences.length} fois</span>
                        </li>
                    `;
                }
                html += '</ul>';
            }

            // Doublons d'emails
            if (conflicts.emailDuplicates.size > 0) {
                html += '<div class="mb-2"><strong class="text-danger">📧 Emails en double :</strong></div>';
                html += '<ul class="list-group list-group-flush mb-3">';
                for (const [email, occurrences] of conflicts.emailDuplicates) {
                    const namesList = occurrences.map(o => `${o.fullName}`).join(', ');
                    html += `
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div>
                                <span class="badge-warning me-2">Doublon email</span>
                                <strong>${email}</strong><br>
                                <small class="text-warning">Noms concernés : ${namesList}</small>
                            </div>
                            <span class="badge bg-warning text-dark">${occurrences.length} fois</span>
                        </li>
                    `;
                }
                html += '</ul>';
            }

            if (!hasConflicts) {
                html = '<span class="text-success fw-bold">✅ Aucun conflit détecté. Le fichier est valide.</span>';
                document.getElementById('duplicatesList').className = 'duplicate-list mt-2';
            } else {
                document.getElementById('duplicatesList').className = 'duplicate-list mt-2';
            }

            document.getElementById('duplicatesList').innerHTML = html;

            // Mettre à jour le message de validation
            updateValidationMessage(conflicts);

            // Activer/désactiver le bouton
            updatePaymentButton();
        }

        /**
         * Affiche le message de validation global
         */
        function updateValidationMessage(conflicts) {
            const container = document.getElementById('validationMessages');
            const content = document.getElementById('validationContent');
            container.style.display = 'block';

            const nbMatricules = conflicts.matriculeDuplicates.size;
            const nbContacts = conflicts.contactDuplicates.size;
            const nbEmails = conflicts.emailDuplicates.size;
            const total = nbMatricules + nbContacts + nbEmails;

            if (total === 0) {
                content.className = 'validation-summary success';
                content.innerHTML = `
                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                    <strong class="text-success">Fichier valide :</strong> Aucun conflit détecté. Vous pouvez procéder au paiement.
                `;
            } else {
                let details = [];
                if (nbMatricules > 0) details.push(`${nbMatricules} matricule(s) en double`);
                if (nbContacts > 0) details.push(`${nbContacts} contact(s) en double`);
                if (nbEmails > 0) details.push(`${nbEmails} email(s) en double`);

                content.className = 'validation-summary error';
                content.innerHTML = `
                    <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>
                    <strong class="error-text">${total} conflit(s) détecté(s) :</strong>
                    ${details.join(', ')}.<br>
                    <small>Veuillez corriger ces erreurs dans le fichier Excel et recharger le fichier.</small>
                `;
            }
        }

        /**
         * Active/désactive le bouton de paiement selon les conflits
         */
        function updatePaymentButton() {
            const payBtn = document.getElementById('payNowBtn');
            const blockMsg = document.getElementById('blockMessage');

            if (hasConflicts) {
                payBtn.disabled = true;
                payBtn.title = 'Corrigez les conflits avant de payer';
                blockMsg.style.display = 'block';
                document.getElementById('paymentStatusBadge').className = 'payment-status-badge blocked';
                document.getElementById('paymentStatusBadge').innerHTML = '🚫 Bloqué';
            } else {
                payBtn.disabled = false;
                payBtn.title = '';
                blockMsg.style.display = 'none';
                document.getElementById('paymentStatusBadge').className = 'payment-status-badge pending';
                document.getElementById('paymentStatusBadge').innerHTML = '⏳ En attente';
            }
        }

        // ---- Vérifier le retour de paiement depuis l'URL ----
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('payment_status') === 'success') {
                const codePaiement = urlParams.get('code_paiement');
                document.getElementById('payNowBtn').style.display = 'none';
                document.getElementById('paymentFields').style.display = 'none';
                document.getElementById('paymentStatusBadge').className = 'payment-status-badge paid';
                document.getElementById('paymentStatusBadge').innerHTML = '✅ Paiement effectué';
                document.getElementById('goToListBtn').style.display = 'inline-block';

                const resultDiv = document.createElement('div');
                resultDiv.className = 'alert alert-success mt-3';
                resultDiv.innerHTML = `
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <strong>Import et paiement réussis !</strong><br>
                    Code paiement : <strong>${codePaiement || 'N/A'}</strong><br>
                    Les membres ont été enregistrés et le paiement initié avec succès.
                `;
                document.querySelector('.recap-card .card-body').appendChild(resultDiv);
            }
        });

        // ---- Télécharger le fichier exemple ----
        document.getElementById('downloadExampleBtn').addEventListener('click', function(e) {
            e.preventDefault();
            const exampleData = [
                ['numero_matricule', 'noms', 'prenoms', 'contact', 'email'],
            ];
            const ws = XLSX.utils.aoa_to_sheet(exampleData);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'Membres');
            XLSX.writeFile(wb, 'exemple_membres.xlsx');
        });

        // ---- Analyse du fichier Excel ----
        document.getElementById('fichier').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (!file) {
                document.getElementById('recapArea').style.display = 'none';
                document.getElementById('paymentFields').style.display = 'none';
                document.getElementById('payNowBtn').style.display = 'none';
                document.getElementById('validationMessages').style.display = 'none';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const data = new Uint8Array(e.target.result);
                const workbook = XLSX.read(data, {
                    type: 'array'
                });
                const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
                const rows = XLSX.utils.sheet_to_json(firstSheet, {
                    header: 1,
                    defval: ""
                });

                if (!rows || rows.length < 2) {
                    alert("Le fichier est vide ou ne contient pas de données.");
                    document.getElementById('recapArea').style.display = 'none';
                    document.getElementById('paymentFields').style.display = 'none';
                    document.getElementById('payNowBtn').style.display = 'none';
                    document.getElementById('validationMessages').style.display = 'none';
                    return;
                }

                const headers = rows[0].map(cell => (cell || "").toString().trim().toLowerCase());

                // Vérifier les colonnes obligatoires
                const requiredColumns = ['numero_matricule', 'noms', 'prenoms', 'contact'];
                const missingColumns = requiredColumns.filter(col => !headers.includes(col));

                if (missingColumns.length > 0) {
                    alert(`Le fichier doit contenir les colonnes : ${missingColumns.join(', ')}`);
                    document.getElementById('recapArea').style.display = 'none';
                    document.getElementById('paymentFields').style.display = 'none';
                    document.getElementById('payNowBtn').style.display = 'none';
                    document.getElementById('validationMessages').style.display = 'none';
                    return;
                }

                // Analyser les conflits
                const conflicts = analyzeConflicts(rows, headers);

                // Calculer les stats
                let members = [];
                let matriculeCount = new Map();

                for (let i = 1; i < rows.length; i++) {
                    const row = rows[i];
                    if (!row || row.length === 0) continue;
                    const matricule = row[headers.indexOf('numero_matricule')] ? row[headers.indexOf(
                        'numero_matricule')].toString().trim() : "";
                    if (matricule === "") continue;
                    matriculeCount.set(matricule, (matriculeCount.get(matricule) || 0) + 1);
                    members.push({
                        matricule,
                        rowIndex: i
                    });
                }

                const totalLines = members.length;
                const uniqueMatricules = Array.from(matriculeCount.entries())
                    .filter(([_, count]) => count === 1)
                    .map(([mat]) => mat);
                const uniqueCount = uniqueMatricules.length;
                const totalAmount = uniqueCount * 10000;

                paymentData.totalLines = totalLines;
                paymentData.uniqueCount = uniqueCount;
                paymentData.totalAmount = totalAmount;

                // Mise à jour UI
                document.getElementById('totalLines').innerText = totalLines;
                document.getElementById('uniqueCount').innerText = uniqueCount;
                document.getElementById('totalAmount').innerHTML = `${totalAmount.toLocaleString()} F CFA`;

                // Afficher les conflits détaillés
                displayConflicts(conflicts);

                document.getElementById('recapArea').style.display = 'block';

                // Champs cachés du paiement
                document.getElementById('input_montant').value = totalAmount;
                document.getElementById('input_nombre_membres').value = uniqueCount;
                document.getElementById('input_description').value = "Droit d'adhésion pour " + uniqueCount +
                    " membres importés";
                document.getElementById('paymentAmountDisplay').innerHTML =
                    `${totalAmount.toLocaleString()} F CFA`;

                // Afficher les champs de paiement et le bouton
                document.getElementById('paymentFields').style.display = 'block';
                document.getElementById('payNowBtn').style.display = 'inline-block';
                document.getElementById('goToListBtn').style.display = 'none';

                // Mettre à jour le statut du bouton
                updatePaymentButton();
            };

            reader.onerror = function() {
                alert("Erreur lors de la lecture du fichier.");
                document.getElementById('recapArea').style.display = 'none';
                document.getElementById('paymentFields').style.display = 'none';
                document.getElementById('payNowBtn').style.display = 'none';
                document.getElementById('validationMessages').style.display = 'none';
            };

            reader.readAsArrayBuffer(file);
        });

        // ---- Soumission du formulaire ----
        document.getElementById('add_mutualiste_form').addEventListener('submit', function(e) {
            // Bloquer si des conflits existent
            if (hasConflicts) {
                e.preventDefault();
                alert(
                    'Veuillez corriger tous les conflits (matricules, contacts ou emails en double) avant de procéder au paiement.'
                    );
                return false;
            }

            if (formSubmitted) {
                e.preventDefault();
                return false;
            }

            const phone = document.getElementById('tresormoney_numero').value.trim();
            if (!phone || phone.length < 10) {
                e.preventDefault();
                alert('Veuillez entrer un numéro TresorMoney valide (10 chiffres).');
                document.getElementById('tresormoney_numero').focus();
                return false;
            }

            const fileInput = document.getElementById('fichier');
            if (!fileInput.files || !fileInput.files[0]) {
                e.preventDefault();
                alert('Veuillez sélectionner un fichier Excel.');
                return false;
            }

            formSubmitted = true;

            const payBtn = document.getElementById('payNowBtn');
            payBtn.disabled = true;
            payBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Traitement...';
            document.getElementById('paymentStatusBadge').className = 'payment-status-badge pending';
            document.getElementById('paymentStatusBadge').innerHTML = '⏳ Import et paiement en cours...';

            showLoading('Import des membres et initiation du paiement...\nVeuillez patienter.');

            return true;
        });
    </script>



@endpush --}}






@extends('layouts.dashboard', ['title' => 'Importer des membres', 'toolbar' => '_toolbar2', 'breadcrumb' => 'Membres > Importer des membres'])

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/dashboard/cssbundle/dropify.min.css') }}">
    <style>
        .recap-card {
            background-color: #f8f9fc;
            border-left: 4px solid #0d6efd;
        }

        .duplicate-list {
            max-height: 250px;
            overflow-y: auto;
            font-size: 0.9rem;
        }

        .badge-duplicate {
            background-color: #dc3545;
            color: white;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 0.75rem;
        }

        .badge-error {
            background-color: #dc3545;
            color: white;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 0.75rem;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #333;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 0.75rem;
        }

        .example-link {
            text-decoration: none;
            font-weight: 500;
        }

        .example-link:hover {
            text-decoration: underline;
        }

        .payment-summary {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            color: white;
            border-radius: 12px;
            padding: 20px;
            margin-top: 16px;
        }

        .payment-summary .amount {
            font-size: 2rem;
            font-weight: 700;
        }

        .payment-summary small {
            opacity: 0.85;
        }

        .btn-payment {
            background: #28a745;
            border: none;
            color: white;
            font-weight: 600;
            padding: 12px 32px;
            border-radius: 50px;
            font-size: 1.05rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 14px rgba(40, 167, 69, 0.35);
        }

        .btn-payment:hover {
            background: #218838;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.45);
            color: white;
        }

        .btn-payment:disabled {
            background: #6c757d;
            box-shadow: none;
            transform: none;
            cursor: not-allowed;
            opacity: 0.65;
        }

        .payment-status-badge {
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .payment-status-badge.pending {
            background: #fff3cd;
            color: #856404;
        }

        .payment-status-badge.paid {
            background: #d4edda;
            color: #155724;
        }

        .payment-status-badge.failed {
            background: #f8d7da;
            color: #721c24;
        }

        .payment-status-badge.blocked {
            background: #f8d7da;
            color: #721c24;
        }

        .phone-input-group {
            position: relative;
        }

        .phone-input-group .phone-prefix {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-weight: 600;
            color: #0d6efd;
            z-index: 5;
        }

        .phone-input-group input {
            padding-left: 60px !important;
        }

        #add_mutualiste_btn {
            display: none;
        }

        #add_mutualiste_btn.visible {
            display: inline-block;
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            z-index: 9999;
            display: none;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .loading-overlay.active {
            display: flex;
        }

        .loading-overlay .spinner-border {
            width: 4rem;
            height: 4rem;
        }

        .loading-overlay p {
            margin-top: 16px;
            font-weight: 600;
            color: #0d6efd;
        }

        .validation-summary {
            background: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 8px;
            padding: 12px 16px;
        }

        .validation-summary.error {
            background: #f8d7da;
            border-color: #dc3545;
        }

        .validation-summary.success {
            background: #d4edda;
            border-color: #28a745;
        }

        .error-text {
            color: #dc3545;
            font-weight: 500;
        }
    </style>
@endpush

@section('content')
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner-border text-primary" role="status"></div>
        <p id="loadingMessage">Traitement en cours, veuillez patienter...</p>
    </div>

    <div class="row g-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Importer la liste des membres</h6>
                    <div class="dropdown morphing scale-left">
                        <a href="#" class="card-fullscreen" data-bs-toggle="tooltip" title="Card Full-Screen">
                            <i class="icon-size-fullscreen"></i>
                        </a>
                        <a href="{{ route('mutualistes.index') }}" class="btn btn-primary d-inline">Retour</a>
                    </div>
                </div>
                <div class="card-body" id="show_all_admin">
                    <form action="{{ route('paiement.initier') }}" method="POST" id="add_mutualiste_form"
                        enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        @method('POST')

                        <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                            <h6 class="fw-bold text-danger">Charger le fichier contenant la liste des membres *</h6>
                            <a href="#" id="downloadExampleBtn" class="example-link text-primary">
                                <i class="bi bi-file-earmark-excel"></i> Télécharger un exemple de fichier Excel
                            </a>
                        </div>

                        <div class="row g-3">
                            <div class="col-lg-12 col-md-12">
                                <div class="form-floating">
                                    <input type="file" name="fichier" id="fichier"
                                        class="form-select form-control @error('fichier') is-invalid @enderror dropify"
                                        data-height="200" accept=".xls,.xlsx" autofocus required />
                                    @error('fichier')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Zone de récapitulatif -->
                        <div id="recapArea" style="display: none;" class="mt-4">
                            <div class="card recap-card">
                                <div class="card-header bg-transparent fw-bold">
                                    <i class="bi bi-info-circle-fill"></i> Récapitulatif du fichier
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <div class="border rounded p-2 text-center">
                                                <span class="text-muted">Nombre total de lignes</span>
                                                <h4 class="mb-0" id="totalLines">0</h4>
                                                <small>(hors en-tête)</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="border rounded p-2 text-center">
                                                <span class="text-muted">Membres uniques</span>
                                                <h4 class="mb-0" id="uniqueCount">0</h4>
                                                <small>(après dédoublonnage)</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <div class="border rounded p-2 text-center">
                                                <span class="text-muted">Montant total à payer</span>
                                                <h4 class="mb-0 text-success" id="totalAmount">0 F CFA</h4>
                                                <small>({{ $taxeMontant ?? 10000 }} FCFA par membre unique)</small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Messages de validation -->
                                    <div id="validationMessages" class="mt-3" style="display: none;">
                                        <div id="validationContent"></div>
                                    </div>

                                    <div class="mt-3">
                                        <div class="fw-bold">
                                            🔁 Conflits détectés dans le fichier :
                                            <span id="conflictsBadge" class="badge bg-secondary ms-2">0</span>
                                        </div>
                                        <div id="duplicatesList" class="duplicate-list mt-2">
                                            <span class="text-muted">Aucun conflit détecté.</span>
                                        </div>
                                    </div>

                                    <!-- Section paiement -->
                                    <div id="paymentFields" style="display: none;" class="mt-3">
                                        <hr>


                                        <input type="hidden" name="montant" id="input_montant" value="0">
                                        <input type="hidden" name="nombre_membres" id="input_nombre_membres"
                                            value="0">
                                        <input type="hidden" name="description" id="input_description" value="">
                                        <input type="hidden" name="type_paiement" value="adhesion">

                                        <div
                                            class="payment-summary d-flex justify-content-between align-items-center flex-wrap">
                                            <div>
                                                <span class="fw-semibold">💳 Paiement des frais d'adhésion</span>
                                                <div class="amount mt-1" id="paymentAmountDisplay">0 F CFA</div>
                                                <small>· {{ $taxeMontant ?? 10000 }} FCFA/membre</small>
                                            </div>
                                            <div class="mt-3 mt-md-0 d-flex align-items-center gap-3">
                                                <span id="paymentStatusBadge" class="payment-status-badge pending">
                                                    ⏳ En attente
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Message blocage si conflits -->
                                        <div id="blockMessage" style="display: none;" class="mt-3 alert alert-danger">
                                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                            <strong>Paiement bloqué :</strong> Veuillez corriger les conflits ci-dessus
                                            avant de procéder au paiement.
                                        </div>

                                        <!-- Numéro TresorMoney -->
                                        <div class="mt-3">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold">📱 Numéro TresorMoney <span
                                                            class="text-danger">*</span></label>
                                                    <div class="phone-input-group">
                                                        <span class="phone-prefix">+225</span>
                                                        <input type="tel" name="tresormoney_numero"
                                                            id="tresormoney_numero" class="form-control form-control-lg"
                                                            placeholder="07 00 00 00 00" pattern="[0-9]{10}"
                                                            maxlength="10" required>
                                                    </div>
                                                    <small class="text-muted">Numéro à 10 chiffres (ex: 0701020304)</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <p><span class="text-danger fw-bold">*</span> Champs obligatoires.</p>
                        </div>
                        <div class="row g-3">
                            <div class="mx-auto d-flex justify-content-center gap-3">
                                <button type="reset" class="btn btn-secondary w-25">Annuler</button>
                                <button type="submit" id="payNowBtn" class="btn btn-success w-25"
                                    style="display: none;" disabled>
                                    <i class="bi bi-credit-card-2-front me-1"></i> Payer maintenant
                                </button>
                                <a href="{{ route('mutualistes.index') }}" id="goToListBtn" class="btn btn-primary"
                                    style="display: none;">
                                    <i class="bi bi-arrow-left me-1"></i> Retour à la liste
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/dashboard/js/bundle/dropify.bundle.js') }}"></script>
    <script src="https://cdn.sheetjs.com/xlsx-0.20.2/package/dist/xlsx.full.min.js"></script>
    <script>
        // Montant unitaire de la taxe d'adhésion (défini dans le contrôleur)
        const TAXE_MONTANT = {{ $taxeMontant ?? 10000 }};
    </script>
    <script>
        $(function() {
            $('.dropify').dropify({
                messages: {
                    'default': 'Faites glisser un fichier ici ou cliquez ici',
                    'replace': 'Faites glisser et déposer ou cliquez pour remplacer',
                    'remove': 'Supprimer',
                    'error': 'Ooops, quelque chose a mal tourné.'
                },
                error: {
                    'fileSize': 'La taille du fichier est trop grande.',
                    'imageFormat': 'Le format du fichier n\'est pas autorisé (.xls,.xlsx) seulement).'
                },
            });
        });

        // ---- Variables ----
        let paymentData = {
            totalLines: 0,
            uniqueCount: 0,
            totalAmount: 0
        };
        let formSubmitted = false;
        let hasConflicts = false;

        // ---- Fonctions d'affichage ----
        function showLoading(message) {
            document.getElementById('loadingMessage').textContent = message || 'Traitement en cours, veuillez patienter...';
            document.getElementById('loadingOverlay').classList.add('active');
        }

        function hideLoading() {
            document.getElementById('loadingOverlay').classList.remove('active');
        }

        /**
         * Analyse les conflits dans le fichier :
         * - Doublons de matricules
         * - Doublons de contacts
         * - Doublons d'emails
         */
        function analyzeConflicts(rows, headers) {
            const colIdx = {
                matricule: headers.indexOf('numero_matricule'),
                noms: headers.indexOf('noms'),
                prenoms: headers.indexOf('prenoms'),
                contact: headers.indexOf('contact'),
                email: headers.indexOf('email')
            };

            const conflicts = {
                matriculeDuplicates: new Map(),
                contactDuplicates: new Map(),
                emailDuplicates: new Map(),
                fullNameMap: new Map()
            };

            const matriculeSeen = new Map();
            const contactSeen = new Map();
            const emailSeen = new Map();

            for (let i = 1; i < rows.length; i++) {
                const row = rows[i];
                if (!row || row.length === 0) continue;

                const matricule = colIdx.matricule !== -1 ? (row[colIdx.matricule] || "").toString().trim() : "";
                const noms = colIdx.noms !== -1 ? (row[colIdx.noms] || "").toString().trim() : "";
                const prenoms = colIdx.prenoms !== -1 ? (row[colIdx.prenoms] || "").toString().trim() : "";
                const contact = colIdx.contact !== -1 ? (row[colIdx.contact] || "").toString().trim() : "";
                const email = colIdx.email !== -1 ? (row[colIdx.email] || "").toString().trim() : "";

                if (matricule === "") continue;

                const fullName = `${noms} ${prenoms}`.trim() || "Nom inconnu";
                conflicts.fullNameMap.set(i, fullName);
                const entry = { fullName, rowIndex: i, contact, email };

                // Vérifier matricule
                if (matriculeSeen.has(matricule)) {
                    if (!conflicts.matriculeDuplicates.has(matricule)) {
                        const firstIndex = matriculeSeen.get(matricule);
                        const firstName = conflicts.fullNameMap.get(firstIndex) || "Nom inconnu";
                        conflicts.matriculeDuplicates.set(matricule, [{
                            fullName: firstName,
                            rowIndex: firstIndex,
                            contact: row[colIdx.contact] || '',
                            email: row[colIdx.email] || ''
                        }, entry]);
                    } else {
                        conflicts.matriculeDuplicates.get(matricule).push(entry);
                    }
                } else {
                    matriculeSeen.set(matricule, i);
                }

                // Vérifier contact
                if (contact && contact.length >= 10) {
                    if (contactSeen.has(contact)) {
                        if (!conflicts.contactDuplicates.has(contact)) {
                            const firstIndex = contactSeen.get(contact);
                            const firstName = conflicts.fullNameMap.get(firstIndex) || "Nom inconnu";
                            conflicts.contactDuplicates.set(contact, [{ fullName: firstName, rowIndex: firstIndex }, entry]);
                        } else {
                            conflicts.contactDuplicates.get(contact).push(entry);
                        }
                    } else {
                        contactSeen.set(contact, i);
                    }
                }

                // Vérifier email
                if (email && email.includes('@')) {
                    if (emailSeen.has(email)) {
                        if (!conflicts.emailDuplicates.has(email)) {
                            const firstIndex = emailSeen.get(email);
                            const firstName = conflicts.fullNameMap.get(firstIndex) || "Nom inconnu";
                            conflicts.emailDuplicates.set(email, [{ fullName: firstName, rowIndex: firstIndex }, entry]);
                        } else {
                            conflicts.emailDuplicates.get(email).push(entry);
                        }
                    } else {
                        emailSeen.set(email, i);
                    }
                }
            }

            return conflicts;
        }

        /**
         * Affiche les conflits dans l'interface
         */
        function displayConflicts(conflicts) {
            const totalConflicts = conflicts.matriculeDuplicates.size + conflicts.contactDuplicates.size + conflicts.emailDuplicates.size;
            hasConflicts = totalConflicts > 0;

            document.getElementById('conflictsBadge').textContent = totalConflicts;
            document.getElementById('conflictsBadge').className = `badge ms-2 ${hasConflicts ? 'bg-danger' : 'bg-success'}`;

            let html = '';

            // Doublons de matricules
            if (conflicts.matriculeDuplicates.size > 0) {
                html += '<div class="mb-2"><strong class="text-danger">📛 Matricules en double :</strong></div>';
                html += '<ul class="list-group list-group-flush mb-3">';
                for (const [matricule, occurrences] of conflicts.matriculeDuplicates) {
                    const namesList = occurrences.map(o => `${o.fullName}`).join(', ');
                    html += `
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div>
                                <span class="badge-duplicate me-2">Doublon matricule</span>
                                <strong>${matricule}</strong><br>
                                <small class="text-danger">Noms concernés : ${namesList}</small>
                            </div>
                            <span class="badge bg-danger">${occurrences.length} fois</span>
                        </li>
                    `;
                }
                html += '</ul>';
            }

            // Doublons de contacts
            if (conflicts.contactDuplicates.size > 0) {
                html += '<div class="mb-2"><strong class="text-danger">📞 Contacts en double :</strong></div>';
                html += '<ul class="list-group list-group-flush mb-3">';
                for (const [contact, occurrences] of conflicts.contactDuplicates) {
                    const namesList = occurrences.map(o => `${o.fullName}`).join(', ');
                    html += `
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div>
                                <span class="badge-warning me-2">Doublon contact</span>
                                <strong>${contact}</strong><br>
                                <small class="text-warning">Noms concernés : ${namesList}</small>
                            </div>
                            <span class="badge bg-warning text-dark">${occurrences.length} fois</span>
                        </li>
                    `;
                }
                html += '</ul>';
            }

            // Doublons d'emails
            if (conflicts.emailDuplicates.size > 0) {
                html += '<div class="mb-2"><strong class="text-danger">📧 Emails en double :</strong></div>';
                html += '<ul class="list-group list-group-flush mb-3">';
                for (const [email, occurrences] of conflicts.emailDuplicates) {
                    const namesList = occurrences.map(o => `${o.fullName}`).join(', ');
                    html += `
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div>
                                <span class="badge-warning me-2">Doublon email</span>
                                <strong>${email}</strong><br>
                                <small class="text-warning">Noms concernés : ${namesList}</small>
                            </div>
                            <span class="badge bg-warning text-dark">${occurrences.length} fois</span>
                        </li>
                    `;
                }
                html += '</ul>';
            }

            if (!hasConflicts) {
                html = '<span class="text-success fw-bold">✅ Aucun conflit détecté. Le fichier est valide.</span>';
            }

            document.getElementById('duplicatesList').innerHTML = html;
            updateValidationMessage(conflicts);
            updatePaymentButton();
        }

        /**
         * Affiche le message de validation global
         */
        function updateValidationMessage(conflicts) {
            const container = document.getElementById('validationMessages');
            const content = document.getElementById('validationContent');
            container.style.display = 'block';

            const nbMatricules = conflicts.matriculeDuplicates.size;
            const nbContacts = conflicts.contactDuplicates.size;
            const nbEmails = conflicts.emailDuplicates.size;
            const total = nbMatricules + nbContacts + nbEmails;

            if (total === 0) {
                content.className = 'validation-summary success';
                content.innerHTML = `
                    <i class="bi bi-check-circle-fill text-success me-2"></i>
                    <strong class="text-success">Fichier valide :</strong> Aucun conflit détecté. Vous pouvez procéder au paiement.
                `;
            } else {
                let details = [];
                if (nbMatricules > 0) details.push(`${nbMatricules} matricule(s) en double`);
                if (nbContacts > 0) details.push(`${nbContacts} contact(s) en double`);
                if (nbEmails > 0) details.push(`${nbEmails} email(s) en double`);

                content.className = 'validation-summary error';
                content.innerHTML = `
                    <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>
                    <strong class="error-text">${total} conflit(s) détecté(s) :</strong>
                    ${details.join(', ')}.<br>
                    <small>Veuillez corriger ces erreurs dans le fichier Excel et recharger le fichier.</small>
                `;
            }
        }

        /**
         * Active/désactive le bouton de paiement
         */
        function updatePaymentButton() {
            const payBtn = document.getElementById('payNowBtn');
            const blockMsg = document.getElementById('blockMessage');

            if (hasConflicts) {
                payBtn.disabled = true;
                payBtn.title = 'Corrigez les conflits avant de payer';
                blockMsg.style.display = 'block';
                document.getElementById('paymentStatusBadge').className = 'payment-status-badge blocked';
                document.getElementById('paymentStatusBadge').innerHTML = '🚫 Bloqué';
            } else {
                payBtn.disabled = false;
                payBtn.title = '';
                blockMsg.style.display = 'none';
                document.getElementById('paymentStatusBadge').className = 'payment-status-badge pending';
                document.getElementById('paymentStatusBadge').innerHTML = '⏳ En attente';
            }
        }

        // ---- API de vérification des doublons en base ----
        async function verifierDoublonsEnBase(file) {
            const formData = new FormData();
            formData.append('fichier', file);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}');

            try {
                const response = await fetch('{{ route('verifier.doublons') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}'
                    }
                });

                const result = await response.json();

                if (!result.success) {
                    return { success: false, message: result.message };
                }

                return { success: true, data: result.data };
            } catch (error) {
                return { success: false, message: error.message };
            }
        }

        // ---- Vérifier le retour de paiement depuis l'URL ----
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('payment_status') === 'success') {
                const codePaiement = urlParams.get('code_paiement');
                document.getElementById('payNowBtn').style.display = 'none';
                document.getElementById('paymentFields').style.display = 'none';
                document.getElementById('paymentStatusBadge').className = 'payment-status-badge paid';
                document.getElementById('paymentStatusBadge').innerHTML = '✅ Paiement effectué';
                document.getElementById('goToListBtn').style.display = 'inline-block';

                const resultDiv = document.createElement('div');
                resultDiv.className = 'alert alert-success mt-3';
                resultDiv.innerHTML = `
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <strong>Import et paiement réussis !</strong><br>
                    Code paiement : <strong>${codePaiement || 'N/A'}</strong><br>
                    Les membres ont été enregistrés et le paiement initié avec succès.
                `;
                document.querySelector('.recap-card .card-body').appendChild(resultDiv);
            }
        });

        // ---- Télécharger le fichier exemple ----
        document.getElementById('downloadExampleBtn').addEventListener('click', function(e) {
            e.preventDefault();
            const exampleData = [
                ['numero_matricule', 'noms', 'prenoms', 'contact', 'email'],
            ];
            const ws = XLSX.utils.aoa_to_sheet(exampleData);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, 'Membres');
            XLSX.writeFile(wb, 'exemple_membres.xlsx');
        });

        // ---- UN SEUL event listener pour le changement de fichier ----
        document.getElementById('fichier').addEventListener('change', async function(event) {
            const file = event.target.files[0];
            if (!file) {
                document.getElementById('recapArea').style.display = 'none';
                document.getElementById('paymentFields').style.display = 'none';
                document.getElementById('payNowBtn').style.display = 'none';
                document.getElementById('validationMessages').style.display = 'none';
                return;
            }

            // Lire le fichier pour l'aperçu côté client
            const reader = new FileReader();

            reader.onload = async function(e) {
                const data = new Uint8Array(e.target.result);
                const workbook = XLSX.read(data, { type: 'array' });
                const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
                const rows = XLSX.utils.sheet_to_json(firstSheet, { header: 1, defval: "" });

                if (!rows || rows.length < 2) {
                    alert("Le fichier est vide ou ne contient pas de données.");
                    document.getElementById('recapArea').style.display = 'none';
                    document.getElementById('paymentFields').style.display = 'none';
                    document.getElementById('payNowBtn').style.display = 'none';
                    document.getElementById('validationMessages').style.display = 'none';
                    return;
                }

                const headers = rows[0].map(cell => (cell || "").toString().trim().toLowerCase());

                // Vérifier les colonnes obligatoires
                const requiredColumns = ['numero_matricule', 'noms', 'prenoms', 'contact'];
                const missingColumns = requiredColumns.filter(col => !headers.includes(col));

                if (missingColumns.length > 0) {
                    alert(`Le fichier doit contenir les colonnes : ${missingColumns.join(', ')}`);
                    document.getElementById('recapArea').style.display = 'none';
                    document.getElementById('paymentFields').style.display = 'none';
                    document.getElementById('payNowBtn').style.display = 'none';
                    document.getElementById('validationMessages').style.display = 'none';
                    return;
                }

                // Analyser les conflits internes du fichier
                const conflicts = analyzeConflicts(rows, headers);

                // Calculer les stats
                let matriculeCount = new Map();
                for (let i = 1; i < rows.length; i++) {
                    const row = rows[i];
                    if (!row || row.length === 0) continue;
                    const matricule = row[headers.indexOf('numero_matricule')] ? row[headers.indexOf('numero_matricule')].toString().trim() : "";
                    if (matricule === "") continue;
                    matriculeCount.set(matricule, (matriculeCount.get(matricule) || 0) + 1);
                }

                const totalLines = Array.from(matriculeCount.values()).reduce((a, b) => a + b, 0);
                const uniqueMatricules = Array.from(matriculeCount.entries())
                    .filter(([_, count]) => count === 1)
                    .map(([mat]) => mat);
                const uniqueCount = uniqueMatricules.length;
                const totalAmount = uniqueCount * TAXE_MONTANT;

                paymentData.totalLines = totalLines;
                paymentData.uniqueCount = uniqueCount;
                paymentData.totalAmount = totalAmount;

                // Mise à jour UI
                document.getElementById('totalLines').innerText = totalLines;
                document.getElementById('uniqueCount').innerText = uniqueCount;
                document.getElementById('totalAmount').innerHTML = `${totalAmount.toLocaleString()} F CFA`;
                document.getElementById('input_montant').value = totalAmount;
                document.getElementById('input_nombre_membres').value = uniqueCount;
                document.getElementById('input_description').value = "Droit d'adhésion pour " + uniqueCount + " membres importés";
                document.getElementById('paymentAmountDisplay').innerHTML = `${totalAmount.toLocaleString()} F CFA`;

                // Afficher les conflits internes du fichier
                displayConflicts(conflicts);
                document.getElementById('recapArea').style.display = 'block';
                document.getElementById('paymentFields').style.display = 'block';
                document.getElementById('payNowBtn').style.display = 'inline-block';
                document.getElementById('goToListBtn').style.display = 'none';

                // Si pas de conflits internes, vérifier les doublons en base via l'API
                if (!hasConflicts) {
                    document.getElementById('paymentStatusBadge').className = 'payment-status-badge pending';
                    document.getElementById('paymentStatusBadge').innerHTML = '⏳ Vérification en base...';
                    document.getElementById('payNowBtn').disabled = true;
                    document.getElementById('payNowBtn').innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Vérification...';

                    try {
                        // Appeler l'API de vérification
                        const result = await verifierDoublonsEnBase(file);

                        if (result.success && result.data.existants > 0) {
                            // Des doublons existent en base
                            hasConflicts = true;
                            document.getElementById('payNowBtn').disabled = true;
                            document.getElementById('payNowBtn').innerHTML = '<i class="bi bi-credit-card-2-front me-1"></i> Payer maintenant';
                            document.getElementById('paymentStatusBadge').className = 'payment-status-badge blocked';
                            document.getElementById('paymentStatusBadge').innerHTML = '🚫 Doublons en base';
                            document.getElementById('blockMessage').style.display = 'block';
                            document.getElementById('blockMessage').innerHTML = `
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <strong>Paiement bloqué :</strong> ${result.data.existants} ligne(s) existe(nt) déjà en base.<br>
                                <small>Ces lignes seront ignorées lors de l'import.</small>
                            `;

                            // Ajouter les lignes existantes en base aux conflits affichés
                            let existingHtml = '';
                            if (document.getElementById('duplicatesList').innerHTML.includes('Aucun conflit') ||
                                document.getElementById('duplicatesList').innerHTML.includes('✅')) {
                                existingHtml = '';
                            } else {
                                existingHtml = document.getElementById('duplicatesList').innerHTML;
                            }

                            existingHtml += '<div class="mb-2 mt-2"><strong class="text-danger">🗄️ Déjà en base de données :</strong></div>';
                            existingHtml += '<ul class="list-group list-group-flush mb-3">';
                            for (const item of result.data.lignes_existantes) {
                                const raisons = Array.isArray(item.raisons) ? item.raisons.join(', ') : item.raisons;
                                existingHtml += `
                                    <li class="list-group-item d-flex justify-content-between align-items-start">
                                        <div>
                                            <span class="badge-error me-2">Existant</span>
                                            <strong>${item.matricule}</strong> - ${item.nom}<br>
                                            <small>Raisons : ${raisons}</small>
                                        </div>
                                    </li>
                                `;
                            }
                            existingHtml += '</ul>';
                            document.getElementById('duplicatesList').innerHTML = existingHtml;

                            // Mettre à jour le compteur de conflits
                            const conflictBadge = document.getElementById('conflictsBadge');
                            const currentCount = parseInt(conflictBadge.textContent) || 0;
                            conflictBadge.textContent = currentCount + result.data.existants;
                            conflictBadge.className = 'badge ms-2 bg-danger';

                            // Mettre à jour le message de validation
                            const validationContent = document.getElementById('validationContent');
                            validationContent.className = 'validation-summary error';
                            validationContent.innerHTML = `
                                <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>
                                <strong class="error-text">${result.data.existants} ligne(s) existe(nt) déjà en base</strong><br>
                                <small>Ces lignes seront ignorées lors de l'import. Veuillez corriger ou retirer les doublons.</small>
                            `;
                        } else {
                            // Aucun doublon en base
                            document.getElementById('payNowBtn').disabled = false;
                            document.getElementById('payNowBtn').innerHTML = '<i class="bi bi-credit-card-2-front me-1"></i> Payer maintenant';
                            document.getElementById('paymentStatusBadge').className = 'payment-status-badge pending';
                            document.getElementById('paymentStatusBadge').innerHTML = '⏳ En attente';
                        }
                    } catch (error) {
                        console.error('Erreur lors de la vérification:', error);
                        document.getElementById('payNowBtn').disabled = false;
                        document.getElementById('payNowBtn').innerHTML = '<i class="bi bi-credit-card-2-front me-1"></i> Payer maintenant';
                        document.getElementById('paymentStatusBadge').className = 'payment-status-badge pending';
                        document.getElementById('paymentStatusBadge').innerHTML = '⏳ En attente';
                    }
                }

                // Mettre à jour le statut du bouton
                updatePaymentButton();
            };
            reader.readAsArrayBuffer(file);
        });

        // ---- Soumission du formulaire ----
        document.getElementById('add_mutualiste_form').addEventListener('submit', function(e) {
            if (hasConflicts) {
                e.preventDefault();
                alert('Veuillez corriger tous les conflits (matricules, contacts ou emails en double) avant de procéder au paiement.');
                return false;
            }

            if (formSubmitted) {
                e.preventDefault();
                return false;
            }

            const phone = document.getElementById('tresormoney_numero').value.trim();
            if (!phone || phone.length < 10) {
                e.preventDefault();
                alert('Veuillez entrer un numéro TresorMoney valide (10 chiffres).');
                document.getElementById('tresormoney_numero').focus();
                return false;
            }

            const fileInput = document.getElementById('fichier');
            if (!fileInput.files || !fileInput.files[0]) {
                e.preventDefault();
                alert('Veuillez sélectionner un fichier Excel.');
                return false;
            }

            formSubmitted = true;

            const payBtn = document.getElementById('payNowBtn');
            payBtn.disabled = true;
            payBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Traitement...';
            document.getElementById('paymentStatusBadge').className = 'payment-status-badge pending';
            document.getElementById('paymentStatusBadge').innerHTML = '⏳ Import et paiement en cours...';

            showLoading('Import des membres et initiation du paiement...\nVeuillez patienter.');

            return true;
        });
    </script>

@endpush



