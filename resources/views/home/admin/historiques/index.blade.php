@extends('layouts.home_dashboard', ['title' => 'page des paiement effectuer '])
@push('css')
    <!-- Inclure le CSS de DataTables -->
    <link rel="stylesheet" href="{{ asset('mutualiste/styledatatable.css') }}">
@endpush

@section('content')
    <div class="col-lg-9">
        <div class="rbt-dashboard-content bg-color-white rbt-shadow-box">
            <div class="content">
                <div class="section-title">
                    <h4 class="rbt-title-style-3">Historique de vos Transactions</h4>
                </div>
                <div class="rbt-dashboard-table table-responsive mobile-table-750">
                    <table id="datatable-buttons" class="rbt-table table table-borderless">
                        <thead>
                            <tr>
                                <th>Redevance</th>
                                <th>Reference</th>
                                <th>Moyen</th>
                                <th>Montant</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($paiements as $paiement)
                                <tr>
                                    <td>
                                        @if ($paiement->type_paiement_id == 1)
                                            <span class="h6 mb-5">{{ $paiement->typePaiement->libelle }}</span>
                                        @elseif($paiement->type_paiement_id == 4)
                                            @php
                                                $libelleRedevance = '';
                                                for ($i = 0; $i < count($facturations); $i++) {
                                                    if ($facturations[$i]->id == $paiement->correspondance_id) {
                                                        $libelleRedevance = $facturations[$i]->redevance->libelle; // Met à jour la variable à chaque correspondance
                                                    }
                                                }
                                            @endphp
                                            @if (!empty($libelleRedevance))
                                                <span class="h6 mb-5">{{ $libelleRedevance }}</span>
                                            @endif
                                        @elseif($paiement->type_paiement_id == 2)
                                            @php
                                                $libelleCotis = '';
                                                for ($i = 0; $i < count($cotisationMutualistes); $i++) {
                                                    if (
                                                        $cotisationMutualistes[$i]->id == $paiement->correspondance_id
                                                    ) {
                                                        $libelleCotis = $cotisationMutualistes[$i]->cotisation->libelle; // Met à jour la variable à chaque correspondance
                                                    }
                                                }
                                            @endphp
                                            @if (!empty($libelleCotis))
                                                <span class="h6 mb-5">{{ $libelleCotis }}</span>
                                            @endif
                                        @elseif($paiement->type_paiement_id == 3)
                                            @php
                                                $pret = '';
                                                for ($i = 0; $i < count($accompagnements); $i++) {
                                                    if ($accompagnements[$i]->id == $paiement->correspondance_id) {
                                                        $pret = $accompagnements[$i]->service->libelle; // Met à jour la variable à
                                                    }
                                                }
                                            @endphp
                                            @if (!empty($pret))
                                                <span class="h6 mb-5">
                                                    {{ Str::limit($pret, 9) }}
                                                </span>
                                            @endif
                                        @else
                                            <span class="h6 mb-5">
                                                {{ $paiement->typePaiement->libelle ?? '' }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @if (!empty($paiement->reference))
                                            {{-- {{ $paiement->reference }} --}}
                                            {{ Str::limit($paiement->reference, 9) }}
                                        @else
                                            {{-- {{ $paiement->code_paiement }} --}}
                                            {{ Str::limit($paiement->code_paiement, 9) }}
                                        @endif
                                    </td>
                                    <td>{{ !empty($paiement->moyen_paiement) ? $paiement->moyen_paiement : 'Non défini' }}
                                    </td>
                                    <td>{{ formatMontant($paiement->montant_initial) }}</td>
                                    <td>{{ !empty($paiement->date_paiement_initial) ? formatDate($paiement->date_paiement_initial) : 'Aucune date' }}
                                    </td>
                                    <td>
                                        @if ($paiement->status == 2)
                                            <span
                                                style="color: blue; background-color: rgba(0, 0, 255, 0.3); border: 1px solid rgb(18, 18, 209); padding: 1px; border-radius: 5px;">
                                                EN ATTENTE
                                            </span>
                                        @elseif($paiement->status == 3)
                                            <span
                                                style="color: red; background-color: rgba(255, 0, 0, 0.455)); border: 1px solid rgba(255, 0, 0, 0.455); padding: 1px; border-radius: 5px;">
                                                ECHEC
                                            </span>
                                        @else
                                            <span
                                                style="color: rgba(54, 143, 54, 0.615); background-color: rgba(54, 143, 54, 0.1); border: 1px solid rgba(54, 143, 54, 0.615); padding: 1px; border-radius: 5px;">
                                                VALIDE
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-start">
                                            @if ($paiement->status == 1)
                                                <a class="rbt-btn btn-xs bg-primary-opacity radius-round mx-1"
                                                    target="target" href="{{ route('telecharger.recu', $paiement->id) }}"
                                                    title="Télécharger">
                                                    <i class="feather-download"></i>
                                                </a>
                                            @endif
                                            @if (!empty($paiement->p_cash))
                                                <a href="{{ route('docs.paiement', $paiement->id) }}"
                                                    class="rbt-btn btn-xs bg-primary-opacity radius-round mx-1"
                                                    target="target">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                        fill="currentColor" class="bi bi-card-image" viewBox="0 0 16 16">
                                                        <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0" />
                                                        <path
                                                            d="M1.5 2A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2zm13 1a.5.5 0 0 1 .5.5v6l-3.775-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12v.54L1 12.5v-9a.5.5 0 0 1 .5-.5z" />
                                                    </svg>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        function removeIdOnMobile() {
            const table = document.getElementById("datatable-buttons");
            if (window.innerWidth < 768 && table) {
                table.removeAttribute("id");
            }
        }

        removeIdOnMobile();
        window.addEventListener("resize", removeIdOnMobile);
    </script>
@endpush
