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

    // Helper function to format UTC date/time
    function formatUtc(datetime) {
        const date = new Date(datetime);
        return `${String(date.getUTCHours()).padStart(2, '0')}:${String(date.getUTCMinutes()).padStart(2, '0')}`;
    }

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
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title mb-3 fw-bold text-primary">${match.name}</h5>
                            <div class="info-box mb-2">
                                <i class="bi bi-calendar-event me-1 text-primary fs-5 align-middle"></i>
                                <span class="info-label">Datum</span>
                                <div class="info-value">${new Date(match.checkin_time).toLocaleDateString()}</div>
                            </div>
                            <div class="info-box mb-2">
                                <i class="bi bi-geo-alt me-1 text-success fs-5 align-middle"></i>
                                <span class="info-label">Locatie</span>
                                <div class="info-value">${match.location}</div>
                            </div>
                            <div class="info-box mb-2">
                                <i class="bi bi-door-open me-1 text-warning fs-5 align-middle"></i>
                                <span class="info-label">Check-in</span>
                                <div class="info-value">${new Date(match.checkin_time).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</div>
                            </div>
                            <div class="info-box mb-2">
                                <i class="bi bi-play-fill me-1 text-danger fs-5 align-middle"></i>
                                <span class="info-label">Aftrap</span>
                                <div class="info-value">${new Date(match.kickoff_time).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</div>
                            </div>
                            <button class="btn btn-outline-primary btn-sm w-100 mt-auto" data-bs-toggle="modal"
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
    // --- Date Range Filter System ---
    let dateFrom = '';
    let dateTo = '';

    const dateFromInput = document.getElementById('filter-date-from');
    const dateToInput = document.getElementById('filter-date-to');

    // Show inputs if present
    if (dateFromInput) {
        dateFromInput.classList.remove('d-none');
        if (dateFromInput.parentElement) dateFromInput.parentElement.classList.remove('d-none');
        dateFromInput.style.display = '';
        if (dateFromInput.parentElement) dateFromInput.parentElement.style.display = '';
        dateFromInput.addEventListener('change', function () {
            dateFrom = dateFromInput.value;
            performDateRangeFilter(dateFrom, dateTo);
        });
    } else {
        console.warn('Date from input with id "filter-date-from" not found in DOM.');
    }

    if (dateToInput) {
        dateToInput.classList.remove('d-none');
        if (dateToInput.parentElement) dateToInput.parentElement.classList.remove('d-none');
        dateToInput.style.display = '';
        if (dateToInput.parentElement) dateToInput.parentElement.style.display = '';
        dateToInput.addEventListener('change', function () {
            dateTo = dateToInput.value;
            performDateRangeFilter(dateFrom, dateTo);
        });
    } else {
        console.warn('Date to input with id "filter-date-to" not found in DOM.');
    }

    function performDateRangeFilter(fromStr, toStr) {
        if (!fromStr && !toStr) {
            // No filter, reset to normal search or all
            if (searchQuery.trim() !== '') {
                performSearch(searchQuery);
            } else {
                isSearching = false;
                offset = 0;
                container.innerHTML = '';
                noMoreText.classList.add('d-none');
                loadMoreBtn.classList.remove('d-none');
                loadMatches();
            }
            return;
        }
        isSearching = true;
        // Filter matches by date range (YYYY-MM-DD) and sort by date ascending
        const filtered = allMatches
            .filter(match => {
                const matchDate = new Date(match.checkin_time).toISOString().split('T')[0];
                let afterFrom = true, beforeTo = true;
                if (fromStr) afterFrom = matchDate >= fromStr;
                if (toStr) beforeTo = matchDate <= toStr;
                return afterFrom && beforeTo;
            })
            .sort((a, b) => new Date(a.checkin_time) - new Date(b.checkin_time));
        renderMatches(filtered);
        loadMoreBtn.classList.add('d-none');
    }

        // --- Full Search System ---
        let searchQuery = '';
        let isSearching = false;

        const searchInput = document.getElementById('search-matches');
        const searchClearBtn = document.getElementById('clear-search-btn');

        function renderMatches(matches) {
            container.innerHTML = '';
            if (matches.length === 0) {
                noMoreText.classList.remove('d-none');
                loadMoreBtn.classList.add('d-none');
                return;
            }
            matches.forEach(match => {
                const col = document.createElement('div');
                col.className = 'col';
                // Calculate numberOfUsers for each match
                let matchUsers = Array.isArray(match.users) ? match.users : (match.users ? JSON.parse(match.users) : []);
                let numberOfUsers = matchUsers.length;
                col.innerHTML = `
                                    <div class="card h-100 shadow-sm p-2">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title mb-3 fw-bold text-primary">${match.name}</h5>
                            <div class="info-box mb-2">
                                <i class="bi bi-calendar-event me-1 text-primary fs-5 align-middle"></i>
                                <span class="info-label">Datum</span>
                                <div class="info-value">${new Date(match.checkin_time).toLocaleDateString()}</div>
                            </div>
                            <div class="info-box mb-2">
                                <i class="bi bi-geo-alt me-1 text-success fs-5 align-middle"></i>
                                <span class="info-label">Locatie</span>
                                <div class="info-value">${match.location}</div>
                            </div>
                            <div class="info-box mb-2">
                                <i class="bi bi-door-open me-1 text-warning fs-5 align-middle"></i>
                                <span class="info-label">Check-in</span>
                                <div class="info-value">${new Date(match.checkin_time).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</div>
                            </div>
                            <div class="info-box mb-2">
                                <i class="bi bi-play-fill me-1 text-danger fs-5 align-middle"></i>
                                <span class="info-label">Aftrap</span>
                                <div class="info-value">${new Date(match.kickoff_time).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</div>
                            </div>
                            <button class="btn btn-outline-primary btn-sm w-100 mt-auto" data-bs-toggle="modal"
                                data-bs-target="#matchModal" onclick='openMatchModal(${JSON.stringify(match)}, true)'>
                                Meer
                            </button>
                        </div>
                    </div>
                `;
                container.appendChild(col);
            });
            noMoreText.classList.add('d-none');
        }

        function performSearch(query) {
            const q = query.trim().toLowerCase();
            if (q === '') {
            isSearching = false;
            offset = 0;
            container.innerHTML = '';
            noMoreText.classList.add('d-none');
            loadMoreBtn.classList.remove('d-none');
            loadMatches();
            return;
            }
            isSearching = true;
            // Filter and sort by date (earliest first)
            const filtered = allMatches
            .filter(match =>
                match.name.toLowerCase().includes(q) ||
                (match.location && match.location.toLowerCase().includes(q))
            )
            .sort((a, b) => new Date(a.checkin_time) - new Date(b.checkin_time));
            renderMatches(filtered);
            loadMoreBtn.classList.add('d-none');
        }

        // --- Ensure search bar is visible ---
        if (searchInput) {
            searchInput.classList.remove('d-none');
            searchInput.parentElement?.classList.remove('d-none');
        }

        if (searchInput) {
            searchInput.addEventListener('input', function () {
                searchQuery = searchInput.value;
                performSearch(searchQuery);
                if (searchClearBtn) {
                    searchClearBtn.classList.toggle('d-none', searchInput.value.trim() === '');
                }
            });
        }

        if (searchClearBtn) {
            searchClearBtn.addEventListener('click', function () {
                searchInput.value = '';
                searchQuery = '';
                isSearching = false;
                performSearch('');
                searchClearBtn.classList.add('d-none');
            });
        }

        // Patch loadMoreBtn to not load more when searching
        const origLoadMatches = loadMatches;
        loadMatches = function () {
            if (isSearching) return;
            origLoadMatches();
        };



});
// Reusable confirmation modal logic
function showConfirmationModal({
    title = "Bevestigen",
    message = "Weet je het zeker?",
    confirmText = "Bevestigen",
    cancelText = "Annuleren",
    onConfirm,
    confirmBtnClass = "btn-danger",
    cancelBtnClass = "btn-secondary"
}) {
    // Hide any currently open modal before showing the confirmation modal
    const openModalEl = document.querySelector('.modal.show');
    let modalEl = document.getElementById('confirmationModal');
    if (openModalEl && (!modalEl || openModalEl !== modalEl)) {
        const openModalInstance = bootstrap.Modal.getInstance(openModalEl);
        if (openModalInstance) openModalInstance.hide();
    }

    if (!modalEl) {
        modalEl = document.createElement('div');
        modalEl.className = 'modal fade';
        modalEl.id = 'confirmationModal';
        modalEl.tabIndex = -1;
        modalEl.innerHTML = `
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmationModalLabel"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Sluiten"></button>
                    </div>
                    <div class="modal-body" id="confirmationModalBody"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-bs-dismiss="modal" id="confirmationModalCancel"></button>
                        <button type="button" class="btn" id="confirmationModalConfirm"></button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modalEl);
    }
    document.getElementById('confirmationModalLabel').innerText = title;
    document.getElementById('confirmationModalBody').innerText = message;

    const cancelBtn = document.getElementById('confirmationModalCancel');
    const confirmBtn = document.getElementById('confirmationModalConfirm');

    cancelBtn.innerText = cancelText;
    confirmBtn.innerText = confirmText;

    // Set button classes
    cancelBtn.className = `btn ${cancelBtnClass}`;
    confirmBtn.className = `btn ${confirmBtnClass}`;

    // Remove previous event listeners
    const newConfirmBtn = confirmBtn.cloneNode(true);
    confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);

    newConfirmBtn.onclick = function () {
        if (typeof onConfirm === 'function') onConfirm();
        bootstrap.Modal.getInstance(modalEl).hide();
    };

    const modal = new bootstrap.Modal(modalEl);
    modal.show();
}

function openEditMatchModal(match) {
    const modal = new bootstrap.Modal(document.getElementById('editMatchModal'));
    // Hide any currently open modal before showing the edit modal
    const openModalEl = document.querySelector('.modal.show');
    if (openModalEl) {
        const openModalInstance = bootstrap.Modal.getInstance(openModalEl);
        if (openModalInstance) {
            openModalInstance.hide();
        }
    }
    document.getElementById('edit-id').value = match.id;
    document.getElementById('edit-name').value = match.name;
    document.getElementById('edit-location').value = match.location;

    const date = new Date(match.checkin_time);
    document.getElementById('edit-date').value = date.toISOString().split('T')[0];

    const form = document.getElementById('edit-match-form');
    form.action = `/admin/match/${match.id}/update`;

    // Intercept form submit for confirmation modal
    const saveBtn = document.querySelector('#editMatchModal .modal-footer button[type="submit"], #edit-match-form button[type="submit"]');
    if (saveBtn && !saveBtn._confirmationAttached) {
        saveBtn._confirmationAttached = true;
        saveBtn.addEventListener('click', function (e) {
            e.preventDefault();

            // Before submitting, update the hidden groups input with checked values
            const groupsContainer = document.getElementById('edit-groups-container');
            if (groupsContainer) {
                const hiddenGroupsInput = document.getElementById('edit-groups-hidden');
                if (hiddenGroupsInput) {
                    hiddenGroupsInput.value = Array.from(groupsContainer.querySelectorAll('.edit-group-checkbox:checked')).map(cb => cb.value);
                }
            }

            // No need to manually hide the previous modal here; handled in showConfirmationModal
            const openModalEl = document.querySelector('.modal.show');
            if (openModalEl) {
                const openModalInstance = bootstrap.Modal.getInstance(openModalEl);
                if (openModalInstance) {
                    openModalInstance.hide();
                }
            }

            showConfirmationModal({
                title: "Wedstrijd opslaan",
                message: "Weet je zeker dat je de wijzigingen wilt opslaan?",
                confirmText: "Opslaan",
                cancelText: "Annuleren",
                confirmBtnClass: "btn-primary",
                onConfirm: function () {
                    form.submit();
                }
            });
        });
    }
    document.getElementById('edit-checkin').value = date.toTimeString().slice(0, 5);

    const kickoff = new Date(match.kickoff_time);
    document.getElementById('edit-kickoff').value = kickoff.toTimeString().slice(0, 5);

    document.getElementById('edit-category').value = match.category || 'Overige';
    document.getElementById('edit-limit').value = match.limit || '';
    document.getElementById('edit-comment').value = match.comment || '';

    // Add delete button if not already present
    let deleteBtn = document.getElementById('delete-match-btn');
    if (!deleteBtn) {
        deleteBtn = document.createElement('button');
        deleteBtn.id = 'delete-match-btn';
        deleteBtn.type = 'button';
        deleteBtn.className = 'btn btn-danger mt-3';
        deleteBtn.innerText = 'Wedstrijd verwijderen';
        deleteBtn.onclick = function () {
            showConfirmationModal({
                title: "Wedstrijd verwijderen",
                message: "Weet je zeker dat je deze wedstrijd wilt verwijderen?",
                confirmText: "Verwijderen",
                cancelText: "Annuleren",
                onConfirm: function () {
                    fetch(`/admin/match/${match.id}/delete`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                        .then(res => res.json())
                        .then(() => {
                            modal.hide();
                            location.reload();
                        })
                        .catch(err => {
                            alert('Fout bij verwijderen van wedstrijd.');
                            console.error(err);
                        });
                }
            });
        };
        // Add the button to the modal footer or form
        const modalFooter = document.querySelector('#editMatchModal .modal-footer');
        // if (modalFooter) {
        //     modalFooter.appendChild(deleteBtn);
        // } else {
        //     form.appendChild(deleteBtn);
        // }
    } else {
        // Update the onclick handler in case match.id changed
        deleteBtn.onclick = function () {
            showConfirmationModal({
                title: "Wedstrijd verwijderen",
                message: "Weet je zeker dat je deze wedstrijd wilt verwijderen?",
                confirmText: "Verwijderen",
                cancelText: "Annuleren",
                onConfirm: function () {
                    fetch(`/admin/match/${match.id}/delete`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })
                        .then(res => res.json())
                        .then(() => {
                            modal.hide();
                            location.reload();
                        })
                        .catch(err => {
                            alert('Fout bij verwijderen van wedstrijd.');
                            console.error(err);
                        });
                }
            });
        };
    }

    modal.show();
}
const allMatches = JSON.parse(document.getElementById('all-matches').textContent);
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

let selectedSwap = {
    fromMatch: null,
    toMatch: null
};
function openMatchModal(match, showRuilButton) {
    document.querySelectorAll('#edit-groups-container input[type="checkbox"]').forEach(cb => {
        cb.checked = false;
    });
    const playedMatches = parseInt(document.querySelector('meta[name="played-matches"]')?.content || '0');
    const upcomingMatches = parseInt(document.querySelector('meta[name="upcoming-matches"]')?.content || '0');
    const totalMatches = playedMatches + upcomingMatches;
    console.log("Total matches:", totalMatches); // Add this to debug
    console.log(upcomingMatches, playedMatches);
    // Zet de juiste vinkjes aan
    if (Array.isArray(match.groups)) {
        match.groups.forEach(groupId => {
            const checkbox = document.querySelector(`#edit-group${groupId}`);
            if (checkbox) checkbox.checked = true;
        });
    }
    console.log("Show ruil button?", showRuilButton); // Add this to debug

    let matchUsers = Array.isArray(match.users) ? match.users : JSON.parse(match.users);
    let numberOfUsers = matchUsers.length;

    function formatUtc(datetime) {
        const date = new Date(datetime);
        return `${String(date.getUTCHours()).padStart(2, '0')}:${String(date.getUTCMinutes()).padStart(2, '0')}`;
    }

    const body = document.getElementById('modal-content-body');
    function formatDateOnly(datetime) {
        const date = new Date(datetime);
        return date.toLocaleDateString();
    }
        const deadlineIsNull = match.deadline === null || match.deadline === "null";

