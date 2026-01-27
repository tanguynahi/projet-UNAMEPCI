<header class="page-header sticky-top px-xl-4 px-sm-2 px-0 py-lg-2 py-1">
    <div class="container-fluid">
        <nav class="navbar">
            <!-- start: toggle btn -->
            <div class="d-flex">
                <button type="button" class="btn btn-link d-none d-xl-block sidebar-mini-btn p-0 text-primary">
                    <span class="hamburger-icon">
                        <span class="line"></span>
                        <span class="line"></span>
                        <span class="line"></span>
                    </span>
                </button>
                <button type="button" class="btn btn-link d-block d-xl-none menu-toggle p-0 text-primary">
                    <span class="hamburger-icon">
                        <span class="line"></span>
                        <span class="line"></span>
                        <span class="line"></span>
                    </span>
                </button>
                <a href="{{ route('dashboard') }}"
                    class="brand-icon d-flex align-items-center mx-2 mx-sm-3 text-primary">
                    MON TABLEAU DE BORD
                </a>
            </div>
            <!-- start: link -->
            <ul class="header-right justify-content-end d-flex align-items-center mb-0">
                <!-- start: notifications dropdown-menu -->
                <li>
                    <div class="dropdown morphing scale-left notifications">
                        <a class="nav-link dropdown-toggle after-none" href="#" role="button"
                            data-bs-toggle="dropdown">
                            <span class="d-none d-xl-block me-2">Notifications</span>
                            <svg class="d-inline-block d-xl-none" viewBox="0 0 16 16" width="18px" fill="currentColor"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M8 1.91802L7.203 2.07902C6.29896 2.26322 5.48633 2.75412 4.90265 3.46864C4.31897 4.18316 4.0001 5.07741 4 6.00002C4 6.62802 3.866 8.19702 3.541 9.74202C3.381 10.509 3.165 11.308 2.878 12H13.122C12.835 11.308 12.62 10.51 12.459 9.74202C12.134 8.19702 12 6.62802 12 6.00002C11.9997 5.07758 11.6807 4.18357 11.097 3.46926C10.5134 2.75494 9.70087 2.26419 8.797 2.08002L8 1.91802ZM14.22 12C14.443 12.447 14.701 12.801 15 13H1C1.299 12.801 1.557 12.447 1.78 12C2.68 10.2 3 6.88002 3 6.00002C3 3.58002 4.72 1.56002 7.005 1.09902C6.99104 0.959974 7.00638 0.819547 7.05003 0.686794C7.09368 0.554041 7.16467 0.43191 7.25842 0.328279C7.35217 0.224647 7.4666 0.141815 7.59433 0.085125C7.72206 0.028435 7.86026 -0.000854492 8 -0.000854492C8.13974 -0.000854492 8.27794 0.028435 8.40567 0.085125C8.5334 0.141815 8.64783 0.224647 8.74158 0.328279C8.83533 0.43191 8.90632 0.554041 8.94997 0.686794C8.99362 0.819547 9.00896 0.959974 8.995 1.09902C10.1253 1.32892 11.1414 1.94238 11.8712 2.83552C12.6011 3.72866 12.9999 4.84659 13 6.00002C13 6.88002 13.32 10.2 14.22 12Z" />
                                <path class="fill-secondary"
                                    d="M9.41421 15.4142C9.03914 15.7893 8.53043 16 8 16C7.46957 16 6.96086 15.7893 6.58579 15.4142C6.21071 15.0391 6 14.5304 6 14H10C10 14.5304 9.78929 15.0391 9.41421 15.4142Z"
                                    fill="black" />
                            </svg>
                        </a>
                        <div id="NotificationsDiv" class="dropdown-menu shadow rounded-4 border-0 p-0 m-0">
                            <div class="card w380">
                                <div class="card-header p-3">
                                    <h6 class="card-title mb-0">Liste des notifications</h6>
                                    <span class="badge bg-danger text-light">14</span>
                                </div>
                                <ul class="nav nav-tabs tab-card d-flex text-center" role="tablist">
                                    <li class="nav-item flex-fill"><a class="nav-link active" data-bs-toggle="tab"
                                            href="#Noti-tab-Message" role="tab">Non lues</a></li>
                                    <li class="nav-item flex-fill"><a class="nav-link" data-bs-toggle="tab"
                                            href="#Noti-tab-Events" role="tab">Lues</a></li>
                                </ul>
                                <div class="tab-content card-body custom_scroll">
                                    <div class="tab-pane fade show active" id="Noti-tab-Message" role="tabpanel">
                                        <ul class="list-unstyled list mb-0">
                                            <li class="py-2 mb-1 border-bottom">
                                                <a href="javascript:void(0);" class="d-flex">
                                                    <img class="avatar rounded-circle"
                                                        src="{{ asset('assets/dashboard/img/xs/avatar5.jpg') }}"
                                                        alt="Image notification">
                                                    <div class="flex-fill ms-3">
                                                        <p class="d-flex justify-content-between mb-0">
                                                            <span>Olive Tree</span> <small>13MIN</small>
                                                        </p>
                                                        <span>making it over 2000 years old</span>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="py-2 mb-1 border-bottom">
                                                <a href="javascript:void(0);" class="d-flex">
                                                    <img class="avatar rounded-circle"
                                                        src="{{ asset('assets/dashboard/img/xs/avatar6.jpg') }}"
                                                        alt="Image notification">
                                                    <div class="flex-fill ms-3">
                                                        <p class="d-flex justify-content-between mb-0">
                                                            <span>Del Phineum</span> <small>1HR</small>
                                                        </p>
                                                        <span>There are many variations of passages</span>
                                                    </div>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="tab-pane fade" id="Noti-tab-Events" role="tabpanel">
                                        <ul class="list-unstyled list mb-0">
                                            <li class="py-2 mb-1 border-bottom">
                                                <a href="javascript:void(0);" class="d-flex">
                                                    <div class="avatar rounded-circle no-thumbnail"><i
                                                            class="fa fa-thumbs-up fa-lg"></i></div>
                                                    <div class="flex-fill ms-3">
                                                        <p class="mb-0">Your New Campaign <strong
                                                                class="text-primary">Holiday Sale</strong> is
                                                            approved.</p>
                                                        <small>11:30 AM Today</small>
                                                    </div>
                                                </a>
                                            </li>
                                            <li class="py-2 mb-1 border-bottom">
                                                <a href="javascript:void(0);" class="d-flex">
                                                    <div class="avatar rounded-circle no-thumbnail"><i
                                                            class="fa fa-pie-chart fa-lg"></i></div>
                                                    <div class="flex-fill ms-3">
                                                        <p class="mb-0">Website visits from Twitter is
                                                            <strong class="text-danger">27% higher</strong>
                                                            than last week.
                                                        </p>
                                                        <small>04:00 PM Today</small>
                                                    </div>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <a href="#" class="btn btn-primary text-light rounded-0">Voir
                                    toutes les notifications</a>
                            </div>
                        </div>
                    </div>
                </li>
                <!-- start: quick light dark -->
                <li class="d-none d-xl-inline-block">
                    <a class="nav-link fullscreen" href="javascript:void(0);"
                        onclick="toggleFullScreen(documentElement)">
                        <svg viewBox="0 0 16 16" width="18px" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M5.8279 10.172C5.73414 10.0783 5.60698 10.0256 5.4744 10.0256C5.34182 10.0256 5.21467 10.0783 5.1209 10.172L1.0249 14.268V11.5C1.0249 11.3674 0.972224 11.2402 0.878456 11.1464C0.784688 11.0527 0.657511 11 0.524902 11C0.392294 11 0.265117 11.0527 0.171349 11.1464C0.0775808 11.2402 0.0249023 11.3674 0.0249023 11.5V15.475C0.0249023 15.6076 0.0775808 15.7348 0.171349 15.8285C0.265117 15.9223 0.392294 15.975 0.524902 15.975H4.4999C4.63251 15.975 4.75969 15.9223 4.85346 15.8285C4.94722 15.7348 4.9999 15.6076 4.9999 15.475C4.9999 15.3424 4.94722 15.2152 4.85346 15.1214C4.75969 15.0277 4.63251 14.975 4.4999 14.975H1.7319L5.8279 10.879C5.92164 10.7852 5.9743 10.6581 5.9743 10.5255C5.9743 10.3929 5.92164 10.2658 5.8279 10.172ZM10.1719 10.172C10.2657 10.0783 10.3928 10.0256 10.5254 10.0256C10.658 10.0256 10.7851 10.0783 10.8789 10.172L14.9749 14.268V11.5C14.9749 11.3674 15.0276 11.2402 15.1213 11.1464C15.2151 11.0527 15.3423 11 15.4749 11C15.6075 11 15.7347 11.0527 15.8285 11.1464C15.9222 11.2402 15.9749 11.3674 15.9749 11.5V15.475C15.9749 15.6076 15.9222 15.7348 15.8285 15.8285C15.7347 15.9223 15.6075 15.975 15.4749 15.975H11.4999C11.3673 15.975 11.2401 15.9223 11.1463 15.8285C11.0526 15.7348 10.9999 15.6076 10.9999 15.475C10.9999 15.3424 11.0526 15.2152 11.1463 15.1214C11.2401 15.0277 11.3673 14.975 11.4999 14.975H14.2679L10.1719 10.879C10.0782 10.7852 10.0255 10.6581 10.0255 10.5255C10.0255 10.3929 10.0782 10.2658 10.1719 10.172ZM5.8279 5.82799C5.73414 5.92173 5.60698 5.97439 5.4744 5.97439C5.34182 5.97439 5.21467 5.92173 5.1209 5.82799L1.0249 1.73199V4.49999C1.0249 4.6326 0.972224 4.75978 0.878456 4.85355C0.784688 4.94732 0.657511 4.99999 0.524902 4.99999C0.392294 4.99999 0.265117 4.94732 0.171349 4.85355C0.0775808 4.75978 0.0249023 4.6326 0.0249023 4.49999V0.524994C0.0249023 0.392386 0.0775808 0.265209 0.171349 0.17144C0.265117 0.0776723 0.392294 0.0249939 0.524902 0.0249939H4.4999C4.63251 0.0249939 4.75969 0.0776723 4.85346 0.17144C4.94722 0.265209 4.9999 0.392386 4.9999 0.524994C4.9999 0.657602 4.94722 0.784779 4.85346 0.878547C4.75969 0.972315 4.63251 1.02499 4.4999 1.02499H1.7319L5.8279 5.12099C5.92164 5.21476 5.9743 5.34191 5.9743 5.47449C5.9743 5.60708 5.92164 5.73423 5.8279 5.82799Z" />
                            <path class="fill-secondary"
                                d="M10.5253 5.97439C10.3927 5.97439 10.2655 5.92173 10.1718 5.82799C10.078 5.73423 10.0254 5.60708 10.0254 5.47449C10.0254 5.34191 10.078 5.21476 10.1718 5.12099L14.2678 1.02499H11.4998C11.3672 1.02499 11.24 0.972315 11.1462 0.878547C11.0525 0.784779 10.9998 0.657602 10.9998 0.524994C10.9998 0.392386 11.0525 0.265209 11.1462 0.17144C11.24 0.0776723 11.3672 0.0249939 11.4998 0.0249939H15.4748C15.6074 0.0249939 15.7346 0.0776723 15.8283 0.17144C15.9221 0.265209 15.9748 0.392386 15.9748 0.524994V4.49999C15.9748 4.6326 15.9221 4.75978 15.8283 4.85355C15.7346 4.94732 15.6074 4.99999 15.4748 4.99999C15.3422 4.99999 15.215 4.94732 15.1212 4.85355C15.0275 4.75978 14.9748 4.6326 14.9748 4.49999V1.73199L10.8788 5.82799C10.785 5.92173 10.6579 5.97439 10.5253 5.97439Z" />
                        </svg>
                    </a>
                </li>
                <!-- start: Language dropdown-menu -->
                <li class="d-none d-xl-inline-block">
                    <div class="dropdown morphing scale-left Language">
                        <a class="nav-link dropdown-toggle after-none" href="#" role="button"
                            data-bs-toggle="dropdown">
                            <svg viewBox="0 0 16 16" width="18px" fill="currentColor"
                                xmlns="http://www.w3.org/2000/svg">
                                <path class="fill-secondary"
                                    d="M4.545 6.714 4.11 8H3l1.862-5h1.284L8 8H6.833l-.435-1.286H4.545zm1.634-.736L5.5 3.956h-.049l-.679 2.022H6.18z" />
                                <path
                                    d="M0 2a2 2 0 0 1 2-2h7a2 2 0 0 1 2 2v3h3a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-3H2a2 2 0 0 1-2-2V2zm2-1a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h7a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H2zm7.138 9.995c.193.301.402.583.63.846-.748.575-1.673 1.001-2.768 1.292.178.217.451.635.555.867 1.125-.359 2.08-.844 2.886-1.494.777.665 1.739 1.165 2.93 1.472.133-.254.414-.673.629-.89-1.125-.253-2.057-.694-2.82-1.284.681-.747 1.222-1.651 1.621-2.757H14V8h-3v1.047h.765c-.318.844-.74 1.546-1.272 2.13a6.066 6.066 0 0 1-.415-.492 1.988 1.988 0 0 1-.94.31z" />
                            </svg>
                        </a>
                        <div class="dropdown-menu rounded-4 shadow border-0 p-0" data-bs-popper="none">
                            <div class="card">
                                <div class="list-group list-group-custom" style="width: 200px;">
                                    <a href="#" class="list-group-item"><span
                                            class="flag-icon flag-icon-fr me-2"></span>FR</a>
                                    <!-- <a href="#" class="list-group-item"><span class="flag-icon flag-icon-us me-2"></span>ENG</a> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <!-- start: quick light dark -->
                <li>
                    <a class="nav-link quick-light-dark" href="#">
                        <svg viewBox="0 0 16 16" width="18px" fill="currentColor"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278zM4.858 1.311A7.269 7.269 0 0 0 1.025 7.71c0 4.02 3.279 7.276 7.319 7.276a7.316 7.316 0 0 0 5.205-2.162c-.337.042-.68.063-1.029.063-4.61 0-8.343-3.714-8.343-8.29 0-1.167.242-2.278.681-3.286z" />
                            <path class="fill-secondary"
                                d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z" />
                        </svg>
                    </a>
                </li>
                <!-- start: User dropdown-menu -->
                <li>
                    <div class="dropdown morphing scale-left user-profile mx-lg-3 mx-2">
                        <a class="nav-link dropdown-toggle rounded-circle after-none p-0" href="#"
                            role="button" data-bs-toggle="dropdown">
                            @if (!empty(auth()->user()->administrateur->lien_photo))
                                <img class="avatar img-thumbnail rounded-circle shadow"
                                    src="{{ asset(auth()->user()->administrateur->lien_photo) }}"
                                    alt="Image de profile">
                            @else
                                <img class="avatar img-thumbnail rounded-circle shadow"
                                    src="{{ asset('assets/icons/user2.png') }}" alt="Image de profile">
                            @endif
                        </a>
                        <div class="dropdown-menu border-0 rounded-4 shadow p-0">
                            <div class="card border-0 w240">
                                <div class="card-body border-bottom d-flex">
                                    @if (!empty(auth()->user()->administrateur->lien_photo))
                                        <img class="avatar rounded-circle"
                                            src="{{ asset(auth()->user()->administrateur->lien_photo) }}"
                                            alt="Image de profile">
                                    @else
                                        <img class="avatar rounded-circle" src="{{ asset('assets/icons/user2.png') }}"
                                            alt="Image de profile">
                                    @endif
                                    <div class="flex-fill ms-3">
                                        @if (auth()->user()->administrateur)
                                            <h6 class="card-title mb-0">{{ auth()->user()->administrateur->nom }}
                                                {{ auth()->user()->administrateur->prenom }}</h6>
                                            <span class="text-muted">{{ auth()->user()->administrateur->email }}</span>
                                        @else
                                            <h6 class="card-title mb-0">Administrateur</h6>
                                            <span class="text-muted"></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="list-group m-2 mb-3">
                                    <a class="list-group-item list-group-item-action border-0" href="#"><i
                                            class="w30 fa fa-user"></i>Mon profil</a>
                                    <a class="list-group-item list-group-item-action border-0" href="#"><i
                                            class="w30 fa fa-gear"></i>Paramètres</a>
                                </div>
                                <a class="btn bg-secondary text-light text-uppercase rounded-0" href="#"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Déconnexion</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </nav>
    </div>
</header>
