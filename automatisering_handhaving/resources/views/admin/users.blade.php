@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/users.css') }}">
@endpush

@section('title', 'Gebruikers beheren')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <a href="{{ url('/admin') }}" class="btn btn-secondary back-button">
        <i class="bi bi-arrow-return-left"></i> Terug naar Admin paneel
    </a>

    <input type="text" id="userSearch" class="form-control" placeholder="Zoek gebruiker...">
</div>
    {{-- users table --}}
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            
            <thead class="table-dark">
                
                <tr>
                    <th>Bewerken</th>
                    <th>Naam</th>
                    <th>Gebruikersnaam</th>
                    <th>Email</th>
                    <th>Klas</th>
                    <th>Rol</th>
                    <th>Toegang</th>
                </tr>
            </thead>
            <tbody id="usersTable">
                @foreach($users as $user)
                    <tr>
                        <td>
                            <!-- Edit button -->
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editUserModal-{{ $user->id }}">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                        </td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @php
                                $group = $groups->firstWhere('id', $user->group_id);
                            @endphp
                            {{ $group ? $group->name : '-' }}
                        </td>
                        <td>{{ $user->role }}</td>
                        <td>{{ $user->access }}</td>
                    </tr>
                    <!-- Edit User Modal -->
                    @include('admin.modals')
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
    // javascript for the search bar
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('userSearch').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll('#usersTable tr');
        rows.forEach(function(row) {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
    });
});
</script>
@endpush
@endsection