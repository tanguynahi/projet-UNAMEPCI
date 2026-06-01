@extends('layouts.dashboard', ['title' => 'FPM MUTUALPAY - TABLEAU DE BORD', 'breadcrumb' => 'FPM MUTUALPAY', 'toolbar' => '_toolbar'])
@push('css')
@endpush
@section('content')
    <div class="row row-cols-xxl-5 row-cols-xxl-4 row-cols-lg-4 row-cols-md-3 row-cols-sm-2 row-cols-1 g-3 mb-3 row-deck">
        @can('voir-montant-total-dashboard')
            <div class="col">
                <div class="card">
                    <div class="card-body d-flex align-items-center">
                        <div class="avatar rounded-circle no-thumbnail bg-light"><i class="fa fa-money fa-lg"></i></div>
                        <div class="flex-fill ms-3 text-truncate">
                            <div class="small text-uppercase">Montant Global</div>
                            <div><span class="h6 mb-0 fw-bold">{{ $montantTotal ?? 0 }}</span> <small
                                    class="text-success">Fcfa</small></div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan

        @can('voir-total-adhesion-dashboard')
            <div class="col">
                <div class="card">
                    <div class="card-body d-flex align-items-center">
                        <div class="avatar rounded-circle no-thumbnail bg-light"><i class="fa fa-briefcase fa-lg"></i></div>
                        <div class="flex-fill ms-3 text-truncate">
                            <div class="small text-uppercase">Total Adhesion</div>
                            <div><span class="h6 mb-0 fw-bold">{{ $mntAdhesion ?? 0 }}</span> <small
                                    class="text-success">Fcfa</small></div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
            @can('voir-total-cotisation-dashboard')
            <div class="col">
                <div class="card">
                    <div class="card-body d-flex align-items-center">
                        <div class="avatar rounded-circle no-thumbnail bg-light"><i class="fa fa-money fa-lg"></i></div>
                        <div class="flex-fill ms-3 text-truncate">
                            <div class="small text-uppercase">Total Cotisations</div>
                            <div><span class="h6 mb-0 fw-bold">{{ $mntCotisation ?? 0 }}</span> <small
                                    class="text-danger">Fcfa</small></div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
        {{-- <div class="col">
        <div class="card">
            <div class="card-body d-flex align-items-center">
                <div class="avatar rounded-circle no-thumbnail bg-light"><i class="fa fa-briefcase fa-lg"></i></div>
                <div class="flex-fill ms-3 text-truncate">
                        <div class="small text-uppercase">Total Carte Membre</div>
                    <div><span class="h6 mb-0 fw-bold">{{ $mntCarte ?? 0 }}</span> <small class="text-success">Fcfa</small></div>
                </div>
            </div>
        </div>
    </div> --}}
        @can('voir-total-projet-dashboard')
            <div class="col">
                <div class="card">
                    <div class="card-body d-flex align-items-center">
                        <div class="avatar rounded-circle no-thumbnail bg-light"><i class="fa fa-money fa-lg"></i></div>
                        <div class="flex-fill ms-3 text-truncate">
                            <div class="small text-uppercase">Total Projets</div>
                            <div><span class="h6 mb-0 fw-bold">{{ $mntProjet ?? 0 }}</span> <small
                                    class="text-danger">Fcfa</small></div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan

        @can('voir-total-pret-dashboard')
            <div class="col">
                <div class="card">
                    <div class="card-body d-flex align-items-center">
                        <div class="avatar rounded-circle no-thumbnail bg-light"><i class="fa fa-money fa-lg"></i></div>
                        <div class="flex-fill ms-3 text-truncate">
                            <div class="small text-uppercase">Total Prêts</div>
                            <div><span class="h6 mb-0 fw-bold">{{ $mntPret ?? 0 }}</span> <small
                                    class="text-danger">Fcfa</small></div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
        @can('voir-total-mutualiste-dashboard')
            <div class="col">
                <div class="card">
                    <div class="card-body d-flex align-items-center">
                        <div class="avatar rounded-circle no-thumbnail bg-light"><i class="fa fa-users fa-lg"></i></div>
                        <div class="flex-fill ms-3 text-truncate">
                            <div class="small text-uppercase">Membres</div>
                            <div><span class="h6 mb-0 fw-bold">{{ $nombrMutualiste ?? 0 }}</span></div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan

        @can('voir-total-administrateur-dashboard')
            <div class="col">
                <div class="card">
                    <div class="card-body d-flex align-items-center">
                        <div class="avatar rounded-circle no-thumbnail bg-light"><i class="fa fa-user-secret fa-lg"></i></div>
                        <div class="flex-fill ms-3 text-truncate">
                            <div class="small text-uppercase">Administrateurs</div>
                            <div><span class="h6 mb-0 fw-bold">{{ $nombrAdmin ?? 0 }}</span></div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
        @can('voir-total-adhesion-attente-dashboard')
            <div class="col">
                <div class="card">
                    <div class="card-body d-flex align-items-center">
                        <div class="avatar rounded-circle no-thumbnail bg-light"><i class="fa fa-users fa-lg"></i></div>
                        <div class="flex-fill ms-3 text-truncate">
                            <div class="small text-uppercase">Adhesion Attente</div>
                            <div><span class="h6 mb-0 fw-bold">{{ $inscrires ?? 0 }}</span></div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
    </div> <!-- .row end -->

    {{-- <div class="row g-3 mb-5 row-deck">
    <div class="col-xl-6 col-lg-12 col-md-12">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title m-0">Sales Statistics</h6>
                <div class="dropdown morphing scale-left">
                    <a href="#" class="card-fullscreen" data-bs-toggle="tooltip" title="Card Full-Screen"><i
                            class="icon-size-fullscreen"></i></a>
                    <a href="#" class="more-icon dropdown-toggle" data-bs-toggle="dropdown"
                        aria-expanded="false"><i class="fa fa-ellipsis-h"></i></a>
                    <ul class="dropdown-menu shadow border-0 p-2">
                        <li><a class="dropdown-item" href="#">File Info</a></li>
                        <li><a class="dropdown-item" href="#">Copy to</a></li>
                        <li><a class="dropdown-item" href="#">Move to</a></li>
                        <li><a class="dropdown-item" href="#">Rename</a></li>
                        <li><a class="dropdown-item" href="#">Block</a></li>
                        <li><a class="dropdown-item" href="#">Delete</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="card p-3">
                            <div class="fw-bold"><span class="h4 mb-0">11.54k USD</span></div>
                            <div class="text-muted small">Revenue</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card p-3">
                            <div class="fw-bold"><span class="h4 mb-0">5.87k USD</span></div>
                            <div class="text-muted small">Cost</div>
                        </div>
                    </div>
                </div>
                <div id="apex-SalesStatistics" class="ac-line-transparent"></div>
            </div>
        </div> <!-- .card end -->
    </div>
    <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title m-0">Top Selling Plans</h6>
                <div class="dropdown morphing scale-left">
                    <a href="#" class="card-fullscreen" data-bs-toggle="tooltip" title="Card Full-Screen"><i
                            class="icon-size-fullscreen"></i></a>
                    <a href="#" class="more-icon dropdown-toggle" data-bs-toggle="dropdown"
                        aria-expanded="false"><i class="fa fa-ellipsis-h"></i></a>
                    <ul class="dropdown-menu shadow border-0 p-2">
                        <li><a class="dropdown-item" href="#">File Info</a></li>
                        <li><a class="dropdown-item" href="#">Copy to</a></li>
                        <li><a class="dropdown-item" href="#">Move to</a></li>
                        <li><a class="dropdown-item" href="#">Rename</a></li>
                        <li><a class="dropdown-item" href="#">Block</a></li>
                        <li><a class="dropdown-item" href="#">Delete</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body d-flex justify-content-center">
                <div id="apex-TopSellingPlans"></div>
            </div>
        </div> <!-- .card end -->
    </div>
    <div class="col-xxl-5 col-xl-12 col-lg-12 col-md-12">
        <div class="card">
            <div class="card-header">
                <h6 class="card-title m-0">Paiements récents</h6>
                <div class="dropdown morphing scale-left">
                    <a href="#" class="card-fullscreen" data-bs-toggle="tooltip" title="Card Full-Screen"><i
                            class="icon-size-fullscreen"></i></a>
                    <a href="#" class="more-icon dropdown-toggle" data-bs-toggle="dropdown"
                        aria-expanded="false"><i class="fa fa-ellipsis-h"></i></a>
                    <ul class="dropdown-menu shadow border-0 p-2">
                        <li><a class="dropdown-item" href="#">File Info</a></li>
                        <li><a class="dropdown-item" href="#">Copy to</a></li>
                        <li><a class="dropdown-item" href="#">Move to</a></li>
                        <li><a class="dropdown-item" href="#">Rename</a></li>
                        <li><a class="dropdown-item" href="#">Block</a></li>
                        <li><a class="dropdown-item" href="#">Delete</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-sm table-bordered align-middle mb-0">
                        <tbody>
                            <tr>
                                <td>Design task for App</td>
                                <td>22 May 2021</td>
                            </tr>
                            <tr>
                                <td>Angular login page</td>
                                <td>22 May 2021</td>
                            </tr>
                            <tr>
                                <td>React Video tools</td>
                                <td>11 May 2021</td>
                            </tr>
                            <tr>
                                <td>Figma Design</td>
                                <td>9 June 2021</td>
                            </tr>
                            <tr>
                                <td>Logo vector design</td>
                                <td>13 June 2021</td>
                            </tr>
                            <tr>
                                <td>iOs and Android App</td>
                                <td>18 June 2021</td>
                            </tr>
                            <tr>
                                <td>Login page figma design</td>
                                <td>25 June 2021</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div> <!-- .card end -->
    </div>
</div> <!-- .row end --> --}}
@endsection

