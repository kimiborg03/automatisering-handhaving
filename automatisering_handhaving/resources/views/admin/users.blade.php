@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/users.css') }}">
@endpush

@section('title', 'Gebruikers beheren')

@section('content')
    {{-- button to go back to admin panel --}}
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <a href="{{ url('/admin') }}" class="btn btn-secondary back-button">
            <i class="bi bi-arrow-return-left"></i> Terug naar Admin paneel
        </a>

        {{-- search bar for users --}}
        <input type="text" id="userSearch" class="form-control" placeholder="Zoek gebruiker...">
    </div>
        {{-- error messages --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
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
                    <th>Gespeelde wedstrijden</th>
                </tr>
            </thead>
            <tbody id="usersTable">
                {{-- load users here --}}
                @include('partials.user-rows', ['users' => $users, 'groups' => $groups])
            </tbody>
        </table>
    </div>

    <div>
        @if ($users->hasMorePages())
            <button id="loadMore" class="btn btn-primary" data-next-page="{{ $users->currentPage() + 1 }}">
                Meer laden
            </button>
        @endif
    </div>
    @push('scripts')
    <script src="{{ asset('js/users.js') }}"></script>
    @endpush
@endsection
