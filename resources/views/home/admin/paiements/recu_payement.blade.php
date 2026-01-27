<!DOCTYPE html>
<html>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Reçu de Paiement</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .receipt-container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 10px;
        }

        .receipt-header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }

        .receipt-details {
            margin-bottom: 20px;
        }

        .receipt-details p {
            margin: 0;
            font-size: 18px;
            color: #666;
        }

        .receipt-details p strong {
            color: #333;
        }

        .receipt-footer {
            text-align: center;
            margin-top: 20px;
        }

        .receipt-footer p {
            margin: 0;
            font-size: 14px;
            color: #999;
        }

        span {
            text-transform: uppercase;
        }

        .floating-button {
            position: fixed;
            bottom: 20px;
            left: 45%;
            transform: translateX(-50%);
            z-index: 1000;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {

            0%,
            20%,
            50%,
            80%,
            100% {
                transform: translateY(0);
            }

            40% {
                transform: translateY(-20px);
            }

            60% {
                transform: translateY(-10px);
            }
        }

        @media print {
            #printButton {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="receipt-container">

        <center>
            <p>------------------------------------------------------------------------------------------</p>
        </center>
        <div class="receipt-header">
            <h1> Reçu de Paiement</h1>
        </div>
        <center>
            <p>------------------------------------------------------------------------------------------</p>
        </center>

        <div class="receipt-details mx-4 container">
            <div class="row">
                <div class="col-sm text-center mb-2 text-uppercase"><u>Informations</u> </div>
            </div>
            <div class="row pb-2">
                <div class="col-7">
                    <strong>
                        Nom & Prénoms: <br>
                    </strong>
                </div>
                <div class="col-5">
                    <span class="text-center  text-uppercase">
                        <b> {{ $paiement->mutualiste->nom }} {{ $paiement->mutualiste->prenom }}</b>
                    </span>
                </div>
            </div>
            <div class="row pb-2">
                <div class="col-7">
                    <strong>
                        Matricule: <br>

                    </strong>
                </div>
                <div class="col-5">
                    <span class="text-center  text-uppercase">
                        <b> {{ $paiement->mutualiste->matricule }}</b>
                    </span>
                </div>
            </div>
            <div class="row pb-2">
                <div class="col-7">
                    <strong>
                        Corps d'armée: <br>
                    </strong>
                </div>
                <div class="col-5">
                    <span class="text-center">
                        <b> {{ $paiement->mutualiste->corp->libelle }}</b>
                    </span>
                </div>
            </div>
            <div class="row pb-2">
                <div class="col-7">
                    <strong>
                        Grade:
                    </strong>
                </div>
                <div class="col-5">
                    <span class="text-center">
                        <b> {{ $paiement->mutualiste->grade->libelle }}</b>
                    </span>
                </div>
            </div>
            <div class="row pb-2">
                <div class="col-7">
                    <strong>
                        Unite: <br>

                    </strong>
                </div>
                <div class="col-5">
                    <span class="text-center">
                        <b> {{ $paiement->mutualiste->unite }}</b>
                    </span>
                </div>
            </div>
            <div class="row pb-2">
                <div class="col-7">
                    <strong>
                        Telephone :

                    </strong>
                </div>
                <div class="col-5">
                    <span class="text-center">
                        <b> {{ $paiement->mutualiste->contact }} {{ $paiement->mutualiste->contact_2 }}</b>
                    </span>
                </div>
            </div>

            <div class="row pb-2">
                <div class="col-sm text-center text-uppercase"><u>Detail du paiement</u> </div>
            </div>
            <div class="row pb-2">
                <div class="col-7">
                    <strong>Référence de Paiement :</strong>
                </div>
                <div class="col-5">
                    <span style="color:red;" class="text-center">
                        @if (!empty($paiement->reference))
                            {{ $paiement->reference }}
                        @else
                            {{ $paiement->code_paiement }}
                        @endif
                    </span>

                </div>
            </div>
            <div class="row pb-2">
                <div class="col-7">
                    <strong>Date de paiement :</strong>
                </div>
                <div class="col-5">
                    <span class="text-center  text-uppercase">
                        {{ formatDateTime($paiement->created_at) }}
                    </span>
                </div>
            </div>
            @if (!empty($paiement->contact_paiement))
                <div class="row pb-4 pt-3">
                    <div class="col-7">
                        <strong>
                            Contact de Paiement :
                        </strong>
                    </div>
                    <div class="col-5">
                        <span class="text-center">
                            <b> {{ $paiement->contact_paiement }} </b>
                        </span>
                    </div>
                </div>
            @endif
            @if (!empty($paiement->moyen_paiement))
                <div class="row pb-2">
                    <div class="col-7">
                        <strong>
                            Moyen de paiement :
                        </strong>
                    </div>
                    <div class="col-5">
                        <span class="text-center">
                            <b>{{ $paiement->moyen_paiement }}</b>
                        </span>
                    </div>
                </div>
            @endif
            <div class="row pb-2">
                <div class="col-7">
                    <strong>
                        Montant :
                    </strong>
                </div>
                <div class="col-5">
                    <span class="text-center">
                        <b>{{ formatMontant($paiement->montant_initial) }}</b>
                    </span>
                </div>
            </div>

            {{-- <div class="container"> --}}
            <div class="row ">
                <div class="col-4">
                    <strong>Nature du paiement</strong>
                    <br>
                    @if ($paiement->type_paiement_id == 1)
                        <span> droit d'{{ $paiement->typePaiement->libelle }}</span>
                    @endif
                    @if ($paiement->type_paiement_id != 1)
                        <span> {{ $paiement->typePaiement->libelle }}</span>
                    @endif
                </div>
                <div class="col-4">
                    <strong>Montant</strong>
                    <br>
                    {{ formatMontant($paiement->montant_initial) }}
                </div>
                <div class="col-4">
                    <strong>Frais</strong> <br>
                    {{ formatMontant($paiement->frais) }}
                </div>
            </div>
            {{-- </div> --}}
        </div>
        <center>
            <p>------------------------------------------------------------------------------------------</p>
        </center>
        <p class="text-center">
            ce reçu atteste que la somme mentionnée ci-dessus a été reçue en paiement
            @if ($paiement->type_paiement_id == 1)
                <b> du droit d'{{ $paiement->typePaiement->libelle }}</b>
            @endif
            @if ($paiement->type_paiement_id != 1)
                <b> de {{ $paiement->typePaiement->libelle }}</b>
            @endif
        </p>
        <div class="text-center ">
            <span class="mt-3">
                {{ QrCode::size(75)->generate($code) }}
            </span>
        </div>
        <center>
            <p>------------------------------------------------------------------------------------------</p>
        </center>
        <div class="receipt-footer">
            <p> <span class="text-center">Merci pour votre paiement.</span> <br>
                FPM, Societe de Secours Mutuels et de Prevoyance Sociale Arrete N°546/NHHJ/JDDZ/010110 du 29-12-1992
                Siege:Ministere de la Defense - <br>
                Abidjan B.P V 327 - Tel.: 20 21 35 55/ 20 21 55 20 - Fax: 20 21 33 61
                <br>
                Email : <a href="Mailto:email@gmail.com">email@gmail.com </a>/
                site web : <a href="https/www.fpmmet.ci">www.fpmmet.ci</a>
            </p>
            <p>Date de telechargement du recu:<strong>{{ date('d-m-Y H:i') }}</strong></p>
        </div>

    </div>

    <div class="floating-button">
        <a class="btn btn-primary" id="printButton">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                class="bi bi-cloud-arrow-down" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                    d="M7.646 10.854a.5.5 0 0 0 .708 0l2-2a.5.5 0 0 0-.708-.708L8.5 9.293V5.5a.5.5 0 0 0-1 0v3.793L6.354 8.146a.5.5 0 1 0-.708.708z" />
                <path
                    d="M4.406 3.342A5.53 5.53 0 0 1 8 2c2.69 0 4.923 2 5.166 4.579C14.758 6.804 16 8.137 16 9.773 16 11.569 14.502 13 12.687 13H3.781C1.708 13 0 11.366 0 9.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383m.653.757c-.757.653-1.153 1.44-1.153 2.056v.448l-.445.049C2.064 6.805 1 7.952 1 9.318 1 10.785 2.23 12 3.781 12h8.906C13.98 12 15 10.988 15 9.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 4.825 10.328 3 8 3a4.53 4.53 0 0 0-2.941 1.1z" />
            </svg>
            Télécharger Reçu
        </a>
    </div>
    <script>
        document.getElementById('printButton').addEventListener('click', function() {
            window.print();
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
</body>

</html>
