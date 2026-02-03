@extends('layouts.home_dashboard', ['title' => 'Espace de Contribution '])
@push('css')
    <link rel="stylesheet" href="{{ asset('mutualiste/styledatatable.css') }}">
@endpush
@section('content')
    <div class="col-lg-9">
        <!-- Start Enrole Course  -->
        <div class="rbt-dashboard-content bg-color-white rbt-shadow-box">
            <div class="content">
                <div class="section-title">
                    <h4 class="rbt-title-style-3">Mes Cotisations
                    </h4>
                </div>
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <a href="{{ route('Cotisation.mutualiste') }}" class="rbt-btn btn-sm">
                            Retour
                        </a>
                    </div>
                </div>
                <div class="rbt-dashboard-filter-wrapper">
                    <h5 class="text-center"> Mes paiement de la cotisation : {{ $libelleCotisation }}</h5>
                    <div class="rbt-dashboard-table table-responsive mobile-table-750">
                        <table id="datatable-buttons" class="rbt-table table table-borderless">
                            <thead>
                                <tr>
                                    <th>Montant</th>
                                    <th>Date Paiement</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($PaiementCotisations as $cotisationMutualiste)
                                    <tr>

                                        <td>
                                            <p class="b3">{{ formatMontant($cotisationMutualiste->montant_initial ?? $cotisationMutualiste->montant ) }}</p>
                                        </td>
                                        <td>
                                            <p class="b3 mb--5">{{ datePaiementCotisa($cotisationMutualiste->updated_at) }}
                                            </p>
                                        </td>
                                        <td>
                                            <span
                                                style="color: rgba(54, 143, 54, 0.615); background-color: rgba(54, 143, 54, 0.1); border: 1px solid rgba(54, 143, 54, 0.615); padding: 1px; border-radius: 5px;">
                                                SOLDE
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- End Enrole Course  -->
        </div>
    </div>
@endsection
