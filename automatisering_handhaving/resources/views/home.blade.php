<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">
    <div class="container mt-4">
        <h3 class="mb-4">Aangemelde Wedstrijden</h3>

        <?php
$matches = \App\Models\Matches::whereJsonContains('users', ['id' => $userId])->get();
        ?>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3">
            @foreach ($matches as $match)
                <div class="col">
                    <div class="card h-100 shadow-sm p-2">
                        <div class="card-body">
                            <h5 class="card-title">{{ $match->name }}</h5>
                            <p class="card-text mb-1"><strong>Datum:</strong> {{ $match->checkin_time->format('Y-m-d') }}
                            </p>
                            <p class="card-text mb-1"><strong>Locatie:</strong> {{ $match->location }}
                            </p>
                            <p class="card-text mb-1"><strong>Check-in:</strong> {{ $match->checkin_time->format('H:i') }}
                            </p>
                            <p class="card-text mb-2"><strong>Aftrap:</strong> {{ $match->kickoff_time->format('H:i') }}</p>
                            <button class="btn btn-outline-primary btn-sm w-100" data-bs-toggle="modal"
                                data-bs-target="#matchModal" onclick='openMatchModal(@json($match))'>
                                Meer
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="modal fade" id="matchModal" tabindex="-1" aria-labelledby="matchModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow">
                <div class="modal-header">
                    <h5 class="modal-title" id="matchModalLabel">Wedstrijd Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Sluiten"></button>
                </div>
                <div class="modal-body" id="modal-content-body">
                    <!-- Dynamic content will be inserted here -->
                </div>
            </div>
        </div>
    </div>
    <!-- Second Modal: Swap Match -->
    <div class="modal fade" id="swapModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow">
                <div class="modal-header">
                    <h5 class="modal-title">Wedstrijd Ruilen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Sluiten"></button>
                </div>
                <div class="modal-body" id="swap-modal-body">
                    <!-- Content will be filled by JS -->
                </div>
            </div>
        </div>
    </div>

    <!-- Third Modal (Confirmation) -->
    <div class="modal fade" id="thirdModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow">
                <div class="modal-header">
                    <h5 class="modal-title">Actie Bevestigen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Sluiten"></button>
                </div>
                <div class="modal-body">
                    <p>Weet je zeker dat je deze actie wilt voltooien?</p>
                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                        <button type="button" class="btn btn-danger" onclick="confirmAction()">Ja, bevestigen</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function openMatchModal(match) {
            let matchUsers = Array.isArray(match.users) ? match.users : JSON.parse(match.users);
            let numberOfUsers = matchUsers.length;

            const checkin = new Date(match.checkin_time);
            const kickoff = new Date(match.kickoff_time);

            // Get raw values without local timezone shift
            function formatUtc(datetime) {
                const date = new Date(datetime);
                return `${date.getUTCFullYear()}-${String(date.getUTCMonth() + 1).padStart(2, '0')}-${String(date.getUTCDate()).padStart(2, '0')} ` +
                    `${String(date.getUTCHours()).padStart(2, '0')}:${String(date.getUTCMinutes()).padStart(2, '0')}`;
            }

            const body = document.getElementById('modal-content-body');
            body.innerHTML = `
        <p><strong>Naam:</strong> ${match.name}</p>
        <p><strong>Datum:</strong> ${formatUtc(match.checkin_time)}</p>
        <p><strong>Locatie:</strong> ${match.location}</p>
        <p><strong>Check-in:</strong> ${formatUtc(match.checkin_time)}</p>
        <p><strong>Aftrap:</strong> ${formatUtc(match.kickoff_time)}</p>
        <p><strong>Category:</strong> ${match.category}</p>
        <p><strong>Ingeschreven:</strong> ${numberOfUsers} / ${match.limit}</p>
         
<button class="btn btn-outline-warning btn-sm w-100 mt-2"
    data-bs-toggle="modal"
    data-bs-target="#swapModal"
    onclick='openSwapModal(${JSON.stringify(match)})'>
    Wedstrijd Ruilen
</button>
    `;
        }
        function openSwapModal(currentMatch) {
            const allMatches = @json($allMatches);

            const body = document.getElementById('swap-modal-body');
            body.innerHTML = '';

            allMatches.forEach(match => {
                if (match.id !== currentMatch.id) {
                    body.innerHTML += `
                <div class="card mb-2">
                    <div class="card-body p-2">
                        <strong>${match.name}</strong><br>
                        Locatie: ${match.location}<br>
                        Tijd: ${new Date(match.kickoff_time).toLocaleTimeString()}
                        <button class="btn btn-sm btn-outline-success mt-2">Kies</button>
                    </div>
                </div>
            `;
                }
            });
        }

        function confirmAction() {
            console.log("Actie bevestigd!");
            bootstrap.Modal.getInstance(document.getElementById('thirdModal')).hide();
        }


    </script>
</body>


</html>