<!DOCTYPE html>
<html>
<head>
    <title>Registreren</title>
</head>
<body>
    <h1>Registratieformulier</h1>

    @if(session('success'))
        <p style="color: green">{{ session('success') }}</p>
    @endif
    {{-- form for registering a new user --}}
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <label for="name">Volledige naam:</label>
        <input type="text" name="name" required><br>

        <label for="username">Gebruikersnaam:</label>
        <input type="text" name="username" required><br>

        <label for="email">Email:</label>
        <input type="email" name="email" required><br>

        <label for="group_id">Groep:</label>
        <select name="group_id" required>
            @foreach($groups as $group)
                <option value="{{ $group->id }}">{{ $group->name }}</option>
            @endforeach
        </select><br>

        <label for="role">Rol:</label>
        <select name="role" required>
            <option value="gebruiker">Gebruiker</option>
            <option value="admin">Admin</option>
        </select><br>
        

        <button type="submit">Account aanmaken</button>
    </form>
</body>
</html>
