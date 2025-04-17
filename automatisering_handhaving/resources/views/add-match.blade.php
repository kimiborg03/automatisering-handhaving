<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>
    <div class="loginform">
        <form action="" method="POST">
            @csrf
            <label for="name-match">Naam wedstrijd</label>
            <input type="text" name="name-match" id="name-match" required>
            <label for="location">Locatie</label>
            <input type="text" name="location" id="location" required>
            <label for="date">Datum</label>
            <input type="date" name="date" id="date" required>
            <label for="check-in-time">Verzamelen tijdstip</label>
            <input type="time" name="check-in-time" id="check-in-time" required>
            <label for="kick-off-time">Aftrap tijdstip</label>
            <input type="time" name="kick-off-time" id="kick-off-time" required>
            <label for="category">Categorie</label>
            <input type="text" name="category" id="category" required>
            <label for="groups">Klas</label><br>
            <select name="groups[]" id="groups" multiple size="4">
                <option value="optie1">Klas 1</option>
                <option value="optie2">Klas 2</option>
                <option value="optie3">Klas 3</option>
                <option value="optie4">Klas 4</option>
            </select>
            <label for="Limit">Aantal</label>
            <input type="number" id="Limit" name="Limit">


            <label for="extraCheckbox">
                <input type="checkbox" id="extraCheckbox"> Andere deadline (by default 3 dagen van te voren)
            </label>

            <div id="extraInputContainer" style="display: none; margin-top: 10px;">
                <label for="deadline">Deadline</label>
                <input type="datetime-local" id="deadline" name="deadline">
            </div>
            <button type="submit">Inloggen</button>

        </form>
    </div>


    <script>
        const checkbox = document.getElementById('extraCheckbox');
        const container = document.getElementById('extraInputContainer');

        checkbox.addEventListener('change', () => {
            container.style.display = checkbox.checked ? 'block' : 'none';
        });
    </script>

</body>

</html>