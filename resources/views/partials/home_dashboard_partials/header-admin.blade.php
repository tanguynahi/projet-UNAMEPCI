     <!-- Start Dashboard Top  -->
     @php
         use App\Models\Compte;
         $mutualiste = auth()->user()->mutualiste;
         if ($mutualiste) {
             $compte = Compte::where([['type_compte_id', 2], ['mutualiste_id', $mutualiste->id]])->first();
         }
     @endphp
     <div class="rbt-dashboard-content-wrapper ">
         <div class="tutor-bg-photo height-350"  style="border-radius: 48px;">
             @if (auth()->user()->mutualiste->photo_couverture)
                 <img src="{{ asset(auth()->user()->mutualiste->photo_couverture) }}"
                     style="height: 100%; width:100%; background-size:cover;" class="radius-10" alt="photo de couverture">
             @else
                 <img src="{{ asset('assets/home/images/banner/plane.jpg') }}" class="radius-10"
                     style=" height: 100%; width:100%; background-size:cover; background-repeat: no-repeat; background-position:center;"
                     alt="photo de couverture">
             @endif

         </div>
         <!-- Start Tutor Information  -->
         <div class="rbt-tutor-information">
             {{-- @foreach ($mutualistes as $mutualiste) --}}
             <div class="rbt-tutor-information-left">
                 <div class="thumbnail rbt-avatars size-lg">
                     @if (auth()->user()->hasRole('mutualiste'))
                         @if (auth()->user()->mutualiste->lien_photo)
                             <img src="{{ asset(auth()->user()->mutualiste->lien_photo) }}"
                                 style="height: 140px; width:150px;" alt="photo de profil">
                         @else
                             <img src="{{ asset('assets/icons/user.png') }}" alt="photo de profil">
                         @endif
                     @endif
                 </div>
                 <div class="tutor-content">
                     @if (auth()->user()->hasRole('mutualiste'))
                         <h5 class="title">{{ auth()->user()->mutualiste->nom }}
                             {{ auth()->user()->mutualiste->prenom }}</h5>
                         <div class="rbt-review">
                             <span class="rating-count"> {{ auth()->user()->email }}</span>
                         </div>
                     @endif
                 </div>

             </div>
             <div class="container ">
                 <div class="row">
                     <div class="col-7">
                     </div>
                     <div class="col">
                     </div>
                     <div class="col-4 mb--20 col-lg-4 col-md-4 col-sm-4 col-12 text-center radius-10"
                         style="color: black;  background-color:#E1F2FD; ">
                         <fieldset>
                             <legend class="text-center">Solde Mutualpay</legend>
                             <b class="text-center" style="position: center">{{ formatMontant($compte->solde) }}</b>
                         </fieldset>

                     </div>
                 </div>
             </div>
         </div>
     </div>
     <!-- End Dashboard Top  -->
