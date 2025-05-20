<!-- Edit user Modal -->
<div class="modal fade" id="editUserModal-{{ $user->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel-{{ $user->id }}">Gebruiker bewerken</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Sluiten"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label class="form-label">Naam</label>
                        <input type="text" class="form-control" name="name" value="{{ $user->name }}" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Gebruikersnaam</label>
                        <input type="text" class="form-control" name="username" value="{{ $user->username }}" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="{{ $user->email }}" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Klas</label>
                        <select class="form-select" name="group_id">
                            <option value="">-- Selecteer klas --</option>
                            @foreach($groups as $group)
                                <option value="{{ $group->id }}" @if($user->group_id == $group->id) selected @endif>
                                    {{ $group->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Rol</label>
                        <select class="form-select" name="role" required>
                            <option value="admin" @if($user->role == 'admin') selected @endif>admin</option>
                            <option value="gebruiker" @if($user->role == 'gebruiker') selected @endif>gebruiker</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label d-block" for="accessSwitch-{{ $user->id }}">Toegang</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="accessSwitch-{{ $user->id }}" name="access" value="1" {{ $user->access ? 'checked' : '' }}>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                    <button type="submit" class="btn btn-primary">Opslaan</button>
                </div>
            </form>
        </div>
    </div>
</div>