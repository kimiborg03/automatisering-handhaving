document.addEventListener('DOMContentLoaded', function () {
    let offset = 0; // amount of matches loaded
    const category = document.querySelector('meta[name="category"]').content; // category of matches
    const container = document.getElementById('matches-container');
    const loadMoreBtn = document.getElementById('load-more');
    const noMoreText = document.getElementById('no-more');

    // Function to load matches
    function loadMatches() {
        loadMoreBtn.disabled = true;
        loadMoreBtn.innerText = 'Laden...';

        // Fetch matches based on category and offset
        fetch(`/load-matches?category=${category}&offset=${offset}`)
            .then(response => response.json())
            .then(matches => {
                // If no more matches, hide button and show "no more" message
                if (matches.length === 0) {
                    loadMoreBtn.classList.add('d-none');
                    noMoreText.classList.remove('d-none');
                    return;
                }

                // Create bootstrap card for each match
                matches.forEach(match => {
                    const col = document.createElement('div');
                    col.className = 'col';
                    col.innerHTML = `
                        <div class="card h-100 shadow-sm p-2">
                            <div class="card-body">
                                <h5 class="card-title">${match.name}</h5>
                                <p class="card-text mb-1"><strong>Datum:</strong> ${new Date(match.checkin_time).toLocaleDateString()}</p>
                                <p class="card-text mb-1"><strong>Locatie:</strong> ${match.location}</p>
                                <p class="card-text mb-1"><strong>Check-in:</strong> ${new Date(match.checkin_time).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</p>
                                <p class="card-text mb-2"><strong>Aftrap:</strong> ${new Date(match.kickoff_time).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</p>
                                <button class="btn btn-outline-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#matchModal" onclick='openMatchModal(${JSON.stringify(match)})'>
                                    Meer
                                </button>
                            </div>
                        </div>
                    `;
                    container.appendChild(col);
                });

                // Increase offset
                offset += matches.length;

                // Enable "meer wedstrijden" button
                loadMoreBtn.disabled = false;
                loadMoreBtn.innerText = 'Meer wedstrijden';
            });
    }

    // Load first 12 matches
    loadMatches();

    // Load more matches on button click
    loadMoreBtn.addEventListener('click', loadMatches);
});