@extends('layouts.dashboard', ['title' => 'Ajouter une images', 'toolbar' => '_toolbar2', 'breadcrumb' => 'Ajouter-image'])

@push('css')
<!-- plugin css file  -->
<link rel="stylesheet" href="{{ asset('assets/dashboard/cssbundle/summernote.min.css') }}"/>
@endpush

@section('content')
<div class="row g-3 justify-content-center">
    <div class="col-10">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title mb-0">Renseignez le formulaire</h6>
                <div class="dropdown morphing scale-left">
                    <a href="#" class="card-fullscreen" data-bs-toggle="tooltip" title="Card Full-Screen"><i class="icon-size-fullscreen"></i></a>
                    <a href="{{ route('slides.index') }}" class="btn btn-primary d-inline">Retour</a>
                </div>
            </div>
            <div class="card-body" id="show_create">
                <h6 class="fw-bold">Informations de l'image</h6>
                <form action="{{ route('slides.store') }}" method="POST" id="add_imageSlide_form" enctype="multipart/form-data" class="needs-validation" novalidate>
                    @csrf
                    <div class="row g-3">
                        <div class="col-lg-6 col-md-6">
                            <div class="form-floating">
                                <input type="text" id="titre" name="titre" class="form-control @error('titre') is-invalid @enderror" value="{{ old('titre') }}" placeholder="Titre" autocomplete="titre" autofocus required>
                                <label>Entrez le titre<span class="text-danger fw-bold">*</span></label>
                                @error('titre')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="form-floating">
                                <input type="sous_titre" id="sous_titre" name="sous_titre" class="form-control @error('sous_titre') is-invalid @enderror" value="{{ old('sous_titre') }}" placeholder="Sous-titre de l'image" autocomplete="sous_titre" autofocus>
                                <label>Entrez le sous-titre<span class="text-danger fw-bold"></span></label>
                                @error('sous_titre')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="form-floating">
                                <select class="form-select form-control @error('categorie') is-invalid @enderror"
                                    id="categorie" name="categorie" autocomplete="categorie" autofocus required>
                                    <option value="">Sélectionnez la catégorie</option>
                                    <option value="Image Annonce" {{ old('categorie') == 'Image Annonce' ? 'selected' : '' }}>Image Annonce</option>
                                    <option value="Image Carousel" {{ old('categorie') == 'Image Carousel' ? 'selected' : '' }}>Image Carousel</option>
                                    <option value="Image Partenaire" {{ old('categorie') == 'Image Partenaire' ? 'selected' : '' }}>Image Partenaire</option>
                                </select>
                                @error('categorie')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <label for="categorie">Catégorie<span class="text-danger fw-bold">*</span></label>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="form-floating">
                                <input  type="file" id="lien_image" name="lien_image" class="form-control @error('lien_image') is-invalid @enderror" value="{{ old('lien_image') }}" placeholder="Image" autocomplete="lien_image" autofocus required>
                                <label for="lien_image">Image<span class="text-danger fw-bold">*</span></label>
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

                            <button type="reset" class="btn btn-secondary w-25 mx-2">Annuler</button>
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
            placeholder: 'Description de l\'actualité'
        });
        $('.note-editor .note-btn').on('click',function(){
            $(this).next().toggleClass("show");
        });
    });
</script>
@endpush
