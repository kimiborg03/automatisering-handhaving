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
            <h2 class="mb-4 text-center fw-bold">Wedstrijden voor {{ $category }}</h2>

            <div id="matches-container" class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3">
                <!-- matches appear here -->
            </div>
            @include('partials.match-modals', ['userId' => auth()->user()->id, 'allMatches' => $allMatches])
    
@endsection
