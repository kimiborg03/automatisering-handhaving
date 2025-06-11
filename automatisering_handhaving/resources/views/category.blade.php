@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/category.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/category.js') }}"></script>
@endpush
<meta name="category" content="{{ $category }}">
<meta name="is-admin" content="{{ auth()->user()->role == 'admin' ? 'true' : 'false' }}">

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
        @if ($category === 'all')
            <!-- Date Range Filter -->
            <div class="row mb-3">
                <div class="col-auto">
                    <label for="filter-date-from" class="form-label mb-0">Van:</label>
                    <input type="date" id="filter-date-from" class="form-control d-none" />
                </div>
                <div class="col-auto">
                    <label for="filter-date-to" class="form-label mb-0">Tot:</label>
                    <input type="date" id="filter-date-to" class="form-control d-none" />
                </div>
            </div>
            <div class="container mt-4 bg-light rounded p-4">
                {{--
                <meta name="category" content="{{ $category }}"> --}}
                <div class="input-group mb-3">
                    <input type="text" id="search-matches" class="form-control d-none" placeholder="Zoek wedstrijden...">
                    <button id="clear-search-btn" class="btn btn-outline-secondary d-none" type="button">X</button>
                </div>
            </div>
        @endif
        <h2 class="mb-4 text-center fw-bold">Wedstrijden voor {{ $category }}</h2>
            <!-- Mobile grid toggle button -->
            <div class="d-sm-none mb-3 text-end">
                <button class="btn btn-outline-secondary" id="toggleGridView" title="Toon als grid">
                    <i class="bi bi-grid"></i>
                </button>
            </div>
        <div id="matches-container" class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3">
            <!-- matches appear here -->
        </div>
        @include('partials.match-modals', ['userId' => auth()->user()->id, 'allMatches' => $allMatches])

@endsection
