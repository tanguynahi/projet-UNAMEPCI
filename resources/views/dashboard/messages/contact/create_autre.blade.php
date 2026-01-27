@extends('layouts.message', ['title' => 'Liste des Messages du Particuliers', 'toolbar' => '_toolbar2', 'breadcrumb' => 'Messages'])
@push('css')
@endpush
@section('content')
    @php
        use App\Models\FichierJointMessage;
    @endphp
    <div class="chat-app">
        <div class="d-flex flex-nowrap">
            <div class="order-1">
                <div class="c-list">
                    {{-- <div class="input-group mb-2">
                        <input type="text" class="form-control mb-1" placeholder="Recherche...">
                    </div> --}}
                    <ul class="nav nav-tabs tab-page-toolbar rounded text-center mb-1" role="tablist">
                        <li class="flex-fill nav-item"><a class="nav-link border-0 " data-bs-toggle="tab"
                                href="#chat-recent" role="tab" aria-selected="false">Messages</a></li>
                        <li class="flex-fill nav-item"><a class="nav-link border-0 active" data-bs-toggle="tab"
                                href="#nouveau-message" role="tab" aria-selected="true">Nouveau</a></li>
                    </ul>
                </div>
               @include('dashboard.messages.contact.off_on')
            </div>
            @php
                // $heure = $message->conversations()->latest('created_at')->first();
            @endphp
            <div class="order-2 flex-grow-1 custom_scroll">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="Chris_Fox" role="tabpanel">
                        <!-- Chat: Header -->
                        <div class="py-xl-3 py-3 px-xxl-5 px-lg-4 px-3 chat-header">
                            <div class="card-body" id="show_all_mutualiste">
                                <h6 class="fw-bold">Ecrire a un mutualiste</h6>
                                <form action="{{ route('message.administrateur_particulier',$messagesAutre->id) }}" method="POST" id="add_mutualiste_form" enctype="multipart/form-data"
                                    class="needs-validation" novalidate>
                                    {{--  --}}
                                    {{--  --}}
                                    @csrf
                                    <div class="row g-3">
                                        {{-- <div class="col-lg-4 col-md-4">
                                            <div class="form-floating">
                                                <select class="form-control show-tick ms select2" name="mutualiste_id"
                                                    data-placeholder="Select">
                                                    <option value="">Sélectionner un Mutualiste</option>
                                                    @foreach ($mutualistes as $mutualiste)
                                                        <option value="{{ $mutualiste->id }}">
                                                            <a
                                                                href="{{ route('message.nouveauMutualiste', $mutualiste->id) }}">
                                                                {{ $mutualiste->nom }} {{ $mutualiste->prenom }}
                                                                ({{ $mutualiste->contact }})
                                                            </a>
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div> --}}
                                        <div class="col-lg-8 col-md-8">
                                            <div class="form-floating">
                                                <input type="email"
                                                    class="form-control @error('email') is-invalid @enderror" id="email"
                                                    name="email" value="{{ old('email') }}" placeholder="email"
                                                    autocomplete="email" autofocus required>
                                                <label>email<span class="text-danger fw-bold">*</span></label>
                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4">
                                            <div class="form-floating">
                                                <input type="text"
                                                    class="form-control @error('sujet') is-invalid @enderror" id="sujet"
                                                    name="sujet" value="{{ old('sujet') }}" placeholder="Sujet"
                                                    autocomplete="sujet" autofocus required>
                                                <label>sujet<span class="text-danger fw-bold">*</span></label>
                                                @error('sujet')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-lg-12 col-md-12 mt-4">
                                            <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message"
                                                value="{{ old('message') }}" placeholder="Saisir le message ici..." autocomplete="message" autofocus required
                                                rows="10"></textarea>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="form-floating">
                                                <input type="file" name="lien_document" id="lien_document"
                                                    class="form-control @error('lien_document') is-invalid @enderror"
                                                    name="" value="{{ old('lien_document') }}" placeholder=""
                                                    autocomplete="email" autofocus required>
                                            </div>
                                        </div>
                                        <p><span class="text-danger fw-bold">*</span>Champs obligatoires.</p>
                                    </div>
                                    <div class="row g-3 ">
                                        <div class="mx-auto d-flex justify-content-center">

                                            <button type="reset" class="btn btn-secondary w-25 mx-2">Annuler</button>
                                            <button type="submit" id="add_mutualiste_btn"
                                                class="btn btn-primary w-25 mx-2">Envoyer</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        {{-- <!-- Chat: body -->
                        <div class="chat-history custom_scroll">
                            @foreach ($conversations as $conversation)
                                @php
                                    // condition pour recupere les fichier joins au produit
                                    $fichiers = FichierJointMessage::where('message_id', $message->id)
                                        ->where('lien_document', '!=', null)
                                        ->where('conversation_id', '=', $conversation->id)
                                        ->first();
                                    // dd($fichiers);
                                @endphp
                                <ul class="py-xl-3 py-2 px-xxl-5 px-lg-4 px-3 list-unstyled mb-0">
                                    <!-- Chat: left -->
                                    <li
                                        class="@if ($conversation->recepteur == 1) mb-3 d-flex flex-row-reverse align-items-end
                                    @else
                                        mb-3 d-flex flex-row align-items-end @endif ">
                                        <div
                                            class=" @if ($conversation->recepteur == 1) max-width-70 text-end
                                        @else
                                             max-width-70 @endif
                                       ">
                                            <div class="user-info mb-1">
                                                @if ($conversation->recepteur == 2)
                                                    <img class="avatar xs rounded-circle me-1"
                                                        src="{{ asset($message->mutualiste->lien_photo) }}" alt="Photo mutualiste">

                                                    <span class="text-muted small">Aujourd'hui, à 10h10
                                                        {{ $conversation->created_at }}</span>
                                                    <strong>
                                                        {{ $message->nom }}
                                                    </strong>
                                                @else
                                                    <span class="text-muted small">Aujourd'hui, à 10h10
                                                        {{ $conversation->created_at }}</span>
                                                    <strong>
                                                        Moi
                                                    </strong>
                                                @endif
                                            </div>
                                            <div
                                                class=" @if ($conversation->recepteur == 1) card border-0 p-3 bg-primary text-light
                                            @else
                                                    card p-3 @endif ">
                                                <div class="message ">
                                                    {!! $conversation->message !!}
                                                    @if ($fichiers)
                                                    <br>
                                                        <img class="w120 img-thumbnail"
                                                            src="{{ asset($fichiers->lien_document) }}"
                                                            alt="Images jointes" />
                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                        <!-- More option -->
                                        <div class="dropdown morphing">
                                            <a href="#" class="nav-link py-2 px-3 text-muted"
                                                data-bs-toggle="dropdown" aria-expanded="false"><i
                                                    class="fa fa-ellipsis-v"></i></a>
                                            <ul class="dropdown-menu border-0 shadow">
                                                <li><a class="dropdown-item" href="#">Modifier</a></li>
                                                <li><a class="dropdown-item" href="#">Supprimer</a></li>
                                            </ul>
                                        </div>
                                    </li>
                                </ul>
                            @endforeach
                        </div>
                        <!-- Chat: Footer -->
                        <form action="{{ route('message.administrateur', $message->id) }}" method="POST"
                            id="update_grade_form" enctype="multipart/form-data" class="needs-validation" novalidate>
                            @csrf
                            @method('Post')
                            <div class="py-xl-3 py-2 px-xxl-5 px-lg-4 px-3 chat-msg">
                                <div class="btn btn-link file-input">
                                    <input type="file" class="form-control" name="lien_document" id="lien_document">
                                    <label for="lien_document" class="fa fa-paperclip"></label>
                                </div>
                                <input type="text" class="form-control bg-transparent border-0"
                                    placeholder="Saisir le message ici..." name="message" autocomplete="message" autofocus
                                    required>
                                @error('message')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <button type="submit" class="btn bg-secondary text-light text-uppercase">Envoyer</button>
                            </div>
                        </form> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <!-- Plugin Js -->
@endpush
