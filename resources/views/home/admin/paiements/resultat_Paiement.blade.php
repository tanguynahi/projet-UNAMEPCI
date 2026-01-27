@extends('layouts.home_dashboard', ['title' => 'Resultat Paiement'])
@section('content')
    <div class="col-lg-9">
        <div class="rbt-dashboard-content bg-color-white rbt-shadow-box mb--60">
            <div class="content">
                <div class="section-title">
                    <h4 class="rbt-title-style-3 text-center text-primary">Resultat Paiement</h4>
                </div>
                <style>
                    .success-container {
                        background-color: #fff;
                        padding: 20px;
                        border-radius: 8px;
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                        text-align: center;
                        position: relative;
                    }

                    .success-container p {
                        color: #333;
                        margin-bottom: 20px;
                    }

                    .success-container a {
                        display: inline-block;
                        padding: 10px 20px;
                        background-color: gray;
                        color: #fff;
                        text-decoration: none;
                        border-radius: 4px;
                    }

                    .success-container a:hover {
                        background-color: #45a049;
                    }

                    .success-symbol,
                    .failure-symbol {
                        width: 200px;
                        height: 200px;
                        margin-bottom: 20px;
                        animation: bounce 1s infinite;
                    }

                    .success-symbol {
                        fill: #4CAF50;
                    }

                    .failure-symbol {
                        fill: rgba(229, 62, 62, 0.951);
                    }

                    @keyframes bounce {

                        0%,
                        100% {
                            transform: translateY(0);
                        }

                        50% {
                            transform: translateY(-10px);
                        }
                    }

                    @media (max-width: 768px) {
                        .success-container {
                            padding: 15px;
                        }

                        .success-symbol,
                        .failure-symbol {
                            width: 80px;
                            height: 80px;
                        }

                        .row {
                            padding: 20px;
                        }
                    }

                    @media (max-width: 576px) {
                        .success-container {
                            padding: 10px;
                        }

                        .success-symbol,
                        .failure-symbol {
                            width: 60px;
                            height: 60px;
                        }

                        .row {
                            padding: 10px;
                        }

                        h4 {
                            font-size: 1.2rem;
                        }

                        .col-md-6 {
                            width: 100%;
                        }
                    }
                </style>


                <div class="row g-5 justify-content-center">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                        <div class="rbt-counterup variation-01 rbt-hover-03 rbt-border-dashed bg-primary-opacity">
                            <a href="#">
                                <div class="inner">
                                    @if ($code == 200)
                                        <svg class="success-symbol" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            width="350" height="350">
                                            <path
                                                d="M12 0C5.371 0 0 5.371 0 12s5.371 12 12 12 12-5.371 12-12S18.629 0 12 0zm-1.27 18L5 11.27l1.41-1.41 4.32 4.32 7.88-7.88L20 8.12l-9.27 9.27z" />
                                        </svg>
                                    @else
                                        <svg class="failure-symbol" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                            <path
                                                d="M12 0C5.371 0 0 5.371 0 12s5.371 12 12 12 12-5.371 12-12S18.629 0 12 0zm5.656 15.656l-1.414 1.414L12 13.414l-4.242 4.242-1.414-1.414L10.586 12 6.343 7.757l1.414-1.414L12 10.586l4.242-4.243 1.414 1.414L13.414 12l4.242 4.242z" />
                                        </svg>
                                    @endif
                                    <h6 class=" mt-3 color-primary">{{ $code }}</h6>
                                    <h6 class=" mt-3 color-primary">{{ $mess }}</h6>
                                </div>

                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
