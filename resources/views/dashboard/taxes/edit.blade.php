@extends('layouts.dashboard', ['title' => 'Modifier un Taxe', 'toolbar' => '_toolbar2', 'breadcrumb' => 'Modifier-Taxe'])

@push('css')
@endpush

@section('content')
    <div class="row g-3 justify-content-center">
        <div class="col-8">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Renseignez le formulaire</h6>
                    <div class="dropdown morphing scale-left">
                        <a href="#" class="card-fullscreen" data-bs-toggle="tooltip" title="Card Full-Screen"><i
                                class="icon-size-fullscreen"></i></a>
                        <a href="{{ route('taxes.index') }}" class="btn btn-primary d-inline">Retour</a>
                    </div>
                </div>
                <div class="card-body" id="show_create">
                    <h6 class="fw-bold">Informations de la taxe</h6>
                    <form action="{{ route('taxes.update', $taxe->id) }}" method="POST" id="update_type_paiement_form"
                        enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')      
                        <div class="row g-3">
                            <div class="col-lg-6 col-md-6">
                                <div class="form-floating">
                                    <input type="text" id="libelle" name="libelle"
                                        class="form-control @error('libelle') is-invalid @enderror"
                                        value="{{ old('libelle', $taxe->libelle) }}" placeholder="Type de pièce"
                                        autocomplete="libelle" autofocus required>
                                    <label>Entrez le libellé<span class="text-danger fw-bold">*</span></label>
                                    @error('libelle')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="form-floating">
                                    <input type="number" id="montant" name="montant"
                                        class="form-control @error('montant') is-invalid @enderror"
                                        value="{{ old('montant', $taxe->montant) }}" placeholder="Taxe " min="0"
                                        autocomplete="montant" autofocus required>
                                    <label>Entrez le Montant<span class="text-danger fw-bold">*</span></label>
                                    @error('montant')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12">

                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" class="form-control no-resize @error('description') is-invalid @enderror" rows="4"
                                    placeholder="Description" autocomplete="description" autofocus>{{ old('description', $taxe->description) }}</textarea>
                                @error('description')
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

                                <button type="reset" class="btn btn-secondary w-25 mx-2">Annuler</button>
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

    <!-- Vendor Script -->
    <script>
        // Data Table
    </script>
@endpush
