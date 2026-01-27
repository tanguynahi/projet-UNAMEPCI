<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>notification</title>
</head>
<body>
    <p> salut

        @foreach ($notifications as $notification)
            <li>{{ $notification->data['message'] }} - {{ $notification->created_at->diffForHumans() }}</li>
        @endforeach
    </p>


    <script>
        Echo.private(`App.Models.User.${mutualiste->id}`)
    .notification((notification) => {
        console.log(notification);
        // Update the UI with the new notification
    });
    </script>
</body>
</html>