let stringThing = `
    <div class="info-box mb-2">
        <i class="bi bi-person-badge me-1 text-primary fs-5 align-middle"></i>
        <span class="info-label">Naam</span>
        <div class="info-value">${match.name}</div>
    </div>
    <div class="info-box mb-2">
        <i class="bi bi-calendar-event me-1 text-primary fs-5 align-middle"></i>
        <span class="info-label">Datum</span>
        <div class="info-value">${formatDateOnly(match.checkin_time)}</div>
    </div>
    <div class="info-box mb-2">
        <i class="bi bi-geo-alt me-1 text-success fs-5 align-middle"></i>
        <span class="info-label">Locatie</span>
        <div class="info-value">${match.location}</div>
    </div>
    <div class="info-box mb-2">
        <i class="bi bi-door-open me-1 text-warning fs-5 align-middle"></i>
        <span class="info-label">Check-in</span>
        <div class="info-value">${formatUtc(match.checkin_time)}</div>
    </div>
    <div class="info-box mb-2">
        <i class="bi bi-play-fill me-1 text-danger fs-5 align-middle"></i>
        <span class="info-label">Aftrap</span>
        <div class="info-value">${formatUtc(match.kickoff_time)}</div>
    </div>
    <div class="info-box mb-2">
        <i class="bi bi-tags me-1 text-info fs-5 align-middle"></i>
        <span class="info-label">Categorie</span>
        <div class="info-value">${match.category}</div>
    </div>
    <div class="info-box mb-2">
        <i class="bi bi-people-fill me-1 text-secondary fs-5 align-middle"></i>
        <span class="info-label">Ingeschreven</span>
        <div class="info-value">${numberOfUsers} / ${match.limit}</div>
    </div>
`;
  const isAdmin = document.querySelector('meta[name="is-admin"]')?.content === 'true';

        if (isAdmin) {
            stringThing += ` <a href="/matches/${match.id}/registrations" class="btn btn-primary mt-3" target="_blank">
                Bekijk deelnemers
            </a>`;
            stringThing += `
<button class="btn btn-outline-primary btn-sm w-100 mt-2"
    onclick='openEditMatchModal(${JSON.stringify(match)})'>
    Bewerken
</button>
`;
            if (deadlineIsNull) {
                stringThing += `
    <button class="btn btn-outline-dark btn-sm w-100 mt-2 set-deadline-btn"
        data-match-id="${match.id}">
        Aanmelding sluiten
    </button>
        `;
            } else {
                stringThing += `
<button class="btn btn-outline-dark btn-sm w-100 mt-2 remove-deadline-btn"
    data-match-id="${match.id}">
    Aanmelding openen
</button>
        `;
            }

        }
    if (showRuilButton == true) {

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
        <button
            class="btn btn-outline-warning btn-sm w-100 mt-2"
            data-bs-toggle="modal"
            data-bs-target="#swapModal"
            onclick='openSwapModal(JSON.parse(decodeURIComponent("${matchData}")))'>
            Wedstrijd Ruilen
        </button>
    `
                if (totalMatches >= 11) {
                    stringThing += `
        <button
            class="btn btn-outline-danger btn-sm w-100 mt-2"
            onclick="confirmUnsubscribe(${match.id})">
            Afmelden
        </button>
    `;
                }

                ;
            }
        } else {
            stringThing += `
        <p class="text-danger fw-bold fs-5 w-100 mt-2">
            Aanmelding is GESLOTEN!
        </p>        `
        }
        
    }
    body.innerHTML = stringThing;

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
                    <div class="card-body p-2 d-flex align-items-center justify-content-between">
                        <div>
                            <strong>${match.name}</strong><br>
                            Locatie: ${match.location}<br>
                            Tijd: ${new Date(match.kickoff_time).toLocaleTimeString()}
                        </div>
                        <button class="btn btn-outline-success btn-lg ms-3 px-4 py-2"
                            style="white-space:nowrap;"
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

    // Eerst toevoegen aan nieuwe match, dan pas verwijderen uit oude
    fetch(`/match/${toMatchId}/update`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ user_id: userId })
    })
        .then(async res => {
            const text = await res.text();
            console.log("Add response:", text);
            // Controleer of toevoegen gelukt is
            let data;
            try {
                data = JSON.parse(text);
            } catch (e) {
                throw new Error("Kon response niet parsen: " + text);
            }
            if (data.status && data.status === 'full') {
                alert("De gekozen wedstrijd zit vol. Wissel niet mogelijk.");
                throw new Error("Wedstrijd vol");
            }
            return data;
        })
        .then(() => {
            // Nu pas verwijderen uit oude match
            return fetch(`/match/${fromMatchId}/user/remove`, {
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
            console.log("Remove response:", text);
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

    // Toggle grid view functionality for mobile
    document.addEventListener("DOMContentLoaded", function () {
        const toggleButton = document.getElementById('toggleGridView');
        const matchesContainer = document.getElementById('matches-container');

        if (toggleButton && matchesContainer) {
            toggleButton.addEventListener('click', function () {
                matchesContainer.classList.toggle('row-cols-1');
                matchesContainer.classList.toggle('row-cols-2');
            });
        }
    });
