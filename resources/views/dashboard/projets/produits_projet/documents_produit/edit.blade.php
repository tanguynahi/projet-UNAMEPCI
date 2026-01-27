@extends('layouts.dashboard', ['title' => 'Modifier un produit', 'toolbar' => '_toolbar2', 'breadcrumb' => 'Modifier-Produit'])

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
                    <div class="dropdown morphing scale-left">
                        <a href="#" class="card-fullscreen" data-bs-toggle="tooltip" title="Card Full-Screen"><i
                                class="icon-size-fullscreen"></i></a>
                        <a href="{{ route('documentproduits.index', ['produit_id' => $documentproduit->produit_projet_id]) }}"
                            class="btn btn-primary d-inline">Retour</a>
                    </div>
                </div>
                <div class="card-body" id="show_create">
                    <h6 class="fw-bold">Informations du document</h6>
                    <form action="{{ route('documentproduits.update', $documentproduit->id) }}" method="POST"
                        id="edit_projet_form" enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="row g-3 mb-4 justify-content-center">
                            <div class="col-12">
                                <div class="form-floating">
                                    <select class="form-control form-control-lg" tabindex="-98" name="type_document_id"
                                        id="type_document_id" required>
                                        <option value="">- Sélectionnez le type de document -</option>
                                        @forelse ($typeDocuments as $typeDocument)
                                            @if ($documentproduit->type_document_id == $typeDocument->id)
                                                <option value="{{ $typeDocument->id }}" selected>
                                                    {{ $typeDocument->libelle }}
                                                </option>
                                            @else
                                                <option value="{{ $typeDocument->id }}">{{ $typeDocument->libelle }}
                                                </option>
                                            @endif
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
                        </div>

                        <div class="mt-3">
                            <p><span class="text-danger fw-bold">*</span>Champs obligatoires.</p>
                        </div>
                        <div class="row g-3 ">
                            <div class="mx-auto d-flex justify-content-center">

                                <a href="{{ route('documentproduits.index', ['produit_id' => $documentproduit->produit_projet_id]) }}" class="btn btn-secondary w-25 mx-2">Annuler</a>
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
@endpush
