@extends('layouts.dashboard', ['title' => 'Ajouter un bien', 'toolbar' => '_toolbar2', 'breadcrumb' => 'Ajouter-Bien'])

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/dashboard/cssbundle/select2.min.css') }}">
@endpush

@section('content')
    <div class="row g-3 justify-content-center">
        <div class="col-10">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Renseignez le formulaire</h6>
                    <div class="dropdown morphing scale-left">
                        <a href="#" class="card-fullscreen" data-bs-toggle="tooltip" title="Card Full-Screen"><i
                                class="icon-size-fullscreen"></i></a>
                        <a href="{{ route('projetmutualistes.index') }}" class="btn btn-primary d-inline">Retour</a>
                    </div>
                </div>
                <div class="card-body" id="show_create">
                    <h6 class="fw-bold">Informations de base</h6>
                    <form action="{{ route('projetmutualistes.store') }}" method="POST" id="add_projets_mutualiste_form"
                        enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        <div class="row g-3 mb-3">
                            <div class="col-lg-6 col-md-6 mb-3">
                                <label class="form-label">Liste des Mutualistes<span
                                        class="text-danger fw-bold">*</span></label>
                                <select
                                    class="form-control show-tick ms select2 @error('mutualiste_id') is-invalid @enderror"
                                    data-placeholder="Select" id="mutualiste_id" name="mutualiste_id"
                                    autocomplete="mutualiste_id" autofocus required style="height: 60px">
                                    <option>Sélectionner le mutualiste</option>
                                    {{-- <option disabled="">Sélectionner le mutualiste</option> --}}
                                    @foreach ($mutualistes as $mutualiste)
                                        <option value="{{ $mutualiste->id }}"
                                            {{ old('mutualiste_id', $mutualiste->id) == $mutualiste->id ? 'selected' : '' }}>
                                            {{ $mutualiste->nom }} {{ $mutualiste->prenom }}
                                            ({{ $mutualiste->contact }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('mutualiste_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-lg-6 col-md-6 mb-3">
                                <label class="form-label">Liste des biens<span class="text-danger fw-bold">*</span></label>
                                <select
                                    class="form-control show-tick ms select2 @error('produit_projet_id') is-invalid @enderror"
                                    data-placeholder="Select" id="produit_projet_id" name="produit_projet_id"
                                    autocomplete="produit_projet_id" autofocus required style="height: 60px;">
                                    <option>Sélectionnez le bien</option>
                                </select>
                                @error('produit_projet_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-lg-4 col-md-4 mb-3">
                                <div class="form-floating">
                                    <input type="number" id="montant_produit" name="montant_produit_display"
                                        class="form-control @error('montant_produit') is-invalid @enderror" value=""
                                        placeholder="Montant" autocomplete="montant_produit" autofocus required disabled>
                                    <label>Montant du Bien<span class="text-danger fw-bold">*</span></label>
                                    <input type="hidden" id="montant_produit_hidden" name="montant_produit" value=""
                                        required>
                                    @error('montant_produit')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 mb-3">
                                <div class="form-floating">
                                    <input type="number" id="total_apayer" name="total_apayer"
                                        class="form-control @error('total_apayer') is-invalid @enderror"
                                        value="{{ old('total_apayer') }}" placeholder="Total à Payer"
                                        autocomplete="total_apayer"  autofocus required>
                                    <label>Total à Payer<span class="text-danger fw-bold">*</span></label>
                                    @error('total_apayer')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 mb-3">
                                <div class="form-floating">
                                    <input type="date" id="date" name="date"
                                        class="form-control @error('date') is-invalid @enderror"
                                        value="{{ old('date') }}" placeholder="Date" autocomplete="date" autofocus
                                        required>
                                    <label>Date affaire<span class="text-danger fw-bold">*</span></label>
                                    @error('date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-lg-12 col-md-12 mb-3">
                                <label for="commentaire" class="form-label">Commentaire</label>
                                <textarea name="commentaire" class="form-control no-resize @error('commentaire') is-invalid @enderror" rows="4"
                                    placeholder="commentaire" autocomplete="commentaire" autofocus>{{ old('commentaire') }}</textarea>
                                @error('commentaire')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-3">
                            <p><span class="text-danger fw-bold">*</span>Champs obligatoires.</p>
                        </div>
                        <div class="row g-3 ">
                            <div class="mx-auto d-flex justify-content-center">

                                <a href="{{ route('projetmutualistes.index') }}"
                                    class="btn btn-secondary w-25 mx-2">Annuler</a>
                                <button type="submit" id="add_admin_btn"
                                    class="btn btn-primary w-25 mx-2">Enregistrer</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <!-- Plugin Js -->
    <script src="{{ asset('assets/dashboard/js/bundles/select2.bundle.js') }}"></script>
    <!-- Vendor Script -->
    <script>
        // Select2 selectbox
        $('.select2').select2();
        $(".search-select").select2({
            allowClear: true
        });
        $("#max-select").select2({
            placeholder: "Select",
            maximumSelectionSize: 2,
        });
        $("#loading-select").select2({
            placeholder: "Select",
            minimumInputLength: 1,
            query: function(query) {
                var data = {
                        results: []
                    },
                    i, j, s;
                for (i = 1; i < 5; i++) {
                    s = "";
                    for (j = 0; j < i; j++) {
                        s = s + query.term;
                    }
                    data.results.push({
                        id: query.term + i,
                        text: s
                    });
                }
                query.callback(data);
            }
        });
        var data = [{
            id: 0,
            tag: 'enhancement'
        }, {
            id: 1,
            tag: 'bug'
        }, {
            id: 2,
            tag: 'duplicate'
        }, {
            id: 3,
            tag: 'invalid'
        }, {
            id: 4,
            tag: 'wontfix'
        }];

        function format(item) {
            return item.tag;
        }
        $("#array-select").select2({
            placeholder: "Select",
            data: {
                results: data,
                text: 'tag'
            },
            formatSelection: format,
            formatResult: format
        });
    </script>
    <script>
        $(document).ready(function() {

            function getProjetMutualistes(mutualisteId) {
                $.ajax({
                    type: "GET",
                    url: "/projets-mutualiste/" + mutualisteId,
                    dataType: 'json',
                    success: function(data) {
                        $('#produit_projet_id').empty();
                        $('#produit_projet_id').append(
                            '<option value="">Sélectionnez le bien</option>');
                        $.each(data, function(key, value) {
                            $('#produit_projet_id').append('<option value="' + value
                                .produit_projet_id + '">' + value.libelle + ' (' + value.projet + ')</option>');
                        });
                    }
                });
            }

            function getProduitDetails(produitProjetId) {
                $.ajax({
                    type: "GET",
                    url: "/produit-details/" + produitProjetId,
                    dataType: 'json',
                    success: function(data) {
                        if (data && data.montant) {
                            $('#montant_produit').val(data.montant);
                            $('#montant_produit_hidden').val(data.montant);
                        } else {
                            $('#montant_produit').val('');
                            $('#montant_produit_hidden').val('');
                        }
                    },
                    error: function() {
                        $('#montant_produit').val('');
                        $('#montant_produit_hidden').val('');
                    }
                });
            }

            var initialMutualisteId = $('#mutualiste_id').val();
            if (initialMutualisteId) {
                getProjetMutualistes(initialMutualisteId);
            }

            $('#mutualiste_id').on('change', function() {
                var mutualisteId = $(this).val();
                if (mutualisteId) {
                    $('#montant_produit').val('');
                    $('#montant_produit_hidden').val('');
                    getProjetMutualistes(mutualisteId);
                }
            });

            $('#produit_projet_id').on('change', function() {
                var produitProjetId = $(this).val();
                if (produitProjetId) {
                    getProduitDetails(produitProjetId);
                }
            });
        });
    </script>
@endpush
