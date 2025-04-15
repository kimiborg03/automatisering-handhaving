<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    @auth
        <h1>Je bent al ingelogd</h1>
        <!-- log out button incase user is logged in -->
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit">Uitloggen</button>
        </form>
    @else
        <h1>Login</h1>
        <!-- login form -->
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>
            <label for="password">Wachtwoord:</label>
            <input type="password" name="password" id="password" required>
            <button type="submit">Inloggen</button>
        </form>
    @endauth
</body>
</html>
