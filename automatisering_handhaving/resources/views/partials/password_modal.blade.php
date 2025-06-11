{{-- resources/views/partials/password_modal.blade.php --}}
<div id="passwordModal" class="modal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4);">
    <div class="modal-content" style="background:#fff; margin:10% auto; padding:30px; border-radius:8px; max-width:400px; position:relative;">
        <span onclick="document.getElementById('passwordModal').style.display='none'" style="position:absolute; top:10px; right:18px; font-size:24px; cursor:pointer;">&times;</span>
        <h4>Wachtwoord veranderen</h4>
        @if($errors->any())
            <div class="alert alert-danger" style="margin-bottom:15px;">
                <ul style="margin-bottom:0;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(session('success'))
            <div class="alert alert-success" style="margin-bottom:15px;">
                {{ session('success') }}
            </div>
        @endif
        {{-- Form to change password --}}
        <form method="POST" action="{{ route('password.change') }}">
            @csrf
            {{-- field for old password --}}
            <div class="mb-3">
                <label for="current_password" class="form-label">Huidig wachtwoord</label>
                <input type="password" class="form-control" name="current_password" required>
            </div>

            {{-- fields for new password --}}
            <div class="mb-3">
                <label for="new_password" class="form-label">Nieuw wachtwoord</label>
                <input type="password" class="form-control" name="new_password" required>
            </div>
            <div class="mb-3">
                <label for="new_password_confirmation" class="form-label">Herhaal nieuw wachtwoord</label>
                <input type="password" class="form-control" name="new_password_confirmation" required>
            </div>

            <button type="submit" class="btn btn-success w-100">Opslaan</button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check of er een error of succesbericht is
    var modal = document.getElementById('passwordModal');
    var hasError = {{ $errors->any() ? 'true' : 'false' }};
    var hasSuccess = {{ session('success') ? 'true' : 'false' }};
    if (hasError || hasSuccess) {
        modal.style.display = 'block';
    }
});
</script>