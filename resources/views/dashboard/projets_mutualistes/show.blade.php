@extends('layouts.dashboard', ['title' => 'Infos Bien - acquis', 'toolbar' => '_toolbar2', 'breadcrumb' => 'Infos-Biens-Acquis'])

@push('css')
@endpush

@section('content')
    <div class="row g-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Informations sur le Bien</h6>
                    <div class="dropdown morphing scale-left">
                        <a href="#" class="card-fullscreen" data-bs-toggle="tooltip" title="Card Full-Screen"><i
                                class="icon-size-fullscreen"></i></a>
                        <a href="{{ route('projetmutualistes.index') }}" class="btn btn-primary d-inline">Retour</a>
                    </div>
                </div>
                <div class="card-body" id="show_all_produits">
                    <div class="row mb-5">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="row mb-3">
                                <h6><u>Informations produit</u></h6>

                                <img src="/{{ $projetmutualiste->lien_photo }}" class="img-thumbnail"
                                    style="height: 80px; width: 100px;" alt="Image du produit">

                                <div class="col-lg-6 col-md-6 col-sm-6">
                                    <h4 class="mt-4 mt-lg-0">
                                        <strong>{{ $projetmutualiste->libelle }}</strong>
                                    </h4>
                                    <p class="mb-0 text-primary fs-6 fw-bold">
                                        <span
                                            class="fs-6 text-muted fw-light  me-2">Coût</span>{{ formatMontant($projetmutualiste->montant_produit) }}
                                    </p>
                                    <p class="mb-0 text-primary fs-6 fw-bold">
                                        <span class="fs-6 text-muted fw-light  me-2">Total à Payer</span> <span
                                            class="fs-6 fw-bold text-danger">{{ formatMontant($projetmutualiste->total_apayer) }}</span>
                                    </p>
                                </div>
                            </div>
                            <div class="row justify-content-center align-items-center mb-3">
                                <div class="col-lg-4 col-md-4 col-sm-12">
                                    @if ($projetmutualiste->demandeProduit->status == 1)
                                        <p><span class="fs-5">État : </span><u><span
                                                    class="text-success fs-5 fw-bold">Approuvée</span></u></p>
                                    @endif
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-8">
                                    <p><i class="fa fa-calendar me-1"></i> Date affaire : <span
                                            class="fw-bold">{{ formatDate($projetmutualiste->date) }}</span>
                                    </p>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <h6 class="mb-3"><u>Documents</u></h6>
                                @if ($projetmutualiste->demandeProduit->documentProduitMutualistes->isNotEmpty())
                                    <div class="col-lg-12 col-md-12">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th scope="col">N°</th>
                                                    <th scope="col">Document</th>
                                                    <th scope="col">Fichier</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($projetmutualiste->demandeProduit->documentProduitMutualistes as $index => $documentProduitMutualiste)
                                                    <tr>
                                                        <th scope="row">{{ $index + 1 }}</th>
                                                        <td>{{ $documentProduitMutualiste->typeDocument->libelle }}</td>
                                                        <td>
                                                            <a href="/{{ $documentProduitMutualiste->lien_document }}"
                                                                class="link-underline-danger fw-bold" target="_blank">
                                                                Voir <i class="fa fa-paste text-primary"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p>Aucun document associé</p>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="row mb-5">
                                <h6><u>Informations Mutualiste</u></h6>
                                <img src="/{{ $projetmutualiste->mutualiste->lien_photo }}" class="img-thumbnail"
                                    style="height: 80px; width: 100px;" alt="Image du mutualiste">

                                <div class="col-lg-6 col-md-6">

                                    <h5 class="mt-4 mt-lg-0">{{ $projetmutualiste->mutualiste->nom }}
                                        {{ $projetmutualiste->mutualiste->prenom }}
                                    </h5>
                                    <p class="mb-0 text-primary fs-6 fw-bold">
                                        <i class="fa fa-envelope text-muted"></i> <a class=""
                                            href="mailto:{{ $projetmutualiste->mutualiste->user->email }}">
                                            {{ $projetmutualiste->mutualiste->user->email }}</a>
                                    </p>
                                    <p class="mb-0 text-primary fs-6 fw-bold">
                                        <i class="fa fa-phone text-muted"></i> <a
                                            href="tel:{{ $projetmutualiste->mutualiste->contact }}">
                                            {{ $projetmutualiste->mutualiste->contact }}</a>
                                    </p>

                                </div>
                            </div>
                            @if ($projetmutualiste->status == 1 && !is_null($projetmutualiste->commentaire))
                                <div class="row">
                                    <h6><u>Commentaire :</u></h6>
                                    <div class="col-12">
                                        <p class="fs-6">{{ $projetmutualiste->commentaire }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        <h6 class="mb-3"><u>Description</u></h6>
                        <div class="col-lg-8 col-md-8 col-sm-6">
                            {!! $projetmutualiste->demandeProduit->produitProjet->description !!}
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="row gap-2">
                                @php
                                    $imageProduits = $projetmutualiste->demandeProduit->produitProjet->imageProjets;
                                @endphp

                                @forelse ($imageProduits as $imageProduit)
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <img src="/{{ $imageProduit->lien_image }}" class="img-thumbnail"
                                            style="height: 80px; width: 100px;" alt="Image du produit">
                                    </div>
                                @empty
                                    {{-- <p class="text-muted">Aucune image associée a ce produit</p> --}}
                                @endforelse
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
        $(document).ready(function() {

        });
    </script>
@endpush
