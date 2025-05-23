document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    let offset = 0;
    const category = document.querySelector('meta[name="category"]').content;
    const allMatches = JSON.parse(document.getElementById('all-matches').textContent);

    const container = document.getElementById('matches-container');
    const loadMoreBtn = document.getElementById('load-more');
    const noMoreText = document.getElementById('no-more');
    console.log("Category:", category);
    console.log("CSRF Token:", csrfToken); // ✅ nu veilig
    // console.log("User ID:", userId);
    document.getElementById('confirm-remove-deadline-btn').addEventListener('click', function () {
        if (!pendingDeadlineMatchId) return;

        fetch(`/admin/match/${pendingDeadlineMatchId}/remove-deadline`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
        })
            .then(res => res.json())
            .then(() => {
                bootstrap.Modal.getInstance(document.getElementById('removeDeadlineModal')).hide();
                location.reload();
            })
            .catch(err => {
                console.error("Fout bij deadline verwijderen:", err);
                alert("Fout bij deadline verwijderen.");
            });
    });
    console.log("All Matches:", allMatches);
    document.getElementById('confirm-deadline-btn').addEventListener('click', function () {
        if (!pendingDeadlineMatchId) return;

        fetch(`/admin/match/${pendingDeadlineMatchId}/set-deadline-now`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
        })
            .then(res => res.json())
            .then(() => {
                bootstrap.Modal.getInstance(document.getElementById('confirmDeadlineModal')).hide();
                location.reload();
            })
            .catch(err => {
                console.error("Fout bij deadline zetten:", err);
                alert("Fout bij deadline zetten.");
            });
    });

    function loadMatches() {
        console.log('Loading matches... Offset:', offset, 'Category:', category);

        loadMoreBtn.disabled = true;
        loadMoreBtn.innerText = 'Laden...';

        fetch(`/load-matches?category=${category}&offset=${offset}`)
            .then(response => {
                console.log('Fetch response status:', response.status);
                if (!response.ok) throw new Error('Server returned an error');
                return response.json();
            })
            .then(matches => {
                console.log('Fetched matches:', matches);

                if (!Array.isArray(matches)) {
                    console.error('Response is not an array:', matches);
                    throw new Error('Invalid response format');
                }

                if (matches.length === 0) {
                    console.log('No more matches to show.');
                    loadMoreBtn.classList.add('d-none');
                    noMoreText.classList.remove('d-none');
                    return;
                }

                matches.forEach(match => {
                    console.log('Rendering match:', match);

                    const col = document.createElement('div');
                    col.className = 'col';
                    col.innerHTML = `
                        <div class="card h-100 shadow-sm p-2">
                            <div class="card-body">
                                <h5 class="card-title">${match.name}</h5>
                                <p class="card-text mb-1"><strong>Datum:</strong> ${new Date(match.checkin_time).toLocaleDateString()}</p>
                                <p class="card-text mb-1"><strong>Deadline:</strong> ${new Date(match.deadline).toLocaleDateString()}</p>
                                <p class="card-text mb-1"><strong>Locatie:</strong> ${match.location}</p>
                                <p class="card-text mb-1"><strong>Check-in:</strong> ${new Date(match.checkin_time).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</p>
                                <p class="card-text mb-2"><strong>Aftrap:</strong> ${new Date(match.kickoff_time).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</p>
                            <button class="btn btn-outline-primary btn-sm w-100" data-bs-toggle="modal"
                                data-bs-target="#matchModal" onclick='openMatchModal(${JSON.stringify(match)}, true)'>
                                Meer
                            </button>
                            </div>
                        </div>
                    `;
                    container.appendChild(col);
                });

                offset += matches.length;
                console.log('Updated offset:', offset);

                loadMoreBtn.disabled = false;
                loadMoreBtn.innerText = 'Meer wedstrijden';
            })
            .catch(error => {
                console.error('Error loading matches:', error);
                alert('Er is iets misgegaan bij het laden van wedstrijden.');
                loadMoreBtn.disabled = false;
                loadMoreBtn.innerText = 'Meer wedstrijden';
            });
    }

    loadMatches();
    loadMoreBtn.addEventListener('click', loadMatches);

    // const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    // console.log(csrfToken);  // Dit print het CSRF-token in de console







});
const allMatches = JSON.parse(document.getElementById('all-matches').textContent);
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

