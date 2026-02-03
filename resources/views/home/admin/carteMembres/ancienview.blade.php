@extends('layouts.home_dashboard', ['title' => 'Ma Carte Membre UNAMEPCI'])
@push('css')
    <style>
        .rotate {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        /* Dimensions standard carte d'identité (ISO/IEC 7810 ID-1) */
        .carte-container {
            width: 85.6mm;
            /* Largeur standard */
            height: 53.98mm;
            /* Hauteur standard */
            perspective: 1000px;
            margin: 0 auto;
        }

        .carte-wrapper {
            width: 100%;
            height: 100%;
            position: relative;
            transform-style: preserve-3d;
            transition: transform 0.8s;
        }

        .carte-flip:hover .carte-wrapper {
            transform: rotateY(180deg);
        }

        .carte-recto,
        .carte-verso {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .carte-recto {
            background: linear-gradient(135deg, #ffffff 0%, #f5f5f5 100%);
            border: 2px solid #0056b3;
            z-index: 2;
        }

        .carte-verso {
            background: linear-gradient(135deg, #f0f0f0 0%, #e0e0e0 100%);
            border: 2px solid #0056b3;
            transform: rotateY(180deg);
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

        .carte-title {
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
            max-width: 600px;
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
            max-width: 600px;
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
            max-width: 600px;
        }

        .preview-text {
            font-size: 14px;
            color: #666;
            margin-bottom: 20px;
        }

        .flip-hint {
            font-size: 12px;
            color: #0056b3;
            margin-top: 10px;
            font-style: italic;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            .carte-container {
                page-break-inside: avoid;
                margin: 0;
                box-shadow: none;
            }

            .carte-wrapper {
                transform: none !important;
            }

            .carte-recto,
            .carte-verso {
                page-break-after: always;
                position: relative;
                margin-bottom: 20mm;
            }
        }
    </style>

    <style>
        /* ... (le reste du CSS reste identique) ... */

        /* Ajout de polices spécifiques pour l'impression */
        @font-face {
            font-family: 'Arial';
            font-style: normal;
            font-weight: normal;
            src: local('Arial'), local('ArialMT');
        }

        @font-face {
            font-family: 'Arial';
            font-style: normal;
            font-weight: bold;
            src: local('Arial Bold'), local('Arial-BoldMT');
        }

        /* Styles spécifiques pour l'impression */
        .print-font {
            font-family: 'Arial', 'Helvetica', sans-serif !important;
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

                <!-- Prévisualisation de la carte -->
                <div class="preview-container">
                    <p class="preview-text">
                        Voici votre carte de membre professionnelle. Survolez la carte pour voir le verso.
                    </p>

                    <div class="carte-flip">
                        <div class="carte-container" id="carteContainer">
                            <div class="carte-wrapper" id="carteWrapper">
                                <!-- Recto de la carte -->
                                <div class="carte-recto" id="carteRecto">
                                    <!-- En-tête -->
                                    <div class="carte-header">
                                        <div class="carte-title">UNAMEPCI</div>
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
                                            <div class="photo-label">PHOTO IDENTITE</div>
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
                                                <div class="info-value">{{ date('d/m/Y') }}</div>
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

                                <!-- Verso de la carte -->
                                <div class="carte-verso" id="carteVerso">
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

                                        <!-- QR Code (simulé) -->
                                        <div class="qr-code">
                                            <div style="font-size: 8px; color: #333; text-align: center; padding: 5px;">
                                                QR CODE<br>
                                                [Code d'authentification]
                                            </div>
                                        </div>

                                        <!-- Code-barres -->
                                        <div class="barcode">
                                            {{ substr($numCarte, 0, 15) }}
                                        </div>

                                        <!-- Informations de validité -->
                                        <div class="validity-info">
                                            Valide jusqu'au : {{ date('d/m/Y', strtotime('+1 year')) }}
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

                    <p class="flip-hint">← Survolez la carte pour voir le verso →</p>
                </div>

                <!-- Section téléchargement -->
                <div class="download-section no-print">
                    <h5>Télécharger votre carte</h5>
                    <p class="text-muted">Téléchargez votre carte dans différents formats pour l'impression</p>

                    <div class="btn-download-group">
                        <button class="btn-download" onclick="downloadRectoImage()">
                            <i class="fas fa-image"></i> Recto (Image)
                        </button>

                        <button class="btn-download" onclick="downloadVersoImage()">
                            <i class="fas fa-image"></i> Verso (Image)
                        </button>

                        <button class="btn-download" onclick="downloadBothImages()">
                            <i class="fas fa-images"></i> Recto+Verso (Images)
                        </button>

                        <button class="btn-download" onclick="downloadPDF()">
                            <i class="fas fa-file-pdf"></i> PDF Complet
                        </button>

                        {{-- <button class="btn-download" onclick="printCard()">
                            <i class="fas fa-print"></i> Imprimer
                        </button> --}}
                    </div>

                    <div style="margin-top: 30px; font-size: 12px; color: #666;">
                        <i class="fas fa-info-circle"></i>
                        <strong>Format standard :</strong> 85.6 x 54 mm (ISO/IEC 7810 ID-1)
                    </div>
                </div>

                <!-- Instructions -->
                <div class="instructions no-print">
                    <h6><i class="fas fa-clipboard-list"></i> Instructions pour l'utilisation de votre carte :</h6>

                    <div class="instruction-item">
                        <div class="instruction-icon">
                            <i class="fas fa-print"></i>
                        </div>
                        <div>
                            <strong>Impression :</strong> Imprimez sur du papier cartonné de 300g/m² minimum pour une
                            meilleure durabilité.
                        </div>
                    </div>

                    <div class="instruction-item">
                        <div class="instruction-icon">
                            <i class="fas fa-id-card"></i>
                        </div>
                        <div>
                            <strong>Plastification :</strong> Faites plastifier votre carte pour la protéger contre l'usure
                            et l'humidité.
                        </div>
                    </div>

                    <div class="instruction-item">
                        <div class="instruction-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div>
                            <strong>Sécurité :</strong> Présentez cette carte lors de vos activités professionnelles pour
                            justifier de votre adhésion à l'UNAMEPCI.
                        </div>
                    </div>

                    <div class="instruction-item">
                        <div class="instruction-icon">
                            <i class="fas fa-history"></i>
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
    {{-- <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <!-- Bibliothèque jsPDF pour génération PDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script> --}}

    <!-- Bibliothèque html2canvas pour capture d'écran -->
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <!-- Bibliothèque jsPDF pour génération PDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <!-- jsPDF AutoTable plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

    <script>
        // Initialiser jsPDF
        const {
            jsPDF
        } = window.jspdf;

        // Fonction pour télécharger le recto en image
        function downloadRectoImage() {
            const carteRecto = document.getElementById('carteRecto');
            const button = event.target;
            const originalText = button.innerHTML;

            // Afficher indicateur de chargement
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Génération...';
            button.disabled = true;

            html2canvas(carteRecto, {
                scale: 3, // Haute résolution
                backgroundColor: null,
                useCORS: true,
                allowTaint: true
            }).then(canvas => {
                // Créer un lien pour télécharger
                const link = document.createElement('a');
                link.download = `carte_unamepci_recto_${getTimestamp()}.png`;
                link.href = canvas.toDataURL('image/png');
                link.click();

                // Réinitialiser le bouton
                button.innerHTML = originalText;
                button.disabled = false;

                showSuccessMessage('Recto téléchargé avec succès!');
            });
        }

        // Fonction pour télécharger le verso en image
        function downloadVersoImage() {
            const carteVerso = document.getElementById('carteVerso');
            const button = event.target;
            const originalText = button.innerHTML;

            // Afficher indicateur de chargement
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
                link.click();

                button.innerHTML = originalText;
                button.disabled = false;
                showSuccessMessage('Verso téléchargé avec succès!');
            });
        }

        // Fonction pour télécharger recto et verso en images séparées
        function downloadBothImages() {
            const button = event.target;
            const originalText = button.innerHTML;

            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Génération...';
            button.disabled = true;

            // Télécharger le recto
            html2canvas(document.getElementById('carteRecto'), {
                scale: 3,
                backgroundColor: null,
                useCORS: true,
                allowTaint: true
            }).then(canvasRecto => {
                // Télécharger le recto
                const linkRecto = document.createElement('a');
                linkRecto.download = `carte_unamepci_recto_${getTimestamp()}.png`;
                linkRecto.href = canvasRecto.toDataURL('image/png');
                linkRecto.click();

                // Puis télécharger le verso
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
                linkVerso.click();

                button.innerHTML = originalText;
                button.disabled = false;
                showSuccessMessage('Recto et verso téléchargés avec succès!');
            });
        }

        // Fonction pour télécharger en PDF
        function downloadPDF() {
            const button = event.target;
            const originalText = button.innerHTML;

            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Génération PDF...';
            button.disabled = true;

            // Créer un nouveau PDF
            const pdf = new jsPDF({
                orientation: 'landscape',
                unit: 'mm',
                format: [85.6, 54] // Format carte d'identité
            });

            // Ajouter le recto
            html2canvas(document.getElementById('carteRecto'), {
                scale: 3,
                backgroundColor: null,
                useCORS: true,
                allowTaint: true
            }).then(canvasRecto => {
                const imgDataRecto = canvasRecto.toDataURL('image/png');

                // Ajouter l'image du recto
                pdf.addImage(imgDataRecto, 'PNG', 0, 0, 85.6, 54);

                // Ajouter une nouvelle page pour le verso
                pdf.addPage([85.6, 54], 'landscape');

                // Ajouter le verso
                return html2canvas(document.getElementById('carteVerso'), {
                    scale: 3,
                    backgroundColor: null,
                    useCORS: true,
                    allowTaint: true
                });
            }).then(canvasVerso => {
                const imgDataVerso = canvasVerso.toDataURL('image/png');
                pdf.addImage(imgDataVerso, 'PNG', 0, 0, 85.6, 54);

                // Télécharger le PDF
                pdf.save(`carte_unamepci_${getTimestamp()}.pdf`);

                button.innerHTML = originalText;
                button.disabled = false;
                showSuccessMessage('PDF téléchargé avec succès!');
            });
        }

        // Fonction pour imprimer
        function printCard() {
            const button = event.target;
            const originalText = button.innerHTML;

            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Préparation...';

            // Créer une fenêtre d'impression
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
            <html>
                <head>
                    <title>Impression Carte UNAMEPCI</title>
                    <style>
                        body {
                            margin: 0;
                            padding: 20px;
                            display: flex;
                            flex-direction: column;
                            align-items: center;
                            background: #f5f5f5;
                        }
                        .print-container {
                            display: flex;
                            flex-direction: column;
                            gap: 20mm;
                            align-items: center;
                        }
                        .carte-print {
                            width: 85.6mm;
                            height: 54mm;
                            border-radius: 10px;
                            overflow: hidden;
                            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
                            page-break-inside: avoid;
                        }
                        .print-info {
                            text-align: center;
                            margin-bottom: 20px;
                            font-family: Arial, sans-serif;
                        }
                        @media print {
                            body {
                                background: white;
                                padding: 0;
                            }
                            .carte-print {
                                margin-bottom: 20mm;
                                box-shadow: none;
                            }
                        }
                    </style>
                </head>
                <body>
                    <div class="print-info">
                        <h3>Carte de Membre UNAMEPCI - ${"{{ $mutualiste->nom }} {{ $mutualiste->prenom }}"}</h3>
                        <p>Imprimez sur du papier cartonné (300g/m² minimum)</p>
                    </div>
                    <div class="print-container">
                        ${document.getElementById('carteRecto').outerHTML}
                        ${document.getElementById('carteVerso').outerHTML}
                    </div>
                    <script>
                        window.onload = function() {
                            window.print();
                            setTimeout(() => {
                                window.close();
                            }, 500);
                        };
                    <\/script>
                </body>
            </html>
        `);
            printWindow.document.close();

            button.innerHTML = originalText;
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
            // Créer une notification
            const notification = document.createElement('div');
            notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #28a745;
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            z-index: 10000;
            animation: slideIn 0.3s ease;
        `;
            notification.innerHTML = `
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-check-circle" style="font-size: 20px;"></i>
                <div>
                    <strong>Succès!</strong><br>
                    <small>${message}</small>
                </div>
            </div>
        `;

            document.body.appendChild(notification);

            // Supprimer après 3 secondes
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }

        // Ajouter des styles d'animation pour la notification
        const style = document.createElement('style');
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

        // Animation au survol améliorée
        document.addEventListener('DOMContentLoaded', function() {
            const carteFlip = document.querySelector('.carte-flip');
            const carteWrapper = document.querySelector('.carte-wrapper');

            carteFlip.addEventListener('mouseenter', function() {
                carteWrapper.style.transform = 'rotateY(180deg)';
            });

            carteFlip.addEventListener('mouseleave', function() {
                carteWrapper.style.transform = 'rotateY(0deg)';
            });

            // Cliquer pour forcer le retour
            carteFlip.addEventListener('click', function() {
                carteWrapper.style.transform = 'rotateY(0deg)';
            });

            // S'assurer que les images se chargent correctement
            const photoProfil = document.getElementById('photoProfil');
            if (photoProfil) {
                photoProfil.onerror = function() {
                    this.onerror = null;
                    this.src =
                        'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><circle cx="50" cy="40" r="20" fill="%23666"/><path d="M50,65 L30,85 L70,85 Z" fill="%23666"/></svg>';
                };
            }
        });
    </script>
@endpush
