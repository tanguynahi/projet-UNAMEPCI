@extends('layouts.message', ['title' => 'Liste des Messages Particulier', 'toolbar' => '_toolbar2', 'breadcrumb' => 'Messages'])
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
                        <li class="flex-fill nav-item"><a class="nav-link border-0 active" data-bs-toggle="tab"
                                href="#chat-recent" role="tab" aria-selected="true">Messages</a></li>
                        <li class="flex-fill nav-item"><a class="nav-link border-0" data-bs-toggle="tab"
                                href="#nouveau-message" role="tab" aria-selected="false">Nouveau</a></li>
                    </ul>
                </div>
                @include('dashboard.messages.liste', ['messages' => $messages])

            </div>
            @php
                $heure = $message->conversations()->latest('created_at')->first();
            @endphp
            <div class="order-2 flex-grow-1 custom_scroll">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="Chris_Fox" role="tabpanel">
                        <!-- Chat: Header -->
                        <div class="py-xl-3 py-3 px-xxl-5 px-lg-4 px-3 chat-header">
                            <a class="d-flex color-600" href="javascript:void(0);" title="">
                                <img class="avatar rounded-circle" src="{{ asset('assets/icons/user2.png') }}"
                                    alt="Photo particulier">
                                <div class="ms-3">
                                    <h6 class="mb-0"> {{ $message->email }}
                                    </h6>
                                    <strong class="text-muted"> Sujet: {{ $message->sujet }}</strong> <br>
                                </div>
                            </a>
                            <div class="d-flex align-items-center">
                            </div>
                        </div>
                        <!-- Chat: body -->
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
                                        class="@if ($conversation->recepteur == 3) mb-3 d-flex flex-row-reverse align-items-end
                                    @elseif ($conversation->recepteur == 2)
                                        mb-3 d-flex flex-row align-items-end @endif ">
                                        <div
                                            class=" @if ($conversation->recepteur == 3) max-width-70 text-end
                                        @else
                                             max-width-70 @endif
                                       ">
                                            <div class="user-info mb-1">
                                                @if ($conversation->recepteur == 2)
                                                    {{-- <img class="avatar xs rounded-circle me-1"
                                                        src="{{ asset($message->mutualiste->lien_photo) }}"
                                                        alt="Photo mutualiste">
                                                    <strong>
                                                        {{ $message->nom }}
                                                    </strong>
                                                    <span class="text-muted small">
                                                        {{ formatJour($conversation->created_at) }}</span> --}}
                                                @else
                                                    <strong>
                                                        Moi
                                                    </strong>
                                                    <span class="text-muted small">
                                                        {{ formatJour($conversation->created_at) }}</span>
                                                @endif
                                            </div>
                                            <div
                                                class=" @if ($conversation->recepteur == 3) card border-0 p-3 bg-primary text-light
                                            @else
                                                    card p-3 @endif ">
                                                    <div class="message ">
                                                        {!! $conversation->message !!} <br>
                                                        @if ($fichiers)
                                                            @php
                                                                $extension = strtolower(
                                                                    pathinfo($fichiers->lien_document, PATHINFO_EXTENSION),
                                                                );
                                                            @endphp
                                                            @if ($extension == 'pdf')
                                                                <a href="{{ asset($fichiers->lien_document) }}" class="text-danger">
                                                                    <img class="w120 img-thumbnail"
                                                                        src="{{ asset('assets/home/images/message/PDF_file_icon.svg.png') }}"
                                                                        style="height: 30px; width:30px;" alt="PDF icon"> <br>
                                                                    fichier(s) joints
                                                                </a>
                                                            @elseif (in_array($extension, ['jpg', 'jpeg', 'png']))
                                                                <a href="{{ asset($fichiers->lien_document) }}">
                                                                    <img class="w120 img-thumbnail"
                                                                        src="{{ asset($fichiers->lien_document) }}"
                                                                        alt="Images jointes" />
                                                                </a>
                                                            @else
                                                            <a href="{{ asset($fichiers->lien_document) }}" class="text-danger">
                                                                <img src="{{ asset('assets/home/images/message/inconnu.png') }}"
                                                                style="height: 30px; width:30px;" alt="default icon">
                                                                    <br>
                                                                    <i class="fa fa-exclamation-circle"></i>
                                                                    fichier(s) joints
                                                            </a>
                                                            @endif
                                                        @endif
                                                    </div>
                                            </div>
                                        </div>
                                        <!-- More option -->
                                    </li>
                                </ul>
                            @endforeach
                        </div>
                        <!-- Chat: Footer -->
                        <form action="{{ route('discultion.emailParticulier', $message->id) }}" method="POST"
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
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <!-- Plugin Js -->
@endpush
