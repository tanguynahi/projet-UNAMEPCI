@extends('layouts.dashboard', ['title' => 'Ajout d\'une Permissions', 'toolbar' => '_toolbar2', 'breadcrumb' => 'Ajouter Permissions'])

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
                        <a href="{{ route('permissions.index') }}" class="btn btn-primary d-inline">Retour</a>
                    </div>
                </div>
                <div class="card-body" id="show_create">
                    <h6 class="fw-bold">Informations d'une permission</h6>
                    <form action="{{ route('permissions.store') }}" method="POST" id="add_type_paiement_form"
                        enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        <div class="row g-3">
                            <div class="col-lg-12 col-md-12">
                                <div class="form-floating">
                                    <label for="name" class="form-label">Nom de la permission <span
                                            class="required">*</span></label>
                                    <input type="text" id="name" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        placeholder="Ex: gestion-utilisateurs, voir-roles" value="{{ old('name') }}"
                                        required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <p class="form-info">Le nom doit être unique. Utilisez un tiret pour indiquer le groupe
                                        (ex:
                                        "gestion-utilisateurs" donnera le groupe "utilisateurs").</p>
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
    <!-- Plugin Js -->

    <!-- Vendor Script -->
    <script>
        // Data Table
    </script>
@endpush
