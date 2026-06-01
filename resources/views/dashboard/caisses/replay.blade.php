@extends('layouts.dashboard', ['title' => 'Résultat du paiement', 'toolbar' => '_toolbar2', 'breadcrumb' => 'Caisse'])

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/dashboard/cssbundle/dataTables.min.css') }}">
    <style>
        /* ====== CARD PRINCIPALE ====== */
        .payment-result-card {
            background: linear-gradient(145deg, #ffffff 0%, #f8f9fc 100%);
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08), 0 8px 20px rgba(0, 0, 0, 0.04);
            padding: 40px 32px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.6);
        }

        .payment-result-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at 30% 40%, rgba(99, 102, 241, 0.03) 0%, transparent 60%);
            pointer-events: none;
        }

        /* ====== TITRE ====== */
        .payment-title {
            font-size: 1.75rem;
            font-weight: 700;
            letter-spacing: -0.02em;
            background: linear-gradient(135deg, #1e293b, #475569);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }

        .payment-subtitle {
            color: #94a3b8;
            font-size: 0.95rem;
            font-weight: 400;
        }

        .payment-divider {
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, #6366f1, #8b5cf6);
            border-radius: 4px;
            margin: 0.75rem auto 1.5rem auto;
        }

        /* ====== SYMBOLES SUCCÈS / ÉCHEC ====== */
        .result-symbol-wrapper {
            width: 180px;
            height: 180px;
            margin: 0 auto 24px auto;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            position: relative;
        }

        .result-symbol-wrapper.success-bg {
            background: radial-gradient(circle at 35% 35%, rgba(34, 197, 94, 0.12), rgba(34, 197, 94, 0.04) 70%);
            box-shadow: 0 0 40px rgba(34, 197, 94, 0.08);
        }

        .result-symbol-wrapper.failure-bg {
            background: radial-gradient(circle at 35% 35%, rgba(239, 68, 68, 0.12), rgba(239, 68, 68, 0.04) 70%);
            box-shadow: 0 0 40px rgba(239, 68, 68, 0.08);
        }

        .success-symbol,
        .failure-symbol {
            width: 140px;
            height: 140px;
            animation: float 2.5s ease-in-out infinite;
            filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.06));
        }

        .success-symbol {
            fill: #22c55e;
        }

        .failure-symbol {
            fill: #ef4444;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        /* ====== CODE / STATUT ====== */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 24px;
            border-radius: 100px;
            font-weight: 600;
            font-size: 1rem;
            letter-spacing: 0.02em;
            margin-bottom: 12px;
        }

        .status-badge.success {
            background: linear-gradient(135deg, #dcfce7, #bbf7d0);
            color: #166534;
            border: 1px solid rgba(34, 197, 94, 0.25);
            box-shadow: 0 2px 8px rgba(34, 197, 94, 0.12);
        }

        .status-badge.failure {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #991b1b;
            border: 1px solid rgba(239, 68, 68, 0.25);
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.12);
        }

        .status-badge .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            animation: pulse-dot 1.5s ease-in-out infinite;
        }

        .status-badge.success .dot {
            background: #22c55e;
        }

        .status-badge.failure .dot {
            background: #ef4444;
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.85); }
        }

        .status-message {
            font-size: 1.1rem;
            color: #334155;
            font-weight: 500;
            background: rgba(248, 250, 252, 0.8);
            padding: 12px 20px;
            border-radius: 12px;
            backdrop-filter: blur(4px);
            border: 1px solid rgba(226, 232, 240, 0.6);
            max-width: 90%;
            margin: 0 auto;
        }

        /* ====== SPINNER ====== */
        .spinner-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .custom-spinner {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            border: 6px solid #e2e8f0;
            border-top-color: #6366f1;
            animation: spin 0.9s cubic-bezier(0.45, 0.05, 0.55, 0.95) infinite;
            margin-bottom: 24px;
            box-shadow: 0 0 20px rgba(99, 102, 241, 0.08);
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .spinner-text {
            font-size: 1.05rem;
            font-weight: 500;
            color: #475569;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: fade-pulse 1.8s ease-in-out infinite;
        }

        @keyframes fade-pulse {
            0%, 100% { opacity: 0.7; }
            50% { opacity: 1; }
        }

        /* ====== BOUTON RETOUR ====== */
        .btn-back-custom {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 32px;
            background: linear-gradient(135deg, #1e293b, #334155);
            color: #fff;
            font-weight: 600;
            font-size: 0.95rem;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 4px 14px rgba(30, 41, 59, 0.2);
            margin-top: 28px;
        }

        .btn-back-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(30, 41, 59, 0.25);
            background: linear-gradient(135deg, #0f172a, #1e293b);
            color: #fff;
        }

        .btn-back-custom svg {
            width: 20px;
            height: 20px;
            transition: transform 0.2s ease;
        }

        .btn-back-custom:hover svg {
            transform: translateX(-4px);
        }

        /* ====== RESPONSIVE ====== */
        @media (max-width: 768px) {
            .payment-result-card {
                padding: 28px 20px;
                border-radius: 20px;
            }

            .result-symbol-wrapper {
                width: 130px;
                height: 130px;
            }

            .success-symbol,
            .failure-symbol {
                width: 100px;
                height: 100px;
            }

            .payment-title {
                font-size: 1.4rem;
            }

            .status-message {
                font-size: 0.95rem;
                padding: 10px 16px;
            }

            .btn-back-custom {
                padding: 12px 24px;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 576px) {
            .payment-result-card {
                padding: 20px 14px;
                border-radius: 16px;
            }

            .result-symbol-wrapper {
                width: 100px;
                height: 100px;
            }

            .success-symbol,
            .failure-symbol {
                width: 76px;
                height: 76px;
            }

            .payment-title {
                font-size: 1.2rem;
            }

            .status-badge {
                font-size: 0.85rem;
                padding: 6px 16px;
            }

            .status-message {
                font-size: 0.85rem;
            }

            .custom-spinner {
                width: 56px;
                height: 56px;
                border-width: 5px;
            }

            .btn-back-custom {
                padding: 10px 20px;
                font-size: 0.85rem;
                width: 100%;
                justify-content: center;
            }
        }

        /* ====== UTILS ====== */
        .rbt-counterup.variation-01 {
            border: none !important;
            background: transparent !important;
        }

        .rbt-counterup .inner > a {
            text-decoration: none;
        }
    </style>
@endpush

@section('content')
    <div class="col-lg-9 mx-auto">
        <div class="rbt-dashboard-content bg-color-white rbt-shadow-box mb--60">
            <div class="content">
                {{-- Titre --}}
                <div class="text-center mb-4">
                    <h4 class="payment-title">Résultat du Paiement</h4>
                    <p class="payment-subtitle">Statut de votre transaction en cours</p>
                    <div class="payment-divider"></div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-8 col-md-10 col-sm-12 col-12">
                        <div class="payment-result-card text-center">

                            {{-- Champs cachés --}}
                            <input type="hidden" value="{{ $ind }}" id="indexCont">
                            <input type="hidden"
                                   value="{{ $paiementinit->code_paiement ?? $codePaiement }}"
                                   id="codeP">

                            {{-- Contenu principal --}}
                            @if ($ind < 15 && $code == 203)
                                {{-- Spinner --}}
                                <div class="spinner-wrapper">
                                    <div class="custom-spinner"></div>
                                    <h6 class="spinner-text">{{ $mess ?? 'Traitement en cours...' }}</h6>
                                </div>

                            @else
                                {{-- Succès ou échec --}}
                                @if ($code == 200 || $code == 201)
                                    @if ($code == 200)
                                        <div class="result-symbol-wrapper success-bg">
                                            <svg class="success-symbol" viewBox="0 0 24 24">
                                                <path d="M12 0C5.371 0 0 5.371 0 12s5.371 12 12 12 12-5.371 12-12S18.629 0 12 0zm-1.27 18L5 11.27l1.41-1.41 4.32 4.32 7.88-7.88L20 8.12l-9.27 9.27z"/>
                                            </svg>
                                        </div>
                                        <span class="status-badge success">
                                            <span class="dot"></span>
                                            Paiement réussi
                                        </span>
                                    @elseif ($code == 201)
                                        <div class="result-symbol-wrapper failure-bg">
                                            <svg class="failure-symbol" viewBox="0 0 24 24">
                                                <path d="M12 0C5.371 0 0 5.371 0 12s5.371 12 12 12 12-5.371 12-12S18.629 0 12 0zm5.656 15.656l-1.414 1.414L12 13.414l-4.242 4.242-1.414-1.414L10.586 12 6.343 7.757l1.414-1.414L12 10.586l4.242-4.243 1.414 1.414L13.414 12l4.242 4.242z"/>
                                            </svg>
                                        </div>
                                        <span class="status-badge failure">
                                            <span class="dot"></span>
                                            Paiement échoué
                                        </span>
                                    @endif

                                    <div class="mt-3 mb-2">
                                        <span class="status-message">
                                            {{ $mess ?? 'Aucun message disponible' }}
                                        </span>
                                    </div>
                                @endif
                            @endif

                            {{-- Bouton retour --}}
                            <a href="{{ route('paiements.caisse') }}" class="btn-back-custom">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                                </svg>
                                Retour à la caisse
                            </a>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- Script de polling --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Premier script : déclenchement manuel ---
        const initPayBtn = document.querySelector("#init-pay");
        if (initPayBtn) {
            initPayBtn.addEventListener("click", function paymentINI() {
                setTimeout(state_pay, 10000);
            });
        }

        const refreshBtn = document.querySelector("#refreshID");

        function state_pay() {
            const v = $('#monInput').val();
            console.log('state_pay check:', v);
            if (v == 2) {
                setTimeout(state_pay, 10000);
                if (refreshBtn) refreshBtn.click();
            }
        }

        // --- Deuxième script : auto-redirection toutes les 50s ---
        const indexInput = document.getElementById('indexCont');
        const codePInput = document.getElementById('codeP');

        if (indexInput && codePInput) {
            let compteur = parseInt(indexInput.value, 10) || 0;
            const codePaiement = codePInput.value;

            function appelerRoute() {
                if (compteur < 15) {
                    compteur++;
                    indexInput.value = compteur;

                    const url = "{{ route('removePlay', ['codePaiement' => 'CODE_PLACEHOLDER', 'ind' => 'compteur']) }}"
                        .replace('CODE_PLACEHOLDER', codePaiement)
                        .replace('compteur', compteur);

                    window.location.href = url;
                }
            }

            setInterval(appelerRoute, 50000);
        }
    });
</script>
