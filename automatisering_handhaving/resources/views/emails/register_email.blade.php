{{-- filepath: resources/views/emails/register_email.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <title>Welkom</title>
</head>
<body>
<h1>Welkom, {{ $user->name }}!</h1>
<p>Klik op onderstaande knop om je wachtwoord in te stellen:</p>
<p><a href="{{ $link }}">Wachtwoord instellen</a></p>

</body>
</html>