@push('js')
    <!-- Plugin Js -->
    <script src="{{ asset('assets/dashboard/js/bundle/apexcharts.bundle.js') }}"></script>
    <script>
        $(function() {
            // Sales Statistics
            var options = {
                series: [{
                    name: 'Revenue',
                    data: [13, 23, 20, 8, 13, 27, 33, 12, 67, 22, 43, 21, 49, 13, 23]
                }, {
                    name: 'Cost',
                    data: [44, 55, 41, 67, 22, 43, 21, 49, 13, 23, 20, 8, 13, 27, 33]
                }],
                chart: {
                    type: 'bar',
                    height: 240,
                    stacked: true,
                    //stackType: '100%',
                    toolbar: {
                        show: false,
                    },
                },
                colors: ['var(--chart-color1)', 'var(--chart-color5)'],
                responsive: [{
                    breakpoint: 480,
                    options: {
                        legend: {
                            position: 'bottom',
                            offsetX: -10,
                            offsetY: 0
                        }
                    }
                }],
                xaxis: {
                    categories: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14',
                        '15',
                    ],
                },
                fill: {
                    opacity: 1
                },
                dataLabels: {
                    enabled: false,
                },
                legend: {
                    position: 'bottom',
                },
                tooltip: {
                    y: [{
                        title: {
                            formatter: function(val) {
                                return val + " (K)"
                            }
                        }
                    }, {
                        title: {
                            formatter: function(val) {
                                return val + " (K)"
                            }
                        }
                    }]
                },
            };
            var chart = new ApexCharts(document.querySelector("#apex-SalesStatistics"), options);
            chart.render();
            // Top Selling Plans
            var options = {
                series: [32, 56, 12, 25],
                chart: {
                    type: 'donut',
                    width: 340,
                    toolbar: {
                        show: false,
                    },
                },
                responsive: [{
                    breakpoint: 1200,
                    options: {
                        chart: {
                            width: 260
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }],
                labels: ['Plan 1', 'Plan 2', 'Plan 3', 'Plan 4'],
                colors: ['var(--chart-color1)', 'var(--chart-color2)', 'var(--chart-color3)',
                    'var(--chart-color4)'
                ],
                dataLabels: {
                    enabled: false
                },
                legend: {
                    position: 'bottom', // top, bottom
                    enabled: false
                }
            };
            var chart = new ApexCharts(document.querySelector("#apex-TopSellingPlans"), options);
            chart.render();
        })
    </script>
@endpush
