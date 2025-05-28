const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const userId = document.querySelector('meta[name="user-id"]').content;
document.addEventListener('DOMContentLoaded', function () {
    const allMatches = JSON.parse(document.getElementById('all-matches').textContent);

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
});
let selectedSwap = {
    fromMatch: null,
    toMatch: null
};

let pendingUnsubscribeMatchId = null;
let availableMatches = window.availableMatches || []; // Make sure you define this in your Blade view

function openMatchModalFromButton(button) {
    const match = JSON.parse(button.getAttribute('data-match'));
    const showRuil = button.getAttribute('data-showruil') === 'true';
    openMatchModal(match, showRuil);
}

function openMatchModal(match, showRuilButton) {
    let matchUsers = Array.isArray(match.users) ? match.users : JSON.parse(match.users || '[]');
    let numberOfUsers = matchUsers.length;

    function formatUtc(datetime) {
        const date = new Date(datetime);
        return `${date.getUTCFullYear()}-${String(date.getUTCMonth() + 1).padStart(2, '0')}-${String(date.getUTCDate()).padStart(2, '0')} ` +
            `${String(date.getUTCHours()).padStart(2, '0')}:${String(date.getUTCMinutes()).padStart(2, '0')}`;
    }

    const body = document.getElementById('modal-content-body');
    let content = `
        <p><strong>Naam:</strong> ${match.name}</p>
        <p><strong>Datum:</strong> ${formatUtc(match.checkin_time)}</p>
        <p><strong>Locatie:</strong> ${match.location}</p>
        <p><strong>Check-in:</strong> ${formatUtc(match.checkin_time)}</p>
        <p><strong>Aftrap:</strong> ${formatUtc(match.kickoff_time)}</p>
        <p><strong>Categorie:</strong> ${match.category}</p>
        <p><strong>Ingeschreven:</strong> ${numberOfUsers} / ${match.limit}</p>
        <a href="/matches/${match.id}/registrations" class="btn btn-primary mt-3" target="_blank">
            Bekijk deelnemers
        </a>
    `;

    // if (showRuilButton) {
    //     const matchData = encodeURIComponent(JSON.stringify(match));
    //     content += `
    //         <button class="btn btn-outline-warning btn-sm w-100 mt-2"
    //             data-bs-toggle="modal"
    //             data-bs-target="#swapModal"
    //             onclick='openSwapModal(JSON.parse(decodeURIComponent("${matchData}")))' >
    //             Wedstrijd Ruilen
    //         </button>
    //         <button class="btn btn-outline-danger btn-sm w-100 mt-2"
    //             onclick="confirmUnsubscribe(${match.id})">
    //             Afmelden
    //         </button>
    //     `;
    // }

    // body.innerHTML = content;
    const deadlineIsNull = match.deadline === null || match.deadline === "null";

    if (deadlineIsNull) {

        if (!matchUsers.some(u => u.user_id == userId)) {
            //         console.log("User is not in match, show signup button");
            //         content += `
            //     <button class="btn btn-outline-success btn-sm w-100 mt-2"
            //         onclick="confirmSignup(${match.id})">
            //         Aanmelden
            //     </button>
            // `

        } else {
            const matchData = encodeURIComponent(JSON.stringify(match));
            content += `
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
            content += `
    <button class="btn btn-outline-dark btn-sm w-100 mt-2 set-deadline-btn"
        data-match-id="${match.id}">
        Sluit deadline
    </button>
        `;
        } else {
            content += `
<button class="btn btn-outline-dark btn-sm w-100 mt-2 remove-deadline-btn"
    data-match-id="${match.id}">
    Verwijder deadline
</button>
        `;
        }

    }
    body.innerHTML = content;

}
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
    const fromMatchId = selectedSwap.fromMatch.id;
    const toMatchId = selectedSwap.toMatch.id;

    console.log("confirmAction gestart!");

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

function confirmUnsubscribe(matchId) {
    pendingUnsubscribeMatchId = matchId;

    const matchModalEl = document.getElementById('matchModal');
    const matchModalInstance = bootstrap.Modal.getInstance(matchModalEl);
    if (matchModalInstance) matchModalInstance.hide();

    const unsubscribeModal = new bootstrap.Modal(document.getElementById('unsubscribeConfirmModal'));
    unsubscribeModal.show();
}

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('confirm-unsubscribe-btn').addEventListener('click', function () {
        if (!pendingUnsubscribeMatchId) return;

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
});
