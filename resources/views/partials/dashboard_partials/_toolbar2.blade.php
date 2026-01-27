<div class="page-toolbar px-xl-4 px-sm-2 px-0 py-3">
    <div class="container-fluid">
        <div class="row g-3 mb-3 align-items-center">
            <div class="col">
                <ol class="breadcrumb bg-transparent mb-0">
                    <li class="breadcrumb-item"><a class="text-secondary" href="{{ route('dashboard') }}">Accueil</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $breadcrumb }}</li>
                </ol>
            </div>
        </div> <!-- .row end -->
        <div class="row align-items-center">
            <div class="col-auto">
                <h1 class="fs-5 color-900 mt-1 mb-0">Bienvenue,
                    @if (auth()->check() && auth()->user()->administrateur)
                        {{ formatGender(auth()->user()->administrateur->genre) }}
                        {{ auth()->user()->administrateur->nom }}
                        {{ auth()->user()->administrateur->prenom }}
                    @else
                        !
                    @endif
                </h1>

                {{-- <small class="text-muted">Vous avez 12 nouveaux messages et 7 nouvelles
                    notifications.</small> --}}
            </div>
            <div class="col d-flex justify-content-lg-end mt-2 mt-md-0">
                {{-- <div class="p-2 me-md-3">
                    <div><span class="h6 mb-0">8.18K</span> <small class="text-secondary"><i class="fa fa-angle-up"></i>
                            1.3%</small></div>
                    <small class="text-muted text-uppercase">Income</small>
                </div>
                <div class="p-2 me-md-3">
                    <div><span class="h6 mb-0">1.11K</span> <small class="text-secondary"><i class="fa fa-angle-up"></i>
                            4.1%</small></div>
                    <small class="text-muted text-uppercase">Expense</small>
                </div>
                <div class="p-2 pe-lg-0">
                    <div><span class="h6 mb-0">3.66K</span> <small class="text-danger"><i class="fa fa-angle-down"></i>
                            7.5%</small></div>
                    <small class="text-muted text-uppercase">Revenue</small>
                </div> --}}
            </div>
        </div> <!-- .row end -->
    </div>
</div>
