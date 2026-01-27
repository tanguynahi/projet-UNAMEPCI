@extends('layouts.dashboard', ['title' => 'Ajout de documents', 'toolbar' => '_toolbar2', 'breadcrumb' => 'Ajouter-Documents'])

@push('css')
    <!-- plugin css file  -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard/cssbundle/summernote.min.css') }}" />
@endpush

@section('content')
    <div class="row g-3 justify-content-center">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Renseignez le formulaire</h6>
                    <div id="message-container my-3" class="alert" role="alert" style="display: none;"></div>
                    <div class="dropdown morphing scale-left">
                        <a href="#" class="card-fullscreen" data-bs-toggle="tooltip" title="Card Full-Screen"><i
                                class="icon-size-fullscreen"></i></a>
                        <a href="{{ route('documentproduits.index', ['produit_id' => $produit_id]) }}"
                            class="btn btn-primary d-inline">Retour</a>
                    </div>
                </div>
                <div class="card-body" id="show_create">
                    <h6 class="fw-bold mb-3">Informations document</h6>
                    <form action="{{ route('documentproduits.store') }}" method="POST" id="add_document_form" class="needs-validation"
                        novalidate>
                        @csrf
                        <input type="hidden" id="produit_projet_id" name="produit_projet_id"
                            class="form-control @error('produit_projet_id') is-invalid @enderror"
                            value="{{ old('produit_projet_id', $produit_id) }}" placeholder="Id du produit"
                            autocomplete="produit_projet_id" autofocus required>
                        @error('produit_projet_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <div id="showDoc">
                            <div class="row g-3 mb-4 justify-content-center">
                                <div class="col-lg-10 col-md-10">
                                    <div class="form-floating">
                                        <select class="form-control form-control-lg" tabindex="-98"
                                            name="type_document_id[]" id="type_document_id" required>
                                            <option value="">- Sélectionnez le type de document -</option>
                                            @forelse ($typeDocuments as $typeDocument)
                                                <option value="{{ $typeDocument->id }}">{{ $typeDocument->libelle }}
                                                </option>
                                            @empty
                                                <option value=""><span readonly>Aucune donnée</span></option>
                                            @endforelse
                                        </select>
                                        <label>Type de document<span class="text-danger fw-bold">*</span></label>
                                        @error('type_document_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2">
                                    <button class="btn btn-primary btn-lg w-100 add_doc_btn" data-bs-toggle="tooltip"
                                        data-bs-placement="top" title="Ajouter"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                        </div>


                        <div class="mt-3">
                            <p><span class="text-danger fw-bold">*</span>Champs obligatoires.</p>
                        </div>
                        <div class="row g-3 ">
                            <div class="mx-auto d-flex justify-content-center">

                                <a href="{{ route('documentproduits.index', ['produit_id' => $produit_id]) }}" class="btn btn-secondary w-25 mx-2">Annuler</a>
                                <button type="submit" id="add_document_btn"
                                    class="btn btn-primary w-25 mx-2">Enregistrer</button>
                            </div>
                        </div>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('.add_doc_btn').click(function(e) {
                e.preventDefault();
                $('#showDoc').prepend(`
                    <div class="row g-3 mb-4 justify-content-center append_item">
                        <div class="col-lg-10 col-md-10">
                            <div class="form-floating">
                                <select class="form-control form-control-lg" tabindex="-98"
                                    name="type_document_id[]" id="type_document_id" required>
                                    <option value="">- Sélectionnez le type de document -</option>
                                    @forelse ($typeDocuments as $typeDocument)
                                        <option value="{{ $typeDocument->id }}">{{ $typeDocument->libelle }}
                                        </option>
                                    @empty
                                        <option value=""><span readonly>Aucune donnée</span></option>
                                    @endforelse
                                </select>
                                <label>Type de document<span class="text-danger fw-bold">*</span></label>
                                @error('type_document_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2">
                            <button class="btn btn-danger btn-lg w-100 remove_doc_btn" data-bs-toggle="tooltip"
                                data-bs-placement="top" title="Supprimer"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                `);
            });

            $(document).on('click', '.remove_doc_btn', function(e) {
                e.preventDefault();
                let row_item = $(this).parent().parent().remove();
            });

        });
    </script>
@endpush
