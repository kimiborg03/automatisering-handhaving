@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/category.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/category.js') }}"></script>
@endpush

@section('title', 'Wedstrijden - ' . $category)

@section('content')
<div class="container mt-4 bg-light rounded p-4">
    <meta name="category" content="{{ $category }}">
    <h2 class="mb-4 text-center fw-bold">Wedstrijden voor {{ $category }}</h2>

    <div id="matches-container" class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3">
        <!-- matches appear here -->
    </div>

    <div class="text-center mt-4">
        <button id="load-more" class="btn btn-primary">Meer wedstrijden</button>
        <p id="no-more" class="text-muted d-none">Geen wedstrijden meer</p>
    </div>
</div>

@endsection
