@extends('layouts.home_dashboard', ['title' => 'detail de la Demande d\'accompagnement'])
@section('content')
    <div class="col-lg-9">
        <!-- Start Instructor Profile  -->
        <div class="rbt-dashboard-content bg-color-white rbt-shadow-box">
            <div class="content">
                <div class="section-title">
                    <div class="row">
                        <div class="col-lg-10 col-md-10 col-sm-10">
                            <h4 class="rbt-title-style-3">Detail sur le prêt : <span class="text-primary">
                                    {{ $demandeaccompagnement->service->libelle }} </span> </h4>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <a href="{{ route('liste.demandeaccompagnement') }}" class="rbt-btn btn-sm">
                                Retour
                            </a>
                        </div>
                    </div>
                </div>
                <div class="rbt-profile-row row row--15">
                    <div class="col-lg-4 col-md-4">
                        <div class="rbt-profile-content b2">Service :</div>
                    </div>
                    <div class="col-lg-8 col-md-8">
                        <div class="rbt-profile-content b2"> <strong>{{ $demandeaccompagnement->service->libelle }}</strong>
                        </div>
                    </div>
                </div>
                <div class="rbt-profile-row row row--15 mt--15">
                    <div class="col-lg-4 col-md-4">
                        <div class="rbt-profile-content b2">Montant emprunter:</div>
                    </div>
                    <div class="col-lg-8 col-md-8">
                        <div class="rbt-profile-content b2">
                            <strong>
                                {{ formatMontant($demandeaccompagnement->montant_voulue) }}
                            </strong>
                        </div>
                    </div>
                </div>
                <div class="rbt-profile-row row row--15 mt--15">
                    <div class="col-lg-4 col-md-4">
                        <div class="rbt-profile-content b2">Montant total a remboursé :<br> (avec les interêt)</div>
                    </div>
                    <div class="col-lg-8 col-md-8">
                        <div class="rbt-profile-content b2">
                            <strong>{{ formatMontant($demandeaccompagnement->montant_apayer) }}</strong>
                        </div>
                    </div>
                </div>
                @if ( $demandeaccompagnement->status == 1 )
                <div class="rbt-profile-row row row--15 mt--15">
                    <div class="col-lg-4 col-md-4">
                        <div class="rbt-profile-content b2">Montant Versé :</div>
                    </div>
                    <div class="col-lg-8 col-md-8">
                        <div class="rbt-profile-content b2">
                            <strong>{{ formatMontant($demandeaccompagnement->payer ?? 0) }}</strong>
                        </div>
                    </div>
                </div>
                <div class="rbt-profile-row row row--15 mt--15">
                    <div class="col-lg-4 col-md-4">
                        <div class="rbt-profile-content b2">Montant Restant :</div>
                    </div>
                    <div class="col-lg-8 col-md-8">
                        <div class="rbt-profile-content b2">
                            <strong>{{ formatMontant($demandeaccompagnement->montant_apayer - $demandeaccompagnement->payer) }}</strong>
                        </div>
                    </div>
                </div>
                @endif
                <div class="rbt-profile-row row row--15 mt--15">
                    <div class="col-lg-4 col-md-4">
                        <div class="rbt-profile-content b2">Numero Tresor Money : </div>
                    </div>
                    <div class="col-lg-8 col-md-8">
                        <div class="rbt-profile-content b2">
                            <strong>{{ formatPhoneNumber($demandeaccompagnement->contact_tresormoney) }}</strong>
                        </div>
                    </div>
                </div>
                <div class="rbt-profile-row row row--15 mt--15">
                    <div class="col-lg-4 col-md-4">
                        <div class="rbt-profile-content b2">Etat :</div>
                    </div>
                    <div class="col-lg-8 col-md-8">
                        <div class="rbt-profile-content b2">
                            <strong>
                                @if ($demandeaccompagnement->status == 2)
                                    <span class="text-warning">En Attente </span>
                                @elseif ($demandeaccompagnement->status == 3)
                                    <span class="text-danger">Rejetée </span>
                                @else
                                    @if ($demandeaccompagnement->montant_apayer == $demandeaccompagnement->payer)
                                        <span class="text-success">Solde</span>
                                    @elseif ($demandeaccompagnement->montant_apayer > $demandeaccompagnement->payer)
                                        <span class="text-primary">En cours</span>
                                    @endif
                                @endif
                            </strong>
                        </div>
                    </div>
                </div>
                <div class="rbt-profile-row row row--15 mt--15">
                    <div class="col-lg-4 col-md-4">
                        <div class="rbt-profile-content b2">
                            @if ($demandeaccompagnement->status == 3)
                                motif
                            @else
                                Commentaire
                            @endif :
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-8">
                        <div class="rbt-profile-content b2">
                            <strong>
                                @if ($demandeaccompagnement->status == 3)
                                    <p> {!! $demandeaccompagnement->rejet !!}</p>
                                @else
                                    <p>{!! $demandeaccompagnement->commentaire !!}</p>
                                @endif
                            </strong>
                        </div>
                    </div>
                </div>
                @if ($demandeaccompagnement->status == 1)
                    @if ($demandeaccompagnement->montant_apayer > $demandeaccompagnement->payer)
                        <br>
                        <hr>
                        <h6 class="text-center"><u>Remboursement (dette)</u>
                            <svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="30" height="30"
                                viewBox="0 0 512.000000 512.000000" preserveAspectRatio="xMidYMid meet">
                                <g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)" fill="#000000"
                                    stroke="none">
                                    <path
                                        d="M3305 5101 c-86 -25 -173 -72 -232 -125 l-51 -46 -68 26 c-195 73
                                                                            -405 97 -496 56 -57 -25 -84 -70 -74 -124 3 -19 54 -115 112 -214 59 -98 108
                                                                            -184 111 -191 3 -8 -3 -13 -16 -13 -63 0 -152 -69 -187 -145 -29 -60 -32 -154
                                                                            -8 -210 15 -36 14 -39 -2 -48 -101 -57 -133 -219 -65 -329 l31 -49 -21 -41
                                                                            c-17 -33 -20 -55 -17 -108 4 -74 29 -122 80 -156 27 -17 29 -23 24 -63 -8 -56
                                                                            7 -112 40 -151 l26 -30 -80 -108 c-192 -258 -324 -494 -378 -677 -23 -78 -27
                                                                            -113 -31 -265 -4 -149 -2 -189 15 -270 11 -52 23 -102 27 -111 5 -13 -5 -17
                                                                            -57 -22 -99 -10 -175 -33 -283 -87 -90 -44 -149 -84 -319 -218 l-55 -43 -33
                                                                            17 c-49 26 -221 31 -323 10 -104 -21 -216 -76 -252 -123 -51 -67 -50 -73 185
                                                                            -646 117 -285 219 -529 228 -542 20 -30 73 -55 118 -55 123 0 361 171 437 314
                                                                            l23 44 140 7 c78 3 341 14 586 25 l445 18 120 36 c152 44 514 167 622 211 45
                                                                            18 205 106 355 195 447 265 469 283 476 393 3 56 1 66 -26 103 -20 27 -51 52
                                                                            -89 70 -51 26 -69 29 -150 29 -51 0 -93 1 -93 3 0 1 15 33 34 70 133 265 153
                                                                            656 49 962 -53 155 -169 348 -313 519 -38 46 -70 87 -70 91 0 4 19 15 43 25
                                                                            23 9 76 35 117 58 41 23 78 44 82 45 5 2 8 -9 8 -24 0 -43 36 -107 76 -133 31
                                                                            -22 68 -29 307 -58 150 -19 287 -31 305 -28 44 8 99 59 112 103 8 25 10 271 8
                                                                            762 l-3 725 -24 34 c-13 18 -43 42 -67 52 -42 19 -45 19 -315 -7 -302 -28
                                                                            -334 -37 -371 -100 -11 -18 -22 -34 -24 -34 -2 0 -42 29 -89 63 -104 77 -188
                                                                            120 -279 143 l-68 17 6 51 c19 139 19 166 2 210 -43 111 -166 152 -321 107z
                                                                            m172 -158 c9 -24 -37 -314 -55 -341 -22 -34 -90 -70 -178 -93 -62 -17 -119
                                                                            -22 -264 -26 l-185 -5 -90 153 c-49 84 -103 175 -118 202 l-29 49 64 -7 c94
                                                                            -10 210 -41 307 -81 47 -19 96 -38 109 -41 28 -7 70 13 77 37 8 26 64 79 114
                                                                            108 99 58 234 83 248 45z m235 -403 c32 -11 108 -58 170 -103 62 -45 125 -90
                                                                            141 -100 l27 -17 0 -455 0 -455 -42 -27 c-80 -51 -227 -124 -306 -153 -141
                                                                            -51 -233 -63 -442 -56 -239 7 -588 39 -620 56 -36 18 -60 41 -60 56 0 20 29
                                                                            44 55 44 28 0 65 43 65 75 0 12 -9 34 -20 47 -16 21 -31 26 -86 32 -103 11
                                                                            -133 36 -113 95 13 37 35 51 79 51 43 0 80 39 80 84 0 46 -37 76 -95 76 -82 0
                                                                            -124 72 -76 131 17 22 28 24 120 27 l101 4 0 79 0 79 -31 0 c-48 0 -100 29
                                                                            -115 65 -21 51 4 114 54 133 15 6 145 13 297 15 299 6 389 19 511 76 72 34
                                                                            146 98 165 142 11 28 16 31 48 26 20 -3 62 -15 93 -27z m988 -720 c0 -379 -3
                                                                            -690 -6 -690 -11 0 -417 50 -451 56 l-33 5 0 634 c0 349 1 635 3 636 2 1 443
                                                                            46 470 48 16 1 17 -38 17 -689z m-1710 -790 c319 -27 519 -25 622 6 12 4 45
                                                                            -29 119 -118 282 -338 371 -569 356 -925 -10 -252 -80 -436 -230 -599 -60 -66
                                                                            -50 -61 -339 -180 l-233 -96 -180 5 c-155 5 -192 9 -270 32 -49 14 -94 30 -98
                                                                            34 -4 4 11 16 33 26 87 39 162 167 145 248 -18 83 -71 142 -152 171 -40 14
                                                                            -256 39 -446 52 -111 8 -103 1 -133 114 -45 171 -38 389 17 555 53 159 177
                                                                            370 356 608 l74 97 62 -5 c34 -3 168 -14 297 -25z m-480 -1519 c190 -19 235
                                                                            -31 245 -62 10 -31 -22 -76 -71 -99 -45 -22 -49 -22 -327 -15 l-282 7 -24 -26
                                                                            c-29 -31 -31 -81 -4 -108 13 -13 148 -53 414 -123 358 -95 407 -106 534 -116
                                                                            77 -6 185 -9 240 -6 l100 5 385 159 385 158 82 0 c65 0 86 -4 100 -18 14 -13
                                                                            15 -21 7 -35 -16 -26 -648 -400 -734 -434 -128 -51 -632 -216 -695 -227 -60
                                                                            -11 -1121 -56 -1164 -49 -19 3 -40 47 -157 332 -74 181 -134 333 -134 336 0 9
                                                                            175 150 248 199 84 57 181 101 267 122 97 24 347 24 585 0z m-1116 -706 c98
                                                                            -239 174 -439 171 -446 -4 -6 -53 -45 -109 -86 -105 -79 -182 -121 -196 -106
                                                                            -5 4 -98 229 -208 498 l-200 490 46 22 c46 21 225 60 287 62 l31 1 178 -435z" />
                                    <path
                                        d="M3079 2771 c-24 -25 -29 -38 -29 -79 0 -49 0 -49 -37 -55 -92 -15
                                                                            -207 -100 -244 -180 -23 -53 -25 -162 -3 -215 38 -92 120 -140 297 -173 237
                                                                            -44 292 -72 292 -149 0 -102 -169 -169 -318 -126 -77 22 -113 56 -132 121 -19
                                                                            63 -35 79 -82 79 -67 0 -95 -82 -58 -172 33 -78 154 -170 248 -187 37 -7 37
                                                                            -8 37 -56 0 -62 31 -102 80 -102 49 0 80 40 80 102 0 48 0 49 38 56 100 19
                                                                            207 98 245 183 28 64 27 161 -3 226 -42 88 -113 124 -332 167 -177 35 -228 58
                                                                            -244 109 -20 62 14 114 99 150 43 19 64 22 132 18 119 -7 200 -56 211 -129 4
                                                                            -21 15 -47 26 -58 24 -27 78 -27 109 -2 53 43 11 186 -76 256 -51 41 -76 54
                                                                            -145 76 -55 17 -55 17 -58 60 -3 55 -17 84 -49 98 -37 17 -52 14 -84 -18z" />
                                    <path
                                        d="M1060 4079 c-30 -5 -99 -25 -153 -43 -302 -104 -536 -359 -623 -681
                                                                            -28 -104 -26 -369 4 -478 92 -335 346 -592 680 -688 288 -83 618 -15 859 175
                                                                            232 184 364 456 364 752 1 281 -95 510 -292 700 -191 183 -379 262 -644 269
                                                                            -77 2 -165 0 -195 -6z m395 -179 c288 -92 492 -309 561 -596 24 -101 22 -276
                                                                            -5 -379 -42 -158 -114 -278 -233 -390 -160 -151 -335 -219 -553 -218 -235 1
                                                                            -428 84 -586 250 -148 155 -219 337 -219 559 0 138 23 235 84 359 78 158 194
                                                                            275 346 354 145 74 221 91 400 87 105 -3 150 -8 205 -26z" />
                                    <path d="M826 3666 c-54 -20 -106 -65 -141 -121 -26 -43 -30 -57 -30 -124 0
                                                                            -66 4 -83 30 -130 36 -66 85 -107 149 -126 102 -31 193 -8 266 64 128 129 96
                                                                            340 -65 423 -51 27 -156 34 -209 14z m150 -164 c55 -44 59 -109 10 -158 -26
                                                                            -26 -42 -34 -71 -34 -49 0 -105 53 -105 100 0 65 43 110 105 110 23 0 49 -8
                                                                            61 -18z" />
                                    <path d="M1172 3177 c-433 -433 -433 -434 -430 -472 4 -49 42 -80 85 -71 21 5
                                                                            148 126 458 436 410 410 429 431 429 466 0 44 -31 74 -78 74 -28 0 -77 -46
                                                                            -464 -433z" />
                                    <path
                                        d="M1489 3089 c-57 -9 -79 -20 -131 -67 -176 -159 -62 -455 175 -454
                                                                            109 0 189 48 237 145 98 197 -62 411 -281 376z m85 -163 c93 -39 87 -164 -8
                                                                            -196 -64 -21 -136 33 -136 100 0 36 30 81 63 95 40 18 41 18 81 1z" />
                                </g>
                            </svg>
                        </h6>
                        <form action="{{ route('enregis.pret') }}" id="paymentForm" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <input type="text" name="idDemandeaccompa" value="{{ $demandeaccompagnement->id }}"
                                    style="display: none;">
                                <input type="text" name="libPro" value="Pret" style="display: none">
                                <input type="text" name="libelleRed"
                                    value="{{ $demandeaccompagnement->service->libelle }}" style="display: none">
                                <div class="col-4 mb--20 col-md-4 col-sm-4 col-12">
                                    <label>Mode de paiement</label>
                                    <div class="rbt-modern-select bg-transparent height-45">
                                        <select class="w-100 @error('modepaiement') is-invalid @enderror"
                                            name="modepaiement" id="modepaiement" autocomplete="modepaiement" autofocus>
                                            <option value="0" selected>Choisir Moyen..</option>
                                            <option value="Cash" {{ old('modepaiement') == 'Cash' ? 'selected' : '' }}>
                                                Cash
                                            </option>
                                            <option value="Chèque" {{ old('modepaiement') == 'Chèque' ? 'selected' : '' }}>
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
                                            max="{{ $demandeaccompagnement->montant_apayer - $demandeaccompagnement->payer }}">
                                        <div id="error-message" style="color: red; display: none;">Montant dépasse le
                                            maximum
                                            autorisé.</div>
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
                                            value="{{ old('document') }}" class="@error('document') is-invalid @enderror"
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
                    @endif
                    @if (!empty($paiements))
                        <br>
                        <hr>
                        <h6 class="text-secondary text-center"><u>Liste des Versements</u></h6>

                        <div class="rbt-dashboard-table table-responsive mobile-table-750">
                            {{-- id="datatable-buttons" --}}
                            <table class="rbt-table table table-borderless">
                                <thead>
                                    <tr>
                                        {{-- <th>Redevance</th> --}}
                                        <th>Reference</th>
                                        <th>Moyen</th>
                                        <th>Montant</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($paiements as $paiement)
                                        <tr>
                                            <td>
                                                @if (!empty($paiement->reference))
                                                    {{ Str::limit($paiement->reference, 9) }}
                                                @else
                                                    {{ Str::limit($paiement->code_paiement, 9) }}
                                                @endif
                                            </td>
                                            <td>{{ !empty($paiement->moyen_paiement) ? $paiement->moyen_paiement : 'Non défini' }}
                                            </td>
                                            <td>
                                                {{ formatMontant($paiement->montant_initial) }}
                                            </td>
                                            <td>{{ !empty($paiement->date_paiement_initial) ? formatDate($paiement->date_paiement_initial) : 'Aucune date' }}
                                            </td>
                                            <td>
                                                @if ($paiement->status == 2)
                                                    <span
                                                        style="color: blue; background-color: rgba(0, 0, 255, 0.3); border: 1px solid rgb(18, 18, 209); padding: 1px; border-radius: 5px;">
                                                        EN ATTENTE
                                                    </span>
                                                @elseif($paiement->status == 3)
                                                    <span
                                                        style="color: red; background-color: rgba(255, 0, 0, 0.455)); border: 1px solid rgba(255, 0, 0, 0.455); padding: 1px; border-radius: 5px;">
                                                        ECHEC
                                                    </span>
                                                @else
                                                    <span
                                                        style="color: rgba(54, 143, 54, 0.615); background-color: rgba(54, 143, 54, 0.1); border: 1px solid rgba(54, 143, 54, 0.615); padding: 1px; border-radius: 5px;">
                                                        VALIDE
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-start">
                                                    @if ($paiement->status == 1)
                                                        <a class="rbt-btn btn-xs bg-primary-opacity radius-round mx-1"
                                                            target="target"
                                                            href="{{ route('telecharger.recu', $paiement->id) }}"
                                                            title="Télécharger">
                                                            <i class="feather-download"></i>
                                                        </a>
                                                    @endif
                                                    @if (!empty($paiement->p_cash))
                                                        <a href="{{ route('imagesPaie.pret', $paiement->id) }}"
                                                            class="rbt-btn btn-xs bg-primary-opacity radius-round mx-1"
                                                            target="target">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                                                height="20" fill="currentColor"
                                                                class="bi bi-card-image" viewBox="0 0 16 16">
                                                                <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0" />
                                                                <path
                                                                    d="M1.5 2A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2zm13 1a.5.5 0 0 1 .5.5v6l-3.775-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12v.54L1 12.5v-9a.5.5 0 0 1 .5-.5z" />
                                                            </svg>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                @endif
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
                    // paymentForm.action = "{{ route('detteRembou.hub') }}"; // Change action
                           paymentForm.action = "{{ route('espaPay', 3) }}"; // Change action
                } else if (modepaiement === "Cash" || modepaiement === "Chèque" || modepaiement === "Virement") {
                    referenceID.style.display = 'block';
                    dateID.style.display = 'block';
                    documentID.style.display = 'block';
                    Enregistrer.style.display = 'block';
                    onlinePayment.style.display = 'none';
                    paymentForm.action = "{{ route('enregis.pret') }}"; // Change action
                } else if (modepaiement === "0") {
                    referenceID.style.display = 'none';
                    dateID.style.display = 'none';
                    documentID.style.display = 'none';
                    Enregistrer.style.display = 'none';
                    onlinePayment.style.display = 'none';
                    paymentForm.action = "{{ route('enregis.pret') }}"; // Change action
                }
            }

            togglePaymentFields();
            document.getElementById('modepaiement').addEventListener('change', togglePaymentFields);
        });
    </script>
    <script>
        document.getElementById('montant').addEventListener('input', function() {
            const montantInput = document.getElementById('montant');
            const maxMontant = parseFloat(montantInput.getAttribute('max'));
            const errorMessage = document.getElementById('error-message');
            const montantValue = parseFloat(montantInput.value);

            if (montantValue > maxMontant) {
                errorMessage.style.display = 'block';
            } else {
                errorMessage.style.display = 'none';
            }
        });
    </script>
@endpush
