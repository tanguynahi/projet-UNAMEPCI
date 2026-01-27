@extends('layouts.home_dashboard', ['title' => 'Boite a messagerie '])
@push('css')
@endpush
@section('content')
    <div class="col-lg-9">
        <div class="rbt-dashboard-content bg-color-white rbt-shadow-box">
            <div class="content">
                <div class="row align-items-start">
                    <div class="col-8">
                        <h4 class="rbt-title-style-3">Mes Messages</h4>
                    </div>
                    <div class="col-4">
                        <div class="call-to-btn text-center text-sm-center text-md-center text-lg-end position-relative">
                            <a class="rbt-btn btn-sm rbt-switch-btn rbt-switch-y"
                                href="{{ route('mutualiste.nouvelleDiscution') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                    class="bi bi-send-plus" viewBox="0 0 16 16">
                                    <path
                                        d="M15.964.686a.5.5 0 0 0-.65-.65L.767 5.855a.75.75 0 0 0-.124 1.329l4.995 3.178 1.531 2.406a.5.5 0 0 0 .844-.536L6.637 10.07l7.494-7.494-1.895 4.738a.5.5 0 1 0 .928.372zm-2.54 1.183L5.93 9.363 1.591 6.602z" />
                                    <path
                                        d="M16 12.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0m-3.5-2a.5.5 0 0 0-.5.5v1h-1a.5.5 0 0 0 0 1h1v1a.5.5 0 0 0 1 0v-1h1a.5.5 0 0 0 0-1h-1v-1a.5.5 0 0 0-.5-.5" />
                                </svg>
                                <span data-text="Nouveau Messages">Nouveau Messages</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="section-title">
                    <h4 class="rbt-title-style-3">
                    </h4>
                </div>
                <div class="rbt-dashboard-content bg-color-white rbt-shadow-box">
                    <div class="content">
                        <div class="section-title">
                            <div class="row align-items-start">
                                <div class="col-9">
                                    <h4 class="rbt-title-style-3">Liste des Messages</h4>
                                </div>
                            </div>
                        </div>
                        <div class="rbt-dashboard-table table-responsive mobile-table-750">
                            <table id="datatable-buttons" class="rbt-table table table-borderless">
                                <thead>
                                    <tr>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($messages as $message)
                                        <strong>
                                            <tr>
                                                @php
                                                    $statuts = $message
                                                        ->conversations()
                                                        ->where('statut', 2)
                                                        ->where('recepteur', 1)
                                                        ->latest('created_at')
                                                        ->first();
                                                    $heure = $message->conversations()->latest('created_at')->first();
                                                    $verification = $message
                                                        ->conversations()
                                                        ->where('statut', 2)
                                                        ->where('recepteur', 1)
                                                        ->latest('created_at')
                                                        ->first();
                                                @endphp
                                                <td
                                                    @if ($statuts) style="color:rgb(24, 24, 217);" @endif>
                                                    @if ($verification)
                                                        Administrateur
                                                    @else
                                                        Moi
                                                    @endif
                                                </td>
                                                <td
                                                    @if ($statuts) style="color:rgb(24, 24, 217);" @endif>
                                                    {{ $message->sujet }}
                                                </td>
                                                <td
                                                    @if ($statuts) style="color:rgb(24, 24, 217);" @endif>
                                                    {{ formatDate2($message->created_at) }}
                                                    {{-- {{ formatJour($message->created_at) }} --}}
                                                </td>
                                                @if ($statuts)
                                                    <td style="color:rgb(24, 24, 217);">
                                                        {{ $message->conversations()->where('statut', 2)->count() }}
                                                        message(s) non lu , il y a
                                                        {{ tempsEcouleDepuis($statuts->created_at) }}
                                                    </td>
                                                @else
                                                    <td>
                                                        il y a {{ tempsEcouleDepuis($heure->created_at) }}
                                                    </td>
                                                @endif
                                                <td
                                                    @if ($statuts) style="color:rgb(24, 24, 217);" @endif>
                                                    <a href="{{ route('mutualiste.message', $message->id) }}"
                                                        class="rbt-badge-5">
                                                        @if ($statuts)
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                height="16" fill="currentColor" class="bi bi-envelope"
                                                                viewBox="0 0 16 16">
                                                                <path
                                                                    d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z" />
                                                            </svg>
                                                        @else
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                                height="16" fill="currentColor"
                                                                class="bi bi-envelope-open" viewBox="0 0 16 16">
                                                                <path
                                                                    d="M8.47 1.318a1 1 0 0 0-.94 0l-6 3.2A1 1 0 0 0 1 5.4v.817l5.75 3.45L8 8.917l1.25.75L15 6.217V5.4a1 1 0 0 0-.53-.882zM15 7.383l-4.778 2.867L15 13.117zm-.035 6.88L8 10.082l-6.965 4.18A1 1 0 0 0 2 15h12a1 1 0 0 0 .965-.738ZM1 13.116l4.778-2.867L1 7.383v5.734ZM7.059.435a2 2 0 0 1 1.882 0l6 3.2A2 2 0 0 1 16 5.4V14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V5.4a2 2 0 0 1 1.059-1.765z" />
                                                            </svg>
                                                        @endif
                                                        <strong
                                                            @if ($statuts) style="color:rgb(24, 24, 217);" @endif
                                                            class="mx-2">Consulter
                                                        </strong>
                                                    </a>
                                                </td>
                                            </tr>
                                        </strong>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
