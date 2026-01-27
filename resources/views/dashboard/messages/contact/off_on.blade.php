<div class="tab-content border-top custom_scroll">
    <div class="tab-pane fade " id="chat-recent" role="tabpanel">
        @if ($messages->count() > 0)
            <ul class="nav nav-tabs list-group list-group-custom list-group-flush mb-0" role="tablist">
                @foreach ($messages as $index => $message)
                    @php
                        $statuts = $message
                            ->conversations()
                            ->where('statut', 2)
                            ->where('recepteur', 2)
                            ->latest('created_at')
                            ->first();
                        $heure = $message->conversations()->latest('created_at')->first();
                        // dd($heure);
                    @endphp
                    <li class="list-group-item" id="searchResults">
                        <a href="{{ route('messages.edit', $message->id) }}" class="d-flex">
                            {{-- @if ($message->mutualiste->lien_photo) --}}
                            <img class="avatar rounded-circle"
                                src="{{ asset($message->mutualiste->lien_photo ?? 'assets/icons/user2.png') }}"
                                alt="Photo mutualiste">
                            {{-- @endif --}}
                            <div class="flex-fill ms-3 text-truncate">
                                <h6 class="d-flex justify-content-between mb-0">
                                    <span>{{ $message->mutualiste->prenom ?? '' }}
                                        {{ $message->mutualiste->nom ?? '' }}</span>
                                    <small class="msg-time">
                                        @if ($statuts)
                                            <span class="badge bg-light text-dark ms-2 ms-auto">
                                                {{ $message->conversations()->where('statut', 2)->count() }}
                                                message(s) non lu
                                            </span>
                                            il y a {{ tempsEcouleDepuis($statuts->created_at) }}
                                        @else
                                            il y a {{ tempsEcouleDepuis($heure->created_at) }}
                                        @endif
                                    </small>
                                </h6>
                                <span class="text-muted">{{ $message->sujet }} </span>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
        {{-- les messages envoyer pas email --}}
        @if ($messageEmails->count() > 0)
            <ul class="nav nav-tabs list-group list-group-custom list-group-flush mb-0" role="tablist">
                <hr>
                <span class="text-center" style="background-color: rgb(143, 143, 201);">Liste des Messages des
                    Particuliers (Messages Envoyer par Email )</span>
                <hr>
                @foreach ($messageEmails as $index => $messageEmail)
                    @php

                        $heure = $messageEmail->conversations()->latest('created_at')->first();
                        // dd($heure);
                    @endphp
                    <li class="list-group-item" id="searchResults">
                        <a href="{{ route('message.editeparticulier', $messageEmail->id) }}" class="d-flex">
                            {{--  --}}
                            {{-- <img class="avatar rounded-circle" src="{{ asset($message->mutualiste->lien_photo)}}"
                            alt="Photo mutualiste"> --}}
                            <div class="flex-fill ms-3 text-truncate">
                                <h6 class="d-flex justify-content-between mb-0">
                                    <span>
                                        {{ $messageEmail->email }}
                                    </span>
                                    <small class="msg-time">
                                        {{-- il y a {{ tempsEcouleDepuis($heure->created_at) }}                                --}}
                                    </small>
                                </h6>
                                <span class="text-muted">{{ $messageEmail->sujet }} </span>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
    {{-- @include('dashboard.messages.nouveau') --}}
    <div class="tab-pane fade show active" id="nouveau-message" role="tabpanel">
        <ul class="list-unstyled list-group list-group-custom list-group-flush mb-0">
            <li class="list-group-item">
                <a href="{{ route('message.nouveauAutre') }}" class="d-flex">
                    <i class="fa fa-envelope"></i>
                    <div class="text-muted">
                        <span class="mx-2">Envoyer par email a un particulier</span>
                    </div>
                </a>
            </li>
            <li class="list-group-item">
                <a href="{{ route('message.nouveauMutualiste') }} " class="d-flex">
                    <i class="fa fa-envelope"></i>
                    <div class="text-muted">
                        <span class="mx-2">Ecrire a un Mutualiste</span>
                    </div>
                </a>
            </li>
        </ul>
    </div>
</div>