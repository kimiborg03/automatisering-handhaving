<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h3 class="card-title text-center mb-4">Wedstrijd Toevoegen</h3>
                        <form action="{{ route('matches.store') }}" method="POST">
                            @csrf
<div class="mb-3">
    <label for="name-match" class="form-label">Naam wedstrijd</label>
    <input type="text" class="form-control" name="name-match" id="name-match" required>
</div>

<div class="mb-3">
    <label for="location" class="form-label">Locatie</label>
    <input type="text" class="form-control" name="location" id="location" required>
</div>

<div class="mb-3">
    <label for="date" class="form-label">Datum</label>
    <input type="date" class="form-control" name="date" id="date" required>
</div>

<div class="mb-3">
    <label for="check-in-time" class="form-label">Verzamelen tijdstip</label>
    <input type="time" class="form-control" name="check-in-time" id="check-in-time" required>
</div>

<div class="mb-3">
    <label for="kick-off-time" class="form-label">Aftrap tijdstip</label>
    <input type="time" class="form-control" name="kick-off-time" id="kick-off-time" required>
</div>

<div class="mb-3">
    <label for="category" class="form-label">Categorie</label>
    <select class="form-select" name="category" id="category" required>
        <option value="">-- Selecteer een categorie --</option>
        <option value="AZ Alkmaar">AZ Alkmaar</option>
        <option value="Jong AZ">Jong AZ</option>
        <option value="AZ-Vrouwen">AZ-Vrouwen</option>
        <option value="Overige">Overige</option>
    </select>
</div>

<div class="mb-3">
    <label for="groups" class="form-label">Klas</label>
    <select name="groups[]" id="groups" class="form-select" multiple size="4">
        @foreach ($groups as $group)
            <option value="{{ $group->id }}">{{ $group->name }}</option>
        @endforeach
    </select>
                            </div>

                            <div class="mb-3">
                                <label for="Limit" class="form-label">Aantal</label>
                                <input type="number" class="form-control" id="Limit" name="Limit">
                            </div>

                            <div id="extraInputContainer" class="mb-3">
                                <label for="deadline" class="form-label">Deadline</label>
                                <input type="datetime-local" class="form-control" id="deadline" name="deadline">
                            </div>

                            <div class="mb-3">
                                <label for="comment" class="form-label">Opmerking</label>
                                <textarea type="text" class="form-control" name="comment" id="comment"></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Wedstrijd toevoegen</button>
                        </form>
                    </div>
                    </div>
                    </div>
                    </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

    <script>
        const checkbox = document.getElementById('extraCheckbox');
        const container = document.getElementById('extraInputContainer');

        checkbox.addEventListener('change', () => {
            container.style.display = checkbox.checked ? 'block' : 'none';
        });
</script>
</body>

</html>
