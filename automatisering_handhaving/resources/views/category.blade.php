@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/category.css') }}">
@endpush

@section('title', 'Wedstrijden - ' . $category)

@section('content')
<div class="container mt-4 bg-light rounded p-4">
    <h2 class="mb-4 text-center fw-bold">Wedstrijden voor {{ $category }}</h2>

        @if($matches->isEmpty())
            <p>Er zijn geen wedstrijden in deze categorie.</p>
        @else
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3">
                @foreach ($matches as $match)
                    <div class="col">
                        <div class="card h-100 shadow-sm p-2">
                            <div class="card-body">
                                <h5 class="card-title">{{ $match->name }}</h5>
                                <p class="card-text mb-1"><strong>Datum:</strong> {{ \Carbon\Carbon::parse($match->checkin_time)->format('Y-m-d') }}</p>
                                <p class="card-text mb-1"><strong>Locatie:</strong> {{ $match->location }}</p>
                                <p class="card-text mb-1"><strong>Check-in:</strong> {{ \Carbon\Carbon::parse($match->checkin_time)->format('H:i') }}</p>
                                <p class="card-text mb-2"><strong>Aftrap:</strong> {{ \Carbon\Carbon::parse($match->kickoff_time)->format('H:i') }}</p>
                                <button class="btn btn-outline-primary btn-sm w-100" data-bs-toggle="modal"
                                    data-bs-target="#matchModal" onclick='openMatchModal(@json($match))'>
                                    Meer
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection