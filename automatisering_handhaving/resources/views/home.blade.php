@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('title', 'home')

@section('content')

<div class="container mt-4 bg-light rounded p-4">
    <h2 class="mb-4 text-center fw-bold">Aangemelde Wedstrijden</h2>

    <?php
        $matches = \App\Models\Matches::whereJsonContains('users', ['id' => $userId])->get();
    ?>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3">
        @foreach ($allMatches as $match)
            <div class="col">
                <div class="card h-100 shadow-sm p-2">
                    <div class="card-body">
                        <h5 class="card-title">{{ $match->name }}</h5>
                        <p class="card-text mb-1"><strong>Datum:</strong> {{ $match->checkin_time->format('Y-m-d') }}</p>
                        <p class="card-text mb-1"><strong>Locatie:</strong> {{ $match->location }}</p>
                        <p class="card-text mb-1"><strong>Check-in:</strong> {{ $match->checkin_time->format('H:i') }}</p>
                        <p class="card-text mb-2"><strong>Aftrap:</strong> {{ $match->kickoff_time->format('H:i') }}</p>
                        <button class="btn btn-outline-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#matchModal"
                            data-match='@json($match)' data-showruil="true" onclick="openMatchModalFromButton(this)">
                            Meer
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="container mt-4">
    <h3 class="mb-4">Gespeelde Wedstrijden</h3>

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
                        <button class="btn btn-outline-primary btn-sm w-100" data-bs-toggle="modal"
                            data-bs-target="#matchModal" onclick='openMatchModal(@json($match))'>
                            Meer
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

{{-- Modals (Match Modal, Swap Modal, Confirm Modal) --}}
@include('partials.match-modals', ['userId' => $userId, 'allMatches' => $allMatches])

{{-- Scripts --}}
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script>
        // Je bestaande JavaScript komt hierheen zoals je al had (openMatchModal, confirmAction, etc.)
    </script>
@endpush

@endsection
