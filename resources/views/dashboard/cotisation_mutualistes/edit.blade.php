@extends('layouts.dashboard', ['title' => 'Modifier une cotisation', 'toolbar' => '_toolbar2', 'breadcrumb' => 'Modifier-Cotisation'])

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
                        <a href="{{ route('cotisations.index') }}" class="btn btn-primary d-inline">Retour</a>
                    </div>
                </div>
                <div class="card-body" id="show_create">
                    <h6 class="fw-bold">Informations de la direction</h6>
                    <form action="{{ route('cotisations.update',$cotisation->id) }}" method="POST" id="edit_direction_form"
                        enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')
                        <div class="row g-3">
                            <div class="col-lg-4 col-md-4">
                                <div class="form-floating">
                                    <select class="form-select form-control @error('direction_id') is-invalid @enderror"
                                        id="direction_id" name="direction_id" autocomplete="direction_id" autofocus>
                                        <option value="" selected>Sélectionner une Direction</option>
                                        @foreach ($directions as $direction)
                                            <option value="{{ $direction->id }}"
                                                {{ old('direction_id', $cotisation->direction_id) == $direction->id ? 'selected' : '' }}>
                                                {{ $direction->libelle }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('direction_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <label for="floatingSelect">Directions<span class="text-danger fw-bold">*</span></label>
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-8">
                                <div class="form-floating">
                                    <input type="text" id="libelle" name="libelle"
                                        class="form-control @error('libelle') is-invalid @enderror"
                                        value="{{ old('libelle', $cotisation->libelle) }}" placeholder="Libelle"
                                        autocomplete="libelle" autofocus required>
                                    <label>Entrez le libelle<span class="text-danger fw-bold">*</span></label>
                                    @error('libelle')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4">
                                <div class="form-floating">
                                    <select
                                        class="form-select form-control @error('frequence_paiement') is-invalid @enderror"
                                        id="frequence_paiement" name="frequence_paiement" required>
                                        <option value="">Sélectionner une Frequence</option>
                                        <option value="Journalière"
                                            {{ old('frequence_paiement', $cotisation->frequence_paiement) == 'Journalière' ? 'selected' : '' }}>
                                            Journalière
                                        </option>
                                        <option value="Hebdomadaire"
                                            {{ old('frequence_paiement', $cotisation->frequence_paiement) == 'Hebdomadaire' ? 'selected' : '' }}>
                                            Hebdomadaire
                                        </option>
                                        <option value="Mensuelle"
                                            {{ old('frequence_paiement', $cotisation->frequence_paiement) == 'Mensuelle' ? 'selected' : '' }}>
                                            Mensuelle
                                        </option>
                                        <option value="Annuelle"
                                            {{ old('frequence_paiement', $cotisation->frequence_paiement) == 'Annuelle' ? 'selected' : '' }}>
                                            Annuelle
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
                            <div class="col-lg-8 col-md-8">
                                <div class="form-floating">
                                    <input type="number" id="montant_a_payer" name="montant_a_payer"
                                        class="form-control @error('montant_a_payer') is-invalid @enderror"
                                        value="{{ old('montant_a_payer', $cotisation->montant_a_payer) }}"
                                        placeholder="montant " autocomplete="montant_a_payer" autofocus required>
                                    <label>Entrez le montant a payer<span class="text-danger fw-bold">*</span></label>
                                    @error('montant_a_payer')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="form-floating">
                                    <input type="date" id="date_debut" name="date_debut"
                                        class="form-control @error('date_debut') is-invalid @enderror"
                                        value="{{ old('date_debut', $cotisation->date_debut) }}" autocomplete="date_debut"
                                        autofocus required>
                                    <label>Date Debut<span class="text-danger fw-bold">*</span></label>
                                    @error('date_debut')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="form-floating">
                                    <input type="date" id="date_fin" name="date_fin"
                                        class="form-control @error('date_fin') is-invalid @enderror"
                                        value="{{ old('date_fin',$cotisation->date_fin) }}" autocomplete="date_fin" autofocus required>
                                    <label>Date fin<span class="text-danger fw-bold">*</span></label>
                                    @error('date_fin')
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
