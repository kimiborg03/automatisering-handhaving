{{-- filepath: resources/views/emails/register_email.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <title>Welkom</title>
</head>
<body>
    <h1>Welkom, {{ $user->name }}!</h1>
    <p>Bedankt voor je registratie bij onze applicatie. We zijn blij je aan boord te hebben!</p>
    <p>Als je vragen hebt, neem dan gerust contact met ons op.</p>
</body>
</html>