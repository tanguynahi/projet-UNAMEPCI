@extends('layouts.home_dashboard', ['title' => 'Carte Membre en Attente'])

@section('content')
    <div class="col-lg-9">
        <div class="rbt-dashboard-content bg-color-white rbt-shadow-box">
            <div class="content">
                <div class="text-center py-5">
                    <!-- Icône d'attente -->
                    <div class="mb-4">
                        <div class="status-icon">
                            <i class="fas fa-clock fa-4x text-warning"></i>
                        </div>
                    </div>

                    <!-- Titre principal -->
                    <h2 class="rbt-title-style-3 text-primary mb-3">Votre carte est en cours de fabrication</h2>

                    <!-- Message d'information -->
                    <div class="alert alert-info mx-auto" style="max-width: 600px;">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle fa-2x me-3"></i>
                            <div>
                                <h5 class="alert-heading mb-2">Information importante</h5>
                                <p class="mb-0">Votre carte de membre professionnelle UNAMEPCI est actuellement en attente
                                    de fabrication.</p>
                            </div>
                        </div>
                    </div>


                    <div class="mt-5">
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                <div class="bg-light p-4 rounded">
                                    <div class="alert alert-warning mt-4 mb-0">
                                        <div class="d-flex">
                                            <i class="fas fa-exclamation-triangle me-3 mt-1"></i>
                                            <div>
                                                <strong>Durée estimée :</strong> Le processus de fabrication prend
                                                généralement 5 à 10 jours ouvrables. Vous serez informé dès que votre carte
                                                sera disponible.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->


                    <!-- Message d'encouragement -->
                    <div class="mt-5 pt-3">
                        <div class="text-center text-muted">
                            <i class="fas fa-heart text-danger me-2"></i>
                            Merci pour votre patience et votre confiance en l'UNAMEPCI
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de contact -->
    <div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="contactModalLabel">
                        <i class="fas fa-headset me-2"></i>Contacter le support
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">Pour toute question concernant votre carte :</h6>

                        <div class="contact-info">
                            <div class="d-flex align-items-center mb-3">
                                <div class="contact-icon bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                                    style="width: 40px; height: 40px;">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">Téléphone</div>
                                    <div class="text-muted">+225 27 22 44 55 66</div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center mb-3">
                                <div class="contact-icon bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                                    style="width: 40px; height: 40px;">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">Email</div>
                                    <div class="text-muted">support@unamepci.ci</div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center">
                                <div class="contact-icon bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                                    style="width: 40px; height: 40px;">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div>
                                    <div class="fw-bold">Adresse</div>
                                    <div class="text-muted">Plateau, Abidjan, Côte d'Ivoire</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-light border">
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i>
                            <strong>Horaires de contact :</strong> Lundi - Vendredi, 8h - 17h
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    <a href="mailto:support@unamepci.ci" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-2"></i>Envoyer un email
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- CSS additionnel -->
    <style>
        .status-icon {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.1);
                opacity: 0.8;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .process-step {
            padding: 15px;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .process-step:hover {
            background-color: rgba(13, 110, 253, 0.05);
            transform: translateY(-5px);
        }

        .step-number {
            font-weight: bold;
            font-size: 1.2rem;
        }

        .step-title {
            color: #333;
            font-weight: 600;
        }

        .contact-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .list-group-item {
            padding: 12px 0;
            background: transparent;
        }

        @media (max-width: 768px) {
            .status-icon i {
                font-size: 3rem;
            }

            .rbt-title-style-3 {
                font-size: 1.5rem;
            }
        }
    </style>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animation pour les étapes
            const steps = document.querySelectorAll('.process-step');
            steps.forEach((step, index) => {
                setTimeout(() => {
                    step.style.opacity = '1';
                    step.style.transform = 'translateY(0)';
                }, index * 200);
            });

            // Initialiser l'opacité
            steps.forEach(step => {
                step.style.opacity = '0';
                step.style.transform = 'translateY(20px)';
                step.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            });
        });
    </script>
@endpush
