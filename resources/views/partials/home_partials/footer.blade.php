<footer class="rbt-footer footer-style-1 bg-color-white overflow-hidden" style="position: bottom;">
    <div class="footer-top" style="background-color:#0F42AF; color:white">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-8 col-md-8 col-sm-8 col-12" style="position: bottom;">
                    <div class="footer-widget">
                        <div class="logo">
                            <a href="{{ route('accueil') }}">
                                {{-- <img src="{{ asset('assets/home/images/logo/mutualplay.jpg') }}" alt="logo mutualplay"> --}}
                                <h3 style="color: white;">
                                    {{ $parametre->nom_site_web }}
                                </h3>
                            </a>
                        </div>

                        <p class="description mt--20">
                            suivez-nous
                        </p>
                        <ul class="social-icon social-default justify-content-start">
                            <li>
                                <a href="{{ $parametre->lien_facebook }}">
                                    <i class="feather-facebook"></i>
                                </a>
                            </li>
                            <li>
                                <a href="{{ $parametre->lien_twitter }}">
                                    <i class="feather-twitter"></i>
                                </a>
                            </li>
                            <li>
                                <a href="{{ $parametre->lien_instagram }}">
                                    <i class="feather-instagram"></i>
                                </a>
                            </li>
                            <li>
                                <a href="{{ $parametre->lien_linkedin }}">
                                    <i class="feather-linkedin"></i>
                                </a>
                            </li>
                            <li>
                                <a href="{{ $parametre->lien_youtube }}">
                                    <i class="feather-youtube"></i>
                                </a>
                            <li>
                                <a href="{{ $parametre->lien_whatsapp }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16">
                                        <path
                                            d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232" />
                                    </svg>
                                </a>
                            </li>
                        </ul>
                        <div class="contact-btn mt--30">
                            <a class="rbt-btn hover-icon-reverse btn-border-gradient radius-round"
                                href="{{ route('contact') }}">
                                <div class="icon-reverse-wrapper">
                                    <span class="btn-text">Contacter nous</span>
                                    <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                    <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-12">
                    <div class="footer-widget">
                        <p class="description">Besoin d'information fiables et pratiques qui vous concernent?<br>
                            Abonnez-vous à la lettre d'information de votre mutuelle</p>
                        <div class="form-group mb--0">
                            <button class="rbt-btn rbt-switch-btn btn-gradient radius-round btn-sm" type="submit">
                                <span data-text="S'abonner a la Newsletter">S'abonner a la Newsletter</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright-area copyright-style-1  " style="background-color:#0F42AF; color:white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-12 col-12">
                    <p class="rbt-link-hover text-center text-lg-start" style="color: white">&copy;{{ date('Y') }}
                        <a href="#" style="color: white">{{ $parametre->nom_site_web }}.</a> tous droits
                        réserves.
                    </p>
                </div>
                <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-12 col-12">
                    <ul
                        class="copyright-link rbt-link-hover justify-content-center justify-content-lg-end mt_sm--10 mt_md--10">
                        <li><a href="#" style="color: white">Conditions d'utilisation</a></li>
                        <li><a href="#" style="color: white">Politique de confidentialité</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
<div class="rbt-progress-parent">
    <svg class="rbt-back-circle svg-inner" width="100%" height="100%" viewBox="-1 -1 102 102">
        <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
    </svg>
</div>

@stack('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const showMoreButton = document.getElementById('show-more-button');
        const cards = document.querySelectorAll('#image-container .col-lg-4');

        // Hide all cards except the first three
        cards.forEach((card, index) => {
            if (index >= 3) {
                card.style.display = 'none';
            }
        });

        // Show more cards when button is clicked
        showMoreButton.addEventListener('click', function() {
            cards.forEach(card => {
                card.style.display = 'block';
            });
            showMoreButton.style.display = 'none';
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- JS
============================================ -->

<script src="{{ asset('assets/home/js/vendor/odometer.js') }}"></script>
<!-- Modernizer JS -->
<script src="{{ asset('assets/home/js/vendor/modernizr.min.js') }}"></script>
<!-- jQuery JS -->
<script src="{{ asset('assets/home/js/vendor/jquery.js') }}"></script>
<!-- Bootstrap JS -->
<script src="{{ asset('assets/home/js/vendor/bootstrap.min.js') }}"></script>
<!-- sal.js -->
<script src="{{ asset('assets/home/js/vendor/sal.js') }}"></script>
<script src="{{ asset('assets/home/js/vendor/swiper.js') }}"></script>
<script src="{{ asset('assets/home/js/vendor/magnify.min.js') }}"></script>
<script src="{{ asset('assets/home/js/vendor/jquery-appear.js') }}"></script>
<script src="{{ asset('assets/home/js/vendor/backtotop.js') }}"></script>
<script src="{{ asset('assets/home/js/vendor/isotop.js') }}"></script>
<script src="{{ asset('assets/home/js/vendor/imageloaded.js') }}"></script>

<script src="{{ asset('assets/home/js/vendor/wow.js') }}"></script>
<script src="{{ asset('assets/home/js/vendor/waypoint.min.js') }}"></script>
<script src="{{ asset('assets/home/js/vendor/easypie.js') }}"></script>
<script src="{{ asset('assets/home/js/vendor/text-type.js') }}"></script>
<script src="{{ asset('assets/home/js/vendor/jquery-one-page-nav.js') }}"></script>
<script src="{{ asset('assets/home/js/vendor/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('assets/home/js/vendor/jquery-ui.js') }}"></script>
<script src="{{ asset('assets/home/js/vendor/magnify-popup.min.js') }}"></script>
<script src="{{ asset('assets/home/js/vendor/paralax-scroll.js') }}"></script>
<script src="{{ asset('assets/home/js/vendor/paralax.min.js') }}"></script>
<script src="{{ asset('assets/home/js/vendor/countdown.js') }}"></script>
<script src="{{ asset('assets/home/js/vendor/plyr.js') }}"></script>
<!-- Main JS -->
<script src="{{ asset('assets/home/js/main.js') }}"></script>


<script src="{{ asset('mutualiste/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('mutualiste/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('mutualiste/plugins/datatables/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('mutualiste/plugins/datatables/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('mutualiste/plugins/datatables/jszip.min.js') }}"></script>
<script src="{{ asset('mutualiste/plugins/datatables/pdfmake.min.js') }}"></script>
<script src="{{ asset('mutualiste/plugins/datatables/vfs_fonts.js') }}"></script>
<script src="{{ asset('mutualiste/plugins/datatables/buttons.html5.min.js') }}"></script>
<script src="{{ asset('mutualiste/plugins/datatables/buttons.print.min.js') }}"></script>
<script src="{{ asset('mutualiste/plugins/datatables/buttons.colVis.min.js') }}"></script>

<script src="{{ asset('mutualiste/plugins/datatables/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('mutualiste/plugins/datatables/responsive.bootstrap4.min.js') }}"></script>

<script src="{{ asset('mutualiste/plugins/switchery/switchery.min.js') }}"></script>
<!-- Apex js -->
<script src="{{ asset('mutualiste/plugins/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('mutualiste/plugins/apexcharts/irregular-data-series.js') }}"></script>
<!-- Slick js -->
<script src="{{ asset('mutualiste/plugins/slick/slick.min.js') }}"></script>
<script src="{{ asset('mutualiste/js/custom/custom-table-datatable.js') }}"></script>
</body>

</html>
