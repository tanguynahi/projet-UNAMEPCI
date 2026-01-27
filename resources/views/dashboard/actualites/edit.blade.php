@extends('layouts.dashboard', ['title' => 'Modifier une actualité', 'toolbar' => '_toolbar2', 'breadcrumb' => 'Modifier-Actualité'])

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
                        <a href="#" class="card-fullscreen" data-bs-toggle="tooltip" title="Card Full-Screen"><i
                                class="icon-size-fullscreen"></i></a>
                        <a href="{{ route('actualite.index') }}" class="btn btn-primary d-inline">Retour</a>
                    </div>
                </div>
                <div class="card-body" id="show_create">
                    <h6 class="fw-bold">Informations de l'actualité</h6>
                    <form action="{{ route('actualite.update', $actualite->id) }}" method="POST" id="edit_actualite_form"
                        enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')
                        <div class="row mb-3">
                            @if ($actualite->lien_photo)
                                <div class="col-lg-4 col-md-4">
                                    <img src="{{ asset($actualite->lien_photo) }}" alt="Image Actualité"
                                        class="img-thumbnail mt-3" width="70" height="70">
                                </div>
                            @endif
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-lg-4 col-md-4">
                                <div class="form-floating">
                                    <input type="file" id="lien_photo" name="lien_photo"
                                        class="form-control @error('lien_photo') is-invalid @enderror" placeholder="Photo"
                                        autocomplete="lien_photo" autofocus>
                                    <label for="lien_photo">Photo<span class="text-danger fw-bold"></span></label>
                                    @error('lien_photo')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-5 col-md-5">
                                <div class="form-floating">
                                    <input type="text" id="libelle" name="libelle"
                                        class="form-control @error('libelle') is-invalid @enderror"
                                        value="{{ old('libelle', $actualite->libelle) }}" placeholder="Actualité"
                                        autocomplete="libelle" autofocus required>
                                    <label>Entrez le libelle<span class="text-danger fw-bold">*</span></label>
                                    @error('libelle')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3">
                                <div class="form-floating">
                                    <input type="date" id="date_actualite" name="date_actualite"
                                        class="form-control @error('date_actualite') is-invalid @enderror"
                                        value="{{ old('date_actualite', $actualite->date_actualite) }}"
                                        placeholder="Date actualité" autocomplete="date_actualite" autofocus required>
                                    <label>Entrez la date<span class="text-danger fw-bold">*</span></label>
                                    @error('date_actualite')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-lg-12 col-md-12">

                                <label for="description" class="form-label">Description<span
                                        class="text-danger fw-bold">*</span></label>
                                <textarea id="summernote" name="description" class="form-control no-resize @error('description') is-invalid @enderror"
                                    autocomplete="description" autofocus required>{{ old('description', $actualite->description) }}</textarea>
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

                                <a href="{{ route('actualite.index') }}" class="btn btn-secondary w-25 mx-2">Annuler</a>
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
    <script src="{{ asset('assets/dashboard/js/bundle/summernote.bundle.js') }}"></script>
    <!-- Vendor Script -->
    <script>
        $(document).ready(function() {
            $('#summernote').summernote({
                height: 150,
                // placeholder: 'Description de l\'actualité'
            });
            $('.note-editor .note-btn').on('click', function() {
                $(this).next().toggleClass("show");
            });
        });
    </script>
@endpush
