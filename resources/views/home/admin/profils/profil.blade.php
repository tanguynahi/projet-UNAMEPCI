@extends('layouts.home_dashboard', ['title' => 'Profil Mutualiste'])
@section('content')
    @php
        $mutualiste = auth()->user()->mutualiste;
        // dd($mutualiste);
    @endphp
    <div class="col-lg-9">
        <!-- Start Instructor Profile  -->
        <div class="rbt-dashboard-content bg-color-white rbt-shadow-box">
            <div class="content">
                <div class="section-title">
                    <h4 class="rbt-title-style-3">Mon Profil</h4>
                </div>
                <!-- Start Profile Row  -->
                {{-- @foreach ($mutualistes as $mutualiste) --}}


                <div class="rbt-profile-row row row--15">
                    <div class="col-lg-4 col-md-4">
                        <div class="rbt-profile-content b2">Date d'inscription </div>
                    </div>
                    <div class="col-lg-8 col-md-8">
                        <div class="rbt-profile-content b2">{{ $mutualiste->created_at }} </div>
                    </div>
                </div>
                <!-- End Profile Row  -->
                <!-- Start Profile Row  -->
                <div class="rbt-profile-row row row--15 mt--15">
                    <div class="col-lg-4 col-md-4">
                        <div class="rbt-profile-content b2">Numero Matricule</div>
                    </div>
                    <div class="col-lg-8 col-md-8">
                        <div class="rbt-profile-content b2">{{ $mutualiste->matricule }}</div>
                    </div>
                </div>
                <!-- End Profile Row  -->
                <!-- Start Profile Row  -->
                <div class="rbt-profile-row row row--15 mt--15">
                    <div class="col-lg-4 col-md-4">
                        <div class="rbt-profile-content b2">Nom</div>
                    </div>
                    <div class="col-lg-8 col-md-8">
                        <div class="rbt-profile-content b2">{{ $mutualiste->nom }} </div>
                    </div>
                </div>
                <!-- End Profile Row  -->

                <!-- Start Profile Row  -->
                <div class="rbt-profile-row row row--15 mt--15">
                    <div class="col-lg-4 col-md-4">
                        <div class="rbt-profile-content b2">Prénoms</div>
                    </div>
                    <div class="col-lg-8 col-md-8">
                        <div class="rbt-profile-content b2">{{ $mutualiste->prenom }} </div>
                    </div>
                </div>
                <!-- End Profile Row  -->
                <div class="rbt-profile-row row row--15 mt--15">
                    <div class="col-lg-4 col-md-4">
                        <div class="rbt-profile-content b2">Genre</div>
                    </div>
                    <div class="col-lg-8 col-md-8">
                        <div class="rbt-profile-content b2">{{ $mutualiste->genre }} </div>
                    </div>
                </div>
                <!-- Start Profile Row  -->
                <div class="rbt-profile-row row row--15 mt--15">
                    <div class="col-lg-4 col-md-4">
                        <div class="rbt-profile-content b2">Date de Naissance</div>
                    </div>
                    <div class="col-lg-8 col-md-8">
                        <div class="rbt-profile-content b2">{{ $mutualiste->date_naissance }}</div>
                    </div>
                </div>
                <!-- End Profile Row  -->

                <!-- Start Profile Row  -->
                <div class="rbt-profile-row row row--15 mt--15">
                    <div class="col-lg-4 col-md-4">
                        <div class="rbt-profile-content b2"> Grade</div>
                    </div>
                    <div class="col-lg-8 col-md-8">
                        <div class="rbt-profile-content b2"> {{ $mutualiste->grade->libelle }}
                        </div>
                    </div>
                </div>
                <!-- End Profile Row  -->

                <!-- Start Profile Row  -->
                <div class="rbt-profile-row row row--15 mt--15">
                    <div class="col-lg-4 col-md-4">
                        <div class="rbt-profile-content b2">Email</div>
                    </div>
                    <div class="col-lg-8 col-md-8">
                        <div class="rbt-profile-content b2">{{ $mutualiste->email }}</div>
                    </div>
                </div>
                <!-- End Profile Row  -->
                <!-- Start Profile Row  -->
                <div class="rbt-profile-row row row--15 mt--15">
                    <div class="col-lg-4 col-md-4">
                        <div class="rbt-profile-content b2">Corps d'armée</div>
                    </div>
                    <div class="col-lg-8 col-md-8">
                        <div class="rbt-profile-content b2">{{ $mutualiste->corp->libelle }}</div>
                    </div>
                </div>
                <!-- End Profile Row  -->

                <!-- Start Profile Row  -->
                <div class="rbt-profile-row row row--15 mt--15">
                    <div class="col-lg-4 col-md-4">
                        <div class="rbt-profile-content b2">Numéro de téléphone</div>
                    </div>
                    <div class="col-lg-8 col-md-8">
                        <div class="rbt-profile-content b2">{{ $mutualiste->contact }} @if ($mutualiste->contact_2)
                                / {{ $mutualiste->contact_2 }}
                            @endif
                        </div>
                    </div>
                </div>
                <!-- End Profile Row  -->
                <!-- Start Profile Row  -->
                <div class="rbt-profile-row row row--15 mt--15">
                    <div class="col-lg-4 col-md-4">
                        <div class="rbt-profile-content b2">Type de pièces</div>
                    </div>
                    <div class="col-lg-8 col-md-8">
                        <div class="rbt-profile-content b2">{{ $mutualiste->typePiece->libelle }}
                            ({{ $mutualiste->typePiece->description }})</div>
                    </div>
                </div>
                <!-- End Profile Row  -->
                <!-- Start Profile Row  -->
                <div class="rbt-profile-row row row--15 mt--15">
                    <div class="col-lg-4 col-md-4">
                        <div class="rbt-profile-content b2">Numéro de pièce</div>
                    </div>
                    <div class="col-lg-8 col-md-8">
                        <div class="rbt-profile-content b2">{{ $mutualiste->numero_piece }}</div>
                    </div>
                </div>
                <!-- End Profile Row  -->
                <div class="rbt-profile-row row row--15 mt--15">
                    <div class="col-lg-4 col-md-4">
                        <div class="rbt-profile-content b2">Date d'Établissement </div>
                    </div>
                    <div class="col-lg-8 col-md-8">
                        <div class="rbt-profile-content b2">{{ $mutualiste->date_etablissement_piece }}</div>
                    </div>
                </div>
                <div class="rbt-profile-row row row--15 mt--15">
                    <div class="col-lg-4 col-md-4">
                        <div class="rbt-profile-content b2">Lieu d'établissement</div>
                    </div>
                    <div class="col-lg-8 col-md-8">
                        <div class="rbt-profile-content b2">{{ $mutualiste->lieu_etablissement_piece }}</div>
                    </div>
                </div>

                <!-- Start Profile Row  -->
                <div class="rbt-profile-row row row--15 mt--15">
                    <div class="col-lg-4 col-md-4">
                        <div class="rbt-profile-content b2">Unité</div>
                    </div>
                    <div class="col-lg-8 col-md-8">
                        <div class="rbt-profile-content b2">{{ $mutualiste->unite }}</div>
                    </div>
                </div>
                <!-- End Profile Row  -->

                <!-- Start Profile Row  -->
                <div class="rbt-profile-row row row--15 mt--15">
                    <div class="col-lg-4 col-md-4">
                        <div class="rbt-profile-content b2">Ville</div>
                    </div>
                    <div class="col-lg-8 col-md-8">
                        <div class="rbt-profile-content b2">{{ $mutualiste->ville->libelle }} </div>
                    </div>
                </div>
                <!-- End Profile Row  -->

                <!-- Start Profile Row  -->
                <div class="rbt-profile-row row row--15 mt--15">
                    <div class="col-lg-4 col-md-4">
                        <div class="rbt-profile-content b2">Adresse</div>
                    </div>
                    <div class="col-lg-8 col-md-8">
                        <div class="rbt-profile-content b2">{{ $mutualiste->adresse ?? "" }}</div>
                    </div>
                </div>
                <!-- End Profile Row  -->
                {{-- @endforeach --}}
            </div>
        </div>
        <!-- End Instructor Profile  -->

    </div>
@endsection
