<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Welkom</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">

    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <tr>
            <td style="text-align: center; padding: 30px 20px 10px;">
                <img src="{{ asset('images/tallandlogovoetbal.png') }}" alt="Voetbal Logo" style="max-width: 150px; height: auto;">
            </td>
        </tr>
        <tr>
            <td style="padding: 0 30px;">
                <h2 style="color: #333;">Welkom, {{ $user->name }}!</h2>
                <p style="font-size: 16px; color: #555;">Je bent succesvol geregistreerd. Klik op de onderstaande knop om je wachtwoord in te stellen en toegang te krijgen tot het systeem.</p>

                <div style="text-align: center; margin: 30px 0;">
                    <a href="{{ $link }}" style="background-color: #28a745; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; font-size: 16px;">Wachtwoord instellen</a>
                </div>

                <p style="font-size: 14px; color: #777;">Deze link is éénmalig te gebruiken. Heb je je niet geregistreerd? Dan kun je deze mail negeren.</p>
            </td>
        </tr>
        <tr>
            <td style="padding: 20px 30px; text-align: center; font-size: 12px; color: #aaa;">
                &copy; {{ date('Y') }} Handhaving Voetbalstadion. Alle rechten voorbehouden.
            </td>
        </tr>
    </table>

</body>
</html>
