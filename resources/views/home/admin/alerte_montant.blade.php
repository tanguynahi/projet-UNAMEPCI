
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alerte Administrateur</title>
    <!-- <link rel="stylesheet" href="styles.css"> -->
</head>
<style>
    /* Style de base pour la page */
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }

    /* Style pour le conteneur de l'alerte */
    .alert-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s, visibility 0.3s;
    }

    /* Style pour la boîte d'alerte */
    .alert-box {
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        text-align: center;
        max-width: 400px;
        width: 100%;
        animation: slideIn 0.5s ease-in-out;
    }

    /* Animation de la boîte d'alerte */
    @keyframes slideIn {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    /* Style pour le bouton de fermeture */
    .close-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 20px;
        cursor: pointer;
    }

    /* Afficher le conteneur de l'alerte */
    .alert-container.show {
        opacity: 1;
        visibility: visible;
    }
</style>

<body>
    <div class="alert-container" id="alertContainer">
        <div class="alert-box">
            <span class="close-btn" onclick="closeAlert()">&times;</span>
            <h2>Salut Cher Administrateur !</h2>
            {{-- <p>Attention : Il manque des informations critiques !</p> --}}
            <p>Bonjour, Cher Administrateur</p>
            <p>Un utilisateur a saisi un montant élevé de
                <b>
                    {{ formatMontant($montant_voulue) }}
                </b>
                <br>
                dans le formulaire de demande d'accompagnements.
            </p>
            <p>Cordialement,</p>
            <p>L'équipe de votre application</p>
        </div>
    </div>

    <!-- <script src="script.js"></script> -->
    <script>
        // Fonction pour afficher l'alerte
        function showAlert() {
            const alertContainer = document.getElementById('alertContainer');
            alertContainer.classList.add('show');
        }

        // Fonction pour fermer l'alerte
        function closeAlert() {
            const alertContainer = document.getElementById('alertContainer');
            alertContainer.classList.remove('show');
        }

        // Afficher l'alerte après un délai (par exemple, 1 seconde)
        setTimeout(showAlert, 1000);
    </script>
</body>

</html>
