@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="is-admin" content="{{ auth()->user()->role == 'admin' ? 'true' : 'false' }}">
    <script id="all-matches" type="application/json">
            {!! json_encode($allMatches) !!}
        </script>
    @push('scripts')
        <script src="{{ asset('js/category.js') }}"></script>
    @endpush
    @push('styles')
        <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    @endpush
    <!-- Voeg dit toe in je Blade-bestand -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700&display=swap" rel="stylesheet">
    <meta name="user-id" content="{{ auth()->user()->id }}">


@endpush

@section('title', 'home')

@section('content')

                <div class="container mt-4">
                    <div class="scoreboard">
                        {{-- <div class="scoreboard-title">Mijn Scorebord</div> --}}
                        <div class="scoreboard-numbers">
                            <div class="scoreboard-section" style="color: red">
                                {{ count($allMatches) }}
                                <div class="scoreboard-label" style="color: red">Nog te spelen</div>
                            </div>
                            <div class="scoreboard-section">
                                {{ count($playedMatches) }}
                                <div class="scoreboard-label">Gespeeld</div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="container mt-4 bg-light rounded p-4">
                    <h2 class="mb-4 text-center fw-bold">Aangemelde Wedstrijden</h2>

                    @php
$matches = \App\Models\Matches::whereJsonContains('users', ['id' => $userId])->get();
                    @endphp

                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3">
                        @foreach ($allMatches as $match)
                            @include('partials.match-card', [
        'match' => $match,
        'showRuil' => $match->deadline == null ? true : false
    ])  

                        @endforeach</div></div>
                    <div class="container mt-4 bg-light rounded p-4">
                        <h2 class="mb-4 text-center fw-bold">Gespeelde Wedstrijden</h2>

                            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3">
                                @foreach ($playedMatches as $match)
                                                        <div class="col">
                                                        <div class="card h-100 shadow-sm p-2">
                                                        <div class="card-body">
                                                        <h5 class="card-title">{{ $match->name }}</h5>
                                                        <p class="card-text mb-1"><strong>Datum:</strong> {{ $match->checkin_time->format('Y-m-d') }}</p>
                                                        <p class="card-text mb-1"><strong>Locatie:</strong> {{ $match->location }}</p>
                                                        <p class="card-text mb-1"><strong>Check-in:</strong> {{ $match->checkin_time->format('H:i') }}</p>
                                                            <p class="card-text mb-2"><strong>Aftrap:</strong> {{ $match->kickoff_time->format('H:i') }}</p>
                                    {{-- {{}} --}}
                                                            <button class="btn btn-outline-primary btn-sm w-100" data-bs-toggle="modal"
                                                        data-bs-target="#matchModal"
                                                    onclick='openMatchModal(@json($match) )'>
                                                Meer
                                            </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
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

                {{-- Modals (Match Modal, Swap Modal, Confirm Modal) --}}
                @include('partials.match-modals', ['userId' => $userId, 'allMatches' => $allMatches])

                {{-- Scripts --}}
                @push('scripts')

                @endpush

@endsection