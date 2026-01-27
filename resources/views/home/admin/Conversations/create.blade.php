@extends('layouts.home_dashboard', ['title' => 'Boite a messagerie '])
@push('css')
@endpush
@section('content')
    <div class="col-lg-9">
        <!-- Start Enrole Course  -->
        <div class="rbt-dashboard-content bg-color-white rbt-shadow-box">
            <div class="content">
                <div class="section-title">
                    <h4 class="rbt-title-style-3">Mes Conversation
                    </h4>
                </div>
                <div class="col">
                    <a href="{{ back()->getTargetUrl() }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-arrow-left-square-fill" viewBox="0 0 16 16">
                            <path
                                d="M16 14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2zm-4.5-6.5H5.707l2.147-2.146a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 0 0 .708-.708L5.707 8.5H11.5a.5.5 0 0 0 0-1" />
                        </svg>
                        Retour
                    </a>
                </div>
                <br>
                <form action="{{ route('mutualiste.traitementMessage', $messages->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <div class="rbt-form-group">
                                <input id="sujet" name="sujet" type="text" value="{{ old('sujet') }}"
                                    class=" @error('sujet') is-invalid @enderror" required placeholder="Sujet">
                                @error('sujet')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>
                                            {{ $message }}
                                        </strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                            <input type="file" id="lien_document" accept=".pdf, .jpg, .jpeg, .png" value="{{ old('lien_document') }}"
                                autofocus name="lien_document" class=" @error('lien_document') is-invalid @enderror">
                            @error('lien_document')
                                <span class="invalid-feedback" role="alert">
                                    <strong>
                                        {{ $message }}
                                    </strong>
                                </span>
                            @enderror

                        </div>
                    </div>
                    <br>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="rbt-form-group">
                            {{-- <label for="lastname">Sujet</label> --}}
                            <textarea name="message" id="message" value="{{ old('message') }}" class=" @error('message') is-invalid @enderror"
                                rows="3" placeholder="Saisir le message ici..." required></textarea>
                            @error('message')
                                <span class="invalid-feedback" role="alert">
                                    <strong>
                                        {{ $message }}
                                    </strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row text-center content-justify-center">

                        <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-5">
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
        <!-- End Enrole Course  -->
    </div>
@endsection
