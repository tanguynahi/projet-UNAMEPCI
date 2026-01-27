@extends('layouts.home_dashboard', ['title' => 'Mes demandes '])
@push('css')
    <link rel="stylesheet" href="{{ asset('mutualiste/styledatatable.css') }}">

@endpush
@section('content')
    <div class="col-lg-9">
        <!-- Start Enrole Course  -->
        <div class="rbt-dashboard-content bg-color-white rbt-shadow-box">
            <div class="content">
                <div class="section-title">
                    <div class="row align-items-start">
                        <div class="col-10">
                            <h4 class="rbt-title-style-3">Mes Projet </h4>
                        </div>
                    </div>
                </div>
                    <div class="content">
                        <div class="rbt-dashboard-table table-responsive mobile-table-750">
                            <table id="datatable-buttons" class="rbt-table table table-borderless">
                                <thead>
                                    <tr>
                                        <th>Images</th>
                                        <th>Propriete</th>
                                        <th>Date de Demande</th>
                                        <th>Montant</th>
                                        <th>Commentaire</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($demandeproduits as $index => $demandeproduit)
                                        <tr>
                                            <td>
                                                <a href="{{ asset($demandeproduit->produitProjet->lien_photo) }}">
                                                    <img src="{{ asset($demandeproduit->produitProjet->lien_photo) }}"
                                                        width="50" height="50"
                                                        alt="Image du {{ $demandeproduit->produitProjet->libelle }}">
                                                </a>
                                            </td>
                                            <td>{{ $demandeproduit->produitProjet->libelle }}</td>
                                            <td>{{ formatDate2($demandeproduit->created_at) }} <br>
                                                {{ formatJour($demandeproduit->created_at) }}</td>
                                            <td>{{ formatMontant($demandeproduit->montant) }}</td>
                                            <td>
                                                @if ($demandeproduit->status == 3)
                                                    {{ $demandeproduit->commentaire }}
                                                @elseif ($demandeproduit->status == 2)
                                                  ....
                                                @elseif ($demandeproduit->status == 1)
                                                    {{ $demandeproduit->commentaire }}
                                                @endif
                                            </td>

                                            <td>
                                                @if ($demandeproduit->status == 1)
                                                    <span
                                                        class="rbt-badge-5 bg-color-success-opacity color-success">APPROUVÉE</span>
                                                @elseif ($demandeproduit->status == 2)
                                                    <span class="rbt-badge-5 bg-color-primary-opacity color-primary">EN
                                                        ATTENTE</span>
                                                @elseif ($demandeproduit->status == 3)
                                                    <span
                                                        class="rbt-badge-5 bg-color-danger-opacity color-danger">REJETÉE</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if ($demandeproduit->status == 1)
                                                        <a href="{{ route('prodAcquis.mutuID', $demandeproduit->id) }}"
                                                            id="DeleteIdentification"
                                                            class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill"
                                                            aria-label="Detail" data-bs-original-title="Detail">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                height="20" fill="currentColor" class="bi bi-eye"
                                                                viewBox="0 0 16 16">
                                                                <path
                                                                    d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z" />
                                                                <path
                                                                    d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0" />
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
    </div>
@endsection
@push('js')

@endpush
