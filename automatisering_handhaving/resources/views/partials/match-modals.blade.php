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