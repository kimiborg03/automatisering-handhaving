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

<div class="modal fade" id="thirdModal" tabindex="-1" aria-labelledby="thirdModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow">
            <div class="modal-header">
                <h5 class="modal-title" id="thirdModalLabel">Bevestig Wissel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Sluiten"></button>
            </div>
            <div class="modal-body" id="third-modal-body">
                Weet je zeker dat je deze wissel wilt uitvoeren?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                <button type="button" class="btn btn-primary" onclick="confirmAction()">Bevestigen</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="unsubscribeConfirmModal" tabindex="-1" aria-labelledby="unsubscribeConfirmModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="unsubscribeConfirmModalLabel">Afmelden Bevestigen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Sluiten"></button>
            </div>
            <div class="modal-body">
                Weet je zeker dat je je wilt afmelden voor deze wedstrijd?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                <button type="button" class="btn btn-danger" id="confirm-unsubscribe-btn">Afmelden</button>
            </div>
        </div>
    </div>
</div>
        <!-- Deadline bevestigingsmodal -->
        <div class="modal fade" id="confirmDeadlineModal" tabindex="-1" aria-labelledby="confirmDeadlineLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmDeadlineLabel">Deadline sluiten</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Sluiten"></button>
                    </div>
                    <div class="modal-body" id="deadline-modal-body">
                        Weet je zeker dat je de deadline nu wilt zetten?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleer</button>
                        <button type="button" class="btn btn-dark" id="confirm-deadline-btn">Bevestig</button>
                    </div>
                </div>
            </div>
        </div>
<div class="modal fade" id="unsubscribeConfirmModal" tabindex="-1" aria-labelledby="unsubscribeConfirmModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="unsubscribeConfirmModalLabel">Afmelden Bevestigen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Sluiten"></button>
            </div>
            <div class="modal-body">
                Weet je zeker dat je je wilt afmelden voor deze wedstrijd?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                <button type="button" class="btn btn-danger" id="confirm-unsubscribe-btn">Afmelden</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="signupConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Aanmelden bevestigen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Sluiten"></button>
            </div>
            <div class="modal-body" id="signup-confirm-body">
                Weet je zeker dat je je wilt aanmelden voor deze wedstrijd?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                <button type="button" class="btn btn-success" id="confirm-signup-btn">Aanmelden</button>
            </div>
        </div>
    </div>
</div>
<div class="text-center mt-4">
    <button id="load-more" class="btn btn-primary">Meer wedstrijden</button>
    <p id="no-more" class="text-muted d-none">Geen wedstrijden meer</p>
</div>
</div>
<!-- Deadline bevestigingsmodal -->
<div class="modal fade" id="confirmDeadlineModal" tabindex="-1" aria-labelledby="confirmDeadlineLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeadlineLabel">Deadline sluiten</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Sluiten"></button>
            </div>
            <div class="modal-body" id="deadline-modal-body">
                Weet je zeker dat je de deadline nu wilt zetten?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleer</button>
                <button type="button" class="btn btn-dark" id="confirm-deadline-btn">Bevestig</button>
            </div>
        </div>
    </div>
</div>
<!-- Deadline verwijderen bevestigingsmodal -->
<div class="modal fade" id="removeDeadlineModal" tabindex="-1" aria-labelledby="removeDeadlineLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="removeDeadlineLabel">Deadline verwijderen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Sluiten"></button>
            </div>
            <div class="modal-body">
                Weet je zeker dat je de deadline wilt verwijderen?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleer</button>
                <button type="button" class="btn btn-dark" id="confirm-remove-deadline-btn">Bevestig</button>
            </div>
        </div>
    </div>
</div>
<!-- Deadline verwijderen bevestigingsmodal -->
<div class="modal fade" id="removeDeadlineModal" tabindex="-1" aria-labelledby="removeDeadlineLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="removeDeadlineLabel">Deadline verwijderen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Sluiten"></button>
            </div>
            <div class="modal-body">
                Weet je zeker dat je de deadline wilt verwijderen?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleer</button>
                <button type="button" class="btn btn-dark" id="confirm-remove-deadline-btn">Bevestig</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="editMatchModal" tabindex="-1" aria-labelledby="editMatchModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="edit-match-form" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editMatchModalLabel">Wedstrijd Bewerken</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit-id" name="id">

                    <div class="mb-3">
                        <label for="edit-name" class="form-label">Naam</label>
                        <input type="text" class="form-control" name="name-match" id="edit-name" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit-location" class="form-label">Locatie</label>
                        <input type="text" class="form-control" name="location" id="edit-location" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit-date" class="form-label">Datum</label>
                        <input type="date" class="form-control" name="date" id="edit-date" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit-checkin" class="form-label">Verzamelen</label>
                        <input type="time" class="form-control" name="check-in-time" id="edit-checkin" required>
                    </div>

                    <div class="mb-3">
                        <label for="edit-kickoff" class="form-label">Aftrap</label>
                        <input type="time" class="form-control" name="kick-off-time" id="edit-kickoff" required>
                    </div>
                    {{-- field for selecting groups --}}
                    <div id="edit-groups-container" class="form-check-group mb-3">
                        @foreach ($groups as $group)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="groups[]" value="{{ $group->id }}"
                                    id="edit-group{{ $group->id }}">
                                <label class="form-check-label d-flex justify-content-between w-100"
                                    for="edit-group{{ $group->id }}">
                                    <span>{{ $group->name }}</span>
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-people-fill me-1"></i>{{ $group->users_count }}
                                    </span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <div class="mb-3">
                        <label for="edit-category" class="form-label">Categorie</label>
                        <select class="form-select" name="category" id="edit-category" required>
                            <option value="AZ-Alkmaar">AZ-Alkmaar</option>
                            <option value="Jong-AZ">Jong-AZ</option>
                            <option value="AZ-Vrouwen">AZ-Vrouwen</option>
                            <option value="Overige">Overige</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="edit-limit" class="form-label">Aantal</label>
                        <input type="number" class="form-control" name="Limit" id="edit-limit">
                    </div>

                    <div class="mb-3">
                        <label for="edit-comment" class="form-label">Opmerking</label>
                        <textarea class="form-control" name="comment" id="edit-comment"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Opslaan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>