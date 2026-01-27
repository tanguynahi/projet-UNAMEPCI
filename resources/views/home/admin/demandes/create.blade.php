@extends('layouts.home_dashboard', ['title' => 'Parametre Mutualiste'])
@section('content')
    @php
        $mutualiste = auth()->user()->mutualiste;
        // dd($mutualiste);
    @endphp

    <div class="col-lg-9">
        <!-- Start Instructor Profile  -->
        <div class="rbt-dashboard-content bg-color-white rbt-shadow-box">
            <div class="content">

                <div class="section-title">
                    <div class="row">
                        <div class="col-lg-10 col-md-10 col-sm-10">
                            <h4 class="rbt-title-style-3">Formulaire de demande d'acquisition de produit</h4>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <a href="{{ back()->getTargetUrl() }}" class="rbt-btn btn-sm">
                                Retour
                            </a>
                        </div>
                    </div>
                </div>
                @if ($nobre = $documentproduits->count() > null)
                <div class="advance-tab-button mb--30">
                    <ul class="nav nav-tabs tab-button-style-2 justify-content-start" id="settinsTab-4" role="tablist">
                        <li role="presentation">
                            <a href="#" class="tab-button active" id="profile-tab" data-bs-toggle="tab"
                                data-bs-target="#profile" role="tab" aria-controls="profile" aria-selected="true">
                                <span class="title">LISTE DES DOCUMENTS A JOINDRE</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content">
                    <div class="tab-pane fade active show" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <form action="{{ route('demande.produit') }}" class="rbt-profile-row rbt-default-form row row--15"
                            enctype="multipart/form-data" method="POST">
                            @csrf

                            <input type="hidden" name="produit_projet_id" value="{{ $produit_projet_id }}" autocomplete=""
                                autofocus required>
                            @foreach ($documentproduits as $documentproduit)
                                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="rbt-form-group">
                                        <label for="firstname">{{ $documentproduit->typeDocument->libelle }}</label>
                                        <input type="hidden" name="document_produit_id[]"
                                            value="{{ $documentproduit->id }}">
                                        <input type="file" id="lien_document" accept=".pdf, .jpg, .jpeg, .png"
                                            value="" autocomplete="" autofocus required name="lien_document[]">
                                    </div>
                                </div>
                            @endforeach

                            <div class="col-12 mt--20 text-center">
                                <div class="rbt-form-group">
                                    <button type="submit" class="rbt-btn btn-gradient">
                                        Valider
                                    </button>
                                </div>
                            </div>

                        </form>
                        <!-- End Profile Row  -->
                    </div>
                </div>
                @endif
            </div>
        </div>
        <!-- End Instructor Profile  -->
    </div>
@endsection
