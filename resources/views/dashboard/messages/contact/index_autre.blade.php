<!DOCTYPE html>
<html>
<head>
    <title>Message du Particulier</title>
</head>
<body>
    <h1>{{ $sujet }}</h1>
    <p>{{ $messageContent }}</p>
    @if($lien_document)
            
        <p>
            <img src="{{ asset($lien_document) }}" alt="fichier joint" style="height:180px; width:200px;"> <br>
            <a href="{{ asset($lien_document) }}" >Télécharger le document joint</a>
        </p>
    @endif
</body>
</html>