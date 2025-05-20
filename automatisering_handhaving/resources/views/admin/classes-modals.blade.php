<!-- Modal for editing a class -->
@foreach ($groups as $group)
    <div class="modal fade" id="editClassModal-{{ $group->id }}" tabindex="-1" aria-labelledby="editClassModalLabel-{{ $group->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editClassModalLabel-{{ $group->id }}">Klas bewerken</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('admin.classes.updateclass', $group->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="class-name-{{ $group->id }}" class="form-label">Naam van de klas</label>
                            <input type="text" class="form-control" id="class-name-{{ $group->id }}" name="name" value="{{ $group->name }}" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Sluiten</button>
                        <button type="submit" class="btn btn-primary">Opslaan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<!-- Modal for adding a new class -->
<div class="modal fade" id="addClassModal" tabindex="-1" aria-labelledby="addClassModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addClassModalLabel">Nieuwe klas toevoegen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.classes.addclass') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="class-name" class="form-label">Naam van de klas</label>
                        <input type="text" class="form-control" id="class-name" name="name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Sluiten</button>
                    <button type="submit" class="btn btn-primary">Toevoegen</button>
                </div>
            </form>
        </div>
    </div>
</div>