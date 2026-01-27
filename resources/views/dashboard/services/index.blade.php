@extends('layouts.dashboard', ['title' => 'Liste des services', 'toolbar' => '_toolbar2', 'breadcrumb' => 'services'])

@push('css')
    <!-- Application vendor css url -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard/cssbundle/dataTables.min.css') }}">
@endpush

@section('content')
    <div class="row g-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Liste des Services</h6>
                    <div class="dropdown morphing scale-left">
                        <a href="#" class="card-fullscreen" data-bs-toggle="tooltip" title="Card Full-Screen"><i
                                class="icon-size-fullscreen"></i></a>
                        <a href="{{ route('services.create') }}" class="btn btn-primary d-inline">Ajouter un Service</a>
                    </div>
                </div>
                <div class="card-body" id="show_all_admin">
                    <div class="table-responsive">
                        <table id="myTable" class="table myDataTable table-hover align-middle mb-0 card-table">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Libelle</th>
                                    <th>Description</th>
                                    <th>Montant Maximun</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($services as $index => $service)
                                    @php
                                        $statusBadge =
                                            $service->status == 1
                                                ? '<span class="badge bg-success"> Actif </span>'
                                                : '<span class="badge bg-danger"> Inactif </span>';
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <span>{{ $service->libelle }}</span>
                                        </td>
                                        <td style="white-space: pre-line;">{{ $service->description }}</td>
                                        <td>{{ $service->montant_maximum }}</td>
                                        <td>{!! $statusBadge !!}</td>
                                        <td>
                                            @if ($service->status == 2)
                                                <a href="#deleteModal{{ $service->id }}" id="RestaurerDirection"
                                                    class="btn btn-link btn-sm text-danger refreshIcon"
                                                    data-bs-toggle="modal" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Restaurer"><i class="fa fa-refresh"></i></a>
                                            @else
                                                <a href="{{ route('interetService.liste', $service->id) }}"
                                                    id="ShowDirection" class="btn btn-link btn-sm text-success infoIcon"
                                                    data-bs-toggle="tooltip" data-bs-toggle="modal"
                                                    data-bs-target="#info_direction" data-bs-placement="top"
                                                    title="Interets">
                                                    <svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="20"
                                                        height="20" viewBox="0 0 512.000000 512.000000"
                                                        preserveAspectRatio="xMidYMid meet">
                                                        <g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)"
                                                            fill="#000000" stroke="none">
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
                                                </a>


                                                <a href="{{ route('services.edit', $service->id) }}" id="EditDirection"
                                                    class="btn btn-link btn-sm text-primary editIcon"
                                                    data-bs-toggle="tooltip" data-bs-target="#edit_direction"
                                                    title="Modifier"><i class="fa fa-pencil"></i></a>

                                                @if (Auth::user()->hasRole('super-administrateur'))
                                                    <a href="#deleteModal{{ $service->id }}" id="DeleteDirection"
                                                        class="btn btn-link btn-sm text-danger deleteIcon"
                                                        data-bs-toggle="modal" data-bs-toggle="tooltip"
                                                        data-bs-placement="top" data-bs-target="#delete_direction"
                                                        title="Supprimer"><i class="fa fa-trash"></i></a>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- Modal delete-->
                                    <div class="modal fade flip" id="deleteModal{{ $service->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-body p-5 text-center">
                                                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                                                        colors="primary:#405189,secondary:#f06548"
                                                        style="width:90px;height:90px">
                                                    </lord-icon>
                                                    <div class="mt-4 text-center">
                                                        @if ($service->status == 2)
                                                            <h4>Vous êtes sur le point de restaurer <br>une direction ?</h4>
                                                            <p class="text-muted fs-15 mb-4">En restaurant cette service,
                                                                vous
                                                                ramener
                                                                <br> toutes les informations la concernant de notre base de
                                                                données.
                                                            </p>
                                                        @else
                                                            <h4>Vous êtes sur le point de supprimer <br>une direction ?</h4>
                                                            <p class="text-muted fs-15 mb-4">En supprimant cette direction,
                                                                vous
                                                                supprimez
                                                                <br> toutes les informations le concernant de notre base de
                                                                données.
                                                            </p>
                                                        @endif
                                                        <div class="hstack gap-2 justify-content-center remove">
                                                            <button
                                                                class="btn btn-link link-success fw-medium text-decoration-none"
                                                                id="deleteRecord-close" data-bs-dismiss="modal"><i
                                                                    class="ri-close-line me-1 align-middle"></i>
                                                                Fermer</button>


                                                            {{-- <input name="_method" type="hidden" value="DELETE"> --}}
                                                            @if ($service->status == 2)
                                                                <form method="POST"
                                                                    action="{{ route('service.restaure', $service->id) }}">
                                                                    @method('PUT')
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-danger"
                                                                        id="delete-record">Oui,
                                                                        restaurer</button>
                                                                </form>
                                                            @else
                                                                <form method="POST"
                                                                    action="{{ route('directions.destroy', $service->id) }}">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button class="btn btn-danger" id="delete-record">Oui,
                                                                        supprimer</button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end modal -->
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <!-- Plugin Js -->
    <script src="{{ asset('assets/dashboard/js/bundle/dataTables.bundle.js') }}"></script>
    <!-- Vendor Script -->
    <script>
        // Data Table
        var langUrl = "{{ asset('DataTables/fr-FR.json') }}";

        $(document).ready(function() {
            $('#myTable').addClass('nowrap').dataTable({
                responsive: true,
                searching: true,
                paging: true,
                ordering: true,
                info: false,
                language: {
                    url: langUrl,
                },
                lengthMenu: [
                    [5, 10, 25, 50, 100],
                    // [5, 10, 25, 50, 100, 150, 200, 250],
                ],
                dom: 'Bfrtip', // Add this line to include Buttons
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
        });
    </script>
@endpush
