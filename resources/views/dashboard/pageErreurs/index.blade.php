<!DOCTYPE html>
<html>
<head>
    <title>Erreur {{ $code }} - Accès non autorisé</title>
</head>
<body>
    <h1>Erreur {{ $code }}</h1>
    <p>{{ $mess ?? 'Une erreur s\'est produite. Veuillez réessayer plus tard.' }}</p>
</body>
</html>
