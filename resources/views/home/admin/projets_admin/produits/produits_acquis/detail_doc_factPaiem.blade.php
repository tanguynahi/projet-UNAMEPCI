@extends('layouts.home_dashboard', ['title' => 'Les images de la facture'])
@section('content')
    <div class="col-lg-9">
        <div class="rbt-dashboard-content bg-color-white rbt-shadow-box mb--60">
            <div class="content">
                <div class="section-title">
                    <h4 class="rbt-title-style-3 text-center">Images des factures</h4>
                </div>


                <div class="row g-5">
                    @foreach ($documentPaiements as $documentPaiement)
                        @php
                            $extension = strtolower(pathinfo($documentPaiement->lien_photo, PATHINFO_EXTENSION));
                        @endphp
                        <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                            <div class="rbt-counterup variation-01 rbt-hover-03 rbt-border-dashed bg-primary-opacity">
                                @if ($extension == 'pdf')
                                    <a href="{{ asset($documentPaiement->lien_photo) }}">
                                        <div class="inner"
                                            style="background-image: url('{{ asset('assets/home/images/message/PDF_file_icon.svg.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat; width: 100%; height: 200px;">
                                        </div>
                                    </a>
                                @elseif (in_array($extension, ['jpg', 'jpeg', 'png', 'jfif', 'heic', 'heif', 'webp']))
                                    <a href="{{ asset($documentPaiement->lien_photo) }}">
                                        <div class="inner"
                                            style="background-image: url('{{ asset($documentPaiement->lien_photo) }}'); background-size: cover; background-position: center; background-repeat: no-repeat; width: 100%; height: 200px;">
                                        </div>
                                    </a>
                                @else
                                    <a href="{{ asset($documentPaiement->lien_photo) }}">
                                        <div class="inner"
                                            style="background-image: url('{{ asset('assets/home/images/message/inconnu.png') }}'); background-size: cover; background-position: center; background-repeat: no-repeat; width: 100%; height: 200px;">
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
@endsection
