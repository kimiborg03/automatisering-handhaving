@extends('layouts.app')

@push('styles')
        <link rel="stylesheet" href="{{ asset('css/home.css') }}">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

@endpush

@section('title', 'home')

@section('content')

                    <div class="container mt-4 bg-light rounded p-4">
                        <h2 class="mb-4 text-center fw-bold">Aangemelde Wedstrijden</h2>

                        <?php
$matches = \App\Models\Matches::whereJsonContains('users', ['id' => $userId])->get();
                                                            ?>

                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3">
                            @foreach ($allMatches as $match)
           @include('partials.match-card', [
        'match' => $match,
        'showRuil' => $match->deadline ? !$match->deadline->isPast() : false
    ])  

                            @endforeach
                        </div>
                    </div>

                    <div class="container mt-4 bg-light rounded p-4">
                        <h2 class="mb-4 text-center fw-bold">Gespeelde Wedstrijden</h2>

                        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3">
                            @foreach ($playedMatches as $match)
                                <div class="col">
                                    <div class="card h-100 shadow-sm p-2">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $match->name }}</h5>
                                            <p class="card-text mb-1"><strong>Datum:</strong> {{ $match->checkin_time->format('Y-m-d') }}</p>
                                            <p class="card-text mb-1"><strong>Locatie:</strong> {{ $match->location }}</p>
                                            <p class="card-text mb-1"><strong>Check-in:</strong> {{ $match->checkin_time->format('H:i') }}</p>
                                            <p class="card-text mb-2"><strong>Aftrap:</strong> {{ $match->kickoff_time->format('H:i') }}</p>
                                            {{-- {{}} --}}
                                            <button class="btn btn-outline-primary btn-sm w-100" data-bs-toggle="modal"
                                                data-bs-target="#matchModal"
                                                onclick='openMatchModal(@json($match) )'>
                                                Meer
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Modals (Match Modal, Swap Modal, Confirm Modal) --}}
                    @include('partials.match-modals', ['userId' => $userId, 'allMatches' => $allMatches])

                    {{-- Scripts --}}
                    @push('scripts')
                        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
                        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
                        <script>
                                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                                console.log(csrfToken);  // Dit print het CSRF-token in de console
                                let selectedSwap = {
                                    fromMatch: null,
                                    toMatch: null
                                };
                                function openMatchModalFromButton(button) {
                                    const match = JSON.parse(button.getAttribute('data-match'));
                                    const showRuil = button.getAttribute('data-showruil') === 'true';
                                    openMatchModal(match, true);
                                }
                                function openMatchModal(match, showRuilButton) {
                                    console.log("Show ruil button?", showRuilButton); // Add this to debug

                                    let matchUsers = Array.isArray(match.users) ? match.users : JSON.parse(match.users);
                                    let numberOfUsers = matchUsers.length;

                                    function formatUtc(datetime) {
                                        const date = new Date(datetime);
                                        return `${date.getUTCFullYear()}-${String(date.getUTCMonth() + 1).padStart(2, '0')}-${String(date.getUTCDate()).padStart(2, '0')} ` +
                                            `${String(date.getUTCHours()).padStart(2, '0')}:${String(date.getUTCMinutes()).padStart(2, '0')}`;
                                    }

                                    const body = document.getElementById('modal-content-body');
                                    let stringThing = `
                                                                                                            <p><strong>Naam:</strong> ${match.name}</p>
                                                                                                            <p><strong>Datum:</strong> ${formatUtc(match.checkin_time)}</p>
                                                                                                            <p><strong>Locatie:</strong> ${match.location}</p>
                                                                                                            <p><strong>Check-in:</strong> ${formatUtc(match.checkin_time)}</p>
                                                                                                            <p><strong>Aftrap:</strong> ${formatUtc(match.kickoff_time)}</p>
                                                                                                            <p><strong>Category:</strong> ${match.category}</p>
                                                                                                            <p><strong>Ingeschreven:</strong> ${numberOfUsers} / ${match.limit}</p>
                                                                                                        `;

                                    if (showRuilButton) {
                                        const matchData = encodeURIComponent(JSON.stringify(match));
                                        stringThing += `
                                                                                                                <button class="btn btn-outline-warning btn-sm w-100 mt-2"
                                                                                                                    data-bs-toggle="modal"
                                                                                                                    data-bs-target="#swapModal"
                                                                                                                    onclick='openSwapModal(JSON.parse(decodeURIComponent("${matchData}")))'>
                                                                                                                    Wedstrijd Ruilen
                                                                                                                </button>
                                                                                                            `;
                                    }

                                    body.innerHTML = stringThing;
                                }

                                function openSwapModal(currentMatch) {
                                    const allMatches = @json($availableMatches);
                                    selectedSwap.fromMatch = currentMatch;

                                    const body = document.getElementById('swap-modal-body');
                                    body.innerHTML = '';

                                    allMatches.forEach(match => {
                                        if (match.id !== currentMatch.id) {
                                            const matchData = encodeURIComponent(JSON.stringify(match));
                                            body.innerHTML += `
                                                                                                                    <div class="card mb-2">
                                                                                                                        <div class="card-body p-2">
                                                                                                                            <strong>${match.name}</strong><br>
                                                                                                                            Locatie: ${match.location}<br>
                                                                                                                            Tijd: ${new Date(match.kickoff_time).toLocaleTimeString()}
                                                                                                                            <button class="btn btn-sm btn-outline-success mt-2"
                                                                                                                                data-bs-toggle="modal"
                                                                                                                                data-bs-target="#thirdModal"
                                                                                                                                onclick='prepareConfirm(JSON.parse(decodeURIComponent("${matchData}")))'>
                                                                                                                                Kies
                                                                                                                            </button>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                `;
                                        }
                                    });
                                }


                                function prepareConfirm(toMatch) {
                                    selectedSwap.toMatch = toMatch;

                                    const body = document.getElementById('third-modal-body');
                                    body.innerHTML = `
                                                                                                            <p>Weet je zeker dat je wilt wisselen naar <strong>${toMatch.name}</strong>?</p>
                                                                                                        `;
                                }
                                function confirmAction() {
                                    const userId = {{ $userId }};
                                    const fromMatchId = selectedSwap.fromMatch.id;
                                    const toMatchId = selectedSwap.toMatch.id;
                                    console.log("confirmAction gestart!");
                                    console.log("Verwijderen uit match", fromMatchId, userId);

                                    fetch(`/match/${fromMatchId}/user/remove`, {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': csrfToken  
                                        },
                                        body: JSON.stringify({ user_id: userId })
                                    })
                                        .then(async res => {
                                            const text = await res.text();
                                            console.log("Remove response:", text);
                                            return JSON.parse(text);
                                        })
                                        .then(() => {
                                            console.log("Toevoegen aan match", toMatchId);
                                            return fetch(`/match/${toMatchId}/update`, {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'X-CSRF-TOKEN': csrfToken  // Correcte CSRF-token
                                                },
                                                body: JSON.stringify({ user_id: userId })
                                            });
                                        })
                                        .then(async res => {
                                            const text = await res.text();
                                            console.log("Add response:", text);
                                            return JSON.parse(text);
                                        })
                                        .then(data => {
                                            console.log("Actie bevestigd en voltooid", data);
                                            bootstrap.Modal.getInstance(document.getElementById('thirdModal')).hide();
                                            location.reload();
                                        })
                                        .catch(err => {
                                            console.error("Fout tijdens fetch:", err);
                                        });
                                }



                            </script>
                    @endpush

@endsection