@extends('layouts.dashboard', ['title' => 'Liste des carte Membres', 'toolbar' => '_toolbar2', 'breadcrumb' => 'carte Membres'])
@push('css')
    <style>
        /* Styles pour les cartes côte à côte */
        .cartes-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
            margin: 40px 0;
        }

        .carte-item {
            text-align: center;
        }

        .carte-title {
            font-size: 14px;
            font-weight: bold;
            color: #0056b3;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        /* Dimensions standard carte d'identité (ISO/IEC 7810 ID-1) */
        .carte-preview {
            width: 85.6mm;
            height: 53.98mm;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            margin: 0 auto;
        }

        .carte-recto {
            background: linear-gradient(135deg, #ffffff 0%, #f5f5f5 100%);
            border: 2px solid #0056b3;
        }

        .carte-verso {
            background: linear-gradient(135deg, #f0f0f0 0%, #e0e0e0 100%);
            border: 2px solid #0056b3;
        }

        /* Header recto */
        .carte-header {
            background: #0056b3;
            color: white;
            padding: 5px 10px;
            text-align: center;
            height: 20%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .carte-title-header {
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 1px;
            margin: 0;
            line-height: 1.2;
        }

        .carte-subtitle {
            font-size: 8px;
            opacity: 0.9;
            margin-top: 2px;
        }

        /* Corps recto */
        .carte-body {
            padding: 8px 10px;
            height: 80%;
            display: flex;
            gap: 8px;
        }

        .carte-photo {
            width: 35%;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .photo-container {
            width: 90%;
            height: 75%;
            background: #e0e0e0;
            border: 1px solid #0056b3;
            border-radius: 3px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin-bottom: 5px;
        }

        .photo-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-label {
            font-size: 7px;
            color: #666;
            font-style: italic;
        }

        .carte-infos {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .info-row {
            display: flex;
            margin-bottom: 4px;
            border-bottom: 0.5px dotted #ccc;
            padding-bottom: 2px;
        }

        .info-label {
            font-weight: bold;
            color: #0056b3;
            width: 55%;
            font-size: 8px;
            text-transform: uppercase;
        }

        .info-value {
            flex: 1;
            font-size: 8px;
            font-weight: bold;
            color: #333;
            text-transform: uppercase;
        }

        .carte-numero {
            background: #f0f0f0;
            padding: 3px;
            border-radius: 3px;
            text-align: center;
            border: 1px dashed #0056b3;
            margin-top: 5px;
        }

        .numero-text {
            font-family: 'Courier New', monospace;
            font-size: 10px;
            font-weight: bold;
            color: #0056b3;
            letter-spacing: 1px;
        }

        .carte-footer {
            position: absolute;
            bottom: 5px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 6px;
            color: #666;
            padding: 0 10px;
        }

        /* Verso */
        .verso-content {
            height: 100%;
            padding: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .logo-unamepci {
            width: 50%;
            margin-bottom: 10px;
        }

        .qr-code {
            width: 40%;
            height: 40%;
            background: #fff;
            border: 1px solid #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 10px 0;
        }

        .qr-code img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .contact-info {
            font-size: 7px;
            text-align: center;
            color: #333;
            margin-top: 10px;
        }

        .barcode {
            width: 80%;
            height: 15px;
            background: #000;
            margin-top: 10px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 6px;
            font-family: 'Courier New', monospace;
            letter-spacing: 2px;
        }

        .validity-info {
            font-size: 7px;
            text-align: center;
            margin-top: 8px;
            color: #0056b3;
            font-weight: bold;
        }

        .signature-area {
            border-top: 1px solid #333;
            width: 60%;
            margin: 5px auto;
            text-align: center;
            font-size: 6px;
            color: #666;
        }

        /* Boutons de téléchargement */
        .download-section {
            margin: 30px auto;
            max-width: 800px;
            text-align: center;
        }

        .btn-download {
            background: linear-gradient(135deg, #0056b3 0%, #003d82 100%);
            color: white;
            border: none;
            padding: 12px 25px;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            margin: 10px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-download:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 86, 179, 0.3);
        }

        .btn-download-group {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 20px;
        }

        /* Instructions */
        .instructions {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin: 30px auto;
            max-width: 800px;
        }

        .instruction-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }

        .instruction-icon {
            font-size: 24px;
            color: #0056b3;
            margin-right: 15px;
            min-width: 40px;
        }

        /* Preview */
        .preview-container {
            text-align: center;
            margin: 30px auto;
            max-width: 800px;
        }

        .preview-text {
            font-size: 14px;
            color: #666;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .cartes-container {
                flex-direction: column;
                align-items: center;
            }

            .carte-preview {
                width: 90%;
                height: auto;
                aspect-ratio: 1.586;
                /* Ratio largeur/hauteur carte */
                max-width: 85.6mm;
                max-height: 53.98mm;
            }
        }

        @media print {
            .no-print {
                display: none !important;
            }

            .carte-preview {
                page-break-inside: avoid;
                margin: 10mm auto;
                box-shadow: none;
            }

            .cartes-container {
                display: block;
            }

            .carte-item {
                page-break-after: always;
                margin-bottom: 20mm;
            }
        }
    </style>
@endpush

@section('content')
    <div class="col-lg-9">
        <div class="rbt-dashboard-content bg-color-white rbt-shadow-box">
            <div class="content">
                <div class="section-title">
                    <h4 class="rbt-title-style-3">Votre Carte de Membre UNAMEPCI</h4>
                    <p class="text-muted">Générée et prête à être téléchargée</p>
                </div>

                <!-- Prévisualisation des cartes côte à côte -->
                <div class="preview-container">
                    <p class="preview-text">
                        Voici votre carte de membre professionnelle - Recto et Verso
                    </p>

                    <div class="cartes-container">
                        <!-- Recto de la carte -->
                        <div class="carte-item">
                            <div class="carte-title">Recto - Carte de Membre</div>
                            <div class="carte-preview carte-recto" id="carteRecto">
                                <!-- En-tête -->
                                <div class="carte-header">
                                    <div class="carte-title-header">UNAMEPCI</div>
                                    <div class="carte-subtitle">CARTE DE MEMBRE PROFESSIONNEL</div>
                                </div>

                                <!-- Corps -->
                                <div class="carte-body">
                                    <!-- Photo -->
                                    <div class="carte-photo">
                                        <div class="photo-container">
                                            @if ($mutualiste->lien_photo && file_exists(public_path($mutualiste->lien_photo)))
                                                <img src="{{ asset($mutualiste->lien_photo) }}" alt="Photo"
                                                    id="photoProfil">
                                            @else
                                                <div style="font-size: 24px; color: #666;">
                                                    <i class="fas fa-user-md"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="photo-label">
                                            signature du titulaire <br>
                                            <img src="{{ asset($mutualiste->signature) }}" alt="signature"
                                                style="width: 27px; height:27px;">
                                        </div>
                                    </div>

                                    <!-- Informations -->
                                    <div class="carte-infos">
                                        <div class="info-row">
                                            <div class="info-label">NOM :</div>
                                            <div class="info-value">{{ strtoupper($mutualiste->nom) }}</div>
                                        </div>

                                        <div class="info-row">
                                            <div class="info-label">PRENOMS :</div>
                                            <div class="info-value">{{ strtoupper($mutualiste->prenom) }}</div>
                                        </div>

                                        <div class="info-row">
                                            <div class="info-label">MATRICULE :</div>
                                            <div class="info-value">{{ strtoupper($mutualiste->matricule) }}</div>
                                        </div>

                                        <div class="info-row">
                                            <div class="info-label">SPECIALITE :</div>
                                            <div class="info-value">{{ strtoupper($mutualiste->specialite->libelle) }}
                                            </div>
                                        </div>

                                        <div class="info-row">
                                            <div class="info-label">DATE NAISS. :</div>
                                            <div class="info-value">
                                                {{ date('d/m/Y', strtotime($mutualiste->date_naissance)) }}</div>
                                        </div>

                                        <div class="info-row">
                                            <div class="info-label">DELIVREE LE :</div>
                                            <div class="info-value">
                                                {{-- {{ $CarteMembre->date_delivre ? $CarteMembre->date_delivre->format('d/m/Y') : '' }} --}}
                                                {{ formatDate02($CarteMembre->date_delivre) }}
                                            </div>
                                        </div>

                                        <div class="carte-numero">
                                            <div class="numero-text" id="numeroCarte">
                                                @php
                                                    $matricule = str_replace(
                                                        ['/', '-', ' '],
                                                        '',
                                                        $mutualiste->matricule,
                                                    );
                                                    $numCarte = 'UPCI-' . date('y') . '-' . substr($matricule, -5);
                                                @endphp
                                                {{ $numCarte }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pied de page -->
                                <div class="carte-footer">
                                    Cette carte est la propriété de l'UNAMEPCI - Toute falsification est punie par la
                                    loi
                                </div>
                            </div>
                        </div>

                        <!-- Verso de la carte -->
                        <div class="carte-item">
                            <div class="carte-title">Verso - Carte de Membre</div>
                            <div class="carte-preview carte-verso" id="carteVerso">
                                <div class="verso-content">
                                    <!-- Logo UNAMEPCI -->
                                    <div class="logo-unamepci">
                                        <div
                                            style="font-size: 14px; font-weight: bold; color: #0056b3; text-align: center;">
                                            UNAMEPCI
                                        </div>
                                        <div style="font-size: 6px; text-align: center; color: #666;">
                                            Union Nationale des Médecins Privés de Côte d'Ivoire
                                        </div>
                                    </div>

                                    <!-- QR Code -->
                                    <div class="qr-code">
                                        <div class="force-font"
                                            style="font-size: 8px; color: #333; text-align: center; padding: 5px;">
                                            {{-- {!! QrCode::size(120)->generate(route('carte.verification', ['id' => $mutualiste->id])) !!} --}}
                                            {!! QrCode::size(30)->generate('nom et prenoms') !!}
                                        </div>
                                    </div>

                                    <!-- Code-barres -->
                                    <div class="barcode">
                                        {{ substr($numCarte, 0, 15) }}
                                    </div>

                                    <!-- Informations de validité -->
                                    <div class="validity-info">
                                        Valide jusqu'au :
                                        {{ formatDate02($CarteMembre->date_expiration) }}

                                    </div>

                                    <!-- Zone de signature -->
                                    <div class="signature-area">
                                        Signature du président
                                    </div>

                                    <!-- Contact -->
                                    <div class="contact-info">
                                        Siège : Abidjan Plateau<br>
                                        Tél : 27 22 44 55 66<br>
                                        www.unamepci.ci
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section téléchargement -->
                <div class="download-section no-print">
                    <h5>Télécharger votre carte</h5>
                    <p class="text-muted">Téléchargez votre carte dans différents formats pour l'impression</p>

                    <div class="btn-download-group">
                        <button class="btn-download" onclick="downloadRectoImage()">
                            <i class="bi bi-image"></i> Recto (Image)
                        </button>

                        <button class="btn-download" onclick="downloadVersoImage()">
                            <i class="bi bi-image"></i> Verso (Image)
                        </button>

                        <button class="btn-download" onclick="downloadBothImages()">
                            <i class="bi bi-images"></i> Recto+Verso (Images)
                        </button>

                        <button class="btn-download" onclick="downloadPDF()">
                            <i class="bi bi-file-pdf"></i> PDF Complet
                        </button>

                        {{-- <button class="btn-download" onclick="printAllCards()">
                            <i class="fas fa-print"></i> Imprimer Recto & Verso
                        </button>

                        <button class="btn-download" onclick="printRectoOnly()">
                            <i class="fas fa-print"></i> Imprimer Recto seulement
                        </button>

                        <button class="btn-download" onclick="printVersoOnly()">
                            <i class="fas fa-print"></i> Imprimer Verso seulement
                        </button> --}}
                    </div>

                    <div style="margin-top: 30px; font-size: 12px; color: #666;">
                        <i class="bi bi-info-circle"></i>
                        <strong>Format standard :</strong> 85.6 x 54 mm (ISO/IEC 7810 ID-1)
                    </div>
                </div>

                <!-- Instructions -->
                <div class="instructions no-print">
                    <h6><i class="bi bi-clipboard-list"></i> Instructions pour l'utilisation de votre carte :</h6>

                    <div class="instruction-item">
                        <div class="instruction-icon">
                            <i class="bi bi-print"></i>
                        </div>
                        <div>
                            <strong>Impression :</strong> Imprimez sur du papier cartonné de 300g/m² minimum pour une
                            meilleure durabilité.
                        </div>
                    </div>

                    <div class="instruction-item">
                        <div class="instruction-icon">
                            <i class="bi bi-id-card"></i>
                        </div>
                        <div>
                            <strong>Plastification :</strong> Faites plastifier votre carte pour la protéger contre l'usure
                            et l'humidité.
                        </div>
                    </div>

                    <div class="instruction-item">
                        <div class="instruction-icon">
                            <i class="bi bi-shield-alt"></i>
                        </div>
                        <div>
                            <strong>Sécurité :</strong> Présentez cette carte lors de vos activités professionnelles pour
                            justifier de votre adhésion à l'UNAMEPCI.
                        </div>
                    </div>

                    <div class="instruction-item">
                        <div class="instruction-icon">
                            <i class="bi bi-history"></i>
                        </div>
                        <div>
                            <strong>Renouvellement :</strong> Cette carte est valable un an. Vous recevrez une notification
                            pour le renouvellement.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script>
        const {
            jsPDF
        } = window.jspdf;

        // Fonction pour télécharger le recto en image
        function downloadRectoImage() {
            const carteRecto = document.getElementById('carteRecto');
            const button = event.currentTarget;
            const originalHTML = button.innerHTML;

            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Génération...';
            button.disabled = true;

            html2canvas(carteRecto, {
                scale: 3,
                backgroundColor: null,
                useCORS: true,
                allowTaint: true
            }).then(canvas => {
                const link = document.createElement('a');
                link.download = `carte_unamepci_recto_${getTimestamp()}.png`;
                link.href = canvas.toDataURL('image/png');
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                button.innerHTML = originalHTML;
                button.disabled = false;
                showSuccessMessage('Recto téléchargé avec succès!');
            }).catch(error => {
                console.error('Erreur:', error);
                button.innerHTML = originalHTML;
                button.disabled = false;
                showErrorMessage('Erreur lors du téléchargement');
            });
        }

        // Fonction pour télécharger le verso en image
        function downloadVersoImage() {
            const carteVerso = document.getElementById('carteVerso');
            const button = event.currentTarget;
            const originalHTML = button.innerHTML;

            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Génération...';
            button.disabled = true;

            html2canvas(carteVerso, {
                scale: 3,
                backgroundColor: null,
                useCORS: true,
                allowTaint: true
            }).then(canvas => {
                const link = document.createElement('a');
                link.download = `carte_unamepci_verso_${getTimestamp()}.png`;
                link.href = canvas.toDataURL('image/png');
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                button.innerHTML = originalHTML;
                button.disabled = false;
                showSuccessMessage('Verso téléchargé avec succès!');
            });
        }

        // Fonction pour télécharger recto et verso en images séparées
        function downloadBothImages() {
            const button = event.currentTarget;
            const originalHTML = button.innerHTML;

            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Génération...';
            button.disabled = true;

            html2canvas(document.getElementById('carteRecto'), {
                scale: 3,
                backgroundColor: null,
                useCORS: true,
                allowTaint: true
            }).then(canvasRecto => {
                const linkRecto = document.createElement('a');
                linkRecto.download = `carte_unamepci_recto_${getTimestamp()}.png`;
                linkRecto.href = canvasRecto.toDataURL('image/png');
                document.body.appendChild(linkRecto);
                linkRecto.click();
                document.body.removeChild(linkRecto);

                return html2canvas(document.getElementById('carteVerso'), {
                    scale: 3,
                    backgroundColor: null,
                    useCORS: true,
                    allowTaint: true
                });
            }).then(canvasVerso => {
                const linkVerso = document.createElement('a');
                linkVerso.download = `carte_unamepci_verso_${getTimestamp()}.png`;
                linkVerso.href = canvasVerso.toDataURL('image/png');
                document.body.appendChild(linkVerso);
                linkVerso.click();
                document.body.removeChild(linkVerso);

                button.innerHTML = originalHTML;
                button.disabled = false;
                showSuccessMessage('Recto et verso téléchargés avec succès!');
            });
        }

        // Fonction pour télécharger en PDF
        function downloadPDF() {
            const button = event.currentTarget;
            const originalHTML = button.innerHTML;

            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Génération PDF...';
            button.disabled = true;

            const pdf = new jsPDF({
                orientation: 'landscape',
                unit: 'mm',
                format: [85.6, 54]
            });

            Promise.all([
                html2canvas(document.getElementById('carteRecto'), {
                    scale: 3,
                    backgroundColor: null,
                    useCORS: true,
                    allowTaint: true
                }),
                html2canvas(document.getElementById('carteVerso'), {
                    scale: 3,
                    backgroundColor: null,
                    useCORS: true,
                    allowTaint: true
                })
            ]).then(([canvasRecto, canvasVerso]) => {
                const imgDataRecto = canvasRecto.toDataURL('image/png');
                const imgDataVerso = canvasVerso.toDataURL('image/png');

                pdf.addImage(imgDataRecto, 'PNG', 0, 0, 85.6, 54);
                pdf.addPage([85.6, 54], 'landscape');
                pdf.addImage(imgDataVerso, 'PNG', 0, 0, 85.6, 54);

                pdf.save(`carte_unamepci_${getTimestamp()}.pdf`);

                button.innerHTML = originalHTML;
                button.disabled = false;
                showSuccessMessage('PDF téléchargé avec succès!');
            });
        }

        // Fonction pour imprimer recto et verso
        function printAllCards() {
            const printContent = document.createElement('div');
            printContent.innerHTML = `
                <style>
                    @media print {
                        body * { visibility: hidden; }
                        .print-carte, .print-carte * { visibility: visible; }
                        .print-carte {
                            position: absolute;
                            left: 0;
                            top: 0;
                            width: 85.6mm;
                            height: 54mm;
                            page-break-after: always;
                        }
                    }
                </style>
                <div class="print-carte">${document.getElementById('carteRecto').outerHTML}</div>
                <div class="print-carte">${document.getElementById('carteVerso').outerHTML}</div>
            `;

            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                    <head>
                        <title>Impression Carte UNAMEPCI</title>
                        <style>
                            body {
                                margin: 0;
                                padding: 10mm;
                                background: white;
                                display: flex;
                                flex-direction: column;
                                align-items: center;
                            }
                            .carte-print {
                                width: 85.6mm;
                                height: 54mm;
                                margin-bottom: 20mm;
                                page-break-after: always;
                            }
                        </style>
                    </head>
                    <body>
                        ${document.getElementById('carteRecto').outerHTML}
                        ${document.getElementById('carteVerso').outerHTML}
                        <script>
                            window.onload = function() {
                                window.print();
                                setTimeout(() => window.close(), 1000);
                            };
                        <\/script>
                    </body>
                </html>
            `);
            printWindow.document.close();
        }

        // Fonction pour imprimer seulement le recto
        function printRectoOnly() {
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                    <head>
                        <title>Impression Recto Carte UNAMEPCI</title>
                        <style>
                            body {
                                margin: 0;
                                padding: 10mm;
                                background: white;
                                display: flex;
                                justify-content: center;
                                align-items: center;
                                height: 100vh;
                            }
                        </style>
                    </head>
                    <body>
                        ${document.getElementById('carteRecto').outerHTML}
                        <script>
                            window.onload = function() {
                                window.print();
                                setTimeout(() => window.close(), 1000);
                            };
                        <\/script>
                    </body>
                </html>
            `);
            printWindow.document.close();
        }

        // Fonction pour imprimer seulement le verso
        function printVersoOnly() {
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                    <head>
                        <title>Impression Verso Carte UNAMEPCI</title>
                        <style>
                            body {
                                margin: 0;
                                padding: 10mm;
                                background: white;
                                display: flex;
                                justify-content: center;
                                align-items: center;
                                height: 100vh;
                            }
                        </style>
                    </head>
                    <body>
                        ${document.getElementById('carteVerso').outerHTML}
                        <script>
                            window.onload = function() {
                                window.print();
                                setTimeout(() => window.close(), 1000);
                            };
                        <\/script>
                    </body>
                </html>
            `);
            printWindow.document.close();
        }

        // Fonction utilitaire pour obtenir un timestamp
        function getTimestamp() {
            const now = new Date();
            return now.getFullYear() +
                String(now.getMonth() + 1).padStart(2, '0') +
                String(now.getDate()).padStart(2, '0') + '_' +
                String(now.getHours()).padStart(2, '0') +
                String(now.getMinutes()).padStart(2, '0');
        }

        // Fonction pour afficher un message de succès
        function showSuccessMessage(message) {
            showNotification(message, 'success');
        }

        // Fonction pour afficher un message d'erreur
        function showErrorMessage(message) {
            showNotification(message, 'error');
        }

        // Fonction générique pour afficher les notifications
        function showNotification(message, type) {
            const color = type === 'success' ? '#28a745' : '#dc3545';
            const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';

            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${color};
                color: white;
                padding: 15px 20px;
                border-radius: 8px;
                box-shadow: 0 5px 15px rgba(0,0,0,0.2);
                z-index: 10000;
                animation: slideIn 0.3s ease;
            `;
            notification.innerHTML = `
                <div style="display: flex; align-items: center; gap: 10px;">
                    <i class="fas ${icon}" style="font-size: 20px;"></i>
                    <div>
                        <strong>${type === 'success' ? 'Succès!' : 'Erreur!'}</strong><br>
                        <small>${message}</small>
                    </div>
                </div>
            `;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => {
                    if (notification.parentNode) {
                        document.body.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }

        // Ajouter des styles d'animation pour la notification
        if (!document.querySelector('#notification-styles')) {
            const style = document.createElement('style');
            style.id = 'notification-styles';
            style.textContent = `
                @keyframes slideIn {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
                @keyframes slideOut {
                    from { transform: translateX(0); opacity: 1; }
                    to { transform: translateX(100%); opacity: 0; }
                }
            `;
            document.head.appendChild(style);
        }

        // Gestion des erreurs d'images
        document.addEventListener('DOMContentLoaded', function() {
            const photoProfil = document.getElementById('photoProfil');
            if (photoProfil) {
                photoProfil.onerror = function() {
                    this.onerror = null;
                    this.style.display = 'none';
                    this.parentElement.innerHTML =
                        '<i class="fas fa-user-md" style="font-size: 24px; color: #666;"></i>';
                };
            }
        });
    </script>
@endpush
