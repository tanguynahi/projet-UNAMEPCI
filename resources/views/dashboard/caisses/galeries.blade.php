@extends('layouts.dashboard', ['title' => 'Liste des demandes - projets', 'toolbar' => '_toolbar2', 'breadcrumb' => 'Demandes-projets'])

@push('css')
    <!-- Application vendor css url -->
    <link rel="stylesheet" href="{{ asset('assets/dashboard/cssbundle/dataTables.min.css') }}">
    <style>
        .image-wrapper {
            position: relative;
            display: inline-block;
        }

        .image-wrapper img {
            display: block;
            /* width: 100%; */
            /* height: auto; */
        }

        .image-wrapper .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            flex-direction: column;
            font-size: 24px;
        }

        .image-wrapper:hover .overlay {
            opacity: 1;
        }

        .image-wrapper .overlay span {
            margin-top: 10px;
            font-size: 16px;
        }
    </style>
@endpush

@section('content')
    <div class="row g-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Liste des Document du Paiement</h6>
                    <div class="dropdown morphing scale-left">
                        <a href="#" class="card-fullscreen" data-bs-toggle="tooltip" title="Card Full-Screen"><i
                                class="icon-size-fullscreen"></i></a>
                        <a href="{{ route('paiements.caisse') }}" class="btn btn-primary d-inline">Retour</a>
                    </div>
                </div>
                <div class="card-body" id="show_all_produits">
                    <div class="row">
                        @foreach ($documentPaiements as $documentPaiement)
                            @php

                                $extension = strtolower(pathinfo($documentPaiement->lien_photo, PATHINFO_EXTENSION));
                                $imgUrl = $documentPaiement->lien_photo
                                    ? asset($documentPaiement->lien_photo)
                                    : asset('assets/dashboard/img/default-img.png');
                            @endphp
                            <div class="col-12 col-lg-3 col-sm-3 col-md-3">
                                <div class="image-wrapper">
                                    @if ($extension == 'pdf')
                                        <a href="{{ asset($documentPaiement->lien_photo) }}" target="_blank">
                                            <img src="{{ asset('assets/home/images/message/PDF_file_icon.svg.png') }}"
                                                alt="Image du document" height="150px;" width="300px;">
                                            <div class="overlay">
                                                <i class="fa fa-eye"></i>
                                                <span>Visualiser</span>
                                            </div>
                                        </a>
                                    @elseif (in_array($extension, ['jpg', 'jpeg', 'png', 'jfif', 'heic', 'heif', 'webp']))
                                        <a href="{{ $imgUrl }}" target="_blank">
                                            <img src="{{ $imgUrl }}" alt="Image du document" height="150px;"
                                                width="300px;">
                                            <div class="overlay">
                                                <i class="fa fa-eye"></i>
                                                <span>Visualiser</span>
                                            </div>
                                        </a>
                                    @else
                                        <a href="{{ asset($documentPaiement->lien_photo) }}" target="_blank">
                                            <img src="{{ asset('assets/home/images/message/inconnu.png') }}"
                                                alt="Image du document" height="150px;" width="300px;">
                                            <div class="overlay">
                                                <i class="fa fa-eye"></i>
                                                <span>Visualiser</span>
                                            </div>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
@endpush
