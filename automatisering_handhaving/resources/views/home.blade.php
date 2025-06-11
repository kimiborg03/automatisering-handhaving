@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endpush

@push('scripts')
    <script src="{{ asset('js/category.js') }}"></script>
@endpush

<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="category" content="null">
<meta name="is-admin" content="{{ auth()->user()->role == 'admin' ? 'true' : 'false' }}">
<meta name="user-id" content="{{ auth()->user()->id }}">

<script id="availableMatches" type="application/json">
    {!! json_encode($availableMatches) !!}
</script>
<script id="all-matches" type="application/json">
    {!! json_encode($allMatches) !!}
</script>

@section('title', 'home')

@section('content')
    <div class="container mt-4">
        <div class="scoreboard">
            <div class="scoreboard-numbers">
                <div class="scoreboard-section" style="color: red">
                    @php
                        $remainingMatches = 10 - count($playedMatches);
                        $remainingMatches = $remainingMatches > 0 ? $remainingMatches : 0;
                    @endphp

                    {{ $remainingMatches }}
                    <div class="scoreboard-label" style="color: red">Nog te spelen</div>
                </div>
                <div class="scoreboard-section">
                    {{ count($playedMatches) }}
                    <div class="scoreboard-label">Gespeeld</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Aangemelde Wedstrijden -->
    <div class="container mt-4 bg-light rounded p-4">
        <h2 class="mb-4 text-center fw-bold">Aangemelde Wedstrijden</h2>

        <!-- Mobile grid toggle button -->
        <div class="d-sm-none mb-3 text-end">
            <button class="btn btn-outline-secondary" id="toggleGridView" title="Toon als grid">
                <i class="bi bi-grid"></i>
            </button>
        </div>

        <div id="matches-container" class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3">
            <!-- matches appear here -->
        </div>
    </div>

    <!-- Gespeelde Wedstrijden -->
    <div class="container mt-4 bg-light rounded p-4">
        <h2 class="mb-4 text-center fw-bold">Gespeelde Wedstrijden</h2>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3">
            @forelse ($playedMatches as $match)
                <div class="col">
                    <div class="card h-100 shadow-sm p-2">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title mb-3 fw-bold text-primary">{{ $match->name }}</h5>
                            <div class="info-box mb-2">
                                <i class="bi bi-calendar-event me-1 text-primary fs-5 align-middle"></i>
                                <span class="info-label">Datum</span>
                                <div class="info-value">{{ $match->checkin_time->format('Y-m-d') }}</div>
                            </div>
                            <div class="info-box mb-2">
                                <i class="bi bi-geo-alt me-1 text-success fs-5 align-middle"></i>
                                <span class="info-label">Locatie</span>
                                <div class="info-value">{{ $match->location }}</div>
                            </div>
                            <div class="info-box mb-2">
                                <i class="bi bi-door-open me-1 text-warning fs-5 align-middle"></i>
                                <span class="info-label">Check-in</span>
                                <div class="info-value">{{ $match->checkin_time->format('H:i') }}</div>
                            </div>
                            <div class="info-box mb-2">
                                <i class="bi bi-play-fill me-1 text-danger fs-5 align-middle"></i>
                                <span class="info-label">Aftrap</span>
                                <div class="info-value">{{ $match->kickoff_time->format('H:i') }}</div>
                            </div>
                            <button class="btn btn-outline-primary btn-sm w-100 mt-auto" data-bs-toggle="modal"
                                data-bs-target="#matchModal" onclick='openMatchModal(@json($match))'>
                                Meer
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                {{-- <div class="col-12">
                    <div class="alert alert-info text-center">
                        Er zijn nog geen gespeelde wedstrijden.
                    </div>
                </div> --}}
            @endforelse
        </div>
    </div>

    {{-- Modals --}}
    @include('partials.match-modals', ['userId' => auth()->user()->id, 'allMatches' => $allMatches])
@endsection