let selectedSwap = {
    fromMatch: null,
    toMatch: null
};
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
            <p><strong>Deadline:</strong> ${match.deadline}</p>
            <p><strong>Datum:</strong> ${formatUtc(match.checkin_time)}</p>
            <p><strong>Locatie:</strong> ${match.location}</p>
            <p><strong>Check-in:</strong> ${formatUtc(match.checkin_time)}</p>
            <p><strong>Aftrap:</strong> ${formatUtc(match.kickoff_time)}</p>
            <p><strong>Category:</strong> ${match.category}</p>
            <p><strong>Ingeschreven:</strong> ${numberOfUsers} / ${match.limit}</p>
        `;

    // if (showRuilButton) {
    //     const matchData = encodeURIComponent(JSON.stringify(match));
    //     stringThing += `
    //             <button class="btn btn-outline-warning btn-sm w-100 mt-2"
    //                 data-bs-toggle="modal"
    //                 data-bs-target="#swapModal"
    //                 onclick='openSwapModal(JSON.parse(decodeURIComponent("${matchData}")))' >
    //                 Wedstrijd Ruilen
    //             </button>
    //         `;
    // }
    const deadlineIsNull = match.deadline === null || match.deadline === "null";

    if (deadlineIsNull) {

        if (!matchUsers.some(u => u.user_id == userId)) {
            console.log("User is not in match, show signup button");
            stringThing += `
        <button class="btn btn-outline-success btn-sm w-100 mt-2"
            onclick="confirmSignup(${match.id})">
            Aanmelden
        </button>
    `;
        } else {
            // console.log("Is admin?", isAdmin);
            console.log("Deadline is null?", deadlineIsNull);

            const matchData = encodeURIComponent(JSON.stringify(match));
            stringThing += `
            <button class="btn btn-outline-warning btn-sm w-100 mt-2"
                data-bs-toggle="modal"
                data-bs-target="#swapModal"
                onclick='openSwapModal(JSON.parse(decodeURIComponent("${matchData}")))' >
                Wedstrijd Ruilen
            </button>
            <button class="btn btn-outline-danger btn-sm w-100 mt-2"
                onclick="confirmUnsubscribe(${match.id})">
                Afmelden
            </button>
        `;
        }
    }
    const isAdmin = document.querySelector('meta[name="is-admin"]')?.content === 'true';

    if (isAdmin) {
        if (deadlineIsNull) {
            stringThing += `
    <button class="btn btn-outline-dark btn-sm w-100 mt-2 set-deadline-btn"
        data-match-id="${match.id}">
        Sluit deadline
    </button>
        `;
        } else {
            stringThing += `
<button class="btn btn-outline-dark btn-sm w-100 mt-2 remove-deadline-btn"
    data-match-id="${match.id}">
    Verwijder deadline
</button>
        `;
        }

    }
    body.innerHTML = stringThing;

}

// document.getElementById('confirm-deadline-btn').addEventListener('click', function () {
//     if (!pendingDeadlineMatchId) return;

//     fetch(`/admin/match/${pendingDeadlineMatchId}/set-deadline-now`, {
//         method: 'POST',
//         headers: {
//             'Content-Type': 'application/json',
//             'X-CSRF-TOKEN': csrfToken
//         },
//     })
//     .then(res => res.json())
//     .then(data => {
//         bootstrap.Modal.getInstance(document.getElementById('confirmDeadlineModal')).hide();
//         location.reload();
//     })
//     .catch(err => {
//         console.error("Fout bij deadline zetten:", err);
//         alert("Fout bij deadline zetten.");
//     });
// });

// document.getElementById('confirm-remove-deadline-btn').addEventListener('click', function () {
//     if (!pendingDeadlineMatchId) return;

//     fetch(`/admin/match/${pendingDeadlineMatchId}/remove-deadline`, {
//         method: 'POST',
//         headers: {
//             'Content-Type': 'application/json',
//             'X-CSRF-TOKEN': csrfToken
//         },
//     })
//     .then(res => res.json())
//     .then(data => {
//         bootstrap.Modal.getInstance(document.getElementById('removeDeadlineModal')).hide();
//         location.reload();
//     })
//     .catch(err => {
//         console.error("Fout bij deadline verwijderen:", err);
//         alert("Fout bij deadline verwijderen.");
//     });
// });


// function setDeadlineNow(matchId) {
//     if (!confirm("Weet je zeker dat je de deadline nu wilt zetten?")) return;
//     console.log("Verstuur verzoek naar:", `/admin/match/${matchId}/set-deadline-now`);
//     console.log("CSRF Token:", csrfToken);

//     fetch(`/admin/match/${matchId}/set-deadline-now`, {
//         method: 'POST',
//         headers: {
//             'Content-Type': 'application/json',
//             'X-CSRF-TOKEN': csrfToken
//         },
//     })
//         .then(async res => {
//             console.log("HTTP status:", res.status);
//             const text = await res.text();
//             console.log("Antwoord tekst:", text);
//             return JSON.parse(text);
//         })
//         .catch(err => {
//             console.error("Fout bij fetch:", err);
//         });
// }
// function removeDeadline(matchId) {
//     if (!confirm("Weet je zeker dat je de deadline nu wilt zetten?")) return;
//     console.log("Verstuur verzoek naar:", `/admin/match/${matchId}/remove-deadline`);
//     console.log("CSRF Token:", csrfToken);

