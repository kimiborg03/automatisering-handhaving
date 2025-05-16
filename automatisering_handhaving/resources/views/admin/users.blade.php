@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/users.css') }}">
@endpush

@section('title', 'Gebruikers beheren')

@section('content')
                    <a href="{{ url('/admin') }}" class="btn btn-secondary back-button">
                        <i class="bi bi-arrow-return-left"></i> Terug naar Admin paneel
                    </a>
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
        <tbody>
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
@endsection