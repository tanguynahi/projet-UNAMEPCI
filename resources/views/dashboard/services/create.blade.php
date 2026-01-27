@extends('layouts.dashboard', ['title' => 'Ajout d\'un services', 'toolbar' => '_toolbar2', 'breadcrumb' => 'Ajouter-Service'])

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
                        <a href="{{ route('services.index') }}" class="btn btn-primary d-inline">Retour</a>
                    </div>
                </div>
                <div class="card-body" id="show_create">
                    <h6 class="fw-bold">Formulaire d'ajoute d'un service</h6>
                    <form action="{{ route('services.store') }}" method="POST" id="add_service_form"
                        enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        <div class="row g-3">
                            <div class="col-lg-6 col-md-6">
                                <div class="form-floating">
                                    <input type="text" id="libelle" name="libelle"
                                        class="form-control @error('libelle') is-invalid @enderror"
                                        value="{{ old('libelle') }}" placeholder="Direction" autocomplete="libelle"
                                        autofocus required>
                                    <label>Entrez le libelle<span class="text-danger fw-bold">*</span></label>
                                    @error('libelle')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="form-floating">
                                    <input type="number" id="montant_maximum" name="montant_maximum"
                                        class="form-control @error('montant_maximum') is-invalid @enderror"
                                        value="{{ old('montant_maximum') }}" placeholder="Montant maximum"
                                        autocomplete="montant_maximum" autofocus required>
                                    <label>Entrez le montant maximum<span class="text-danger fw-bold">*</span></label>
                                    @error('montant_maximum')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" class="form-control no-resize @error('description') is-invalid @enderror" rows="4"
                                    placeholder="Description" autocomplete="description" autofocus>{{ old('description') }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <hr>
                            {{-- <span>Les Interêts : <br></span>
                            <div class="col-lg-4 col-md-4">
                                <div class="form-floating">
                                    <select
                                        class="form-select form-control @error('frequence_paiement') is-invalid @enderror"
                                        id="frequence_paiement" name="frequence_paiement" required>
                                        <option value="">Sélectionner Montant</option>
                                        <option value="Journalière"
                                            {{ old('frequence_paiement') == 'Journalière' ? 'selected' : '' }}>Journalière
                                        </option>
                                        <option value="Hebdomadaire"
                                            {{ old('frequence_paiement') == 'Hebdomadaire' ? 'selected' : '' }}>Hebdomadaire
                                        </option>
                                        <option value="Mensuelle"
                                            {{ old('frequence_paiement') == 'Mensuelle' ? 'selected' : '' }}>Mensuelle
                                        </option>
                                        <option value="Annuelle"
                                            {{ old('frequence_paiement') == 'Annuelle' ? 'selected' : '' }}>Annuelle
                                        </option>
                                    </select>
                                    @error('frequence_paiement')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <label for="floatingSelect">Frequence<span class="text-danger fw-bold">*</span></label>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="form-floating">
                                    <input type="number" id="montant_maximum" name="montant_maximum"
                                        class="form-control @error('montant_maximum') is-invalid @enderror"
                                        value="{{ old('montant_maximum') }}" placeholder="Montant maximum"
                                        autocomplete="montant_maximum" autofocus required>
                                    <label>Entrez le montant maximum<span class="text-danger fw-bold">*</span></label>
                                    @error('montant_maximum')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div> --}}

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
@endpush
