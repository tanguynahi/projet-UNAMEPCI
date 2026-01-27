<!-- resources/views/emails/contact.blade.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{ $details['objet'] }}</title>
</head>
<body>
    <h1>Nouveau message de contact</h1>
   <center><h6>Depuis la plateforme Mutualpaly</h6></center> 
    <p><strong>Nom :</strong> {{ $details['contact_name'] }}</p>
    <p><strong>Email :</strong> {{ $details['contact_email'] }}</p>
    <p><strong>Objet :</strong> {{ $details['objet'] }}</p>
    <p><strong>Message :</strong></p>
    <p>{{ $details['contact_message'] }}</p>
</body>
</html>
