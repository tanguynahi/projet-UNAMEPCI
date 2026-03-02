@extends('layouts.home_dashboard', ['title' => 'Paiement Carte Membre UNAMEPCI'])
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

        /* Conteneur principal avec la carte en background */
        .payment-container {
            position: relative;
            min-height: 700px;
            overflow: hidden;
        }

        /* Carte en arrière-plan */
        .background-card {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-5deg);
            width: 90%;
            max-width: 550px;
            opacity: 0.15;
            z-index: 1;
            pointer-events: none;
            filter: blur(0.5px);
        }

        .identity-card {
            background: linear-gradient(135deg, #ffffff 0%, #f5f5f5 100%);
            border: 3px solid #0056b3;
            border-radius: 15px;
            padding: 0;
            color: #333;
            position: relative;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            font-family: 'Arial', sans-serif;
        }

        .card-header {
            background: #0056b3;
            color: white;
            padding: 15px 20px;
            text-align: center;
        }

        .card-title {
            font-size: 22px;
            font-weight: bold;
            letter-spacing: 1px;
            margin: 0;
        }

        .card-subtitle {
            font-size: 12px;
            opacity: 0.9;
            margin-top: 3px;
        }

        .card-body {
            padding: 25px;
            display: flex;
            gap: 20px;
        }

        .photo-section {
            width: 120px;
            text-align: center;
        }

        .avatar {
            width: 110px;
            height: 140px;
            background: #e0e0e0;
            border: 2px solid #0056b3;
            border-radius: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }

        .avatar-icon {
            font-size: 40px;
            color: #666;
        }

        .photo-label {
            font-size: 11px;
            color: #666;
            font-style: italic;
        }

        .info-section {
            flex: 1;
        }

        .info-row {
            display: flex;
            margin-bottom: 12px;
            border-bottom: 1px dotted #ddd;
            padding-bottom: 5px;
        }

        .info-label {
            font-weight: bold;
            color: #0056b3;
            width: 140px;
            font-size: 13px;
        }

        .info-value {
            flex: 1;
            font-size: 13px;
            letter-spacing: 1px;
        }

        .masked-info {
            font-family: 'Courier New', monospace;
            color: #333;
            font-weight: bold;
        }

        .card-number-display {
            background: #f0f0f0;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
            margin-top: 15px;
            border: 1px dashed #0056b3;
        }

        .card-number {
            font-family: 'Courier New', monospace;
            font-size: 18px;
            font-weight: bold;
            color: #0056b3;
            letter-spacing: 2px;
        }

        .card-footer {
            background: #f8f9fa;
            padding: 15px 25px;
            border-top: 2px solid #e0e0e0;
            font-size: 11px;
            color: #666;
            text-align: center;
        }

        .watermark {
            position: absolute;
            bottom: 20px;
            right: 20px;
            font-size: 60px;
            color: rgba(0, 86, 179, 0.08);
            font-weight: bold;
            transform: rotate(-15deg);
        }

        /* Section paiement au premier plan */
        .foreground-content {
            position: relative;
            z-index: 10;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 40px;
            margin: 0 auto;
            max-width: 600px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .payment-section {
            text-align: center;
        }

        .amount-display {
            font-size: 48px;
            font-weight: bold;
            color: #0056b3;
            margin: 30px 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
            background: linear-gradient(135deg, #0056b3, #007bff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .btn-payment {
            background: linear-gradient(135deg, #0056b3 0%, #003d82 100%);
            color: white;
            border: none;
            padding: 18px 50px;
            font-size: 20px;
            font-weight: bold;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 8px 20px rgba(0, 86, 179, 0.4);
            margin: 30px 0 20px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-payment:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0, 86, 179, 0.5);
        }

        .btn-payment:active {
            transform: translateY(-2px);
        }

        .info-note {
            font-size: 14px;
            color: #666;
            margin: 20px 0;
            font-style: italic;
            line-height: 1.6;
        }

        .preview-title {
            font-size: 18px;
            color: #0056b3;
            margin-bottom: 15px;
            font-weight: bold;
            text-align: center;
        }

        .preview-text {
            font-size: 14px;
            color: #666;
            text-align: center;
            margin-bottom: 30px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin: 40px 0;
        }

        .feature-item {
            text-align: center;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            border: 1px solid #e9ecef;
            transition: all 0.3s;
        }

        .feature-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-color: #0056b3;
        }

        .feature-icon {
            font-size: 30px;
            color: #0056b3;
            margin-bottom: 15px;
        }

        .feature-title {
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
        }

        .feature-desc {
            font-size: 12px;
            color: #666;
            line-height: 1.4;
        }

        @media (max-width: 768px) {
            .foreground-content {
                padding: 25px;
                margin: 20px;
            }

            .amount-display {
                font-size: 36px;
            }

            .btn-payment {
                padding: 15px 30px;
                font-size: 18px;
                width: 100%;
                justify-content: center;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .background-card {
                opacity: 0.1;
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    <div class="col-lg-9">
        <div class="rbt-dashboard-content bg-color-white rbt-shadow-box">
            <div class="content">
                <div class="section-title">
                    <h4 class="rbt-title-style-3">Carte de Membre UNAMEPCI</h4>
                    {{-- <p class="text-muted">Paiement de votre cotisation annuelle</p> --}}
                </div>

                <!-- Conteneur avec carte en background -->
                <div class="payment-container">
                    <!-- Carte en arrière-plan -->
                    <div class="background-card">

                        <div class="identity-card">
                            <div class="card-header">
                                <div class="card-title">UNAMEPCI</div>
                                <div class="card-subtitle">CARTE DE MEMBRE PROFESSIONNEL</div>
                            </div>

                            <div class="card-body">
                                <!-- Section photo -->
                                <div class="photo-section">
                                    <div class="avatar">
                                        <i class="fas fa-user-md avatar-icon"></i>
                                    </div>
                                    <div class="photo-label">PHOTO IDENTITE</div>
                                </div>

                                <!-- Section informations -->
                                <div class="info-section">
                                    <div class="info-row">
                                        <div class="info-label">NOM :</div>
                                        <div class="info-value masked-info">XXXXXXXXXXX</div>
                                    </div>

                                    <div class="info-row">
                                        <div class="info-label">PRENOMS :</div>
                                        <div class="info-value masked-info">XXXXXXXXXXX</div>
                                    </div>

                                    <div class="info-row">
                                        <div class="info-label">MATRICULE :</div>
                                        <div class="info-value masked-info">UPCI-{{ date('y') }}-XXXX</div>
                                    </div>

                                    <div class="info-row">
                                        <div class="info-label">SPECIALITE :</div>
                                        <div class="info-value masked-info">XXXXXXXXXXX</div>
                                    </div>

                                    <div class="info-row">
                                        <div class="info-label">DATE NAISS. :</div>
                                        <div class="info-value masked-info">XX/XX/XXXX</div>
                                    </div>

                                    <div class="info-row">
                                        <div class="info-label">DELIVREE LE :</div>
                                        <div class="info-value">{{ date('d/m/Y') }}</div>
                                    </div>

                                    <div class="info-row">
                                        <div class="info-label">VALIDITE :</div>
                                        <div class="info-value">Jusqu'au {{ date('d/m/Y', strtotime('+1 year')) }}</div>
                                    </div>

                                    <div class="card-number-display">
                                        <div class="card-number">
                                            @php
                                                $cardNumber = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
                                                echo 'UPCI-' . date('y') . '-' . $cardNumber;
                                            @endphp
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <div>Cette carte est la propriété de l'UNAMEPCI - Toute falsification est punie par la loi
                                </div>
                            </div>

                            <!-- Filigrane -->
                            <div class="watermark">UNAMEPCI</div>
                        </div>
                    </div>

                    <!-- Contenu principal au premier plan -->
                    <div class="foreground-content">
                        <div class="payment-section">
                            {{-- <h5>Paiement de la cotisation annuelle</h5>
                            <p class="preview-text">Obtenez votre carte de membre professionnel UNAMEPCI</p> --}}

                            <!-- Aperçu de la carte -->
                            <div class="preview-title">
                                <i class="fas fa-id-card"></i> Votre carte personnalisée
                            </div>
                            <p class="preview-text">
                                Après paiement, votre carte sera fabriquée avec vos informations personnelles
                                et vous sera livrée à votre cabinet médical.
                            </p>

                            <!-- Montant -->
                            <div class="amount-display">
                                {{ number_format($carteMembre->montant, 0, ',', ' ') }} FCFA
                            </div>

                            <!-- Avantages -->
                            {{-- <div class="features-grid">
                                <div class="feature-item">
                                    <div class="feature-icon">
                                        <i class="fas fa-shield-alt"></i>
                                    </div>
                                    <div class="feature-title">Accréditation Officielle</div>
                                    <div class="feature-desc">Reconnaissance nationale de votre statut de médecin privé
                                    </div>
                                </div>

                                <div class="feature-item">
                                    <div class="feature-icon">
                                        <i class="fas fa-handshake"></i>
                                    </div>
                                    <div class="feature-title">Représentation</div>
                                    <div class="feature-desc">Défense de vos intérêts auprès des institutions</div>
                                </div>

                                <div class="feature-item">
                                    <div class="feature-icon">
                                        <i class="fas fa-graduation-cap"></i>
                                    </div>
                                    <div class="feature-title">Formations</div>
                                    <div class="feature-desc">Accès aux formations continues agréées</div>
                                </div>

                                <div class="feature-item">
                                    <div class="feature-icon">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div class="feature-title">Réseau</div>
                                    <div class="feature-desc">Intégration au réseau national des médecins privés</div>
                                </div>
                            </div> --}}

                            <div class="info-note">
                                <i class="fas fa-info-circle"></i>
                                Le paiement déclenchera la fabrication de votre carte personnalisée avec vos informations.

                            </div>

                            <!-- Bouton de paiement -->


                            {{-- <a href="javascript:void(0)" class="btn-payment" id="paymentButton"
                                data-url="{{ route('paiement.CarteMembres') }}">

                                <span id="buttonText">
                                    <i class="fas fa-credit-card"></i> Passer au paiement
                                </span>
                            </a> --}}

                            <form action="{{ route('espaPay', 5) }}" method="POST" id="paymentButton">
                                @csrf
                                @method('POST')
                                <input type="hidden" name="montant" value="{{ $carteMembre->montant }}">
                                <input type="hidden" name="idCarte" value="{{ $carteMembre->id }}">
                                <input type="hidden" name="libelle" value="{{ $carteMembre->libelle }}">
                                <button type="submit" class="btn-payment">
                                    <span id="buttonText">
                                        <i class="fas fa-credit-card"></i> Passer au paiement
                                    </span>
                                </button>
                            </form>


                            <!-- Sécurité -->
                            <div
                                style="margin-top: 30px; padding: 15px; background: #f0f8ff; border-radius: 10px; border: 1px solid #d1e7ff;">
                                <div style="display: flex; align-items: center; justify-content: center; gap: 10px;">
                                    <i class="fas fa-shield-alt" style="color: #28a745;"></i>
                                    <span style="font-size: 14px; color: #333;">
                                        Paiement 100% sécurisé - Données cryptées SSL
                                    </span>
                                </div>
                            </div>

                            <!-- Délais -->
                            <div style="margin-top: 25px; font-size: 13px; color: #666;">
                                <div style="display: flex; justify-content: center; gap: 30px; flex-wrap: wrap;">
                                    <div style="text-align: center;">
                                        <div style="color: #0056b3; font-weight: bold;">10 jours</div>
                                        <div style="font-size: 12px;">Fabrication de la carte</div>
                                    </div>
                                    <div style="text-align: center;">
                                        <div style="color: #0056b3; font-weight: bold;">48h</div>
                                        <div style="font-size: 12px;">Confirmation par email</div>
                                    </div>
                                    <div style="text-align: center;">
                                        <div style="color: #0056b3; font-weight: bold;">1 an</div>
                                        <div style="font-size: 12px;">Validité de la carte</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Assistance -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="alert alert-light border">
                            <h6 style="color: #0056b3;"><i class="fas fa-headset"></i> Besoin d'aide ?</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Service adhésions UNAMEPCI</strong></p>
                                    <p class="mb-1">📞 27 22 44 55 66</p>
                                    <p class="mb-0">📧 adhesion@unamepci.ci</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong>Horaires d'ouverture</strong></p>
                                    <p class="mb-1">Lundi - Vendredi : 8h - 17h</p>
                                    <p class="mb-0">Samedi : 9h - 13h</p>
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
        document.getElementById("paymentButton").addEventListener("click", function() {

            let btn = this;

            // Bloquer les doubles clics
            if (btn.dataset.clicked === "true") return;

            btn.dataset.clicked = "true";

            document.getElementById("buttonText").innerHTML =
                '<i class="fas fa-spinner fa-spin"></i> Redirection vers le paiement...';

            btn.style.pointerEvents = "none";
            btn.style.opacity = "0.6";

            // Redirection après verrouillage
            window.location.href = btn.dataset.url;
        });
    </script>


    <script>
        // document.getElementById('paymentForm').addEventListener('submit', function(e) {
        //     e.preventDefault();

        //     const button = document.getElementById('paymentButton');
        //     const buttonText = document.getElementById('buttonText');
        //     const loadingSpinner = document.getElementById('loadingSpinner');

        //     // Afficher l'animation de chargement
        //     buttonText.style.display = 'none';
        //     loadingSpinner.style.display = 'inline-block';
        //     button.disabled = true;

        //     // Animation de la carte en background
        //     const backgroundCard = document.querySelector('.background-card');
        //     backgroundCard.style.transition = 'all 0.5s ease';
        //     backgroundCard.style.transform = 'translate(-50%, -50%) rotate(0deg) scale(1.05)';
        //     backgroundCard.style.opacity = '0.2';

        //     // Simulation du traitement
        //     setTimeout(() => {
        //         // Envoyer le formulaire (en production, décommenter cette ligne)
        //         // this.submit();

        //         // Pour l'exemple, simuler une redirection
        //         const transactionId = 'UPCI-' + Date.now().toString().slice(-8);

        //         // Afficher une notification
        //         const notification = document.createElement('div');
        //         notification.className = 'alert alert-success alert-dismissible fade show mt-3';
        //         notification.innerHTML = `
    //             <h6><i class="fas fa-check-circle"></i> Paiement initié avec succès !</h6>
    //             <p>Numéro de transaction : <strong>${transactionId}</strong></p>
    //             <p>Vous allez être redirigé vers la plateforme de paiement sécurisé...</p>
    //             <button type="button" class="close" data-dismiss="alert">
    //                 <span>&times;</span>
    //             </button>
    //         `;
        //         document.querySelector('.payment-section').appendChild(notification);

        //         // Redirection vers la passerelle de paiement
        //         setTimeout(() => {
        //             // En production, rediriger vers la vraie URL
        //             // window.location.href = '/payment/gateway/' + transactionId;

        //             // Pour l'exemple, afficher un message
        //             alert('Redirection vers la plateforme de paiement...\n\nTransaction: ' +
        //                 transactionId);

        //             // Réinitialiser le bouton
        //             buttonText.style.display = 'inline-block';
        //             loadingSpinner.style.display = 'none';
        //             button.disabled = false;

        //             // Réinitialiser l'animation de la carte
        //             backgroundCard.style.transform = 'translate(-50%, -50%) rotate(-5deg) scale(1)';
        //             backgroundCard.style.opacity = '0.15';
        //         }, 2000);

        //     }, 1500);
        // });

        // Animation au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            const backgroundCard = document.querySelector('.background-card');
            backgroundCard.style.transition = 'opacity 1.5s ease, transform 1.5s ease';
            backgroundCard.style.opacity = '0.15';

            // Effet de pulsation sur le montant
            const amountDisplay = document.querySelector('.amount-display');
            setInterval(() => {
                amountDisplay.style.transform = 'scale(1.02)';
                setTimeout(() => {
                    amountDisplay.style.transform = 'scale(1)';
                }, 500);
            }, 3000);

            // Effet sur les éléments de features
            const featureItems = document.querySelectorAll('.feature-item');
            featureItems.forEach((item, index) => {
                item.style.animationDelay = `${index * 0.1}s`;
                item.classList.add('animate__animated', 'animate__fadeInUp');
            });
        });
    </script>
@endpush
