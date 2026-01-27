@extends('layouts.dashboard', ['title' => 'Attribution de cotisations', 'toolbar' => '_toolbar2', 'breadcrumb' => 'Attribuer Cotisation'])

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
                        <a href="{{ route('cotisationmutualistes.index') }}" class="btn btn-primary d-inline">Retour</a>
                    </div>
                </div>
                <div class="card-body" id="show_create">
                    <h6 class="fw-bold">Informations </h6>
                    <form action="{{ route('cotisationmutualistes.store') }}" method="POST" id="add_direction_form"
                        enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        <div class="row g-3">
                            <div class="col-lg-6 col-md-6">
                                <div class="form-floating">
                                    <select class="form-select form-control @error('cotisation_id') is-invalid @enderror"
                                        id="cotisation_id" name="cotisation_id" autocomplete="cotisation_id" autofocus
                                        >
                                        <option value="" selected>Sélectionner une cotisation</option>
                                        @foreach ($cotisations as $cotisation)
                                            <option value="{{ $cotisation->id }}"
                                                {{ old('cotisation_id') == $cotisation->id ? 'selected' : '' }}>
                                                {{ $cotisation->libelle }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('cotisation_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <label for="floatingSelect">Cotisations<span class="text-danger fw-bold">*</span></label>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="form-floating">
                                    <select class="form-select form-control @error('mutualiste_id') is-invalid @enderror"
                                        id="mutualiste_id" name="mutualiste_id" autocomplete="mutualiste_id" autofocus
                                        >
                                        <option value="" selected>Sélectionner un mutualiste</option>
                                        @foreach ($mutualistes as $mutualiste)
                                            <option value="{{ $mutualiste->id }}"
                                                {{ old('mutualiste_id') == $mutualiste->id ? 'selected' : '' }}>
                                                {{ $mutualiste->nom }}  {{ $mutualiste->prenom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('mutualiste_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <label for="floatingSelect">Mutualiste<span class="text-danger fw-bold">*</span></label>
                                </div>
                            </div>                          
                                                    
                           
                        </div>
                        <div class="mt-3">
                            <p><span class="text-danger fw-bold">* </span>Champs obligatoires.</p>
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
