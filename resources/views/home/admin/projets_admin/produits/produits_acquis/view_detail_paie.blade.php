@extends('layouts.home_dashboard', ['title' => 'Paiement Detail| FPM'])
@section('content')
    <div class="col-lg-9">
        <div class="rbt-dashboard-content bg-color-white rbt-shadow-box">
            <div class="content">
                <div class="section-title">
                    <h4 class="rbt-title-style-3">Fiche Paiement</h4>
                    {{-- <button>Acceder a la liste des paiements</button> --}}
                    <a href="{{ back()->getTargetUrl() }}" style="padding-bottom:12px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor"
                            class="bi bi-arrow-left-square-fill" viewBox="0 0 16 16">
                            <path
                                d="M16 14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2zm-4.5-6.5H5.707l2.147-2.146a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 0 0 .708-.708L5.707 8.5H11.5a.5.5 0 0 0 0-1" />
                        </svg>
                    </a>
                </div>
                <div class="row">
                    <div class="col-12 col-lg-12 col-sm-12">
                        <strong>Informations Générale</strong>
                        <hr>
                        <br>
                        <div class="row">
                            <div class="col-6 mb--20 col-md-6 col-sm-6 col-12">
                                <label>Biens <span class="text-danger">*</span></label>
                                <div class="rbt-modern-select bg-transparent height-45">
                                    <input id="biens" name="biens" type="text"
                                        value="{{ old('biens', $produitMutualiste->produitProjet->libelle) }}"
                                        class=" @error('biens') is-invalid @enderror" placeholder="biens" readonly>
                                    @error('biens')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6 mb--20 col-md-6 col-sm-6 col-12">
                                <label>Redevance <span class="text-danger">*</span></label>
                                <div class="rbt-modern-select bg-transparent height-45">
                                    <input id="redevance" name="redevance" type="text"
                                        value="{{ old('redevance', $facturation->redevance->libelle) }}"
                                        class=" @error('redevance') is-invalid @enderror" placeholder="redevance" readonly>
                                    @error('redevance')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-3 mb--20 col-md-3 col-sm-3 col-12">
                                <label>Echéance <span class="text-danger">*</span></label>
                                <div class="rbt-modern-select bg-transparent height-45">
                                    <input id="echeance" name="echeance" type="text"
                                        value="{{ old('echeance', $facturation->periode->libelle) }}"
                                        class=" @error('echeance') is-invalid @enderror" placeholder="echeance" readonly>
                                    @error('echeance')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-3 mb--20 col-md-3 col-sm-3 col-12">
                                <label>Total à payer <span class="text-danger">*</span></label>
                                <div class="rbt-modern-select bg-transparent height-45">
                                    <input id="total_a_payer" name="total_a_payer" type="text"
                                        value="{{ old('total_a_payer', formatMontant($facturation->total_apayer)) }}"
                                        class=" @error('total_a_payer') is-invalid @enderror" placeholder="total_a_payer"
                                        readonly>
                                    @error('total_a_payer')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-3 mb--20 col-md-3 col-sm-3 col-12">
                                <label>Total payer <span class="text-danger">*</span></label>
                                <div class="rbt-modern-select bg-transparent height-45">
                                    <input id="total_payer" name="total_payer" type="text"
                                        value="{{ old('total_payer', formatMontant($facturation->total_payer)) }}"
                                        class=" @error('total_payer') is-invalid @enderror" placeholder="total_payer"
                                        readonly>
                                    @error('total_payer')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-3 mb--20 col-md-3 col-sm-3 col-12">
                                <label>Reste à payer <span class="text-danger">*</span></label>
                                <div class="rbt-modern-select bg-transparent height-45">
                                    <input id="reste_a_payer" name="reste_a_payer" type="text"
                                        value="{{ old('reste_a_payer', formatMontant($facturation->reste_apayer)) }}"
                                        class=" @error('reste_a_payer') is-invalid @enderror" placeholder="reste_a_payer"
                                        readonly>
                                    @error('reste_a_payer')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            @if (
                                $facturation->periode_id == 3 ||
                                    $facturation->periode_id == 4 ||
                                    $facturation->periode_id == 5 ||
                                    $facturation->periode_id == 2)
                                {{-- // les id des periodes : 1 immediat, 2 journaliere, 3 hebdomadaire ,4 mensuelle, 5 Annuelle , 6 Aperiodique --}}
                                <div class="col-3 mb--20 col-md-3 col-sm-3 col-12">
                                    <label>Montant periodique <span class="text-danger">*</span></label>
                                    <div class="rbt-modern-select bg-transparent height-45">
                                        <input id="montant_periodique" name="montant_periodique" type="text"
                                            value="{{ old('montant_periodique', formatMontant($facturation->montant_periodique)) }}"
                                            class=" @error('montant_periodique') is-invalid @enderror"
                                            placeholder="montant_periodique" readonly>
                                        @error('montant_periodique')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>

                    <div class="col-12 col-lg-12 col-sm-12">

                        <strong>Informations Paiement</strong>
                        <hr>
                        <br>
                        <form action="{{ route('remp.infoPaiem') }}" id="paymentForm" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <input type="text" name="facturationID" value="{{ $facturation->id }}"
                                    style="display: none;">
                                <input type="text" name="projetID" value="{{ $produitMutualiste->id }}"
                                    style="display: none;">
                                <input type="text" name="redevance" value="{{ $facturation->redevance->libelle }}"
                                    style="display: none;">
                                <input type="text" name="libPro"
                                    value="{{ $produitMutualiste->produitProjet->libelle }}" style="display: none;">
                                <div class="col-4 mb--20 col-md-4 col-sm-4 col-12">
                                    <label>Mode de paiement</label>
                                    <div class="rbt-modern-select bg-transparent height-45">
                                        <select class="w-100 @error('modepaiement') is-invalid @enderror"
                                            name="modepaiement" id="modepaiement" autocomplete="modepaiement" autofocus>
                                            <option value="0" selected>Choisir Moyen..</option>
                                            <option value="Cash" {{ old('modepaiement') == 'Cash' ? 'selected' : '' }}>
                                                Cash
                                            </option>
                                            <option value="Chèque"
                                                {{ old('modepaiement') == 'Chèque' ? 'selected' : '' }}>
                                                Chèque</option>
                                            <option value="Virement"
                                                {{ old('modepaiement') == 'Virement' ? 'selected' : '' }}>
                                                Virement</option>
                                            <option value="En Ligne"
                                                {{ old('modepaiement') == 'En Ligne' ? 'selected' : '' }}>
                                                En Ligne</option>
                                        </select>
                                        @error('modepaiement')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                                    <div class="rbt-form-group">
                                        <label for="montant">Montant</label>
                                        <input id="montant" name="montant" type="text"
                                            value="{{ old('montant') }}" class="@error('montant') is-invalid @enderror"
                                            placeholder="montant"
                                            @if ($facturation->periode->libelle === 'Immediat' || $facturation->periode_id == 1) min="{{ $facturation->total_apayer }}" @elseif (
                                                $facturation->periode_id == 3 ||
                                                    $facturation->periode_id == 4 ||
                                                    $facturation->periode_id == 5 ||
                                                    $facturation->periode_id == 2)  min="{{ $facturation->montant_periodique }}" @endif>
                                        <div id="error-message" style="color: red; display: none;"></div>
                                        @error('montant')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-12" id="referenceID">
                                    <div class="rbt-form-group">
                                        <label for="reference">Référence</label>
                                        <input id="reference" name="reference" type="text"
                                            value="{{ old('reference') }}"
                                            class="@error('reference') is-invalid @enderror"
                                            placeholder="reference du paiement ">
                                        @error('reference')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-12" id="dateID">
                                    <div class="rbt-form-group">
                                        <label for="date">Date</label>
                                        <input id="date" name="date" type="date" value="{{ old('date') }}"
                                            class="@error('date') is-invalid @enderror">
                                        @error('date')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-12" id="documentID">
                                    <div class="rbt-form-group">
                                        <label for="document">Document de paiement</label>
                                        <input id="document" name="document[]" type="file"
                                            value="{{ old('document') }}"
                                            class="@error('document') is-invalid @enderror"
                                            style="border: 1px solid #ced4da; padding: 10px; background-color: #fff; border-radius: 4px; height: 45px;"
                                            multiple>
                                        @error('document')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-12" id="Enregistrer">
                                    <div class="rbt-form-group">
                                        <button class="btn btn-primary btn-lg"
                                            style="width: 100%; height: 45px; font-size: 16px; margin-top:25px;"
                                            type="submit">Enregistrer</button>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-12" id="payment" style="display: none;">
                                    <div class="rbt-form-group">
                                        <button class="btn btn-primary btn-lg" type="submit"
                                            style="width: 100%; height: 45px; font-size: 16px; margin-top:25px;">Passer au
                                            Paiement</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function togglePaymentFields() {
                var modepaiement = document.getElementById('modepaiement').value;
                var referenceID = document.getElementById('referenceID');
                var dateID = document.getElementById('dateID');
                var documentID = document.getElementById('documentID');
                var Enregistrer = document.getElementById('Enregistrer');
                var onlinePayment = document.getElementById('payment');
                var paymentForm = document.getElementById('paymentForm');

                if (modepaiement === 'En Ligne') {
                    referenceID.style.display = 'none';
                    dateID.style.display = 'none';
                    documentID.style.display = 'none';
                    Enregistrer.style.display = 'none';
                    onlinePayment.style.display = 'block';
                    // paymentForm.action = "{{ route('passeHub.paiement') }}"; // Change action
                    paymentForm.action = "{{ route('espaPay', 4) }}"; // Change action
                } else if (modepaiement === "Cash" || modepaiement === "Chèque" || modepaiement === "Virement") {
                    referenceID.style.display = 'block';
                    dateID.style.display = 'block';
                    documentID.style.display = 'block';
                    Enregistrer.style.display = 'block';
                    onlinePayment.style.display = 'none';
                    paymentForm.action = "{{ route('remp.infoPaiem') }}"; // Change action
                } else if (modepaiement === "0") {
                    referenceID.style.display = 'none';
                    dateID.style.display = 'none';
                    documentID.style.display = 'none';
                    Enregistrer.style.display = 'none';
                    onlinePayment.style.display = 'none';
                    paymentForm.action = "{{ route('remp.infoPaiem') }}"; // Change action
                }
            }

            togglePaymentFields();
            document.getElementById('modepaiement').addEventListener('change', togglePaymentFields);
        });
    </script>
    <script>
        document.getElementById('montant').addEventListener('input', function() {
            var montantField = this;
            var periodeLibelle = '{{ $facturation->periode->libelle }}';
            if (periodeLibelle === 'Immediat') {
                var minimumMontant = {{ $facturation->total_apayer }};
                if (parseFloat(montantField.value) < minimumMontant) {
                    montantField.setCustomValidity('Le montant doit être au moins ' + minimumMontant);
                } else if (parseFloat(montantField.value) > minimumMontant) {
                    montantField.setCustomValidity('Le montant ne doit pas dépasser ' + minimumMontant);
                } else {
                    montantField.setCustomValidity('');
                }
            } else {
                montantField.setCustomValidity('');
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const montantInput = document.getElementById('montant');
            const errorMessage = document.getElementById('error-message');

            // Récupération de la valeur minimale en fonction des conditions définies
            let montantMin = 0;
            @if ($facturation->periode->libelle === 'Immediat' || $facturation->periode_id == 1)
                montantMin = {{ $facturation->total_apayer }};
                montantMax = {{ $facturation->reste_apayer }};
            @elseif (
                $facturation->periode_id == 3 ||
                    $facturation->periode_id == 4 ||
                    $facturation->periode_id == 5 ||
                    $facturation->periode_id == 2)
                montantMin = {{ $facturation->montant_periodique }};
                montantMax = {{ $facturation->reste_apayer }};
            @endif
            montantInput.addEventListener('input', function() {
                const montantValue = parseFloat(montantInput.value);
                if (montantValue < montantMin) {
                    errorMessage.style.display = 'block';
                    errorMessage.textContent = `Le montant doit être au minimum de ${montantMin} F CFA`;
                    montantInput.classList.add('is-invalid');
                } else if (montantValue > montantMax) {
                    errorMessage.style.display = 'block';
                    errorMessage.textContent = `Le montant ne  doit pas  être plus  de ${montantMax} F CFA`;
                    montantInput.classList.add('is-invalid');
                } else {
                    errorMessage.style.display = 'none';
                    montantInput.classList.remove('is-invalid');
                }
            });
        });
    </script>
@endpush
