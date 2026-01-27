@extends('layouts.dashboard', ['title' => 'Ajouter d\'un service', 'toolbar' => '_toolbar2', 'breadcrumb' => 'Ajouter-Service'])

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
                        <a href="{{ route('interetService.liste', $id) }}" class="btn btn-primary d-inline">Retour</a>
                    </div>
                </div>
                <div class="card-body" id="show_create">
                    <h6 class="fw-bold">Formulaire d'ajoute d'un service</h6>
                    <form action="{{ route('interetservices.store') }}" method="POST" id="add_interet_form"
                        enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        <div class="row g-3">
                            <input type="text" name="id_service" value="{{ $id }}" style="display: none;"
                                class="form-control @error('id_service') is-invalid @enderror">
                            @error('id_service')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <div class="col-lg-4 col-md-4">
                                <div class="form-floating">
                                    <input type="number" id="montant_debut" name="montant_debut"
                                        class="form-control @error('montant_debut') is-invalid @enderror"
                                        value="{{ old('montant_debut') }}" placeholder="montant debut"
                                        autocomplete="montant_debut" autofocus required min="1">
                                    <label>Entrez le montant minimum <span class="text-danger fw-bold">*</span></label>
                                    @error('montant_debut')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-4 col-md-4">
                                <div class="form-floating">
                                    <input type="number" id="montant_fin" name="montant_fin"
                                        class="form-control @error('montant_fin') is-invalid @enderror"
                                        value="{{ old('montant_fin') }}" placeholder="Montant fin"
                                        autocomplete="montant_fin" autofocus required max="5000000">
                                    <label>Entrez le montant maximum <span class="text-danger fw-bold">*</span></label>

                                    @error('montant_fin')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <span class="text-danger" id="textalert" style="display: none"> Ce montant doit être supérieur au montant de minimum</span>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4">
                                <div class="form-floating">
                                    <input type="number" id="taux_interet" name="taux_interet"
                                        class="form-control @error('taux_interet') is-invalid @enderror"
                                        value="{{ old('taux_interet') }}" placeholder="taux d'interêt"
                                        autocomplete="taux_interet" autofocus required>
                                    <label>Entrez le taux d'interêt <span class="text-danger fw-bold">*</span></label>
                                    @error('taux_interet')
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('add_interet_form');
            const montantDebutInput = document.getElementById('montant_debut');
            const montantFinInput = document.getElementById('montant_fin');
            const textAffiche = document.getElementById('textalert');
            form.addEventListener('submit', function(event) {
                const montantDebut = parseFloat(montantDebutInput.value);
                const montantFin = parseFloat(montantFinInput.value);
                if (montantDebut >= montantFin) {
                    event.preventDefault();
                    textAffiche.style.display = 'block';
                    montantDebutInput.classList.add('is-invalid');
                    montantFinInput.classList.add('is-invalid');
                } else {
                    montantDebutInput.classList.remove('is-invalid');
                    montantFinInput.classList.remove('is-invalid');
                }
            });
        });
    </script>
@endpush
