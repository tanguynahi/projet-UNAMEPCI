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
            height: 53.98mm;
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
            font-family: Arial, Helvetica, sans-serif !important;
        }

        .carte-subtitle {
            font-size: 8px;
            opacity: 0.9;
            margin-top: 2px;
            font-family: Arial, Helvetica, sans-serif !important;
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
            font-family: Arial, Helvetica, sans-serif !important;
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
            font-family: Arial, Helvetica, sans-serif !important;
        }

        .info-value {
            flex: 1;
            font-size: 8px;
            font-weight: bold;
            color: #333;
            text-transform: uppercase;
            font-family: Arial, Helvetica, sans-serif !important;
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
            font-family: Arial, Helvetica, sans-serif !important;
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

        .contact-info {
            font-size: 7px;
            text-align: center;
            color: #333;
            margin-top: 10px;
            font-family: Arial, Helvetica, sans-serif !important;
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
            font-family: Arial, Helvetica, sans-serif !important;
        }

        .signature-area {
            border-top: 1px solid #333;
            width: 60%;
            margin: 5px auto;
            text-align: center;
            font-size: 6px;
            color: #666;
            font-family: Arial, Helvetica, sans-serif !important;
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

        /* Impression forcée des polices */
        .force-font {
            font-family: Arial, Helvetica, sans-serif !important;
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

            /* Forcer les polices Arial pour l'impression */
            * {
                font-family: Arial, Helvetica, sans-serif !important;
            }

            .carte-verso,
            .carte-verso * {
                font-family: 'Montserrat', sans-serif !important;
                letter-spacing: 0;
                -webkit-font-smoothing: antialiased;
                text-rendering: optimizeLegibility;
            }

            .carte-verso {
                transform: translateZ(0);
                backface-visibility: hidden;
            }

            .force-font {
                white-space: nowrap;
                font-kerning: normal;
                font-feature-settings: "kern" 1;
            }



        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
@endpush

@section('content')
    <div class="col-lg-9">
        <div class="rbt-dashboard-content bg-color-white rbt-shadow-box">
            <div class="content">
                <div class="section-title">
                    <h4 class="rbt-title-style-3">Votre Carte de Membre UNAMEPCI</h4>
                    {{-- <p class="text-muted">Générée et prête à être téléchargée</p> --}}
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
                                                <div class="info-value"> {{ formatDate02($carteMembre->date_delivre) }}
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
                                <!-- Verso de la carte -->
                                <div class="carte-verso" id="carteVerso">
                                    <div class="verso-content">
                                        <!-- Logo UNAMEPCI -->
                                        <div class="logo-unamepci">
                                            {{-- <div class="force-font"
                                                style="font-size: 14px; font-weight: bold; color: #0056b3; text-align: center;"> --}}
                                            <div class="force-font"
                                                style="font-size: 14px; font-weight: bold; color: #0056b3; text-align: center; font-family: 'Arial', 'Helvetica', sans-serif !important;">
                                                UNAMEPCI
                                            </div>
                                            <div class="force-font"
                                                style="font-size: 6px; text-align: center; color: #666;">
                                                Union Nationale des Médecins Privés de Côte d'Ivoire
                                            </div>
                                        </div>

                                        <!-- QR Code (simulé) -->
                                        <div class="qr-code">
                                            <div class="force-font"
                                                style="font-size: 8px; color: #333; text-align: center; padding: 5px;">
                                                {{-- QR CODE<br> --}}
                                                {{ QrCode::size(35)->generate($mutualiste->nom) }}
                                            </div>
                                        </div>

                                        <!-- Code-barres -->
                                        <div class="barcode">
                                            {{ substr($numCarte, 0, 15) }}
                                        </div>

                                        <!-- Informations de validité -->
                                        <div class="validity-info">
                                            Valide jusqu'au : {{ formatDate02($carteMembre->date_expiration) }}
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

                    {{-- <p class="flip-hint">← Survolez la carte pour voir le verso →</p> --}}
                    <p class="flip-hint">← Cliquez ou survolez la carte pour voir le verso →</p>
                </div>

                <!-- Section téléchargement -->
                <div class="download-section no-print" style="display: none">
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

                        <button class="btn-download" onclick="printCard()">
                            <i class="fas fa-print"></i> Imprimer
                        </button>
                    </div>

                    <div style="margin-top: 30px; font-size: 12px; color: #666;">
                        <i class="fas fa-info-circle"></i>
                        <strong>Format standard :</strong> 85.6 x 54 mm (ISO/IEC 7810 ID-1)
                    </div>
                </div>

                <!-- Instructions -->
                <div class="instructions no-print" style="display: none">
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
    <!-- Bibliothèques externes -->
    {{-- <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script>
        // Initialiser jsPDF
        const {
            jsPDF
        } = window.jspdf;

        // Stocker les données PHP dans des variables JavaScript
        const mutualisteData = {
            nom: "{{ strtoupper($mutualiste->nom) }}",
            prenom: "{{ strtoupper($mutualiste->prenom) }}",
            matricule: "{{ strtoupper($mutualiste->matricule) }}",
            specialite: "{{ strtoupper($mutualiste->specialite->libelle) }}",
            dateNaissance: "{{ date('d/m/Y', strtotime($mutualiste->date_naissance)) }}",
            photo: @if ($mutualiste->lien_photo && file_exists(public_path($mutualiste->lien_photo)))
                "{{ asset($mutualiste->lien_photo) }}"
            @else
                null
            @endif
        };

        // Numéro de carte
        const numCarte = "{{ $numCarte }}";

        // Fonction pour télécharger le recto en image
        function downloadRectoImage() {
            const carteRecto = document.getElementById('carteRecto');
            const button = event.target;
            const originalText = button.innerHTML;

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
                link.click();

                button.innerHTML = originalText;
                button.disabled = false;
                showSuccessMessage('Recto téléchargé avec succès!');
            }).catch(error => {
                console.error('Erreur:', error);
                button.innerHTML = originalText;
                button.disabled = false;
                alert('Erreur lors de la génération de l\'image');
            });
        }

        // Fonction pour télécharger le verso en image
        function downloadVersoImage() {
            const carteVerso = document.getElementById('carteVerso');
            const button = event.target;
            const originalText = button.innerHTML;

            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Génération...';
            button.disabled = true;

            html2canvas(carteVerso, {
                  //scale: 3,
                 //backgroundColor: null,
                scale: 4, // meilleure précision
                backgroundColor: "#ffffff", // pas null
                useCORS: true,
                //allowTaint: true,
                allowTaint: false,

                //foreignObjectRendering: true,
               imageTimeout: 15000,
                logging: false,
                removeContainer: true
            }).then(canvas => {
                const link = document.createElement('a');
                link.download = `carte_unamepci_verso_${getTimestamp()}.png`;
                link.href = canvas.toDataURL('image/png');
                link.click();

                button.innerHTML = originalText;
                button.disabled = false;
                showSuccessMessage('Verso téléchargé avec succès!');
            }).catch(error => {
                console.error('Erreur:', error);
                button.innerHTML = originalText;
                button.disabled = false;
                alert('Erreur lors de la génération de l\'image');
            });
        }

        // Fonction pour télécharger recto et verso en images séparées
        function downloadBothImages() {
            const button = event.target;
            const originalText = button.innerHTML;

            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Génération...';
            button.disabled = true;

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
                // Télécharger le recto
                const linkRecto = document.createElement('a');
                linkRecto.download = `carte_unamepci_recto_${getTimestamp()}.png`;
                linkRecto.href = canvasRecto.toDataURL('image/png');
                linkRecto.click();

                // Puis télécharger le verso
                setTimeout(() => {
                    const linkVerso = document.createElement('a');
                    linkVerso.download = `carte_unamepci_verso_${getTimestamp()}.png`;
                    linkVerso.href = canvasVerso.toDataURL('image/png');
                    linkVerso.click();

                    button.innerHTML = originalText;
                    button.disabled = false;
                    showSuccessMessage('Recto et verso téléchargés avec succès!');
                }, 500);
            }).catch(error => {
                console.error('Erreur:', error);
                button.innerHTML = originalText;
                button.disabled = false;
                alert('Erreur lors de la génération des images');
            });
        }

        // Fonction pour télécharger en PDF
        function downloadPDF() {
            const button = event.target;
            const originalText = button.innerHTML;

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

                // Ajouter le recto
                pdf.addImage(imgDataRecto, 'PNG', 0, 0, 85.6, 54);

                // Ajouter une nouvelle page pour le verso
                pdf.addPage([85.6, 54], 'landscape');
                pdf.addImage(imgDataVerso, 'PNG', 0, 0, 85.6, 54);

                // Télécharger le PDF
                pdf.save(`carte_unamepci_${getTimestamp()}.pdf`);

                button.innerHTML = originalText;
                button.disabled = false;
                showSuccessMessage('PDF téléchargé avec succès!');
            }).catch(error => {
                console.error('Erreur:', error);
                button.innerHTML = originalText;
                button.disabled = false;
                alert('Erreur lors de la génération du PDF');
            });
        }

        // Fonction pour imprimer
        function printCard() {
            const button = event.target;
            const originalText = button.innerHTML;

            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Préparation...';
            button.disabled = true;

            // Ouvrir une nouvelle fenêtre
            const printWindow = window.open('', '_blank');

            // Créer le contenu HTML pour l'impression
            const printContent = `
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="UTF-8">
                    <title>Carte Membre UNAMEPCI - Impression</title>
                    <style>
                        body {
                            font-family: Arial, Helvetica, sans-serif;
                            margin: 0;
                            padding: 20px;
                            background: #f5f5f5;
                        }
                        .print-container {
                            display: flex;
                            flex-direction: column;
                            align-items: center;
                            gap: 20mm;
                        }
                        .carte-print {
                            width: 85.6mm;
                            height: 54mm;
                            border-radius: 10px;
                            overflow: hidden;
                            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
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
                    <div class="print-container">
                        <!-- Insérer le recto et verso tels quels -->
                        <div class="carte-print">
                            ${document.getElementById('carteRecto').outerHTML}
                        </div>
                        <div class="carte-print">
                            ${document.getElementById('carteVerso').outerHTML}
                        </div>
                    </div>
                    <script>
                        window.onload = function() {
                            window.print();
                            setTimeout(() => window.close(), 100);
                        };
                    <\/script>
                </body>
                </html>
            `;

            printWindow.document.open();
            printWindow.document.write(printContent);
            printWindow.document.close();

            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            }, 1000);
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
                font-family: Arial, sans-serif;
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
                    if (notification.parentNode) {
                        document.body.removeChild(notification);
                    }
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

            if (carteFlip && carteWrapper) {
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
            }

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
    </script> --}}


    <!-- Bibliothèques externes -->
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script>
        // Initialiser jsPDF
        const {
            jsPDF
        } = window.jspdf;

        // Stocker les données PHP dans des variables JavaScript
        const mutualisteData = {
            nom: "{{ strtoupper($mutualiste->nom) }}",
            prenom: "{{ strtoupper($mutualiste->prenom) }}",
            matricule: "{{ strtoupper($mutualiste->matricule) }}",
            specialite: "{{ strtoupper($mutualiste->specialite->libelle) }}",
            dateNaissance: "{{ date('d/m/Y', strtotime($mutualiste->date_naissance)) }}",
            photo: @if ($mutualiste->lien_photo && file_exists(public_path($mutualiste->lien_photo)))
                "{{ asset($mutualiste->lien_photo) }}"
            @else
                null
            @endif
        };

        // Numéro de carte
        const numCarte = "{{ $numCarte }}";

        // Variable pour suivre l'état de la carte (true = verso visible)
        let isCardFlipped = false;

        // Fonction pour basculer la carte
        function toggleCardFlip() {
            const carteWrapper = document.getElementById('carteWrapper');
            isCardFlipped = !isCardFlipped;

            if (isCardFlipped) {
                carteWrapper.style.transform = 'rotateY(180deg)';
            } else {
                carteWrapper.style.transform = 'rotateY(0deg)';
            }
        }

        // Initialisation des événements tactiles et hover
        document.addEventListener('DOMContentLoaded', function() {
            const carteFlip = document.querySelector('.carte-flip');
            const carteWrapper = document.getElementById('carteWrapper');

            if (!carteFlip || !carteWrapper) return;

            // Pour les écrans tactiles (mobile)
            carteFlip.addEventListener('click', function(e) {
                // Ne pas basculer si on clique sur un bouton de téléchargement
                if (e.target.closest('.btn-download')) {
                    return;
                }
                toggleCardFlip();
            });

            // Pour les écrans de bureau avec souris
            carteFlip.addEventListener('mouseenter', function() {
                if (window.innerWidth > 768) { // Seulement sur desktop
                    carteWrapper.style.transform = 'rotateY(180deg)';
                    isCardFlipped = true;
                }
            });

            carteFlip.addEventListener('mouseleave', function() {
                if (window.innerWidth > 768) { // Seulement sur desktop
                    carteWrapper.style.transform = 'rotateY(0deg)';
                    isCardFlipped = false;
                }
            });

            // Détection tactile pour améliorer l'expérience mobile
            let touchStartX = 0;
            let touchEndX = 0;

            carteFlip.addEventListener('touchstart', function(e) {
                touchStartX = e.changedTouches[0].screenX;
            }, {
                passive: true
            });

            carteFlip.addEventListener('touchend', function(e) {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipeGesture();
            }, {
                passive: true
            });

            function handleSwipeGesture() {
                const swipeThreshold = 50; // seuil en pixels pour détecter un swipe
                const diff = touchStartX - touchEndX;

                // Swipe gauche pour voir le verso
                if (Math.abs(diff) > swipeThreshold) {
                    if (diff > 0 && !isCardFlipped) {
                        // Swipe gauche, montre le verso
                        toggleCardFlip();
                    } else if (diff < 0 && isCardFlipped) {
                        // Swipe droite, retour au recto
                        toggleCardFlip();
                    }
                }
            }

            // Mise à jour du texte d'indication selon le support
            const flipHint = document.querySelector('.flip-hint');
            if (flipHint) {
                if ('ontouchstart' in window || navigator.maxTouchPoints) {
                    // C'est un appareil tactile
                    flipHint.innerHTML = '← Cliquez ou glissez pour voir le verso →';
                } else {
                    // C'est un ordinateur
                    flipHint.innerHTML = '← Survolez la carte pour voir le verso →';
                }
            }

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

        // Fonctions de téléchargement (inchangées)
        function downloadRectoImage() {
            const carteRecto = document.getElementById('carteRecto');
            const button = event.target;
            const originalText = button.innerHTML;

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
                link.click();

                button.innerHTML = originalText;
                button.disabled = false;
                showSuccessMessage('Recto téléchargé avec succès!');
            }).catch(error => {
                console.error('Erreur:', error);
                button.innerHTML = originalText;
                button.disabled = false;
                alert('Erreur lors de la génération de l\'image');
            });
        }

        function downloadVersoImage() {
            const carteVerso = document.getElementById('carteVerso');
            const button = event.target;
            const originalText = button.innerHTML;

            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Génération...';
            button.disabled = true;

            html2canvas(carteVerso, {
                scale: 4,
                backgroundColor: "#ffffff",
                useCORS: true,
                allowTaint: false,
                imageTimeout: 15000,
                logging: false,
                removeContainer: true
            }).then(canvas => {
                const link = document.createElement('a');
                link.download = `carte_unamepci_verso_${getTimestamp()}.png`;
                link.href = canvas.toDataURL('image/png');
                link.click();

                button.innerHTML = originalText;
                button.disabled = false;
                showSuccessMessage('Verso téléchargé avec succès!');
            }).catch(error => {
                console.error('Erreur:', error);
                button.innerHTML = originalText;
                button.disabled = false;
                alert('Erreur lors de la génération de l\'image');
            });
        }

        function downloadBothImages() {
            const button = event.target;
            const originalText = button.innerHTML;

            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Génération...';
            button.disabled = true;

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
                const linkRecto = document.createElement('a');
                linkRecto.download = `carte_unamepci_recto_${getTimestamp()}.png`;
                linkRecto.href = canvasRecto.toDataURL('image/png');
                linkRecto.click();

                setTimeout(() => {
                    const linkVerso = document.createElement('a');
                    linkVerso.download = `carte_unamepci_verso_${getTimestamp()}.png`;
                    linkVerso.href = canvasVerso.toDataURL('image/png');
                    linkVerso.click();

                    button.innerHTML = originalText;
                    button.disabled = false;
                    showSuccessMessage('Recto et verso téléchargés avec succès!');
                }, 500);
            }).catch(error => {
                console.error('Erreur:', error);
                button.innerHTML = originalText;
                button.disabled = false;
                alert('Erreur lors de la génération des images');
            });
        }

        function downloadPDF() {
            const button = event.target;
            const originalText = button.innerHTML;

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

                button.innerHTML = originalText;
                button.disabled = false;
                showSuccessMessage('PDF téléchargé avec succès!');
            }).catch(error => {
                console.error('Erreur:', error);
                button.innerHTML = originalText;
                button.disabled = false;
                alert('Erreur lors de la génération du PDF');
            });
        }

        function printCard() {
            const button = event.target;
            const originalText = button.innerHTML;

            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Préparation...';
            button.disabled = true;

            const printWindow = window.open('', '_blank');

            const printContent = `
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <title>Carte Membre UNAMEPCI - Impression</title>
                <style>
                    body {
                        font-family: Arial, Helvetica, sans-serif;
                        margin: 0;
                        padding: 20px;
                        background: #f5f5f5;
                    }
                    .print-container {
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        gap: 20mm;
                    }
                    .carte-print {
                        width: 85.6mm;
                        height: 54mm;
                        border-radius: 10px;
                        overflow: hidden;
                        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
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
                <div class="print-container">
                    <div class="carte-print">
                        ${document.getElementById('carteRecto').outerHTML}
                    </div>
                    <div class="carte-print">
                        ${document.getElementById('carteVerso').outerHTML}
                    </div>
                </div>
                <script>
                    window.onload = function() {
                        window.print();
                        setTimeout(() => window.close(), 100);
                    };
                <\/script>
            </body>
            </html>
        `;

            printWindow.document.open();
            printWindow.document.write(printContent);
            printWindow.document.close();

            setTimeout(() => {
                button.innerHTML = originalText;
                button.disabled = false;
            }, 1000);
        }

        function getTimestamp() {
            const now = new Date();
            return now.getFullYear() +
                String(now.getMonth() + 1).padStart(2, '0') +
                String(now.getDate()).padStart(2, '0') + '_' +
                String(now.getHours()).padStart(2, '0') +
                String(now.getMinutes()).padStart(2, '0');
        }

        function showSuccessMessage(message) {
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
            font-family: Arial, sans-serif;
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
    </script>
@endpush
