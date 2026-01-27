@extends('layouts.home_dashboard', ['title' => 'Boite a messagerie '])
@push('css')
@endpush
@section('content')
    @php
        use App\Models\FichierJointMessage;
    @endphp
    <div class="col-lg-9">
        <!-- Start Enrole Course  -->
        <div class="rbt-dashboard-content bg-color-white rbt-shadow-box">
            <div class="content">
                <div class="section-title">
                    <h4 class="rbt-title-style-3">Discultions
                    </h4>
                </div>
                <div class="container ">
                    <div class="row row-cols-2">
                        <div class="col">
                            <a href="{{ Route('mutualiste.boiteReception') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-arrow-left-square-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M16 14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2zm-4.5-6.5H5.707l2.147-2.146a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 0 0 .708-.708L5.707 8.5H11.5a.5.5 0 0 0 0-1" />
                                </svg>
                                <u>
                                    Retour
                                </u>
                            </a>
                        </div>
                        <div class="col"></div>
                        <div class="col">
                            <br>
                            <p>
                                <strong><u>Sujet :</u> {{ $messages->sujet }}</strong>
                            </p>
                        </div>
                        <div class="col"></div>
                    </div>
                </div>
                <div class="row">
                    <hr>
                    {{-- @if ($conversations->hasMorePages())
                        <div class="text-center">
                            <a href="{{ $conversations->nextPageUrl() }}" class="btn btn-light">
                                Voir les messages precedents
                            </a>
                        </div>
                    @endif --}}
                    @foreach ($conversations as $conversation)
                        @php
                            // condition pour recupere les fichier joins au produit
                            $fichiers = FichierJointMessage::where('message_id', $messages->id)
                                ->where('lien_document', '!=', null)
                                ->where('conversation_id', '=', $conversation->id)
                                ->first();
                        @endphp
                        <div class="col-md-10  @if ($conversation->recepteur == 2) offset-md-6 text-rigth @endif">
                            <div class="row">
                                <div class="col-1">
                                    <div class="thumbnail size-lg">
                                        @if ($conversation->recepteur == 2)
                                            @if (auth()->user()->hasRole('mutualiste'))
                                                @if (auth()->user()->mutualiste->lien_photo)
                                                    <img src="{{ asset(auth()->user()->mutualiste->lien_photo) }}"
                                                        style="height: 50px; width:50px;" alt="photo de profil"
                                                        class="radius-10">
                                                @else
                                                    <img src="{{ asset('assets/icons/user.png') }}"
                                                        style="height: 50px; width:50px;" alt="photo de profil"
                                                        class="radius-10">
                                                @endif
                                            @endif
                                        @else
                                            @if (auth()->user()->hasRole('adminsitrateur'))
                                                @if (auth()->user()->administrateur->lien_photo)
                                                    <img src="{{ asset(auth()->user()->administrateur->lien_photo) }}"
                                                        style="height: 50px; width:50px;" alt="photo de profil"
                                                        class="radius-10">
                                                @else
                                                    <img src="{{ asset('assets/icons/user.png') }}"
                                                        style="height: 50px; width:50px;" alt="photo de profil"
                                                        class="radius-10">
                                                @endif
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <div class="col-10">
                                    <div class="row">
                                        <div class="col-12">
                                            @if ($conversation->recepteur == 2)
                                                <strong>
                                                    Moi
                                                </strong>
                                            @else
                                                Administrateur
                                            @endif
                                        </div>
                                    </div>

                                    @if ($fichiers)
                                        <div class="row">
                                            <div class="col-6">
                                                @php
                                                    $extension = strtolower(
                                                        pathinfo($fichiers->lien_document, PATHINFO_EXTENSION),
                                                    );
                                                @endphp

                                                @if ($extension == 'pdf')
                                                    <a href="{{ asset($fichiers->lien_document) }}" class="text-primary">
                                                        <img src="{{ asset('assets/home/images/message/PDF_file_icon.svg.png') }}"
                                                            style="height: 30px; width:30px;" alt="PDF icon">
                                                        <br>
                                                        fichier(s) joints
                                                    </a>
                                                @elseif (in_array($extension, ['jpg', 'jpeg', 'png']))
                                                    <a href="{{ asset($fichiers->lien_document) }}" class="text-primary">
                                                        <img src="{{ asset($fichiers->lien_document) }}"
                                                            style="height: 40px; width:50px;" alt="image jointe">
                                                    </a>
                                                @else
                                                    <a href="{{ asset($fichiers->lien_document) }}" class="text-danger">
                                                        <img src="{{ asset('assets/home/images/message/inconnu.png') }}"
                                                            style="height: 30px; width:30px;" alt="default icon"> <br>
                                                        <i class="fa fa-exclamation-circle"></i>
                                                        fichier joints
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                    <div class="row">
                                        <div class="col-6">
                                            {!! $conversation->message !!}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6" style="color: rgba(10, 86, 185, 0.493);">
                                            <i> {{ formatJour($conversation->created_at) }}</i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                    @endforeach
                    {{-- @if ($conversations->previousPageUrl())
                        <div class="text-center">
                            <a href="{{ $conversations->previousPageUrl() }}" class="btn btn-light">
                                Voir les messages suivants
                            </a>
                        </div>
                    @endif --}}
                </div>
                <form action="{{ route('message.discultion', $messages->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="rbt-form-group">
                            <textarea name="message" rows="3" placeholder="Saisir le message ici..." id="message"
                                value="{{ old('message') }}" class=" @error('message') is-invalid @enderror" rows="3"
                                placeholder="Saisir le message ici..." required autofocus> </textarea>
                            @error('message')
                                <span class="invalid-feedback" role="alert">
                                    <strong>
                                        {{ $message }}
                                    </strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12 mt-5">
                            <input type="file" id="lien_document" accept=".pdf, .jpg, .jpeg, .png" name="lien_document"
                                value="{{ old('lien_document') }}"
                                class=" @error('lien_document') is-invalid @enderror">
                            @error('lien_document')
                                <span class="invalid-feedback" role="alert">
                                    <strong>
                                        {{ $message }}
                                    </strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12 mt-5">
                            <div class="rbt-form-group">
                                <button type="submit" class="rbt-btn btn-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-send" viewBox="0 0 16 16">
                                        <path
                                            d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576zm6.787-8.201L1.591 6.602l4.339 2.76z" />
                                    </svg>
                                    Envoyer
                                </button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