//     fetch(`/admin/match/${matchId}/remove-deadline`, {
//         method: 'POST',
//         headers: {
//             'Content-Type': 'application/json',
//             'X-CSRF-TOKEN': csrfToken
//         },
//     })
//         .then(async res => {
//             console.log("HTTP status:", res.status);
//             const text = await res.text();
//             console.log("Antwoord tekst:", text);
//             return JSON.parse(text);
//         })
//         .catch(err => {
//             console.error("Fout bij fetch:", err);
//         });
// }
let pendingDeadlineMatchId = null;
document.addEventListener('click', function (e) {
    if (e.target.matches('.set-deadline-btn')) {
        const matchId = e.target.getAttribute('data-match-id');
        pendingDeadlineMatchId = matchId;
        const modal = new bootstrap.Modal(document.getElementById('confirmDeadlineModal'));
        modal.show();
    }

    if (e.target.matches('.remove-deadline-btn')) {
        const matchId = e.target.getAttribute('data-match-id');
        pendingDeadlineMatchId = matchId;
        const modal = new bootstrap.Modal(document.getElementById('removeDeadlineModal'));
        modal.show();
    }
});



function setDeadlineNow(matchId) {
    pendingDeadlineMatchId = matchId;
    const modal = new bootstrap.Modal(document.getElementById('confirmDeadlineModal'));
    modal.show();
}

function removeDeadline(matchId) {
    pendingDeadlineMatchId = matchId;
    const modal = new bootstrap.Modal(document.getElementById('removeDeadlineModal'));
    modal.show();
}
const availableMatches = JSON.parse(document.getElementById('availableMatches').textContent);

const userId = document.querySelector('meta[name="user-id"]').content;

function openMatchModalFromButton(button) {
    const match = JSON.parse(button.getAttribute('data-match'));
    const showRuil = button.getAttribute('data-showruil') === 'true';
    openMatchModal(match, showRuil);
}

let pendingSignupMatchId = null;

function confirmSignup(matchId) {
    pendingSignupMatchId = matchId;
    const modal = new bootstrap.Modal(document.getElementById('signupConfirmModal'));
    modal.show();
}



function openSwapModal(currentMatch) {
    selectedSwap.fromMatch = currentMatch;

    const body = document.getElementById('swap-modal-body');
    body.innerHTML = '';

    availableMatches.forEach(match => {
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
                            onclick='prepareConfirm(JSON.parse(decodeURIComponent("${matchData}")))' >
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
    const userId = document.querySelector('meta[name="user-id"]').content;
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
                    'X-CSRF-TOKEN': csrfToken
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

let pendingUnsubscribeMatchId = null;

document.addEventListener('DOMContentLoaded', function () {




    document.getElementById('confirm-unsubscribe-btn').addEventListener('click', function () {
        if (!pendingUnsubscribeMatchId) return;

        // /match/{matchId}/user/remove
        const userId = document.querySelector('meta[name="user-id"]').content;

        fetch(`/match/${pendingUnsubscribeMatchId}/user/remove`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ user_id: userId })
        })
            .then(async res => {
                const text = await res.text();
                return JSON.parse(text);
            })
            .then(data => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('unsubscribeConfirmModal'));
                if (modal) modal.hide();
                location.reload();
            })
            .catch(err => {
                console.error("Fout bij afmelden:", err);
                alert("Er is iets misgegaan bij het afmelden.");
            });
    });

    let pendingSignupMatchId = null;

    window.confirmSignup = function (matchId) {
        pendingSignupMatchId = matchId;

        const matchModalEl = document.getElementById('matchModal');
        const matchModalInstance = bootstrap.Modal.getInstance(matchModalEl);
        if (matchModalInstance) matchModalInstance.hide();

        const signupModal = new bootstrap.Modal(document.getElementById('signupConfirmModal'));
        signupModal.show();
    };

    document.getElementById('confirm-signup-btn').addEventListener('click', function () {
        if (!pendingSignupMatchId) return;

        const userId = document.querySelector('meta[name="user-id"]').content;

        fetch(`/match/${pendingSignupMatchId}/update`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ user_id: userId })
        })
            .then(async res => {
                const text = await res.text();
                return JSON.parse(text);
            })
            .then(data => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('signupConfirmModal'));
                if (modal) modal.hide();

                // if (data.status === 'user_added') {
                //     alert("Je bent succesvol aangemeld!");
                // } else if (data.status === 'already_exists') {
                //     alert("Je bent al aangemeld.");
                // } else {
                //     alert("Onverwachte serverrespons.");
                // }

                location.reload();
            })
            .catch(err => {
                console.error("Fout bij aanmelden:", err);
                alert("Er is iets misgegaan.");
            });
    });
});

function confirmUnsubscribe(matchId) {
    pendingUnsubscribeMatchId = matchId;

    const matchModalEl = document.getElementById('matchModal');
    const matchModalInstance = bootstrap.Modal.getInstance(matchModalEl);
    if (matchModalInstance) matchModalInstance.hide();

    const unsubscribeModal = new bootstrap.Modal(document.getElementById('unsubscribeConfirmModal'));
    unsubscribeModal.show();
}