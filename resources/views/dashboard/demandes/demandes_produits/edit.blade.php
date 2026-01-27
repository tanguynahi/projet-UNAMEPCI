@extends('layouts.dashboard', ['title' => 'Modifier un produit', 'toolbar' => '_toolbar2', 'breadcrumb' => 'Modifier-Produit'])

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
                        <a href="{{ route('produitprojets.index', ['projet_id' => $produitprojet->projet_id]) }}"
                            class="btn btn-primary d-inline">Retour</a>
                    </div>
                </div>
                <div class="card-body" id="show_create">
                    <h6 class="fw-bold">Informations du produit</h6>
                    @if ($produitprojet->lien_photo)
                        <div class="row g-3 mb-3 justify-content-start align-content-center">
                            <div class="col-lg-4 col-md-4">
                                <img src="{{ asset($produitprojet->lien_photo) }}" alt="Images du produit"
                                    class="img-thumbnail mt-3" width="60" height="50">
                                <span class="text-primary fw-bold">Image du produit</span>
                            </div>
                        </div>
                    @endif
                    <form action="{{ route('produitprojets.update', $produitprojet->id) }}" method="POST"
                        id="edit_projet_form" enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')
                        <div class="row g-3 mb-3">
                            <div class="col-lg-4 col-md-4">
                                <div class="form-floating">
                                    <input type="text" id="libelle" name="libelle"
                                        class="form-control @error('libelle') is-invalid @enderror"
                                        value="{{ old('libelle', $produitprojet->libelle) }}" placeholder="Actualité"
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
                                    <input type="number" id="cout" name="cout"
                                        class="form-control @error('cout') is-invalid @enderror"
                                        value="{{ old('cout', $produitprojet->cout) }}" placeholder="Coût"
                                        autocomplete="cout" autofocus required>
                                    <label>Coût<span class="text-danger fw-bold">*</span></label>
                                    @error('cout')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3">
                                <div class="form-floating">
                                    <input type="number" id="contribution" name="contribution"
                                        class="form-control @error('contribution') is-invalid @enderror"
                                        value="{{ old('contribution', $produitprojet->contribution) }}" placeholder="Contribution" autocomplete="contribution"
                                        autofocus required>
                                    <label>Contribution (Mois)<span class="text-danger fw-bold">*</span></label>
                                    @error('contribution')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2">
                                <div class="form-floating">
                                    <input type="number" id="piece" name="piece"
                                        class="form-control @error('piece') is-invalid @enderror"
                                        value="{{ old('piece', $produitprojet->piece) }}" placeholder="Pièce"
                                        autocomplete="piece" autofocus required>
                                    <label>Nbre de Pièces<span class="text-danger fw-bold">*</span></label>
                                    @error('piece')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <h6 class="fw-bold">Charger les autres images du produit</h6>
                        <div class="row g-3">
                            <div class="col-lg-6 col-md-6">
                                <div class="form-floating">
                                    <input type="file" id="lien_photo" name="lien_photo"
                                        class="form-control @error('lien_photo') is-invalid @enderror"
                                        accept=".jpeg, .jpg, .png" placeholder="Image principale du produit"
                                        autocomplete="lien_photo" autofocus>
                                    <label for="lien_photo">Image principale du produit<span
                                            class="text-danger fw-bold"></span></label>
                                    @error('lien_photo')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <div class="form-floating">
                                    <input type="file" id="lien_image" name="lien_image[]" multiple
                                        class="form-control @error('lien_image') is-invalid @enderror"
                                        accept=".jpeg, .jpg, .png" placeholder="Images du produit" autocomplete="lien_image"
                                        autofocus>
                                    <label for="lien_image">Images du produit<span
                                            class="text-danger fw-bold"></span></label>
                                    @error('lien_image')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                @if ($produitprojet->imagesProjet->count() > 0)
                                    @foreach ($produitprojet->imagesProjet as $imageProjet)
                                        <div class="col-lg-3 col-md-3">
                                            <img src="{{ asset($imageProjet->lien_image) }}"
                                                alt="Image du produit {{ $loop->iteration }}" class="img-thumbnail mt-3"
                                                width="80" height="90">
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <div class="col-lg-12 col-md-12">
                                <label for="description" class="form-label">Description<span
                                        class="text-danger fw-bold">*</span></label>
                                <textarea id="summernote" name="description"
                                    class="form-control no-resize @error('description') is-invalid @enderror" autocomplete="description" autofocus
                                    required>{{ old('description', $produitprojet->description) }}</textarea>
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
    <script src="{{ asset('assets/dashboard/js/bundle/summernote.bundle.js') }}"></script>
    <!-- Vendor Script -->
    <script>
        $(document).ready(function() {
            $('#summernote').summernote();
            $('.note-editor .note-btn').on('click', function() {
                $(this).next().toggleClass("show");
            });
        });
    </script>
@endpush
