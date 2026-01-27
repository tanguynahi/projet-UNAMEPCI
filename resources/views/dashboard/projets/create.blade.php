@extends('layouts.dashboard', ['title' => 'Ajout d\'un projet', 'toolbar' => '_toolbar2', 'breadcrumb' => 'Ajouter-Projet'])

@push('css')
<!-- plugin css file  -->
<link rel="stylesheet" href="{{ asset('assets/dashboard/cssbundle/summernote.min.css') }}" />
@endpush

@section('content')
<div class="row g-3 justify-content-center">
    <div class="col-10">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">Renseignez le formulaire</h6>
                <div class="dropdown morphing scale-left">
                    <a href="#" class="card-fullscreen" data-bs-toggle="tooltip" title="Card Full-Screen"><i class="icon-size-fullscreen"></i></a>
                    <a href="{{ route('projets.index') }}" class="btn btn-primary d-inline">Retour</a>
                </div>
            </div>
            <div class="card-body" id="show_create">
                <h6 class="fw-bold">Informations du projet</h6>
                <form action="{{ route('projets.store') }}" method="POST" id="add_projet_form" enctype="multipart/form-data" class="needs-validation" novalidate>
                    @csrf
                    <div class="row g-3 mb-4">
                        <div class="col-lg-6 col-md-6">
                            <div class="form-floating">
                                <input type="text" id="libelle" name="libelle" class="form-control @error('libelle') is-invalid @enderror" value="{{ old('libelle') }}" placeholder="Projet" autocomplete="libelle" autofocus required>
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
                                <input type="file" id="lien_photo" name="lien_photo" class="form-control @error('lien_photo') is-invalid @enderror" accept=".jpeg, .jpg, .png" value="{{ old('lien_photo') }}" placeholder="Image principale du projet" autocomplete="lien_photo" autofocus required>
                                <label for="lien_photo">Image principale du projet<span class="text-danger fw-bold">*</span></label>
                                @error('lien_photo')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12">
                            <label for="description" class="form-label">Description<span class="text-danger fw-bold">*</span></label>
                            <textarea id="summernote" name="description" class="form-control no-resize @error('description') is-invalid @enderror" autocomplete="description" autofocus required>{{ old('description') }}</textarea>
                            @error('description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <h6 class="fw-bold">Charger les autres images du projet</h6>
                    <div class="row g-3">
                        <div class="col-lg-12 col-md-12">
                            <div class="form-floating">
                                <input type="file" id="lien_image" name="lien_image[]" multiple class="form-control @error('lien_image') is-invalid @enderror" accept=".jpeg, .jpg, .png" value="{{ old('lien_image') }}" placeholder="Images du projet" autocomplete="lien_image" autofocus>
                                <label for="lien_image">Images du projet<span class="text-danger fw-bold"></span></label>
                                @error('lien_image')
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

                            <a href="{{ route('projets.index') }}" class="btn btn-secondary w-25 mx-2">Annuler</a>
                            <button type="submit" id="add_admin_btn" class="btn btn-primary w-25 mx-2">Enregistrer</button>
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
<script src="{{ asset('assets/dashboard/js/bundle/summernote.bundle.js') }}"></script>
<!-- Vendor Script -->
<script>
    $(document).ready(function() {
        $('#summernote').summernote({
            height: 150,
            placeholder: 'Description du projet'
        });
        $('.note-editor .note-btn').on('click', function() {
            $(this).next().toggleClass("show");
        });
    });
</script>
@endpush
