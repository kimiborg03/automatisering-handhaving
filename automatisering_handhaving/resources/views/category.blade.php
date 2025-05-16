@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/category.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/category.js') }}"></script>
@endpush
<meta name="category" content="{{ $category }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="user-id" content="{{ auth()->user()->id }}">
<script id="all-matches" type="application/json">
    {!! json_encode($allMatches) !!}
</script>
<script id="availableMatches" type="application/json">
    {!! json_encode($availableMatches) !!}
</script>

@section('title', 'Wedstrijden - ' . $category)

@section('content')
    <div class="container mt-4 bg-light rounded p-4">
        <meta name="category" content="{{ $category }}">
        <h2 class="mb-4 text-center fw-bold">Wedstrijden voor {{ $category }}</h2>

        <div id="matches-container" class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3">
            <!-- matches appear here -->
        </div>
        @include('partials.match-modals', ['userId' => auth()->user()->id, 'allMatches' => $allMatches])
        <div class="modal fade" id="signupConfirmModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Aanmelden bevestigen</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Sluiten"></button>
                    </div>
                    <div class="modal-body" id="signup-confirm-body">
                        Weet je zeker dat je je wilt aanmelden voor deze wedstrijd?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuleren</button>
                        <button type="button" class="btn btn-success" id="confirm-signup-btn">Aanmelden</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center mt-4">
            <button id="load-more" class="btn btn-primary">Meer wedstrijden</button>
            <p id="no-more" class="text-muted d-none">Geen wedstrijden meer</p>
        </div>
    </div>

@endsection
